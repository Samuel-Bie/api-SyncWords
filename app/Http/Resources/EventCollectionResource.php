<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\EventResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EventCollectionResource extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = EventResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
