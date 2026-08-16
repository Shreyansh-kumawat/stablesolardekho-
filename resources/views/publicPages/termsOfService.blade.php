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
    .policy-card ul, .policy-card ol {
        padding-left: 20px;
        margin: 8px 0;
    }
    .policy-card ul li, .policy-card ol li {
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
        <h1>Terms of Service</h1>
        <p>Effective Date: 02 August 2026</p>
    </div>

    <div class="policy-card">
        <h2>Acceptance of Terms</h2>
        <p>By accessing and using the website <a href="https://stablesolardekho.com">StableSolarDekho.com</a> ("Website"), you agree to be bound by these Terms of Service. If you do not agree with any part of these terms, please do not use our website.</p>

        <h2>About Us</h2>
        <p>This website is owned and operated by <strong>Stable Solar Energy Pvt. Ltd.</strong>, a company registered in Rajasthan, India. We provide solar energy products, installation services, and related solutions through our online platform.</p>

        <h2>User Accounts</h2>
        <ul>
            <li>You must provide accurate and complete information when creating an account.</li>
            <li>You are responsible for maintaining the confidentiality of your account credentials.</li>
            <li>You must be at least 18 years of age to create an account and place orders.</li>
            <li>We reserve the right to suspend or terminate accounts that violate these terms.</li>
        </ul>

        <h2>Products and Services</h2>
        <ul>
            <li>All products listed on the website are subject to availability.</li>
            <li>We strive to display accurate product descriptions, images, and pricing, but minor variations may occur.</li>
            <li>Prices are listed in Indian Rupees (INR) and may be updated without prior notice.</li>
            <li>Product warranties are provided as per the manufacturer's terms, unless stated otherwise.</li>
        </ul>

        <h2>Orders and Payments</h2>
        <ul>
            <li>Placing an order constitutes an offer to purchase. We reserve the right to accept or reject any order.</li>
            <li>Payment must be completed as per the available payment methods on the website.</li>
            <li>Channel Partners must upload a valid payment receipt for order confirmation.</li>
            <li>Orders are confirmed only after successful payment verification.</li>
        </ul>

        <h2>Channel Partner Program</h2>
        <ul>
            <li>Channel Partners are enrolled at our discretion and must comply with all applicable terms.</li>
            <li>Channel Partner pricing and benefits are exclusive and non-transferable.</li>
            <li>We reserve the right to modify or discontinue the Channel Partner program at any time.</li>
        </ul>

        <h2>Intellectual Property</h2>
        <p>All content on this website, including but not limited to text, images, logos, graphics, and software, is the property of Stable Solar Energy Pvt. Ltd. and is protected by applicable intellectual property laws. You may not reproduce, distribute, or use any content without our prior written consent.</p>

        <h2>Prohibited Activities</h2>
        <p>You agree not to:</p>
        <ul>
            <li>Use the website for any unlawful purpose.</li>
            <li>Attempt to gain unauthorized access to any part of the website.</li>
            <li>Interfere with the website's functionality or security.</li>
            <li>Submit false or misleading information.</li>
            <li>Use the website to transmit spam, viruses, or harmful content.</li>
        </ul>

        <h2>Limitation of Liability</h2>
        <p>To the maximum extent permitted by law, Stable Solar Energy Pvt. Ltd. shall not be liable for any indirect, incidental, consequential, or punitive damages arising from the use of our website or services. Our total liability shall not exceed the amount paid by you for the specific product or service in question.</p>

        <h2>Disclaimer</h2>
        <p>Our website and services are provided on an "as is" and "as available" basis. We make no warranties, express or implied, regarding the accuracy, reliability, or availability of the website or its content.</p>

        <h2>Governing Law</h2>
        <p>These Terms of Service are governed by and construed in accordance with the laws of India. Any disputes arising from these terms shall be subject to the exclusive jurisdiction of the courts in Jaipur, Rajasthan.</p>

        <h2>Changes to These Terms</h2>
        <p>We reserve the right to update these Terms of Service at any time. Changes will be posted on this page with an updated effective date. Continued use of the website after changes constitutes your acceptance of the revised terms.</p>

        <h2>Contact Us</h2>
        <p>If you have any questions about these Terms of Service, please contact us:</p>
        <ul>
            <li><strong>Email:</strong> <a href="mailto:info@stablesolardekho.com">info@stablesolardekho.com</a></li>
            <li><strong>Phone:</strong> <a href="tel:+917014920144">+91 70149 20144</a></li>
            <li><strong>Website:</strong> <a href="https://stablesolardekho.com">StableSolarDekho.com</a></li>
        </ul>
    </div>
</div>
@endsection
