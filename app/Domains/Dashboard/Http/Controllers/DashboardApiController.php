<?php

namespace App\Domains\Dashboard\Http\Controllers;

use App\Shared\Http\Controllers\Controller;
use App\Domains\Customer\Services\CustomerService;
use App\Domains\Project\Services\ProjectService;
use App\Domains\Update\Services\UpdateService;
use App\Domains\Group\Services\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function __construct(
        private CustomerService $customerService,
        private ProjectService $projectService,
        private UpdateService $updateService,
        private GroupService $groupService
    ) {}

    /**
     * Get dashboard statistics
     * 
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'customers' => [
                    'total' => $this->customerService->getAllCustomers()->count(),
                    'active' => $this->customerService->getActiveCustomers()->count(),
                    'inactive' => $this->customerService->getAllCustomers()->where('status', 'inactive')->count(),
                ],
                'projects' => [
                    'total' => $this->projectService->getAllProjects()->count(),
                    'active' => $this->projectService->getAllProjects()->where('status', 'active')->count(),
                    'completed' => $this->projectService->getAllProjects()->where('status', 'completed')->count(),
                    'on_hold' => $this->projectService->getAllProjects()->where('status', 'on_hold')->count(),
                ],
                'updates' => [
                    'total' => $this->updateService->getAllUpdates()->count(),
                    'this_month' => $this->updateService->getAllUpdates()
                        ->where('created_at', '>=', now()->startOfMonth())->count(),
                    'this_week' => $this->updateService->getAllUpdates()
                        ->where('created_at', '>=', now()->startOfWeek())->count(),
                ],
                'groups' => [
                    'total' => $this->groupService->getAllGroups()->count(),
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar estatísticas',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get recent updates for dashboard
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function recentUpdates(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $limit = min($limit, 50); // Max 50 items

            $updates = $this->updateService->getAllUpdates()
                ->sortByDesc('created_at')
                ->take($limit)
                ->map(function ($update) {
                    return [
                        'id' => $update->id,
                        'title' => $update->title,
                        'description' => $update->description,
                        'type' => $update->type,
                        'status' => $update->status,
                        'is_global' => $update->is_global,
                        'created_at' => $update->created_at->toISOString(),
                        'project' => $update->project ? [
                            'id' => $update->project->id,
                            'name' => $update->project->name,
                        ] : null,
                        'customer' => $update->customer ? [
                            'id' => $update->customer->id,
                            'name' => $update->customer->name,
                        ] : null,
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => $updates
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar atualizações recentes',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get recent projects for dashboard
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function recentProjects(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $limit = min($limit, 50); // Max 50 items

            $projects = $this->projectService->getAllProjects()
                ->sortByDesc('created_at')
                ->take($limit)
                ->map(function ($project) {
                    return [
                        'id' => $project->id,
                        'name' => $project->name,
                        'description' => $project->description,
                        'status' => $project->status,
                        'start_date' => $project->start_date?->toDateString(),
                        'end_date' => $project->end_date?->toDateString(),
                        'created_at' => $project->created_at->toISOString(),
                        'customers' => $project->customers->map(function ($customer) {
                            return [
                                'id' => $customer->id,
                                'name' => $customer->name,
                            ];
                        }),
                        'group' => $project->group ? [
                            'id' => $project->group->id,
                            'name' => $project->group->name,
                        ] : null,
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => $projects
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar projetos recentes',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get activity timeline for dashboard
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function activityTimeline(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $days = min($days, 365); // Max 1 year

            $startDate = now()->subDays($days);

            // Get updates activity
            $updates = $this->updateService->getAllUpdates()
                ->where('created_at', '>=', $startDate)
                ->map(function ($update) {
                    return [
                        'type' => 'update',
                        'title' => $update->title,
                        'description' => 'Nova atualização criada',
                        'date' => $update->created_at->toISOString(),
                        'project' => $update->project?->name,
                        'customer' => $update->customer?->name,
                    ];
                });

            // Get projects activity
            $projects = $this->projectService->getAllProjects()
                ->where('created_at', '>=', $startDate)
                ->map(function ($project) {
                    return [
                        'type' => 'project',
                        'title' => $project->name,
                        'description' => 'Novo projeto criado',
                        'date' => $project->created_at->toISOString(),
                        'project' => $project->name,
                        'customer' => $project->customers->pluck('name')->join(', '),
                    ];
                });

            // Get customers activity
            $customers = $this->customerService->getAllCustomers()
                ->where('created_at', '>=', $startDate)
                ->map(function ($customer) {
                    return [
                        'type' => 'customer',
                        'title' => $customer->name,
                        'description' => 'Novo cliente cadastrado',
                        'date' => $customer->created_at->toISOString(),
                        'project' => null,
                        'customer' => $customer->name,
                    ];
                });

            // Combine and sort activities
            $activities = collect()
                ->merge($updates)
                ->merge($projects)
                ->merge($customers)
                ->sortByDesc('date')
                ->take($request->get('limit', 20))
                ->values();

            return response()->json([
                'success' => true,
                'data' => $activities
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar timeline de atividades',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get dashboard overview with all main data
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function overview(Request $request): JsonResponse
    {
        try {
            // Get stats
            $statsResponse = $this->stats();
            $stats = $statsResponse->getData(true)['data'];

            // Get recent updates (limited)
            $recentUpdatesRequest = new Request(['limit' => 5]);
            $updatesResponse = $this->recentUpdates($recentUpdatesRequest);
            $recentUpdates = $updatesResponse->getData(true)['data'];

            // Get recent projects (limited)
            $recentProjectsRequest = new Request(['limit' => 5]);
            $projectsResponse = $this->recentProjects($recentProjectsRequest);
            $recentProjects = $projectsResponse->getData(true)['data'];

            // Get recent activity (limited)
            $activityRequest = new Request(['limit' => 10, 'days' => 7]);
            $activityResponse = $this->activityTimeline($activityRequest);
            $recentActivity = $activityResponse->getData(true)['data'];

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'recent_updates' => $recentUpdates,
                    'recent_projects' => $recentProjects,
                    'recent_activity' => $recentActivity,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar dados da dashboard',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }
}
