<?php


namespace App\Http\Controllers\Api\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ModelRelationshipBinding
{
    public function buildRelationshipsToLoad(Request $request, Builder $query) : Builder
    {
        if (!$request->has('load'))
            return $query;

        if (!array_key_exists($request->load, $this->relationship_dependencies))
            return $query;

        $load = $this->relationship_dependencies[$request->load];

        return $query->with($load);
    }
}
