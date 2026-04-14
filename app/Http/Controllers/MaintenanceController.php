<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the maintenance schedules and history.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $pendingMaintenances = Maintenance::with('asset', 'performedByUser')
            ->pending()
            ->orderBy('maintenance_date', 'asc')
            ->get();

        $completedMaintenances = Maintenance::with('asset', 'performedByUser')
            ->completed()
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('maintenance_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('maintenance_date', '<=', $endDate);
            })
            ->orderBy('maintenance_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('maintenances.index', compact('pendingMaintenances', 'completedMaintenances', 'startDate', 'endDate'));
    }

    /**
     * Export maintenance history to PDF.
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $maintenances = Maintenance::with('asset', 'performedByUser')
            ->completed()
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('maintenance_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('maintenance_date', '<=', $endDate);
            })
            ->orderBy('maintenance_date', 'asc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('maintenances.pdf', compact('maintenances', 'startDate', 'endDate'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('maintenance-history-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export maintenance history to Excel (HTML format).
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $maintenances = Maintenance::with('asset', 'performedByUser')
            ->completed()
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('maintenance_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('maintenance_date', '<=', $endDate);
            })
            ->orderBy('maintenance_date', 'asc')
            ->get();

        $filename = 'maintenance-history-' . now()->format('Y-m-d') . '.xls';

        return response()->view('maintenances.excel', compact('maintenances', 'startDate', 'endDate'))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    /**
     * Show the form for creating a new maintenance log.
     */
    public function create(Asset $asset = null)
    {
        $assets = Asset::orderBy('name')->get();
        return view('maintenances.create', compact('asset', 'assets'));
    }

    /**
     * Store a newly created maintenance log in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'title' => 'nullable|string|max:255',
            'maintenance_date' => 'required|date',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,completed',
        ]);

        Maintenance::create([
            'asset_id' => $request->asset_id,
            'title' => $request->title,
            'maintenance_date' => $request->maintenance_date,
            'description' => $request->description,
            'performed_by' => auth()->id(),
            'cost' => $request->cost ?? 0,
            'status' => $request->status,
        ]);

        $message = $request->status === 'pending' ? 'Maintenance scheduled successfully!' : 'Maintenance record added successfully!';

        return redirect()->route('inventaris.show', $request->asset_id)
            ->with('status', $message);
    }

    /**
     * Mark a maintenance as completed.
     */
    public function complete(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'cost' => 'nullable|numeric|min:0',
            'description' => 'required|string',
        ]);

        $maintenance->update([
            'status' => 'completed',
            'cost' => $request->cost ?? 0,
            'description' => $request->description,
            'maintenance_date' => now(), // Update to completion date
            'performed_by' => auth()->id(),
        ]);

        return redirect()->back()->with('status', 'Maintenance schedule marked as completed!');
    }

    /**
     * Remove the specified maintenance from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->back()->with('status', 'Maintenance record deleted successfully!');
    }
}
