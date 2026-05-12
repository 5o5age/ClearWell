// Breath Flow — guided breathing patterns.

const PATTERNS = {
    relax: {
        label: 'Atslābt',
        sub: '4 · 7 · 8',
        phases: [
            { name: 'inhale',      text: 'Ieelpo', duration: 4, fromLevel: 0, toLevel: 1 },
            { name: 'hold-top',    text: 'Aizturi',   duration: 7, fromLevel: 1, toLevel: 1 },
            { name: 'exhale',      text: 'Izelpo', duration: 8, fromLevel: 1, toLevel: 0 },
        ],
    },
    box: {
        label: 'Kvadrāts',
        sub: '4 · 4 · 4 · 4',
        phases: [
            { name: 'inhale',      text: 'Ieelpo', duration: 4, fromLevel: 0, toLevel: 1 },
            { name: 'hold-top',    text: 'Aizturi',   duration: 4, fromLevel: 1, toLevel: 1 },
            { name: 'exhale',      text: 'Izelpo', duration: 4, fromLevel: 1, toLevel: 0 },
            { name: 'hold-bottom', text: 'Atpūta',   duration: 4, fromLevel: 0, toLevel: 0 },
        ],
    },
    calm: {
        label: 'Miers',
        sub: '5 · 5',
        phases: [
            { name: 'inhale', text: 'Ieelpo', duration: 5, fromLevel: 0, toLevel: 1 },
            { name: 'exhale', text: 'Izelpo', duration: 5, fromLevel: 1, toLevel: 0 },
        ],
    },
};

const MIN_SCALE = 0.55;
const MAX_SCALE = 1.00;

