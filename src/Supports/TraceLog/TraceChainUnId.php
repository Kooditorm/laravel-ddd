<?php

namespace DDDCore\Supports\TraceLog;

use Exception;

/**
 * @class TraceChainUnId
 * @package DDDCore\Supports\TraceLog
 */
class TraceChainUnId
{
    public const X_TRACE_ID_HEADER  = 'X-B3-TraceId';
    public const X_SPAN_ID_HEADER   = 'X-B3-SpanId';
    public const X_PARENT_ID_HEADER = 'X-B3-ParentSpanId';

    protected string $traceId;
    protected string $parentSpanId;
    protected string $spanId;

    /**
     * @return string
     * @throws Exception
     */
    public function getTraceId(): string
    {
        $this->traceId = getHeader(self::X_TRACE_ID_HEADER);

        if (empty($this->traceId)) {
            $this->traceId = $this->genUid();
        }

        return $this->traceId;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getSpanId(): string
    {
        $this->spanId = getHeader(self::X_SPAN_ID_HEADER);

        if (empty($this->spanId)) {
            $this->spanId = $this->genUid();
        }

        return $this->spanId;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getParentSpanId(): string
    {
        $this->parentSpanId = getHeader(self::X_PARENT_ID_HEADER);

        if (empty($this->parentSpanId)) {
            $this->parentSpanId = $this->getTraceId();
        }

        return $this->parentSpanId;
    }


    /**
     * 生成UID
     * @param  int  $length
     * @return string
     * @throws Exception
     */
    protected function genUid(int $length = 16): string
    {
        $length = max($length, 16);

        $uid = uniqid('', false);
        $rem = $length - mb_strlen($uid);

        if ($rem >= 0) {
            $rand = $this->generate($rem, 4);
            $rand = strtolower($rand);
            $uid  .= $rand;
        }

        return $uid;
    }


    /**
     * 随机生成字符串
     * @param  int  $length  长度
     * @return string
     * @throws Exception
     */
    protected function generate(int $length): string
    {
        $ranString  = '';
        $numStr     = '0,1,2,3,4,5,6,7,8,9,a,b,c,d,e,f';
        $randomPool = explode(',', $numStr);
        if ($length > 0) {
            do {
                $random    = random_int(0, count($randomPool) - 1);
                $ranString .= $randomPool[$random];
                $length--;
            } while ($length > 0);
        }

        return $ranString;
    }
}
