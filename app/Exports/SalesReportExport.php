<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesReportExport implements WithMultipleSheets
{
    public function __construct(
        protected Carbon $start,
        protected Carbon $end,
        protected ?int $userId = null
    ) {}

    public function sheets(): array
    {
        return [
            new SalesExport($this->start, $this->end, $this->userId),
            new BestSellerExport($this->start, $this->end, $this->userId),
        ];
    }
}
