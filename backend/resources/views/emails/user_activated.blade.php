{{-- resources/views/emails/user_activated.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation de votre compte</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background: #4CAF50; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; }
        .button { display: inline-block; background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px; }
        .footer { font-size: 12px; text-align: center; margin-top: 20px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Bienvenue sur {{ $appName }} !</h2>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $userName }}</strong>,</p>
            <p>Nous avons le plaisir de vous informer que votre compte a été <strong>activé</strong> par un administrateur.</p>
            <p>Vous pouvez dès à présent vous connecter à votre espace et commencer à créer vos portfolios NFC.</p>
            <p style="text-align: center;">
                <a href="{{ $loginUrl }}" class="button">Se connecter</a>
            </p>
            <p>Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
            <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
            <p>Nous vous souhaitons une excellente expérience sur {{ $appName }}.</p>
            <p>Cordialement,<br>L’équipe {{ $appName }}</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $appName }}. Tous droits réservés.
        </div>
    </div>
</body>
</html>