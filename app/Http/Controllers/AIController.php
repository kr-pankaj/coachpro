<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class AIController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Generate quiz questions via AI.
     */
    public function generateQuestions(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:5000',
            'count' => 'required|integer|min:1|max:20',
        ]);

        try {
            $questions = $this->gemini->generateQuestions($request->topic, $request->count);
            return response()->json(['success' => true, 'questions' => $questions]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Suggest a follow-up message for a lead/enquiry.
     */
    public function suggestFollowUp(Enquiry $enquiry)
    {
        try {
            $data = [
                'institute_name' => auth()->user()->institute->name,
                'student_name' => $enquiry->student_name,
                'course_interest' => $enquiry->course_interested ?? 'Our Courses',
                'original_message' => $enquiry->message ?? 'No message provided.',
            ];

            $suggestion = $this->gemini->suggestFollowUp($data);

            return response()->json([
                'success' => true,
                'suggestion' => $suggestion,
                'phone' => $enquiry->phone
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
