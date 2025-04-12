<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class ProductController extends Controller
{
    /**
     * @group Products
     *
     * Get all products
     *
     * Returns a list of all available products.
     *
     * @response 200 {
     *  "id": 1,
     *  "name": "Laptop",
     *  "description": "A high-performance laptop",
     *  "price": 1299.99,
     *  "stock": 5
     * }
     */
    public function index()
    {
        $products = Cache::remember('products', 300, function () {
            return Product::all();
        });

        return response()->json($products, 200);
    }

    /**
     * @group Products
     *
     * Create a new product
     *
     * @bodyParam name string required The name of the product. Example: "Laptop"
     * @bodyParam price numeric required The price of the product. Example: 1299.99
     * @bodyParam stock int required The stock quantity. Example: 10
     *
     * @response 201 {
     *  "id": 1,
     *  "name": "Laptop",
     *  "price": 1299.99,
     * "description": "This is a powerful and versatile laptop designed for both productivity and entertainment."
     *  "stock": 10
     * }
     *
     * @response 422 {

     * "message": "Validation failed",
     * "errors": {
     * "name": ["The name field is required."],
     * "price": ["The price field must be numeric."]
     *}
     * }
     */

    public function store(Request $request)
    {
        Log::info("Product creation request received", ['data' => $request->all()]);

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'description' => 'required|max:255',
                'stock' => 'required|integer',
            ]);

            $product = Product::create($validated);

            Cache::forget('products');

            Log::info("Product created successfully", ['product_id' => $product->id]);

            return response()->json($product, 201);
        } catch (ValidationException $e) {
            Log::error("Validation failed", ['errors' => $e->errors()]);

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }


    /**
     * @group Products
     *
     * Get a single product
     *
     * @urlParam id int required The ID of the product. Example: 1
     *
     * @response 200 {
     *  "id": 1,
     *  "name": "Laptop",
     *  "price": 1299.99,
     * "description": "A premium 2-in-1 convertible laptop with Intel Core i7, OLED touchscreen, and a sleek, ultra-slim design.",
     *  "stock": 5
     * }
     * @response 404 {
     *  "message": "Product not found"
     * }
     */
    public function show($id)
    {
        $product = Cache::remember("product_{$id}", 600, function () use ($id) {
            return Product::find($id);
        });

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product, 200);
    }

    /**
     * @group Products
     *
     * Update a product
     *
     * @urlParam id int required The ID of the product. Example: 1
     * @bodyParam name string The updated name of the product. Example: "Gaming Laptop"
     * @bodyParam price numeric The updated price of the product. Example: 1499.99
     * @bodyParam stock int The updated stock quantity. Example: 8
     *
     * @response 200 {
     *  "id": 1,
     *  "name": "Gaming Laptop",
     *  "price": 1499.99,
     * "description": "A premium 2-in-1 convertible laptop with Intel Core i7, OLED touchscreen, and a sleek, ultra-slim design.",
     *  "stock": 8
     * }
     * @response 422 {
     *  "message": "Product not found"
     * }
     */

    public function update(Request $request, $id)
    {
        Log::info("Update request received for product ID: {$id}", ['data' => $request->all()]);

        $product = Product::find($id);

        if (!$product) {
            Log::warning("Product not found: ID {$id}");
            return response()->json(['message' => 'Product not found'], 404);
        }

        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'price' => 'sometimes|required|numeric',
                'description' => 'sometimes|required|max:255',
                'stock' => 'sometimes|required|integer',
            ]);

            $product->update($validated);

            // Clear cache
            Cache::forget("product_{$id}");
            Cache::forget('products');

            Log::info("Product updated successfully: ID {$id}", ['updated_data' => $product]);

            return response()->json($product, 200);
        } catch (ValidationException $e) {
            Log::error("Validation failed for product update", ['errors' => $e->errors()]);

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }


    /**
     * @group Products
     *
     * Delete a product
     *
     * @urlParam id int required The ID of the product. Example: 1
     *
     * @response 200 {
     *  "message": "Product deleted successfully"
     * }
     * @response 404 {
     *  "message": "Product not found"
     * }
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        Cache::forget("product_{$id}");
        Cache::forget('products');

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
