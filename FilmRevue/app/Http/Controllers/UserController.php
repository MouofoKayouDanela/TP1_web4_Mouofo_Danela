<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class UserController extends Controller
{
 

    /**
     * Store a newly created resource in storage.
     * https://laravel.com/docs/master/validation#creating-form-requests
     */
    /**
     * @OA\Post(
     *   path="/api/users",
     *   summary="Créer un nouvel utilisateur",
     *   tags={"Users"},
     *
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"first_name", "last_name", "login", "email", "password"},
     *           @OA\Property(property="first_name", type="string", example="John"),
     *           @OA\Property(property="last_name", type="string", example="Doe"),
     *           @OA\Property(property="login", type="string", example="johndoe"),
     *           @OA\Property(property="email", type="string", example="john@example.com"),
     *           @OA\Property(property="password", type="string", example="secret123")
     *       )
     *   ),
     *
     *   @OA\Response(
     *       response=201,
     *       description="Utilisateur créé avec succès",
     *   ),
     *
     *   @OA\Response(
     *       response=422,
     *       description="Erreur de validation",
     *       @OA\JsonContent(
     *           @OA\Property(property="message", type="string", example="The email field is required.")
     *       )
     *   ),
     *
     *   @OA\Response(
     *       response=500,
     *       description="Erreur interne du serveur"
     *   )
     * )
     */

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name'      => 'required|string|max:50',
                'last_name'      => 'required|string|max:50',
                'login'      => 'required|string|max:50',
                'email'     => 'required|email|unique:users,email|max:50',
                'password'  => 'required|string|min:6|max:255',
            ]);

            $user = User::create([
                'first_name'     => $validated['first_name'],
                'last_name'     => $validated['last_name'],
                'login'     => $validated['login'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return (new UserResource($user))
                ->response()
                ->setStatusCode(201);

        } catch (ValidationException $ex) {
            abort(422, $ex->getMessage());
        
        } catch (\Exception $ex) {
            abort(500, "Server error");
        }
    }

        /**
     * @OA\Put(
     *   path="/api/users/{id}",
     *   summary="Mettre à jour un utilisateur",
     *   tags={"Users"},
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="ID de l'utilisateur",
     *      @OA\Schema(type="integer", example=3)
     *   ),
     *
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"first_name", "last_name", "login", "email", "password"},
     *           @OA\Property(property="first_name", type="string", example="Jane"),
     *           @OA\Property(property="last_name", type="string", example="Smith"),
     *           @OA\Property(property="login", type="string", example="janesmith"),
     *           @OA\Property(property="email", type="string", example="jane@example.com"),
     *           @OA\Property(property="password", type="string", example="newpassword123")
     *       )
     *   ),
     *
     *   @OA\Response(
     *       response=200,
     *       description="Utilisateur mis à jour avec succès"
     *   ),
     *
     *   @OA\Response(
     *       response=404,
     *       description="Utilisateur introuvable",
     *       @OA\JsonContent(
     *           @OA\Property(property="message", type="string", example="User not found")
     *       )
     *   ),
     *
     *   @OA\Response(
     *       response=422,
     *       description="Erreur de validation"
     *   ),
     *
     *   @OA\Response(
     *       response=500,
     *       description="Erreur interne du serveur"
     *   )
     * )
     */

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                abort(404, "User not found");
            }

            $validated = $request->validate([
                'first_name'=> 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'login'     => 'required|string|max:50',
                'email' => 'required|email|max:50|unique:users,email,' . $id,
                'password'  => 'required|string|min:6|max:255',
            ]);

             $user->update([
                'first_name'     => $validated['first_name'],
                'last_name'     => $validated['last_name'],
                'login'     => $validated['login'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return (new UserResource($user))
                ->response()
                ->setStatusCode(200);

        } catch (ValidationException $ex) {
            abort(422, $ex->getMessage());

        } catch (Exception $ex) {
            abort(500, "Server error");
        }
    }

    /**
     * @OA\Get(
     *   path="/api/users/{id}/preferred-language",
     *   summary="Obtenir la langue préférée d’un utilisateur",
     *   description="La langue préférée est déterminée selon les critiques publiées par l'utilisateur.",
     *   tags={"Users"},
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="ID de l'utilisateur",
     *      @OA\Schema(type="integer", example=4)
     *   ),
     *
     *   @OA\Response(
     *       response=200,
     *       description="Langue préférée trouvée ou aucune critique",
     *       @OA\JsonContent(
     *           @OA\Property(property="preferred_language", type="string", nullable=true, example="English"),
     *           @OA\Property(property="message", type="string", example="User has no critics")
     *       )
     *   ),
     *
     *   @OA\Response(
     *       response=404,
     *       description="Utilisateur introuvable"
     *   ),
     *
     *   @OA\Response(
     *       response=500,
     *       description="Erreur interne du serveur"
     *   )
     * )
     */
    public function preferredLanguage($user_id)
    {
        try {
       
            $user = User::findOrFail($user_id);

           
            $preferred = \DB::table('critics')
                ->join('films', 'critics.film_id', '=', 'films.id')
                ->join('languages', 'films.language_id', '=', 'languages.id')
                ->select('languages.id', 'languages.name')
                ->where('critics.user_id', $user_id)
                ->groupBy('languages.id', 'languages.name')
                ->orderByRaw('COUNT(languages.id) DESC')
                ->first();  

            if (!$preferred) {
                return response()->json([
                    'preferred_language' => null,
                    'message' => 'User has no critics'
                ])->setStatusCode(200);
            }

            return response()->json([
                'preferred_language' => $preferred->name
            ])->setStatusCode(200);

        } catch (ModelNotFoundException $ex) {
            abort(404, "User not found");

        } catch (\Exception $ex) {
            abort(500, "Server error");
        }
    }

}
