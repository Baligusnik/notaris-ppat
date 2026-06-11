<aside class="settings-panel" aria-hidden="true">
    <div class="settings-card" role="dialog" aria-modal="true" aria-labelledby="settingsTitle">
        <div class="settings-head">
            <div>
                <span class="settings-kicker">SYSTEM</span>
                <strong id="settingsTitle" data-i18n="settings.title">Pengaturan</strong>
            </div>
            <button type="button" class="settings-close" aria-label="Tutup pengaturan" onclick="const p=document.querySelector('.settings-panel');document.body.classList.remove('settings-open');p.classList.remove('open');p.setAttribute('aria-hidden','true')">&times;</button>
        </div>

        <div class="settings-group">
            <label data-i18n="settings.language">Pilihan Bahasa</label>
            <div class="segmented settings-segmented">
                <button type="button" data-lang-btn="id" onclick="window.setPublicLanguage && window.setPublicLanguage('id')">Bahasa Indonesia</button>
                <button type="button" data-lang-btn="en" onclick="window.setPublicLanguage && window.setPublicLanguage('en')">English</button>
            </div>
        </div>

        <div class="settings-group">
            <label data-i18n="settings.auth">Login / Logout</label>
            <button type="button" class="outline-btn" data-auth-btn>Login</button>
            <small data-i18n="settings.loginNote">Login admin akan tersedia di dashboard notaris.</small>
        </div>

        <div class="settings-group">
            <label data-i18n="settings.display">Ukuran Tampilan</label>
            <div class="segmented settings-segmented four">
                <button type="button" data-view-btn="auto" onclick="window.setPublicView && window.setPublicView('auto')">Auto</button>
                <button type="button" data-view-btn="desktop" onclick="window.setPublicView && window.setPublicView('desktop')">Desktop</button>
                <button type="button" data-view-btn="tablet" onclick="window.setPublicView && window.setPublicView('tablet')">Tablet</button>
                <button type="button" data-view-btn="phone" onclick="window.setPublicView && window.setPublicView('phone')">Handphone</button>
            </div>
        </div>

        <div class="settings-group">
            <label data-i18n="settings.theme">Tema Tampilan</label>
            <div class="segmented settings-segmented">
                <button type="button" data-theme-btn="light" onclick="window.setPublicTheme && window.setPublicTheme('light')" data-i18n="settings.light">Siang</button>
                <button type="button" data-theme-btn="dark" onclick="window.setPublicTheme && window.setPublicTheme('dark')" data-i18n="settings.dark">Malam</button>
            </div>
        </div>
    </div>
</aside>
