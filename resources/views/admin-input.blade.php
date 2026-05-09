<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Input Data Availability - Admin</title>
  <link rel="stylesheet" href="css/style.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    /* ===== UPLOAD SECTION ===== */
    .upload-section {
      margin-top: 32px;
      border-top: 2px dashed #bfdbfe;
      padding-top: 28px;
    }

    .upload-dropzone {
      border: 2.5px dashed #93c5fd;
      border-radius: 16px;
      background: #f0f7ff;
      padding: 40px 24px;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s ease;
      position: relative;
    }

    .upload-dropzone:hover,
    .upload-dropzone.dragover {
      border-color: #2563eb;
      background: #dbeafe;
    }

    .upload-dropzone input[type="file"] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
      width: 100%;
      height: 100%;
    }

    .upload-icon { font-size: 48px; margin-bottom: 12px; }
    .upload-label { font-size: 16px; font-weight: 700; color: #1d4ed8; margin-bottom: 6px; }
    .upload-sublabel { font-size: 13px; color: #64748b; }
    .upload-filename { margin-top: 14px; font-size: 13px; font-weight: 700; color: #15803d; display: none; }

    /* ===== PARSING OVERLAY ===== */
    .parsing-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.5);
      z-index: 3000;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 16px;
    }
    .parsing-overlay.show { display: flex; }
    .parsing-spinner {
      width: 52px; height: 52px;
      border: 5px solid #bfdbfe;
      border-top-color: #2563eb;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }
    .parsing-text { color: #fff; font-size: 16px; font-weight: 700; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ===== MODAL KONFIRMASI ===== */
    .import-modal {
      display: none;
      position: fixed;
      z-index: 2000;
      inset: 0;
      background: rgba(0,0,0,0.55);
      backdrop-filter: blur(4px);
      overflow-y: auto;
      padding: 32px 16px;
    }

    .import-modal-content {
      background: #fff;
      margin: 0 auto;
      border-radius: 18px;
      width: 100%;
      max-width: 1080px;
      box-shadow: 0 16px 40px rgba(0,0,0,0.2);
      overflow: hidden;
    }

    .import-modal-header {
      background: #1d4ed8;
      color: #fff;
      padding: 20px 28px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .import-modal-header h2 { font-size: 18px; font-weight: 800; margin: 0; }
    .import-modal-header p  { font-size: 13px; opacity: 0.85; margin: 4px 0 0; }

    .import-close-btn {
      background: rgba(255,255,255,0.2);
      border: none; color: #fff;
      font-size: 20px; width: 36px; height: 36px;
      border-radius: 50%; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
    }

    .import-modal-body { padding: 24px 28px; }

    .import-summary {
      display: flex; gap: 16px; margin-bottom: 20px; flex-wrap: wrap;
    }
    .import-summary-item {
      background: #f0f7ff; border: 1px solid #bfdbfe;
      border-radius: 10px; padding: 12px 20px; text-align: center; min-width: 120px;
    }
    .import-summary-item .val { font-size: 24px; font-weight: 900; color: #1d4ed8; }
    .import-summary-item .lbl { font-size: 12px; color: #64748b; margin-top: 2px; }

    .import-table-wrap {
      overflow-x: auto; max-height: 380px;
      border: 1px solid #e5e7eb; border-radius: 10px;
    }
    .import-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .import-table thead th {
      background: #1e3a8a; color: #fff;
      padding: 10px 12px; text-align: left;
      white-space: nowrap; position: sticky; top: 0;
    }
    .import-table tbody tr:nth-child(even) { background: #f8fafc; }
    .import-table tbody td { padding: 9px 12px; border-bottom: 1px solid #f1f5f9; white-space: nowrap; }
    .badge-normal  { background:#dcfce7; color:#15803d; padding:2px 8px; border-radius:20px; font-weight:700; font-size:11px; }
    .badge-warning { background:#fee2e2; color:#dc2626; padding:2px 8px; border-radius:20px; font-weight:700; font-size:11px; }
    .badge-zero    { background:#f1f5f9; color:#94a3b8; padding:2px 8px; border-radius:20px; font-size:11px; }

    .import-progress {
      display: none; padding: 16px 28px;
      background: #f0f7ff; border-top: 1px solid #bfdbfe;
    }
    .import-progress-bar-wrap {
      background: #dbeafe; border-radius: 99px; height: 10px; overflow: hidden; margin-bottom: 8px;
    }
    .import-progress-bar { height: 100%; background: #2563eb; border-radius: 99px; width: 0%; transition: width 0.3s ease; }
    .import-progress-text { font-size: 13px; color: #1d4ed8; font-weight: 600; }

    .import-modal-footer {
      padding: 20px 28px; border-top: 1px solid #e5e7eb;
      display: flex; justify-content: flex-end; gap: 12px; flex-wrap: wrap;
    }
  </style>
</head>
<body>

  <div class="dashboard-app">
    <aside class="sidebar">
      <div class="sidebar-top">
        <img src="img/Logo wikon.png" alt="Logo wikon" class="sidebar-logo" />
      </div>
      <nav class="sidebar-menu">
        <a href="/admin" class="menu-link">Dashboard</a>
        <a href="/admin-input" class="menu-link active">Input Data</a>
        <a href="/admin-reports" class="menu-link">Laporan</a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;padding:0;">
  @csrf
  <button type="submit" class="menu-link" style="background:none;border:none;cursor:pointer;font-family:inherit;font-size:inherit;display:block;width:100%;">Logout</button>
</form>
      </nav>
    </aside>

    <main class="main-content">
      <header class="main-header">
        <div>
          <h1>Input Data Availability</h1>
          <p>Form input data availability mesin untuk admin</p>
        </div>
        <div class="header-right">
          <span class="role-pill">Admin</span>
        </div>
      </header>

      <section class="panel">
        <div class="panel-header">
          <h2>Form Input Data</h2>
          <span>Periode bulanan</span>
        </div>

        <!-- ===== FORM MANUAL ===== -->
        <form class="admin-form-grid" id="inputForm">
          <div class="form-group">
            <label for="bulan">Bulan</label>
            <select id="bulan">
              <option value="">-- Pilih Bulan --</option>
              <option>Januari</option><option>Februari</option><option>Maret</option>
              <option>April</option><option>Mei</option><option>Juni</option>
              <option>Juli</option><option>Agustus</option><option>September</option>
              <option>Oktober</option><option>November</option><option>Desember</option>
            </select>
          </div>

          <div class="form-group">
            <label for="tahun">Tahun</label>
            <input id="tahun" type="number" value="2026" placeholder="Masukkan tahun" />
          </div>

          <div class="form-group">
            <label for="plant">Plant</label>
            <select id="plant">
              <option value="">-- Pilih Plant --</option>
              <option value="DC">DC - Die Casting</option>
              <option value="GC">GC - Gravity Casting</option>
              <option value="SB">SB - Sand Blowing</option>
              <option value="CNC">CNC - CNC</option>
            </select>
          </div>

          <div class="form-group">
            <label for="kodeMesin">Kode Mesin</label>
            <select id="kodeMesin">
              <option value="">-- Pilih Plant Dulu --</option>
            </select>
          </div>

          <div class="form-group">
            <label for="loadingTime">Loading Time (Jam)</label>
            <input id="loadingTime" type="number" step="0.01" placeholder="Contoh: 320" />
          </div>

          <div class="form-group">
            <label for="operatingTime">Operating Time (Jam)</label>
            <input id="operatingTime" type="number" step="0.01" placeholder="Contoh: 278" />
          </div>

          <div class="form-group">
            <label for="breakdownTime">Breakdown Time (Jam)</label>
            <input id="breakdownTime" type="number" step="0.01" placeholder="Contoh: 42" />
          </div>

          <div class="form-group">
            <label for="freqBreakdown">Frekuensi Breakdown</label>
            <input id="freqBreakdown" type="number" placeholder="Contoh: 2" />
          </div>

          <div class="form-group full-width">
            <label for="masalah">Masalah / Hambatan</label>
            <textarea id="masalah" rows="4" placeholder="Masukkan masalah / hambatan"></textarea>
          </div>

          <div class="form-group full-width">
            <label for="langkahPerbaikan">Langkah Perbaikan</label>
            <textarea id="langkahPerbaikan" rows="4" placeholder="Masukkan langkah perbaikan"></textarea>
          </div>

          <div class="form-group full-width">
            <label for="langkahPencegahan">Langkah Pencegahan</label>
            <textarea id="langkahPencegahan" rows="4" placeholder="Masukkan langkah pencegahan"></textarea>
          </div>
        </form>

        <!-- ===== KALKULASI OTOMATIS ===== -->
        <div class="panel-header" style="margin-top: 10px;">
          <h2>Hasil Perhitungan Otomatis</h2>
          <span>Preview rumus</span>
        </div>

        <div class="report-summary-grid">
          <div class="panel mini-panel center">
            <h3>Availability</h3>
            <div class="big-value small-big" id="calcAvailability">0.00</div>
          </div>
          <div class="panel mini-panel center">
            <h3>MTBF</h3>
            <div class="big-value small-big" id="calcMTBF">0</div>
          </div>
        </div>
        <div class="report-summary-grid">
          <div class="panel mini-panel center">
            <h3>MTTR</h3>
            <div class="big-value small-big" id="calcMTTR">0</div>
          </div>
          <div class="panel mini-panel center">
            <h3>Status</h3>
            <div class="big-value small-big" id="calcStatus">-</div>
          </div>
        </div>

        <!-- ===== TOMBOL FORM MANUAL ===== -->
        <div class="admin-action-row">
          <button class="btn-login admin-btn-secondary" type="button" id="btnReset">Reset</button>
          <button class="btn-login admin-btn-primary" type="button" id="btnSimpan">Simpan ke Database</button>
        </div>

        <!-- ===== UPLOAD / DROP FILE ===== -->
        <div class="upload-section">
          <div class="panel-header" style="margin-bottom: 16px;">
            <h2>atau Import dari File</h2>
            <span>Format: .xls, .xlsx, .csv — sheet "GRAFIK CNC" / "GRAFIK GM, DC & SB"</span>
          </div>

          <div class="upload-dropzone" id="dropzone">
            <input type="file" id="fileInput" accept=".xls,.xlsx,.csv" />
            <div class="upload-icon">📂</div>
            <div class="upload-label">Klik untuk Upload atau Drop File di Sini</div>
            <div class="upload-sublabel">Mendukung .xls, .xlsx, .csv &nbsp;•&nbsp; Maks. 10MB</div>
            <div class="upload-filename" id="uploadFilename"></div>
          </div>
        </div>

      </section>
    </main>
  </div>

  <!-- ===== OVERLAY PARSING ===== -->
  <div class="parsing-overlay" id="parsingOverlay">
    <div class="parsing-spinner"></div>
    <div class="parsing-text">Membaca file, mohon tunggu...</div>
  </div>

  <!-- ===== MODAL KONFIRMASI IMPORT ===== -->
  <div class="import-modal" id="importModal">
    <div class="import-modal-content">
      <div class="import-modal-header">
        <div>
          <h2>Konfirmasi Import Data</h2>
          <p id="importModalSubtitle">Periksa data sebelum disimpan ke database</p>
        </div>
        <button class="import-close-btn" onclick="closeImportModal()">✕</button>
      </div>

      <div class="import-modal-body">
        <div class="import-summary">
          <div class="import-summary-item">
            <div class="val" id="summaryTotal">0</div>
            <div class="lbl">Total Baris</div>
          </div>
          <div class="import-summary-item">
            <div class="val" id="summarySheet">-</div>
            <div class="lbl">Sheet Dibaca</div>
          </div>
          <div class="import-summary-item">
            <div class="val" id="summaryBulan">-</div>
            <div class="lbl">Bulan</div>
          </div>
          <div class="import-summary-item">
            <div class="val" id="summaryTahun">-</div>
            <div class="lbl">Tahun</div>
          </div>
        </div>

        <div class="import-table-wrap">
          <table class="import-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Kode Mesin</th>
                <th>Plant</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Loading (Jam)</th>
                <th>Operating (Jam)</th>
                <th>Breakdown (Jam)</th>
                <th>Freq.</th>
                <th>MTBF</th>
                <th>MTTR</th>
                <th>Availability (%)</th>
                <th>Status</th>
                <th>Masalah</th>
                <th>Perbaikan</th>
                <th>Pencegahan</th>
              </tr>
            </thead>
            <tbody id="importTableBody"></tbody>
          </table>
        </div>
      </div>

      <div class="import-progress" id="importProgress">
        <div class="import-progress-bar-wrap">
          <div class="import-progress-bar" id="importProgressBar"></div>
        </div>
        <div class="import-progress-text" id="importProgressText">Menyimpan data...</div>
      </div>

      <div class="import-modal-footer">
        <button class="btn-login admin-btn-secondary" id="btnBatal" onclick="closeImportModal()">Batal</button>
        <button class="btn-login admin-btn-primary" id="btnImportSubmit" onclick="submitImport()">
          Simpan Semua ke Database
        </button>
      </div>
    </div>
  </div>

  <!-- SheetJS: baca XLS/XLSX/CSV di browser tanpa backend -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

  <script>
    // ========================================================
    // FORM MANUAL
    // ========================================================
    const machineConfig   = { "DC": 8, "GC": 10, "SB": 7, "CNC": 32 };
    const plantSelect     = document.getElementById("plant");
    const kodeMesinSelect = document.getElementById("kodeMesin");

    plantSelect.addEventListener("change", function () {
      kodeMesinSelect.innerHTML = '<option value="">-- Pilih Mesin --</option>';
      if (machineConfig[this.value]) {
        for (let i = 1; i <= machineConfig[this.value]; i++) {
          const num = i < 10 ? '0' + i : String(i);
          const opt = document.createElement("option");
          opt.value = opt.textContent = `${this.value} ${num}`;
          kodeMesinSelect.appendChild(opt);
        }
      } else {
        kodeMesinSelect.innerHTML = '<option value="">-- Pilih Plant Dulu --</option>';
      }
    });

    const elLoading   = document.getElementById("loadingTime");
    const elOperating = document.getElementById("operatingTime");
    const elBreakdown = document.getElementById("breakdownTime");
    const elFreq      = document.getElementById("freqBreakdown");
    const elAvail     = document.getElementById("calcAvailability");
    const elMTBF      = document.getElementById("calcMTBF");
    const elMTTR      = document.getElementById("calcMTTR");
    const elStatus    = document.getElementById("calcStatus");

    function calculateMetrics() {
      const loading   = parseFloat(elLoading.value)   || 0;
      const operating = parseFloat(elOperating.value) || 0;
      const breakdown = parseFloat(elBreakdown.value) || 0;
      const freq      = parseFloat(elFreq.value)      || 0;
      const avail     = loading > 0 ? ((loading - breakdown) / loading) * 100 : 0;
      const mtbf      = freq > 0 ? operating / freq : 0;
      const mttr      = freq > 0 ? breakdown / freq : 0;

      elAvail.textContent = avail.toFixed(2);
      elMTBF.textContent  = mtbf.toFixed(2);
      elMTTR.textContent  = mttr.toFixed(2);

      if (loading === 0 && operating === 0) {
        elStatus.textContent = "-"; elStatus.style.color = "#1d4ed8";
      } else if (avail >= 98) {
        elStatus.textContent = "Normal"; elStatus.style.color = "#16a34a";
      } else {
        elStatus.textContent = "Warning"; elStatus.style.color = "#dc2626";
      }
    }

    [elLoading, elOperating, elBreakdown, elFreq].forEach(el =>
      el.addEventListener("input", calculateMetrics)
    );

    document.getElementById("btnReset").addEventListener("click", () => {
      document.getElementById("inputForm").reset();
      kodeMesinSelect.innerHTML = '<option value="">-- Pilih Plant Dulu --</option>';
      calculateMetrics();
    });

    document.getElementById("btnSimpan").addEventListener("click", () => {
      if (!plantSelect.value || !kodeMesinSelect.value || !elLoading.value || !elOperating.value) {
        alert("Lengkapi data Plant, Mesin, dan Jam Operasional dulu ya.");
        return;
      }
      const btn = document.getElementById("btnSimpan");
      const ori = btn.textContent;
      btn.textContent = "Menyimpan...";
      btn.disabled = true;

      fetch('/api/machines/store', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          bulan:               document.getElementById("bulan").value,
          tahun:               document.getElementById("tahun").value,
          plant:               plantSelect.value,
          kode_mesin:          kodeMesinSelect.value,
          loading_time:        elLoading.value,
          operating_time:      elOperating.value,
          breakdown_time:      elBreakdown.value || 0,
          freq_breakdown:      elFreq.value || 0,
          masalah:             document.getElementById("masalah").value,
          langkah_perbaikan:   document.getElementById("langkahPerbaikan").value,
          langkah_pencegahan:  document.getElementById("langkahPencegahan").value,
          availability:        elAvail.textContent,
          mtbf:                elMTBF.textContent,
          mttr:                elMTTR.textContent,
          status:              elStatus.textContent
        })
      })
      .then(r => r.json())
      .then(d => {
        if (d.status === 'success') { alert("Data berhasil disimpan!"); document.getElementById("btnReset").click(); }
        else alert("Gagal: " + d.message);
      })
      .then(r => {
  if (!r.ok) return r.text().then(t => { throw new Error('HTTP ' + r.status + ': ' + t.substring(0, 300)); });
  return r.json();
})
.then(d => {
  if (d.status === 'success') { alert("Data berhasil disimpan!"); document.getElementById("btnReset").click(); }
  else alert("Gagal menyimpan:\n" + (d.message || JSON.stringify(d.errors || {})));
})
.catch(err => alert("Terjadi kesalahan:\n" + err.message + "\n\nCoba refresh dan simpan ulang."))
.finally(() => { btn.textContent = ori; btn.disabled = false; });
    });

    // ========================================================
    // UPLOAD & PARSE FILE (SheetJS — 100% di browser)
    // ========================================================
    let parsedImportData = [];

    const dropzone       = document.getElementById("dropzone");
    const fileInput      = document.getElementById("fileInput");
    const uploadFilename = document.getElementById("uploadFilename");

    dropzone.addEventListener("dragover",  e => { e.preventDefault(); dropzone.classList.add("dragover"); });
    dropzone.addEventListener("dragleave", () => dropzone.classList.remove("dragover"));
    dropzone.addEventListener("drop", e => {
      e.preventDefault();
      dropzone.classList.remove("dragover");
      if (e.dataTransfer.files[0]) handleFile(e.dataTransfer.files[0]);
    });
    fileInput.addEventListener("change", () => {
      if (fileInput.files[0]) handleFile(fileInput.files[0]);
    });

    function handleFile(file) {
      const ext = '.' + file.name.split('.').pop().toLowerCase();
      if (!['.xls','.xlsx','.csv'].includes(ext)) {
        alert("Format tidak didukung. Gunakan .xls, .xlsx, atau .csv"); return;
      }
      if (file.size > 10 * 1024 * 1024) { alert("File terlalu besar. Maks. 10MB."); return; }

      uploadFilename.textContent  = "📄 " + file.name;
      uploadFilename.style.display = "block";
      document.getElementById("parsingOverlay").classList.add("show");

      const reader = new FileReader();
      reader.onload = e => {
        try {
          const wb = XLSX.read(new Uint8Array(e.target.result), { type: 'array' });

          // Pilih sheet GRAFIK — ambil semua sheet yang namanya mengandung GRAFIK
          const grafikSheets = wb.SheetNames.filter(s => s.toUpperCase().includes('GRAFIK'));
          const sheetsToRead = grafikSheets.length > 0 ? grafikSheets : [wb.SheetNames[0]];

          let allResults = [];
          sheetsToRead.forEach(name => {
            const rows = parseGrafikSheet(wb, name);
            allResults = allResults.concat(rows);
          });

          document.getElementById("parsingOverlay").classList.remove("show");

          if (allResults.length === 0) {
            alert("Tidak ada data yang bisa dibaca.\n\nPastikan file punya sheet 'GRAFIK CNC' atau 'GRAFIK GM, DC & SB'.");
            return;
          }

          parsedImportData = allResults;
          showImportModal(allResults, sheetsToRead.join(", "));
        } catch (err) {
          document.getElementById("parsingOverlay").classList.remove("show");
          alert("Gagal membaca file.\n\nError: " + err.message);
        }
      };
      reader.readAsArrayBuffer(file);
    }

    // ---------- Helpers ----------
    function safeNum(v) { const n = parseFloat(v); return isNaN(n) ? 0 : parseFloat(n.toFixed(4)); }
    function safeStr(v) {
      if (v === null || v === undefined) return "-";
      const s = String(v).trim();
      return (s === "" || s.toLowerCase() === "nan") ? "-" : s;
    }
    function detectPlant(kode) {
      kode = (kode || "").trim().toUpperCase();
      if (kode.startsWith("CNC")) return "CNC";
      if (kode.startsWith("GC") || kode.startsWith("GM")) return "GC";
      if (kode.startsWith("SB")) return "SB";
      if (kode.startsWith("DC")) return "DC";
      return "DC";
    }
    function calcMetrics(loading, breakdown, operating, freq) {
      const avail = loading > 0 ? ((loading - breakdown) / loading) * 100 : 0;
      return {
        availability: parseFloat(avail.toFixed(4)),
        mtbf:         freq > 0 ? parseFloat((operating / freq).toFixed(4)) : 0,
        mttr:         freq > 0 ? parseFloat((breakdown / freq).toFixed(4)) : 0,
        status:       avail >= 98 ? "Normal" : (avail > 0 ? "Warning" : "-")
      };
    }
    function extractBulanTahun(rows) {
      for (let r = 0; r < Math.min(12, rows.length); r++) {
        for (let c = 0; c < rows[r].length; c++) {
          const m = String(rows[r][c] || "").match(/bulan\s*[:\-]?\s*(\w+)\s+(\d{4})/i);
          if (m) {
            const raw = m[1];
            const bulan = raw.charAt(0).toUpperCase() + raw.slice(1).toLowerCase();
            return { bulan, tahun: m[2] };
          }
        }
      }
      return { bulan: "", tahun: "" };
    }

    function parseGrafikSheet(wb, sheetName) {
      const sheet = wb.Sheets[sheetName];
      const rows  = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: "" });
      const { bulan, tahun } = extractBulanTahun(rows);

      // Cari baris header kolom
      let headerRow = -1;
      for (let r = 0; r < Math.min(20, rows.length); r++) {
        const str = rows[r].join(" ").toUpperCase();
        if (str.includes("AVAILABILITY") || str.includes("LOADING")) { headerRow = r; break; }
      }
      if (headerRow === -1) headerRow = 0;

      const headers = rows[headerRow].map(h => String(h).toUpperCase().trim());

      function col(kws) {
        for (const kw of kws) {
          const i = headers.findIndex(h => h.includes(kw));
          if (i !== -1) return i;
        }
        return -1;
      }

      const iKode      = col(["NO. LOKASI","LOKASI","KODE MESIN","KODE"]);
      const iNama      = col(["NAMA MESIN","NAMA"]);
      const iLoading   = col(["LOADING TIME","LOADING"]);
      const iOperating = col(["OPERATING TIME","OPERATING"]);
      const iBreakdown = col(["BREAKDOWN TIME","BREAKDOWN"]);
      const iFreq      = col(["FREKWENSI","FREKUENSI","FREQ"]);
      const iMasalah   = col(["MASALAH"]);
      const iPerbaikan = col(["PERBAIKAN"]);
      const iPencegahan= col(["PENCEGAHAN"]);

      const results = [];
      for (let r = headerRow + 1; r < rows.length; r++) {
        const row = rows[r];
        if (!row || row.every(c => c === "" || c === null)) continue;

        let kode = iKode !== -1 ? String(row[iKode] || "").trim() : "";
        if (!kode && iNama !== -1) kode = String(row[iNama] || "").trim();

        // Skip baris bukan mesin
        if (!kode || kode.length < 2) continue;
        if (/^[\d\s.]+$/.test(kode)) continue;
        const skip = ["rata","rata-rata","total","jumlah","plant","keterangan","no.","nomor"];
        if (skip.some(s => kode.toLowerCase().includes(s))) continue;

        const loading   = safeNum(iLoading   !== -1 ? row[iLoading]   : 0);
        const operating = safeNum(iOperating !== -1 ? row[iOperating] : 0);
        const breakdown = safeNum(iBreakdown !== -1 ? row[iBreakdown] : 0);
        const freq      = safeNum(iFreq      !== -1 ? row[iFreq]      : 0);
        const calc      = calcMetrics(loading, breakdown, operating, freq);

        results.push({
          bulan,
          tahun,
          plant:               detectPlant(kode),
          kode_mesin:          kode,
          loading_time:        loading,
          operating_time:      operating,
          breakdown_time:      breakdown,
          freq_breakdown:      freq,
          mtbf:                calc.mtbf,
          mttr:                calc.mttr,
          availability:        calc.availability,
          status:              calc.status,
          masalah:             safeStr(iMasalah    !== -1 ? row[iMasalah]    : ""),
          langkah_perbaikan:   safeStr(iPerbaikan  !== -1 ? row[iPerbaikan]  : ""),
          langkah_pencegahan:  safeStr(iPencegahan !== -1 ? row[iPencegahan] : ""),
        });
      }
      return results;
    }

    // ---------- Modal ----------
    function showImportModal(data, sheetLabel) {
      const bulanList = [...new Set(data.map(d => d.bulan).filter(Boolean))].join(", ") || "—";
      const tahunList = [...new Set(data.map(d => d.tahun).filter(Boolean))].join(", ") || "—";

      document.getElementById("summaryTotal").textContent  = data.length;
      document.getElementById("summarySheet").textContent  = sheetLabel;
      document.getElementById("summaryBulan").textContent  = bulanList;
      document.getElementById("summaryTahun").textContent  = tahunList;
      document.getElementById("importModalSubtitle").textContent =
        `${data.length} baris ditemukan dari sheet "${sheetLabel}" — periksa sebelum menyimpan`;

      const tbody = document.getElementById("importTableBody");
      tbody.innerHTML = "";
      data.forEach((row, i) => {
        const badge = row.status === "Normal"
          ? `<span class="badge-normal">Normal</span>`
          : row.status === "Warning"
          ? `<span class="badge-warning">Warning</span>`
          : `<span class="badge-zero">—</span>`;

        tbody.innerHTML += `
          <tr>
            <td>${i + 1}</td>
            <td><strong>${row.kode_mesin}</strong></td>
            <td>${row.plant}</td>
            <td>${row.bulan || "—"}</td>
            <td>${row.tahun || "—"}</td>
            <td>${row.loading_time}</td>
            <td>${row.operating_time}</td>
            <td>${row.breakdown_time}</td>
            <td>${row.freq_breakdown}</td>
            <td>${row.mtbf}</td>
            <td>${row.mttr}</td>
            <td><strong>${row.availability}%</strong></td>
            <td>${badge}</td>
            <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;">${row.masalah}</td>
            <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;">${row.langkah_perbaikan}</td>
            <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;">${row.langkah_pencegahan}</td>
          </tr>`;
      });

      document.getElementById("importModal").style.display = "block";
    }

    function closeImportModal() {
      document.getElementById("importModal").style.display = "none";
      document.getElementById("importProgress").style.display = "none";
      document.getElementById("importProgressBar").style.width = "0%";
    }

    async function submitImport() {
      if (!parsedImportData.length) return;

      const btnSubmit = document.getElementById("btnImportSubmit");
      const btnBatal  = document.getElementById("btnBatal");
      const progress  = document.getElementById("importProgress");
      const bar       = document.getElementById("importProgressBar");
      const text      = document.getElementById("importProgressText");

      btnSubmit.disabled = true;
      btnBatal.disabled  = true;
      btnSubmit.textContent = "Menyimpan...";
      progress.style.display = "block";

      const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      let saved = 0, failed = 0;

      for (let i = 0; i < parsedImportData.length; i++) {
        try {
  const res = await fetch('/api/machines/store', {
    method: 'POST',
    credentials: 'same-origin',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
    body: JSON.stringify(parsedImportData[i])
  });
  if (!res.ok) {
    const errText = await res.text();
    console.error('Row ' + (i+1) + ' HTTP ' + res.status + ':', errText.substring(0, 300));
    failed++;
  } else {
    const json = await res.json();
    if (json.status === 'success') saved++;
    else { console.error('Row ' + (i+1) + ' error:', json.message); failed++; }
  }
} catch(e) { console.error('Row ' + (i+1) + ' exception:', e.message); failed++; }

        const pct = Math.round(((i + 1) / parsedImportData.length) * 100);
        bar.style.width  = pct + "%";
        text.textContent = `Menyimpan ${i + 1} dari ${parsedImportData.length} data... (${pct}%)`;
      }

      closeImportModal();
      fileInput.value = "";
      uploadFilename.style.display = "none";
      parsedImportData = [];
      btnSubmit.disabled = false;
      btnBatal.disabled  = false;
      btnSubmit.textContent = "Simpan Semua ke Database";

      alert(`Import selesai!\n✅ Berhasil disimpan: ${saved}\n❌ Gagal: ${failed}`);
    }
  </script>
</body>
</html>
