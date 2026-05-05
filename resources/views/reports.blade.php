<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Availability Mesin - User</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .report-container {
      width: 100%;
    }

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
      margin-bottom: 24px;
    }

    .plant-title {
      font-size: 16px;
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

    @media screen and (max-width: 1200px) {
      .responsive-table thead {
        display: none;
      }

      .responsive-table,
      .responsive-table tbody,
      .responsive-table tr,
      .responsive-table td {
        display: block;
        width: 100%;
      }

      .responsive-table tr {
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        padding: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
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
        font-size: 12px;
        text-transform: uppercase;
      }

      .availability-badge {
        margin-left: auto;
      }
    }

    @media screen and (min-width: 1201px) {
      .responsive-table thead th {
        background: #1d4ed8;
        color: #fff;
        padding: 12px;
        text-align: left;
        font-size: 13px;
      }

      .responsive-table td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 13px;
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
        <a href="/home" class="menu-link">Dashboard</a>
        <a href="/reports" class="menu-link active">Laporan</a>
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
          <p>Melihat detail laporan tanpa scroll kanan-kiri</p>
        </div>
        <div class="header-right">
          <span class="role-pill">User</span>
        </div>
      </header>

      <section class="panel report-filter-panel">
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
              <option>2025</option><option>2026</option>
            </select>
          </div>
          <div class="form-group">
            <label>Cari Mesin</label>
            <input type="text" id="searchFilter" placeholder="Contoh: CNC 01..." />
          </div>
        </div>
      </section>

      <div id="reportsContainer" class="report-container">
        <p style="text-align:center; padding:20px;">Memuat data dari database...</p>
      </div>
    </main>
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

    function getMachinePrefix(kodeMesin) {
      return String(kodeMesin || "").split(" ")[0] || "";
    }

    function getMachineNumber(kodeMesin) {
      const parts = String(kodeMesin || "").split(" ");
      return parseInt(parts[parts.length - 1], 10) || 0;
    }

    function sortLaporan(data) {
      return [...data].sort((a, b) => {
        if (Number(b.tahun) !== Number(a.tahun)) {
          return Number(b.tahun) - Number(a.tahun);
        }

        const monthA = monthOrder[a.bulan] || 99;
        const monthB = monthOrder[b.bulan] || 99;
        if (monthB !== monthA) {
          return monthB - monthA;
        }

        if (a.plant !== b.plant) {
          return a.plant.localeCompare(b.plant, 'id');
        }

        const prefixA = getMachinePrefix(a.kode_mesin);
        const prefixB = getMachinePrefix(b.kode_mesin);
        if (prefixA !== prefixB) {
          return prefixA.localeCompare(prefixB, 'id');
        }

        return getMachineNumber(a.kode_mesin) - getMachineNumber(b.kode_mesin);
      });
    }

    function fetchLaporan() {
      fetch('/api/machines')
        .then(response => response.json())
        .then(data => {
          reportData = Array.isArray(data) ? data : [];
          renderTable();
        })
        .catch(() => {
          container.innerHTML = `<p style="text-align:center; color:red;">Gagal menarik data.</p>`;
        });
    }

    function renderTable() {
      const selectedMonth = document.getElementById("monthFilter").value.toLowerCase();
      const selectedYear = document.getElementById("yearFilter").value;
      const searchValue = document.getElementById("searchFilter").value.toLowerCase();

      const filteredData = sortLaporan(
        reportData.filter(item => {
          const monthMatch = selectedMonth ? item.bulan.toLowerCase() === selectedMonth : true;
          const yearMatch = selectedYear ? String(item.tahun) === selectedYear : true;
          const searchMatch = searchValue
            ? item.kode_mesin.toLowerCase().includes(searchValue) || item.plant.toLowerCase().includes(searchValue)
            : true;

          return monthMatch && yearMatch && searchMatch;
        })
      );

      container.innerHTML = "";

      if (filteredData.length === 0) {
        container.innerHTML = "<p style='text-align:center; padding:20px;'>Data tidak ditemukan.</p>";
        return;
      }

      const grouped = {};
      filteredData.forEach(item => {
        if (!grouped[item.tahun]) grouped[item.tahun] = {};
        if (!grouped[item.tahun][item.bulan]) grouped[item.tahun][item.bulan] = {};
        if (!grouped[item.tahun][item.bulan][item.plant]) grouped[item.tahun][item.bulan][item.plant] = [];
        grouped[item.tahun][item.bulan][item.plant].push(item);
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
                      <th>No</th><th>Bulan</th><th>Tahun</th><th>Mesin</th>
                      <th>Loading</th><th>Operating</th><th>Breakdown</th><th>Freq</th>
                      <th>MTBF</th><th>MTTR</th><th>Availability</th><th>Masalah</th>
                      <th>Perbaikan</th><th>Pencegahan</th>
                    </tr>
                  </thead>
                  <tbody>
            `;

            plantData.forEach((item, index) => {
              yearHtml += `
                <tr>
                  <td data-label="No">${index + 1}</td>
                  <td data-label="Bulan">${item.bulan}</td>
                  <td data-label="Tahun">${item.tahun}</td>
                  <td data-label="Kode Mesin">${item.kode_mesin}</td>
                  <td data-label="Loading Time">${item.loading_time}h</td>
                  <td data-label="Operating Time">${item.operating_time}h</td>
                  <td data-label="Breakdown Time">${item.breakdown_time}h</td>
                  <td data-label="Freq. Breakdown">${item.freq_breakdown}</td>
                  <td data-label="MTBF">${item.mtbf}</td>
                  <td data-label="MTTR">${item.mttr}</td>
                  <td data-label="Availability">
                    <span class="availability-badge">${item.availability}%</span>
                  </td>
                  <td data-label="Masalah">${item.masalah || '-'}</td>
                  <td data-label="Perbaikan">${item.langkah_perbaikan || '-'}</td>
                  <td data-label="Pencegahan">${item.langkah_pencegahan || '-'}</td>
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
    }

    document.getElementById("monthFilter").addEventListener("change", renderTable);
    document.getElementById("yearFilter").addEventListener("change", renderTable);
    document.getElementById("searchFilter").addEventListener("input", renderTable);

    fetchLaporan();
  </script>
</body>
</html>