<?php

namespace Nrg\Http\Abstraction;

use Nrg\Http\Value\HttpResponse;

/**
 * Interface ResponseEmitter
 */
interface ResponseEmitter
{
    /**
     * @param HttpResponse $response
     * @param bool $terminate
     */
    public function emit(HttpResponse $response, $terminate = false): void;
}