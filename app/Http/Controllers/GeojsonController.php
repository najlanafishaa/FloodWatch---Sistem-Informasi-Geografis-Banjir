<?php

namespace App\Http\Controllers;

use App\Models\GeojsonLayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GeojsonController extends Controller
{
    /**
     * Display the listing of uploaded GeoJSON files.
     */
    public function index()
    {
        $layers = GeojsonLayer::orderBy('created_at', 'desc')->get();
        return view('admin.geojson.index', compact('layers'));
    }

    /**
     * Upload and register a new GeoJSON layer.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Accept .geojson and .json files via extension and common mime types
            'geojson_file' => 'required|file|mimes:geojson,json|mimetypes:application/json,application/geo+json,text/plain|max:10240', // Max 10MB
        ], [
            'geojson_file.required' => 'Berkas GeoJSON wajib diunggah.',
            'geojson_file.mimes' => 'Format berkas harus .geojson atau .json.',
            'geojson_file.mimetypes' => 'Format berkas harus berupa format GeoJSON/JSON yang valid.',
            'geojson_file.max' => 'Ukuran berkas tidak boleh melebihi 10MB.',
        ]);

        $file = $request->file('geojson_file');

        // Check if file is valid JSON
        $content = file_get_contents($file->getRealPath());
        json_decode($content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['geojson_file' => 'Berkas tidak valid. Isi berkas harus berupa format GeoJSON/JSON yang valid.']);
        }

        // Ensure target directory exists in public/geojson
        $targetDir = public_path('geojson');
        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        // Generate clean file name
        $extension = $file->getClientOriginalExtension();
        if (empty($extension)) {
            $extension = 'geojson';
        }
        $filename = time() . '_' . uniqid() . '.' . $extension;

        // Move to public/geojson
        $file->move($targetDir, $filename);

        // Save metadata to DB
        GeojsonLayer::create([
            'name' => $request->name,
            'filename' => $filename,
            'is_active' => true
        ]);

        return redirect()->route('admin.geojson.index')
            ->with('success', 'Berkas GeoJSON berhasil diunggah dan diaktifkan.');
    }

    /**
     * Toggle the active status of a GeoJSON layer.
     */
    public function toggleActive(GeojsonLayer $geojsonLayer)
    {
        $geojsonLayer->is_active = !$geojsonLayer->is_active;
        $geojsonLayer->save();

        $status = $geojsonLayer->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.geojson.index')
            ->with('success', "Layer \"{$geojsonLayer->name}\" berhasil {$status}.");
    }

    /**
     * Remove the specified GeoJSON layer and its physical file.
     */
    public function destroy(GeojsonLayer $geojsonLayer)
    {
        // Delete physical file
        $filePath = public_path('geojson/' . $geojsonLayer->filename);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Delete from database
        $geojsonLayer->delete();

        return redirect()->route('admin.geojson.index')
            ->with('success', 'Layer GeoJSON berhasil dihapus.');
    }
}
