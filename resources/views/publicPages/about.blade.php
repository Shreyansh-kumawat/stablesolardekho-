@extends('layouts.public')

@section('css')
<style>
    .ab-wrap { max-width: 1100px; margin: 0 auto; padding: 48px 20px 72px; color: var(--text); }

    .ab-hero {
        text-align: center;
        padding: 40px 20px 32px;
        margin-bottom: 40px;
    }
    .ab-hero .kicker {
        display: inline-block;
        background: linear-gradient(135deg, rgba(96,165,250,0.15), rgba(167,139,250,0.15));
        border: 1px solid rgba(96,165,250,0.3);
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #93c5fd;
        margin-bottom: 18px;
    }
    .ab-hero h1 {
        font-size: clamp(1.8rem, 4vw, 2.8rem);
        font-weight: 800;
        line-height: 1.15;
        margin: 0 0 16px;
        background: linear-gradient(135deg, #e2e8f0, #60a5fa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .ab-hero p {
        max-width: 720px;
        margin: 0 auto;
        font-size: 1.02rem;
        line-height: 1.7;
        color: var(--muted);
    }

    .ab-section {
        margin-bottom: 44px;
    }
    .ab-section h2 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 14px;
        color: #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ab-section h2::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 24px;
        border-radius: 4px;
        background: linear-gradient(180deg, #60a5fa, #a78bfa);
    }
    .ab-section p {
        color: var(--muted);
        line-height: 1.75;
        font-size: 0.94rem;
        margin: 0 0 12px;
    }

    .ab-grid {
        display: grid;
        gap: 16px;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        margin-top: 20px;
    }
    .ab-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 22px;
        transition: transform 0.2s, border-color 0.2s;
    }
    .ab-card:hover {
        transform: translateY(-3px);
        border-color: rgba(96,165,250,0.35);
    }
    .ab-card .icon {
        width: 44px; height: 44px;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, rgba(96,165,250,0.15), rgba(167,139,250,0.15));
        border-radius: 12px;
        margin-bottom: 14px;
    }
    .ab-card h3 { font-size: 1rem; font-weight: 700; margin: 0 0 8px; color: #e2e8f0; }
    .ab-card p { font-size: 0.85rem; color: var(--muted); margin: 0; line-height: 1.6; }

    .ab-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px;
        margin: 28px 0 8px;
    }
    .ab-stat {
        background: linear-gradient(135deg, rgba(96,165,250,0.08), rgba(167,139,250,0.05));
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px 16px;
        text-align: center;
    }
    .ab-stat .num { font-size: 1.8rem; font-weight: 800; color: #60a5fa; line-height: 1; }
    .ab-stat .lbl { font-size: 0.78rem; color: var(--muted); margin-top: 6px; letter-spacing: 0.03em; }

    .ab-cta {
        margin-top: 40px;
        background: linear-gradient(135deg, rgba(96,165,250,0.12), rgba(167,139,250,0.1));
        border: 1px solid rgba(96,165,250,0.25);
        border-radius: 18px;
        padding: 36px 28px;
        text-align: center;
    }
    .ab-cta h3 { font-size: 1.4rem; font-weight: 700; margin: 0 0 8px; color: #e2e8f0; }
    .ab-cta p { color: var(--muted); margin: 0 0 20px; font-size: 0.95rem; }
    .ab-cta .btn-row { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
    .ab-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 24px; border-radius: 999px;
        font-weight: 700; font-size: 0.9rem;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .ab-btn.primary {
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: #fff;
        box-shadow: 0 6px 18px rgba(249,115,22,0.35);
    }
    .ab-btn.primary:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(249,115,22,0.45); }
    .ab-btn.ghost {
        background: rgba(255,255,255,0.06);
        color: #e2e8f0;
        border: 1px solid var(--border);
    }
    .ab-btn.ghost:hover { background: rgba(255,255,255,0.12); }
</style>
@endsection

@section('content')
<div class="ab-wrap">

    <div class="ab-hero">
        <span class="kicker">About Us</span>
        <h1>Powering Homes and Businesses with Clean Solar Energy</h1>
        <p>Stable Solar is on a mission to make reliable, affordable solar power accessible to every home, farm, and business in India. From rooftop installations to full-scale commercial projects, we deliver quality equipment, transparent pricing, and long-term after-sales support.</p>
    </div>

    <div class="ab-section">
        <h2>Who We Are</h2>
        <p>Founded with a simple belief that clean energy should be within everyone's reach, Stable Solar has grown into a trusted name in solar solutions across Rajasthan and beyond. We combine technical expertise, quality-first sourcing, and a customer-first approach to help you switch from grid dependence to energy independence.</p>
        <p>Our team handles everything under one roof: site inspection, custom system design, government subsidy paperwork, installation by trained technicians, and ongoing maintenance. No middlemen, no hidden costs, no surprises.</p>
    </div>

    <div class="ab-section">
        <h2>What We Offer</h2>
        <div class="ab-grid">
            <div class="ab-card">
                <div class="icon">
                    <svg width="22" height="22" fill="none" stroke="#60a5fa" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                </div>
                <h3>Rooftop Solar</h3>
                <p>On-grid, off-grid, and hybrid systems for residential rooftops with subsidy assistance and long warranties.</p>
            </div>
            <div class="ab-card">
                <div class="icon">
                    <svg width="22" height="22" fill="none" stroke="#a78bfa" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1M9 13h1M9 17h1M14 9h1M14 13h1M14 17h1"/></svg>
                </div>
                <h3>Commercial Projects</h3>
                <p>End-to-end solar installations for factories, warehouses, shops, and institutional buildings.</p>
            </div>
            <div class="ab-card">
                <div class="icon">
                    <svg width="22" height="22" fill="none" stroke="#22d3ee" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2L3 7v6c0 5 3.5 9.5 9 11 5.5-1.5 9-6 9-11V7l-9-5z"/></svg>
                </div>
                <h3>Quality Equipment</h3>
                <p>Genuine panels, inverters, and batteries from trusted brands with authentic warranties and service.</p>
            </div>
            <div class="ab-card">
                <div class="icon">
                    <svg width="22" height="22" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75M9 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
                </div>
                <h3>Channel Partners</h3>
                <p>A growing network of certified installation and sales partners to serve you locally, wherever you are.</p>
            </div>
        </div>
    </div>

    <div class="ab-section">
        <h2>Why Choose Us</h2>
        <div class="ab-grid">
            <div class="ab-card">
                <h3>Transparent Pricing</h3>
                <p>Clear quotes with itemized costs. No hidden charges, no surprise bills after installation.</p>
            </div>
            <div class="ab-card">
                <h3>Subsidy Support</h3>
                <p>We handle the full PM Surya Ghar subsidy paperwork so you get every rupee you are entitled to.</p>
            </div>
            <div class="ab-card">
                <h3>After-Sales Service</h3>
                <p>Systems come with warranty coverage and prompt on-site service whenever you need it.</p>
            </div>
            <div class="ab-card">
                <h3>Certified Team</h3>
                <p>Trained technicians and MNRE-approved installation practices for safe, long-lasting systems.</p>
            </div>
        </div>
    </div>

    <div class="ab-stats">
        <div class="ab-stat"><div class="num">500+</div><div class="lbl">Installations</div></div>
        <div class="ab-stat"><div class="num">50+</div><div class="lbl">Channel Partners</div></div>
        <div class="ab-stat"><div class="num">10+</div><div class="lbl">Cities Served</div></div>
        <div class="ab-stat"><div class="num">25 yr</div><div class="lbl">Panel Warranty</div></div>
    </div>

    <div class="ab-cta">
        <h3>Ready to switch to solar?</h3>
        <p>Get a free consultation and custom quote for your home or business.</p>
        <div class="btn-row">
            <a href="{{ route('shop') }}" class="ab-btn primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                Browse Products
            </a>
            <a href="{{ route('contactUs') }}" class="ab-btn ghost">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.01 1.18 2 2 0 012 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z"/></svg>
                Talk to Us
            </a>
        </div>
    </div>

</div>
@endsection
