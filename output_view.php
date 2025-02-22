<?php
include('koneksi.php');
date_default_timezone_set('Asia/Jakarta');
$date = date('d-m-Y');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Output Machine Siap Completion</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/sweetalert2/package/dist/sweetalert2.css">
    <link rel="stylesheet" href="style.css">
</head>

<body onload="startTime()">
    <div class="header mb-3" id="myHeader">
        <table width="100%" class="table align-middle" border=1>
            <tr>
                <td width="10%"><img src="../assets/img/KYB-Kayaba.png" alt="Logo KYB" class="gambar"></td>
                <td width="20%" align="center;">
                    <h5 class="text-light"><b>OUTPUT MACHINE SIAP COMPLETION - FF5</b></h5>
                </td>
                <td width="60%" align="right">
                    <div id="planvsactual">

                    </div>
                </td>
                <td width="10%"><button type="button" class="btn btn-danger btn-lg" onclick="showHistory();">History</button></td>
            </tr>
        </table>
    </div>
    <div class="container-fluid">
        <div class="table-responsive">
            <table class="table table-bordered table-striped border-secondary align-middle text-center" style="font-size: 12pt;">
                <thead class="text-dark" style="background-color: rgb(255, 215, 85);">
                    <tr>
                        <th>ID</th>
                        <th>TANGGAL</th>
                        <th>LINE</th>
                        <th>SHIFT</th>
                        <th>MODEL</th>
                        <th>CYCLE TIME</th>
                        <th>QTY</th>
                        <th>COMPLETION</th>
                    </tr>
                </thead>
                <tbody style="background-color: rgb(255, 249, 233);" id="isi">

                </tbody>
            </table>
        </div>
    </div>
    <div id="plc"></div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">History Completion</h5>
                </div>
                <div class="modal-body" id="modalContent">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/jQuery/jquery-3.6.0.js"></script>
    <script src="../assets/sweetalert2/package/dist/sweetalert2.all.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.bundle.js"></script>
    <script>
        function showHistory() {
            $('#exampleModalCenter').modal('show');

            $("#exampleModalCenter").on('shown.bs.modal', function() {
                // AJAX POST
                $.ajax({
                    type: 'POST',
                    url: 'historyCompletion.php', // Replace 'login.php' with your login processing PHP script
                    data: {
                        line: "21"
                    },
                    success: function(response) {
                        //success message
                        // alert(response);
                        document.getElementById("modalContent").innerHTML = response;
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error('Error:', error);
                    }
                });
            });

        }

        function closeModal() {
            $('#exampleModalCenter').modal('hide');
        }
    </script>
    <script>
        var type = '';
        $('#selectModel').change(function() {
            type = $(this).val();
        });

        function loadNum() {
            $('#isi').load('output_view_prs.php?type=' + type);
            setTimeout(loadNum, 30000);
        }

        function loadPlanvsActual() {
            $('#planvsactual').load('view_planvsactual.php');
            setTimeout(loadPlanvsActual, 30000);
        }
        loadNum();
        loadPlanvsActual();
    </script>
    <script>
        function startTime() {
            const today = new Date();
            let h = today.getHours();
            let m = today.getMinutes();
            let s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('txt').innerHTML = h + ":" + m + ":" + s;
            setTimeout(startTime, 1000);
        }

        function checkTime(i) {
            if (i < 10) {
                i = "0" + i
            };
            return i;
        }
    </script>
</body>

</html>
<?php
oci_close($connora);
?>