<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Input Data Availability - Admin</title>
  <link rel="stylesheet" href="css/style.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

        <form class="admin-form-grid" id="inputForm">
          <div class="form-group">
            <label for="bulan">Bulan</label>
            <select id="bulan">
              <option value="">-- Pilih Bulan --</option>
              <option>Januari</option>
              <option>Februari</option>
              <option selected>Maret</option>
              <option>April</option>
              <option>Mei</option>
              <option>Juni</option>
              <option>Juli</option>
              <option>Agustus</option>
              <option>September</option>
              <option>Oktober</option>
              <option>November</option>
              <option>Desember</option>
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

        <div class="panel-header" style="margin-top: 10px;">
          <h2>Hasil Perhitungan Otomatis</h2>
          <span>Preview rumus</span>
        </div>

        <div class="report-summary-grid">
          <div class="panel mini-panel center">
            <h3>Availability</h3>
            <div class="big-value small-big" id="calcAvailability">0.00%</div>
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

        <div class="admin-action-row">
          <button class="btn-login admin-btn-secondary" type="button" id="btnReset">Reset</button>
          <button class="btn-login admin-btn-primary" type="button" id="btnSimpan">Simpan ke Database</button>
        </div>
      </section>
    </main>
  </div>

  <script>
    const machineConfig = {
      "DC": 8, "GC": 10, "SB": 7, "CNC": 32
    };

    const plantSelect = document.getElementById("plant");
    const kodeMesinSelect = document.getElementById("kodeMesin");

    plantSelect.addEventListener("change", function() {
      const selectedPlant = this.value;
      kodeMesinSelect.innerHTML = '<option value="">-- Pilih Mesin --</option>';

      if (machineConfig[selectedPlant]) {
        const totalMachine = machineConfig[selectedPlant];
        for (let i = 1; i <= totalMachine; i++) {
          const num = i < 10 ? '0' + i : i;
          const machineCode = `${selectedPlant} ${num}`;
          const option = document.createElement("option");
          option.value = machineCode;
          option.textContent = machineCode;
          kodeMesinSelect.appendChild(option);
        }
      } else {
        kodeMesinSelect.innerHTML = '<option value="">-- Pilih Plant Dulu --</option>';
      }
    });

    const loadingTime = document.getElementById("loadingTime");
    const operatingTime = document.getElementById("operatingTime");
    const breakdownTime = document.getElementById("breakdownTime");
    const freqBreakdown = document.getElementById("freqBreakdown");

    const calcAvailability = document.getElementById("calcAvailability");
    const calcMTBF = document.getElementById("calcMTBF");
    const calcMTTR = document.getElementById("calcMTTR");
    const calcStatus = document.getElementById("calcStatus");

    function calculateMetrics() {
      const loading = parseFloat(loadingTime.value) || 0;
      const operating = parseFloat(operatingTime.value) || 0;
      const breakdown = parseFloat(breakdownTime.value) || 0;
      const freq = parseFloat(freqBreakdown.value) || 0;

      let availability = 0;
      // Rumus baru sesuai dengan standar pabrik: ((Loading - Breakdown) / Loading) * 100
      if (loading > 0) {
        availability = ((loading - breakdown) / loading) * 100;
      }

      let mtbf = 0;
      let mttr = 0;
      if (freq > 0) {
        mtbf = operating / freq;
        mttr = breakdown / freq;
      }

      calcAvailability.textContent = availability.toFixed(2);
      calcMTBF.textContent = mtbf.toFixed(2);
      calcMTTR.textContent = mttr.toFixed(2);

      if (loading === 0 && operating === 0) {
        calcStatus.textContent = "-";
        calcStatus.style.color = "#1d4ed8";
      } else if (availability >= 98) {
        calcStatus.textContent = "Normal";
        calcStatus.style.color = "#16a34a";
      } else {
        calcStatus.textContent = "Warning";
        calcStatus.style.color = "#dc2626";
      }
    }

    [loadingTime, operatingTime, breakdownTime, freqBreakdown].forEach(input => {
      input.addEventListener("input", calculateMetrics);
    });

    const btnReset = document.getElementById("btnReset");
    btnReset.addEventListener("click", () => {
      document.getElementById("inputForm").reset();
      kodeMesinSelect.innerHTML = '<option value="">-- Pilih Plant Dulu --</option>';
      calculateMetrics();
    });

    const btnSimpan = document.getElementById("btnSimpan");
    btnSimpan.addEventListener("click", () => {
      if(!plantSelect.value || !kodeMesinSelect.value || !loadingTime.value || !operatingTime.value) {
        alert("lengkapi data Plant, Mesin, dan Jam Operasional dulu ya biar bisa disimpan.");
        return;
      }

      const originalText = btnSimpan.textContent;
      btnSimpan.textContent = "Menyimpan...";
      btnSimpan.disabled = true;

      const newData = {
        bulan:               document.getElementById("bulan").value,
        tahun:               document.getElementById("tahun").value,
        plant:               plantSelect.value,
        kode_mesin:          kodeMesinSelect.value,
        loading_time:        loadingTime.value,
        operating_time:      operatingTime.value,
        breakdown_time:      breakdownTime.value || 0,
        freq_breakdown:      freqBreakdown.value || 0,
        masalah:             document.getElementById("masalah").value,
        langkah_perbaikan:   document.getElementById("langkahPerbaikan").value,
        langkah_pencegahan:  document.getElementById("langkahPencegahan").value,
        availability:        calcAvailability.textContent,
        mtbf:                calcMTBF.textContent,
        mttr:                calcMTTR.textContent,
        status:              calcStatus.textContent
      };

      fetch('/api/machines/store', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(newData)
      })
      .then(r => {
        if (!r.ok) return r.text().then(t => { throw new Error('HTTP ' + r.status + ': ' + t.substring(0, 200)); });
        return r.json();
      })
      .then(data => {
        if(data.status === 'success') {
          alert("Yeay! Data mesin " + kodeMesinSelect.value + " berhasil masuk ke database.");
          btnReset.click();
        } else {
          alert("Gagal menyimpan data:\n" + (data.message || JSON.stringify(data.errors || {})));
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert("Terjadi kesalahan:\n" + error.message + "\n\nCoba refresh halaman dan simpan ulang.");
      })
      .finally(() => {
        btnSimpan.textContent = originalText;
        btnSimpan.disabled = false;
      });
    });
  </script>
</body>
</html>
