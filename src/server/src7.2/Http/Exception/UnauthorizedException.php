<?php

namespace Nrg\Http\Exception;

use Nrg\Http\Value\HttpStatus;

/**
 * Class UnauthorizedException
 */
class UnauthorizedException extends HttpException
{
    /**
     * {@inheritdoc}
     */
    public function __construct($message = 'Unauthorized', ...$args)
    {
        array_shift($args);
        parent::__construct($message, HttpStatus::UNAUTHORIZED, ...$args);
    }
}