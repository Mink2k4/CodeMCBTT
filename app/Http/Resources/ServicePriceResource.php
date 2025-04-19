<?php

namespace App\Http\Resources;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicePriceResource extends JsonResource
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
        // $api_token = $request->header('Api-token');
        // $user = User::where('api_token', $api_token)->where('domain', env('PARENT_SITE'))->first();
        return [
            'status' => 'success',
            'message' => "Lấy dữ liệu thành công!",
            'data' => [
                'id' => $this->id,
                'server' => $this->server,
                'name' => $this->name,
                // 'price' => priceServer($this->id, $user->level),
                'min' => $this->min,
                'max' => $this->max,
                'title' => $this->title,
                'description' => $this->description,
                'status' => $this->status,
            ]
        ];
    }
}
