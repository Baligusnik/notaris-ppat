<section id="layanan" class="section soft-section">
    <div class="section-heading">
        <p data-i18n="services.eyebrow">Layanan</p>
        <h2 data-i18n="services.title">Jenis Layanan</h2>
        <span data-i18n="services.desc">Pilih layanan untuk langsung mendaftarkan berkas atau melihat alur prosesnya.</span>
    </div>
    <div class="service-category-tabs" role="tablist" aria-label="Kategori layanan">
        <button type="button" class="active" data-service-filter="all" data-i18n="services.all">Semua</button>
        <button type="button" data-service-filter="notary" data-i18n="services.notary">Layanan Notaris</button>
        <button type="button" data-service-filter="ppat" data-i18n="services.ppat">Layanan PPAT</button>
    </div>
    <div class="service-catalog" id="serviceCatalog"></div>
</section>

<div class="process-modal" id="processModal" aria-hidden="true">
    <div class="process-dialog" role="dialog" aria-modal="true" aria-labelledby="processTitle">
        <button type="button" class="process-close" data-close-process>?</button>
        <p class="eyebrow" id="processCategory"></p>
        <h3 id="processTitle"></h3>
        <div id="processSteps" class="process-steps"></div>
    </div>
</div>
