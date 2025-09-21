<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Sipariş oluştur
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        DB::beginTransaction();

        try {
            // Sipariş oluştur
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'verildi',
                'delivery_date' => $request->delivery_date,
                'note' => $request->note,
            ]);

            // Order items ekle
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Sipariş başarıyla oluşturuldu.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kullanıcının sipariş listesi
     */
    public function index()
    {
        $user = Auth::user();

        $orders = Order::with('items.product')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'date' => Carbon::parse($order->created_at)
                        ->locale('tr')
                        ->isoFormat('D MMMM YYYY HH:mm'),// Örn: 16 Nisan 2023
                    'status' => $order->status,
                    'invoice_url' => $order->getFirstMediaUrl('invoices') ?: null,
                    'items' => $order->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product_name' => $item->product?->name ?? '—',
                            'product_image' => $item->product?->getFirstMediaUrl('products', 'preview') ?? null,
                            'quantity' => $item->quantity,
                        ];
                    }),
                ];
            });

        return response()->json($orders);
    }

    /**
     * Sipariş detayı
     */
    public function show($id)
    {
        $user = Auth::user();

        $order = Order::with('items.product')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return response()->json([
            'id' => $order->id,
            'status' => $order->status,
            'note' => $order->note,
            'date' => Carbon::parse($order->created_at)
                ->locale('tr')
                ->isoFormat('D MMMM YYYY HH:mm'), // Örn: 25 Temmuz 2024
            'invoice_url' => $order->getFirstMediaUrl('invoices') ?: null,
            'items' => $order->items->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->product?->name ?? '—',
                'quantity' => $item->quantity,
                'product_image' => $item->product?->getFirstMediaUrl('products', 'preview') ?? null,
            ]),
        ]);
    }

    /**
     * Fatura indir (PDF)
     */
    public function downloadInvoice($id)
    {
        $user = Auth::user();

        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Spatie Media Library kullanıldığı için path'i buradan alabiliriz
        $invoiceUrl = $order->getFirstMediaUrl('invoices');
        if (!$invoiceUrl) {
            return response()->json(['message' => 'Fatura bulunamadı.'], 404);
        }

        // Eğer local storage kullanıyorsan
        $filePath = Storage::path($order->getFirstMedia('invoices')->getPath());
        return response()->download($filePath);
    }
}
