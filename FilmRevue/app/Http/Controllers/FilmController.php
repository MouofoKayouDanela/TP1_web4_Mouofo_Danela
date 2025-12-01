<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\FilmResource;
use App\Http\Resources\ActorResource;


class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
