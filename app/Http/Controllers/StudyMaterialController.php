<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudyMaterial;
use App\Models\Batch;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudyMaterialController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'student') {
            $student = $user->student;
            $materials = StudyMaterial::where(function($q) use ($student) {
                $q->whereNull('batch_id');
                if ($student) {
                    $q->orWhere('batch_id', $student->batch_id);
                }
            })->latest()->get();
            return view('study_materials.index', compact('materials'));
        }

        $materials = StudyMaterial::with('batch')->latest()->get();
        $batches = Batch::all();
        return view('study_materials.index', compact('materials', 'batches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:pdf,video,link,document',
            'batch_id'    => 'nullable|exists:batches,id',
            'description' => 'nullable|string',
            'file'        => 'required_without:external_url|file|mimes:pdf,doc,docx,zip,ppt,pptx|max:10240', // 10MB
            'external_url'=> 'required_without:file|nullable|url',
        ]);

        $fileUrl = $request->external_url;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('study_materials', 'public');
            $fileUrl = Storage::url($path);
        }

        StudyMaterial::create([
            'title'       => $request->title,
            'type'        => $request->type,
            'batch_id'    => $request->batch_id,
            'description' => $request->description,
            'file_url'    => $fileUrl,
            'institute_id'=> auth()->user()->institute_id,
        ]);

        return back()->with('success', 'Study material uploaded successfully!');
    }

    public function destroy(StudyMaterial $studyMaterial)
    {
        // Global scope ensures ownership
        if (Str::contains($studyMaterial->file_url, '/storage/study_materials/')) {
            $path = Str::after($studyMaterial->file_url, '/storage/');
            Storage::disk('public')->delete($path);
        }
        
        $studyMaterial->delete();
        return back()->with('success', 'Material deleted successfully!');
    }
}