export function init(root) {
    root.innerHTML = '';
    root.classList.remove('items-center', 'justify-center');
    root.classList.add('relative');

    if (!document.getElementById('breath-flow-styles')) {
        const style = document.createElement('style');
        style.id = 'breath-flow-styles';
        style.textContent = STYLES;
        document.head.appendChild(style);
    }

    root.innerHTML = `
        <div class="bf-scene">

            <div class="bf-timer" data-timer>00:00</div>

            <div class="bf-corner bf-corner-left">
                <span class="bf-corner-label">Cikli</span>
                <span class="bf-corner-value" data-cycles>0</span>
            </div>

            <div class="bf-pattern" role="tablist" aria-label="Elpošanas raksts">
                ${Object.entries(PATTERNS).map(([key, p]) => `
                    <button type="button"
                            class="bf-pattern-btn ${key === 'relax' ? 'is-active' : ''}"
                            data-pattern="${key}"
                            role="tab"
                            aria-selected="${key === 'relax'}">
                        <span class="bf-pattern-label">${p.label}</span>
                        <span class="bf-pattern-sub">${p.sub}</span>
                    </button>
                `).join('')}
            </div>

            <div class="bf-stage">
                <div class="bf-outer-ring"></div>

                <div class="bf-ball" data-ball>
                    <div class="bf-ball-glow"></div>
                    <div class="bf-ball-core"></div>

                    <div class="bf-line-clip">
                        <div class="bf-line-track" data-line>
                            <div class="bf-line"></div>
                        </div>
                    </div>

                    <div class="bf-center-dot"></div>
                </div>
            </div>

            <div class="bf-footer">
                <div class="bf-phase" data-phase>Gatavs</div>
                <div class="bf-bar">
                    <div class="bf-bar-fill" data-bar></div>
                </div>
            </div>

            <div class="bf-controls">
                <button type="button" class="bf-btn" data-play aria-label="Sākt">
                    <svg data-icon-play xmlns="http://www.w3.org/2000/svg" class="bf-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M8 5.14v13.72a1 1 0 001.53.85l11-6.86a1 1 0 000-1.7l-11-6.86A1 1 0 008 5.14z"/>
                    </svg>
                    <svg data-icon-pause xmlns="http://www.w3.org/2000/svg" class="bf-icon bf-hidden" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="6.5" y="5" width="4" height="14" rx="1.2"/>
                        <rect x="13.5" y="5" width="4" height="14" rx="1.2"/>
                    </svg>
                    <span data-play-label>Sākt</span>
                </button>
                <button type="button" class="bf-btn-ghost" data-reset aria-label="Atiestatīt">
                    <svg xmlns="http://www.w3.org/2000/svg" class="bf-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 0115.5-6.3L21 8"/>
                        <path d="M21 3v5h-5"/>
                        <path d="M21 12a9 9 0 01-15.5 6.3L3 16"/>
                        <path d="M3 21v-5h5"/>
                    </svg>
                </button>
            </div>
        </div>
    `;

    const els = {
        scene:       root.querySelector('.bf-scene'),
        ball:        root.querySelector('[data-ball]'),
        line:        root.querySelector('[data-line]'),
        bar:         root.querySelector('[data-bar]'),
        phase:       root.querySelector('[data-phase]'),
        timer:       root.querySelector('[data-timer]'),
        cycles:      root.querySelector('[data-cycles]'),
        play:        root.querySelector('[data-play]'),
        playLabel:   root.querySelector('[data-play-label]'),
        playIcon:    root.querySelector('[data-icon-play]'),
        pauseIcon:   root.querySelector('[data-icon-pause]'),
        reset:       root.querySelector('[data-reset]'),
        patternBtns: root.querySelectorAll('[data-pattern]'),
    };

    const state = {
        patternKey: 'relax',
        phaseIndex: 0,
        phaseStart: 0,
        elapsedInPhase: 0,
        cycles: 0,
        running: false,
        rafId: null,
        sessionStart: 0,
        sessionElapsed: 0,
    };

    const pattern = () => PATTERNS[state.patternKey];
    const phase   = () => pattern().phases[state.phaseIndex];

    function setLevel(level) {
        const scale = MIN_SCALE + (MAX_SCALE - MIN_SCALE) * level;
        els.ball.style.transform = `translate(-50%, -50%) scale(${scale})`;
        els.line.style.transform = `translateY(${(1 - level) * 100}%)`;
    }

    function setBar(progress) {
        els.bar.style.transform = `scaleX(${progress})`;
    }

    function setPhaseText(text) {
        if (els.phase.textContent === text) return;
        els.phase.classList.remove('is-in');
        void els.phase.offsetWidth;
        els.phase.textContent = text;
        els.phase.classList.add('is-in');
    }

    function startPhase(index, now) {
        state.phaseIndex = index;
        state.phaseStart = now;
        state.elapsedInPhase = 0;
        els.scene.dataset.phase = phase().name;
        setPhaseText(phase().text);
    }

    function formatTime(seconds) {
        const s = Math.max(0, Math.floor(seconds));
        const mm = String(Math.floor(s / 60)).padStart(2, '0');
        const ss = String(s % 60).padStart(2, '0');
        return `${mm}:${ss}`;
    }

    function tick(now) {
        if (!state.running) return;

        const p = phase();
        const elapsed = state.elapsedInPhase + (now - state.phaseStart) / 1000;
        const t = Math.min(elapsed / p.duration, 1);

        // Smooth breath curve.
        const eased = easeInOutSine(t);
        const level = p.fromLevel + (p.toLevel - p.fromLevel) * eased;

        setLevel(level);
        setBar(t);

        const totalElapsed = state.sessionElapsed + (now - state.sessionStart) / 1000;
        els.timer.textContent = formatTime(totalElapsed);

        if (t >= 1) {
            const next = (state.phaseIndex + 1) % pattern().phases.length;
            if (next === 0) {
                state.cycles += 1;
                els.cycles.textContent = state.cycles;
                bump(els.cycles);
            }
            startPhase(next, now);
        }

        state.rafId = requestAnimationFrame(tick);
    }

    function play() {
        if (state.running) return;
        state.running = true;
        els.play.classList.add('is-playing');
        els.playIcon.classList.add('bf-hidden');
        els.pauseIcon.classList.remove('bf-hidden');
        els.playLabel.textContent = 'Pauzēt';
        els.scene.classList.add('is-running');

        const now = performance.now();
        state.phaseStart = now;
        state.sessionStart = now;

        if (state.elapsedInPhase === 0) {
            startPhase(state.phaseIndex, now);
        } else {
            els.scene.dataset.phase = phase().name;
            setPhaseText(phase().text);
        }
        state.rafId = requestAnimationFrame(tick);
    }

    function pause() {
        if (!state.running) return;
        state.running = false;
        const now = performance.now();
        state.elapsedInPhase += (now - state.phaseStart) / 1000;
        state.sessionElapsed += (now - state.sessionStart) / 1000;
        cancelAnimationFrame(state.rafId);
        els.play.classList.remove('is-playing');
        els.playIcon.classList.remove('bf-hidden');
        els.pauseIcon.classList.add('bf-hidden');
        els.playLabel.textContent = 'Turpināt';
        els.scene.classList.remove('is-running');
    }

    function reset() {
        pause();
        state.phaseIndex = 0;
        state.elapsedInPhase = 0;
        state.sessionElapsed = 0;
        state.cycles = 0;
        els.cycles.textContent = '0';
        els.timer.textContent = '00:00';
        els.scene.dataset.phase = '';
        setLevel(0);
        setBar(0);
        setPhaseText('Gatavs');
        els.playLabel.textContent = 'Sākt';
    }

    function choosePattern(key) {
        if (key === state.patternKey) return;
        state.patternKey = key;
        els.patternBtns.forEach(btn => {
            const active = btn.dataset.pattern === key;
            btn.classList.toggle('is-active', active);
            btn.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        reset();
    }

    els.play.addEventListener('click', () => state.running ? pause() : play());
    els.reset.addEventListener('click', reset);
    els.patternBtns.forEach(btn => {
        btn.addEventListener('click', () => choosePattern(btn.dataset.pattern));
    });

    function onKey(e) {
        if (e.key === ' ' || e.code === 'Space') {
            const tag = (e.target && e.target.tagName || '').toLowerCase();
            if (tag === 'input' || tag === 'textarea') return;
            e.preventDefault();
            state.running ? pause() : play();
        }
    }
    window.addEventListener('keydown', onKey);

    setLevel(0);
    setBar(0);

    const observer = new MutationObserver(() => {
        if (!document.body.contains(root)) {
            state.running = false;
            if (state.rafId) cancelAnimationFrame(state.rafId);
            window.removeEventListener('keydown', onKey);
            observer.disconnect();
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });
}

function easeInOutSine(t) {
    return 0.5 - 0.5 * Math.cos(Math.PI * t);
}

function bump(el) {
    el.classList.remove('bf-bump');
    void el.offsetWidth;
    el.classList.add('bf-bump');
}

const STYLES = `
.bf-scene {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; align-items: center;
    justify-content: flex-start;
    padding: 32px 24px 28px;
    border-radius: inherit;
    overflow: hidden;
    isolation: isolate;
    background:
        radial-gradient(ellipse at 50% 50%, oklch(var(--b2) / 0.4) 0%, transparent 75%),
        oklch(var(--b1));
}

.bf-timer {
    font-family: 'DM Sans', sans-serif;
    font-size: 2rem;
    font-weight: 500;
    letter-spacing: 0.04em;
    color: oklch(var(--bc) / 0.9);
    font-variant-numeric: tabular-nums;
    margin-bottom: 8px;
}

.bf-corner {
    position: absolute;
    top: 24px;
    display: flex; flex-direction: column; gap: 2px;
    z-index: 3;
}
.bf-corner-left  { left: 28px;  }
.bf-corner-label {
    font-size: 0.6rem;
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: oklch(var(--bc) / 0.35);
}
.bf-corner-value {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem;
    font-weight: 500;
    color: oklch(var(--bc) / 0.8);
    font-variant-numeric: tabular-nums;
}
.bf-bump { animation: bf-bump 0.35s ease-out; }
@keyframes bf-bump {
    0%   { transform: scale(1); }
    40%  { transform: scale(1.18); color: oklch(var(--p)); }
    100% { transform: scale(1); }
}

.bf-pattern {
    position: absolute;
    top: 24px; right: 28px;
    display: flex;
    gap: 2px;
    background: oklch(var(--b2) / 0.5);
    border: 1px solid oklch(var(--bc) / 0.06);
    padding: 3px;
    border-radius: 9999px;
    backdrop-filter: blur(10px);
    z-index: 3;
}
.bf-pattern-btn {
    display: flex; flex-direction: column; align-items: center;
    padding: 5px 14px;
    border-radius: 9999px;
    background: transparent;
    border: none;
    color: oklch(var(--bc) / 0.45);
    cursor: pointer;
    transition: all 0.25s ease;
    line-height: 1.1;
}
.bf-pattern-btn:hover { color: oklch(var(--bc) / 0.75); }
.bf-pattern-btn.is-active {
    background: oklch(var(--p) / 0.14);
    color: oklch(var(--p));
}
.bf-pattern-label {
    font-size: 0.72rem;
    font-weight: 600;
}
.bf-pattern-sub {
    font-size: 0.55rem;
    font-weight: 500;
    opacity: 0.6;
    letter-spacing: 0.08em;
    font-variant-numeric: tabular-nums;
}

.bf-stage {
    position: relative;
    width: min(360px, 72vmin);
    aspect-ratio: 1 / 1;
    display: flex; align-items: center; justify-content: center;
    margin-top: auto;
    margin-bottom: 4px;
}

.bf-outer-ring {
    position: absolute; inset: 0;
    border-radius: 9999px;
    border: 1px solid oklch(var(--bc) / 0.12);
    pointer-events: none;
}

.bf-ball {
    position: absolute;
    left: 50%; top: 50%;
    width: 78%; aspect-ratio: 1 / 1;
    border-radius: 9999px;
    transform: translate(-50%, -50%) scale(0.55);
    transition: transform 0.18s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform;
}

.bf-ball-glow {
    position: absolute; inset: -8%;
    border-radius: 9999px;
    background: radial-gradient(circle, oklch(var(--p) / 0.45) 0%, oklch(var(--p) / 0.15) 55%, transparent 75%);
    filter: blur(14px);
    pointer-events: none;
    z-index: 0;
}

.bf-ball-core {
    position: absolute; inset: 0;
    border-radius: 9999px;
    background:
        radial-gradient(circle at 50% 40%,
            oklch(var(--p) / 0.95) 0%,
            oklch(var(--p) / 0.85) 50%,
            oklch(var(--p) / 0.7) 100%);
    box-shadow:
        inset 0 -10px 30px rgba(0, 0, 0, 0.25),
        inset 0 0 0 1px oklch(var(--p) / 0.6),
        0 10px 40px oklch(var(--p) / 0.35);
    z-index: 1;
    transition: filter 1s ease;
}

.bf-line-clip {
    position: absolute; inset: 0;
    border-radius: 9999px;
    overflow: hidden;
    pointer-events: none;
    z-index: 2;
}
.bf-line-track {
    position: absolute;
    inset: 10% 0;
    transform: translateY(100%);
    transition: transform 0.18s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform;
}
.bf-line {
    position: absolute;
    left: -5%; right: -5%;
    top: 0;
    height: 1px;
    background: linear-gradient(
        to right,
        transparent 0%,
        oklch(var(--bc) / 0.25) 20%,
        oklch(var(--bc) / 0.45) 50%,
        oklch(var(--bc) / 0.25) 80%,
        transparent 100%
    );
}
.bf-line::before {
    content: '';
    position: absolute;
    left: 10%; right: 10%;
    top: -10px;
    height: 20px;
    background: radial-gradient(ellipse at center, oklch(var(--bc) / 0.1), transparent 70%);
    filter: blur(6px);
}

.bf-center-dot {
    position: absolute;
    left: 50%; top: 50%;
    width: 34%; aspect-ratio: 1 / 1;
    transform: translate(-50%, -50%);
    border-radius: 9999px;
    background:
        radial-gradient(circle at 40% 35%,
            oklch(var(--b1) / 1) 0%,
            oklch(var(--b1) / 0.92) 55%,
            oklch(var(--bc) / 0.15) 100%);
    box-shadow:
        0 4px 22px oklch(var(--bc) / 0.25),
        0 1px 4px oklch(var(--bc) / 0.15);
    z-index: 3;
}

.bf-scene[data-phase="hold-top"] .bf-ball-core,
.bf-scene[data-phase="hold-bottom"] .bf-ball-core {
    filter: saturate(0.85) brightness(0.98);
}

.bf-footer {
    display: flex; flex-direction: column; align-items: center;
    gap: 14px;
    margin-top: 24px;
    margin-bottom: auto;
}
.bf-phase {
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    font-weight: 500;
    letter-spacing: 0.02em;
    color: oklch(var(--bc) / 0.9);
    opacity: 0;
    animation: bf-fade-in 0.6s ease forwards;
}
.bf-phase.is-in {
    animation: bf-fade-in 0.5s ease forwards;
}
@keyframes bf-fade-in {
    from { opacity: 0; transform: translateY(4px); }
    to   { opacity: 1; transform: translateY(0); }
}

.bf-bar {
    position: relative;
    width: 160px; height: 3px;
    background: oklch(var(--bc) / 0.1);
    border-radius: 9999px;
    overflow: hidden;
}
.bf-bar-fill {
    position: absolute; inset: 0;
    transform-origin: left center;
    transform: scaleX(0);
    background: linear-gradient(to right,
        oklch(var(--p) / 0.9) 0%,
        oklch(var(--p) / 0.7) 100%);
    border-radius: 9999px;
    transition: transform 0.08s linear;
}

.bf-controls {
    position: absolute;
    bottom: 24px; left: 50%;
    transform: translateX(-50%);
    display: flex; align-items: center; gap: 10px;
    z-index: 3;
}
.bf-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 22px;
    border-radius: 9999px;
    background: oklch(var(--p));
    color: oklch(var(--pc));
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    border: none;
    cursor: pointer;
    box-shadow: 0 6px 18px oklch(var(--p) / 0.25);
    transition: transform 0.15s ease, box-shadow 0.2s ease, background 0.2s ease;
}
.bf-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 22px oklch(var(--p) / 0.35);
}
.bf-btn:active { transform: translateY(0); }
.bf-btn.is-playing {
    background: oklch(var(--b2) / 0.8);
    color: oklch(var(--bc) / 0.85);
    border: 1px solid oklch(var(--bc) / 0.08);
    box-shadow: 0 4px 14px oklch(var(--bc) / 0.08);
    backdrop-filter: blur(8px);
}
.bf-btn-ghost {
    display: inline-flex; align-items: center; justify-content: center;
    width: 38px; height: 38px;
    border-radius: 9999px;
    background: oklch(var(--b2) / 0.6);
    color: oklch(var(--bc) / 0.55);
    border: 1px solid oklch(var(--bc) / 0.08);
    cursor: pointer;
    backdrop-filter: blur(8px);
    transition: color 0.2s ease, background 0.2s ease;
}
.bf-btn-ghost:hover {
    color: oklch(var(--bc) / 0.9);
    background: oklch(var(--b2) / 0.9);
}
.bf-icon    { width: 16px; height: 16px; }
.bf-icon-sm { width: 15px; height: 15px; }
.bf-hidden  { display: none !important; }

@media (prefers-reduced-motion: reduce) {
    .bf-ball, .bf-line-track { transition: transform 0.3s ease; }
    .bf-phase { animation: none !important; }
}
`;
