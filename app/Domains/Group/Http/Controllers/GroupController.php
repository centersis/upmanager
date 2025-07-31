<?php

namespace App\Domains\Group\Http\Controllers;

use App\Domains\Group\Services\GroupService;
use App\Domains\Group\Http\Requests\StoreGroupRequest;
use App\Domains\Group\Http\Requests\UpdateGroupRequest;
use App\Shared\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class GroupController extends Controller
{
    public function __construct(
        private GroupService $groupService
    ) {}

    public function index(): JsonResponse
    {
        $groups = $this->groupService->getAllGroups();
        
        return response()->json($groups);
    }

    public function store(StoreGroupRequest $request): JsonResponse
    {
        $group = $this->groupService->createGroup($request->validated());
        
        return response()->json($group, 201);
    }

    public function show(int $id): JsonResponse
    {
        $group = $this->groupService->getGroupById($id);
        
        if (!$group) {
            abort(404);
        }

        // Carregar relacionamentos
        $group->load('projects.customers');
        
        return response()->json($group);
    }

    public function update(UpdateGroupRequest $request, int $id): JsonResponse
    {
        $group = $this->groupService->updateGroup($id, $request->validated());
        
        if (!$group) {
            abort(404);
        }
        
        return response()->json($group);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->groupService->deleteGroup($id);
        
        if (!$deleted) {
            abort(404);
        }
        
        return response()->json(null, 204);
    }

    public function active(): JsonResponse
    {
        $groups = $this->groupService->getActiveGroups();
        
        return response()->json(['data' => $groups]);
    }
} 