// Focus Ripples — click the pond, watch the ripples settle.

const RING_SPEED_PX_PER_SEC = 140;
const RING_LIFETIME_MS      = 4200;
const RING_COUNT_PER_TAP    = 4;
const RING_SPACING_PX       = 22;
const AMBIENT_INTERVAL_MS   = 7000;
const POINTER_TRAIL_EVERY_MS = 120;

export function init(root) {
    root.innerHTML = '';
    root.classList.remove('items-center', 'justify-center');
    root.classList.add('relative');

    if (!document.getElementById('focus-ripples-styles')) {
        const style = document.createElement('style');
        style.id = 'focus-ripples-styles';
        style.textContent = STYLES;
        document.head.appendChild(style);
    }

    const canvas = document.createElement('canvas');
    canvas.className = 'fr-canvas';
    root.appendChild(canvas);

    const hud = document.createElement('div');
    hud.className = 'fr-hud';
    hud.innerHTML = `
        <div class="fr-stat">
            <span class="fr-stat-label">Ripples</span>
            <span class="fr-stat-value" data-ripples>0</span>
        </div>
        <div class="fr-stat">
            <span class="fr-stat-label">Stillness</span>
            <span class="fr-stat-value" data-stillness>0:00</span>
        </div>
    `;
    root.appendChild(hud);

    const hint = document.createElement('div');
    hint.className = 'fr-hint';
    hint.textContent = 'Tap the water';
    root.appendChild(hint);

    const ctx = canvas.getContext('2d');
    const state = {
        ripples: [],
        taps: 0,
        lastTap: performance.now(),
        lastAmbient: performance.now(),
        lastTrail: 0,
        running: true,
        dpr: window.devicePixelRatio || 1,
        w: 0,
        h: 0,
        primary: readPrimary(),
    };

    const ripplesEl = hud.querySelector('[data-ripples]');
    const stillnessEl = hud.querySelector('[data-stillness]');

    function resize() {
        const rect = root.getBoundingClientRect();
        state.w = rect.width;
        state.h = rect.height;
        state.dpr = window.devicePixelRatio || 1;
        canvas.width = Math.round(state.w * state.dpr);
        canvas.height = Math.round(state.h * state.dpr);
        canvas.style.width = state.w + 'px';
        canvas.style.height = state.h + 'px';
        ctx.setTransform(state.dpr, 0, 0, state.dpr, 0, 0);
    }
    resize();

    const ro = new ResizeObserver(resize);
    ro.observe(root);

    const themeObs = new MutationObserver(() => { state.primary = readPrimary(); });
    themeObs.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme', 'class'] });

    function spawnRipple(x, y, strength = 1) {
        state.ripples.push({
            x, y,
            born: performance.now(),
            strength,
        });
        if (state.ripples.length > 80) state.ripples.shift();
    }

    function onDown(e) {
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        spawnRipple(x, y, 1);
        state.taps += 1;
        state.lastTap = performance.now();
        ripplesEl.textContent = state.taps;
        bump(ripplesEl);
        hint.classList.add('fr-hint-hide');
    }

    function onMove(e) {
        const now = performance.now();
        if (now - state.lastTrail < POINTER_TRAIL_EVERY_MS) return;
        if (e.buttons !== 1 && e.pointerType === 'mouse') return;
        state.lastTrail = now;
        const rect = canvas.getBoundingClientRect();
        spawnRipple(e.clientX - rect.left, e.clientY - rect.top, 0.35);
    }

    canvas.addEventListener('pointerdown', onDown);
    canvas.addEventListener('pointermove', onMove);

    function frame(now) {
        if (!state.running) return;

        ctx.clearRect(0, 0, state.w, state.h);
        paintBackground(ctx, state);

        if (now - state.lastAmbient > AMBIENT_INTERVAL_MS * (0.5 + Math.random())) {
            state.lastAmbient = now;
            spawnRipple(
                rand(state.w * 0.15, state.w * 0.85),
                rand(state.h * 0.15, state.h * 0.85),
                0.22,
            );
        }

        for (let i = state.ripples.length - 1; i >= 0; i--) {
            const r = state.ripples[i];
            const age = now - r.born;
            if (age > RING_LIFETIME_MS) {
                state.ripples.splice(i, 1);
                continue;
            }
            drawRipple(ctx, r, age, state.primary);
        }

        const quiet = Math.floor((now - state.lastTap) / 1000);
        stillnessEl.textContent = formatTime(quiet);

        requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);

    const observer = new MutationObserver(() => {
        if (!document.body.contains(root)) {
            state.running = false;
            canvas.removeEventListener('pointerdown', onDown);
            canvas.removeEventListener('pointermove', onMove);
            ro.disconnect();
            themeObs.disconnect();
            observer.disconnect();
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });
}

function paintBackground(ctx, state) {
    const { w, h } = state;
    const g = ctx.createRadialGradient(w * 0.5, h * 0.55, 40, w * 0.5, h * 0.55, Math.max(w, h) * 0.75);
    g.addColorStop(0, `oklch(${state.primary.l + 0.04} ${state.primary.c * 0.7} ${state.primary.h} / 0.12)`);
    g.addColorStop(1, `oklch(${state.primary.l - 0.05} ${state.primary.c * 0.4} ${state.primary.h} / 0)`);
    ctx.fillStyle = g;
    ctx.fillRect(0, 0, w, h);
}

function drawRipple(ctx, r, age, primary) {
    const t = age / RING_LIFETIME_MS;
    const baseRadius = (age / 1000) * RING_SPEED_PX_PER_SEC;

    for (let i = 0; i < RING_COUNT_PER_TAP; i++) {
        const radius = baseRadius - i * RING_SPACING_PX;
        if (radius <= 0) continue;

        const ageFade  = 1 - t;
        const ringFade = 1 - i / RING_COUNT_PER_TAP;
        const alpha = Math.max(0, 0.55 * ageFade * ringFade * r.strength);
        if (alpha < 0.005) continue;

        ctx.beginPath();
        ctx.arc(r.x, r.y, radius, 0, Math.PI * 2);
        ctx.strokeStyle = `oklch(${primary.l} ${primary.c} ${primary.h} / ${alpha.toFixed(3)})`;
        ctx.lineWidth = 1.6 + (1 - t) * 0.8;
        ctx.stroke();
    }

    if (t < 0.25) {
        const glow = 1 - t / 0.25;
        const grad = ctx.createRadialGradient(r.x, r.y, 0, r.x, r.y, 36);
        grad.addColorStop(0, `oklch(${primary.l} ${primary.c} ${primary.h} / ${(0.35 * glow * r.strength).toFixed(3)})`);
        grad.addColorStop(1, `oklch(${primary.l} ${primary.c} ${primary.h} / 0)`);
        ctx.fillStyle = grad;
        ctx.beginPath();
        ctx.arc(r.x, r.y, 36, 0, Math.PI * 2);
        ctx.fill();
    }
}
function rand(min, max) { return min + Math.random() * (max - min); }

function formatTime(seconds) {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m}:${s.toString().padStart(2, '0')}`;
}

function bump(el) {
    el.classList.remove('fr-bump');
    void el.offsetWidth;
    el.classList.add('fr-bump');
}

function readPrimary() {
    const raw = getComputedStyle(document.documentElement).getPropertyValue('--p').trim();
    if (raw) {
        const parts = raw.split(/\s+/);
        const l = parseFloat(parts[0]);
        const c = parseFloat(parts[1]);
        const h = parseFloat(parts[2]);
        if (Number.isFinite(l) && Number.isFinite(c) && Number.isFinite(h)) {
            return { l, c, h };
        }
    }
    return { l: 0.72, c: 0.09, h: 210 };
}

const STYLES = `
.fr-canvas {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border-radius: inherit;
    cursor: crosshair;
    touch-action: none;
    display: block;
}
.fr-hud {
    position: absolute; top: 16px; left: 20px;
    display: flex; gap: 24px;
    pointer-events: none;
    z-index: 2;
}
.fr-stat { display: flex; flex-direction: column; gap: 2px; }
.fr-stat-label {
    font-size: 0.65rem;
    font-weight: 600;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: oklch(var(--bc) / 0.4);
}
.fr-stat-value {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 500;
    color: oklch(var(--bc) / 0.85);
    font-variant-numeric: tabular-nums;
    display: inline-block;
}
.fr-bump { animation: fr-bump .28s ease-out; }
@keyframes fr-bump {
    0% { transform: scale(1); }
    40% { transform: scale(1.25); color: oklch(var(--p)); }
    100% { transform: scale(1); }
}

.fr-hint {
    position: absolute; bottom: 16px; left: 50%;
    transform: translateX(-50%);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    letter-spacing: 0.05em;
    color: oklch(var(--bc) / 0.5);
    padding: 8px 18px;
    background: oklch(var(--b1) / 0.7);
    border: 1px solid oklch(var(--bc) / 0.1);
    border-radius: 9999px;
    backdrop-filter: blur(6px);
    z-index: 2;
    pointer-events: none;
    transition: opacity .6s ease;
}
.fr-hint-hide { opacity: 0; }
`;
