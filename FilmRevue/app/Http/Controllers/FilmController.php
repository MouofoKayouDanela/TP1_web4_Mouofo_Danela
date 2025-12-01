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
