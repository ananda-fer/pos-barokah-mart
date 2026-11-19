<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'user'])->latest()->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get(['id', 'code', 'name', 'stock', 'unit']);
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;
            $details = [];

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $subtotal = $item['qty'] * $item['cost_price'];
                $total += $subtotal;

                $details[] = [
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'cost_price' => $item['cost_price'],
                    'subtotal' => $subtotal,
                ];

                // Tambah stok produk otomatis
                $product->increment('stock', $item['qty']);
            }

            $purchase = Purchase::create([
                'invoice_number' => 'PO-' . now()->format('Ymd') . '-' . str_pad(Purchase::whereDate('created_at', now())->count() + 1, 4, '0', STR_PAD_LEFT),
                'supplier_id' => $request->supplier_id,
                'user_id' => Auth::id(),
                'total' => $total,
                'purchase_date' => $request->purchase_date,
            ]);

            $purchase->details()->createMany($details);
        });

        return redirect()->route('purchases.index')->with('success', 'Pembelian stok berhasil disimpan dan stok bertambah.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'details.product']);
        return view('purchases.show', compact('purchase'));
    }
}
