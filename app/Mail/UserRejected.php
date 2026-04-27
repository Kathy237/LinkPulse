<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRejected extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * L'utilisateur dont la demande a été rejetée.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Créer une nouvelle instance de message.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Obtenir l'enveloppe du message.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '❌ Votre demande d’accès a été refusée - ' . config('app.name'),
        );
    }

    /**
     * Obtenir la définition du contenu du message.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user_rejected',
            with: [
                'userName' => $this->user->name,
                'appName' => config('app.name'),
                'contactEmail' => config('mail.from.address'),
            ],
        );
    }

    /**
     * Obtenir les pièces jointes.
     */
    public function attachments(): array
    {
        return [];
    }
}