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
    <!-- Grid Dua Kotak (Kiri: Filter & Tema | Kanan: Daftar Variabel Checklist) -->
    <div class="dtsen-var-grid">
        
        <!-- ================================================================= -->
        <!-- KOTAK KIRI: Filter, Dropdown Tema, Pencarian & Ringkasan Terpilih -->
        <!-- ================================================================= -->
        <div class="dtsen-var-box dtsen-var-box-left">
            <div class="dtsen-box-header">
                <div class="dtsen-box-title">Filter & Kategori Data</div>
                <div class="dtsen-box-subtitle">Pilih jenis set data DTSEN untuk ditampilkan</div>
            </div>

            <!-- Dropdown Pemilihan Tema / Jenis Data -->
            <div class="dtsen-form-group">
                <label for="dtsenSelectTema" class="dtsen-field-label">
                    Pilih Tema Set Data <span style="color: #dc2626;">*</span>
                </label>
                <select id="dtsenSelectTema" class="dtsen-select-input" onchange="dtsenOnTemaChange(this.value)">
                    <option value="">-- Pilih Jenis/Tema Data --</option>
                    <option value="keluarga">🏠 1. Set Data Keluarga (52 Variabel)</option>
                    <option value="anggota">👤 2. Set Data Anggota Keluarga (48 Variabel)</option>
                    <option value="semua">📑 Gabungan / Seluruh Variabel (100 Variabel)</option>
                </select>
            </div>

            <!-- Pencarian Cepat Variabel -->
            <div class="dtsen-form-group" style="margin-top: 12px;">
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
                    ✓ Pilih Semua di Tema Ini
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

                <div class="dtsen-summary-desc">
                    Atribut data mikro DTSEN yang akan disertakan dalam permohonan resmi:
                </div>

                <!-- Wadah Chips Variabel Terpilih -->
                <div id="dtsenSelectedChips" class="dtsen-chips-container">
                    <div class="dtsen-chips-empty">
                        Belum ada variabel yang dipilih.<br>
                        Silakan pilih tema data di atas, lalu centang variabel pada kotak kanan.
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
            
            <!-- Keadaan 1: KOSONG (Sebelum Pengguna Memilih Tema di Kotak Kiri) -->
            <div id="dtsenEmptyState" class="dtsen-empty-state">
                <div class="dtsen-empty-icon-wrap">
                    📋
                </div>
                <h4 class="dtsen-empty-title">Daftar Variabel Masih Kosong</h4>
                <p class="dtsen-empty-text">
                    Silakan pilih jenis data pada menu dropdown <strong>Pilih Tema Set Data</strong> di kotak sebelah kiri 
                    (<em>Set Data Keluarga</em> atau <em>Set Data Anggota Keluarga</em>) untuk memunculkan daftar variabel 
                    yang siap Anda centang (checklist).
                </p>
            </div>

            <!-- Keadaan 2: AKTIF (Muncul Setelah Tema Dipilih) -->
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
<!-- STYLESHEET SCOPED UNTUK DUAL-BOX SELECTOR VARIABEL DTSEN                  -->
<!-- ========================================================================= -->
<style>
.dtsen-var-component {
    width: 100%;
    margin-top: 14px;
    font-family: inherit;
}
.dtsen-var-grid {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 20px;
    align-items: stretch;
}
@media (max-width: 960px) {
    .dtsen-var-grid {
        grid-template-columns: 1fr;
    }
}
.dtsen-var-box {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    display: flex;
    flex-direction: column;
}
.dtsen-var-box-left {
    background: #f8fafc;
    border-color: #cbd5e1;
}
.dtsen-var-box-right {
    min-height: 480px;
    position: relative;
}
.dtsen-box-header {
    margin-bottom: 16px;
}
.dtsen-box-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}
.dtsen-box-subtitle {
    font-size: 12px;
    color: #64748b;
    margin-top: 2px;
}
.dtsen-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.dtsen-field-label {
    font-size: 12.5px;
    font-weight: 700;
    color: #334155;
}
.dtsen-select-input {
    width: 100%;
    height: 44px;
    padding: 0 14px;
    border-radius: 10px;
    border: 1.5px solid #94a3b8;
    background: #ffffff;
    font-size: 13.5px;
    font-weight: 600;
    color: #0f172a;
    cursor: pointer;
    transition: all 0.2s ease;
    outline: none;
}
.dtsen-select-input:focus {
    border-color: #0b57d0;
    box-shadow: 0 0 0 3px rgba(11, 87, 208, 0.15);
}
.dtsen-search-wrap {
    position: relative;
    width: 100%;
}
.dtsen-text-input {
    width: 100%;
    height: 38px;
    padding: 0 12px 0 34px;
    border-radius: 8px;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    font-size: 13px;
    color: #1e293b;
    outline: none;
    transition: all 0.2s ease;
    box-sizing: border-box;
}
.dtsen-text-input:focus {
    border-color: #0b57d0;
    box-shadow: 0 0 0 3px rgba(11, 87, 208, 0.12);
}
.dtsen-search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 13px;
    color: #94a3b8;
    pointer-events: none;
}
.dtsen-actions-bar {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}
.dtsen-btn-action {
    flex: 1;
    padding: 7px 10px;
    font-size: 11.5px;
    font-weight: 600;
    border-radius: 8px;
    border: 1px solid #bfdbfe;
    background: #eff6ff;
    color: #1d4ed8;
    cursor: pointer;
    transition: all 0.15s ease;
    text-align: center;
}
.dtsen-btn-action:hover {
    background: #dbeafe;
    border-color: #93c5fd;
}
.dtsen-btn-action-ghost {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #475569;
}
.dtsen-btn-action-ghost:hover {
    background: #e2e8f0;
    color: #1e293b;
}
.dtsen-divider {
    border: none;
    border-top: 1px dashed #cbd5e1;
    margin: 16px 0;
}
.dtsen-summary-section {
    display: flex;
    flex-direction: column;
    flex: 1;
}
.dtsen-summary-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}
.dtsen-summary-title {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
}
.dtsen-count-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 999px;
    background: #0b57d0;
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    margin-left: 6px;
}
.dtsen-btn-reset {
    background: none;
    border: none;
    color: #dc2626;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: underline;
    padding: 0;
}
.dtsen-summary-desc {
    font-size: 11.5px;
    color: #64748b;
    margin-bottom: 10px;
    line-height: 1.4;
}
.dtsen-chips-container {
    max-height: 240px;
    overflow-y: auto;
    padding: 8px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    align-content: flex-start;
}
.dtsen-chips-empty {
    font-size: 12px;
    color: #94a3b8;
    text-align: center;
    padding: 18px 8px;
    width: 100%;
    line-height: 1.5;
}
.dtsen-chip-item {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 9px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    color: #1e40af;
    animation: dtsenFadeIn 0.15s ease-in;
}
.dtsen-chip-del {
    background: none;
    border: none;
    color: #64748b;
    cursor: pointer;
    font-size: 12px;
    font-weight: 700;
    padding: 0;
    line-height: 1;
    display: inline-flex;
    align-items: center;
}
.dtsen-chip-del:hover {
    color: #dc2626;
}

