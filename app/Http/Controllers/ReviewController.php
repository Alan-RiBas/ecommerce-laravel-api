<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Obter os dados da requisição
            $productId = $request->input('productId');
            $userId = $request->input('userId');
            $rating = $request->input('rating');
            $comment = $request->input('comment');

            // Validar os campos obrigatórios
            if (!$productId || !$userId || !$rating || !$comment) {
                return response()->json(['message' => 'Preencha todos os campos'], 400);
            }

            // Verificar se já existe uma avaliação do mesmo usuário para o produto
            $existingReview = Review::where('product_id', $productId)
                ->where('user_id', $userId)
                ->first();

            if ($existingReview) {
                // Atualizar a avaliação existente
                $existingReview->comment = $comment;
                $existingReview->rating = $rating;
                $existingReview->save();
            } else {
                // Criar uma nova avaliação
                $review = new Review([
                    'comment' => $comment,
                    'rating' => $rating,
                    'product_id' => $productId,
                    'user_id' => $userId,
                ]);
                $review->save();
            }

            // Recalcular a média de avaliações do produto
            $reviews = Review::where('product_id', $productId)->get();

            if ($reviews->count() > 0) {
                $totalRating = $reviews->sum('rating');
                $averageRating = $totalRating / $reviews->count();

                $product = Product::find($productId);
                if ($product) {
                    $product->rating = $averageRating;
                    $product->save();
                } else {
                    return response()->json(['message' => 'Produto não encontrado'], 404);
                }
            }

            return response()->json([
                'message' => 'Avaliação postada com sucesso',
                'reviews' => $reviews,
            ], 201);

        } catch (\Exception $e) {
            Log::error("Erro ao postar avaliação: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao postar avaliação'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
