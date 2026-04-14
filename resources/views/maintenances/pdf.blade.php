<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Maintenance Asset</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #444;
            padding-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            color: #1a5632;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
            text-align: left;
            padding: 10px 8px;
            border: 1px solid #ddd;
            text-transform: uppercase;
            font-size: 10px;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #777;
        }
        .meta-info {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            background: #eee;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Riwayat Maintenance Asset</h2>
        <p>Laporan Perawatan dan Perbaikan Inventaris</p>
    </div>

    <div class="meta-info">
        <strong>Periode:</strong> {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Awal' }} s/d {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : \Carbon\Carbon::now()->format('d/m/Y') }}<br>
        <strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Asset</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 38%;">Deskripsi Perbaikan</th>
                <th style="width: 15%;" class="text-right">Biaya</th>
                <th style="width: 15%;">Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
            @if($maintenances->count() > 0)
                @foreach($maintenances as $maintenance)
                    <tr>
                        <td>
                            <strong>{{ $maintenance->asset->display_name }}</strong><br>
                            <span style="color: #777; font-size: 9px;">{{ ucfirst($maintenance->asset->category) }}</span>
                        </td>
                        <td>{{ $maintenance->maintenance_date->format('d/m/Y') }}</td>
                        <td>
                            @if($maintenance->title)
                                <div style="font-weight: bold; margin-bottom: 3px;">{{ $maintenance->title }}</div>
                            @endif
                            {{ $maintenance->description }}
                        </td>
                        <td class="text-right">Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</td>
                        <td>{{ $maintenance->performedByUser->name ?? 'System' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data maintenance pada periode ini.</td>
                </tr>
            @endif
        </tbody>
        @if($maintenances->count() > 0)
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">Total Biaya</th>
                    <th class="text-right">Rp {{ number_format($maintenances->sum('cost'), 0, ',', '.') }}</th>
                    <th></th>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem Inventaris Mas Tolong Mas
    </div>
</body>
</html>
