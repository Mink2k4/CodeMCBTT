<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetOrderResource extends JsonResource
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
            'message' => "Lấy dữ liệu thành công!",
            'data' => [
                'id' => $this->id,
                'service_id' => $this->service_id,
                'service_name' => $this->service_name,
                'server_order' => $this->server_service,
                'price' => $this->price,
                'quantity' => $this->quantity,
                'total_payment' => $this->total_payment,
                'order_link' => $this->order_link,
                'start_count' => $this->start,
                'buff_count' => $this->buff,
                'status' => $this->status,
                'status_name' => statusOrder($this->status, false),
                'action' => $this->action,
                'error' => $this->error,
                'history' => $this->history,
                'note' => $this->note,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]
        ];
    }
}
