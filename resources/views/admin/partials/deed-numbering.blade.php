<section class="admin-section" data-admin-section="deed-numbering">
    <div class="page-head">
        <div>
            <p>PENOMORAN AKTA</p>
            <h1>Register Nomor Akta & Buku Bulanan</h1>
            <small>Form berubah sesuai kategori: laporan bulanan Notaris, Waarmerking, Legalisasi, Protes Wasel/Cheque, dan Akta PPAT.</small>
        </div>
    </div>

    <div class="admin-stats deed-stats">
        <article><i><img src="{{ asset('images/dashboard-icons/applications.svg') }}" alt=""></i><small>Total Bulan Ini</small><strong>{{ $deedStats['monthTotal'] }}</strong><span>Semua register</span></article>
        <article><i class="blue"><img src="{{ asset('images/dashboard-icons/document-alert.svg') }}" alt=""></i><small>Notaris</small><strong>{{ $deedStats['notary'] }}</strong><span>Laporan bulanan</span></article>
        <article><i class="amber"><img src="{{ asset('images/dashboard-icons/process.svg') }}" alt=""></i><small>Wasel/Cheque</small><strong>{{ $deedStats['protest'] }}</strong><span>Register protes</span></article>
        <article><i class="green"><img src="{{ asset('images/dashboard-icons/done.svg') }}" alt=""></i><small>Legalisasi/Waarmerking</small><strong>{{ $deedStats['legalization'] + $deedStats['waarmerking'] }}</strong><span>Buku register</span></article>
        <article><i class="red"><img src="{{ asset('images/dashboard-icons/late.svg') }}" alt=""></i><small>PPAT</small><strong>{{ $deedStats['ppat'] }}</strong><span>Buku daftar akta</span></article>
    </div>

    <div class="deed-numbering-grid">
        <section class="panel deed-form-panel">
            <header>
                <div>
                    <h2>Input Penomoran Baru</h2>
                    <span>Isi sesuai jenis register. Nama pihak dan nomor tetap dicek agar tidak ganda.</span>
                </div>
            </header>

            @if($errors->any())
                <div class="deed-warning danger"><strong>Peringatan</strong><p>{{ $errors->first() }}</p></div>
            @endif

            <form method="POST" action="{{ route('admin.deed-numberings.store') }}" id="deedNumberingForm" class="deed-numbering-form">
                @csrf
                <div class="deed-category-note" data-deed-help>Gunakan kategori untuk menampilkan kebutuhan kolom yang sesuai.</div>
                <div class="profile-form-grid deed-dynamic-grid">
                    <label>Kategori Register
                        <select name="category" data-deed-category required>
                            <option value="notary" @selected(old('category') === 'notary')>Laporan Bulanan Notaris</option>
                            <option value="waarmerking" @selected(old('category') === 'waarmerking')>Waarmerking</option>
                            <option value="legalization" @selected(old('category') === 'legalization')>Legalisasi</option>
                            <option value="protest" @selected(old('category') === 'protest')>Protes Wasel/Cheque</option>
                            <option value="ppat" @selected(old('category') === 'ppat')>Akta PPAT</option>
                        </select>
                    </label>

                    <label data-field="serial_number">Nomor Urut<input type="number" min="1" name="serial_number" value="{{ old('serial_number') }}" placeholder="Contoh: 1"></label>
                    <label data-field="deed_number">Nomor Akta/Register<input name="deed_number" data-deed-number value="{{ old('deed_number') }}" placeholder="Contoh: 12/VI/2026" required></label>
                    <label data-field="monthly_number">Nomor Bulanan<input name="monthly_number" value="{{ old('monthly_number') }}" placeholder="Contoh: 06/2026"></label>
                    <label data-field="deed_date">Tanggal Akta / Tanggal Register<input type="date" name="deed_date" value="{{ old('deed_date', now()->toDateString()) }}" required></label>
                    <label data-field="time_start">Jam Mulai<input type="time" name="time_start" value="{{ old('time_start') }}"></label>
                    <label data-field="time_end">Jam Selesai<input type="time" name="time_end" value="{{ old('time_end') }}"></label>
                    <label data-field="deed_title">Jenis/Sifat Surat atau Akta<input name="deed_title" value="{{ old('deed_title') }}" placeholder="Contoh: Jual Beli / Kuasa / Perjanjian" required></label>
                    <label class="full" data-field="party_primary">Nama Penghadap / Pihak yang Menandatangani / Diwakili / Kuasa<textarea name="party_primary" data-party-primary placeholder="Contoh:&#10;1. Markus Sukmawati (selaku Direktur PT Antana)&#10;2. Mita Sukmawati&#10;3. Antana Wijaha" required>{{ old('party_primary') }}</textarea></label>
                    <label data-field="letter_date">Tanggal Surat<input type="date" name="letter_date" value="{{ old('letter_date') }}"></label>
                    <label data-field="registered_date">Tanggal Didaftarkan<input type="date" name="registered_date" value="{{ old('registered_date') }}"></label>
                    <label data-field="instrument_date">Tanggal Wasel/Cheque<input type="date" name="instrument_date" value="{{ old('instrument_date') }}"></label>
                    <label data-field="due_date">Tanggal Jatuh Tempo Wasel/Cheque<input type="date" name="due_date" value="{{ old('due_date') }}"></label>
                    <label data-field="extra.protested_to">Yang Ditagih<input name="extra[protested_to]" value="{{ old('extra.protested_to') }}" placeholder="Nama pihak yang ditagih"></label>
                    <label data-field="extra.protester">Yang Menagih<input name="extra[protester]" value="{{ old('extra.protester') }}" placeholder="Nama pihak yang menagih"></label>
                    <label data-field="extra.ppat_deed_type">Jenis Perbuatan Hukum PPAT
                        <select name="extra[ppat_deed_type]">
                            <option value="">Pilih jenis</option>
                            <option>Jual Beli</option>
                            <option>Tukar Menukar</option>
                            <option>Hibah</option>
                            <option>Pemasukan ke Dalam Perusahaan / Inbreng</option>
                            <option>Pembagian Hak Bersama / APHB</option>
                            <option>Pemberian Hak Tanggungan / APHT</option>
                            <option>Surat Kuasa Membebankan Hak Tanggungan / SKMHT</option>
                        </select>
                    </label>
                    <label data-field="extra.land_right">Jenis & Nomor Hak<input name="extra[land_right]" value="{{ old('extra.land_right') }}" placeholder="Contoh: SHM No. 1234"></label>
                    <label data-field="extra.land_location">Letak Tanah/Bangunan<input name="extra[land_location]" value="{{ old('extra.land_location') }}" placeholder="Desa/Kelurahan, Kecamatan"></label>
                    <label data-field="extra.land_area">Luas Tanah/Bangunan<input name="extra[land_area]" value="{{ old('extra.land_area') }}" placeholder="Contoh: 250 m² / 120 m²"></label>
                    <label data-field="extra.tax_number">NOP/SPPT PBB<input name="extra[tax_number]" value="{{ old('extra.tax_number') }}" placeholder="Nomor objek pajak jika ada"></label>
                    <label data-field="extra.transaction_value">Nilai Transaksi<input name="extra[transaction_value]" value="{{ old('extra.transaction_value') }}" placeholder="Contoh: Rp 750.000.000"></label>
                    <label data-field="extra.tax_status">Status Pajak<input name="extra[tax_status]" value="{{ old('extra.tax_status') }}" placeholder="BPHTB/PPH tervalidasi atau catatan pajak"></label>
                    <label class="full" data-field="note">Catatan<textarea name="note" placeholder="Keterangan tambahan, nomor berkas, objek akta, atau catatan register">{{ old('note') }}</textarea></label>
                </div>
                <div class="deed-warning" data-deed-duplicate-warning hidden></div>
                <footer><button type="button" data-check-deed-duplicate>Cek Kesamaan</button><button class="primary" type="submit">Simpan Penomoran</button></footer>
            </form>
        </section>

        <section class="panel deed-history-panel">
            <header>
                <div>
                    <h2>Riwayat Bulanan Akta</h2>
                    <span>Menampilkan register bulan {{ now()->translatedFormat('F Y') }}.</span>
                </div>
                <input type="search" data-deed-search placeholder="Cari nama pihak / nomor akta">
            </header>
            <div class="deed-history-list" data-deed-history-list>
                @forelse($deedNumberings as $deed)
                    @php
                        $historyTitle = trim(($deed->serial_number ? $deed->serial_number . '. ' : '') . $deed->deed_number . ' · ' . $deed->deed_title);
                        $historyParties = trim(str_replace(["\r\n", "\r", "\n"], ' · ', $deed->party_primary . ($deed->party_secondary ? "\n" . $deed->party_secondary : '')));
                        $historyTime = $deed->time_start ? ' · ' . substr($deed->time_start, 0, 5) . ($deed->time_end ? '-' . substr($deed->time_end, 0, 5) : '') : '';
                        $historyMeta = $formatDate($deed->deed_date) . $historyTime . ' · ' . ($deed->staff_name ?? 'Staf');
                    @endphp
                    <article data-deed-history-item data-search="{{ strtolower($deed->party_primary.' '.$deed->party_secondary.' '.$deed->deed_number.' '.$deed->deed_title) }}">
                        <span>{{ strtoupper($deed->category) }}</span>
                        <div>
                            <strong>{{ $historyTitle }}</strong>
                            <p>{{ $historyParties }}</p>
                            <small>{{ $historyMeta }}</small>
                            @if($deed->note)
                                <em>{{ $deed->note }}</em>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="empty-state wide"><strong>Belum ada riwayat penomoran bulan ini.</strong><p>Data akan muncul setelah staf menyimpan penomoran akta.</p></div>
                @endforelse
            </div>
        </section>
    </div>
</section>
