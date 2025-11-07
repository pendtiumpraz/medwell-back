<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Facility;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::with(['facility.organization']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $departments = $query->orderBy('created_at', 'desc')->paginate(20);
        $facilities = Facility::where('status', 'active')->with('organization')->get();

        return view('super-admin.departments.index', compact('departments', 'facilities'));
    }

    public function create()
    {
        $facilities = Facility::where('status', 'active')->with('organization')->get();
        return view('admin.departments.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facility_id' => 'required|exists:facilities,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments',
            'head_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $department = Department::create($request->all());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($department)
            ->log('Department created');

        return redirect()->route('super-admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function show($id)
    {
        $department = Department::with(['facility.organization', 'users'])->findOrFail($id);
        return view('super-admin.departments.show', compact('department'));
    }

    public function getJson($id)
    {
        $department = Department::findOrFail($id);
        return response()->json([
            'id' => $department->id,
            'facility_id' => $department->facility_id,
            'name' => $department->name,
            'code' => $department->code,
            'head_name' => $department->head_name,
            'phone' => $department->phone,
            'email' => $department->email,
            'description' => $department->description,
            'status' => $department->status,
        ]);
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $facilities = Facility::where('status', 'active')->with('organization')->get();
        return view('admin.departments.edit', compact('department', 'facilities'));
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'facility_id' => 'required|exists:facilities,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code,' . $id,
            'head_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $department->update($request->all());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($department)
            ->log('Department updated');

        return redirect()->route('super-admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($department)
            ->log('Department deleted');

        return redirect()->route('super-admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
