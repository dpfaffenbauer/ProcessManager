<?php
/**
 * @date    17/11/2017 11:53
 * @author  Wojciech Peisert <wpeisert@divante.co>
 */

namespace ProcessManagerBundle\Service;

/**
 * Interface ProcessLogInterface
 * @package ProcessManagerBundle\Service
 */
interface ProcessLogInterface
{
    /**
     * @param int $processId
     * @param string $message
     */
    public function logEvent($processId, $message);

    /**
     * @param int $processId
     * @return string
     */
    public function getReportLogFile($processId);
}
