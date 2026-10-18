<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="{{ $companyProfile?->description ? \Illuminate\Support\Str::limit(strip_tags($companyProfile->description), 160) : 'Informasi resmi CV MILI WORAYEL MAJU.' }}">
    <meta name="theme-color" content="#222a99">
    <title>CV MILI WORAYEL MAJU | Konstruksi</title>
    {{-- Memuat aset CSS dan JavaScript utama melalui Vite Laravel. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    {{-- CDN tidak boleh menahan tampilan halaman ketika koneksi sedang lambat atau gagal. --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <style>
        :root {
            --ink: #0d3b35;
            --green: #176b5e;
            --gold: #f59e0b;
            --cream: #f5f7f7;
            --muted: #5d716d
        }

        html {
            scroll-behavior: smooth;
            overflow-x: hidden
        }

        body {
            font-family: Inter, system-ui, -apple-system, sans-serif;
            color: var(--ink);
            background: var(--cream);
            overflow-x: hidden
        }

        /* Layar pembuka singkat agar identitas perusahaan tampil sebelum halaman siap digunakan. */
        #company-loader {
            position: fixed;
            z-index: 2000;
            inset: 0;
            display: grid;
            place-items: center;
            overflow: hidden;
            color: #fff;
            background: radial-gradient(circle at 50% 42%, #247c6e 0%, #0d3b35 48%, #071f1b 100%);
            transition: opacity .45s ease, visibility .45s ease;
        }

        #company-loader.done { opacity: 0; visibility: hidden; pointer-events: none; }
        .company-loader__content { position: relative; z-index: 1; display: grid; justify-items: center; gap: 1rem; padding: 2rem; text-align: center; }
        .company-loader__ring { display: grid; place-items: center; width: 92px; height: 92px; border: 2px solid #ffffff45; border-top-color: #f59e0b; border-radius: 50%; animation: company-loader-spin 1s linear infinite; }
        .company-loader__ring img { width: 68px; height: 68px; padding: 5px; object-fit: contain; border-radius: 50%; background: #fff; animation: company-loader-counter-spin 1s linear infinite; }
        .company-loader__label { margin: 0; color: #ffd77e; font-size: .7rem; font-weight: 800; letter-spacing: .24em; }
        .company-loader__name { margin: 0; font-size: clamp(1.3rem, 4vw, 2.1rem); font-weight: 900; letter-spacing: .12em; line-height: 1.2; }
        .company-loader__bar { width: min(200px, 65vw); height: 3px; overflow: hidden; border-radius: 99px; background: #ffffff2b; }
        .company-loader__bar::after { content: ''; display: block; width: 45%; height: 100%; border-radius: inherit; background: #f59e0b; animation: company-loader-progress 1.1s ease-in-out infinite; }
        #company-loader::before, #company-loader::after { content: ''; position: absolute; width: 42vw; height: 42vw; border: 1px solid #ffffff1f; border-radius: 50%; }
        #company-loader::before { top: -22vw; left: -14vw; } #company-loader::after { right: -18vw; bottom: -25vw; }
        @keyframes company-loader-spin { to { transform: rotate(360deg); } }
        @keyframes company-loader-counter-spin { to { transform: rotate(-360deg); } }
        @keyframes company-loader-progress { from { transform: translateX(-115%); } to { transform: translateX(245%); } }
        @media (prefers-reduced-motion: reduce) { .company-loader__ring, .company-loader__ring img, .company-loader__bar::after { animation: none; } }

        .navbar {
            background: #062b55ed;
            backdrop-filter: blur(12px)
        }

        .brand-logo {
            width: 52px;
            height: 52px;
            object-fit: contain;
            background: #fff;
            border-radius: 12px;
            padding: 4px
        }

        .nav-link {
            font-size: .88rem;
            font-weight: 600;
            color: #dfece9 !important
        }

        .nav-link:hover {
            color: var(--gold) !important
        }

        .btn-gold {
            background: var(--gold);
            border-color: var(--gold);
            color: #192d29;
            font-weight: 800
        }

        .btn-gold:hover {
            background: #ffd26e;
            border-color: #ffd26e;
            color: #192d29
        }

        .hero {
            min-height: 720px;
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
            background: radial-gradient(circle at 78% 30%, #176b5e 0, #0d3b35 35%, #062b55 100%)
        }

        .hero-overlay { position: absolute; inset: 0; background: linear-gradient(90deg, #031f43a8 0%, #063c785c 55%, #062b5520 100%); z-index: 1; }

        .hero:after {
            content: "";
            position: absolute;
            right: 5%;
            bottom: 8%;
            width: 190px;
            height: 190px;
            border: 1px solid #f4b94299;
            border-radius: 50%
        }

        .hero-content {
            position: relative;
            z-index: 1
        }

        .eyebrow {
            color: var(--gold);
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .16em;
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            padding: .35rem .75rem;
            border: 1px solid #d99b2480;
            border-radius: 999px;
            background: #fff8e8
        }

        .hero h1 {
            max-width: 760px;
            font-size: clamp(2.8rem, 6vw, 5.8rem);
            font-weight: 800;
            letter-spacing: -.055em;
            line-height: .98
        }

        .hero p {
            max-width: 620px;
            color: #080808;
            font-size: 1.08rem
        }

        .hero-stat {
            position: relative;
            z-index: 2;
            margin-top: -74px
        }

        .hero-stat .card {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 12px 40px #061c1930
        }

        .hero-stat i {
            color: var(--green);
            font-size: 1.4rem
        }

        .display-number {
            font-size: 1.9rem;
            font-weight: 800;
            letter-spacing: -.06em
        }

        .section {
            padding: 100px 0
        }

        .section-title {
            max-width: 760px;
            font-size: clamp(2.1rem, 4vw, 3.7rem);
            font-weight: 800;
            letter-spacing: -.05em;
            line-height: 1.04;
            color: #075ca8
        }

        .section-title span {
            color: #e8a10b
        }

        h3, h4, h5 { color:#075ca8; }

        .section-heading-center { text-align:center; max-width:850px; margin-inline:auto; }
        .section-heading-center .section-title { margin-inline:auto; }
        .section-heading-center .eyebrow { background:linear-gradient(90deg,#fff8e8,#e7f5f0); }
        .section-heading-center .lead-copy { margin-inline:auto; }
        body { background:linear-gradient(180deg,#f7fbfa 0%,#fff 35%,#f5faf8 100%); }
        .text-secondary { color:#38544d!important; }

        .lead-copy {
            font-size: 1.08rem;
            /* line-height: 1.85; */
            /* color: var(--muted) */
            color: #090909
        }

        .image-frame {
            position: relative
        }

        .company-leader { display: inline-flex; align-items: center; gap: .85rem; margin-top: 1rem; padding: .8rem 1rem; border: 1px solid #cfe1da; border-radius: .8rem; background: #fff; color: var(--ink); box-shadow: 0 8px 20px #0d3b350d; text-align: left; }
        .company-leader i { display: grid; place-items: center; width: 38px; height: 38px; border-radius: .65rem; background: #e1f2ed; color: var(--green); font-size: 1.1rem; }
        .company-leader small, .company-leader strong { display: block; }
        .company-leader small { color: var(--muted); font-size: .62rem; font-weight: 800; letter-spacing: .08em; }
        .company-leader strong { margin-top: .12rem; font-size: .88rem; letter-spacing: .04em; }

        .image-frame img {
            width: 100%;
            min-height: 470px;
            object-fit: cover;
            border-radius: 1.2rem
        }

        .image-note {
            position: absolute;
            right: -15px;
            bottom: 28px;
            max-width: 245px;
            padding: 1.35rem;
            background: var(--gold);
            border-radius: .85rem;
            color: #20332f;
            font-weight: 700;
            box-shadow: 0 10px 25px #0002
        }

        .value-card,
        .service-card {
            border: 0;
            border-radius: 1rem;
            height: 100%;
            box-shadow: 0 8px 28px #133d3510
        }

        .value-icon,
        .service-icon {
            width: 52px;
            height: 52px;
            border-radius: .85rem;
            display: grid;
            place-items: center;
            background: #e2f2ec;
            color: var(--green);
            font-size: 1.35rem
        }

        .vision {
            background: linear-gradient(130deg, #062b55, #0876d1);
            color: #fff
        }

        .vision .section-title {
            color: #fff
        }

        .vision .section-title span {
            color: var(--gold)
        }

        .vision .section-heading-center { max-width:780px; margin-inline:auto; }
        .vision .section-heading-center .eyebrow { color:#ffe09a; background:#ffffff14; border-color:#ffd16680; }

        .mission-item {
            border-top: 1px solid #ffffff3d;
            padding: 1.1rem 0;
            color: #e1eeea
        }

        .mission-item b {
            color: var(--gold);
            font-size: .8rem;
            letter-spacing: .1em
        }

        .service-card {
            padding: 1.6rem
        }

        .service-card h3 {
            font-size: 1.08rem;
            font-weight: 800
        }

        .service-card ul {
            padding-left: 1.1rem;
            /* color: var(--muted); */
            font-size: .91rem;
            line-height: 1.9
        }

        .project {
            position: relative;
            overflow: hidden;
            min-height: 310px;
            border-radius: 1rem
        }

        .project img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .4s
        }

        .project:hover img {
            transform: scale(1.06)
        }

        .project:after {
            content: "";
            position: absolute;
            inset: 35% 0 0;
            background: linear-gradient(transparent, #071f1be8)
        }

        .project div {
            position: absolute;
            z-index: 1;
            bottom: 0;
            padding: 1.5rem;
            color: #fff
        }

        .project small {
            color: var(--gold);
            font-weight: 800;
            letter-spacing: .08em
        }

        .commitment {
            background: #fffdf8
        }

        .testimonials {
            background: #eaf5ff
        }

        .testimonial-card {
            height: 100%;
            padding: 2rem;
            border: 0;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 10px 30px #133d3510
        }

        .testimonial-card .quote-icon {
            color: var(--gold);
            font-size: 2rem;
            line-height: 1
        }

        .testimonial-card blockquote {
            margin: 1rem 0 1.5rem;
            color: var(--ink);
            font-size: 1.04rem;
            line-height: 1.75
        }

        .testimonial-person {
            display: flex;
            align-items: center;
            gap: .8rem;
            padding-top: 1.1rem;
            border-top: 1px solid #e4ece9
        }

        .testimonial-avatar {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 50%;
            background: var(--green);
            color: #fff;
            font-weight: 800
        }

        .testimonial-photo { width: 58px; height: 58px; object-fit: cover; border-radius: 50%; border: 3px solid #e2f2ec; }

        .testimonial-stars {
            color: var(--gold);
            font-size: .82rem;
            letter-spacing: .08em
        }

        .faq {
            background: #fffdf8
        }

        .faq .accordion-item {
            overflow: hidden;
            margin-bottom: .85rem;
            border: 1px solid #dfe9e5;
            border-radius: .75rem;
        }

        .faq .accordion-button {
            padding: 1.25rem 1.4rem;
            color: var(--ink);
            font-weight: 750;
            box-shadow: none;
        }

        .faq .accordion-button:not(.collapsed) {
            background: #e2f2ec;
            color: var(--green);
        }

        .faq .accordion-body {
            color: var(--muted);
            line-height: 1.8;
        }

        .topbar {
            background: #092622;
            color: #dbe9e5;
            font-size: .78rem;
        }

        .topbar a { color: inherit; text-decoration: none; }
        .topbar a:hover { color: var(--gold); }
        .topbar-social { display: flex; gap: .65rem; align-items: center; }
        .topbar-social a { display: grid; place-items: center; width: 24px; height: 24px; border: 1px solid #ffffff35; border-radius: 50%; }
        .header-tool { border: 1px solid #ffffff45; border-radius: .35rem; background: transparent; color: #fff; font-size: .7rem; font-weight: 700; padding: .3rem .45rem; }
        .header-tool:hover { background: var(--gold); border-color: var(--gold); color: var(--ink); }

        .project-filter { display: flex; flex-wrap: wrap; gap: .6rem; }
        .project-filter button { border: 1px solid #cfe0da; border-radius: 99px; background: #fff; color: var(--ink); padding: .5rem .9rem; font-size: .82rem; font-weight: 700; }
        .project-filter button.active, .project-filter button:hover { background: var(--green); border-color: var(--green); color: #fff; }
        .project-detail { display: flex; flex-wrap: wrap; gap: .5rem 1rem; margin-top: .8rem; color: #e2eeeb; font-size: .75rem; }
        .project-filter-item[hidden] { display: none !important; }

        .partners { background: linear-gradient(180deg, #fff, #edf7ff); overflow: hidden; }

        /* Area logo dipotong agar animasi marquee tidak membuat halaman melebar pada ponsel. */
        .logo-marquee { overflow: hidden; mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent); }
        .logo-track { display: flex; width: max-content; gap: 1rem; padding: .5rem 0; animation: marquee-left 28s linear infinite; }
        .logo-track.reverse { animation-name: marquee-right; }
        .logo-marquee:hover .logo-track { animation-play-state: paused; }
        .partner-card { display: grid; grid-template-columns: 44px minmax(155px, 1fr); align-items: center; gap: .75rem; min-height: 86px; width: 280px; padding: 1rem; border: 1px solid #d3e8fb; border-radius: .8rem; color: #062b55; background: #fff; font-size: .82rem; font-weight: 800; text-align: left; letter-spacing: .01em; box-shadow: 0 8px 20px #0a4d8d0b; }
        .partner-logo { display:grid; place-items:center; width:44px; height:44px; border-radius:50%; color:#fff; font-size:1.2rem; }
        .partner-image { width:48px; height:48px; object-fit:contain; padding:2px; }
        .logo-papua { background:#de2027; }.logo-jayapura { background:#15785e; }.logo-pln { background:#13a8e0; }.logo-telkom { background:#e51b23; }.logo-pertamina { background:linear-gradient(135deg,#e3232c 0 35%,#1a9bd7 35% 67%,#51a745 67%); }.logo-bumn { background:#1559a6; }.logo-swasta { background:#9a6c24; }
        @keyframes marquee-left { to { transform: translateX(-50%); } } @keyframes marquee-right { from { transform:translateX(-50%); } to { transform:translateX(0); } }
        @media (prefers-reduced-motion: reduce) { .hero-slides span, .logo-track, .quick-contact .whatsapp, .quick-contact .email, .quick-contact .whatsapp::after { animation: none; } .hero-slides span:first-child { opacity: 1; } }
        .certificate-card { height: 100%; padding: 1.5rem; border: 0; border-top: 4px solid var(--gold); border-radius: .75rem; box-shadow: 0 8px 25px #133d3512; }
        .certificate-card i { color: var(--green); font-size: 1.7rem; }

        .gallery-section { background: #edf5f1; }
        .gallery-grid { display: grid; grid-template-columns: 1.35fr 1fr 1fr; grid-auto-rows: 190px; gap: 1rem; }
        .gallery-item { position: relative; overflow: hidden; border-radius: 1rem; background: var(--ink); }
        .gallery-item:first-child { grid-row: span 2; }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform .35s ease; }
        .gallery-item:hover img { transform: scale(1.06); }
        .gallery-item span { position: absolute; inset: auto 0 0; padding: 1rem; background: linear-gradient(transparent, #071f1be6); color: #fff; font-size: .8rem; font-weight: 700; }

        .news-card { height: 100%; overflow: hidden; border: 0; border-radius: 1rem; box-shadow: 0 10px 30px #0a4d8d17; transition: transform .25s ease, box-shadow .25s ease; }
        .news-card:hover { transform: translateY(-7px); box-shadow: 0 18px 38px #0a4d8d28; }
        .news-card img { width: 100%; height: 190px; object-fit: cover; }
        .news-card .card-body { padding: 1.4rem; }
        .news-date { color: var(--green); font-size: .75rem; font-weight: 800; letter-spacing: .06em; }
        #berita { background: linear-gradient(180deg, #f4f9ff, #fff); }
        .career-panel { border-radius: 1.1rem; background: linear-gradient(130deg, #103b34, #176b5d); color: #fff; }
        .career-panel .section-title { color: #fff; }
        .video-placeholder { display: grid; place-items: center; }

        /* Tombol kontak mengambang: WhatsApp, email, dan tombol kembali ke bagian atas. */
        .quick-contact { position: fixed; z-index: 20; right: 1.2rem; bottom: 1.2rem; display: grid; gap: .6rem; }
        .quick-contact a { display: grid; place-items: center; width: 46px; height: 46px; border-radius: 50%; color: #fff; font-size: 1.25rem; box-shadow: 0 7px 20px #0003; }
        .quick-contact .whatsapp { background: #25d366; }
        .quick-contact .email { background: #0876d1; }
        .quick-contact .back-top { background: #062b55; opacity: 0; pointer-events: none; transform: translateY(10px); transition: .25s ease; }
        .quick-contact .back-top.is-visible { opacity: 1; pointer-events: auto; transform: translateY(0); }
        .quick-contact .whatsapp, .quick-contact .email { position: relative; animation: contact-float 2.8s ease-in-out infinite; }
        .quick-contact .email { animation-delay: .45s; }
        .quick-contact .whatsapp::after { content: ''; position: absolute; inset: -5px; border: 1px solid #25d366; border-radius: inherit; animation: contact-pulse 2.8s ease-out infinite; }
        @keyframes contact-float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        @keyframes contact-pulse { 0%, 55%, 100% { opacity: 0; transform: scale(.9); } 65% { opacity: .7; } 90% { opacity: 0; transform: scale(1.35); } }

        body.dark-mode { --ink: #e7f0ed; --muted: #b6c9c4; --cream: #0c1d1a; background: #0c1d1a; }
        body.dark-mode .navbar, body.dark-mode .footer { background: #071714 !important; }
        body.dark-mode .section:not(.vision):not(.testimonials):not(.gallery-section), body.dark-mode .faq, body.dark-mode .commitment, body.dark-mode .partners { background: #0c1d1a; }
        body.dark-mode .testimonials, body.dark-mode .gallery-section { background: #102924; }
        body.dark-mode .card, body.dark-mode .testimonial-card, body.dark-mode .certificate-card, body.dark-mode .news-card, body.dark-mode .partner-card, body.dark-mode .accordion-item { background: #15332d; color: #e7f0ed; border-color: #31574e; }
        body.dark-mode .lead-copy, body.dark-mode .text-secondary, body.dark-mode .faq .accordion-body { color: #b6c9c4 !important; }
        body.dark-mode .faq .accordion-button { background: #15332d; color: #e7f0ed; }
        body.dark-mode .faq .accordion-button:not(.collapsed) { background: #1b453b; color: #ffd26e; }

        @media(max-width: 767px) {
            .topbar-contact { display: none; }
            .gallery-grid { grid-template-columns: 1fr 1fr; grid-auto-rows: 150px; }
            .gallery-item:first-child { grid-column: span 2; grid-row: span 2; }
            .quick-contact { right: .8rem; bottom: .8rem; }
        }

        .quote {
            border-left: 4px solid var(--gold);
            padding-left: 1.4rem;
            font-size: clamp(1.3rem, 2.6vw, 2rem);
            font-weight: 700;
            line-height: 1.35;
            color: #054fa3;
        }

        .contact-box {
            background: linear-gradient(135deg, #062b55, #0876d1);
            color: #fff;
            border-radius: 1.2rem
        }

        .contact-box .form-control {
            border: 0;
            border-radius: .5rem;
            padding: .8rem
        }

        .footer {
            background: #041c3b;
            color: #c9d9d5
        }

        .footer a {
            color: #c9d9d5;
            text-decoration: none
        }

        .footer a:hover {
            color: var(--gold)
        }

        .footer-detail { display: grid; gap: .65rem; margin: 0; padding: 0; list-style: none; }
        .footer-detail li { display: flex; gap: .6rem; align-items: flex-start; color: #c9d9d5; font-size: .82rem; }
        .footer-detail i { color: var(--gold); }
        .footer-ceo { display: flex; gap: .75rem; align-items: center; padding: .85rem; border: 1px solid #ffffff24; border-radius: .8rem; background: #ffffff0a; }
        .footer-ceo i { display: grid; place-items: center; width: 38px; height: 38px; border-radius: 50%; background: var(--gold); color: #0d3b35; }
        .footer-ceo small, .footer-ceo strong { display: block; }
        .footer-ceo small { color: #d4e5e0; font-size: .62rem; font-weight: 800; letter-spacing: .1em; }
        .footer-ceo strong { color: #fff; font-size: .84rem; letter-spacing: .03em; }

        @media(max-width:991px) {
            .hero {
                min-height: 650px
            }

            .hero-stat {
                margin-top: -35px
            }

            .navbar-collapse {
                padding: 1rem 0
            }

            .image-note {
                right: 15px
            }
        }

        @media(max-width:575px) {
            .section {
                padding: 70px 0
            }

            .hero {
                min-height: 630px
            }

            .hero-stat {
                margin-top: -20px
            }

            .image-frame img {
                min-height: 360px
            }

            .image-note {
                position: relative;
                right: auto;
                bottom: auto;
                margin: -35px 15px 0
            }

            .hero h1 {
                font-size: 3rem
            }
        }

        /* Sistem visual halaman pengunjung diselaraskan dengan dashboard. */
        body { background: #f5f7f7; color: var(--ink); }
        .navbar { background: #0d3b35ed; border-bottom: 1px solid #ffffff14; box-shadow: 0 10px 30px #071f1b18; }
        .brand-logo { border-radius: 14px; box-shadow: 0 6px 16px #00000026; }
        .nav-link { color: #d9ebe6 !important; }
        .nav-link:hover, .nav-link:focus { color: #ffd77e !important; }
        .btn-gold { background: var(--gold); border-color: var(--gold); color: #182b27; box-shadow: 0 8px 18px #f59e0b3d; }
        .btn-gold:hover { background: #ffc44d; border-color: #ffc44d; color: #182b27; transform: translateY(-1px); }
        .hero { min-height: min(760px, 86vh); background: #0d3b35; }
        .hero-overlay { background: linear-gradient(100deg, #082e29f5 0%, #0d3b35dc 50%, #0d3b3570 100%); }
        .hero::after { width: 260px; height: 260px; border-color: #f59e0b66; }
        .hero-content { padding-block: 6rem 8rem; }
        .hero h1 { max-width: 820px; text-wrap: balance; }
        .hero p { color: #e1eeeb; }
        .eyebrow { color: #b05d00; border-color: #f59e0b72; background: #fff8e8; box-shadow: 0 5px 16px #071f1b12; }
        .hero .eyebrow { color: #ffd77e; background: #ffffff12; border-color: #f59e0b6e; }
        .hero-stat { margin-top: -62px; }
        .hero-stat .card { border: 1px solid #dce9e5; border-top: 4px solid var(--gold); box-shadow: 0 14px 34px #071f1b1c; }
        .hero-stat i, .value-icon, .service-icon, .certificate-card i { color: var(--green); }
        .section { padding: 110px 0; }
        .section-title, h3, h4, h5, .quote { color: var(--ink); }
        .section-title span { color: #d58000; }
        .lead-copy, .text-secondary { color: var(--muted) !important; }
        .value-card, .service-card, .testimonial-card, .certificate-card, .news-card { border: 1px solid #e0ebe8; box-shadow: 0 10px 28px #0d3b350d; }
        .value-card:hover, .service-card:hover, .news-card:hover { transform: translateY(-5px); box-shadow: 0 18px 34px #0d3b3518; transition: transform .25s ease, box-shadow .25s ease; }
        .vision, .contact-box { background: linear-gradient(125deg, #0d3b35, #176b5e); }
        .vision .section-title { color: #fff; }
        .partners { background: linear-gradient(180deg, #fff, #eaf4f1); }
        .testimonials { background: #e7f3ef; }
        .gallery-section { background: #edf5f2; }
        .contact-box { box-shadow: 0 20px 40px #0d3b3533; }
        .footer { background: #082e29; }
        .quick-contact .email { background: var(--green); }
        .quick-contact .back-top { background: var(--ink); }
        body.dark-mode { --ink: #e7f0ed; --muted: #b9cec8; --cream: #0b211d; background: #0b211d; }
        body.dark-mode .navbar, body.dark-mode .footer { background: #061a17 !important; }

        @media (max-width: 991px) {
            .hero { min-height: 680px; }
            .hero-content { padding-block: 7rem 7.5rem; }
            .navbar-collapse { margin-top: .75rem; padding: .75rem 1rem; border: 1px solid #ffffff18; border-radius: .85rem; background: #0d3b35fa; }
        }

        @media (max-width: 575px) {
            .hero { min-height: 620px; }
            .hero-content { padding-block: 6rem 5rem; }
            .hero h1 { font-size: clamp(2.45rem, 12vw, 3.3rem); }
            .hero-stat { margin-top: -28px; }
            .hero-stat .card { border-top-width: 3px; }
            .section { padding: 76px 0; }
            .section-title { font-size: clamp(2rem, 10vw, 2.65rem); }
        }

        /* Tata letak pengunjung: judul terpusat dan hierarki teks lebih tegas. */
        .hero-content { display: grid; grid-template-columns: minmax(0, 1fr) 230px; align-items: center; gap: clamp(2rem, 6vw, 6rem); text-align: left; }
        .hero-copy { max-width: 760px; }
        .hero-copy .eyebrow { width: fit-content; }
        .hero-content .d-flex { justify-content: flex-start; }
        .hero h1 { font-weight: 900; text-shadow: 0 4px 24px #04171378; }
        .hero h1 span { color: #ffd166 !important; }
        .hero-description { max-width: 640px; margin: 1.25rem 0 0; color: #e5f1ed !important; font-size: clamp(.92rem, 1.7vw, 1.08rem); font-weight: 600; line-height: 1.75; }
        .hero-brand-mark { display: grid; justify-items: center; gap: 1rem; color: #ffe1a0; font-size: .68rem; font-weight: 800; letter-spacing: .14em; text-align: center; }
        .hero-brand-ring { display: grid; place-items: center; width: 188px; height: 188px; padding: 14px; border: 1px solid #f9c65b80; border-radius: 50%; background: #ffffff0c; box-shadow: 0 20px 48px #031f4380, inset 0 0 0 9px #ffffff08; }
        .hero-brand-ring img { width: 132px; height: 132px; padding: 9px; object-fit: contain; border-radius: 50%; background: #fff; box-shadow: 0 8px 24px #0004; }
        .display-number { color: #0d3b35; font-weight: 900; }
        .hero-stat small { color: #38544f !important; font-weight: 700; }

        .public-section-header { max-width: 840px; margin: 0 auto clamp(2.75rem, 5vw, 4.5rem); text-align: center; }
        .public-section-header .eyebrow { width: fit-content; margin-inline: auto; }
        .section-intro { max-width: 620px; margin: 1.15rem auto 0; color: #345149; font-size: .96rem; font-weight: 700; line-height: 1.75; }
        #tentang .col-lg-6:first-child { text-align: left !important; }
        #tentang .col-lg-6:first-child > .lead-copy { margin-inline: 0 !important; color: #173d34 !important; font-weight: 650; }
        #tentang .value-card { border-top: 3px solid #176b5e; }

        .section-heading-center,
        #layanan .d-flex.flex-column,
        #testimoni .d-flex.flex-column,
        #tentang .col-lg-6:first-child,
        .commitment .col-lg-6:last-child,
        #faq .section-heading-center { text-align: center !important; }
        .section-heading-center .eyebrow,
        #layanan .eyebrow,
        #testimoni .eyebrow,
        #tentang .col-lg-6:first-child > .eyebrow,
        .commitment .col-lg-6:last-child > .eyebrow,
        #faq .section-heading-center > .eyebrow { width: fit-content; margin-inline: auto; }
        .section-title { display: grid; justify-items: center; gap: .8rem; max-width: 880px; margin-inline: auto; color: #0a332c; font-weight: 900; letter-spacing: -.055em; }
        .section-title::after { content: ''; width: 54px; height: 4px; border-radius: 99px; background: linear-gradient(90deg, #f59e0b, #176b5e); }
        .section-title span { color: #b96700; }
        .vision .section-title::after { background: linear-gradient(90deg, #f59e0b, #ffd77e); }
        .vision .section-title span { color: #ffd77e; }
        .vision .section-title { color: #fff; }
        #layanan .d-flex.flex-column > p,
        #tentang .col-lg-6:first-child > .lead-copy,
        .commitment .col-lg-6:last-child > .lead-copy,
        #faq .section-heading-center > .lead-copy { margin-inline: auto; }

        p, li, .accordion-body { color: #29463e; font-weight: 600; }
        .lead-copy { color: #173d34 !important; font-size: 1.05rem; font-weight: 650; line-height: 1.85; }
        .text-secondary { color: #304f46 !important; font-weight: 650; }
        .service-card h3, .value-card h3, .certificate-card h3, .news-card h3 { color: #0a332c; font-weight: 850 !important; }
        .service-card ul { color: #314943; font-weight: 600; }
        .value-card { text-align: left; }
        .image-frame { max-width: 580px; margin-inline: auto; }
        .image-note { background: #f59e0b; color: #162d27; box-shadow: 0 14px 30px #0d3b3530; }
        .project-filter { justify-content: center; }
        .project-filter button { border-color: #b9d4cc; color: #174a41; font-weight: 800; }
        .project-filter button.active, .project-filter button:hover { background: #176b5e; border-color: #176b5e; }
        .faq .accordion-button { color: #163e36; font-weight: 800; }
        .faq .accordion-body { color: #314943; }

        @media (min-width: 992px) {
            #tentang .row, .commitment .row { align-items: center !important; }
            #tentang .col-lg-6:first-child, .commitment .col-lg-6:last-child { padding-inline: 2.25rem; }
            #layanan .d-flex.flex-column { align-items: center !important; }
        }

        @media (max-width: 575px) {
            .hero-content { grid-template-columns: minmax(0, 1fr) 88px; align-items: start; gap: .9rem; }
            .hero-content .btn { width: 100%; }
            .hero-content .d-flex { width: 100%; }
            .hero-brand-mark { gap: .45rem; padding-top: 1.25rem; font-size: .42rem; letter-spacing: .07em; }
            .hero-brand-ring { width: 78px; height: 78px; padding: 6px; }
            .hero-brand-ring img { width: 58px; height: 58px; padding: 4px; }
            .section-title::after { width: 42px; height: 3px; }
            .lead-copy { font-size: 1rem; }
            #tentang .col-lg-6:first-child { text-align: center !important; }
            #tentang .col-lg-6:first-child > .lead-copy { margin-inline: auto !important; }
        }
    </style>
</head>

<body>
    <div id="company-loader" role="status" aria-live="polite" aria-label="Memuat halaman CV MILI WORAYEL MAJU">
        <div class="company-loader__content">
            <div class="company-loader__ring"><img src="{{ asset('images/Logo-Mili.png') }}" alt=""></div>
            <p class="company-loader__label">MEMUAT PROFIL PERUSAHAAN</p>
            <p class="company-loader__name">CV MILI WORAYEL MAJU</p>
            <div class="company-loader__bar" aria-hidden="true"></div>
        </div>
    </div>
    <div class="topbar py-2">
        <div class="container d-flex justify-content-between align-items-center gap-3">
            <div class="topbar-social">
                @foreach($socialLinks as $socialLink)
                    <a href="{{ $socialLink->url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $socialLink->platform }}"><i class="{{ $socialLink->icon ?: 'bi bi-link-45deg' }}"></i></a>
                @endforeach
                @if($companyProfile?->phone)<a class="topbar-contact ms-2" href="tel:{{ preg_replace('/[^0-9+]/', '', $companyProfile->phone) }}"><i class="bi bi-telephone-fill text-warning me-1"></i>{{ $companyProfile->phone }}</a>@endif
                @if($companyProfile?->email)<a class="topbar-contact" href="mailto:{{ $companyProfile->email }}"><i class="bi bi-envelope-fill text-warning me-1"></i>{{ $companyProfile->email }}</a>@endif
            </div>
            <div class="d-flex align-items-center gap-2">
                @guest
                    <a href="{{ route('login') }}">Login</a><span class="text-white-50">/</span><a href="{{ route('register') }}">Daftar</a>
                @else
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                @endguest
                <button class="header-tool ms-1" id="languageToggle" type="button" aria-label="Ganti bahasa">ID</button>
                <button class="header-tool" id="themeToggle" type="button" aria-label="Ganti tema"><i class="bi bi-moon-stars"></i></button>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#beranda">
                <img class="brand-logo header-brand-logo" src="{{ asset('images/Logo-Mili.png') }}" alt="Logo CV MILI WORAYEL MAJU">
                <span class="fw-bold lh-sm fs-6">CV MILI WORAYEL MAJU
                    <small class="d-block text-warning fw-normal"
                        style="font-size:.64rem;letter-spacing:.16em">KONSTRUKSI </small></span></a><button
                class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span
                    class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#visi-misi">Visi & Misi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#layanan">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#proyek">Proyek</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Informasi</a>
                        <ul class="dropdown-menu dropdown-menu-dark shadow border-0">
                            <li><a class="dropdown-item" href="#mitra">Mitra Kerja</a></li>
                            <li><a class="dropdown-item" href="#galeri">Galeri</a></li>
                            <li><a class="dropdown-item" href="#berita">Berita</a></li>
                            <li><a class="dropdown-item" href="#karir">Karir</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimoni">Testimoni</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                </ul>
                <a class="btn btn-gold btn-sm ms-lg-3 px-3" href="#kontak">Konsultasi Proyek
                    <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </nav>

    <header id="beranda" class="hero">
        <div class="hero-overlay" aria-hidden="true"></div>
        <div class="container hero-content">
            <div class="hero-copy">
                <p class="eyebrow mb-3">{{ $companyProfile?->tagline ?: 'PROFIL PERUSAHAAN' }}</p>
                <h1 class="text-white">{{ $companyProfile?->name ?: 'CV MILI WORAYEL MAJU' }} <span style="color:var(--gold)">resmi.</span></h1>
                <p class="hero-description">{{ $companyProfile?->description ? \Illuminate\Support\Str::limit(strip_tags($companyProfile->description), 260) : 'Profil perusahaan, layanan, dan informasi yang telah disetujui untuk ditampilkan kepada publik.' }}</p>
                <div class="d-flex flex-wrap gap-3 mt-4"><a class="btn btn-gold px-4 py-3" href="#layanan">Lihat Layanan <i
                            class="bi bi-arrow-down-right ms-2"></i></a><a class="btn btn-outline-light px-4 py-3"
                        href="#tentang">Profil Perusahaan</a></div>
            </div>
            <aside class="hero-brand-mark" aria-label="Logo CV MILI WORAYEL MAJU">
                <div class="hero-brand-ring"><img src="{{ asset('images/Logo-Mili.png') }}" alt="Logo CV MILI WORAYEL MAJU"></div>
                <span>CV MILI WORAYEL MAJU</span>
            </aside>
        </div>
    </header>

    <section class="hero-stat">
        <div class="container">
            <div class="row g-3">
                <div class="col-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body d-flex gap-3 align-items-center">
                            <i class="bi bi-building"></i>
                            <div>
                                <div class="display-number">{{ number_format($siteStats['projects']) }}</div>
                                <small class="text-secondary">Proyek dipublikasikan</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body d-flex gap-3 align-items-center">
                            <i class="bi bi-people"></i>
                            <div>
                                <div class="display-number">{{ number_format($siteStats['partners']) }}</div>
                                <small class="text-secondary">Mitra kerja</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body d-flex gap-3 align-items-center">
                            <i class="bi bi-person-workspace"></i>
                            <div>
                                <div class="display-number">{{ number_format($siteStats['employees']) }}</div>
                                <small class="text-secondary">Profil pegawai publik</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main>
        <section id="tentang" class="section">
            <div class="container">
                <header class="public-section-header">
                    <p class="eyebrow">01 / TENTANG KAMI</p>
                    <h2 class="section-title mt-2">{{ $companyProfile?->tagline ?: 'Profil resmi' }} <span>perusahaan.</span></h2>
                    <p class="section-intro">{{ $companyProfile?->description ? \Illuminate\Support\Str::limit(strip_tags($companyProfile->description), 240) : 'Informasi profil perusahaan sedang dilengkapi. Silakan hubungi perusahaan untuk mendapatkan informasi resmi.' }}</p>
                </header>
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <p class="lead-copy mt-4">{{ $companyProfile?->description ?: 'Profil, sejarah, serta informasi resmi perusahaan akan ditampilkan di sini setelah dikelola dan dipublikasikan oleh administrator.' }}</p>
                        @if($companyHistories->isNotEmpty())
                            <h3 class="h5 fw-bold mt-4">Sejarah perusahaan</h3>
                            <div class="d-grid gap-3 mt-3">
                                @foreach($companyHistories as $history)
                                    <article class="border-start border-success border-3 ps-3">
                                        @if($history->year)<small class="text-success fw-bold">{{ $history->year }}</small>@endif
                                        <h4 class="h6 fw-bold mb-1">{{ $history->title }}</h4>
                                        @if($history->description)<p class="small text-secondary mb-0">{{ $history->description }}</p>@endif
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="image-frame d-flex align-items-center justify-content-center p-5 bg-white rounded-4 shadow-sm">
                            <img class="img-fluid" style="max-height:260px;object-fit:contain" src="{{ asset('images/Logo-Mili.png') }}" alt="Logo CV MILI WORAYEL MAJU" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="visi-misi" class="section vision">
            <div class="container">
                <div class="section-heading-center text-center mb-5">
                    <p class="eyebrow">02 / ARAH PERUSAHAAN</p>
                    <h2 class="section-title mt-2">Visi dan misi <span>perusahaan.</span></h2>
                </div>
                <div class="row g-5">
                    <div class="col-lg-5">
                        <p class="eyebrow">VISI PERUSAHAAN</p>
                        <div class="mt-4 p-4 border border-light border-opacity-25 rounded-4">
                            <p class="small text-warning fw-bold mb-2">VISI</p>
                            @forelse($companyVisions as $vision)
                                <p class="mb-3 fs-5 lh-base">{{ $vision->content }}</p>
                            @empty
                                <p class="mb-0 text-white-50">Informasi visi belum dipublikasikan.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <p class="eyebrow">MISI KAMI</p>
                        <div class="mt-3">
                            @forelse($companyMissions as $index => $mission)
                                <div class="mission-item d-flex gap-3"><b>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</b><span>{{ $mission->content }}</span></div>
                            @empty
                                <p class="text-white-50">Informasi misi belum dipublikasikan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="layanan" class="section">
            <div class="container">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-5">
                    <div>
                        <p class="eyebrow">03 / BIDANG USAHA</p>
                        <h2 class="section-title mt-2 mb-0">Layanan <span>perusahaan.</span></h2>
                    </div>
                    <p class="text-secondary mb-0" style="max-width:370px">Informasi yang tampil di halaman ini telah diaktifkan untuk publik oleh pengelola.</p>
                </div>
                <div class="row g-4">
                    @forelse($services as $service)
                        <div class="col-md-6 col-xl">
                            <article class="card service-card">
                                <div class="service-icon mb-4"><i class="bi {{ $service->icon ?: 'bi-briefcase' }}"></i></div>
                                <h3>{{ $service->name }}</h3>
                                @if($service->excerpt)<p class="mb-0 mt-3">{{ $service->excerpt }}</p>@endif
                            </article>
                        </div>
                    @empty
                        <div class="col-12"><p class="text-secondary">Informasi layanan belum tersedia.</p></div>
                    @endforelse
                </div>
                @if($products->isNotEmpty())
                    <div class="mt-5">
                        <h3 class="h4 fw-bold mb-4">Produk yang dipublikasikan</h3>
                        <div class="row g-4">
                            @foreach($products as $product)
                                <div class="col-md-6 col-lg-4">
                                    <article class="card service-card h-100">
                                        @if($product->image)<img class="img-fluid rounded-3 mb-4" src="{{ \Illuminate\Support\Str::startsWith($product->image, 'products/') ? asset('storage/'.$product->image) : asset($product->image) }}" alt="{{ $product->name }}" loading="lazy">@endif
                                        <h4 class="h5 fw-bold">{{ $product->name }}</h4>
                                        @if($product->excerpt)<p class="mb-0 mt-3">{{ $product->excerpt }}</p>@endif
                                        @if($product->price !== null)<p class="mb-0 mt-3 fw-bold">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>@endif
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <section id="proyek" class="section pt-0">
            <div class="container">
                <div class="section-heading-center mb-4">
                <p class="eyebrow">04 / KAPABILITAS PROYEK</p>
                    <h2 class="section-title mt-2 mb-3">Proyek yang telah <span>dipublikasikan.</span></h2>
                    @if($projects->isNotEmpty())
                        <div class="project-filter" role="group" aria-label="Filter proyek">
                            <button type="button" class="active" data-filter="all">Semua</button>
                            @foreach($projects->whereNotNull('category_slug')->unique('category_slug') as $category)
                                <button type="button" data-filter="{{ $category->category_slug }}">{{ $category->category_name }}</button>
                            @endforeach
                        </div>
                    @endif
                </div></div>
                <div class="row g-3" id="projectGrid">
                    @forelse($projects as $project)
                        <div class="col-md-6 col-lg-4 project-filter-item" data-category="{{ $project->category_slug ?: '' }}">
                            <article class="project">
                                @if($project->cover_image)
                                    @php($projectImage = \Illuminate\Support\Str::startsWith($project->cover_image, 'projects/') ? asset('storage/'.$project->cover_image) : asset($project->cover_image))
                                    <img src="{{ $projectImage }}" alt="{{ $project->title }}" loading="lazy">
                                @endif
                                <div><small>PROYEK</small><h3 class="h5 fw-bold mt-2 mb-0">{{ $project->title }}</h3>
                                    <p class="project-detail mb-2">
                                        @if($project->category_name)<span><i class="bi bi-tag me-1"></i>{{ $project->category_name }}</span>@endif
                                        @if($project->completed_at)<span><i class="bi bi-calendar3 me-1"></i>{{ date('Y', strtotime($project->completed_at)) }}</span>@endif
                                    </p>
                                    <a class="text-warning fw-bold small text-decoration-none" href="{{ route('projects.show', $project->slug) }}">Baca selengkapnya <i class="bi bi-arrow-right"></i></a>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12"><p class="text-secondary">Belum ada proyek yang dipublikasikan.</p></div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="section commitment">
            <div class="container">
                <div class="section-heading-center text-center">
                    <p class="eyebrow">05 / INFORMASI PERUSAHAAN</p>
                    <h2 class="section-title mx-auto mt-2">Informasi resmi <span>terkelola.</span></h2>
                    <p class="lead-copy mx-auto mt-4">Konten pada halaman ini ditampilkan setelah diperbarui dan dipublikasikan oleh pengelola yang berwenang.</p>
                </div>
            </div>
        </section>

        <section id="mitra" class="section partners">
            <div class="container text-center">
                <div class="section-heading-center"><p class="eyebrow">06 / MITRA KERJA</p>
                <h2 class="section-title mx-auto mt-2">Mitra <span>perusahaan.</span></h2>
                <p class="lead-copy mx-auto mt-3">Daftar mitra ditampilkan berdasarkan data yang telah diaktifkan untuk publik.</p>
                </div>
                @if($partners->isEmpty())
                    <p class="text-secondary mt-4">Belum ada mitra yang dipublikasikan.</p>
                @else
                    <div class="row g-3 mt-3">
                        @foreach($partners as $partner)
                            <div class="col-6 col-md-4 col-lg-3"><article class="partner-card h-100">
                                @if($partner->logo)<img class="partner-image" src="{{ \Illuminate\Support\Str::startsWith($partner->logo, 'partners/') ? asset('storage/'.$partner->logo) : asset($partner->logo) }}" alt="Logo {{ $partner->name }}" loading="lazy">@endif
                                <span>{{ $partner->name }}</span>
                            </article></div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section id="testimoni" class="section testimonials">
            <div class="container">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-5">
                    <div>
                        <div class="section-heading-center"><p class="eyebrow">08 / TESTIMONI MITRA</p>
                        <h2 class="section-title mt-2 mb-2">Kepercayaan yang dibangun melalui <span>hasil kerja.</span></h2>
                        <p class="text-secondary mb-0 mx-auto" style="max-width:560px">Kami menghargai setiap kepercayaan yang diberikan dan menjaganya lewat komunikasi yang terbuka serta pekerjaan yang bertanggung jawab.</p></div>
                    </div>

                </div>
                <div class="row g-4">
                    @forelse($testimonials as $testimonial)
                        <div class="col-md-6 col-lg-4">
                            <article class="testimonial-card">
                                <i class="bi bi-quote quote-icon" aria-hidden="true"></i>
                                <blockquote>“{{ $testimonial->content }}”</blockquote>
                                <div class="testimonial-person">
                                    @if($testimonial->photo)<img class="testimonial-photo" src="{{ \Illuminate\Support\Str::startsWith($testimonial->photo, 'testimonials/') ? asset('storage/'.$testimonial->photo) : asset($testimonial->photo) }}" alt="{{ $testimonial->client_name }}" loading="lazy">@else<span class="testimonial-avatar">{{ mb_strtoupper(mb_substr($testimonial->client_name, 0, 1)) }}</span>@endif
                                    <div>
                                        <strong class="d-block">{{ $testimonial->client_name }}</strong>
                                        @if($testimonial->position || $testimonial->company)<small class="text-secondary">{{ collect([$testimonial->position, $testimonial->company])->filter()->implode(' · ') }}</small>@endif
                                        <div class="testimonial-stars" aria-label="Rating {{ $testimonial->rating }} dari 5">{{ str_repeat('★', max(0, min(5, (int) $testimonial->rating))) }}</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12"><p class="text-secondary">Belum ada testimoni yang dipublikasikan.</p></div>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="faq" class="section faq">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-5 section-heading-center">
                        <p class="eyebrow">09 / PERTANYAAN UMUM</p>
                        <h2 class="section-title mt-2">Jawaban untuk membantu Anda memulai <span>proyek.</span></h2>
                        <p class="lead-copy mt-4">Belum menemukan informasi yang dibutuhkan? Hubungi kami untuk konsultasi awal sesuai kebutuhan proyek Anda.</p>
                        <a class="btn btn-gold mt-2 px-4 py-3" href="#kontak">Hubungi Tim Kami <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                    <div class="col-lg-7">
                        <div class="accordion" id="faqAccordion">
                            @foreach([
                                ['Bagaimana melihat informasi layanan?', 'Halaman ini hanya menampilkan layanan yang dikelola dan diaktifkan untuk publik. Hubungi perusahaan melalui formulir untuk meminta informasi lebih lanjut.'],
                                ['Bagaimana melihat proyek perusahaan?', 'Daftar proyek hanya memuat item yang telah dipublikasikan oleh pengelola. Informasi yang tidak disetujui untuk publikasi tidak ditampilkan.'],
                                ['Bagaimana mengirim pertanyaan?', 'Gunakan formulir kontak pada halaman ini. Pesan akan disimpan untuk ditindaklanjuti oleh petugas perusahaan.'],
                                ['Bagaimana melamar pekerjaan?', 'Periksa halaman Karier untuk melihat posisi aktif, persyaratan, batas waktu, dan tautan lamaran.'],
                                ['Bagaimana data lamaran diproses?', 'Baca Kebijakan Privasi Rekrutmen sebelum mengirim lamaran. CV disimpan secara privat dan aksesnya dibatasi kepada petugas berwenang.']
                            ] as $index => [$question, $answer])
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="faqHeading{{ $index }}">
                                        <button class="accordion-button {{ $index ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}" aria-expanded="{{ $index ? 'false' : 'true' }}" aria-controls="faqCollapse{{ $index }}">{{ $question }}</button>
                                    </h3>
                                    <div id="faqCollapse{{ $index }}" class="accordion-collapse collapse {{ $index ? '' : 'show' }}" aria-labelledby="faqHeading{{ $index }}" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">{{ $answer }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="galeri" class="section gallery-section">
            <div class="container">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-5">
                    <div><p class="eyebrow">09 / GALERI PROYEK</p>
                    <h2 class="section-title mt-2 mb-0">Dokumentasi dari setiap
                        <span>tahap pembangunan.</span></h2></div>
                    <p class="text-secondary mb-0" style="max-width:380px">Dokumentasi yang telah dipublikasikan oleh pengelola perusahaan.</p>
                </div>
                @if($galleries->isEmpty())
                    <p class="text-secondary">Belum ada dokumentasi yang dipublikasikan.</p>
                @else
                    <div class="gallery-grid">
                        @foreach($galleries as $gallery)
                            @if($gallery->file_path)
                                @php($imageUrl = \Illuminate\Support\Str::startsWith($gallery->file_path, 'galleries/') ? asset('storage/'.$gallery->file_path) : asset($gallery->file_path))
                                <a class="gallery-item" href="{{ $imageUrl }}" target="_blank" rel="noopener"><img src="{{ $imageUrl }}" alt="{{ $gallery->alt_text ?: $gallery->caption ?: $gallery->title }}" loading="lazy"><span>{{ $gallery->caption ?: $gallery->title }} <i class="bi bi-arrow-up-right ms-1"></i></span></a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section id="berita" class="section">
            <div class="container">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-5"><div><p class="eyebrow">10 / BERITA TERBARU</p><h2 class="section-title mt-2 mb-0">Informasi <span>perusahaan.</span></h2></div><a href="#kontak" class="btn btn-outline-success">Hubungi Kami <i class="bi bi-arrow-right ms-1"></i></a></div>
                <div class="row g-4">
                    @forelse($newsItems as $article)
                        <div class="col-md-6 col-lg-4"><article class="news-card card">
                            @if($article->cover_image)<img src="{{ \Illuminate\Support\Str::startsWith($article->cover_image, 'news/') ? asset('storage/'.$article->cover_image) : asset($article->cover_image) }}" alt="{{ $article->title }}" loading="lazy">@endif
                            <div class="card-body"><p class="news-date mb-2">{{ $article->category_name ?: 'Berita' }} · {{ \Illuminate\Support\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}</p><h3 class="h5 fw-bold">{{ $article->title }}</h3>@if($article->excerpt)<p class="small text-secondary">{{ \Illuminate\Support\Str::limit(strip_tags($article->excerpt), 160) }}</p>@endif<a href="{{ route('news.show', $article->slug) }}" class="text-success fw-bold small text-decoration-none">Baca selengkapnya <i class="bi bi-arrow-right"></i></a></div>
                        </article></div>
                    @empty
                        <div class="col-12"><p class="text-secondary">Belum ada berita yang dipublikasikan.</p></div>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="karir" class="section pt-0"><div class="container"><div class="career-panel p-4 p-md-5"><div class="row align-items-center g-4"><div class="col-lg-8"><p class="eyebrow">11 / KARIR</p><h2 class="section-title mt-2">Bertumbuh bersama tim yang <span>membangun.</span></h2><p class="text-white-50 mb-0">Temukan lowongan yang tersedia dan kirim lamaran dengan aman melalui formulir rekrutmen kami.</p></div><div class="col-lg-4 text-lg-end"><a href="{{ route('careers.index') }}" class="btn btn-gold px-4 py-3">Lihat lowongan <i class="bi bi-arrow-right ms-2"></i></a></div></div></div></div></section>

        <section id="kontak" class="section pt-0">
            <div class="container">
                <div class="contact-box p-4 p-md-5">
                    <div class="row g-5">
                        <div class="col-lg-5">
                            <p class="eyebrow">HUBUNGI KAMI</p>
                            <h2 class="display-6 fw-bold mt-2">Mulai proyek Anda bersama kami.</h2>
                            <p class="text-white-50 mt-3">Kirim pertanyaan atau informasi yang perlu ditindaklanjuti oleh perusahaan.</p>
                            <div class="mt-4">
                                @if($companyProfile?->address)<p><i class="bi bi-geo-alt-fill text-warning me-2"></i>{{ $companyProfile->address }}</p>@endif
                                <p><i class="bi bi-building-check text-warning me-2"></i>{{ $companyProfile?->name ?: 'CV MILI WORAYEL MAJU' }}</p>
                            </div>
                        </div>
                        <div class="col-lg-7">@if(session('contact_success'))
                        <div class="alert alert-success">{{ session('contact_success') }}</div>@endif<form
                                method="POST" action="{{ route('contact.store') }}" class="row g-3">@csrf<div
                                    class="col-md-6"><input class="form-control" name="name" value="{{ old('name') }}"
                                        placeholder="Nama lengkap" required></div>
                                <div class="col-md-6"><input class="form-control" name="email" type="email"
                                        value="{{ old('email') }}" placeholder="Email" required></div>
                                <div class="col-md-6"><input class="form-control" name="phone"
                                        value="{{ old('phone') }}" placeholder="Nomor telepon"></div>
                                <div class="col-md-6"><input class="form-control" name="subject"
                                        value="{{ old('subject') }}" placeholder="Subjek proyek"></div>
                                <div class="col-12"><textarea class="form-control" name="message" rows="5"
                                        placeholder="Ceritakan kebutuhan proyek Anda"
                                        required>{{ old('message') }}</textarea></div>
                                <div class="col-12"><button class="btn btn-gold px-4 py-3" type="submit">Kirim Pesan <i
                                            class="bi bi-send ms-2"></i></button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="footer py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex align-items-center gap-2">
                        <img class="brand-logo" src="{{ asset('images/Logo-Mili.png') }}" alt="Logo CV MILI WORAYEL MAJU">
                        <div class="fw-bold text-white">CV MILI WORAYEL MAJU
                            <small class="d-block text-warning fw-normal">KONSTRUKSI PAPUA</small>
                        </div>
                    </div>
                    @if($companyProfile?->tagline)<p class="mt-3 mb-0 small">{{ $companyProfile->tagline }}</p>@endif
                </div>
                <div class="col-6 col-lg-2">
                    <h3 class="h6 text-white">Navigasi</h3>
                    <div class="d-grid gap-2 small">
                        <a href="#tentang">Tentang Kami</a>
                        <a href="#layanan">Layanan</a>
                        <a href="#proyek">Kapabilitas</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h3 class="h6 text-white">Hubungi Kami</h3>
                    <ul class="footer-detail">
                        @if($companyProfile?->address)<li><i class="bi bi-geo-alt-fill"></i><span>{{ $companyProfile->address }}</span></li>@endif
                        @if($companyProfile?->email)<li><i class="bi bi-envelope-fill"></i><a href="mailto:{{ $companyProfile->email }}">{{ $companyProfile->email }}</a></li>@endif
                        @if($companyProfile?->phone)<li><i class="bi bi-telephone-fill"></i><a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyProfile->phone) }}">{{ $companyProfile->phone }}</a></li>@endif
                        @if($companyProfile?->whatsapp)<li><i class="bi bi-whatsapp"></i><a href="https://wa.me/{{ preg_replace('/\D/', '', $companyProfile->whatsapp) }}" target="_blank" rel="noopener noreferrer">{{ $companyProfile->whatsapp }}</a></li>@endif
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h3 class="h6 text-white">Manajemen</h3>
                    <p class="small mb-0">Informasi resmi perusahaan dikelola oleh administrator yang berwenang.</p>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <small>© {{ date('Y') }} CV MILI WORAYEL MAJU. Seluruh hak dilindungi.</small>
        </div>
    </footer>
    <div class="quick-contact" aria-label="Kontak cepat">
        @if($companyProfile?->whatsapp)<a class="whatsapp" href="https://wa.me/{{ preg_replace('/\D/', '', $companyProfile->whatsapp) }}" target="_blank" rel="noopener noreferrer" aria-label="Hubungi melalui WhatsApp"><i class="bi bi-whatsapp"></i></a>@endif
        @if($companyProfile?->email)<a class="email" href="mailto:{{ $companyProfile->email }}?subject=Pertanyaan%20Perusahaan" aria-label="Kirim email"><i class="bi bi-envelope-fill"></i></a>@endif
        <a class="back-top" href="#beranda" aria-label="Kembali ke bagian atas"><i class="bi bi-arrow-up"></i></a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" defer></script>
    <script>
        (() => {
            const themeToggle = document.getElementById('themeToggle');
            const languageToggle = document.getElementById('languageToggle');
            const setTheme = (dark) => {
                document.body.classList.toggle('dark-mode', dark);
                themeToggle?.querySelector('i')?.classList.toggle('bi-sun', dark);
                themeToggle?.querySelector('i')?.classList.toggle('bi-moon-stars', !dark);
                localStorage.setItem('mili-theme', dark ? 'dark' : 'light');
            };
            setTheme(localStorage.getItem('mili-theme') === 'dark');
            themeToggle?.addEventListener('click', () => setTheme(!document.body.classList.contains('dark-mode')));

            const setLanguage = (language) => {
                document.documentElement.lang = language;
                languageToggle.textContent = language === 'id' ? 'ID' : 'EN';
                languageToggle.setAttribute('aria-label', language === 'id' ? 'Bahasa Indonesia aktif' : 'English active');
                const copy = {
                    id: {
                        '#visi-misi .section-heading-center .eyebrow': '02 / ARAH PERUSAHAAN', '#visi-misi .section-heading-center .section-title': 'Membangun masa depan yang <span>berkelanjutan.</span>',
                        '#proyek .eyebrow': '04 / KAPABILITAS PROYEK', '#proyek .section-title': 'Dikerjakan dengan presisi, dijaga dengan <span>tanggung jawab.</span>',
                        '#mitra .eyebrow': '06 / MITRA KERJA', '#mitra .section-title': 'Bertumbuh melalui <span>kolaborasi.</span>',
                        '#testimoni .eyebrow': '08 / TESTIMONI MITRA', '#testimoni .section-title': 'Kepercayaan yang dibangun melalui <span>hasil kerja.</span>',
                        '#faq .eyebrow': '09 / PERTANYAAN UMUM', '#faq .section-title': 'Jawaban untuk membantu Anda memulai <span>proyek.</span>'
                    },
                    en: {
                        '#visi-misi .section-heading-center .eyebrow': '02 / COMPANY DIRECTION', '#visi-misi .section-heading-center .section-title': 'Building a sustainable <span>future.</span>',
                        '#proyek .eyebrow': '04 / PROJECT CAPABILITIES', '#proyek .section-title': 'Built with precision and <span>accountability.</span>',
                        '#mitra .eyebrow': '06 / PARTNERS', '#mitra .section-title': 'Growing through <span>collaboration.</span>',
                        '#testimoni .eyebrow': '08 / PARTNER TESTIMONIALS', '#testimoni .section-title': 'Trust built through <span>results.</span>',
                        '#faq .eyebrow': '09 / FREQUENTLY ASKED QUESTIONS', '#faq .section-title': 'Answers to help you start a <span>project.</span>'
                    }
                };
                Object.entries(copy[language]).forEach(([selector, value]) => { const element = document.querySelector(selector); if (element) element.innerHTML = value; });
                const navCopy = { id: ['Tentang','Visi & Misi','Layanan','Proyek','Informasi','Testimoni','FAQ','Kontak'], en: ['About','Vision & Mission','Services','Projects','Information','Testimonials','FAQ','Contact'] };
                document.querySelectorAll('.navbar-nav > .nav-item > .nav-link').forEach((link, index) => { if (navCopy[language][index]) link.textContent = navCopy[language][index]; });
                localStorage.setItem('mili-language', language);
            };
            setLanguage(localStorage.getItem('mili-language') || 'id');
            languageToggle?.addEventListener('click', () => setLanguage(document.documentElement.lang === 'id' ? 'en' : 'id'));

            document.querySelectorAll('[data-filter]').forEach((button) => button.addEventListener('click', () => {
                const filter = button.dataset.filter;
                document.querySelectorAll('[data-filter]').forEach((item) => item.classList.toggle('active', item === button));
                document.querySelectorAll('.project-filter-item').forEach((item) => {
                    item.hidden = filter !== 'all' && item.dataset.category !== filter;
                });
            }));
            // Tombol panah hanya ditampilkan ketika pengunjung telah menggulir cukup jauh dari header.
            const backTop = document.querySelector('.back-top');
            const toggleBackTop = () => backTop?.classList.toggle('is-visible', window.scrollY > 420);
            window.addEventListener('scroll', toggleBackTop, { passive: true });
            toggleBackTop();

        })();
    </script>
</body>

</html>
