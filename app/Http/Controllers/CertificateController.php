<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertificateTemplate;
use App\Models\IssuedCertificate;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function settings()
    {
        $institute = auth()->user()->institute;
        $template = $institute->certificateTemplate ?? new CertificateTemplate([
            'title' => 'Certificate of Excellence',
            'body_text' => 'This is to certify that {student_name} has successfully completed the {course} course at {institute_name} on {date}.',
            'authorized_signatory_name' => auth()->user()->name,
            'authorized_signatory_designation' => 'Director'
        ]);
        
        return view('certificates.settings', compact('template'));
    }

    public function updateSettings(Request $request)
    {
        $institute = auth()->user()->institute;
        $template = $institute->certificateTemplate ?? new CertificateTemplate(['institute_id' => $institute->id]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body_text' => 'required|string',
            'authorized_signatory_name' => 'required|string|max:255',
            'authorized_signatory_designation' => 'required|string|max:255',
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'signature_image' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        if ($request->hasFile('background_image')) {
            if ($template->background_image) Storage::disk('public')->delete($template->background_image);
            $validated['background_image'] = $request->file('background_image')->store('certificates/backgrounds', 'public');
        }

        if ($request->hasFile('signature_image')) {
            if ($template->signature_image) Storage::disk('public')->delete($template->signature_image);
            $validated['signature_image'] = $request->file('signature_image')->store('certificates/signatures', 'public');
        }

        $template->fill($validated);
        $template->save();

        return back()->with('success', 'Certificate settings updated successfully.');
    }

    public function issue(Student $student)
    {
        $institute = auth()->user()->institute;
        $template = $institute->certificateTemplate;

        if (!$template) {
            return back()->with('error', 'Please configure the certificate template first.');
        }

        $issued = IssuedCertificate::create([
            'certificate_number' => 'CERT-' . strtoupper(Str::random(8)),
            'institute_id' => $institute->id,
            'student_id' => $student->id,
            'certificate_template_id' => $template->id,
            'issued_at' => now(),
        ]);

        return $this->download($issued);
    }

    public function download(IssuedCertificate $issuedCertificate)
    {
        $issuedCertificate->load(['student', 'template', 'institute']);
        $template = $issuedCertificate->template;
        $student = $issuedCertificate->student;
        $institute = $issuedCertificate->institute;

        // Parse placeholders
        $body = str_replace(
            ['{student_name}', '{course}', '{date}', '{certificate_number}', '{institute_name}'],
            [
                $student->name,
                $student->batch ? $student->batch->name : 'N/A',
                $issuedCertificate->issued_at->format('d M, Y'),
                $issuedCertificate->certificate_number,
                $institute->name
            ],
            $template->body_text
        );

        $pdf = Pdf::loadView('certificates.pdf', [
            'template' => $template,
            'student' => $student,
            'institute' => $institute,
            'body' => $body,
            'issuedCertificate' => $issuedCertificate
        ]);

        return $pdf->setPaper('a4', 'landscape')->download("Certificate-{$issuedCertificate->certificate_number}.pdf");
    }

    public function preview()
    {
        $institute = auth()->user()->institute;
        $template = $institute->certificateTemplate ?? new CertificateTemplate([
            'title' => 'Certificate of Excellence',
            'body_text' => 'This is to certify that {student_name} has successfully completed the {course} course at {institute_name} on {date}.',
            'authorized_signatory_name' => auth()->user()->name,
            'authorized_signatory_designation' => 'Director'
        ]);

        // Sample data for preview
        $body = str_replace(
            ['{student_name}', '{course}', '{date}', '{certificate_number}', '{institute_name}'],
            [
                'John Doe (Sample)',
                'Advanced Web Development',
                now()->format('d M, Y'),
                'CERT-SAMPLE123',
                $institute->name
            ],
            $template->body_text
        );

        $pdf = Pdf::loadView('certificates.pdf', [
            'template' => $template,
            'student' => (object)['name' => 'John Doe (Sample)'],
            'institute' => $institute,
            'body' => $body,
            'issuedCertificate' => (object)[
                'certificate_number' => 'CERT-SAMPLE123',
                'issued_at' => now()
            ]
        ]);

        return $pdf->setPaper('a4', 'landscape')->stream('certificate-preview.pdf');
    }
}
