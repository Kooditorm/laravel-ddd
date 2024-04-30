<?php

namespace DDDCore\Supports\TraceLog;

use DDDCore\Facades\TraceChainId;

/**
 * @class TraceLog
 * @package DDDCore\Supports\TraceLog
 */
class TraceLog
{

    /**
     * @param $logger
     * @return void
     * @thows \Exception
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
    private function getPid(): int
    {
        $pid = getmypid();

        if (function_exists('posix_getpid')) {
            $pid = posix_getpid();
        }

        return $pid;
    }

    private function getThreadId(): int
    {
        $tid = 0;

        if (function_exists('zend_thread_id')) {
            $tid = zend_thread_id();
        }

        return $tid;
    }
}
