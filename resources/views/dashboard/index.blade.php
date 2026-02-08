@extends('layouts.public')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    :root {
        --glass: rgba(255,255,255,0.08);
        --border: rgba(255,255,255,0.12);
        --text: #e2e8f0;
        --muted: #94a3b8;
        --accent: #60a5fa;
        --accent-2: #a78bfa;
        --accent-3: #22d3ee;
    }

    body { font-size: 14px; }

    .public-hero {
        padding: 48px 16px;
        text-align: center;
    }
    .public-hero h1 {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: .2px;
        margin-bottom: 6px;
    }
    .public-hero p {
        color: var(--muted);
        font-size: .95rem;
    }

    .container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 12px 16px 48px;
    }

    .grid {
        display: grid;
        gap: 14px;
    }
    .grid-2 { grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }
    .grid-3 { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }

    .card {
        background: var(--glass);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 14px;
        box-shadow: 0 10px 22px rgba(2,6,23,.25);
        backdrop-filter: blur(10px);
    }
    .card h3 {
        font-size: .95rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .card p { color: var(--muted); font-size: .9rem; }

    .stat {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }
    .stat .value {
        font-weight: 800;
        font-size: 1.1rem;
        color: #fff;
    }
    .stat .label {
        font-size: .8rem;
        color: var(--muted);
    }

    .tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 8px;
        border-radius: 999px;
        font-size: .75rem;
        background: linear-gradient(135deg, rgba(96,165,250,.2), rgba(167,139,250,.2));
        border: 1px solid rgba(96,165,250,.25);
        color: #fff;
    }

    .list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .list li {
        padding: 6px 0 6px 18px;
        position: relative;
        color: var(--muted);
        font-size: .9rem;
    }
    .list li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: var(--accent-3);
    }

    .quote-form .form-row {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        margin-bottom: 10px;
    }
    .form-label {
        font-size: .8rem;
        color: var(--muted);
        margin-bottom: 6px;
        display: block;
    }
    .form-input {
        width: 100%;
        padding: 9px 12px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: rgba(255,255,255,0.04);
        color: var(--text);
        font-size: .9rem;
    }
    .radio-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .radio-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        border: 1px solid var(--border);
        background: rgba(255,255,255,0.04);
        font-size: .8rem;
        cursor: pointer;
    }
    .radio-label input { display: none; }
    .radio-label input:checked + .radio-btn {
        background: linear-gradient(135deg, rgba(96,165,250,.25), rgba(167,139,250,.25));
        border-color: rgba(96,165,250,.4);
        color: #fff;
    }

    .btn-submit {
        width: 100%;
        padding: 10px 14px;
        border-radius: 12px;
        border: none;
        font-weight: 800;
        font-size: .95rem;
        color: #0b1220;
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        box-shadow: 0 8px 18px rgba(96,165,250,.35);
        cursor: pointer;
    }

    .muted { color: var(--muted); font-size: .85rem; }

    .media-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }
    .media-item {
        background: var(--glass);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 22px rgba(2,6,23,.25);
    }
    .media-item iframe,
    .media-item img {
        width: 100%;
        height: 190px;
        object-fit: cover;
        display: block;
    }
    .media-caption {
        padding: 8px 10px;
        font-size: .8rem;
        color: var(--muted);
    }
</style>
@endsection

@section('content')
<!-- Compact Hero -->
<section class="public-hero">
    <div class="tag">Solar • Smart • Savings</div>
    <h1>Stable Solar Energy</h1>
    <p>Compact dashboard + solar info + instant quote. No login required.</p>
</section>

