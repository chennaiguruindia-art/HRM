<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reports Login &mdash; Guru Group</title>
  <link rel="icon" type="image/png" href="{{ asset('logo/guru.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
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
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px;
    }

    .card {
      background: rgba(255, 255, 255, .06);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, .1);
      border-radius: 20px;
      padding: 36px 34px;
      width: 100%;
      max-width: 420px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 6px;
    }

    .brand img {
      height: 40px;
      width: auto;
      object-fit: contain;
    }

    .brand .brand-text {
      font-family: 'Sora', sans-serif;
      font-size: 1.3rem;
      font-weight: 800;
    }

    .brand .brand-text span {
      color: #4f5bd5;
    }

    .title {
      font-family: 'Sora', sans-serif;
      font-size: 1.5rem;
      font-weight: 700;
      margin: 18px 0 4px;
    }

    .sub {
      color: #9499b5;
      font-size: .85rem;
      margin-bottom: 24px;
    }

    label {
      display: block;
      font-size: .78rem;
      font-weight: 600;
      letter-spacing: .02em;
      margin-bottom: 6px;
      color: #c6cae0;
    }

    .field {
      margin-bottom: 16px;
    }

    select,
    input {
      width: 100%;
      padding: 11px 13px;
      border-radius: 10px;
      border: 1px solid rgba(255, 255, 255, .14);
      background: rgba(255, 255, 255, .07);
      color: #fff;
      font-size: .9rem;
      font-family: 'Inter', sans-serif;
      outline: none;
      transition: border .2s, background .2s;
    }

    select:focus,
    input:focus {
      border-color: #4f5bd5;
      background: rgba(255, 255, 255, .1);
    }

    select option {
      color: #1c2033;
      background: #fff;
    }

    input::placeholder {
      color: #7c82a0;
    }

    .btn {
      width: 100%;
      margin-top: 6px;
      padding: 12px;
      border: none;
      border-radius: 10px;
      font-size: .95rem;
      font-weight: 700;
      font-family: 'Sora', sans-serif;
      cursor: pointer;
      background: #4f5bd5;
      color: #fff;
      transition: background .2s;
    }

    .btn:hover {
      background: #3f49c2;
    }

    .err {
      background: rgba(239, 93, 111, .14);
      border: 1px solid rgba(239, 93, 111, .4);
      color: #ffb3bd;
      font-size: .8rem;
      border-radius: 10px;
      padding: 10px 12px;
      margin-bottom: 16px;
    }

    .back {
      display: block;
      text-align: center;
      margin-top: 18px;
      color: #9499b5;
      font-size: .82rem;
      text-decoration: none;
    }

    .back:hover {
      color: #fff;
    }
  </style>
</head>

<body>
  <div class="card">
    <div class="brand">
      <img src="{{ asset('logo/guru.png') }}" alt="Guru Group">
      <div class="brand-text">Guru Group <span>Reports</span></div>
    </div>

    <div class="title">Branch Login</div>
    <div class="sub">Sign in to view reports for your branch.</div>

    @if ($errors->any())
      <div class="err">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('reports.login') }}">
      @csrf

      <div class="field">
        <label for="branch_id">Branch</label>
        <select id="branch_id" name="branch_id" required>
          <option value="" disabled {{ old('branch_id') ? '' : 'selected' }}>Select your branch</option>
          <option value="" {{ old('branch_id') === '' ? 'selected' : '' }}>Head Office (All Branches)</option>
          @foreach ($branches as $branch)
            <option value="{{ $branch->id }}" {{ (string) old('branch_id') === (string) $branch->id ? 'selected' : '' }}>
              {{ $branch->name }}{{ $branch->location ? ' — ' . $branch->location : '' }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="e.g. chennaigurugroup@gmail.com" required autofocus autocomplete="username">
      </div>

      <div class="field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" placeholder="Enter password" required autocomplete="current-password">
      </div>

      <button type="submit" class="btn">Sign In</button>
    </form>

    <a class="back" href="{{ url('/') }}">&larr; Back to home</a>
  </div>
</body>

</html>
