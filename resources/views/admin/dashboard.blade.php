<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#0a8577">
  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
  <link rel="apple-touch-icon" href="{{ asset('pwa/icons/icon-192.png') }}">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <title>Guru Group &mdash; Admin Dashboard</title>
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

    .avatar-sm {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
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

    @media(max-width:991px) {
      .sidebar {
        position: fixed;
        left: -264px;
        z-index: 60;
        transition: left .2s ease;
      }

      .sidebar.open {
        left: 0;
      }

      .topbar {
        flex-wrap: wrap;
        row-gap: 10px;
      }

      .search-box {
        order: 5;
        flex-basis: 100%;
        max-width: none;
        margin-left: 0;
      }
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

    @media(max-width:991px) {
      .sidebar-close {
        display: inline-flex;
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
        @if ($isBranchAdmin)
        <div class="side-link" data-view="employees">
          <i class="bi bi-people-fill"></i> Employees
        </div>
        @endif
        <div class="side-link" data-view="employee-list">
          <i class="bi bi-person-lines-fill"></i> Employee List
        </div>
        <div class="side-link" data-view="attendance">
          <i class="bi bi-calendar2-check-fill"></i> Attendance
        </div>
        @if ($isBranchAdmin)
        <div class="side-link" data-view="leave">
          <i class="bi bi-file-earmark-text-fill"></i> Leave / Permission
          <span class="badge-pill" id="leaveBadge">0</span>
        </div>
        @endif
        <div class="side-link" data-view="designation">
          <i class="bi bi-diagram-3-fill"></i> Designation List
        </div>
        <div class="side-link" data-view="notifications">
          <i class="bi bi-bell-fill"></i> Notifications
          <span class="badge-pill" id="notifBadge">0</span>
        </div>
        @if ($isBranchAdmin)
        <div class="side-link" data-view="salary">
          <i class="bi bi-cash-stack"></i> Salary
        </div>
        <div class="side-link" data-view="holidays">
          <i class="bi bi-calendar-heart-fill"></i> Holidays
        </div>
        @endif
        <div class="side-link" data-view="reports">
          <i class="bi bi-file-earmark-bar-graph-fill"></i> Daily Reports
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
        <button class="btn btn-ghost d-lg-none" id="burgerBtn"><i class="bi bi-list"></i></button>
        <div>
          <p class="page-title" id="pageTitle">Dashboard</p>
          <p class="page-sub" id="pageSub">Overview of your organization today</p>
        </div>
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" id="globalSearch" placeholder="Search this page...">
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
          <div class="icon-btn" data-view="notifications"><i class="bi bi-bell"></i><span class="dot" id="topNotifDot" style="display:none;"></span></div>
          <img src="{{ asset('logo/guru.png') }}" style="height:32px;width:auto;object-fit:contain;" alt="Guru Group">
        </div>
      </div>

      <main class="main" id="mainArea">

        <!-- ================= DASHBOARD VIEW ================= -->
        <section class="view active" id="view-dashboard">
          <div class="row g-3 mb-2">
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--accent-soft);color:var(--accent);"><i class="bi bi-people-fill"></i></div>
                <div>
                  <div class="stat-num" id="statTotal">--</div>
                  <div class="stat-label">Total Employees</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--teal-soft);color:#0a8577;"><i class="bi bi-check-circle-fill"></i></div>
                <div>
                  <div class="stat-num" id="statPresent">--</div>
                  <div class="stat-label">Present Today</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--amber-soft);color:#a06405;"><i class="bi bi-airplane-fill"></i></div>
                <div>
                  <div class="stat-num" id="statLeave">--</div>
                  <div class="stat-label">On Leave Today</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--coral-soft);color:#c22f42;"><i class="bi bi-hourglass-split"></i></div>
                <div>
                  <div class="stat-num" id="statPending">--</div>
                  <div class="stat-label">Pending Requests</div>
                </div>
              </div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-lg-4">
              <div class="section-card h-100">
                <h5 class="mb-3">Today's Attendance</h5>
                <div class="d-flex align-items-center gap-4">
                  <div class="ring-wrap">
                    <svg width="96" height="96">
                      <circle cx="48" cy="48" r="40" stroke="#eef0f7" stroke-width="10" fill="none" />
                      <circle id="attRingCircle" cx="48" cy="48" r="40" stroke="#0fb5a3" stroke-width="10" fill="none"
                        stroke-linecap="round" stroke-dasharray="251" stroke-dashoffset="251" />
                    </svg>
                    <div class="ring-center">
                      <div class="fw-bold" id="attRingPct" style="font-family:'Sora',sans-serif;">0%</div>
                      <div style="font-size:.65rem;color:var(--text-soft);">present</div>
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <div class="d-flex justify-content-between small mb-1"><span><i class="bi bi-square-fill" style="color:#0fb5a3;font-size:.6rem;"></i> Present</span><span class="mono" id="legendPresent">0</span></div>
                    <div class="d-flex justify-content-between small mb-1"><span><i class="bi bi-square-fill" style="color:#f5a524;font-size:.6rem;"></i> On Leave</span><span class="mono" id="legendLeave">0</span></div>
                    <div class="d-flex justify-content-between small"><span><i class="bi bi-square-fill" style="color:#ef5d6f;font-size:.6rem;"></i> Absent</span><span class="mono" id="legendAbsent">0</span></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-8">
              <div class="section-card h-100">
                <div class="section-head">
                  <h5>Recent Leave Requests</h5>
                  <a href="#" class="ms-auto small" data-view="leave">View all &rarr;</a>
                </div>
                <div class="table-responsive">
                  <table class="tbl">
                    <thead>
                      <tr>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody id="dashRecentLeave"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ================= EMPLOYEES VIEW ================= -->
        @if ($isBranchAdmin)
        <section class="view" id="view-employees">
          <div class="section-card">
            <div class="section-head">
              <h5>All Employees</h5>
              <div class="ms-auto d-flex gap-2">
                <input type="text" id="empSearch" class="form-control form-control-sm" placeholder="Search name or ID" style="width:200px;">
                <button class="btn btn-accent btn-sm" onclick="openEmployeeModal()"><i class="bi bi-plus-lg"></i> Add Employee</button>
              </div>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Designation</th>
                    <th>Branch</th>
                    <th>Shift</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th>Blood Group</th>
                    <th>Salary</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                  </tr>
                </thead>
                <tbody id="employeesBody"></tbody>
              </table>
            </div>
          </div>
        </section>
        @endif

        <!-- ================= EMPLOYEE LIST VIEW ================= -->
        <section class="view" id="view-employee-list">
          <div class="section-card">
            <div class="section-head flex-wrap gap-2">
              <h5>Employee List</h5>
              <div class="d-flex gap-2 ms-auto">
                <select class="form-select form-select-sm" id="empListBranchFilter" style="width:180px;">
                  <option value="">All Branches</option>
                </select>
                <select class="form-select form-select-sm" id="empListDesignationFilter" style="width:180px;">
                  <option value="">All Designations</option>
                </select>
              </div>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Designation</th>
                    <th>Branch</th>
                    <th>Shift</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th>Blood Group</th>
                    <th>Salary</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="employeeListBody"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ================= ATTENDANCE VIEW ================= -->
        <section class="view" id="view-attendance">
          <div class="section-card">
            <div class="section-head flex-wrap gap-2">
              <h5>Attendance Log</h5>
              <div class="btn-group btn-group-sm period-group ms-auto" role="group" id="attPeriodGroup">
                <button type="button" class="btn active" data-period="daily">Daily</button>
                <button type="button" class="btn" data-period="weekly">Weekly</button>
                <button type="button" class="btn" data-period="monthly">Monthly</button>
                <button type="button" class="btn" data-period="yearly">Yearly</button>
              </div>
              <input type="date" id="attDate" class="form-control form-control-sm" style="width:160px;">
              <button class="btn btn-sm btn-outline-success" onclick="exportAttendancePDF()" title="Download PDF"><i class="bi bi-filetype-pdf"></i> PDF</button>
              <button class="btn btn-sm btn-outline-success" onclick="exportAttendanceExcel()" title="Download Excel"><i class="bi bi-filetype-xlsx"></i> Excel</button>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead id="attendanceHead">
                  <tr>
                    <th>Date</th>
                    <th>Employee</th>
                    <th>Designation</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Hours</th>
                    <th>Status</th>
                    <th>Location</th>
                  </tr>
                </thead>
                <tbody id="attendanceBody"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ================= LEAVE / PERMISSION VIEW ================= -->
        @if ($isBranchAdmin)
        <section class="view" id="view-leave">
          <div class="section-card">
            <div class="section-head">
              <h5>Leave &amp; Permission Requests</h5>
              <select class="form-select form-select-sm ms-auto" id="leaveFilter" style="width:160px;">
                <option value="all">All Status</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
              </select>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                  </tr>
                </thead>
                <tbody id="leaveBody"></tbody>
              </table>
            </div>
          </div>
        </section>
        @endif

        <!-- ================= DESIGNATION LIST VIEW ================= -->
        <section class="view" id="view-designation">
          <div class="section-card">
            <div class="section-head">
              <h5>Designation List</h5>
              <button class="btn btn-accent btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#designationModal"><i class="bi bi-plus-lg"></i> Add Designation</button>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Employees</th>
                    <th class="text-end">Actions</th>
                  </tr>
                </thead>
                <tbody id="designationBody"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ================= NOTIFICATIONS VIEW ================= -->
        <section class="view" id="view-notifications">
          <div class="section-card">
            <div class="section-head">
              <h5>Send Notification</h5>
            </div>
            <form id="sendNotifForm">
              <div class="row g-2 mb-2">
                <div class="col-md-5">
                  <label class="form-label small fw-semibold">Send To</label>
                  <select id="notifTarget" class="form-select form-select-sm">
                    <option value="all">All Employees</option>
                  </select>
                </div>
                <div class="col-md-7">
                  <label class="form-label small fw-semibold">Title</label>
                  <input type="text" id="notifTitle" class="form-control form-control-sm" placeholder="e.g. Holiday announcement" required>
                </div>
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold">Message</label>
                <textarea id="notifBody" class="form-control form-control-sm" rows="3" placeholder="Notification message..." required></textarea>
              </div>
              <div id="notifSendMsg" class="small mb-2"></div>
              <button type="submit" class="btn btn-accent btn-sm"><i class="bi bi-send"></i> Send Notification</button>
            </form>
          </div>

          <div class="section-card">
            <div class="section-head">
              <h5>Notification Center</h5>
              <button type="button" class="btn btn-ghost btn-sm ms-auto" id="markAllReadBtn">Mark all as read</button>
            </div>
            <div id="notifList"></div>
          </div>
        </section>

        <!-- ================= SALARY VIEW ================= -->
        @if ($isBranchAdmin)
        <section class="view" id="view-salary">
          <div class="section-card">
            <div class="section-head">
              <h5>Salary Calculations</h5>
              <button class="btn btn-accent btn-sm ms-auto" onclick="openSalaryModal()"><i class="bi bi-plus-lg"></i> Calculate Salary</button>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Employee</th>
                    <th>Month</th>
                    <th>Base Salary</th>
                    <th>Absent Days</th>
                    <th>Leave Days</th>
                    <th>Paid Leaves</th>
                    <th>Deductible</th>
                    <th>Final Salary</th>
                    <th>Processed On</th>
                  </tr>
                </thead>
                <tbody id="salaryBody"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ================= HOLIDAYS VIEW ================= -->
        <section class="view" id="view-holidays">
          <div class="section-card">
            <div class="section-head">
              <h5>Company Holidays</h5>
              <button class="btn btn-accent btn-sm ms-auto" onclick="openHolidayModal()"><i class="bi bi-plus-lg"></i> Add Holiday</button>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Title</th>
                    <th class="text-end">Actions</th>
                  </tr>
                </thead>
                <tbody id="holidaysBody"></tbody>
              </table>
            </div>
          </div>
        </section>
        @endif

        <!-- ================= DAILY REPORTS VIEW ================= -->
        <section class="view" id="view-reports">
          <div class="section-card">
            <div class="section-head">
              <h5>Daily Reports</h5>
              <select class="form-select form-select-sm ms-auto" id="reportBranchFilter" style="width: 220px;">
                <option value="">All Branches</option>
              </select>
            </div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Designation</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                  </tr>
                </thead>
                <tbody id="reportEmployeesBody"></tbody>
              </table>
            </div>
          </div>
        </section>

      </main>
    </div>
  </div>

  <!-- ============ Daily Report Modal ============ -->
  <div class="modal fade" id="dailyReportModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="dailyReportTitle">Daily Report</h5>
          <div class="d-flex align-items-center gap-2">
            <button class="btn btn-accent btn-sm" onclick="downloadAdminReportPdf()"><i class="bi bi-file-earmark-pdf-fill"></i> Download PDF</button>
            <button class="btn-close" data-bs-dismiss="modal"></button>
          </div>
        </div>
        <div class="modal-body">
          <div class="row g-2 mb-3 align-items-center">
            <div class="col-md-auto">
              <div class="btn-group btn-group-sm" role="group" id="reportPeriodGroup">
                <button type="button" class="btn btn-outline-secondary active" data-period="all">All</button>
                <button type="button" class="btn btn-outline-secondary" data-period="date">Date</button>
                <button type="button" class="btn btn-outline-secondary" data-period="month">Month</button>
              </div>
            </div>
            <div class="col-md-auto" id="reportDateWrap" style="display: none;">
              <input type="date" class="form-control form-control-sm" id="reportDate" style="width: 170px;">
            </div>
            <div class="col-md-auto" id="reportMonthWrap" style="display: none;">
              <input type="month" class="form-control form-control-sm" id="reportMonth" style="width: 170px;">
            </div>
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
              <tbody id="dailyReportBody"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ============ Salary Modal ============ -->
  @if ($isBranchAdmin)
  <div class="modal fade" id="salaryModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Calculate Salary</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="salaryForm">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label small">Employee</label>
              <select required class="form-select" name="employee_id" id="salaryEmployeeSelect"></select>
            </div>
            <div class="mb-3">
              <label class="form-label small">Month</label>
              <input required type="month" class="form-control" name="month" id="salaryMonth">
            </div>
            <div class="mb-3">
              <label class="form-label small">Base Salary (per month)</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input required type="number" step="0.01" min="0" class="form-control" name="base_salary" id="salaryBase" readonly>
              </div>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-4">
                <label class="form-label small">Present Days</label>
                <input type="number" min="0" class="form-control" id="salaryPresent" readonly>
              </div>
              <div class="col-4">
                <label class="form-label small">Half Days</label>
                <input type="number" min="0" class="form-control" id="salaryHalfDays" readonly>
              </div>
              <div class="col-4">
                <label class="form-label small">Absent Days</label>
                <input type="number" min="0" class="form-control" id="salaryAbsent" readonly>
              </div>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-4">
                <label class="form-label small">Approved Leave</label>
                <input type="number" min="0" class="form-control" id="salaryLeaveDays" readonly>
              </div>
              <div class="col-4">
                <label class="form-label small">Eligible Days</label>
                <input type="number" min="0" class="form-control" id="salaryEligible" readonly>
              </div>
              <div class="col-4">
                <label class="form-label small">Deductible Days</label>
                <input type="number" min="0" class="form-control" id="salaryDeductible" readonly>
              </div>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label small text-muted">Worked Days <span class="text-muted">(present + half&times;0.5 + leave)</span></label>
                <input type="number" min="0" class="form-control" id="salaryWorked" readonly>
              </div>
              <div class="col-6">
                <label class="form-label small fw-bold">Final Salary</label>
                <div class="input-group">
                  <span class="input-group-text">₹</span>
                  <input type="text" class="form-control fw-bold fs-5" id="salaryFinalDisplay" readonly style="color:var(--accent);">
                </div>
              </div>
            </div>
            <div class="alert alert-info small py-2 mb-0">
              <i class="bi bi-info-circle"></i> Final = (Base &divide; 30) &times; worked days. Worked days are capped at eligible days (join-date based). Paid leaves count as worked.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-accent">Save Calculation</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif

  <!-- ============ Holiday Modal ============ -->
  @if ($isBranchAdmin)
  <div class="modal fade" id="holidayModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Holiday</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="holidayForm">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label small">Date</label>
              <input required type="date" class="form-control" name="date">
            </div>
            <div class="mb-3">
              <label class="form-label small">Holiday Title</label>
              <input required type="text" class="form-control" name="title" placeholder="e.g. Diwali, Republic Day">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-accent">Add Holiday</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif

  <!-- ============ Employee Modal ============ -->
  @if ($isBranchAdmin)
  <div class="modal fade" id="employeeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="employeeModalTitle">Add Employee</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="employeeForm">
          <div class="modal-body">
            <input type="hidden" name="id" id="empFormId">

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label small">Employee ID</label>
                <input required class="form-control form-control-sm" name="employee_id" id="empIdInput" placeholder="e.g. EMP-001">
              </div>
              <div class="col-md-6">
                <label class="form-label small">Blood Group</label>
                <select class="form-select form-select-sm" name="blood_group">
                  <option value="">Select</option>
                  <option>A+</option>
                  <option>A-</option>
                  <option>B+</option>
                  <option>B-</option>
                  <option>AB+</option>
                  <option>AB-</option>
                  <option>O+</option>
                  <option>O-</option>
                </select>
              </div>
            </div>

            <div class="d-flex align-items-center gap-3 mb-3">
              <img id="photoPreview" src="https://i.pravatar.cc/100?img=68" class="rounded-circle" style="width:64px;height:64px;object-fit:cover;border:1px solid var(--line);">
              <div>
                <label class="form-label small mb-1 d-block">Profile Photo</label>
                <input type="file" class="form-control form-control-sm" id="photoInput" name="photo" accept="image/*" style="max-width:240px;">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3"><label class="form-label small">Full Name</label><input required class="form-control" name="name"></div>
              <div class="col-md-6 mb-3"><label class="form-label small">Email</label><input required type="email" class="form-control" name="email"></div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3"><label class="form-label small">Designation</label><select required class="form-select" name="designation" id="empDesignationSelect"></select></div>
              <div class="col-md-6 mb-3">
                <label class="form-label small">Branch</label>
                <select required class="form-select" name="branch" id="empBranchSelect"></select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label small">Shift Start</label>
                <input required type="time" class="form-control" name="shiftStart" value="09:30">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label small">Shift End</label>
                <input required type="time" class="form-control" name="shiftEnd" value="18:30">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label small">Gender</label>
                <select required class="form-select" name="gender">
                  <option value="">Select</option>
                  <option>Male</option>
                  <option>Female</option>
                  <option>Other</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3 mb-3"><label class="form-label small">Age</label><input required type="number" min="18" max="70" class="form-control" name="age"></div>
              <div class="col-md-3 mb-3"><label class="form-label small">Date of Birth</label><input required type="date" class="form-control" name="dob"></div>
              <div class="col-md-3 mb-3"><label class="form-label small">Join Date</label><input type="date" class="form-control" name="join_date"></div>
              <div class="col-md-3 mb-3"><label class="form-label small">Salary (per month)</label><input type="number" step="0.01" min="0" class="form-control" name="salary" placeholder="0.00"></div>
              <div class="col-md-3 mb-3"><label class="form-label small">Paid Leaves / Month</label><input type="number" min="0" class="form-control" name="paid_leaves" value="1" re></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-accent" id="employeeSubmitBtn">Save Employee</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif

  <!-- ============ Designation Modal ============ -->
  <div class="modal fade" id="designationModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Designation</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="designationForm">
          <div class="modal-body">
            <div class="mb-3"><label class="form-label small">Designation Title</label><input required class="form-control" name="title"></div>
            <div class="mb-3"><label class="form-label small">Department</label><input required class="form-control" name="department"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-accent">Save Designation</button>
          </div>
        </form>
      </div>
    </div>
  </div>

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

  <!-- ============ Attendance edit modal (admin only) ============ -->
  <div class="modal fade" id="attendanceEditModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Edit Attendance</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="attendanceEditForm">
          <input type="hidden" id="attEmpId">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label small fw-semibold">Employee</label>
              <input type="text" id="attEmpName" class="form-control form-control-sm" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Date</label>
              <input type="date" id="attDateEdit" class="form-control form-control-sm" required>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label small fw-semibold">Check-in <span class="text-muted fw-normal">(shift 09:30)</span></label>
                <input type="time" id="attCheckIn" class="form-control form-control-sm" value="09:30">
              </div>
              <div class="col-6">
                <label class="form-label small fw-semibold">Check-out <span class="text-muted fw-normal">(shift 18:30)</span></label>
                <input type="time" id="attCheckOut" class="form-control form-control-sm" value="">
              </div>
            </div>
            <p class="small text-muted mb-0"><i class="bi bi-info-circle"></i> Use this when server/network was down and time was noted manually. Check-in and check-out update <strong>independently</strong> — if the employee hasn't clocked out yet, only update check-in.</p>
            <div id="attEditMsg" class="small mt-2 mb-0"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-accent btn-sm" id="saveCheckInBtn"><i class="bi bi-box-arrow-in-right"></i> Update Check-in</button>
            <button type="submit" class="btn btn-sm text-white" id="saveCheckOutBtn" style="background:var(--teal);"><i class="bi bi-box-arrow-right"></i> Update Check-out</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
      employees: "{{ route('admin.api.employees') }}",
      branches: "{{ route('admin.api.branches') }}",
      attendance: "{{ route('admin.api.attendance') }}",
      attendanceUpdate: "{{ route('admin.api.attendance.update') }}",
      leaveRequests: "{{ route('admin.api.leave-requests') }}",
      leaveAction: "{{ route('admin.api.leave-action') }}",
      designations: "{{ route('admin.api.designations') }}",
      notifications: "{{ route('admin.api.notifications') }}",
      markRead: "{{ route('admin.api.notifications.read') }}",
      sendNotification: "{{ route('admin.api.notifications.send') }}",
      salaryCalculations: "{{ route('admin.api.salary-calculations') }}",
      salaryPreview: "{{ route('admin.api.salary-preview') }}",
      holidays: "{{ route('admin.api.holidays') }}",
      dailyReports: "{{ route('admin.api.reports.daily') }}",
      employeeStatus: "{{ route('admin.api.employees.status') }}",
    };

    const ADMIN_BRANCH = @json($branch ? $branch->name : null);
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
      dashboard: ["Dashboard", "Overview of your organization today"],
      employees: ["Employees", "Manage employee profiles and records"],
      "employee-list": ["Employee List", "View employees with branch &amp; designation filters"],
      branches: ["Branches", "Manage office / branch locations"],
      attendance: ["Attendance", "Daily check-in / check-out log"],
      leave: ["Leave / Permission", "Review and act on employee requests"],
      designation: ["Designation List", "Job titles across departments"],
      notifications: ["Notifications", "Everything that needs your attention"],
      salary: ["Salary Calculations", "Monthly salary processing"],
      holidays: ["Holidays", "Manage company holidays"],
      reports: ["Daily Reports", "Branch-wise employee daily reports"]
    };

    function goTo(viewName) {
      const HIDDEN_VIEWS = IS_BRANCH_ADMIN ? ["branches"] : ["employees", "branches", "leave", "salary", "holidays"];
      if (HIDDEN_VIEWS.includes(viewName)) return;
      $(".view").removeClass("active");
      $("#view-" + viewName).addClass("active");
      $(".side-link[data-view]").removeClass("active");
      $(".side-link[data-view='" + viewName + "']").addClass("active");
      $("#pageTitle").text(titles[viewName][0]);
      $("#pageSub").text(titles[viewName][1]);
      closeSidebar();
      $("#globalSearch").val("");
      $(".view table.tbl tbody tr").show();

      if (viewName === "dashboard") loadDashboard();
      if (viewName === "employees") loadEmployees();
      if (viewName === "employee-list") loadEmployeeList();
      if (viewName === "branches") loadBranches();
      if (viewName === "attendance") loadAttendance();
      if (viewName === "leave") loadLeave();
      if (viewName === "designation") loadDesignations();
      if (viewName === "notifications") {
        loadNotifications();
        loadNotifTargets();
      }
      if (viewName === "salary") loadSalaryCalculations();
      if (viewName === "holidays") loadHolidays();
      if (viewName === "reports") loadReportsView();
    }

    $(document).on("click", "[data-view]", function(e) {
      e.preventDefault();
      goTo($(this).data("view"));
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
        $("#statTotal").text(stats.total);
        $("#statPresent").text(stats.present);
        $("#statLeave").text(stats.onLeave);
        $("#statPending").text(stats.pending);

        const pct = Math.round((stats.present / stats.total) * 100);
        const circ = 251;
        $("#attRingCircle").css("stroke-dashoffset", circ - (circ * pct / 100));
        $("#attRingPct").text(pct + "%");
        $("#legendPresent").text(stats.present);
        $("#legendLeave").text(stats.onLeave);
        $("#legendAbsent").text(stats.absent);
      });

      apiGet(API.leaveRequests).then(function(rows) {
        const recent = (rows || []).slice(0, 4);
        $("#dashRecentLeave").html(recent.map(function(r) {
          return "<tr><td>" + r.name + "</td><td>" + r.type + "</td>" +
            "<td class='mono small'>" + r.from + " &rarr; " + r.to + "</td>" +
            "<td>" + statusPill(r.status) + "</td></tr>";
        }).join(""));
      });
    }

    /* =========================================================================
       EMPLOYEES
       ========================================================================= */
    let employeesCache = [];

    function loadEmployees() {
      $("#employeesBody").html(skeletonRows(7, 10));
      return apiGet(API.employees).then(function(rows) {
        employeesCache = rows || [];
        renderEmployees(employeesCache);
        populateFilterDropdowns();
        renderEmployeeList(applyEmployeeListFilters());
      }).fail(function() {
        $("#employeesBody").html('<tr><td colspan="10" class="text-center text-muted py-4">Failed to load employees.</td></tr>');
      });
    }

    function fmtSalary(val) {
      if (val === null || val === undefined || val === "") return "--";
      return "₹" + parseFloat(val).toLocaleString("en-IN", {
        minimumFractionDigits: 2
      });
    }

    function renderEmployees(rows) {
      $("#employeesBody").html(rows.map(function(e) {
        return "<tr>" +
          "<td class='mono small text-muted'>" + e.id + "</td>" +
          "<td><div class='d-flex align-items-center gap-2'><img class='avatar-sm' src='" + e.img + "'><div><div class='fw-semibold'>" + e.name + "</div></div></div></td>" +
          "<td>" + e.designation + "</td>" +
          "<td>" + e.branch + "</td>" +
          "<td class='mono small'>" + fmtTime(e.shiftStart) + " - " + fmtTime(e.shiftEnd) + "</td>" +
          "<td class='text-muted small'>" + e.email + "</td>" +
          "<td class='mono small'>" + (e.join_date || "--") + "</td>" +
          "<td class='mono small'>" + (e.blood_group || "--") + "</td>" +
          "<td class='mono small'>" + fmtSalary(e.salary) + "</td>" +
          "<td>" + statusToggle(e.id, e.status) + "</td>" +
          "<td class='text-end'>" +
          "<span class='action-ic me-1' title='Edit' onclick='editEmployee(\"" + e.id + "\")'><i class='bi bi-pencil-fill'></i></span>" +
          "<span class='action-ic text-danger' title='Remove' onclick='removeEmployee(\"" + e.id + "\")'><i class='bi bi-trash-fill'></i></span>" +
          "</td>" +
          "</tr>";
      }).join(""));
    }

    function fmtTime(t) {
      if (!t) return "--";
      const [h, m] = t.split(":").map(Number);
      const period = h >= 12 ? "PM" : "AM";
      const h12 = ((h % 12) || 12);
      return h12 + ":" + String(m).padStart(2, "0") + " " + period;
    }
    $("#empSearch").on("input", function() {
      const q = $(this).val().toLowerCase();
      renderEmployees(employeesCache.filter(function(e) {
        return e.name.toLowerCase().includes(q) || e.id.toLowerCase().includes(q);
      }));
    });

    function removeEmployee(id) {
      if (!confirm("Remove this employee?")) return;
      apiPost(API.employees + "/delete", {
        id: id
      }).then(function() {
        loadEmployees();
        loadDashboard();
      }).fail(function(xhr) {
        var msg = (xhr.responseJSON && (xhr.responseJSON.message || JSON.stringify(xhr.responseJSON.errors))) || "unknown error";
        alert("Failed to remove employee: " + msg);
      });
    }
    let editingEmployeeId = null;
    let pendingPhotoDataUrl = null;

    function populateBranchSelect() {
      const source = (branchesCache && branchesCache.length) ? branchesCache : [];
      $("#empBranchSelect").html(source.map(function(b) {
        return "<option value='" + b.name + "'>" + b.name + "</option>";
      }).join(""));
    }

    let designationsCache = [];

    function populateDesignationSelect(selected) {
      const source = (designationsCache && designationsCache.length) ? designationsCache : [];
      let html = "<option value=''>Select designation</option>" + source.map(function(d) {
        return "<option value='" + d.title + "'" + (d.title === selected ? " selected" : "") + ">" + d.title + "</option>";
      }).join("");
      if (!source.length) html = "<option value=''>No designations (add one in Designation List)</option>";
      $("#empDesignationSelect").html(html);
    }

    function openEmployeeModal(employee) {
      const form = document.getElementById("employeeForm");
      form.reset();
      pendingPhotoDataUrl = null;
      populateBranchSelect();
      populateDesignationSelect(employee ? employee.designation : null);

      if (employee) {
        editingEmployeeId = employee.id;
        $("#employeeModalTitle").text("Edit Employee");
        $("#employeeSubmitBtn").text("Update Employee");
        $("#empFormId").val(employee.id);
        form.name.value = employee.name;
        form.email.value = employee.email;
        form.branch.value = employee.branch;
        form.shiftStart.value = employee.shiftStart;
        form.shiftEnd.value = employee.shiftEnd;
        form.gender.value = employee.gender;
        form.age.value = employee.age;
        form.dob.value = employee.dob;
        form.join_date.value = employee.join_date;
        form.employee_id.value = employee.id;
        form.blood_group.value = employee.blood_group || "";
        form.salary.value = employee.salary;
        form.paid_leaves.value = employee.paid_leaves || 1;
        $("#photoPreview").attr("src", employee.img);
      } else {
        editingEmployeeId = null;
        $("#employeeModalTitle").text("Add Employee");
        $("#employeeSubmitBtn").text("Save Employee");
        $("#photoPreview").attr("src", "https://i.pravatar.cc/100?img=68");
      }
      new bootstrap.Modal(document.getElementById("employeeModal")).show();
    }

    function editEmployee(id) {
      const emp = employeesCache.find(function(e) {
        return e.id === id;
      });
      if (emp) openEmployeeModal(emp);
    }

    $("#photoInput").on("change", function(e) {
      const file = e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function(ev) {
        pendingPhotoDataUrl = ev.target.result;
        $("#photoPreview").attr("src", pendingPhotoDataUrl);
      };
      reader.readAsDataURL(file);
    });

    $("input[name='dob']").on("change", function() {
      const dob = new Date($(this).val());
      if (isNaN(dob)) return;
      const today = new Date();
      let age = today.getFullYear() - dob.getFullYear();
      const m = today.getMonth() - dob.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
      $("input[name='age']").val(age);
    });

    $("#employeeForm").on("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(this);

      function sendRequest(url) {
        $.ajax({
          url: url,
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        }).then(function() {
          bootstrap.Modal.getInstance(document.getElementById("employeeModal")).hide();
          loadEmployees();
          loadDashboard();
        }).fail(function(xhr) {
          console.error("Employee save failed", xhr.responseJSON || xhr.statusText);
          alert("Failed to save employee: " + ((xhr.responseJSON && (xhr.responseJSON.message || JSON.stringify(xhr.responseJSON.errors))) || "unknown error"));
        });
      }

      sendRequest(editingEmployeeId ? API.employees + "/update" : API.employees);
    });

    /* =========================================================================
       EMPLOYEE LIST (filtered view)
       ========================================================================= */
    function loadEmployeeList() {
      if (!employeesCache || !employeesCache.length) {
        loadEmployees();
        return;
      }
      populateFilterDropdowns();
      applyEmployeeListFilters();
    }

    function populateFilterDropdowns() {
      const branchSet = {},
        desigSet = {};
      employeesCache.forEach(function(e) {
        if (e.branch) branchSet[e.branch] = true;
        if (e.designation) desigSet[e.designation] = true;
      });
      const branchHtml = ["<option value=''>All Branches</option>"].
      concat(Object.keys(branchSet).sort().map(function(b) {
        return "<option value='" + b + "'>" + b + "</option>";
      }));
      $("#empListBranchFilter").html(branchHtml.join(""));

      const desigHtml = ["<option value=''>All Designations</option>"].
      concat(Object.keys(desigSet).sort().map(function(d) {
        return "<option value='" + d + "'>" + d + "</option>";
      }));
      $("#empListDesignationFilter").html(desigHtml.join(""));
    }

    function applyEmployeeListFilters() {
      const branch = $("#empListBranchFilter").val();
      const desig = $("#empListDesignationFilter").val();
      const filtered = employeesCache.filter(function(e) {
        return (!branch || e.branch === branch) && (!desig || e.designation === desig);
      });
      renderEmployeeList(filtered);
      return filtered;
    }

    function renderEmployeeList(rows) {
      if (!rows || !rows.length) {
        $("#employeeListBody").html('<tr><td colspan="9" class="text-center text-muted py-4">No employees found.</td></tr>');
        return;
      }
      $("#employeeListBody").html(rows.map(function(e) {
        return "<tr>" +
          "<td class='mono small text-muted'>" + e.id + "</td>" +
          "<td><div class='d-flex align-items-center gap-2'><img class='avatar-sm' src='" + e.img + "'><div><div class='fw-semibold'>" + e.name + "</div></div></div></td>" +
          "<td>" + e.designation + "</td>" +
          "<td>" + e.branch + "</td>" +
          "<td class='mono small'>" + fmtTime(e.shiftStart) + " - " + fmtTime(e.shiftEnd) + "</td>" +
          "<td class='text-muted small'>" + e.email + "</td>" +
          "<td class='mono small'>" + (e.join_date || "--") + "</td>" +
          "<td class='mono small'>" + (e.blood_group || "--") + "</td>" +
          "<td class='mono small'>" + fmtSalary(e.salary) + "</td>" +
          "<td>" + statusToggle(e.id, e.status) + "</td>" +
          "</tr>";
      }).join(""));
    }

    $(document).on("change", "#empListBranchFilter, #empListDesignationFilter", applyEmployeeListFilters);

    /* =========================================================================
       BRANCHES
       ========================================================================= */
    let branchesCache = [];

    function loadBranches() {
      apiGet(API.branches).then(function(rows) {
        branchesCache = rows || [];
        if ($("#branchesBody").length) renderBranches(branchesCache);
      }).fail(function() {
        if ($("#branchesBody").length) $("#branchesBody").html('<tr><td colspan="7" class="text-center text-muted py-4">Failed to load branches.</td></tr>');
      });
    }

    function renderBranches(rows) {
      $("#branchesBody").html(rows.map(function(b) {
        return "<tr>" +
          "<td class='mono small text-muted'>" + b.id + "</td>" +
          "<td class='fw-semibold'>" + b.name + "</td>" +
          "<td>" + b.location + "</td>" +
          "<td>" + b.manager + "</td>" +
          "<td class='mono small'>" + b.phone + "</td>" +
          "<td class='mono'>" + b.employees + "</td>" +
          "<td class='text-end'>" +
          "<span class='action-ic me-1' title='Edit'><i class='bi bi-pencil-fill'></i></span>" +
          "<span class='action-ic text-danger' title='Remove' onclick='removeBranch(\"" + b.id + "\")'><i class='bi bi-trash-fill'></i></span>" +
          "</td>" +
          "</tr>";
      }).join(""));
    }

    function removeBranch(id) {
      if (!confirm("Remove this branch?")) return;
      apiPost(API.branches + "/delete", {
        id: id
      }).then(function() {
        branchesCache = branchesCache.filter(function(b) {
          return b.id !== id;
        });
        if ($("#branchesBody").length) renderBranches(branchesCache);
      });
    }
    $("#branchForm").on("submit", function(e) {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(this));
      data.id = "BR-" + String(branchesCache.length + 1).padStart(2, "0");
      data.employees = 0;
      apiPost(API.branches, data).then(function() {
        branchesCache.unshift(data);
        renderBranches(branchesCache);
        bootstrap.Modal.getInstance(document.getElementById("branchModal")).hide();
        document.getElementById("branchForm").reset();
      });
    });

    /* =========================================================================
       ATTENDANCE
       ========================================================================= */
    const ATT_HEADERS = {
      daily: ["Date", "Employee", "Designation", "Check-in", "Check-out", "Hours", "Status", "Location", "Action"],
      weekly: ["Employee", "Designation", "Present Days", "Absent Days", "Leave Days", "Late Count", "Total Hours"],
      monthly: ["Employee", "Designation", "Present Days", "Absent Days", "Leave Days", "Late Count", "Total Hours"],
      yearly: ["Employee", "Designation", "Present Days", "Absent Days", "Leave Days", "Late Count", "Total Hours"]
    };
    let currentAttPeriod = "daily";

    function loadAttendance(period) {
      currentAttPeriod = period || currentAttPeriod || "daily";
      $("#attDate").toggle(currentAttPeriod === "daily");
      $("#attendanceHead").html("<tr>" + ATT_HEADERS[currentAttPeriod].map(function(h) {
        return "<th>" + h + "</th>";
      }).join("") + "</tr>");
      $("#attendanceBody").html(skeletonRows(5, ATT_HEADERS[currentAttPeriod].length));

      apiGet(API.attendance + "?period=" + currentAttPeriod + (currentAttPeriod === "daily" ? "&date=" + ($("#attDate").val() || "") : "")).then(function(rows) {
        if (currentAttPeriod === "daily") {
          $("#attendanceBody").html(rows.map(function(a) {
            const loc = a.location_name || (a.latitude && a.longitude ? a.latitude + ", " + a.longitude : "--");
            return "<tr><td class='mono small'>" + a.date + "</td><td class='fw-semibold'>" + a.name + "</td><td>" + a.designation + "</td>" +
              "<td class='mono small'>" + a.in + "</td><td class='mono small'>" + a.out + "</td>" +
              "<td class='mono small'>" + a.hours + "</td><td>" + attendancePill(a.status) + "</td>" +
              "<td class='small text-muted' title='" + (a.latitude ? a.latitude + ", " + a.longitude : "") + "'>" + loc + "</td>" +
              "<td><span class='action-ic' title='Edit check-in / check-out' onclick='openAttendanceEdit(\"" + a.employee_id + "\", \"" + a.name + "\", \"" + a.date + "\", \"" + (a.inRaw || "") + "\", \"" + (a.outRaw || "") + "\")'><i class='bi bi-pencil-fill'></i></span></td></tr>";
          }).join(""));
        } else {
          $("#attendanceBody").html(rows.map(function(a) {
            return "<tr><td class='fw-semibold'>" + a.name + "</td><td>" + a.designation + "</td>" +
              "<td class='mono'>" + a.present + "</td><td class='mono'>" + a.absent + "</td>" +
              "<td class='mono'>" + a.leave + "</td><td class='mono'>" + a.late + "</td>" +
              "<td class='mono'>" + a.totalHours + "h</td></tr>";
          }).join(""));
        }
      });
    }
    $("#attDate").on("change", function() {
      loadAttendance("daily");
    }); // hook a date param onto API.attendance in production
    $("#attPeriodGroup .btn").on("click", function() {
      $("#attPeriodGroup .btn").removeClass("active");
      $(this).addClass("active");
      loadAttendance($(this).data("period"));
    });

    /* ---------------- Manual attendance edit (admin) ---------------- */
    function openAttendanceEdit(employeeId, name, date, inRaw, outRaw) {
      $("#attEmpId").val(employeeId);
      $("#attEmpName").val(name);
      $("#attDateEdit").val(date);
      $("#attCheckIn").val(inRaw || "09:30");
      $("#attCheckOut").val(outRaw || "");
      $("#attEditMsg").removeClass("text-danger text-success").text(outRaw ? "" : "No check-out yet — update only the check-in.");
      new bootstrap.Modal(document.getElementById("attendanceEditModal")).show();
    }

    let attEditField = "check_in";
    $("#saveCheckInBtn").on("click", function() {
      attEditField = "check_in";
    });
    $("#saveCheckOutBtn").on("click", function() {
      attEditField = "check_out";
    });

    $("#attendanceEditForm").on("submit", function(e) {
      e.preventDefault();
      const $msg = $("#attEditMsg");
      $msg.removeClass("text-danger text-success").text("");

      const field = attEditField;
      const time = field === "check_in" ? $("#attCheckIn").val() : $("#attCheckOut").val();
      if (!time) {
        $msg.addClass("text-danger").text(field === "check_in" ? "Enter a check-in time." : "Enter a check-out time.");
        return;
      }

      const btn = field === "check_in" ? $("#saveCheckInBtn") : $("#saveCheckOutBtn");
      btn.prop("disabled", true);

      apiPost(API.attendanceUpdate, {
        employee_id: $("#attEmpId").val(),
        date: $("#attDateEdit").val(),
        field: field,
        time: time
      }).done(function(resp) {
        $msg.addClass("text-success").text(resp.message || (field === "check_in" ? "Check-in updated." : "Check-out updated."));
        setTimeout(function() {
          bootstrap.Modal.getInstance(document.getElementById("attendanceEditModal")).hide();
          loadAttendance("daily");
        }, 900);
      }).fail(function(xhr) {
        const m = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Failed to update attendance.";
        $msg.addClass("text-danger").text(m);
      }).always(function() {
        btn.prop("disabled", false);
      });
    });

    function getAttendanceTableData() {
      const period = currentAttPeriod || "daily";
      const headers = ATT_HEADERS[period].filter(function(h) {
        return h !== "Action";
      });
      const rows = [];
      $("#attendanceBody tr").each(function() {
        if ($(this).hasClass("skeleton-row")) return;
        const row = [];
        $(this).find("td").each(function() {
          row.push($(this).text().trim());
        });
        while (row.length && row[row.length - 1] === "") row.pop();
        if (row.length) rows.push(row);
      });
      return {
        period: period,
        headers: headers,
        rows: rows
      };
    }

    function exportAttendancePDF() {
      const data = getAttendanceTableData();
      const title = "Attendance Report (" + data.period.charAt(0).toUpperCase() + data.period.slice(1) + ")";
      let html = "<div style='padding:20px;font-family:Inter,sans-serif;'>";
      html += "<h2 style='text-align:center;margin-bottom:20px;'>" + title + "</h2>";
      html += "<table style='width:100%;border-collapse:collapse;'>";
      html += "<thead><tr>";
      data.headers.forEach(function(h) {
        html += "<th style='border:1px solid #ccc;padding:8px;background:#4f5bd5;color:#fff;text-align:left;font-size:12px;'>" + h + "</th>";
      });
      html += "</tr></thead><tbody>";
      data.rows.forEach(function(r) {
        html += "<tr>";
        r.forEach(function(c) {
          html += "<td style='border:1px solid #ccc;padding:6px;font-size:11px;'>" + c + "</td>";
        });
        html += "</tr>";
      });
      html += "</tbody></table></div>";

      var opt = {
        margin: [10, 10, 10, 10],
        filename: title + ".pdf",
        image: {
          type: "jpeg",
          quality: 0.98
        },
        html2canvas: {
          scale: 2,
          useCORS: true
        },
        jsPDF: {
          unit: "mm",
          format: "a4",
          orientation: "landscape"
        }
      };
      html2pdf().set(opt).from(html).save();
    }

    function exportAttendanceExcel() {
      const data = getAttendanceTableData();
      const title = "Attendance Report (" + data.period.charAt(0).toUpperCase() + data.period.slice(1) + ")";
      const ws_data = [data.headers].concat(data.rows);
      const ws = XLSX.utils.aoa_to_sheet(ws_data);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, "Attendance");
      XLSX.writeFile(wb, title + ".xlsx");
    }

    /* =========================================================================
       LEAVE / PERMISSION
       ========================================================================= */
    let leaveCache = [];

    function loadLeave() {
      $("#leaveBody").html(skeletonRows(4, 7));
      apiGet(API.leaveRequests).then(function(rows) {
        leaveCache = rows;
        renderLeave(rows);
        $("#leaveBadge, #notifBadge").each(function() {});
        $("#leaveBadge").text(rows.filter(function(r) {
          return r.status === "Pending";
        }).length);
      });
    }

    function renderLeave(rows) {
      const filter = $("#leaveFilter").val() || "all";
      const filtered = filter === "all" ? rows : rows.filter(function(r) {
        return r.status === filter;
      });
      $("#leaveBody").html(filtered.map(function(r) {
        const actions = r.status === "Pending" ?
          "<button class='btn btn-sm text-white me-1' style='background:var(--teal);' onclick='actOnLeave(" + r.id + ",\"Approved\")'>Approve</button>" +
          "<button class='btn btn-sm text-white' style='background:var(--coral);' onclick='actOnLeave(" + r.id + ",\"Rejected\")'>Reject</button>" :
          "<span class='text-muted small'>&mdash;</span>";
        return "<tr><td class='fw-semibold'>" + r.name + "</td><td>" + r.type + "</td>" +
          "<td class='mono small'>" + r.from + "</td><td class='mono small'>" + r.to + "</td>" +
          "<td class='small'>" + r.reason + "</td><td>" + statusPill(r.status) + "</td>" +
          "<td class='text-end'>" + actions + "</td></tr>";
      }).join(""));
    }
    $("#leaveFilter").on("change", function() {
      renderLeave(leaveCache);
    });

    function actOnLeave(id, action) {
      apiPost(API.leaveAction, {
        id: id,
        action: action
      }).then(function() {
        leaveCache = leaveCache.map(function(r) {
          if (r.id === id) r.status = action;
          return r;
        });
        renderLeave(leaveCache);
        $("#leaveBadge").text(leaveCache.filter(function(r) {
          return r.status === "Pending";
        }).length);
      });
    }

    /* =========================================================================
       DESIGNATION LIST
       ========================================================================= */
    function loadDesignations() {
      $("#designationBody").html(skeletonRows(5, 4));
      apiGet(API.designations).then(function(rows) {
        $("#designationBody").html(rows.map(function(d) {
          return "<tr><td class='fw-semibold'>" + d.title + "</td><td>" + d.department + "</td>" +
            "<td class='mono'>" + d.count + "</td>" +
            "<td class='text-end'><span class='action-ic me-1'><i class='bi bi-pencil-fill'></i></span>" +
            "<span class='action-ic text-danger'><i class='bi bi-trash-fill'></i></span></td></tr>";
        }).join(""));
      });
    }
    $("#designationForm").on("submit", function(e) {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(this));
      data.count = 0;
      apiPost(API.designations, data).then(function() {
        loadDesignations();
        bootstrap.Modal.getInstance(document.getElementById("designationModal")).hide();
      });
    });

    /* =========================================================================
       NOTIFICATIONS
       ========================================================================= */
    function loadNotifications() {
      apiGet(API.notifications).then(function(rows) {
        renderNotifBadge(rows);
        $("#notifList").html(rows.map(function(n) {
          const colorMap = {
            amber: ["var(--amber-soft)", "#a06405"],
            coral: ["var(--coral-soft)", "#c22f42"],
            teal: ["var(--teal-soft)", "#0a8577"],
            indigo: ["var(--accent-soft)", "#4147a8"]
          };
          const c = colorMap[n.color] || colorMap.indigo;
          return "<div class='notif-item " + (n.unread ? "unread" : "") + "'>" +
            "<div class='notif-ic' style='background:" + c[0] + ";color:" + c[1] + ";'><i class='bi " + n.icon + "'></i></div>" +
            "<div class='flex-grow-1'>" +
            "<div class='fw-semibold small'>" + n.title + "</div>" +
            "<div class='text-muted small'>" + n.body + "</div>" +
            "<div class='notif-time'>" + n.time + "</div>" +
            "</div>" +
            "</div>";
        }).join(""));
      });
    }

    function renderNotifBadge(rows) {
      const unread = rows.filter(function(n) {
        return n.unread;
      }).length;
      $("#notifBadge").text(unread);
      $("#topNotifDot").toggle(unread > 0);
    }
    $("#markAllReadBtn").on("click", function() {
      var $btn = $(this);
      $btn.prop("disabled", true);
      apiPost(API.markRead, {
        all: true
      }).then(function() {
        $btn.prop("disabled", false);
        loadNotifications();
      }).fail(function() {
        $btn.prop("disabled", false);
      });
    });

    /* ---------------- Send notification ---------------- */
    let notifEmployeeOptions = [];
    let notifTargetsLoaded = false;

    function loadNotifTargets() {
      if (notifTargetsLoaded) return;
      apiGet(API.employees).then(function(rows) {
        notifEmployeeOptions = rows || [];
        notifTargetsLoaded = true;
        renderNotifTargets();
      });
    }

    function renderNotifTargets() {
      const allLabel = notifEmployeeOptions.length ?
        "All Employees (" + notifEmployeeOptions.length + ")" :
        "All Employees";
      const options = notifEmployeeOptions.map(function(e) {
        return "<option value='" + e.id + "'>" + e.name + " (" + e.id + ")</option>";
      }).join("");
      $("#notifTarget").html('<option value="all">' + allLabel + '</option>' +
        '<optgroup label="Individual employees">' + options + '</optgroup>');
    }

    $("#sendNotifForm").on("submit", function(e) {
      e.preventDefault();
      const $msg = $("#notifSendMsg");
      $msg.removeClass("text-danger text-success").text("");

      const target = $("#notifTarget").val();
      const title = $("#notifTitle").val().trim();
      const body = $("#notifBody").val().trim();
      if (!title || !body) {
        $msg.addClass("text-danger").text("Please fill in the title and message.");
        return;
      }

      const btn = $(this).find("button[type=submit]");
      btn.prop("disabled", true);

      apiPost(API.sendNotification, {
        target: target,
        title: title,
        body: body
      }).done(function(resp) {
        $msg.addClass("text-success").text(resp.message || "Notification sent.");
        $("#notifTitle").val("");
        $("#notifBody").val("");
        loadNotifications();
      }).fail(function(xhr) {
        const m = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Failed to send notification.";
        $msg.addClass("text-danger").text(m);
      }).always(function() {
        btn.prop("disabled", false);
      });
    });

    /* =========================================================================
       SALARY CALCULATIONS
       ========================================================================= */
    function loadSalaryCalculations() {
      $("#salaryBody").html(skeletonRows(4, 9));
      apiGet(API.salaryCalculations).then(function(rows) {
        renderSalaryCalculations(rows || []);
      }).fail(function() {
        $("#salaryBody").html('<tr><td colspan="9" class="text-center text-muted py-4">Failed to load salary data.</td></tr>');
      });
    }

    function fmtMonthLabel(m) {
      var parts = String(m || "").split("-");
      if (parts.length === 2 && /^\d{4}$/.test(parts[0]) && /^\d{1,2}$/.test(parts[1])) {
        var names = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        return names[parseInt(parts[1], 10) - 1] + "-" + parts[0];
      }
      return m;
    }

    function renderSalaryCalculations(rows) {
      if (!rows.length) {
        $("#salaryBody").html('<tr><td colspan="9" class="text-center text-muted py-4">No salary records yet.</td></tr>');
        return;
      }
      $("#salaryBody").html(rows.map(function(s) {
        return "<tr>" +
          "<td><div class='d-flex align-items-center gap-2'><img class='avatar-sm' src='" + s.employee_img + "'><div><div class='fw-semibold'>" + s.employee_name + "</div></div></div></td>" +
          "<td class='mono'>" + fmtMonthLabel(s.month) + "</td>" +
          "<td class='mono'>" + fmtSalary(s.base_salary) + "</td>" +
          "<td>" + s.absent_days + "</td>" +
          "<td>" + s.leave_days + "</td>" +
          "<td>" + s.paid_leaves_used + "</td>" +
          "<td>" + s.deductible_days + "</td>" +
          "<td class='mono fw-semibold'>" + fmtSalary(s.final_salary) + "</td>" +
          "<td class='small text-muted'>" + s.created_at + "</td>" +
          "</tr>";
      }).join(""));
    }

    function openSalaryModal() {
      apiGet(API.employees).then(function(emps) {
        const sel = $("#salaryEmployeeSelect");
        sel.html("<option value=''>Select employee</option>" + emps.map(function(e) {
          return "<option value='" + e.id + "' data-salary='" + (e.salary || 0) + "' data-paid-leaves='" + (e.paid_leaves || 1) + "'>" + e.name + " (" + e.id + ")</option>";
        }).join(""));
      });
      const now = new Date();
      $("#salaryMonth").val(now.getFullYear() + "-" + String(now.getMonth() + 1).padStart(2, "0"));
      $("#salaryBase").val("");
      $("#salaryPresent").val(0);
      $("#salaryHalfDays").val(0);
      $("#salaryAbsent").val(0);
      $("#salaryLeaveDays").val(0);
      $("#salaryEligible").val(0);
      $("#salaryDeductible").val(0);
      $("#salaryWorked").val(0);
      $("#salaryFinalDisplay").val("");
      new bootstrap.Modal(document.getElementById("salaryModal")).show();
    }

    function recalcSalary(preview) {
      const base = parseFloat($("#salaryBase").val()) || 0;
      if (!preview) {
        $("#salaryFinalDisplay").val(base ? "₹" + base.toLocaleString("en-IN", {
          minimumFractionDigits: 2
        }) : "₹0.00");
        return;
      }
      const p = preview;
      $("#salaryBase").val(p.base_salary);
      $("#salaryPresent").val(p.present_days);
      $("#salaryHalfDays").val(p.half_days);
      $("#salaryAbsent").val(p.absent_days);
      $("#salaryLeaveDays").val(p.leave_days);
      $("#salaryEligible").val(p.eligible_days);
      $("#salaryDeductible").val(p.deductible_days);
      $("#salaryWorked").val(p.worked_days);
      $("#salaryFinalDisplay").val(p.final_salary ? "₹" + Number(p.final_salary).toLocaleString("en-IN", {
        minimumFractionDigits: 2
      }) : "₹0.00");
    }

    $("#salaryEmployeeSelect").on("change", function() {
      const opt = $(this).find("option:selected");
      $("#salaryBase").val(opt.data("salary") || 0);
      recalcSalary(null);
    });

    $(document).on("change", "#salaryEmployeeSelect, #salaryMonth", function() {
      const emp = $("#salaryEmployeeSelect").val();
      const month = $("#salaryMonth").val();
      if (emp && month) {
        apiGet(API.salaryPreview + "?employee_id=" + encodeURIComponent(emp) + "&month=" + encodeURIComponent(month)).then(function(p) {
          if (p.success) recalcSalary(p);
        });
      }
    });

    $("#salaryForm").on("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      $.ajax({
        url: API.salaryCalculations,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      }).then(function(res) {
        loadSalaryCalculations();
        bootstrap.Modal.getInstance(document.getElementById("salaryModal")).hide();
      }).fail(function(xhr) {
        console.error("Salary calculation failed", xhr.responseJSON || xhr.statusText);
        alert("Failed to save calculation: " + ((xhr.responseJSON && (xhr.responseJSON.message || JSON.stringify(xhr.responseJSON.errors))) || "unknown error"));
      });
    });

    /* =========================================================================
       HOLIDAYS
       ========================================================================= */
    function loadHolidays() {
      $("#holidaysBody").html(skeletonRows(3, 4));
      apiGet(API.holidays).then(function(rows) {
        renderHolidays(rows || []);
      }).fail(function() {
        $("#holidaysBody").html('<tr><td colspan="4" class="text-center text-muted py-4">Failed to load holidays.</td></tr>');
      });
    }

    function renderHolidays(rows) {
      if (!rows.length) {
        $("#holidaysBody").html('<tr><td colspan="4" class="text-center text-muted py-4">No holidays added yet.</td></tr>');
        return;
      }
      const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
      $("#holidaysBody").html(rows.map(function(h) {
        const d = new Date(h.date + "T00:00:00");
        const dayName = dayNames[d.getDay()];
        return "<tr>" +
          "<td class='mono'>" + h.date + "</td>" +
          "<td>" + dayName + "</td>" +
          "<td class='fw-semibold'>" + h.title + "</td>" +
          "<td class='text-end'><span class='action-ic text-danger' onclick='deleteHoliday(" + h.id + ")' title='Remove'><i class='bi bi-trash-fill'></i></span></td>" +
          "</tr>";
      }).join(""));
    }

    function deleteHoliday(id) {
      if (!confirm("Remove this holiday?")) return;
      apiPost(API.holidays + "/delete", {
        id: id
      }).then(function() {
        loadHolidays();
      });
    }

    function openHolidayModal() {
      $("#holidayForm")[0].reset();
      new bootstrap.Modal(document.getElementById("holidayModal")).show();
    }

    $("#holidayForm").on("submit", function(e) {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(this));
      apiPost(API.holidays, data).then(function() {
        loadHolidays();
        bootstrap.Modal.getInstance(document.getElementById("holidayModal")).hide();
      }).fail(function(xhr) {
        console.error("Adding holiday failed", xhr.responseJSON || xhr.statusText);
      });
    });

    /* =========================================================================
       DAILY REPORTS
       ========================================================================= */
    let currentReportEmployeeId = null;

    function loadReportsView() {
      populateReportBranches();
      if (!employeesCache.length) {
        loadEmployees().then(renderReportEmployees);
      } else {
        renderReportEmployees();
      }
    }

    function populateReportBranches() {
      const branches = (branchesCache && branchesCache.length) ? branchesCache : [];
      let html = "<option value=''>All Branches</option>";
      html += branches.map(function(b) {
        return "<option value='" + b.name + "'>" + b.name + "</option>";
      }).join("");
      $("#reportBranchFilter").html(html);
      if (IS_BRANCH_ADMIN) {
        $("#reportBranchFilter").val(ADMIN_BRANCH).prop("disabled", true);
      }
    }

    function renderReportEmployees() {
      const branch = $("#reportBranchFilter").val();
      const rows = employeesCache.filter(function(e) {
        return !branch || e.branch === branch;
      });
      if (!rows.length) {
        $("#reportEmployeesBody").html('<tr><td colspan="6" class="text-center text-muted py-4">No employees found for the selected branch.</td></tr>');
        return;
      }
      $("#reportEmployeesBody").html(rows.map(function(e) {
        return "<tr>" +
          "<td class='mono small text-muted'>" + e.id + "</td>" +
          "<td><div class='d-flex align-items-center gap-2'><img class='avatar-sm' src='" + e.img + "'><div class='fw-semibold'>" + e.name + "</div></div></td>" +
          "<td>" + e.designation + "</td>" +
          "<td class='text-muted small'>" + e.email + "</td>" +
          "<td>" + statusPill(e.status) + "</td>" +
          "<td class='text-end'><button class='btn btn-accent btn-sm' onclick='openDailyReport(\"" + e.id + "\",\"" + e.name.replace(/"/g, "&quot;") + "\")'><i class='bi bi-eye-fill'></i> View</button></td>" +
          "</tr>";
      }).join(""));
    }

    $("#reportBranchFilter").on("change", renderReportEmployees);

    function openDailyReport(employeeId, name) {
      currentReportEmployeeId = employeeId;
      $("#dailyReportTitle").text("Daily Report - " + name + " (" + employeeId + ")");
      $("#reportPeriodGroup .btn").removeClass("active");
      $("#reportPeriodGroup .btn[data-period='all']").addClass("active");
      $("#reportDateWrap").hide();
      $("#reportMonthWrap").hide();
      $("#reportDate").val("");
      $("#reportMonth").val("");
      loadDailyReport();
      new bootstrap.Modal(document.getElementById("dailyReportModal")).show();
    }

    $("#reportPeriodGroup .btn").on("click", function() {
      $("#reportPeriodGroup .btn").removeClass("active");
      $(this).addClass("active");
      const period = $(this).data("period");
      $("#reportDateWrap").toggle(period === "date");
      $("#reportMonthWrap").toggle(period === "month");
      loadDailyReport();
    });

    $("#reportDate, #reportMonth").on("change", loadDailyReport);

    function loadDailyReport() {
      if (!currentReportEmployeeId) return;
      const period = $("#reportPeriodGroup .btn.active").data("period");
      let url = API.dailyReports + "?employee_id=" + encodeURIComponent(currentReportEmployeeId);
      if (period === "date" && $("#reportDate").val()) {
        url += "&date=" + $("#reportDate").val();
      }
      if (period === "month" && $("#reportMonth").val()) {
        url += "&month=" + $("#reportMonth").val();
      }

      $("#dailyReportBody").html(skeletonRows(4, 6));
      apiGet(url).then(function(rows) {
        if (!rows || !rows.length) {
          $("#dailyReportBody").html('<tr><td colspan="6" class="text-center text-muted py-4">No reports found for the selected period.</td></tr>');
          return;
        }
        $("#dailyReportBody").html(rows.map(function(r) {
          return "<tr>" +
            "<td class='mono small'>" + r.date + "</td>" +
            "<td class='mono small'>" + r.check_in + "</td>" +
            "<td class='mono small'>" + r.check_out + "</td>" +
            "<td class='mono small'>" + r.hours + "</td>" +
            "<td>" + attendancePill(r.status) + "</td>" +
            "<td class='small report-text'>" + (r.report ? r.report : "<span class='text-muted'>--</span>") + "</td>" +
            "</tr>";
        }).join(""));
      }).fail(function() {
        $("#dailyReportBody").html('<tr><td colspan="6" class="text-center text-muted py-4">Failed to load reports.</td></tr>');
      });
    }

    function downloadAdminReportPdf() {
      var bodyHtml = $("#dailyReportBody").html();
      if (!bodyHtml || bodyHtml.indexOf("No reports") > -1) {
        alert("No report data to download.");
        return;
      }
      var period = "All records";
      if ($("#reportPeriodGroup .btn.active").data("period") === "date") period = "Date: " + $("#reportDate").val();
      else if ($("#reportPeriodGroup .btn.active").data("period") === "month") period = "Month: " + $("#reportMonth").val();

      var title = $("#dailyReportTitle").text();
      var cleanRows = bodyHtml.replace(/<span class="pill[^"]*">(.*?)<\/span>/g, "$1");

      var $el = $("<div style='padding:24px;font-family:Helvetica,Arial,sans-serif;color:#111;'>" +
        "<h2 style='margin:0 0 2px;color:#0a8577;'>Guru Group Attendance</h2>" +
        "<p style='margin:0 0 14px;color:#555;font-size:12px;'>" + title + "<br>" + period + "</p>" +
        "<table style='width:100%;border-collapse:collapse;font-size:11px;'>" +
        "<thead><tr style='background:#0fb5a3;color:#fff;'>" +
        "<th style='padding:6px;border:1px solid #cfd2dd;text-align:left;'>Date</th>" +
        "<th style='padding:6px;border:1px solid #cfd2dd;text-align:left;'>Check In</th>" +
        "<th style='padding:6px;border:1px solid #cfd2dd;text-align:left;'>Check Out</th>" +
        "<th style='padding:6px;border:1px solid #cfd2dd;text-align:left;'>Hours</th>" +
        "<th style='padding:6px;border:1px solid #cfd2dd;text-align:left;'>Status</th>" +
        "<th style='padding:6px;border:1px solid #cfd2dd;text-align:left;'>Daily Report</th>" +
        "</tr></thead><tbody>" + cleanRows + "</tbody></table></div>").appendTo("body");

      html2pdf().set({
        margin: [10, 10, 10, 10],
        filename: "daily-report-" + $("#dailyReportTitle").text().replace(/[^a-zA-Z0-9]/g, "-") + ".pdf",
        image: { type: "jpeg", quality: 0.95 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: "mm", format: "a4", orientation: "portrait" }
      }).from($el[0]).save().then(function() {
        $el.remove();
      });
    }

    /* =========================================================================
       HELPERS
       ========================================================================= */
    function statusPill(status) {
      const map = {
        Active: "pill-teal",
        Approved: "pill-teal",
        Present: "pill-teal",
        Holiday: "pill-indigo",
        Pending: "pill-amber",
        "On Leave": "pill-amber",
        Rejected: "pill-coral",
        Deactivated: "pill-coral",
        Absent: "pill-coral"
      };
      const cls = map[status] || "pill-indigo";
      return "<span class='pill " + cls + "'>" + status + "</span>";
    }

    function statusToggle(employeeId, currentStatus) {
      const isActive = currentStatus === "Active";
      const cls = isActive ? "pill-teal" : "pill-coral";
      return "<div class='dropdown d-inline-block'>" +
        "<span class='pill " + cls + " dropdown-toggle' data-bs-toggle='dropdown' style='cursor:pointer;'>&nbsp;" + currentStatus + "&nbsp;</span>" +
        "<ul class='dropdown-menu dropdown-menu-end min-width-auto' style='min-width:auto;'>" +
        "<li><a class='dropdown-item small' href='#' onclick='toggleEmployeeStatus(\"" + employeeId + "\",\"Active\")'><span class='pill pill-teal' style='font-size:.68rem;'>Active</span></a></li>" +
        "<li><a class='dropdown-item small' href='#' onclick='toggleEmployeeStatus(\"" + employeeId + "\",\"Deactivated\")'><span class='pill pill-coral' style='font-size:.68rem;'>Deactivated</span></a></li>" +
        "</ul></div>";
    }

    function toggleEmployeeStatus(employeeId, newStatus) {
      apiPost(API.employeeStatus, {
        employee_id: employeeId,
        status: newStatus
      }).then(function(resp) {
        employeesCache = employeesCache.map(function(e) {
          if (e.id === employeeId) e.status = resp.status;
          return e;
        });
        renderEmployees(employeesCache);
        renderEmployeeList(applyEmployeeListFilters());
      }).fail(function(xhr) {
        console.error("Status update failed", xhr.responseJSON || xhr.statusText);
      });
    }

    function attendancePill(status) {
      const map = {
        Present: "pill-teal",
        Late: "pill-amber",
        "Half-day": "pill-amber",
        "On Leave": "pill-indigo",
        Absent: "pill-coral",
        Holiday: "pill-indigo"
      };
      return "<span class='pill " + (map[status] || "pill-indigo") + "'>" + status + "</span>";
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
      if (IS_BRANCH_ADMIN) loadLeave();
      loadNotifications();
      loadNotifTargets();
      loadBranches();
      apiGet(API.designations).then(function(rows) {
        designationsCache = rows || [];
        populateDesignationSelect();
      });
    });
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