<div class="container grid grid-2">
    <!-- Compact Dashboard -->
    <div class="card">
        <h3>Compact Dashboard</h3>
        <div class="grid grid-3">
            <div class="card">
                <div class="stat">
                    <div>
                        <div class="label">Active Systems</div>
                        <div class="value">24</div>
                    </div>
                    <span class="tag">Live</span>
                </div>
            </div>
            <div class="card">
                <div class="stat">
                    <div>
                        <div class="label">Today’s Gen</div>
                        <div class="value">45.6 kWh</div>
                    </div>
                    <span class="tag">+8%</span>
                </div>
            </div>
            <div class="card">
                <div class="stat">
                    <div>
                        <div class="label">CO₂ Saved</div>
                        <div class="value">8.2 T</div>
                    </div>
                    <span class="tag">Green</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Solar Info -->
    <div class="card">
        <h3>Solar Essentials</h3>
        <ul class="list">
            <li>Average payback: 3–5 years</li>
            <li>Typical install: 1–3 days</li>
            <li>Works best with south-facing roofs</li>
            <li>Net‑metering reduces utility bills</li>
        </ul>
    </div>
</div>

<div class="container grid grid-2">
    <!-- Benefits -->
    <div class="card">
        <h3>Why Go Solar</h3>
        <div class="grid grid-2">
            <div class="card">
                <div class="stat">
                    <div>
                        <div class="label">Monthly Savings</div>
                        <div class="value">₹2,500+</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="stat">
                    <div>
                        <div class="label">Warranty</div>
                        <div class="value">25 Years</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="stat">
                    <div>
                        <div class="label">Maintenance</div>
                        <div class="value">Low</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="stat">
                    <div>
                        <div class="label">Energy</div>
                        <div class="value">Clean</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quote Form -->
    <div class="card">
        <h3>Get a Solar Quote</h3>
        <p class="muted">Share a few details and we’ll send the best option.</p>

        <form class="quote-form" action="{{ route('userQuoteQuery') }}" method="POST">
            @csrf
            <div class="form-row">
                <div>
                    <label class="form-label" for="name">Full Name</label>
                    <input class="form-input" id="name" name="name" type="text" required placeholder="Your name">
                </div>
                <div>
                    <label class="form-label" for="mob_no">WhatsApp Number</label>
                    <input class="form-input" id="mob_no" name="mob_no" type="text" pattern="[6-9]\d{9}" maxlength="10" required placeholder="10-digit number">
                </div>
            </div>

            <label class="form-label">Monthly Electricity Bill</label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="bill" value="<1500" required>
                    <span class="radio-btn">< ₹1500</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="bill" value="1500-2500">
                    <span class="radio-btn">₹1500-2500</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="bill" value="2500-4000">
                    <span class="radio-btn">₹2500-4000</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="bill" value="4000-8000">
                    <span class="radio-btn">₹4000-8000</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="bill" value=">8000">
                    <span class="radio-btn">> ₹8000</span>
                </label>
            </div>

            <div class="form-row" style="margin-top:10px;">
                <div>
                    <label class="form-label" for="pin">PIN Code</label>
                    <input class="form-input" id="pin" name="pin" type="text" pattern="\d{6}" maxlength="6" required placeholder="6-digit PIN">
                </div>
                <div>
                    <label class="form-label" for="city">City</label>
                    <input class="form-input" id="city" name="city" type="text" placeholder="City">
                </div>
            </div>

            <div class="muted" style="margin:8px 0 12px;">
                By submitting, you agree to the <a href="/terms-of-use" target="_blank">terms</a> & <a href="/privacy-policy" target="_blank">privacy policy</a>.
            </div>

            <button class="btn-submit" type="submit">Get My Quote</button>
        </form>
    </div>
</div>

<div class="container">
    <div class="card">
        <h3>Installation Stories</h3>
        <p class="muted">Latest project videos and site photos.</p>

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
        @endphp

        <div class="media-grid">
            @foreach ($installations as $item)
                @php
                    $videos = $toArray($item->videos);
                    $photos = $toArray($item->photos);
                @endphp

                @foreach ($videos as $v)
                    @php $embed = $toEmbed($v); @endphp
                    @if ($embed)
                        <div class="media-item">
                            <iframe src="{{ $embed }}" title="Installation video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <div class="media-caption">Video</div>
                        </div>
                    @endif
                @endforeach

                @foreach ($photos as $p)
                    <div class="media-item">
                        <img src="{{ Storage::url('/'.$p) }}" alt="Installation photo">
                        <div class="media-caption">Photo</div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>
@endsection
