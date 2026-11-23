/* Auto Detox Studio — premium interactions */
(() => {
    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => [...c.querySelectorAll(s)];
    const services = window.__services || [];
    const EMAIL_TO = 'autodetoxstudio@gmail.com';

    const sendEmailToStudio = async(payload, source = 'Website Form') => {
        const subject = payload.subject || `New ${source} from ${payload.name || 'Website Visitor'}`;
        const body = [
            `Source: ${source}`,
            `Name: ${payload.name || 'N/A'}`,
            `Phone: ${payload.phone || 'N/A'}`,
            `Email: ${payload.email || 'N/A'}`,
            `Package: ${payload.package_name || 'N/A'}`,
            `Preferred Date: ${payload.preferred_date || 'N/A'}`,
            `Preferred Time: ${payload.preferred_time || 'N/A'}`,
            `Vehicle: ${payload.car_model || 'N/A'}`,
            `Service: ${payload.service || 'N/A'}`,
            `Notes: ${payload.notes || payload.message || 'N/A'}`,
            `---`,
            `This message was sent from the Auto Detox Studio website.`
        ].join('\n');

        try {
            const response = await fetch(`https://formsubmit.co/ajax/${EMAIL_TO}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    _subject: subject,
                    _captcha: 'false',
                    _template: 'table',
                    name: payload.name || 'N/A',
                    email: payload.email || 'N/A',
                    phone: payload.phone || 'N/A',
                    service: payload.service || 'N/A',
                    package_name: payload.package_name || 'N/A',
                    preferred_date: payload.preferred_date || 'N/A',
                    preferred_time: payload.preferred_time || 'N/A',
                    car_model: payload.car_model || 'N/A',
                    notes: payload.notes || payload.message || 'N/A',
                    message: body,
                    source,
                    to: EMAIL_TO
                })
            });

            if (!response.ok) {
                throw new Error(`Request failed with status ${response.status}`);
            }

            return true;
        } catch (error) {
            console.error('Email send failed:', error);
            return false;
        }
    };

    /* ---------- Year ---------- */
    const yr = $('#year');
    if (yr) yr.textContent = new Date().getFullYear();

    /* ---------- Loader ---------- */
    const loader = $('#loader');
    window.addEventListener('load', () => {
        setTimeout(() => loader?.classList.add('done'), 350);
    });

    /* ---------- Monsoon offer popup ---------- */
    const offerPopup = $('#offerPopup');
    if (offerPopup) {
        let alreadyClosed = false;
        try { alreadyClosed = sessionStorage.getItem('adsMonsoonPopupClosed') === '1'; } catch (e) {}

        const closePopup = () => {
            offerPopup.classList.remove('show');
            try { sessionStorage.setItem('adsMonsoonPopupClosed', '1'); } catch (e) {}
        };

        if (!alreadyClosed) {
            setTimeout(() => offerPopup.classList.add('show'), 1400);
        }

        $('#offerPopupClose')?.addEventListener('click', closePopup);
        offerPopup.addEventListener('click', (e) => { if (e.target === offerPopup) closePopup(); });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && offerPopup.classList.contains('show')) closePopup();
        });
        $$('.js-popup-book', offerPopup).forEach(btn => btn.addEventListener('click', closePopup));
    }

    /* ---------- Nav scroll state ---------- */
    const nav = $('#nav');
    const onScroll = () => nav?.classList.toggle('scrolled', window.scrollY > 20);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    /* ---------- Mobile menu ---------- */
    const burger = $('#burger');
    const links = $('#navLinks');
    burger?.addEventListener('click', () => {
        const open = links.classList.toggle('open');
        burger.classList.toggle('open', open);
        burger.setAttribute('aria-expanded', open);
    });
    $$('.nav-links a').forEach(a => a.addEventListener('click', () => {
        links?.classList.remove('open');
        burger?.classList.remove('open');
        burger?.setAttribute('aria-expanded', 'false');
    }));

    /* ---------- Active section indicator ---------- */
    const sections = $$('section[id], div.sub-strip[id]');
    const navLinkFor = (id) => $(`.nav-link[data-section="${id}"]`);
    if (sections.length) {
        const navIo = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    $$('.nav-link.active').forEach(a => a.classList.remove('active'));
                    navLinkFor(e.target.id)?.classList.add('active');
                }
            });
        }, { threshold: 0.4, rootMargin: '-30% 0px -50% 0px' });
        sections.forEach(s => navIo.observe(s));
    }

    /* ---------- Reveal on scroll ---------- */
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('in');
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    $$('.reveal').forEach(el => io.observe(el));

    /* ---------- Magnetic buttons (desktop only) ---------- */
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        $$('.magnetic').forEach(m => {
            const target = m.querySelector('a, button') || m;
            m.addEventListener('mousemove', (e) => {
                const r = m.getBoundingClientRect();
                const x = e.clientX - r.left - r.width / 2;
                const y = e.clientY - r.top - r.height / 2;
                target.style.transform = `translate(${x * 0.22}px, ${y * 0.32}px)`;
            });
            m.addEventListener('mouseleave', () => { target.style.transform = ''; });
        });

        /* ---------- Cursor glow ---------- */
        const glow = $('#cursorGlow');
        let gx = 0,
            gy = 0,
            cx = 0,
            cy = 0;
        window.addEventListener('mousemove', (e) => {
            gx = e.clientX;
            gy = e.clientY;
            glow?.classList.add('active');
        });
        (function raf() {
            cx += (gx - cx) * 0.12;
            cy += (gy - cy) * 0.12;
            if (glow) glow.style.transform = `translate(${cx}px, ${cy}px) translate(-50%, -50%)`;
            requestAnimationFrame(raf);
        })();

        /* ---------- Hero parallax on mouse move ---------- */
        const heroVisual = $('#heroVisual');
        const hero = $('.hero');
        hero?.addEventListener('mousemove', (e) => {
            const r = hero.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width - 0.5;
            const py = (e.clientY - r.top) / r.height - 0.5;
            if (heroVisual) {
                heroVisual.style.transform = `translate3d(${px * -14}px, ${py * -14}px, 0)`;
            }
        });
    }

    /* ---------- Hero scroll parallax ---------- */
    const heroVisual = $('#heroVisual');
    window.addEventListener('scroll', () => {
        if (!heroVisual) return;
        const y = Math.min(window.scrollY, 800);
        heroVisual.querySelector('.hero-slide.active')?.style.setProperty('transform', `scale(1.08) translateY(${y * 0.06}px)`);
    }, { passive: true });

    /* ---------- Stagger children ---------- */
    $$('.services-grid .svc-card').forEach((el, i) => el.style.setProperty('--i', i));
    $$('.packages-grid .pkg-card').forEach((el, i) => el.style.setProperty('--i', i));

    /* ---------- Animated stat counters ---------- */
    $$('.stat-num[data-count]').forEach(stat => {
        const target = parseFloat(stat.dataset.count);
        const decimals = parseInt(stat.dataset.decimal || '0', 10);
        const valEl = stat.querySelector('.val');
        const counterIo = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (!e.isIntersecting) return;
                counterIo.unobserve(stat);
                const dur = 1400;
                const start = performance.now();
                const tick = (now) => {
                    const p = Math.min(1, (now - start) / dur);
                    const eased = 1 - Math.pow(1 - p, 3);
                    const val = target * eased;
                    valEl.textContent = decimals ? val.toFixed(decimals) : Math.round(val).toString();
                    if (p < 1) requestAnimationFrame(tick);
                };
                requestAnimationFrame(tick);
            });
        }, { threshold: 0.5 });
        counterIo.observe(stat);
    });

    /* ---------- Before / After slider ---------- */
    const baSlider = $('#baSlider');
    const baBefore = $('#baBefore');
    const baHandle = $('#baHandle');
    if (baSlider) {
        let dragging = false;
        const setPos = (clientX) => {
            const r = baSlider.getBoundingClientRect();
            let pct = ((clientX - r.left) / r.width) * 100;
            pct = Math.max(0, Math.min(100, pct));
            baBefore.style.clipPath = `inset(0 ${100 - pct}% 0 0)`;
            baHandle.style.left = `${pct}%`;
        };
        const start = (x) => {
            dragging = true;
            baSlider.classList.add('dragging');
            setPos(x);
        };
        const move = (x) => { if (dragging) setPos(x); };
        const end = () => {
            dragging = false;
            baSlider.classList.remove('dragging');
        };

        baSlider.addEventListener('mousedown', (e) => start(e.clientX));
        window.addEventListener('mousemove', (e) => move(e.clientX));
        window.addEventListener('mouseup', end);

        baSlider.addEventListener('touchstart', (e) => start(e.touches[0].clientX), { passive: true });
        baSlider.addEventListener('touchmove', (e) => move(e.touches[0].clientX), { passive: true });
        baSlider.addEventListener('touchend', end);
    }

    /* ---------- Gallery drag + arrows ---------- */
    const galleryWrap = $('#galleryTrackWrap');
    const galleryTrack = $('#galleryTrack');
    if (galleryWrap && galleryTrack) {
        let isDown = false,
            startX = 0,
            scrollStart = 0;
        const down = (x) => {
            isDown = true;
            galleryWrap.classList.add('dragging');
            startX = x;
            scrollStart = galleryTrack.scrollOffset || 0;
        };
        let offset = 0;
        const applyOffset = () => { galleryTrack.style.transform = `translateX(${offset}px)`; };
        const maxOffset = () => -(galleryTrack.scrollWidth - galleryWrap.clientWidth);

        galleryWrap.addEventListener('mousedown', (e) => {
            isDown = true;
            galleryWrap.classList.add('dragging');
            startX = e.clientX;
            scrollStart = offset;
        });
        window.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            offset = Math.max(maxOffset(), Math.min(0, scrollStart + (e.clientX - startX)));
            applyOffset();
        });
        window.addEventListener('mouseup', () => {
            isDown = false;
            galleryWrap.classList.remove('dragging');
        });

        galleryWrap.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].clientX;
            scrollStart = offset;
        }, { passive: true });
        galleryWrap.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            offset = Math.max(maxOffset(), Math.min(0, scrollStart + (e.touches[0].clientX - startX)));
            applyOffset();
        }, { passive: true });
        galleryWrap.addEventListener('touchend', () => { isDown = false; });

        const step = () => Math.min(720, galleryWrap.clientWidth * 0.8);
        $('#galleryNext')?.addEventListener('click', () => {
            offset = Math.max(maxOffset(), offset - step());
            applyOffset();
        });
        $('#galleryPrev')?.addEventListener('click', () => {
            offset = Math.min(0, offset + step());
            applyOffset();
        });
    }

    /* ---------- Service Finder / Configurator ---------- */
    const recommendMap = {
        wash: ['detox'],
        shine: ['rubbing', 'glass'],
        coating: ['ceramic', 'graphene'],
        protect: ['underbody', 'ceramic'],
        restore: ['denting'],
    };
    const cfgOptions = $$('.cfg-opt');
    const cfgServices = $('#cfgServices');
    const renderCfg = (need) => {
        if (!cfgServices) return;
        const ids = recommendMap[need] || [];
        cfgServices.innerHTML = ids.map(id => {
            const svc = services.find(s => s.id === id);
            if (!svc) return '';
            return `<a href="#svc-${id}" class="cfg-service-chip"><svg viewBox="0 0 24 24"><path fill="currentColor" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg>${svc.title}</a>`;
        }).join('');
    };
    cfgOptions.forEach(opt => opt.addEventListener('click', () => {
        cfgOptions.forEach(o => o.classList.remove('active'));
        opt.classList.add('active');
        renderCfg(opt.dataset.need);
    }));
    renderCfg('wash');

    /* ---------- Reviews carousel ---------- */
    const reviewsTrack = $('#reviewsTrack');
    const reviewCards = $$('.review-card');
    const dotsWrap = $('#reviewDots');
    let reviewIdx = 0;
    if (reviewsTrack && reviewCards.length) {
        reviewCards.forEach((_, i) => {
            const d = document.createElement('button');
            d.className = 'reviews-dot' + (i === 0 ? ' active' : '');
            d.addEventListener('click', () => goToReview(i));
            dotsWrap.appendChild(d);
        });

        function goToReview(i) {
            reviewIdx = (i + reviewCards.length) % reviewCards.length;
            reviewsTrack.style.transform = `translateX(-${reviewIdx * 100}%)`;
            $$('.reviews-dot', dotsWrap).forEach((d, di) => d.classList.toggle('active', di === reviewIdx));
        }
        $('#reviewNext')?.addEventListener('click', () => goToReview(reviewIdx + 1));
        $('#reviewPrev')?.addEventListener('click', () => goToReview(reviewIdx - 1));

        let reviewTimer = setInterval(() => goToReview(reviewIdx + 1), 6000);
        [$('#reviewNext'), $('#reviewPrev'), dotsWrap].forEach(el => el?.addEventListener('click', () => {
            clearInterval(reviewTimer);
            reviewTimer = setInterval(() => goToReview(reviewIdx + 1), 6000);
        }));
    }

    /* ---------- Multi-step booking ---------- */
    const bookingForm = $('#bookingForm');
    if (bookingForm) {
        const steps = $$('.bstep', bookingForm);
        const pills = $$('.step-pill');
        const pkgInput = $('#bf-package');
        const timeInput = $('#bf-time');

        const goStep = (n) => {
            steps.forEach(s => s.classList.toggle('active', Number(s.dataset.step) === n));
            pills.forEach(p => {
                const ps = Number(p.dataset.step);
                p.classList.toggle('active', ps === n);
                p.classList.toggle('done', ps < n);
            });
            bookingForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
        };

        const validateStep = (n) => {
            if (n === 1 && !pkgInput.value) { alert('Please select a package to continue.'); return false; }
            if (n === 2) {
                const date = $('#bf-date').value;
                if (!date) { alert('Please choose a preferred date.'); return false; }
                if (!timeInput.value) { alert('Please choose a time slot.'); return false; }
            }
            if (n === 3) {
                const name = $('#bf-name').value.trim();
                const phone = $('#bf-phone').value.trim();
                if (!name) { alert('Please enter your name.'); return false; }
                if (!/^[6-9]\d{9}$/.test(phone)) { alert('Please enter a valid 10-digit phone number.'); return false; }
            }
            return true;
        };

        $$('.js-next', bookingForm).forEach(btn => btn.addEventListener('click', () => {
            const current = Number(btn.closest('.bstep').dataset.step);
            if (!validateStep(current)) return;
            if (current === 3) fillSummary();
            goStep(Number(btn.dataset.next));
        }));
        $$('.js-back', bookingForm).forEach(btn => btn.addEventListener('click', () => {
            goStep(Number(btn.dataset.back));
        }));

        $$('.js-svc-pick', bookingForm).forEach(btn => btn.addEventListener('click', () => {
            $$('.js-svc-pick', bookingForm).forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            pkgInput.value = btn.dataset.pkgId;
        }));

        $$('.js-slot', bookingForm).forEach(btn => btn.addEventListener('click', () => {
            $$('.js-slot', bookingForm).forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            timeInput.value = btn.dataset.slot;
        }));

        function fillSummary() {
            const selectedPkg = $('.js-svc-pick.selected', bookingForm);
            $('#cs-package').textContent = selectedPkg ? `${selectedPkg.dataset.pkgName} — ₹${Number(selectedPkg.dataset.pkgPrice).toLocaleString('en-IN')}` : '—';
            $('#cs-date').textContent = $('#bf-date').value || '—';
            $('#cs-time').textContent = timeInput.value || '—';
            $('#cs-name').textContent = $('#bf-name').value || '—';
            $('#cs-phone').textContent = $('#bf-phone').value || '—';
            $('#cs-car').textContent = $('#bf-car').value || '—';
        }

        const selectPackageInForm = (pkgId) => {
            const btn = $(`.js-svc-pick[data-pkg-id="${pkgId}"]`, bookingForm);
            if (btn) {
                $$('.js-svc-pick', bookingForm).forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
                pkgInput.value = pkgId;
            }
        };
        $$('.js-book-pkg').forEach(btn => btn.addEventListener('click', () => selectPackageInForm(btn.dataset.pkgId)));
        $$('.js-book-service').forEach(btn => btn.addEventListener('click', () => goStep(1)));

        bookingForm.addEventListener('submit', async(event) => {
            event.preventDefault();

            const finalPackage = $('.js-svc-pick.selected', bookingForm);
            const date = $('#bf-date').value.trim();
            const time = $('#bf-time').value.trim();
            const name = $('#bf-name').value.trim();
            const phone = $('#bf-phone').value.trim();
            const email = $('#bf-email').value.trim();
            const carModel = $('#bf-car').value.trim();
            const notes = $('#bf-notes').value.trim();

            if (!finalPackage || !date || !time || !name || !/^[6-9]\d{9}$/.test(phone)) {
                alert('Please complete all required booking details before confirming.');
                return;
            }

            const payload = {
                name,
                phone,
                email,
                service: finalPackage.dataset.pkgName || 'Auto Detox Service',
                package_name: finalPackage.dataset.pkgName || 'Auto Detox Service',
                preferred_date: date,
                preferred_time: time,
                car_model: carModel,
                notes,
                subject: `New booking request from ${name}`,
                message: `New booking request for ${finalPackage.dataset.pkgName || 'Auto Detox service'}.`
            };

            const sent = await sendEmailToStudio(payload, 'Booking Request');
            if (!sent) {
                alert('Booking saved locally, but the email notification could not be sent. Please contact us at autodetoxstudio@gmail.com.');
                return;
            }

            alert('Your booking request has been sent successfully. Our team will contact you soon.');
            bookingForm.reset();
            $$('.js-svc-pick', bookingForm).forEach(b => b.classList.remove('selected'));
            $$('.js-slot', bookingForm).forEach(b => b.classList.remove('selected'));
            pkgInput.value = '';
            timeInput.value = '';
            goStep(1);
            closeBooking();
        });
    }

    /* ---------- Hero slideshow ---------- */
    const heroSlides = $$('.hero-slide');
    const heroDots = $$('.hero-slide-dots span');
    if (heroSlides.length > 1) {
        let heroIdx = 0;
        setInterval(() => {
            heroSlides[heroIdx].classList.remove('active');
            heroDots[heroIdx]?.classList.remove('active');
            heroIdx = (heroIdx + 1) % heroSlides.length;
            heroSlides[heroIdx].classList.add('active');
            heroDots[heroIdx]?.classList.add('active');
        }, 4500);
    }

    /* ---------- Booking modal: every Book button opens the existing multi-step form ---------- */
    const bookingSection = $('#book');
    const openBooking = (pkgId = '') => {
        if (!bookingSection) return;
        bookingSection.classList.add('modal-booking');
        document.body.classList.add('booking-open');
        if (pkgId) {
            const btn = $(`.js-svc-pick[data-pkg-id="${pkgId}"]`, bookingForm);
            if (btn) {
                $$('.js-svc-pick', bookingForm).forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
                $('#bf-package').value = pkgId;
            }
        }
        bookingSection.scrollTop = 0;
    };
    const closeBooking = () => {
        bookingSection?.classList.remove('modal-booking');
        document.body.classList.remove('booking-open');
    };
    $$('a[href="#book"]').forEach(link => link.addEventListener('click', (e) => {
        e.preventDefault();
        openBooking(link.dataset.pkgId || '');
    }));
    $('#bookingModalClose')?.addEventListener('click', closeBooking);
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeBooking();
            $('#enquiryOverlay')?.classList.remove('show');
        }
    });
    bookingSection?.addEventListener('click', e => {
        if (e.target === bookingSection && bookingSection.classList.contains('modal-booking')) closeBooking();
    });

    /* ---------- Service finder live recommendation ---------- */
    const cfgConcern = $('#cfgConcern');
    const cfgVehicle = $('#cfgVehicle');
    const cfgGoal = $('#cfgGoal');
    const cfgResultTitle = $('.cfg-result-title');
    const cfgDetails = document.createElement('div');
    cfgDetails.className = 'cfg-result-details';
    if (cfgServices?.parentElement) cfgServices.parentElement.appendChild(cfgDetails);

    function updateFinder() {
        const need = cfgConcern?.value || 'wash';
        renderCfg(need);
        const vehicle = cfgVehicle?.value || 'your vehicle';
        const goal = cfgGoal?.value || 'Best finish';
        if (cfgResultTitle) cfgResultTitle.textContent = `Recommended for ${vehicle}`;
        if (cfgDetails) cfgDetails.textContent = `${goal} • Based on your selected concern`;
    }
    [cfgConcern, cfgVehicle, cfgGoal].forEach(el => el?.addEventListener('change', updateFinder));
    updateFinder();

    /* ---------- Gallery filters, shuffle and lightbox ---------- */
    const galleryItems = $$('.gallery-item');
    const galleryFilters = $$('.gallery-filter[data-filter]');
    const lightbox = $('#galleryLightbox');
    const lightboxContent = $('#galleryLightboxContent');
    const showMedia = (item) => {
        if (!lightbox || !lightboxContent) return;
        const type = item.dataset.type || 'image';
        const src = item.dataset.src;
        const title = item.dataset.title || 'Gallery';
        lightboxContent.innerHTML = type === 'video' ?
            `<video src="${src}" controls autoplay playsinline></video>` :
            `<img src="${src}" alt="${title}">`;
        lightbox.classList.add('show');
        lightbox.setAttribute('aria-hidden', 'false');
    };
    galleryItems.forEach(item => item.addEventListener('click', () => showMedia(item)));
    $('#galleryLightboxClose')?.addEventListener('click', () => {
        lightbox?.classList.remove('show');
        lightbox?.setAttribute('aria-hidden', 'true');
    });
    lightbox?.addEventListener('click', e => {
        if (e.target === lightbox) {
            lightbox.classList.remove('show');
            lightbox.setAttribute('aria-hidden', 'true');
        }
    });
    galleryFilters.forEach(filter => filter.addEventListener('click', () => {
        galleryFilters.forEach(f => f.classList.remove('active'));
        filter.classList.add('active');
        const wanted = filter.dataset.filter;
        galleryItems.forEach(item => item.style.display = (wanted === 'all' || item.dataset.type === wanted) ? '' : 'none');
    }));
    $('#galleryShuffle')?.addEventListener('click', () => {
        const track = $('#galleryTrack');
        if (!track) return;
        [...galleryItems].sort(() => Math.random() - 0.5).forEach(item => track.appendChild(item));
        track.style.transform = 'translateX(0)';
    });

    /* ---------- Quick enquiry popup ---------- */
    const enquiryOverlay = $('#enquiryOverlay');
    const enquiryForm = document.querySelector('.enquiry-form');
    const openEnquiry = () => enquiryOverlay?.classList.add('show');
    const closeEnquiry = () => enquiryOverlay?.classList.remove('show');
    $('#floatEnquiry')?.addEventListener('click', openEnquiry);
    $('#enquiryClose')?.addEventListener('click', closeEnquiry);
    enquiryOverlay?.addEventListener('click', e => { if (e.target === enquiryOverlay) closeEnquiry(); });
    if (enquiryOverlay && !sessionStorage.getItem('ads_enquiry_seen')) {
        setTimeout(() => {
            openEnquiry();
            sessionStorage.setItem('ads_enquiry_seen', '1');
        }, 9000);
    }

    if (enquiryForm) {
        enquiryForm.addEventListener('submit', async(event) => {
            event.preventDefault();

            const name = $('#eq-name', enquiryForm).value.trim();
            const phone = $('#eq-phone', enquiryForm).value.trim();
            const email = $('#eq-email', enquiryForm)?.value.trim() || '';
            const service = $('#eq-service', enquiryForm).value.trim();
            const message = $('#eq-message', enquiryForm).value.trim();

            if (!name || !/^[6-9]\d{9}$/.test(phone)) {
                alert('Please enter your name and valid 10-digit phone number.');
                return;
            }

            const payload = {
                name,
                phone,
                email,
                service,
                message,
                subject: `New enquiry from ${name}`,
                notes: message
            };

            const sent = await sendEmailToStudio(payload, 'Quick Enquiry');
            if (!sent) {
                alert('Your enquiry could not be sent. Please email autodetoxstudio@gmail.com directly.');
                return;
            }

            alert('Your enquiry has been sent successfully. We will contact you shortly.');
            enquiryForm.reset();
            closeEnquiry();
        });
    }

})();