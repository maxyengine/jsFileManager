<?php

namespace Nrg\Data\Dto;

use Nrg\Data\Service\PopulateAbility;
use InvalidArgumentException;

/**
 * Class Pagination.
 */
class Pagination
{
    use PopulateAbility;

    private const LITERAL_MIN_LIMIT = 'minLimit';
    private const LITERAL_MAX_LIMIT = 'maxLimit';
    private const LITERAL_MIN_OFFSET = 'minOffset';
    public const MIN_LIMIT = 1;
    public const MAX_LIMIT = 10000;
    public const MIN_OFFSET = 0;
    private const DEFAULT_LIMIT = 10000;

    /**
     * @var int
     */
    private $limit = self::DEFAULT_LIMIT;

    /**
     * @var int
     */
    private $maxLimit = self::MAX_LIMIT;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var array
     */
    private $errorMessages = [
        self::LITERAL_MIN_LIMIT => 'The limit should not be less than %d.',
        self::LITERAL_MAX_LIMIT => 'The limit should not be greater than %d.',
        self::LITERAL_MIN_OFFSET => 'The offset should not be less than %d.',
    ];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->populateObject($data);
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $maxLimit
     *
     * @return Pagination
     */
    public function setMaxLimit(int $maxLimit): self
    {
        $this->maxLimit = $maxLimit;

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return Pagination
     *
     * @throws InvalidArgumentException
     */
    public function setLimit(int $limit): self
    {
        if (self::MIN_LIMIT > $limit) {
            throw new InvalidArgumentException(sprintf($this->errorMessages[self::LITERAL_MIN_LIMIT], self::MIN_LIMIT));
        }

        if ($this->maxLimit < $limit) {
            throw new InvalidArgumentException(sprintf($this->errorMessages[self::LITERAL_MAX_LIMIT], $this->maxLimit));
        }

        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $offset
     *
     * @return Pagination
     *
     * @throws InvalidArgumentException
     */
    public function setOffset(int $offset): self
    {
        if (self::MIN_OFFSET > $offset) {
            throw new InvalidArgumentException(sprintf($this->errorMessages[self::LITERAL_MIN_OFFSET], self::MIN_OFFSET));
        }

        $this->offset = $offset;

        return $this;
    }
}
