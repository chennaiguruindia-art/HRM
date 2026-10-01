<!DOCTYPE html>
<html lang="en" data-branch="{{ $branch ? $branch->name : '' }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#0a8577">
  <title>Guru Group &mdash; CRM Dashboard</title>
  <link rel="icon" type="image/png" href="{{ asset('logo/guru.png') }}">

  <!-- Bootstrap 5 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --ink-900: #101425;
      --ink-800: #182038;
      --ink-700: #232c4a;
      --surface: #f3f5fb;
      --card: #ffffff;
      --line: #e7e9f2;
      --text: #1c2033;
      --text-soft: #6b7286;
      --accent: #4f5bd5;
      /* signal indigo */
      --accent-soft: #eceeff;
      --teal: #0fb5a3;
      /* present / success */
      --teal-soft: #e2f9f5;
      --amber: #f5a524;
      /* pending */
      --amber-soft: #fff3de;
      --coral: #ef5d6f;
      /* absent / reject */
      --coral-soft: #fdeaed;
      --radius: 14px;
    }

    * {
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
    }

    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: var(--surface);
      color: var(--text);
      font-size: 14.5px;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    .brand,
    .stat-num {
      font-family: 'Sora', sans-serif;
    }

    .mono {
      font-family: 'JetBrains Mono', monospace;
    }

    a {
      text-decoration: none;
    }

    /* ---------- Layout shell ---------- */
    .app-shell {
      display: flex;
      min-height: 100vh;
    }

    /* ---------- Sidebar ---------- */
    .sidebar {
      width: 264px;
      flex-shrink: 0;
      background: linear-gradient(180deg, #ffffff 0%, #f3f5fb 100%);
      color: #2a2f45;
      display: flex;
      flex-direction: column;
      position: sticky;
      top: 0;
      height: 100vh;
      border-right: 1px solid var(--line);
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 22px 22px 18px 22px;
      color: var(--text);
      font-weight: 700;
      font-size: 1.15rem;
      border-bottom: 1px solid var(--line);
    }

    .brand .brand-logo {
      height: 34px;
      width: auto;
      object-fit: contain;
    }

    .side-nav {
      flex: 1;
      padding: 16px 12px;
      overflow-y: auto;
    }

    .side-nav .nav-label {
      font-size: .68rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: #8a91a8;
      padding: 14px 12px 6px;
    }

    .side-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 14px;
      margin-bottom: 3px;
      border-radius: 10px;
      color: #5b6278;
      font-weight: 500;
      cursor: pointer;
      position: relative;
      transition: background .15s ease, color .15s ease;
    }

    .side-link i {
      font-size: 1.05rem;
      width: 20px;
      text-align: center;
    }

    .side-link .badge-pill {
      margin-left: auto;
      font-size: .68rem;
      font-weight: 600;
      background: var(--coral);
      color: #fff;
      border-radius: 20px;
      padding: 1px 7px;
    }

    .side-link:hover {
      background: #e9ebf5;
      color: var(--text);
    }

    .side-link.active {
      background: var(--accent-soft);
      color: var(--accent);
    }

    .side-link.active::before {
      content: "";
      position: absolute;
      left: -12px;
      top: 8px;
      bottom: 8px;
      width: 4px;
      border-radius: 0 4px 4px 0;
      background: var(--accent);
    }

    .side-foot {
      padding: 16px 22px 20px;
      border-top: 1px solid var(--line);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .side-foot img {
      height: 28px;
      width: auto;
      object-fit: contain;
    }

    .side-foot .name {
      color: var(--text);
      font-weight: 600;
      font-size: .85rem;
    }

    .side-foot .role {
      color: #8a91a8;
      font-size: .72rem;
    }

    /* ---------- Topbar ---------- */
    .topbar {
      background: var(--card);
      border-bottom: 1px solid var(--line);
      padding: 14px 28px;
      display: flex;
      align-items: center;
      gap: 16px;
      position: sticky;
      top: 0;
      z-index: 5;
    }

    .topbar .page-title {
      font-weight: 700;
      font-size: 1.15rem;
      margin: 0;
    }

    .topbar .page-sub {
      color: var(--text-soft);
      font-size: .8rem;
      margin: 0;
    }

    .search-box {
      flex: 1;
      max-width: 360px;
      margin-left: 20px;
      position: relative;
    }

    .search-box input {
      width: 100%;
      border: 1px solid var(--line);
      background: var(--surface);
      border-radius: 10px;
      padding: 8px 12px 8px 34px;
      font-size: .85rem;
    }

    .search-box i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-soft);
    }

    .topbar .icon-btn {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: var(--surface);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text);
      position: relative;
      border: 1px solid var(--line);
    }

    .topbar .icon-btn .dot {
      position: absolute;
      top: 6px;
      right: 6px;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--coral);
      border: 2px solid var(--card);
    }

    .content {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
    }

    .main {
      padding: 26px 28px 60px;
    }

    /* ---------- Cards / stats ---------- */
    .card-flat {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: var(--radius);
    }

    .stat-card {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 18px 20px;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .stat-ic {
      width: 46px;
      height: 46px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
    }

    .stat-num {
      font-size: 1.5rem;
      font-weight: 700;
      line-height: 1;
    }

    .stat-label {
      color: var(--text-soft);
      font-size: .78rem;
      margin-top: 4px;
    }

    .section-card {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 20px 22px;
      margin-bottom: 22px;
    }

    .section-card .section-head {
      display: flex;
      align-items: center;
      justify-content: between;
      gap: 10px;
      margin-bottom: 16px;
    }

    .section-card h5 {
      margin: 0;
      font-weight: 700;
    }

    table.tbl {
      width: 100%;
      border-collapse: collapse;
    }

    table.tbl thead th {
      font-size: .72rem;
      text-transform: uppercase;
      letter-spacing: .06em;
      color: var(--text-soft);
      border-bottom: 1px solid var(--line);
      padding: 10px 12px;
      text-align: left;
      font-weight: 600;
    }

    table.tbl tbody td {
      padding: 12px;
      border-bottom: 1px solid var(--line);
      vertical-align: middle;
      font-size: .86rem;
    }

    table.tbl tbody tr:last-child td {
      border-bottom: none;
    }

    table.tbl tbody tr:hover {
      background: var(--surface);
    }

    table.tbl td.report-text {
      white-space: normal;
      min-width: 260px;
      line-height: 1.5;
    }

    .pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: .72rem;
      font-weight: 600;
    }

    .pill-teal {
      background: var(--teal-soft);
      color: #0a8577;
    }

    .pill-amber {
      background: var(--amber-soft);
      color: #a06405;
    }

    .pill-coral {
      background: var(--coral-soft);
      color: #c22f42;
    }

    .pill-indigo {
      background: var(--accent-soft);
      color: #4147a8;
    }


    .btn-accent {
      background: var(--accent);
      border-color: var(--accent);
      color: #fff;
    }

    .btn-accent:hover {
      background: #3f49c2;
      border-color: #3f49c2;
      color: #fff;
    }

    .btn-ghost {
      background: var(--surface);
      border: 1px solid var(--line);
      color: var(--text);
    }

    .action-ic {
      width: 30px;
      height: 30px;
      border-radius: 8px;
      border: 1px solid var(--line);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: var(--card);
      color: var(--text-soft);
    }

    .action-ic:hover {
      background: var(--surface);
      color: var(--text);
    }

    .skeleton-row td {
      height: 44px;
    }

    .skeleton {
      background: linear-gradient(90deg, #eef0f7 25%, #e4e7f2 37%, #eef0f7 63%);
      background-size: 400% 100%;
      animation: shine 1.4s ease infinite;
      border-radius: 6px;
      height: 14px;
    }

    @keyframes shine {
      0% {
        background-position: 100% 50%
      }

      100% {
        background-position: 0 50%
      }
    }

    .notif-item {
      display: flex;
      gap: 12px;
      padding: 14px 6px;
      border-bottom: 1px solid var(--line);
    }

    .notif-item:last-child {
      border-bottom: none;
    }

    .notif-ic {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
    }

    .notif-item.unread {
      background: rgba(79, 91, 213, .04);
      border-radius: 10px;
    }

    .notif-time {
      color: var(--text-soft);
      font-size: .72rem;
    }

    .ring-wrap {
      position: relative;
      width: 96px;
      height: 96px;
    }

    .ring-wrap svg {
      transform: rotate(-90deg);
    }

    .ring-center {
      position: absolute;
      inset: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .view {
      display: none;
    }

    .view.active {
      display: block;
      animation: fade .25s ease;
    }

    @keyframes fade {
      from {
        opacity: 0;
        transform: translateY(4px)
      }

      to {
        opacity: 1;
        transform: translateY(0)
      }
    }

    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    ::-webkit-scrollbar-thumb {
      background: #c9cde3;
      border-radius: 8px;
    }

    .sidebar-close {
      display: none;
      margin-left: auto;
      background: transparent;
      border: none;
      color: #5b6278;
      font-size: 1.25rem;
      line-height: 1;
      cursor: pointer;
    }

    /* ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚ÂÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚ÂÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ Bottom Nav (mobile) ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚ÂÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚ÂÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ */
    .bottom-nav {
      display: none;
    }

    @media(max-width:991px) {
      .sidebar {
        position: fixed;
        left: -280px;
        z-index: 60;
        transition: left .25s cubic-bezier(.4,0,.2,1);
        width: 280px;
      }

      .sidebar.open {
        left: 0;
        box-shadow: 8px 0 32px rgba(10,12,30,.18);
      }

      .sidebar-close {
        display: inline-flex;
      }

      .content {
        width: 100%;
      }

      .topbar {
        padding: 8px 14px;
        position: sticky;
        top: 0;
        z-index: 40;
        flex-wrap: wrap;
        row-gap: 8px;
        box-shadow: 0 1px 0 var(--line), 0 2px 8px rgba(10,12,30,.05);
      }

      .topbar .page-title {
        font-size: 1rem;
        line-height: 1.2;
      }

      .topbar .page-sub {
        font-size: .72rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 160px;
      }

      .search-box {
        order: 5;
        flex-basis: 100%;
        max-width: none;
        margin-left: 0;
        margin-top: 2px;
      }

      .search-box input {
        padding: 6px 12px 6px 32px;
        font-size: .8rem;
      }

      .main {
        padding: 16px 14px 90px;
      }

      /* Stat cards: smaller on mobile */
      .stat-card {
        padding: 14px 12px;
        gap: 10px;
      }

      .stat-ic {
        width: 38px;
        height: 38px;
        font-size: 1rem;
        flex-shrink: 0;
      }

      .stat-num {
        font-size: 1.15rem;
      }

      .stat-label {
        font-size: .7rem;
      }

      .section-card {
        padding: 16px 14px;
      }

      /* Horizontal scroll for tables on mobile */
      .section-card, .card-flat {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }

      /* Bottom Nav */
      .bottom-nav {
        display: flex;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 50;
        background: #fff;
        border-top: 1px solid var(--line);
        box-shadow: 0 -4px 20px rgba(10,12,30,.08);
        padding: 6px 0 env(safe-area-inset-bottom, 6px);
        justify-content: space-around;
        align-items: flex-end;
      }

      .bn-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
        padding: 6px 8px;
        cursor: pointer;
        flex: 1;
        position: relative;
        border: none;
        background: transparent;
        color: var(--text-soft);
        transition: color .2s ease;
        -webkit-tap-highlight-color: transparent;
      }

      .bn-item i {
        font-size: 1.35rem;
        line-height: 1;
        transition: transform .2s cubic-bezier(.34,1.56,.64,1);
      }

      .bn-item span {
        font-size: .58rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
      }

      .bn-item.active {
        color: var(--accent);
      }

      .bn-item.active i {
        transform: translateY(-2px) scale(1.12);
      }

      .bn-item .bn-badge {
        position: absolute;
        top: 2px;
        right: calc(50% - 18px);
        background: var(--coral);
        color: #fff;
        font-size: .55rem;
        font-weight: 700;
        border-radius: 20px;
        padding: 1px 5px;
        min-width: 16px;
        text-align: center;
        border: 2px solid #fff;
      }

      .bn-item.active::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 28px;
        height: 3px;
        background: var(--accent);
        border-radius: 0 0 4px 4px;
      }
    }

    @media(max-width:480px) {
      .topbar .page-sub {
        display: none !important;
      }

      .bn-item {
        padding: 6px 4px;
      }

      .bn-item i {
        font-size: 1.2rem;
      }

      .bn-item span {
        font-size: .55rem;
      }
    }

    .period-group .btn {
      border-color: var(--line);
      color: var(--text-soft);
      font-weight: 600;
    }

    .period-group .btn.active {
      background: var(--accent);
      border-color: var(--accent);
      color: #fff;
    }
  </style>
