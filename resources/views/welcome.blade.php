<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Guru Group — Unified HRM & CRM platform. Manage attendance, payroll, leaves, customer relationships, and workforce analytics all in one place.">
  <title>Guru Group — HRM & CRM Platform</title>
  <link rel="icon" type="image/png" href="{{ asset('logo/guru.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

  <style>
    /* ── Reset & Base ── */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --bg-primary: #07090f;
      --bg-secondary: #0d1117;
      --surface: rgba(255, 255, 255, .04);
      --surface-hover: rgba(255, 255, 255, .08);
      --border: rgba(255, 255, 255, .07);
      --border-hover: rgba(255, 255, 255, .14);
      --text-primary: #f0f2f5;
      --text-secondary: #8b92a8;
      --text-muted: #5a6178;
      --accent-teal: #0fb5a3;
      --accent-teal-glow: rgba(15, 181, 163, .25);
      --accent-indigo: #4f5bd5;
      --accent-indigo-glow: rgba(79, 91, 213, .25);
      --accent-amber: #f5a524;
      --accent-rose: #ef5d6f;
      --radius-sm: 10px;
      --radius-md: 16px;
      --radius-lg: 24px;
      --radius-xl: 32px;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: var(--bg-primary);
      color: var(--text-primary);
      overflow-x: hidden;
      min-height: 100vh;
    }

    /* ── Animated Background ── */
    .bg-canvas {
      position: fixed;
      inset: 0;
      z-index: 0;
      overflow: hidden;
      background: radial-gradient(ellipse 80% 60% at 50% -20%, rgba(15, 181, 163, .08), transparent),
        radial-gradient(ellipse 60% 50% at 80% 50%, rgba(79, 91, 213, .06), transparent),
        radial-gradient(ellipse 70% 50% at 20% 80%, rgba(245, 165, 36, .04), transparent),
        var(--bg-primary);
    }

    .bg-canvas::before {
      content: '';
      position: absolute;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(15, 181, 163, .12) 0%, transparent 70%);
      border-radius: 50%;
      top: -200px;
      right: -100px;
      animation: orbFloat 18s ease-in-out infinite;
    }

    .bg-canvas::after {
      content: '';
      position: absolute;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(79, 91, 213, .1) 0%, transparent 70%);
      border-radius: 50%;
      bottom: -150px;
      left: -100px;
      animation: orbFloat 22s ease-in-out infinite reverse;
    }

    @keyframes orbFloat {

      0%,
      100% {
        transform: translate(0, 0) scale(1);
      }

      33% {
        transform: translate(40px, -30px) scale(1.05);
      }

      66% {
        transform: translate(-20px, 20px) scale(.95);
      }
    }

    /* Floating particles */
    .particle {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      opacity: 0;
      animation: particleDrift linear infinite;
    }

    @keyframes particleDrift {
      0% {
        opacity: 0;
        transform: translateY(0) scale(0);
      }

      10% {
        opacity: 1;
      }

      90% {
        opacity: 1;
      }

      100% {
        opacity: 0;
        transform: translateY(-100vh) scale(1);
      }
    }

    /* ── Grid pattern overlay ── */
    .grid-overlay {
      position: fixed;
      inset: 0;
      z-index: 1;
      background-image:
        linear-gradient(rgba(255, 255, 255, .015) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, .015) 1px, transparent 1px);
      background-size: 60px 60px;
      pointer-events: none;
    }

    /* ── Layout ── */
    .page-wrapper {
      position: relative;
      z-index: 2;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── Navbar ── */
    .navbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 48px;
      position: relative;
      z-index: 10;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 14px;
      text-decoration: none;
    }

    .navbar-brand img {
      height: 42px;
      width: auto;
      object-fit: contain;
    }

    .navbar-brand-text {
      font-family: 'Sora', sans-serif;
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--text-primary);
    }

    .navbar-brand-text span {
      background: linear-gradient(135deg, var(--accent-teal), var(--accent-indigo));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .navbar-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 30px;
      background: var(--surface);
      border: 1px solid var(--border);
      font-size: .72rem;
      font-weight: 500;
      color: var(--text-secondary);
      letter-spacing: .5px;
      text-transform: uppercase;
    }

    .navbar-badge .dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #22c55e;
      animation: pulse-dot 2s ease-in-out infinite;
    }

    @keyframes pulse-dot {

      0%,
      100% {
        opacity: 1;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, .4);
      }

      50% {
        opacity: .7;
        box-shadow: 0 0 0 6px rgba(34, 197, 94, 0);
      }
    }

    /* ── Hero Section ── */
    .hero {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 48px 60px;
    }

    .hero-inner {
      max-width: 1100px;
      width: 100%;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 80px;
      align-items: center;
    }

    /* ── Left Column ── */
    .hero-content {
      animation: fadeInUp .7s ease both;
    }

    .hero-label {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 7px 16px;
      border-radius: 30px;
      background: linear-gradient(135deg, rgba(15, 181, 163, .1), rgba(79, 91, 213, .1));
      border: 1px solid rgba(15, 181, 163, .15);
      font-size: .75rem;
      font-weight: 600;
      color: var(--accent-teal);
      text-transform: uppercase;
      letter-spacing: .8px;
      margin-bottom: 28px;
    }

    .hero-label svg {
      width: 14px;
      height: 14px;
    }

    .hero-title {
      font-family: 'Sora', sans-serif;
      font-size: 3rem;
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: 20px;
      letter-spacing: -.5px;
    }

    .hero-title .gradient-text {
      background: linear-gradient(135deg, var(--accent-teal), #6ee7b7, var(--accent-indigo));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .hero-subtitle {
      font-size: 1.05rem;
      font-weight: 400;
      color: var(--text-secondary);
      line-height: 1.7;
      margin-bottom: 40px;
      max-width: 460px;
    }

    /* ── Stats Bar ── */
    .stats-bar {
      display: flex;
      gap: 32px;
      margin-bottom: 44px;
      animation: fadeInUp .7s ease .2s both;
    }

    .stat-item {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .stat-number {
      font-family: 'Sora', sans-serif;
      font-size: 1.6rem;
      font-weight: 700;
      color: var(--text-primary);
    }

    .stat-number .accent-teal {
      color: var(--accent-teal);
    }

    .stat-number .accent-indigo {
      color: var(--accent-indigo);
    }

    .stat-number .accent-amber {
      color: var(--accent-amber);
    }

    .stat-label {
      font-size: .72rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: .8px;
      font-weight: 500;
    }

    /* ── Portal Cards ── */
    .portal-section {
      display: flex;
      gap: 16px;
      animation: fadeInUp .7s ease .35s both;
    }

    .portal-card {
      flex: 1;
      position: relative;
      background: var(--surface);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 28px 24px;
      text-align: center;
      transition: all .35s cubic-bezier(.4, 0, .2, 1);
      cursor: pointer;
      overflow: hidden;
      text-decoration: none;
      display: block;
    }

    .portal-card::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: inherit;
      opacity: 0;
      transition: opacity .35s ease;
      pointer-events: none;
    }

    .portal-card.card-hrm::before {
      background: radial-gradient(circle at 50% 0%, var(--accent-teal-glow), transparent 70%);
    }

    .portal-card.card-crm::before {
      background: radial-gradient(circle at 50% 0%, var(--accent-indigo-glow), transparent 70%);
    }

    .portal-card:hover {
      transform: translateY(-6px);
      border-color: var(--border-hover);
    }

    .portal-card:hover::before {
      opacity: 1;
    }

    .portal-card.card-hrm:hover {
      box-shadow: 0 20px 60px -12px rgba(15, 181, 163, .2);
    }

    .portal-card.card-crm:hover {
      box-shadow: 0 20px 60px -12px rgba(79, 91, 213, .2);
    }

    .portal-icon {
      width: 52px;
      height: 52px;
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px;
      position: relative;
      z-index: 1;
    }

    .portal-icon svg {
      width: 24px;
      height: 24px;
    }

    .card-hrm .portal-icon {
      background: rgba(15, 181, 163, .12);
      color: var(--accent-teal);
    }

    .card-crm .portal-icon {
      background: rgba(79, 91, 213, .12);
      color: var(--accent-indigo);
    }

    .portal-card h3 {
      font-family: 'Sora', sans-serif;
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 6px;
      position: relative;
      z-index: 1;
      color: var(--text-primary);
    }

    .portal-card p {
      font-size: .78rem;
      color: var(--text-secondary);
      margin-bottom: 20px;
      line-height: 1.5;
      position: relative;
      z-index: 1;
    }

    .portal-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 24px;
      border-radius: 30px;
      font-size: .8rem;
      font-weight: 600;
      text-decoration: none;
      transition: all .25s ease;
      position: relative;
      z-index: 1;
    }

    .portal-btn svg {
      width: 14px;
      height: 14px;
      transition: transform .25s ease;
    }

    .portal-card:hover .portal-btn svg {
      transform: translateX(3px);
    }

    .btn-hrm {
      background: linear-gradient(135deg, var(--accent-teal), #0d9e8e);
      color: #fff;
      box-shadow: 0 4px 16px rgba(15, 181, 163, .25);
    }

    .btn-hrm:hover {
      box-shadow: 0 6px 24px rgba(15, 181, 163, .35);
    }

    .btn-crm {
      background: linear-gradient(135deg, var(--accent-indigo), #3f49c2);
      color: #fff;
      box-shadow: 0 4px 16px rgba(79, 91, 213, .25);
    }

    .btn-crm:hover {
      box-shadow: 0 6px 24px rgba(79, 91, 213, .35);
    }

    /* ── Right Column — Clock + Visual ── */
    .hero-visual {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 28px;
      animation: fadeInUp .7s ease .15s both;
    }

    /* Clock Card */
    .clock-card {
      width: 100%;
      max-width: 380px;
      background: var(--surface);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 32px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .clock-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--accent-teal), var(--accent-indigo), var(--accent-amber));
      border-radius: 2px 2px 0 0;
    }

    .clock-label {
      font-size: .7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: var(--text-muted);
      margin-bottom: 12px;
    }

    .clock-time {
      font-family: 'Sora', sans-serif;
      font-size: 3.6rem;
      font-weight: 700;
      letter-spacing: 2px;
      color: var(--text-primary);
      line-height: 1;
      margin-bottom: 8px;
    }

    .clock-time .seconds {
      font-size: 1.6rem;
      color: var(--accent-teal);
      font-weight: 600;
    }

    .clock-date {
      font-size: .82rem;
      color: var(--text-secondary);
      font-weight: 400;
    }

    /* Features mini grid */
    .features-grid {
      width: 100%;
      max-width: 380px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .feature-chip {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 14px 16px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      transition: all .25s ease;
    }

    .feature-chip:hover {
      background: var(--surface-hover);
      border-color: var(--border-hover);
      transform: translateY(-2px);
    }

    .feature-chip-icon {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .feature-chip-icon svg {
      width: 16px;
      height: 16px;
    }

    .feature-chip-text {
      font-size: .72rem;
      font-weight: 500;
      color: var(--text-secondary);
      line-height: 1.3;
    }

    /* ── Footer ── */
    .footer {
      text-align: center;
      padding: 20px 48px 28px;
      color: var(--text-muted);
      font-size: .72rem;
      letter-spacing: .3px;
    }

    .footer span {
      color: var(--accent-teal);
    }

    /* ── Animations ── */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* ── Responsive: Tablet (≤ 1024px) ── */
    @media (max-width: 1024px) {
      .hero-inner {
        gap: 50px;
      }

      .hero-title {
        font-size: 2.4rem;
      }

      .clock-time {
        font-size: 3rem;
      }
    }

    /* ── Responsive: Tablet portrait / Large phone (≤ 768px) ── */
    @media (max-width: 768px) {
      .navbar {
        padding: 16px 20px;
      }

      .hero {
        padding: 0 20px 40px;
      }

      .hero-inner {
        grid-template-columns: 1fr;
        gap: 36px;
        text-align: center;
      }

      .hero-content {
        order: 1;
      }

      .hero-visual {
        order: 0;
      }

      .hero-label {
        margin-bottom: 20px;
      }

      .hero-title {
        font-size: 2.2rem;
      }

      .hero-subtitle {
        margin: 0 auto 32px;
        font-size: .95rem;
      }

      .stats-bar {
        justify-content: center;
        gap: 28px;
        margin-bottom: 36px;
      }

      .portal-section {
        gap: 12px;
      }

      .portal-card {
        padding: 20px 14px;
      }

      .portal-btn {
        width: 100%;
        justify-content: center;
        padding: 10px 14px;
        font-size: .75rem;
      }

      .clock-card {
        max-width: 340px;
        padding: 28px 24px;
      }

      .clock-time {
        font-size: 2.8rem;
      }

      .features-grid {
        max-width: 340px;
      }

      .footer {
        padding: 16px 20px 24px;
      }
    }

    /* ── Responsive: Phones (≤ 480px) ── */
    @media (max-width: 480px) {
      .navbar {
        padding: 14px 16px;
      }

      .navbar-brand img {
        height: 34px;
      }

      .navbar-brand-text {
        font-size: 1.1rem;
      }

      .navbar-badge {
        font-size: .65rem;
        padding: 5px 10px;
      }

      .hero {
        padding: 0 16px 32px;
      }

      .hero-inner {
        gap: 28px;
      }

      .hero-label {
        font-size: .68rem;
        padding: 6px 12px;
        margin-bottom: 16px;
      }

      .hero-title {
        font-size: 1.75rem;
        letter-spacing: -.3px;
      }

      .hero-subtitle {
        font-size: .85rem;
        margin-bottom: 24px;
        line-height: 1.6;
      }

      .stats-bar {
        gap: 20px;
        margin-bottom: 28px;
      }

      .stat-number {
        font-size: 1.3rem;
      }

      .stat-label {
        font-size: .65rem;
      }

      .portal-section {
        gap: 12px;
      }

      .portal-card {
        padding: 22px 18px;
        border-radius: var(--radius-md);
      }

      .portal-icon {
        width: 44px;
        height: 44px;
      }

      .portal-card h3 {
        font-size: .92rem;
      }

      .portal-btn {
        padding: 11px 20px;
        font-size: .78rem;
        border-radius: 12px;
      }

      .clock-card {
        max-width: 100%;
        padding: 24px 18px;
        border-radius: var(--radius-md);
      }

      .clock-time {
        font-size: 2.4rem;
      }

      .clock-time .seconds {
        font-size: 1.2rem;
      }

      .clock-date {
        font-size: .78rem;
      }

      .features-grid {
        max-width: 100%;
        gap: 10px;
      }

      .feature-chip {
        padding: 12px;
        border-radius: 12px;
      }

      .feature-chip-icon {
        width: 30px;
        height: 30px;
      }

      .feature-chip-text {
        font-size: .68rem;
      }
    }

    /* ── Responsive: Very small phones (≤ 360px) ── */
    @media (max-width: 360px) {
      .navbar {
        padding: 12px;
      }

      .navbar-brand img {
        height: 30px;
      }

      .navbar-brand-text {
        font-size: 1rem;
      }

      .hero {
        padding: 0 12px 24px;
      }

      .hero-title {
        font-size: 1.5rem;
      }

      .hero-subtitle {
        font-size: .8rem;
      }

      .stats-bar {
        gap: 16px;
      }

      .stat-number {
        font-size: 1.15rem;
      }

      .clock-time {
        font-size: 2rem;
      }

      .clock-time .seconds {
        font-size: 1rem;
      }

      .portal-card {
        padding: 18px 14px;
      }

      .feature-chip {
        padding: 10px;
        gap: 8px;
      }

      .feature-chip-text {
        font-size: .64rem;
      }
    }

    /* ── Touch device states ── */
    @media (hover: none) and (pointer: coarse) {
      .portal-card:active {
        transform: scale(.97);
        background: var(--surface-hover);
      }

      .portal-btn:active {
        opacity: .85;
      }

      .feature-chip:active {
        background: var(--surface-hover);
      }
    }
  </style>
</head>

<body>

  <!-- Animated background -->
  <div class="bg-canvas" id="bgCanvas"></div>
  <div class="grid-overlay"></div>

  <div class="page-wrapper">

    <!-- Navbar -->
    <nav class="navbar">
      <a href="/" class="navbar-brand">
        <img src="{{ asset('logo/guru.png') }}" alt="Guru Group">
        <div class="navbar-brand-text">Guru <span>Group</span></div>
      </a>
      <div class="navbar-badge">
        <span class="dot"></span>
        System Online
      </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
      <div class="hero-inner">

        <!-- Left: Content -->
        <div class="hero-content">
          <div class="hero-label">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
            </svg>
            HRM & CRM Platform
          </div>

          <h1 class="hero-title">
            Manage Your<br>
            <span class="gradient-text">Workforce & Clients</span><br>
            Seamlessly
          </h1>

          <p class="hero-subtitle">
            Unified platform for attendance tracking, payroll management, leave approvals, customer relationships, and real-time business analytics.
          </p>

          <div class="stats-bar">
            <div class="stat-item">
              <div class="stat-number"><span class="accent-teal">100%</span></div>
              <div class="stat-label">Attendance Accuracy</div>
            </div>
            <div class="stat-item">
              <div class="stat-number"><span class="accent-indigo">Real-Time</span></div>
              <div class="stat-label">Reports & Analytics</div>
            </div>
            <div class="stat-item">
              <div class="stat-number"><span class="accent-amber">24/7</span></div>
              <div class="stat-label">Cloud Access</div>
            </div>
          </div>

          <div class="portal-section">
            <!-- HRM Card -->
            <div class="portal-card card-hrm" data-href="{{ route('employee.login') }}">
              <div class="portal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                  <circle cx="12" cy="7" r="4" />
                </svg>
              </div>
              <h3>Employee Login</h3>
              <p>Mark attendance, track leaves & view payroll records</p>
              <a href="{{ route('employee.login') }}" class="portal-btn btn-hrm">
                Attendance Login
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14" />
                  <path d="m12 5 7 7-7 7" />
                </svg>
              </a>
            </div>

            <!-- CRM / Reports Card -->
            <div class="portal-card card-crm" data-href="{{ route('reports.login') }}">
              <div class="portal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
              </div>
              <h3>Managment Login</h3>
              <p>Branch-wise reports, analytics & management console</p>
              <a href="{{ route('reports.login') }}" class="portal-btn btn-crm">
                Admin Login
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14" />
                  <path d="m12 5 7 7-7 7" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <!-- Right: Clock + Features -->
        <div class="hero-visual">
          <!-- Live Clock -->
          <div class="clock-card">
            <div class="clock-label">Current Time</div>
            <div class="clock-time" id="liveTime">
              <span id="timeHM">--:--</span><span class="seconds" id="timeSec">:--</span>
            </div>
            <div class="clock-date" id="liveDate">---</div>
          </div>

          <!-- Feature Chips -->
          <div class="features-grid">
            <div class="feature-chip">
              <div class="feature-chip-icon" style="background: rgba(15, 181, 163, .1); color: var(--accent-teal);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                  <line x1="16" y1="2" x2="16" y2="6" />
                  <line x1="8" y1="2" x2="8" y2="6" />
                  <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
              </div>
              <span class="feature-chip-text">Attendance Tracking</span>
            </div>

            <div class="feature-chip">
              <div class="feature-chip-icon" style="background: rgba(79, 91, 213, .1); color: var(--accent-indigo);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="12" y1="1" x2="12" y2="23" />
                  <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
              </div>
              <span class="feature-chip-text">Payroll & Salary</span>
            </div>

            <div class="feature-chip">
              <div class="feature-chip-icon" style="background: rgba(245, 165, 36, .1); color: var(--accent-amber);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                  <polyline points="14 2 14 8 20 8" />
                  <line x1="16" y1="13" x2="8" y2="13" />
                  <line x1="16" y1="17" x2="8" y2="17" />
                  <polyline points="10 9 9 9 8 9" />
                </svg>
              </div>
              <span class="feature-chip-text">Leave Management</span>
            </div>

            <div class="feature-chip">
              <div class="feature-chip-icon" style="background: rgba(239, 93, 111, .1); color: var(--accent-rose);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
              </div>
              <span class="feature-chip-text">Team Communication</span>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      &copy; {{ date('Y') }} <span>Guru Group</span> — All rights reserved. Powered by advanced HRM & CRM technology.
    </footer>
  </div>

  <script>
    // ── Live Clock ──
    function updateClock() {
      const now = new Date();
      const h = String(now.getHours()).padStart(2, '0');
      const m = String(now.getMinutes()).padStart(2, '0');
      const s = String(now.getSeconds()).padStart(2, '0');
      document.getElementById('timeHM').textContent = h + ':' + m;
      document.getElementById('timeSec').textContent = ':' + s;

      const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
      const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      document.getElementById('liveDate').textContent = days[now.getDay()] + ', ' + months[now.getMonth()] + ' ' + now.getDate() + ', ' + now.getFullYear();
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ── Card click navigation ──
    document.querySelectorAll('.portal-card').forEach(function(card) {
      card.addEventListener('click', function(e) {
        if (e.target.closest('.portal-btn')) return; // let button links work normally
        window.location.href = card.dataset.href;
      });
    });

    // ── Floating particles ──
    (function() {
      const canvas = document.getElementById('bgCanvas');
      const colors = ['rgba(15,181,163,.35)', 'rgba(79,91,213,.35)', 'rgba(245,165,36,.3)', 'rgba(239,93,111,.25)'];

      function spawnParticle() {
        const p = document.createElement('div');
        p.className = 'particle';
        const size = Math.random() * 4 + 2;
        const x = Math.random() * 100;
        const dur = Math.random() * 12 + 10;
        const delay = Math.random() * 6;
        const color = colors[Math.floor(Math.random() * colors.length)];

        p.style.cssText = `
          width: ${size}px; height: ${size}px;
          left: ${x}%; bottom: -10px;
          background: ${color};
          animation-duration: ${dur}s;
          animation-delay: ${delay}s;
        `;
        canvas.appendChild(p);

        setTimeout(() => p.remove(), (dur + delay) * 1000);
      }

      // Spawn initial batch
      for (let i = 0; i < 15; i++) spawnParticle();
      setInterval(spawnParticle, 2000);
    })();
  </script>
</body>

</html>