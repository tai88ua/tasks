<?php

namespace App\Dto;

use DateTimeInterface;

class CriteriaDto
{
    public const STATUSES = ['new', 'in progress', 'done'];
    public const NAMES = ['id', 'title', 'date'];
    private bool $sortASC = true;
    private string $sortedBy = 'id';
    private ?DateTimeInterface $filterByDate = null;
    private ?string $filterByStatus = null;

    /**
     * @return bool
     */
    public function isSortASC(): bool
    {
        return $this->sortASC;
    }

    /**
     * @param bool $sortASC
     */
    public function setSortASC(bool $sortASC): void
    {
        $this->sortASC = $sortASC;
    }

    /**
     * @return string
     */
    public function getSortedBy(): ?string
    {
        return $this->sortedBy;
    }

    /**
     * @param string $sortedBy
     */
    public function setSortedBy(string $sortedBy): void
    {
        if (!in_array($sortedBy, self::NAMES)) {
            throw new \InvalidArgumentException("Invalid sortedBy name value");
        }
        $this->sortedBy = $sortedBy;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getFilterByDate(): ?DateTimeInterface
    {
        return $this->filterByDate;
    }

    /**
     * @param DateTimeInterface|null $filterByDate
     */
    public function setFilterByDate(?DateTimeInterface $filterByDate): void
    {
        $this->filterByDate = $filterByDate;
    }

    /**
     * @return string|null
     */
    public function getFilterByStatus(): ?string
    {
        return $this->filterByStatus;
    }

    /**
     * @param string|null $filterByStatus
     */
    public function setFilterByStatus(?string $filterByStatus): void
    {

        if (!$filterByStatus) {
            return;
        }

        if (!in_array($filterByStatus, self::STATUSES)) {
            throw new \InvalidArgumentException("Invalid status");
        }

        $this->filterByStatus = $filterByStatus;
    }
}
