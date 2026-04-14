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
    </style>
</head>
<body>
    <table>
        <tr>
            <th colspan="5" class="header">DATA INVENTARIS ASSET</th>
        </tr>
        <tr>
            <th colspan="5">Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</th>
        </tr>
        @if($search)
            <tr>
                <th colspan="5">Pencarian: "{{ $search }}"</th>
            </tr>
        @endif
        <tr>
            <th>NAMA ASSET</th>
            <th>KATEGORI</th>
            <th>LOKASI</th>
            <th>STATUS</th>
            <th>MAINTENANCE TERAKHIR</th>
        </tr>
        @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->display_name }} @if($asset->ipMapping && $asset->ipMapping->ip_address) (IP: {{ $asset->ipMapping->ip_address }}) @endif</td>
                <td>{{ $asset->category === 'computer' ? 'Komputer' : 'Non-Komputer' }}</td>
                <td>{{ $asset->location ?? '-' }}</td>
                <td>
                    @if($asset->status === 'active') Aktif
                    @elseif($asset->status === 'broken') Rusak
                    @else Dibuang
                    @endif
                </td>
                <td>
                    @php $lastMaint = $asset->maintenances()->completed()->latest('maintenance_date')->first(); @endphp
                    @if($lastMaint)
                        {{ $lastMaint->maintenance_date->format('d/m/Y') }} ({{ $lastMaint->description }})
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</body>
</html>
