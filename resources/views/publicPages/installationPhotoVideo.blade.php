@extends('layouts.public')

@section('css')
<style>
    .inst-container {
        max-width: 1100px;
        margin: 32px auto 64px;
        padding: 0 16px;
    }
    .inst-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #e2e8f0;
        margin-bottom: 6px;
    }
    .inst-sub {
        color: #94a3b8;
        font-size: .9rem;
        margin-bottom: 16px;
    }
    .inst-section {
        margin: 18px 0 28px;
    }
    .inst-section h3 {
        font-size: 1.05rem;
        font-weight: 800;
        color: #e2e8f0;
        margin-bottom: 10px;
    }
    .inst-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    }
    .inst-card {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 14px;
        padding: 12px;
        box-shadow: 0 10px 22px rgba(2,6,23,.25);
        backdrop-filter: blur(10px);
    }
    .inst-meta {
        font-size: .82rem;
        color: #cbd5e1;
        line-height: 1.5;
    }
    .inst-meta span {
        color: #94a3b8;
    }
    .media-grid {
        display: grid;
        gap: 10px;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        margin-top: 10px;
    }
    .media-item {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 12px;
        overflow: hidden;
    }
    .media-item img,
    .media-item iframe {
        width: 100%;
        height: 160px;
        object-fit: cover;
        display: block;
    }
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
    <div class="inst-title">Installation Stories & History</div>
    <div class="inst-sub">Residential and commercial milestones with photos & videos.</div>

    <div class="inst-section">
        <h3>Residential</h3>
        <div class="inst-grid">
            @forelse ($residential as $i)
                <div class="inst-card">
                    <div class="inst-meta"><span>Type:</span> {{ $i->installation_type }}</div>
                    <div class="inst-meta"><span>Location:</span> {{ $i->location }}</div>
                    <div class="inst-meta"><span>System Size:</span> {{ $i->system_size_kw }} kW</div>
                    <div class="inst-meta"><span>Date:</span> {{ $i->installation_date }}</div>

                    @php
                        $photos = $toArray($i->photos);
                        $video = $toEmbed($i->videos);
                    @endphp

                    <div class="media-grid">
                        @if ($video)
                            <div class="media-item">
                                <iframe src="{{ $video }}" title="Installation video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @endif

                        @foreach ($photos as $p)
                            <div class="media-item">
                                <img src="{{ Storage::url('/'.$p) }}" alt="Installation photo">
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="inst-card">
                    <div class="inst-meta">No residential installations found.</div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="inst-section">
        <h3>Commercial</h3>
        <div class="inst-grid">
            @forelse ($commercial as $i)
                <div class="inst-card">
                    <div class="inst-meta"><span>Type:</span> {{ $i->installation_type }}</div>
                    <div class="inst-meta"><span>Location:</span> {{ $i->location }}</div>
                    <div class="inst-meta"><span>System Size:</span> {{ $i->system_size_kw }} kW</div>
                    <div class="inst-meta"><span>Date:</span> {{ $i->installation_date }}</div>

                    @php
                        $photos = $toArray($i->photos);
                        $video = $toEmbed($i->videos);
                    @endphp

                    <div class="media-grid">
                        @if ($video)
                            <div class="media-item">
                                <iframe src="{{ $video }}" title="Installation video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @endif

                        @foreach ($photos as $p)
                            <div class="media-item">
                                <img src="{{ Storage::url('/'.$p) }}" alt="Installation photo">
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="inst-card">
                    <div class="inst-meta">No commercial installations found.</div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection