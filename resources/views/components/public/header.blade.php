<header class="site-header">
    <div class="topbar">
        <div class="brand-logos" aria-label="Logo kantor">
            <img class="brand-logo brand-logo-garuda" src="{{ asset('images/garuda_logo.png') }}" alt="Logo Garuda">
            <img class="brand-logo" src="{{ asset('images/logo-ini-1.jpg') }}" alt="Logo INI">
            <img class="brand-logo" src="{{ asset('images/PPAT.png') }}" alt="Logo PPAT">
        </div>
        <a href="#profil" class="office-title">
            <span data-i18n="office.type">KANTOR NOTARIS/PPAT</span>
            <strong data-i18n="office.name">NI LUH PUTU SURYA MIRA YANTI, SH., M.Kn.</strong>
        </a>
        <button class="settings-toggle" type="button" aria-label="Pengaturan" data-i18n-aria="settings.aria" title="Pengaturan" onclick="const p=document.querySelector('.settings-panel');document.body.classList.add('settings-open');p.classList.add('open');p.setAttribute('aria-hidden','false')"><span class="gear-icon" aria-hidden="true">&#9881;</span></button>
    </div>
</header>
    <nav class="main-nav" aria-label="Navigasi utama">
    <button class="hamburger" type="button" aria-expanded="false"><span></span><span></span><span></span><b data-i18n="nav.menu">Menu</b></button>
        <div class="nav-links">
            <a href="#profil"><img class="mobile-nav-icon" src="{{ asset('images/nav-icons/profil.svg') }}" alt=""><span data-i18n="nav.profile">Profil</span></a>
            <a href="#berkas"><img class="mobile-nav-icon" src="{{ asset('images/nav-icons/berkas.svg') }}" alt=""><span data-i18n="nav.documents">Berkas yang Ditangani</span></a>
            <a href="#layanan"><img class="mobile-nav-icon" src="{{ asset('images/nav-icons/layanan.svg') }}" alt=""><span data-i18n="nav.services">Jenis Layanan</span></a>
            <a href="#tracking"><img class="mobile-nav-icon" src="{{ asset('images/nav-icons/tracking.svg') }}" alt=""><span data-i18n="nav.tracking">Tracking Berkas</span></a>
            <a href="{{ route('user.dashboard.registration') }}"><img class="mobile-nav-icon" src="{{ asset('images/nav-icons/pendaftaran.svg') }}" alt=""><span data-i18n="nav.registration">Pendaftaran Berkas Baru</span></a>
            <a href="#kontak"><img class="mobile-nav-icon" src="{{ asset('images/nav-icons/kontak.svg') }}" alt=""><span data-i18n="nav.contact">Kontak</span></a>
            <a href="#alamat"><img class="mobile-nav-icon" src="{{ asset('images/nav-icons/alamat.svg') }}" alt=""><span data-i18n="nav.address">Alamat</span></a>
        </div>
    </nav>
