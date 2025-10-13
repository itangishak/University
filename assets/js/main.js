// Enhanced Professional JavaScript for UAB Website

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all features
    initMobileMenu();
    initLanguageSwitcher();
    initFormValidation();
    initScrollEffects();
    initCounterAnimation();
    initSmoothScrolling();
    initLoadingStates();
    initNewsCardEffects();
    initParallaxEffect();
    initTypewriterEffect();
    initAnnouncementPopup();
});

// Mobile Menu Toggle
function initMobileMenu() {
    const mobileMenuButton = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const body = document.body;

    if (mobileMenuButton && navMenu) {
        mobileMenuButton.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            mobileMenuButton.classList.toggle('active');
            body.classList.toggle('menu-open');
            
            // Animate hamburger icon
            const icon = mobileMenuButton.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-list');
                icon.classList.toggle('bi-x');
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!navMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                navMenu.classList.remove('active');
                mobileMenuButton.classList.remove('active');
                body.classList.remove('menu-open');
            }
        });
    }
}

// Language Switcher
function initLanguageSwitcher() {
    const links = document.querySelectorAll('.language-switcher a[data-lang]');

    if (links.length) {
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedLang = this.getAttribute('data-lang');

                document.body.style.opacity = '0.8';

                setTimeout(() => {
                    window.location.href = `?lang=${selectedLang}`;
                }, 300);
            });
        });
    }
}

// Enhanced Form Validation
function initFormValidation() {
    const forms = document.querySelectorAll('form.validate');
    
    forms.forEach(form => {
        const submitBtn = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function(e) {
            const required = form.querySelectorAll('[required]');
            let valid = true;
            let firstInvalidField = null;

            // Remove previous error states
            form.querySelectorAll('.error').forEach(field => {
                field.classList.remove('error');
            });

            required.forEach(field => {
                if (!validateField(field)) {
                    valid = false;
                    field.classList.add('error');
                    
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                }
            });

            if (!valid) {
                e.preventDefault();
                
                // Focus on first invalid field
                if (firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
                
                // Show error message with animation
                showErrorMessage('Please fill in all required fields correctly');
            } else {
                // Add loading state to submit button
                if (submitBtn) {
                    submitBtn.classList.add('loading');
                    submitBtn.innerHTML = '<i class="bi bi-arrow-clockwise spin me-2"></i>Processing...';
                }
            }
        });

        // Real-time validation
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required')) {
                    if (validateField(this)) {
                        this.classList.remove('error');
                        this.classList.add('valid');
                    } else {
                        this.classList.add('error');
                        this.classList.remove('valid');
                    }
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    if (validateField(this)) {
                        this.classList.remove('error');
                        this.classList.add('valid');
                    }
                }
            });
        });
    });
}

// Field Validation Helper
function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    
    if (!value) return false;
    
    switch (type) {
        case 'email':
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        case 'tel':
            return /^[\+]?[\d\s\-\(\)]{8,}$/.test(value);
        case 'url':
            try {
                new URL(value);
                return true;
            } catch {
                return false;
            }
        default:
            return value.length > 0;
    }
}

// Error Message Display
function showErrorMessage(message) {
    // Remove existing error messages
    const existingErrors = document.querySelectorAll('.error-message');
    existingErrors.forEach(error => error.remove());
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message alert alert-danger fade-in-up';
    errorDiv.innerHTML = `<i class="bi bi-exclamation-circle me-2"></i>${message}`;
    
    document.body.appendChild(errorDiv);
    
    setTimeout(() => {
        errorDiv.classList.add('fade-out');
        setTimeout(() => errorDiv.remove(), 300);
    }, 5000);
}

