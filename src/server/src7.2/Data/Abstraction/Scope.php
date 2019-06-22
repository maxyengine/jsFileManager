<?php

namespace Nrg\Data\Abstraction;

/**
 * Interface Scope.
 */
interface Scope
{
    /**
     * @param $query
     */
    public function apply($query): void;
}
