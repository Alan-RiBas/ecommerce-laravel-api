<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Countable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showAll(Request $request): JsonResponse
    {
        try {
            $category = $request->query('category', 'all');
            $color = $request->query('color', 'all');
            $minPrice = $request->query('minPrice');
            $maxPrice = $request->query('maxPrice');
            $page = $request->query('page', 1);
            $limit = $request->query('limit', 10);

            $filter = [];

            if ($category !== 'all') {
                $filter['category'] = $category;
            }

            if ($color !== 'all') {
                $filter['color'] = $color;
            }

            if ($minPrice && $maxPrice) {
                $filter['price'] = ['>=', $minPrice, '<=', $maxPrice];
            }

            $skip = ($page - 1) * $limit;
            $totalProducts = Product::where($filter)->count();
            $totalPages = ceil($totalProducts / $limit);


            $products = Product::where($filter)
                ->skip($skip)
                ->take($limit)
                ->with('author:id,email')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'products' => $products,
                'totalPages' => $totalPages,
                'totalProducts' => $totalProducts,
            ], 200);

        } catch (\Exception $e) {
            Log::error("Erro ao buscar produtos: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao buscar produtos'], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try{
            $newProduct = new Product($request->all());
            if(!$newProduct->save()){
                Log::error("Erro ao salvar produto");
                return response()->json(['message' => 'Erro ao salvar produto'], 500);
            }

            $reviews = Review::where('product_id', $newProduct->id)->get();
            if ($reviews->isNotEmpty()) {
                // Calcular a média das avaliações
                $totalRating = $reviews->reduce(function ($acc, $review) {
                    return $acc + $review->rating;
                }, 0);

                $averageRating = $totalRating / $reviews->count();

                // Atualizar o produto com a média de avaliação
                $newProduct->rating = $averageRating;
                $newProduct->save();
            }

            return response()->json(compact('newProduct'), 201);
        } catch (\Exception $e) {
            Log::error("Erro ao criar novo produto: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao criar novo produto'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $productId): JsonResponse
    {
        try{

            $product = Product::where('id', $productId->id)
                ->with('author')
                ->first();
            if(!$product){
                return response()->json(['message' => 'Produto não encontrado'], 404);
            }

            $reviews = Review::where('product_id', $productId)
                ->with('author')
                ->orderBy('created_at', 'desc')
                ->get();

            // if($reviews->isNotEmpty()){
            //     $product->reviews = $reviews;
            // }

            return response()->json(compact('product', 'reviews'), 200);

        }catch(\Exception $e){
            Log::error("Erro ao buscar produto: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao buscar produto'], 500);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $productId): JsonResponse
    {
        try{
            $product = Product::find($productId->id);
            if(!$product){
                return response()->json(['message' => 'Produto não encontrado'], 404);
            }

            if(!$product->update($request->all())){
                Log::error("Erro ao atualizar produto");
                return response()->json(['message' => 'Erro ao atualizar produto'], 500);
            }

            return response()->json(compact('product'), 200);
        }catch(\Exception $e){
            Log::error("Erro ao atualizar produto: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao atualizar produto'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $productId): JsonResponse
    {
        return response()->json("destroy");
    }

    /**
     * Display related products.
     */
    public function related(Product $productId): JsonResponse
    {
        return response()->json("related");
    }
}
