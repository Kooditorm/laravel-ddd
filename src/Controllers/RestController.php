<?php

namespace DDDCore\Controllers;

use DDDCore\Services\BaseService;
use DDDCore\Traits\ResponseTraits;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * @class RestController
 * @package DDDCore\Controllers
 */
class RestController extends Controller
{
    use ResponseTraits;

    /** @var BaseService $service */
    protected BaseService $service;

    /**
     * 业务方法的映射
     * @var array
     */
    protected array $mapping = [];

    public function __construct(BaseService $service)
    {
        $this->service = $service;
    }

    /**
     * @param  $data
     * @return JsonResponse
     */
    public function success($data = []): JsonResponse
    {
        return $this->status($this->statusCode, 'success', $data);
    }

    public function failed(string $message = 'fail', string $code = '1000'): JsonResponse
    {
        return $this->message($message, $code);
    }

    public function __call($method, $parameters)
    {
        try {
            $method = $this->mapping[$method] ?? $method;
            $result = $this->service->$method();
            return $this->success($result);
        } catch (Exception $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }

    }


}
