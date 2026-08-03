<section id="kontak" class="section contact container">
    <div class="contact-heading reveal">
        <div class="section-label">08 / KONTAK</div>
        <h2>Mari mulai sesuatu yang <em>berarti.</em></h2>
        <p>Ceritakan kebutuhan Anda. Tim kami akan menghubungi dalam 1×24 jam kerja.</p>
        <div class="contact-details"><a href="https://wa.me/6281234567890" target="_blank"
                rel="noopener">WhatsApp<br><b>+62 812 3456 7890</b></a><a
                href="mailto:halo@nusantarakreasi.id">Email<br><b>halo@nusantarakreasi.id</b></a></div>
    </div>
    <form class="contact-form reveal" method="POST" action="{{ route('contact.store') }}">@csrf<label>Nama lengkap<input
                required name="name" placeholder="Nama Anda"></label><label>Email<input required name="email"
                type="email" placeholder="email@perusahaan.com"></label><label>Nomor telepon<input name="phone"
                placeholder="08xx xxxx xxxx"></label><label>Subjek<input name="subject"
                placeholder="Kebutuhan Anda"></label><label>Pesan<textarea required name="message"
                placeholder="Bagaimana kami dapat membantu?"></textarea></label><button class="button"
            type="submit">Kirim pesan <span>→</span></button>
        <p class="form-message">{{ session('contact_success') }}</p>
    </form>
</section>
<section class="map-section"><iframe title="Peta lokasi kantor"
        src="https://www.google.com/maps?q=Jakarta%20Indonesia&output=embed" loading="lazy"></iframe>
    <div class="map-pin"><span>●</span> Jakarta, Indonesia</div>
</section>