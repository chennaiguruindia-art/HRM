<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#0d9488">
  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
  <link rel="apple-touch-icon" href="{{ asset('pwa/icons/icon-192.png') }}">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <title>Employee Attendance — Guru Group</title>
  <link rel="icon" type="image/png" href="{{ asset('logo/guru.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

  <style>
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --bg: #f8fafc;
      --card-bg: #ffffff;
      --text-main: #0f172a;
      --text-muted: #64748b;
      --text-light: #94a3b8;
      --border: #e2e8f0;
      --border-focus: #0d9488;
      --teal: #0d9488;
      --teal-hover: #0f766e;
      --teal-light: #f0fdfa;
      --rose: #e11d48;
      --rose-hover: #be123c;
      --rose-light: #fff1f2;
      --shadow-card: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
      --radius: 16px;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      background-color: var(--bg);
      background-image: 
        radial-gradient(at 100% 0%, rgba(13, 148, 136, 0.06) 0px, transparent 50%),
        radial-gradient(at 0% 100%, rgba(59, 130, 246, 0.05) 0px, transparent 50%);
      color: var(--text-main);
      min-height: 100vh;
      min-height: 100dvh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
      line-height: 1.5;
      -webkit-font-smoothing: antialiased;
    }

    .portal-container {
      width: 100%;
      max-width: 440px;
      margin: auto;
    }

    .card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow-card);
      padding: 36px 32px;
      text-align: center;
    }

    /* Brand Header */
    .brand-section {
      margin-bottom: 20px;
    }

    .brand-logo {
      height: 48px;
      width: auto;
      object-fit: contain;
      margin-bottom: 12px;
    }

    .brand-title {
      font-size: 1.25rem;
      font-weight: 800;
      color: var(--text-main);
      letter-spacing: -0.02em;
    }

    .brand-title span {
      color: var(--teal);
    }

    .brand-subtitle {
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-top: 2px;
    }

    /* Clock Widget */
    .clock-widget {
      background: #f1f5f9;
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 12px 16px;
      margin: 18px 0 24px;
    }

    .clock-digits {
      font-family: 'JetBrains Mono', monospace;
      font-size: 1.85rem;
      font-weight: 600;
      letter-spacing: 1px;
      color: var(--teal);
      line-height: 1.2;
    }

    .clock-date {
      font-size: 0.78rem;
      color: var(--text-muted);
      font-weight: 500;
      margin-top: 4px;
    }

    /* Input Group */
    .input-wrapper {
      margin-bottom: 18px;
    }

    .input-label {
      display: block;
      text-align: left;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--text-main);
      margin-bottom: 6px;
    }

    .input-row {
      display: flex;
      gap: 8px;
    }

    .input-field {
      flex: 1;
      min-width: 0;
      padding: 11px 14px;
      font-size: 0.95rem;
      font-family: inherit;
      color: var(--text-main);
      background: #ffffff;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      outline: none;
      transition: all 0.2s ease;
    }

    .input-field:focus {
      border-color: var(--border-focus);
      box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
    }

    .input-field::placeholder {
      color: var(--text-light);
    }

    .btn-verify {
      padding: 11px 18px;
      font-size: 0.88rem;
      font-weight: 600;
      font-family: inherit;
      color: #ffffff;
      background: var(--teal);
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.2s ease;
      white-space: nowrap;
    }

    .btn-verify:hover:not(:disabled) {
      background: var(--teal-hover);
    }

    .btn-verify:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    /* Loading Spinner */
    .spinner {
      display: none;
      width: 24px;
      height: 24px;
      border: 3px solid rgba(13, 148, 136, 0.2);
      border-top-color: var(--teal);
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
      margin: 16px auto;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Employee Info Card */
    .emp-profile {
      display: none;
      background: #f8fafc;
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 20px;
      text-align: left;
    }

    .emp-profile.show {
      display: block;
      animation: fadeIn 0.25s ease;
    }

    .emp-profile-top {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 12px;
    }

    .emp-avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: var(--teal);
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.15rem;
      font-weight: 700;
      flex-shrink: 0;
    }

    .emp-profile-name {
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--text-main);
    }

    .emp-profile-role {
      font-size: 0.78rem;
      color: var(--text-muted);
      font-weight: 500;
    }

    .emp-profile-meta {
      display: flex;
      gap: 16px;
      border-top: 1px dashed var(--border);
      padding-top: 10px;
      font-size: 0.78rem;
      color: var(--text-muted);
    }

    .emp-profile-meta strong {
      display: block;
      color: var(--text-main);
      font-size: 0.82rem;
      margin-top: 1px;
    }

    /* Action Buttons */
    .actions-row {
      display: none;
      gap: 12px;
      margin-bottom: 16px;
    }

    .actions-row.show {
      display: flex;
    }

    .btn-action {
      flex: 1;
      padding: 13px 16px;
      font-size: 0.92rem;
      font-weight: 700;
      font-family: inherit;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.2s ease;
    }

    .btn-action svg {
      width: 18px;
      height: 18px;
    }

    .btn-action:disabled {
      opacity: 0.4;
      cursor: not-allowed;
      filter: grayscale(40%);
    }

    .btn-clockin {
      background: var(--teal);
      color: #ffffff;
    }

    .btn-clockin:hover:not(:disabled) {
      background: var(--teal-hover);
    }

    .btn-clockout {
      background: var(--rose);
      color: #ffffff;
    }

    .btn-clockout:hover:not(:disabled) {
      background: var(--rose-hover);
    }

    /* Hours Worked Badge */
    .hours-pill {
      display: none;
      font-size: 0.82rem;
      font-weight: 600;
      color: var(--teal-hover);
      background: var(--teal-light);
      border: 1px solid rgba(13, 148, 136, 0.2);
      padding: 6px 14px;
      border-radius: 9999px;
      margin: 10px auto;
    }

    .hours-pill.show {
      display: inline-block;
    }

    /* Messages / Alerts */
    .alert-box {
      display: none;
      font-size: 0.82rem;
      padding: 10px 14px;
      border-radius: 10px;
      margin-top: 14px;
      text-align: left;
    }

    .alert-box.success {
      display: block;
      background: var(--teal-light);
      border: 1px solid rgba(13, 148, 136, 0.3);
      color: #0f766e;
    }

    .alert-box.error {
      display: block;
      background: var(--rose-light);
      border: 1px solid rgba(225, 29, 72, 0.3);
      color: var(--rose-hover);
    }

    .alert-box.info {
      display: block;
      background: #eff6ff;
      border: 1px solid rgba(59, 130, 246, 0.3);
      color: #1d4ed8;
    }

    /* Dashboard Link */
    .btn-dashboard {
      display: none;
      width: 100%;
      margin-top: 14px;
      padding: 11px 16px;
      font-size: 0.85rem;
      font-weight: 600;
      text-decoration: none;
      border-radius: 10px;
      text-align: center;
      transition: all 0.2s ease;
      background: #f1f5f9;
      color: var(--text-main);
      border: 1px solid var(--border);
    }

    .btn-dashboard.enabled {
      display: block;
      background: var(--teal-light);
      color: var(--teal-hover);
      border-color: rgba(13, 148, 136, 0.3);
    }

    .btn-dashboard.enabled:hover {
      background: #ccfbf1;
    }

    /* Back Link */
    .back-nav {
      margin-top: 20px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 0.82rem;
      color: var(--text-muted);
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .back-nav:hover {
      color: var(--text-main);
    }

    .back-nav svg {
      width: 14px;
      height: 14px;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(6px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 480px) {
      .card {
        padding: 28px 20px;
      }
      .clock-digits {
        font-size: 1.6rem;
      }
    }
  </style>
</head>

<body>

  <div class="portal-container">
    
    <div class="card">
      
      <!-- Logo & Titles -->
      <div class="brand-section">
        <img src="{{ asset('logo/guru.png') }}" class="brand-logo" alt="Guru Group">
        <h1 class="brand-title">Guru Group <span>Attendance</span></h1>
        <div class="brand-subtitle">Employee Portal</div>
      </div>

      <!-- Live Clock -->
      <div class="clock-widget">
        <div class="clock-digits" id="liveTime">--:--:--</div>
        <div class="clock-date" id="liveDate">Loading date...</div>
      </div>

      <!-- Employee ID Input -->
      <div class="input-wrapper">
        <label for="empIdInput" class="input-label">Employee ID</label>
        <div class="input-row">
          <input type="text" id="empIdInput" class="input-field" placeholder="Enter ID (e.g. 1001)" autocomplete="off">
          <button id="lookupBtn" class="btn-verify" onclick="lookupEmployee()">Verify</button>
        </div>
      </div>

      <!-- Spinner -->
      <div class="spinner" id="spinner"></div>

      <!-- Verified Employee Info -->
      <div class="emp-profile" id="empInfo">
        <div class="emp-profile-top">
          <div class="emp-avatar" id="empAvatar">-</div>
          <div>
            <div class="emp-profile-name" id="empName">-</div>
            <div class="emp-profile-role" id="empRole">-</div>
          </div>
        </div>
        <div class="emp-profile-meta">
          <div>Branch: <strong id="empBranch">-</strong></div>
          <div>ID: <strong id="empId">-</strong></div>
        </div>
      </div>

      <!-- Clock In / Out Action Buttons -->
      <div class="actions-row" id="btnGroup">
        <button class="btn-action btn-clockin" id="clockInBtn" onclick="doClockIn()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
          Clock In
        </button>
        <button class="btn-action btn-clockout" id="clockOutBtn" onclick="doClockOut()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
          Clock Out
        </button>
      </div>

      <!-- Hours Badge -->
      <div class="hours-pill" id="hoursBadge"></div>

      <!-- Feedback Messages -->
      <div class="alert-box" id="msg"></div>

      <!-- Dashboard Link -->
      <a href="/" class="btn-dashboard" id="dashLink">Go to Employee Dashboard &rarr;</a>

    </div>

    <!-- Back to Home -->
    <div style="text-align: center;">
      <a href="{{ url('/') }}" class="back-nav">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Back to Portal Selection
      </a>
    </div>

  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    let currentEmployeeId = null;
    let currentLat = null;
    let currentLng = null;
    let currentLocationName = null;

    function getLocation() {
      if (!navigator.geolocation) return;
      navigator.geolocation.getCurrentPosition(function(pos) {
        currentLat = pos.coords.latitude;
        currentLng = pos.coords.longitude;
        $.post("{{ route('employee.address-from-coords') }}", {
          lat: currentLat,
          lng: currentLng,
          _token: "{{ csrf_token() }}"
        }).done(function(resp) {
          currentLocationName = resp.address || '';
        }).fail(function() {
          currentLocationName = '';
        });
      }, function() {
        currentLat = null;
        currentLng = null;
        currentLocationName = null;
      }, {
        timeout: 5000,
        enableHighAccuracy: true
      });
    }
    getLocation();

    function updateClock() {
      const now = new Date();
      let hours = now.getHours();
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const seconds = String(now.getSeconds()).padStart(2, '0');
      const ampm = hours >= 12 ? 'PM' : 'AM';
      hours = hours % 12;
      hours = hours ? String(hours).padStart(2, '0') : '12';
      
      document.getElementById('liveTime').textContent = `${hours}:${minutes}:${seconds} ${ampm}`;
      
      const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
      document.getElementById('liveDate').textContent = now.toLocaleDateString('en-US', options);
    }
    updateClock();
    setInterval(updateClock, 1000);

    $('#empIdInput').on('keydown', function(e) {
      if (e.key === 'Enter') lookupEmployee();
    });

    function showMsg(text, type) {
      const el = $('#msg');
      el.removeClass('success error info').addClass(type).text(text).show();
      if (type !== 'error') {
        setTimeout(function() {
          el.fadeOut();
        }, 4500);
      }
    }

    function lookupEmployee() {
      const id = $('#empIdInput').val().trim();
      if (!id) {
        showMsg('Please enter an Employee ID.', 'error');
        return;
      }

      $('#lookupBtn').prop('disabled', true);
      $('#spinner').show();
      $('#empInfo').removeClass('show');
      $('#btnGroup').removeClass('show');
      $('#hoursBadge').removeClass('show').hide();
      $('#msg').hide().removeClass('success error info');

      $.ajax({
        url: '{{ route("employee.lookup") }}',
        method: 'POST',
        data: { employee_id: id }
      }).done(function(resp) {
        currentEmployeeId = resp.employee.id;
        $('#empAvatar').text(resp.employee.name.charAt(0).toUpperCase());
        $('#empName').text(resp.employee.name);
        $('#empRole').text(resp.employee.designation || 'Staff');
        $('#empBranch').text(resp.employee.branch || 'Main');
        $('#empId').text(resp.employee.id);
        $('#empInfo').addClass('show');

        var dashUrl = '{{ route("employee.dashboard", ["employee_id" => "__ID__"]) }}'.replace('__ID__', resp.employee.id);
        $('#dashLink').attr('href', dashUrl).addClass('enabled');

        const att = resp.attendance;
        const clockedIn = att && att.check_in;
        const clockedOut = att && att.check_out;

        $('#clockInBtn').prop('disabled', clockedIn);
        $('#clockOutBtn').prop('disabled', !clockedIn || clockedOut);
        $('#btnGroup').addClass('show');

        if (clockedOut) {
          showMsg('Day complete. Clocked out at ' + att.check_out, 'info');
        } else if (clockedIn) {
          showMsg('Clocked in at ' + att.check_in + '. Ready to clock out.', 'info');
        }
      }).fail(function(xhr) {
        const msg = xhr.responseJSON ? xhr.responseJSON.message : ('Lookup failed (HTTP ' + (xhr.status || 'network error') + ')');
        showMsg(msg, 'error');
      }).always(function() {
        $('#lookupBtn').prop('disabled', false);
        $('#spinner').hide();
      });
    }

    function doClockIn() {
      if (!currentEmployeeId) return;
      $('#clockInBtn').prop('disabled', true);
      $('#clockOutBtn').prop('disabled', true);
      $('#msg').hide().removeClass('success error info');

      const data = { employee_id: currentEmployeeId };
      if (currentLat && currentLng) {
        data.latitude = currentLat;
        data.longitude = currentLng;
        data.location_name = currentLocationName || '';
      }

      $.ajax({
        url: '{{ route("employee.clock-in") }}',
        method: 'POST',
        data: data
      }).done(function(resp) {
        showMsg(resp.message, 'success');
        $('#clockInBtn').prop('disabled', true);
        $('#clockOutBtn').prop('disabled', false);
      }).fail(function(xhr) {
        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Clock In failed.';
        showMsg(msg, 'error');
        $('#clockInBtn').prop('disabled', false);
      });
    }

    function doClockOut() {
      if (!currentEmployeeId) return;
      if (!confirm('Confirm Clock Out for today?')) return;
      
      $('#clockInBtn').prop('disabled', true);
      $('#clockOutBtn').prop('disabled', true);
      $('#msg').hide().removeClass('success error info');

      const data = { employee_id: currentEmployeeId };
      if (currentLat && currentLng) {
        data.latitude = currentLat;
        data.longitude = currentLng;
        data.location_name = currentLocationName || '';
      }

      $.ajax({
        url: '{{ route("employee.clock-out") }}',
        method: 'POST',
        data: data
      }).done(function(resp) {
        showMsg(resp.message, 'success');
        $('#clockOutBtn').prop('disabled', true);
        if (resp.hours_worked) {
          $('#hoursBadge').text('Total Hours: ' + resp.hours_worked).addClass('show');
        }
      }).fail(function(xhr) {
        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Clock Out failed.';
        showMsg(msg, 'error');
        $('#clockOutBtn').prop('disabled', false);
      });
    }
  </script>

  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register("{{ asset('sw.js') }}").catch(function() {});
      });
    }
  </script>
</body>

</html>