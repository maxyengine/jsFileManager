<?php

namespace Nrg\Data\Dto;

/**
 * Class Metadata.
 */
class Metadata
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var Sorting
     */
    private $sorting;

    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->filter = (new Filtering($data))->getFilter();
        $this->sorting = new Sorting($data);
        $this->pagination = new Pagination($data);
    }

    /**
     * @return Filter
     */
    public function getFilter(): Filter
    {
        return $this->filter;
    }

    /**
     * @return Sorting
     */
    public function getSorting(): Sorting
    {
        return $this->sorting;
    }

    /**
     * @return Pagination
     */
    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    /**
     * @param Filter $filter
     *
     * @return Metadata
     */
    public function setFilter(Filter $filter): self
    {
        $this->filter = $filter;

        return $this;
    }
}
