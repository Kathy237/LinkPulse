<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte NFC - {{ $design->user->name ?? 'Design' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
        }
        .card {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            font-family: inherit;
        }
        .card-header {
            background: #1E3A8A;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .card-body {
            padding: 20px;
        }
        .info-row {
            margin-bottom: 12px;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }
        .label {
            font-weight: bold;
            color: #333;
            width: 100px;
            display: inline-block;
        }
        .value {
            color: #555;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h2>Carte de visite NFC</h2>
        </div>
        <div class="card-body">
            @php
                $data = $design->card_data;
            @endphp
            @if(!empty($data['name']))
                <div class="info-row">
                    <span class="label">Nom :</span>
                    <span class="value">{{ $data['name'] }}</span>
                </div>
            @endif
            @if(!empty($data['first_name']) || !empty($data['last_name']))
                <div class="info-row">
                    <span class="label">Prénom/Nom :</span>
                    <span class="value">{{ $data['first_name'] ?? '' }} {{ $data['last_name'] ?? '' }}</span>
                </div>
            @endif
            @if(!empty($data['phone']))
                <div class="info-row">
                    <span class="label">Téléphone :</span>
                    <span class="value">{{ $data['phone'] }}</span>
                </div>
            @endif
            @if(!empty($data['email']))
                <div class="info-row">
                    <span class="label">Email :</span>
                    <span class="value">{{ $data['email'] }}</span>
                </div>
            @endif
            @if(!empty($data['website']))
                <div class="info-row">
                    <span class="label">Site web :</span>
                    <span class="value">{{ $data['website'] }}</span>
                </div>
            @endif
            @if(!empty($data['address']))
                <div class="info-row">
                    <span class="label">Adresse :</span>
                    <span class="value">{{ $data['address'] }}</span>
                </div>
            @endif
        </div>
        <div class="footer">
            Généré par {{ config('app.name') }} - {{ now()->format('d/m/Y') }}
        </div>
    </div>
</body>
</html>