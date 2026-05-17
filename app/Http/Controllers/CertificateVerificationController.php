<?php

namespace App\Http\Controllers;

use App\Models\IssuedCertificate;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    public function verify(Request $request, $certificate_number = null)
    {
        $certificate = null;
        $error = null;

        $searchNumber = $certificate_number ?? $request->query('number');

        if ($searchNumber) {
            $certificate = IssuedCertificate::where('certificate_number', $searchNumber)
                ->with(['student', 'institute'])
                ->first();

            if (!$certificate) {
                $error = "No certificate found with number: " . $searchNumber;
            }
        }

        return view('public.verify-certificate', compact('certificate', 'error', 'searchNumber'));
    }
}
