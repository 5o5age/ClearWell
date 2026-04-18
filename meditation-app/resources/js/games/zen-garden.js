// Zen Garden — rake sand into patterns. Place stones. Breathe slowly.
// Canvas-based. Two layers: rake marks below, stones on top (so rake flows
// naturally around stones — the stones visually occlude the grooves).

const TINES          = 7;       // number of rake teeth
const TINE_SPACING   = 7;       // px between tines
const TINE_WIDTH     = 1.6;     // stroke width
const GROOVE_ALPHA   = 0.55;    // darkness of each groove
const SMOOTH_RADIUS  = 38;      // smoothing brush radius
const STONE_COLORS   = [
    ['#3e3a36', '#1a1816'],
    ['#4a443f', '#24211e'],
    ['#2f2d2b', '#131211'],
    ['#52433a', '#231c17'],
];

export function init(root) {
    root.innerHTML = '';
    root.classList.remove('items-center', 'justify-center');
    root.classList.add('relative');

    if (!document.getElementById('zen-garden-styles')) {
        const style = document.createElement('style');
        style.id = 'zen-garden-styles';
        style.textContent = STYLES;
        document.head.appendChild(style);
    }

    root.innerHTML = `
        <div class="zg-scene">
            <div class="zg-sand"></div>
            <canvas class="zg-canvas zg-rake-canvas" data-rake></canvas>
            <canvas class="zg-canvas zg-stones-canvas" data-stones></canvas>

            <div class="zg-cursor" data-cursor aria-hidden="true"></div>

            <div class="zg-hud">
                <div class="zg-brand">
                    <span class="zg-brand-kicker">Mindful play</span>
                    <span class="zg-brand-title">Zen Garden</span>
                </div>
            </div>

            <div class="zg-toolbar" role="toolbar" aria-label="Tools">
                <button type="button" class="zg-tool is-active" data-tool="rake" aria-pressed="true" title="Rake (R)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                        <path d="M4 18c3-3 6-3 8 0s5 3 8 0"/>
                        <path d="M4 14c3-3 6-3 8 0s5 3 8 0"/>
                        <path d="M4 10c3-3 6-3 8 0s5 3 8 0"/>
                    </svg>
                    <span>Rake</span>
                </button>
                <button type="button" class="zg-tool" data-tool="stone" aria-pressed="false" title="Stone (S)">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <ellipse cx="12" cy="13" rx="8" ry="5.5"/>
                    </svg>
                    <span>Stone</span>
                </button>
                <button type="button" class="zg-tool" data-tool="smooth" aria-pressed="false" title="Smooth (E)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 16c4-2 8-2 12 0s4 2 4 2"/>
                        <path d="M4 12c4-2 8-2 12 0s4 2 4 2"/>
                    </svg>
                    <span>Smooth</span>
                </button>
                <div class="zg-tool-divider"></div>
                <button type="button" class="zg-tool-ghost" data-action="clear" title="Clear garden">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18"/>
                        <path d="M8 6V4.5A1.5 1.5 0 019.5 3h5A1.5 1.5 0 0116 4.5V6"/>
                        <path d="M6 6l1 13a2 2 0 002 2h6a2 2 0 002-2l1-13"/>
                    </svg>
                </button>
            </div>

            <div class="zg-hint" data-hint>Drag across the sand to rake gentle patterns.</div>
        </div>
    `;

    const scene        = root.querySelector('.zg-scene');
    const rakeCanvas   = root.querySelector('[data-rake]');
    const stonesCanvas = root.querySelector('[data-stones]');
    const cursor       = root.querySelector('[data-cursor]');
    const hint         = root.querySelector('[data-hint]');
    const toolBtns     = root.querySelectorAll('[data-tool]');
    const clearBtn     = root.querySelector('[data-action="clear"]');

    const rake   = rakeCanvas.getContext('2d');
    const stones = stonesCanvas.getContext('2d');

    // Lazy DPR + sizing.
    const dpr = Math.max(1, Math.min(2, window.devicePixelRatio || 1));
    let W = 0, H = 0;

    const state = {
        tool: 'rake',
        pointerDown: false,
        lastX: 0,
        lastY: 0,
        stones: [],            // { x, y, rx, ry, rot, palette, seed, born }
        hasInteracted: false,
        // Per-stroke state — reset on every fresh pointerdown.
        stroke: {
            prev: null,            // last smoothed point { x, y }
            lastMids: [],          // midpoint of last drawn segment per tine (groove layer)
            lastMidsHi: [],        // same, for highlight layer
        },
    };

    function resize() {
        const rect = scene.getBoundingClientRect();
        W = Math.max(300, Math.floor(rect.width));
        H = Math.max(300, Math.floor(rect.height));

        [rakeCanvas, stonesCanvas].forEach(c => {
            c.width  = W * dpr;
            c.height = H * dpr;
            c.style.width  = W + 'px';
            c.style.height = H + 'px';
        });
        rake.setTransform(dpr, 0, 0, dpr, 0, 0);
        stones.setTransform(dpr, 0, 0, dpr, 0, 0);

        redrawStones();
    }

    // Place a trio of stones at pleasing spots when first mounted.
    function seedStones() {
        const pts = [
            { xr: 0.30, yr: 0.55, size: 1.0 },
            { xr: 0.58, yr: 0.44, size: 1.3 },
            { xr: 0.72, yr: 0.68, size: 0.75 },
        ];
        for (const p of pts) addStone(p.xr * W, p.yr * H, p.size, false);
        redrawStones();
    }

    function addStone(x, y, sizeMul = 1, animate = true) {
        const baseR = 26 + Math.random() * 14;
        const rx = baseR * sizeMul;
        const ry = rx * (0.6 + Math.random() * 0.15);
        const rot = (Math.random() - 0.5) * 0.6;
        const palette = STONE_COLORS[Math.floor(Math.random() * STONE_COLORS.length)];
        state.stones.push({
            x, y, rx, ry, rot, palette,
            seed: Math.random() * 1000,
            born: animate ? performance.now() : 0,
            animating: animate,
        });
    }

    function redrawStones() {
        stones.clearRect(0, 0, W, H);
        for (const s of state.stones) {
            drawStone(stones, s);
        }
    }

    function drawStone(ctx, s) {
        const now = performance.now();
        let scale = 1;
        if (s.animating) {
            const age = now - s.born;
            if (age < 420) {
                const t = age / 420;
                // Overshoot → settle.
                scale = t < 0.6
                    ? easeOutBack(t / 0.6)
                    : 1 + (Math.sin((t - 0.6) * Math.PI * 4) * 0.03 * (1 - (t - 0.6) / 0.4));
            } else {
                s.animating = false;
                scale = 1;
            }
        }

        ctx.save();
        ctx.translate(s.x, s.y);
        ctx.rotate(s.rot);
        ctx.scale(scale, scale);

        // Ground shadow — long & soft.
        ctx.save();
        ctx.translate(3, 4);
        ctx.filter = 'blur(6px)';
        ctx.fillStyle = 'rgba(60, 45, 25, 0.35)';
        ctx.beginPath();
        ctx.ellipse(0, 0, s.rx * 1.05, s.ry * 0.9, 0, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();

        // Stone body gradient.
        const grad = ctx.createRadialGradient(
            -s.rx * 0.35, -s.ry * 0.6, s.ry * 0.2,
            0, 0, s.rx
        );
        grad.addColorStop(0, s.palette[0]);
        grad.addColorStop(1, s.palette[1]);
        ctx.fillStyle = grad;
        ctx.beginPath();
        ctx.ellipse(0, 0, s.rx, s.ry, 0, 0, Math.PI * 2);
        ctx.fill();

        // Subtle rim light.
        ctx.strokeStyle = 'rgba(255, 245, 220, 0.10)';
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.ellipse(0, 0, s.rx - 0.5, s.ry - 0.5, 0, 0, Math.PI * 2);
        ctx.stroke();

        // Highlight streak.
        ctx.save();
        ctx.globalAlpha = 0.18;
        ctx.fillStyle = '#ffffff';
        ctx.beginPath();
        ctx.ellipse(-s.rx * 0.25, -s.ry * 0.55, s.rx * 0.45, s.ry * 0.18, -0.2, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();

        ctx.restore();
    }

    // ── Rake drawing (smooth) ───────────────────────────────
    // Technique: EMA-smooth the input pointer stream, then draw each new
    // segment as a quadratic Bézier curve from the *previous midpoint*,
    // through the current raw point (used as control), to the new midpoint.
    // This is the classic "midpoint smoothing" trick used by drawing apps —
    // curves blend seamlessly at each point and hide direction-change kinks.

    const RAKE_EMA      = 0.5;   // 0 = no movement (laggy), 1 = no smoothing (raw)
    const MIN_STEP_DIST = 1.25;  // ignore micro-jitter below this distance

    function rakeStart(x, y) {
        state.stroke.prev = { x, y };
        state.stroke.lastMids   = new Array(TINES).fill(null);
        state.stroke.lastMidsHi = new Array(TINES).fill(null);
        rakeStamp(x, y);
    }

    function rakeStep(x, y) {
        const prev = state.stroke.prev;
        if (!prev) return;
        const dx = x - prev.x;
        const dy = y - prev.y;
        if (Math.hypot(dx, dy) < MIN_STEP_DIST) return;

        const sx = prev.x + dx * RAKE_EMA;
        const sy = prev.y + dy * RAKE_EMA;

        drawRakeSegment(prev, { x: sx, y: sy });
        state.stroke.prev = { x: sx, y: sy };
    }

    function drawRakeSegment(A, B) {
        const dx = B.x - A.x;
        const dy = B.y - A.y;
        const len = Math.hypot(dx, dy) || 1;
        const nx = -dy / len;
        const ny =  dx / len;

        const midX = (A.x + B.x) / 2;
        const midY = (A.y + B.y) / 2;

        const half = (TINES - 1) / 2;

        rake.save();
        rake.lineCap  = 'round';
        rake.lineJoin = 'round';

        // Groove pass (dark).
        rake.lineWidth   = TINE_WIDTH;
        rake.strokeStyle = `rgba(105, 82, 50, ${GROOVE_ALPHA})`;
        for (let i = 0; i < TINES; i++) {
            const off = (i - half) * TINE_SPACING;
            const ax = A.x    + nx * off, ay = A.y    + ny * off;
            const mx = midX   + nx * off, my = midY   + ny * off;

            rake.beginPath();
            const lm = state.stroke.lastMids[i];
            if (lm) {
                rake.moveTo(lm.x, lm.y);
                rake.quadraticCurveTo(ax, ay, mx, my);
            } else {
                rake.moveTo(ax, ay);
                rake.lineTo(mx, my);
            }
            rake.stroke();
            state.stroke.lastMids[i] = { x: mx, y: my };
        }

        // Highlight pass (thin cream line, shifted perpendicularly).
        rake.lineWidth   = 0.8;
        rake.strokeStyle = 'rgba(255, 248, 225, 0.22)';
        for (let i = 0; i < TINES; i++) {
            const off = (i - half) * TINE_SPACING + 1.3;
            const ax = A.x    + nx * off, ay = A.y    + ny * off;
            const mx = midX   + nx * off, my = midY   + ny * off;

            rake.beginPath();
            const lm = state.stroke.lastMidsHi[i];
            if (lm) {
                rake.moveTo(lm.x, lm.y);
                rake.quadraticCurveTo(ax, ay, mx, my);
            } else {
                rake.moveTo(ax, ay);
                rake.lineTo(mx, my);
            }
            rake.stroke();
            state.stroke.lastMidsHi[i] = { x: mx, y: my };
        }

        rake.restore();
    }

    // Tiny mark for single clicks (no drag).
    function rakeStamp(x, y) {
        const half = (TINES - 1) / 2;
        rake.save();
        rake.lineCap     = 'round';
        rake.lineWidth   = TINE_WIDTH;
        rake.strokeStyle = `rgba(105, 82, 50, ${GROOVE_ALPHA})`;
        for (let i = 0; i < TINES; i++) {
            const off = (i - half) * TINE_SPACING;
            rake.beginPath();
            rake.moveTo(x - 1, y + off);
            rake.lineTo(x + 1, y + off);
            rake.stroke();
        }
        rake.restore();
    }

    // Smooth / erase tool — soft radial erase with destination-out.
    function smoothStroke(x0, y0, x1, y1) {
        const steps = Math.max(1, Math.ceil(Math.hypot(x1 - x0, y1 - y0) / 4));
        rake.save();
        rake.globalCompositeOperation = 'destination-out';
        for (let i = 0; i <= steps; i++) {
            const t = i / steps;
            const x = x0 + (x1 - x0) * t;
            const y = y0 + (y1 - y0) * t;

            const g = rake.createRadialGradient(x, y, 0, x, y, SMOOTH_RADIUS);
            g.addColorStop(0,   'rgba(0,0,0,0.85)');
            g.addColorStop(0.6, 'rgba(0,0,0,0.35)');
            g.addColorStop(1,   'rgba(0,0,0,0)');
            rake.fillStyle = g;
            rake.beginPath();
            rake.arc(x, y, SMOOTH_RADIUS, 0, Math.PI * 2);
            rake.fill();
        }
        rake.restore();
    }

    // ── Pointer handlers ────────────────────────────────────

    function pointFrom(e) {
        const rect = scene.getBoundingClientRect();
        return { x: e.clientX - rect.left, y: e.clientY - rect.top };
    }

    function stoneHitIndex(x, y) {
        for (let i = state.stones.length - 1; i >= 0; i--) {
            const s = state.stones[i];
            const cos = Math.cos(-s.rot), sin = Math.sin(-s.rot);
            const lx = (x - s.x) * cos - (y - s.y) * sin;
            const ly = (x - s.x) * sin + (y - s.y) * cos;
            if ((lx * lx) / (s.rx * s.rx) + (ly * ly) / (s.ry * s.ry) <= 1) return i;
        }
        return -1;
    }

    function onPointerDown(e) {
        // Let toolbar / hud clicks through — they're interactive controls.
        if (e.target.closest('.zg-toolbar, .zg-hud, .zg-hint')) return;

        e.preventDefault();
        scene.setPointerCapture?.(e.pointerId);
        const { x, y } = pointFrom(e);
        state.pointerDown = true;
        state.lastX = x;
        state.lastY = y;
        dismissHint();

        if (state.tool === 'stone') {
            const hit = stoneHitIndex(x, y);
            if (hit >= 0) {
                // Remove stone on click.
                state.stones.splice(hit, 1);
                redrawStones();
            } else {
                addStone(x, y, 0.85 + Math.random() * 0.5, true);
                animateStones();
            }
            return;
        }

        if (state.tool === 'rake') {
            rakeStart(x, y);
        }
    }

    function onPointerMove(e) {
        const overUI = !state.pointerDown && e.target.closest('.zg-toolbar, .zg-hud, .zg-hint');
        cursor.classList.toggle('is-visible', !overUI);

        const { x, y } = pointFrom(e);
        cursor.style.transform = `translate(${x}px, ${y}px) translate(-50%, -50%)`;

        if (!state.pointerDown) return;

        if (state.tool === 'rake') {
            rakeStep(x, y);
        } else if (state.tool === 'smooth') {
            smoothStroke(state.lastX, state.lastY, x, y);
        }
        state.lastX = x;
        state.lastY = y;
    }

    function onPointerUp(e) {
        state.pointerDown = false;
        scene.releasePointerCapture?.(e.pointerId);
    }

    function onPointerEnter() { cursor.classList.add('is-visible'); }
    function onPointerLeave() { cursor.classList.remove('is-visible'); state.pointerDown = false; }

    scene.addEventListener('pointerdown',  onPointerDown);
    scene.addEventListener('pointermove',  onPointerMove);
    scene.addEventListener('pointerup',    onPointerUp);
    scene.addEventListener('pointercancel',onPointerUp);
    scene.addEventListener('pointerenter', onPointerEnter);
    scene.addEventListener('pointerleave', onPointerLeave);

    // ── Tool switching ──────────────────────────────────────

    function setTool(tool) {
        state.tool = tool;
        toolBtns.forEach(b => {
            const active = b.dataset.tool === tool;
            b.classList.toggle('is-active', active);
            b.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
        scene.dataset.tool = tool;
        updateCursor();
    }

    function updateCursor() {
        cursor.dataset.tool = state.tool;
        cursor.style.width  = state.tool === 'smooth' ? SMOOTH_RADIUS * 2 + 'px' : '24px';
        cursor.style.height = state.tool === 'smooth' ? SMOOTH_RADIUS * 2 + 'px' : '24px';
    }

    toolBtns.forEach(btn => btn.addEventListener('click', () => setTool(btn.dataset.tool)));

    clearBtn.addEventListener('click', () => {
        rake.clearRect(0, 0, W, H);
        state.stones = [];
        redrawStones();
    });

    // Keyboard shortcuts.
    function onKey(e) {
        const tag = (e.target && e.target.tagName || '').toLowerCase();
        if (tag === 'input' || tag === 'textarea') return;
        if (e.key === 'r' || e.key === 'R') setTool('rake');
        else if (e.key === 's' || e.key === 'S') setTool('stone');
        else if (e.key === 'e' || e.key === 'E') setTool('smooth');
    }
    window.addEventListener('keydown', onKey);

    // ── Stone placement animations ──────────────────────────

    let animationId = null;
    function animateStones() {
        if (animationId) return;
        const loop = () => {
            redrawStones();
            const stillAnimating = state.stones.some(s => s.animating);
            if (stillAnimating) {
                animationId = requestAnimationFrame(loop);
            } else {
                animationId = null;
            }
        };
        animationId = requestAnimationFrame(loop);
    }

    function dismissHint() {
        if (state.hasInteracted) return;
        state.hasInteracted = true;
        hint.classList.add('is-gone');
    }

    // ── Lifecycle ───────────────────────────────────────────

    resize();
    seedStones();
    updateCursor();

    const ro = new ResizeObserver(() => {
        // Save rake canvas, resize, redraw.
        const tmp = document.createElement('canvas');
        tmp.width  = rakeCanvas.width;
        tmp.height = rakeCanvas.height;
        tmp.getContext('2d').drawImage(rakeCanvas, 0, 0);

        resize();

        // Restore rake marks scaled to new size.
        rake.save();
        rake.setTransform(1, 0, 0, 1, 0, 0);
        rake.drawImage(tmp, 0, 0, rakeCanvas.width, rakeCanvas.height);
        rake.setTransform(dpr, 0, 0, dpr, 0, 0);
        rake.restore();
    });
    ro.observe(scene);

    const observer = new MutationObserver(() => {
        if (!document.body.contains(root)) {
            window.removeEventListener('keydown', onKey);
            ro.disconnect();
            observer.disconnect();
            if (animationId) cancelAnimationFrame(animationId);
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });
}

// ── helpers ─────────────────────────────────────────────────

function easeOutBack(t) {
    const c1 = 1.70158;
    const c3 = c1 + 1;
    return 1 + c3 * Math.pow(t - 1, 3) + c1 * Math.pow(t - 1, 2);
}

// ── styles ──────────────────────────────────────────────────
const STYLES = `
.zg-scene {
    position: absolute; inset: 0;
    border-radius: inherit;
    overflow: hidden;
    isolation: isolate;
    cursor: none;
    touch-action: none;
    user-select: none;
}
/* Toolbar / hud / hint are real UI — restore the normal cursor over them. */
.zg-scene .zg-toolbar,
.zg-scene .zg-toolbar *,
.zg-scene .zg-hud,
.zg-scene .zg-hint { cursor: default; }
.zg-scene .zg-tool,
.zg-scene .zg-tool-ghost { cursor: pointer; }

/* Warm sand base with subtle variation */
.zg-sand {
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse at 25% 20%, #f0e1c1 0%, transparent 55%),
        radial-gradient(ellipse at 75% 70%, #d8c79e 0%, transparent 60%),
        linear-gradient(135deg, #e9d8b4 0%, #d9c59c 100%);
}
/* Fine grain noise via repeating SVG */
.zg-sand::before {
    content: '';
    position: absolute; inset: 0;
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' seed='3'/><feColorMatrix values='0 0 0 0 0.35  0 0 0 0 0.28  0 0 0 0 0.18  0 0 0 0.08 0'/></filter><rect width='100%' height='100%' filter='url(%23n)'/></svg>");
    opacity: 0.9;
    mix-blend-mode: multiply;
    pointer-events: none;
}
/* Outer vignette */
.zg-sand::after {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at center, transparent 55%, rgba(60, 40, 20, 0.25) 100%);
    pointer-events: none;
}

.zg-canvas {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    pointer-events: none;
}
.zg-rake-canvas   { z-index: 1; }
.zg-stones-canvas { z-index: 2; }

/* Custom cursor */
.zg-cursor {
    position: absolute;
    left: 0; top: 0;
    width: 24px; height: 24px;
    border-radius: 9999px;
    border: 1px solid rgba(40, 25, 10, 0.55);
    background: rgba(255, 245, 215, 0.15);
    pointer-events: none;
    z-index: 10;
    opacity: 0;
    transition: opacity 0.15s ease, width 0.2s ease, height 0.2s ease, background 0.2s ease, border-color 0.2s ease;
}
.zg-cursor.is-visible { opacity: 1; }
.zg-cursor[data-tool="smooth"] {
    background: rgba(255, 248, 220, 0.3);
    border-color: rgba(60, 40, 20, 0.3);
}
.zg-cursor[data-tool="stone"] {
    background: rgba(30, 25, 20, 0.3);
    border-color: rgba(30, 25, 20, 0.7);
}

/* HUD (title) */
.zg-hud {
    position: absolute;
    top: 20px; left: 24px;
    z-index: 5;
    pointer-events: none;
    display: flex; flex-direction: column; gap: 2px;
}
.zg-brand-kicker {
    font-size: 0.6rem;
    font-weight: 600;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: rgba(60, 45, 25, 0.55);
}
.zg-brand-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 500;
    color: rgba(45, 30, 15, 0.85);
}

/* Toolbar */
.zg-toolbar {
    position: absolute;
    top: 18px; right: 22px;
    display: flex;
    align-items: center;
    gap: 3px;
    padding: 5px;
    background: rgba(255, 248, 225, 0.72);
    backdrop-filter: blur(14px);
    border: 1px solid rgba(60, 45, 25, 0.1);
    border-radius: 9999px;
    box-shadow:
        0 4px 20px rgba(60, 40, 20, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.5);
    z-index: 5;
}
.zg-tool {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 12px;
    background: transparent;
    border: none;
    border-radius: 9999px;
    color: rgba(60, 45, 25, 0.65);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.78rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}
.zg-tool:hover { color: rgba(45, 30, 15, 0.9); background: rgba(60, 45, 25, 0.05); }
.zg-tool.is-active {
    background: rgba(45, 30, 15, 0.88);
    color: #f5ecd0;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.2);
}
.zg-tool svg { width: 16px; height: 16px; }
.zg-tool-divider {
    width: 1px; height: 20px;
    background: rgba(60, 45, 25, 0.15);
    margin: 0 3px;
}
.zg-tool-ghost {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px;
    background: transparent;
    border: none;
    border-radius: 9999px;
    color: rgba(60, 45, 25, 0.55);
    cursor: pointer;
    transition: all 0.2s ease;
}
.zg-tool-ghost:hover { color: rgba(120, 30, 15, 0.9); background: rgba(120, 30, 15, 0.08); }
.zg-tool-ghost svg { width: 15px; height: 15px; }

/* Hint */
.zg-hint {
    position: absolute;
    bottom: 22px; left: 50%;
    transform: translateX(-50%);
    padding: 7px 16px;
    background: rgba(255, 248, 225, 0.75);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(60, 45, 25, 0.08);
    border-radius: 9999px;
    color: rgba(60, 45, 25, 0.7);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.78rem;
    letter-spacing: 0.01em;
    z-index: 5;
    pointer-events: none;
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.zg-hint.is-gone {
    opacity: 0;
    transform: translate(-50%, 8px);
}
`;
