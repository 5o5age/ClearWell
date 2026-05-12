// Floating Leaves — guide leaves into pools using your cursor.

const LEAF_SPAWN_MIN_MS   = 850;
const LEAF_SPAWN_MAX_MS   = 1600;
const LEAF_BASE_SPEED     = 42;
const LEAF_STEER          = 1.4;
const CURSOR_RADIUS       = 150;
const CURSOR_STRENGTH     = 220;
const POOL_RADIUS         = 44;
const POOL_GATHER_BONUS_MS = 1400;

const LEAF_TINTS = [
    ['oklch(0.82 0.13 85)',  'oklch(0.62 0.14 70)',  'oklch(0.45 0.12 60)'],
    ['oklch(0.72 0.12 55)',  'oklch(0.52 0.14 40)',  'oklch(0.38 0.10 35)'],
    ['oklch(0.76 0.10 140)', 'oklch(0.55 0.11 145)', 'oklch(0.38 0.08 140)'],
    ['oklch(0.80 0.07 155)', 'oklch(0.62 0.08 160)', 'oklch(0.44 0.06 150)'],
    ['oklch(0.70 0.14 35)',  'oklch(0.50 0.15 30)',  'oklch(0.35 0.11 28)'],
];

export function init(root) {
    root.innerHTML = '';
    root.classList.remove('items-center', 'justify-center');
    root.classList.add('relative');

    if (!document.getElementById('floating-leaves-styles')) {
        const style = document.createElement('style');
        style.id = 'floating-leaves-styles';
        style.textContent = STYLES;
        document.head.appendChild(style);
    }

    const canvas = document.createElement('canvas');
    canvas.className = 'fl-canvas';
    root.appendChild(canvas);

    const hud = document.createElement('div');
    hud.className = 'fl-hud';
    hud.innerHTML = `
        <div class="fl-stat">
            <span class="fl-stat-label">Gathered</span>
            <span class="fl-stat-value" data-gathered>0</span>
        </div>
        <div class="fl-stat">
            <span class="fl-stat-label">Drifting</span>
            <span class="fl-stat-value" data-drifting>0</span>
        </div>
    `;
    root.appendChild(hud);

    const hint = document.createElement('div');
    hint.className = 'fl-hint';
    hint.textContent = 'Guide the leaves with your cursor';
    root.appendChild(hint);

    const ctx = canvas.getContext('2d');
    const flowLines = buildFlowLines();
    const state = {
        leaves: [],
        sparkles: [],
        pools: [],
        gathered: 0,
        cursor: { x: -9999, y: -9999, active: false },
        lastSpawn: 0,
        nextSpawnDelay: rand(LEAF_SPAWN_MIN_MS, LEAF_SPAWN_MAX_MS),
        running: true,
        w: 0,
        h: 0,
        dpr: 1,
        primary: readOklch('--p', { l: 0.72, c: 0.09, h: 210 }),
        hintSeen: false,
    };

    const gatheredEl = hud.querySelector('[data-gathered]');
    const driftingEl = hud.querySelector('[data-drifting]');

    function resize() {
        const rect = root.getBoundingClientRect();
        state.w = rect.width;
        state.h = rect.height;
        state.dpr = window.devicePixelRatio || 1;
        canvas.width  = Math.round(state.w * state.dpr);
        canvas.height = Math.round(state.h * state.dpr);
        canvas.style.width  = state.w + 'px';
        canvas.style.height = state.h + 'px';
        ctx.setTransform(state.dpr, 0, 0, state.dpr, 0, 0);
        layoutPools(state);
    }
    resize();

    const ro = new ResizeObserver(resize);
    ro.observe(root);

    const themeObs = new MutationObserver(() => {
        state.primary = readOklch('--p', state.primary);
    });
    themeObs.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme', 'class'] });

    function onMove(e) {
        const rect = canvas.getBoundingClientRect();
        state.cursor.x = e.clientX - rect.left;
        state.cursor.y = e.clientY - rect.top;
        state.cursor.active = true;
        if (!state.hintSeen) {
            state.hintSeen = true;
            hint.classList.add('fl-hint-hide');
        }
    }
    function onLeave() { state.cursor.active = false; }

    canvas.addEventListener('pointermove', onMove);
    canvas.addEventListener('pointerleave', onLeave);

    let lastFrame = performance.now();
    function frame(now) {
        if (!state.running) return;
        const dt = Math.min(0.05, (now - lastFrame) / 1000);
        lastFrame = now;

        if (now - state.lastSpawn > state.nextSpawnDelay) {
            spawnLeaf(state);
            state.lastSpawn = now;
            state.nextSpawnDelay = rand(LEAF_SPAWN_MIN_MS, LEAF_SPAWN_MAX_MS);
        }

        updateLeaves(state, dt, now);
        updateSparkles(state, dt);

        ctx.clearRect(0, 0, state.w, state.h);
        paintBackground(ctx, state);
        drawFlowLines(ctx, state, flowLines, now);
        drawPools(ctx, state, now);
        drawLeaves(ctx, state);
        drawSparkles(ctx, state);
        drawCursorField(ctx, state);

        driftingEl.textContent = state.leaves.length;

        requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);

    const observer = new MutationObserver(() => {
        if (!document.body.contains(root)) {
            state.running = false;
            canvas.removeEventListener('pointermove', onMove);
            canvas.removeEventListener('pointerleave', onLeave);
            ro.disconnect();
            themeObs.disconnect();
            observer.disconnect();
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });

    state.onGather = () => {
        state.gathered += 1;
        gatheredEl.textContent = state.gathered;
        bump(gatheredEl);
    };
}

function layoutPools(state) {
    const marginRight = 72;
    const x = state.w - marginRight;
    const slots = 3;
    state.pools = [];
    for (let i = 0; i < slots; i++) {
        const t = (i + 1) / (slots + 1);      // 0.25, 0.5, 0.75
        state.pools.push({
            x,
            y: state.h * t,
            r: POOL_RADIUS,
            lastHit: -99999,
            count: 0,
        });
    }
}

function spawnLeaf(state) {
    const tint = LEAF_TINTS[Math.floor(Math.random() * LEAF_TINTS.length)];
    state.leaves.push({
        x: -30,
        y: rand(state.h * 0.15, state.h * 0.85),
        vx: LEAF_BASE_SPEED,
        vy: 0,
        rot: rand(-0.3, 0.3),
        rotWobble: rand(0, Math.PI * 2),
        size: rand(11, 16),
        tint,
        born: performance.now(),
        dying: 0, // 0..1 when gathered
    });
}

function updateLeaves(state, dt, now) {
    const { cursor } = state;

    for (let i = state.leaves.length - 1; i >= 0; i--) {
        const l = state.leaves[i];

        // Dying → scale down and fade
        if (l.dying > 0) {
            l.dying += dt * 2.4;
            if (l.dying >= 1) state.leaves.splice(i, 1);
            continue;
        }

        // Base flow: rightward drift with a gentle sinusoidal vertical bob
        const flowVx = LEAF_BASE_SPEED + Math.sin(l.y * 0.01 + now * 0.0004) * 6;
        const flowVy = Math.sin(l.x * 0.008 + now * 0.0005) * 10
                     + Math.cos(l.y * 0.013 - now * 0.0003) * 4;

        let ax = (flowVx - l.vx) * LEAF_STEER;
        let ay = (flowVy - l.vy) * LEAF_STEER;

        // Cursor attraction
        if (cursor.active) {
            const dx = cursor.x - l.x;
            const dy = cursor.y - l.y;
            const d2 = dx * dx + dy * dy;
            if (d2 < CURSOR_RADIUS * CURSOR_RADIUS) {
                const d = Math.sqrt(d2) + 0.001;
                const falloff = 1 - d / CURSOR_RADIUS;
                const pull = CURSOR_STRENGTH * falloff * falloff;
                ax += (dx / d) * pull;
                ay += (dy / d) * pull;
            }
        }

        l.vx += ax * dt;
        l.vy += ay * dt;

        // Mild speed cap
        const vmax = 180;
        const speed = Math.hypot(l.vx, l.vy);
        if (speed > vmax) {
            l.vx *= vmax / speed;
            l.vy *= vmax / speed;
        }

        l.x += l.vx * dt;
        l.y += l.vy * dt;

        // Steer rotation toward travel direction, with wobble
        const targetRot = Math.atan2(l.vy, l.vx);
        l.rot += shortestAngle(targetRot - l.rot) * 0.08;
        l.rotWobble += dt * 1.4;

        // Pool collision
        for (const p of state.pools) {
            const ddx = p.x - l.x;
            const ddy = p.y - l.y;
            if (ddx * ddx + ddy * ddy < (p.r - 6) * (p.r - 6)) {
                gatherLeaf(state, l, p, now);
                break;
            }
        }

        // Drift off the right or top/bottom
        if (l.x > state.w + 40 || l.y < -40 || l.y > state.h + 40) {
            state.leaves.splice(i, 1);
        }
    }
}

function gatherLeaf(state, leaf, pool, now) {
    leaf.dying = 0.0001;
    pool.lastHit = now;
    pool.count += 1;
    spawnSparkles(state, leaf.x, leaf.y, leaf.tint);
    if (state.onGather) state.onGather();
}

function spawnSparkles(state, x, y, tint) {
    for (let i = 0; i < 8; i++) {
        const a = rand(0, Math.PI * 2);
        const s = rand(40, 90);
        state.sparkles.push({
            x, y,
            vx: Math.cos(a) * s,
            vy: Math.sin(a) * s,
            life: 1,
            color: tint[0],
        });
    }
}

function updateSparkles(state, dt) {
    for (let i = state.sparkles.length - 1; i >= 0; i--) {
        const s = state.sparkles[i];
        s.x += s.vx * dt;
        s.y += s.vy * dt;
        s.vx *= 0.94;
        s.vy *= 0.94;
        s.life -= dt * 1.4;
        if (s.life <= 0) state.sparkles.splice(i, 1);
    }
}

function paintBackground(ctx, state) {
    const { w, h, primary } = state;
    const g = ctx.createLinearGradient(0, 0, 0, h);
    g.addColorStop(0, `oklch(${primary.l + 0.02} ${primary.c * 0.5} ${primary.h} / 0.08)`);
    g.addColorStop(1, `oklch(${primary.l - 0.05} ${primary.c * 0.3} ${primary.h} / 0.02)`);
    ctx.fillStyle = g;
    ctx.fillRect(0, 0, w, h);
}

function buildFlowLines() {
    const lines = [];
    for (let i = 0; i < 7; i++) {
        lines.push({
            yFrac: 0.1 + (i / 6) * 0.8,
            amp: rand(4, 14),
            phase: rand(0, Math.PI * 2),
            freq: rand(0.007, 0.014),
            speed: rand(0.0004, 0.0009),
        });
    }
    return lines;
}

function drawFlowLines(ctx, state, lines, now) {
    ctx.lineWidth = 1;
    for (const line of lines) {
        ctx.beginPath();
        for (let x = 0; x <= state.w; x += 8) {
            const y = line.yFrac * state.h
                    + Math.sin(x * line.freq + now * line.speed + line.phase) * line.amp;
            if (x === 0) ctx.moveTo(x, y);
            else ctx.lineTo(x, y);
        }
        ctx.strokeStyle = `oklch(var(--bc) / 0.05)`;
        ctx.stroke();
    }
}

function drawPools(ctx, state, now) {
    const { primary } = state;
    for (const p of state.pools) {
        const pulse = Math.max(0, 1 - (now - p.lastHit) / POOL_GATHER_BONUS_MS);
        const outerR = p.r * (1 + pulse * 0.55);

        const glow = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, outerR * 1.6);
        glow.addColorStop(0, `oklch(${primary.l + 0.05} ${primary.c} ${primary.h} / ${(0.22 + pulse * 0.35).toFixed(3)})`);
        glow.addColorStop(0.55, `oklch(${primary.l} ${primary.c} ${primary.h} / ${(0.06 + pulse * 0.10).toFixed(3)})`);
        glow.addColorStop(1, `oklch(${primary.l} ${primary.c} ${primary.h} / 0)`);
        ctx.fillStyle = glow;
        ctx.beginPath();
        ctx.arc(p.x, p.y, outerR * 1.6, 0, Math.PI * 2);
        ctx.fill();

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.strokeStyle = `oklch(${primary.l} ${primary.c} ${primary.h} / ${(0.35 + pulse * 0.35).toFixed(3)})`;
        ctx.lineWidth = 1.2;
        ctx.stroke();

        if (p.count > 0) {
            ctx.fillStyle = `oklch(var(--bc) / 0.45)`;
            ctx.font = '500 0.75rem "DM Sans", sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(p.count, p.x, p.y);
        }
    }
}

