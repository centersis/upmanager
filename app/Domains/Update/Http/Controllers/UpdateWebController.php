<?php

namespace App\Domains\Update\Http\Controllers;

use App\Domains\Update\Services\UpdateService;
use App\Http\Controllers\Controller;

class UpdateWebController extends Controller
{
    public function __construct(
        private UpdateService $updateService
    ) {}

    public function index()
    {
        $updates = $this->updateService->getAllUpdates();
        
        return view('updates.index', compact('updates'));
    }

    public function show($id)
    {
        $update = $this->updateService->getUpdateById($id);
        
        if (!$update) {
            abort(404, 'Atualização não encontrada');
        }

        // Incrementar visualizações
        $this->updateService->incrementUpdateViews($id);
        
        return view('updates.show', compact('update'));
    }
} 