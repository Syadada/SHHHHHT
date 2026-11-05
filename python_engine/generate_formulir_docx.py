#!/usr/bin/env python3
"""
SIMONEV / DTSEN Python Formulir Generator
Mengisi template resmi 'Lampiran I - Format Formulir Pemenuhan Permintaan Layanan Bagi Pakai DTSEN v1.0.docx'
berdasarkan input tersimpan dari database SQLite.
"""

import os
import sys
import json
import sqlite3
from datetime import datetime
import docx

def get_base_dir():
    return os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

def format_date_indo(dt_val):
    if not dt_val:
        return "-"
    if isinstance(dt_val, str):
        try:
            dt_val = datetime.strptime(dt_val[:10], "%Y-%m-%d")
        except Exception:
            return dt_val
    bulan_indo = [
        "", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ]
    return f"{dt_val.day} {bulan_indo[dt_val.month]} {dt_val.year}"

def generate_formulir(ticket_id):
    base_dir = get_base_dir()
    db_path = os.path.join(base_dir, "database", "database.sqlite")
    template_path = os.path.join(base_dir, "Lampiran I - Format Formulir Pemenuhan Permintaan Layanan Bagi Pakai DTSEN v1.0.docx")

    if not os.path.exists(template_path):
        return {"success": False, "error": f"Template tidak ditemukan di: {template_path}"}

    if not os.path.exists(db_path):
        return {"success": False, "error": f"Database SQLite tidak ditemukan di: {db_path}"}

    # Query tiket data
    conn = sqlite3.connect(db_path)
    conn.row_factory = sqlite3.Row
    cursor = conn.cursor()

    cursor.execute("SELECT * FROM tiket_dtsens WHERE id = ? LIMIT 1", (ticket_id,))
    row = cursor.fetchone()
    if not row:
        cursor.execute("SELECT * FROM tiket_dtsens WHERE nomor_tiket = ? LIMIT 1", (str(ticket_id),))
        row = cursor.fetchone()

    if not row:
        conn.close()
        return {"success": False, "error": f"Tiket dengan ID/Nomor {ticket_id} tidak ditemukan."}

    tiket = dict(row)
    conn.close()

    # Parse JSON fields
    sop_extra = {}
    if tiket.get("data_sop_extra"):
        try:
            sop_extra = json.loads(tiket["data_sop_extra"])
        except Exception:
            sop_extra = {}

    # Format dates
    created_at_str = tiket.get("created_at") or datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    try:
        dt_created = datetime.strptime(created_at_str[:19], "%Y-%m-%d %H:%M:%S")
    except Exception:
        dt_created = datetime.now()

    tgl_permintaan_str = format_date_indo(tiket.get("tgl_permintaan")) if tiket.get("tgl_permintaan") else format_date_indo(dt_created)
    tgl_diterima_str = format_date_indo(tiket.get("tgl_diterima")) if tiket.get("tgl_diterima") else tgl_permintaan_str
    tgl_surat_tugas_str = format_date_indo(tiket.get("tgl_surat_tugas")) if tiket.get("tgl_surat_tugas") else tgl_permintaan_str
    tgl_mulai_str = format_date_indo(tiket.get("tgl_mulai")) if tiket.get("tgl_mulai") else tgl_permintaan_str
    
    step5_str = tiket.get("step5_at")
    dt_step5 = None
    if step5_str:
        try:
            dt_step5 = datetime.strptime(step5_str[:19], "%Y-%m-%d %H:%M:%S")
        except Exception:
            pass
    tgl_selesai_str = format_date_indo(tiket.get("tgl_selesai")) if tiket.get("tgl_selesai") else format_date_indo(dt_step5 or dt_created)

    waktu_mulai = f"{tgl_mulai_str}; {dt_created.strftime('%H:%M')}"
    waktu_selesai = f"{tgl_selesai_str}; {(dt_step5 or dt_created).strftime('%H:%M')}"

    # Extract all fields directly based on inputs
    nomor_naskah = tiket.get("nomor_naskah") or "555/07-SOP/DISKOMINFO SP/2026"
    nomor_tiket = tiket.get("nomor_tiket") or f"TIK-DTSEN-{dt_created.strftime('%Y%m%d')}-0001"
    nama_instansi = tiket.get("nama_instansi") or "Perangkat Daerah Pemohon"
    nama_pic = tiket.get("nama_pemohon") or f"PIC {nama_instansi}"
    kontak_pic = tiket.get("kontak_pemohon") or "-"
    no_se = tiket.get("no_surat_edaran") or "-"
    agenda_disposisi = tiket.get("no_agenda_disposisi") or f"DSP-{dt_created.year}-{str(tiket.get('id', 1)).zfill(4)}"
    surat_tugas_no = tiket.get("no_surat_tugas") or f"ST.090/182/DISKOMINFO SP/{dt_created.year}"
    
    deskripsi = tiket.get("deskripsi_pekerjaan") or f"Memenuhi permintaan layanan bagi pakai data mikro DTSEN dari Walidata ke {nama_instansi}."
    penanggung_jawab = tiket.get("penanggung_jawab") or "Chobir Sabbaha, S.Kom., M.T."
    nip_penanggung_jawab = tiket.get("nip_penanggung_jawab") or "19780218 200501 1 009"
    tempat = tiket.get("tempat_pelaksanaan") or "Gedung Diskominfo SP Provinsi Banten (KP3B Serang)"
    pelaksana_1 = tiket.get("pelaksana_1") or "Tia Fitria Saumi, S.Kom."
    nip_pelaksana_1 = tiket.get("nip_pelaksana_1") or "19930812 202012 2 008"
    jabatan_pelaksana_1 = tiket.get("jabatan_pelaksana_1") or "Pranata Komputer Ahli Muda"
    pelaksana_2 = tiket.get("pelaksana_2") or "Ahmad Fauzi, S.T."
    nip_pelaksana_2 = tiket.get("nip_pelaksana_2") or "19890514 201503 1 004"
    jabatan_pelaksana_2 = tiket.get("jabatan_pelaksana_2") or "Statistisi Ahli Pertama"
    pelaksana_3 = tiket.get("pelaksana_3") or "-"
    pelaksana_4 = tiket.get("pelaksana_4") or "-"
    sop_rujukan = tiket.get("sop_rujukan") or "555/07-SOP/DISKOMINFO SP/2026"

    pelaksana_opd_1 = tiket.get("pelaksana_opd_1") or tiket.get("nama_narahubung") or tiket.get("nama_pemohon") or "-"
    nip_pelaksana_opd_1 = tiket.get("nip_pelaksana_opd_1") or tiket.get("nip_narahubung") or tiket.get("nip_pemohon") or "-"
    jabatan_pelaksana_opd_1 = tiket.get("jabatan_pelaksana_opd_1") or tiket.get("jabatan_narahubung") or "Narahubung / PIC Hadir"
    keterangan_kehadiran = tiket.get("keterangan_kehadiran") or "-"
    
    catatan_admin = tiket.get("catatan_admin") or "-"
    hasil_pekerjaan = tiket.get("hasil_pekerjaan") or f"Pelaksana telah melaksanakan pemenuhan permintaan Layanan Bagi Pakai DTSEN ke {nama_instansi} sesuai dengan penugasan pimpinan dan SOP {sop_rujukan}."

    # Output directory
    output_dir = os.path.join(base_dir, "storage", "app", "public", "formulir_generated")
    os.makedirs(output_dir, exist_ok=True)

    clean_nomor = nomor_tiket.replace("/", "-")
    output_filename = f"Formulir_Pemenuhan_Bagi_Pakai_{clean_nomor}.docx"
    output_path = os.path.join(output_dir, output_filename)

    # Load template docx
    doc = docx.Document(template_path)

    # Map text replacements
    replacements = {
        "{Penomoran naskah internal bidang}": nomor_naskah,
        "{Contoh: REQ-DTSEN-90001}": nomor_tiket,
        "{Contoh: Dinas Sosial Provinsi Banten}": nama_instansi,
        "{Nama lengkap narahubung}": nama_pic,
        "{0812-1234-5678}": kontak_pic,
        "{Contoh: B-000.1/123/DINSOS/2026}": no_se,
        "{Contoh: 00001}": agenda_disposisi,
        "{Contoh: Memenuhi permintaan layanan bagi pakai DTSEN dari walidata ke Dinas Sosial Provinsi Banten.}": deskripsi,
        "{Nama Lengkap Pejabat Administrator. Contoh: Chobir Sabbaha}": penanggung_jawab,
        "{Contoh: Gedung Diskominfo SP Provinsi Banten}": tempat,
        "{Nama Lengkap Pelaksana. Contoh: Tia Fitria Saumi}": pelaksana_1,
        "{Nama lengkap pelaksana. Dapat diisi lebih dari 1 (satu) pelaksana sesuai kebutuhan.}": f"{pelaksana_1} / {pelaksana_2}",
        "{20 Oktober 2026; 13:20}": waktu_mulai,
        "{20 Oktober 2026; 14:15}": waktu_selesai,
        "{Contoh: Kamis, 15 Oktober 2026.}": tgl_permintaan_str,
        "{Berikan catatan jika ada. Jika tidak ada, isi dengan tanda strip (-).}": catatan_admin,
        "{Contoh: Pelaksana telah melaksanakan pemenuhan permintaan Layanan Bagi Pakai DTSEN ke Dinas/Badan  sesuai dengan penugasan pimpinan.}": hasil_pekerjaan,
        "{NIP Pejabat Administrator}": nip_penanggung_jawab,
        "{NIP Pelaksana 1}": nip_pelaksana_1,
        "{Jabatan Pelaksana 1}": jabatan_pelaksana_1,
        "{Nama Lengkap Pelaksana 2}": pelaksana_2,
        "{NIP Pelaksana 2}": nip_pelaksana_2,
        "{Jabatan Pelaksana 2}": jabatan_pelaksana_2,
        "{Nama Lengkap PIC OPD}": pelaksana_opd_1,
        "{NIP PIC OPD}": nip_pelaksana_opd_1,
        "{Jabatan PIC OPD}": jabatan_pelaksana_opd_1,
        "{Keterangan Kehadiran}": keterangan_kehadiran
    }

    # Iterate through tables and replace
    for tbl in doc.tables:
        for row in tbl.rows:
            for cell in row.cells:
                for p in cell.paragraphs:
                    for k, v in replacements.items():
                        if k in p.text:
                            p.text = p.text.replace(k, v)

    # Fill specific date cells in Table 3 (Tanggal Permintaan & Diterima)
    if len(doc.tables) > 3:
        t3 = doc.tables[3]
        if len(t3.rows) > 2 and len(t3.rows[2].cells) > 3:
            t3.rows[2].cells[1].text = tgl_permintaan_str
            t3.rows[2].cells[3].text = tgl_diterima_str

    # Fill Table 4 (Surat Tugas)
    if len(doc.tables) > 4:
        t4 = doc.tables[4]
        if len(t4.rows) > 1 and len(t4.rows[0].cells) > 1:
            t4.rows[0].cells[1].text = surat_tugas_no
            t4.rows[1].cells[1].text = tgl_surat_tugas_str

    # Fill Table 5 (Deskripsi, Pelaksana 2, 3, 4 & SOP)
    if len(doc.tables) > 5:
        t5 = doc.tables[5]
        if len(t5.rows) > 4:
            if len(t5.rows[2].cells) > 3:
                t5.rows[2].cells[1].text = tgl_mulai_str
                t5.rows[2].cells[3].text = pelaksana_2
            if len(t5.rows[3].cells) > 3:
                t5.rows[3].cells[1].text = tgl_selesai_str
                t5.rows[3].cells[3].text = pelaksana_3
            if len(t5.rows[4].cells) > 3:
                t5.rows[4].cells[1].text = sop_rujukan
                t5.rows[4].cells[3].text = pelaksana_4

    # Save to output path
    doc.save(output_path)

    return {
        "success": True,
        "path": output_path,
        "filename": output_filename,
        "nomor_tiket": nomor_tiket,
        "instansi": nama_instansi
    }

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"success": False, "error": "Argumen ID Tiket diperlukan"}))
        sys.exit(1)

    t_id = sys.argv[1]
    res = generate_formulir(t_id)
    print(json.dumps(res))