function drawLeaves(ctx, state) {
    for (const l of state.leaves) {
        const alpha = l.dying > 0 ? Math.max(0, 1 - l.dying) : 1;
        const scale = l.dying > 0 ? (1 + l.dying * 0.4) : 1;

        ctx.save();
        ctx.translate(l.x, l.y);
        const wobble = Math.sin(l.rotWobble) * 0.15;
        ctx.rotate(l.rot + wobble);
        ctx.scale(scale, scale);
        ctx.globalAlpha = alpha;

        const s = l.size;

        // Leaf teardrop path
        ctx.beginPath();
        ctx.moveTo(-s, 0);
        ctx.quadraticCurveTo(-s * 0.2, -s * 0.58, s, 0);
        ctx.quadraticCurveTo(-s * 0.2, s * 0.58, -s, 0);
        ctx.closePath();

        const grad = ctx.createLinearGradient(-s, -s * 0.4, s, s * 0.4);
        grad.addColorStop(0, l.tint[1]);
        grad.addColorStop(1, l.tint[0]);
        ctx.fillStyle = grad;
        ctx.fill();

        // Midrib vein
        ctx.beginPath();
        ctx.moveTo(-s * 0.85, 0);
        ctx.lineTo(s * 0.85, 0);
        ctx.strokeStyle = l.tint[2];
        ctx.lineWidth = 0.8;
        ctx.stroke();

        // Subtle side veins
        ctx.lineWidth = 0.5;
        for (let i = -2; i <= 2; i++) {
            if (i === 0) continue;
            const vx = i * s * 0.28;
            ctx.beginPath();
            ctx.moveTo(vx, 0);
            ctx.quadraticCurveTo(vx + s * 0.05, -s * 0.18, vx - s * 0.15, -s * 0.32);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(vx, 0);
            ctx.quadraticCurveTo(vx + s * 0.05, s * 0.18, vx - s * 0.15, s * 0.32);
            ctx.stroke();
        }

        ctx.restore();
    }
}

function drawSparkles(ctx, state) {
    for (const s of state.sparkles) {
        const a = Math.max(0, s.life);
        ctx.fillStyle = s.color.replace(')', ` / ${a.toFixed(3)})`);
        ctx.beginPath();
        ctx.arc(s.x, s.y, 1.8 + a * 1.2, 0, Math.PI * 2);
        ctx.fill();
    }
}

function drawCursorField(ctx, state) {
    if (!state.cursor.active) return;
    const { cursor, primary } = state;
    const g = ctx.createRadialGradient(cursor.x, cursor.y, 0, cursor.x, cursor.y, CURSOR_RADIUS);
    g.addColorStop(0, `oklch(${primary.l} ${primary.c} ${primary.h} / 0.10)`);
    g.addColorStop(1, `oklch(${primary.l} ${primary.c} ${primary.h} / 0)`);
    ctx.fillStyle = g;
    ctx.beginPath();
    ctx.arc(cursor.x, cursor.y, CURSOR_RADIUS, 0, Math.PI * 2);
    ctx.fill();

    ctx.beginPath();
    ctx.arc(cursor.x, cursor.y, 4, 0, Math.PI * 2);
    ctx.fillStyle = `oklch(${primary.l} ${primary.c} ${primary.h} / 0.55)`;
    ctx.fill();
}

function rand(min, max) { return min + Math.random() * (max - min); }

function shortestAngle(a) {
    while (a > Math.PI)  a -= Math.PI * 2;
    while (a < -Math.PI) a += Math.PI * 2;
    return a;
}

function bump(el) {
    el.classList.remove('fl-bump');
    void el.offsetWidth;
    el.classList.add('fl-bump');
}

function readOklch(varName, fallback) {
    const raw = getComputedStyle(document.documentElement).getPropertyValue(varName).trim();
    if (raw) {
        const parts = raw.split(/\s+/);
        const l = parseFloat(parts[0]);
        const c = parseFloat(parts[1]);
        const h = parseFloat(parts[2]);
        if (Number.isFinite(l) && Number.isFinite(c) && Number.isFinite(h)) {
            return { l, c, h };
        }
    }
    return fallback;
}

const STYLES = `
.fl-canvas {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border-radius: inherit;
    cursor: none;
    touch-action: none;
    display: block;
}
.fl-hud {
    position: absolute; top: 16px; left: 20px;
    display: flex; gap: 24px;
    pointer-events: none;
    z-index: 2;
}
.fl-stat { display: flex; flex-direction: column; gap: 2px; }
.fl-stat-label {
    font-size: 0.65rem;
    font-weight: 600;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: oklch(var(--bc) / 0.4);
}
.fl-stat-value {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 500;
    color: oklch(var(--bc) / 0.85);
    font-variant-numeric: tabular-nums;
    display: inline-block;
}
.fl-bump { animation: fl-bump .28s ease-out; }
@keyframes fl-bump {
    0% { transform: scale(1); }
    40% { transform: scale(1.25); color: oklch(var(--p)); }
    100% { transform: scale(1); }
}

.fl-hint {
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
    transition: opacity .7s ease;
}
.fl-hint-hide { opacity: 0; }
`;
