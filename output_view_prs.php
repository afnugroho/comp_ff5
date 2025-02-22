<?php
include('koneksi.php');
date_default_timezone_set('Asia/Jakarta');
$time = date('H:i:s');
if ($time >= '22:30:01' || $time <= '06:00:00') {
	$shift = 1;
	if ($time >= '22:30:01' && $time <= "23:59:59") {
		$fdate = date('Y-m-d') . " 22:30:00";
		$tdate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day')) . " 06:00:00";
	} else {
		$fdate = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 day')) . " 22:30:00";
		$tdate = date('Y-m-d') . " 06:00:00";
	}
} elseif ($time >= '06:00:01' && $time <= '14:30:00') {
	$shift = 2;
	$fdate = date('Y-m-d') . " 06:00:00";
	$tdate = date('Y-m-d') . " 14:30:00";
} elseif ($time >= '14:30:01' && $time <= '22:30:00') {
	$shift = 3;
	$fdate = date('Y-m-d') . " 14:30:00";
	$tdate = date('Y-m-d') . " 22:30:00";
}

$query = "select t$" . "id, to_char(t$" . "date+7/24,'YYYY-MM-DD HH24:MI:SS'), t$" . "line, t$" . "shift, t$" . "type, t$" . "ct, t$" . "totalrh from baandb.ttisfc935999 where t$" . "id>=240132 and to_char(t$" . "date+7/24,'YYYY-MM-DD HH24:MI:SS') between '" . $fdate . "' and '" . $tdate . "' and t$" . "baansta='2' and t$" . "line='23' and rownum<=4 order by t$" . "id desc";
$parse = oci_parse($connora, $query);
oci_execute($parse);
while ($fetch = oci_fetch_array($parse, OCI_BOTH)) {
	$id = $fetch[0];
	$date = $fetch[1];
	$line = $fetch[2];
	$shift = $fetch[3];
	$type = $fetch[4];
	$ct = $fetch[5];
	$totalrh = $fetch[6];
	$desc_line = "RC-FA5";
	?>
	<tr style="font-size: 25pt;">
		<td>
			<?php echo $id; ?>
		</td>
		<td>
			<?php echo $date; ?>
		</td>
		<td>
			<?php echo $desc_line; ?>
		</td>
		<td>
			<?php echo $shift; ?>
		</td>
		<td>
			<?php echo $type; ?>
		</td>
		<td>
			<?php echo $ct; ?>
		</td>
		<td>
			<?php echo $totalrh; ?>
		</td>
		<td><img src="qrcode.php?param=<?php echo $id; ?>" alt="QR CODE" class="p-0 clickFunc" data-img="<?php echo $id; ?>"
				style="width: 130px; height: 130px;"></td>
	</tr>
	<span id="updt"></span>
	<?php
}
oci_free_statement($parse);
oci_close($connora);
?>

<script>
	$(document).ready(function () {
		$('.clickFunc').click(function () {
			var id = $(this).attr("data-img");
			Swal.fire({
				title: 'Peringatan!',
				text: "Apakah anda yakin akan Completion Batch ini?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: "output_view_update.php",
						method: "POST",
						data: {
							id: id,
						},
						// dataType: 'json',
						success: function (data) {
							// alert(data);
							if (data == "OK") {
								$(location).attr('href', 'hm://')
								// alert(data);
							} else if (data == "UNKNOWN HOSTNAME") {
								Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Unknown Hostname. Silahkan hubungi MIS (Ext. 212)'
								})
							} else {
								Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Proses Completion Gagal Silahkan hubungi MIS (Ext. 212)'
								})
							}
						}
					})
				}
			})

		});
	});  
</script>