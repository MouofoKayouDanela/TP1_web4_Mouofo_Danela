<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use App\Http\Resources\UserResource;


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
}
