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
    .policy-card .highlight-box {
        background: rgba(96,165,250,0.08);
        border: 1px solid rgba(96,165,250,0.15);
        border-radius: 10px;
        padding: 16px;
        margin: 12px 0;
    }
</style>
@endsection

@section('content')
<div class="policy-container">
    <div class="policy-header">
        <h1>Refund &amp; Cancellation Policy</h1>
        <p>Effective Date: 02 August 2026</p>
    </div>

    <div class="policy-card">
        <h2>Overview</h2>
        <p>At Stable Solar Energy Pvt. Ltd., we are committed to ensuring customer satisfaction. This policy outlines the terms and conditions for refunds, returns, and cancellations for products and services purchased through <a href="https://stablesolardekho.com">StableSolarDekho.com</a>.</p>

        <h2>Order Cancellation</h2>
        <ul>
            <li>Orders can be cancelled within <strong>24 hours</strong> of placement, provided the order has not been dispatched.</li>
            <li>To cancel an order, contact us via email or phone with your order details.</li>
            <li>Once an order has been dispatched, it cannot be cancelled. You may request a return after delivery instead.</li>
            <li>Custom or made-to-order products cannot be cancelled after production has begun.</li>
        </ul>

        <h2>Returns</h2>
        <ul>
            <li>Products can be returned within <strong>7 days</strong> of delivery if they are defective, damaged during transit, or do not match the product description.</li>
            <li>The product must be in its original packaging and unused condition.</li>
            <li>To initiate a return, contact our support team with your order number and photographs of the product.</li>
            <li>Return shipping costs may be borne by the customer unless the return is due to our error or a defective product.</li>
        </ul>

        <h2>Non-Returnable Items</h2>
        <p>The following items are not eligible for return or refund:</p>
        <ul>
            <li>Products that have been installed or used.</li>
            <li>Products damaged due to misuse, negligence, or improper handling by the customer.</li>
            <li>Custom or made-to-order products.</li>
            <li>Products without original packaging or missing accessories.</li>
        </ul>

        <h2>Refund Process</h2>
        <div class="highlight-box">
            <ul>
                <li>Once we receive and inspect the returned product, we will notify you of the refund status within <strong>3-5 business days</strong>.</li>
                <li>Approved refunds will be processed to the original payment method within <strong>7-10 business days</strong>.</li>
                <li>If payment was made via bank transfer or receipt upload, the refund will be credited to the same bank account.</li>
                <li>Shipping charges are non-refundable unless the return is due to our error.</li>
            </ul>
        </div>

        <h2>Installation Services</h2>
        <ul>
            <li>Once a solar installation has been completed, it is not eligible for a refund.</li>
            <li>If installation has not yet commenced, you may cancel the service and receive a full refund minus any advance costs incurred.</li>
            <li>Any issues with installation quality will be addressed under our service warranty.</li>
        </ul>

        <h2>Channel Partner Orders</h2>
        <ul>
            <li>Channel Partner orders follow the same cancellation and return policy as regular orders.</li>
            <li>Refunds for Channel Partner orders will be processed to the Channel Partner's registered account.</li>
            <li>Bulk orders may have specific terms; please contact us for details.</li>
        </ul>

        <h2>Damaged or Defective Products</h2>
        <p>If you receive a damaged or defective product:</p>
        <ol>
            <li>Contact us within <strong>48 hours</strong> of delivery with photographs of the damage.</li>
            <li>We will arrange a pickup or replacement at no additional cost.</li>
            <li>Replacement is subject to stock availability. If the product is out of stock, a full refund will be issued.</li>
        </ol>

        <h2>Contact Us</h2>
        <p>For any refund or cancellation queries, please reach out to us:</p>
        <ul>
            <li><strong>Email:</strong> <a href="mailto:info@stablesolardekho.com">info@stablesolardekho.com</a></li>
            <li><strong>Phone:</strong> <a href="tel:+917014920144">+91 70149 20144</a></li>
            <li><strong>Website:</strong> <a href="https://stablesolardekho.com">StableSolarDekho.com</a></li>
        </ul>
    </div>
</div>
@endsection
