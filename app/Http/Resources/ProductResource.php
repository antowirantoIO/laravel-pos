<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'barcode' => $this->barcode,
            'price' => $this->price,
            'purchase_price' => $this->purchase_price,
            'expired_date' => $this->expired_date,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'uom' => $this->uom_prod->name,
            'created_at' => $this->created_at,
            'image_url' => Storage::url($this->image)
        ];
    }
}
