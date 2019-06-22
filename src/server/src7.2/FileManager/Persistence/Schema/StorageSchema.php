<?php

namespace Nrg\FileManager\Persistence\Schema;

use Nrg\Data\Abstraction\AbstractSchema;

class StorageSchema extends AbstractSchema
{
    private const TABLE_NAME = 'nrgsoft_file_manager_storage';

    /**
     * {@inheritdoc}
     */
    public function getFieldNames(): array
    {
        return [
            'id',
            'type',
            'name',
            'description',
            'params',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getTableName(): string
    {
        return self::TABLE_NAME;
    }
}
