<?php
include('koneksi.php');
error_reporting(E_ALL);
ini_set('display_errors', 'On');
date_default_timezone_set('Asia/Jakarta');
$date = date('Y-m-d H:i:s');
$curr_date = date('Y-m-d H:i:s');
$date_min_7 = date('Y-m-d H:i:s', strtotime($date . '-7 hours'));
$curr_date_min_7hours = date("Y-m-d H:i:s", strtotime("$curr_date -7 hour"));
$id_post = $_POST['id'];

function get_client_ip()
{
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if (getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if (getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if (getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if (getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if (getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';

	return $ipaddress;
}

$ip = get_client_ip();

$selectIP = mysqli_query($db3, "SELECT * FROM ip_hostname WHERE ip_address = '" . $ip . "'");
$ip = mysqli_fetch_array($selectIP);

$hostname = $ip['hostname'];
$id_hostname = substr(str_replace(".kayaba.kyb.co.id", "", trim($hostname)), -3);
// $id_hostname="1190";

$found = 0;
//cek apakah hostname sudah terregistrasi di tccom970
$query3 = "select t$" . "ffn, t$" . "item, t$" . "last$" . "dt, t$" . "last$" . "usr, t$" . "qty, t$" . "printed from baandb.ttccom970999 where t$" . "ffn='" . $id_hostname . "'";
$parse3 = oci_parse($connora, $query3);
oci_execute($parse3);
while ($fetch3 = oci_fetch_array($parse3, OCI_BOTH)) {
	$found++;
	$ffn = $fetch3[0];
	$item = $fetch3[1];
}
oci_free_statement($parse3);

if ($found == 0) {
	//device terdaftar di tccom970
	echo "UNKNOWN HOSTNAME";
} else {
	$query = "select t$" . "id, t$" . "status, t$" . "remark from baandb.ttisfc935999 where t$" . "id='" . $id_post . "'";
	$parse = oci_parse($connora, $query);
	oci_execute($parse);
	$fetch = oci_fetch_array($parse, OCI_BOTH);
	$status = $fetch[1];
	$remark = trim($fetch[2]);
	oci_free_statement($parse);
	
	if ($status == '2') {
		$oci_update = "update baandb.ttisfc935999 set t$" . "status=1, t$" . "host='" . $hostname . "', t$" . "tgltarik=TO_DATE('" . $curr_date_min_7hours . "','YYYY-MM-DD HH24:MI:SS') where t$" . "id='" . $id_post . "'";
	} else {
		$oci_update = "update baandb.ttisfc935999 set t$" . "host='" . $hostname . "' where t$" . "id='" . $id_post . "'";
	}
	$oci_parse = oci_parse($connora, $oci_update);
	$oci_result = oci_execute($oci_parse, OCI_DEFAULT);
	if ($oci_result) {
		oci_commit($connora);
		$oci_update2 = "update baandb.ttccom970999 set t$" . "qty='" . $id_post . "', t$" . "last$" . "dt=TO_DATE('" . $curr_date_min_7hours . "','YYYY-MM-DD HH24:MI:SS') where t$" . "ffn='" . $id_hostname . "'";
		$oci_parse2 = oci_parse($connora, $oci_update2);
		$oci_result2 = oci_execute($oci_parse2, OCI_DEFAULT);
		if ($oci_result2) {
			oci_commit($connora);
			echo "OK";
		} else {
			$e = oci_error($connora); // For oci_parse errors pass the connection handle
			trigger_error(htmlentities($e['message']), E_USER_ERROR);
			echo "NOK1";
		}
	} else {
		$e = oci_error($connora); // For oci_parse errors pass the connection handle
		trigger_error(htmlentities($e['message']), E_USER_ERROR);
		echo "NOK2";
	}
}
oci_close($connora);
?>