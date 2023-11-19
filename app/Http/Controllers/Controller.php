<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(
 *     title="Deni",
 *     version="1.0",
 *     description="Descripción de la API de Ejemplo",
 *     termsOfService="https://www.ejemplo.com/terms",
 *     @OA\Contact(
 *         email="contacto@ejemplo.com"
 *     ),
 *     @OA\License(
 *         name="Licencia de Ejemplo",
 *         url="https://www.ejemplo.com/licencia"
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
