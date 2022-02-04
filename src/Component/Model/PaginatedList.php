<?php

namespace App\Component\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class PaginatedList
{
    #[Groups(['base'])]
    private int $totalCount;

    #[Groups(['base'])]
    private int $page;

    #[Groups(['base'])]
    private int $pageCount;

    #[Groups(['base'])]
    private array $items;

    public function __construct(int $totalCount, int $page, int $pageCount, array $items)
    {
        $this->totalCount = $totalCount;
        $this->page = $page;
        $this->pageCount = $pageCount;
        $this->items = $items;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
