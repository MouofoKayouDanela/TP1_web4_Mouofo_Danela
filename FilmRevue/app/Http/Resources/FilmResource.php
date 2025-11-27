<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
     public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'length'          => $this->length,
            'description'     => $this->description,
            'release_year'    => $this->release_year,
            'special_features'=> $this->special_features,
            'language_id'     => $this->language_id, 
            'rating'          => $this->rating,
            'image '          => $this->image, 
            'rating'          => $this->rating,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
