<?php 

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCart()
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        return response()->json($cart->items()->with('product')->get());
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $product = Product::findOrFail($request->product_id);
    
        if ($product->stock_quantity < $request->quantity) {
            return response()->json(['error' => 'Not enough stock'], 400);
        }
    
        // ✅ Vérifier si l'article existe déjà
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();
    
        if ($cartItem) {
            // 🔥 Incrémente la quantité au lieu de l'écraser
            $cartItem->increment('quantity', $request->quantity);
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }
    
        return response()->json(['message' => 'Product added to cart', 'cart' => $cart->items]);
    }
    

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:cart_items,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('product_id', $request->product_id)->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated', 'cart' => $cartItem->cart->items]);
    }

    public function removeFromCart($productId)
    {
        $cart = Cart::where('user_id', auth()->id())->first();

        if ($cart) {
            CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->delete();
        }

        return response()->json(['message' => 'Item removed from cart']);
    }
}
