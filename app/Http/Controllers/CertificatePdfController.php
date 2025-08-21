<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificatePdfController extends Controller
{
    // Download certificate as PDF
    public function download($id)
    {
        $cert = Certificate::with('user', 'opportunity')->findOrFail($id);
        $qr = base64_encode(QrCode::format('png')->size(120)->generate(route('certificate.verify', $cert->certificate_number)));

        $pdf = PDF::loadView('certificates.pdf', compact('cert', 'qr'));
        return $pdf->download("certificate-{$cert->certificate_number}.pdf");
    }
}
