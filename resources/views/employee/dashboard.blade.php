<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: var(--surface);
      color: var(--text);
      font-size: 14.5px;
    }
    h1, h2, h3, h4, h5, .brand, .stat-num { font-family: 'Sora', sans-serif; }
    .mono { font-family: 'JetBrains Mono', monospace; }
    a { text-decoration: none; }

    .app-shell { display: flex; min-height: 100vh; }

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
    .brand .brand-logo { height: 34px; width: auto; object-fit: contain; }

    .side-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }
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
    .side-link i { font-size: 1.05rem; width: 20px; text-align: center; }
    .side-link .badge-pill {
      margin-left: auto;
      font-size: .68rem;
      font-weight: 600;
      background: var(--coral);
      color: #fff;
      border-radius: 20px;
      padding: 1px 7px;
    }
    .side-link:hover { background: #e9ebf5; color: var(--text); }
    .side-link.active { background: var(--accent-soft); color: var(--accent); }
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
    .side-foot img { height: 28px; width: auto; object-fit: contain; }
    .side-foot .name { color: var(--text); font-weight: 600; font-size: .85rem; }
    .side-foot .role { color: #8a91a8; font-size: .72rem; }

    .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(10,12,20,.5); z-index: 55; }
    .sidebar-overlay.show { display: block; }
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
    .content { flex: 1; min-width: 0; display: flex; flex-direction: column; }
    .topbar {
      background: var(--card);
      border-bottom: 1px solid var(--line);
      padding: 14px 28px;
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .topbar .page-title { font-family: 'Sora', sans-serif; font-weight: 700; font-size: 1.15rem; margin: 0; }
    .topbar .page-sub { font-size: .78rem; color: var(--text-soft); margin: 0; }
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
    .topbar .user-menu { position: relative; }
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
    .topbar .user-menu-btn:hover { border-color: var(--accent); }
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
      box-shadow: 0 12px 32px rgba(20,24,50,.14);
      min-width: 250px;
      z-index: 80;
      overflow: hidden;
    }
    .topbar .user-menu-dropdown.open { display: block; }
    .topbar .user-menu-head {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 13px 14px;
      border-bottom: 1px solid var(--line);
    }
    .topbar .user-menu-head img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
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
    .topbar .user-menu-item:hover { background: var(--accent-soft); }
    .topbar .user-menu-item.logout { color: var(--coral); }

    .main { padding: 26px 28px 60px; }

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
    .stat-ic { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .stat-num { font-size: 1.5rem; font-weight: 700; line-height: 1; }
    .stat-label { color: var(--text-soft); font-size: .78rem; margin-top: 4px; }

    .section-card {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 20px 22px;
      margin-bottom: 22px;
    }
    .section-card .section-head { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
    .section-card h5 { margin: 0; font-weight: 700; }

    .view { display: none; }
    .view.active { display: block; }

    /* ---------- Table ---------- */
    table.tbl { width: 100%; border-collapse: collapse; }
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
    table.tbl tbody td { padding: 12px; border-bottom: 1px solid var(--line); vertical-align: middle; font-size: .86rem; }
    table.tbl tbody tr:last-child td { border-bottom: none; }
    table.tbl tbody tr:hover { background: var(--surface); }

    .pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
    .pill-teal { background: var(--teal-soft); color: #0a8577; }
    .pill-amber { background: var(--amber-soft); color: #a06405; }
    .pill-coral { background: var(--coral-soft); color: #c22f42; }
    .pill-indigo { background: var(--accent-soft); color: #4147a8; }

    .avatar-sm { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }

    .btn-accent { background: var(--accent); border-color: var(--accent); color: #fff; }
    .btn-accent:hover { background: #3f49c2; border-color: #3f49c2; color: #fff; }
    .btn-ghost { background: var(--surface); border: 1px solid var(--line); color: var(--text); }
    .btn-ghost:hover { background: #e9ebf5; color: var(--text); }

    /* ---------- Profile ---------- */
    .profile-head {
      display: flex;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
    }
    .profile-head img.avatar {
      width: 92px;
      height: 92px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid rgba(79,91,213,.4);
    }
    .profile-head .p-name { font-size: 1.35rem; font-weight: 700; }
    .profile-head .p-role { color: var(--accent); font-weight: 600; font-size: .9rem; margin: 3px 0 8px; }
    .profile-head .p-meta { display: flex; gap: 18px; flex-wrap: wrap; font-size: .82rem; color: var(--text-soft); }
    .profile-head .p-meta strong { color: var(--text); display: block; font-size: .86rem; margin-top: 1px; }

    .detail-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); gap: 14px; }
    .detail-item { background: var(--surface); border: 1px solid var(--line); border-radius: 12px; padding: 12px 14px; }
    .detail-item .l { font-size: .7rem; color: var(--text-soft); text-transform: uppercase; letter-spacing: .06em; }
    .detail-item .v { font-size: .9rem; font-weight: 600; margin-top: 4px; word-break: break-word; }

    /* ---------- Notifications ---------- */
    .notif-item { display: flex; gap: 12px; padding: 14px 6px; border-bottom: 1px solid var(--line); }
    .notif-item:last-child { border-bottom: none; }
    .notif-ic { width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
    .notif-item.unread { background: rgba(79,91,213,.04); border-radius: 10px; }
    .notif-time { color: var(--text-soft); font-size: .72rem; }

    /* ---------- Salary ---------- */
    .salary-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 14px; }
    .salary-box { background: var(--surface); border: 1px solid var(--line); border-radius: 12px; padding: 14px; text-align: center; }
    .salary-box .l { font-size: .7rem; color: var(--text-soft); text-transform: uppercase; letter-spacing: .06em; }
    .salary-box .v { font-size: 1.05rem; font-weight: 700; margin-top: 5px; font-family: 'Sora', sans-serif; }

    @media(max-width:991px) {
      .sidebar { position: fixed; left: -264px; z-index: 60; transition: left .2s ease; }
      .sidebar.open { left: 0; }
      .sidebar-close { display: inline-flex; }
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
        <div class="side-link" data-view="leave">
          <i class="bi bi-file-earmark-text-fill"></i> Leave / Permission
          <span class="badge-pill" id="leaveBadge">{{ $pendingLeaves->count() }}</span>
        </div>
        <div class="side-link" data-view="notifications">
          <i class="bi bi-bell-fill"></i> Notifications
          <span class="badge-pill" id="notifBadge" @if($unreadNotifications === 0) style="display:none;" @endif>{{ $unreadNotifications }}</span>
        </div>
        <div class="side-link" data-view="salary"><i class="bi bi-cash-stack"></i> Salary</div>

        <div class="nav-label">Session</div>
        <a class="side-link" href="{{ route('employee.login') }}"><i class="bi bi-box-arrow-right"></i> Back to Login</a>
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
        <button class="btn btn-ghost d-lg-none" id="burgerBtn" style="border:1px solid var(--line);background:var(--surface);"><i class="bi bi-list"></i></button>
        <div>
          <p class="page-title" id="pageTitle">Dashboard</p>
          <p class="page-sub" id="pageSub">Welcome back, {{ $employee->name }}</p>
        </div>
        <div class="ms-auto d-flex align-items-center gap-3">
          <div class="icon-btn" data-view="notifications"><i class="bi bi-bell"></i>@if($unreadNotifications > 0)<span class="dot" id="topEmpNotifDot"></span>@endif</div>
          <div class="user-menu">
            <button class="user-menu-btn" id="userMenuBtn" type="button">
              <img src="{{ $photo }}" alt="{{ $employee->name }}">
              <span class="user-menu-name">{{ $employee->name }}</span>
              <i class="bi bi-chevron-down small"></i>
            </button>
            <div class="user-menu-dropdown" id="userMenuDropdown">
              <div class="user-menu-head">
                <img src="{{ $photo }}" alt="{{ $employee->name }}">
                <div>
                  <div class="fw-semibold" style="font-size:.86rem;">{{ $employee->name }}</div>
                  <div class="small" style="color:var(--text-soft);">{{ $employee->employee_id }}@if($employee->designation) &middot; {{ $employee->designation }}@endif</div>
                </div>
              </div>
              <a href="{{ route('employee.login') }}" class="user-menu-item logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
          </div>
        </div>
      </div>

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
                      <tr><th>Date</th><th>Check In</th><th>Check Out</th><th>Status</th></tr>
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
                        <tr><td colspan="4" style="color:var(--text-soft);">No attendance records yet.</td></tr>
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
              <img src="{{ $photo }}" class="avatar" alt="{{ $employee->name }}">
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
            <h5 class="mb-3">Profile Details</h5>
            <div class="detail-grid">
              <div class="detail-item"><div class="l">Email</div><div class="v">{{ $employee->email ?? '—' }}</div></div>
              <div class="detail-item"><div class="l">Gender</div><div class="v">{{ $employee->gender ?? '—' }}</div></div>
              <div class="detail-item"><div class="l">Age</div><div class="v">{{ $employee->age ?? '—' }}</div></div>
              <div class="detail-item"><div class="l">Date of Birth</div><div class="v">{{ $employee->dob?->format('d M Y') ?? '—' }}</div></div>
              <div class="detail-item"><div class="l">Join Date</div><div class="v">{{ $employee->join_date?->format('d M Y') ?? '—' }}</div></div>
              <div class="detail-item">
                <div class="l">Shift</div>
                <div class="v">{{ $employee->shift_start && $employee->shift_end ? Carbon\Carbon::parse($employee->shift_start)->format('h:i A') . ' – ' . Carbon\Carbon::parse($employee->shift_end)->format('h:i A') : '—' }}</div>
              </div>
              <div class="detail-item"><div class="l">Salary</div><div class="v">&#8377; {{ number_format($employee->salary ?? 0, 2) }}</div></div>
              <div class="detail-item"><div class="l">Blood Group</div><div class="v">{{ $employee->blood_group ?? '—' }}</div></div>
              <div class="detail-item"><div class="l">Paid Leaves</div><div class="v">{{ $paidLeavesTotal }}</div></div>
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
                  <tr><th>Date</th><th>Check In</th><th>Check Out</th><th>Hours</th><th>Status</th></tr>
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
                    <tr><td colspan="5" style="color:var(--text-soft);">No attendance records found.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ================= LEAVE / PERMISSION VIEW ================= -->
        <section class="view" id="view-leave">
          <div class="row g-3 mb-2">
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--accent-soft);color:var(--accent);"><i class="bi bi-calendar-check"></i></div>
                <div><div class="stat-num">{{ $paidLeavesTotal }}</div><div class="stat-label">Allocated Leaves</div></div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--amber-soft);color:#a06405;"><i class="bi bi-calendar-x"></i></div>
                <div><div class="stat-num">{{ $usedLeaveDays }}</div><div class="stat-label">Used Leaves</div></div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--teal-soft);color:#0a8577;"><i class="bi bi-calendar-minus"></i></div>
                <div><div class="stat-num">{{ $remainingLeaves }}</div><div class="stat-label">Remaining</div></div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stat-card">
                <div class="stat-ic" style="background:var(--coral-soft);color:#c22f42;"><i class="bi bi-hourglass-split"></i></div>
                <div><div class="stat-num">{{ $pendingLeaves->count() }}</div><div class="stat-label">Pending</div></div>
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
                <div class="section-head"><h5>My Requests</h5></div>
                <div class="table-responsive">
                  <table class="tbl">
                    <thead>
                      <tr><th>Type</th><th>Dates</th><th>Reason</th><th>Status</th></tr>
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
                        <tr><td colspan="4" style="color:var(--text-soft);">No requests yet.</td></tr>
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
              <div class="salary-box"><div class="l">Base Salary</div><div class="v">&#8377; {{ number_format($baseSalary, 0) }}</div></div>
              <div class="salary-box"><div class="l">Per Day</div><div class="v">&#8377; {{ number_format($perDay, 0) }}</div></div>
              <div class="salary-box"><div class="l">Eligible Days</div><div class="v">{{ $eligibleDays }}</div></div>
              <div class="salary-box"><div class="l">Days Worked</div><div class="v">{{ number_format($workedDays, 1) }}</div></div>
              <div class="salary-box"><div class="l">Half Days</div><div class="v">{{ $halfDayCount }}</div></div>
              <div class="salary-box"><div class="l">Leave Days</div><div class="v">{{ $approvedLeaveDaysThisMonth }}</div></div>
              <div class="salary-box"><div class="l">Deductible Days</div><div class="v">{{ number_format($deductibleDays, 1) }}</div></div>
              <div class="salary-box" style="border-color:var(--teal);"><div class="l">Final Salary</div><div class="v" style="color:#0a8577;">&#8377; {{ number_format($finalSalary, 2) }}</div></div>
            </div>
            <p class="small" style="color:var(--text-soft);">
              Calculation: Base (&#8377;{{ number_format($baseSalary, 0) }}) &divide; 30 &times; Days Worked ({{ number_format($workedDays, 1) }}) = &#8377;{{ number_format($finalSalary, 2) }}
            </p>
          </div>

          <div class="section-card">
            <div class="section-head"><h5>Salary History</h5></div>
            <div class="table-responsive">
              <table class="tbl">
                <thead>
                  <tr><th>Month</th><th>Base</th><th>Absent</th><th>Leave</th><th>Deductible</th><th>Final</th></tr>
                </thead>
                <tbody>
                  @forelse($salaryRecords as $s)
                    <tr>
                      <td>{{ \Carbon\Carbon::parse($s->month . '-01')->format('F Y') }}</td>
                      <td>&#8377; {{ number_format($s->base_salary, 2) }}</td>
                      <td>{{ $s->absent_days }}</td>
                      <td>{{ $s->leave_days }}</td>
                      <td>{{ $s->deductible_days }}</td>
                      <td class="fw-semibold">&#8377; {{ number_format($s->final_salary, 2) }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="6" style="color:var(--text-soft);">No processed salary records yet.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </section>

      </main>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var viewMeta = {
      dashboard: ['Dashboard', 'Overview of your attendance and leaves'],
      profile: ['Profile', 'Your personal and employment details'],
      attendance: ['Attendance', 'Your attendance history'],
      leave: ['Leave / Permission', 'Apply for leaves and track requests'],
      notifications: ['Notifications', 'Updates and announcements'],
      salary: ['Salary', 'Monthly salary calculation']
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
    }

    $(document).on('click', '.side-link[data-view]', function () {
      switchView($(this).data('view'));
    });

    $('#burgerBtn').on('click', function () {
      $('#sidebar').addClass('open');
      $('#sidebarOverlay').addClass('show');
    });
    $('#sidebarCloseBtn, #sidebarOverlay').on('click', function () {
      $('#sidebar').removeClass('open');
      $('#sidebarOverlay').removeClass('show');
    });

    $('#leaveForm').on('submit', function (e) {
      e.preventDefault();
      var $msg = $('#leaveMsg');
      $msg.removeClass('text-danger text-success').text('');
      $.post('{{ route("employee.leave") }}', $(this).serialize())
        .done(function (resp) {
          $msg.addClass('text-success').text(resp.message);
          setTimeout(function () { location.reload(); }, 1200);
        })
        .fail(function (xhr) {
          var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to submit request.';
          $msg.addClass('text-danger').text(msg);
        });
    });

    $('#empMarkReadBtn').on('click', function () {
      var $btn = $(this);
      $btn.prop('disabled', true);
      $.post('{{ route("employee.notifications.read") }}', { employee_id: '{{ $employee->employee_id }}' })
        .done(function () {
          $('.notif-item').removeClass('unread');
          $('#notifBadge').hide();
          $('#topEmpNotifDot').remove();
          $btn.remove();
        })
        .fail(function () {
          $btn.prop('disabled', false);
          alert('Failed to mark notifications as read.');
        });
    });

    /* ---------------- User menu dropdown ---------------- */
    $('#userMenuBtn').on('click', function (e) {
      e.stopPropagation();
      $('#userMenuDropdown').toggleClass('open');
    });
    $(document).on('click', function (e) {
      if (!$(e.target).closest('.user-menu').length) {
        $('#userMenuDropdown').removeClass('open');
      }
    });
    $(document).on('keydown', function (e) {
      if (e.key === 'Escape') $('#userMenuDropdown').removeClass('open');
    });
  </script>

</body>
</html>
