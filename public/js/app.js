/**
 * UNIFIED APPLICATION JAVASCRIPT (ONE MASTER SCRIPT)
 * Features:
 * 1. Multi-Language Real-time Switcher (ID, EN, SU, JV)
 * 2. File Upload & Drag-and-Drop Dropzone
 * 3. Quick Fill for Demo / Authentication
 * 4. Cryptographic Hash Copy to Clipboard
 * Zero inline scripts in any Blade template.
 */

// 1. Four-Language Localization Dictionary
const LANG_DICTIONARY = {
    id: {
        form_title: "Layanan Permintaan Bagi Pakai DTSEN",
        form_subtitle: "Silakan isi rincian instansi dan tentukan variabel data DTSEN yang Anda butuhkan.",
        notice_text: "Sertifikasi Integritas Otomatis: Berkas surat yang Anda unggah akan diproses menggunakan Dual-Hash SHA-256 sebagai jaminan keaslian dan bukti validasi pada lembar verifikasi.",
        label_judul: "Nama Instansi / Lembaga Pemohon",
        help_judul: "Nama lengkap instansi pengusul permohonan bagi pakai data DTSEN.",
        ph_judul: "Contoh: Dinas Sosial Provinsi / Bappeda Kota",
        label_pengusul: "No. Surat Edaran / Permohonan",
        ph_pengusul: "Contoh: SE/400.9/081/DINSOS/2026",
        label_unit: "Nama PIC / Penanggung Jawab",
        ph_unit: "Nama Pejabat / Pegawai Narahubung",
        label_file: "Lampiran Surat Edaran / Permohonan (File Attachment)",
        title_file: "Klik di sini untuk memilih berkas surat atau seret ke area ini",
        sub_file: "Mendukung format: PDF, DOCX, XLSX, atau ZIP (Maksimal 50MB)",
        selected_file: "✓ Berkas Terpilih",
        label_klasifikasi: "Klasifikasi Keamanan Data",
        opt_terbatas: "TERBATAS (Level 3) — Pemanfaatan internal antar SKPD / Lembaga",
        opt_biasa: "BIASA (Level 2) — Terbuka untuk umum / pengumuman publik",
        opt_rahasia: "RAHASIA (Level 4) — Data mikro strategis bersyarat ketat",
        label_catatan: "Tujuan Pemanfaatan Bagi Pakai DTSEN (Opsional)",
        ph_catatan: "Tulis ringkasan peruntukan atau program pemanfaatan data DTSEN...",
        text_kepatuhan: "Menyetujui perekaman nilai integritas Dual-Hash SHA-256 untuk sertifikasi keabsahan dokumen anti-tamper.",
        link_blanko: "Tiket Layanan DTSEN",
        link_riwayat: "Dashboard Admin",
        btn_submit: "Kirim & Buat Tiket DTSEN",
        // Login Page
        login_title: "Login Administrator",
        login_subtitle: "Masukkan akun Anda untuk melanjutkan ke Dashboard Manajemen Tiket DTSEN.",
        label_email: "Email atau nama pengguna",
        label_password: "Kata sandi",
        link_forgot: "Lupa sandi? / Isi Cepat",
        guest_text: "Bukan komputer Anda? Gunakan mode Tamu untuk login secara pribadi.",
        btn_create: "Buat akun",
        btn_next: "Masuk Sistem",
        // Confirmation Page
        confirm_title: "Tiket Berhasil Dibuat",
        confirm_subtitle: "Permohonan bagi pakai data DTSEN telah berhasil diregistrasi.",
        confirm_success_msg: "Tiket DTSEN berhasil disimpan dan masuk ke antrean verifikasi admin.",
        confirm_reg_no: "Nomor Tiket DTSEN",
        confirm_lbl_title: "Instansi Pemohon:",
        confirm_lbl_proposer: "No. Surat Edaran:",
        confirm_lbl_file: "Berkas Surat Terlampir:",
        confirm_lbl_date: "Waktu Pengajuan:",
        confirm_lbl_hash: "🛡️ Nilai Kriptografi SHA-256 (Integritas Berkas)",
        confirm_btn_copy: "Salin",
        confirm_link_other: "← Buat Tiket Lain",
        confirm_btn_print: "Lihat Progress Tiket"
    },
    en: {
        form_title: "Terms of Reference (TOR/KAK) Submission Form",
        form_subtitle: "Please enter the activity details and attach your Terms of Reference document.",
        notice_text: "Automated Integrity Certification: Uploaded documents are verified with Dual-Hash SHA-256 for non-repudiation on the endorsement sheet.",
        label_judul: "TOR Title / Activity Name",
        help_judul: "Full title of the activity (if empty, filename will be used automatically).",
        ph_judul: "e.g., Server & Mini Data Center Procurement 2026",
        label_pengusul: "Proposer / PPTK Officer Name",
        ph_pengusul: "Technical Activity Implementation Officer Name",
        label_unit: "Department / Working Division",
        ph_unit: "e.g., Infrastructure & IT Division",
        label_file: "TOR Document File (Attachment)",
        title_file: "Click here to choose file or drag into this area",
        sub_file: "Supported formats: PDF, DOCX, XLSX, or ZIP (Max 50MB)",
        selected_file: "✓ File Selected",
        label_klasifikasi: "Document Security Classification",
        opt_terbatas: "RESTRICTED (Level 3) — Internal department activity & budget details",
        opt_biasa: "PUBLIC (Level 2) — Open for general public announcement",
        opt_rahasia: "CONFIDENTIAL (Level 4) — High-value strategic procurement specs",
        label_catatan: "Notes / Scope of Work Summary (Optional)",
        ph_catatan: "Write brief background or scope of the TOR document...",
        text_kepatuhan: "Agree to record Dual-Hash SHA-256 integrity digest for anti-tamper certification.",
        link_blanko: "Print Blank Form",
        link_riwayat: "Document History",
        btn_submit: "Submit & Register TOR",
        // Login Page
        login_title: "Sign in",
        login_subtitle: "Enter your account credentials to access the TOR Document System.",
        label_email: "Email or username",
        label_password: "Password",
        link_forgot: "Forgot password? / Quick Fill",
        guest_text: "Not your computer? Use Guest mode to sign in privately.",
        btn_create: "Create account",
        btn_next: "Next",
        // Confirmation Page
        confirm_title: "Response Recorded",
        confirm_subtitle: "Terms of Reference (TOR/KAK) document submission has been successfully registered.",
        confirm_success_msg: "TOR document successfully stored with Dual-Hash SHA-256 cryptographic certification.",
        confirm_reg_no: "TOR Registration Number",
        confirm_lbl_title: "Activity Title:",
        confirm_lbl_proposer: "Proposer / PPTK:",
        confirm_lbl_file: "Uploaded File:",
        confirm_lbl_date: "Registration Date:",
        confirm_lbl_hash: "🛡️ Cryptographic Value SHA-256 (File Integrity)",
        confirm_btn_copy: "Copy",
        confirm_link_other: "← Submit Another Form",
        confirm_btn_print: "Print Endorsement Sheet (PRINT)"
    },
    su: {
        form_title: "Formulir Pangajuan Dokumén KAK",
        form_subtitle: "Mangga lebetkeun wincikan kagiatan sareng lampirkeun dokumén KAK anjeun.",
        notice_text: "Sertifikasi Integritas Otomatis: Dokumén anu diunggah bakal diverifikasi nganggo Dual-Hash SHA-256 pikeun jaminan kaaslian dina lambar pangesahan.",
        label_judul: "Judul KAK / Nami Kagiatan",
        help_judul: "Nami lengkep kagiatan (upami kosong, bakal nganggo nami berkas).",
        ph_judul: "Conto: Pangadaan Parangkat Server & Data Center 2026",
        label_pengusul: "Nami Pangusul / PPTK",
        ph_pengusul: "Nami Pajabat Pelaksana Teknis Kagiatan",
        label_unit: "Unit Damel / Bagian",
        ph_unit: "Conto: Bagian Infrastruktur & TI",
        label_file: "Berkas Dokumén KAK (Lampiran)",
        title_file: "Klik di dieu kanggo milih berkas atanapi seret ka dieu",
        sub_file: "Format dirojong: PDF, DOCX, XLSX, atanapi ZIP (Maks. 50MB)",
        selected_file: "✓ Berkas Kapilih",
        label_klasifikasi: "Klasifikasi Dokumén",
        opt_terbatas: "DIWATESAN (Level 3) — Rincian anggaran internal SKPD",
        opt_biasa: "BIASA (Level 2) — Kabuka kanggo umum / publik",
        opt_rahasia: "RAHASIA (Level 4) — Spésifikasi téknis strategis luhur",
        label_catatan: "Catetan / Ringkesan Ambahan (Opsional)",
        ph_catatan: "Tulis ringkesan kasang tukang dokumén KAK...",
        text_kepatuhan: "Satuju kana rékaman kode integritas Dual-Hash SHA-256 pikeun sertifikasi kaaslian.",
        link_blanko: "Cétak Blanko KAK",
        link_riwayat: "Riwayat Dokumén",
        btn_submit: "Kintun & Daptar KAK",
        // Login Page
        login_title: "Lebet Akun",
        login_subtitle: "Lebetkeun akun anjeun kanggo neraskeun kana Sistem KAK.",
        label_email: "Email atanapi nami pamaké",
        label_password: "Kecap konci",
        link_forgot: "Hilap kecap konci? / Eusian Gancang",
        guest_text: "Sanés komputer anjeun? Anggo mode Tamu kanggo lebet sacara pribadi.",
        btn_create: "Damel akun",
        btn_next: "Salajengna",
        // Confirmation Page
        confirm_title: "Walaran Parantos Dirékam",
        confirm_subtitle: "Pangajuan dokumén KAK parantos suksés dirégristrasi.",
        confirm_success_msg: "Dokumén KAK suksés disimpen nganggo sertifikasi kriptografi Dual-Hash SHA-256.",
        confirm_reg_no: "Nomer Régistrasi KAK",
        confirm_lbl_title: "Judul Kagiatan:",
        confirm_lbl_proposer: "Pangusul / PPTK:",
        confirm_lbl_file: "Berkas Diunggah:",
        confirm_lbl_date: "Kaping Régistrasi:",
        confirm_lbl_hash: "🛡️ Nilé Kriptografi SHA-256 (Integritas Berkas)",
        confirm_btn_copy: "Tiron",
        confirm_link_other: "← Kintun Formulir Séjén",
        confirm_btn_print: "Cétak Lambar Pangesahan (PRINT)"
    },
    jv: {
        form_title: "Formulir Pengajuan Dhokumen KAK",
        form_subtitle: "Mangga dipun isi rincian kagiyatan lan lampiraken dhokumen KAK panjenengan.",
        notice_text: "Sertifikasi Integritas Otomatis: Dhokumen ingkang dipun unggah bakal dipun verifikasi nganggo Dual-Hash SHA-256 kanggé jaminan kaaslian ing lembar pengesahan.",
        label_judul: "Judul KAK / Jeneng Kagiyatan",
        help_judul: "Jeneng jangkep kagiyatan (menawa kothong, ngetutke jeneng berkas).",
        ph_judul: "Tuladha: Pengadaan Perangkat Server & Mini Data Center 2026",
        label_pengusul: "Jeneng Pengusul / PPTK",
        ph_pengusul: "Jeneng Pejabat Pelaksana Teknis Kagiyatan",
        label_unit: "Unit Kerja / Bagéan",
        ph_unit: "Tuladha: Bagéan Infrastruktur & TI",
        label_file: "Berkas Dhokumen KAK (Lampiran)",
        title_file: "Klik ing kene kanggo milih berkas utawa seret mrene",
        sub_file: "Format saged: PDF, DOCX, XLSX, utawa ZIP (Maks. 50MB)",
        selected_file: "✓ Berkas Sampun Dipilih",
        label_klasifikasi: "Klasifikasi Dhokumen",
        opt_terbatas: "WATES (Level 3) — Rincian anggaran internal SKPD",
        opt_biasa: "BIASA (Level 2) — Bikak kagem umum / publik",
        opt_rahasia: "WADI / RAHASIA (Level 4) — Spesifikasi teknis strategis inggil",
        label_catatan: "Cathetan / Ringkesan Kagiyatan (Opsional)",
        ph_catatan: "Serat ringkesan latar wuri dhokumen KAK...",
        text_kepatuhan: "Sarujuk pencatatan kode integritas Dual-Hash SHA-256 kanggé sertifikasi kaaslian.",
        link_blanko: "Cithak Blanko KAK",
        link_riwayat: "Riwayat Dhokumen",
        btn_submit: "Kintun & Dhaptar KAK",
        // Login Page
        login_title: "Mlebet Akun",
        login_subtitle: "Mlebetaken akun panjenengan kagem nerusaken dhateng Sistem KAK.",
        label_email: "Email utawa jeneng pangguna",
        label_password: "Tembung sandi",
        link_forgot: "Lali tembung sandi? / Isi Cepet",
        guest_text: "Sanes komputer panjenengan? Gunakaken mode Tamu kagem mlebet pribadi.",
        btn_create: "Damel akun",
        btn_next: "Salajengipun",
        // Confirmation Page
        confirm_title: "Tanggapan Sampun Kaserat",
        confirm_subtitle: "Pengajuan berkas KAK sampun kasil dipun registrasi.",
        confirm_success_msg: "Dhokumen KAK kasil dipun simpen mawi sertifikasi kriptografi Dual-Hash SHA-256.",
        confirm_reg_no: "Nomer Registrasi KAK",
        confirm_lbl_title: "Judul Kagiyatan:",
        confirm_lbl_proposer: "Pengusul / PPTK:",
        confirm_lbl_file: "Berkas Dipun Unggah:",
        confirm_lbl_date: "Tanggal Registrasi:",
        confirm_lbl_hash: "🛡️ Nilai Kriptografi SHA-256 (Integritas Berkas)",
        confirm_btn_copy: "Turun",
        confirm_link_other: "← Kintun Formulir Liyan",
        confirm_btn_print: "Cithak Lembar Pengesahan (PRINT)"
    }
};

