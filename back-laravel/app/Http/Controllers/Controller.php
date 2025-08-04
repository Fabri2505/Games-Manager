<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="API de Gestión de Juegos",
 *     version="1.0.0",
 *     description="API para la gestión de juegos, rondas y participantes. Permite crear juegos, gestionar rondas y administrar participantes con sus respectivos resultados.",
 *     @OA\Contact(
 *         email="fabriciocervantesmendoza@gmail.com",
 *         name="Soporte API"
 *     ),
 *     @OA\License(
 *         name="AGPL-3.0",
 *         url="https://opensource.org/licenses/AGPL-3.0"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor API"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Ingrese el token JWT en el formato: Bearer {token}"
 * )
 * 
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="Usuario",
 *     description="Modelo de usuario/jugador",
 *     required={"id", "name", "email"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID único del usuario",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=255,
 *         description="Nombre completo del usuario",
 *         example="Juan Pérez"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         maxLength=255,
 *         description="Correo electrónico del usuario",
 *         example="juan.perez@example.com"
 *     ),
 *     @OA\Property(
 *         property="email_verified_at",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         description="Fecha de verificación del email",
 *         example="2024-01-15T10:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación del usuario",
 *         example="2024-01-10T08:15:30Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización",
 *         example="2024-01-20T14:22:45Z"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Game",
 *     type="object",
 *     title="Juego",
 *     description="Modelo de juego",
 *     required={"id", "descrip", "monto", "fec_juego"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID único del juego",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="descrip",
 *         type="string",
 *         maxLength=255,
 *         description="Descripción del juego",
 *         example="Torneo de Ajedrez Mensual"
 *     ),
 *     @OA\Property(
 *         property="monto",
 *         type="number",
 *         format="float",
 *         minimum=0,
 *         description="Monto del juego",
 *         example=50.00
 *     ),
 *     @OA\Property(
 *         property="fec_juego",
 *         type="string",
 *         format="date-time",
 *         description="Fecha del juego",
 *         example="2024-08-05T10:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="fec_cierre",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         description="Fecha de cierre del juego",
 *         example="2024-08-10T18:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación",
 *         example="2024-08-01T09:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización",
 *         example="2024-08-02T11:15:00Z"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Ronda",
 *     type="object",
 *     title="Ronda",
 *     description="Modelo de ronda de juego",
 *     required={"id", "fec", "hora_ini", "game_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID único de la ronda",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="fec",
 *         type="string",
 *         format="date",
 *         description="Fecha de la ronda",
 *         example="2024-08-05"
 *     ),
 *     @OA\Property(
 *         property="hora_ini",
 *         type="string",
 *         format="time",
 *         description="Hora de inicio de la ronda",
 *         example="14:30:00"
 *     ),
 *     @OA\Property(
 *         property="hora_fin",
 *         type="string",
 *         format="time",
 *         nullable=true,
 *         description="Hora de finalización de la ronda",
 *         example="16:45:00"
 *     ),
 *     @OA\Property(
 *         property="game_id",
 *         type="integer",
 *         format="int64",
 *         description="ID del juego al que pertenece la ronda",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="game",
 *         ref="#/components/schemas/Game",
 *         description="Información del juego asociado"
 *     ),
 *     @OA\Property(
 *         property="participantes",
 *         type="array",
 *         description="Lista de participantes en la ronda",
 *         @OA\Items(ref="#/components/schemas/Participante")
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación",
 *         example="2024-08-05T14:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización",
 *         example="2024-08-05T16:45:00Z"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Participante",
 *     type="object",
 *     title="Participante",
 *     description="Modelo de participante en una ronda",
 *     required={"id", "user_id", "ronda_id", "winner"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID único del participante",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         format="int64",
 *         description="ID del usuario participante",
 *         example=3
 *     ),
 *     @OA\Property(
 *         property="ronda_id",
 *         type="integer",
 *         format="int64",
 *         description="ID de la ronda",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="winner",
 *         type="boolean",
 *         description="Indica si el participante es el ganador",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User",
 *         description="Información del usuario participante"
 *     ),
 *     @OA\Property(
 *         property="ronda",
 *         ref="#/components/schemas/Ronda",
 *         description="Información de la ronda"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación",
 *         example="2024-08-05T14:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización",
 *         example="2024-08-05T16:45:00Z"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="ApiResponse",
 *     type="object",
 *     title="Respuesta API",
 *     description="Estructura estándar de respuesta de la API",
 *     @OA\Property(
 *         property="success",
 *         type="boolean",
 *         description="Indica si la operación fue exitosa",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         description="Mensaje descriptivo de la operación",
 *         example="Operación realizada exitosamente"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         description="Datos de respuesta (estructura variable según endpoint)"
 *     ),
 *     @OA\Property(
 *         property="error",
 *         type="string",
 *         nullable=true,
 *         description="Mensaje de error (solo presente en caso de error)",
 *         example="Descripción del error ocurrido"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="ValidationError",
 *     type="object",
 *     title="Error de Validación",
 *     description="Respuesta de error de validación",
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         description="Mensaje general del error",
 *         example="The given data was invalid."
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         description="Errores específicos por campo",
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string")
 *         ),
 *         example={
 *             "field_name": {"Este campo es obligatorio."},
 *             "another_field": {"Este campo debe ser un número."}
 *         }
 *     )
 * )
 * 
 * @OA\Response(
 *     response="400",
 *     description="Solicitud incorrecta",
 *     @OA\JsonContent(
 *         @OA\Property(property="success", type="boolean", example=false),
 *         @OA\Property(property="message", type="string", example="Datos de entrada inválidos")
 *     )
 * )
 * 
 * @OA\Response(
 *     response="401",
 *     description="No autorizado",
 *     @OA\JsonContent(
 *         @OA\Property(property="message", type="string", example="Unauthenticated.")
 *     )
 * )
 * 
 * @OA\Response(
 *     response="403",
 *     description="Acceso prohibido",
 *     @OA\JsonContent(
 *         @OA\Property(property="message", type="string", example="This action is unauthorized.")
 *     )
 * )
 * 
 * @OA\Response(
 *     response="404",
 *     description="Recurso no encontrado",
 *     @OA\JsonContent(
 *         @OA\Property(property="message", type="string", example="No query results for model.")
 *     )
 * )
 * 
 * @OA\Response(
 *     response="422",
 *     description="Error de validación",
 *     @OA\JsonContent(ref="#/components/schemas/ValidationError")
 * )
 * 
 * @OA\Response(
 *     response="500",
 *     description="Error interno del servidor",
 *     @OA\JsonContent(
 *         @OA\Property(property="success", type="boolean", example=false),
 *         @OA\Property(property="message", type="string", example="Error interno del servidor"),
 *         @OA\Property(property="error", type="string", example="Descripción técnica del error")
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
