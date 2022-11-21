<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;

/**
 * @OA\Info(title="Space Flight News API", version="0.1")
 */

class ArticleController extends Controller
{
    public function home()
    {
        return response()->json(['message' => 'Back-end Challenge 2021 🏅 - Space Flight News']);
    }

    /**
     * @OA\Get(
     *     tags={"articles"},
     *     summary="Retorna uma lista de artigos",
     *     description="Retorna um objeto de artigos",
     *     path="/articles",
     *     @OA\Parameter(
     *         name="paginate",
     *         in="query",
     *         @OA\Schema(type="integer", minimum=1, maximum=1000, default=300),
     *         description="Número de artigos por paginação",
     *         required=false,
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="A list with articles",
     *          @OA\JsonContent()
     *     ),
     * ),
     *
     */
    public function index(ListArticleRequest $request)
    {
        $filters = $request->all();

        $articles = Article::when(isset($filters["paginate"]), function ($query) use ($filters) {
            return $query->paginate($filters["paginate"]);
        }, function ($query) {
            return $query->paginate(300);
        });

        return ArticleResource::collection($articles);
    }

    /**
     * @OA\Post(
     *     tags={"articles"},
     *     summary="Cria um novo artigo",
     *     description="Cria um novo artigo",
     *     path="/articles",
     *     @OA\Parameter(
     *         name="featured",
     *         in="query",
     *         @OA\Schema(type="boolean", default=false),
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="url",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="imageUrl",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="newSite",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="summary",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="publishedAt",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         required=false,
     *     ),
     *     @OA\Response(
     *          response="201",
     *          description="OK",
     *          @OA\JsonContent()
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Faltando dados"
     *     )
     * ),
     */
    public function store(StoreArticleRequest $request)
    {
        $article = Article::create($request->validated());
        return new ArticleResource($article);
    }

    /**
     * @OA\Get(
     *     tags={"articles"},
     *     summary="Retorna um artigo pelo ID",
     *     description="Retorna um artigo pelo ID",
     *     path="/articles/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(type="integer"),
     *         required=true,
     *     ),
     *
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent()
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Não encontrado",
     *          @OA\JsonContent()
     *     )
     * ),
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * @OA\Put(
     *     tags={"articles"},
     *     summary="Atualiza um artigo",
     *     description="Atualiza um artigo",
     *     path="/articles/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(type="integer"),
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="featured",
     *         in="query",
     *         @OA\Schema(type="boolean", default=false),
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="url",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="imageUrl",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="newSite",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="summary",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="publishedAt",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         required=false,
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent()
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Faltando dados"
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Não encontrado"
     *     )
     * ),
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article->fill($request->validated())->save();
        return new ArticleResource($article);
    }

    /**
     * @OA\Delete(
     *     tags={"articles"},
     *     summary="Delete um artigo",
     *     description="Delete um artigo",
     *     path="/articles/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(type="integer"),
     *         required=true,
     *     ),
     *
     *     @OA\Response(
     *          response="204",
     *          description="OK",
     *          @OA\JsonContent()
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Não encontrado"
     *     )
     * ),
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json([null], 204);
    }
}
