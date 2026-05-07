<style>
    footer.guest-footer {
        margin-top: 60px;
        background: #fff;
        border-top: 1px solid #e8edf3;
    }

    .footer-inner {
        max-width: 1100px;
        margin: 0 auto;
        padding: 52px 40px 40px;
        display: grid;
        grid-template-columns: 1.4fr 1fr 1fr;
        gap: 48px;
    }

    .footer-col-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #003366;
        margin-bottom: 16px;
    }

    .footer-brand-desc {
        font-size: 13px;
        color: #6b7a8d;
        line-height: 1.7;
        max-width: 240px;
    }

    .footer-contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #6b7a8d;
        margin-bottom: 10px;
    }

    .footer-contact-item i {
        color: #003366;
        width: 14px;
        text-align: center;
        font-size: 13px;
    }

    .footer-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .footer-links a {
        font-size: 13px;
        color: #6b7a8d;
        text-decoration: none;
        transition: color 0.2s;
    }

    .footer-links a:hover { color: #003366; }

    .footer-bottom-bar {
        background: #003366;
        text-align: center;
        padding: 16px 40px;
        font-size: 13px;
        color: rgba(255,255,255,0.75);
    }

    /* ── RESPONSIVE: Tablet (≤768px) ── */
    @media (max-width: 768px) {
        .footer-inner {
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            padding: 32px 20px 24px;
        }
        footer.guest-footer { margin-top: 32px; }
    }

    /* ── RESPONSIVE: Mobile (≤480px) ── */
    @media (max-width: 480px) {
        .footer-inner {
            grid-template-columns: 1fr;
            gap: 16px;
            padding: 24px 16px 16px;
        }
        .footer-brand-desc { max-width: 100%; }
        footer.guest-footer { margin-top: 20px; }
        .footer-bottom-bar { padding: 12px 16px; font-size: 12px; }
        .footer-contact-item { font-size: 12px; }
        .footer-links a { font-size: 12px; }
        .footer-col-title { font-size: 12px; margin-bottom: 10px; }
    }
</style>

<footer class="guest-footer">
    <div class="footer-inner">

        {{-- Brand --}}
        <div>
            <div class="footer-col-title">Ragadio Plaza</div>
            <p class="footer-brand-desc">Experience luxury and comfort in the heart of the city. Your perfect stay for business or leisure.</p>
        </div>

        {{-- Contact --}}
        <div>
            <div class="footer-col-title">Contact Us</div>
            <div class="footer-contact-item">
                <i class="fas fa-envelope"></i>
                <span>info@ragadioplaza.com</span>
            </div>
            <div class="footer-contact-item">
                <i class="fas fa-phone"></i>
                <span>+63 912 345 6789</span>
            </div>
        </div>

        {{-- Quick Links --}}
        <div>
            <div class="footer-col-title">Quick Links</div>
            <div class="footer-links">
                <a href="/rooms">Rooms</a>
                <a href="/reservations">Reservations</a>
            </div>
        </div>

    </div>

    <div class="footer-bottom-bar">
        &copy; 2026 Ragadio Plaza Hotel. All rights reserved.
    </div>
</footer>