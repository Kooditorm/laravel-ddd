<?php

namespace DDDCore\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

trait ResponseTraits
{
    /**
     * 默认状态码
     * @var int
     */
    protected int $statusCode = FoundationResponse::HTTP_OK;

    /**
     * 获取状态码
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * 设置状态码
     * @param $code
     * @return $this
     */
    public function setStatusCode($code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * 返回数据
     * @param $data
     * @param  array  $header
     * @return JsonResponse
     */
    public function respond($data, array $header = []): JsonResponse
    {
        return Response::json($data, $this->statusCode, $header);
    }

    /**
     * 调整返回数据格式
     * @param $code
     * @param $message
     * @param $data
     * @return JsonResponse
     */
    public function status($code, $message, $data): JsonResponse
    {
        return $this->respond(compact("code", "message", "data"));
    }

    /**
     * 返回信息
     * @param  string  $message
     * @param  string  $code
     * @return JsonResponse
     */
    public function message(string $message, string $code = '1000'): JsonResponse
    {
        return $this->status($code, $message, []);
    }

    /**
     * 成功
     * @param  array  $data
     * @return JsonResponse
     */
    public function success(array $data = []): JsonResponse
    {
        return $this->status(FoundationResponse::HTTP_OK, 'success', $data);
    }

    /**
     * 失败
     * @param  string  $message
     * @param  int  $code
     * @return JsonResponse
     */
    public function failed(string $message, int $code = 1000): JsonResponse
    {
        return $this->message($message, $code);
    }

    /**
     * 找不到页面
     * @param  string  $message
     * @return JsonResponse
     */
    public function notFound(string $message = 'Not Fond!'): JsonResponse
    {
        return $this->failed($message, Foundationresponse::HTTP_NOT_FOUND);
    }


}
