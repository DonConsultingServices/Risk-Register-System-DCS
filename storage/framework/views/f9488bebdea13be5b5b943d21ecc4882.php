<!-- DCS-Best Footer Component -->
<footer class="dcs-footer">
    <div class="footer-content">
        <div class="footer-row">
            <!-- Company Info -->
            <div class="footer-column">
                <div class="footer-brand">
                    <div class="footer-logo-container">
                        <img src="<?php echo e(asset('logo/logo.png')); ?>" alt="DCS Logo" class="footer-logo">
                    </div>
                    <p>
                        <a href="https://www.dcs.com.na" target="_blank" class="footer-link">
                            <i class="fas fa-globe me-2"></i>www.dcs.com.na
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Support -->
            <div class="footer-column">
                <h6 class="footer-heading">Support</h6>
                <ul class="footer-links">
                    <li><a href="mailto:ITSupport@dcs.com.na" class="footer-link">
                        <i class="fas fa-envelope me-2"></i>ITSupport@dcs.com.na
                    </a></li>
                    <li><a href="mailto:info@dcs.com.na" class="footer-link">
                        <i class="fas fa-envelope me-2"></i>info@dcs.com.na
                    </a></li>
                    <li><a href="tel:+26461302391" class="footer-link">
                        <i class="fas fa-phone me-2"></i>061-302 391
                    </a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div class="footer-column">
                <h6 class="footer-heading">Contact Info</h6>
                <div class="footer-contact">
                    <p class="footer-text">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        No 41, Johann and Sturrock<br>
                        Windhoek, Namibia
                    </p>
                    <p class="footer-text">
                        <i class="fas fa-clock me-2"></i>
                        Mon-Thu: 09h00 - 16h00<br>
                        Fri: 08h00 - 13h00<br>
                        Weekends & Public Holidays: Closed
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p class="footer-copyright">
                        &copy; <?php echo e(date('Y')); ?> Client Acceptance & Retention Risk Register. All rights reserved.
                </p>
                <div class="footer-legal">
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.dcs-footer {
    background: linear-gradient(135deg, var(--logo-dark-blue-primary) 0%, var(--logo-dark-blue-secondary) 100%);
    color: var(--logo-white);
    width: 100vw;
    margin-left: calc(-50vw + 50%);
    padding: 2.5rem 0 1rem;
    margin-top: auto;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.footer-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    gap: 2rem;
}

.footer-column {
    flex: 1;
    padding: 0;
    text-align: left;
}

.footer-brand {
    margin-bottom: 0;
}

.footer-logo-container {
    margin-bottom: 1rem;
}

.footer-logo {
    width: 120px;
    height: 40px;
}

.dcs-logo {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-bottom: 1rem;
}

.dcs-letter {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    color: white;
    font-weight: bold;
    font-size: 20px;
    border-radius: 8px;
    font-family: 'Arial', sans-serif;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.dcs-d {
    background: linear-gradient(135deg, var(--logo-dark-blue-primary) 0%, var(--logo-dark-blue-secondary) 100%);
}

.dcs-c {
    background: linear-gradient(135deg, var(--logo-red) 0%, var(--logo-red-hover) 100%);
}

.dcs-s {
    background: linear-gradient(135deg, var(--logo-green) 0%, #15803d 100%);
}

.footer-title {
    color: var(--logo-white);
    font-weight: 600;
    margin-bottom: 0.75rem;
    font-size: 1.2rem;
}

.footer-description {
    color: var(--logo-lighter-blue);
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 0;
}

.footer-heading {
    color: var(--logo-white);
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.75rem;
    list-style: none;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-link {
    color: var(--logo-lighter-blue);
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.footer-link:hover {
    color: var(--logo-white);
    text-decoration: none;
    transform: translateX(3px);
}

.footer-contact {
    margin-bottom: 0;
}

.footer-text {
    color: var(--logo-lighter-blue);
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: flex-start;
    line-height: 1.4;
}

.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.15);
    padding-top: 1.5rem;
}

.footer-bottom-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.footer-copyright {
    color: var(--logo-lighter-blue);
    font-size: 0.9rem;
    margin: 0;
}

.footer-legal {
    display: flex;
    gap: 1rem;
}

.footer-legal .footer-link {
    font-size: 0.85rem;
    color: var(--logo-lighter-blue);
}

.footer-legal .footer-link:hover {
    color: var(--logo-white);
    transform: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dcs-footer {
        padding: 2rem 0 1rem;
    }
    
    .footer-content {
        padding: 0 1rem;
    }
    
    .footer-row {
        flex-direction: column;
        gap: 2rem;
    }
    
    .footer-column {
        padding: 0;
    }
    
    .footer-bottom-content {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}

@media (max-width: 576px) {
    .footer-title {
        font-size: 1.1rem;
    }
    
    .footer-description {
        font-size: 0.85rem;
    }
    
    .footer-heading {
        font-size: 0.9rem;
    }
}
</style>
<?php /**PATH C:\xampp\htdocs\DCS-Best\resources\views/components/footer.blade.php ENDPATH**/ ?>