<?php
include('koneksi.php');
$query = mysqli_query($db, "SELECT * FROM ff_assy WHERE Line='RC-FA5' and `Type`<>'' ORDER BY id DESC LIMIT 1");
$row = mysqli_fetch_array($query);
$plan = $row['plan_mesin'];
$actual = $row['cnt_mesin'];
$model = $row['Type'];

// //get last transaction id
// $query1 = "select t$" . "id from baandb.ttisfc935999 where t$" . "line='23' and t$" . "baansta=1 order by t$" . "id desc";
// // $query1 = "select MAX(t$" . "id) from baandb.ttisfc935999 where t$"."id>0 and t$" . "line='23' and t$" . "baansta=1";
// $parse1 = oci_parse($connora, $query1);
// oci_execute($parse1);
// $fetch1 = oci_fetch_array($parse1, OCI_BOTH);
// $lastId = $fetch1[0];
// oci_free_statement($parse1);

$counter = 0;
$time = date('H:i:s');
if($time >= '22:30:01' || $time <= '06:00:00'){
    $fdate = date('Y-m-d'). " 22:30:00";
    $tdate = date('Y-m-d'). " 06:00:00";
} elseif($time >= '06:00:01' && $time <= '14:30:00'){
    $fdate = date('Y-m-d'). " 06:00:00";
    $tdate = date('Y-m-d'). " 14:30:00";
} elseif($time >= '14:30:01' && $time <= '22:30:00'){
    $fdate = date('Y-m-d'). " 14:30:00";
    $tdate = date('Y-m-d'). " 22:30:00";
}
$query4 = mysqli_query($db, "SELECT COUNT(*) FROM ff_assy WHERE Line='RC-FA5' AND lot=0 AND `Type`='" . $model . "' AND okrh='1' AND tanggal >= '".$fdate."' and tanggal < '".$tdate."'");
$row4 = mysqli_fetch_array($query4);
$counter = $row4[0];

//get item by model
$query2 = "select t$" . "item from baandb.ttisfc936999 where t$" . "cwoc='RC-FA5' and t$" . "model='" . $model . "'";
$parse2 = oci_parse($connora, $query2);
oci_execute($parse2);
$fetch2 = oci_fetch_array($parse2, OCI_BOTH);
$item = $fetch2['0'];
oci_free_statement($parse2);
//get lot per pallet
$query3 = "select t$" . "lot from baandb.twhinh839999 where t$" . "item='" . $item . "'";
$parse3 = oci_parse($connora, $query3);
oci_execute($parse3);
$fetch3 = oci_fetch_array($parse3, OCI_BOTH);
$qty_per_lot = $fetch3['0'];
oci_free_statement($parse3);

oci_close($connora);

if ($qty_per_lot - $counter == 0) {
    ?>
    <button type="button" class="btn btn-success btn-lg">
        <?= $counter; ?>
    </button>
    <?php
} else {
    ?>
    <button type="button" class="btn btn-danger btn-lg">
        <?= $counter; ?>
    </button>
    <?php
}
?>
<!-- <button type="button" class="btn btn-info btn-lg">LAST ID :
    <?= $lastId; ?>
</button> -->
<button type="button" class="btn btn-warning btn-lg">MODEL :
    <?= $model; ?>
</button>
<button type="button" class="btn btn-primary btn-lg">PLAN :
    <?= number_format($plan, 0); ?>
</button>
<button type="button" class="btn btn-success btn-lg">OUTPUT :
    <?= number_format($actual, 0); ?>
</button>