// 2. Lifecycle Initialization
document.addEventListener('DOMContentLoaded', function () {
    initDeviceFingerprint();
    initLanguageSwitcher();
    initFileDropzone();
    initQuickFill();
});

// Device Machine Identifier (Fingerprint Unique to Each Laptop/Browser)
function initDeviceFingerprint() {
    let deviceId = localStorage.getItem('app_device_id');
    if (!deviceId) {
        const randomHex = Math.random().toString(36).substring(2, 6).toUpperCase();
        let prefix = 'DEV';
        const ua = navigator.userAgent;
        if (ua.includes('Windows')) prefix = 'WIN';
        else if (ua.includes('Macintosh') || ua.includes('Mac OS')) prefix = 'MAC';
        else if (ua.includes('Android')) prefix = 'AND';
        else if (ua.includes('iPhone') || ua.includes('iPad')) prefix = 'IOS';
        else if (ua.includes('Linux')) prefix = 'LNX';
        deviceId = `${prefix}-${randomHex}`;
        localStorage.setItem('app_device_id', deviceId);
    }
    document.cookie = `app_device_id=${deviceId}; path=/; max-age=31536000; SameSite=Lax`;
}

// 3. Multi-Language Engine
function initLanguageSwitcher() {
    const langSelect = document.getElementById('langSwitcher');
    if (!langSelect) return;

    const savedLang = localStorage.getItem('app_kak_lang') || 'id';
    langSelect.value = savedLang;
    applyLanguage(savedLang);

    langSelect.addEventListener('change', function () {
        const selected = this.value;
        localStorage.setItem('app_kak_lang', selected);
        applyLanguage(selected);
    });
}

