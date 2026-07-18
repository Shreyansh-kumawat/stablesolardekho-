@extends('layouts.public')

@section('css')
<style>
    .team-container { max-width:1200px; margin:48px auto 80px; padding:0 20px; }
    .team-hero { text-align:center; margin-bottom:40px; }
    .team-hero h1 { font-size:2rem; font-weight:900; color:#fff; margin:0 0 8px; }
    .team-hero p { color:#94a3b8; font-size:1rem; }
    .team-grid { display:grid; gap:20px; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); }
    .team-card { background:linear-gradient(145deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02)); border:1px solid rgba(255,255,255,0.08); border-radius:16px; padding:24px; transition:transform .2s, box-shadow .2s; text-align:center; }
    .team-card:hover { transform:translateY(-4px); box-shadow:0 16px 40px rgba(0,0,0,.4); }
    .team-photo-wrap { width:90px; height:90px; border-radius:50%; margin:0 auto 16px; overflow:hidden; border:3px solid rgba(249,115,22,.5); }
    .team-photo-wrap img { width:100%; height:100%; object-fit:cover; }
    .team-name { font-size:1.1rem; font-weight:800; color:#fff; margin:0 0 4px; }
    .team-pos { font-size:.85rem; color:#f97316; font-weight:600; margin:0 0 16px; }
    .team-info { text-align:left; }
    .team-info-row { display:flex; align-items:flex-start; gap:8px; padding:6px 0; border-top:1px solid rgba(255,255,255,.06); }
    .team-info-row:first-child { border-top:none; }
    .team-info-icon { color:#f97316; font-size:.8rem; margin-top:2px; min-width:16px; text-align:center; }
    .team-info-text { font-size:.85rem; color:#cbd5e1; line-height:1.4; }
    .team-info-label { color:#64748b; font-size:.75rem; text-transform:uppercase; letter-spacing:.5px; display:block; margin-bottom:1px; }
</style>
@endsection

@section('content')
<div class="team-container">
    <div class="team-hero">
        <h1>Our Solar Team</h1>
        <p>Meet the experts powering your solar journey.</p>
    </div>

    <div class="team-grid">
        @foreach ($teamMembers as $m)
            @php
                $photoPath = $m->profile_photo ? ltrim(preg_replace('~^public/~', '', $m->profile_photo), '/') : null;
            @endphp
            <div class="team-card">
                <div class="team-photo-wrap">
                    <img src="{{ $photoPath ? Storage::url($photoPath) : 'https://ui-avatars.com/api/?name='.urlencode($m->name).'&background=f97316&color=fff&size=180' }}" alt="{{ $m->name }}">
                </div>
                <div class="team-name">{{ $m->name }}</div>
                <div class="team-pos">{{ $m->position }}</div>
                <div class="team-info">
                    @if($m->mobile_number)
                    <div class="team-info-row">
                        <span class="team-info-icon"><i class="fas fa-phone"></i></span>
                        <div class="team-info-text"><span class="team-info-label">Mobile</span>{{ $m->mobile_number }}</div>
                    </div>
                    @endif
                    @if($m->address)
                    <div class="team-info-row">
                        <span class="team-info-icon"><i class="fas fa-map-marker-alt"></i></span>
                        <div class="team-info-text"><span class="team-info-label">Address</span>{{ $m->address }}</div>
                    </div>
                    @endif
                    @if($m->state || $m->district)
                    <div class="team-info-row">
                        <span class="team-info-icon"><i class="fas fa-location-arrow"></i></span>
                        <div class="team-info-text"><span class="team-info-label">Location</span>{{ $m->district ?? '' }}{{ $m->district && $m->state ? ', ' : '' }}{{ $m->state ?? '' }}</div>
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
