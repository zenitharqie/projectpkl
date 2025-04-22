<?php

namespace App\Mail;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class QuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quotation;

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    public function build()
{
    $email = $this->subject('New Quotation Submitted')
                  ->view('emails.emailquo');

    // Ambil path file dari folder "private"
    if (!empty($this->quotation->attachment)) {
        $attachmentPath = storage_path('app/private/quotations/' . basename($this->quotation->attachment));

        if (file_exists($attachmentPath)) {
            $email->attach($attachmentPath);
        } else {
            \Log::error("Attachment not found: " . $attachmentPath);
        }
    }

    return $email;
}

    
    
    
}