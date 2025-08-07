<?php

namespace App\Domains\Update\Http\Controllers;

use App\Shared\Http\Controllers\Controller;
use App\Domains\Update\Http\Requests\StoreUpdateApiRequest;
use App\Domains\Update\Http\Requests\UpdateUpdateApiRequest;
use App\Domains\Update\Services\UpdateService;
use App\Domains\Project\Services\ProjectService;
use App\Domains\Customer\Services\CustomerService;
use App\Domains\Update\Entities\Update;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UpdateApiController extends Controller
{
    public function __construct(
        private UpdateService $updateService,
        private ProjectService $projectService,
        private CustomerService $customerService
    ) {}

    /**
     * Display a listing of updates
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = min($request->get('per_page', 15), 100); // Max 100 items per page
            $status = $request->get('status');
            $projectId = $request->get('project_id');
            $customerId = $request->get('customer_id');
            // is_global functionality was removed from the system
            $search = $request->get('search');

            $query = Update::with(['project', 'customer'])
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($status) {
                $query->where('status', $status);
            }

            if ($projectId) {
                $query->where('project_id', $projectId);
            }

            if ($customerId) {
                $query->where('customer_id', $customerId);
            }

            // is_global functionality was removed from the system

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('caption', 'like', "%{$search}%");
                });
            }

            $updates = $query->paginate($perPage);

            // Transform the data
            $updates->getCollection()->transform(function ($update) {
                return [
                    'id' => $update->id,
                    'title' => $update->title,
                    'caption' => $update->caption,
                    'description' => $update->description,
                    'status' => $update->status,
                    'views' => $update->views,
                    'hash' => $update->hash,
                    'created_at' => $update->created_at->toISOString(),
                    'updated_at' => $update->updated_at->toISOString(),
                    'project' => $update->project ? [
                        'id' => $update->project->id,
                        'name' => $update->project->name,
                    ] : null,
                    'customer' => $update->customer ? [
                        'id' => $update->customer->id,
                        'name' => $update->customer->name,
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $updates->items(),
                'pagination' => [
                    'current_page' => $updates->currentPage(),
                    'last_page' => $updates->lastPage(),
                    'per_page' => $updates->perPage(),
                    'total' => $updates->total(),
                    'from' => $updates->firstItem(),
                    'to' => $updates->lastItem(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar atualizações',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a newly created update
     * 
     * @param StoreUpdateApiRequest $request
     * @return JsonResponse
     */
    public function store(StoreUpdateApiRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            // Generate hash if not provided
            if (!isset($data['hash'])) {
                $data['hash'] = Str::uuid()->toString();
            }

            // Set default status if not provided
            if (!isset($data['status'])) {
                $data['status'] = 'pending';
            }

            $update = $this->updateService->createUpdate($data);
            $update->load(['project', 'customer']);

            return response()->json([
                'success' => true,
                'message' => 'Atualização criada com sucesso',
                'data' => [
                    'id' => $update->id,
                    'title' => $update->title,
                    'caption' => $update->caption,
                    'description' => $update->description,
                    'status' => $update->status,
                    'views' => $update->views,
                    'hash' => $update->hash,
                    'created_at' => $update->created_at->toISOString(),
                    'updated_at' => $update->updated_at->toISOString(),
                    'project' => $update->project ? [
                        'id' => $update->project->id,
                        'name' => $update->project->name,
                    ] : null,
                    'customer' => $update->customer ? [
                        'id' => $update->customer->id,
                        'name' => $update->customer->name,
                    ] : null,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar atualização',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified update
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $update = $this->updateService->getUpdateById($id);

            if (!$update) {
                return response()->json([
                    'success' => false,
                    'message' => 'Atualização não encontrada'
                ], 404);
            }

            // Increment views
            $this->updateService->incrementUpdateViews($id);
            $update->refresh();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $update->id,
                    'title' => $update->title,
                    'caption' => $update->caption,
                    'description' => $update->description,
                    'status' => $update->status,
                    'views' => $update->views,
                    'hash' => $update->hash,
                    'created_at' => $update->created_at->toISOString(),
                    'updated_at' => $update->updated_at->toISOString(),
                    'project' => $update->project ? [
                        'id' => $update->project->id,
                        'name' => $update->project->name,
                        'description' => $update->project->description,
                        'status' => $update->project->status,
                    ] : null,
                    'customer' => $update->customer ? [
                        'id' => $update->customer->id,
                        'name' => $update->customer->name,
                        'email' => $update->customer->email,
                        'status' => $update->customer->status,
                    ] : null,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar atualização',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update the specified update
     * 
     * @param UpdateUpdateApiRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateUpdateApiRequest $request, int $id): JsonResponse
    {
        try {
            $update = Update::find($id);

            if (!$update) {
                return response()->json([
                    'success' => false,
                    'message' => 'Atualização não encontrada'
                ], 404);
            }

            $data = $request->validated();
            $updatedUpdate = $this->updateService->updateUpdate($id, $data);
            $updatedUpdate->load(['project', 'customer']);

            return response()->json([
                'success' => true,
                'message' => 'Atualização atualizada com sucesso',
                'data' => [
                    'id' => $updatedUpdate->id,
                    'title' => $updatedUpdate->title,
                    'caption' => $updatedUpdate->caption,
                    'description' => $updatedUpdate->description,
                    'status' => $updatedUpdate->status,
                    'views' => $updatedUpdate->views,
                    'hash' => $updatedUpdate->hash,
                    'created_at' => $updatedUpdate->created_at->toISOString(),
                    'updated_at' => $updatedUpdate->updated_at->toISOString(),
                    'project' => $updatedUpdate->project ? [
                        'id' => $updatedUpdate->project->id,
                        'name' => $updatedUpdate->project->name,
                    ] : null,
                    'customer' => $updatedUpdate->customer ? [
                        'id' => $updatedUpdate->customer->id,
                        'name' => $updatedUpdate->customer->name,
                    ] : null,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar atualização',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified update
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $update = Update::find($id);

            if (!$update) {
                return response()->json([
                    'success' => false,
                    'message' => 'Atualização não encontrada'
                ], 404);
            }

            $this->updateService->deleteUpdate($id);

            return response()->json([
                'success' => true,
                'message' => 'Atualização removida com sucesso'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover atualização',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get updates by project
     * 
     * @param Request $request
     * @param int $projectId
     * @return JsonResponse
     */
    public function byProject(Request $request, int $projectId): JsonResponse
    {
        try {
            $perPage = min($request->get('per_page', 15), 100);
            
            $updates = Update::where('project_id', $projectId)
                ->with(['project', 'customer'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $updates->getCollection()->transform(function ($update) {
                return [
                    'id' => $update->id,
                    'title' => $update->title,
                    'caption' => $update->caption,
                    'description' => $update->description,
                    'status' => $update->status,
                    'views' => $update->views,
                    'hash' => $update->hash,
                    'created_at' => $update->created_at->toISOString(),
                    'updated_at' => $update->updated_at->toISOString(),
                    'customer' => $update->customer ? [
                        'id' => $update->customer->id,
                        'name' => $update->customer->name,
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $updates->items(),
                'pagination' => [
                    'current_page' => $updates->currentPage(),
                    'last_page' => $updates->lastPage(),
                    'per_page' => $updates->perPage(),
                    'total' => $updates->total(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar atualizações do projeto',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get updates by customer
     * 
     * @param Request $request
     * @param int $customerId
     * @return JsonResponse
     */
    public function byCustomer(Request $request, int $customerId): JsonResponse
    {
        try {
            $perPage = min($request->get('per_page', 15), 100);
            
            $updates = Update::where('customer_id', $customerId)
                ->with(['project', 'customer'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $updates->getCollection()->transform(function ($update) {
                return [
                    'id' => $update->id,
                    'title' => $update->title,
                    'caption' => $update->caption,
                    'description' => $update->description,
                    'status' => $update->status,
                    'views' => $update->views,
                    'hash' => $update->hash,
                    'created_at' => $update->created_at->toISOString(),
                    'updated_at' => $update->updated_at->toISOString(),
                    'project' => $update->project ? [
                        'id' => $update->project->id,
                        'name' => $update->project->name,
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $updates->items(),
                'pagination' => [
                    'current_page' => $updates->currentPage(),
                    'last_page' => $updates->lastPage(),
                    'per_page' => $updates->perPage(),
                    'total' => $updates->total(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar atualizações do cliente',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get update by hash (public access)
     * 
     * @param string $hash
     * @return JsonResponse
     */
    public function byHash(string $hash): JsonResponse
    {
        try {
            $update = Update::where('hash', $hash)
                ->with(['project', 'customer'])
                ->first();

            if (!$update) {
                return response()->json([
                    'success' => false,
                    'message' => 'Atualização não encontrada'
                ], 404);
            }

            // Increment views
            $update->increment('views');

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $update->id,
                    'title' => $update->title,
                    'caption' => $update->caption,
                    'description' => $update->description,
                    'status' => $update->status,
                    'views' => $update->views,
                    'created_at' => $update->created_at->toISOString(),
                    'updated_at' => $update->updated_at->toISOString(),
                    'project' => $update->project ? [
                        'id' => $update->project->id,
                        'name' => $update->project->name,
                    ] : null,
                    'customer' => $update->customer ? [
                        'id' => $update->customer->id,
                        'name' => $update->customer->name,
                    ] : null,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar atualização',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get available options for creating updates
     * 
     * @return JsonResponse
     */
    public function options(): JsonResponse
    {
        try {
            $projects = $this->projectService->getAllProjects()->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'status' => $project->status,
                ];
            });

            $customers = $this->customerService->getAllCustomers()->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'status' => $customer->status,
                ];
            });

            $statuses = [
                ['value' => 'pending', 'label' => 'Pendente'],
                ['value' => 'published', 'label' => 'Publicado'],
                ['value' => 'draft', 'label' => 'Rascunho'],
                ['value' => 'archived', 'label' => 'Arquivado'],
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'projects' => $projects,
                    'customers' => $customers,
                    'statuses' => $statuses,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar opções',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }
}
