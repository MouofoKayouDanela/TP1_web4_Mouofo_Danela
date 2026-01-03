<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
  /**
 * @OA\Post(
 *     path="/api/signup",
 *     summary="Inscription utilisateur",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"first_name","last_name","login","email","password","password_confirmation"},
 *             @OA\Property(property="first_name", type="string", example="John"),
 *             @OA\Property(property="last_name", type="string", example="Doe"),
 *             @OA\Property(property="login", type="string", example="jdoe"),
 *             @OA\Property(property="email", type="string", example="john@test.com"),
 *             @OA\Property(property="password", type="string", example="password123")
 *  *             @OA\Property(property="password_confirmation", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Utilisateur créé"),
 *     @OA\Response(response=422, description="Erreur de validation"),
 *     @OA\Response(response=429, description="Trop de requêtes")
 * )
 */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email|max:50',
            'login' => 'required|string|unique:users,login|max:50',
            'password' => 'required|string|min:8|confirmed|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

       User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'login' => $request->login,
        'password' => bcrypt($request->password), 
    ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
        ], 201);
    }
/**
 * @OA\Post(
 *     path="/api/signin",
 *     summary="Connexion utilisateur",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","login"},
 *             @OA\Property(property="login", type="string", example="jdoe"),
 *             @OA\Property(property="password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Connexion réussie"),
 *     @OA\Response(response=401, description="Identifiants invalides"),
 *     @OA\Response(response=429, description="Trop de requêtes")
 * )
 */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $validator->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Identifiants invalides',
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'token'   => $token,
            'user'    => $user,
        ], 200);
    }
/**
 * @OA\Post(
 *     path="/api/signout",
 *     summary="Déconnexion utilisateur",
 *     tags={"Auth"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(response=200, description="Déconnecté"),
 *     @OA\Response(response=401, description="Non authentifié")
 * )
 */
  public function logout(Request $request)
    {
       
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ], 200);
    }


  
}
