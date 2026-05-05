<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Dashboard Availability Mesin</title>
  <link rel="stylesheet" href="/css/style.css" />
</head>
<body class="login-page">

  <div class="login-wrapper">
    <div class="login-left">
      <div class="login-left-content">
        <img src="/img/Logo wikon.png" alt="Logo wikon" class="login-logo" />
        <h1>Dashboard Availability Mesin</h1>
        <p>
          Sistem monitoring dan penginputan availability mesin bulanan
          untuk Die Casting, Gravity Casting, Sand Blowing, dan CNC.
        </p>
        <div class="login-info-card">
          <h3>Fitur Utama</h3>
          <ul>
            <li>Input data availability bulanan</li>
            <li>Dashboard grafik per plant</li>
            <li>Pencarian histori data sebelumnya</li>
            <li>Role Admin dan User</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="login-right">
      <div class="login-box">
        <div class="login-box-header">
          <h2>Masuk</h2>
          <p>Masuk ke dashboard availability mesin</p>
        </div>

        @if (session('error'))
          <p style="color:#dc2626; margin-bottom:12px; font-size:14px;">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
          @csrf

          {{-- Role --}}
          <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
              <option value="">-- Pilih Role --</option>
              <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="user"  {{ old('role') === 'user'  ? 'selected' : '' }}>User</option>
            </select>
            @error('role')
              <p style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</p>
            @enderror
          </div>

          {{-- Email & Password — hanya tampil jika pilih Admin --}}
          <div id="adminFields" style="display:none;">
            <div class="form-group">
              <label for="email">Email</label>
              <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email admin"
                autocomplete="email"
              />
              @error('email')
                <p style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</p>
              @enderror
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input
                id="password"
                name="password"
                type="password"
                placeholder="Masukkan password admin"
                autocomplete="current-password"
              />
              @error('password')
                <p style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</p>
              @enderror
            </div>

            <div class="form-group" style="display:flex; align-items:center; gap:8px;">
              <input type="checkbox" id="remember" name="remember" value="1" style="width:auto;" />
              <label for="remember" style="margin:0; font-weight:normal;">Ingat saya</label>
            </div>
          </div>

          <button type="submit" class="btn-login">Login</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    const roleSelect   = document.getElementById('role');
    const adminFields  = document.getElementById('adminFields');
    const emailInput   = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    function toggleAdminFields() {
      const isAdmin = roleSelect.value === 'admin';
      adminFields.style.display = isAdmin ? 'block' : 'none';
      emailInput.required    = isAdmin;
      passwordInput.required = isAdmin;
    }

    // Jalankan saat halaman load (untuk old('role') dari validasi)
    toggleAdminFields();
    roleSelect.addEventListener('change', toggleAdminFields);
  </script>

</body>
</html>