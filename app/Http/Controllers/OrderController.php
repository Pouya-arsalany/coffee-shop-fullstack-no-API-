<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Table;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function addProduct(Product $product)
    {
        $user = Auth::user();

        $order = Order::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            ['total_price' => 0]
        );

        $existingItem = OrderItem::where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += 1;
            $existingItem->save();
        } else {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }

        // Update total price
        $order->total_price += $product->price;
        $order->save();

        return redirect()->back()->with('success', 'Product added to your order.');
    }

    public function showOrder()
    {
        $order = Order::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->with(['orderItems.product', 'table'])
            ->first();

        $tables = Table::where('is_available', true)->get();

        return view('reservation', compact('order', 'tables'));
    }


    public function showTableSelection()
    {
        $tables = Table::where('is_available', true)->get();

        return view('reservation.select-table', compact('tables'));
    }

    public function chooseTable(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
        ]);

        $order = Order::firstOrCreate(
            ['user_id' => auth()->id(), 'status' => 'pending'],
            ['total_price' => 0]
        );

        $order->table_id = $request->table_id;
        $order->save();

        return redirect()->route('reserve')->with('success', 'Table selected.');
    }

    public function removeItem($itemId)
    {
        $orderItem = OrderItem::findOrFail($itemId);

        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        $orderItem->delete();

        return redirect()->back()->with('success', 'Item removed from order.');
    }

    public function submitOrder(Request $request)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return redirect()->back()->withErrors('No pending order found.');
        }

        if (!$order->table_id) {
            return redirect()->back()->withErrors('Please select a table before placing your order.');
        }

        $order->status = 'completed';
        $order->save();

        return redirect()->route('reserve')->with('success', 'Order placed successfully!');
    }

    public function clearOrder()
    {
        $order = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($order) {
            $order->orderItems()->delete();
            $order->delete();
        }

        return redirect()->back()->with('success', 'Order cleared.');
    }
    public function index()
    {
        $orders = Order::with(['user', 'table'])->get();
        $users = User::all();
        $tables = Table::where('is_available', true)->get();

        return view('panel.orders.index', compact('orders', 'users', 'tables'));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}

