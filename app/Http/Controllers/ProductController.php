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
    public function showAll(): JsonResponse
    {
        return response()->json("showAll");
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

        
        return response()->json("show");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $productId): JsonResponse
    {
        return response()->json("update");
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
