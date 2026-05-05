<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Availability Mesin - Admin</title>
  <link rel="stylesheet" href="css/style.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    .modal {
      display: none; position: fixed; z-index: 1000; left: 0; top: 0;
      width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);
      backdrop-filter: blur(4px); padding-top: 40px; overflow-y: auto;
    }
    .modal-content {
      background-color: #fff; margin: 5% auto; padding: 24px;
      border-radius: 18px; width: 90%; max-width: 600px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .close-btn { float: right; color: #dc2626; font-size: 28px; font-weight: bold; cursor: pointer; }
    .modal-title { margin-bottom: 20px; font-size: 20px; color: #1e3a8a; font-weight: 800; border-bottom: 2px solid #dbeafe; padding-bottom: 10px; }
    .edit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
    .edit-full { grid-column: 1 / -1; }
    .modal-input { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; outline: none; }
    .modal-input:focus { border-color: #2563eb; }

    .year-group {
      margin-bottom: 40px;
    }

    .year-title {
      font-size: 22px;
      color: #0f172a;
      font-weight: 900;
      margin-bottom: 18px;
      padding-bottom: 10px;
      border-bottom: 3px solid #bfdbfe;
    }

    .month-group {
      margin-bottom: 32px;
    }

    .month-title {
      font-size: 18px;
      color: #1d4ed8;
      font-weight: 800;
      margin-bottom: 14px;
    }

    .plant-group {
      margin-bottom: 40px;
    }

    .plant-title {
      font-size: 18px;
      color: #1e3a8a;
      font-weight: 800;
      border-bottom: 2px solid #dbeafe;
      padding-bottom: 8px;
      margin-bottom: 15px;
      text-transform: uppercase;
    }

    .responsive-table {
      width: 100%;
      border-collapse: collapse;
    }

    @media screen and (min-width: 1201px) {
      .responsive-table thead th {
        background: #1d4ed8;
        color: #fff;
        padding: 12px;
        text-align: left;
        font-size: 12px;
        white-space: nowrap;
      }
      .responsive-table td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 13px;
      }
    }

    @media screen and (max-width: 1200px) {
      .responsive-table thead {
        display: none;
      }
      .responsive-table, .responsive-table tbody, .responsive-table tr, .responsive-table td {
        display: block;
        width: 100%;
      }
      .responsive-table tr {
        margin-bottom: 25px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        padding: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      }
      .responsive-table td {
        text-align: right;
        padding: 10px 15px;
        border-bottom: 1px solid #f1f5f9;
        position: relative;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .responsive-table td:last-child {
        border-bottom: none;
      }
      .responsive-table td::before {
        content: attr(data-label);
        font-weight: 800;
        color: #1e3a8a;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        flex: 1;
      }
      .responsive-table td .availability-badge {
        margin: 0;
      }
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
        <a href="/admin-input" class="menu-link">Input Data</a>
        <a href="/admin-reports" class="menu-link active">Laporan</a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0; padding:0;">
    @csrf
    <button type="submit" class="menu-link" style="background:none; border:none; cursor:pointer; font-family:inherit; font-size:inherit; display:block; width:100%;">Logout</button>
</form>
      </nav>
    </aside>

    <main class="main-content">
      <header class="main-header">
        <div>
          <h1>Laporan Availability Mesin</h1>
          <p>Melihat dan mengedit laporan tanpa scroll kanan-kiri</p>
        </div>
        <div class="header-right">
          <span class="role-pill">Admin</span>
        </div>
      </header>

      <section class="panel report-filter-panel">
        <div class="panel-header"><h2>Filter Laporan</h2></div>
        <div class="report-filter-grid">
          <div class="form-group">
            <label>Bulan</label>
            <select id="monthFilter">
              <option value="">Semua Bulan</option>
              <option>Januari</option><option>Februari</option><option>Maret</option>
              <option>April</option><option>Mei</option><option>Juni</option>
              <option>Juli</option><option>Agustus</option><option>September</option>
              <option>Oktober</option><option>November</option><option>Desember</option>
            </select>
          </div>
          <div class="form-group">
            <label>Tahun</label>
            <select id="yearFilter">
              <option value="">Semua Tahun</option>
              <option>2024</option><option>2025</option><option>2026</option>
            </select>
          </div>
          <div class="form-group">
            <label>Cari Plant / Mesin</label>
            <input type="text" id="searchFilter" placeholder="Contoh: DC, CNC 01..." />
          </div>
          <div class="form-group filter-button-wrap">
            <button id="resetFilterBtn" class="btn-login">Reset Filter</button>
          </div>
        </div>
      </section>

      <section class="report-summary-grid">
        <div class="panel mini-panel center">
          <h3>Total Data</h3>
          <div class="big-value small-big" id="totalDataCount">0</div>
        </div>
        <div class="panel mini-panel center">
          <h3>Rata-rata Availability</h3>
          <div class="big-value small-big" id="avgAvailability">0%</div>
        </div>
      </section>

      <div id="reportsContainer">
        <p style="text-align:center; padding:20px;">Memuat data dari database...</p>
      </div>
    </main>
  </div>

  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close-btn" onclick="closeModal()">&times;</span>
      <h2 class="modal-title">Edit Data Mesin <span id="editMesinTitle"></span></h2>
      <input type="hidden" id="editId">
      <input type="hidden" id="editBulan"><input type="hidden" id="editTahun">
      <input type="hidden" id="editPlant"><input type="hidden" id="editKodeMesin">
      <div class="edit-grid">
        <div><label>Loading Time (Jam)</label><input type="number" step="0.01" id="editLoading" class="modal-input"></div>
        <div><label>Operating Time (Jam)</label><input type="number" step="0.01" id="editOperating" class="modal-input"></div>
        <div><label>Breakdown Time (Jam)</label><input type="number" step="0.01" id="editBreakdown" class="modal-input"></div>
        <div><label>Freq. Breakdown</label><input type="number" id="editFreq" class="modal-input"></div>
        <div class="edit-full"><label>Masalah / Hambatan</label><textarea id="editMasalah" rows="2" class="modal-input"></textarea></div>
        <div class="edit-full"><label>Langkah Perbaikan</label><textarea id="editPerbaikan" rows="2" class="modal-input"></textarea></div>
        <div class="edit-full"><label>Langkah Pencegahan</label><textarea id="editPencegahan" rows="2" class="modal-input"></textarea></div>
        <div class="edit-full" style="text-align: right;"><button onclick="simpanEdit()" class="btn-login" id="btnSimpanEdit">Simpan Ubahan</button></div>
      </div>
    </div>
  </div>

  <script>
    let reportData = [];
    const container = document.getElementById("reportsContainer");

    const monthOrder = {
      "Januari": 1,
      "Februari": 2,
      "Maret": 3,
      "April": 4,
      "Mei": 5,
      "Juni": 6,
      "Juli": 7,
      "Agustus": 8,
      "September": 9,
      "Oktober": 10,
      "November": 11,
      "Desember": 12
    };

    function getMachinePrefix(machine) {
      return String(machine || "").split(" ")[0] || "";
    }

    function getMachineNumber(machine) {
      const parts = String(machine || "").split(" ");
      return parseInt(parts[parts.length - 1], 10) || 0;
    }

    function sortLaporan(data) {
      return [...data].sort((a, b) => {
        if (Number(b.year) !== Number(a.year)) {
          return Number(b.year) - Number(a.year);
        }

        const monthA = monthOrder[a.month] || 99;
        const monthB = monthOrder[b.month] || 99;
        if (monthB !== monthA) {
          return monthB - monthA;
        }

        if (a.plant !== b.plant) {
          return a.plant.localeCompare(b.plant, 'id');
        }

        const prefixA = getMachinePrefix(a.machine);
        const prefixB = getMachinePrefix(b.machine);
        if (prefixA !== prefixB) {
          return prefixA.localeCompare(prefixB, 'id');
        }

        return getMachineNumber(a.machine) - getMachineNumber(b.machine);
      });
    }

    function fetchLaporan() {
      fetch('/api/machines')
        .then(response => response.json())
        .then(data => {
          reportData = (Array.isArray(data) ? data : []).map(d => ({
            id: d.id,
            month: d.bulan,
            year: d.tahun,
            plant: d.plant,
            machine: d.kode_mesin,
            loading: parseFloat(d.loading_time) || 0,
            operating: parseFloat(d.operating_time) || 0,
            breakdown: parseFloat(d.breakdown_time) || 0,
            freq: parseInt(d.freq_breakdown) || 0,
            mtbf: parseFloat(d.mtbf) || 0,
            mttr: parseFloat(d.mttr) || 0,
            availability: parseFloat(d.availability) || 0,
            issue: d.masalah || "-",
            perbaikan: d.langkah_perbaikan || "-",
            pencegahan: d.langkah_pencegahan || "-"
          }));

          renderTable();
        })
        .catch(() => {
          container.innerHTML = `<p style="text-align:center; color:red;">Gagal menarik data. Pastikan XAMPP menyala.</p>`;
        });
    }

    function renderTable() {
      const sMonth = document.getElementById("monthFilter").value.toLowerCase();
      const sYear = document.getElementById("yearFilter").value;
      const sSearch = document.getElementById("searchFilter").value.toLowerCase();

      const filtered = sortLaporan(
        reportData.filter(item => {
          const mMatch = sMonth ? item.month.toLowerCase() === sMonth : true;
          const yMatch = sYear ? String(item.year) === sYear : true;
          const sMatch = sSearch
            ? item.machine.toLowerCase().includes(sSearch) || item.plant.toLowerCase().includes(sSearch)
            : true;

          return mMatch && yMatch && sMatch;
        })
      );

      container.innerHTML = "";

      if (filtered.length === 0) {
        container.innerHTML = "<p style='text-align:center; padding:20px;'>Data tidak ditemukan.</p>";
        document.getElementById("totalDataCount").textContent = "0";
        document.getElementById("avgAvailability").textContent = "0%";
        return;
      }

      let totalAvail = 0;
      const grouped = {};

      filtered.forEach(item => {
        totalAvail += item.availability;

        if (!grouped[item.year]) grouped[item.year] = {};
        if (!grouped[item.year][item.month]) grouped[item.year][item.month] = {};
        if (!grouped[item.year][item.month][item.plant]) grouped[item.year][item.month][item.plant] = [];
        grouped[item.year][item.month][item.plant].push(item);
      });

      const years = Object.keys(grouped).sort((a, b) => Number(b) - Number(a));

      years.forEach(year => {
        let yearHtml = `<div class="year-group"><h2 class="year-title">Tahun ${year}</h2>`;

        const months = Object.keys(grouped[year]).sort((a, b) => (monthOrder[b] || 99) - (monthOrder[a] || 99));
        months.forEach(month => {
          yearHtml += `<div class="month-group"><h3 class="month-title">Bulan ${month}</h3>`;

          const plants = Object.keys(grouped[year][month]).sort((a, b) => a.localeCompare(b, 'id'));
          plants.forEach(plant => {
            const plantData = grouped[year][month][plant];

            yearHtml += `
              <div class="plant-group">
                <h4 class="plant-title">Area Plant: ${plant}</h4>
                <table class="responsive-table">
                  <thead>
                    <tr>
                      <th>Aksi</th><th>Bulan</th><th>Tahun</th><th>Mesin</th>
                      <th>Loading</th><th>Operating</th><th>Breakdown</th><th>Freq</th>
                      <th>MTBF</th><th>MTTR</th><th>Availability</th><th>Masalah</th>
                      <th>Perbaikan</th><th>Pencegahan</th>
                    </tr>
                  </thead>
                  <tbody>
            `;

            plantData.forEach(item => {
              yearHtml += `
                <tr>
                  <td data-label="Aksi">
                    <button onclick="bukaModalEdit(${item.id})" style="background:#f59e0b; color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; font-weight:bold;">Edit</button>
                  </td>
                  <td data-label="Bulan">${item.month}</td>
                  <td data-label="Tahun">${item.year}</td>
                  <td data-label="Kode Mesin">${item.machine}</td>
                  <td data-label="Loading Time">${item.loading}h</td>
                  <td data-label="Operating Time">${item.operating}h</td>
                  <td data-label="Breakdown Time">${item.breakdown}h</td>
                  <td data-label="Freq. Breakdown">${item.freq}</td>
                  <td data-label="MTBF">${item.mtbf}</td>
                  <td data-label="MTTR">${item.mttr}</td>
                  <td data-label="Availability"><span class="availability-badge">${item.availability}%</span></td>
                  <td data-label="Masalah">${item.issue}</td>
                  <td data-label="Perbaikan">${item.perbaikan}</td>
                  <td data-label="Pencegahan">${item.pencegahan}</td>
                </tr>
              `;
            });

            yearHtml += `
                  </tbody>
                </table>
              </div>
            `;
          });

          yearHtml += `</div>`;
        });

        yearHtml += `</div>`;
        container.innerHTML += yearHtml;
      });

      document.getElementById("totalDataCount").textContent = filtered.length;
      document.getElementById("avgAvailability").textContent = (totalAvail / filtered.length).toFixed(2) + "%";
    }

    // LOGIKA MODAL EDIT (TETAP DIPERTAHANKAN)
    const modal = document.getElementById("editModal");

    function bukaModalEdit(id) {
      const d = reportData.find(item => item.id === id);
      document.getElementById("editId").value = d.id;
      document.getElementById("editMesinTitle").textContent = `(${d.machine} - ${d.month})`;
      document.getElementById("editBulan").value = d.month;
      document.getElementById("editTahun").value = d.year;
      document.getElementById("editPlant").value = d.plant;
      document.getElementById("editKodeMesin").value = d.machine;
      document.getElementById("editLoading").value = d.loading;
      document.getElementById("editOperating").value = d.operating;
      document.getElementById("editBreakdown").value = d.breakdown;
      document.getElementById("editFreq").value = d.freq;
      document.getElementById("editMasalah").value = d.issue === "-" ? "" : d.issue;
      document.getElementById("editPerbaikan").value = d.perbaikan === "-" ? "" : d.perbaikan;
      document.getElementById("editPencegahan").value = d.pencegahan === "-" ? "" : d.pencegahan;
      modal.style.display = "block";
    }

    function closeModal() {
      modal.style.display = "none";
    }

    function simpanEdit() {
      const btn = document.getElementById("btnSimpanEdit");
      btn.textContent = "Menyimpan...";
      btn.disabled = true;

      const id   = document.getElementById("editId").value;
      const load = parseFloat(document.getElementById("editLoading").value) || 0;
      const breakd = parseFloat(document.getElementById("editBreakdown").value) || 0;
      const freq = parseFloat(document.getElementById("editFreq").value) || 0;
      const operating = parseFloat(document.getElementById("editOperating").value) || 0;

      const avail = load > 0 ? (((load - breakd) / load) * 100).toFixed(2) : 0;
      const mtbf = freq > 0 ? (operating / freq).toFixed(2) : 0;
      const mttr = freq > 0 ? (breakd / freq).toFixed(2) : 0;

      const updated = {
        bulan:               document.getElementById("editBulan").value,
        tahun:               document.getElementById("editTahun").value,
        plant:               document.getElementById("editPlant").value,
        kode_mesin:          document.getElementById("editKodeMesin").value,
        loading_time:        load,
        operating_time:      operating,
        breakdown_time:      breakd,
        freq_breakdown:      freq,
        masalah:             document.getElementById("editMasalah").value,
        langkah_perbaikan:   document.getElementById("editPerbaikan").value,
        langkah_pencegahan:  document.getElementById("editPencegahan").value,
        availability:        avail,
        mtbf:                mtbf,
        mttr:                mttr,
        status:              avail >= 98 ? "Normal" : "Warning"
      };

      fetch(`/api/machines/${id}`, {
        method: 'PUT',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(updated)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          alert("Berhasil diupdate!");
          closeModal();
          fetchLaporan();
        } else {
          alert("Gagal update: " + (data.message || "Unknown error"));
        }
      })
      .catch(() => {
        alert("Terjadi kesalahan saat menyimpan data.");
      })
      .finally(() => {
        btn.textContent = "Simpan Ubahan";
        btn.disabled = false;
      });
    }

    document.getElementById("monthFilter").addEventListener("change", renderTable);
    document.getElementById("yearFilter").addEventListener("change", renderTable);
    document.getElementById("searchFilter").addEventListener("input", renderTable);
    document.getElementById("resetFilterBtn").addEventListener("click", () => {
      document.getElementById("monthFilter").value = "";
      document.getElementById("yearFilter").value = "";
      document.getElementById("searchFilter").value = "";
      renderTable();
    });

    fetchLaporan();
  </script>
</body>
</html>