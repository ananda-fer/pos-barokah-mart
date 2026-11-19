<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl print:hidden">Struk Transaksi</h2>
    </x-slot>

    <style>
        @media print {
            @page {
                size: 58mm auto;
                margin: 0;
            }
            body {
                width: 58mm;
                margin: 0;
                padding: 2mm;
                background: #fff !important;
                font-family: 'Courier New', Courier, monospace;
                font-size: 10px;
                color: #000;
            }
            nav, header, .no-print {
                display: none !important;
            }
            .struk-container {
                width: 100% !important;
                max-width: 58mm !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
        }
    </style>

    <div class="py-8 print:py-0">
        <div class="max-w-md mx-auto sm:px-6 struk-container">
            <x-card class="print:shadow-none print:p-0">
                <div class="text-center mb-2">
                    <p class="font-bold uppercase">BAROKAH MART</p>
                    <p class="text-xs text-gray-500 print:text-black">Jl. Raya Karawang No. 10</p>
                    <p class="text-xs text-gray-500 print:text-black mt-1">{{ $transaction->invoice_number }}</p>
                </div>

                <p class="text-xs text-gray-500 print:text-black mb-2">
                    {{ $transaction->created_at->format('d/m/Y H:i') }} | Kasir: {{ $transaction->user->name }}
                </p>

                <hr class="border-dashed border-gray-400 my-2">

                @foreach ($transaction->details as $detail)
                    <div class="text-xs mb-1">
                        <div class="font-medium">{{ $detail->product->name }}</div>
                        <div class="flex justify-between">
                            <span>{{ $detail->qty }} x {{ number_format($detail->price, 0, ',', '.') }}</span>
                            <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach

                <hr class="border-dashed border-gray-400 my-2">

                <div class="flex justify-between font-bold text-xs">
                    <span>TOTAL</span>
                    <span>Rp{{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span>BAYAR</span>
                    <span>Rp{{ number_format($transaction->pay, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span>KEMBALI</span>
                    <span>Rp{{ number_format($transaction->change, 0, ',', '.') }}</span>
                </div>

                <hr class="border-dashed border-gray-400 my-2">
                <p class="text-center text-xs mt-2">Terima Kasih Atas Kunjungan Anda</p>

                <div class="mt-4 space-y-2 no-print">
                    <button type="button" onclick="window.print()"
                        class="w-full bg-gray-800 text-white py-2 rounded-md">
                        Cetak Struk (58mm)
                    </button>
                    <a href="{{ route('pos.index') }}"
                        class="block text-center bg-indigo-600 text-white py-2 rounded-md">
                        Transaksi Baru
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
