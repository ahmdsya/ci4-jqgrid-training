<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>

	<style>
		.zui-table {
			border: solid 1px #DDEEEE;
			border-collapse: collapse;
			border-spacing: 0;
			font: normal 13px Arial, sans-serif;
			margin-bottom: 50px;
			/* width: 100%; */
		}

		.zui-table thead th {
			background-color: #DDEFEF;
			border: solid 1px #DDEEEE;
			color: #336B6B;
			padding: 10px;
			text-align: center;
			text-shadow: 1px 1px 1px #fff;
		}

		.zui-table tbody td {
			border: solid 1px #DDEEEE;
			color: #333;
			padding: 10px;
			text-shadow: 1px 1px 1px #fff;
            text-align: center;
		}

	</style>
</head>

<body>

	<?php
	    header("Content-type: application/vnd-ms-excel");
	    header("Content-Disposition: attachment; filename=Data-Pelanggan-Penjualan.xls");
	?>

	<h1 style="text-align: center;">Laporan Data Pelanggan - Pesanan</h1>

	<?php foreach($dataPelanggan as $row): ?>
	<table style="border: none; background-color: #DDEFEF; float:left; display: inline-block;">
        <tr>
            <td>Tgl Pesanan : <?= $row->tgl_pesanan ?></td>
        </tr>
		<tr>
			<td>Nama : <?= $row->nama ?></td>
		</tr>
		<tr>
			<td>NIK : <?= $row->nik ?></td>
		</tr>
		<tr>
			<td>HP : <?= $row->hp ?></td>
		</tr>
		<tr>
			<td>Email : <?= $row->email ?></td>
		</tr>
		<tr>
			<td>Alamat :<?= $row->alamat ?></td>
		</tr>
	</table>

	<table class="zui-table" style="display: inline-block;">
		<thead>
			<tr>
				<th></th>
				<th>NO</th>
				<th>Nama Produk</th>
				<th>Harga</th>
				<th>Kuntitas</th>
				<th>Total Harga</th>
			</tr>
		</thead>
		<tbody>
            <?php $total = 0; ?>
			<?php foreach($row->relations as $index => $relation): ?>
            <?php $total += $relation->total_harga ?>
			<tr>
				<td></td>
				<td><?= $index+1 ?></td>
				<td><?= $relation->nama_produk ?></td>
				<td>Rp. <?= number_format($relation->harga) ?></td>
				<td><?= $relation->qty ?></td>
				<td>Rp. <?= number_format($relation->total_harga) ?></td>
			</tr>
			<?php endforeach; ?>
            <tr>
				<td></td>
                <td colspan="4"><b>Total</b></td>
                <td><b>Rp. <?= number_format($total) ?></b></td>
            </tr>
		</tbody>
	</table>

	<br>

	<?php endforeach; ?>

</body>

</html>
