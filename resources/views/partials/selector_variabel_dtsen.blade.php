@php
    $metadataKeluarga = config('dtsen_metadata.keluarga', []);
    $metadataAnggota = config('dtsen_metadata.anggota_keluarga', []);
    
    // Inisialisasi variabel terpilih (dari old input jika ada error validasi, atau dari model tiket pada edit)
    $initialSelected = old('variabel_dtsen', $selectedVars ?? []);
    if (!is_array($initialSelected)) {
        $initialSelected = [];
    }
    // Filter nilai kosong
    $initialSelected = array_values(array_filter(array_map('trim', $initialSelected)));
@endphp

<div class="dtsen-var-component">
    <!-- Grid Dua Kotak (Kiri: Filter Checkbox Tema & Terpilih | Kanan: Daftar Variabel Checklist) -->
    <div class="dtsen-var-grid">
        
        <!-- ================================================================= -->
        <!-- KOTAK KIRI: Checkbox Tema, Tombol Terapkan, Pencarian & Ringkasan -->
        <!-- ================================================================= -->
        <div class="dtsen-var-box dtsen-var-box-left">
            <div class="dtsen-box-header">
                <div class="dtsen-box-title">Pilih Tema & Filter Data</div>
                <div class="dtsen-box-subtitle">Pilih 1 atau kedua tema, lalu klik <strong>Terapkan</strong></div>
            </div>

            <!-- Pilihan Checkbox Tema (Bisa 1 atau 2-duanya) -->
            <div class="dtsen-theme-select-group">
                <label class="dtsen-field-label">
                    Pilih Tema Set Data <span style="color: #dc2626;">*</span>
                </label>
                
                <div class="dtsen-theme-checkboxes">
                    <!-- Tema 1: Data Keluarga -->
                    <label class="dtsen-theme-card" for="dtsenChkTemaKeluarga">
                        <input type="checkbox" id="dtsenChkTemaKeluarga" value="keluarga" class="dtsen-theme-chk">
                        <div class="dtsen-theme-card-body">
                            <div class="dtsen-theme-card-title-row">
                                <span class="dtsen-theme-title">Set Data Keluarga</span>
                                <span class="dtsen-theme-badge dtsen-badge-blue">52 Var</span>
                            </div>
                            
                        </div>
                    </label>

                    <!-- Tema 2: Data Anggota Keluarga -->
                    <label class="dtsen-theme-card" for="dtsenChkTemaAnggota">
                        <input type="checkbox" id="dtsenChkTemaAnggota" value="anggota" class="dtsen-theme-chk">
                        <div class="dtsen-theme-card-body">
                            <div class="dtsen-theme-card-title-row">
                                <span class="dtsen-theme-title">Set Data Anggota Keluarga</span>
                                <span class="dtsen-theme-badge dtsen-badge-purple">48 Var</span>
                            </div>
                            
                        </div>
                    </label>
                </div>

                <!-- Tombol Terapkan -->
                <button type="button" id="dtsenBtnApplyTema" class="dtsen-btn-apply" onclick="dtsenApplyThemes()">
                    <span>✓</span> Terapkan
                </button>
                <div id="dtsenTemaFeedback" class="dtsen-tema-feedback" style="display: none;"></div>
            </div>

            <!-- Pencarian Cepat Variabel -->
            <div class="dtsen-form-group" style="margin-top: 14px;">
                <label for="dtsenSearchInput" class="dtsen-field-label">
                    Pencarian Cepat
                </label>
                <div class="dtsen-search-wrap">
                    <input type="text" 
                           id="dtsenSearchInput" 
                           class="dtsen-text-input" 
                           placeholder="Ketik nama atau kode variabel..." 
                           oninput="dtsenOnSearchInput(this.value)">
                    <span class="dtsen-search-icon">🔍</span>
                </div>
            </div>

            <!-- Tombol Aksi Cepat pada Tema Aktif -->
            <div class="dtsen-actions-bar" id="dtsenQuickActions" style="display: none;">
                <button type="button" class="dtsen-btn-action" onclick="dtsenSelectAllVisible()">
                    ✓ Pilih Semua di Tampilan
                </button>
                <button type="button" class="dtsen-btn-action dtsen-btn-action-ghost" onclick="dtsenUnselectAllVisible()">
                    ✕ Batalkan Pilihan
                </button>
            </div>

            <hr class="dtsen-divider">

            <!-- Panel Ringkasan Variabel Terpilih -->
            <div class="dtsen-summary-section">
                <div class="dtsen-summary-head">
                    <div>
                        <span class="dtsen-summary-title">Variabel Terpilih</span>
                        <span id="dtsenSelectedBadge" class="dtsen-count-badge">0 Dipilih</span>
                    </div>
                    <button type="button" 
                            id="dtsenBtnResetAll" 
                            class="dtsen-btn-reset" 
                            style="display: none;" 
                            onclick="dtsenResetAllSelection()">
                        Reset Semua
                    </button>
                </div>

                

                <!-- Wadah Chips Variabel Terpilih -->
                <div id="dtsenSelectedChips" class="dtsen-chips-container">
                    <div class="dtsen-chips-empty">
                        Belum ada variabel yang dipilih.<br>
                        Centang tema data di atas lalu klik <strong>Terapkan</strong>, kemudian centang variabel pada kotak kanan.
                    </div>
                </div>
            </div>

            <!-- Wadah Input Tersembunyi (Hidden Inputs) untuk Pengiriman Form ke Controller -->
            <div id="dtsenHiddenInputs">
                @foreach($initialSelected as $v)
                    <input type="hidden" name="variabel_dtsen[]" value="{{ $v }}">
                @endforeach
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- KOTAK KANAN: Tabel / Daftar Checklist Variabel DTSEN              -->
        <!-- ================================================================= -->
        <div class="dtsen-var-box dtsen-var-box-right">
            
            <!-- Keadaan 1: KOSONG (Sebelum Pengguna Memilih Tema & Klik Terapkan) -->
            <div id="dtsenEmptyState" class="dtsen-empty-state">
                <div class="dtsen-empty-icon-wrap">
                    📋
                </div>
                <h4 class="dtsen-empty-title">Daftar Variabel Belum Ditampilkan</h4>
                <p class="dtsen-empty-text">
                    Silakan centang tema data pada kotak sebelah kiri (<strong>Set Data Keluarga</strong> dan/atau <strong>Set Data Anggota Keluarga</strong>), 
                    lalu klik tombol <strong>Terapkan</strong> untuk memunculkan daftar variabel checklist yang dapat dipilih.
                </p>
            </div>

            <!-- Keadaan 2: AKTIF (Muncul Setelah Tema Diterapkan) -->
            <div id="dtsenActivePanel" class="dtsen-active-panel" style="display: none;">
                <!-- Header Daftar Kotak Kanan -->
                <div class="dtsen-active-header">
                    <div>
                        <h4 id="dtsenActiveThemeTitle" class="dtsen-active-title">Daftar Variabel</h4>
                        <div id="dtsenActiveThemeSubtitle" class="dtsen-active-subtitle">Menampilkan variabel data DTSEN</div>
                    </div>
                    <div id="dtsenVisibleCountBadge" class="dtsen-visible-badge">
                        0 Item
                    </div>
                </div>

                <!-- Daftar Baris Variabel Berpenampilan Checklist -->
                <div id="dtsenChecklistContainer" class="dtsen-checklist-list">
                    <!-- Baris-baris checklist variabel dirender dinamis via JavaScript -->
                </div>
            </div>

        </div>

    </div>
