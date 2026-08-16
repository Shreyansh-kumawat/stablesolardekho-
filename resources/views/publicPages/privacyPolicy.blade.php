@extends('layouts.public')

@section('css')
<style>
    .policy-container {
        max-width: 860px;
        margin: 40px auto 80px;
        padding: 0 20px;
    }
    .policy-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .policy-header h1 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #e2e8f0;
        margin-bottom: 8px;
    }
    .policy-header p {
        color: #94a3b8;
        font-size: 0.9rem;
    }
    .policy-card {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 16px;
        padding: 32px;
        backdrop-filter: blur(10px);
    }
    .policy-card h2 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #e2e8f0;
        margin: 28px 0 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .policy-card h2:first-child { margin-top: 0; }
    .policy-card p, .policy-card li {
        color: #94a3b8;
        font-size: 0.88rem;
        line-height: 1.75;
    }
    .policy-card ul {
        padding-left: 20px;
        margin: 8px 0;
    }
    .policy-card ul li {
        margin-bottom: 6px;
    }
    .policy-card a {
        color: #60a5fa;
        text-decoration: none;
    }
    .policy-card a:hover { text-decoration: underline; }
</style>
@endsection

@section('content')
<div class="policy-container">
    <div class="policy-header">
        <h1>Privacy Policy</h1>
        <p>Effective Date: 02 August 2026</p>
    </div>

    <div class="policy-card">
        <h2>Introduction</h2>
        <p>Stable Solar Energy Pvt. Ltd. ("Company", "we", "us", or "our") operates the website <a href="https://stablesolardekho.com">StableSolarDekho.com</a>. This Privacy Policy explains how we collect, use, store, and protect your personal information when you visit our website or use our services.</p>

        <h2>Information We Collect</h2>
        <p>We may collect the following types of personal information:</p>
        <ul>
            <li><strong>Personal Details:</strong> Name, email address, phone number, and residential/business address.</li>
            <li><strong>Business Information:</strong> Company name, GST number, and channel partner details (if applicable).</li>
            <li><strong>Transaction Data:</strong> Order history, payment receipts, and billing information.</li>
            <li><strong>Technical Data:</strong> IP address, browser type, device information, and cookies.</li>
            <li><strong>Usage Data:</strong> Pages visited, time spent on the site, and navigation patterns.</li>
        </ul>

        <h2>How We Use Your Information</h2>
        <p>We use the collected information for the following purposes:</p>
        <ul>
            <li>To process and fulfill your orders for solar products and services.</li>
            <li>To manage your account and provide customer support.</li>
            <li>To communicate with you about orders, promotions, and updates.</li>
            <li>To improve our website, products, and services.</li>
            <li>To comply with legal and regulatory requirements.</li>
            <li>To prevent fraud and ensure the security of our platform.</li>
        </ul>

        <h2>Information Sharing</h2>
        <p>We do not sell your personal information to third parties. We may share your data only in the following circumstances:</p>
        <ul>
            <li><strong>Service Providers:</strong> With trusted partners who assist in order fulfillment, payment processing, and delivery.</li>
            <li><strong>Legal Requirements:</strong> When required by law, court order, or government authority.</li>
            <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of company assets.</li>
        </ul>

        <h2>Data Security</h2>
        <p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>

        <h2>Cookies</h2>
        <p>Our website uses cookies to enhance your browsing experience. Cookies are small files stored on your device that help us understand how you interact with our site. You can disable cookies through your browser settings, though this may affect some website functionality.</p>

        <h2>Your Rights</h2>
        <p>You have the right to:</p>
        <ul>
            <li>Access, correct, or delete your personal information.</li>
            <li>Withdraw consent for data processing at any time.</li>
            <li>Request a copy of the data we hold about you.</li>
            <li>Opt out of marketing communications.</li>
        </ul>

        <h2>Third-Party Links</h2>
        <p>Our website may contain links to third-party websites. We are not responsible for the privacy practices or content of those sites. We encourage you to read the privacy policies of any third-party websites you visit.</p>

        <h2>Changes to This Policy</h2>
        <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with an updated effective date. We encourage you to review this policy periodically.</p>

        <h2>Contact Us</h2>
        <p>If you have any questions about this Privacy Policy, please contact us:</p>
        <ul>
            <li><strong>Email:</strong> <a href="mailto:info@stablesolardekho.com">info@stablesolardekho.com</a></li>
            <li><strong>Phone:</strong> <a href="tel:+917014920144">+91 70149 20144</a></li>
            <li><strong>Website:</strong> <a href="https://stablesolardekho.com">StableSolarDekho.com</a></li>
        </ul>
    </div>
</div>
@endsection
