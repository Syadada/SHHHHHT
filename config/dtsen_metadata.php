<?php

return [
    'keluarga' => [
        [
            'no' => 1,
            'nama_layanan' => 'Kode Provinsi',
            'nama_variabel' => 'kode_provinsi',
            'definisi' => 'Kode provinsi pada DTSEN',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '2 digit kode provinsi DTSEN',
        ],
        [
            'no' => 2,
            'nama_layanan' => 'Provinsi',
            'nama_variabel' => 'provinsi',
            'definisi' => 'Nama provinsi',
            'tipe_data' => 'varchar(100)',
            'keterangan' => '',
        ],
        [
            'no' => 3,
            'nama_layanan' => 'Kode Kabupaten/Kota',
            'nama_variabel' => 'kode_kabupaten_kota',
            'definisi' => 'Kode kabupaten/kota pada DTSEN',
            'tipe_data' => 'varchar(4)',
            'keterangan' => '4 digit kode kabupaten/kota DTSEN',
        ],
        [
            'no' => 4,
            'nama_layanan' => 'Kabupaten/Kota',
            'nama_variabel' => 'kabupaten_kota',
            'definisi' => 'Nama kabupaten/kota',
            'tipe_data' => 'varchar(100)',
            'keterangan' => '',
        ],
        [
            'no' => 5,
            'nama_layanan' => 'Kode Kecamatan',
            'nama_variabel' => 'kode_kecamatan',
            'definisi' => 'Kode kecamatan pada DTSEN',
            'tipe_data' => 'varchar(7)',
            'keterangan' => '7 digit kode kecamatan DTSEN',
        ],
        [
            'no' => 6,
            'nama_layanan' => 'Kecamatan',
            'nama_variabel' => 'kecamatan',
            'definisi' => 'Nama kecamatan',
            'tipe_data' => 'varchar(100)',
            'keterangan' => '',
        ],
        [
            'no' => 7,
            'nama_layanan' => 'Kode Kelurahan/Desa',
            'nama_variabel' => 'kode_kelurahan_desa',
            'definisi' => 'Kode kelurahan/desa pada DTSEN',
            'tipe_data' => 'varchar(10)',
            'keterangan' => '10 digit kode kelurahan/desa DTSEN',
        ],
        [
            'no' => 8,
            'nama_layanan' => 'Kelurahan/Desa',
            'nama_variabel' => 'kelurahan_desa',
            'definisi' => 'Nama kelurahan/desa',
            'tipe_data' => 'varchar(100)',
            'keterangan' => '',
        ],
        [
            'no' => 9,
            'nama_layanan' => 'Alamat',
            'nama_variabel' => 'alamat',
            'definisi' => 'Alamat domisili',
            'tipe_data' => 'varchar(500)',
            'keterangan' => 'alamat lengkap domisili keluarga',
        ],
        [
            'no' => 10,
            'nama_layanan' => 'Nomor Kartu Keluarga',
            'nama_variabel' => 'nomor_kartu_keluarga',
            'definisi' => 'Nomor kartu keluarga yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(16)',
            'keterangan' => '16 digit nomor kartu keluarga yang tercatat di Dukcapil',
        ],
        [
            'no' => 11,
            'nama_layanan' => 'Jumlah Anggota Keluarga',
            'nama_variabel' => 'Jumlah_anggota_keluarga',
            'definisi' => 'Jumlah anggota keluarga atau jumlah individu dalam keluarga',
            'tipe_data' => 'int(4)',
            'keterangan' => '',
        ],
        [
            'no' => 12,
            'nama_layanan' => 'Nama Anggota Keluarga',
            'nama_variabel' => 'nama_anggota_keluarga',
            'definisi' => 'Nama anggota keluarga yang berstatus sebagai kepala keluarga',
            'tipe_data' => 'varchar(500)',
            'keterangan' => '',
        ],
        [
            'no' => 13,
            'nama_layanan' => 'Desil Nasional',
            'nama_variabel' => 'desil_nasional',
            'definisi' => 'Desil kesejahteraan keluarga level nasional',
            'tipe_data' => 'int(2)',
            'keterangan' => '1 s.d. 10',
        ],
        [
            'no' => 14,
            'nama_layanan' => 'Desil Provinsi',
            'nama_variabel' => 'desil_provinsi',
            'definisi' => 'Desil kesejahteraan keluarga level provinsi',
            'tipe_data' => 'int(2)',
            'keterangan' => '1 s.d. 10',
        ],
        [
            'no' => 15,
            'nama_layanan' => 'Desil Kabupaten/Kota',
            'nama_variabel' => 'desil_kabupaten_kota',
            'definisi' => 'Desil kesejahteraan keluarga level kabupaten/kota',
            'tipe_data' => 'int(2)',
            'keterangan' => '1 s.d. 10',
        ],
        [
            'no' => 16,
            'nama_layanan' => 'PBI Nasional',
            'nama_variabel' => 'pbi_nas',
            'definisi' => 'Status penerima bantuan iuran nasional',
            'tipe_data' => '',
            'keterangan' => '1. Penerima
0. Tidak',
        ],
        [
            'no' => 17,
            'nama_layanan' => 'PBI Pemda',
            'nama_variabel' => 'pbi_pemda',
            'definisi' => 'Status penerima bantuan iuran pemda',
            'tipe_data' => '',
            'keterangan' => '1. Penerima
0. Tidak',
        ],
        [
            'no' => 18,
            'nama_layanan' => 'ID Pelanggan PLN',
            'nama_variabel' => 'id_pelanggan_pln',
            'definisi' => 'Nomor ID pelanggan PLN dari rumah yang dihuni',
            'tipe_data' => 'varchar',
            'keterangan' => '',
        ],
        [
            'no' => 19,
            'nama_layanan' => 'Status Kepemilikan Rumah',
            'nama_variabel' => 'status_kepemilikan_rumah',
            'definisi' => 'Status kepemilikan rumah/bangunan tempat tinggal yang dihuni',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Milik sendiri
2. Kontrak/sewa
3. Bebas sewa
4. Dinas
5. Lainnya',
        ],
        [
            'no' => 20,
            'nama_layanan' => 'Jenis Lantai Terluas',
            'nama_variabel' => 'jenis_lantai_terluas',
            'definisi' => 'Jenis lantai terluas dari rumah/bangunan tempat tinggal yang dihuni',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '01.Marmer/granit
02.Keramik
03.Parket/vinil/karpet
04.Ubin/tegel/teraso
05.Kayu/papan
06.Semen/bata merah
07.Bambu
08.Tanah
09.Lainnya
10.Keramik/granit/marmer/ubin/tegel/teras',
        ],
        [
            'no' => 21,
            'nama_layanan' => 'Luas Lantai',
            'nama_variabel' => 'luas_lantai',
            'definisi' => 'Luas lantai rumah/bangunan tempat tinggal (m2)',
            'tipe_data' => 'int(3)',
            'keterangan' => '0 s.d. 999',
        ],
        [
            'no' => 22,
            'nama_layanan' => 'Jenis Dinding Terluas',
            'nama_variabel' => 'jenis_dinding_terluas',
            'definisi' => 'Jenis dinding terluas dari rumah/bangunan tempat tinggal yang dihuni',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Tembok
2. Plesteran anyamanbambu/kawat
3. Kayu/papan/gypsum/GRC/calciboard
4. Anyaman bambu
5. Batang kayu
6. Bambu
7. Lainnya',
        ],
        [
            'no' => 23,
            'nama_layanan' => 'Jenis Atap Terluas',
            'nama_variabel' => 'jenis_atap_terluas',
            'definisi' => 'Jenis atap terluas dari rumah/bangunan tempat tinggal yang dihuni',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Beton
2. Genteng
3. Seng
4. Asbes
5. Bambu
6. Kayu/sirap
7. Jerami/ijuk/daun-daunan/rumbia
8. Lainnya
9. Asbes/seng',
        ],
        [
            'no' => 24,
            'nama_layanan' => 'Sumber Air Minum Utama',
            'nama_variabel' => 'sumber_air_minum_utama',
            'definisi' => 'Sumber air minum utama dari rumah/bangunan tempat tinggal yang dihuni',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '01. Air kemasan bermerk
02. Air isi ulang
03. Leding
04. Sumur bor/pompa
05. Sumur terlindung
06. Sumur tak terlindung
07. Mata air terlindung
08. Mata air tak terlindung
09. Air permukaan (sungai/danau/waduk/kolam/irigasi)
10. Air hujan
11. Lainnya
12. Air kemasan/isi ulang',
        ],
        [
            'no' => 25,
            'nama_layanan' => 'Sumber Penerangan Utama',
            'nama_variabel' => 'sumber_penerangan_utama',
            'definisi' => 'Sumber penerangan utama dari rumah/bangunan tempat tinggal yang dihuni',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Listrik PLN dengan meteran
2. Listrik PLN tanpa meteran
3. Listrik non-PLN
4. Bukan listrik',
        ],
        [
            'no' => 26,
            'nama_layanan' => 'Daya Terpasang',
            'nama_variabel' => 'daya_terpasang',
            'definisi' => 'Daya terpasang dari rumah/bangunan tempat tinggal yang dihuni',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. 450 watt
2. 900 watt
3. 1.300 watt
4. 2.200 watt
5. > 2.200 watt
6. <= 900 watt
7. > 900 watt',
        ],
        [
            'no' => 27,
            'nama_layanan' => 'Bahan Bakar Utama Memasak',
            'nama_variabel' => 'bahan_bakar_utama_memasak',
            'definisi' => 'Bahan bakar utama memasak dari rumah yang dihuni',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '00. Tidak memasak di rumah
01. Listrik
02. Gas elpiji5,5kg/blue gaz
03. Gas elpiji 12 kg
04. Gas elpiji 3 kg
05. Gas kota/meteran PGN
06. Biogas
07. Minyak tanah
08. Briket
09. Arang
10. Kayu bakar
11. Lainnya
12. Listrik/gas',
        ],
        [
            'no' => 28,
            'nama_layanan' => 'Fasilitas BAB',
            'nama_variabel' => 'fasilitas_bab',
            'definisi' => 'Fasilitas bab dari rumah yang dihuni',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Ada, digunakan hanya Anggota Keluarga sendiri
2. Ada, digunakan bersama Anggota Keluarga dari Keluarga tertentu
3. Ada, di MCK komunal
4. Ada, di MCK umum/siapapun menggunakan
5. Ada, Anggota Keluarga tidak menggunakan
6. Tidak ada fasilitas',
        ],
        [
            'no' => 29,
            'nama_layanan' => 'Jenis Kloset',
            'nama_variabel' => 'jenis_kloset',
            'definisi' => 'Jenis kloset yang digunakan dari rumah yang dihuni',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Leher angsa
2. Plengsengan dengan tutup
3. Plengsengan tanpa tutup
4. Cemplung/cubluk',
        ],
        [
            'no' => 30,
            'nama_layanan' => 'Pembuangan Akhir Tinja',
            'nama_variabel' => 'pembuangan_akhir_tinja',
            'definisi' => 'Jenis pembuangan akhir tinja yang digunakan dari rumah yang dihuni',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Tangki septik
2. IPAL
3. Kolam/sawah/sungai/danau/laut
4. Lubang tanah
5. Pantai/tanah lapang/kebun
6. Lainnya
7. Tanpa tangki septik',
        ],
        [
            'no' => 31,
            'nama_layanan' => 'Kepemilikan Aset',
            'nama_variabel' => 'kepemilikan_aset',
            'definisi' => 'Status kepemilkan aset',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya memiliki aset
2. Tidak memiliki aset',
        ],
        [
            'no' => 32,
            'nama_layanan' => 'Aset Bergerak Tabung Gas',
            'nama_variabel' => 'aset_bergerak_tabung_gas',
            'definisi' => 'Kepemilikan aset bergerak tabung gas minimal 5,5kg',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 33,
            'nama_layanan' => 'Aset Bergerak Lemari Es',
            'nama_variabel' => 'aset_bergerak_lemari_es',
            'definisi' => 'Kepemilikan aset bergerak lemari es atau kulkas',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 34,
            'nama_layanan' => 'Aset Bergerak AC',
            'nama_variabel' => 'aset_bergerak_ac',
            'definisi' => 'Kepemilikan aset bergerak AC (Air Conditioner)',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 35,
            'nama_layanan' => 'Aset Bergerak Pemanas Air',
            'nama_variabel' => 'aset_bergerak_pemanas_air',
            'definisi' => 'Kepemilikan aset bergerak pemanas air (Water Heater) untuk mandi',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 36,
            'nama_layanan' => 'Aset Bergerak Telepon Rumah',
            'nama_variabel' => 'aset_bergerak_telepon_rumah',
            'definisi' => 'Kepemilikan aset bergerak telepon rumah atau PSTN',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 37,
            'nama_layanan' => 'Aset Bergerak TV Datar',
            'nama_variabel' => 'aset_bergerak_tv_datar',
            'definisi' => 'Kepemilikan aset bergerak televisi datar',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 38,
            'nama_layanan' => 'Aset Bergerak Emas Perhiasan',
            'nama_variabel' => 'aset_bergerak_emas_perhiasan',
            'definisi' => 'Kepemilikan aset bergerak perhiasan emas',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 39,
            'nama_layanan' => 'Aset Bergerak Komputer Laptop Tablet',
            'nama_variabel' => 'aset_bergerak_komputer_laptop_tablet',
            'definisi' => 'Kepemilikan aset bergerak komputer/PC atau laptop atau tablet',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 40,
            'nama_layanan' => 'Aset Bergerak Sepeda Motor',
            'nama_variabel' => 'aset_bergerak_sepeda_motor',
            'definisi' => 'Kepemilikan aset bergerak sepeda motor',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 41,
            'nama_layanan' => 'Aset Bergerak Sepeda',
            'nama_variabel' => 'aset_bergerak_sepeda',
            'definisi' => 'Kepemilikan aset bergerak sepeda',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 42,
            'nama_layanan' => 'Aset Bergerak Mobil',
            'nama_variabel' => 'aset_bergerak_mobil',
            'definisi' => 'Kepemilikan aset bergerak mobil',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 43,
            'nama_layanan' => 'Aset Bergerak Perahu',
            'nama_variabel' => 'aset_bergerak_perahu',
            'definisi' => 'Kepemilikan aset bergerak perahu',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 44,
            'nama_layanan' => 'Aset Bergerak Kapal Perahu Motor',
            'nama_variabel' => 'aset_bergerak_kapal_perahu_motor',
            'definisi' => 'Kepemilikan aset bergerak kapal atau perahu motor',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 45,
            'nama_layanan' => 'Aset Bergerak Smartphone',
            'nama_variabel' => 'aset_bergerak_smartphone',
            'definisi' => 'Kepemilikan aset bergerak smartphone',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 46,
            'nama_layanan' => 'Aset Tidak Bergerak Lahan Lainnya',
            'nama_variabel' => 'aset_tidak_bergerak_lahan_lainnya',
            'definisi' => 'Kepemilikan aset tidak bergerak lahan selain yang dihuni',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 47,
            'nama_layanan' => 'Aset Tidak Bergerak Rumah Lainnya',
            'nama_variabel' => 'aset_tidak_bergerak_rumah_lainnya',
            'definisi' => 'Kepemilikan aset tidak bergerak rumah selain yang dihuni',
            'tipe_data' => 'int(1)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 48,
            'nama_layanan' => 'Jumlah Ternak Sapi',
            'nama_variabel' => 'jumlah_ternak_sapi',
            'definisi' => 'Jumlah kepemilikan ternak sapi',
            'tipe_data' => 'int(3)',
            'keterangan' => '0 s.d. 999',
        ],
        [
            'no' => 49,
            'nama_layanan' => 'Jumlah Ternak Kerbau',
            'nama_variabel' => 'jumlah_ternak_kerbau',
            'definisi' => 'Jumlah kepemilikan ternak kerbau',
            'tipe_data' => 'int(3)',
            'keterangan' => '0 s.d. 999',
        ],
        [
            'no' => 50,
            'nama_layanan' => 'Jumlah Ternak Kuda',
            'nama_variabel' => 'jumlah_ternak_kuda',
            'definisi' => 'Jumlah kepemilikan ternak kuda',
            'tipe_data' => 'int(3)',
            'keterangan' => '0 s.d. 999',
        ],
        [
            'no' => 51,
            'nama_layanan' => 'Jumlah Ternak Babi',
            'nama_variabel' => 'jumlah_ternak_babi',
            'definisi' => 'Jumlah kepemilikan ternak babi',
            'tipe_data' => 'int(3)',
            'keterangan' => '0 s.d. 999',
        ],
        [
            'no' => 52,
            'nama_layanan' => 'Jumlah Ternak Kambing Domba',
            'nama_variabel' => 'jumlah_ternak_kambing_domba',
            'definisi' => 'Jumlah kepemilikan ternak kambing atau domba',
            'tipe_data' => 'int(3)',
            'keterangan' => '0 s.d. 999',
        ],
    ],
    'anggota_keluarga' => [
        [
            'no' => 1,
            'nama_layanan' => 'Nomor Induk Kependudukan',
            'nama_variabel' => 'nomor_induk_kependudukan',
            'definisi' => 'Nomor induk kependudukan yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(16)',
            'keterangan' => '16 digit nomor induk kependudukan yang tercatat di Dukcapil',
        ],
        [
            'no' => 2,
            'nama_layanan' => 'Nomor Kartu Keluarga',
            'nama_variabel' => 'nomor_kartu_keluarga',
            'definisi' => 'Nomor kartu keluarga yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(16)',
            'keterangan' => '16 digit nomor kartu keluarga yang tercatat di Dukcapil',
        ],
        [
            'no' => 3,
            'nama_layanan' => 'Nama',
            'nama_variabel' => 'nama',
            'definisi' => 'Nama lengkap yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(500)',
            'keterangan' => '',
        ],
        [
            'no' => 4,
            'nama_layanan' => 'Tanggal Lahir',
            'nama_variabel' => 'tanggal_lahir',
            'definisi' => 'Tanggal lahir yang tercatat di Dukcapil',
            'tipe_data' => 'datetime(yyyy-mm-dd)',
            'keterangan' => '',
        ],
        [
            'no' => 5,
            'nama_layanan' => 'Jenis Kelamin',
            'nama_variabel' => 'jenis_kelamin',
            'definisi' => 'Jenis kelamin individu',
            'tipe_data' => 'varchar(1)',
            'keterangan' => '1. Laki-laki
2. Perempuan',
        ],
        [
            'no' => 6,
            'nama_layanan' => 'Status Hubungan Keluarga',
            'nama_variabel' => 'status_hubungan_keluarga',
            'definisi' => 'Status hubungan anggota keluarga terhadap kepala keluarga',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Kepala keluarga
2. Suami
3. Istri
4. Anak
5. Menantu
6. Cucu
7. Orang tua
8. Mertua
9. Famili lain
10. Pembantu
11. Lainnya',
        ],
        [
            'no' => 7,
            'nama_layanan' => 'PBI Nasional',
            'nama_variabel' => 'pbi_nas',
            'definisi' => 'Status penerima bantuan iuran nasional',
            'tipe_data' => '',
            'keterangan' => '1. Penerima
0. Tidak',
        ],
        [
            'no' => 8,
            'nama_layanan' => 'PBI Pemda',
            'nama_variabel' => 'pbi_pemda',
            'definisi' => 'Status penerima bantuan iuran pemda',
            'tipe_data' => '',
            'keterangan' => '1. Penerima
0. Tidak',
        ],
        [
            'no' => 9,
            'nama_layanan' => 'Status Kawin',
            'nama_variabel' => 'status_kawin',
            'definisi' => 'Status perkawinan individu',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Belum kawin
2. Kawin/nikah
3. Cerai hidup
4. Cerai mati',
        ],
        [
            'no' => 10,
            'nama_layanan' => 'Partisipasi Sekolah',
            'nama_variabel' => 'partisipasi_sekolah',
            'definisi' => 'Status pertisipasi sekolah',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Tidak/belum pernah sekolah
2. Masih sekolah
3. Tidak bersekolah lagi',
        ],
        [
            'no' => 11,
            'nama_layanan' => 'Jenjang Tertinggi yang Diduduki',
            'nama_variabel' => 'jenjang_tertinggi_yang_diduduki',
            'definisi' => 'Jenjang dan jenis pendidikan tertinggi yang pernah/sedang diduduki',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '01. Paket A
02. SDLB
03. SD
04. MI
05. SPM/PDF Ula
06. Paket B
07. SMP LB
08. SMP
09. MTs
10. SPM/PDF Wustha
11. Paket C
12. SMLB
13. SMA
14. MA
15. SMK
16. MAK
17. SPM/PDF Ulya
18. D1/D2/D3
19. D4/S1
20. Profesi
21. S2
22. S3',
        ],
        [
            'no' => 12,
            'nama_layanan' => 'Kelas Tertinggi yang Diduduki',
            'nama_variabel' => 'kelas_tertinggi_yang_diduduki',
            'definisi' => 'Kelas tertinggi yang pernah/sedang diduduki',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1 s.d. 8',
        ],
        [
            'no' => 13,
            'nama_layanan' => 'Ijazah Tertinggi yang Dimiliki',
            'nama_variabel' => 'ijazah_tertinggi_yang_dimiliki',
            'definisi' => 'Ijazah/STTB tertinggi yang dimiliki',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '01. Paket A
02. SDLB
03. SD
04. MI
05. SPM/PDF Ula
06. Paket B
07. SMP LB
08. SMP
09. MTs
10. SPM/PDF Wustha
11. Paket C
12. SMLB
13. SMA
14. MA
15. SMK
16. MAK
17. SPM/PDF Ulya
18. D1/D2/D3
19. D4/S1
20. Profesi
21. S2
22. S3
23. Tidak Punya Ijazah SD',
        ],
        [
            'no' => 14,
            'nama_layanan' => 'Status Bekerja',
            'nama_variabel' => 'status_bekerja',
            'definisi' => 'Status bekerja/membantu bekerja',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 15,
            'nama_layanan' => 'Lapangan Usaha dari Pekerjaan Utama',
            'nama_variabel' => 'lapangan_usaha_dari_pekerjaan_utama',
            'definisi' => 'Lapangan usaha dari pekerjaan utama',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '01. Pertanian tanaman pangan dan palawija
02. Hortikultura
03. Perkebunan
04. Perikanan
05. Peternakan
06. Kehutanan & pertanian lainnya
07. Pertambangan/penggalian
08. Industri pengolahan
09. Pengadaan listrik, gas, uap/air panas, dan udara dingin
10. Pengelolaan air, pengelolaan air limbah, pengelolaan dan daur ulang sampah, dan aktivitas remediasi
11. Konstruksi
12. Perdagangan besar dan eceran, reparasidan perawatan mobil dan sepeda motor
13. Pengangkutan dan pergudangan
14. Penyediaan akomodasi & makan minum
15. Informasi & komunikasi
16. Keuangan & asuransi
17. Real estate
18. Aktivitas profesional, ilmiah, dan teknis
19. Aktivitas penyewaan dan sewa guna tanpa hak opsi, ketenagakerjaan, agen perjalanan,dan penunjang usaha lainnya
20. Administrasi pemerintahan, pertahanan, danjaminan sosial wajib
21. Pendidikan
22. Aktivitas kesehatan manusia dan aktivitas sosial
23. Kesenian, hiburan, dan rekreasi
24. Aktivitas jasa lainnya
25. Aktivitas keluarga sebagai pemberi kerja
26. Aktivitas badan internasional dan badan ekstra internasional lainnya',
        ],
        [
            'no' => 16,
            'nama_layanan' => 'Status Dalam Pekerjaan Utama',
            'nama_variabel' => 'status_dalam_pekerjaan_utama',
            'definisi' => 'Status/kedudukan dalam pekerjaan utama',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Berusaha sendiri
2. Berusaha dibantu buruh tidak tetap/tidak dibayar
3. Berusaha dibantu buruh tetap/buruh dibayar
4. Buruh/karyawan/pegawai swasta
5. PNS/TNI/Polri/ BUMN/BUMD/pejabat negara
6. Pekerja bebas pertanian
7. Pekerja bebas non pertanian
8. Pekerja keluarga/tidak dibayar',
        ],
        [
            'no' => 17,
            'nama_layanan' => 'Kepemilikan Usaha',
            'nama_variabel' => 'kepemilikan_usaha',
            'definisi' => 'Kepemilikan usaha sendiri/bersama',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Ya
2. Tidak',
        ],
        [
            'no' => 18,
            'nama_layanan' => 'Jumlah Usaha',
            'nama_variabel' => 'jumlah_usaha',
            'definisi' => 'Jumlah usaha yang dimiliki sendiri/bersama',
            'tipe_data' => 'int(3)',
            'keterangan' => '',
        ],
        [
            'no' => 19,
            'nama_layanan' => 'Lapangan Usaha dari Usaha Utama',
            'nama_variabel' => 'lapangan_usaha_dari_usaha_utama',
            'definisi' => 'Lapangan usaha dari usaha utama',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '01. Pertanian tanaman padi & palawija
02. Hortikultura
03. Perkebunan
04. Perikanan
05. Peternakan
06. Kehutanan & pertanian lainnya
07. Pertambangan/penggalian
08. Industri pengolahan
09. Pengadaan listrik, gas, uap/air panas, dan udara dingin
10. Pengelolaan air, pengelolaan air limbah, pengelolaan dan daur ulang sampah, dan aktivitas remediasi
11. Konstruksi
12. Perdagangan besar dan eceran, reparasidan perawatan mobil dan sepeda motor
13. Pengangkutan dan pergudangan
14. Penyediaan akomodasi & makan minum
15. Informasi & komunikasi
16. Keuangan & asuransi
17. Real estate
18. Aktivitas profesional, ilmiah, dan teknis
19. Aktivitas penyewaan dan sewa guna tanpa hak opsi, ketenagakerjaan, agen perjalanan,dan penunjang usaha lainnya
20. Administrasi pemerintahan, pertahanan, danjaminan sosial wajib
21. Pendidikan
22. Aktivitas kesehatan manusia dan aktivitas sosial
23. Kesenian, hiburan, dan rekreasi
24. Aktivitas jasa lainnya
25. Aktivitas keluarga sebagai pemberi kerja
26. Aktivitas badan internasional dan badan ekstra internasional lainnya',
        ],
        [
            'no' => 20,
            'nama_layanan' => 'Jumlah Pekerja yang Dibayar dari Usaha Utama',
            'nama_variabel' => 'jumlah_pekerja_yang_dibayar_dari_usaha_utama',
            'definisi' => 'Jumlah pekerja yang dibayar dari usaha utama',
            'tipe_data' => 'int(3)',
            'keterangan' => '',
        ],
        [
            'no' => 21,
            'nama_layanan' => 'Jumlah Pekerja yang Tidak Dibayar dari Usaha Utama',
            'nama_variabel' => 'jumlah_pekerja_yang_tidak_dibayar_dari_usaha_utama',
            'definisi' => 'Jumlah pekerja yang tidak dibayar dari usaha utama',
            'tipe_data' => 'int(3)',
            'keterangan' => '',
        ],
        [
            'no' => 22,
            'nama_layanan' => 'Omzet Usaha Utama',
            'nama_variabel' => 'omzet_usaha_utama',
            'definisi' => 'Omzet dari usaha utama perbulan (Rupiah)',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. < 5 Juta (ultra mikro)
2. 5-<15 Juta (ultra mikro)
3. 15-<25 Juta (ultra mikro)
4. 25-<167 Juta (mikro)
5. 167 -< 1.250 Juta (kecil)
6. 1.250 -< 4.167 Juta (menengah)
7. ≥ 4.167 Juta (besar)',
        ],
        [
            'no' => 23,
            'nama_layanan' => 'Kondisi Gizi',
            'nama_variabel' => 'kondisi_gizi',
            'definisi' => 'Kondisi gizi anak hasil pemeriksaan dengan mengacu pada catatan/buku kontrol',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Kurang gizi (Wasting)
2. Kerdil (Stunting)
3. Tidak ada catatan
8. Tidak tahu',
        ],
        [
            'no' => 24,
            'nama_layanan' => 'Penglihatan',
            'nama_variabel' => 'penglihatan',
            'definisi' => 'Kesulitan/gangguan penglihatan meskipun menggunakan alat bantu melihat?',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Ya, sama sekali tidak bisa
2. Ya, banyak kesulitan dan membutuhkan bantuan
3. Ya, sedikit kesulitan, tapi tidak membutuhkan bantuan
4. Tidak mengalami kesulitan',
        ],
        [
            'no' => 25,
            'nama_layanan' => 'Pendengaran',
            'nama_variabel' => 'pendengaran',
            'definisi' => 'Kesulitan/gangguan pendengaran meskipun menggunakan alat bantu dengar',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Ya, sama sekali tidak bisa
2. Ya, banyak kesulitan dan membutuhkan bantuan
3. Ya, sedikit kesulitan, tapi tidak membutuhkan bantuan
4. Tidak mengalami kesulitan',
        ],
        [
            'no' => 26,
            'nama_layanan' => 'Berjalan atau Naik Tangga',
            'nama_variabel' => 'berjalan_atau_naik_tangga',
            'definisi' => 'Kesulitan/gangguan berjalan atau naik tangga',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Ya, sama sekali tidak bisa
2. Ya, banyak kesulitan dan membutuhkan bantuan
3. Ya, sedikit kesulitan, tapi tidak membutuhkan bantuan
4. Tidak mengalami kesulitan',
        ],
        [
            'no' => 27,
            'nama_layanan' => 'Menggunakan Tangan Jari',
            'nama_variabel' => 'menggunakan_tangan_jari',
            'definisi' => 'Kesulitan/gangguan menggerakkan/menggunakan tangan/ jari',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Ya, sama sekali tidak bisa
2. Ya, banyak kesulitan dan membutuhkan bantuan
3. Ya, sedikit kesulitan, tapi tidak membutuhkan bantuan
4. Tidak mengalami kesulitan',
        ],
        [
            'no' => 28,
            'nama_layanan' => 'Belajar Kemampuan Intelektual',
            'nama_variabel' => 'belajar_kemampuan_intelektual',
            'definisi' => 'Dibandingkan dengan yang sebaya, individu mengalami kesulitan/gangguan dalam belajar atau kemampuan intelektual',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Ya, sama sekali tidak bisa
2. Ya, banyak kesulitan dan membutuhkan bantuan
3. Ya, sedikit kesulitan, tapi tidak membutuhkan bantuan
4. Tidak mengalami kesulitan',
        ],
        [
            'no' => 29,
            'nama_layanan' => 'Pengendalian Perilaku',
            'nama_variabel' => 'pengendalian_perilaku',
            'definisi' => 'Dibandingkan dengan yang sebaya, individu mengalami kesulitan/gangguan mengendalikan perilaku',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Ya, sama sekali tidak bisa
2. Ya, banyak kesulitan dan membutuhkan bantuan
3. Ya, sedikit kesulitan, tapi tidak membutuhkan bantuan
4. Tidak mengalami kesulitan',
        ],
        [
            'no' => 30,
            'nama_layanan' => 'Berbicara Komunikasi',
            'nama_variabel' => 'berbicara_komunikasi',
            'definisi' => 'Kesulitan/gangguan berbicara/berkomunikasi',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Ya, sama sekali tidak bisa
2. Ya, banyak kesulitan dan membutuhkan bantuan
3. Ya, sedikit kesulitan, tapi tidak membutuhkan bantuan
4. Tidak mengalami kesulitan',
        ],
        [
            'no' => 31,
            'nama_layanan' => 'Mengurus Diri',
            'nama_variabel' => 'mengurus_diri',
            'definisi' => 'Kesulitan/gangguan untuk mengurus diri sendiri? (mandi, makan, berpakaian, BAK, BAB)',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Ya, sama sekali tidak bisa
2. Ya, banyak kesulitan dan membutuhkan bantuan
3. Ya, sedikit kesulitan, tapi tidak membutuhkan bantuan
4. Tidak mengalami kesulitan',
        ],
        [
            'no' => 32,
            'nama_layanan' => 'Mengingat Berkonsentrasi',
            'nama_variabel' => 'mengingat_berkonsentrasi',
            'definisi' => 'Kesulitan/gangguan mengingat/berkonsentrasi',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Ya, sama sekali tidak bisa
2. Ya, banyak kesulitan dan membutuhkan bantuan
3. Ya, sedikit kesulitan, tapi tidak membutuhkan bantuan
4. Tidak mengalami kesulitan',
        ],
        [
            'no' => 33,
            'nama_layanan' => 'Kesedihan Depresi',
            'nama_variabel' => 'kesedihan_depresi',
            'definisi' => 'Gangguan kesedihan depresi',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '1. Sangat sering
2. Sering
3. Jarang
4. Tidak pernah',
        ],
        [
            'no' => 34,
            'nama_layanan' => 'Penyakit Kronis',
            'nama_variabel' => 'penyakit_kronis',
            'definisi' => 'Keluhan kesehatan kronis/menahun',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '01. Tidak Ada
02. Hipertensi (darah tinggi)
03. Rematik
04. Asma
05. Masalah jantung
06. Diabetes (kencing manis)
07. Tuberculosis (TBC)
08. Stroke
09. Kanker atau tumor ganas
10. Gagal ginjal
11. Haemophilia
12. HIV/AIDS
13. Kolesterol
14. Sirosis Hati
15. Thalasemia
16. Leukimia
17. Alzheimer
18. Lainnya',
        ],
        [
            'no' => 35,
            'nama_layanan' => 'Kode Provinsi (KTP)',
            'nama_variabel' => 'kode_provinsi_ktp',
            'definisi' => 'Kode provinsi yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(2)',
            'keterangan' => '2 digit kode provinsi yang tercatat di Dukcapil',
        ],
        [
            'no' => 36,
            'nama_layanan' => 'Provinsi (KTP)',
            'nama_variabel' => 'provinsi_ktp',
            'definisi' => 'Nama provinsi yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(100)',
            'keterangan' => '',
        ],
        [
            'no' => 37,
            'nama_layanan' => 'Kode Kabupaten/Kota (KTP)',
            'nama_variabel' => 'kode_kabupaten_kota_ktp',
            'definisi' => 'Kode kabupaten/kota yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(4)',
            'keterangan' => '4 digit kode kabupaten/kota yang tercatat di Dukcapil',
        ],
        [
            'no' => 38,
            'nama_layanan' => 'Kabupaten/Kota (KTP)',
            'nama_variabel' => 'kabupaten_kota_ktp',
            'definisi' => 'Nama kabupaten/kota yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(100)',
            'keterangan' => '',
        ],
        [
            'no' => 39,
            'nama_layanan' => 'Kode Kecamatan (KTP)',
            'nama_variabel' => 'kode_kecamatan_ktp',
            'definisi' => 'Kode kecamatan yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(7)',
            'keterangan' => '7 digit kode kecamatan yang tercatat di Dukcapil',
        ],
        [
            'no' => 40,
            'nama_layanan' => 'Kecamatan (KTP)',
            'nama_variabel' => 'kecamatan_ktp',
            'definisi' => 'Nama kecamatan yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(100)',
            'keterangan' => '',
        ],
        [
            'no' => 41,
            'nama_layanan' => 'Kode Kelurahan/Desa (KTP)',
            'nama_variabel' => 'kode_kelurahan_desa_ktp',
            'definisi' => 'Kode kelurahan/desa yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(10)',
            'keterangan' => '10 digit kode kelurahan/desa yang tercatat di Dukcapil',
        ],
        [
            'no' => 42,
            'nama_layanan' => 'Kelurahan/Desa (KTP)',
            'nama_variabel' => 'kelurahan_desa_ktp',
            'definisi' => 'Nama kelurahan/desa yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(100)',
            'keterangan' => '',
        ],
        [
            'no' => 43,
            'nama_layanan' => 'RT (KTP)',
            'nama_variabel' => 'rt_ktp',
            'definisi' => 'RT yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(5)',
            'keterangan' => '',
        ],
        [
            'no' => 44,
            'nama_layanan' => 'RW (KTP)',
            'nama_variabel' => 'rw_ktp',
            'definisi' => 'RW yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(5)',
            'keterangan' => '',
        ],
        [
            'no' => 45,
            'nama_layanan' => 'Dusun (KTP)',
            'nama_variabel' => 'dusun_ktp',
            'definisi' => 'Dusun yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(100)',
            'keterangan' => '',
        ],
        [
            'no' => 46,
            'nama_layanan' => 'Alamat (KTP)',
            'nama_variabel' => 'alamat_ktp',
            'definisi' => 'Alamat yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(500)',
            'keterangan' => '',
        ],
        [
            'no' => 47,
            'nama_layanan' => 'Pekerjaan (KTP)',
            'nama_variabel' => 'pekerjaan_ktp',
            'definisi' => 'Pekerjaan yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(500)',
            'keterangan' => '',
        ],
        [
            'no' => 48,
            'nama_layanan' => 'Pendidikan Akhir (KTP)',
            'nama_variabel' => 'pendidikan_akhir_ktp',
            'definisi' => 'Pendidikan terakhir yang tercatat di Dukcapil',
            'tipe_data' => 'varchar(100)',
            'keterangan' => '',
        ],
    ],
];
