<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Availability Mesin - User</title>
  <link rel="stylesheet" href="css/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <div class="dashboard-app">
    <aside class="sidebar">
      <div class="sidebar-top">
        <img src="img/Logo wikon.png" alt="Logo wikon" class="sidebar-logo" />
      </div>
      <nav class="sidebar-menu">
        <a href="/home" class="menu-link active">Dashboard</a>
        <a href="/reports" class="menu-link">Laporan</a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0; padding:0;">
    @csrf
    <button type="submit" class="menu-link" style="background:none; border:none; cursor:pointer; font-family:inherit; font-size:inherit; display:block; width:100%;">Logout</button>
</form>
      </nav>
    </aside>

    <main class="main-content">
      <header class="main-header">
        <div>
          <h1>Dashboard Availability Mesin</h1>
          <p>Monitoring performa mesin WIKA (User View)</p>
        </div>
        <div class="header-right"><span class="role-pill">User</span></div>
      </header>

      <section class="panel" style="margin-bottom: 20px; padding: 15px 25px;">
        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
          <h2 style="font-size: 15px; margin: 0; color: #1e3a8a; font-weight: 700;">Filter Periode:</h2>
          <div style="display: flex; gap: 10px;">
            <select id="dashMonthFilter" style="padding: 8px 12px; border-radius: 8px; border: 1px solid #d1d5db; outline: none; min-width: 140px;"></select>
            <select id="dashYearFilter" style="padding: 8px 12px; border-radius: 8px; border: 1px solid #d1d5db; outline: none; min-width: 100px;"></select>
          </div>
          <button id="btnApplyFilter" class="btn-login" style="max-width: 130px; margin: 0; padding: 8px 15px;">Cari Data</button>
        </div>
      </section>

      <section class="summary-grid">
        <div class="summary-card summary-primary">
          <h3 id="summaryMonthYear">Total Mesin</h3>
          <p id="totalMesinSummary">0</p>
        </div>
        <div class="summary-card summary-dark">
          <h3>Average Availability</h3>
          <p id="avgAvailabilitySummary">0%</p>
        </div>
        <div class="summary-card summary-dark">
          <h3>Total Plant</h3>
          <p>4</p>
        </div>
        <div class="summary-card summary-dark">
          <h3>Total Breakdown</h3>
          <p id="totalBreakdownSummary">0</p>
        </div>
      </section>

      <section class="panel large-panel compact-panel">
        <div style="text-align: center; margin-bottom: 20px;">
          <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 6px;">Grafik Availability Mesin per Plant</h2>
          <span id="periodeText" style="font-weight: 800; font-size: 15px; color: #374151;">Periode: -</span>
        </div>
        <div class="chart-wrap main-chart-wrap"><canvas id="availabilityChart"></canvas></div>
      </section>

      <div class="panel-header" style="margin-bottom: 16px; margin-top: 8px;">
        <h2>Availability Tertinggi & Terendah per Plant</h2>
      </div>

      <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="panel mini-panel center" id="cardDC">
          <h3 style="color: #1e3a8a; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 14px; font-size: 16px;">DC</h3>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="flex: 1;"><p style="font-size: 10px; font-weight: 800; color: #6b7280;">MAX</p><div class="big-value" id="dcMaxVal" style="font-size: 22px; color: #16a34a;">0%</div><span id="dcMaxMesin">-</span></div>
            <div style="width: 1px; height: 40px; background: #e5e7eb;"></div>
            <div style="flex: 1;"><p style="font-size: 10px; font-weight: 800; color: #6b7280;">MIN</p><div class="big-value" id="dcMinVal" style="font-size: 22px; color: #dc2626;">0%</div><span id="dcMinMesin">-</span></div>
          </div>
        </div>
        <div class="panel mini-panel center" id="cardGC">
            <h3 style="color: #1e3a8a; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 14px; font-size: 16px;">GC</h3>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <div style="flex: 1;"><div class="big-value" id="gcMaxVal" style="font-size: 22px; color: #16a34a;">0%</div><span id="gcMaxMesin">-</span></div>
              <div style="width: 1px; height: 40px; background: #e5e7eb;"></div>
              <div style="flex: 1;"><div class="big-value" id="gcMinVal" style="font-size: 22px; color: #dc2626;">0%</div><span id="gcMinMesin">-</span></div>
            </div>
        </div>
        <div class="panel mini-panel center" id="cardSB">
            <h3 style="color: #1e3a8a; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 14px; font-size: 16px;">SB</h3>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <div style="flex: 1;"><div class="big-value" id="sbMaxVal" style="font-size: 22px; color: #16a34a;">0%</div><span id="sbMaxMesin">-</span></div>
              <div style="width: 1px; height: 40px; background: #e5e7eb;"></div>
              <div style="flex: 1;"><div class="big-value" id="sbMinVal" style="font-size: 22px; color: #dc2626;">0%</div><span id="sbMinMesin">-</span></div>
            </div>
        </div>
        <div class="panel mini-panel center" id="cardCNC">
            <h3 style="color: #1e3a8a; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 14px; font-size: 16px;">CNC</h3>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <div style="flex: 1;"><div class="big-value" id="cncMaxVal" style="font-size: 22px; color: #16a34a;">0%</div><span id="cncMaxMesin">-</span></div>
              <div style="width: 1px; height: 40px; background: #e5e7eb;"></div>
              <div style="flex: 1;"><div class="big-value" id="cncMinVal" style="font-size: 22px; color: #dc2626;">0%</div><span id="cncMinMesin">-</span></div>
            </div>
        </div>
      </section>

      <section class="bottom-panel-grid compact-gap">
        <div class="panel compact-panel">
          <div class="panel-header"><h3>Trend Availability (6 Bulan)</h3></div>
          <div class="chart-wrap small-chart-wrap"><canvas id="trendChart"></canvas></div>
        </div>
        <div class="panel compact-panel">
          <div class="panel-header"><h3>Breakdown Frequency</h3></div>
          <div class="chart-wrap small-chart-wrap"><canvas id="breakdownChart"></canvas></div>
        </div>
      </section>
    </main>
  </div>

  <script>
    let rawData = [];
    const monthOrder = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    const elements = {
      DC: { maxVal: "dcMaxVal", maxMesin: "dcMaxMesin", minVal: "dcMinVal", minMesin: "dcMinMesin" },
      GC: { maxVal: "gcMaxVal", maxMesin: "gcMaxMesin", minVal: "gcMinVal", minMesin: "gcMinMesin" },
      SB: { maxVal: "sbMaxVal", maxMesin: "sbMaxMesin", minVal: "sbMinVal", minMesin: "sbMinMesin" },
      CNC: { maxVal: "cncMaxVal", maxMesin: "cncMaxMesin", minVal: "cncMinVal", minMesin: "cncMinMesin" }
    };

    function renderMainChart(labels, values) {
      const existing = Chart.getChart("availabilityChart");
      if (existing) existing.destroy();
      new Chart(document.getElementById("availabilityChart"), {
        type: "bar",
        data: { labels, datasets: [
          { label: "Standard Min (%)", data: [98, 98, 98, 98], backgroundColor: "#ef4444", borderRadius: 8, barThickness: 28 },
          { label: "Availability (%)", data: values, backgroundColor: "#22c55e", borderRadius: 8, barThickness: 28 }
        ]},
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } } }
      });
    }

    function renderLineCharts(targetMonth, targetYear) {
      let labels = []; let trendData = []; let bdData = [];
      let mIdx = monthOrder.indexOf(targetMonth);
      let yr = parseInt(targetYear);

      for (let i = 5; i >= 0; i--) {
        let currentMIdx = mIdx - i; let currentYr = yr;
        if (currentMIdx < 0) { currentMIdx += 12; currentYr -= 1; }
        let mName = monthOrder[currentMIdx];
        labels.push(mName.substring(0, 3));
        
        let monthData = rawData.filter(d => d.bulan === mName && parseInt(d.tahun) === currentYr);
        let active = monthData.filter(d => parseFloat(d.loading_time) > 0);
        let avg = active.length > 0 ? (active.reduce((s, x) => s + parseFloat(x.availability), 0) / active.length) : 0;
        let totalBD = monthData.reduce((s, x) => s + parseInt(x.freq_breakdown), 0);
        
        trendData.push(parseFloat(avg.toFixed(2)));
        bdData.push(totalBD);
      }

      const exTrend = Chart.getChart("trendChart"); if (exTrend) exTrend.destroy();
      new Chart(document.getElementById("trendChart"), {
        type: 'line',
        data: { labels, datasets: [{ label: "Trend", data: trendData, borderColor: "#2563eb", fill: true, backgroundColor: "rgba(37,99,235,0.1)", tension: 0.4 }] },
        options: { responsive: true, maintainAspectRatio: false }
      });

      const exBD = Chart.getChart("breakdownChart"); if (exBD) exBD.destroy();
      new Chart(document.getElementById("breakdownChart"), {
        type: 'line',
        data: { labels, datasets: [{ label: "Freq", data: bdData, borderColor: "#f59e0b", fill: true, backgroundColor: "rgba(245,158,11,0.1)", tension: 0.4 }] },
        options: { responsive: true, maintainAspectRatio: false }
      });
    }

    function updateDashboardUI(month, year) {
      const filtered = rawData.filter(d => d.bulan === month && String(d.tahun) === String(year));
      document.getElementById("periodeText").textContent = `Periode: ${month} ${year}`;
      document.getElementById("summaryMonthYear").textContent = `Total Mesin - ${month} ${year}`;
      document.getElementById("totalMesinSummary").textContent = filtered.length;

      const activeAll = filtered.filter(d => parseFloat(d.loading_time) > 0);
      const avgAll = activeAll.length > 0 ? (activeAll.reduce((s, i) => s + parseFloat(i.availability), 0) / activeAll.length).toFixed(2) : 0;
      document.getElementById("avgAvailabilitySummary").textContent = avgAll + "%";
      document.getElementById("totalBreakdownSummary").textContent = filtered.reduce((s, i) => s + parseInt(i.freq_breakdown), 0);

      const plants = ["DC", "GC", "SB", "CNC"];
      const chartLabels = []; const chartValues = [];

      plants.forEach(plant => {
        const pData = filtered.filter(d => d.plant === plant);
        const pActive = pData.filter(d => parseFloat(d.loading_time) > 0);
        if (pActive.length > 0) {
          const avgPlant = (pActive.reduce((s, i) => s + parseFloat(i.availability), 0) / pActive.length).toFixed(2);
          chartValues.push(parseFloat(avgPlant)); chartLabels.push([plant, avgPlant + "%"]);
          const max = pActive.reduce((p, c) => (parseFloat(p.availability) > parseFloat(c.availability)) ? p : c);
          const min = pActive.reduce((p, c) => (parseFloat(p.availability) < parseFloat(c.availability)) ? p : c);
          document.getElementById(elements[plant].maxVal).textContent = parseFloat(max.availability).toFixed(1) + "%";
          document.getElementById(elements[plant].maxMesin).textContent = max.kode_mesin;
          document.getElementById(elements[plant].minVal).textContent = parseFloat(min.availability).toFixed(1) + "%";
          document.getElementById(elements[plant].minMesin).textContent = min.kode_mesin;
        } else {
          chartValues.push(0); chartLabels.push([plant, "0%"]);
          document.getElementById(elements[plant].maxVal).textContent = "0%";
          document.getElementById(elements[plant].minVal).textContent = "-";
        }
      });
      renderMainChart(chartLabels, chartValues);
      renderLineCharts(month, year);
    }

    fetch(`/api/machines`).then(res => res.json()).then(data => {
      rawData = data;
      if (data.length > 0) {
        const mF = document.getElementById("dashMonthFilter"); const yF = document.getElementById("dashYearFilter");
        const years = [...new Set(data.map(d => d.tahun))].sort((a,b) => b - a);
        const months = [...new Set(data.map(d => d.bulan))];
        yF.innerHTML = years.map(y => `<option value="${y}">${y}</option>`).join('');
        const sortedMonths = monthOrder.filter(m => months.includes(m));
        mF.innerHTML = sortedMonths.map(m => `<option value="${m}">${m}</option>`).join('');
        
        const latestYear = years[0];
        const monthsInYear = data.filter(d => String(d.tahun) === String(latestYear)).map(d => d.bulan);
        const latestMonth = monthOrder.filter(m => monthsInYear.includes(m)).pop();
        mF.value = latestMonth; yF.value = latestYear;
        updateDashboardUI(latestMonth, latestYear);
      }
    });

    document.getElementById("btnApplyFilter").addEventListener("click", () => {
      updateDashboardUI(document.getElementById("dashMonthFilter").value, document.getElementById("dashYearFilter").value);
    });
  </script>
</body>
</html>