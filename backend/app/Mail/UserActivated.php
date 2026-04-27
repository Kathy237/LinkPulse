<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserActivated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * L'utilisateur qui vient d'être activé.
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
            subject: '✅ Votre compte a été activé - ' . config('app.name'),
        );
    }

    /**
     * Obtenir la définition du contenu du message.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user_activated', // Vue Blade utilisée
            with: [
                'userName' => $this->user->name,
                'appName' => config('app.name'),
                'loginUrl' => url('/login'),
            ],
        );
    }

    /**
     * Obtenir les pièces jointes (aucune ici).
     */
    public function attachments(): array
    {
        return [];
    }
}