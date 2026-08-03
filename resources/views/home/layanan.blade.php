<section id="layanan" class="section container">
    <div class="service-layout">
        <div class="reveal">
            <div class="section-label">03 / LAYANAN</div>
            <h2>Dari ide hingga <em>dampak.</em></h2>
            <p>Layanan menyeluruh untuk kebutuhan transformasi digital organisasi Anda.</p>
        </div>
        <div class="service-list reveal">
            @foreach(['Konsultasi IT', 'Website Development', 'Mobile Development', 'UI/UX Design', 'Cloud Service', 'Sistem Informasi', 'Maintenance Support'] as $number => $service)
                <div><span>{{ str_pad($number + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $service }}</h3>
                    <p>Solusi profesional yang sesuai tujuan bisnis.</p>
            </div>@endforeach</div>
    </div>
</section>