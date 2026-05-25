<?php

namespace App\Http\Controllers;

use App\Models\Flood;
use App\Models\Region;
use App\Models\GeojsonLayer;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display the public landing page with rich statistics.
     */
    public function landing()
    {
        // 1. Total Active Flood Incidents (Waspada, Siaga, Awas)
        $totalActiveFloods = Flood::whereIn('status', ['Waspada', 'Siaga', 'Awas'])->count();

        // 2. Total Affected Population
        $totalAffectedPopulation = Flood::whereIn('status', ['Waspada', 'Siaga', 'Awas'])->sum('affected_population');

        // 3. Most Affected Region
        $regions = Region::with(['floods' => function($q) {
            $q->whereIn('status', ['Waspada', 'Siaga', 'Awas']);
        }])->get();
        
        $mostAffectedRegion = null;
        $maxAffected = -1;
        
        foreach ($regions as $r) {
            $affectedSum = $r->floods->sum('affected_population');
            if ($affectedSum > $maxAffected && $r->floods->count() > 0) {
                $maxAffected = $affectedSum;
                $mostAffectedRegion = $r;
            }
        }

        // 4. Recent Flood Updates (latest 3 reports)
        $recentUpdates = Flood::with('region')
            ->orderBy('reported_at', 'desc')
            ->take(3)
            ->get();

        return view('landing', compact('totalActiveFloods', 'totalAffectedPopulation', 'mostAffectedRegion', 'recentUpdates', 'regions'));
    }

    /**
     * Display the interactive GIS map page.
     */
    public function map()
    {
        $regions = Region::all();
        return view('map', compact('regions'));
    }

    /**
     * API to fetch flood locations based on filters.
     */
    public function apiFloods(Request $request)
    {
        $query = Flood::with('region');

        // Filter by Region
        if ($request->has('region_id') && !empty($request->region_id)) {
            $query->where('region_id', $request->region_id);
        }

        // Filter by Status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by Search text
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('location_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        $floods = $query->orderBy('reported_at', 'desc')->get();

        return response()->json($floods);
    }

    /**
     * API to fetch all active GeoJSON layer files.
     */
    public function apiGeojson()
    {
        $layers = GeojsonLayer::where('is_active', true)->get()->map(function ($layer) {
            return [
                'id' => $layer->id,
                'name' => $layer->name,
                'url' => asset('geojson/' . $layer->filename)
            ];
        });

        return response()->json($layers);
    }
}
