<?php

namespace App\Http\Controllers;

use App\Models\Critic;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
class CriticController extends Controller
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
     public function destroy($id)
    {
        try {
            
            $critic = Critic::findOrFail($id);

            $critic->delete();

            return response()->json([
                'message' => 'critic deleted successfully'
            ], 200);

        } catch (QueryException $ex) {
            abort(404, "critic not found");

        } catch (\Exception $ex) {
            abort(500, "Server error");
        }
    }
        
    }

