<?php

namespace App\Http\Controllers;

use App\Models\Critic;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
class CriticController extends Controller
{


    /**
     * @OA\Delete(
     *   path="/api/critics/{id}",
     *   summary="Supprimer une critique",
     *   description="Supprime une critique en utilisant son ID.",
     *   tags={"Critics"},
     *
     *   @OA\Parameter(
     *       name="id",
     *       in="path",
     *       required=true,
     *       description="ID de la critique à supprimer",
     *       @OA\Schema(type="integer", example=5)
     *   ),
     *
     *   @OA\Response(
     *       response=200,
     *       description="Critique supprimée avec succès",
     *       @OA\JsonContent(
     *           @OA\Property(
     *               property="message",
     *               type="string",
     *               example="critic deleted successfully"
     *           )
     *       )
     *   ),
     *
     *   @OA\Response(
     *       response=404,
     *       description="Critique introuvable",
     *       @OA\JsonContent(
     *           @OA\Property(
     *               property="message",
     *               type="string",
     *               example="critic not found"
     *           )
     *       )
     *   ),
     *
     *   @OA\Response(
     *       response=500,
     *       description="Erreur interne du serveur",
     *       @OA\JsonContent(
     *           @OA\Property(
     *               property="message",
     *               type="string",
     *               example="Server error"
     *           )
     *       )
     *   ),
     * )
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

