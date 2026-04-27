<!DOCTYPE html>
<html>
<head>
    <title>Réinitialisation mot de passe</title>
</head>
<body>
    <p>Bonjour {{ $user->name }},</p>
    <p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
    <p><a href="{{ $url }}">{{ $url }}</a></p>
    <p>Ce lien expire dans 60 minutes.</p>
    <p>Si vous n'avez pas demandé de réinitialisation, ignorez cet email.</p>
</body>
</html>