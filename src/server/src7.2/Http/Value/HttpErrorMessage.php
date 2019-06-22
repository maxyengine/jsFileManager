<?php

namespace Nrg\Http\Value;

use JsonSerializable;

/**
 * Class HttpErrorMessage
 */
class HttpErrorMessage implements JsonSerializable
{
    /**
     * @var HttpStatus
     */
    private $status;

    /**
     * @var array
     */
    private $details = [];

    /**
     * @var array
     */
    private $debugInfo = [];

    /**
     * @param HttpStatus $status
     */
    public function __construct(HttpStatus $status)
    {
        $this->status = $status;
    }

    /**
     * @param string $reasonPhrase
     * @return HttpErrorMessage
     */
    public function setReasonPhrase(string $reasonPhrase): HttpErrorMessage
    {
        $this->status->setReasonPhrase($reasonPhrase);

        return $this;
    }

    /**
     * @param array $details
     * @return HttpErrorMessage
     */
    public function setDetails(array $details): HttpErrorMessage
    {
        $this->details = $details;

        return $this;
    }

    /**
     * @param array $debugInfo
     * @return HttpErrorMessage
     */
    public function setDebugInfo(array $debugInfo): HttpErrorMessage
    {
        $this->debugInfo = $debugInfo;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'statusCode' => $this->status->getCode(),
            'reasonPhrase' => $this->status->getReasonPhrase(),
            'details' => $this->details,
            'debugInfo' => $this->debugInfo,
        ];
    }
}