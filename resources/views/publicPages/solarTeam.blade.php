@extends('layouts.public')

@section('css')
<style>
    .team-container {
        max-width: 1100px;
        margin: 32px auto 64px;
        padding: 0 16px;
    }
    .team-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #e2e8f0;
        margin-bottom: 6px;
    }
    .team-sub {
        color: #94a3b8;
        font-size: .9rem;
        margin-bottom: 16px;
    }
    .team-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }
    .team-card {
        position: relative;
        padding-top: 18px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 14px;
        padding: 12px;
        box-shadow: 0 10px 22px rgba(2,6,23,.25);
        backdrop-filter: blur(10px);
    }
    .team-photo {
        width: 48px;
        height: 48px;
        border-radius: 999px;
        object-fit: cover;
        border: 1px solid rgba(255,255,255,0.18);
        margin-bottom: 0;
    }
    .team-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    .team-name {
        font-size: .98rem;
        font-weight: 800;
        color: #e2e8f0;
        margin-bottom: 2px;
    }
    .team-pos {
        font-size: .8rem;
        color: #94a3b8;
        margin-bottom: 8px;
    }
    .team-meta {
        font-size: .82rem;
        color: #cbd5e1;
        line-height: 1.4;
    }
    .team-meta span {
        color: #94a3b8;
    }
</style>
@endsection

@section('content')
<div class="team-container">
    <div class="team-title">Our Solar Team</div>
    <div class="team-sub">Meet the people powering your solar journey.</div>

    <div class="team-grid">
        @foreach ($teamMembers as $m)
            @php
                $photoPath = $m->profile_photo ? ltrim(preg_replace('~^public/~', '', $m->profile_photo), '/') : null;
            @endphp
            <div class="team-card">
                <div class="team-header">
                    <img
                        class="team-photo"
                        src="{{ $photoPath ? Storage::url($photoPath) : 'https://via.placeholder.com/100?text=Photo' }}"
                        alt="{{ $m->name  ?? 'Team Member Photo' }}"
                    >
                    <div>
                        <div class="team-name">{{ $m->name }}</div>
                        <div class="team-pos">{{ $m->position }}</div>
                    </div>
                </div>
                <div class="team-meta"><span>Mobile:</span> {{ $m->mobile_number ?? '' }}</div>
                <div class="team-meta"><span>Address:</span> {{ $m->address ?? '' }}</div>
                <div class="team-meta"><span>State:</span> {{ $m->state ?? '' }}</div>
                <div class="team-meta"><span>District:</span> {{ $m->district ?? '' }}</div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('js')
@endsection