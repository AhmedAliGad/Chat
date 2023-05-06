<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="login_user",
     *      tags={"Sign"},
     *      description="Login User",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"phone","password"},
     *                  @OA\Property(
     *                      property="phone",
     *                      type="string",
     *                      description="User Phone"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      format="password",
     *                      description="User Password"
     *                  ),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *     )
     * @param LoginRequest $request
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = auth()->attempt($request->only(['phone', 'password']));
        if ($user) {
            $token = auth()->user()->createToken('myapptoken')->plainTextToken;
            return (new UserResource(auth()->user()))->additional(['token' => (string)$token]);
        } else {
            return response()->json(['message' => 'These credentials do not match our records.'], 422);
        }
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="logout_user",
     *      tags={"Sign"},
     *      description="Loogout User",
     *      security={{"Bearer":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *     )
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }
}
