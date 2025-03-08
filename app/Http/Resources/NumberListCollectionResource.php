<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NumberListCollectionResource extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'number_lists' => NumberListResource::collection($this->collection),
            'pagination' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'next_page_url' => $this->nextPageUrl() ? $this->currentPage() + 1 : NULL,
                'prev_page_url' => $this->previousPageUrl() ? $this->currentPage() - 1 : NULL
            ]
        ];
    }
}