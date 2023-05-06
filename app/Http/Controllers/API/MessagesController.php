<?php

namespace App\Http\Controllers\API;

use App\Events\NewMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesListResource;
use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * @OA\Get(
     *      path="/messages",
     *      operationId="chat_messages",
     *      tags={"Messages"},
     *      description="Chat Messages List",
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $messages = Message::with('user:id,name')->orderBy('created_at', 'desc')->get();

        return MessagesListResource::collection($messages);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *      path="/messages",
     *      operationId="store_new_messsage",
     *      tags={"Messages"},
     *      description="Send Message",
     *      security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"content"},
     *                  @OA\Property(
     *                      property="content",
     *                      type="string",
     *                      description="Message Content"
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
     * @param Request $request
     * @return MessagesListResource|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $message = Message::create(['user_id' => auth()->id(),
            'content' => base64_encode(openssl_encrypt($request->get('content'),'AES-256-CBC',
                        config('services.chat.content_key'),
                 OPENSSL_RAW_DATA, config('services.chat.content_key')))]);

        event(new NewMessage($request->get('content')));

        return new MessagesListResource($message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
