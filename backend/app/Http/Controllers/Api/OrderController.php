<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(
            Order::with(['user', 'items', 'payment'])->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.price'      => 'required|numeric',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;

            foreach ($request->items as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $order = Order::create([
                'user_id'        => auth()->id() ?? 1,
                'invoice_number'=> 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'total_price'   => $total,
                'status'        => 'pending',
                'payment_status'=> 'unpaid',
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id'  => $order->id,
                    'product_id'=> $item['product_id'],
                    'price'     => $item['price'],
                    'qty' => $item['quantity'],
                    'subtotal'  => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'data'    => $order->load('items')
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create order',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        return response()->json(
            Order::with(['user', 'items', 'payment'])->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->update($request->only([
            'status',
            'payment_status'
        ]));

        return response()->json([
            'message' => 'Order updated',
            'data' => $order
        ]);
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            OrderItem::where('order_id', $id)->delete();
            Order::findOrFail($id)->delete();
        });

        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }
}
