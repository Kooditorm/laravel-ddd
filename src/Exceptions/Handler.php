<?php

namespace DDDCore\Exceptions;

use DDDCore\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{

    use ResponseTraits;


    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];


    /**
     * Register the exception handling callbacks for the application.
     *
     * @param $request
     * @param  Throwable  $e
     * @return JsonResponse
     * @throws Throwable
     */
    public function render($request, Throwable $e): JsonResponse
    {
        if ($e instanceof BaseException) {
            return $this->failed($e->getMessage(), $e->getCode());
        }

        if ($e instanceof ValidatorException) {
            $message = $e->getMessage();
            $bag     = $e->getMessageBag();
            if ($bag !== null) {
                $message = $bag->first();
            }
            return $this->failed($message, 400);
        }


        if ($e instanceof ModelNotFoundException) {
            return $this->notFound();
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->notFound();
        }

        return $this->failed($e->getMessage(), $e->getCode());
    }
}
