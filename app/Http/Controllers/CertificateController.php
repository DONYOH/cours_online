<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\View\View;

class CertificateController extends Controller
{
    /** Page publique : sert aussi de vérification d'authenticité via le code. */
    public function show(Certificate $certificate): View
    {
        $certificate->load(['user', 'course.teacher']);

        return view('certificates.show', compact('certificate'));
    }
}
