<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Order::with('client');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate($request->input('per_page', 10));

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->validated());

        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', 'Order created successfully.');
        }

        return new OrderResource($order);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', 'Order updated successfully.');
        }

        return new OrderResource($order);
    }

    public function destroy(Request $request, Order $order)
    {
        $order->delete();

        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', 'Order deleted successfully.');
        }

        return response()->json(null, 204);
    }
}
