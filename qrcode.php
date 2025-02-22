<?php
header('Content-Type: image/png');
include('../phpqrcode/qrlib.php');

$param = $_GET['param'];

QRcode::png($param);
?>