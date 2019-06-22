<?php

namespace Nrg\Data\Service;

/**
 * Trait PopulateAbility
 */
trait PopulateAbility
{
    /**
     * @param array $data
     * @param array $exceptFields
     *
     * @return bool
     */
    public function populateEntity(array $data, array $exceptFields = []): bool
    {
        foreach ($exceptFields as $field) {
            unset($data[$field]);
        }

        $wasModified = false;
        foreach ($data as $field => $value) {
            $setter = 'set'.ucfirst($field);
            if (method_exists($this, $setter)) {
                $getter = 'get'.ucfirst($field);
                $oldValue = $this->{$getter}();
                $this->{$setter}($data[$field]);

                if ($this->{$getter}() !== $oldValue && !$wasModified) {
                    $wasModified = true;
                }
            }
        }

        return $wasModified;
    }

    /**
     * @param array $data
     */
    public function populateObject(array $data): void
    {
        foreach ($data as $field => $value) {
            $setter = 'set'.ucfirst($field);

            if (method_exists($this, $setter)) {
                $this->{$setter}($data[$field]);
            }
        }
    }
}
