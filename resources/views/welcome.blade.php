<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Guru Group — Attendance & Management Portal. Real-time attendance tracking, payroll, and branch reports.">
  <title>Guru Group — Attendance & Reports Portal</title>
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
      --border-hover: #cbd5e1;
      --teal: #0d9488;
      --teal-hover: #0f766e;
      --teal-light: #f0fdfa;
      --indigo: #4338ca;
      --indigo-hover: #3730a3;
      --indigo-light: #eef2ff;
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.07), 0 2px 4px -2px rgb(0 0 0 / 0.05);
      --shadow-lg: 0 12px 24px -4px rgb(0 0 0 / 0.08), 0 4px 6px -2px rgb(0 0 0 / 0.04);
      --radius-sm: 8px;
      --radius-md: 14px;
      --radius-lg: 20px;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      background-color: var(--bg);
      background-image: 
        radial-gradient(at 100% 0%, rgba(13, 148, 136, 0.05) 0px, transparent 50%),
        radial-gradient(at 0% 100%, rgba(67, 56, 202, 0.05) 0px, transparent 50%),
        radial-gradient(at 50% 50%, rgba(248, 250, 252, 1) 0px, transparent 100%);
      color: var(--text-main);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      line-height: 1.5;
      -webkit-font-smoothing: antialiased;
    }

    /* Navbar */
    .top-nav {
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      padding: 20px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .brand-link {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: var(--text-main);
    }

    .brand-logo {
      height: 44px;
      width: auto;
      object-fit: contain;
    }

    .brand-title {
      font-size: 1.25rem;
      font-weight: 800;
      letter-spacing: -0.02em;
      color: var(--text-main);
    }

    .brand-title span {
      color: var(--teal);
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 14px;
      border-radius: 9999px;
      background: #ffffff;
      border: 1px solid var(--border);
      box-shadow: var(--shadow-sm);
      font-size: 0.78rem;
      font-weight: 600;
      color: var(--text-muted);
    }

    .status-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #10b981;
      position: relative;
    }

    .status-dot::after {
      content: '';
      position: absolute;
      inset: -3px;
      border-radius: 50%;
      background: rgba(16, 185, 129, 0.35);
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.8; }
      50% { transform: scale(1.6); opacity: 0; }
    }

    /* Main Container */
    .main-wrapper {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      max-width: 1100px;
      width: 100%;
      margin: 0 auto;
      padding: 24px 24px 48px;
    }

    /* Live Time Bar */
    .time-banner {
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 12px 24px;
      display: inline-flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 36px;
      box-shadow: var(--shadow-sm);
    }

    .time-unit {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.82rem;
      color: var(--text-muted);
      font-weight: 500;
    }

    .time-unit svg {
      width: 16px;
      height: 16px;
      color: var(--teal);
    }

    .time-display {
      font-family: 'JetBrains Mono', monospace;
      font-weight: 600;
      font-size: 0.95rem;
      color: var(--text-main);
      letter-spacing: 0.5px;
    }

    /* Hero Heading */
    .hero-header {
      text-align: center;
      max-width: 680px;
      margin-bottom: 40px;
    }

    .hero-badge {
      display: inline-block;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--teal);
      background: var(--teal-light);
      border: 1px solid rgba(13, 148, 136, 0.2);
      padding: 4px 12px;
      border-radius: 9999px;
      margin-bottom: 14px;
    }

    .hero-title {
      font-size: 2.2rem;
      font-weight: 800;
      letter-spacing: -0.03em;
      color: var(--text-main);
      line-height: 1.25;
      margin-bottom: 12px;
    }

    .hero-subtitle {
      font-size: 0.98rem;
      color: var(--text-muted);
      line-height: 1.6;
    }

    /* Portal Grid */
    .portals-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 24px;
      width: 100%;
      max-width: 860px;
      margin-bottom: 48px;
    }

    .portal-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 32px 28px;
      text-decoration: none;
      color: inherit;
      box-shadow: var(--shadow-md);
      transition: all 0.25s ease;
      display: flex;
      flex-direction: column;
      position: relative;
      overflow: hidden;
    }

    .portal-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: transparent;
      transition: background 0.25s ease;
    }

    .portal-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
      border-color: var(--border-hover);
    }

    .portal-employee:hover::before {
      background: var(--teal);
    }

    .portal-reports:hover::before {
      background: var(--indigo);
    }

    .card-icon {
      width: 52px;
      height: 52px;
      border-radius: var(--radius-md);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    .portal-employee .card-icon {
      background: var(--teal-light);
      color: var(--teal);
    }

    .portal-reports .card-icon {
      background: var(--indigo-light);
      color: var(--indigo);
    }

    .card-icon svg {
      width: 26px;
      height: 26px;
    }

    .card-title {
      font-size: 1.28rem;
      font-weight: 700;
      letter-spacing: -0.01em;
      color: var(--text-main);
      margin-bottom: 8px;
    }

    .card-desc {
      font-size: 0.88rem;
      color: var(--text-muted);
      line-height: 1.5;
      margin-bottom: 28px;
      flex-grow: 1;
    }

    .card-cta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 18px;
      border-radius: var(--radius-md);
      font-size: 0.88rem;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .portal-employee .card-cta {
      background: var(--teal-light);
      color: var(--teal-hover);
    }

    .portal-employee:hover .card-cta {
      background: var(--teal);
      color: #ffffff;
    }

    .portal-reports .card-cta {
      background: var(--indigo-light);
      color: var(--indigo-hover);
    }

    .portal-reports:hover .card-cta {
      background: var(--indigo);
      color: #ffffff;
    }

    .card-cta svg {
      width: 16px;
      height: 16px;
      transition: transform 0.2s ease;
    }

    .portal-card:hover .card-cta svg {
      transform: translateX(4px);
    }

    /* Key Features Pills */
    .features-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 14px;
      flex-wrap: wrap;
    }

    .feature-pill {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 9999px;
      font-size: 0.8rem;
      font-weight: 500;
      color: var(--text-muted);
      box-shadow: var(--shadow-sm);
    }

    .feature-pill svg {
      width: 14px;
      height: 14px;
      color: var(--teal);
    }

    /* Footer */
    .site-footer {
      border-top: 1px solid var(--border);
      padding: 24px;
      text-align: center;
      font-size: 0.8rem;
      color: var(--text-light);
      background: #ffffff;
    }

    .site-footer span {
      color: var(--text-main);
      font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .portals-grid {
        grid-template-columns: 1fr;
        gap: 16px;
      }
      .hero-title {
        font-size: 1.8rem;
      }
      .time-banner {
        flex-direction: column;
        gap: 6px;
        padding: 10px 16px;
      }
      .portal-card {
        padding: 24px 20px;
      }
    }
  </style>
