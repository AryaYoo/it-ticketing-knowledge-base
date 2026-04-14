<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpMapping;
use App\Models\User;
use Illuminate\Http\Request;

class IpMappingController extends Controller
{
    /**
     * Display a listing of IP mappings.
     */
    public function index()
    {
        $search = request('search');

        $ipMappings = IpMapping::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends(['search' => $search]);

        return view('admin.ip_mappings.index', compact('ipMappings', 'search'));
    }

    /**
     * Show the form for creating a new IP mapping.
     */
    public function create()
    {
        return view('admin.ip_mappings.create');
    }

    /**
     * Store a newly created IP mapping.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => [
                'required',
                'string',
                'unique:ip_mappings,ip_address',
                'regex:/^(192\.168\.100\.\d{1,3}|127\.0\.0\.1)$/',
            ],
            'display_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'is_hospital_asset' => ['boolean'],
        ], [
            'ip_address.regex' => 'IP address must be in format 192.168.100.x',
        ]);

        // Validate IP range (1-254)
        $lastOctet = (int) substr($request->ip_address, strrpos($request->ip_address, '.') + 1);
        if ($lastOctet < 1 || $lastOctet > 254) {
            return back()->withErrors(['ip_address' => 'Last octet must be between 1 and 254.'])->withInput();
        }

        $ipMapping = IpMapping::create([
            'ip_address' => $request->ip_address,
            'display_name' => $request->display_name,
            'location' => $request->location,
            'is_active' => $request->has('is_active'),
            'is_hospital_asset' => $request->has('is_hospital_asset'),
        ]);

        return redirect()->route('ip-mappings.index')->with('status', 'IP mapping created successfully!');
    }

    /**
     * Show the form for editing the specified IP mapping.
     */
    public function edit($id)
    {
        $ipMapping = IpMapping::with('user')->findOrFail($id);
        return view('admin.ip_mappings.edit', compact('ipMapping'));
    }

    /**
     * Update the specified IP mapping.
     */
    public function update(Request $request, $id)
    {
        $ipMapping = IpMapping::findOrFail($id);

        $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'is_hospital_asset' => ['boolean'],
        ]);

        $ipMapping->update([
            'display_name' => $request->display_name,
            'location' => $request->location,
            'is_active' => $request->has('is_active'),
            'is_hospital_asset' => $request->has('is_hospital_asset'),
        ]);

        // Update associated user's name if exists
        if ($ipMapping->user) {
            $ipMapping->user->update(['name' => $request->display_name]);
        }

        return redirect()->route('ip-mappings.index')->with('status', 'IP mapping updated successfully!');
    }

    /**
     * Remove the specified IP mapping.
     */
    public function destroy($id)
    {
        $ipMapping = IpMapping::findOrFail($id);

        // Delete associated user if it's an IP-based user
        if ($ipMapping->user && $ipMapping->user->isIpBasedUser()) {
            $ipMapping->user->delete();
        }

        $ipMapping->delete();

        return redirect()->route('ip-mappings.index')->with('status', 'IP mapping deleted successfully!');
    }
}
