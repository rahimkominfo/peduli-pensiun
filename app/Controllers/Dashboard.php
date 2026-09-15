<?php

namespace App\Controllers;

use App\Models\PpDataModel;
use App\Models\PpProgresDataModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $ppDataModel = new PpDataModel();
        $progresModel = new PpProgresDataModel();

        $unitId = is_admin_unit() ? (int) (session('unit_id') ?? 0) : null;

        $stats = $ppDataModel->getSummaryStats($unitId);
        
        // Latest candidates
        $filters = [];
        if ($unitId !== null && $unitId > 0) {
            $filters['unit_id'] = $unitId;
        }
        $candidates = $ppDataModel->getWithProgres($filters);

        $data = [
            'title'      => 'Dashboard - Peduli Pensiun',
            'activeMenu' => 'dashboard',
            'stats'      => $stats,
            'candidates' => array_slice($candidates, 0, 5),
        ];

        return view('dashboard/index', $data);
    }
}
