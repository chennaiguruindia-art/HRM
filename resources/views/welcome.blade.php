<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Guru Group — Sales CRM Portal. Daily plan visits, lead conversion and branch-wise sales tracking.">
  <title>Guru Group — Sales CRM Portal</title>
  <link rel="icon" type="image/png" href="{{ asset('logo/guru.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary: #2563eb;
      --primary-hover: #1d4ed8;
      --primary-light: #eff6ff;
      --primary-glow: rgba(37, 99, 235, 0.15);
      
      --accent-teal: #0d9488;
      --accent-teal-light: #f0fdfa;
      
      --accent-purple: #7c3aed;
      --accent-purple-light: #f5f3ff;

      --bg-gradient-1: #0f172a;
      --bg-gradient-2: #1e293b;
      
      --surface-card: rgba(255, 255, 255, 0.95);
      --surface-glass: rgba(255, 255, 255, 0.85);
      --surface-border: rgba(226, 232, 240, 0.8);
      
      --text-main: #0f172a;
      --text-muted: #475569;
      --text-subtle: #94a3b8;
      
      --radius-sm: 10px;
      --radius-md: 16px;
      --radius-lg: 24px;
      --radius-full: 9999px;
      
      --shadow-sm: 0 2px 4px rgba(15, 23, 42, 0.04);
      --shadow-md: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
      --shadow-xl: 0 25px 50px -12px rgba(15, 23, 42, 0.15);
      --shadow-glow: 0 0 30px rgba(37, 99, 235, 0.2);
    }

    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      scroll-behavior: smooth;
      font-size: 16px;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background-color: #f8fafc;
      color: var(--text-main);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    /* Ambient Background Mesh */
    .bg-mesh {
      position: fixed;
      inset: 0;
      z-index: -1;
      pointer-events: none;
      background: 
        radial-gradient(circle at 15% 15%, rgba(37, 99, 235, 0.08) 0%, transparent 45%),
        radial-gradient(circle at 85% 20%, rgba(13, 148, 136, 0.08) 0%, transparent 45%),
        radial-gradient(circle at 50% 80%, rgba(124, 58, 237, 0.06) 0%, transparent 50%),
        linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    }

    /* Top Navigation Bar */
    .header-nav {
      position: sticky;
      top: 0;
      z-index: 50;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      background: rgba(255, 255, 255, 0.85);
      border-bottom: 1px solid var(--surface-border);
      transition: all 0.3s ease;
    }

    .header-container {
      max-width: 1280px;
      margin: 0 auto;
      padding: 14px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }

    .brand-link {
      display: flex;
      align-items: center;
      gap: 14px;
      text-decoration: none;
      color: var(--text-main);
      transition: transform 0.2s ease;
    }

    .brand-link:hover {
      transform: translateY(-1px);
    }

    .brand-logo-wrapper {
      background: #ffffff;
      padding: 6px 10px;
      border-radius: var(--radius-sm);
      border: 1px solid var(--surface-border);
      box-shadow: var(--shadow-sm);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .brand-logo {
      height: 38px;
      width: auto;
      object-fit: contain;
    }

    .brand-text {
      display: flex;
      flex-direction: column;
    }

    .brand-title {
      font-size: 1.35rem;
      font-weight: 800;
      letter-spacing: -0.03em;
      line-height: 1.1;
      color: var(--text-main);
    }

    .brand-title span {
      background: linear-gradient(135deg, var(--primary) 0%, var(--accent-teal) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .brand-tag {
      font-size: 0.72rem;
      font-weight: 600;
      color: var(--text-muted);
      letter-spacing: 0.05em;
      text-transform: uppercase;
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 14px;
      border-radius: var(--radius-full);
      background: #ffffff;
      border: 1px solid rgba(16, 185, 129, 0.3);
      box-shadow: 0 2px 8px rgba(16, 185, 129, 0.08);
      font-size: 0.8rem;
      font-weight: 600;
      color: #065f46;
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
      background: rgba(16, 185, 129, 0.4);
      animation: pulse-ring 2s infinite cubic-bezier(0.4, 0, 0.6, 1);
    }

    @keyframes pulse-ring {
      0% { transform: scale(0.95); opacity: 0.8; }
      50% { transform: scale(1.7); opacity: 0; }
      100% { transform: scale(0.95); opacity: 0; }
    }

    .login-btn-header {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 18px;
      background: var(--primary);
      color: #ffffff;
      border-radius: var(--radius-sm);
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
      transition: all 0.2s ease;
    }

    .login-btn-header:hover {
      background: var(--primary-hover);
      box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
      transform: translateY(-1px);
    }

    /* Main Container */
    .main-content {
      flex: 1;
      max-width: 1280px;
      width: 100%;
      margin: 0 auto;
      padding: 40px 24px 60px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* Live Time Bar */
    .time-bar {
      background: rgba(255, 255, 255, 0.9);
      border: 1px solid var(--surface-border);
      border-radius: var(--radius-full);
      padding: 8px 24px;
      display: inline-flex;
      align-items: center;
      gap: 24px;
      margin-bottom: 36px;
      box-shadow: var(--shadow-sm);
      backdrop-filter: blur(8px);
    }

    .time-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.85rem;
      color: var(--text-muted);
      font-weight: 500;
    }

    .time-item svg {
      width: 16px;
      height: 16px;
      color: var(--primary);
    }

    .time-value {
      font-family: 'JetBrains Mono', monospace;
      font-weight: 700;
      font-size: 0.95rem;
      color: var(--text-main);
    }

    .time-divider {
      width: 4px;
      height: 4px;
      border-radius: 50%;
      background: var(--text-subtle);
    }

    /* Hero Banner */
    .hero-section {
      text-align: center;
      max-width: 800px;
      margin-bottom: 48px;
    }

    .hero-pill {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 16px;
      border-radius: var(--radius-full);
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(13, 148, 136, 0.1) 100%);
      border: 1px solid rgba(37, 99, 235, 0.2);
      color: var(--primary);
      font-size: 0.8rem;
      font-weight: 700;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      margin-bottom: 20px;
    }

    .hero-title {
      font-size: clamp(2rem, 4vw, 3.2rem);
      font-weight: 800;
      letter-spacing: -0.04em;
      color: var(--text-main);
      line-height: 1.18;
      margin-bottom: 18px;
    }

    .hero-title .gradient-text {
      background: linear-gradient(135deg, var(--primary) 0%, var(--accent-purple) 50%, var(--accent-teal) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
      font-size: clamp(1rem, 1.8vw, 1.15rem);
      color: var(--text-muted);
      line-height: 1.6;
      max-width: 660px;
      margin: 0 auto;
    }

    /* Portal Grid & Main Cards */
    .cards-wrapper {
      width: 100%;
      max-width: 1060px;
      margin-bottom: 56px;
    }

    .main-portal-card {
      background: var(--surface-card);
      border: 1px solid var(--surface-border);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-xl);
      padding: 40px;
      position: relative;
      overflow: hidden;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      align-items: center;
      transition: all 0.3s ease;
    }

    .main-portal-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, var(--primary) 0%, var(--accent-teal) 50%, var(--accent-purple) 100%);
    }

    .portal-info {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .portal-icon-box {
      width: 64px;
      height: 64px;
      border-radius: var(--radius-md);
      background: linear-gradient(135deg, var(--primary-light) 0%, #e0e7ff 100%);
      color: var(--primary);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: var(--shadow-sm);
    }

    .portal-icon-box svg {
      width: 32px;
      height: 32px;
    }

    .portal-heading {
      font-size: 1.75rem;
      font-weight: 800;
      letter-spacing: -0.02em;
      color: var(--text-main);
    }

    .portal-desc {
      font-size: 0.95rem;
      color: var(--text-muted);
      line-height: 1.6;
    }

    .portal-features-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-top: 8px;
    }

    .portal-feature-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--text-main);
    }

    .portal-feature-item svg {
      width: 18px;
      height: 18px;
      color: var(--accent-teal);
      flex-shrink: 0;
    }

    .portal-action-side {
      background: linear-gradient(145deg, #f8fafc 0%, #f1f5f9 100%);
      border: 1px solid var(--surface-border);
      border-radius: var(--radius-md);
      padding: 32px 28px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      justify-content: center;
      gap: 20px;
    }

    .action-badge {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--primary);
      background: #ffffff;
      padding: 4px 12px;
      border-radius: var(--radius-full);
      border: 1px solid var(--surface-border);
      box-shadow: var(--shadow-sm);
    }

    .action-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--text-main);
    }

    .btn-login-main {
      width: 100%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 16px 28px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
      color: #ffffff;
      border-radius: var(--radius-md);
      text-decoration: none;
      font-size: 1rem;
      font-weight: 700;
      box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
      transition: all 0.25s ease;
    }

    .btn-login-main:hover {
      box-shadow: 0 12px 28px rgba(37, 99, 235, 0.45);
      transform: translateY(-2px);
    }

    .btn-login-main svg {
      width: 20px;
      height: 20px;
      transition: transform 0.2s ease;
    }

    .btn-login-main:hover svg {
      transform: translateX(4px);
    }

    .security-note {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.78rem;
      color: var(--text-subtle);
      font-weight: 500;
    }

    .security-note svg {
      width: 14px;
      height: 14px;
      color: var(--accent-teal);
    }

    /* Grid of Secondary Feature Cards */
    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
      width: 100%;
      max-width: 1060px;
      margin-bottom: 48px;
    }

    .feature-card {
      background: var(--surface-card);
      border: 1px solid var(--surface-border);
      border-radius: var(--radius-md);
      padding: 24px;
      box-shadow: var(--shadow-md);
      transition: all 0.25s ease;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .feature-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-xl);
      border-color: rgba(37, 99, 235, 0.3);
    }

    .feature-icon {
      width: 44px;
      height: 44px;
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .feature-icon.teal {
      background: var(--accent-teal-light);
      color: var(--accent-teal);
    }

    .feature-icon.blue {
      background: var(--primary-light);
      color: var(--primary);
    }

    .feature-icon.purple {
      background: var(--accent-purple-light);
      color: var(--accent-purple);
    }

    .feature-icon svg {
      width: 22px;
      height: 22px;
    }

    .feature-card-title {
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--text-main);
    }

    .feature-card-desc {
      font-size: 0.85rem;
      color: var(--text-muted);
      line-height: 1.5;
    }

    /* Stats Bar Section */
    .stats-container {
      width: 100%;
      max-width: 1060px;
      background: #ffffff;
      border: 1px solid var(--surface-border);
      border-radius: var(--radius-md);
      padding: 24px 32px;
      box-shadow: var(--shadow-sm);
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 24px;
      text-align: center;
    }

    .stat-item {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .stat-number {
      font-family: 'JetBrains Mono', monospace;
      font-size: 1.6rem;
      font-weight: 800;
      color: var(--text-main);
    }

    .stat-label {
      font-size: 0.78rem;
      font-weight: 600;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    /* Footer */
    .footer-site {
      margin-top: auto;
      background: #ffffff;
      border-top: 1px solid var(--surface-border);
      padding: 24px;
      text-align: center;
      font-size: 0.82rem;
      color: var(--text-muted);
    }

    .footer-site strong {
      color: var(--text-main);
    }

    /* Responsive Breakpoints */
    @media (max-width: 1024px) {
      .main-portal-card {
        grid-template-columns: 1fr;
        gap: 32px;
        padding: 32px;
      }
      .features-grid {
        grid-template-columns: repeat(3, 1fr);
      }
      .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
      }
    }

    @media (max-width: 768px) {
      .header-container {
        padding: 12px 16px;
      }
      .brand-title {
        font-size: 1.15rem;
      }
      .main-content {
        padding: 24px 16px 40px;
      }
      .time-bar {
        flex-direction: column;
        gap: 8px;
        border-radius: var(--radius-md);
        padding: 10px 18px;
      }
      .time-divider {
        display: none;
      }
      .hero-title {
        font-size: 1.85rem;
      }
      .main-portal-card {
        padding: 24px 20px;
      }
      .features-grid {
        grid-template-columns: 1fr;
        gap: 16px;
      }
      .stats-container {
        grid-template-columns: repeat(2, 1fr);
        padding: 20px 16px;
      }
    }

    @media (max-width: 480px) {
      .stats-container {
        grid-template-columns: 1fr;
      }
      .status-badge span:not(.status-dot) {
        display: none;
      }
    }
  </style>
</head>

<body>

  <div class="bg-mesh"></div>

  <!-- Header Navigation -->
  <header class="header-nav">
    <div class="header-container">
      <a href="{{ url('/') }}" class="brand-link">
        <div class="brand-logo-wrapper">
          <img src="{{ asset('logo/guru.png') }}" alt="Guru Group Logo" class="brand-logo">
        </div>
        <div class="brand-text">
          <div class="brand-title">Guru <span>Group</span></div>
          <div class="brand-tag">Sales CRM Portal</div>
        </div>
      </a>

      <div class="header-actions">
        <div class="status-badge">
          <span class="status-dot"></span>
          <span>System Online</span>
        </div>
        <a href="{{ route('login') }}" class="login-btn-header">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
            <polyline points="10 17 15 12 10 7"></polyline>
            <line x1="15" y1="12" x2="3" y2="12"></line>
          </svg>
          <span>Login</span>
        </a>
      </div>
    </div>
  </header>

  <!-- Main Hero & Portal Area -->
  <main class="main-content">

    <!-- Live Date & Clock Bar -->
    <div class="time-bar">
      <div class="time-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
        <span class="time-value" id="clockTime">--:--:--</span>
      </div>
      <div class="time-divider"></div>
      <div class="time-item">
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
    <div class="hero-section">
      <div class="hero-pill">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
        </svg>
        <span>Enterprise Sales Portal</span>
      </div>
      <h1 class="hero-title">
        Streamline Branch Sales & <span class="gradient-text">Lead Conversions</span>
      </h1>
      <p class="hero-subtitle">
        Manage daily sales visits, monitor lead progression, log rejected entries, and track branch-wise performance in real time.
      </p>
    </div>

    <!-- Main Portal Hero Card -->
    <div class="cards-wrapper">
      <div class="main-portal-card">
        
        <div class="portal-info">
          <div class="portal-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
          </div>
          <h2 class="portal-heading">CRM & Sales Dashboard</h2>
          <p class="portal-desc">
            Access your dedicated branch portal to create daily plans, convert prospective visits into leads, and inspect overall branch performance.
          </p>
          <ul class="portal-features-list">
            <li class="portal-feature-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>Daily Plan Visit Entry & Conversion</span>
            </li>
            <li class="portal-feature-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>Lead & Non-Lead Management</span>
            </li>
            <li class="portal-feature-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>Multi-Branch Filtering & Analytics</span>
            </li>
          </ul>
        </div>

        <div class="portal-action-side">
          <div class="action-badge">Authorized Access</div>
          <h3 class="action-title">Ready to sign in?</h3>
          <a href="{{ route('login') }}" class="btn-login-main">
            <span>Login to CRM Portal</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </a>
          <div class="security-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            <span>256-bit Encrypted Session Security</span>
          </div>
        </div>

      </div>
    </div>

    <!-- Secondary Features Grid -->
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon teal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
        </div>
        <h3 class="feature-card-title">Daily Visit Planning</h3>
        <p class="feature-card-desc">Log scheduled customer visits, set follow-up targets, and update conversion status instantly.</p>
      </div>

      <div class="feature-card">
        <div class="feature-icon blue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
        </div>
        <h3 class="feature-card-title">Lead Conversion Engine</h3>
        <p class="feature-card-desc">Seamlessly convert raw visits into active leads or categorize non-lead feedback automatically.</p>
      </div>

      <div class="feature-card">
        <div class="feature-icon purple">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"></line>
            <line x1="12" y1="20" x2="12" y2="4"></line>
            <line x1="6" y1="20" x2="6" y2="14"></line>
          </svg>
        </div>
        <h3 class="feature-card-title">Branch Performance</h3>
        <p class="feature-card-desc">Filter lead stats, branch ratios, and sales metrics across all company branches.</p>
      </div>
    </div>

    <!-- Live System Metrics Footer Stats -->
    <div class="stats-container">
      <div class="stat-item">
        <div class="stat-number">100%</div>
        <div class="stat-label">Cloud Uptime</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">Real-Time</div>
        <div class="stat-label">Data Sync</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">Multi-Branch</div>
        <div class="stat-label">Sales Network</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">256-Bit</div>
        <div class="stat-label">SSL Encryption</div>
      </div>
    </div>

  </main>

  <!-- Site Footer -->
  <footer class="footer-site">
    &copy; {{ date('Y') }} <strong>Guru Group</strong>. All rights reserved. Enterprise Sales CRM Portal.
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