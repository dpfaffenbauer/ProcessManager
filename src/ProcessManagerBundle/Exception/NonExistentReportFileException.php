<?php
/**
 * @date    17/11/2017 11:53
 * @author  Wojciech Peisert <wpeisert@divante.pl>
 */

namespace ProcessManagerBundle\Exception;

class NonExistentReportFileException extends \Exception
{
    public function __construct($processId = 0, \Throwable $previous = null)
    {
        parent::__construct("Non existent log file for process: $processId", 1, $previous);
    }
}
