{{-- resources/views/public/nfc-not-found.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte NFC introuvable — LinkPulse</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root{--bg:#060a12;--accent:#00d4ff;--text:#e2e8f0;--muted:#64748b;--font-d:'Syne',sans-serif;--font-b:'DM Sans',sans-serif;}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:var(--font-b);background:var(--bg);color:var(--text);min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:20px;}
        .icon{font-size:80px;color:rgba(0,212,255,0.2);margin-bottom:24px;}
        h1{font-family:var(--font-d);font-size:28px;font-weight:800;color:#fff;margin-bottom:12px;}
        p{font-size:15px;color:var(--muted);margin-bottom:28px;line-height:1.7;}
        .code{font-family:monospace;font-size:13px;background:rgba(255,255,255,0.05);padding:4px 12px;border-radius:6px;color:var(--accent);}
        a{display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border-radius:10px;background:var(--accent);color:#060a12;text-decoration:none;font-weight:700;font-size:14px;}
    </style>
</head>
<body>
<div>
    <div class="icon"><i class="ri-wifi-off-line"></i></div>
    <h1>Carte NFC non configurée</h1>
    <p>
        La carte NFC avec l'UID <span class="code">{{ $uid ?? 'inconnu' }}</span><br>
        n'est associée à aucun portfolio LinkPulse.
    </p>
    <a href="{{ route('home') }}"><i class="ri-home-line"></i> Accueil LinkPulse</a>
</div>
</body>
</html>
