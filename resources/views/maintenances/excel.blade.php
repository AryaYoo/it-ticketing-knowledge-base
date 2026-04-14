<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table {
            border-collapse: collapse;
        }
        th {
            background-color: #f2f2f2;
            border: 1px solid #000000;
            font-weight: bold;
            text-align: center;
        }
        td {
            border: 1px solid #000000;
            vertical-align: top;
        }
        .header {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th colspan="5" class="header">RIWAYAT MAINTENANCE ASSET</th>
        </tr>
        <tr>
            <th colspan="5">Periode: {{ $startDate ?? 'Awal' }} s/d {{ $endDate ?? \Carbon\Carbon::now()->format('Y-m-d') }}</th>
        </tr>
        <tr>
            <th>ASSET</th>
            <th>TANGGAL</th>
            <th>DESKRIPSI PERBAIKAN</th>
            <th>BIAYA</th>
            <th>PENANGGUNG JAWAB</th>
        </tr>
        @foreach($maintenances as $maintenance)
            <tr>
                <td>{{ $maintenance->asset->display_name }} ({{ ucfirst($maintenance->asset->category) }})</td>
                <td>{{ $maintenance->maintenance_date->format('d/m/Y') }}</td>
                <td>
                    @if($maintenance->title)
                        [{{ $maintenance->title }}] 
                    @endif
                    {{ $maintenance->description }}
                </td>
                <td class="text-right">{{ $maintenance->cost }}</td>
                <td>{{ $maintenance->performedByUser->name ?? 'System' }}</td>
            </tr>
        @endforeach
        @if($maintenances->count() > 0)
            <tr>
                <th colspan="3" class="text-right">TOTAL BIAYA</th>
                <th class="text-right">{{ $maintenances->sum('cost') }}</th>
                <th></th>
            </tr>
        @endif
    </table>
</body>
</html>
