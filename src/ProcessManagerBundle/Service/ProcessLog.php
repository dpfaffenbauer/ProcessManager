<?php
/**
 * @date    17/11/2017 11:53
 * @author  Wojciech Peisert <wpeisert@divante.co>
 */

namespace ProcessManagerBundle\Service;

use Pimcore\Log\Simple;
use ProcessManagerBundle\Exception\NonExistentReportFileException;

/**
 * Class ProcessLog
 * @package ProcessManagerBundle\Service
 */
class ProcessLog implements ProcessLogInterface
{
    const LOG_DIRECTORY = 'process_log';
    const LOG_PREFIX = 'process_';
    const LOG_EXTENSION = 'log';

    /**
     * @param int $processId
     * @param string $message
     */
    public function logEvent($processId, $message)
    {
        if (!is_dir($this->getReportLogDir())) {
            mkdir($this->getReportLogDir());
        }
        Simple::log($this->getReportLogPath($processId), $message);
    }

    /**
     * @param int $processId
     * @return string
     */
    public function getReportLogFile($processId)
    {
        return PIMCORE_LOG_DIRECTORY . '/' . $this->getReportLogPath($processId) . '.' . self::LOG_EXTENSION;
    }

    /**
     * @param int $processId
     * @return string
     */
    private function getReportLogDir()
    {
        return PIMCORE_LOG_DIRECTORY . '/' . self::LOG_DIRECTORY . '/';
    }

    /**
     * @param int $processId
     * @return string
     */
    private function getReportLogPath($processId)
    {
        return self::LOG_DIRECTORY . '/' . self::LOG_PREFIX . $processId;
    }
}
