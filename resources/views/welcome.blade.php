<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Informasi resmi CV MILI WORAYEL MAJU.">
    <meta name="theme-color" content="#0e2fd6">
    <link rel="manifest" href="/manifest.webmanifest">
    <title>CV MILI WORAYEL MAJU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div id="loader" class="loader"><span class="brand-mark">N</span></div>
    <header class="site-header" id="home">
        <nav class="nav container" aria-label="Navigasi utama">
            <a class="brand" href="#home" aria-label="MILI WORAYEL MAJU Beranda"><img
                    src="{{ asset('images/Logo-Mili.png') }}" alt="Logo MILI WORAYEL MAJU" width="38" height="38"
                    style="object-fit:contain;background:#fff;border-radius:6px"><span>MILI
                    WORAYEL<br><small>MAJU</small></span></a>
            <button class="menu-toggle" aria-label="Buka menu" aria-expanded="false">☰</button>
            <div class="nav-links">
                <a href="#home" data-i18n="navHome">Beranda</a><a href="#tentang" data-i18n="navAbout">Tentang</a><a
                    href="#produk" data-i18n="navProducts">Produk</a><a href="#layanan"
                    data-i18n="navServices">Layanan</a><a href="#portofolio">Portofolio</a><a
                    href="#galeri">Galeri</a><a href="#berita">Berita</a><a href="#karir">Karir</a><a
                    href="#kontak">Kontak</a>
            </div>
            <div class="nav-actions"><button class="icon-button theme-toggle" aria-label="Ubah tema">☾</button><button
                    class="lang-toggle" aria-label="Change language">EN</button>@auth<a class="account-link"
                    href="{{ route('admin.dashboard') }}">Dashboard</a>@else<a class="account-link"
                        href="{{ route('login') }}">Login</a><a class="account-link"
                    href="{{ route('register') }}">Daftar</a>@endauth<a class="button small" href="#kontak"
                    data-i18n="contactUs">Hubungi Kami</a></div>
        </nav>
        <div class="hero container">
            <div class="hero-content reveal">
                <p class="eyebrow">CV MILI WORAYEL MAJU</p>
                <h1>Solusi bisnis yang <em>terukur</em> dan <em>bermanfaat.</em></h1>
                <p class="hero-copy" data-i18n="heroCopy">Kami menyajikan profil, layanan, dan karya yang diperbarui sesuai data resmi yang disetujui perusahaan.</p>
                <div class="hero-buttons"><a class="button" href="#kontak" data-i18n="contactUs">Hubungi Kami
                        <span>→</span></a><a class="text-link" href="#tentang" data-i18n="viewProfile">Lihat Profil
                        <span>↓</span></a></div>
            </div>
            <div class="hero-visual reveal">
                <div class="hero-orb orb-one"></div>
                <div class="hero-orb orb-two"></div>
                <div class="hero-card">
                    <p>DATA RESMI</p><strong>CMS</strong><span>profil dan konten<br>terkelola aman</span>
                </div>
                <div class="visual-pattern">✦</div>
            </div>
        </div>
    </header>

    <main>
        <section id="tentang" class="section about container">
            <div class="section-label reveal">01 / TENTANG KAMI</div>
            <div class="about-layout">
                <div class="reveal">
                    <h2>Bergerak bersama, tumbuh lebih <em>besar.</em></h2>
                    <p>CV MILI WORAYEL MAJU hadir sebagai perusahaan yang menekankan komitmen, kerja profesional, dan nilai yang dapat dibuktikan lewat karya dan data yang dikelola secara resmi.</p>
                </div>
                <div class="about-info reveal">
                    <p><b>Visi</b> Menjadi mitra terpercaya yang mendorong kemajuan melalui kerja profesional dan solusi yang berdampak.</p>
                    <p><b>Misi</b> Menyediakan layanan dan layanan strategis yang sesuai kebutuhan, dikelola dengan integritas, dan siap berkembang bersama pelanggan.</p>
                    <a href="#kontak" class="text-link">Hubungi kami untuk detail resmi <span>↓</span></a>
                </div>
            </div>
            <div class="value-grid reveal">
                <div><span>01</span>
                    <h3>Integritas</h3>
                    <p>Kejujuran dalam setiap proses.</p>
                </div>
                <div><span>02</span>
                    <h3>Komitmen</h3>
                    <p>Tanggung jawab yang dapat dipercaya.</p>
                </div>
                <div><span>03</span>
                    <h3>Profesional</h3>
                    <p>Kerja yang terukur dan konsisten.</p>
                </div>
                <div><span>04</span>
                    <h3>Kolaborasi</h3>
                    <p>Bersama tumbuh dan berkembang.</p>
                </div>
            </div>
        </section>

        <section class="statistics">
            <div class="container stats-grid">
                <div class="reveal"><strong data-counter="0">0</strong><span>Proyek aktif</span></div>
                <div class="reveal"><strong data-counter="0">0</strong><span>Produk layanan</span></div>
                <div class="reveal"><strong data-counter="0">0</strong><span>Pengunjung</span></div>
                <div class="reveal"><strong data-counter="0">0</strong><span>Data terverifikasi</span></div>
            </div>
        </section>

        <section id="struktur" class="section container">
            <div class="section-heading reveal">
                <div>
                    <div class="section-label">02 / TIM KAMI</div>
                    <h2>Profil tim akan diatur melalui CMS.</h2>
                </div>
                <p>Informasi personal dan struktur tim harus diinput oleh administrator setelah data resmi dan disetujui perusahaan.</p>
            </div>
            <div class="org-grid">
                <article class="person reveal">
                    <div class="avatar a1">CMS</div><span>Kelola tim</span>
                    <h3>Data pegawai dapat ditambahkan di dashboard</h3>
                </article>
                <article class="person reveal">
                    <div class="avatar a2">P</div><span>Publik</span>
                    <h3>Hanya tampil jika dibuka untuk umum</h3>
                </article>
                <article class="person reveal">
                    <div class="avatar a3">R</div><span>Resmi</span>
                    <h3>Periksa data sebelum dipublikasikan</h3>
                </article>
                <article class="person reveal">
                    <div class="avatar a4">A</div><span>Audit</span>
                    <h3>Data internal tidak dipakai sembarangan</h3>
                </article>
            </div>
        </section>

        <section id="produk" class="section products-section">
            <div class="container">
                <div class="section-heading reveal">
                    <div>
                        <div class="section-label">03 / PRODUK</div>
                        <h2>Solusi yang dibuat untuk <em>maju.</em></h2>
                    </div><label class="search"><span>⌕</span><input id="product-search" type="search"
                            placeholder="Cari produk..."></label>
                </div>
                <div class="product-grid">
                    <article class="product-card reveal" data-product="Nusa Insight analytics data">
                        <div class="product-icon">◒</div><span>ANALITIK</span>
                        <h3>Nusa Insight</h3>
                        <p>Dasbor cerdas untuk keputusan bisnis berbasis data.</p><a href="#kontak">Pelajari lebih
                            lanjut →</a>
                    </article>
                    <article class="product-card featured reveal" data-product="Nusa Connect platform digital">
                        <div class="product-icon">⌘</div><span>PLATFORM DIGITAL</span>
                        <h3>Nusa Connect</h3>
                        <p>Ekosistem kolaborasi yang menghubungkan tim dan pelanggan.</p><a href="#kontak">Pelajari
                            lebih lanjut →</a>
                    </article>
                    <article class="product-card reveal" data-product="Nusa Impact sustainability">
                        <div class="product-icon">✦</div><span>KEBERLANJUTAN</span>
                        <h3>Nusa Impact</h3>
                        <p>Program inovasi yang menciptakan manfaat nyata dan terukur.</p><a href="#kontak">Pelajari
                            lebih lanjut →</a>
                    </article>
                </div>
                <p id="no-products" class="empty-state" hidden>Produk tidak ditemukan. Coba kata kunci lain.</p>
            </div>
        </section>

        <section id="layanan" class="section container">
            <div class="service-layout">
                <div class="reveal">
                    <div class="section-label">04 / LAYANAN</div>
                    <h2>Dari ide hingga <em>dampak.</em></h2>
                    <p>Kami menyertai perjalanan transformasi bisnis Anda secara end-to-end.</p>
                </div>
                <div class="service-list reveal">
                    <div><span>01</span>
                        <h3>Konsultasi Strategis</h3>
                        <p>Memetakan peluang dan arah pertumbuhan bisnis.</p>
                    </div>
                    <div><span>02</span>
                        <h3>Implementasi Solusi</h3>
                        <p>Mengubah strategi menjadi langkah yang nyata.</p>
                    </div>
                    <div><span>03</span>
                        <h3>Maintenance</h3>
                        <p>Menjaga solusi Anda tetap optimal dan relevan.</p>
                    </div>
                    <div><span>04</span>
                        <h3>Support Berkelanjutan</h3>
                        <p>Tim responsif untuk setiap kebutuhan Anda.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="portofolio" class="section portfolio">
            <div class="container">
                <div class="section-heading reveal">
                    <div>
                        <div class="section-label">05 / PORTOFOLIO</div>
                        <h2>Karya yang membawa <em>perubahan.</em></h2>
                    </div><a href="#kontak" class="text-link">Lihat semua proyek →</a>
                </div>
                <div class="portfolio-grid">
                    <article class="project project-large reveal">
                        <div class="project-art art-one"><span>01</span></div>
                        <p>TRANSFORMASI DIGITAL · 2025</p>
                        <h3>Ekosistem ritel untuk masa depan</h3>
                    </article>
                    <article class="project reveal">
                        <div class="project-art art-two"><span>02</span></div>
                        <p>BRANDING · 2024</p>
                        <h3>Identitas baru yang lebih dekat</h3>
                    </article>
                    <article class="project reveal">
                        <div class="project-art art-three"><span>03</span></div>
                        <p>SUSTAINABILITY · 2024</p>
                        <h3>Mengukur dampak untuk bumi</h3>
                    </article>
                </div>
            </div>
        </section>

        <section id="galeri" class="section container">
            <div class="section-heading reveal">
                <div>
                    <div class="section-label">06 / GALERI</div>
                    <h2>Momen yang kami <em>rayakan.</em></h2>
                </div>
                <p>Dokumentasi kegiatan, kolaborasi, dan cerita di balik layar Nusantara Kreasi.</p>
            </div>
            <div class="gallery-grid reveal">
                <div class="gallery-cell g1">WORKSHOP</div>
                <div class="gallery-cell g2">TEAM DAY</div>
                <div class="gallery-cell g3">INNOVATE</div>
                <div class="gallery-cell g4">TOGETHER</div>
                <div class="gallery-cell g5">IMPACT</div>
            </div>
        </section>

        <section id="berita" class="section news">
            <div class="container">
                <div class="section-heading reveal">
                    <div>
                        <div class="section-label">07 / INSIGHT</div>
                        <h2>Cerita, ide, dan <em>kabar terbaru.</em></h2>
                    </div><a href="#kontak" class="text-link">Semua berita →</a>
                </div>
                <div class="news-list">
                    <article class="reveal"><span>18 JUN 2026 · PENGUMUMAN</span>
                        <h3>Nusantara Kreasi meraih sertifikasi sistem manajemen mutu</h3><a href="#kontak">Baca artikel
                            →</a>
                    </article>
                    <article class="reveal"><span>05 JUN 2026 · INSIGHT</span>
                        <h3>Melihat peluang pertumbuhan bisnis di era serba terhubung</h3><a href="#kontak">Baca artikel
                            →</a>
                    </article>
                    <article class="reveal"><span>20 MEI 2026 · EVENT</span>
                        <h3>Merayakan kolaborasi dalam Nusa Innovation Forum 2026</h3><a href="#kontak">Baca artikel
                            →</a>
                    </article>
                </div>
            </div>
        </section>

        <section id="karir" class="section career container">
            <div class="career-box reveal">
                <div>
                    <div class="section-label">08 / KARIR</div>
                    <h2>Tempat ide baik menemukan <em>ruangnya.</em></h2>
                    <p>Bergabunglah untuk membuat perubahan yang bisa dirasakan banyak orang.</p>
                </div><a class="button light" href="#kontak">Lihat lowongan <span>→</span></a>
            </div>
        </section>

        <section id="partner" class="section partners container">
            <div class="section-label reveal">09 / PARTNER & CLIENT</div>
            <p class="partner-intro reveal">Dipercaya oleh organisasi yang ingin terus <em>bergerak maju.</em></p>
            <div class="logo-grid reveal">
                <span>ARUNA</span><span>karsa.</span><span>VITRA</span><span>lumi</span><span>ORBIT</span><span>kanan</span>
            </div>
        </section>

        <section id="testimoni" class="section testimonial">
            <div class="container quote reveal"><span class="quote-mark">“</span>
                <blockquote>Kolaborasi dengan Nusantara Kreasi memberi kami cara pandang baru untuk berkembang. Mereka
                    bukan hanya vendor, tetapi mitra strategis.</blockquote>
                <div><strong>Rani Kusuma</strong><span>Chief Strategy Officer, Aruna Group</span><span
                        class="stars">★★★★★</span></div>
            </div>
        </section>

        <section id="kontak" class="section contact container">
            <div class="contact-heading reveal">
                <div class="section-label">10 / KONTAK</div>
                <h2>Mari mulai sesuatu yang <em>berarti.</em></h2>
                <p>Ceritakan kebutuhan Anda. Tim kami akan menghubungi dalam 1×24 jam kerja.</p>
                <div class="contact-details"><a href="https://wa.me/6281234567890" target="_blank"
                        rel="noopener">WhatsApp<br><b>+62 812 3456 7890</b></a><a
                        href="mailto:halo@nusantarakreasi.id">Email<br><b>halo@nusantarakreasi.id</b></a></div>
            </div>
            <form class="contact-form reveal" id="contact-form" method="POST" action="{{ route('contact.store') }}">
                @csrf<label>Nama lengkap<input required name="name" placeholder="Nama Anda"></label><label>Email<input
                        required name="email" type="email" placeholder="email@perusahaan.com"></label><label>Nomor
                    telepon<input name="phone" placeholder="08xx xxxx xxxx"></label><label>Subjek<input name="subject"
                        placeholder="Kebutuhan Anda"></label><label>Pesan<textarea required name="message"
                        placeholder="Bagaimana kami dapat membantu?"></textarea></label><button class="button"
                    type="submit">Kirim pesan <span>→</span></button>
                <p class="form-message" aria-live="polite">{{ session('contact_success') }}</p>
            </form>
        </section>
        <section class="map-section" aria-label="Lokasi kantor"><iframe title="Peta lokasi kantor Nusantara Kreasi"
                src="https://www.google.com/maps?q=Jakarta%20Indonesia&output=embed" loading="lazy"></iframe>
            <div class="map-pin"><span>●</span> Jakarta, Indonesia</div>
        </section>
    </main>
    <footer>
        <div class="container footer-grid">
            <div><a class="brand" href="#home"><img src="{{ asset('images/Logo-Mili.png') }}"
                        alt="Logo MILI WORAYEL MAJU" width="38" height="38"
                        style="object-fit:contain;background:#fff;border-radius:6px"><span>MILI
                        WORAYEL<br><small>MAJU</small></span></a>
                <p>Membangun nilai yang bertahan.</p>
            </div>
            <div>
                <h4>Jelajahi</h4><a href="#tentang">Tentang kami</a><a href="#produk">Produk</a><a
                    href="#layanan">Layanan</a>
            </div>
            <div>
                <h4>Terhubung</h4><a href="#kontak">Instagram</a><a href="#kontak">LinkedIn</a><a
                    href="#kontak">YouTube</a>
            </div>
            <div>
                <h4>Newsletter</h4>
                <p>Dapatkan cerita terbaru kami.</p>
                <form class="newsletter"><input type="email" placeholder="Email Anda"
                        aria-label="Email newsletter"><button aria-label="Kirim">→</button></form>
            </div>
        </div>
        <div class="container footer-bottom"><span>© 2026 MILI WORAYEL MAJU. All rights reserved.</span><span><a
                    href="#home">Kebijakan Privasi</a> · <a href="#home">Syarat Layanan</a></span></div>
    </footer>
    <button class="floating-whatsapp" aria-label="Hubungi via WhatsApp"
        onclick="window.open('https://wa.me/6281234567890','_blank')">◔</button><button class="back-top"
        aria-label="Kembali ke atas">↑</button>
    <section class="chatbot" aria-label="AI Chatbot">
        <div class="chat-window" hidden>
            <div class="chat-head"><span><b>✦ Nusa Assistant</b><small>Online · Siap membantu</small></span><button
                    class="chat-close" aria-label="Tutup chat">×</button></div>
            <div class="chat-body">
                <p class="bot-message">Halo! Ada yang ingin Anda ketahui tentang produk atau layanan kami?</p>
            </div>
            <div class="chat-suggestions"><button>Tanya produk</button><button>Tanya layanan</button><button>Info
                    perusahaan</button></div>
            <form class="chat-form"><input placeholder="Tulis pesan..." aria-label="Pesan chatbot"><button>↑</button>
            </form>
        </div><button class="chat-toggle" aria-label="Buka AI Chatbot">✦ <span>Butuh bantuan?</span></button>
    </section>
</body>

</html>