// Scroll Effects and Animations
function initScrollEffects() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                
                // Special handling for counters
                if (entry.target.classList.contains('counter')) {
                    animateCounter(entry.target);
                }
                
                // Special handling for news cards
                if (entry.target.classList.contains('news-card')) {
                    setTimeout(() => {
                        entry.target.classList.add('animate-in');
                    }, Math.random() * 200);
                }
            }
        });
    }, observerOptions);

    // Observe elements for animation
    const animateElements = document.querySelectorAll(
        '.stat-card, .news-card, .event-item, .quick-link-item, .section-header, .announcement-alert'
    );
    
    animateElements.forEach(el => {
        observer.observe(el);
    });
}

// Counter Animation
function initCounterAnimation() {
    // This will be triggered by the scroll observer
}

function animateCounter(counter) {
    if (counter.classList.contains('counted')) return;
    
    const target = parseInt(counter.getAttribute('data-target'));
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;
    
    counter.classList.add('counted');
    
    const updateCounter = () => {
        current += step;
        if (current < target) {
            counter.textContent = Math.floor(current);
            requestAnimationFrame(updateCounter);
        } else {
            counter.textContent = target;
        }
    };
    
    updateCounter();
}

// Smooth Scrolling for Anchor Links
function initSmoothScrolling() {
    const scrollLinks = document.querySelectorAll('a[href^="#"]');
    
    scrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            // Skip if href is just "#"
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Loading States
function initLoadingStates() {
    // Add loading class to body initially
    document.body.classList.add('loading');
    
    // Remove loading class when everything is loaded
    window.addEventListener('load', () => {
        document.body.classList.remove('loading');
        
        // Trigger entrance animations
        const heroElements = document.querySelectorAll('.fade-in-up');
        heroElements.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('animate-in');
            }, index * 200);
        });
    });
    
    // Handle link loading states
    const links = document.querySelectorAll('a:not([href^="#"]):not([href^="mailto"]):not([href^="tel"])');
    links.forEach(link => {
        if (link.hostname === window.location.hostname) {
            link.addEventListener('click', function(e) {
                if (!e.ctrlKey && !e.metaKey) {
                    document.body.classList.add('page-loading');
                }
            });
        }
    });
}

// News Card Hover Effects
function initNewsCardEffects() {
    const newsCards = document.querySelectorAll('.news-card');
    
    newsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('hover');
            
            // Add tilt effect
            card.addEventListener('mousemove', handleCardTilt);
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
            this.style.transform = '';
            
            card.removeEventListener('mousemove', handleCardTilt);
        });
    });
}

function handleCardTilt(e) {
    const card = e.currentTarget;
    const rect = card.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    const centerX = rect.width / 2;
    const centerY = rect.height / 2;
    
    const rotateX = (y - centerY) / 10;
    const rotateY = (centerX - x) / 10;
    
    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
}

// Parallax Effect for Hero Section
function initParallaxEffect() {
    const heroSection = document.querySelector('.hero-section');
    if (!heroSection) return;
    
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallaxSpeed = 0.5;
        
        if (scrolled < window.innerHeight) {
            heroSection.style.transform = `translateY(${scrolled * parallaxSpeed}px)`;
        }
    });
}

// Typewriter Effect for Hero Tagline
function initTypewriterEffect() {
    const taglineElement = document.querySelector('.tagline-text');
    if (!taglineElement) return;
    
    const originalText = taglineElement.textContent;
    taglineElement.textContent = '';
    
    setTimeout(() => {
        let charIndex = 0;
        const typeInterval = setInterval(() => {
            taglineElement.textContent += originalText[charIndex];
            charIndex++;
            
            if (charIndex >= originalText.length) {
                clearInterval(typeInterval);
                taglineElement.classList.add('typing-complete');
            }
        }, 100);
    }, 1500);
}

// Announcement Popup
var announcementPopupTimer = window.announcementPopupTimer || null;

function initAnnouncementPopup() {
    createAnnouncementPopup();

    // Show on full page load
    window.addEventListener('load', () => {
        showAnnouncementPopup();
    });

    // Exit-intent: show when cursor leaves viewport at top (desktop)
    let exitIntentCooldown = false;
    document.addEventListener('mouseout', (e) => {
        if (e.relatedTarget === null && e.clientY <= 0) {
            if (!exitIntentCooldown) {
                showAnnouncementPopup();
                // Avoid rapid re-triggers
                exitIntentCooldown = true;
                setTimeout(() => exitIntentCooldown = false, 90000);
            }
        }
    });
}

