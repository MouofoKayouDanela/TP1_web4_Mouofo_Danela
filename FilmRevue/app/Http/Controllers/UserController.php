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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * https://laravel.com/docs/master/validation#creating-form-requests
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

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
