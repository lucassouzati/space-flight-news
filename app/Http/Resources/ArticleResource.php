<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return
            [
                "id" => $this->id,
                "featured" => $this->featured,
                "title" => $this->title,
                "url" => $this->url,
                "imageUrl" => $this->imageUrl,
                "newsSite" => $this->newsSite,
                "summary" => $this->summary,
                "publishedAt" => $this->publishedAt,
                "launches" => $this->has('launches')? $this->launches->toArray() : [],
                "events" => $this->has('events') ? $this->events->toArray() : [],
            ];
    }
}
