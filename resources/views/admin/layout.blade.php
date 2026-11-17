<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f6f3ee; color: #1c140f; margin: 0; }
        .wrap { max-width: 720px; margin: 40px auto; padding: 0 16px; }
        .card { background: #fff; border: 1px solid #ece5da; border-radius: 12px; padding: 24px; }
        label { display: block; font-size: 13px; font-weight: 600; margin: 14px 0 6px; }
        input, textarea { width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #d9d0c4; border-radius: 8px; font: inherit; }
        button, .btn { display: inline-block; background: #1c140f; color: #fff; border: 0; border-radius: 8px; padding: 10px 16px; text-decoration: none; cursor: pointer; }
        .btn-line { background: transparent; color: #1c140f; border: 1px solid #1c140f; }
        .row { display: flex; gap: 12px; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .flash { background: #e8f6e8; border: 1px solid #b7e0b7; padding: 10px 12px; border-radius: 8px; margin-bottom: 16px; }
        .error { color: #a11; font-size: 13px; margin-top: 4px; }
        .hint { color: #6b6259; font-size: 13px; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="wrap">
        @yield('content')
    </div>
</body>
</html>
