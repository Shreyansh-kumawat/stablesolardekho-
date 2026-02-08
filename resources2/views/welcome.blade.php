@extends('layouts.public')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    /* Modern Solar Energy Dashboard - Complete Inline Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary-orange: #ff6b35;
        --primary-blue: #004e89;
        --accent-yellow: #ffd23f;
        --light-bg: #f7f9fc;
        --dark-text: #1a1a2e;
        --light-text: #6c757d;
        --white: #ffffff;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.08);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.12);
        --shadow-lg: 0 8px 24px rgba(0,0,0,0.16);
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light-bg);
        color: var(--dark-text);
        line-height: 1.6;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #004e89 0%, #1a659e 50%, #ff6b35 100%);
        color: var(--white);
        padding: 80px 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.05)"/></svg>') repeat;
        opacity: 0.3;
    }

    .hero-content {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .hero-subtitle {
        font-size: 1.5rem;
        margin-bottom: 10px;
        opacity: 0.95;
    }

    .hero-description {
        font-size: 1.1rem;
        opacity: 0.85;
    }

    /* Container */
    .main-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .stat-card {
        background: var(--white);
        border-radius: 20px;
        padding: 30px;
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border-top: 5px solid transparent;
    }

    .stat-card:nth-child(1) { border-top-color: #10b981; }
    .stat-card:nth-child(2) { border-top-color: #fbbf24; }
    .stat-card:nth-child(3) { border-top-color: #3b82f6; }
    .stat-card:nth-child(4) { border-top-color: #10b981; }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: currentColor;
        opacity: 0.05;
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 15px;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .stat-icon.green { background: #d1fae5; color: #10b981; }
    .stat-icon.yellow { background: #fef3c7; color: #fbbf24; }
    .stat-icon.blue { background: #dbeafe; color: #3b82f6; }

    .stat-content h3 {
        font-size: 0.9rem;
        color: var(--light-text);
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-content .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-text);
    }

    /* Video Section */
    .video-section {
        background: var(--white);
        border-radius: 25px;
        padding: 50px;
        margin-bottom: 50px;
        box-shadow: var(--shadow-md);
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-orange), var(--accent-yellow));
        border-radius: 2px;
    }

    .section-description {
        color: var(--light-text);
        font-size: 1.1rem;
        margin-bottom: 40px;
        margin-top: 20px;
    }

    /* Video Carousel */
    .video-carousel {
        position: relative;
    }

    .video-slide {
        display: none;
        animation: slideIn 0.5s ease-out;
    }

    .video-slide.active {
        display: block;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .video-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
    }

    @media (max-width: 968px) {
        .video-layout {
            grid-template-columns: 1fr;
        }
    }

    .video-player {
        position: relative;
        background: #000;
        border-radius: 20px;
        overflow: hidden;
        aspect-ratio: 16/9;
        cursor: pointer;
    }

    .video-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: opacity 0.3s ease;
    }

    .video-player.playing .video-thumbnail {
        opacity: 0;
    }

    .play-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100px;
        height: 100px;
        background: rgba(255, 107, 53, 0.95);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .play-overlay:hover {
        transform: translate(-50%, -50%) scale(1.15);
        background: rgba(255, 107, 53, 1);
        box-shadow: 0 0 30px rgba(255, 107, 53, 0.5);
    }

    .video-player.playing .play-overlay {
        display: none;
    }

    .play-icon {
        width: 0;
        height: 0;
        border-left: 30px solid white;
        border-top: 20px solid transparent;
        border-bottom: 20px solid transparent;
        margin-left: 8px;
    }

    .video-iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        display: none;
    }

    .video-player.playing .video-iframe {
        display: block;
    }

    .video-info {
        background: linear-gradient(135deg, #fff5e6 0%, #ffe6cc 100%);
        border-radius: 20px;
        padding: 35px;
        border-left: 6px solid var(--primary-orange);
    }

    .video-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 20px;
        line-height: 1.3;
    }

    .video-description {
        color: var(--dark-text);
        line-height: 1.8;
        margin-bottom: 25px;
    }

    .watch-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--primary-orange);
        color: white;
        padding: 12px 24px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    /* Video Controls */
    .video-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #e5e7eb;
    }

    .nav-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--primary-orange);
        border: none;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    .nav-btn:hover:not(:disabled) {
        background: #ff5722;
        transform: scale(1.1);
        box-shadow: var(--shadow-md);
    }

    .nav-btn:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .video-dots {
        display: flex;
        gap: 12px;
    }

    .dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #cbd5e1;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .dot.active {
        background: var(--primary-orange);
        transform: scale(1.3);
        border-color: #ffebcc;
    }

    .dot:hover {
        background: #ff8a65;
    }

    .video-counter {
        text-align: center;
        margin-top: 20px;
        font-weight: 600;
        color: var(--light-text);
    }

    .video-counter .current {
        color: var(--primary-orange);
        font-size: 1.2rem;
    }

    /* Info Cards */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .info-card {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        border-radius: 20px;
        padding: 35px;
        border-left: 6px solid #0284c7;
    }

    .info-card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #075985;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .info-list {
        list-style: none;
    }

    .info-list li {
        padding: 10px 0;
        padding-left: 30px;
        position: relative;
        color: #0c4a6e;
        line-height: 1.6;
    }

    .info-list li::before {
        content: '●';
        position: absolute;
        left: 0;
        color: #0284c7;
        font-size: 1.5rem;
        line-height: 1;
    }

    /* Calculator Section */
    .calculator-section {
        background: var(--white);
        border-radius: 25px;
        padding: 50px;
        margin-bottom: 50px;
        box-shadow: var(--shadow-md);
    }

    .toggle-btn {
        display: inline-flex;
        align-items: center;
        gap: 15px;
        background: transparent;
        border: none;
        color: var(--primary-blue);
        font-size: 2rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .toggle-btn:hover {
        color: var(--primary-orange);
        transform: translateX(5px);
    }

    .calculator-form {
        margin-top: 40px;
        display: none;
    }

    .calculator-form.show {
        display: block;
        animation: fadeInDown 0.5s ease-out;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 25px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 10px;
        font-size: 0.95rem;
    }

    .form-input,
    .form-select {
        padding: 14px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--white);
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }

    .radio-group {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .radio-label {
        flex: 1;
        min-width: 120px;
    }

    .radio-label input {
        display: none;
    }

    .radio-btn {
        display: block;
        padding: 12px 20px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        background: var(--white);
    }

    .radio-label input:checked + .radio-btn {
        background: var(--primary-orange);
        color: white;
        border-color: var(--primary-orange);
        transform: scale(1.05);
    }

    .radio-btn:hover {
        border-color: var(--primary-orange);
    }

    .btn-primary {
        padding: 16px 40px;
        background: linear-gradient(135deg, var(--primary-orange) 0%, #ff5722 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-md);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .result-box {
        margin-top: 30px;
        padding: 30px;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 15px;
        border-left: 6px solid #f59e0b;
        display: none;
    }

    .result-box.show {
        display: block;
        animation: fadeInUp 0.5s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .result-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #92400e;
        margin-bottom: 20px;
    }

    .result-item {
        padding: 10px 0;
        color: #78350f;
        font-size: 1.05rem;
    }

    .result-item strong {
        color: #92400e;
    }

    /* Quote Form Section */
    .quote-section {
        background: linear-gradient(135deg, var(--primary-blue) 0%, #1a659e 100%);
        border-radius: 25px;
        padding: 60px 50px;
        margin-bottom: 50px;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .quote-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .quote-section h2 {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }

    .quote-section > p {
        color: rgba(255,255,255,0.9);
        font-size: 1.2rem;
        margin-bottom: 40px;
        position: relative;
        z-index: 1;
    }

    .quote-form {
        position: relative;
        z-index: 1;
    }

    .quote-form .form-input {
        background: rgba(255,255,255,0.95);
        border-color: rgba(255,255,255,0.3);
    }

    .quote-form .form-label {
        color: white;
        font-size: 1rem;
    }

    .quote-form .required::after {
        content: ' *';
        color: var(--accent-yellow);
    }

    .quote-form .radio-btn {
        background: rgba(255,255,255,0.15);
        border-color: rgba(255,255,255,0.3);
        color: white;
    }

    .quote-form .radio-label input:checked + .radio-btn {
        background: white;
        color: var(--primary-blue);
        border-color: white;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 12px;
        color: white;
    }

    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .checkbox-group a {
        color: var(--accent-yellow);
        text-decoration: underline;
    }

    .btn-submit {
        width: 100%;
        padding: 20px;
        background: white;
        color: var(--primary-blue);
        border: none;
        border-radius: 12px;
        font-size: 1.3rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-lg);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        background: var(--accent-yellow);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 30px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon svg {
        width: 60px;
        height: 60px;
        color: #9ca3af;
    }

    .empty-title {
        font-size: 1.5rem;
        color: var(--light-text);
        margin-bottom: 10px;
    }

    .empty-text {
        color: var(--light-text);
        font-size: 1.1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.2rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
        }

        .video-section,
        .calculator-section,
        .quote-section {
            padding: 30px 25px;
        }

        .section-title {
            font-size: 2rem;
        }

        .video-controls {
            flex-direction: column;
            gap: 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .radio-group {
            flex-direction: column;
        }

        .radio-label {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Welcome to Stable Solar Energy</h1>
        <p class="hero-subtitle">Empowering Your Energy Independence</p>
        <p class="hero-description">Monitor, manage, and maximize your solar potential</p>
    </div>
</div>

<div class="main-container">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon green">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Active Systems</h3>
                    <div class="stat-value">24</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon yellow">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Today's Generation</h3>
                    <div class="stat-value">45.6 kWh</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon blue">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>CO2 Saved</h3>
                    <div class="stat-value">8.2 Tons</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon green">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Savings</h3>
                    <div class="stat-value">₹98,765</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Section -->
    <div class="video-section">
        <h2 class="section-title">Residential Solar Installation</h2>
        <p class="section-description">Learn about our professional installation process through video guides</p>

        @if(isset($videos) && count($videos) > 0)
        <div class="video-carousel">
            @foreach($videos as $index => $video)
            <div class="video-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                <div class="video-layout">
                    <div class="video-player" data-video-id="{{ $video->id }}">
                        <img src="{{ $video->youtube_thumbnail_url }}" alt="{{ $video->title }}" class="video-thumbnail">
                        <div class="play-overlay" onclick="playVideo({{ $video->id }}, '{{ $video->youtube_embed_url }}')">
                            <div class="play-icon"></div>
                        </div>
                        <iframe id="iframe-{{ $video->id }}" class="video-iframe" src="" title="{{ $video->title }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>

                    <div class="video-info">
                        <h3 class="video-title">{{ $video->title }}</h3>
                        <p class="video-description">{{ $video->description }}</p>
                        <span class="watch-badge">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Watch Full Video
                        </span>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="video-controls">
                <button class="nav-btn" id="prevBtn" onclick="changeVideo(-1)" aria-label="Previous">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <div class="video-dots">
                    @foreach($videos as $index => $video)
                    <span class="dot {{ $index === 0 ? 'active' : '' }}" onclick="goToVideo({{ $index }})"></span>
                    @endforeach
                </div>

                <button class="nav-btn" id="nextBtn" onclick="changeVideo(1)" aria-label="Next">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <div class="video-counter">
                Video <span class="current" id="currentVideo">1</span> of {{ count($videos) }}
            </div>
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="empty-title">No installation videos available at the moment</p>
            <p class="empty-text">Check back soon for educational content</p>
        </div>
        @endif

        <div class="info-grid">
            <div class="info-card">
                <h4 class="info-card-title">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Installation Process
                </h4>
                <ul class="info-list">
                    <li>Site assessment and custom design</li>
                    <li>Quick permits and approvals</li>
                    <li>Professional installation (1-3 days)</li>
                    <li>Inspection and grid connection</li>
                </ul>
            </div>

            <div class="info-card">
                <h4 class="info-card-title">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Key Requirements
                </h4>
                <ul class="info-list">
                    <li>Suitable roof condition & orientation</li>
                    <li>Minimal shading throughout day</li>
                    <li>Adequate electrical panel capacity</li>
                    <li>Local permits & utility approval</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Calculator Section -->
    <div class="calculator-section">
        <button type="button" class="toggle-btn" id="toggleCalculator">
            <svg width="35" height="35" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Calculate Your Solar Requirements
        </button>

        <div class="calculator-form" id="calculatorForm">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="electricity_office">Electricity Office</label>
                    <select class="form-select" id="electricity_office">
                        <option value="">Select an office</option>
                        <option value="jvvnl">Jaipur (JVVNL)</option>
                        <option value="avvnl">Ajmer (AVVNL)</option>
                        <option value="jdvvnl">Jodhpur (JDVVNL)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Connection Type</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="calc_connection_type" value="residential" checked>
                            <span class="radio-btn">Residential</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="calc_connection_type" value="commercial">
                            <span class="radio-btn">Commercial</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="calc_connection_type" value="industry">
                            <span class="radio-btn">Industry</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="monthly_units">Monthly Unit Consumption</label>
                    <input type="number" class="form-input" id="monthly_units" placeholder="Enter monthly units">
                </div>

                <div class="form-group">
                    <label class="form-label" for="monthly_bill">Approximate Monthly Bill</label>
                    <input type="number" class="form-input" id="monthly_bill" placeholder="Enter bill amount">
                </div>
            </div>

            <button type="button" class="btn-primary" id="calculateBtn">Calculate Requirements</button>

            <div class="result-box" id="resultBox">
                <h4 class="result-title">Your Solar Requirements</h4>
                <div id="resultContent"></div>
            </div>
        </div>
    </div>

    <!-- Quote Form -->
    <div class="quote-section">
        <h2>Get Your Free Solar Quote Today</h2>
        <p>Start your journey to energy independence with a custom solar solution</p>

        <form class="quote-form" id="quoteForm" action="{{ route('userQuoteQuery') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label required" for="name">Full Name</label>
                    <input type="text" class="form-input" id="name" name="name" required placeholder="Your Full Name">
                </div>

                <div class="form-group">
                    <label class="form-label required" for="mob_no">WhatsApp Number</label>
                    <input type="text" class="form-input" id="mob_no" name="mob_no" pattern="[6-9]\d{9}" maxlength="10" required placeholder="10-digit Mobile Number">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label required">Connection Type</label>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="connection_type" value="residential" required>
                        <span class="radio-btn">Residential</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="connection_type" value="commercial">
                        <span class="radio-btn">Commercial</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="connection_type" value="industry">
                        <span class="radio-btn">Industry</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label required">Monthly Electricity Bill</label>
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
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label required" for="complete_address">Installation Address</label>
                    <input type="text" class="form-input" id="complete_address" name="complete_address" required placeholder="Complete Address">
                </div>

                <div class="form-group">
                    <label class="form-label" for="state_name">State</label>
                    <input type="text" class="form-input" id="state_name" name="state_name" placeholder="State Name">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label required" for="pin">PIN Code</label>
                    <input type="text" class="form-input" id="pin" name="pin" pattern="\d{6}" maxlength="6" required placeholder="6-digit PIN Code">
                </div>

                <div class="form-group">
                    <label class="form-label" for="city">City</label>
                    <input type="text" class="form-input" id="city" name="city" placeholder="City">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-input" id="email" name="email" placeholder="Email Address">
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="terms" checked disabled>
                <label for="terms">
                    I agree to <a href="/terms-of-use" target="_blank">terms of service</a> & <a href="/privacy-policy" target="_blank">privacy policy</a>
                </label>
            </div>

            <button type="submit" class="btn-submit">Get My Free Solar Quote</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Video Carousel
let currentSlide = 0;
const slides = document.querySelectorAll('.video-slide');
const dots = document.querySelectorAll('.dot');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');

function showSlide(index) {
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));

    if (slides[index]) {
        slides[index].classList.add('active');
        dots[index].classList.add('active');
        document.getElementById('currentVideo').textContent = index + 1;

        prevBtn.disabled = index === 0;
        nextBtn.disabled = index === slides.length - 1;

        document.querySelectorAll('.video-player').forEach(player => {
            player.classList.remove('playing');
            const iframe = player.querySelector('iframe');
            if (iframe) iframe.src = '';
        });
    }
}

function changeVideo(direction) {
    currentSlide += direction;
    if (currentSlide < 0) currentSlide = 0;
    if (currentSlide >= slides.length) currentSlide = slides.length - 1;
    showSlide(currentSlide);
}

function goToVideo(index) {
    currentSlide = index;
    showSlide(currentSlide);
}

function playVideo(videoId, embedUrl) {
    const player = document.querySelector(`[data-video-id="${videoId}"]`);
    const iframe = document.getElementById(`iframe-${videoId}`);
    if (player && iframe) {
        player.classList.add('playing');
        iframe.src = embedUrl + '?autoplay=1';
    }
}

if (slides.length > 0) showSlide(0);

// Calculator Toggle
document.getElementById('toggleCalculator').addEventListener('click', function() {
    const form = document.getElementById('calculatorForm');
    form.classList.toggle('show');
});

// Calculate
document.getElementById('calculateBtn').addEventListener('click', function() {
    const office = document.getElementById('electricity_office').value;
    const units = document.getElementById('monthly_units').value;
    const bill = document.getElementById('monthly_bill').value;
    const type = document.querySelector('input[name="calc_connection_type"]:checked').value;

    if (!office || !units || !bill) {
        Swal.fire({
            title: 'Missing Information',
            text: 'Please fill in all required fields',
            icon: 'warning',
            confirmButtonColor: '#ff6b35'
        });
        return;
    }

    const capacity = (units / 150).toFixed(2);
    const savings = (bill * 0.7).toFixed(0);

    document.getElementById('resultContent').innerHTML = `
        <div class="result-item"><strong>Connection Type:</strong> ${type.charAt(0).toUpperCase() + type.slice(1)}</div>
        <div class="result-item"><strong>Monthly Consumption:</strong> ${units} units</div>
        <div class="result-item"><strong>Recommended Solar Capacity:</strong> ~${capacity} kW</div>
        <div class="result-item"><strong>Estimated Monthly Savings:</strong> ₹${savings}</div>
        <div class="result-item" style="margin-top: 15px; font-size: 0.95rem;">
            ℹ️ This is an approximate estimate. For accurate sizing, please request a free consultation.
        </div>
    `;
    document.getElementById('resultBox').classList.add('show');
});

// Quote Form
document.getElementById('quoteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const btn = form.querySelector('.btn-submit');
    const originalText = btn.textContent;

    btn.disabled = true;
    btn.textContent = 'Submitting...';

    fetch(form.action, {
        method: 'POST',
        body: new FormData(form)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Success!',
                text: 'Your solar quote request has been submitted successfully!',
                icon: 'success',
                        confirmButtonColor: '#ff6b35'
            }).then(() => form.reset());
        } else {
            throw new Error(data.message || 'Something went wrong');
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error!',
            text: error.message,
            icon: 'error',
            confirmButtonColor: '#ff6b35'
        });
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = originalText;
    });
});
</script>
@endsection
