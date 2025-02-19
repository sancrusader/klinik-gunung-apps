<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QrCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $qrCodeUrl;

    public function __construct($qrCodeUrl)
    {
        $this->qrCodeUrl = $qrCodeUrl;
    }

    public function build()
    {
        return $this->subject('QR Code Pemeriksaan Fisik')
            ->view('mail.screenings.qrcode')
            ->with([
                'qrCodeUrl' => $this->qrCodeUrl,
            ]);
    }
}
