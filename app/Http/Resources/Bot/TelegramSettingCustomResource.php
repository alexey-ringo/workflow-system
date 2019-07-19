<?php

namespace App\Http\Resources\Bot;

use Illuminate\Http\Resources\Json\JsonResource;

class TelegramSettingCustomResource extends JsonResource
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
            'key' => $this->key,
            'value' => $this->value,
            //'serialized' => $this->serialized ?? false,
            ];
    }
}
