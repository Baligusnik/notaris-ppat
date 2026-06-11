<div id="tracking" class="tracking-anchor" aria-hidden="true"></div>
<div class="tracking-modal" id="trackingModal" aria-hidden="true">
    <div class="tracking-dialog" role="dialog" aria-modal="true" aria-labelledby="trackingModalTitle">
        <button type="button" class="tracking-close" data-close-tracking>?</button>
        <section class="tracking-section">
            <div class="section-heading compact-heading">
                <p data-i18n="tracking.eyebrow">Pantau Progres</p>
                <h2 id="trackingModalTitle" data-i18n="tracking.title">Tracking Berkas</h2>
                <span data-i18n="tracking.desc">Masukkan kode tracking untuk melihat status berkas tanpa login.</span>
            </div>
            <div class="form-shell modal-form-shell">
                <form id="trackingForm" novalidate>
                    <label for="trackingCode" data-i18n="tracking.code">Kode tracking</label>
                    <div class="inline-form">
                        <input id="trackingCode" name="trackingCode" placeholder="TRK-2026-0001" data-i18n-placeholder="tracking.placeholder">
                        <button class="primary-btn" type="submit" data-i18n="tracking.button">Cek Status</button>
                    </div>
                    <p class="error" data-error-for="trackingCode"></p>
                </form>
                <div id="trackingResult" class="tracking-result" hidden></div>
            </div>
        </section>
    </div>
</div>
