<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\IpMapping;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventarisController extends Controller
{
    /**
     * Display a listing of the assets.
     */
    public function index()
    {
        $search = request('search');

        $assets = Asset::with(['ipMapping', 'maintenances'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('ipMapping', function ($sub) use ($search) {
                            $sub->where('ip_address', 'like', "%{$search}%")
                                ->orWhere('display_name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('category', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->appends(['search' => $search]);

        return view('inventaris.index', compact('assets', 'search'));
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create()
    {
        // Only non-computer assets can be manually created via this form
        // Computer assets are created via IP Mapping
        return view('inventaris.create');
    }

    /**
     * Store a newly created asset in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,broken,disposed',
            'remote_app_name' => 'nullable|string|max:255',
            'remote_address' => 'nullable|string|max:255',
            'remote_password' => 'nullable|string|max:255',
        ]);

        Asset::create([
            'category' => 'non-computer',
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'status' => $request->status,
            'remote_app_name' => $request->remote_app_name,
            'remote_address' => $request->remote_address,
            'remote_password' => $request->remote_password,
        ]);

        return redirect()->route('inventaris.index')->with('status', 'Asset created successfully!');
    }

    /**
     * Display the specified asset.
     */
    public function show(Asset $asset)
    {
        $asset->load(['ipMapping']);
        $pendingMaintenances = $asset->maintenances()
            ->pending()
            ->orderBy('maintenance_date', 'asc')
            ->get();
            
        $completedMaintenances = $asset->maintenances()
            ->with('performedByUser')
            ->completed()
            ->latest('maintenance_date')
            ->paginate(10);

        return view('inventaris.show', compact('asset', 'pendingMaintenances', 'completedMaintenances'));
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Asset $asset)
    {
        if ($asset->category === 'computer') {
            return redirect()->route('ip-mappings.edit', $asset->ip_mapping_id)
                ->with('info', 'Data komputer ini dikelola melalui halaman Mapping IP.');
        }

        return view('inventaris.edit', compact('asset'));
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'name' => $asset->category === 'non-computer' ? 'required|string|max:255' : 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,broken,disposed',
            'remote_app_name' => 'nullable|string|max:255',
            'remote_address' => 'nullable|string|max:255',
            'remote_password' => 'nullable|string|max:255',
        ]);

        $asset->update($request->only([
            'name',
            'description',
            'location',
            'status',
            'remote_app_name',
            'remote_address',
            'remote_password'
        ]));

        // Sync back to IpMapping if it's a computer asset
        if ($asset->category === 'computer' && $asset->ipMapping) {
            $asset->ipMapping->update(['location' => $asset->location]);
        }

        return redirect()->route('inventaris.show', $asset->id)->with('status', 'Asset updated successfully!');
    }

    /**
     * Remove the specified asset from storage.
     */
    public function destroy(Asset $asset)
    {
        if ($asset->category === 'computer') {
            // For computers, we should probably unmark them as hospital asset in IP Mapping
            if ($asset->ipMapping) {
                $asset->ipMapping->update(['is_hospital_asset' => false]);
            }
            // The model event in IpMapping will delete the asset record
        } else {
            $asset->delete();
        }

        return redirect()->route('inventaris.index')->with('status', 'Asset removed successfully!');
    }

    /**
     * Export assets to PDF.
     */
    public function exportPdf(Request $request)
    {
        $search = $request->query('search');

        $assets = Asset::with(['ipMapping', 'maintenances'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('ipMapping', function ($sub) use ($search) {
                            $sub->where('ip_address', 'like', "%{$search}%")
                                ->orWhere('display_name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('category', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $pdf = Pdf::loadView('inventaris.pdf', compact('assets', 'search'))->setPaper('a4', 'landscape');
        return $pdf->download('Data_Inventaris_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export assets to Excel (HTML format).
     */
    public function exportExcel(Request $request)
    {
        $search = $request->query('search');

        $assets = Asset::with(['ipMapping', 'maintenances'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('ipMapping', function ($sub) use ($search) {
                            $sub->where('ip_address', 'like', "%{$search}%")
                                ->orWhere('display_name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('category', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->view('inventaris.excel', compact('assets', 'search'))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="Data_Inventaris_' . date('Y-m-d') . '.xls"');
    }
}