</head>

<body>

  <!-- Top Navigation -->
  <header class="top-nav">
    <a href="{{ url('/') }}" class="brand-link">
      <img src="{{ asset('logo/guru.png') }}" alt="Guru Group Logo" class="brand-logo">
      <div class="brand-title">Guru <span>Group</span></div>
    </a>
    <div class="status-badge">
      <span class="status-dot"></span>
      Portal Active
    </div>
  </header>

  <!-- Main Hero & Portal Options -->
  <main class="main-wrapper">

    <!-- Live Date & Time -->
    <div class="time-banner">
      <div class="time-unit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
        <span class="time-display" id="clockTime">--:--:--</span>
      </div>
      <div class="time-unit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
          <line x1="16" y1="2" x2="16" y2="6"></line>
          <line x1="8" y1="2" x2="8" y2="6"></line>
          <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        <span id="clockDate">Loading date...</span>
      </div>
    </div>

    <!-- Hero Title -->
    <div class="hero-header">
      <div class="hero-badge">Enterprise Workforce System</div>
      <h1 class="hero-title">Attendance & Management Portal</h1>
      <p class="hero-subtitle">
        Select your portal below to record daily attendance or access branch reports and management analytics.
      </p>
    </div>

    <!-- Portal Cards -->
    <div class="portals-grid">
      
      <!-- Employee Portal -->
      <a href="{{ route('employee.login') }}" class="portal-card portal-employee">
        <div class="card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
        </div>
        <h2 class="card-title">Employee Attendance</h2>
        <p class="card-desc">
          Quick verification, GPS-enabled Clock In & Clock Out, daily work hours, and self-service attendance dashboard.
        </p>
        <div class="card-cta">
          <span>Clock In / Out</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </div>
      </a>

      <!-- Management & Reports Portal -->
      <a href="{{ route('reports.login') }}" class="portal-card portal-reports">
        <div class="card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          </svg>
        </div>
        <h2 class="card-title">Management & Reports</h2>
        <p class="card-desc">
          Branch-level attendance reports, real-time analytics, leave administration, and head office console.
        </p>
        <div class="card-cta">
          <span>Branch Login</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </div>
      </a>

    </div>

    <!-- Features / Assurances -->
    <div class="features-row">
      <div class="feature-pill">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
        <span>Secure Authentication</span>
      </div>
      <div class="feature-pill">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="2" y1="12" x2="22" y2="12"></line>
          <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
        </svg>
        <span>GPS Verification</span>
      </div>
      <div class="feature-pill">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        <span>Real-time Sync</span>
      </div>
    </div>

  </main>

  <!-- Footer -->
  <footer class="site-footer">
    &copy; {{ date('Y') }} <span>Guru Group</span>. All rights reserved. Attendance & Management System.
  </footer>

  <script>
    function updateClock() {
      const now = new Date();
      let hours = now.getHours();
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const seconds = String(now.getSeconds()).padStart(2, '0');
      const ampm = hours >= 12 ? 'PM' : 'AM';
      hours = hours % 12;
      hours = hours ? String(hours).padStart(2, '0') : '12';
      
      const timeStr = `${hours}:${minutes}:${seconds} ${ampm}`;
      document.getElementById('clockTime').textContent = timeStr;

      const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
      document.getElementById('clockDate').textContent = now.toLocaleDateString('en-US', options);
    }
    updateClock();
    setInterval(updateClock, 1000);
  </script>
</body>

</html>