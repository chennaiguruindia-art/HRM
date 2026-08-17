<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#0a8577">
  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
  <link rel="apple-touch-icon" href="{{ asset('pwa/icons/icon-192.png') }}">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <title>Employee Attendance - Guru Group</title>
  <link rel="icon" type="image/png" href="{{ asset('logo/guru.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #0f1225 0%, #1a2340 50%, #0f1225 100%);
      background-attachment: fixed;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      /* fallback for old browsers */
      min-height: 100svh;
      /* small viewport height: excludes browser UI */
      min-height: 100dvh;
      /* dynamic viewport height: tracks address-bar show/hide */
      padding: 20px;
      overflow-x: hidden;
    }

    .card {
      background: rgba(255, 255, 255, .06);
      backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, .1);
      border-radius: 20px;
      padding: 36px 32px;
      width: 100%;
      max-width: 440px;
      text-align: center;
      margin: auto;
    }

    .brand {
      font-family: 'Sora', sans-serif;
      font-size: 1.3rem;
      font-weight: 700;
      margin-bottom: 4px;
    }

    .brand-logo {
      height: 44px;
      width: auto;
      max-width: 100%;
      object-fit: contain;
      margin-bottom: 10px;
    }

    .brand span {
      color: #0fb5a3;
    }

    .brand small {
      color: #6f7794;
      font-weight: 500;
      font-size: .75rem;
      display: block;
      margin-top: 2px;
    }

    .clock-wrap {
      margin: 20px 0 24px;
    }

    .clock-time {
      font-family: 'JetBrains Mono', monospace;
      font-size: 2.6rem;
      font-weight: 500;
      letter-spacing: 3px;
      color: #0fb5a3;
    }

    .clock-date {
      font-size: .82rem;
      color: #6f7794;
      margin-top: 4px;
    }

    .input-group {
      display: flex;
      gap: 8px;
      margin-bottom: 20px;
    }

    .input-group input {
      flex: 1;
      min-width: 0;
      padding: 10px 14px;
      border-radius: 10px;
      border: 1px solid rgba(255, 255, 255, .12);
      background: rgba(255, 255, 255, .06);
      color: #fff;
      font-size: .9rem;
      outline: none;
      font-family: 'JetBrains Mono', monospace;
    }

    .input-group input::placeholder {
      color: #6f7794;
    }

    .input-group input:focus {
      border-color: #0fb5a3;
    }

    .input-group button {
      padding: 10px 18px;
      border-radius: 10px;
      border: none;
      background: #0fb5a3;
      color: #fff;
      font-weight: 600;
      font-size: .85rem;
      cursor: pointer;
      white-space: nowrap;
    }

    .input-group button:hover {
      background: #0d9e8e;
    }

    .input-group button:disabled {
      opacity: .5;
      cursor: not-allowed;
    }

    .emp-info {
      display: none;
      background: rgba(255, 255, 255, .05);
      border-radius: 14px;
      padding: 18px;
      margin-bottom: 20px;
      text-align: left;
    }

    .emp-info.show {
      display: block;
    }

    .emp-info .top {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 12px;
    }

    .emp-info .top img {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      object-fit: cover;
    }

    .emp-info .top .name {
      font-weight: 600;
      font-size: .95rem;
    }

    .emp-info .top .role {
      font-size: .78rem;
      color: #9499b5;
    }

    .emp-info .detail {
      display: flex;
      gap: 12px;
      font-size: .8rem;
      color: #9499b5;
    }

    .emp-info .detail span {
      flex: 1;
    }

    .emp-info .detail strong {
      color: #fff;
      display: block;
      font-size: .82rem;
      margin-top: 2px;
    }

    .btn-group {
      display: none;
      gap: 12px;
    }

    .btn-group.show {
      display: flex;
    }

    .btn-group .btn {
      flex: 1;
      padding: 14px;
      border-radius: 12px;
      border: none;
      font-weight: 600;
      font-size: .9rem;
      cursor: pointer;
      transition: background .2s, opacity .2s;
    }

    .btn-group .btn:disabled {
      opacity: .35;
      cursor: not-allowed;
    }

    .btn-clock-in {
      background: #0fb5a3;
      color: #fff;
    }

    .btn-clock-in:hover:not(:disabled) {
      background: #0d9e8e;
    }

    .btn-clock-out {
      background: #ef5d6f;
      color: #fff;
    }

    .btn-clock-out:hover:not(:disabled) {
      background: #d94a5c;
    }

    .msg {
      margin-top: 16px;
      font-size: .82rem;
      padding: 10px 14px;
      border-radius: 10px;
      display: none;
    }

    .msg.success {
      display: block;
      background: rgba(15, 181, 163, .15);
      color: #0fb5a3;
    }

    .msg.error {
      display: block;
      background: rgba(239, 93, 111, .15);
      color: #ef5d6f;
    }

    .msg.info {
      display: block;
      background: rgba(79, 91, 213, .15);
      color: #7b85d8;
    }

    .back-link {
      display: inline-block;
      margin-top: 18px;
      color: #6f7794;
      font-size: .8rem;
      text-decoration: none;
      pointer-events: none;
      opacity: .55;
    }

    .back-link:hover {
      color: #fff;
    }

    .back-link.enabled {
      pointer-events: auto;
      opacity: 1;
      background: rgba(15, 181, 163, .15);
      color: #0fb5a3;
      border: 1px solid rgba(15, 181, 163, .4);
      padding: 9px 20px;
      border-radius: 30px;
      font-weight: 600;
    }

    .back-link.enabled:hover {
      background: rgba(15, 181, 163, .28);
      color: #0fb5a3;
    }

    .spinner {
      display: none;
      width: 18px;
      height: 18px;
      border: 2px solid rgba(255, 255, 255, .2);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin .6s linear infinite;
      margin: 0 auto;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    .hours-badge {
      font-size: .78rem;
      color: #9499b5;
      margin-top: 10px;
      display: none;
    }

    .hours-badge.show {
      display: block;
    }

    /* Daily report modal for clock out */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(10, 12, 25, .75);
      backdrop-filter: blur(6px);
      z-index: 1000;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .modal-overlay.show {
      display: flex;
    }

    .modal-panel {
      background: #1a2340;
      border: 1px solid rgba(255, 255, 255, .12);
      border-radius: 16px;
      padding: 22px;
      width: 100%;
      max-width: 460px;
    }

    .modal-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 6px;
    }

    .modal-close {
      background: none;
      border: none;
      color: #9499b5;
      font-size: 1.2rem;
      line-height: 1;
      cursor: pointer;
      padding: 4px;
    }

    .btn-ghost {
      background: rgba(255, 255, 255, .08);
      border: 1px solid rgba(255, 255, 255, .15);
      color: #fff;
      padding: 12px 18px;
      border-radius: 12px;
      font-weight: 600;
      font-size: .85rem;
      cursor: pointer;
    }

    .btn-ghost:hover {
      background: rgba(255, 255, 255, .14);
    }

    /* Smaller phones / short viewports: tighten spacing so nothing feels
       like it's floating in a huge empty gradient */
    @media (max-width: 420px) {
      .card {
        padding: 28px 22px;
        border-radius: 16px;
      }

      .clock-time {
        font-size: 2.1rem;
        letter-spacing: 2px;
      }

      .clock-wrap {
        margin: 16px 0 20px;
      }
    }

    @media (max-height: 700px) {
      body {
        align-items: flex-start;
        padding-top: 40px;
      }
    }
  </style>
