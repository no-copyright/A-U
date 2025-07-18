<?php

namespace App\Mail\KingExpressBus;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerRegistrationSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public array $customerData;

    /**
     * Create a new message instance.
     */
    public function __construct(array $customerData)
    {
        $this->customerData = $customerData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác Nhận Đăng Ký Tư Vấn Thành Công',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'kingexpressbus.mail.customer_registration_success',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}