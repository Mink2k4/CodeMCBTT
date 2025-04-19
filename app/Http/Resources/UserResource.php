<?php

namespace App\Http\Resources;

use App\Models\Orders;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'status' => 'success',
            'message' => 'Lấy dữ liệu người dùng thành công!',
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'balance' => $this->balance,
                'total_recharge' => $this->total_recharge,
                'total_deduct' => $this->total_deduct,
                'api_token' => $this->api_token,
                'level' => $this->level,
                'status' => $this->status,
                'facebook' => $this->facebook,
                'avatar' => $this->avatar,
                'telegram_chat_id' => $this->telegram_chat_id,
                'telegram_verified' => $this->telegram_verified,
                'last_login' => $this->last_login,
                'created_at' => $this->created_at,
                'order' => [
                    'total_order' => Orders::where('username', $this->username)->count(),
                ]
            ]
        ];
    }
}
