<!DOCTYPE html>
<html>
<head>
    <title>Table C Export</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h3>Table C Data Export</h3>
    <table>
        <thead>
            <tr>
                <th>Kode Toko</th>
                <th>Area Sales</th>
                <!-- Add more columns as needed -->
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row->kode_toko }}</td>
                    <td>{{ $row->area_sales }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
