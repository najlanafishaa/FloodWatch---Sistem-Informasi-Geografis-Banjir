<?php

namespace App\Http\Controllers;

use App\Models\Flood;
use App\Models\Region;
use App\Models\GeojsonLayer;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard Overview.
     */
    public function index()
    {
        // Totals
        $totalRegions = Region::count();
        $totalFloods = Flood::count();
        $activeAlerts = Flood::whereIn('status', ['Waspada', 'Siaga', 'Awas'])->count();
        $activeGeojsonLayers = GeojsonLayer::where('is_active', true)->count();

        // Status Breakdown
        $statusBreakdown = [
            'Awas' => Flood::where('status', 'Awas')->count(),
            'Siaga' => Flood::where('status', 'Siaga')->count(),
            'Waspada' => Flood::where('status', 'Waspada')->count(),
            'Aman' => Flood::where('status', 'Aman')->count(),
        ];

        // Region Breakdown
        $regions = Region::withCount('floods')->get();

        // Recent Activity (latest 5 reports)
        $recentFloods = Flood::with('region')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRegions',
            'totalFloods',
            'activeAlerts',
            'activeGeojsonLayers',
            'statusBreakdown',
            'regions',
            'recentFloods'
        ));
    }
}
