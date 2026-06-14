<div class="register-modal" id="registerModal" aria-hidden="true">
    <div class="register-dialog" role="dialog" aria-modal="true" aria-labelledby="registerTitle">
        <button type="button" class="register-close" data-close-register aria-label="Tutup register">&times;</button>
        <div class="register-garuda-stage" aria-hidden="true">
            <img src="{{ asset('images/garuda_logo.png') }}" alt="">
        </div>
        <form class="register-card" id="publicRegisterForm">
            <div class="register-bg-garuda" aria-hidden="true"></div>
            <section class="register-step active" data-register-step="choice">
                <p class="login-kicker" data-i18n="auth.registerKicker">Registrasi Akun</p>
                <h2 id="registerTitle" data-i18n="auth.registerTitle">Daftar Sebagai Apa?</h2>
                <p class="login-desc" data-i18n="auth.registerDesc">Pilih jenis akun terlebih dahulu. Form berikutnya akan disesuaikan dengan kebutuhan akun.</p>
                <div class="register-choice-grid">
                    <button type="button" data-select-register-role="user"><strong data-i18n="auth.roleUser">User Standar</strong><small data-i18n="auth.roleUserDesc">Untuk klien/pemohon berkas</small></button>
                    <button type="button" data-select-register-role="staff"><strong data-i18n="auth.roleStaff">Pegawai / Staff</strong><small data-i18n="auth.roleStaffDesc">Untuk operasional kantor</small></button>
                    <button type="button" data-select-register-role="notary"><strong data-i18n="auth.roleNotary">User / Notaris</strong><small data-i18n="auth.roleNotaryDesc">Akun utama notaris, satu kali daftar</small></button>
                </div>
                <p class="login-register-link"><span data-i18n="auth.haveAccount">Sudah punya akun?</span> <a href="#" data-back-login data-i18n="auth.loginLink">Login</a></p>
            </section>

            <section class="register-step" data-register-step="form" hidden>
                <button type="button" class="register-back" data-register-back><i aria-hidden="true"></i><span data-i18n="auth.chooseAgain">Pilih ulang jenis akun</span></button>
                <input type="hidden" name="role" value="user">
                <p class="login-kicker" data-register-role-kicker>User Standar</p>
                <h2 data-register-form-title>Registrasi User Standar</h2>
                <p class="login-desc" data-register-form-desc>Lengkapi data dasar untuk membuat akun pemohon.</p>

                <div class="register-fields" data-register-fields="user">
                    <label><span data-i18n="auth.fullName">Nama lengkap</span><input name="user_name" autocomplete="name"></label>
                    <label><span data-i18n="auth.activePhone">Nomor HP aktif untuk OTP</span><input name="user_phone" inputmode="tel" placeholder="08xxxxxxxxxx"></label>
                    <label><span data-i18n="auth.activeEmail">Email aktif perangkat</span><input name="user_email" type="email" autocomplete="email" placeholder="nama@email.com"></label>
                </div>

                <div class="register-fields" data-register-fields="staff" hidden>
                    <label><span data-i18n="auth.staffEmail">Email pegawai / staff</span><input name="staff_email" type="email" autocomplete="email" placeholder="staff@kantor.com"></label>
                </div>

                <div class="register-fields" data-register-fields="notary" hidden>
                    <div class="notary-lock-note"><strong data-i18n="auth.notarySecurity">Keamanan Notaris:</strong> <span data-i18n="auth.notarySecurityText">akun notaris hanya bisa dibuat satu kali. Setelah terdaftar, pilihan ini akan terkunci.</span></div>
                    <label><span data-i18n="auth.notaryEmail">Email utama notaris</span><input name="notary_email" type="email" autocomplete="email" placeholder="notaris@email.com"></label>
                    <label><span data-i18n="auth.recoveryEmail">Email pemulihan</span><input name="recovery_email" type="email" placeholder="pemulihan@email.com"></label>
                    <label><span>Nomor HP aktif untuk OTP</span><input name="notary_phone" inputmode="tel" placeholder="08xxxxxxxxxx"></label>
                </div>

                <div class="register-password-grid"><label class="register-password"><span data-i18n="auth.password">Password</span><input name="password" type="password" autocomplete="new-password" required placeholder="Minimal 8 karakter" data-i18n-placeholder="auth.passwordPlaceholder"></label><label class="register-password"><span data-i18n="auth.repeatPassword">Ulangi Password</span><input name="password_confirmation" type="password" autocomplete="new-password" required placeholder="Ketik ulang password" data-i18n-placeholder="auth.repeatPasswordPlaceholder"></label></div>
                <div class="password-meter" data-password-meter><span></span></div>
                <p class="password-hint" data-password-hint>Masukkan password dengan huruf besar, huruf kecil, angka, dan simbol.</p>

                <div class="register-otp-box">
                    <span data-i18n="auth.otpTitle">Verifikasi OTP</span>
                    <p data-otp-text>Kode OTP akan dikirim sesuai jenis akun.</p>
                    <button type="button" class="secondary-btn" data-send-otp data-i18n="auth.sendOtp">Kirim OTP</button>
                </div>
                <p class="login-error" data-register-error hidden></p>
                <button type="submit" class="primary-btn login-submit" data-i18n="auth.registerSubmit">Register Akun</button>
                <p class="login-register-link">Sudah punya akun? <a href="#" data-back-login>Login</a></p>
            </section>
        </form>
    </div>
</div>
