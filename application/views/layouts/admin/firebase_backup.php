<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Firebase Demo</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script defer src="https://use.fontawesome.com/releases/v5.0.0/js/all.js"></script>
    </head>
    <body>
        <section class="section">
            <div class="container">
                <h3 class="title">Kontak by: firebase</h3>
                <div class="row">
                    <div class="col-md-12">
                        <button id="btnAdd" type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal1">Tambah kontak</button>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="opsi_select">Filter Cari</label>
                                    <select id="opsi_select" class="custom-select">
                                        <option value="nama" selected>Nama</option>
                                        <option value="alamat">Alamat</option>
                                        <option value="perusahaan">Perusahaan</option>
                                        <option value="telp">Telp</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cari">Kolom Pencarian</label>
                                    <!-- <input type="text" id="cari" name="cari" onkeyup="cari_data(this.value);" class="form-control" autocomplete="off" placeholder="Kata kunci"> -->
                                    <input type="text" id="cari" name="cari" class="form-control" autocomplete="off" placeholder="Kata kunci">                                    
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Perusahaan</th>
                                    <th>Telp</th>
                                    <th>Pilihan</th>
                                </tr>
                            </thead>
                            <tbody id="data-firebase">
                                <!-- isi data disini -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Tambah kontak</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <!-- hidden input -->
                                <input name="key" id="key" type="hidden" readonly>

                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input name="nama" type="text" class="form-control" id="txtnama" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input name="alamat" type="text" class="form-control" id="txtalamat" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="perusahaan">Perusahaan</label>
                                    <input name="perusahaan" type="text" class="form-control" id="txtperusahaan" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="telp">Telp</label>
                                    <input name="telp" type="number" class="form-control" id="numtelp" autocomplete="off">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button id="btnTutup" type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button id="btnSimpan" type="button" class="btn btn-primary">Simpan</button>
                            <button id="btnUpdate" type="button" class="btn btn-warning d-none">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <!-- yg ini wajib firebase-app dan firebase-database -->
        <script src="https://www.gstatic.com/firebasejs/8.3.1/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.3.1/firebase-database.js"></script>
        <script>
            $(function () {
                dataTable();
            })

            var table = "jrn_contact";
            var config = {
                apiKey: "<?php echo $firebase['apiKey']; ?>",
                authDomain: "<?php echo $firebase['authDomain']; ?>",
                databaseURL: "<?php echo $firebase['databaseURL']; ?>",
                projectId: "<?php echo $firebase['projectId']; ?>",
                storageBucket: "<?php echo $firebase['storageBucket']; ?>",
                messagingSenderId: "<?php echo $firebase['messagingSenderId']; ?>",
                appId: "<?php echo $firebase['appId']; ?>"
            };
            firebase.initializeApp(config);
            var database = firebase.database();

            $(document).on("keyup","#cari",function(e) {
                e.preventDefault();
                e.stopPropagation();
                var terms = this.value
                if(terms.length > 2){
                    $("#data-firebase").empty();
                    var tipe_search = $("#opsi_select").find(":selected").val();

                    database.ref(table).orderByChild(tipe_search).startAt(terms).endAt(terms + "\uf8ff").on('child_added', function (data) {
                        console.log("prosess : added " + terms);
                        // dataTableCreate(data.val().nama, data.val().alamat, data.val().perusahaan, data.val().telp, data.key);
                        dataTable();
                    });                    
                }
            });
            $(document).on("click","#btnAdd",function(e) {
                formEmpty();
            });
            $(document).on("click","#btnSimpan",function(e) {
                // format keyUnique => 1618891006051-2021320-105646
                var date = new Date();
                var keyUnique = date.getTime() + "-"
                        + date.getFullYear() + date.getMonth() + date.getDate() + "-"
                        + date.getHours() + date.getMinutes() + date.getSeconds();
                database.ref(table + '/' + keyUnique).set({
                    nama: $("#txtnama").val(),
                    alamat: $("#txtalamat").val(),
                    perusahaan: $("#txtperusahaan").val(),
                    telp: $("#numtelp").val(),
                    id: keyUnique
                });               
                dataTable();
                if ($("#cari").val().length != 0) {
                    $("#cari").val('');
                }
                $('#modal1').modal('hide');
            });
            $(document).on("click","#btnUpdate",function(e) {
                var key = $(this).attr('data-id');
                database.ref(table + '/' + key).update({
                    nama: $("#txtnama").val(),
                    alamat: $("#txtalamat").val(),
                    perusahaan: $("#txtperusahaan").val(),
                    telp: $("#numtelp").val(),
                    id: key
                });                
                // update_data($("#txtnama").val(), $("#txtalamat").val(), $("#txtperusahaan").val(), $("#numtelp").val(), $("#key").val());
                $('#modal1').modal('hide');
                dataTable();
            });
            $(document).on("click",".btn_edit",function(e) {
                $('#modal1').modal('show');
                $("#btnUpdate").removeClass("d-none");
                $("#btnSimpan").addClass("d-none");
                var key = $(this).attr('data-id');
                // database.ref(table + '/' + key).once("value").then(function (snapshot) {
                database.ref(table + '/' + key).on("value", function(snapshot) {
                    // console.log(snapshot.val());
                    $("#txtnama").val(snapshot.val().nama);
                    $("#txtalamat").val(snapshot.val().alamat);
                    $("#txtperusahaan").val(snapshot.val().perusahaan);
                    $("#numtelp").val(snapshot.val().telp);
                    $("#key").val(key);                    
                }, function (error) {
                    console.log("Error: " + error.code);
                });                      
                // }); 
                $("#btnUpdate").attr('data-id',key);
            });
            $(document).on("click",".btn_delete",function(e) {
                var key = $(this).attr('data-id');
                if (confirm("Yakin ingin menghapus data ini?")) {
                    // delete_data(key);
                    database.ref(table + '/' + key).remove();
                    $("#data-firebase tr[id='" + key + "']").remove();
                }
            });           
            function dataTable(){
                $("#data-firebase").empty();
                var content = '';
                database.ref(table).orderByChild("nama").on('child_added', function (data) {
                    content = "<tr id='" + data.key + "'>";
                    content += "	<td>" + data.val().nama + "</td>";
                    content += "	<td>" + data.val().alamat + "</td>";
                    content += "	<td>" + data.val().perusahaan + "</td>";
                    content += "	<td>" + data.val().telp + "</td>";
                    content += "	<td><button type='button' class='btn btn_edit btn-success m-2' data-id="+data.key+">Edit</button> <button type='button' class='btn btn_delete btn-danger m-2' data-id="+data.key+">Hapus</button></td>";
                    content += "</tr>";               
                    $("#data-firebase").append(content);                     
                });

            }
            function formEmpty(){
                $("#txtnama").val("");
                $("#txtalamat").val("");
                $("#txtperusahaan").val("");
                $("#numtelp").val("");
                $("#key").val("");

                $("#btnUpdate").addClass("d-none");
                $("#btnSimpan").removeClass("d-none");
            }    
            // ibaratkan query ke firebase otomatis mengubah data
            database.ref(table).orderByChild("nama").on('child_changed', function (data) {
                console.log('Table->Data Has Update');                
                dataTable();
                // console.log(data.val().nama);
                // formLive(data.val().key);
            });

            // ibaratkan query ke firebase otomatis menghapus data
            database.ref(table).orderByChild("nama").on('child_removed', function (data) {
                console.log('Table->Data Has Remove');
                dataTable();
            });                            
        </script>
    </body>
</html>