function createAnnouncementPopup() {
    if (document.getElementById('announcement-popup')) return;

    // Inject minimal styles once
    if (!document.getElementById('announcement-popup-styles')) {
        const styles = document.createElement('style');
        styles.id = 'announcement-popup-styles';
        styles.textContent = `
        #announcement-popup { position: fixed; inset: 0; display: none; align-items: center; justify-content: center; z-index: 10000; }
        #announcement-popup.visible { display: flex; }
        #announcement-popup .announcement-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.6); }
        #announcement-popup .announcement-dialog { position: relative; background: transparent; border-radius: 12px; max-width: 90vw; max-height: 90vh; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.4); animation: popupIn 200ms ease-out; }
        #announcement-popup .announcement-image { display: block; max-width: 90vw; max-height: 85vh; object-fit: contain; }
        #announcement-popup .announcement-close { position: absolute; top: 8px; right: 8px; background: rgba(0,0,0,0.65); color: #fff; border: 0; border-radius: 50%; width: 36px; height: 36px; font-size: 22px; cursor: pointer; line-height: 36px; }
        @keyframes popupIn { from { transform: translateY(8px); opacity: 0.01; } to { transform: translateY(0); opacity: 1; } }
        @media (max-width: 480px) { #announcement-popup .announcement-dialog { width: 95vw; } #announcement-popup .announcement-image { width: 100%; height: auto; } }
        `;
        document.head.appendChild(styles);
    }

    const overlay = document.createElement('div');
    overlay.id = 'announcement-popup';
    overlay.setAttribute('aria-hidden', 'true');
    overlay.innerHTML = `
        <div class="announcement-backdrop" data-close></div>
        <div class="announcement-dialog" role="dialog" aria-modal="true" aria-labelledby="announcement-title">
            <button class="announcement-close" aria-label="Close" data-close>&times;</button>
            <img src="/assets/images/admission.jpeg" alt="Admission Announcement" class="announcement-image" />
        </div>
    `;
    document.body.appendChild(overlay);

    // Close handlers
    overlay.addEventListener('click', (e) => {
        if (e.target.hasAttribute('data-close') || (e.target.closest && e.target.closest('[data-close]'))) {
            hideAnnouncementPopup();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') hideAnnouncementPopup();
    });
}

function showAnnouncementPopup() {
    let overlay = document.getElementById('announcement-popup');
    if (!overlay) {
        createAnnouncementPopup();
        overlay = document.getElementById('announcement-popup');
    }
    if (!overlay) return;

    overlay.classList.add('visible');
    overlay.setAttribute('aria-hidden', 'false');

    // Auto-hide after 60 seconds
    if (announcementPopupTimer) clearTimeout(announcementPopupTimer);
    announcementPopupTimer = setTimeout(() => {
        hideAnnouncementPopup();
    }, 60000);
}

function hideAnnouncementPopup() {
    const overlay = document.getElementById('announcement-popup');
    if (!overlay) return;
    overlay.classList.remove('visible');
    overlay.setAttribute('aria-hidden', 'true');
    if (announcementPopupTimer) {
        clearTimeout(announcementPopupTimer);
        announcementPopupTimer = null;
    }
}

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Performance Optimization
if (typeof debouncedScrollHandler === 'undefined') {
    const debouncedScrollHandler = debounce(() => {
        // Handle scroll-based animations
        requestAnimationFrame(() => {
            // Update any scroll-dependent elements
        });
    }, 10);

    window.addEventListener('scroll', debouncedScrollHandler);
}

// Error Handling
window.addEventListener('error', (e) => {
    console.error('JavaScript Error:', e.error);
    // Could send error to logging service
});

// Service Worker Registration (if available)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registered: ', registration);
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}