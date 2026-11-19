<?php

namespace App\Exports;

use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Export;

class BestSellerExport implements Export, FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        protected Carbon $start,
        protected Carbon $end,
        protected ?int $userId = null
    ) {}

    public function collection(): Collection
    {
        return TransactionDetail::with('product')
            ->whereHas('transaction', function ($q) {
                $q->whereBetween('created_at', [$this->start, $this->end])
                  ->when($this->userId, fn ($sub) => $sub->where('user_id', $this->userId));
            })
            ->selectRaw('product_id, SUM(qty) as total_qty, SUM(subtotal) as total_pendapatan')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->get();
    }

    public function headings(): array
    {
        return ['Kode Produk', 'Nama Produk', 'Total Terjual', 'Total Pendapatan'];
    }

    public function map($detail): array
    {
        return [
            $detail->product?->code ?? '-',
            $detail->product?->name ?? 'Produk Terhapus',
            $detail->total_qty,
            $detail->total_pendapatan,
        ];
    }

    public function title(): string
    {
        return 'Produk Terlaris';
    }
}
