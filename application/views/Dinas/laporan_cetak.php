<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Panen <?= $tahun ?></title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; font-size: 12px; }
        th { background-color: #f0f0f0; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; font-size: 14px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>DINAS PERTANIAN KABUPATEN</h2>
        <p>Laporan Rekapitulasi Hasil Panen Tahunan</p>
        <p>Periode: <?= $tahun ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Desa</th>
                <th>Luas Lahan (Ha)</th>
                <th>Jumlah Petani</th>
                <th>Padi (Ton)</th>
                <th>Jagung (Ton)</th>
                <th>Kedelai (Ton)</th>
                <th>Produktivitas</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            foreach($rekap as $row): 
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td style="text-align: left;"><?= $row->desa ?></td>
                <td><?= $row->luas_total ?></td>
                <td><?= $row->jumlah_petani ?></td>
                <td><?= $row->padi_ton ?></td>
                <td><?= $row->jagung_ton ?></td>
                <td><?= $row->kedelai_ton ?></td>
                <td><?= $row->produktivitas ?> Ton/Ha</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 50px; float: right; text-align: center; width: 200px;">
        <p>Mengetahui,</p>
        <p>Kepala Dinas</p>
        <br><br><br>
        <p>____________________</p>
    </div>

</body>
</html>