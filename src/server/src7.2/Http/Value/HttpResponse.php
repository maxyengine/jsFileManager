<?php

namespace Nrg\Http\Value;

/**
 * Class HttpResponse.
 *
 * HTTP response implementation.
 */
class HttpResponse
{
    use HttpMessage;

    /**
     * @var HttpStatus
     */
    private $status;

    /**
     * @return HttpStatus
     */
    public function getStatus(): HttpStatus
    {
        return $this->status ?? new HttpStatus(HttpStatus::OK);
    }

    /**
     * @param HttpStatus $status
     *
     * @return HttpResponse
     */
    public function setStatus(HttpStatus $status): HttpResponse
    {
        $this->status = $status;

        return $this;
    }

    public function setStatusCode(int $code, string $reasonPhrase = null): HttpResponse
    {
        $this->setStatus(new HttpStatus($code, $reasonPhrase));

        return $this;
    }
}
