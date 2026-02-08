@extends('layouts.public')

@section('css')
<style>
    .cu-container {
        max-width: 1000px;
        margin: 32px auto 64px;
        padding: 0 16px;
    }
    .cu-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #e2e8f0;
        margin-bottom: 6px;
    }
    .cu-sub {
        color: #94a3b8;
        font-size: .9rem;
        margin-bottom: 16px;
    }
    .cu-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    }
    .cu-card {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 14px;
        padding: 14px;
        box-shadow: 0 10px 22px rgba(2,6,23,.25);
        backdrop-filter: blur(10px);
    }
    .cu-label {
        font-size: .8rem;
        color: #94a3b8;
        margin-bottom: 6px;
        display: block;
    }
    .cu-input, .cu-textarea {
        width: 100%;
        padding: 9px 12px;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.04);
        color: #e2e8f0;
        font-size: .9rem;
    }
    .cu-textarea { min-height: 120px; resize: vertical; }
    .cu-btn {
        width: 100%;
        padding: 10px 14px;
        border-radius: 12px;
        border: none;
        font-weight: 800;
        font-size: .95rem;
        color: #0b1220;
        background: linear-gradient(135deg, #60a5fa, #a78bfa);
        box-shadow: 0 8px 18px rgba(96,165,250,.35);
        cursor: pointer;
    }
    .cu-meta {
        font-size: .85rem;
        color: #cbd5e1;
        line-height: 1.5;
    }
    .cu-meta span { color: #94a3b8; }
    .cu-map iframe {
        width: 100%;
        height: 240px;
        border: 0;
        border-radius: 12px;
    }
</style>
@endsection

@section('content')
<div class="cu-container">
    <div class="cu-title">Contact Us</div>
    <div class="cu-sub">Send your query or reach our teams directly.</div>

    <div class="cu-grid">
        <div class="cu-card">
            <form method="POST" action="#">
                @csrf
                <label class="cu-label" for="name">Name</label>
                <input class="cu-input" id="name" name="name" type="text" required placeholder="Your name">

                <label class="cu-label" for="email" style="margin-top:10px;">Email</label>
                <input class="cu-input" id="email" name="email" type="email" required placeholder="name@email.com">

                <label class="cu-label" for="query" style="margin-top:10px;">Query</label>
                <textarea class="cu-textarea" id="query" name="query" required placeholder="How can we help?"></textarea>

                <div style="margin-top:12px;">
                    <button class="cu-btn" type="submit">Send Query</button>
                </div>
            </form>
        </div>

        <div class="cu-card">
            <div class="cu-meta"><span>Sales Team:</span> sales@rebatedekho.com | +91 70149 20144</div>
            <div class="cu-meta"><span>Technical Team:</span> support@rebatedekho.com | +91 78780 31129</div>
            <div class="cu-meta" style="margin-top:10px;">
                <span>Head Office:</span> RQH3+RGQ, Iskcon Rd, Ganesh Nagar, Sumer Nagar, Sanganer, Jaipur, Barhmohanpura, Rajasthan 302020
            </div>

            <div class="cu-map" style="margin-top:12px;">
               <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3082.833613561526!2d75.75387529999999!3d26.8296058!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db5006e957861%3A0xfd9af896e0517789!2sStable%20Energy%20Private%20limited!5e1!3m2!1sen!2sin!4v1770400169256!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                  
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection