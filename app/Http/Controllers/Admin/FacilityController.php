<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::with('organization');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $facilities = $query->orderBy('created_at', 'desc')->paginate(20);
        $organizations = Organization::where('status', 'active')->get();

        return view('super-admin.facilities.index', compact('facilities', 'organizations'));
    }

    public function create()
    {
        $organizations = Organization::where('status', 'active')->get();
        return view('admin.facilities.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $facility = Facility::create($request->all());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($facility)
            ->log('Facility created');

        return redirect()->route('super-admin.facilities.index')
            ->with('success', 'Facility created successfully.');
    }

    public function show($id)
    {
        $facility = Facility::with(['organization', 'departments'])->findOrFail($id);
        return view('super-admin.facilities.show', compact('facility'));
    }

    /**
     * Get facility data as JSON (for AJAX modal edit)
     */
    public function getJson($id)
    {
        $facility = Facility::with('organization')->findOrFail($id);
        
        return response()->json([
            'id' => $facility->id,
            'organization_id' => $facility->organization_id,
            'name' => $facility->name,
            'type' => $facility->type,
            'phone' => $facility->phone,
            'address' => $facility->address,
            'status' => $facility->status,
        ]);
    }

    public function edit($id)
    {
        $facility = Facility::findOrFail($id);
        $organizations = Organization::where('status', 'active')->get();
        return view('admin.facilities.edit', compact('facility', 'organizations'));
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $facility->update($request->all());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($facility)
            ->log('Facility updated');

        return redirect()->route('super-admin.facilities.index')
            ->with('success', 'Facility updated successfully.');
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);
        $facility->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($facility)
            ->log('Facility deleted');

        return redirect()->route('super-admin.facilities.index')
            ->with('success', 'Facility deleted successfully.');
    }
}
