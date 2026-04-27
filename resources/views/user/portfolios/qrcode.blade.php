@extends('layouts.app')

@section('title', 'QR Code')
@section('page-title', 'QR Code du portfolio')

@section('content')

<div style="max-width:500px;margin:0 auto;text-align:center;">

    <a href="{{ route('user.portfolios') }}" class="btn btn-ghost btn-sm" style="margin-bottom:28px;">
        <i class="ri-arrow-left-line"></i> Retour aux portfolios
    </a>

    <div class="card" style="padding:40px 32px;">

        <div style="font-family:var(--font-display);font-size:17px;font-weight:700;color:var(--color-white);margin-bottom:8px;">
            QR Code
        </div>
        <p class="text-muted text-sm" style="margin-bottom:28px;">
            Scannez ce QR code pour accéder directement au portfolio.
        </p>

        {{-- Image QR Code --}}
        <div style="background:white;border-radius:16px;padding:24px;display:inline-block;margin-bottom:24px;">
            @if(!empty($data['qr_url']))
                <img src="{{ $data['qr_url'] }}" alt="QR Code"
                     style="width:200px;height:200px;display:block;">
            @elseif(!empty($data['qr_base64']))
                <img src="data:image/png;base64,{{ $data['qr_base64'] }}" alt="QR Code"
                     style="width:200px;height:200px;display:block;">
            @else
                <div style="width:200px;height:200px;display:flex;align-items:center;justify-content:center;color:#ccc;">
                    <i class="ri-qr-code-line" style="font-size:64px;"></i>
                </div>
            @endif
        </div>

        {{-- Lien direct --}}
        <div style="background:var(--color-surface);border-radius:10px;padding:12px 16px;margin-bottom:24px;word-break:break-all;font-size:13px;color:var(--color-accent);">
            {{ url('/p/' . ($data['slug'] ?? $id)) }}
        </div>

        {{-- Actions --}}
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            @if(!empty($data['qr_url']))
            <a href="{{ $data['qr_url'] }}" download="qrcode-portfolio-{{ $id }}.png"
               class="btn btn-primary">
                <i class="ri-download-line"></i> Télécharger PNG
            </a>
            @elseif(!empty($data['qr_base64']))
            <a href="data:image/png;base64,{{ $data['qr_base64'] }}"
               download="qrcode-portfolio-{{ $id }}.png"
               class="btn btn-primary">
                <i class="ri-download-line"></i> Télécharger PNG
            </a>
            @endif

            <button onclick="window.print()" class="btn btn-secondary">
                <i class="ri-printer-line"></i> Imprimer
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .sidebar, .topbar, .btn { display:none !important; }
    .main-content { margin-left:0 !important; }
    .page-body { padding:0 !important; }
}
</style>
@endpush

@endsection
