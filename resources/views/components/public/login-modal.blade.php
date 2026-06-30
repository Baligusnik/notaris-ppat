<div class="login-modal" id="loginModal" aria-hidden="true">
    <div class="login-blur-orbit" aria-hidden="true"></div>
    <div class="login-dialog" role="dialog" aria-modal="true" aria-labelledby="loginTitle">
        <button type="button" class="login-close" data-close-login aria-label="Tutup login">&times;</button>
        <div class="login-garuda-stage" aria-hidden="true">
            <img src="{{ asset('images/garuda_logo.png') }}" alt="">
        </div>
        <form class="login-card" id="publicLoginForm" method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="login-bg-garuda" aria-hidden="true"></div>
            <div class="login-brand">
                <img src="{{ asset('images/garuda_logo.png') }}" alt="Logo Garuda">
                <span data-i18n="auth.adminArea">Admin Area</span>
            </div>
            <p class="login-kicker" data-i18n="auth.officeKicker">Kantor Notaris/PPAT</p>
            <h2 id="loginTitle" data-i18n="auth.loginTitle">Masuk Dashboard Notaris</h2>
            <p class="login-desc" data-i18n="auth.loginDesc">Silakan masuk untuk mengelola data kantor, kontak, layanan, dan monitoring berkas.</p>
            <label><span data-i18n="auth.username">Email / Username</span><input type="email" name="email" autocomplete="username" required data-login-email></label>
            <p class="login-status-note" data-login-staff-status hidden></p>
            <label><span data-i18n="auth.password">Password</span><input type="password" name="password" autocomplete="current-password" required></label>

            <div class="login-captcha" data-login-captcha>
                <div>
                    <span data-i18n="auth.captcha">Captcha keamanan</span>
                    <strong data-captcha-question>0 + 0 = ?</strong>
                </div>
                <button type="button" data-refresh-captcha aria-label="Refresh captcha" data-i18n="auth.refresh">Refresh</button>
                <input type="number" name="captcha" data-captcha-answer placeholder="Jawaban" data-i18n-placeholder="auth.answer" required>
            </div>
            <p class="login-error" data-login-error @if (! $errors->any()) hidden @endif>{{ $errors->first() }}</p>

            <button type="submit" class="primary-btn login-submit" data-i18n="auth.loginSubmit">Masuk Sekarang</button>
            <p class="login-register-link"><span data-i18n="auth.noAccount">Belum punya akun?</span> <a href="#" data-register-account data-i18n="auth.registerLink">Register</a></p>
            <small data-i18n="auth.loginSmall">Gunakan akun yang terdaftar dan terverifikasi oleh sistem kantor.</small>
        </form>
    </div>
</div>
