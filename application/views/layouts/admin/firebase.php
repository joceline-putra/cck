<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kitchen</title>
        <link href="<?php echo base_url();?>assets/core/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	    <script defer src="https://use.fontawesome.com/releases/v5.0.0/js/all.js"></script>
    </head>
    <body style="background-color:#eaeaea;">
            <div class="container" style="background-color:white;margin-top:20px;padding-bottom:15px;">
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12" style="padding-top:15px;padding-bottom:15px;padding-left:0px;padding-right:0px;">
                        <div class="col-md-12 col-xs-12 col-sm-12" style="padding-left:0px;padding-right:0px;">
                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12 form-group">
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <label class="form-label">Cari</label>
                                    <input id="cari" name="cari" type="text" value="" class="form-control" placeholder="Pencarian" />
                                </div>
                            </div>         
                            <div class="col-md-3 col-xs-12 col-sm-12">
                                <div class="pull-right">                     
                                    <button id="btn_new" class="btn btn-primary btn-small" type="button" style="display: inline;" data-toggle="modal" data-target="#modal1">
                                        <i class="fas fa-plus"></i>
                                        Buat Baru
                                    </button>
                                </div>
                            </div>
                        </div>                                  
                        <div class="col-md-12 col-xs-12 col-sm-12" style="padding-left: 0;">
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-5 form-group">
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <label class="form-label">Kitchen Side</label>
                                    <select id="filter_kitchen" name="filter_kitchen" class="form-control">
                                        <option value="ALL">All</option>
                                        <option value="Food" selected>Food</option>
                                        <option value="Beverages">Beverages</option>                                        
                                    </select>
                                </div>
                            </div>   
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-5 form-group">
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <label class="form-label">Order Type</label>
                                    <select id="filter_type" name="filter_type" class="form-control">
                                        <option value="ALL">All</option>
                                        <option value="Dine-In">Dine-In</option>
                                        <option value="Take Away">Take Away</option>  
                                        <option value="Delivery">Delivery</option>                                                                                
                                    </select>
                                </div>
                            </div>   
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-5 form-group">
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <label class="form-label">Table</label>
                                    <select id="filter_table" name="filter_table" class="form-control">
                                        <option value="ALL">All</option>
                                        <option value="Meja 001">Meja 001</option>
                                        <option value="Meja 002">Meja 002</option>                                        
                                    </select>
                                </div>
                            </div>                                                          
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-5 form-group">
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <label class="form-label">Status</label>
                                    <select id="filter_status" name="filter_status" class="form-control">
                                        <option value="ALL">All</option>
                                        <option value="Order">Order</option>
                                        <option value="Proses">Proses</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Cancel">Cancel</option>                                        
                                    </select>
                                </div>
                            </div>                             
                        </div>
                    </div>           
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="data-firebase">  
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Tambah Data</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <!-- hidden input -->
                                <input name="key" id="key" type="hidden" readonly>
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <label class="form-label">Kitchen Side</label>
                                    <select id="kitchen" name="kitchen" class="form-control">
                                        <option value="Food">Food</option>
                                        <option value="Beverages">Beverages</option>                                        
                                    </select>
                                </div> 
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <label class="form-label">Order Type</label>
                                    <select id="type" name="type" class="form-control">
                                        <option value="ALL">All</option>
                                        <option value="Dine-In">Dine-In</option>
                                        <option value="Take Away">Take Away</option>  
                                        <option value="Delivery">Delivery</option>                                                                                
                                    </select>
                                </div>  
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <label class="form-label">Table</label>
                                    <select id="table" name="table" class="form-control">
                                        <option value="ALL">All</option>
                                        <option value="Meja 001">Meja 001</option>
                                        <option value="Meja 002">Meja 002</option> 
                                        <option value="Meja 003">Meja 003</option>
                                        <option value="Meja 004">Meja 004</option>                                                                                
                                    </select>
                                </div>                                                                                            
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button id="btnTutup" type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i>Tutup</button>
                            <button id="btn_save" type="button" class="btn btn-primary"><i class="fas fa-disk"></i>Simpan</button>
                            <button id="btn_update" type="button" class="btn btn-warning d-none"><i class="fas fa-disk"></i>Update</button>
                        </div>
                    </div>
                </div>
            </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="<?php echo base_url();?>assets/core/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- yg ini wajib firebase-app dan firebase-database -->
        <script src="https://www.gstatic.com/firebasejs/8.3.1/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.3.1/firebase-database.js"></script>
        <script>
            $(function () {
                loadData();
            })

            var table = "jrn_table";
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

            // $(document).on("keyup","#cari",function(e) {
            //     e.preventDefault();
            //     e.stopPropagation();
            //     var terms = this.value
            //     if(terms.length > 2){
            //         $("#data-firebase").empty();
            //         var tipe_search = $("#opsi_select").find(":selected").val();

            //         database.ref(table).orderByChild(tipe_search).startAt(terms).endAt(terms + "\uf8ff").on('child_added', function (data) {
            //             console.log("prosess : added " + terms);
            //             // dataTableCreate(data.val().nama, data.val().alamat, data.val().perusahaan, data.val().telp, data.key);
            //             // loadData();
            //         });                    
            //     }
            // });
            $(document).on("change","#filter_kitchen",function(e) {
                loadData();
            });
            $(document).on("click","#btn_new",function(e) {
                formEmpty();
            });
            $(document).on("click","#btn_save",function(e) {
                // format keyUnique => 1618891006051-2021320-105646
                var date = new Date();
                var keyUnique = date.getTime() + "-"
                        + date.getFullYear() + date.getMonth() + date.getDate() + "-"
                        + date.getHours() + date.getMinutes() + date.getSeconds();
                database.ref(table + '/' + keyUnique).set({
                    type: $("#type").val(),
                    kitchen: $("#kitchen").val(),
                    table: $("#table").val(),                    
                    id: keyUnique
                });               
                loadData();
                if ($("#cari").val().length != 0) {
                    $("#cari").val('');
                }
                $('#modal1').modal('hide');
            });
            $(document).on("click","#btn_update",function(e) {
                var key = $(this).attr('data-id');
                database.ref(table + '/' + key).update({
                    type: $("#type").val(),
                    kitchen: $("#kitchen").val(),
                    table: $("#table").val(),      
                    id: key
                });                
                // update_data($("#txtnama").val(), $("#txtalamat").val(), $("#txtperusahaan").val(), $("#numtelp").val(), $("#key").val());
                $('#modal1').modal('hide');
                loadData();
            });
            $(document).on("click",".btn_edit",function(e) {
                $('#modal1').modal('show');
                $("#btn_update").removeClass("d-none");
                $("#btn_save").addClass("d-none");
                var key = $(this).attr('data-id');
                // database.ref(table + '/' + key).once("value").then(function (snapshot) {
                database.ref(table + '/' + key).on("value", function(snapshot) {
                    // console.log(snapshot.val());
                    $("#kitchen").val(snapshot.val().kitchen);
                    $("#type").val(snapshot.val().type);
                    $("#table").val(snapshot.val().table);                    
                    $("#key").val(key);                    
                }, function (error) {
                    console.log("Error: " + error.code);
                });                      
                // }); 
                $("#btn_update").attr('data-id',key);
            });
            $(document).on("click",".btn_delete",function(e) {
                var key = $(this).attr('data-id');
                if (confirm("Yakin ingin menghapus data ini?")) {
                    // delete_data(key);
                    database.ref(table + '/' + key).remove();
                    $("#data-firebase tr[id='" + key + "']").remove();
                }
            });           
            function loadData(){
                var filter_kitchen = $("#filter_kitchen").find(":selected").val();
                var filter_type = $("#filter_type").find(":selected").val();
                var filter_table = $("#filter_table").find(":selected").val();                                
                $("#data-firebase").html('');
                $("#data-firebase").empty();
                var dsp = '';
                database.ref(table).orderByChild("id").on('child_added', function (data) {
                    // database.ref(table).orderByValue().on('value', function (data) {   
                        // data.forEach(function(data){
                        //     console.log(data.val().table);
                        // });              
                        console.log(data);
                    // content = "<div class='col-lg-4 col-md-4 col-xs-12' id='" + data.key + "'>";
                    // content += "	<p>" + data.val().nama + "</p>";
                    // content += "	<p>" + data.val().qty + "</p>";
                    // // content += "	<td><button type='button' class='btn btn_edit btn-success m-2' data-id="+data.key+">Edit</button> <button type='button' class='btn btn_delete btn-danger m-2' data-id="+data.key+">Hapus</button></td>";
                    // content += "</div>"; 
                    dsp += '<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">';    
                        dsp += '    <div class="col-md-12 col-sm-12" style="padding:12px 0px;cursor:pointer;border:1px solid white;background-color: #b7e7a1;">';    
                        dsp += '        <div class="col-md-12 col-sm-12" style="text-align:center;">';    
                        dsp += '            <span class="order-ref">';    
                        dsp += '                <b style="font-size:16px;">'+ data.val().table +'</b>';    
                        dsp += '            </span><br>';    
                        dsp += '            <span class="order-ref">';    
                        dsp += '                <b style="font-size:12px;">'+ data.val().type +'</b>';    
                        dsp += '            </span><br>';                                       
                        dsp += '        </div>';    
                        dsp += '        <div class="col-md-12 col-sm-12" style="text-align:left;padding-top:10px;padding-bottom:10px;background-color:#eaeaea;border:1px solid #b7e7a1;">';    
                        dsp += '            <p>1 x Es Teh Manis</p>';    
                        dsp += '            <p>1 x Jus Mangga';    
                        dsp += '                <br><i>esnya sedikit aja</i>';    
                        dsp += '            </p>';    
                        dsp += '            <p style="text-decoration:line-through;">2 x Sop Buah</p>';    
                        dsp += '        </div>';    
                        dsp += '        <div class="col-md-12 col-sm-12" style="text-align:center;">';    
                        dsp += '            <span class="order-ref">';    
                        dsp += '                <b style="font-size:12px;">02:00 Menit</b><br>';  
                        dsp += '                '+ data.val().kitchen +'';  
                        dsp += '            </span><br>';                                        
                        dsp += '        </div>';                                    
                        dsp += '    </div>';    
                    dsp += '</div>';                          
                    $("#data-firebase").append(dsp);                     
                });
            }
            function formEmpty(){
                $("#nama").val("");
                $("#qty").val("");
                $("#key").val("");

                $("#btn_update").addClass("d-none");
                $("#btn_save").removeClass("d-none");
            }    
            // ibaratkan query ke firebase otomatis mengubah data
            database.ref(table).orderByChild("nama").on('child_changed', function (data) {
                console.log('Table->Data Has Update');                
                loadData();
                // console.log(data.val().nama);
                // formLive(data.val().key);
            });

            // ibaratkan query ke firebase otomatis menghapus data
            database.ref(table).orderByChild("nama").on('child_removed', function (data) {
                console.log('Table->Data Has Remove');
                loadData();
            });                            
        </script>
    </body>
</html>