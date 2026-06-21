(() => {
    const translations = {
        'Beranda': 'Home', 'Layanan': 'Services', 'Tracking': 'Tracking', 'Kontak': 'Contact',
        'Klien': 'Client', 'Dashboard': 'Dashboard', 'Pengajuan Saya': 'My Applications',
        'Buat Pengajuan': 'New Application', 'Upload Dokumen': 'Upload Documents',
        'Jadwal Temu': 'Appointments', 'Notifikasi': 'Notifications', 'Pengaturan': 'Settings', 'Keluar': 'Sign Out',
        'RINGKASAN AKUN': 'ACCOUNT SUMMARY', 'Halo, Komang Arya': 'Hello, Komang Arya',
        'Pantau pengajuan, dokumen, dan jadwal Anda dalam satu tempat.': 'Monitor your applications, documents, and appointments in one place.',
        '+ Buat Pengajuan': '+ New Application', 'Total Pengajuan': 'Total Applications',
        'Sedang Diproses': 'In Progress', 'Selesai': 'Completed', 'Menunggu Dokumen': 'Waiting for Documents',
        '+1 bulan ini': '+1 this month', '50% pengajuan': '50% of applications',
        'Perlu tindakan': 'Action required', 'Jadwal Mendatang': 'Upcoming Appointment',
        'Ringkasan pengajuan yang perlu Anda pantau': 'Important applications to monitor', 'Lihat Semua': 'View All',
        'Sedang diproses pada tahap Penandatanganan AJB.': 'Currently at the AJB signing stage.',
        'Menunggu SPPT PBB terbaru untuk melanjutkan validasi pajak.': 'Waiting for the latest SPPT PBB to continue tax validation.',
        'Pengajuan telah selesai dan dokumen siap diambil.': 'The application is complete and documents are ready for pickup.',
        'Progress 67% · Estimasi 7–11 hari kerja': 'Progress 67% · Estimated 7–11 business days',
        'Progress 38% · Perlu tindakan': 'Progress 38% · Action required', 'Selesai 9 Juni 2026': 'Completed June 9, 2026',
        'Jadwal Terdekat': 'Nearest Appointment', 'Penandatanganan AJB': 'AJB Signing',
        'Selasa, 10.00 WITA': 'Tuesday, 10:00 WITA',
        'Kantor Notaris/PPAT': 'Notary/PPAT Office', 'Lihat Jadwal': 'View Schedule',
        'MONITORING BERKAS': 'FILE MONITORING', 'Pantau seluruh pengajuan dan progres penyelesaiannya.': 'Monitor all applications and their completion progress.',
        'Semua Status': 'All Statuses', 'Diproses': 'In Progress', 'Menunggu Kelengkapan': 'Waiting for Completion',
        'Tanggal Pengajuan': 'Application Date', 'Status Saat Ini': 'Current Status', 'Estimasi Selesai': 'Estimated Completion',
        'Dokumen Dibutuhkan': 'Required Document', 'Tanggal Selesai': 'Completion Date', 'Pengambilan': 'Pickup',
        'Siap diambil': 'Ready for pickup', 'Progress': 'Progress', 'Detail Pengajuan': 'Application Details', 'Detail Tracking': 'Tracking Details',
        'FORM DINAMIS': 'DYNAMIC FORM', 'Pilih layanan dan lengkapi data secara bertahap.': 'Choose a service and complete the information step by step.',
        'Pilih Jenis Layanan': 'Choose Service Type', 'Para Pihak': 'Parties', 'Data Objek': 'Object Data',
        'Dokumen': 'Documents', 'Review': 'Review', 'Sebelumnya': 'Previous', 'Selanjutnya': 'Next', 'Kirim Pengajuan': 'Submit Application',
        'DOKUMEN TAMBAHAN': 'ADDITIONAL DOCUMENTS', 'Unggah dokumen yang diminta oleh admin atau notaris.': 'Upload documents requested by the administrator or notary.',
        'Upload Dokumen': 'Upload Document', 'Riwayat Upload': 'Upload History', 'Ganti Dokumen': 'Replace Document',
        'AGENDA KLIEN': 'CLIENT AGENDA', 'Jadwal Temu Notaris': 'Notary Appointments',
        'Kelola jadwal konsultasi, klarifikasi, dan penandatanganan.': 'Manage consultation, clarification, and signing appointments.',
        '+ Minta Jadwal': '+ Request Appointment', 'Konfirmasi Hadir': 'Confirm Attendance', 'Minta Jadwal Ulang': 'Reschedule', 'Tambah ke Kalender': 'Add to Calendar',
        'PUSAT INFORMASI': 'INFORMATION CENTER', 'Semua update terkait pengajuan dan jadwal Anda.': 'All updates related to your applications and appointments.',
        'Tandai Semua Dibaca': 'Mark All as Read', 'Tandai dibaca': 'Mark as Read', 'Lihat Semua Notifikasi': 'View All Notifications',
        'SISTEM AKUN KLIEN': 'CLIENT ACCOUNT SYSTEM', 'Pilihan Bahasa': 'Language', 'Ukuran Tampilan': 'Display Size',
        'Tema Tampilan': 'Display Theme', 'Siang': 'Light', 'Malam': 'Dark', 'Handphone': 'Mobile',
        'Keamanan Akun': 'Account Security', 'Ubah Password': 'Change Password',
        'Terakhir diubah 30 hari lalu': 'Last changed 30 days ago', 'Logout dari Semua Perangkat': 'Sign Out from All Devices',
        'Keluar dari seluruh sesi aktif': 'End all active sessions', 'Lihat & Ubah Profil': 'View & Edit Profile',
        'Keluar dari akun?': 'Sign out of your account?', 'Anda perlu masuk kembali untuk membuka dashboard.': 'You will need to sign in again to access the dashboard.',
        'Batal': 'Cancel', 'Ya, Keluar': 'Yes, Sign Out'
    };
    const reverse = Object.fromEntries(Object.entries(translations).map(([id, en]) => [en, id]));
    const placeholders = {'Cari nomor registrasi atau layanan': 'Search registration number or service'};

    const sourceTexts = new WeakMap();

    function translateText(root, language) {
        const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
        const nodes = [];
        while (walker.nextNode()) nodes.push(walker.currentNode);
        nodes.forEach(node => {
            const value = node.nodeValue;
            const trimmed = value.trim();
            if (!trimmed) return;
            if (!sourceTexts.has(node)) sourceTexts.set(node, reverse[trimmed] || trimmed);
            const source = sourceTexts.get(node);
            const output = language === 'en' ? (translations[source] || source) : source;
            node.nodeValue = value.replace(trimmed, output);
        });
    }

    window.applyDashboardLanguage = function (language) {
        document.documentElement.lang = language;
        document.documentElement.dataset.userLang = language;
        translateText(document.body, language);
        document.querySelectorAll('[placeholder]').forEach(input => {
            const map = language === 'en' ? placeholders : Object.fromEntries(Object.entries(placeholders).map(([id, en]) => [en, id]));
            if (map[input.placeholder]) input.placeholder = map[input.placeholder];
        });
        document.querySelectorAll('[data-dashboard-lang]').forEach(button => button.classList.toggle('active', button.dataset.dashboardLang === language));
        localStorage.setItem('userDashboardLanguage', language);
        localStorage.setItem('publicLanguage', language);
    };

    document.addEventListener('DOMContentLoaded', () => {
        applyDashboardLanguage(localStorage.getItem('userDashboardLanguage') || localStorage.getItem('publicLanguage') || 'id');
        document.addEventListener('click', event => {
            const button = event.target.closest('[data-dashboard-lang]');
            if (button) applyDashboardLanguage(button.dataset.dashboardLang);
        });
    });
})();
