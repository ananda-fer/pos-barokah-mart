<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Export;

class SalesExport implements Export, FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        protected Carbon $start,
        protected Carbon $end,
        protected ?int $userId = null
    ) {}

    public function collection(): Collection
    {
        return Transaction::with('user')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->when($this->userId, fn ($q) => $q->where('user_id', $this->userId))
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return ['No. Invoice', 'Tanggal', 'Kasir', 'Total', 'Bayar', 'Kembalian'];
    }

    public function map($transaction): array
    {
        return [
            $transaction->invoice_number,
            $transaction->created_at->format('d-m-Y H:i'),
            $transaction->user->name,
            $transaction->total,
            $transaction->pay,
            $transaction->change,
        ];
    }

    public function title(): string
    {
        return 'Data Transaksi';
    }
}
