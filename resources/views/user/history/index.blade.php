@extends('layouts.app')

@section('title', 'Historique')
@section('page-title', 'Historique')

@section('content')

<p class="text-muted text-sm" style="margin-bottom:24px;">
    Historique de tous les portfolios que vous avez consultés via NFC.
</p>

@if(count($history) > 0)

<div style="display:flex;flex-direction:column;gap:10px;">
    @foreach($history as $item)
    <div class="card" style="padding:16px 20px;">
        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">

            {{-- Icône NFC --}}
            <div style="width:40px;height:40px;background:rgba(0,212,255,0.08);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="ri-wifi-line" style="font-size:18px;color:var(--color-accent);"></i>
            </div>

            {{-- Infos --}}
            <div style="flex:1;min-width:0;">
                <div style="font-size:14px;font-weight:500;color:var(--color-white);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ $item['portfolio_title'] ?? $item['portfolio']['title'] ?? 'Portfolio inconnu' }}
                </div>
                <div class="text-xs text-muted" style="margin-top:2px;">
                    @if(!empty($item['nfc_uid']))
                    <span style="font-family:monospace;">UID: {{ $item['nfc_uid'] }}</span>
                    •
                    @endif
                    {{ isset($item['read_at']) ? \Carbon\Carbon::parse($item['read_at'])->format('d/m/Y à H:i') : '—' }}
                </div>
            </div>

            {{-- Lien vers le portfolio --}}
            @if(!empty($item['portfolio']['slug']))
            <a href="{{ route('public.portfolio', $item['portfolio']['slug']) }}"
               target="_blank"
               class="btn btn-ghost btn-sm">
                <i class="ri-external-link-line"></i>
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>

@else
<div class="empty-state">
    <i class="ri-history-line"></i>
    <p>Aucun historique de tag NFC.</p>
    <p class="text-sm text-muted">L'historique s'alimentera lorsque vous taggerez des cartes NFC.</p>
</div>
@endif

@endsection
