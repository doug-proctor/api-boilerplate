<?php

namespace App\Http\Controllers;

use App\Thing;
use App\Http\Resources\Thing as ThingResource;

use Illuminate\Http\Request;

class ThingController extends Controller
{
    public function index()
    {
        return ThingResource::collection(Thing::all());
    }

    public function show($thingName)
    {
        $thing = Thing::where('name', $thingName)->first();

        return $thing === null ? $this->emptyResponse() : new ThingResource($thing);
    }

    private function emptyResponse()
    {
        return response(['data' => (object)[]], 200);
    }
}
