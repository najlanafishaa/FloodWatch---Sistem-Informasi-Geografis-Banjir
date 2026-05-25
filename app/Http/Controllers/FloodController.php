<?php

namespace App\Http\Controllers;

use App\Models\Flood;
use App\Models\Region;
use Illuminate\Http\Request;

class FloodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Flood::with('region');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('location_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('region', function($rq) use ($search) {
                      $rq->where('name', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        $floods = $query->orderBy('reported_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.floods.index', compact('floods', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regions = Region::all();
        return view('admin.floods.create', compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'location_name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status' => 'required|in:Aman,Waspada,Siaga,Awas',
            'water_level' => 'required|integer|min:0',
            'affected_population' => 'required|integer|min:0',
            'weather' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'reported_at' => 'required|date',
        ]);

        Flood::create($request->all());

        return redirect()->route('admin.floods.index')
            ->with('success', 'Data titik banjir baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flood $flood)
    {
        $regions = Region::all();
        return view('admin.floods.edit', compact('flood', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flood $flood)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'location_name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status' => 'required|in:Aman,Waspada,Siaga,Awas',
            'water_level' => 'required|integer|min:0',
            'affected_population' => 'required|integer|min:0',
            'weather' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'reported_at' => 'required|date',
        ]);

        $flood->update($request->all());

        return redirect()->route('admin.floods.index')
            ->with('success', 'Data titik banjir berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flood $flood)
    {
        $flood->delete();

        return redirect()->route('admin.floods.index')
            ->with('success', 'Data titik banjir berhasil dihapus.');
    }
}
