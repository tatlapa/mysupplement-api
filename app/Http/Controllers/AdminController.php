<?php 

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /** 🔹 PRODUCTS */
    public function getProducts()
    {
        return response()->json(Product::with('category')->get());
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'stock_quantity' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageUrl = $this->imageService->uploadImage($request->file('image'), 'products');
        } else {
            return response()->json(['error' => 'Image upload failed'], 400);
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'image_url' => $imageUrl,
        ]);

        return response()->json($product, 201);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'stock_quantity' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_url) {
                $this->imageService->deleteImage($product->image_url);
            }

            $product->image_url = $this->imageService->uploadImage($request->file('image'), 'products');
        }

        $product->update($request->only(['name', 'price', 'description', 'stock_quantity', 'category_id', 'image_url']));

        return response()->json($product);
    }


    public function deleteProduct(Product $product)
    {
        // Supprimer l'image du stockage si elle existe
        if ($product->image_url) {
            $this->imageService->deleteImage($product->image_url);
        }

        // Supprimer le produit de la base de données
        $product->delete();
        
        return response()->json(['message' => 'Produit supprimé avec succès'], 200);
    }

    /** 🔹 CATEGORIES */
    public function getCategories()
    {
        return response()->json(Category::all());
    }
}
