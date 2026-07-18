@extends('layouts.public')

@section('css')
<style>
    .inst-container { max-width:1200px; margin:48px auto 80px; padding:0 20px; }
    .inst-hero { text-align:center; margin-bottom:40px; }
    .inst-hero h1 { font-size:2rem; font-weight:900; color:#fff; margin:0 0 8px; }
    .inst-hero p { color:#94a3b8; font-size:1rem; }
    .inst-section { margin-bottom:48px; }
    .inst-section-title { font-size:1.2rem; font-weight:800; color:#f97316; margin-bottom:20px; display:flex; align-items:center; gap:10px; }
    .inst-section-title::after { content:''; flex:1; height:1px; background:rgba(255,255,255,.1); }
    .inst-grid { display:grid; gap:20px; grid-template-columns:repeat(auto-fill, minmax(340px, 1fr)); }
    .inst-card { background:linear-gradient(145deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02)); border:1px solid rgba(255,255,255,0.08); border-radius:16px; overflow:hidden; transition:transform .2s, box-shadow .2s; }
    .inst-card:hover { transform:translateY(-4px); box-shadow:0 16px 40px rgba(0,0,0,.4); }
    .inst-card-media { position:relative; }
    .inst-card-media img, .inst-card-media iframe { width:100%; height:220px; object-fit:cover; display:block; }
    .inst-card-media .no-media { height:120px; display:flex; align-items:center; justify-content:center; background:rgba(255,255,255,.03); color:#475569; font-size:.85rem; }
    .inst-card-body { padding:16px 20px; }
    .inst-card-location { font-size:1rem; font-weight:700; color:#e2e8f0; margin:0 0 8px; }
    .inst-card-details { display:flex; gap:16px; flex-wrap:wrap; }
    .inst-card-detail { font-size:.8rem; color:#94a3b8; }
    .inst-card-detail strong { color:#cbd5e1; }
    .inst-card-gallery { display:flex; gap:6px; padding:0 20px 16px; overflow-x:auto; }
    .inst-card-gallery img { width:80px; height:60px; object-fit:cover; border-radius:8px; border:1px solid rgba(255,255,255,.1); cursor:pointer; transition:opacity .2s; flex-shrink:0; }
    .inst-card-gallery img:hover { opacity:.8; }
    .inst-badge { position:absolute; top:12px; left:12px; background:rgba(249,115,22,.9); color:#fff; font-size:.7rem; font-weight:700; padding:4px 10px; border-radius:6px; text-transform:uppercase; letter-spacing:.5px; }
    .inst-empty { background:rgba(255,255,255,0.04); border:1px dashed rgba(255,255,255,0.1); border-radius:16px; padding:40px; text-align:center; color:#64748b; font-size:.9rem; }
</style>
@endsection

@section('content')
@php
    $toArray = function ($v) {
        if (is_array($v)) return $v;
        if (!is_string($v)) return [];
        $v = trim($v);
        if ($v === '') return [];
        $decoded = json_decode($v, true);
        return (json_last_error() === JSON_ERROR_NONE) ? ($decoded ?: []) : [$v];
    };
    $toEmbed = function ($url) {
        if (!$url) return null;
        if (preg_match('~youtu\.be/([^\?&/]+)~', $url, $m)) return 'https://www.youtube.com/embed/'.$m[1];
        if (preg_match('~v=([^\?&/]+)~', $url, $m)) return 'https://www.youtube.com/embed/'.$m[1];
        if (preg_match('~/embed/([^\?&/]+)~', $url, $m)) return 'https://www.youtube.com/embed/'.$m[1];
        return null;
    };
    $residential = $installations->filter(fn($i) => strtolower($i->installation_type ?? '') === 'residential');
    $commercial  = $installations->filter(fn($i) => strtolower($i->installation_type ?? '') === 'commercial');
@endphp

<div class="inst-container">
    <div class="inst-hero">
        <h1>Our Installations</h1>
        <p>Real projects. Real impact. See our solar installations across India.</p>
    </div>

    <div class="inst-section">
        <div class="inst-section-title">Residential</div>
        <div class="inst-grid">
            @forelse ($residential as $i)
                @php $photos = $toArray($i->photos); $video = $toEmbed($i->videos); @endphp
                <div class="inst-card">
                    <div class="inst-card-media">
                        @if ($video)
                            <iframe src="{{ $video }}" title="Installation video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @elseif(count($photos))
                            <img src="{{ Storage::url('/'.$photos[0]) }}" alt="Installation at {{ $i->location }}">
                        @else
                            <div class="no-media">No photos available</div>
                        @endif
                        <span class="inst-badge">{{ $i->system_size_kw }} kW</span>
                    </div>
                    <div class="inst-card-body">
                        <p class="inst-card-location">{{ $i->location }}</p>
                        <div class="inst-card-details">
                            <span class="inst-card-detail"><strong>Type:</strong> {{ $i->installation_type }}</span>
                            <span class="inst-card-detail"><strong>Date:</strong> {{ \Carbon\Carbon::parse($i->installation_date)->format('d M Y') }}</span>
                        </div>
                    </div>
                    @if(count($photos) > 1)
                    <div class="inst-card-gallery">
                        @foreach(array_slice($photos, 1) as $p)
                            <img src="{{ Storage::url('/'.$p) }}" alt="Installation photo">
                        @endforeach
                    </div>
                    @endif
                </div>
            @empty
                <div class="inst-empty">No residential installations yet.</div>
            @endforelse
        </div>
    </div>

    <div class="inst-section">
        <div class="inst-section-title">Commercial</div>
        <div class="inst-grid">
            @forelse ($commercial as $i)
                @php $photos = $toArray($i->photos); $video = $toEmbed($i->videos); @endphp
                <div class="inst-card">
                    <div class="inst-card-media">
                        @if ($video)
                            <iframe src="{{ $video }}" title="Installation video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @elseif(count($photos))
                            <img src="{{ Storage::url('/'.$photos[0]) }}" alt="Installation at {{ $i->location }}">
                        @else
                            <div class="no-media">No photos available</div>
                        @endif
                        <span class="inst-badge">{{ $i->system_size_kw }} kW</span>
                    </div>
                    <div class="inst-card-body">
                        <p class="inst-card-location">{{ $i->location }}</p>
                        <div class="inst-card-details">
                            <span class="inst-card-detail"><strong>Type:</strong> {{ $i->installation_type }}</span>
                            <span class="inst-card-detail"><strong>Date:</strong> {{ \Carbon\Carbon::parse($i->installation_date)->format('d M Y') }}</span>
                        </div>
                    </div>
                    @if(count($photos) > 1)
                    <div class="inst-card-gallery">
                        @foreach(array_slice($photos, 1) as $p)
                            <img src="{{ Storage::url('/'.$p) }}" alt="Installation photo">
                        @endforeach
                    </div>
                    @endif
                </div>
            @empty
                <div class="inst-empty">No commercial installations yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
