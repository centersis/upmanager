<?php

namespace App\Http\Controllers;

use App\Models\Update;
use App\Http\Requests\StoreUpdateRequest;
use App\Http\Requests\UpdateUpdateRequest;

class UpdateController extends Controller
{
    public function index()
    {
        return response()->json(Update::with('project')->get());
    }

    public function store(StoreUpdateRequest $request)
    {
        $data = $request->validated();

        $update = Update::create($data);

        return response()->json($update->load('project'), 201);
    }

    public function show(Update $update)
    {
        return response()->json($update->load('project'));
    }

    public function update(UpdateUpdateRequest $request, Update $update)
    {
        $data = $request->validated();

        $update->update($data);

        return response()->json($update->load('project'));
    }

    public function destroy(Update $update)
    {
        $update->delete();

        return response()->noContent();
    }
} 