</head>

<body>
  @php $isBranchAdmin = !empty($branch); @endphp

  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <div class="app-shell">

    <!-- ============ SIDEBAR ============ -->
    <aside class="sidebar" id="sidebar">
      <div class="brand"><img src="{{ asset('logo/guru.png') }}" class="brand-logo" alt="Guru Group"><button class="sidebar-close" id="sidebarCloseBtn"><i class="bi bi-x-lg"></i></button></div>

      <nav class="side-nav">
        <div class="nav-label">Workspace</div>

        <div class="side-link active" data-view="dashboard">
          <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </div>
        <div class="side-link" data-view="daily-plan">
          <i class="bi bi-journal-text"></i> Lead
        </div>
        <div class="side-link" data-view="leads">
          <i class="bi bi-person-check-fill"></i> Booking
        </div>
        <div class="side-link" data-view="non-leads">
          <i class="bi bi-person-x-fill"></i> Non Booking
        </div>

        <div class="nav-label">Session</div>
        <div class="side-link" id="logoutLink">
          <i class="bi bi-box-arrow-right"></i> Logout
        </div>
      </nav>

      <div class="side-foot">
        <img src="{{ asset('logo/guru.png') }}" alt="Guru Group">
        <div>
          <div class="name">Guru Group</div>
          <div class="role">{{ $isBranchAdmin ? $branch->name . ' Admin' : 'Super Admin' }}</div>
        </div>
      </div>
    </aside>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="content">

      <div class="topbar">
        <button class="btn btn-ghost d-lg-none" id="burgerBtn" style="border:1px solid var(--line);background:var(--surface);flex-shrink:0;"><i class="bi bi-list"></i></button>
        <div class="d-flex align-items-center gap-2" style="min-width:0;">
          <img src="{{ asset('logo/guru.png') }}" alt="Guru" class="d-lg-none" style="height:30px;width:auto;object-fit:contain;flex-shrink:0;">
          <div style="min-width:0;">
            <p class="page-title" id="pageTitle">Dashboard</p>
            <p class="page-sub" id="pageSub">Overview of your organization today</p>
          </div>
        </div>
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" id="globalSearch" placeholder="Search this page...">
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
          <img src="{{ asset('logo/guru.png') }}" class="d-none d-lg-block" style="height:32px;width:auto;object-fit:contain;" alt="Guru Group">
        </div>
      </div>

      {{-- ===== BOTTOM NAVIGATION (mobile only) ===== --}}
      <nav class="bottom-nav" id="bottomNav">
        <button class="bn-item active" data-view="dashboard">
          <i class="bi bi-grid-1x2-fill"></i>
          <span>Home</span>
        </button>
        <button class="bn-item" data-view="daily-plan">
          <i class="bi bi-journal-text"></i>
          <span>Lead</span>
        </button>
        <button class="bn-item" data-view="leads">
          <i class="bi bi-person-check-fill"></i>
          <span>Booking</span>
        </button>
        <button class="bn-item" id="bnAdminMoreBtn">
          <i class="bi bi-grid"></i>
          <span>More</span>
        </button>
      </nav>

      <main class="main" id="mainArea">

        <!-- ================= DASHBOARD VIEW ================= -->
        <section class="view active" id="view-dashboard">
          <div class="row g-3 mb-2">
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--accent-soft);color:var(--accent);"><i class="bi bi-journal-text"></i></div>
                <div>
                  <div class="stat-num" id="statTotal">--</div>
                  <div class="stat-label">Leads</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--teal-soft);color:#0a8577;"><i class="bi bi-calendar-check-fill"></i></div>
                <div>
                  <div class="stat-num" id="statToday">--</div>
                  <div class="stat-label">Plans Today</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--teal-soft);color:#0a8577;"><i class="bi bi-person-check-fill"></i></div>
                <div>
                  <div class="stat-num" id="statLeads">--</div>
                  <div class="stat-label">Bookings</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--coral-soft);color:#c22f42;"><i class="bi bi-person-x-fill"></i></div>
                <div>
                  <div class="stat-num" id="statRejected">--</div>
                  <div class="stat-label">Rejected</div>
                </div>
              </div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-lg-4">
              <div class="section-card h-100">
                <h5 class="mb-3">Conversion Rate</h5>
                <div class="d-flex align-items-center gap-4">
                  <div class="ring-wrap">
                    <svg width="96" height="96">
                      <circle cx="48" cy="48" r="40" stroke="#eef0f7" stroke-width="10" fill="none" />
                      <circle id="convRingCircle" cx="48" cy="48" r="40" stroke="#0fb5a3" stroke-width="10" fill="none"
                        stroke-linecap="round" stroke-dasharray="251" stroke-dashoffset="251" />
                    </svg>
                    <div class="ring-center">
                      <div class="fw-bold" id="convRingPct" style="font-family:'Sora',sans-serif;">0%</div>
                      <div style="font-size:.65rem;color:var(--text-soft);">converted</div>
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <div class="d-flex justify-content-between small mb-1"><span><i class="bi bi-square-fill" style="color:#0fb5a3;font-size:.6rem;"></i> Bookings</span><span class="mono" id="legendLeads">0</span></div>
                    <div class="d-flex justify-content-between small mb-1"><span><i class="bi bi-square-fill" style="color:#f5a524;font-size:.6rem;"></i> Open Plans</span><span class="mono" id="legendOpen">0</span></div>
                    <div class="d-flex justify-content-between small"><span><i class="bi bi-square-fill" style="color:#ef5d6f;font-size:.6rem;"></i> Rejected</span><span class="mono" id="legendRejected">0</span></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-8">
              <div class="section-card h-100">
                <div class="section-head">
                  <h5>Recent Bookings</h5>
                  <a href="#" class="ms-auto small" data-view="leads">View all &rarr;</a>
                </div>
                <div class="table-responsive">
                  <table class="tbl">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Salesperson</th>
                        <th>Company</th>
                        <th>Type of Service</th>
                      </tr>
                    </thead>
                    <tbody id="dashRecentLeads"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ================= DAILY PLAN VIEW ================= -->
        <section class="view" id="view-daily-plan">
          <div class="section-card">
            <div class="section-head">
              <h5>Lead</h5>
              @if (!$isBranchAdmin)
              <select class="form-select form-select-sm ms-auto" id="dailyPlanBranchFilter" style="width:200px;">
                <option value="">All Branches</option>
              </select>
              @endif
              @if ($isBranchAdmin)
              <button class="btn btn-accent btn-sm ms-auto" onclick="openDailyPlanModal()"><i class="bi bi-plus-lg"></i> Add Lead</button>
              @endif
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Si.No</th>
                    @if (!$isBranchAdmin)
                    <th>Branch</th>
                    @endif
                    <th>Date</th>
                    <th>Salesperson</th>
                    <th>Company Address</th>
                    <th>Company Details</th>
                    <th>Purpose of Visit</th>
                    <th>Type of Service</th>
                    <th>Inspection</th>
                    <th>Quotation</th>
                    <th>Followup 1</th>
                    <th>Followup 2</th>
                    <th>Followup 3</th>
                    <th>Remarks</th>
                    <th>Updated</th>
                    <th class="text-end">Actions</th>
                  </tr>
                </thead>
                <tbody id="dailyPlanBody"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ================= LEADS VIEW ================= -->
        <section class="view" id="view-leads">
          <div class="section-card">
            <div class="section-head">
              <h5>Booking</h5>
              <span class="text-muted small ms-auto">Converted daily plan entries</span>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Si.No</th>
                    @if (!$isBranchAdmin)
                    <th>Branch</th>
                    @endif
                    <th>Date</th>
                    <th>Salesperson</th>
                    <th>Company Address</th>
                    <th>Company Details</th>
                    <th>Purpose of Visit</th>
                    <th>Type of Service</th>
                    <th>Inspection</th>
                    <th>Quotation</th>
                    <th>Followup 1</th>
                    <th>Followup 2</th>
                    <th>Followup 3</th>
                    <th>Remarks</th>
                    <th>Updated</th>
                  </tr>
                </thead>
                <tbody id="leadsBody"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ================= NON-LEADS VIEW ================= -->
        <section class="view" id="view-non-leads">
          <div class="section-card">
            <div class="section-head">
              <h5>Non Booking</h5>
              <span class="text-muted small ms-auto">Rejected daily plan entries</span>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Si.No</th>
                    @if (!$isBranchAdmin)
                    <th>Branch</th>
                    @endif
                    <th>Date</th>
                    <th>Salesperson</th>
                    <th>Company Address</th>
                    <th>Company Details</th>
                    <th>Purpose of Visit</th>
                    <th>Type of Service</th>
                    <th>Inspection</th>
                    <th>Quotation</th>
                    <th>Followup 1</th>
                    <th>Followup 2</th>
                    <th>Followup 3</th>
                    <th>Remarks</th>
                    <th>Updated</th>
                  </tr>
                </thead>
                <tbody id="nonLeadsBody"></tbody>
              </table>
            </div>
          </div>
        </section>

      </main>
    </div>
  </div>

  <!-- ============ Daily Plan Modal ============ -->
  @if ($isBranchAdmin)
  <div class="modal fade" id="dailyPlanModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="dailyPlanModalTitle">Add Lead</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="dailyPlanForm">
          <div class="modal-body">
            <input type="hidden" name="id" id="dpFormId">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label small">Date <span class="text-danger">*</span></label>
                <input required type="date" class="form-control form-control-sm" name="date" id="dpDate">
              </div>
              <div class="col-md-4">
                <label class="form-label small">Salesperson</label>
                <input type="text" class="form-control form-control-sm" name="salesperson" placeholder="Salesperson name">
              </div>
              <div class="col-md-4">
                <label class="form-label small">Type of Service</label>
                <input type="text" class="form-control form-control-sm" name="type_of_service" placeholder="e.g. AMC, Installation">
              </div>
              <div class="col-md-6">
                <label class="form-label small">Company Address</label>
                <textarea class="form-control form-control-sm" name="company_address" rows="2" placeholder="Full address"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label small">Company Details</label>
                <textarea class="form-control form-control-sm" name="company_details" rows="2" placeholder="Company name, contact info..."></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label small">Purpose of Visit</label>
                <textarea class="form-control form-control-sm" name="purpose_of_visit" rows="2" placeholder="Purpose of visit"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label small">Inspection</label>
                <textarea class="form-control form-control-sm" name="inspection" rows="2" placeholder="Inspection details"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label small">Quotation</label>
                <textarea class="form-control form-control-sm" name="quotation" rows="2" placeholder="Quotation details"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label small">Followup 1</label>
                <textarea class="form-control form-control-sm" name="followup1" rows="2" placeholder="Followup 1"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label small">Followup 2</label>
                <textarea class="form-control form-control-sm" name="followup2" rows="2" placeholder="Followup 2"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label small">Followup 3</label>
                <textarea class="form-control form-control-sm" name="followup3" rows="2" placeholder="Followup 3"></textarea>
              </div>
              <div class="col-12">
                <label class="form-label small">Remarks</label>
                <textarea class="form-control form-control-sm" name="remarks" rows="2" placeholder="Additional remarks"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-accent" id="dailyPlanSubmitBtn">Save Lead</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif

  <!-- ============ Logout confirm modal ============ -->
  <div class="modal fade" id="logoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-body text-center py-4">
          <i class="bi bi-box-arrow-right" style="font-size:2rem;color:var(--coral);"></i>
          <h6 class="mt-2 mb-1">Log out of Guru Group?</h6>
          <p class="text-muted small">You'll need to sign in again to access the dashboard.</p>
          <div class="d-flex gap-2 justify-content-center mt-3">
            <button class="btn btn-ghost btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-sm px-3 text-white" style="background:var(--coral);" id="confirmLogout">Log out</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

  <script>
    /* =========================================================================
   CONFIG
   Point these at your real backend endpoints. Every function below calls
   $.ajax() first; if the endpoint isn't reachable (e.g. while you're still
   wiring up the backend) it falls back to local demo data so the UI stays
   usable during development. Remove the fallback once your API is live.
   ========================================================================= */
    const API = {
      dashboardStats: "{{ route('admin.api.dashboard-stats') }}",
      branches: "{{ route('admin.api.branches') }}",
      dailyPlans: "{{ route('admin.api.daily-plans') }}",
      dailyPlanConvert: "{{ route('admin.api.daily-plans.convert') }}",
      dailyPlanReject: "{{ route('admin.api.daily-plans.reject') }}",
      leads: "{{ route('admin.api.leads') }}",
      nonLeads: "{{ route('admin.api.non-leads') }}",
    };

    const ADMIN_BRANCH = document.documentElement.getAttribute('data-branch') || null;
    const IS_BRANCH_ADMIN = ADMIN_BRANCH !== null;

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    /* ---------------- Generic AJAX wrapper ---------------- */
    function apiGet(url) {
      return $.ajax({
        url: url,
        method: "GET",
        dataType: "json"
      }).fail(function(xhr) {
        console.error("GET " + url + " failed", xhr.responseJSON || xhr.statusText);
      });
    }

    function apiPost(url, payload) {
      return $.ajax({
        url: url,
        method: "POST",
        data: payload,
        dataType: "json"
      }).fail(function(xhr) {
        console.error("POST " + url + " failed", xhr.responseJSON || xhr.statusText);
      });
    }

    /* =========================================================================
       NAVIGATION
       ========================================================================= */
    const titles = {
      dashboard: ["Dashboard", "Track sales visits, leads and conversions"],
      "daily-plan": ["Lead", "Track sales visits and followups"],
      leads: ["Booking", "Converted daily plan entries"],
      "non-leads": ["Non Booking", "Rejected daily plan entries"]
    };

    function goTo(viewName) {
      if (!titles[viewName]) return;
      $(".view").removeClass("active");
      $("#view-" + viewName).addClass("active");
      $(".side-link[data-view]").removeClass("active");
      $(".side-link[data-view='" + viewName + "']").addClass("active");
      $("#pageTitle").text(titles[viewName][0]);
      $("#pageSub").text(titles[viewName][1]);
      closeSidebar();
      $("#globalSearch").val("");
      $(".view table.tbl tbody tr").show();

      /* sync bottom nav */
      $(".bn-item[data-view]").removeClass("active");
      $(".bn-item[data-view='" + viewName + "']").addClass("active");

      /* scroll to top */
      $("#mainArea").scrollTop(0);
      window.scrollTo(0, 0);

      if (viewName === "dashboard") loadDashboard();
      if (viewName === "daily-plan") loadDailyPlans();
      if (viewName === "leads") loadLeads();
      if (viewName === "non-leads") loadNonLeads();
    }

    $(document).on("click", "[data-view]", function(e) {
      e.preventDefault();
      goTo($(this).data("view"));
    });

    /* bottom nav "More" button opens sidebar */
    $("#bnAdminMoreBtn").on("click", function() {
      openSidebar();
    });

    /* ---- Mobile sidebar open / close ---- */
    function openSidebar() {
      $("#sidebar").addClass("open");
      $("#sidebarOverlay").addClass("show");
    }

    function closeSidebar() {
      $("#sidebar").removeClass("open");
      $("#sidebarOverlay").removeClass("show");
    }
    $("#burgerBtn").on("click", function() {
      $("#sidebar").hasClass("open") ? closeSidebar() : openSidebar();
    });
    $("#sidebarCloseBtn, #sidebarOverlay").on("click", closeSidebar);

    /* ---- Global search: filters visible rows on whichever section is open ---- */
    $("#globalSearch").on("input", function() {
      const q = $(this).val().toLowerCase().trim();
      $(".view.active table.tbl tbody tr").each(function() {
        const match = $(this).text().toLowerCase().indexOf(q) !== -1;
        $(this).toggle(q === "" || match);
      });
    });

    /* =========================================================================
       DASHBOARD
       ========================================================================= */
    function loadDashboard() {
      apiGet(API.dashboardStats).then(function(stats) {
        const total = stats.total || 0;
        const leads = stats.leads || 0;
        const rejected = stats.nonLeads || 0;

        $("#statTotal").text(total);
        $("#statToday").text(stats.today || 0);
        $("#statLeads").text(leads);
        $("#statRejected").text(rejected);

        const decided = leads + rejected;
        const pct = decided ? Math.round((leads / decided) * 100) : 0;
        const circ = 251;
        $("#convRingCircle").css("stroke-dashoffset", circ - (circ * pct / 100));
        $("#convRingPct").text(pct + "%");
        $("#legendLeads").text(leads);
        $("#legendOpen").text(total);
        $("#legendRejected").text(rejected);
      });

      apiGet(API.leads).then(function(rows) {
        const recent = (rows || []).slice(0, 5);
        $("#dashRecentLeads").html(recent.length ? recent.map(function(p) {
          return "<tr><td class='mono small'>" + p.date + "</td>" +
            "<td>" + (p.salesperson || '-') + "</td>" +
            "<td>" + (p.company_details || p.company_address || '-') + "</td>" +
            "<td>" + (p.type_of_service || '-') + "</td></tr>";
        }).join("") : '<tr><td colspan="4" class="text-center text-muted py-3">No bookings yet.</td></tr>');
      });
    }

    /* =========================================================================
       DAILY PLAN
       ========================================================================= */
    let branchesCache = [];
    let dailyPlansCache = [];

    function loadDailyPlans() {
      $("#dailyPlanBody").html(skeletonRows(4, 15));
      if (!IS_BRANCH_ADMIN && !$("#dailyPlanBranchFilter option:not(:first)").length) {
        loadBranchesToDailyPlanFilter();
      }
      apiGet(API.dailyPlans).then(function(rows) {
        dailyPlansCache = rows || [];
        renderDailyPlans(dailyPlansCache);
      }).fail(function() {
        $("#dailyPlanBody").html('<tr><td colspan="15" class="text-center text-muted py-4">Failed to load leads.</td></tr>');
      });
    }

    function loadBranchesToDailyPlanFilter() {
      var src = (branchesCache && branchesCache.length) ? branchesCache : [];
      if (!src.length) {
        apiGet(API.branches).then(function(rows) {
          branchesCache = rows || [];
          populateDailyPlanFilter(branchesCache);
        });
      } else {
        populateDailyPlanFilter(src);
      }
    }

    function populateDailyPlanFilter(list) {
      var val = $("#dailyPlanBranchFilter").val();
      var html = '<option value="">All Branches</option>' + list.map(function(b) {
        return '<option value="' + b.name + '">' + b.name + '</option>';
      }).join("");
      $("#dailyPlanBranchFilter").html(html).val(val);
    }

    $("#dailyPlanBranchFilter").on("change", function() {
      renderDailyPlans(dailyPlansCache);
    });

    function renderDailyPlans(rows) {
      var filterBranch = $("#dailyPlanBranchFilter").val();
      if (filterBranch) {
        rows = rows.filter(function(p) { return p.branch === filterBranch; });
      }
      if (!rows.length) {
        $("#dailyPlanBody").html('<tr><td colspan="' + (IS_BRANCH_ADMIN ? 15 : 16) + '" class="text-center text-muted py-4">No leads yet.</td></tr>');
        return;
      }
      $("#dailyPlanBody").html(rows.map(function(p, i) {
        let html = "<tr>" +
          "<td class='mono small'>" + p.sino + "</td>";
        if (!IS_BRANCH_ADMIN) {
          html += "<td><span class='badge bg-info bg-opacity-10 text-info'>" + (p.branch || '-') + "</span></td>";
        }
        html += "<td class='mono'>" + p.date + "</td>" +
          "<td>" + (p.salesperson || '-') + "</td>" +
          "<td>" + (p.company_address || '-') + "</td>" +
          "<td>" + (p.company_details || '-') + "</td>" +
          "<td>" + (p.purpose_of_visit || '-') + "</td>" +
          "<td>" + (p.type_of_service || '-') + "</td>" +
          "<td>" + (p.inspection || '-') + "</td>" +
          "<td>" + (p.quotation || '-') + "</td>" +
          "<td>" + (p.followup1 || '-') + "</td>" +
          "<td>" + (p.followup2 || '-') + "</td>" +
          "<td>" + (p.followup3 || '-') + "</td>" +
          "<td>" + (p.remarks || '-') + "</td>" +
          "<td class='mono small text-muted'>" + p.updated_at + "</td>";
        if (IS_BRANCH_ADMIN) {
          html += "<td class='text-end whitespace-nowrap'>" +
            "<span class='action-ic me-1' title='Edit' onclick='editDailyPlan(" + i + ")'><i class='bi bi-pencil-fill'></i></span>" +
            "<span class='action-ic text-danger me-1' title='Delete' onclick='deleteDailyPlan(" + p.id + ")'><i class='bi bi-trash-fill'></i></span>" +
            "<span class='action-ic me-1' style='color:var(--teal);' title='Convert to Booking' onclick='convertDailyPlan(" + p.id + ")'><i class='bi bi-person-check-fill'></i></span>" +
            "<span class='action-ic text-danger me-1' title='Reject' onclick='rejectDailyPlan(" + p.id + ")'><i class='bi bi-person-x-fill'></i></span>" +
            "<button type='button' class='btn btn-sm btn-outline-secondary'>Generate</button>" +
            "</td>";
        } else {
          html += "<td class='text-end whitespace-nowrap'>" +
            "<span class='action-ic me-1' style='color:var(--teal);' title='Convert to Booking' onclick='convertDailyPlan(" + p.id + ")'><i class='bi bi-person-check-fill'></i></span>" +
            "<span class='action-ic text-danger me-1' title='Reject' onclick='rejectDailyPlan(" + p.id + ")'><i class='bi bi-person-x-fill'></i></span>" +
            "<button type='button' class='btn btn-sm btn-outline-secondary'>Generate</button>" +
            "</td>";
        }
        html += "</tr>";
        return html;
      }).join(""));
    }

    function openDailyPlanModal(data) {
      $("#dailyPlanForm")[0].reset();
      $("#dpFormId").val("");
      $("#dailyPlanModalTitle").text("Add Lead");
      $("#dailyPlanSubmitBtn").text("Save Lead");
      if (data) {
        $("#dpFormId").val(data.id);
        $("#dailyPlanModalTitle").text("Edit Lead");
        $("#dailyPlanSubmitBtn").text("Update Lead");
        const form = $("#dailyPlanForm")[0];
        form.date.value = data.date || "";
        form.salesperson.value = data.salesperson || "";
        form.company_address.value = data.company_address || "";
        form.company_details.value = data.company_details || "";
        form.purpose_of_visit.value = data.purpose_of_visit || "";
        form.type_of_service.value = data.type_of_service || "";
        form.inspection.value = data.inspection || "";
        form.quotation.value = data.quotation || "";
        form.followup1.value = data.followup1 || "";
        form.followup2.value = data.followup2 || "";
        form.followup3.value = data.followup3 || "";
        form.remarks.value = data.remarks || "";
      }
      new bootstrap.Modal(document.getElementById("dailyPlanModal")).show();
    }

    function editDailyPlan(index) {
      openDailyPlanModal(dailyPlansCache[index]);
    }

    function deleteDailyPlan(id) {
      if (!confirm("Delete this lead entry?")) return;
      apiPost(API.dailyPlans + "/delete", { id: id }).then(function() {
        loadDailyPlans();
      });
    }

    $("#dailyPlanForm").on("submit", function(e) {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(this));
      const url = data.id ? API.dailyPlans + "/update" : API.dailyPlans;
      apiPost(url, data).then(function() {
        loadDailyPlans();
        bootstrap.Modal.getInstance(document.getElementById("dailyPlanModal")).hide();
      }).fail(function(xhr) {
        console.error("Saving daily plan failed", xhr.responseJSON || xhr.statusText);
      });
    });

    function convertDailyPlan(id) {
      if (!confirm("Convert this lead to a Booking?")) return;
      apiPost(API.dailyPlanConvert, { id: id }).then(function() {
        loadDailyPlans();
      });
    }

    function rejectDailyPlan(id) {
      if (!confirm("Reject this lead? It will move to Non Booking.")) return;
      apiPost(API.dailyPlanReject, { id: id }).then(function() {
        loadDailyPlans();
      });
    }

    /* =========================================================================
       LEADS / NON-LEADS
       ========================================================================= */
    function renderLeadRows(bodySel, rows, emptyMsg) {
      const cols = IS_BRANCH_ADMIN ? 15 : 16;
      if (!rows.length) {
        $(bodySel).html('<tr><td colspan="' + cols + '" class="text-center text-muted py-4">' + emptyMsg + '</td></tr>');
        return;
      }
      $(bodySel).html(rows.map(function(p) {
        let html = "<tr>" +
          "<td class='mono small'>" + p.sino + "</td>";
        if (!IS_BRANCH_ADMIN) {
          html += "<td><span class='badge bg-info bg-opacity-10 text-info'>" + (p.branch || '-') + "</span></td>";
        }
        html += "<td class='mono'>" + p.date + "</td>" +
          "<td>" + (p.salesperson || '-') + "</td>" +
          "<td>" + (p.company_address || '-') + "</td>" +
          "<td>" + (p.company_details || '-') + "</td>" +
          "<td>" + (p.purpose_of_visit || '-') + "</td>" +
          "<td>" + (p.type_of_service || '-') + "</td>" +
          "<td>" + (p.inspection || '-') + "</td>" +
          "<td>" + (p.quotation || '-') + "</td>" +
          "<td>" + (p.followup1 || '-') + "</td>" +
          "<td>" + (p.followup2 || '-') + "</td>" +
          "<td>" + (p.followup3 || '-') + "</td>" +
          "<td>" + (p.remarks || '-') + "</td>" +
          "<td class='mono small text-muted'>" + p.updated_at + "</td>" +
          "</tr>";
        return html;
      }).join(""));
    }

    function loadLeads() {
      $("#leadsBody").html(skeletonRows(4, IS_BRANCH_ADMIN ? 14 : 15));
      apiGet(API.leads).then(function(rows) {
        renderLeadRows("#leadsBody", rows || [], "No bookings yet.");
      }).fail(function() {
        $("#leadsBody").html('<tr><td colspan="' + (IS_BRANCH_ADMIN ? 14 : 15) + '" class="text-center text-muted py-4">Failed to load leads.</td></tr>');
      });
    }

    function loadNonLeads() {
      $("#nonLeadsBody").html(skeletonRows(4, IS_BRANCH_ADMIN ? 14 : 15));
      apiGet(API.nonLeads).then(function(rows) {
        renderLeadRows("#nonLeadsBody", rows || [], "No rejected entries yet.");
      }).fail(function() {
        $("#nonLeadsBody").html('<tr><td colspan="' + (IS_BRANCH_ADMIN ? 14 : 15) + '" class="text-center text-muted py-4">Failed to load non-bookings.</td></tr>');
      });
    }

    function skeletonRows(count, cols) {
      let html = "";
      for (let i = 0; i < count; i++) {
        html += "<tr class='skeleton-row'>";
        for (let c = 0; c < cols; c++) {
          html += "<td><div class='skeleton'></div></td>";
        }
        html += "</tr>";
      }
      return html;
    }

    /* =========================================================================
       LOGOUT
       ========================================================================= */
    $("#logoutLink").on("click", function() {
      new bootstrap.Modal(document.getElementById("logoutModal")).show();
    });
    $("#confirmLogout").on("click", function() {
      $.post("{{ route('logout') }}", {
        _token: '{{ csrf_token() }}'
      }).then(function() {
        window.location.href = "/";
      });
    });

    /* =========================================================================
       INIT
       ========================================================================= */
    $(function() {
      loadDashboard();
    });
  </script>
</body>

</html>
