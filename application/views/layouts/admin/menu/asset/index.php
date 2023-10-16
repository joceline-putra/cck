<style>
    *,*:before,*:after{
        box-sizing:border-box;
        margin:0;
        padding:0;
        /*transition*/
        -webkit-transition:.25s ease-in-out;
        -moz-transition:.25s ease-in-out;
        -o-transition:.25s ease-in-out;
        transition:.25s ease-in-out;
        outline:none;
        /*font-family:Helvetica Neue,helvetica,arial,verdana,sans-serif;*/
    }

    .toggles{
        width:48px;
        /*margin-left: 25px;*/
        /*margin:50px auto;*/
        /*text-align:center;*/
    }
    .ios-toggle,.ios-toggle:active{
        position:absolute;
        top:-5000px;
        height:0;
        width:0;
        opacity:0;
        border:none;
        outline:none;
    }
    .checkbox-label{
        display:block;
        position:relative;
        padding:10px;
        margin-bottom:0px!important;
        font-size:12px;
        line-height:16px;
        width:100%;
        height:24px;
        /*border-radius*/
        -webkit-border-radius:18px;
        -moz-border-radius:18px;
        border-radius:18px;
        /*background:#f8f8f8;*/
        background: #f46767;
        cursor:pointer;
    }
    .checkbox-label:before{
        content:'';
        display:block;
        position:absolute;
        z-index:1;
        line-height:34px;
        text-indent:40px;
        height:24px;
        width:24px;
        /*border-radius*/
        -webkit-border-radius:100%;
        -moz-border-radius:100%;
        border-radius:100%;
        top:0px;
        left:0px;
        right:auto;
        background:white;
        /*box-shadow*/
        -webkit-box-shadow:0 3px 3px rgba(0,0,0,.2),0 0 0 2px #dddddd;
        -moz-box-shadow:0 3px 3px rgba(0,0,0,.2),0 0 0 2px #dddddd;
        box-shadow:0 3px 3px rgba(0,0,0,.2),0 0 0 2px #dddddd;
    }
    .checkbox-label:after{
        /*content:attr(data-off);*/
        display:block;
        position:absolute;
        z-index:0;
        top:0;
        left:-300px;
        padding:10px;
        height:100%;
        width:300px;
        text-align:right;
        color:#bfbfbf;
        white-space:nowrap;
    }
    .ios-toggle:checked + .checkbox-label{
        /*box-shadow*/
        -webkit-box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
        -moz-box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
        box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
    }
    .ios-toggle:checked + .checkbox-label:before{
        left:calc(100% - 24px);
        /*box-shadow*/
        -webkit-box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
        -moz-box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
        box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
    }
    .ios-toggle:checked + .checkbox-label:after{
        /*content:attr(data-on);*/
        left:60px;
        width:36px;
    }

    /* GREEN CHECKBOX
    #checkbox1 + .checkbox-label{
      -webkit-box-shadow:inset 0 0 0 0px rgba(19,191,17,1),0 0 0 2px #dddddd;
         -moz-box-shadow:inset 0 0 0 0px rgba(19,191,17,1),0 0 0 2px #dddddd;
              box-shadow:inset 0 0 0 0px rgba(19,191,17,1),0 0 0 2px #dddddd;
    }
    #checkbox1:checked + .checkbox-label{
      -webkit-box-shadow:inset 0 0 0 18px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
         -moz-box-shadow:inset 0 0 0 18px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
              box-shadow:inset 0 0 0 18px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
    }
    #checkbox1:checked + .checkbox-label:after{
      color:rgba(19,191,17,1);
    } */

    /* RED CHECKBOX
    #checkbox2 + .checkbox-label{
      -webkit-box-shadow:inset 0 0 0 0px #f35f42,0 0 0 2px #dddddd;
         -moz-box-shadow:inset 0 0 0 0px #f35f42,0 0 0 2px #dddddd;
              box-shadow:inset 0 0 0 0px #f35f42,0 0 0 2px #dddddd;
    }
    #checkbox2:checked + .checkbox-label{
      -webkit-box-shadow:inset 0 0 0 20px #f35f42,0 0 0 2px #f35f42;
         -moz-box-shadow:inset 0 0 0 20px #f35f42,0 0 0 2px #f35f42;
              box-shadow:inset 0 0 0 20px #f35f42,0 0 0 2px #f35f42;
    }
    #checkbox2:checked + .checkbox-label:after{
      color:#f35f42;
    } */

    /* BLUE CHECKBOX
    #checkbox3 + .checkbox-label{
      -webkit-box-shadow:inset 0 0 0 0px #1fc1c8,0 0 0 2px #dddddd;
         -moz-box-shadow:inset 0 0 0 0px #1fc1c8,0 0 0 2px #dddddd;
              box-shadow:inset 0 0 0 0px #1fc1c8,0 0 0 2px #dddddd;
    }
    #checkbox3:checked + .checkbox-label{
      -webkit-box-shadow:inset 0 0 0 20px #1fc1c8,0 0 0 2px #1fc1c8;
         -moz-box-shadow:inset 0 0 0 20px #1fc1c8,0 0 0 2px #1fc1c8;
              box-shadow:inset 0 0 0 20px #1fc1c8,0 0 0 2px #1fc1c8;
    }
    #checkbox3:checked + .checkbox-label:after{
      color:#1fc1c8;
    } */
