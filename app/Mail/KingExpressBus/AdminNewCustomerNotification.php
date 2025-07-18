<?php

namespace App\Mail\KingExpressBus;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewCustomerNotification extends Mailable
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
            subject: 'Thông Báo: Có Khách Hàng Mới Đăng Ký Tư Vấn',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'kingexpressbus.mail.admin_new_customer_notification',
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