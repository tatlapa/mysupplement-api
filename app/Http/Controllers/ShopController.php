<?php 

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function getProducts()
    {
        return response()->json(Product::with('category')->get());
    }
    public function getProduct($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        return response()->json($product);
    }
}
