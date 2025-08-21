<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login — SawaedUAE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    html,body{height:100%;margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#f7f8fb;color:#111}
    .wrap{min-height:100%;display:grid;place-items:center;padding:24px}
    .card{width:100%;max-width:420px;background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.08);padding:28px}
    h1{margin:0 0 18px;font-size:26px}
    label{display:block;font-size:14px;margin:10px 0 6px}
    input[type=email],input[type=password]{width:100%;padding:12px 14px;border:1px solid #d7dbe3;border-radius:10px;font-size:15px;outline:none}
    input:focus{border-color:#6c8dff;box-shadow:0 0 0 3px rgba(108,141,255,.15)}
    .row{display:flex;align-items:center;justify-content:space-between;margin:12px 0}
    .btn{width:100%;padding:12px 14px;border:none;border-radius:10px;background:#4c6fff;color:#fff;font-weight:600;font-size:15px;cursor:pointer}
    .btn:hover{filter:brightness(.98)}
    .muted{font-size:12px;color:#6b7280;text-align:center;margin-top:10px}
    .err{background:#fff3f3;border:1px solid #ffd0d0;color:#8b1c1c;padding:10px 12px;border-radius:10px;font-size:14px;margin-bottom:10px}
  </style>
</head>
<body>
<div class="wrap">
  <div class="card" role="dialog" aria-labelledby="ttl">
    <h1 id="ttl">Admin Login</h1>

    @if ($errors->any())
      <div class="err">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}" autocomplete="on">
      @csrf
      <label for="email">Email</label>
      <input id="email" name="email" type="email" required autofocus value="{{ old('email') }}"/>

      <label for="password">Password</label>
      <input id="password" name="password" type="password" required/>

      <div class="row">
        <label style="display:flex;gap:.5rem;align-items:center;font-size:13px">
          <input type="checkbox" name="remember" value="1" style="width:16px;height:16px"> Remember me
        </label>
        <a href="{{ url('/password/reset') }}" style="font-size:13px;text-decoration:none;color:#4c6fff">Forgot?</a>
      </div>

      <button class="btn" type="submit">Sign in</button>
      <div class="muted" style="margin-top:12px;">
        <a href="{{ url('/') }}" style="text-decoration:none;color:#6b7280">← Back to site</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
