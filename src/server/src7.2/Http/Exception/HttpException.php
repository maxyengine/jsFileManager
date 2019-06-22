<?php

namespace Nrg\Http\Exception;

use Exception;
use Nrg\Http\Value\HttpStatus;

/**
 * Class HttpException
 */
class HttpException extends Exception
{
    /**
     * @var HttpStatus
     */
    private $status;

    /**
     * {@inheritdoc}
     */
    public function __construct(...$args)
    {
        parent::__construct(...$args);
        $this->status = new HttpStatus($this->getCode(), $this->getMessage());
    }

    /**
     * @return HttpStatus
     */
    public function getStatus(): HttpStatus
    {
        return $this->status;
    }
}