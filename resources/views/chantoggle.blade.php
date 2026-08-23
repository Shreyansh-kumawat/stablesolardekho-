<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Toggle</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f172a; color: #e2e8f0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { background: #1e293b; border-radius: 12px; padding: 2.5rem; text-align: center; max-width: 450px; width: 90%; box-shadow: 0 4px 24px rgba(0,0,0,0.3); }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        .status { font-size: 1.1rem; margin: 1.5rem 0; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; }
        .status.on { background: #991b1b; color: #fca5a5; }
        .status.off { background: #166534; color: #86efac; }
        .info { font-size: 0.85rem; color: #94a3b8; margin-bottom: 1.5rem; line-height: 1.5; }
        button { padding: 0.75rem 2rem; border-radius: 8px; border: none; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.2s; }
        button.turn-on { background: #dc2626; color: #fff; }
        button.turn-on:hover { background: #b91c1c; }
        button.turn-off { background: #16a34a; color: #fff; }
        button.turn-off:hover { background: #15803d; }
    </style>
</head>
<body>
    <div class="card">
        <!-- <h1>Maintenance Mode</h1> -->

        @if($isOn)
            <div class="status on">&nbsp;</div>
            <!-- <p class="info">Home, Shop, Products, Categories, Featured, and Kit pages are showing "Under Maintenance" to visitors right now.</p> -->
            <form method="POST" action="{{ route('maintenance.toggle.action') }}">
                @csrf
                <button type="submit" class="turn-off">&nbsp;</button>
            </form>
        @else
            <div class="status off">&nbsp;</div>
            <!-- <p class="info">All pages are working normally. Click below to put Home, Shop, Products, Categories, Featured, and Kit pages into maintenance mode.</p> -->
            <form method="POST" action="{{ route('maintenance.toggle.action') }}">
                @csrf
                <button type="submit" class="turn-on">&nbsp;</button>
            </form>
        @endif
    </div>
</body>
</html>