</div>

<!-- ========================================================================= -->
<!-- JAVASCRIPT LOGIC UNTUK CHECKLIST & SELECTION DUA KOTAK                   -->
<!-- ========================================================================= -->
<script>
(function() {
    // 1. Data Katalog Metadata Resmi BPS/DTSEN 2026
    const metadataKeluarga = @json($metadataKeluarga);
    const metadataAnggota = @json($metadataAnggota);
    
    // Tagging tema
    const allDataset = [
        ...metadataKeluarga.map(item => ({ ...item, tema_key: 'keluarga', tema_label: 'Set Data Keluarga' })),
        ...metadataAnggota.map(item => ({ ...item, tema_key: 'anggota', tema_label: 'Set Data Anggota Keluarga' }))
    ];

    // State Variabel Terpilih (Set untuk efisiensi O(1) lookup)
    const initialArr = @json($initialSelected);
    let selectedSet = new Set(initialArr.map(s => String(s).trim()).filter(Boolean));

    // Tema yang sedang diterapkan (Set of 'keluarga' and/or 'anggota')
    let appliedThemes = new Set();
    let currentSearch = '';

    // Inisialisasi saat DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        dtsenUpdateSelectedUI();

        // Jika sudah ada data terpilih sebelumnya (misal di halaman Edit),
        // otomatis cek tema mana yang memuat variabel tersebut dan terapkan langsung
        if (selectedSet.size > 0) {
            let hasKeluarga = false;
            let hasAnggota = false;

            selectedSet.forEach(vName => {
                const found = allDataset.find(item => item.nama_layanan === vName);
                if (found) {
                    if (found.tema_key === 'keluarga') hasKeluarga = true;
                    if (found.tema_key === 'anggota') hasAnggota = true;
                }
            });

            // Jika tidak terpetakan khusus, centang keduanya agar semua variabel muncul
            if (!hasKeluarga && !hasAnggota) {
                hasKeluarga = true;
                hasAnggota = true;
            }

            const chkKeluarga = document.getElementById('dtsenChkTemaKeluarga');
            const chkAnggota = document.getElementById('dtsenChkTemaAnggota');
            if (chkKeluarga) chkKeluarga.checked = hasKeluarga;
            if (chkAnggota) chkAnggota.checked = hasAnggota;

            // Terapkan otomatis
            dtsenApplyThemes(true);
        }
    });

    // Handler Tombol "Terapkan"
    window.dtsenApplyThemes = function(isAutoInit = false) {
        const chkKeluarga = document.getElementById('dtsenChkTemaKeluarga');
        const chkAnggota = document.getElementById('dtsenChkTemaAnggota');
        const feedbackEl = document.getElementById('dtsenTemaFeedback');

        const newThemes = new Set();
        if (chkKeluarga && chkKeluarga.checked) newThemes.add('keluarga');
        if (chkAnggota && chkAnggota.checked) newThemes.add('anggota');

        const emptyState = document.getElementById('dtsenEmptyState');
        const activePanel = document.getElementById('dtsenActivePanel');
        const quickActions = document.getElementById('dtsenQuickActions');

        if (newThemes.size === 0) {
            appliedThemes.clear();
            if (emptyState) emptyState.style.display = 'flex';
            if (activePanel) activePanel.style.display = 'none';
            if (quickActions) quickActions.style.display = 'none';

            if (!isAutoInit && feedbackEl) {
                feedbackEl.style.display = 'block';
                feedbackEl.style.color = '#dc2626';
                feedbackEl.innerText = 'Pilih minimal salah satu tema (Keluarga atau Anggota Keluarga).';
            }
            return;
        }

        appliedThemes = newThemes;
        if (feedbackEl) {
            feedbackEl.style.display = 'none';
        }

        // Tampilkan kotak kanan aktif
        if (emptyState) emptyState.style.display = 'none';
        if (activePanel) activePanel.style.display = 'flex';
        if (quickActions) quickActions.style.display = 'flex';

        // Update Judul Tema Aktif
        const titleEl = document.getElementById('dtsenActiveThemeTitle');
        const subtitleEl = document.getElementById('dtsenActiveThemeSubtitle');
        
        if (appliedThemes.has('keluarga') && appliedThemes.has('anggota')) {
            if (titleEl) titleEl.innerText = 'Daftar Seluruh Variabel DTSEN';
            if (subtitleEl) subtitleEl.innerText = 'Menampilkan Set Data Keluarga (52) & Set Data Anggota Keluarga (48) — Total 100 Variabel';
        } else if (appliedThemes.has('keluarga')) {
            if (titleEl) titleEl.innerText = 'Daftar Variabel: Set Data Keluarga';
            if (subtitleEl) subtitleEl.innerText = 'Menampilkan 52 Atribut Variabel Rumah Tangga & Fasilitas Tempat Tinggal';
        } else if (appliedThemes.has('anggota')) {
            if (titleEl) titleEl.innerText = 'Daftar Variabel: Set Data Anggota Keluarga';
            if (subtitleEl) subtitleEl.innerText = 'Menampilkan 48 Atribut Variabel Individu, Demografi & Dukcapil';
        }

        dtsenRenderChecklist();
    };

    // Handler Pencarian Cepat
    window.dtsenOnSearchInput = function(keyword) {
        currentSearch = (keyword || '').toLowerCase().trim();
        if (appliedThemes.size > 0) {
            dtsenRenderChecklist();
        }
    };

    // Filter data sesuai tema & pencarian
    function getFilteredList() {
        if (appliedThemes.size === 0) return [];

        let list = allDataset.filter(item => appliedThemes.has(item.tema_key));

        if (currentSearch) {
            list = list.filter(item => {
                const s1 = (item.nama_layanan || '').toLowerCase();
                const s2 = (item.nama_variabel || '').toLowerCase();
                const s3 = (item.definisi || '').toLowerCase();
                return s1.includes(currentSearch) || s2.includes(currentSearch) || s3.includes(currentSearch);
            });
        }
        return list;
    }

    // Render Baris-baris Checklist di Kotak Kanan
    function dtsenRenderChecklist() {
        const container = document.getElementById('dtsenChecklistContainer');
        const countBadge = document.getElementById('dtsenVisibleCountBadge');
        if (!container) return;

        const visibleItems = getFilteredList();
        if (countBadge) countBadge.innerText = `${visibleItems.length} Item`;

        if (visibleItems.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 40px 10px; color: #94a3b8; font-size: 13px;">
                    Tidak ditemukan variabel yang cocok dengan pencarian "<strong>${escapeHtml(currentSearch)}</strong>".
                </div>
            `;
            return;
        }

        let html = '';
        visibleItems.forEach((item) => {
            const isChecked = selectedSet.has(item.nama_layanan);
            const cardClass = isChecked ? 'dtsen-var-card is-selected' : 'dtsen-var-card';
            const temaClass = item.tema_key === 'keluarga' ? 'dtsen-tema-keluarga' : 'dtsen-tema-anggota';
            const temaShort = item.tema_key === 'keluarga' ? 'Keluarga' : 'Anggota';

            html += `
                <div class="${cardClass}" onclick="dtsenCardClick(event, '${escapeHtml(item.nama_layanan)}')">
                    <input type="checkbox" 
                           class="dtsen-check-input" 
                           id="chk_var_${item.tema_key}_${item.no}" 
                           ${isChecked ? 'checked' : ''} 
                           onchange="dtsenCheckboxChange(event, '${escapeHtml(item.nama_layanan)}')">
                    
                    <div class="dtsen-var-info">
                        <div class="dtsen-var-topline">
                            <span class="dtsen-var-num">#${item.no}</span>
                            <span class="dtsen-badge-tema ${temaClass}">${temaShort}</span>
                            <span class="dtsen-var-label">${escapeHtml(item.nama_layanan)}</span>
                        </div>
                        
                        <div class="dtsen-var-meta-row">
                            <code class="dtsen-code-tag">${escapeHtml(item.nama_variabel || '-')}</code>
                            <span class="dtsen-type-tag">${escapeHtml(item.tipe_data || 'varchar')}</span>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    // Toggle klik card
    window.dtsenCardClick = function(event, varName) {
        // Jangan trigger dobel jika yang diklik langsung elemen input checkbox
        if (event.target.tagName.toLowerCase() === 'input') return;

        if (selectedSet.has(varName)) {
            selectedSet.delete(varName);
        } else {
            selectedSet.add(varName);
        }
        dtsenUpdateSelectedUI();
        dtsenRenderChecklist();
    };

    // Toggle dari checkbox langsung
    window.dtsenCheckboxChange = function(event, varName) {
        event.stopPropagation();
        if (event.target.checked) {
            selectedSet.add(varName);
        } else {
            selectedSet.delete(varName);
        }
        dtsenUpdateSelectedUI();
        dtsenRenderChecklist();
    };

    // Hapus satu variabel dari daftar chip di kotak kiri
    window.dtsenRemoveSelected = function(varName) {
        selectedSet.delete(varName);
        dtsenUpdateSelectedUI();
        if (appliedThemes.size > 0) {
            dtsenRenderChecklist();
        }
    };

    // Pilih semua yang tampil di filter tema saat ini
    window.dtsenSelectAllVisible = function() {
        const visible = getFilteredList();
        visible.forEach(item => selectedSet.add(item.nama_layanan));
        dtsenUpdateSelectedUI();
        dtsenRenderChecklist();
    };

    // Hapus centang semua yang tampil di filter tema saat ini
    window.dtsenUnselectAllVisible = function() {
        const visible = getFilteredList();
        visible.forEach(item => selectedSet.delete(item.nama_layanan));
        dtsenUpdateSelectedUI();
        dtsenRenderChecklist();
    };

    // Reset seluruh pilihan
    window.dtsenResetAllSelection = function() {
        if (confirm('Yakin ingin mengosongkan seluruh variabel yang telah dipilih?')) {
            selectedSet.clear();
            dtsenUpdateSelectedUI();
            if (appliedThemes.size > 0) {
                dtsenRenderChecklist();
            }
        }
    };

    // Perbarui Tampilan UI di Kotak Kiri & Input Hidden Form
    function dtsenUpdateSelectedUI() {
        const badge = document.getElementById('dtsenSelectedBadge');
        const chipsBox = document.getElementById('dtsenSelectedChips');
        const btnReset = document.getElementById('dtsenBtnResetAll');
        const hiddenInputs = document.getElementById('dtsenHiddenInputs');

        const totalSelected = selectedSet.size;
        if (badge) badge.innerText = `${totalSelected} Dipilih`;

        if (btnReset) {
            btnReset.style.display = totalSelected > 0 ? 'inline-block' : 'none';
        }

        // Render Chips di Kotak Kiri
        if (chipsBox) {
            if (totalSelected === 0) {
                chipsBox.innerHTML = `
                    <div class="dtsen-chips-empty">
                        Belum ada variabel yang dipilih.<br>
                        Centang tema data di atas lalu klik <strong>Terapkan</strong>, kemudian centang variabel pada kotak kanan.
                    </div>
                `;
            } else {
                let chipsHtml = '';
                selectedSet.forEach(v => {
                    chipsHtml += `
                        <span class="dtsen-chip-item">
                            <span>${escapeHtml(v)}</span>
                            <button type="button" class="dtsen-chip-del" onclick="dtsenRemoveSelected('${escapeHtml(v)}')" title="Hapus variabel ini">✕</button>
                        </span>
                    `;
                });
                chipsBox.innerHTML = chipsHtml;
            }
        }

        // Render Hidden Input name="variabel_dtsen[]"
        if (hiddenInputs) {
            let inputsHtml = '';
            selectedSet.forEach(v => {
                inputsHtml += `<input type="hidden" name="variabel_dtsen[]" value="${escapeHtml(v)}">\n`;
            });
            hiddenInputs.innerHTML = inputsHtml;
        }
    }

    // Helper sanitasi teks HTML
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
})();
</script>
