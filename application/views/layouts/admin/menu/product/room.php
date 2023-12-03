<style>
    .scroll {
        margin-top: 4px;
        margin-bottom: 8px;
        margin-left: 4px;
        margin-right: 4px;
        padding: 4px;
        /*background-color: green; */
        width: 100%;
        height: 200px;
        overflow-x: hidden;
        overflow-y: auto;
        text-align: justify;
    }
    /* Large desktops and laptops */
    @media (min-width: 1200px) {
        .table-responsive{
            overflow-x: unset;
        }
    }

    /* Landscape tablets and medium desktops */
    @media (min-width: 992px) and (max-width: 1199px) {
        .table-responsive{
            overflow-x: unset;
        }
    }

    /* Portrait tablets and small desktops */
    @media (min-width: 768px) and (max-width: 991px) {
        .table-responsive{
            overflow-x: unset;
        }
    }

    /* Landscape phones and portrait tablets */
    @media (max-width: 767px) {
        .table-responsive{
            overflow-x: unset;
        }
    }

    /* Portrait phones and smaller */
    @media (max-width: 480px) {
        .tab-content > .active{
            padding: 8px!important;
        }  
        .padding-remove-left, .padding-remove-right{
            padding-left:0px!important;
            padding-right:0px!important;    
        }
        .padding-remove-side{
            padding-left: 5px!important;
            padding-right: 5px!important;
        }
        .form-label{
            /*padding-left: 5px!important;*/
        }
        .prs-0{
            padding-left: 0px!important;
            padding-right: 0px!important;    
        }
        .prs-0 > label{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }
        .prs-0 > div{
            /*padding-left: 5px!important;*/
            /*padding-right: 5px!important;    */
        }
        .prs-0 > input{
            margin-left: 0px!important;
            margin-right: 0px!important;    
        }
        .prs-0 > select{
            margin-left: 5px!important;
            margin-right: 5px!important;    
        }

        .prs-5{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }
        .prs-5 > label{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }
        .prs-5 > div{
            /*padding-left: 5px!important;*/
            /*padding-right: 5px!important;    */
        }
        .prs-5 > input{
            margin-left: 5px!important;
            margin-right: 5px!important;    
        }
        .prs-5 > select{
            margin-left: 5px!important;
            margin-right: 5px!important;    
        }    

        .prl-2{
            padding-left: 2.5px!important;
        }
        .prr-2{
            padding-right: 2.5px!important;
        }    
    }    
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div id="div-form-trans" style="display:none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div class="grid simple">
                        <div class="grid-body">
                            <h5><b>Form <?php echo $title; ?></b></h5>                            
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                    <form id="form-master" name="form-master" method="" action="">    
                                        <input id="tipe" type="hidden" value="<?php echo $identity; ?>">
                                        <div class="col-md-12">
                                            <input id="id_document" name="id_document" type="hidden" value="" placeholder="id" readonly>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label class="form-label">Gambar <?php echo $title; ?> <?php echo $image_width; ?> x <?php echo $image_height; ?> px</label>
                                                    <!--
                                                    <img id="img-preview1" class="img-responsive" 
                                                        data-is-new="0"
                                                        style="width:100%"
                                                        src=""/>
                                                    -->
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
                                        <div class="col-md-5 col-sm-12 col-xs-12">
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label class="form-label">Cabang</label>
                                                    <div class="radio radio-success">
                                                        <?php 
                                                        foreach($branch as $i => $v){
                                                            $c = '';
                                                            if($i==0){
                                                                $c = 'checked';
                                                            }
                                                        ?>
                                                            <input id="branch_<?php echo $v['branch_id']; ?>" type="radio" name="product_branch_id" value="<?php echo $v['branch_id']; ?>" <?php echo $c; ?>><label for="branch_<?php echo $v['branch_id']; ?>"><?php echo $v['branch_name']; ?></label>
                                                        <?php 
                                                        } 
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">                        
                                                    <label class="form-label">Jenis Kamar *</label>
                                                    <select id="referensii" name="referensii" class="form-control">
                                                        <option value="0">-- Pilih --</option>
                                                    </select>
                                                </div>
                                            </div>                                                                                       
                                            <div class="col-lg-12 col-md-12 col-sm-8 col-xs-8 padding-remove-side">
                                                <div class="form-group">
                                                    <label class="form-label">Nomor <?php echo $title; ?> *</label>
                                                    <input id="nama" name="nama" type="text" value="" class="form-control" readonly='true'/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side" style="margin-bottom:14px;">
                                                <div class="form-group">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea id="keterangan" name="keterangan" type="text" class="form-control" readonly='true' rows="8"></textarea>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12">
                                            <div class="form-group" style="">
                                                <label class="form-label">Status</label>
                                                <select id="status" name="status" class="form-control">
                                                    <!-- <option value="">select</option> -->
                                                    <?php
                                                    $status_values = array(
                                                        '1' => 'Aktif',
                                                        '0' => 'Nonaktif',
                                                    );

                                                    foreach ($status_values as $value => $display_text) {
                                                        echo '<option value="' . $value . '" ' . $selected . '>' . $display_text . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <p><i class="fas fa-info"></i> <?php echo $title; ?> nonaktif tidak akan dimunculkan di semua transaksi</p>
                                            </div>                                             
                                        </div>                                                                                
                                        <div class="clearfix"></div>
                                        <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top: 10px;">
                                            <div class="form-group">
                                                <div class="hide pull-left">                            
                                                    <button id="btn-recipe" class="btn btn-default btn-small" type="button">
                                                        <i class="fas fa-list-alt"></i>
                                                        Resep Untuk <?php echo $title; ?> Ini
                                                    </button>
                                                </div>                        
                                                <div class="pull-right">
                                                    <button id="btn-cancel" class="btn btn-warning btn-small" type="reset" style="display: none;">
                                                        <i class="fas fa-ban"></i> 
                                                        Batal
                                                    </button>                                                                  
                                                    <button id="btn-save" onClick="" class="btn btn-primary btn-small" type="button" style="display:none;">
                                                        <i class="fas fa-save"></i>                                 
                                                        Simpan
                                                    </button>                                        
                                                    <button id="btn-update" class="btn btn-info btn-small" type="button" style="display: none;">
                                                        <i class="fas fa-edit"></i> 
                                                        Perbarui
                                                    </button> 
                                                    <button id="btn-delete" class="btn btn-danger btn-small" type="button" style="display: none;">
                                                        <i class="fas fa-trash"></i> 
                                                        Hapus
                                                    </button>                                   
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div class="grid simple">
                        <div class="grid-body">
                            <div class="row">
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                        <h5><b>Data <?php echo $title; ?></b></h5>
                                    </div>
                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                        <div class="pull-right">
                                            <!-- <button id="btn_import_excel" onClick="" class="btn btn-default btn-small" type="button"
                                                    style="display: inline;">
                                                <i class="fas fa-file-excel"></i>
                                                Import via Excel
                                            </button>    -->
                                            <button id="btn-print-all" onClick="" class="btn btn-default btn-small" type="button"
                                                    style="display: inline;">
                                                <i class="fas fa-print"></i>
                                                Print Data <?php echo $title; ?>
                                            </button>
                                            <button id="btn-new" onClick="" class="btn btn-success btn-small" type="button"
                                                    style="display: inline;">
                                                <i class="fas fa-plus"></i>
                                                Buat <?php echo $title; ?> Baru
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                                        <label class="form-label">Cabang</label>
                                        <select id="filter_branch" name="filter_branch" class="form-control">
                                            <option value="0">-- Semua --</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                                        <label class="form-label">Jenis Kamar</label>
                                        <select id="filter_ref" name="filter_ref" class="form-control">
                                            <option value="0">-- Semua --</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-xs-4 col-sm-4 form-group padding-remove-right">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                            <label class="form-label">Status</label>
                                            <select id="filter_flag" name="filter_flag" class="form-control">
                                                <option value="100">Semua</option>
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>                                    
                                    <div class="col-lg-3 col-md-3 col-xs-8 col-sm-8 form-group padding-remove-right">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                            <label class="form-label">Cari</label>
                                            <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                        </div>
                                    </div>                                 
                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group prs-0">
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
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table id="table-data" class="table table-bordered display nowrap" data-limit-start="0" data-limit-end="10" style="width:100%;">
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab2">
            </div>
        </div>  
    </div>
</div>

<div class="modal fade" id="modal-recipe" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-size" class="modal-dialog">
        <div class="modal-content">
            <form id="form-modal-search-stock">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Resep</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="">
                        <span aria-hidden="true" style="color:#888888;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background: white!important;">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">  
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Komponen <?php echo $title; ?></label>
                                    <select id="recipe-goods" name="recipe-goods" class="form-control">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Qty</label>
                                    <input id="recipe-qty" name="recipe-qty" class="form-control" value="0,0000">
                                </div>
                            </div>  
                            <div class="col-md-3 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Satuan</label>
                                    <input id="recipe-unit" name="recipe-unit" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-left">
                                <div class="form-group">
                                    <button id="btn-recipe-save-item" onClick="" class="btn btn-default btn-small" type="button"
                                            style="margin-top:20px;">
                                        <i class="fas fa-plus-square"></i>
                                        Tambah
                                    </button>
                                </div>
                            </div>   
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-price" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-size" class="modal-dialog">
        <div class="modal-content">
            <form id="form-modal-search-stock">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Varian Harga</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="">
                        <span aria-hidden="true" style="color:#888888;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background: white!important;">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">  
                            <div class="col-md-6 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Nama Varian</label>
                                    <input id="product_price_name" name="product_price_name" class="form-control" placeholder="Eceran, Grosir, dll">
                                </div>
                            </div>  
                            <div class="col-md-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Harga</label>
                                    <input id="product_price_price" name="product_price_price" class="form-control" value="0,00">
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
<div class="modal fade" id="modal_product_excel" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_product_excel" name="form_product_excel" method="" action="" enctype="multipart/form-data">         
                <div class="modal-header" style="background-color: #6F7A8A;">
                    <h4 style="color:white;">Import <?php echo $title; ?> via Excel</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 padding-remove-side"> 
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Contoh Penulisan Excel</label>
                                            <img src="<?php echo base_url('upload/template/template_produk_barang.png');?>" class="img-responsive" style="width:100%;">
                                        </div>               
                                    </div>                                 
                                </div> 
                                <div class="col-md-12 col-sm-12 col-xs-12">                                                 
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Pilih File Excel ( .xls / .xlsx )</label>
                                            <input id="excel_file" type="file" class="form-control" name="excel_file" accept=".xls, .xlsx" required>
                                            <p>Template excel dapat diunduh <a href="<?php echo base_url('upload/template/template_produk_barang.xlsx');?>">disini</a>
                                        </div>
                                    </div>              
                                </div>                                   
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn_import_excel_save" class="btn btn-primary btn-small" type="button" style="display:inline;">
                        <i class="fas fa-paper-plane"></i>                                 
                        Import
                    </button>            
                    <button class="btn btn-outline-danger waves-effect btn-small" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>                   
                </div>
            </form>      
        </div>
    </div>
</div>