</style>  
<div class="row">
    <div class="col-md-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div id="div-form-trans" style="display: none;" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                        <form id="form-trans" name="form-trans" method="" action="">
                            <div class="grid simple">
                                <div class="grid-body">
                                    <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                        <h5><b>Detail <?php echo $title; ?></b></h5>
                                    </div>
                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                        <div class="pull-right">
                                            <button id="btn-help" onClick="" class="hide btn btn-default btn-small" type="button"
                                                    style="display: none;">
                                                <i class="fas fa-hands-helping"></i>
                                                Lihat Tutorial
                                            </button> 
                                            <button id="btn-cancel" class="btn btn-default btn-small" type="reset"
                                                    style="display: inline;">
                                                <i class="fas fa-times"></i>
                                                Tutup
                                            </button>                            
                                        </div>
                                    </div> 								
                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side"> 										
                                                <div class="col-lg-12 col-md-12 col-xs-12">														
                                                    <div class="form-group">
                                                        <input id="id_document" name="id_document" type="hidden" value="" placeholder="id" readonly>                            
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12">
                                                    <div class="col-lg-5 col-md-5 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Nomor Asset</label>
                                                            <input id="product_asset_code" name="product_asset_code" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Nama Asset *</label>
                                                            <input id="product_asset_name" name="product_asset_name" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>      
                                                    <div class="col-ld-12 col-md-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Akun Aset Tetap</label>
                                                            <select id="product_asset_fixed_account_id" name="product_asset_fixed_account_id" class="form-control" disabled>
                                                            </select>
                                                        </div>           
                                                    </div>                                               
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Deskripsi</label>
                                                            <textarea id="product_asset_note" name="product_asset_note" type="text" value="" class="form-control" readonly='true' rows="2" style="height:60px!important;"/></textarea>
                                                        </div>
                                                    </div>                                           
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12">													
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Tgl Transaksi</label>
                                                            <div class="input-append success date col-md-12 col-lg-12 no-padding prs-0">
                                                                <input name="product_asset_acquisition_date" id="product_asset_acquisition_date" type="text" class="form-control" readonly="true"
                                                                       value="<?php echo $end_date; ?>" data-value="">
                                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>												
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Biaya Akusisi</label>
                                                            <input id="product_asset_acquisition_value" name="product_asset_acquisition_value" type="text" value="0.00" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>      
                                                    <div class="col-ld-12 col-md-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Akun Di Kreditkan</label>
                                                            <select id="product_asset_cost_account_id" name="product_asset_cost_account_id" class="form-control" disabled>
                                                            </select>
                                                        </div>           
                                                    </div>                                        
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label>Gambar Asset</label>
                                                            <!-- <img id="img-preview1" class="img-responsive" 
                                                                 data-is-new="0"
                                                                 style="width:255px"
                                                                 src="<?= site_url(); ?>/upload/noimage.png"/>
                                                            <div class="custom-file">
                                                                <input class="form-control" id="upload1" name="upload1" type="file" tabindex="1">
                                                                <label class="custom-file-label">Pilih Gambar</label>
                                                            </div> -->
                                                            <a class="files_link" href="<?= site_url('upload/noimage.png'); ?>">
                                                                <img id="files_preview" src="<?= site_url('upload/noimage.png'); ?>" class="img-responsive" height="120px" width="240px" style="margin-bottom:5px;"/>
                                                            </a>
                                                            <div class="custom-file">
                                                                <input class="form-control" id="files" name="files" type="file" tabindex="1">
                                                                <!-- <label class="custom-file-label">Pilih Gambar</label> -->
                                                            </div>                                                            
                                                        </div>
                                                    </div>                                        
                                                </div>
                                            </div>
                                        </div>      
                                    </div>                    
                                </div>
                            </div> 
                            <div class="grid simple">
                                <div class="grid-body">
                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                        <h5><b>Penyusutan <?php echo $title; ?></b></h5>  
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                <div class="col-md-5 col-sm-12 col-xs-12">
                                                    <div class="col-ld-12 col-md-12 padding-remove-side">													
                                                        <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-left">
                                                            <div class="form-group">
                                                                <label class="form-label" id="asset-checkbox-label">Asset non-depresiasi</label>
                                                                <div class="toggles">
                                                                    <input type="checkbox" name="checkbox" id="product_asset_dep_flag" class="ios-toggle" checked/>
                                                                    <label class="checkbox-label" data-flag="1">																
                                                                    </label>	
                                                                </div>
                                                            </div>																									
                                                        </div>  
                                                        <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-side">	
                                                            <div class="form-group">
                                                                <label class="form-label">Masa Manfaat (tahun)</label>
                                                                <input id="product_asset_dep_period" name="product_asset_dep_period" type="number" value="0" class="form-control" readonly='true'/>
                                                                <div class="add-on" style="position: absolute;right:8px;top:24px;font-weight: 800;font-size:14px;">
                                                                    <p style="color:#585b5f;font-weight: 600;">tahun</p>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>    
                                                    <div class="col-ld-12 col-md-12 padding-remove-side">												
                                                        <div class="col-lg-6 col-md-6 col-md-12 padding-remove-left">
                                                            <div class="form-group">
                                                                <label class="form-label">Metode</label>
                                                                <select id="product_asset_dep_method" name="product_asset_dep_method" class="form-control" disabled>
                                                                    <option value="1" selected>Straight Line</option>
                                                                    <option value="2">Reducing Balance</option>
                                                                </select>
                                                            </div>           
                                                        </div>														  
                                                        <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-side">
                                                            <div class="form-group">
                                                                <label class="form-label">Nilai Tahun (persen)</label>
                                                                <input id="product_asset_dep_percentage" name="product_asset_dep_percentage" type="text" value="0.00" class="form-control" readonly='true'/>
                                                                <div class="add-on" style="position: absolute;right:8px;top:24px;font-weight: 800;font-size:14px;">
                                                                    <p style="color:#585b5f;font-weight:600;">persen</p>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>																										                                    
                                                </div>
                                                <div class="col-md-7 col-sm-12 col-xs-12">
                                                    <div class="col-ld-12 col-md-12 padding-remove-side">												
                                                        <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-left">
                                                            <div class="form-group">
                                                                <label class="form-label">Akun Penyusutan</label>
                                                                <select id="product_asset_depreciation_account_id" name="product_asset_depreciation_account_id" class="form-control" disabled>
                                                                </select>
                                                            </div>           
                                                        </div>   
                                                        <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-side">
                                                            <div class="form-group">
                                                                <label class="form-label">Akumulasi Akun Penyusutan</label>
                                                                <select id="product_asset_accumulated_depreciation_account_id" name="product_asset_accumulated_depreciation_account_id" class="form-control" disabled>
                                                                </select>
                                                            </div>           
                                                        </div> 
                                                    </div>      
                                                    <div class="col-ld-12 col-md-12 padding-remove-side">												
                                                        <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-left">
                                                            <div class="form-group">
                                                                <label class="form-label">Akumulasi Penyusutan</label>
                                                                <input id="product_asset_accumulated_depreciation_value" name="product_asset_accumulated_depreciation_value" type="text" class="form-control" readonly='true' value="0.00"/>
                                                            </div>
                                                        </div>  
                                                        <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-side">
                                                            <div class="form-group">
                                                                <label class="form-label">Tanggal</label>
                                                                <div class="input-append success date col-md-12 col-lg-12 no-padding prs-0">
                                                                    <input name="product_asset_accumulated_depreciation_date" id="product_asset_accumulated_depreciation_date" type="text" class="form-control" readonly="true"
                                                                           value="<?php echo $end_date; ?>" data-value="">
                                                                    <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>		
                                                    </div>													  																										                                    
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top: 10px;">
                                                    <div class="form-group">
                                                        <div class="pull-right">
                                                            <button id="btn-cancel" onClick="formCancel();" class="btn btn-warning btn-small" type="reset" style="display: none;">
                                                                <i class="fas fa-ban"></i> 
                                                                Batal
                                                            </button>                                                                  
                                                            <button id="btn-save" onClick="" class="btn btn-primary btn-small" type="button" style="display:none;">
                                                                <i class="fas fa-save"></i>                                 
                                                                Save
                                                            </button>                                        
                                                            <button id="btn-update" class="btn btn-info btn-small" type="button" style="display: none;">
                                                                <i class="fas fa-edit"></i> 
                                                                Update
                                                            </button> 
                                                            <button id="btn-delete" class="btn btn-danger btn-small" type="button" style="display: none;">
                                                                <i class="fas fa-trash"></i> 
                                                                Delete
                                                            </button>                                   
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>	
                        </form>					 
                    </div>
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">                
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
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                    <div class="row">                
                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                            <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                                <h5><b>Data <?php echo $title; ?></b></h5>
                                            </div>
                                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                                <div class="pull-right">
                                                    <button id="btn-export" onClick="" class="btn btn-default btn-small" type="button"
                                                            style="display: none;">
                                                        <i class="fas fa-file-excel"></i>
                                                        Ekspor Excel
                                                    </button>                      
                                                    <button id="btn-new" onClick="" class="btn btn-success btn-small" type="button"
                                                            style="display: inline;">
                                                        <i class="fas fa-plus"></i>
                                                        Buat <?php echo $title; ?> Baru
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:10px;">
                                            <div class="col-lg-4 col-md-3 col-xs-12 form-group padding-remove-right">
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Akun Asset</label>                          
                                                    <select id="filter_asset_fixed_account_id" name="filter_asset_fixed_account_id" class="form-control">
                                                        <option value="0" selected>Semua</option>
                                                    </select>
                                                </div>
                                            </div>                     
                                            <div class="col-lg-6 col-md-7 col-xs-12 form-group padding-remove-right">            
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Cari</label>                          
                                                    <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                                </div>  
                                            </div> 
                                            <div class="col-lg-2 col-md-2 col-xs-12 form-group">
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Tampil</label>                          
                                                    <select id="filter_length" name="filter_length" class="form-control">
                                                        <option value="10">10 Baris</option>
                                                        <option value="25">25 Baris</option>
                                                        <option value="50">50 Baris</option>
                                                        <option value="100">100 Baris</option>
                                                    </select>
                                                </div>
                                            </div>                       
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12 table-responsive">
                                            <table id="table-data" class="table table-bordered" style="width:100%;" data-limit-start="0" data-limit-end="10">
                                                <thead>
                                                    <tr>
                                                        <th>Tgl Akusisi</th>
                                                        <th>Detail Asset</th>
                                                        <th>Akun Asset</th>
                                                        <th>Biaya Akusisi</th>
                                                        <th>Nilai Buku</th>
                                                        <th>Action</th>														  														  														  
                                                    </tr>
                                                </thead>
                                            </table>
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
<div class="modal fade" id="modal-depreciation" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-size" class="modal-dialog">
        <div class="modal-content modal-md">
            <form id="form-depreciation">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Riwayat Penyusutan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="">
                        <span aria-hidden="true" style="color:#888888;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background: white!important;">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-12">  
                            <table id="table-data-depreciation" class="table table-bordered" style="width:100%;" data-limit-start="0" data-limit-end="10">
                                <thead>
                                    <tr>
                                        <th>Tgl Penyusutan</th>
                                        <th>Nomor</th>
                                        <th>Nilai</th>
                                        <th>Action</th>														  														  														  
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!--
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">  
                                <div class="col-md-6 col-xs-12 col-sm-12">
                                        <div class="form-group">
                                                <label class="form-label">Nama Varian</label>
                                                <input id="product_price_name" name="product_price_name" class="form-control" placeholder="Eceran, Grosir, dll">
                                        </div>
                                </div>  
                                <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                        <div class="form-group">
                                                <button id="btn-price-save-item" onClick="" class="btn btn-default btn-small" type="button"
                                                style="margin-top:20px;">
                                                        <i class="fas fa-plus-square"></i>
                                                        Tambah
                                                </button>
                                        </div>
                                </div>   
                        </div>
                        -->
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-croppie" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div id="modal-croppie-canvas"></div>
            </div>
            <div class="modal-footer">
                <button id="modal-croppie-save" type="button" class="btn btn-primary"><span class="fas fa-crop"></span> Crop Gambar</button>                
                <button id="modal-croppie-cancel" type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fas fa-times"></span> Tutup</button>
            </div>
        </div>
    </div>
</div>