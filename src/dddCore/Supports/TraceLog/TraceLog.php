<?php

namespace App\Infrastructure\Supports\TraceLog;

use App\Infrastructure\Facades\TraceChainId;
use App\Infrastructure\Supports\GenerateCode;
use Exception;

/**
 * @class TraceLog
 * @package App\Infrastructure\Supports\TraceLog
 */
class TraceLog
{
    /**
     * @param $logger
     * @return void
     * @throws Exception
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(tap(new TraceLogFormatter(null, null, true, true, [
                'traceId'      => TraceChainId::getTraceId(),
                'spanId'       => TraceChainId::getSpanId(),
                'parentSpanId' => TraceChainId::getParentSpanId(),
                'process'      => $this->getPid(),
                'threadId'     => $this->getThreadId(),
            ]), static function ($formatter) {
                $formatter->includeStacktraces();
            }));
        }
    }

    /**
     * 进程ID
     * @return int
     */
    protected function getPid(): int
    {
        $pid = getmypid();

        if (function_exists('posix_getpid')) {
            $pid = posix_getpid();
        }

        return $pid;
    }

    protected function getThreadId(): int
    {
        $tid = 0;

        if (function_exists('zend_thread_id')) {
            $tid = zend_thread_id();
        }

        return $tid;
    }
}
