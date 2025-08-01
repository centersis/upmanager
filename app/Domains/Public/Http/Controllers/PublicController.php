<?php

namespace App\Domains\Public\Http\Controllers;

use App\Domains\Project\Entities\Project;
use App\Domains\Update\Entities\Update;
use App\Domains\Customer\Entities\Customer;
use App\Shared\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PublicController extends Controller
{
    /**
     * Set locale based on lang parameter
     */
    private function setLocaleFromParameter($lang = null)
    {
        $supportedLocales = ['en', 'pt_BR'];
        
        if ($lang) {
            // Normalize pt-br and pt-BR to pt_BR
            if (strtolower($lang) === 'pt-br' || $lang === 'pt-BR') {
                $lang = 'pt_BR';
            }
            
            if (in_array($lang, $supportedLocales)) {
                App::setLocale($lang);
            }
        }
    }

    /**
     * Display all updates for a project using its hash with pagination
     */
    public function projectUpdates(Request $request, $projectHash, $lang = null)
    {
        $this->setLocaleFromParameter($lang);
        
        $project = Project::where('hash', $projectHash)
            ->with(['customers', 'group'])
            ->firstOrFail();

        $updates = Update::where('project_id', $project->id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('public::project-updates', compact('project', 'updates'));
    }

    /**
     * Display a specific update using its hash
     */
    public function updateDetail(Request $request, $updateHash, $lang = null)
    {
        $this->setLocaleFromParameter($lang);
        
        $update = Update::where('hash', $updateHash)
            ->where('status', 'published')
            ->with(['project.customers', 'project.group', 'customer'])
            ->firstOrFail();

        // Increment view count
        $update->increment('views');

        return view('public::update-detail', compact('update'));
    }

    /**
     * Display updates for a specific customer's projects with pagination
     */
    public function customerUpdates(Request $request, $customerHash, $lang = null)
    {
        $this->setLocaleFromParameter($lang);
        
        // First, find the customer
        $customer = Customer::where('hash', $customerHash)->firstOrFail();
        
        // Find projects that belong to this customer
        $projects = Project::whereHas('customers', function ($query) use ($customerHash) {
            $query->where('hash', $customerHash);
        })->with(['customers', 'group'])->get();

        if ($projects->isEmpty()) {
            abort(404, __('projects.customer_not_found'));
        }

        // Get all updates for these projects with pagination
        $updates = Update::whereIn('project_id', $projects->pluck('id'))
            ->where('status', 'published')
            ->with(['project', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('public::customer-updates', compact('updates', 'customer', 'projects'));
    }

    /**
     * Display updates for a specific project of a specific customer
     */
    public function customerProjectUpdates(Request $request, $customerHash, $projectHash, $lang = null)
    {
        $this->setLocaleFromParameter($lang);
        
        // Find the customer
        $customer = Customer::where('hash', $customerHash)->firstOrFail();
        
        // Find the project and verify it belongs to the customer
        $project = Project::where('hash', $projectHash)
            ->whereHas('customers', function ($query) use ($customerHash) {
                $query->where('hash', $customerHash);
            })
            ->with(['customers', 'group'])
            ->firstOrFail();

        // Get updates for this specific project filtered by customer
        $updates = Update::where('project_id', $project->id)
            ->where('status', 'published')
            ->where('customer_id', $customer->id)
            ->with(['project', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('public::customer-project-updates', compact('updates', 'customer', 'project'));
    }

    /**
     * Display iframe with last 5 updates for a specific project/customer combination
     */
    public function iframe(Request $request, $customerHash, $projectHash, $lang = null)
    {
        $this->setLocaleFromParameter($lang);
        
        // Find the customer
        $customer = Customer::where('hash', $customerHash)->firstOrFail();
        
        // Find the project and verify it belongs to the customer
        $project = Project::where('hash', $projectHash)
            ->whereHas('customers', function ($query) use ($customerHash) {
                $query->where('hash', $customerHash);
            })
            ->with(['customers', 'group'])
            ->firstOrFail();

        // Get last 5 updates for this specific project/customer
        $updates = Update::where('project_id', $project->id)
            ->where('status', 'published')
            ->where('customer_id', $customer->id)
            ->with(['project', 'customer'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('public::iframe', compact('updates', 'customer', 'project'));
    }
} 