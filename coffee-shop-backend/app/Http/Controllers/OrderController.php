<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['orderItems.product', 'user']);

        // Filter by status if provided
        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        // Admin can see all orders, regular users only see their own
        /** @var \App\Models\User $user */
        $user = auth('api')->user();
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        } elseif ($request->has('user_id')) {
            // Admin can filter by specific user
            $query->where('user_id', $request->user_id);
        }

        $orders = $query->recent()->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $orderItemsData = [];

            // Validate stock and calculate total
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => "Insufficient stock for product: {$product->name}"
                    ], 400);
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];

                // Reduce stock
                $product->stock -= $item['quantity'];
                $product->save();
            }

            // Create order
            /** @var \App\Models\User $authUser */
            $authUser = auth('api')->user();
            $order = Order::create([
                'user_id' => $authUser->id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            DB::commit();

            $order->load(['orderItems.product']);

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified order
     */
    public function show($uuid)
    {
        $order = Order::with(['orderItems.product', 'user'])
            ->where('uuid', $uuid)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        // Users can only view their own orders
        /** @var \App\Models\User $currentUser */
        $currentUser = auth('api')->user();
        if ($order->user_id !== $currentUser->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order
        ]);
    }

    /**
     * Update the specified order status
     */
    public function update(Request $request, $uuid)
    {
        $order = Order::where('uuid', $uuid)->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        // Only admin can update order status
        /** @var \App\Models\User $adminUser */
        $adminUser = auth('api')->user();
        if (!$adminUser->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only admin can update order status.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // If order is being cancelled, restore stock
        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            DB::beginTransaction();
            try {
                foreach ($order->orderItems as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->stock += $item->quantity;
                        $product->save();
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to restore stock'
                ], 500);
            }
        }

        $order->update(['status' => $request->status]);
        $order->load(['orderItems.product']);

        return response()->json([
            'status' => 'success',
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }

    /**
     * Remove the specified order
     */
    public function destroy($uuid)
    {
        $order = Order::where('uuid', $uuid)->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        // Only admin can delete orders
        /** @var \App\Models\User $deleteUser */
        $deleteUser = auth('api')->user();
        if (!$deleteUser->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only admin can delete orders.'
            ], 403);
        }

        // Only allow deletion of pending orders
        if ($order->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending orders can be deleted'
            ], 400);
        }

        // Restore stock
        DB::beginTransaction();
        try {
            foreach ($order->orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock += $item->quantity;
                    $product->save();
                }
            }

            $order->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete order'
            ], 500);
        }
    }
}
