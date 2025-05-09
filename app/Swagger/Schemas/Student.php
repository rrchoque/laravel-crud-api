<?php

namespace App\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Student",
 *     type="object",
 *     title="Estudiante",
 *     required={"name", "email", "phone", "language"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="phone", type="string"),
 *     @OA\Property(property="language", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