/* Kotak Kanan: Empty State */
.dtsen-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 60px 24px;
    flex: 1;
    margin: auto;
}
.dtsen-empty-icon-wrap {
    width: 76px;
    height: 76px;
    border-radius: 20px;
    background: #f1f5f9;
    border: 1.5px dashed #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
    margin-bottom: 16px;
}
.dtsen-empty-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 6px 0;
}
.dtsen-empty-text {
    font-size: 13px;
    color: #64748b;
    max-width: 440px;
    line-height: 1.6;
    margin: 0;
}

/* Kotak Kanan: Active Panel */
.dtsen-active-panel {
    display: flex;
    flex-direction: column;
    flex: 1;
}
.dtsen-active-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 12px;
    margin-bottom: 12px;
    border-bottom: 1.5px solid #f1f5f9;
}
.dtsen-active-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.dtsen-active-subtitle {
    font-size: 12px;
    color: #64748b;
    margin-top: 2px;
}
.dtsen-visible-badge {
    padding: 3px 10px;
    background: #f1f5f9;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
    border-radius: 20px;
}
.dtsen-checklist-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    max-height: 520px;
    overflow-y: auto;
    padding-right: 6px;
}
.dtsen-var-card {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 14px;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 11px;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
    user-select: none;
}
.dtsen-var-card:hover {
    border-color: #93c5fd;
    background: #f8fafc;
}
.dtsen-var-card.is-selected {
    border-color: #0b57d0;
    background: #f0f7ff;
    box-shadow: 0 0 0 1px #0b57d0 inset;
}
.dtsen-check-input {
    margin-top: 3px;
    width: 18px;
    height: 18px;
    accent-color: #0b57d0;
    cursor: pointer;
}
.dtsen-var-info {
    flex: 1;
    min-width: 0;
}
.dtsen-var-topline {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 4px;
}
.dtsen-var-num {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 5px;
}
.dtsen-var-label {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
}
.dtsen-badge-tema {
    font-size: 10.5px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 6px;
    text-transform: uppercase;
}
.dtsen-tema-keluarga {
    background: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
}
.dtsen-tema-anggota {
    background: #ede9fe;
    color: #6d28d9;
    border: 1px solid #ddd6fe;
}
.dtsen-var-meta-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 4px;
}
.dtsen-code-tag {
    font-family: 'JetBrains Mono', Consolas, monospace;
    font-size: 11.5px;
    color: #0369a1;
    background: #f0f9ff;
    padding: 1px 6px;
    border-radius: 4px;
    border: 1px solid #e0f2fe;
}
.dtsen-type-tag {
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
}
.dtsen-var-desc {
    font-size: 12px;
    color: #475569;
    margin-top: 4px;
    line-height: 1.4;
}
.dtsen-var-notes {
    font-size: 11px;
    color: #64748b;
    background: #f8fafc;
    border-left: 3px solid #cbd5e1;
    padding: 4px 8px;
    margin-top: 6px;
    border-radius: 0 4px 4px 0;
    line-height: 1.35;
    white-space: pre-line;
}
@keyframes dtsenFadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
</style>

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
        ...metadataAnggota.map(item => ({ ...item, tema_key: 'anggota', tema_label: 'Set Data Anggota' }))
    ];

    // State Variabel Terpilih (Set untuk efisiensi O(1) lookup)
    const initialArr = @json($initialSelected);
    let selectedSet = new Set(initialArr.map(s => String(s).trim()).filter(Boolean));

    let currentTema = '';
    let currentSearch = '';

    // Inisialisasi saat DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        dtsenUpdateSelectedUI();

        // Jika sudah ada data terpilih sebelumnya (misal di halaman Edit),
        // otomatis set dropdown tema ke 'semua' agar pengguna langsung bisa melihat & mengubah checklist
        if (selectedSet.size > 0) {
            const selectEl = document.getElementById('dtsenSelectTema');
            if (selectEl) {
                selectEl.value = 'semua';
                dtsenOnTemaChange('semua');
            }
        }
    });

    // Handler Perubahan Dropdown Tema
    window.dtsenOnTemaChange = function(tema) {
        currentTema = tema;
        const emptyState = document.getElementById('dtsenEmptyState');
        const activePanel = document.getElementById('dtsenActivePanel');
        const quickActions = document.getElementById('dtsenQuickActions');

        if (!tema) {
            // Kotak kanan KOSONG jika belum ada tema yang dipilih
            if (emptyState) emptyState.style.display = 'flex';
            if (activePanel) activePanel.style.display = 'none';
            if (quickActions) quickActions.style.display = 'none';
            return;
        }

        // Tampilkan kotak kanan aktif
        if (emptyState) emptyState.style.display = 'none';
        if (activePanel) activePanel.style.display = 'flex';
        if (quickActions) quickActions.style.display = 'flex';

        // Update Judul Tema Aktif
        const titleEl = document.getElementById('dtsenActiveThemeTitle');
        const subtitleEl = document.getElementById('dtsenActiveThemeSubtitle');
        if (tema === 'keluarga') {
            if (titleEl) titleEl.innerText = 'Daftar Variabel: Set Data Keluarga';
            if (subtitleEl) subtitleEl.innerText = 'Total 52 Atribut Variabel Rumah Tangga & Fasilitas';
        } else if (tema === 'anggota') {
            if (titleEl) titleEl.innerText = 'Daftar Variabel: Set Data Anggota Keluarga';
            if (subtitleEl) subtitleEl.innerText = 'Total 48 Atribut Variabel Individu, Demografi & Dukcapil';
        } else {
            if (titleEl) titleEl.innerText = 'Daftar Seluruh Variabel DTSEN';
            if (subtitleEl) subtitleEl.innerText = 'Total 100 Atribut Variabel (Keluarga & Anggota)';
        }

        dtsenRenderChecklist();
    };

    // Handler Pencarian Cepat
    window.dtsenOnSearchInput = function(keyword) {
        currentSearch = (keyword || '').toLowerCase().trim();
        if (currentTema) {
            dtsenRenderChecklist();
        }
    };

    // Filter data sesuai tema & pencarian
    function getFilteredList() {
        let list = allDataset;
        if (currentTema === 'keluarga') {
            list = list.filter(item => item.tema_key === 'keluarga');
        } else if (currentTema === 'anggota') {
            list = list.filter(item => item.tema_key === 'anggota');
        }

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
        visibleItems.forEach((item, idx) => {
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

                        ${item.definisi ? `<div class="dtsen-var-desc">${escapeHtml(item.definisi)}</div>` : ''}
                        ${item.keterangan ? `<div class="dtsen-var-notes">${escapeHtml(item.keterangan)}</div>` : ''}
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
        if (currentTema) {
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
            if (currentTema) {
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
                        Silakan pilih tema data di atas, lalu centang variabel pada kotak kanan.
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
