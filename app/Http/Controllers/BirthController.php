<?php

namespace App\Http\Controllers;

use App\Http\Requests\Birth\StoreBirthRequest;
use App\Http\Requests\Birth\UpdateBirthRequest;
use App\Http\Resources\BirthResource;
use App\Models\Birth;
use Illuminate\Http\Request;

class BirthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $births = Birth::all()->toResourceCollection();

        return $births;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBirthRequest $request)
    {
        $data = $request->validated();

        $birth = Birth::create($data);

        return (new BirthResource($birth));
    }

    /**
     * Display the specified resource.
     */
    public function show(Birth $birth)
    {
        return (new BirthResource($birth));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBirthRequest $request, Birth $birth): BirthResource
    {
        $data = $request->validated();

        $birth->update($data);

        return (new BirthResource($birth));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Birth $birth)
    {
        $birth->delete();

        return response(null, 204);
    }
}
