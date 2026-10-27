<?php

namespace App\Mail;

use App\Models\ItemRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $itemRequest;
    public $type; // 'submitted', 'accepted', 'rejected', 'completed'
    public $recipientRole; // 'supplier' atau 'customer'

    public function __construct(ItemRequest $itemRequest, $type, $recipientRole)
    {
        $this->itemRequest = $itemRequest;
        $this->type = $type;
        $this->recipientRole = $recipientRole;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'submitted' => 'Permintaan Baru Masuk',
            'accepted'  => 'Permintaan Kamu Diterima!',
            'rejected'  => 'Update: Permintaan Kamu Ditolak',
            'completed' => 'Transaksi Selesai',
            default     => 'Update Status Permintaan',
        };

        return new Envelope(
            subject: "[SILABUNG] {$subject}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.request-status',
        );
    }
}