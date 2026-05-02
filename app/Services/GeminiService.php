<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
    }

    /**
     * Generate MCQs based on a prompt or text.
     */
    public function generateQuestions(string $input, int $count = 5)
    {
        if (!$this->apiKey) {
            throw new \Exception('Gemini API Key is not configured.');
        }

        $prompt = "You are an expert educational content creator. Generate exactly {$count} Multiple Choice Questions (MCQs) based on the following content or topic: \"{$input}\".
        
        CRITICAL INSTRUCTIONS:
        1. Return ONLY a valid JSON array of objects.
        2. Each object MUST have these exact keys: 'question', 'options' (array of 4 strings), 'correct_index' (0-3), and 'explanation'.
        3. Do not include any markdown formatting (like ```json) or preamble. Just the raw JSON array.
        4. Ensure the questions are accurate and pedagogical.
        5. The response must be a single valid JSON array.";

        try {
            $response = Http::post($this->baseUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error: ' . $response->body());
                throw new \Exception('Failed to connect to AI Service.');
            }

            $data = $response->json();
            
            // Extract text from the nested structure
            $resultText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$resultText) {
                throw new \Exception('AI returned an empty response.');
            }

            // Clean the text in case it contains markdown code blocks
            $resultText = trim($resultText);
            if (strpos($resultText, '```json') === 0) {
                $resultText = substr($resultText, 7);
            }
            if (substr($resultText, -3) === '```') {
                $resultText = substr($resultText, 0, -3);
            }

            $questions = json_decode(trim($resultText), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Gemini JSON Parsing Error: ' . json_last_error_msg() . ' | Content: ' . $resultText);
                throw new \Exception('AI returned invalid data format.');
            }

            return $questions;

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Suggest a persuasive follow-up message for a lead.
     */
    public function suggestFollowUp(array $data)
    {
        if (!$this->apiKey) {
            throw new \Exception('Gemini API Key is not configured.');
        }

        $prompt = "You are a professional admissions counselor at '{$data['institute_name']}'. 
        Suggest a warm, professional, and slightly persuasive follow-up message for a potential student.
        
        CONTEXT:
        - Student Name: {$data['student_name']}
        - Interested In: {$data['course_interest']}
        - Their Message: \"{$data['original_message']}\"
        
        REQUIREMENTS:
        1. Keep it under 100 words.
        2. Make it sound like a friendly human wrote it, not a robot.
        3. Include a call-to-action (e.g., 'Let us know if you want to visit our center' or 'Can we schedule a call?').
        4. Do not include placeholders like [Name]. Use the data provided.
        5. Return ONLY the suggested message text. No preamble.";

        try {
            $response = Http::post($this->baseUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error (Follow-up): ' . $response->body());
                throw new \Exception('Failed to connect to AI Service.');
            }

            $data = $response->json();
            $resultText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$resultText) {
                throw new \Exception('AI returned an empty response.');
            }

            return trim($resultText);

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception (Follow-up): ' . $e->getMessage());
            throw $e;
        }
    }
}
