/* ============================================================
   NOVASEP — shared behaviour + chrome injection
   ============================================================ */
(function () {
  'use strict';

  /* ---------- Icon sprite (simple line icons) ---------- */
  var ICONS = {
    shield: '<path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/>',
    eye: '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
    drone: '<rect x="9" y="9" width="6" height="6" rx="1"/><path d="M9 9L5 5M15 9l4-4M9 15l-4 4M15 15l4 4"/><circle cx="5" cy="5" r="2"/><circle cx="19" cy="5" r="2"/><circle cx="5" cy="19" r="2"/><circle cx="19" cy="19" r="2"/>',
    camera: '<path d="M3 8h3l1.5-2h9L18 8h3v12H3z"/><circle cx="12" cy="13" r="3.5"/>',
    radar: '<path d="M12 3a9 9 0 1 0 9 9"/><path d="M12 12l6-3"/><path d="M12 12a4 4 0 1 0 4 4"/>',
    lock: '<rect x="5" y="11" width="14" height="9" rx="1.5"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>',
    check: '<path d="M5 12l4.5 4.5L19 7"/>',
    arrowRight: '<path d="M5 12h14M13 6l6 6-6 6"/>',
    arrowUpRight: '<path d="M7 17L17 7M9 7h8v8"/>',
    phone: '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L19 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" transform="translate(-1 -1)"/>',
    mail: '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
    pin: '<path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/>',
    whatsapp: '<path d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.7-1.2A9 9 0 1 0 12 3z" fill="none"/><path d="M8.6 8c.2-.5.4-.5.6-.5h.5c.2 0 .4 0 .6.5l.7 1.7c.1.2 0 .4-.1.5l-.5.6c-.1.1-.2.3-.1.5a5.5 5.5 0 0 0 2.6 2.3c.2.1.4.1.5 0l.6-.6c.2-.2.4-.2.6-.1l1.6.8c.2.1.3.3.3.5 0 .8-.6 1.5-1.3 1.6-.6.1-1.3.2-3.3-.7a8 8 0 0 1-3.4-3.4c-.7-1.4-.6-2.5-.5-3z"/>',
    facebook: '<path d="M14 8h2V5h-2a3 3 0 0 0-3 3v2H9v3h2v6h3v-6h2.2l.8-3H14V8.5c0-.4.2-.5.5-.5z"/>',
    linkedin: '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M7 10v7M7 7v.01M11 17v-4a2 2 0 0 1 4 0v4M11 10v7"/>',
    instagram: '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17" cy="7" r="1.2" fill="currentColor" stroke="none"/>',
    clock: '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    building: '<rect x="5" y="3" width="14" height="18" rx="1"/><path d="M9 7h2M13 7h2M9 11h2M13 11h2M9 15h2M13 15h2"/>',
    grad: '<path d="M3 9l9-4 9 4-9 4-9-4z"/><path d="M7 11v4c0 1 2.5 2 5 2s5-1 5-2v-4"/>',
    trend: '<path d="M3 17l6-6 4 4 7-7"/><path d="M21 8v-3h-3"/>',
    handshake: '<path d="M3 12l3-3 4 3 2-2 5 5-2 2-3-3"/><path d="M14 8l3-2 4 4"/>',
    leaf: '<path d="M4 20s1-9 8-12c4-1.8 8-1 8-1s.8 4-1 8c-3 7-12 8-12 8z"/><path d="M4 20c4-6 8-8 8-8"/>',
    bulb: '<path d="M9 18h6M10 21h4"/><path d="M8 14a6 6 0 1 1 8 0c-.8.7-1 1.2-1 2H9c0-.8-.2-1.3-1-2z"/>',
    zap: '<path d="M13 3L5 13h6l-1 8 8-10h-6z"/>',
    award: '<circle cx="12" cy="9" r="6"/><path d="M9 14l-1.5 7L12 18l4.5 3L15 14"/>',
    scan: '<path d="M4 8V5a1 1 0 0 1 1-1h3M16 4h3a1 1 0 0 1 1 1v3M20 16v3a1 1 0 0 1-1 1h-3M8 20H5a1 1 0 0 1-1-1v-3"/><path d="M4 12h16"/>',
    users: '<circle cx="9" cy="8" r="3"/><path d="M3 20a6 6 0 0 1 12 0"/><path d="M16 5.5a3 3 0 0 1 0 5M22 20a6 6 0 0 0-4-5.6"/>',
    target: '<circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="1" fill="currentColor" stroke="none"/>',
    route: '<circle cx="6" cy="18" r="2.5"/><circle cx="18" cy="6" r="2.5"/><path d="M8.5 18H15a3 3 0 0 0 0-6H9a3 3 0 0 1 0-6h6.5"/>',
    layers: '<path d="M12 3l9 5-9 5-9-5 9-5z"/><path d="M3 13l9 5 9-5"/>',
    doc: '<path d="M7 3h7l4 4v14H7z"/><path d="M14 3v4h4M10 13h6M10 17h6"/>',
    gov: '<path d="M4 9h16M5 9l7-5 7 5M6 9v8M10 9v8M14 9v8M18 9v8M4 21h16"/>',
    flag: '<path d="M5 21V4M5 5h11l-2 3 2 3H5"/>',
    cog: '<circle cx="12" cy="12" r="3"/><path d="M12 3v3M12 18v3M3 12h3M18 12h3M5.5 5.5l2 2M16.5 16.5l2 2M18.5 5.5l-2 2M7.5 16.5l-2 2"/>',
    plus: '<path d="M12 5v14M5 12h14"/>',
    star: '<path d="M12 4l2.3 5 5.2.5-4 3.4 1.3 5L12 20l-4.8 2.9 1.3-5-4-3.4 5.2-.5z"/>',
    quote: '<path d="M9 7H5v6h4l-1 4M19 7h-4v6h4l-1 4"/>'
  };

  function buildSprite() {
    var ns = 'http://www.w3.org/2000/svg';
    var svg = document.createElementNS(ns, 'svg');
    svg.setAttribute('aria-hidden', 'true');
    svg.style.cssText = 'position:absolute;width:0;height:0;overflow:hidden';
    var defs = '';
    for (var k in ICONS) {
      defs += '<symbol id="ic-' + k + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
        'stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">' + ICONS[k] + '</symbol>';
    }
    svg.innerHTML = defs;
    document.body.insertBefore(svg, document.body.firstChild);
  }
  function icon(name, cls) {
    return '<svg class="ico ' + (cls || '') + '" aria-hidden="true"><use href="#ic-' + name + '"></use></svg>';
  }
  window.novasepIcon = icon;

  /* ---------- Shared data ---------- */
  var WA = 'https://wa.me/573207873338';
  var NAV = [
    { id: 'inicio', label: 'Inicio', href: 'index.html' },
    { id: 'servicios', label: 'Servicios', href: 'servicios.html' },
    { id: 'sectores', label: 'Sectores', href: 'sectores.html' },
    { id: 'certificaciones', label: 'Certificaciones', href: 'certificaciones.html' },
    { id: 'quienes-somos', label: 'Quiénes somos', href: 'quienes-somos.html' },
    { id: 'trabaja', label: 'Trabaja con nosotros', href: 'trabaja.html' }
  ];
  var BRAND = '<a class="brand" href="index.html" aria-label="NOVASEP — Inicio">' +
    '<img src="media/logo.png" alt="NOVASEP — Innovación en Seguridad" class="brand-logo brand-logo-light" width="160" height="40" loading="eager">' +
    '<img src="media/logo negro.png" alt="NOVASEP — Innovación en Seguridad" class="brand-logo brand-logo-dark" width="160" height="40" loading="eager">' +
    '</a>';

  /* ---------- Header ---------- */
  function buildHeader(active) {
    var links = NAV.map(function (n) {
      return '<li><a href="' + n.href + '"' + (n.id === active ? ' class="active" aria-current="page"' : '') + '>' + n.label + '</a></li>';
    }).join('');
    var header = document.createElement('header');
    header.className = 'site-header';
    header.innerHTML =
      '<div class="wrap nav">' + BRAND +
      '<nav aria-label="Principal"><ul class="nav-links">' + links + '</ul></nav>' +
      '<div class="nav-cta">' +
      '<a class="btn btn-primary" href="contacto.html">Cotizar ' + icon('arrowRight') + '</a>' +
      '<button class="hamburger" aria-label="Abrir menú" aria-expanded="false" aria-controls="mobileMenu"><span></span><span></span><span></span></button>' +
      '</div></div>';

    var drawer = document.createElement('div');
    drawer.className = 'mobile-menu';
    drawer.id = 'mobileMenu';
    drawer.innerHTML =
      NAV.map(function (n) {
        return '<a href="' + n.href + '"' + (n.id === active ? ' class="active"' : '') + '>' + n.label + icon('arrowRight') + '</a>';
      }).join('') +
      '<a class="btn btn-primary btn-lg" href="contacto.html" style="justify-content:center">Cotizar ahora</a>' +
      '<div class="mm-contact">+57 604 448 40 52<br><a href="' + WA + '">WhatsApp: +57 320 787 3338</a><br>' +
      '<a href="mailto:servicioalcliente@novaseguridad.com.co">servicioalcliente@novaseguridad.com.co</a></div>';

    document.body.insertBefore(header, document.body.firstChild);
    document.body.appendChild(drawer);

    var ham = header.querySelector('.hamburger');
    function toggle(open) {
      ham.classList.toggle('open', open);
      drawer.classList.toggle('open', open);
      ham.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.style.overflow = open ? 'hidden' : '';
    }
    ham.addEventListener('click', function () { toggle(!drawer.classList.contains('open')); });
    drawer.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { toggle(false); }); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') toggle(false); });
    // Close mobile drawer when viewport passes desktop breakpoint
    window.addEventListener('resize', function () {
      if (window.innerWidth > 1024 && drawer.classList.contains('open')) { toggle(false); }
    });

    var onScroll = function () { header.classList.toggle('scrolled', window.scrollY > 50); };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ---------- Footer ---------- */
  function buildFooter() {
    var f = document.createElement('footer');
    f.className = 'site-footer';
    f.innerHTML = '<div class="wrap">' +
      '<div class="footer-grid">' +
      '<div class="footer-brand">' + BRAND +
      '<p>Empresa de seguridad privada con presencia nacional. Vigilancia humana, tecnología e innovación al servicio de sus operaciones.</p>' +
      '<div class="f-contact"><a href="mailto:servicioalcliente@novaseguridad.com.co">servicioalcliente@novaseguridad.com.co</a><br>' +
      '<a href="tel:+576044484052">+57 604 448 40 52</a> · <a href="' + WA + '">+57 320 787 3338</a></div>' +
      '<div class="f-soc">' +
      '<a href="https://www.facebook.com/Novasep.Seguridad/?locale=es_LA" target="_blank" rel="noopener" aria-label="Facebook">' + icon('facebook') + '</a>' +
      '<a href="https://co.linkedin.com/company/nova-seguridad-privada-limitada" target="_blank" rel="noopener" aria-label="LinkedIn">' + icon('linkedin') + '</a>' +
      '<a href="https://www.instagram.com/novasep.seguridad/?hl=es" target="_blank" rel="noopener" aria-label="Instagram">' + icon('instagram') + '</a></div></div>' +
      '<div class="f-col"><h4>Servicios</h4><ul>' +
      '<li><a href="srv-vigilancia-humana.html">Vigilancia humana</a></li>' +
      '<li><a href="srv-escoltas.html">Escoltas</a></li>' +
      '<li><a href="srv-drones.html">Drones de seguridad</a></li>' +
      '<li><a href="srv-seguridad-electronica.html">Seguridad electrónica</a></li>' +
      '<li><a href="srv-analisis-riesgos.html">Análisis de riesgos</a></li></ul></div>' +
      '<div class="f-col"><h4>Empresa</h4><ul>' +
      '<li><a href="quienes-somos.html">Quiénes somos</a></li>' +
      '<li><a href="sectores.html">Sectores</a></li>' +
      '<li><a href="certificaciones.html">Certificaciones</a></li>' +
      '<li><a href="trabaja.html">Trabaja con nosotros</a></li>' +
      '<li><a href="contacto.html">Contacto</a></li></ul></div>' +
      '<div class="f-col"><h4>Sedes</h4><ul>' +
      '<li><a href="contacto.html">Medellín — Principal</a></li>' +
      '<li><a href="contacto.html">Bogotá</a></li>' +
      '<li><a href="contacto.html">Barranquilla</a></li>' +
      '<li><a href="contacto.html">Apartadó</a></li></ul></div>' +
      '</div>' +
      '<div class="footer-bottom"><p>© ' + new Date().getFullYear() + ' Nova Seguridad Privada Ltda. Todos los derechos reservados.</p>' +
      '<p><a href="#">Política de privacidad</a> · <a href="#">Términos y condiciones</a></p></div>' +
      '</div>';
    document.body.appendChild(f);
  }

  /* ---------- WhatsApp FAB ---------- */
  function buildWhatsApp() {
    var a = document.createElement('a');
    a.className = 'wa-fab';
    a.href = WA;
    a.target = '_blank';
    a.rel = 'noopener';
    a.setAttribute('aria-label', 'Escríbenos por WhatsApp');
    a.innerHTML = '<span class="wa-circle">' + icon('whatsapp') + '</span><span class="wa-text">Escríbenos</span>';
    document.body.appendChild(a);
  }

  /* ---------- Accordion ---------- */
  function initAccordions() {
    document.querySelectorAll('.acc-item').forEach(function (item) {
      var trigger = item.querySelector('.acc-trigger');
      var panel = item.querySelector('.acc-panel');
      if (!trigger || !panel) return;
      var startOpen = item.classList.contains('open');
      trigger.setAttribute('aria-expanded', startOpen ? 'true' : 'false');
      if (startOpen) panel.style.height = 'auto';
      trigger.addEventListener('click', function () {
        var open = item.classList.toggle('open');
        trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) { panel.style.height = panel.scrollHeight + 'px'; }
        else { panel.style.height = panel.scrollHeight + 'px'; requestAnimationFrame(function () { panel.style.height = '0px'; }); }
      });
    });
    window.addEventListener('resize', function () {
      document.querySelectorAll('.acc-item.open .acc-panel').forEach(function (p) { p.style.height = p.scrollHeight + 'px'; });
    });
  }

  /* ---------- EmailJS Configuration ---------- */
  var EMAILJS_CONFIG = {
    publicKey: 'YOUR_PUBLIC_KEY',                // Clave pública (Public Key) de EmailJS
    serviceId: 'YOUR_SERVICE_ID',                // Service ID (ej: service_novasep)
    templateIdContact: 'YOUR_TEMPLATE_ID_CONTACT', // Template ID para formularios de contacto / cotizaciones
    templateIdCV: 'YOUR_TEMPLATE_ID_CV'           // Template ID para hojas de vida (Trabaja con nosotros)
  };
  window.EMAILJS_CONFIG = EMAILJS_CONFIG;

  var emailjsScriptPromise = null;
  function loadEmailJS() {
    if (window.emailjs) return Promise.resolve();
    if (emailjsScriptPromise) return emailjsScriptPromise;
    emailjsScriptPromise = new Promise(function (resolve, reject) {
      var script = document.createElement('script');
      script.src = 'https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js';
      script.async = true;
      script.onload = function () {
        if (window.emailjs && EMAILJS_CONFIG.publicKey && EMAILJS_CONFIG.publicKey !== 'YOUR_PUBLIC_KEY') {
          try { window.emailjs.init({ publicKey: EMAILJS_CONFIG.publicKey }); } catch (err) {}
        }
        resolve();
      };
      script.onerror = function (err) {
        emailjsScriptPromise = null;
        reject(err);
      };
      document.head.appendChild(script);
    });
    return emailjsScriptPromise;
  }

  /* ---------- Form submission (EmailJS) ---------- */
  function initForms() {
    var forms = document.querySelectorAll('form[data-emailjs], form[data-formspree]');
    forms.forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var btn = form.querySelector('[type="submit"]');
        if (!btn || btn.disabled) return;

        var type = form.getAttribute('data-emailjs') || (form.getAttribute('aria-label') && form.getAttribute('aria-label').toLowerCase().indexOf('hoja') !== -1 ? 'cv' : 'contact');
        var customTemplate = form.getAttribute('data-emailjs-template');
        var templateId = customTemplate || (type === 'cv' ? EMAILJS_CONFIG.templateIdCV : EMAILJS_CONFIG.templateIdContact);
        var serviceId = EMAILJS_CONFIG.serviceId;
        var publicKey = EMAILJS_CONFIG.publicKey;

        if (!publicKey || publicKey === 'YOUR_PUBLIC_KEY' || !serviceId || serviceId === 'YOUR_SERVICE_ID' || !templateId || templateId.indexOf('YOUR_TEMPLATE') === 0) {
          alert('EmailJS aún no ha sido configurado con sus claves.\n\nPor favor abra main.js y reemplace EMAILJS_CONFIG con su Public Key, Service ID y Template ID de EmailJS.');
          return;
        }

        var origHTML = btn.innerHTML;
        btn.innerHTML = 'Enviando…';
        btn.disabled = true;

        loadEmailJS().then(function () {
          return emailjs.sendForm(serviceId, templateId, form, publicKey);
        })
        .then(function (r) {
          form.reset();
          btn.innerHTML = '✓ Enviado correctamente';
          setTimeout(function () { btn.innerHTML = origHTML; btn.disabled = false; }, 4000);
        })
        .catch(function (err) {
          console.error('Error al enviar formulario con EmailJS:', err);
          btn.innerHTML = '✕ Error al enviar, intente de nuevo';
          btn.disabled = false;
          setTimeout(function () { btn.innerHTML = origHTML; }, 3500);
        });
      });
    });
  }

  /* ---------- File size validation (5 MB) ---------- */
  function initFileValidation() {
    document.querySelectorAll('input[type="file"]').forEach(function (input) {
      input.addEventListener('change', function () {
        if (this.files[0] && this.files[0].size > 5 * 1024 * 1024) {
          alert('El archivo excede el tamaño máximo de 5 MB. Por favor seleccione un archivo más pequeño.');
          this.value = '';
        } else if (this.files[0]) {
          var label = this.previousElementSibling;
          if (label && label.classList.contains('field-file')) {
            label.innerHTML = novasepIcon('doc') + ' ✓ ' + this.files[0].name;
          }
        }
      });
    });
  }

  /* ---------- Counters ---------- */
  function animateCount(el) {
    var raw = el.getAttribute('data-count');
    var target = parseFloat(raw);
    var prefix = el.getAttribute('data-prefix') || '';
    var suffix = el.getAttribute('data-suffix') || '';
    var dur = 1400, start = null;
    function step(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      var val = Math.round(target * eased);
      el.textContent = prefix + val.toLocaleString('es-CO') + suffix;
      if (p < 1) requestAnimationFrame(step);
      else el.textContent = prefix + target.toLocaleString('es-CO') + suffix;
    }
    requestAnimationFrame(step);
  }

  /* ---------- Scroll animation engine (enter + reverse on exit) ---------- */
  function setFinalCount(c) {
    c.textContent = (c.getAttribute('data-prefix') || '') + (+c.getAttribute('data-count')).toLocaleString('es-CO') + (c.getAttribute('data-suffix') || '');
  }
  function initObservers() {
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var els = [].slice.call(document.querySelectorAll('[data-animate], .reveal'));

    function runCounters(el) {
      var cs = el.matches('[data-count]') ? [el] : [];
      el.querySelectorAll('[data-count]').forEach(function (c) { cs.push(c); });
      cs.forEach(function (c) {
        if (c.dataset.done) return;
        c.dataset.done = '1';
        if (reduce) { setFinalCount(c); return; }
        animateCount(c);
        setTimeout(function () { setFinalCount(c); }, 1700); // failsafe for throttled rAF
      });
    }
    function watchdog(el) {
      // In some embeds/previews the iframe's animation clock is throttled, so the
      // opacity transition stalls at 0. If it hasn't progressed shortly after being
      // marked visible, snap to the final state without an animation.
      setTimeout(function () {
        if (!el.classList.contains('is-visible')) return;
        if (parseFloat(getComputedStyle(el).opacity) >= 0.9) return;
        var prev = el.style.transition;
        el.style.transition = 'none';
        void el.offsetHeight; // force reflow → element jumps to final style
        requestAnimationFrame(function () { el.style.transition = prev; });
      }, 1000);
    }
    function setState(el, vis) {
      if (vis) {
        if (!el.classList.contains('is-visible')) {
          el.classList.add('is-visible');
          el.classList.add('in'); // back-compat with existing .reveal styles
          runCounters(el);
          watchdog(el);
        }
      } else if (!el.hasAttribute('data-animate-once')) {
        // reverse out when scrolled past (either direction)
        el.classList.remove('is-visible');
        el.classList.remove('in');
      }
    }
    function inView(el) {
      var r = el.getBoundingClientRect();
      var vh = window.innerHeight || document.documentElement.clientHeight;
      if (r.height === 0 && r.width === 0) return false;
      return r.top < vh * 0.85 && r.bottom > vh * 0.15;
    }
    function check() { for (var i = 0; i < els.length; i++) setState(els[i], inView(els[i])); }

    // Primary: IntersectionObserver (threshold 0.15) — fires enter & exit
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) { setState(e.target, e.isIntersecting); });
      }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });
      els.forEach(function (el) { io.observe(el); });
    }
    // Fallbacks for embeds where IO / scroll events are throttled
    var last = 0;
    function onScroll() { var n = Date.now(); if (n - last < 60) return; last = n; check(); }
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    check();
    setTimeout(check, 200);
    setTimeout(check, 800);
    window.addEventListener('load', check);
    // Interval fallback for embeds — auto-stops 5s after load to save battery
    var checkInterval = setInterval(check, 260);
    window.addEventListener('load', function () {
      setTimeout(function () { clearInterval(checkInterval); }, 5000);
    });
  }

  /* ---------- Interactive Colombia Map ---------- */
  function initColombiaMap() {
    var mapContainers = document.querySelectorAll('[data-map-container]');
    if (!mapContainers.length) return;

    mapContainers.forEach(function (container) {
      var wrapper = container.querySelector('.map-svg-wrapper');
      var tooltip = container.querySelector('.map-tooltip');
      if (!wrapper || !tooltip) return;

      fetch('media/colombia-map.svg')
        .then(function (res) { return res.text(); })
        .then(function (svgText) {
          wrapper.innerHTML = svgText;
          var svg = wrapper.querySelector('svg');
          if (!svg) return;

          var markers = Array.from(svg.querySelectorAll('.map-marker-item'));
          var activeMarker = null;

          function activateMarker(m) {
            if (activeMarker === m) return;
            if (activeMarker) activeMarker.classList.remove('active');
            activeMarker = m;
            if (!m) {
              tooltip.classList.remove('visible');
              return;
            }

            m.classList.add('active');
            var name = m.getAttribute('data-name') || '';
            var cat = m.getAttribute('data-category') || '';
            var desc = m.getAttribute('data-desc') || '';

            var catEl = tooltip.querySelector('.mt-cat');
            var titleEl = tooltip.querySelector('.mt-title');
            var descEl = tooltip.querySelector('.mt-desc');

            if (catEl) catEl.textContent = cat;
            if (titleEl) titleEl.textContent = name;
            if (descEl) descEl.textContent = desc;

            var rect = container.getBoundingClientRect();
            var mRect = m.getBoundingClientRect();

            var posX = (mRect.left + mRect.width / 2) - rect.left;
            var posY = mRect.top - rect.top;

            if (posX > rect.width - 210) posX -= 185;
            else if (posX < 50) posX = 15;
            else posX -= 85;

            if (posY < 90) posY += 30;
            else posY -= 70;

            tooltip.style.left = Math.max(10, Math.min(rect.width - 210, posX)) + 'px';
            tooltip.style.top = Math.max(10, posY) + 'px';
            tooltip.classList.add('visible');
          }

          // Direct hover on marker enlarged hit targets
          markers.forEach(function (m) {
            m.addEventListener('mouseenter', function () { activateMarker(m); });
            m.addEventListener('touchstart', function () { activateMarker(m); }, { passive: true });
          });

          // Proximity detection: trigger when mouse is near ANY node within 50px radius
          container.addEventListener('mousemove', function (e) {
            var rect = container.getBoundingClientRect();
            var mx = e.clientX;
            var my = e.clientY;
            var closest = null;
            var minDist = 55; // 55px threshold proximity

            markers.forEach(function (m) {
              var mRect = m.getBoundingClientRect();
              var cx = mRect.left + mRect.width / 2;
              var cy = mRect.top + mRect.height / 2;
              var dist = Math.hypot(mx - cx, my - cy);
              if (dist < minDist) {
                minDist = dist;
                closest = m;
              }
            });

            if (closest) {
              activateMarker(closest);
            } else if (activeMarker) {
              activeMarker.classList.remove('active');
              activeMarker = null;
              tooltip.classList.remove('visible');
            }
          });

          container.addEventListener('mouseleave', function () {
            if (activeMarker) activeMarker.classList.remove('active');
            activeMarker = null;
            tooltip.classList.remove('visible');
          });
        })
        .catch(function (err) {
          console.warn('Map inline load fallback', err);
        });
    });
  }

  /* ---------- Init ---------- */
  function novasepInit() {
    buildSprite();
    buildHeader(document.body.getAttribute('data-page') || '');
    initAccordions();
    initObservers();
    initForms();
    initFileValidation();
    initColombiaMap();
    if (!document.body.hasAttribute('data-no-footer')) buildFooter();
    buildWhatsApp();
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', novasepInit);
  } else {
    novasepInit();
  }
})();
