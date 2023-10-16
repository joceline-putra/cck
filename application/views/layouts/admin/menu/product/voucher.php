<style>
    select{min-height: 28px!important; height: 28px!important;} 
    .form-control{padding:0px 8px!important;}
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
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div id="div_form_voucher" style="display: none;" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                        <div class="grid simple">
                            <div class="grid-body">
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                    <h5><b><?php echo $title;?></b></h5>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side"> 
                                            <form id="form_voucher" name="form_voucher" method="" action="">
                                                <div class="col-lg-12 col-md-12 col-xs-12">
                                                    <div class="form-group">
                                                        <input id="voucher_id" name="voucher_id" type="hidden" value="" placeholder="id" readonly>
                                                        <input id="voucher_session" name="voucher_session" type="hidden" value="" placeholder="session" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-12 col-xs-12">    
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side prs-0">
                                                        <div class="form-group">
                                                            <label class="form-label">Gambar Voucher 480 x 240 px</label>
                                                            <!--
                                                            <img id="img-preview1" class="img-responsive" 
                                                                data-is-new="0"
                                                                style="width:100%"
                                                                src=""/>
                                                            -->
                                                            <a class="files_link" href="<?= site_url('upload/voucher/noimage.png'); ?>">
                                                                <img id="files_preview" src="<?= site_url('upload/voucher/noimage.png'); ?>" class="img-responsive" height="120px" width="240px" style="margin-bottom:5px;"/>
                                                            </a>
                                                            <div class="custom-file">
                                                                <input class="form-control" id="files" name="files" type="file" tabindex="1">
                                                                <!-- <label class="custom-file-label">Pilih Gambar</label> -->
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>                                                
                                                <div class="col-md-5 col-sm-12 col-xs-12 prs-0">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side prs-0">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side form-group">
                                                            <label class="form-label">Jenis</label>
                                                            <select id="voucher_type" name="voucher_type" class="form-control" style="width:100%;">
                                                                <option value="0">Pilih</option>
                                                                <option value="1">Voucher</option>
                                                                <option value="2">Promo</option>
                                                            </select>
                                                        </div>
                                                    </div>                                                    
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side prs-5">
                                                        <div class="form-group">
                                                            <label class="form-label">Kode Voucher</label>
                                                            <input id="voucher_code" name="voucher_code" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="col-lg-5 col-md-6 col-xs-6 padding-remove-left">
                                                            <div class="form-group">
                                                                <label class="form-label">Minimum Transaksi (Rp)</label>
                                                                <input id="voucher_minimum_transaction" name="voucher_minimum_transaction" type="text" value="" class="form-control" readonly='true'/>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-xs-6 padding-remove-side">
                                                            <div class="form-group">
                                                                <label class="form-label">Nilai Voucher (Rp)</label>
                                                                <input id="voucher_price" name="voucher_price" type="text" value="" class="form-control" readonly='true'/>
                                                            </div>
                                                        </div>            
                                                        <div class="col-lg-3 col-md-12 col-xs-12 padding-remove-right">
                                                            <div class="form-group">
                                                                <label class="form-label">Diskon (%)</label>
                                                                <input id="voucher_discount_percentage" name="voucher_discount_percentage" type="text" value="" class="form-control" readonly='true'/>
                                                            </div>
                                                        </div>    
                                                    </div>                                                    
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12 prs-0">                                                 
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side prs-0">
                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 form-group padding-remove-left prs-0 prr-5">
                                                            <label class="form-label">Tanggal Berlaku Awal</label>
                                                            <div class="col-md-12 col-sm-12 padding-remove-side">
                                                                <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                                    <input name="voucher_date_start" id="voucher_date_start" type="text" class="form-control input-sm" readonly="true" value="<?php echo $end_date;?>">
                                                                    <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 form-group padding-remove-side prs-0">
                                                            <label class="form-label">Tanggal Berlaku Berakhir</label>
                                                            <div class="col-md-12 col-sm-12 padding-remove-side">
                                                                <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                                    <input name="voucher_date_end" id="voucher_date_end" type="text" class="form-control input-sm" readonly="true" value="<?php echo $end_date;?>">
                                                                    <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                            <div class="form-group">
                                                                <label class="form-label">Judul</label>
                                                                <textarea id="voucher_title" name="voucher_title" type="text" value="" class="form-control" rows="4"/></textarea>
                                                            </div>
                                                        </div>
                                                    </div>                                                                                                                                                                                                                  
                                                    <div class="col-ld-12 col-md-12 padding-remove-side prs-0">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 form-group padding-remove-side">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side form-group">
                                                                <label class="form-label">Status</label>
                                                                <select id="voucher_flag" name="voucher_flag" class="form-control" style="width:100%;">
                                                                    <option value="1">Aktif</option>
                                                                    <option value="0">Nonaktif</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                                
                                                <div class="clearfix"></div>
                                                <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top: 10px;">
                                                    <div class="form-group">
                                                        <div class="pull-right">
                                                            <button id="btn_cancel_voucher" class="btn btn-small" type="reset" style="display: none;">
                                                                <i class="fas fa-times"></i> 
                                                                Tutup
                                                            </button>
                                                            <button id="btn_save_voucher" class="btn btn-primary btn-small" type="button" style="display:none;">
                                                                <i class="fas fa-save"></i>
                                                                Save
                                                            </button>
                                                            <button id="btn_update_voucher" class="btn btn-info btn-small" type="button" style="display: none;">
                                                                <i class="fas fa-edit"></i> 
                                                                Update
                                                            </button> 
                                                            <button id="btn_delete_voucher" class="btn btn-danger btn-small" type="button" style="display: none;">
                                                                <i class="fas fa-trash"></i> 
                                                                Delete
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
                                                <h5><b>Data <?php echo $title;?> & Promo</b></h5>
                                            </div>
                                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                                <div class="pull-right">
                                                    <!--
                                                        <button id="btn_export_voucher" onClick="" class="btn btn-default btn-small" type="button" style="display: inline;">
                                                            <i class="fas fa-file-excel"></i>
                                                            Ekspor Excel
                                                        </button>
                                                    -->
                                                    <button id="btn_new_voucher" class="btn btn-success btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-plus"></i>
                                                        Buat <?php echo $title; ?> Baru
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12 prs-0 padding-remove-side" style="padding-top:8px;">
                                            <!--
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                                                    <label class="form-label">Periode Awal</label>
                                                    <div class="col-md-12 col-sm-12 padding-remove-side">
                                                        <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                            <input name="filter_start_date" id="filter_start_date" type="text" class="form-control input-sm" readonly="true" value="<?php echo $first_date;?>">
                                                            <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                                                    <label class="form-label">Periode Akhir</label>
                                                    <div class="col-md-12 col-sm-12 padding-remove-side">
                                                        <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                            <input name="filter_end_date" id="filter_end_date" type="text" class="form-control input-sm" readonly="true" value="<?php echo $end_date;?>">
                                                            <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            -->
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Jenis</label>
                                                    <select id="filter_type" name="filter_type" class="form-control">
                                                        <option value="All">Semua</option>
                                                        <option value="1">Voucher</option>
                                                        <option value="2">Promo</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Status</label>
                                                    <select id="filter_flag" name="filter_flag" class="form-control">
                                                        <option value="All">Semua</option>
                                                        <option value="1">Aktif</option>
                                                        <option value="0">Nonaktif</option>
                                                        <!-- <option value="4">Terhapus</option> -->
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 form-group padding-remove-right prs-0">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Cari</label>
                                                    <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group prs-0">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Tampil</label>
                                                    <select id="filter_length" name="filter_length" class="form-control">
                                                        <option value="10">10 Baris</option>
                                                        <option value="25">25 Baris</option>
                                                        <option value="50">50 Baris</option>
                                                        <option value="100">100 Baris</option>
                                                        <!-- <option value="-1">Semuanya</option> -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12 prs-0" style="padding-top:10px;">
                                            <div class="table-responsive">
                                                <table id="table_data" class="table table-bordered" style="width:100%;">
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
            <div class="tab-pane" id="tab2">
            </div>
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