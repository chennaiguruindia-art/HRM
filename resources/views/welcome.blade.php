<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Guru Group Attendance</title>
  <link rel="icon" type="image/png" href="{{ asset('logo/guru.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #0f1225 0%, #1a2340 50%, #0f1225 100%);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px;
    }
    .container {
      display: flex;
      align-items: center;
      gap: 60px;
      max-width: 1000px;
      width: 100%;
    }
    .left {
      flex: 1;
    }
    .left .brand {
      font-family: 'Sora', sans-serif;
      font-size: 2rem;
      font-weight: 800;
      margin-bottom: 6px;
    }
    .left .brand-logo {
      height: 56px;
      width: auto;
      object-fit: contain;
      margin-bottom: 14px;
    }
    .left .brand span {
      color: #0fb5a3;
    }
    .left .sub {
      color: #9499b5;
      font-size: .95rem;
      margin-bottom: 40px;
      line-height: 1.6;
    }
    .portal-grid {
      display: flex;
      gap: 20px;
    }
    .portal-card {
      background: rgba(255,255,255,.06);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255,255,255,.1);
      border-radius: 18px;
      padding: 28px 24px;
      flex: 1;
      text-align: center;
      transition: transform .2s ease, background .2s ease;
      cursor: pointer;
    }
    .portal-card:hover {
      background: rgba(255,255,255,.1);
      transform: translateY(-4px);
    }
    .portal-card .icon {
      width: 52px;
      height: 52px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      margin: 0 auto 14px;
    }
    .portal-card h3 {
      font-family: 'Sora', sans-serif;
      font-size: 1.05rem;
      margin-bottom: 6px;
    }
    .portal-card p {
      font-size: .8rem;
      color: #9499b5;
      margin-bottom: 18px;
    }
    .portal-card .btn {
      display: inline-block;
      padding: 8px 22px;
      border-radius: 30px;
      font-size: .82rem;
      font-weight: 600;
      text-decoration: none;
      transition: background .2s;
    }
    .btn-employee {
      background: #0fb5a3;
      color: #fff;
    }
    .btn-employee:hover {
      background: #0d9e8e;
    }
    .btn-admin {
      background: #4f5bd5;
      color: #fff;
    }
    .btn-admin:hover {
      background: #3f49c2;
    }
    .right {
      flex-shrink: 0;
    }
    .right .illu {
      width: 340px;
      height: auto;
    }
    .right .illu svg {
      width: 100%;
      height: auto;
    }

    .clock-wrap {
      text-align: center;
      margin-bottom: 30px;
    }
    .clock-time {
      font-family: 'Sora', sans-serif;
      font-size: 3.2rem;
      font-weight: 700;
      letter-spacing: 2px;
      color: #fff;
      line-height: 1;
    }
    .clock-date {
      font-size: .85rem;
      color: #9499b5;
      margin-top: 6px;
    }

    @media(max-width:768px) {
      .container { flex-direction: column-reverse; gap: 30px; }
      .right .illu { width: 200px; }
      .portal-grid { flex-direction: column; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left">
      <img src="{{ asset('logo/guru.png') }}" class="brand-logo" alt="Guru Group">
      <div class="brand">Guru Group <span>Attendance</span></div>
      <div class="sub">Streamlined workforce management — track check-ins, leaves, and payroll all in one place.</div>

      <div class="clock-wrap">
        <div class="clock-time" id="liveTime">--:--:--</div>
        <div class="clock-date" id="liveDate">---</div>
      </div>

      <div class="portal-grid">
        <div class="portal-card" data-href="{{ route('employee.login') }}">
          <div class="icon" style="background:rgba(15,181,163,.18);color:#0fb5a3;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
          <h3>Employee Portal</h3>
          <p>Mark attendance &amp; view your records</p>
          <a href="{{ route('employee.login') }}" class="btn btn-employee">Employee Login</a>
        </div>

        <div class="portal-card" data-href="{{ route('login') }}?admin=1">
          <div class="icon" style="background:rgba(79,91,213,.18);color:#4f5bd5;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <h3>Admin Portal</h3>
          <p>Manage employees, reports &amp; settings</p>
          <a href="{{ route('login') }}" class="btn btn-admin">Admin Login</a>
        </div>
      </div>
    </div>

    <div class="right">
      <div class="illu">
        <svg viewBox="0 0 340 280" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="20" y="40" width="300" height="200" rx="16" fill="rgba(255,255,255,.04)" stroke="rgba(255,255,255,.1)" stroke-width="1"/>
          <rect x="40" y="60" width="60" height="60" rx="8" fill="rgba(15,181,163,.15)"/>
          <circle cx="70" cy="90" r="18" fill="#0fb5a3" opacity=".7"/>
          <rect x="120" y="66" width="80" height="6" rx="3" fill="rgba(255,255,255,.2)"/>
          <rect x="120" y="80" width="50" height="5" rx="2.5" fill="rgba(255,255,255,.12)"/>
          <rect x="120" y="93" width="65" height="5" rx="2.5" fill="rgba(255,255,255,.08)"/>

          <rect x="40" y="140" width="60" height="60" rx="8" fill="rgba(79,91,213,.15)"/>
          <circle cx="70" cy="170" r="18" fill="#4f5bd5" opacity=".7"/>
          <rect x="120" y="146" width="70" height="6" rx="3" fill="rgba(255,255,255,.2)"/>
          <rect x="120" y="160" width="45" height="5" rx="2.5" fill="rgba(255,255,255,.12)"/>
          <rect x="120" y="173" width="55" height="5" rx="2.5" fill="rgba(255,255,255,.08)"/>

          <rect x="40" y="220" width="60" height="60" rx="8" fill="rgba(245,165,36,.15)"/>
          <circle cx="70" cy="250" r="18" fill="#f5a524" opacity=".7"/>
          <rect x="120" y="226" width="60" height="6" rx="3" fill="rgba(255,255,255,.2)"/>
          <rect x="120" y="240" width="55" height="5" rx="2.5" fill="rgba(255,255,255,.12)"/>
          <rect x="120" y="253" width="40" height="5" rx="2.5" fill="rgba(255,255,255,.08)"/>

          <circle cx="260" cy="100" r="40" fill="none" stroke="rgba(15,181,163,.2)" stroke-width="6"/>
          <path d="M260 80v20l13 13" stroke="#0fb5a3" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>

          <rect x="220" y="165" width="80" height="14" rx="7" fill="rgba(15,181,163,.12)"/>
          <rect x="230" y="175" width="60" height="14" rx="7" fill="rgba(245,165,36,.12)"/>
          <rect x="240" y="185" width="40" height="14" rx="7" fill="rgba(239,93,111,.12)"/>

          <rect x="220" y="220" width="80" height="4" rx="2" fill="rgba(79,91,213,.2)"/>
          <rect x="220" y="232" width="60" height="4" rx="2" fill="rgba(79,91,213,.15)"/>
          <rect x="220" y="244" width="70" height="4" rx="2" fill="rgba(79,91,213,.1)"/>
        </svg>
      </div>
    </div>
  </div>

  <script>
    function updateClock() {
      const now = new Date();
      const hours = String(now.getHours()).padStart(2, '0');
      const mins = String(now.getMinutes()).padStart(2, '0');
      const secs = String(now.getSeconds()).padStart(2, '0');
      document.getElementById('liveTime').textContent = hours + ':' + mins + ':' + secs;

      const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
      const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
      document.getElementById('liveDate').textContent = days[now.getDay()] + ', ' + months[now.getMonth()] + ' ' + now.getDate() + ', ' + now.getFullYear();
    }
    updateClock();
    setInterval(updateClock, 1000);

    document.querySelectorAll('.portal-card').forEach(function (card) {
      card.addEventListener('click', function () {
        window.location.href = card.dataset.href;
      });
    });
  </script>
</body>
</html>