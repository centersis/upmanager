<?php

namespace App\Domains\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Customer\Services\CustomerService;
use App\Domains\Project\Services\ProjectService;
use App\Domains\Update\Services\UpdateService;

class DashboardController extends Controller
{
    public function __construct(
        private CustomerService $customerService,
        private ProjectService $projectService,
        private UpdateService $updateService
    ) {}

    public function index()
    {
        $stats = [
            'customers' => $this->customerService->getAllCustomers()->count(),
            'projects' => $this->projectService->getAllProjects()->count(),
            'updates' => $this->updateService->getAllUpdates()->count(),
            'active_customers' => $this->customerService->getActiveCustomers()->count(),
        ];

        $recentUpdates = $this->updateService->getAllUpdates()
            ->sortByDesc('created_at')
            ->take(5);

        return view('dashboard::index', compact('stats', 'recentUpdates'));
    }
} 