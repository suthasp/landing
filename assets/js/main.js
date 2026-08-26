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
