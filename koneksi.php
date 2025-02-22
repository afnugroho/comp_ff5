<?php
  // Oracle initial connection
  $dbora   = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.10.3)(PORT = 1521)))(CONNECT_DATA=(SID=BAAN)))";
  $connora = oci_connect("root","root",$dbora);

  // buat koneksi dengan database mysql
  $dbhost = "192.168.11.161";
  $dbuser = "usrindustry40";
  $dbpass = "passindustry40";
  $dbname = "industry40";
  $db3 = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
  
  //periksa koneksi, tampilkan pesan kesalahan jika gagal
  if(!$db3){
    die ("Koneksi dengan database gagal: ".mysqli_connect_errno().
         " - ".mysqli_connect_error());
  }
  
  $db = mysqli_connect('172.16.16.253','industri40','kayaba','front_fork') or die("Error : ".mysqli_connect_error($db));
?>