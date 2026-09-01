/* TEXSON — progressive enhancement เท่านั้น เว็บใช้งานได้ครบแม้ปิด JavaScript */
(function () {
    'use strict';

    var doc = document.documentElement;

    /* ---- สลับธีมโดยไม่ต้องโหลดหน้าใหม่ (ลิงก์ ?theme= ยังทำงานถ้าปิด JS) ---- */
    var themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function (event) {
            event.preventDefault();
            var next = doc.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            doc.setAttribute('data-theme', next);
            document.cookie = 'theme=' + next + ';path=/;max-age=31536000;samesite=lax';
            themeToggle.setAttribute(
                'href',
                themeToggle.getAttribute('href').replace(/theme=(light|dark)/, 'theme=' + (next === 'dark' ? 'light' : 'dark'))
            );
        });
    }

    /* ---- อินโทรหน้าแรก: ตัวอักษรเจาะภาพ แล้วขยายออกตามการเลื่อน ---- */
    var intro = document.getElementById('intro');
    if (intro) {
        var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (reduced || window.location.hash) {
            /* เข้ามาด้วยลิงก์ที่มี #anchor หรือผู้ใช้ปิดแอนิเมชัน → ข้ามอินโทรไปเลย */
            intro.parentNode.removeChild(intro);
            document.body.classList.add('intro-done');
            var target = window.location.hash ? document.querySelector(window.location.hash) : null;
            if (target) { target.scrollIntoView(); }
        } else {
            var media = document.getElementById('intro-media');
            var svg   = document.getElementById('intro-mask');
            var word  = document.getElementById('intro-word');
            var text  = document.getElementById('intro-text');
            var cover = document.getElementById('intro-cover');
            var fill  = document.getElementById('intro-fill');
            var mask  = document.getElementById('intro-cut');
            var hint  = document.getElementById('intro-hint');

            var MAX_SCALE = 70;      /* ขยายจนช่องเจาะกินพื้นที่ทั้งจอ */
            var FADE_FROM = 0.86;    /* ช่วงท้ายค่อยๆ จางแผ่นดำ กันขอบตัวอักษรค้าง */
            var cx = 0, cy = 0, lastWidth = 0, ticking = false;

            var draw = function () {
                var span = intro.offsetHeight - window.innerHeight;
                var p = span > 0 ? Math.min(Math.max(window.scrollY / span, 0), 1) : 1;

                var scale = 1 + Math.pow(p, 2.2) * (MAX_SCALE - 1);
                word.setAttribute(
                    'transform',
                    'translate(' + (cx * (1 - scale)) + ' ' + (cy * (1 - scale)) + ') scale(' + scale + ')'
                );
                media.style.transform = 'scale(' + (1.12 - 0.12 * p) + ')';
                cover.style.opacity = p > FADE_FROM ? String(Math.max(0, (1 - p) / (1 - FADE_FROM))) : '1';
                hint.style.opacity = p > 0.03 ? '0' : '1';

                document.body.classList.toggle('intro-done', p > 0.98);
            };

            /* ปรับขนาดตัวอักษรให้พอดีจอ แล้ววาดใหม่ */
            var fit = function () {
                var box = svg.getBoundingClientRect();
                if (!box.width) { return; }
                cx = box.width / 2;
                cy = box.height / 2;
                [cover, fill, mask].forEach(function (el) {
                    el.setAttribute('width', String(Math.ceil(box.width)));
                    el.setAttribute('height', String(Math.ceil(box.height)));
                });
                text.setAttribute('x', String(cx));
                text.setAttribute('y', String(cy));
                text.setAttribute('font-size', '100');
                var len = text.getComputedTextLength() || 1;
                var size = Math.min(100 * (box.width * 0.78) / len, box.height * 0.4);
                text.setAttribute('font-size', String(size));
                lastWidth = window.innerWidth;
                draw();
            };

            var onIntroScroll = function () {
                if (ticking) { return; }
                ticking = true;
                window.requestAnimationFrame(function () { ticking = false; draw(); });
            };

            /* ความกว้างเปลี่ยน = คำนวณขนาดตัวอักษรใหม่, สูงเปลี่ยนเฉยๆ (แถบ URL มือถือ) แค่วาดใหม่ */
            var onResize = function () {
                if (Math.abs(window.innerWidth - lastWidth) > 1) { fit(); } else { draw(); }
            };

            fit();
            if (document.fonts && document.fonts.ready) { document.fonts.ready.then(fit); }
            window.addEventListener('load', fit);
            window.addEventListener('scroll', onIntroScroll, { passive: true });
            window.addEventListener('resize', onResize);
            window.addEventListener('orientationchange', fit);
        }
    }

    /* ---- เมนูมือถือ ---- */
    var burger = document.getElementById('nav-toggle');
    var nav = document.getElementById('primary-nav');
    if (burger && nav) {
        var closeNav = function () {
            nav.classList.remove('is-open');
            burger.setAttribute('aria-expanded', 'false');
        };
        burger.addEventListener('click', function () {
            var open = nav.classList.toggle('is-open');
            burger.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        nav.addEventListener('click', function (event) {
            if (event.target.tagName === 'A') { closeNav(); }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') { closeNav(); }
        });
    }

    /* ---- เงาใต้ header เมื่อเลื่อนลง ---- */
    var header = document.querySelector('.site-header');
    if (header) {
        var onScroll = function () {
            header.classList.toggle('is-stuck', window.scrollY > 8);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /* ---- เอฟเฟกต์ค่อยๆ ปรากฏเมื่อเลื่อนถึง ---- */
    var revealables = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && revealables.length) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 });
        revealables.forEach(function (el) { observer.observe(el); });
    } else {
        revealables.forEach(function (el) { el.classList.add('is-visible'); });
    }

    /* ---- ฟอร์ม: ตรวจเบื้องต้น + กันกดส่งซ้ำ ---- */
    var form = document.querySelector('.form');
    if (form) {
        form.addEventListener('submit', function (event) {
            var ok = true;
            ['f-name', 'f-contact'].forEach(function (id) {
                var field = document.getElementById(id);
                if (field && field.value.trim() === '') {
                    field.classList.add('is-invalid');
                    ok = false;
                }
            });
            if (!ok) {
                event.preventDefault();
                var invalid = form.querySelector('.is-invalid');
                if (invalid) { invalid.focus(); }
                return;
            }
            var button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.textContent = button.getAttribute('data-sending') || button.textContent;
            }
        });
        form.addEventListener('input', function (event) {
            if (event.target.classList) { event.target.classList.remove('is-invalid'); }
        });
    }

    /* ---- เลื่อนไปหาข้อความแจ้งผลหลังส่งฟอร์ม ---- */
    var alertBox = document.querySelector('.alert');
    if (alertBox && window.location.hash === '#contact') {
        alertBox.setAttribute('tabindex', '-1');
        alertBox.focus({ preventScroll: true });
    }
})();