</head>

<body>
  <div class="card">
    <img src="{{ asset('logo/guru.png') }}" class="brand-logo" alt="Guru Group">
    <div class="brand">Guru Group <span>Attendance</span><small>Employee Portal</small></div>

    <div class="clock-wrap">
      <div class="clock-time" id="liveTime">--:--:--</div>
      <div class="clock-date" id="liveDate">---</div>
    </div>

    <div class="input-group">
      <input type="text" id="empIdInput" placeholder="Enter Employee ID" autocomplete="off">
      <button id="lookupBtn" onclick="lookupEmployee()">Verify</button>
    </div>

    <div class="spinner" id="spinner"></div>

    <div class="emp-info" id="empInfo">
      <div class="top">
        <img id="empImg" src="" alt="">
        <div>
          <div class="name" id="empName">-</div>
          <div class="role" id="empRole">-</div>
        </div>
      </div>
      <div class="detail">
        <span>Branch <strong id="empBranch">-</strong></span>
        <span>Employee ID <strong id="empId">-</strong></span>
      </div>
    </div>

    <div class="btn-group" id="btnGroup">
      <button class="btn btn-clock-in" id="clockInBtn" onclick="doClockIn()">Clock In</button>
      <button class="btn btn-clock-out" id="clockOutBtn" onclick="doClockOut()">Clock Out</button>
    </div>
    <div class="hours-badge" id="hoursBadge"></div>

    <div class="msg" id="msg"></div>

    <a href="/" class="back-link" id="dashLink"> Employee dashboard &rarr;</a>
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
      const h = String(now.getHours()).padStart(2, '0');
      const m = String(now.getMinutes()).padStart(2, '0');
      const s = String(now.getSeconds()).padStart(2, '0');
      document.getElementById('liveTime').textContent = h + ':' + m + ':' + s;
      const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
      const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      document.getElementById('liveDate').textContent = days[now.getDay()] + ', ' + months[now.getMonth()] + ' ' + now.getDate() + ', ' + now.getFullYear();
    }
    updateClock();
    setInterval(updateClock, 1000);

    $('#empIdInput').on('keydown', function(e) {
      if (e.key === 'Enter') lookupEmployee();
    });

    function showMsg(text, type) {
      const el = $('#msg');
      el.removeClass('success error info').addClass(type).text(text).show();
      if (type !== 'error') setTimeout(function() {
        el.fadeOut();
      }, 4000);
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
        data: {
          employee_id: id
        }
      }).done(function(resp) {
        currentEmployeeId = resp.employee.id;
        $('#empImg').attr('src', resp.employee.img);
        $('#empName').text(resp.employee.name);
        $('#empRole').text(resp.employee.designation);
        $('#empBranch').text(resp.employee.branch);
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
          showMsg('Day complete. Clock Out at ' + att.check_out, 'info');
        } else if (clockedIn) {
          showMsg('Clocked in at ' + att.check_in + '. Ready for clock out.', 'info');
        }
      }).fail(function(xhr) {
        const msg = xhr.responseJSON ? xhr.responseJSON.message : ('Request failed (HTTP ' + (xhr.status || 'network error') + ')');
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

      const data = {
        employee_id: currentEmployeeId
      };
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
      if (!confirm('Are you sure you want to clock out?')) return;
      submitClockOut();
    }

    function submitClockOut() {
      if (!currentEmployeeId) return;
      $('#clockInBtn').prop('disabled', true);
      $('#clockOutBtn').prop('disabled', true);
      $('#msg').hide().removeClass('success error info');

      const data = {
        employee_id: currentEmployeeId
      };
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
          $('#hoursBadge').text('Total hours: ' + resp.hours_worked).addClass('show');
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