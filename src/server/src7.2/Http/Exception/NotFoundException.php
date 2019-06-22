<?php

namespace Nrg\Http\Exception;

use Nrg\Http\Value\HttpStatus;

/**
 * Class NotFoundException
 */
class NotFoundException extends HttpException
{
    /**
     * {@inheritdoc}
     */
    public function __construct($message = 'Not Found', ...$args)
    {
        array_shift($args);
        parent::__construct($message, HttpStatus::NOT_FOUND, ...$args);
    }
}