<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientosAnimales;

/**
 * @OA\Schema(
 *     schema="movimientosAnimales",
 *     title="movimientosAnimales",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="id_solicitud", type="integer"),
 *     @OA\Property(property="id_rescate", type="integer"),
 *     @OA\Property(property="id_usuario", type="integer"),
 *     @OA\Property(property="fecha_movimiento", type="date"),
 * )
 */
class MovimientosAnimalesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/movimientos-animales",
     *     tags={"MovimientosAnimales"},
     *     summary="Obtener todos los movimientos de animales",
     *     description="Recupera todos los movimientos de animales de la base de datos.",
     *     operationId="indexMovimientosAnimales",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de movimientos de animales",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/movimientosAnimales")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $movimientos = MovimientosAnimales::all();

        return response()->json($movimientos, 200);
    }

    /**
     * @OA\Post(
     *     path="/movimientos-animales",
     *     tags={"MovimientosAnimales"},
     *     summary="Registrar un nuevo movimiento de animal",
     *     description="Crea un nuevo movimiento de animal con la información proporcionada.",
     *     operationId="storeMovimientoAnimal",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del movimiento de animal",
     *         @OA\JsonContent(
     *             required={"id_solicitud", "id_rescate", "id_usuario", "fecha_movimiento"},
     *             @OA\Property(property="id_solicitud", type="integer", format="int", example=1),
     *             @OA\Property(property="id_rescate", type="integer", format="int", example=2),
     *             @OA\Property(property="id_usuario", type="integer", format="int", example=3),
     *             @OA\Property(property="fecha_movimiento", type="date", example="2023-01-01"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Movimiento de animal registrado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/movimientosAnimales"
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validación fallida",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Error de validación"),
     *             @OA\Property(property="errors", type="object"),
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_solicitud' => 'required|integer',
            'id_rescate' => 'required|integer',
            'id_usuario' => 'required|integer',
            'fecha_movimiento' => 'required|date',
        ]);

        $movimiento = MovimientosAnimales::create([
            'id_solicitud' => $request->input('id_solicitud'),
            'id_rescate' => $request->input('id_rescate'),
            'id_usuario' => $request->input('id_usuario'),
            'fecha_movimiento' => $request->input('fecha_movimiento'),
        ]);

        return response()->json($movimiento, 201);
    }

    /**
     * @OA\Put(
     *     path="/movimientos-animales/{id}",
     *     tags={"MovimientosAnimales"},
     *     summary="Actualizar un movimiento de animal",
     *     description="Actualiza un movimiento de animal existente por su ID con la información proporcionada.",
     *     operationId="updateMovimientoAnimal",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del movimiento de animal",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos actualizados del movimiento de animal",
     *         @OA\JsonContent(
     *             required={"id_solicitud", "id_rescate", "id_usuario", "fecha_movimiento"},
     *             @OA\Property(property="id_solicitud", type="integer", format="int", example=1),
     *             @OA\Property(property="id_rescate", type="integer", format="int", example=2),
     *             @OA\Property(property="id_usuario", type="integer", format="int", example=3),
     *             @OA\Property(property="fecha_movimiento", type="date", example="2023-01-01"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Movimiento de animal actualizado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/movimientosAnimales"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Movimiento de animal no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Movimiento de animal no encontrado"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validación fallida",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Error de validación"),
     *             @OA\Property(property="errors", type="object"),
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_solicitud' => 'required|integer',
            'id_rescate' => 'required|integer',
            'id_usuario' => 'required|integer',
            'fecha_movimiento' => 'required|date',
        ]);

        $movimiento = MovimientosAnimales::find($id);

        if (!$movimiento) {
            return response()->json(['error' => 'Movimiento de animal no encontrado'], 404);
        }

        $movimiento->update([
            'id_solicitud' => $request->input('id_solicitud'),
            'id_rescate' => $request->input('id_rescate'),
            'id_usuario' => $request->input('id_usuario'),
            'fecha_movimiento' => $request->input('fecha_movimiento'),
        ]);

        return response()->json($movimiento, 200);
    }

    /**
     * @OA\Delete(
     *     path="/movimientos-animales/{id}",
     *     tags={"MovimientosAnimales"},
     *     summary="Eliminar un movimiento de animal",
     *     description="Elimina un movimiento de animal existente por su ID.",
     *     operationId="deleteMovimientoAnimal",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del movimiento de animal",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Movimiento de animal eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Movimiento de animal no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Movimiento de animal no encontrado"),
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $movimiento = MovimientosAnimales::find($id);

        if (!$movimiento) {
            return response()->json(['error' => 'Movimiento de animal no encontrado'], 404);
        }

        $movimiento->delete();

        return response()->json(null, 204);
    }

    // Puedes agregar otros métodos según tus necesidades
}
