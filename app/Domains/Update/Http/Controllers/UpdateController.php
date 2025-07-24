<?php

namespace App\Domains\Update\Http\Controllers;

use App\Domains\Update\Entities\Update;
use App\Domains\Update\Http\Requests\StoreUpdateRequest;
use App\Domains\Update\Http\Requests\UpdateUpdateRequest;
use App\Domains\Update\Services\UpdateService;
use App\Http\Controllers\Controller;

class UpdateController extends Controller
{
    public function __construct(
        private UpdateService $updateService
    ) {}

    public function index()
    {
        $updates = $this->updateService->getAllUpdates();
        return response()->json($updates);
    }

    public function store(StoreUpdateRequest $request)
    {
        $update = $this->updateService->createUpdate($request->validated());
        return response()->json($update, 201);
    }

    public function show(Update $update)
    {
        $update = $this->updateService->getUpdateById($update->id);
        return response()->json($update);
    }

    public function update(UpdateUpdateRequest $request, Update $update)
    {
        $updatedUpdate = $this->updateService->updateUpdate($update->id, $request->validated());
        return response()->json($updatedUpdate);
    }

    public function destroy(Update $update)
    {
        $this->updateService->deleteUpdate($update->id);
        return response()->noContent();
    }
} 