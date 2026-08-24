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

        $stats = $ppDataModel->getSummaryStats();
        
        // Latest candidates
        $candidates = $ppDataModel->getWithProgres();

        $data = [
            'title'      => 'Dashboard - Peduli Pensiun',
            'activeMenu' => 'dashboard',
            'stats'      => $stats,
            'candidates' => array_slice($candidates, 0, 5),
        ];

        return view('dashboard/index', $data);
    }
}
