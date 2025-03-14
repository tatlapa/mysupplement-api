<?php 

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /** 🔹 PRODUITS */
    public function getProducts()
    {
        return response()->json(Product::with('category', 'image')->get());
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'stock_quantity' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'required',
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);

        $product->update($request->all());

        return response()->json($product);
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }

    /** 🔹 CATÉGORIES */
    public function getCategories()
    {
        return response()->json(Category::all());
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:255',
        ]);

        $category = Category::create($request->all());

        return response()->json($category, 201);
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }

        /** 🔹 IMAGES */
        public function getProductImages()
        {
            return response()->json(ProductImage::with('product')->get());
        }
    
        public function storeProductImage(Request $request)
        {
            $request->validate([
                'product_id' => 'required|exists:product,id',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
    
            // Sauvegarde de l’image
            $path = $request->file('image')->store('product_images', 'public');
    
            $image = ProductImage::create([
                'product_id' => $request->product_id,
                'image_path' => $path
            ]);
    
            return response()->json($image, 201);
        }
    
        public function deleteProductImage(ProductImage $productImage)
        {
            Storage::disk('public')->delete($productImage->image_path); // Supprime le fichier
            $productImage->delete(); // Supprime la ligne en BDD
    
            return response()->json(null, 204);
        }
}
