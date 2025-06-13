<?php

namespace App\Http\Controllers;

use App\Repositories\GuruDashboardRepository;
use Illuminate\Http\Request;

class GuruDashboardController extends Controller
{
    protected $guruDashboardRepository;

    public function __construct(GuruDashboardRepository $guruDashboardRepository)
    {
        $this->guruDashboardRepository = $guruDashboardRepository;
    }

    public function index(Request $request)
    {
        $tahunAjaran = $request->get('tahun_ajaran', 'all');
        $kelas = $request->get('kelas', 'all');
        $quizStatus = $request->get('quiz_status', 'all');
        
        $dashboard = $this->guruDashboardRepository->getDashboardData($tahunAjaran, $kelas, $quizStatus);
        
        return view('pages.role_admin.guru_dashboard.index', compact('dashboard'));
    }
} 