<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\FilmResource;
use App\Http\Resources\CriticResource;
use App\Http\Resources\ActorResource;
use Illuminate\Database\QueryException;


class FilmController extends Controller
{
    /**
     * @OA\Get(
     *     path="/films",
     *     summary="Lister tous les films",
     *     description="Retourne la liste complète des films.",
     *     tags={"Films"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Liste des films",
     *         @OA\JsonContent(
     *             type="array",    
     *          @OA\Items(
     *      type="object",
     *      @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Inception"),
 *                 @OA\Property(property="release_year", type="integer", example=2010),
 *                 @OA\Property(property="length", type="integer", example=148),
 *                 @OA\Property(property="description", type="string", example="A mind-bending thriller"),
 *                 @OA\Property(property="rating", type="string", example="PG-13"),
 *                 @OA\Property(property="language_id", type="integer", example=1),
 *                 @OA\Property(property="special_features", type="string", example="Deleted Scenes,Behind the Scenes"),
 *                 @OA\Property(property="image", type="string", example="inception.jpg"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-12-01T14:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-12-01T14:00:00Z")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */
    public function index()
    {
       try {
            return FilmResource::collection(Film::all())-> response()->setStatusCode(200);
       } 
        catch (Exception $ex) {
            abort(500, 'server error');
        
       }
    }

    /**
     * @OA\Get(
     *     path="/films/{id}/actors",
     *     summary="Obtenir les acteurs d’un film",
     *     description="Retourne la liste des acteurs associés au film.",
     *     tags={"Films"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du film",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Liste des acteurs",
     *         @OA\JsonContent(
     *             type="array",
     *                @OA\Items(
     *      type="object",
     *      @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Inception"),
 *                 @OA\Property(property="release_year", type="integer", example=2010),
 *                 @OA\Property(property="length", type="integer", example=148),
 *                 @OA\Property(property="description", type="string", example="A mind-bending thriller"),
 *                 @OA\Property(property="rating", type="string", example="PG-13"),
 *                 @OA\Property(property="language_id", type="integer", example=1),
 *                 @OA\Property(property="special_features", type="string", example="Deleted Scenes,Behind the Scenes"),
 *                 @OA\Property(property="image", type="string", example="inception.jpg"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-12-01T14:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-12-01T14:00:00Z")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Film non trouvé ou ID invalide"
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */
    public function getFilmActors($id)
    {
        try {
            
            $film = Film::with('actors')->find($id);

            if (!$film) {
            abort(404, 'Film not found');
            }

            return response()->json($film->actors, 200);

        } catch (QueryException $ex) {

            abort(404, 'invalid id');


        } catch ( Exception $ex) {

            abort(500, 'Server error');
        }
    }

        /**
     * @OA\Get(
     *     path="/films/{id}/critics",
     *     summary="Obtenir les critiques d’un film",
     *     description="Retourne les informations du film ainsi que ses critiques.",
     *     tags={"Films"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du film",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Film et critiques associés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="film",
     *             ),
     *             @OA\Property(
     *                 property="critics",
     *                 type="array",
     *                   @OA\Items(
     *      type="object",
     *      @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Inception"),
 *                 @OA\Property(property="release_year", type="integer", example=2010),
 *                 @OA\Property(property="length", type="integer", example=148),
 *                 @OA\Property(property="description", type="string", example="A mind-bending thriller"),
 *                 @OA\Property(property="rating", type="string", example="PG-13"),
 *                 @OA\Property(property="language_id", type="integer", example=1),
 *                 @OA\Property(property="special_features", type="string", example="Deleted Scenes,Behind the Scenes"),
 *                 @OA\Property(property="image", type="string", example="inception.jpg"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-12-01T14:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-12-01T14:00:00Z")
     *             )
     *         ))
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Film non trouvé ou ID invalide"
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */


    public function getFilmCritics($id)
    {
        try {
            
            $film = Film::with('critics')->find($id);

            if (!$film) {
            abort(404, 'Film not found');
            }

            $data = [
                'film' => new FilmResource($film),
                'critics' => CriticResource::collection($film->critics),
            ];

            return response()->json($data, 200);

        } catch (QueryException $ex) {

            abort(404, 'invalid id');


        } catch ( Exception $ex) {

            abort(500, 'Server error');
        }
    }

    //https://laravel.com/docs/master/eloquent-resources

    /**
     * @OA\Get(
     *     path="/films/{film_id}/average-score",
     *     summary="Obtenir la moyenne des scores d'un film",
     *     description="Calcule la moyenne des scores des critiques associées à un film.",
     *     tags={"Films"},
     *
     *     @OA\Parameter(
     *         name="film_id",
     *         in="path",
     *         required=true,
     *         description="ID du film",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Moyenne des scores",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="average_score",
     *                 type="number",
     *                 example=4.2
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Film non trouvé",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Film not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Server error")
     *         )
     *     )
     * )
     */
    public function averageScore($film_id)
    {
        try {
            $film = Film::findOrFail($film_id);
            
            if (!$film) {
            abort(404, 'Film not found');
            }

            $avgScore = $film->critics()->avg('score');

            return response()->json([
                'average_score' => $avgScore ?? 0   
            ])->setStatusCode(200);

        } catch (Exception $ex) {
            abort(500, "Server error");
        }
    }

    //https://laravel.com/docs/master/queries#where-clauses

    /**
     * @OA\Get(
     *     path="/films-search",
     *     summary="Recherche de films",
     *     description="Filtrer les films selon différents paramètres",
     *     tags={"Films"},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Filtre par mot clé dans le titre",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="rating",
     *         in="query",
     *         description="Filtre par rating du film",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="minLength",
     *         in="query",
     *         description="Longueur minimale du film",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="maxLength",
     *         in="query",
     *         description="Longueur maximale du film",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste paginée des films"
     *     ),
     *   @OA\Response(
     *         response=500,
     *         description="Erreur interne du serveur"
     *     ),
     * )
     */
    public function search(Request $request)
    {
        try {
            $films = Film::query() 
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->keyword . '%');
            })
            ->when($request->filled('rating'), function ($query) use ($request) {
                $query->where('rating', $request->rating);
            })
            ->when($request->filled('minLength'), function ($query) use ($request) {
                $query->where('length', '>=', $request->minLength);
            })
            ->when($request->filled('maxLength'), function ($query) use ($request) {
                $query->where('length', '<=', $request->maxLength);
            })
             ->paginate(20);

            return response()->json($films);

        } catch (Exception $ex) {
            abort(500, "Server error");
        }
    }


}
