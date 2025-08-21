<?php

namespace App\Mail;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateIssued extends Mailable
{
    use Queueable, SerializesModels;

    public Certificate $certificate;

    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    public function build()
    {
        $downloadUrl = url('/storage/'.$this->certificate->file_path);
        $verifyUrl   = url('/verify/'.$this->certificate->code);

        return $this->subject(__('messages.certificate_subject', ['event' => $this->certificate->event->title ?? '']))
            ->view('emails.certificate_issued', [
                'certificate' => $this->certificate,
                'downloadUrl' => $downloadUrl,
                'verifyUrl'   => $verifyUrl,
            ]);
    }
}
