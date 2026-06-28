<?php

namespace App\Http\Controllers;

use App\Http\Requests\Newborn\StoreNewbornRequest;
use App\Http\Requests\Newborn\UpdateNewbornRequest;
use App\Http\Resources\NewbornResource;
use App\Models\Newborn;
use Illuminate\Http\Request;

class NewbornController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newborns = Newborn::all()->toResourceCollection();

        return $newborns;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewbornRequest $request)
    {
        $data = $request->validated();

        $newborn = Newborn::create($data);

        return (new NewbornResource($newborn));
    }

    /**
     * Display the specified resource.
     */
    public function show(Newborn $newborn)
    {
        return (new NewbornResource($newborn));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewbornRequest $request, Newborn $newborn): NewbornResource
    {
        $data = $request->validated();

        $newborn->update($data);

        return (new NewbornResource($newborn));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Newborn $newborn)
    {
        $newborn->delete();

        return response(null, 204);
    }
}
