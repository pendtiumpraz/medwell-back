<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $query = Organization::withCount(['users', 'facilities']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $organizations = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('super-admin.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('admin.organizations.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:organizations',
            'type' => 'required|in:hospital,clinic,pharmacy,laboratory,other',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $organization = Organization::create($request->all());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($organization)
            ->log('Organization created');

        return redirect()->route('super-admin.organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    public function show($id)
    {
        $organization = Organization::withCount(['users', 'patients', 'facilities'])
            ->findOrFail($id);

        $recentUsers = $organization->users()->latest()->take(10)->get();
        $recentPatients = $organization->patients()->latest()->take(10)->get();

        return view('super-admin.organizations.show', compact('organization', 'recentUsers', 'recentPatients'));
    }

    /**
     * Get organization data as JSON (for AJAX modal edit)
     */
    public function getJson($id)
    {
        $organization = Organization::findOrFail($id);
        
        return response()->json([
            'id' => $organization->id,
            'name' => $organization->name,
            'code' => $organization->code,
            'type' => $organization->type,
            'email' => $organization->email,
            'phone' => $organization->phone,
            'address' => $organization->address,
            'city' => $organization->city,
            'state' => $organization->state,
            'country' => $organization->country,
            'postal_code' => $organization->postal_code,
            'website' => $organization->website,
            'status' => $organization->status,
        ]);
    }

    public function edit($id)
    {
        $organization = Organization::findOrFail($id);
        return view('admin.organizations.edit', compact('organization'));
    }

    public function update(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:organizations,code,' . $id,
            'type' => 'required|in:hospital,clinic,pharmacy,laboratory,other',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $organization->update($request->all());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($organization)
            ->log('Organization updated');

        return redirect()->route('super-admin.organizations.index')
            ->with('success', 'Organization updated successfully.');
    }

    public function destroy($id)
    {
        $organization = Organization::findOrFail($id);

        if ($organization->users()->count() > 0) {
            return back()->with('error', 'Cannot delete organization with existing users.');
        }

        $organization->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($organization)
            ->log('Organization deleted');

        return redirect()->route('super-admin.organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }
}
