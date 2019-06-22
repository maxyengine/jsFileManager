<?php

namespace Nrg\Data\Abstraction;

use Nrg\Data\Dto\Filter;
use Nrg\Data\Dto\Metadata;
use Nrg\Data\Dto\Sorting;


interface SchemaAdapter
{
    public function adaptFieldsForDb(string ...$fields): array;

    /**
     * @param array $data
     *
     * @return array
     */
    public function adaptDataForDB(array $data): array;

    /**
     * @param array $data
     *
     * @return array
     */
    public function adaptDataForApp(array $data): array;

    /**
     * @param Metadata $metadata
     */
    public function adaptMetadata(Metadata $metadata): void;

    /**
     * @param Filter $filter
     */
    public function adaptFilter(Filter $filter): void;

    /**
     * @param Sorting $sorting
     */
    public function adaptSorting(Sorting $sorting): void;
}
