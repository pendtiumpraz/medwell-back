<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentApiController extends Controller
{
    /**
     * Get shared documents for patient
     */
    public function index(Request $request)
    {
        $patient = $request->user()->patientProfile;

        $documents = $patient->documents()
                            ->with('uploader')
                            ->when($request->category, function($q) use ($request) {
                                $q->where('category', $request->category);
                            })
                            ->orderBy('created_at', 'desc')
                            ->get();

        return response()->json([
            'success' => true,
            'data' => $documents
        ]);
    }

    /**
     * Get document detail
     */
    public function show($id, Request $request)
    {
        $patient = $request->user()->patientProfile;
        $document = $patient->documents()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $document
        ]);
    }

    /**
     * Download document
     */
    public function download($id, Request $request)
    {
        $patient = $request->user()->patientProfile;
        $document = $patient->documents()->findOrFail($id);

        // Mark as viewed
        if (!$document->viewed_by_patient) {
            $document->markAsViewed();
        }

        return Storage::download($document->file_path, $document->original_filename);
    }

    /**
     * Upload document (patient can upload)
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'title' => 'required|string|max:255',
            'category' => 'required|in:lab_result,prescription,medical_report,other',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient = $request->user()->patientProfile;
        $file = $request->file('file');

        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents/patients/' . $patient->id, $filename, 'private');

        $document = $patient->documents()->create([
            'uploader_id' => $request->user()->id,
            'title' => $request->title,
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'notes' => $request->notes,
            'category' => $request->category,
            'shared_with_patient' => true,
            'shared_at' => now(),
            'status' => 'uploaded',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully',
            'data' => $document
        ], 201);
    }

    /**
     * Mark document as viewed
     */
    public function markAsViewed($id, Request $request)
    {
        $patient = $request->user()->patientProfile;
        $document = $patient->documents()->findOrFail($id);

        $document->markAsViewed();

        return response()->json([
            'success' => true,
            'message' => 'Document marked as viewed',
            'data' => $document
        ]);
    }
}
