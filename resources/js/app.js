import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import Sortable from 'sortablejs';

window.Alpine = Alpine;
window.Chart = Chart;
window.Sortable = Sortable;

/** Chronomètre d'une tentative de quiz : soumission automatique à l'échéance. */
Alpine.data('quizTimer', (deadlineIso) => ({
    remaining: null,
    init() {
        if (!deadlineIso) return;
        const deadline = new Date(deadlineIso).getTime();
        const tick = () => {
            this.remaining = Math.max(0, Math.floor((deadline - Date.now()) / 1000));
            if (this.remaining === 0) {
                clearInterval(this.timer);
                document.getElementById('attempt-form')?.submit();
            }
        };
        tick();
        this.timer = setInterval(tick, 1000);
    },
    get display() {
        if (this.remaining === null) return '';
        const m = Math.floor(this.remaining / 60);
        const s = String(this.remaining % 60).padStart(2, '0');
        return `${m}:${s}`;
    },
}));

/** Constructeur de cours : glisser-déposer des sections et des éléments. */
Alpine.data('courseBuilder', (reorderUrl) => ({
    saving: false,
    saved: false,
    init() {
        const opts = { animation: 150, handle: '[data-handle]', onEnd: () => this.save() };
        Sortable.create(this.$refs.sections, { ...opts, handle: '[data-section-handle]' });
        this.$root.querySelectorAll('[data-items]').forEach((el) =>
            Sortable.create(el, { ...opts, group: 'items' }),
        );
    },
    async save() {
        this.saving = true;
        const sections = [...this.$refs.sections.querySelectorAll(':scope > [data-section]')].map((s) => ({
            id: Number(s.dataset.section),
            items: [...s.querySelectorAll('[data-item]')].map((i) => ({ kind: i.dataset.kind, id: Number(i.dataset.item) })),
        }));
        await window.axios.post(reorderUrl, { sections });
        this.saving = false;
        this.saved = true;
        setTimeout(() => (this.saved = false), 1500);
    },
}));

Alpine.start();
