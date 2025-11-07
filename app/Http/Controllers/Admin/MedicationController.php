<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Medication::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('generic_name', 'like', "%{$request->search}%")
                  ->orWhere('brand_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $medications = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('super-admin.medications.index', compact('medications'));
    }

    public function create()
    {
        return view('admin.medications.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'route' => 'nullable|string|max:100',
            'requires_prescription' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $medication = Medication::create($request->all());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($medication)
            ->log('Medication created');

        return redirect()->route('super-admin.medications.index')
            ->with('success', 'Medication created successfully.');
    }

    public function show($id)
    {
        $medication = Medication::findOrFail($id);
        return view('super-admin.medications.show', compact('medication'));
    }

    public function getJson($id)
    {
        $medication = Medication::findOrFail($id);
        return response()->json([
            'id' => $medication->id,
            'name' => $medication->name,
            'generic_name' => $medication->generic_name,
            'brand_name' => $medication->brand_name,
            'manufacturer' => $medication->manufacturer,
            'category' => $medication->category,
            'description' => $medication->description,
            'route' => $medication->route,
            'requires_prescription' => $medication->requires_prescription,
            'status' => $medication->status,
        ]);
    }

    public function edit($id)
    {
        $medication = Medication::findOrFail($id);
        return view('admin.medications.edit', compact('medication'));
    }

    public function update(Request $request, $id)
    {
        $medication = Medication::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'route' => 'nullable|string|max:100',
            'requires_prescription' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $medication->update($request->all());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($medication)
            ->log('Medication updated');

        return redirect()->route('super-admin.medications.index')
            ->with('success', 'Medication updated successfully.');
    }

    public function destroy($id)
    {
        $medication = Medication::findOrFail($id);
        $medication->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($medication)
            ->log('Medication deleted');

        return redirect()->route('super-admin.medications.index')
            ->with('success', 'Medication deleted successfully.');
    }
}
