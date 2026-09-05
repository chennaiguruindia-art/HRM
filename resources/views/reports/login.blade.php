<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Branch & Reports Login — Guru Group</title>
  <link rel="icon" type="image/png" href="{{ asset('logo/guru.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
      --border-focus: #4338ca;
      --indigo: #4338ca;
      --indigo-hover: #3730a3;
      --indigo-light: #eef2ff;
      --rose: #e11d48;
      --rose-light: #fff1f2;
      --shadow-card: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
      --radius: 16px;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      background-color: var(--bg);
      background-image: 
        radial-gradient(at 100% 0%, rgba(67, 56, 202, 0.06) 0px, transparent 50%),
        radial-gradient(at 0% 100%, rgba(13, 148, 136, 0.05) 0px, transparent 50%);
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
      padding: 38px 32px;
    }

    /* Brand Header */
    .brand-section {
      text-align: center;
      margin-bottom: 24px;
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
      color: var(--indigo);
    }

    .brand-subtitle {
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-top: 2px;
    }

    /* Error Alert */
    .alert-error {
      background: var(--rose-light);
      border: 1px solid rgba(225, 29, 72, 0.3);
      border-radius: 10px;
      padding: 12px 14px;
      margin-bottom: 20px;
      font-size: 0.82rem;
      color: #be123c;
    }

    .alert-error ul {
      margin: 0;
      padding-left: 18px;
    }

    /* Form Styles */
    .form-group {
      margin-bottom: 18px;
    }

    .form-label {
      display: block;
      font-size: 0.82rem;
      font-weight: 600;
      color: var(--text-main);
      margin-bottom: 6px;
    }

    .input-box {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-box svg.input-icon {
      position: absolute;
      left: 14px;
      width: 17px;
      height: 17px;
      color: var(--text-light);
      pointer-events: none;
    }

    .form-control {
      width: 100%;
      padding: 11px 14px 11px 40px;
      font-size: 0.92rem;
      font-family: inherit;
      color: var(--text-main);
      background: #ffffff;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      outline: none;
      transition: all 0.2s ease;
    }

    .form-control:focus {
      border-color: var(--border-focus);
      box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.12);
    }

    .form-control::placeholder {
      color: var(--text-light);
    }

    select.form-control {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 12px center;
      background-size: 16px;
      padding-right: 36px;
    }

    /* Password Toggle */
    .toggle-pwd {
      position: absolute;
      right: 12px;
      background: none;
      border: none;
      color: var(--text-light);
      cursor: pointer;
      padding: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .toggle-pwd:hover {
      color: var(--text-main);
    }

    .toggle-pwd svg {
      width: 18px;
      height: 18px;
    }

    /* Submit Button */
    .btn-submit {
      width: 100%;
      margin-top: 8px;
      padding: 12px 18px;
      font-size: 0.95rem;
      font-weight: 700;
      font-family: inherit;
      color: #ffffff;
      background: var(--indigo);
      border: none;
      border-radius: 10px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background 0.2s ease, transform 0.1s ease;
    }

    .btn-submit:hover {
      background: var(--indigo-hover);
    }

    .btn-submit:active {
      transform: scale(0.99);
    }

    .btn-submit svg {
      width: 17px;
      height: 17px;
    }

    /* Back Link */
    .back-nav {
      margin-top: 22px;
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

    @media (max-width: 480px) {
      .card {
        padding: 30px 20px;
      }
    }
  </style>
</head>

<body>

  <div class="portal-container">

    <div class="card">
      
      <!-- Brand Section -->
      <div class="brand-section">
        <img src="{{ asset('logo/guru.png') }}" class="brand-logo" alt="Guru Group">
        <h1 class="brand-title">Guru Group <span>Reports</span></h1>
        <div class="brand-subtitle">Branch Management Login</div>
      </div>

      <!-- Errors -->
      @if ($errors->any())
        <div class="alert-error">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Form -->
      <form method="POST" action="{{ route('reports.login') }}">
        @csrf

        <!-- Branch Select -->
        <div class="form-group">
          <label for="branch_id" class="form-label">Branch Location</label>
          <div class="input-box">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            <select id="branch_id" name="branch_id" class="form-control" required>
              <option value="" disabled {{ old('branch_id') ? '' : 'selected' }}>Select your branch</option>
              <option value="" {{ old('branch_id') === '' ? 'selected' : '' }}>Head Office (All Branches)</option>
              @foreach ($branches as $branch)
                <option value="{{ $branch->id }}" {{ (string) old('branch_id') === (string) $branch->id ? 'selected' : '' }}>
                  {{ $branch->name }}{{ $branch->location ? ' — ' . $branch->location : '' }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Email Address -->
        <div class="form-group">
          <label for="email" class="form-label">Work Email</label>
          <div class="input-box">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="name@gurugroup.com" required autofocus autocomplete="username">
          </div>
        </div>

        <!-- Password -->
        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <div class="input-box">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <input id="password" type="password" name="password" class="form-control" placeholder="Enter your password" required autocomplete="current-password">
            <button type="button" class="toggle-pwd" onclick="togglePassword()" title="Toggle visibility">
              <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
            </button>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-submit">
          Sign In to Reports
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </button>
      </form>

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

  <script>
    function togglePassword() {
      const pwd = document.getElementById('password');
      const eye = document.getElementById('eyeIcon');
      if (pwd.type === 'password') {
        pwd.type = 'text';
        eye.innerHTML = `
          <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
          <line x1="1" y1="1" x2="23" y2="23"></line>
        `;
      } else {
        pwd.type = 'password';
        eye.innerHTML = `
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
          <circle cx="12" cy="12" r="3"></circle>
        `;
      }
    }
  </script>
</body>

</html>
