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
                "launches" => $this->has('launches') ? LaunchResource::collection($this->launches) : [],
                "events" => $this->has('events') ? EventResource::collection($this->events) : [],
                $this->mergeWhen($request->routeIs('articles.index'), [
                    "links" => [
                        "rel" => "self",
                        "href" => route('articles.show', $this->id),
                    ]
                ]),
                $this->mergeWhen($request->routeIs(['articles.show', 'articles.store', 'articles.update']), [
                    "_links" => [
                        "List articles" => route('articles.index'),
                    ]
                ]),
            ];
    }
}
