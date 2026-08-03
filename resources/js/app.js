import './bootstrap';

const qs = (selector, parent = document) => parent.querySelector(selector);

// Sembunyikan loader segera setelah struktur halaman siap. Tidak menunggu event
// `load` karena iframe peta atau aset pihak ketiga dapat menunda event tersebut.
const hideLoader = () => {
    // Durasi minimum agar animasi identitas perusahaan tetap terlihat saat halaman cepat dimuat.
    window.setTimeout(() => {
        qs('#loader')?.classList.add('done');
    }, 700);

    // Halaman utama menggunakan layar pembuka perusahaan selama 2,3 detik.
    window.setTimeout(() => {
        qs('#company-loader')?.classList.add('done');
    }, 2300);
};
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', hideLoader, { once: true });
} else {
    hideLoader();
}

const menu = qs('.menu-toggle');
const navLinks = qs('.nav-links');
if (menu && navLinks) {
    menu.addEventListener('click', () => {
        const isOpen = navLinks.classList.toggle('open');
        menu.setAttribute('aria-expanded', String(isOpen));
    });
    navLinks.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => navLinks.classList.remove('open')));
}

const themeButton = qs('.theme-toggle');
if (localStorage.theme === 'dark') document.body.classList.add('dark');
if (themeButton) themeButton.addEventListener('click', () => {
    document.body.classList.toggle('dark');
    const dark = document.body.classList.contains('dark');
    localStorage.theme = dark ? 'dark' : 'light';
    themeButton.innerHTML = dark ? '&#9728;' : '&#9790;';
});

const langButton = qs('.lang-toggle');
const translations = {
    id: { about: 'Tentang', services: 'Layanan', projects: 'Proyek', legal: 'Legalitas', testimonials: 'Testimoni', faq: 'FAQ', contact: 'Kontak' },
    en: { about: 'About', services: 'Services', projects: 'Projects', legal: 'Credentials', testimonials: 'Testimonials', faq: 'FAQ', contact: 'Contact' },
};
const setLanguage = (language) => {
    document.documentElement.lang = language;
    document.querySelectorAll('[data-i18n]').forEach((element) => {
        element.textContent = translations[language][element.dataset.i18n] || element.textContent;
    });
    if (langButton) langButton.textContent = language === 'en' ? 'ID' : 'EN';
    localStorage.language = language;
};
if (langButton) {
    setLanguage(localStorage.language === 'en' ? 'en' : 'id');
    langButton.addEventListener('click', () => setLanguage(document.documentElement.lang === 'en' ? 'id' : 'en'));
}

const observer = new IntersectionObserver((entries) => entries.forEach((entry) => {
    if (!entry.isIntersecting) return;
    entry.target.classList.add('visible');
    const counter = qs('[data-counter]', entry.target);
    if (!counter || counter.dataset.done) return;
    counter.dataset.done = 'true';
    const target = Number(counter.dataset.counter);
    let value = 0;
    const timer = setInterval(() => {
        value += Math.ceil(target / 35);
        counter.textContent = String(Math.min(value, target));
        if (value >= target) clearInterval(timer);
    }, 35);
}), { threshold: 0.15 });
document.querySelectorAll('.reveal, .stats-grid div, .cp-hero-stats article').forEach((element) => observer.observe(element));

// Pencarian global PT-CP: menyaring layanan, proyek, dan informasi di halaman.
const siteSearch = qs('#site-search');
const searchStatus = qs('#search-status');
const searchClear = qs('#search-clear');
if (siteSearch) {
    const searchableItems = [...document.querySelectorAll('.search-item')];
    const filterPage = () => {
        const query = siteSearch.value.trim().toLocaleLowerCase('id-ID');
        let total = 0;
        searchableItems.forEach((item) => {
            const searchableText = `${item.dataset.searchable || ''} ${item.textContent || ''}`.toLocaleLowerCase('id-ID');
            const match = !query || searchableText.includes(query);
            item.hidden = !match;
            if (match) total += 1;
        });
        if (!searchStatus) return;
        searchStatus.textContent = query
            ? `${total} hasil ditemukan untuk “${siteSearch.value.trim()}”.`
            : 'Ketik kata kunci untuk mencari seluruh halaman.';
    };
    siteSearch.addEventListener('input', filterPage);
    searchClear?.addEventListener('click', () => {
        siteSearch.value = '';
        filterPage();
        siteSearch.focus();
    });
}

const backTop = qs('.back-top');
if (backTop) {
    window.addEventListener('scroll', () => backTop.classList.toggle('show', window.scrollY > 600));
    backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

// Lengkapi tabel manajemen dengan aksi Lihat, Edit, dan Hapus pada setiap data.
const managementTable = qs('#dataTable');
if (managementTable) {
    managementTable.querySelectorAll('tbody tr').forEach((row) => {
        const actionCell = row.querySelector('td:last-child');
        const deleteForm = actionCell?.querySelector('form');
        const deleteUrl = deleteForm?.getAttribute('action');
        if (!actionCell || !deleteForm || !deleteUrl) return;
        const showUrl = deleteUrl.replace(/\/(\d+)$/, '/$1');
        const editUrl = `${showUrl}/edit`;
        actionCell.innerHTML = `<div class="d-flex gap-1"><a class="btn btn-sm btn-outline-primary" href="${showUrl}" title="Lihat"><i class="bi bi-eye"></i></a><a class="btn btn-sm btn-outline-warning" href="${editUrl}" title="Edit"><i class="bi bi-pencil"></i></a>${deleteForm.outerHTML}</div>`;
    });
}
