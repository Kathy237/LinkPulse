<!DOCTYPE html>
<html>
<head>
    <title>Réinitialisation mot de passe</title>
</head>
<body>
    <form method="POST" action="/api/reset-password">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="password" name="password" placeholder="Nouveau mot de passe" required>
        <input type="password" name="password_confirmation" placeholder="Confirmation" required>
        <button type="submit">Réinitialiser</button>
    </form>
</body>
</html>