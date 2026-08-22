<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#0a8577">
  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
  <link rel="apple-touch-icon" href="{{ asset('pwa/icons/icon-192.png') }}">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <title>Employee Dashboard - Guru Group</title>
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
      --surface: #f3f5fb;
      --card: #ffffff;
      --line: #e7e9f2;
      --text: #1c2033;
      --text-soft: #6b7286;
      --accent: #4f5bd5;
      --accent-soft: #eceeff;
      --teal: #0fb5a3;
      --teal-soft: #e2f9f5;
      --amber: #f5a524;
      --amber-soft: #fff3de;
      --coral: #ef5d6f;
      --coral-soft: #fdeaed;
      --radius: 14px;
    }

    * {
      box-sizing: border-box;
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

    .sidebar-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(10, 12, 20, .5);
      z-index: 55;
    }

    .sidebar-overlay.show {
      display: block;
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

    /* ---------- Topbar ---------- */
    .content {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
    }

    .topbar {
      background: var(--card);
      border-bottom: 1px solid var(--line);
      padding: 14px 28px;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .topbar .page-title {
      font-family: 'Sora', sans-serif;
      font-weight: 700;
      font-size: 1.15rem;
      margin: 0;
    }

    .topbar .page-sub {
      font-size: .78rem;
      color: var(--text-soft);
      margin: 0;
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

    .topbar .user-menu {
      position: relative;
    }

    .topbar .user-menu-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 5px 10px 5px 5px;
      cursor: pointer;
      color: var(--text);
      font-family: inherit;
    }

    .topbar .user-menu-btn:hover {
      border-color: var(--accent);
    }

    .topbar .user-menu-btn img {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--accent-soft);
    }

    .topbar .user-menu-name {
      font-weight: 600;
      font-size: .84rem;
      max-width: 130px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .topbar .user-menu-dropdown {
      display: none;
      position: absolute;
      right: 0;
      top: calc(100% + 8px);
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 14px;
      box-shadow: 0 12px 32px rgba(20, 24, 50, .14);
      min-width: 250px;
      z-index: 80;
      overflow: hidden;
    }

    .topbar .user-menu-dropdown.open {
      display: block;
    }

    .topbar .user-menu-head {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 13px 14px;
      border-bottom: 1px solid var(--line);
    }

    .topbar .user-menu-head img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    .topbar .user-menu-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 11px 14px;
      color: var(--text);
      font-weight: 500;
      font-size: .85rem;
      cursor: pointer;
    }

    .topbar .user-menu-item:hover {
      background: var(--accent-soft);
    }

    .topbar .user-menu-item.logout {
      color: var(--coral);
    }

    /* ---------- Custom modal ---------- */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(10,12,20,.55);
      z-index: 100;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .modal-overlay.show {
      display: flex;
    }
    .modal-panel {
      background: var(--card);
      border-radius: 16px;
      width: 100%;
      max-width: 480px;
      padding: 20px 22px;
      box-shadow: 0 18px 50px rgba(20,24,50,.25);
      max-height: 92vh;
      overflow-y: auto;
    }
    .modal-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }
    .modal-close {
      background: transparent;
      border: none;
      color: var(--text-soft);
      font-size: 1.1rem;
      cursor: pointer;
    }
    .modal-close:hover {
      color: var(--text);
    }

    .main {
      padding: 26px 28px 60px;
    }

    /* ---------- Cards ---------- */
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
      gap: 10px;
      margin-bottom: 16px;
    }

    .section-card h5 {
      margin: 0;
      font-weight: 700;
    }

    .view {
      display: none;
    }

    .view.active {
      display: block;
    }

    /* ---------- Table ---------- */
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
      white-space: nowrap;
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

    .avatar-letter {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: var(--accent);
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.8rem;
      text-transform: uppercase;
      flex-shrink: 0;
    }
    .avatar-letter.avatar-lg {
      width: 64px;
      height: 64px;
      font-size: 1.5rem;
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

    .btn-ghost:hover {
      background: #e9ebf5;
      color: var(--text);
    }

    /* ---------- Profile ---------- */
    .profile-head {
      display: flex;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
    }

    .profile-head .avatar-letter {
      width: 92px;
      height: 92px;
      font-size: 2rem;
      border: 3px solid rgba(79, 91, 213, .4);
    }

    .profile-head .p-name {
      font-size: 1.35rem;
      font-weight: 700;
    }

    .profile-head .p-role {
      color: var(--accent);
      font-weight: 600;
      font-size: .9rem;
      margin: 3px 0 8px;
    }

    .profile-head .p-meta {
      display: flex;
      gap: 18px;
      flex-wrap: wrap;
      font-size: .82rem;
      color: var(--text-soft);
    }

    .profile-head .p-meta strong {
      color: var(--text);
      display: block;
      font-size: .86rem;
      margin-top: 1px;
    }

    .detail-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
      gap: 14px;
    }

    .detail-item {
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 12px 14px;
    }

    .detail-item .l {
      font-size: .7rem;
      color: var(--text-soft);
      text-transform: uppercase;
      letter-spacing: .06em;
    }

    .detail-item .v {
      font-size: .9rem;
      font-weight: 600;
      margin-top: 4px;
      word-break: break-word;
    }

    /* ---------- Notifications ---------- */
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

    /* ---------- Salary ---------- */
    .salary-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 14px;
    }

    .salary-box {
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 14px;
      text-align: center;
    }

    .salary-box .l {
      font-size: .7rem;
      color: var(--text-soft);
      text-transform: uppercase;
      letter-spacing: .06em;
    }

    .salary-box .v {
      font-size: 1.05rem;
      font-weight: 700;
      margin-top: 5px;
      font-family: 'Sora', sans-serif;
    }

    /* ── Bottom Nav (mobile) ── */
    .bottom-nav {
      display: none;
    }

    @media(max-width:991px) {
      /* Sidebar slide-in */
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

      /* Main content takes full width */
      .content {
        width: 100%;
      }

      /* Topbar overhaul */
      .topbar {
        padding: 0 14px;
        height: 60px;
        position: sticky;
        top: 0;
        z-index: 40;
        box-shadow: 0 1px 0 var(--line), 0 2px 8px rgba(10,12,30,.05);
      }

      /* Hide desktop user-menu name & chevron on mobile */
      .topbar .user-menu-name,
      .topbar .user-menu-btn .bi-chevron-down {
        display: none !important;
      }

      /* Compact user-menu-btn on mobile: just avatar */
      .topbar .user-menu-btn {
        padding: 4px;
        border-radius: 50%;
        border: 2px solid var(--line);
        background: var(--surface);
        width: 38px;
        height: 38px;
        justify-content: center;
      }

      /* Icon btn sizing */
      .topbar .icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
      }

      /* Page title compact */
      .topbar .page-title {
        font-size: 1rem;
      }

      .topbar .page-sub {
        font-size: .72rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 160px;
      }

      /* Burger button */
      #burgerBtn {
        width: 38px;
        height: 38px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 1.2rem;
        flex-shrink: 0;
      }

      /* Main area padding */
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

      /* Section cards */
      .section-card {
        padding: 16px 14px;
      }

      /* Tables: horizontal scroll */
      .tbl-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }

      /* Profile */
      .profile-head {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
      }

      /* Salary grid */
      .salary-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      /* Detail grid */
      .detail-grid {
        grid-template-columns: repeat(2, 1fr);
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
        padding: 6px 10px;
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

      /* Active pill indicator above bottom nav icon */
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

    /* Extra small phones */
    @media(max-width:480px) {
      .topbar .page-sub {
        max-width: 110px;
      }

      .bn-item {
        padding: 6px 6px;
      }

      .bn-item i {
        font-size: 1.2rem;
      }

      .bn-item span {
        font-size: .55rem;
      }
    }
  </style>
</head>

<body>

  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <div class="app-shell">

    <!-- ============ SIDEBAR ============ -->
    <aside class="sidebar" id="sidebar">
      <div class="brand">
        <img src="{{ asset('logo/guru.png') }}" class="brand-logo" alt="Guru Group">
        <button class="sidebar-close" id="sidebarCloseBtn"><i class="bi bi-x-lg"></i></button>
      </div>

      <nav class="side-nav">
        <div class="nav-label">Employee Menu</div>
        <div class="side-link active" data-view="dashboard"><i class="bi bi-grid-1x2-fill"></i> Dashboard</div>
        <div class="side-link" data-view="profile"><i class="bi bi-person-badge-fill"></i> Profile</div>
        <div class="side-link" data-view="attendance"><i class="bi bi-calendar2-check-fill"></i> Attendance</div>
        <div class="side-link" data-view="reports"><i class="bi bi-file-earmark-bar-graph-fill"></i> Daily Reports</div>
        <div class="side-link" data-view="work-update"><i class="bi bi-journal-check"></i> Work Update</div>
        <div class="side-link" data-view="leave">
          <i class="bi bi-file-earmark-text-fill"></i> Leave / Permission
          <span class="badge-pill" id="leaveBadge">{{ $pendingLeaves->count() }}</span>
        </div>
        <div class="side-link" data-view="notifications">
          <i class="bi bi-bell-fill"></i> Notifications
          <span class="badge-pill" id="notifBadge" @if($unreadNotifications===0) style="display:none;" @endif>{{ $unreadNotifications }}</span>
        </div>
        <div class="side-link" data-view="salary"><i class="bi bi-cash-stack"></i> Salary</div>

        <div class="nav-label">Session</div>
        <a href="#" class="side-link logout-link"><i class="bi bi-box-arrow-right"></i> Logout</a>
      </nav>

      <div class="side-foot">
        <img src="{{ asset('logo/guru.png') }}" alt="Guru Group">
        <div>
          <div class="name">{{ $employee->name }}</div>
          <div class="role">{{ $employee->employee_id }}</div>
        </div>
      </div>
    </aside>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="content">

      <div class="topbar">
        {{-- Burger (mobile only) --}}
        <button class="btn btn-ghost d-lg-none" id="burgerBtn"
          style="border:1px solid var(--line);background:var(--surface);flex-shrink:0;">
          <i class="bi bi-list"></i>
        </button>

        {{-- Logo + Title --}}
        <div class="d-flex align-items-center gap-2" style="min-width:0;">
          {{-- Logo visible only on mobile --}}
          <img src="{{ asset('logo/guru.png') }}" alt="Guru" class="d-lg-none" style="height:30px;width:auto;object-fit:contain;flex-shrink:0;">
          <div style="min-width:0;">
            <p class="page-title" id="pageTitle" style="line-height:1.2;">Dashboard</p>
            <p class="page-sub d-none d-sm-block" id="pageSub">Welcome back, {{ $employee->name }}</p>
          </div>
        </div>

        {{-- Right actions --}}
        <div class="ms-auto d-flex align-items-center gap-2">
          {{-- Notifications --}}
          <div class="icon-btn" id="topNotifBtn" data-view="notifications" style="cursor:pointer;">
            <i class="bi bi-bell"></i>
            @if($unreadNotifications > 0)
              <span class="dot" id="topEmpNotifDot"></span>
            @endif
          </div>

          {{-- User menu (avatar only on mobile, full on desktop) --}}
          <div class="user-menu">
            <button class="user-menu-btn" id="userMenuBtn" type="button">
              <div class="avatar-letter" style="width:32px;height:32px;font-size:.85rem;">{{ substr($employee->name, 0, 1) }}</div>
              <span class="user-menu-name">{{ $employee->name }}</span>
              <i class="bi bi-chevron-down small"></i>
            </button>
            <div class="user-menu-dropdown" id="userMenuDropdown">
              <div class="user-menu-head">
                <div class="avatar-letter">{{ substr($employee->name, 0, 1) }}</div>
                <div>
                  <div class="fw-semibold" style="font-size:.86rem;">{{ $employee->name }}</div>
                  <div class="small" style="color:var(--text-soft);">{{ $employee->employee_id }}@if($employee->designation) &middot; {{ $employee->designation }}@endif</div>
                </div>
              </div>
              <a href="#" class="user-menu-item" data-view="profile" style="color:var(--text);"><i class="bi bi-person"></i> My Profile</a>
              <a href="#" class="user-menu-item logout logout-link"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
          </div>
        </div>
      </div>

      {{-- ===== BOTTOM NAVIGATION (mobile only) ===== --}}
      <nav class="bottom-nav" id="bottomNav">
        <button class="bn-item active" data-view="dashboard">
          <i class="bi bi-grid-1x2-fill"></i>
          <span>Home</span>
        </button>
        <button class="bn-item" data-view="attendance">
          <i class="bi bi-calendar2-check-fill"></i>
          <span>Attendance</span>
        </button>
        <button class="bn-item" data-view="leave">
          <i class="bi bi-file-earmark-text-fill"></i>
          <span>Leave</span>
          @if($pendingLeaves->count() > 0)
            <span class="bn-badge">{{ $pendingLeaves->count() }}</span>
          @endif
        </button>
        <button class="bn-item" data-view="notifications">
          <i class="bi bi-bell-fill"></i>
          <span>Alerts</span>
          @if($unreadNotifications > 0)
            <span class="bn-badge" id="bnNotifBadge">{{ $unreadNotifications }}</span>
          @endif
        </button>
        <button class="bn-item" id="bnMoreBtn">
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
                <div class="stat-ic" style="background:var(--teal-soft);color:#0a8577;"><i class="bi bi-check-circle-fill"></i></div>
                <div>
                  <div class="stat-num" id="statToday">@if($todayAtt && $todayAtt->check_in){{ $todayAtt->check_in->format('h:i A') }}@else-- @endif</div>
                  <div class="stat-label">Clock In</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--amber-soft);color:#a06405;"><i class="bi bi-airplane-fill"></i></div>
                <div>
                  <div class="stat-num">{{ $remainingLeaves }}</div>
                  <div class="stat-label">Leaves Balance</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--accent-soft);color:var(--accent);"><i class="bi bi-hourglass-split"></i></div>
                <div>
                  <div class="stat-num">{{ $pendingLeaves->count() }}</div>
                  <div class="stat-label">Pending Requests</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--coral-soft);color:#c22f42;"><i class="bi bi-cash-stack"></i></div>
                <div>
                  <div class="stat-num">&#8377;{{ number_format($finalSalary, 0) }}</div>
                  <div class="stat-label">This Month Salary</div>
                </div>
              </div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-lg-5">
              <div class="section-card h-100">
                <h5 class="mb-3">Today's Attendance</h5>
                <div class="mb-2">
                  @if($todayAtt)
                  @php $s = $todayAtt->status; @endphp
                  <span class="pill {{ $s === 'present' ? 'pill-teal' : ($s === 'half-day' ? 'pill-amber' : ($s === 'On Leave' ? 'pill-indigo' : 'pill-coral')) }}">
                    {{ $s === 'On Leave' ? 'On Leave' : ucfirst($s) }}
                  </span>
                  @else
                  <span class="pill pill-coral">Not Clocked In</span>
                  @endif
                </div>
                <div class="row text-center g-3">
                  <div class="col-6">
                    <div class="detail-item">
                      <div class="l">Clock In</div>
                      <div class="v mono">{{ $todayAtt?->check_in ? $todayAtt->check_in->format('h:i:s A') : '--:--:--' }}</div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="detail-item">
                      <div class="l">Clock Out</div>
                      <div class="v mono">{{ $todayAtt?->check_out ? $todayAtt->check_out->format('h:i:s A') : '--:--:--' }}</div>
                    </div>
                  </div>
                </div>
                @if($todayAtt?->location_name)
                <div class="small mt-3" style="color:var(--text-soft);"><i class="bi bi-geo-alt"></i> {{ $todayAtt->location_name }}</div>
                @endif
              </div>
            </div>

            <div class="col-lg-7">
              <div class="section-card h-100">
                <div class="section-head">
                  <h5>Recent Attendance</h5>
                  <a href="#" class="ms-auto small" style="color:var(--accent);" data-view="attendance">View all &rarr;</a>
                </div>
                <div class="table-responsive">
                  <table class="tbl">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($attendances->take(6) as $att)
                      <tr>
                        <td>{{ $att->date->format('d M Y') }}</td>
                        <td class="mono">{{ $att->check_in ? $att->check_in->format('h:i A') : '--' }}</td>
                        <td class="mono">{{ $att->check_out ? $att->check_out->format('h:i A') : '--' }}</td>
                        <td>
                          @php $s = $att->status; @endphp
                          <span class="pill {{ $s === 'present' ? 'pill-teal' : ($s === 'half-day' ? 'pill-amber' : ($s === 'On Leave' ? 'pill-indigo' : 'pill-coral')) }}">{{ $s === 'On Leave' ? 'On Leave' : ucfirst($s) }}</span>
                        </td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="4" style="color:var(--text-soft);">No attendance records yet.</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ================= PROFILE VIEW ================= -->
        <section class="view" id="view-profile">
          <div class="section-card">
            <div class="profile-head">
              <div class="avatar-letter avatar-lg">{{ substr($employee->name, 0, 1) }}</div>
              <div>
                <div class="p-name">{{ $employee->name }}</div>
                <div class="p-role">{{ $employee->designation }}</div>
                <div class="p-meta">
                  <span>Employee ID <strong>{{ $employee->employee_id }}</strong></span>
                  <span>Branch <strong>{{ $employee->branch?->name ?? '—' }}</strong></span>
                  <span>Status <strong>{{ $employee->status }}</strong></span>
                </div>
              </div>
            </div>
          </div>

          <div class="section-card">
            <div class="section-head">
              <h5 class="mb-0">Profile Details</h5>
              <button type="button" class="btn btn-accent btn-sm ms-auto" id="editProfileBtn"><i class="bi bi-pencil-fill"></i> Edit Profile</button>
            </div>
            <div class="detail-grid">
              <div class="detail-item">
                <div class="l">Email</div>
                <div class="v">{{ $employee->email ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="l">Mobile Number</div>
                <div class="v" id="profMobile">{{ $employee->mobile ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="l">Emergency Contact</div>
                <div class="v" id="profEmergency">{{ $employee->emergency_contact ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="l">Gender</div>
                <div class="v">{{ $employee->gender ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="l">Age</div>
                <div class="v">{{ $employee->age ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="l">Date of Birth</div>
                <div class="v">{{ $employee->dob?->format('d M Y') ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="l">Join Date</div>
                <div class="v">{{ $employee->join_date?->format('d M Y') ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="l">State</div>
                <div class="v" id="profState">{{ $employee->state ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="l">City</div>
                <div class="v" id="profCity">{{ $employee->city ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="l">Shift</div>
                <div class="v">{{ $employee->shift_start && $employee->shift_end ? Carbon\Carbon::parse($employee->shift_start)->format('h:i A') . ' – ' . Carbon\Carbon::parse($employee->shift_end)->format('h:i A') : '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="l">Salary</div>
                <div class="v">&#8377; {{ number_format($employee->salary ?? 0, 2) }}</div>
              </div>
              <div class="detail-item">
                <div class="l">Blood Group</div>
                <div class="v" id="profBlood">{{ $employee->blood_group ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="l">Paid Leaves</div>
                <div class="v">{{ $paidLeavesTotal }}</div>
              </div>
            </div>
          </div>
        </section>

        <!-- ================= ATTENDANCE VIEW ================= -->
        <section class="view" id="view-attendance">
          <div class="section-card">
            <div class="section-head">
              <h5>Attendance History</h5>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Hours</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($attendances as $att)
                  <tr>
                    <td>{{ $att->date->format('d M Y') }}</td>
                    <td class="mono">{{ $att->check_in ? $att->check_in->format('h:i:s A') : '—' }}</td>
                    <td class="mono">{{ $att->check_out ? $att->check_out->format('h:i:s A') : '—' }}</td>
                    <td class="mono">
                      {{ $att->check_in && $att->check_out ? Carbon\Carbon::parse($att->check_in)->diffInHours(Carbon\Carbon::parse($att->check_out)) . 'h' : '—' }}
                    </td>
                    <td>
                      @php $s = $att->status; @endphp
                      <span class="pill {{ $s === 'present' ? 'pill-teal' : ($s === 'half-day' ? 'pill-amber' : ($s === 'On Leave' ? 'pill-indigo' : 'pill-coral')) }}">{{ $s === 'On Leave' ? 'On Leave' : ucfirst($s) }}</span>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" style="color:var(--text-soft);">No attendance records found.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ================= DAILY REPORTS VIEW ================= -->
        <section class="view" id="view-reports">
          <div class="section-card">
            <div class="section-head">
              <h5>My Daily Reports</h5>
              <button class="btn btn-accent btn-sm ms-auto" onclick="downloadReportPdf()"><i class="bi bi-file-earmark-pdf-fill"></i> Download PDF</button>
            </div>
            <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
              <div class="btn-group btn-group-sm" id="reportPeriodGroup">
                <button type="button" class="btn btn-outline-secondary active" data-period="all">All</button>
                <button type="button" class="btn btn-outline-secondary" data-period="date">Date</button>
                <button type="button" class="btn btn-outline-secondary" data-period="month">Month</button>
              </div>
              <div id="reportDateWrap" style="display:none;"><input type="date" id="reportDate" class="form-control form-control-sm" style="width:170px;"></div>
              <div id="reportMonthWrap" style="display:none;"><input type="month" id="reportMonth" class="form-control form-control-sm" style="width:170px;"></div>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Hours</th>
                    <th>Status</th>
                    <th>Daily Report</th>
                  </tr>
                </thead>
                <tbody id="reportBody"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ================= WORK UPDATE VIEW ================= -->
        <section class="view" id="view-work-update">
          <div class="section-card">
            <div class="section-head">
              <h5>Daily Work Update</h5>
              <button class="btn btn-accent btn-sm ms-auto" onclick="openWorkUpdateModal()"><i class="bi bi-plus-lg"></i> Add Update</button>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Work Report</th>
                    <th>Updated</th>
                    <th class="text-end">Actions</th>
                  </tr>
                </thead>
                <tbody id="workUpdateBody"></tbody>
              </table>
            </div>
          </div>
        </section>

        <section class="view" id="view-leave">
          <div class="row g-3 mb-2">
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--accent-soft);color:var(--accent);"><i class="bi bi-calendar-check"></i></div>
                <div>
                  <div class="stat-num">{{ $paidLeavesTotal }}</div>
                  <div class="stat-label">Allocated Leaves</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--amber-soft);color:#a06405;"><i class="bi bi-calendar-x"></i></div>
                <div>
                  <div class="stat-num">{{ $usedLeaveDays }}</div>
                  <div class="stat-label">Used Leaves</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--teal-soft);color:#0a8577;"><i class="bi bi-calendar-minus"></i></div>
                <div>
                  <div class="stat-num">{{ $remainingLeaves }}</div>
                  <div class="stat-label">Remaining</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--coral-soft);color:#c22f42;"><i class="bi bi-hourglass-split"></i></div>
                <div>
                  <div class="stat-num">{{ $pendingLeaves->count() }}</div>
                  <div class="stat-label">Pending</div>
                </div>
              </div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-lg-5">
              <div class="section-card">
                <h5 class="mb-3">Apply for Leave / Permission</h5>
                <form id="leaveForm">
                  <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
                  <div class="mb-3">
                    <label class="form-label small fw-semibold">Type</label>
                    <select name="type" class="form-select form-select-sm" required>
                      <option value="Sick Leave">Sick Leave</option>
                      <option value="Casual Leave">Casual Leave</option>
                      <option value="Earned Leave">Earned Leave</option>
                      <option value="Permission">Permission</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                  <div class="row g-2 mb-3">
                    <div class="col-6">
                      <label class="form-label small fw-semibold">From</label>
                      <input type="date" name="from_date" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-6">
                      <label class="form-label small fw-semibold">To</label>
                      <input type="date" name="to_date" class="form-control form-control-sm" required>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label small fw-semibold">Reason</label>
                    <textarea name="reason" class="form-control form-control-sm" rows="3" placeholder="Brief reason..."></textarea>
                  </div>
                  <div id="leaveMsg" class="small mb-2"></div>
                  <button type="submit" class="btn btn-accent btn-sm w-100"><i class="bi bi-send"></i> Submit Request</button>
                </form>
              </div>
            </div>

            <div class="col-lg-7">
              <div class="section-card">
                <div class="section-head">
                  <h5>My Requests</h5>
                </div>
                <div class="table-responsive">
                  <table class="tbl">
                    <thead>
                      <tr>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Reason</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($leaves as $l)
                      <tr>
                        <td>{{ $l->type }}</td>
                        <td class="mono">{{ $l->from_date->format('d M Y') }} &rarr; {{ $l->to_date->format('d M Y') }}</td>
                        <td>{{ $l->reason ?? '—' }}</td>
                        <td>
                          @if($l->status === 'Approved')
                          <span class="pill pill-teal">Approved</span>
                          @elseif($l->status === 'Rejected')
                          <span class="pill pill-coral">Rejected</span>
                          @else
                          <span class="pill pill-amber">Pending</span>
                          @endif
                        </td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="4" style="color:var(--text-soft);">No requests yet.</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ================= NOTIFICATIONS VIEW ================= -->
        <section class="view" id="view-notifications">
          <div class="section-card">
            <div class="section-head">
              <h5>Notifications</h5>
              @if($unreadNotifications > 0)
              <button class="btn btn-ghost btn-sm ms-auto" id="empMarkReadBtn">Mark all as read</button>
              @endif
            </div>
            @forelse($notifications as $n)
            <div class="notif-item @if(!$n->is_read) unread @endif">
              <div class="notif-ic" style="background:var(--accent-soft);color:var(--accent);"><i class="bi {{ $n->type ?? 'bi-bell-fill' }}"></i></div>
              <div>
                <div class="fw-semibold" style="font-size:.88rem;">{{ $n->title }}</div>
                <div class="small" style="color:var(--text-soft);">{{ $n->body }}</div>
                <div class="notif-time">{{ $n->created_at->diffForHumans() }}</div>
              </div>
            </div>
            @empty
            <div style="color:var(--text-soft);padding:14px 6px;">No notifications.</div>
            @endforelse
          </div>
        </section>

        <!-- ================= SALARY VIEW ================= -->
        <section class="view" id="view-salary">
          <div class="section-card">
            <div class="section-head">
              <h5>Salary Calculation - {{ $monthStart->format('F Y') }}</h5>
            </div>
            <div class="salary-grid mb-4">
              <div class="salary-box">
                <div class="l">Base Salary</div>
                <div class="v">&#8377; {{ number_format($baseSalary, 0) }}</div>
              </div>
              <div class="salary-box">
                <div class="l">Per Day</div>
                <div class="v">&#8377; {{ number_format($perDay, 0) }}</div>
              </div>
              <div class="salary-box">
                <div class="l">Eligible Days</div>
                <div class="v">{{ $eligibleDays }}</div>
              </div>
              <div class="salary-box">
                <div class="l">Days Worked</div>
                <div class="v">{{ number_format($workedDays, 1) }}</div>
              </div>
              <div class="salary-box">
                <div class="l">Half Days</div>
                <div class="v">{{ $halfDayCount }}</div>
              </div>
              <div class="salary-box">
                <div class="l">Leave Days</div>
                <div class="v">{{ $approvedLeaveDaysThisMonth }}</div>
              </div>
              <div class="salary-box">
                <div class="l">Deductible Days</div>
                <div class="v">{{ number_format($deductibleDays, 1) }}</div>
              </div>
              <div class="salary-box" style="border-color:var(--teal);">
                <div class="l">Final Salary</div>
                <div class="v" style="color:#0a8577;">&#8377; {{ number_format($finalSalary, 2) }}</div>
              </div>
            </div>
            <p class="small" style="color:var(--text-soft);">
              Calculation: Base (&#8377;{{ number_format($baseSalary, 0) }}) &divide; 30 &times; Days Worked ({{ number_format($workedDays, 1) }}) = &#8377;{{ number_format($finalSalary, 2) }}
            </p>
          </div>

          <div class="section-card">
            <div class="section-head">
              <h5>Salary History</h5>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Month</th>
                    <th>Base</th>
                    <th>Absent</th>
                    <th>Leave</th>
                    <th>Deductible</th>
                    <th>Final</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($salaryRecords as $s)
                  <tr>
                    <td>{{ \Carbon\Carbon::createFromFormat('F-Y', $s->month)->format('F Y') }}</td>
                    <td>&#8377; {{ number_format($s->base_salary, 2) }}</td>
                    <td>{{ $s->absent_days }}</td>
                    <td>{{ $s->leave_days }}</td>
                    <td>{{ $s->deductible_days }}</td>
                    <td class="fw-semibold">&#8377; {{ number_format($s->final_salary, 2) }}</td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="6" style="color:var(--text-soft);">No processed salary records yet.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </section>

      </main>
    </div>
  </div>

  <!-- ============ Edit Profile modal ============ -->
  <div class="modal-overlay" id="profileModal">
    <div class="modal-panel">
      <div class="modal-head">
        <h6 class="mb-0">Edit Profile</h6>
        <button type="button" class="modal-close" id="profileModalClose"><i class="bi bi-x-lg"></i></button>
      </div>
      <form id="profileEditForm">
        <input type="hidden" id="profEmployeeId" value="{{ $employee->employee_id }}">
        <div class="mb-3">
          <label class="form-label small fw-semibold">Mobile Number</label>
          <input type="text" id="profMobileInput" class="form-control form-control-sm" maxlength="20" placeholder="Enter mobile number">
        </div>
        <div class="mb-3">
          <label class="form-label small fw-semibold">Emergency Contact</label>
          <input type="text" id="profEmergencyInput" class="form-control form-control-sm" maxlength="20" placeholder="Enter emergency contact number">
        </div>
        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="form-label small fw-semibold">State</label>
            <input type="text" id="profStateInput" class="form-control form-control-sm" maxlength="100" placeholder="Enter state">
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold">City</label>
            <input type="text" id="profCityInput" class="form-control form-control-sm" maxlength="100" placeholder="Enter city">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label small fw-semibold">Blood Group</label>
          <select id="profBloodInput" class="form-select form-select-sm">
            <option value="">Select blood group</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
          </select>
        </div>
        <div id="profileMsg" class="small mb-0"></div>
        <div class="d-flex gap-2 justify-content-end mt-3">
          <button type="button" class="btn btn-ghost btn-sm" id="profileModalCancel">Cancel</button>
          <button type="submit" class="btn btn-accent btn-sm"><i class="bi bi-check-lg"></i> Save Profile</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Work Update Modal -->
  <div class="modal-overlay" id="workUpdateModal">
    <div class="modal-panel">
      <div class="modal-head">
        <h6 class="mb-0" id="workUpdateModalTitle">Add Work Update</h6>
        <button type="button" class="modal-close" id="workUpdateModalClose">&times;</button>
      </div>
      <form id="workUpdateForm">
        <input type="hidden" id="wuFormId">
        <div class="mb-3">
          <label class="form-label small fw-semibold">Date <span class="text-danger">*</span></label>
          <input required type="date" class="form-control form-control-sm" id="wuDate">
        </div>
        <div class="mb-3">
          <label class="form-label small fw-semibold">Work Report <span class="text-danger">*</span></label>
          <textarea class="form-control form-control-sm" id="wuReport" rows="5" placeholder="Describe what you worked on today..." required></textarea>
        </div>
        <div id="wuMsg" class="small mb-0"></div>
        <div class="d-flex gap-2 justify-content-end mt-3">
          <button type="button" class="btn btn-ghost btn-sm" id="workUpdateCancel">Cancel</button>
          <button type="submit" class="btn btn-accent btn-sm" id="wuSubmitBtn">Save Update</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var viewMeta = {
      dashboard: ['Dashboard', 'Overview of your attendance and leaves'],
      profile: ['Profile', 'Your personal and employment details'],
      attendance: ['Attendance', 'Your attendance history'],
      reports: ['Daily Reports', 'Your daily work reports'],
      leave: ['Leave / Permission', 'Apply for leaves and track requests'],
      notifications: ['Notifications', 'Updates and announcements'],
      salary: ['Salary', 'Monthly salary calculation'],
      'work-update': ['Work Update', 'Submit and manage daily work updates']
    };

    function switchView(name) {
      $('.view').removeClass('active');
      $('#view-' + name).addClass('active');
      $('.side-link').removeClass('active');
      $('.side-link[data-view="' + name + '"]').addClass('active');
      var meta = viewMeta[name] || ['Dashboard', ''];
      $('#pageTitle').text(meta[0]);
      $('#pageSub').text(meta[1]);
      $('#sidebar').removeClass('open');
      $('#sidebarOverlay').removeClass('show');
      if (name === 'reports') loadEmpReports();
      if (name === 'work-update') loadWorkUpdates();

      /* sync bottom nav */
      $('.bn-item[data-view]').removeClass('active');
      $('.bn-item[data-view="' + name + '"]').addClass('active');

      /* scroll to top on view change */
      $('#mainArea').scrollTop(0);
      window.scrollTo(0, 0);
    }

    $(document).on('click', '.side-link[data-view]', function() {
      switchView($(this).data('view'));
    });

    /* bottom nav items */
    $(document).on('click', '.bn-item[data-view]', function() {
      switchView($(this).data('view'));
    });

    /* bottom nav "More" opens sidebar */
    $('#bnMoreBtn').on('click', function() {
      $('#sidebar').addClass('open');
      $('#sidebarOverlay').addClass('show');
    });

    /* topbar notification icon */
    $('#topNotifBtn').on('click', function() {
      switchView('notifications');
    });

    /* profile link in user-menu dropdown */
    $(document).on('click', '.user-menu-item[data-view]', function(e) {
      e.preventDefault();
      var v = $(this).data('view');
      $('#userMenuDropdown').removeClass('open');
      switchView(v);
    });


    /* ---------------- Daily Reports ---------------- */
    function empReportPill(status) {
      var cls = 'pill-coral';
      if (status === 'Present') cls = 'pill-teal';
      else if (status === 'Half-day') cls = 'pill-amber';
      else if (status === 'On Leave' || status === 'Holiday') cls = 'pill-indigo';
      return '<span class="pill ' + cls + '">' + status + '</span>';
    }

    function loadEmpReports() {
      var $body = $('#reportBody');
      $body.html('<tr><td colspan="6" style="color:var(--text-soft);padding:24px;text-align:center;">Loading reports...</td></tr>');
      var period = $('#reportPeriodGroup .btn.active').data('period');
      var url = '{{ route("employee.reports.daily") }}';
      var params = [];
      if (period === 'date' && $('#reportDate').val()) params.push('date=' + encodeURIComponent($('#reportDate').val()));
      if (period === 'month' && $('#reportMonth').val()) params.push('month=' + encodeURIComponent($('#reportMonth').val()));
      if (params.length) url += '?' + params.join('&');

      $.get(url).done(function(rows) {
        if (!rows || !rows.length) {
          $body.html('<tr><td colspan="6" style="color:var(--text-soft);padding:24px;text-align:center;">No reports found for the selected period.</td></tr>');
          return;
        }
        $body.html(rows.map(function(r) {
          return '<tr>' +
            '<td class="mono">' + r.date + '</td>' +
            '<td class="mono">' + r.check_in + '</td>' +
            '<td class="mono">' + r.check_out + '</td>' +
            '<td class="mono">' + r.hours + '</td>' +
            '<td>' + empReportPill(r.status) + '</td>' +
            '<td style="white-space:normal;">' + (r.report ? r.report : '—') + '</td>' +
            '</tr>';
        }).join(''));
      }).fail(function() {
        $body.html('<tr><td colspan="6" style="color:var(--text-soft);padding:24px;text-align:center;">Failed to load reports.</td></tr>');
      });
    }

    $('#reportPeriodGroup .btn').on('click', function() {
      $('#reportPeriodGroup .btn').removeClass('active');
      $(this).addClass('active');
      var period = $(this).data('period');
      $('#reportDateWrap').toggle(period === 'date');
      $('#reportMonthWrap').toggle(period === 'month');
      loadEmpReports();
    });
    $('#reportDate, #reportMonth').on('change', loadEmpReports);

    function downloadReportPdf() {
      var $body = $('#reportBody');
      var bodyHtml = $body.html();
      if (!bodyHtml || bodyHtml.indexOf('No reports') > -1) {
        alert('No report data to download.');
        return;
      }
      var period = 'All records';
      if ($('#reportPeriodGroup .btn.active').data('period') === 'date') period = 'Date: ' + $('#reportDate').val();
      else if ($('#reportPeriodGroup .btn.active').data('period') === 'month') period = 'Month: ' + $('#reportMonth').val();

      var title = 'Daily Report - {{ $employee->name }} ({{ $employee->employee_id }})';
      var cleanRows = bodyHtml.replace(/<span class="pill[^"]*">(.*?)<\/span>/g, '$1');

      var $el = $('<div style="padding:24px;font-family:Helvetica,Arial,sans-serif;color:#111;">' +
        '<h2 style="margin:0 0 2px;color:#0a8577;">Guru Group Attendance</h2>' +
        '<p style="margin:0 0 14px;color:#555;font-size:12px;">' + title + '<br>' + period + '</p>' +
        '<table style="width:100%;border-collapse:collapse;font-size:11px;">' +
        '<thead><tr style="background:#0fb5a3;color:#fff;">' +
        '<th style="padding:6px;border:1px solid #cfd2dd;text-align:left;">Date</th>' +
        '<th style="padding:6px;border:1px solid #cfd2dd;text-align:left;">Check In</th>' +
        '<th style="padding:6px;border:1px solid #cfd2dd;text-align:left;">Check Out</th>' +
        '<th style="padding:6px;border:1px solid #cfd2dd;text-align:left;">Hours</th>' +
        '<th style="padding:6px;border:1px solid #cfd2dd;text-align:left;">Status</th>' +
        '<th style="padding:6px;border:1px solid #cfd2dd;text-align:left;">Daily Report</th>' +
        '</tr></thead><tbody>' + cleanRows + '</tbody></table></div>').appendTo('body');

      html2pdf().set({
        margin: [10, 10, 10, 10],
        filename: 'daily-report-{{ $employee->employee_id }}.pdf',
        image: { type: 'jpeg', quality: 0.95 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
      }).from($el[0]).save().then(function() {
        $el.remove();
      });
    }

    $('#burgerBtn').on('click', function() {
      $('#sidebar').addClass('open');
      $('#sidebarOverlay').addClass('show');
    });
    $('#sidebarCloseBtn, #sidebarOverlay').on('click', function() {
      $('#sidebar').removeClass('open');
      $('#sidebarOverlay').removeClass('show');
    });

    function escapeHtml(str) {
      return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    $('#leaveForm').on('submit', function(e) {
      e.preventDefault();
      var $msg = $('#leaveMsg');
      $msg.removeClass('text-danger text-success').text('');
      var $form = $(this);
      $.post('{{ route("employee.leave") }}', $form.serialize())
        .done(function(resp) {
          $msg.addClass('text-success').text(resp.message || 'Leave request submitted successfully.');
          
          var type = $form.find('select[name=type]').val();
          var fromDate = $form.find('input[name=from_date]').val();
          var toDate = $form.find('input[name=to_date]').val();
          var reason = $form.find('textarea[name=reason]').val();

          var newRow = '<tr>' +
            '<td>' + escapeHtml(type) + '</td>' +
            '<td class="mono">' + escapeHtml(fromDate) + ' &rarr; ' + escapeHtml(toDate) + '</td>' +
            '<td>' + (reason ? escapeHtml(reason) : '—') + '</td>' +
            '<td><span class="pill pill-amber">Pending</span></td>' +
            '</tr>';

          var $tbody = $('#view-leave table.tbl tbody');
          if ($tbody.find('td[colspan]').length) {
            $tbody.empty();
          }
          $tbody.prepend(newRow);

          $form[0].reset();
          setTimeout(function() { $msg.text(''); }, 3500);
        })
        .fail(function(xhr) {
          var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to submit request.';
          $msg.addClass('text-danger').text(msg);
        });
    });

    $('#empMarkReadBtn').on('click', function() {
      var $btn = $(this);
      $btn.prop('disabled', true);
      $.post('{{ route("employee.notifications.read") }}', {
          employee_id: '{{ $employee->employee_id }}'
        })
        .done(function() {
          $('.notif-item').removeClass('unread');
          $('#notifBadge').hide();
          $('#topEmpNotifDot').remove();
          $btn.remove();
        })
        .fail(function() {
          $btn.prop('disabled', false);
          alert('Failed to mark notifications as read.');
        });
    });

    /* ---------------- User menu dropdown ---------------- */
    $('#userMenuBtn').on('click', function(e) {
      e.stopPropagation();
      $('#userMenuDropdown').toggleClass('open');
    });
    $(document).on('click', function(e) {
      if (!$(e.target).closest('.user-menu').length) {
        $('#userMenuDropdown').removeClass('open');
      }
    });
    $(document).on('keydown', function(e) {
      if (e.key === 'Escape') $('#userMenuDropdown').removeClass('open');
    });

    /* ---------------- Logout ---------------- */
    $(document).on('click', '.logout-link', function(e) {
      e.preventDefault();
      $.post('{{ route("employee.logout") }}', { _token: '{{ csrf_token() }}' })
        .done(function() { window.location.href = '/'; })
        .fail(function() { window.location.href = '/'; });
    });

    /* ---------------- Edit profile ---------------- */
    function openProfileEdit() {
      $('#profMobileInput').val('{{ $employee->mobile }}');
      $('#profEmergencyInput').val('{{ $employee->emergency_contact }}');
      $('#profStateInput').val('{{ $employee->state }}');
      $('#profCityInput').val('{{ $employee->city }}');
      $('#profBloodInput').val('{{ $employee->blood_group }}');
      $('#profileMsg').removeClass('text-danger text-success').text('');
      $('#profileModal').addClass('show');
    }
    function closeProfileEdit() {
      $('#profileModal').removeClass('show');
    }
    $('#editProfileBtn').on('click', openProfileEdit);
    $('#profileModalClose, #profileModalCancel').on('click', closeProfileEdit);
    $('#profileModal').on('click', function(e) {
      if (e.target === this) closeProfileEdit();
    });
    $(document).on('keydown', function(e) {
      if (e.key === 'Escape') closeProfileEdit();
    });

    $('#profileEditForm').on('submit', function(e) {
      e.preventDefault();
      var $msg = $('#profileMsg');
      $msg.removeClass('text-danger text-success').text('');
      var $btn = $(this).find('button[type=submit]');
      $btn.prop('disabled', true);

      $.post('{{ route("employee.profile.update") }}', {
        employee_id: $('#profEmployeeId').val(),
        mobile: $('#profMobileInput').val().trim(),
        emergency_contact: $('#profEmergencyInput').val().trim(),
        state: $('#profStateInput').val().trim(),
        city: $('#profCityInput').val().trim(),
        blood_group: $('#profBloodInput').val()
      }).done(function(resp) {
        $('#profMobile').text($('#profMobileInput').val().trim() || '—');
        $('#profEmergency').text($('#profEmergencyInput').val().trim() || '—');
        $('#profState').text($('#profStateInput').val().trim() || '—');
        $('#profCity').text($('#profCityInput').val().trim() || '—');
        $('#profBlood').text($('#profBloodInput').val() || '—');
        $msg.addClass('text-success').text(resp.message || 'Profile updated.');
        setTimeout(closeProfileEdit, 800);
      }).fail(function(xhr) {
        var m = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to update profile.';
        $msg.addClass('text-danger').text(m);
      }).always(function() {
        $btn.prop('disabled', false);
      });
    });

    /* ---------------- Work Update ---------------- */
    var workUpdates = [];

    function loadWorkUpdates() {
      $.get('{{ route("employee.daily-work-updates") }}', function(data) {
        workUpdates = data;
        renderWorkUpdates();
      });
    }

    function renderWorkUpdates() {
      var tbody = $('#workUpdateBody');
      tbody.empty();
      if (!workUpdates.length) {
        tbody.append('<tr><td colspan="4" class="text-center text-muted py-3">No work updates yet</td></tr>');
        return;
      }
      workUpdates.forEach(function(u) {
        var reportShort = u.report.length > 80 ? u.report.substring(0, 80) + '...' : u.report;
        tbody.append(
          '<tr>' +
          '<td>' + u.date + '</td>' +
          '<td title="' + u.report.replace(/"/g, '&quot;') + '">' + reportShort + '</td>' +
          '<td>' + u.updated_at + '</td>' +
          '<td class="text-end">' +
          '<button class="btn btn-sm btn-outline-accent me-1" onclick="editWorkUpdate(' + u.id + ')"><i class="bi bi-pencil"></i></button>' +
          '<button class="btn btn-sm btn-outline-danger" onclick="deleteWorkUpdate(' + u.id + ')"><i class="bi bi-trash"></i></button>' +
          '</td></tr>'
        );
      });
    }

    function openWorkUpdateModal(id, date, report) {
      $('#wuFormId').val(id || '');
      $('#wuDate').val(date || new Date().toISOString().slice(0, 10));
      $('#wuReport').val(report || '');
      $('#wuMsg').hide().removeClass('text-success text-danger');
      $('#workUpdateModalTitle').text(id ? 'Edit Work Update' : 'Add Work Update');
      $('#workUpdateModal').addClass('show');
    }

    function editWorkUpdate(id) {
      var u = workUpdates.find(function(x) { return x.id === id; });
      if (u) openWorkUpdateModal(u.id, u.date, u.report);
    }

    function closeWorkUpdateModal() {
      $('#workUpdateModal').removeClass('show');
    }

    $('#workUpdateModalClose, #workUpdateCancel').on('click', closeWorkUpdateModal);
    $('#workUpdateModal').on('click', function(e) { if (e.target === this) closeWorkUpdateModal(); });

    $('#workUpdateForm').on('submit', function(e) {
      e.preventDefault();
      var $btn = $('#wuSubmitBtn');
      $btn.prop('disabled', true);
      $('#wuMsg').hide();

      var payload = {
        date: $('#wuDate').val(),
        report: $('#wuReport').val().trim()
      };

      $.ajax({
        url: '{{ route("employee.daily-work-updates.store") }}',
        method: 'POST',
        data: payload
      }).done(function(resp) {
        if (resp.success) {
          closeWorkUpdateModal();
          loadWorkUpdates();
        }
      }).fail(function(xhr) {
        var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to save.';
        $('#wuMsg').removeClass('text-success').addClass('text-danger').text(msg).show();
      }).always(function() {
        $btn.prop('disabled', false);
      });
    });

    function deleteWorkUpdate(id) {
      if (!confirm('Delete this work update?')) return;
      $.ajax({
        url: '{{ route("employee.daily-work-updates.delete") }}',
        method: 'POST',
        data: { id: id }
      }).done(function() {
        loadWorkUpdates();
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