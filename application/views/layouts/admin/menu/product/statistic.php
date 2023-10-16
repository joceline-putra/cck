<style>
    .scroll { 
        margin-top:4px;
        margin-bottom: 8px;
        margin-left:4px;
        margin-right: 4px; 
        padding:4px; 
        /*background-color: green; */
        width: 100%; 
        height: 400px; 
        overflow-x: hidden; 
        overflow-y: auto; 
        text-align:justify; 
    } 
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                        <div class="grid simple">
                            <div class="hidden grid-title">
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="#grid-config" data-toggle="modal" class="config"></a>
                                    <a href="javascript:;" class="reload"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                            </div>
                            <div class="grid-body">                   
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                        <div class="col-md-4 col-xs-12 col-sm-12" style="padding-left: 0;">
                                            <h5><b><i class="fas fa-table"></i> Data <?php echo $title; ?></b></h5>                      
                                        </div>
                                        <div class="col-md-8 col-xs-12 col-sm-12 padding-remove-side">
                                            <div class="col-md-3 col-xs-4 form-group">
                                                <label class="form-label">Periode Awal</label>
                                                <div class="col-md-12 col-sm-12 padding-remove-side">
                                                    <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                        <input name="start" id="start" type="text" 
                                                               class="form-control input-sm" readonly="true"
                                                               value="<?php echo $first_date; ?>">
                                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>  
                                            </div>

                                            <div class="col-md-3 col-xs-4 form-group">
                                                <label class="form-label">Periode Akhir</label>
                                                <div class="col-md-12 col-sm-12 padding-remove-side">
                                                    <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                        <input name="end" id="end" type="text" 
                                                               class="form-control input-sm" readonly="true"
                                                               value="<?php echo $end_date; ?>">
                                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>  
                                            </div> 

                                            <div class="col-md-3 col-xs-4 form-group">
                                                <label class="form-label">Action</label>
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <!--
                                                    <button id="btn-preview" class="btn btn-primary" type="button" style="padding:5px 12px;"
                                                      data-action="1" data-request="1">
                                                      <i class="fa fa-list-alt"></i>&nbsp;&nbsp;Print & Preview
                                                    </button>
                                                    -->
                                                    <div class="btn-group"> 
                                                        <a class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 
                                                            <i class="fas fa-print"></i>&nbsp;&nbsp;Print&nbsp;&nbsp;<span class="fa fa-angle-down"></span> 
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a href="#" class="btn-print-all" data-action="1" data-request="report_pembelian">
                                                                    <i class="fas fa-file-pdf"></i>&nbsp;&nbsp;PDF
                                                                </a>
                                                            </li>
                                                            <li class="hide">
                                                                <a href="#" class="btn-print-all" data-action="2" data-request="2">
                                                                    <i class="fas fa-file-excel"></i>&nbsp;&nbsp;Excel
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-3 col-xs-4 form-group">
                                                <label class="form-label">Data</label>
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <!--
                                                    <button id="btn-preview" class="btn btn-primary" type="button" style="padding:5px 12px;"
                                                      data-action="1" data-request="1">
                                                      <i class="fa fa-list-alt"></i>&nbsp;&nbsp;Print & Preview
                                                    </button>
                                                    -->
                                                    <div class="btn-group"> 
                                                        <a class="btn btn-success btn-small dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 
                                                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah Produk&nbsp;&nbsp;<span class="fa fa-angle-down"></span> 
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a href="<?php echo base_url('product/barang/new'); ?>" class="btn-default">
                                                                    <i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah Barang
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo base_url('product/jasa/new'); ?>" class="btn-default">
                                                                    <i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah Jasa
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo base_url('konfigurasi/lokasi/new'); ?>" class="btn-default">
                                                                    <i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah Jasa
                                                                </a>
                                                            </li>                              
                                                            <li>
                                                                <a href="<?php echo base_url('opname/barang/new'); ?>" class="btn-default">
                                                                    <i class="fas fa-plus"></i>&nbsp;&nbsp;Penyesuaian Stok
                                                                </a>
                                                            </li>                           
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>                           
                                        </div>    

                                    </div>              
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                        <div class="grid simple">
                            <div class="grid-body">                   
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                        <div class="col-md-4 col-xs-12 col-sm-12" style="padding-left: 0;">               
                                            <div class="grid simple">
                                                <div class="grid-body">                   
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                                            <h5><b><i class="fas fa-arrow-circle-down fa-2x"></i> Total Pemasukan Kas/Bank</b></h5>      
                                                            <h3>Rp 20,000</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>
                                        <div class="col-md-4 col-xs-12 col-sm-12" style="padding-left: 0;">                      
                                            <div class="grid simple">
                                                <div class="grid-body">                   
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                                            <h5><b><i class="fas fa-arrow-circle-up fa-2x"></i> Total Pengeluaran Kas/Bank</b></h5>
                                                            <h3>Rp 15,000</h3>                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                       
                                        </div>
                                        <div class="col-md-4 col-xs-12 col-sm-12" style="padding-left: 0;">
                                            <div class="grid simple">
                                                <div class="grid-body">                   
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                                            <h5><b><i class="fas fa-money-check fa-2x"></i> Total Biaya Keluar</b></h5>                              
                                                            <h3>Rp 10,000</h3>                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>   
                                        </div>                    
                                    </div>              
                                </div>
                            </div>
                        </div>
                    </div>          
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                        <div class="grid simple">
                            <div class="grid-body">                   
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                        <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">               
                                            <div class="grid simple">
                                                <div class="grid-body">                   
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                                            <h5><b><i class="fas fa-table"></i> Total Pemasukan</b></h5>     
                                                            <canvas id="chart-one"></canvas> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">                      
                                            <div class="grid simple">
                                                <div class="grid-body">                   
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                                            <h5><b><i class="fas fa-table"></i> Total Pengeluaran</b></h5>
                                                            <canvas id="chart-two"></canvas>                               
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                       
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">                      
                                            <div class="grid simple">
                                                <div class="grid-body">                   
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                                            <h5><b><i class="fas fa-table"></i> Total Keseluruhan</b></h5>
                                                            <canvas id="chart-three"></canvas>                               
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                       
                                        </div>                                                
                                    </div>              
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-trans-diskon" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f94545;">
                <h4 style="color:white;">Pasang Diskon</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <style>
                            .btn-diskon{
                                cursor:pointer;
                                padding-bottom:20px;
                            }
                            .btn-diskon > div{
                                background-color:#f94545;
                                height: 100px;
                            }
                            .btn-diskon:hover > div{
                                background-color:#616161;
                                height: 100px;
                            }              
                            .btn-diskon > div > h4{
                                padding-top:28px;
                                font-size:42px;font-weight: 800;color:white;text-align:center;
                            }
                        </style>
                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="0" style="">
                            <div class="col-md-12 col-xs-12 col-sm-12" style="">
                                <h4 style="">Reset</h4>
                            </div>
                        </div> 
                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="5" style="">
                            <div class="col-md-12 col-xs-12 col-sm-12" style="">
                                <h4 style="">5%</h4>
                            </div>
                        </div> 

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="10">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>10%</h4>
                            </div>
                        </div> 

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="15">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>15%</h4>
                            </div>
                        </div> 

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="20">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>20%</h4>
                            </div>
                        </div>    

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="25">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>25%</h4>
                            </div>
                        </div>  

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="50">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>50%</h4>
                            </div>
                        </div>  

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="75">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>75%</h4>
                            </div>
                        </div>  

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="100">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>100%</h4>
                            </div>
                        </div>                                                 

                    </div>
                </div>                                         
            </div>
            <div class="hide modal-footer">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-trans-save" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #05534d;">
                <h4 style="color:white;">Sukses Menyimpan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <p></p>
                        <p class="text-center">
                            <i class="fas fa-print fa-4x"></i>
                        </p>
                    </div>
                    <div class="col-md-9 col-xs-12">
                        <p>
                            Berhasil menyimpan transaksi pembelian, silahkan melanjutkan
                        </p>
                        <p>
                            <strong>Transaksi sudah disimpan ke dalam database
                                <u>one day</u>.
                            </strong>
                        </p>
                        <h2>
                            <span class="badge">v52gs1</span>
                        </h2>
                    </div>
                </div>                                         
            </div>
            <div class="modal-footer flex-center">
                <a href="#" id="" class="btn-print btn btn-success" data-id="">
                    <i class="fas fa-print white"></i> Print
                </a>
                <a type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Tutup</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-trans-note" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #05534d;">
                <h4 style="color:white;">Tambahkan Catatan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <p></p>
                        <p class="text-center">
                            <i class="fas fa-pencil fa-4x"></i>
                        </p>
                    </div>
                    <div class="col-md-9 col-xs-12">
                        <p id="trans-item-label">
                        </p>
                        <input id="trans-item-note" name="trans-item-note" type="text" value="">
                    </div>
                </div>                                         
            </div>
            <div class="modal-footer flex-center">
                <button id="btn-save-item-note" class="btn btn-success" data-id="">
                    <i class="fas fa-print white"></i> Update
                </button>
                <button type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Tutup</button>        
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>