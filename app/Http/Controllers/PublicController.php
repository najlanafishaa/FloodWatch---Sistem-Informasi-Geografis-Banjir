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

    /**
     * API to fetch BPS administrative boundary GeoJSON data.
     * Scans the public/geojson directory for any .json or .geojson files
     * and returns them as available BPS boundary layers.
     */
    public function apiBpsGeojson()
    {
        $geojsonPath = public_path('geojson');
        $layers = [];

        if (is_dir($geojsonPath)) {
            $files = scandir($geojsonPath);
            foreach ($files as $file) {
                // Include all .json and .geojson files
                if (preg_match('/\.(json|geojson)$/i', $file)) {
                    $layers[] = [
                        'name' => pathinfo($file, PATHINFO_FILENAME),
                        'url' => asset('geojson/' . $file),
                        'filename' => $file,
                    ];
                }
            }
        }

        return response()->json($layers);
    }

    /**
     * API to fetch historical weather-flood statistics from the Kaggle dataset.
     */
    public function apiDatasetStats()
    {
        try {
            $totalRecords = \App\Models\WeatherFloodRecord::count();
            
            // Return empty stats if no data
            if ($totalRecords === 0) {
                return response()->json([
                    'total_records' => 0,
                    'flood_days' => 0,
                    'normal_days' => 0,
                    'avg_rainfall_flood' => 0,
                    'avg_rainfall_normal' => 0,
                    'avg_rainfall_1week_flood' => 0,
                    'avg_rainfall_1week_normal' => 0,
                    'avg_temp_flood' => 0,
                    'avg_temp_normal' => 0,
                    'avg_humidity_flood' => 0,
                    'avg_humidity_normal' => 0,
                    'monsoon_wind_flood' => [],
                    'recent_records' => []
                ]);
            }

            $floodDays = \App\Models\WeatherFloodRecord::where('flood_occurred', true)->count();
            $normalDays = $totalRecords - $floodDays;

            $avgRainfallFlood = \App\Models\WeatherFloodRecord::where('flood_occurred', true)->avg('rainfall') ?? 0;
            $avgRainfallNormal = \App\Models\WeatherFloodRecord::where('flood_occurred', false)->avg('rainfall') ?? 0;

            $avgRainfallOneWeekFlood = \App\Models\WeatherFloodRecord::where('flood_occurred', true)->avg('rainfall_one_week') ?? 0;
            $avgRainfallOneWeekNormal = \App\Models\WeatherFloodRecord::where('flood_occurred', false)->avg('rainfall_one_week') ?? 0;

            $avgTempFlood = \App\Models\WeatherFloodRecord::where('flood_occurred', true)->avg('avg_temperature') ?? 0;
            $avgTempNormal = \App\Models\WeatherFloodRecord::where('flood_occurred', false)->avg('avg_temperature') ?? 0;

            $avgHumidityFlood = \App\Models\WeatherFloodRecord::where('flood_occurred', true)->avg('avg_humidity') ?? 0;
            $avgHumidityNormal = \App\Models\WeatherFloodRecord::where('flood_occurred', false)->avg('avg_humidity') ?? 0;

            // Group by monsoon wind
            $monsoonWindFlood = \App\Models\WeatherFloodRecord::where('flood_occurred', true)
                ->selectRaw('monsoon_wind, count(*) as count')
                ->groupBy('monsoon_wind')
                ->get();

            // Get latest 10 historical records
            $recentRecords = \App\Models\WeatherFloodRecord::orderBy('date', 'desc')->take(10)->get();

            return response()->json([
                'total_records' => $totalRecords,
                'flood_days' => $floodDays,
                'normal_days' => $normalDays,
                'avg_rainfall_flood' => round($avgRainfallFlood, 2),
                'avg_rainfall_normal' => round($avgRainfallNormal, 2),
                'avg_rainfall_1week_flood' => round($avgRainfallOneWeekFlood, 2),
                'avg_rainfall_1week_normal' => round($avgRainfallOneWeekNormal, 2),
                'avg_temp_flood' => round($avgTempFlood, 2),
                'avg_temp_normal' => round($avgTempNormal, 2),
                'avg_humidity_flood' => round($avgHumidityFlood, 2),
                'avg_humidity_normal' => round($avgHumidityNormal, 2),
                'monsoon_wind_flood' => $monsoonWindFlood,
                'recent_records' => $recentRecords
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'total_records' => 0,
                'flood_days' => 0,
                'normal_days' => 0,
                'avg_rainfall_flood' => 0,
                'avg_rainfall_normal' => 0,
                'avg_rainfall_1week_flood' => 0,
                'avg_rainfall_1week_normal' => 0,
                'avg_temp_flood' => 0,
                'avg_temp_normal' => 0,
                'avg_humidity_flood' => 0,
                'avg_humidity_normal' => 0,
                'monsoon_wind_flood' => [],
                'recent_records' => []
            ]);
        }
    }

    /**
     * Check whether the Kaggle dataset has been imported into the database.
     * Instead of checking a hardcoded file path, we check if the WeatherFloodRecord table has data.
     */
    public function apiDatasetExists()
    {
        try {
            $hasData = \App\Models\WeatherFloodRecord::count() > 0;
            return response()->json(['exists' => $hasData]);
        } catch (\Exception $e) {
            return response()->json(['exists' => false]);
        }
    }
}
