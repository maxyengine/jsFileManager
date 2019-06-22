<?php

namespace Nrg\Data\Service;

use Nrg\Data\Abstraction\SchemaAdapter;
use Nrg\Data\Dto\Filter;
use Nrg\Data\Dto\Metadata;
use Nrg\Data\Dto\Sorting;
use Nrg\Utility\Service\CaseStyleConverter;

/**
 * Class CaseStyleSchemaAdapter.
 */
class CaseStyleSchemaAdapter implements SchemaAdapter
{
    /**
     * @var CaseStyleConverter
     */
    private $styleConverter;

    /**
     * SchemaAdapter constructor.
     */
    public function __construct()
    {
        $this->styleConverter = new CaseStyleConverter();
    }

    /**
     * @param string[] ...$fields
     *
     * @return array
     */
    public function adaptFieldsForDb(string ...$fields): array
    {
        foreach ($fields as $index => $fieldName) {
            $newFieldName = $this->styleConverter->toSnakeCase($fieldName);

            if ($newFieldName !== $fieldName) {
                $fields[$index] = $newFieldName;
            }
        }

        return $fields;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function adaptDataForDB(array $data): array
    {
        foreach ($data as $fieldName => $value) {
            $newFieldName = $this->styleConverter->toSnakeCase($fieldName);

            if ($newFieldName !== $fieldName) {
                $data[$newFieldName] = $value;
                unset($data[$fieldName]);
            }
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function adaptDataForApp(array $data): array
    {
        foreach ($data as $fieldName => $value) {
            $newFieldName = $this->styleConverter->toCamelCase($fieldName);

            if ($newFieldName !== $fieldName) {
                $data[$newFieldName] = $value;
                unset($data[$fieldName]);
            }

            if ('null' === $value) {
                $data[$newFieldName !== $fieldName ? $newFieldName : $fieldName] = null;
            }
        }

        return $data;
    }

    /**
     * @param Metadata $metadata
     */
    public function adaptMetadata(Metadata $metadata): void
    {
        $this->adaptFilter($metadata->getFilter());
        $this->adaptSorting($metadata->getSorting());
    }

    /**
     * @param Filter $filter
     */
    public function adaptFilter(Filter $filter): void
    {
        foreach ($filter->getConditions() as $condition) {
            $condition->setField(
                $this->styleConverter->toSnakeCase($condition->getField())
            );
        }

        foreach ($filter->getFilters() as $subFilter) {
            $this->adaptFilter($subFilter);
        }
    }

    /**
     * @param Sorting $sorting
     */
    public function adaptSorting(Sorting $sorting): void
    {
        foreach ($sorting as $orderBy) {
            $orderBy->setField(
                $this->styleConverter->toSnakeCase($orderBy->getField())
            );
        }
    }
}
