<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
  public function toArray(Request $request)
  {
    return [
      'id' => $this->id,
      'name' => $this->whenHas('name', $this->name),
      'email' => $this->whenHas('email', $this->email),
      'email_verified_at' => $this->whenHas('email_verified_at', fn() => $this->email_verified_at?->diffForHumans()),
      //      'password' => $this->password,
      //      'unread_notifications_count' => $this->unread_notifications_count,
      //      'tokens_count' => $this->tokens_count,
      //      'read_notifications_count' => $this->read_notifications_count,
      //      'notifications_count' => $this->notifications_count,
      //      'updated_at' => $this->updated_at,
      //      'created_at' => $this->created_at,
      //      'remember_token' => $this->remember_token,
    ];
  }
}