function applyLanguage(lang) {
    const dict = LANG_DICTIONARY[lang] || LANG_DICTIONARY.id;

    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (dict[key]) {
            el.textContent = dict[key];
        }
    });

    document.querySelectorAll('[data-i18n-ph]').forEach(el => {
        const key = el.getAttribute('data-i18n-ph');
        if (dict[key]) {
            el.setAttribute('placeholder', dict[key]);
        }
    });
}

// 4. File Dropzone & Selection Handling
function initFileDropzone() {
    const fileInput = document.getElementById('gFileInput') || document.getElementById('kakFileInput');
    const dropzone = document.getElementById('gFileBox') || document.getElementById('kakDropzone');

    if (!fileInput || !dropzone) return;

    fileInput.addEventListener('change', function () {
        handleSelectedFile(this.files);
    });

    ['dragenter', 'dragover'].forEach(name => {
        dropzone.addEventListener(name, function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(name => {
        dropzone.addEventListener(name, function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('dragover');
        });
    });

    dropzone.addEventListener('drop', function (e) {
        const dt = e.dataTransfer;
        if (dt && dt.files && dt.files.length > 0) {
            fileInput.files = dt.files;
            handleSelectedFile(dt.files);
        }
    });
}

function handleSelectedFile(files) {
    if (!files || files.length === 0) return;
    const file = files[0];
    const fileName = file.name;
    const ext = fileName.split('.').pop().toUpperCase();
    
    let sizeStr = '';
    if (file.size >= 1048576) {
        sizeStr = (file.size / 1048576).toFixed(2) + ' MB';
    } else {
        sizeStr = (file.size / 1024).toFixed(1) + ' KB';
    }

    const promptElem = document.getElementById('gFilePrompt') || document.getElementById('dropzonePrompt');
    const selectedElem = document.getElementById('gFileSelected') || document.getElementById('dropzoneSelected');
    const nameElem = document.getElementById('gFileNameText') || document.getElementById('fileNameText');
    const sizeElem = document.getElementById('gFileSizeText') || document.getElementById('fileSizeText');
    const extElem = document.getElementById('gFileExtBadge') || document.getElementById('fileExtBadge');

    if (promptElem) promptElem.style.display = 'none';
    if (selectedElem) selectedElem.style.display = 'flex';
    if (nameElem) nameElem.textContent = fileName;
    if (sizeElem) sizeElem.textContent = sizeStr;
    if (extElem) extElem.textContent = ext.substring(0, 4);

    // Auto-isi Judul KAK jika kosong
    const judulInput = document.getElementById('inputJudulKak');
    if (judulInput && !judulInput.value) {
        const base = fileName.substring(0, fileName.lastIndexOf('.')) || fileName;
        judulInput.value = base.replace(/[_-]/g, ' ');
    }
}

// 5. Quick Fill Demo Login
function initQuickFill() {
    const btnQuick = document.getElementById('btnQuickFill');
    if (btnQuick) {
        btnQuick.addEventListener('click', function () {
            const loginInput = document.getElementById('input_login');
            const passInput = document.getElementById('input_password');
            if (loginInput) loginInput.value = 'admin@gmail.com';
            if (passInput) passInput.value = 'admin';
        });
    }
}

// 6. Copy SHA-256 Cryptographic Hash
function copyHash() {
    const hashElem = document.getElementById('gHashCode');
    if (!hashElem) return;
    const text = hashElem.textContent.trim();
    navigator.clipboard.writeText(text).then(() => {
        alert('Kode Hash SHA-256 berhasil disalin ke clipboard!');
    }).catch(() => {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('Kode Hash SHA-256 berhasil disalin ke clipboard!');
    });
}

// 7. Generic helpers for legacy kak views
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const name = file.name;
        const sizeBytes = file.size;
        const ext = name.split('.').pop().toUpperCase();

        let sizeText = '';
        if (sizeBytes >= 1048576) {
            sizeText = (sizeBytes / 1048576).toFixed(2) + ' MB';
        } else {
            sizeText = (sizeBytes / 1024).toFixed(2) + ' KB';
        }

        const promptBox = document.getElementById('dropzonePrompt');
        if (promptBox) promptBox.style.display = 'none';

        const fileInfoBox = document.getElementById('fileInfoBox');
        if (fileInfoBox) fileInfoBox.style.display = 'flex';

        const fileNameElem = document.getElementById('selectedFileName');
        if (fileNameElem) fileNameElem.innerText = name;

        const fileSizeElem = document.getElementById('selectedFileSize');
        if (fileSizeElem) fileSizeElem.innerText = sizeText;

        const extBadge = document.getElementById('fileExtBadge');
        if (extBadge) extBadge.innerText = ext.substring(0, 4);

        const judulInput = document.getElementById('judul_kak');
        if (judulInput && !judulInput.value) {
            const baseName = name.substring(0, name.lastIndexOf('.')) || name;
            judulInput.value = baseName.replace(/[_-]/g, ' ');
        }
    }
}

