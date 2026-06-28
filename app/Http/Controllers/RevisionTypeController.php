<?php

namespace App\Http\Controllers;

use App\Http\Requests\RevisionType\UpdateRevisionTypeRequest;
use App\Http\Requests\RevisionType\StoreRevisionTypeRequest;
use App\Http\Resources\RevisionTypeResource;
use App\Models\RevisionType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RevisionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $revisionTypes = RevisionType::all()->toResourceCollection();

        return $revisionTypes;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRevisionTypeRequest $request)
    {
        $data = $request->validated();

        $revisionType = RevisionType::create($data);

        return (new RevisionTypeResource($revisionType));
    }

    /**
     * Display the specified resource.
     */
    public function show(RevisionType $revisionType)
    {
        return (new RevisionTypeResource($revisionType));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRevisionTypeRequest $request, RevisionType $revisionType): RevisionTypeResource
    {
        $data = $request->validated();

        $revisionType->update($data);

        return (new RevisionTypeResource($revisionType));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RevisionType $revisionType)
    {
        $revisionType->delete();

        return response(null, 204);
    }
}
