<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;

    /**
     * Create a new message instance.
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Password Reset Code')
                    ->html($this->emailBody());
    }

    protected function emailBody(): string
    {
        return <<<HTML
        <html>
            <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; color: #333;">
                <div style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h2 style="color: #555;">Reset Your Password</h2>
                    <p>You recently requested to reset your password. Use the code below to proceed:</p>
                    <p style="font-size: 32px; font-weight: bold; color: #2d3748; letter-spacing: 4px; text-align: center;">{$this->code}</p>
                    <p>This code will expire in a few minutes. If you didn’t request this, you can safely ignore this email.</p>
                    <hr>
                    <p style="font-size: 12px; color: #888;">&copy; 2025 YourAppName. All rights reserved.</p>
                </div>
            </body>
        </html>
        HTML;
    }
}