function printBlankForm() {
    window.open('/formulir/blanko', '_blank');
}

// 8. Sidebar Navigation Dropdown (Ensure flyout closes immediately when hover ends)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.app-nav-dropdown').forEach(function(item) {
        item.classList.remove('open');
        item.addEventListener('mouseleave', function() {
            item.classList.remove('open');
            const sub = item.querySelector('.app-nav-submenu');
            if (sub) {
                sub.style.display = 'none';
                setTimeout(() => { sub.style.display = ''; }, 30);
            }
        });
    });
});

function toggleNavDropdown(btn) {
    // No-op or toggle with auto-close
    const parent = btn.closest('.app-nav-dropdown');
    if (parent) {
        parent.classList.toggle('open');
    }
}

// 9. Generic Custom Flaticon Dropdown Toggle
document.addEventListener('click', function(e) {
    const toggleBtn = e.target.closest('.custom-flaticon-btn');
    const allDropdowns = document.querySelectorAll('.custom-flaticon-dropdown');

    if (toggleBtn) {
        e.preventDefault();
        const parent = toggleBtn.closest('.custom-flaticon-dropdown');
        allDropdowns.forEach(dd => {
            if (dd !== parent) dd.classList.remove('open');
        });
        if (parent) parent.classList.toggle('open');
        return;
    }

    if (!e.target.closest('.custom-flaticon-dropdown')) {
        allDropdowns.forEach(dd => dd.classList.remove('open'));
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.custom-flaticon-dropdown').forEach(dd => dd.classList.remove('open'));
    }
});
