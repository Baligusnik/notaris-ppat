(() => {
    const translations = {
        'Beranda': 'Home', 'Layanan': 'Services', 'Tracking': 'Tracking', 'Kontak': 'Contact',
        'KANTOR NOTARIS/PPAT': 'NOTARY/PPAT OFFICE', 'Klien': 'Client', 'Dashboard': 'Dashboard',
        'Pengajuan Saya': 'My Applications', 'Buat Pengajuan': 'New Application', 'Upload Dokumen': 'Upload Documents',
        'Jadwal Temu': 'Appointments', 'Notifikasi': 'Notifications', 'Pengaturan': 'Settings', 'Keluar': 'Sign Out',
        'RINGKASAN AKUN': 'ACCOUNT SUMMARY', 'Halo,': 'Hello,',
        'Pantau pengajuan, dokumen, dan jadwal Anda dalam satu tempat.': 'Monitor your applications, documents, and appointments in one place.',
        '+ Buat Pengajuan': '+ New Application', 'Total Pengajuan': 'Total Applications', 'Data dari database': 'From database',
        'Sedang Diproses': 'In Progress', 'Dalam Proses': 'In Progress', 'Selesai': 'Completed', 'Menunggu Dokumen': 'Waiting for Documents',
        'Menunggu Konfirmasi': 'Waiting for Confirmation', 'Disetujui': 'Approved', 'Ditolak': 'Rejected', 'Dijadwalkan Ulang': 'Rescheduled', 'Dibatalkan': 'Cancelled',
        'Berkas berjalan': 'Active files', 'Siap/selesai': 'Ready/completed', 'Perlu tindakan': 'Action required',
        'Jadwal Mendatang': 'Upcoming Appointment', 'Belum ada': 'None yet',
        'Ringkasan pengajuan yang perlu Anda pantau': 'Important applications to monitor', 'Lihat Semua': 'View All', 'Lihat semua': 'View all',
        'Status berkas diperbarui': 'File status updated', 'Dokumen tambahan diminta': 'Additional document requested',
        'Layanan belum dipilih': 'Service not selected', 'Diperbarui': 'Updated', 'Belum ada pengajuan.': 'No applications yet.',
        'Klik tombol Buat Pengajuan untuk mendaftarkan berkas pertama Anda.': 'Click New Application to register your first file.',
        'Jadwal Terdekat': 'Nearest Appointment', 'Kantor Notaris/PPAT': 'Notary/PPAT Office', 'Lihat Jadwal': 'View Schedule',
        'Jadwal yang disetujui kantor akan tampil di sini.': 'Office-approved appointments will appear here.',
        'MONITORING BERKAS': 'FILE MONITORING', 'Pantau seluruh pengajuan dan progres penyelesaiannya.': 'Monitor all applications and their completion progress.',
        'Semua Status': 'All Statuses', 'Diproses': 'In Progress', 'Menunggu Kelengkapan': 'Waiting for Completion',
        'Tanggal Pengajuan': 'Application Date', 'Kode Tracking': 'Tracking Code', 'Tanggal Selesai': 'Completion Date',
        'Detail dari database': 'Details from database', 'Belum ada pengajuan berkas.': 'No file applications yet.',
        'Data akan muncul setelah pengajuan tersimpan ke database.': 'Data will appear after the application is saved to the database.',
        'FORM DINAMIS': 'DYNAMIC FORM', 'Pilih layanan dan lengkapi data secara bertahap.': 'Choose a service and complete the information step by step.',
        'Pilih Jenis Layanan': 'Choose Service Type', 'Layanan': 'Service', 'Notaris': 'Notary', 'Para Pihak': 'Parties', 'Konfigurasi Para Pihak': 'Party Configuration',
        'Data Objek': 'Object Data', 'Dokumen': 'Documents', 'Upload Dokumen Wajib': 'Upload Required Documents',
        'Format PDF/JPG/PNG, maksimal 2 MB.': 'PDF/JPG/PNG format, maximum 2 MB.', 'Review & Submit': 'Review & Submit',
        'Data yang diisi sudah benar.': 'The entered data is correct.', 'Review': 'Review', 'Sebelumnya': 'Previous', 'Selanjutnya': 'Next', 'Kirim Pengajuan': 'Submit Application',
        'DOKUMEN TAMBAHAN': 'ADDITIONAL DOCUMENTS', 'Unggah dokumen yang diminta oleh admin atau notaris.': 'Upload documents requested by the administrator or notary.',
        'Riwayat Upload': 'Upload History', 'Tidak ada catatan tambahan.': 'No additional notes.', 'Diminta': 'Requested',
        'Belum ada permintaan dokumen.': 'No document requests yet.', 'Jika admin meminta kelengkapan berkas, daftarnya akan muncul di sini.': 'If an admin requests file completion, the list will appear here.',
        'AGENDA KLIEN': 'CLIENT AGENDA', 'Jadwal Temu Notaris': 'Notary Appointments',
        'Kelola jadwal konsultasi, klarifikasi, dan penandatanganan.': 'Manage consultation, clarification, and signing appointments.',
        '+ Minta Jadwal': '+ Request Appointment', 'Belum ada catatan dari kantor.': 'No note from the office yet.', 'Detail': 'Details',
        'Belum ada jadwal temu.': 'No appointments yet.', 'Jadwal dari database akan muncul setelah dibuat atau disetujui admin.': 'Database appointments will appear after they are created or approved by admin.',
        'PUSAT INFORMASI': 'INFORMATION CENTER', 'Semua update terkait pengajuan dan jadwal Anda.': 'All updates related to your applications and appointments.',
        'Tandai Semua Dibaca': 'Mark All as Read', 'Tandai dibaca': 'Mark as Read', 'Lihat Semua Notifikasi': 'View All Notifications',
        'Belum ada notifikasi.': 'No notifications yet.', 'Update dari database akan muncul di sini.': 'Database updates will appear here.',
        'SISTEM AKUN KLIEN': 'CLIENT ACCOUNT SYSTEM', 'Pilihan Bahasa': 'Language', 'Bahasa Indonesia': 'Indonesian',
        'Ukuran Tampilan': 'Display Size', 'Tema Tampilan': 'Display Theme', 'Siang': 'Light', 'Malam': 'Dark', 'Handphone': 'Mobile',
        'Keamanan Akun': 'Account Security', 'Ubah Password': 'Change Password', 'Terakhir diubah 30 hari lalu': 'Last changed 30 days ago',
        'Logout dari Semua Perangkat': 'Sign Out from All Devices', 'Keluar dari seluruh sesi aktif': 'End all active sessions',
        'Lihat & Ubah Profil': 'View & Edit Profile', 'Tutup pengaturan': 'Close settings',
        'JADWAL TEMU': 'APPOINTMENT', 'Minta Jadwal': 'Request Appointment', 'Pilih waktu temu. Admin akan mengonfirmasi atau memberi alternatif jika jadwal penuh.': 'Choose an appointment time. Admin will confirm or provide an alternative if the schedule is full.',
        'Detail Jadwal': 'Appointment Details', 'Keperluan': 'Purpose', 'Tanggal': 'Date', 'Jam': 'Time', 'Mode Pertemuan': 'Meeting Mode',
        'Datang ke Kantor': 'Visit Office', 'Online / Video Call': 'Online / Video Call', 'Telepon': 'Phone', 'Berkas Terkait': 'Related File',
        'Tidak terkait berkas tertentu': 'Not related to a specific file', 'Catatan': 'Note', 'Kirim Permintaan': 'Send Request',
        'PROFIL KLIEN': 'CLIENT PROFILE', 'Edit & Ubah Profil': 'Edit & Update Profile', 'Perbarui identitas akun agar admin kantor lebih mudah menghubungi Anda.': 'Update your account identity so office admins can contact you more easily.',
        'Ganti Foto': 'Change Photo', 'Data Pribadi': 'Personal Data', 'Nama Lengkap': 'Full Name', 'Nama Panggilan': 'Nickname', 'Nomor WhatsApp': 'WhatsApp Number', 'Email Aktif': 'Active Email', 'Alamat Domisili': 'Residential Address',
        'Identitas & Preferensi': 'Identity & Preferences', 'Pekerjaan': 'Occupation', 'Metode Kontak Utama': 'Main Contact Method', 'Bahasa Notifikasi': 'Notification Language',
        'Status Akun': 'Account Status', 'Data akun diambil dari database user yang sedang login.': 'Account data is taken from the logged-in user database.', 'Simpan Profil': 'Save Profile',
        'Keluar dari akun?': 'Sign out of your account?', 'Anda perlu masuk kembali untuk membuka dashboard.': 'You will need to sign in again to access the dashboard.',
        'Batal': 'Cancel', 'Ya, Keluar': 'Yes, Sign Out', 'Belum ada layanan aktif.': 'No active services yet.',
        'Admin/notaris perlu mengisi data layanan di database terlebih dahulu.': 'Admin/notary needs to fill service data in the database first.',
        'Belum ada layanan aktif di database.': 'No active services in the database.', 'Periksa kembali data lalu kirim pengajuan.': 'Review the data again, then submit the application.',
        'Upload foto profil akan disambungkan ke penyimpanan akun.': 'Profile photo upload will be connected to account storage.', 'Dokumen dipilih dan siap diunggah.': 'Document selected and ready to upload.',
        'Profil berhasil diperbarui.': 'Profile updated successfully.', 'Bahasa diubah ke Indonesia.': 'Language changed to Indonesian.', 'Language changed to English.': 'Language changed to English.'
    };
    const placeholders = {
        'Cari nomor registrasi atau layanan': 'Search registration number or service',
        'Contoh: Konsultasi AJB / Penandatanganan': 'Example: AJB consultation / Signing',
        'Tulis kebutuhan atau keterangan tambahan': 'Write needs or additional information'
    };
    const monthMap = {'Januari':'January','Februari':'February','Maret':'March','April':'April','Mei':'May','Juni':'June','Juli':'July','Agustus':'August','September':'September','Oktober':'October','November':'November','Desember':'December','Senin':'Monday','Selasa':'Tuesday','Rabu':'Wednesday','Kamis':'Thursday','Jumat':'Friday','Sabtu':'Saturday','Minggu':'Sunday'};
    const reverse = Object.fromEntries(Object.entries(translations).map(([id, en]) => [en, id]));
    const reverseMonths = Object.fromEntries(Object.entries(monthMap).map(([id, en]) => [en, id]));
    const sourceTexts = new WeakMap();
    function convertText(source, language) {
        const map = language === 'en' ? {...translations, ...monthMap} : {...reverse, ...reverseMonths};
        const entries = Object.entries(map).sort((a,b)=>b[0].length-a[0].length);
        if (!entries.length) return source;
        const escapeRegExp = text => text.replace(/[-\/\^$*+?.()|[\]{}]/g, '\\$&');
        const pattern = new RegExp(entries.map(([from]) => escapeRegExp(from)).join('|'), 'g');
        return source.replace(pattern, match => map[match] || match);
    }
    function translateText(root, language) {
        const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
        const nodes = [];
        while (walker.nextNode()) nodes.push(walker.currentNode);
        nodes.forEach(node => {
            const value = node.nodeValue;
            if (!value.trim()) return;
            if (!sourceTexts.has(node)) sourceTexts.set(node, convertText(value, 'id'));
            node.nodeValue = language === 'en' ? convertText(sourceTexts.get(node), 'en') : sourceTexts.get(node);
        });
    }
    function translatePlaceholders(language) {
        const map = language === 'en' ? placeholders : Object.fromEntries(Object.entries(placeholders).map(([id, en]) => [en, id]));
        document.querySelectorAll('[placeholder], [aria-label]').forEach(input => {
            ['placeholder','aria-label'].forEach(attr => {
                const value = input.getAttribute(attr);
                if (value && map[value]) input.setAttribute(attr, map[value]);
            });
        });
    }
    window.translateDashboardText = function (text, language = document.documentElement.lang || 'id') { return convertText(text, language); };
    window.applyDashboardLanguage = function (language) {
        document.documentElement.lang = language;
        document.documentElement.dataset.userLang = language;
        document.title = language === 'en' ? 'Client Dashboard - Notary/PPAT Office' : 'Dashboard Klien - Kantor Notaris/PPAT';
        translateText(document.body, language);
        translatePlaceholders(language);
        document.querySelectorAll('[data-dashboard-lang]').forEach(button => button.classList.toggle('active', button.dataset.dashboardLang === language));
        localStorage.setItem('userDashboardLanguage', language);
        localStorage.setItem('publicLanguage', language);
        localStorage.setItem('publicLang', language);
    };
    document.addEventListener('DOMContentLoaded', () => {
        applyDashboardLanguage(localStorage.getItem('publicLanguage') || localStorage.getItem('publicLang') || localStorage.getItem('userDashboardLanguage') || 'id');
        document.addEventListener('click', event => {
            const button = event.target.closest('[data-dashboard-lang]');
            if (button) applyDashboardLanguage(button.dataset.dashboardLang);
        });
    });
})();
