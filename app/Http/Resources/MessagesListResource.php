<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessagesListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => openssl_decrypt(base64_decode($this->content),'AES-256-CBC',
                        config('services.chat.content_key'),
                 OPENSSL_RAW_DATA, config('services.chat.content_key')),
            'user' => $this->user->name ?? ''
        ];
    }
}
