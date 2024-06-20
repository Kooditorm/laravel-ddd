<?php

namespace DDDCore\Middleware;

use Closure;
use DateTime;
use DDDCore\Facades\TraceChainId;
use Illuminate\Http\Request;
use JsonException;

/**
 * @class LogsMiddleware
 * @package DDDCore\Middleware
 */
class LogsMiddleware
{

    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @throws JsonException
     */
    public function handle(Request $request, Closure $next)
    {
        return $this->genTraceLog(function ($request) use ($next) {
            return $next($request);
        }, $request);
    }

    /**
     * @param  Closure  $callable
     * @param  Request  $request
     * @return mixed
     * @throws JsonException
     */
    protected function genTraceLog(Closure $callable, Request $request)
    {
        $begin       = new DateTime();
        $response    = $callable($request);
        $finish      = new DateTime();
        $textContext = array_merge_recursive($this->getRequest($request), $this->getResponse($response), $this->getTrace());
//        event(new LogEvent([$begin, $finish, $textContext]));
        return $response;
    }

    /**
     * @param  Request  $request
     * @return array
     * @throws JsonException
     */
    protected function getRequest(Request $request): array
    {
        $body = trim($request->getContent());
        if (is_json(trim($request->getContent()))){
            $body = json_decode(trim($request->getContent()), true, 512, JSON_THROW_ON_ERROR);
        }

        return [
            'body'   => $body,
            'header' => $request->header(),
            'method' => $request->getMethod(),
            'params' => $request->query(),
            'path'   => $request->path(),
        ];
    }

    protected function getResponse($response): array
    {
        return [
            'status'   => $response->getStatusCode(),
            'response' => $response->original ?? ''
        ];
    }

    protected function getTrace(): array
    {
        return [
            'traceId'      => TraceChainId::getTraceId(),
            'spanId'       => TraceChainId::getSpanId(),
            'parentSpanId' => TraceChainId::getParentSpanId(),
        ];
    }
}
