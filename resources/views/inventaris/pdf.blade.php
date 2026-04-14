<!DOCTYPE html>
<html>
<head>
    <title>Data Inventaris Asset</title>
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
            color: #004b7e;
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
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-secondary { background-color: #f3f4f6; color: #374151; }
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
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Data Inventaris Asset</h2>
        <p>Sistem Manajemen Inventaris Mas Tolong Mas</p>
    </div>

    <div class="meta-info">
        @if($search)
            <strong>Pencarian:</strong> "{{ $search }}"<br>
        @endif
        <strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25%;">Nama Asset</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 20%;">Lokasi</th>
                <th style="width: 15%;" class="text-center">Status</th>
                <th style="width: 25%;">Maintenance Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $asset)
                <tr>
                    <td>
                        <strong>{{ $asset->display_name }}</strong>
                        @if($asset->ipMapping && $asset->ipMapping->ip_address)
                            <br><small style="color: #666;">IP: {{ $asset->ipMapping->ip_address }}</small>
                        @endif
                    </td>
                    <td>{{ $asset->category === 'computer' ? 'Komputer' : 'Non-Komputer' }}</td>
                    <td>{{ $asset->location ?? '-' }}</td>
                    <td class="text-center">
                        @if($asset->status === 'active')
                            <span class="badge badge-success">Aktif</span>
                        @elseif($asset->status === 'broken')
                            <span class="badge badge-danger">Rusak</span>
                        @else
                            <span class="badge badge-secondary">Dibuang</span>
                        @endif
                    </td>
                    <td>
                        @php $lastMaint = $asset->maintenances()->completed()->latest('maintenance_date')->first(); @endphp
                        @if($lastMaint)
                            {{ $lastMaint->maintenance_date->format('d/m/Y') }}<br>
                            <small style="color: #666;">{{ \Illuminate\Support\Str::limit($lastMaint->description, 40) }}</small>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">Tidak ada data asset yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem Inventaris Mas Tolong Mas
    </div>
</body>
</html>
