<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['notary', 'Pendirian PT', 'Pembuatan akta pendirian PT dan kebutuhan legalitas awal perusahaan.'],
            ['notary', 'Perubahan PT', 'Perubahan anggaran dasar, direksi, komisaris, pemegang saham, atau modal PT.'],
            ['notary', 'Pendirian CV', 'Pembuatan akta pendirian CV untuk usaha perorangan/kelompok.'],
            ['notary', 'Perubahan CV', 'Perubahan data sekutu, alamat, kegiatan usaha, atau ketentuan CV.'],
            ['notary', 'Pendirian Yayasan', 'Pembuatan akta pendirian yayasan dan dokumen pendukungnya.'],
            ['notary', 'Perubahan Yayasan', 'Perubahan pengurus, pembina, pengawas, alamat, atau anggaran dasar yayasan.'],
            ['notary', 'Pendirian Perkumpulan', 'Akta pendirian perkumpulan atau organisasi berbadan hukum.'],
            ['notary', 'Perubahan Perkumpulan', 'Perubahan data organisasi, pengurus, atau anggaran dasar perkumpulan.'],
            ['notary', 'Akta Perjanjian Kerja Sama', 'Pembuatan perjanjian kerja sama usaha, proyek, atau kemitraan.'],
            ['notary', 'Akta Perjanjian Utang Piutang', 'Perjanjian pinjam-meminjam uang dengan penguatan akta notaris.'],
            ['notary', 'Akta Pengakuan Hutang', 'Akta pengakuan kewajiban pembayaran atau pelunasan hutang.'],
            ['notary', 'Akta Kuasa', 'Pembuatan kuasa umum atau kuasa khusus sesuai kebutuhan hukum.'],
            ['notary', 'Kuasa Menjual', 'Pembuatan kuasa untuk menjual tanah, bangunan, saham, atau aset lain.'],
            ['notary', 'Akta Pernyataan', 'Pembuatan pernyataan sepihak untuk kebutuhan administrasi/hukum.'],
            ['notary', 'Akta Persetujuan', 'Akta persetujuan pihak tertentu atas tindakan hukum.'],
            ['notary', 'Akta Wasiat', 'Pembuatan akta wasiat dan pengaturan kehendak pewaris.'],
            ['notary', 'Akta Keterangan Hak Waris', 'Dokumen keterangan ahli waris sesuai kebutuhan administrasi.'],
            ['notary', 'Perjanjian Sewa Menyewa', 'Pembuatan perjanjian sewa rumah, ruko, tanah, kantor, atau aset lain.'],
            ['notary', 'Pemindahan Hak Sewa', 'Pengalihan hak sewa dari penyewa lama kepada pihak baru.'],
            ['notary', 'Perpanjangan Sewa', 'Perpanjangan jangka waktu sewa dengan addendum atau akta baru.'],
            ['notary', 'Addendum Perjanjian', 'Perubahan atau penambahan klausul perjanjian yang sudah ada.'],
            ['notary', 'Perjanjian Jual Beli / PPJB', 'Pengikatan jual beli sebelum pelaksanaan akta final.'],
            ['notary', 'Perjanjian Kawin', 'Pembuatan perjanjian perkawinan atau pisah harta.'],
            ['notary', 'Fidusia', 'Pembuatan akta jaminan fidusia untuk pembiayaan atau kredit.'],
            ['notary', 'RUPS / Keputusan Pemegang Saham', 'Akta rapat umum pemegang saham atau keputusan sirkuler.'],
            ['notary', 'Legalisasi Tanda Tangan', 'Pengesahan tanda tangan di hadapan notaris.'],
            ['notary', 'Waarmerking', 'Pendaftaran dokumen bawah tangan pada buku notaris.'],
            ['notary', 'Legalisir Salinan', 'Pengesahan salinan dokumen sesuai asli.'],
            ['notary', 'Pengesahan AHU', 'Pengurusan pengesahan badan hukum melalui sistem AHU.'],
            ['notary', 'Pengurusan NIB / OSS', 'Pendampingan dokumen legalitas usaha melalui OSS.'],

            ['ppat', 'Akta Jual Beli / AJB', 'Akta peralihan hak tanah dan bangunan karena jual beli.'],
            ['ppat', 'Akta Hibah', 'Akta peralihan hak tanah/bangunan karena hibah.'],
            ['ppat', 'Akta Tukar Menukar', 'Akta peralihan hak karena pertukaran objek tanah/bangunan.'],
            ['ppat', 'Akta Pembagian Hak Bersama / APHB', 'Pembagian hak bersama, waris, atau kepemilikan bersama.'],
            ['ppat', 'Akta Pemasukan ke Dalam Perusahaan / Inbreng', 'Pemasukan tanah/bangunan sebagai penyertaan ke perusahaan.'],
            ['ppat', 'Akta Pemberian Hak Tanggungan / APHT', 'Pembuatan hak tanggungan atas jaminan kredit.'],
            ['ppat', 'SKMHT', 'Surat kuasa membebankan hak tanggungan.'],
            ['ppat', 'Balik Nama Sertifikat', 'Proses perubahan nama pemegang hak pada sertifikat.'],
            ['ppat', 'Roya', 'Penghapusan hak tanggungan setelah kredit lunas.'],
            ['ppat', 'Pengecekan Sertifikat', 'Pengecekan validitas sertifikat tanah ke kantor pertanahan.'],
            ['ppat', 'Validasi BPHTB', 'Validasi pajak BPHTB untuk peralihan hak.'],
            ['ppat', 'Validasi PPh', 'Validasi pajak penghasilan atas transaksi tanah/bangunan.'],
            ['ppat', 'Pemecahan Sertifikat', 'Pemecahan satu sertifikat menjadi beberapa bidang.'],
            ['ppat', 'Penggabungan Sertifikat', 'Penggabungan beberapa sertifikat menjadi satu bidang.'],
            ['ppat', 'Pemisahan Sertifikat', 'Pemisahan sebagian bidang tanah dari sertifikat induk.'],
            ['ppat', 'Peralihan Hak Karena Waris', 'Proses peralihan hak tanah/bangunan karena pewarisan.'],
            ['ppat', 'Peningkatan Hak', 'Peningkatan status hak tanah, misalnya HGB menjadi SHM.'],
            ['ppat', 'Penurunan Hak', 'Penyesuaian atau perubahan status hak sesuai kebutuhan.'],
            ['ppat', 'Perpanjangan Hak', 'Perpanjangan masa berlaku HGB, Hak Pakai, atau hak lainnya.'],
            ['ppat', 'Pendaftaran Hak Tanggungan', 'Pendaftaran hak tanggungan elektronik ke BPN.'],
            ['ppat', 'Penghapusan Hak Tanggungan', 'Penghapusan catatan hak tanggungan pada sertifikat.'],
            ['ppat', 'Pengukuran Tanah', 'Pendampingan proses ukur tanah untuk kebutuhan sertifikat/peralihan.'],
            ['ppat', 'Konversi Hak Tanah', 'Penyesuaian hak lama menjadi hak yang berlaku saat ini.'],
            ['ppat', 'Sertifikat Pengganti', 'Pengurusan sertifikat rusak, hilang, atau penggantian blanko.'],
            ['ppat', 'Cek Zona / Tata Ruang', 'Pengecekan informasi zonasi atau tata ruang bidang tanah.'],
        ];

        foreach ($services as [$category, $name, $description]) {
            DB::table('office_services')->updateOrInsert(
                ['name' => $name, 'category' => $category],
                [
                    'description' => $description,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
