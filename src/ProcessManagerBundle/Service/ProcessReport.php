<?php
/**
 * @date    17/11/2017 11:53
 * @author  Wojciech Peisert <wpeisert@divante.co>
 */

namespace ProcessManagerBundle\Service;

use ProcessManagerBundle\Exception\NonExistentReportFileException;

/**
 * Class ProcessReport
 *
 * Prepares import process report from log file
 *
 * @package ProcessManagerBundle\Service
 */
class ProcessReport
{
    const SKIP_LINES_SHORTER_THAN = 50;

    const EVENT_TOTAL = 'import_definition.total:';
    const EVENT_PROGRESS = 'import_definition.progress:';
    const EVENT_STATUS_ERROR = 'import_definition.status: Error: ';
    const EVENT_STATUS_IMPORT_NEW = 'import_definition.status: Import Object new';
    const EVENT_STATUS_IMPORT_EXISTING = 'import_definition.status: Import Object';
    const EVENT_STATUS_IGNORE_NEW = 'import_definition.status: Ignoring new Object';
    const EVENT_STATUS_IGNORE_EXISTING = 'import_definition.status: Ignoring existing Object';
    const EVENT_STATUS_IGNORE_FILTERED = 'import_definition.status: Filtered Object';

    const CHECKS = [
        [
            'text' => self::EVENT_STATUS_IMPORT_NEW,
            'attr' => 'new'
        ],
        [
            'text' => self::EVENT_STATUS_IMPORT_EXISTING,
            'attr' => 'existing'
        ],
        [
            'text' => self::EVENT_STATUS_IGNORE_NEW,
            'attr' => 'ignore_new'
        ],
        [
            'text' => self::EVENT_STATUS_IGNORE_EXISTING,
            'attr' => 'ignore_existing'
        ],
        [
            'text' => self::EVENT_STATUS_IGNORE_FILTERED,
            'attr' => 'ignore_filtered'
        ],
    ];

    protected $processId;

    protected $total;
    protected $productsStatuses = [];

    protected $errors = [];
    protected $errorsCnt;
    protected $skippedFiltered;
    protected $skippedNew;
    protected $skippedExisting;
    protected $importedNew;
    protected $importedExisting;

    protected $currentObjectNo;

    /**
     * @var ProcessLogInterface
     */
    private $processLog;

    /**
     * ProcessReport constructor.
     * @param ProcessLogInterface $processLog
     */
    public function __construct(ProcessLogInterface $processLog)
    {
        $this->processLog = $processLog;
    }

    /**
     * @param int $processId
     * @return string
     * @throws NonExistentReportFileException
     */
    public function getReportLogFile($processId)
    {
        return $this->processLog->getReportLogFile($processId);
    }

    /**
     * @param int $processId
     * @throws NonExistentReportFileException
     */
    public function prepareReport($processId)
    {
        $this->processId = $processId;
        $this->doReport();
        $this->doSummary();
    }

    /**
     * @return array
     */
    public function getReportHtml()
    {
        $items = [];
        $items[] = 'Total lines processed: ' . $this->total;
        if ($this->importedNew) {
            $items[] = 'Imported new objects: ' . $this->importedNew;
        }

        if ($this->importedExisting) {
            $items[] = 'Updates: ' . $this->importedExisting;
        }

        if ($this->skippedNew) {
            $items[] = 'Skipped new: ' . $this->skippedNew;
        }

        if ($this->skippedExisting) {
            $items[] = 'Skipped existing: ' . $this->skippedExisting;
        }

        if ($this->skippedFiltered) {
            $items[] = 'Filtered: ' . $this->skippedFiltered;
        }

        $items[] = 'Errors count: ' . $this->errorsCnt;

        $link = '/admin/process_manager/reports/log-download/' . $this->processId;
        $items[] = "Log file: <a href=\"$link\" target=\"_blank\">download</a>";

        if (count($this->errors)) {
            $items[] = 'Errors: ';
            $items = array_merge($items, $this->errors);
        }

        return implode('<br />', $items);
    }

    protected function doSummary()
    {
        $this->importedNew = 0;
        $this->importedExisting = 0;
        $this->skippedNew = 0;
        $this->skippedExisting = 0;
        $this->skippedFiltered = 0;
        $this->errorsCnt = 0;
        $this->errros = [];

        $cnt = $this->currentObjectNo; // after import it keeps number of processed products
        for ($iter = 0; $iter <= $cnt; ++$iter) {
            if (isset($this->productsStatuses[$iter])) {
                $status = $this->productsStatuses[$iter];

                if (isset($status['error'])) {
                    $this->errors[] = '<b>Line ' . ($iter+1) . '. Error: </b>' . $status['error'];
                    $this->errorsCnt++;
                } elseif (isset($status['ignore_filtered'])) {
                    $this->skippedFiltered++;
                } elseif (isset($status['ignore_new'])) {
                    $this->skippedNew++;
                } elseif (isset($status['ignore_existing'])) {
                    $this->skippedExisting++;
                } elseif (isset($status['new'])) {
                    $this->importedNew++;
                } elseif (isset($status['existing'])) {
                    $this->importedExisting++;
                }
            }
        }
    }

    protected function doReport()
    {
        $filePath = $this->processLog->getReportLogFile($this->processId);
        if (!file_exists($filePath)) {
            throw new NonExistentReportFileException($this->processId);
        }

        $this->currentObjectNo = 0;

        $file = new \SplFileObject($filePath);
        while (!$file->eof()) {
            $line = $file->fgets();
            if (strlen($line) < self::SKIP_LINES_SHORTER_THAN) {
                continue;
            }
            $this->processLine($line);
        }
    }

    /**
     * @param string $line
     */
    protected function processLine($line)
    {
        if ($this->checkForProgress($line)) {
            return;
        }

        if ($this->checkForTotal($line)) {
            return;
        }

        if ($this->checkForError($line)) {
            return;
        }

        $this->processChecks($line);
    }

    /**
     * @param string $line
     * @return bool
     */
    protected function checkForTotal($line)
    {
        $pos = strpos($line,self::EVENT_TOTAL);
        if ($pos) {
            $total = substr($line, $pos + strlen(self::EVENT_TOTAL));
            $this->total = intval($total);
            return true;
        }

        return false;
    }

    /**
     * @param string $line
     * @return bool
     */
    protected function checkForProgress($line)
    {
        if (false !== strpos($line,self::EVENT_PROGRESS)) {
            $this->currentObjectNo++;
            return true;
        }

        return false;
    }

    /**
     * @param string $line
     * @return bool
     */
    protected function checkForError($line)
    {
        $pos = strpos($line,self::EVENT_STATUS_ERROR);
        if (false !== $pos) {
            $this->productsStatuses[$this->currentObjectNo]['error']
                = substr($line, $pos + strlen(self::EVENT_STATUS_ERROR));
            return true;
        }

        return false;
    }

    /**
     * @param string $line
     */
    protected function processChecks($line)
    {
        foreach (self::CHECKS as $check) {
            $pos = strpos($line, $check['text']);
            if (false !== $pos) {
                $this->productsStatuses[$this->currentObjectNo][$check['attr']] = true;
            }
        }
    }
}
