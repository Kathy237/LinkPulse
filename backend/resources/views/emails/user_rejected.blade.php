{{-- resources/views/emails/user_rejected.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande d’accès refusée</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background: #f44336; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; }
        .footer { font-size: 12px; text-align: center; margin-top: 20px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Demande d’accès non retenue</h2>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $userName }}</strong>,</p>
            <p>Nous avons examiné votre demande d’inscription sur <strong>{{ $appName }}</strong>.</p>
            <p>Malheureusement, nous ne pouvons pas donner suite à votre demande pour le moment.</p>
            <p>Si vous pensez qu’il s’agit d’une erreur, vous pouvez contacter notre support à l’adresse :<br>
            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
            <p>Nous vous remercions de votre intérêt pour notre plateforme.</p>
            <p>Cordialement,<br>L’équipe {{ $appName }}</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $appName }}. Tous droits réservés.
        </div>
    </div>
</body>
</html>