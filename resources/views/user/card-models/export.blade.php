@extends('layouts.app')

@section('title', 'Exporter le design')
@section('page-title', 'Export de carte')

@section('content')

<div style="max-width:500px;margin:0 auto;text-align:center;">

    <a href="{{ route('user.card-models') }}" class="btn btn-ghost btn-sm" style="margin-bottom:28px;">
        <i class="ri-arrow-left-line"></i> Retour aux modèles
    </a>

    <div class="card" style="padding:40px 32px;">
        <i class="ri-file-pdf-line" style="font-size:48px;color:var(--color-danger);margin-bottom:16px;display:block;"></i>

        <div style="font-family:var(--font-display);font-size:17px;font-weight:700;color:var(--color-white);margin-bottom:8px;">
            Design prêt à exporter
        </div>

        <p class="text-muted text-sm" style="margin-bottom:28px;">
            Votre design de carte NFC a été généré. Téléchargez-le ou imprimez-le directement.
        </p>

        @if(!empty($data['pdf_url']))
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ $data['pdf_url'] }}" download="carte-nfc-{{ $designId }}.pdf"
               class="btn btn-primary">
                <i class="ri-download-line"></i> Télécharger PDF
            </a>
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="ri-printer-line"></i> Imprimer
            </button>
        </div>
        @elseif(!empty($data['pdf_base64']))
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="data:application/pdf;base64,{{ $data['pdf_base64'] }}"
               download="carte-nfc-{{ $designId }}.pdf"
               class="btn btn-primary">
                <i class="ri-download-line"></i> Télécharger PDF
            </a>
        </div>
        @else
        <div class="alert alert-warning" style="text-align:left;">
            <i class="ri-information-line"></i>
            Le PDF n'est pas encore disponible. Veuillez réessayer dans quelques instants.
        </div>
        @endif

        <hr class="divider">

        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('user.card-models') }}" class="btn btn-ghost btn-sm">
                <i class="ri-bank-card-line"></i> Mes modèles
            </a>
            <a href="{{ route('user.portfolios') }}" class="btn btn-ghost btn-sm">
                <i class="ri-folder-line"></i> Mes portfolios
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .sidebar,.topbar,.btn{display:none !important;}
    .main-content{margin-left:0 !important;}
    .page-body{padding:0 !important;}
}
</style>
@endpush

@endsection
