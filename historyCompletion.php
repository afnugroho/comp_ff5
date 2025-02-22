<?php
require_once('koneksi.php');
$query = "select t$" . "id, to_char(t$" . "date+7/24,'YYYY-MM-DD HH24:MI:SS'), t$" . "line, t$" . "shift, t$" . "type, t$" . "ct, t$" . "totalrh, to_char(t$" . "baandt+7/24,'YYYY-MM-DD HH24:MI:SS') from baandb.ttisfc935999 where t$" . "id>'240132' and t$" . "baansta='1' and t$" . "line='23' and rownum<=5 order by t$" . "id desc";
$parse = oci_parse($connora, $query);
oci_execute($parse);
?>
<table class="table table-bordered table-striped border-secondary align-middle text-center" style="font-size: 12pt;">
    <tr>
        <th>ID</th>
        <th>Tgl Completion</th>
        <th>Line</th>
        <th>Shift</th>
        <th>Model</th>
        <th>Qty</th>
    </tr>
    <?php
    while ($fetch = oci_fetch_array($parse, OCI_BOTH)) {
        $id = $fetch[0];
        $date = $fetch[1];
        $line = $fetch[2];
        $shift = $fetch[3];
        $type = $fetch[4];
        $ct = $fetch[5];
        $totalrh = $fetch[6];
        $baandt = $fetch[7];
    ?>
        <tr>
            <td><?= $id; ?></td>
            <td><?= $baandt; ?></td>
            <td>RC-FA5</td>
            <td><?= $shift; ?></td>
            <td><?= $type; ?></td>
            <td><?= $totalrh; ?></td>
        </tr>
    <?php
    }
    ?>
</table>
<?php
oci_free_statement($parse);
oci_close($connora);
