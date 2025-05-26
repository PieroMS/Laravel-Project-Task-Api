<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="Laravel Swagger API",
 *     description="Laravel Swagger API Documentation"
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server - Development"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token"
 * )
 */
abstract class Controller
{
    //
}
