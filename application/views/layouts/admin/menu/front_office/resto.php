<!-- <style>
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
</style>
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php #include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
            </div>
            <div class="tab-pane" id="tab2">
            </div>
        </div>
    </div>
</div> -->

<style>
    select{min-height: 28px!important; height: 28px!important;}
    .form-control{padding:0px 8px!important;}
    /* Large desktops and laptops */
    @media (min-width: 1200px) {
        .table-responsive{ overflow-x: unset; }
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}
    }
    /* Landscape tablets and medium desktops */
    @media (min-width: 992px) and (max-width: 1199px) {
        .table-responsive{ overflow-x: unset; }
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}
    }
    /* Portrait tablets and small desktops */
    @media (min-width: 768px) and (max-width: 991px) {
        .table-responsive{ overflow-x: unset; }
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}
    }
    /* Landscape phones and portrait tablets */
    @media (max-width: 767px) {
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}
    }
    /* Portrait phones and smaller */
    @media (max-width: 480px) {
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}
        .pull-right{height: auto!important;}
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<div class="row">
    <div class="col-md-12">
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
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
                                                <h5><b>Data <?php echo $title;?></b></h5>
                                            </div>
                                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                                <div class="pull-right">
                                                    <button id="btn_export_name" onClick="" class="btn btn-default btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-file-excel"></i>
                                                        Ekspor Excel
                                                    </button>
                                                    <button id="btn_new_name" class="btn btn-success btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-plus"></i>
                                                        Buat <?php echo $title; ?> Baru
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                <label class="form-label">Periode Awal</label>
                                                <div class="col-md-12 col-sm-12 padding-remove-side">
                                                    <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                        <input name="filter_start_date" id="filter_start_date" type="text" class="form-control input-sm" readonly="true" value="<?php echo $first_date;?>">
                                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                <label class="form-label">Periode Akhir</label>
                                                <div class="col-md-12 col-sm-12 padding-remove-side">
                                                    <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                        <input name="filter_end_date" id="filter_end_date" type="text" class="form-control input-sm" readonly="true" value="<?php echo $end_date;?>">
                                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 form-group padding-remove-right prs-15">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Cari</label>
                                                    <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Status</label>
                                                    <select id="filter_flag" name="filter_flag" class="form-control">
                                                        <option value="All">Semua</option>
                                                        <option value="1">Aktif</option>
                                                        <option value="0">Nonaktif</option>
                                                        <option value="4">Terhapus</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Tampil</label>
                                                    <select id="filter_length" name="filter_length" class="form-control">
                                                        <option value="10">10 Baris</option>
                                                        <option value="25">25 Baris</option>
                                                        <option value="50">50 Baris</option>
                                                        <option value="100">100 Baris</option>
                                                        <option value="-1">Semuanya</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12" style="padding-top:10px;">
                                            <div class="table-responsive">
                                                <table id="table_name" class="table table-bordered" style="width:100%;">
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

<!-- Modal -->
<div class="modal fade" id="modal_name" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
       <div class="modal-content">
            <form id="form_name" name="form_name" method="" action="">
                <div class="modal-header">
                    <h4 style="text-align:left;">Form name</h4>
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal"
                        style="position:relative;top:-38px;float:right;">
                        <i class="fas fa-times"></i>
                        Tutup
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Input</label>
                                    <input id="name_input" name="name_input" type="text" value="" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Textarea</label>
                                    <textarea id="name_textarea" name="name_textarea" type="text" value="" class="form-control" rows="8"/></textarea>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-8 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Select</label>
                                    <select id="name_select" name="name_select" class="form-control">
                                        <option value="0">Pilih</option>
                                        <option value="1">Ya</option>
                                        <option value="2">Tidak</option>
                                    </select>
                               </div>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-8 padding-remove-side">
                                <div class="form-group">
                                   <label class="form-label">Radio</label>
                                   <div class="radio radio-success">
                                        <input id="aktif" type="radio" name="name_radio" value="1" checked><label for="aktif">Aktif</label>
                                        <input id="nonaktif" type="radio" name="name_radio" value="2"><label for="nonaktif">NonAktif</label>
                                    </div>
                               </div>
                            </div>
                            <div class="col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Checkbox</label>
                                </div>
                                <div class="checkbox check-success">
                                    <input name="name_checkbox_1" id="name_checkbox_1" type="checkbox">
                                    <label for="name_checkbox_1">&nbsp;Checkbox 1
                                </div>
                                <div class="checkbox check-success">
                                    <input name="name_checkbox_2" id="name_checkbox_2" type="checkbox">
                                    <label for="name_checkbox_2">&nbsp;Checkbox 2
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">
                                    <label class="form-label">Tanggal</label>
                                    <div class="col-md-12 col-sm-12 padding-remove-side">
                                        <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                            <input name="name_date" id="name_date" type="text" class="form-control input-sm" readonly="true" value="<?php echo $end_date;?>">
                                            <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">
                                        <label class="form-label">Jam</label>
                                        <div class="col-md-12 col-sm-12 padding-remove-side">
                                            <div class="input-group transparent clockpicker col-md-12">
                                                <input name="name_hour" id="name_hour" type="text" class="form-control input-sm" value="<?php echo $hour; ?>" >
                                                <span class="input-group-addon clock-add"><i class="fas fa-clock"></i></span>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Gambar *</label>
                                        <img id="img-preview1" class="img-responsive" 
                                            data-is-new="0"
                                            style="width:100%"
                                            src=""/>
                                        <a class="files_link" href="https://via.placeholder.com/450x450?text=Belumadagambar">
                                            <img id="files_preview" src="https://via.placeholder.com/150x150?text=Belum+ada+gambar" class="img-responsive" height="125px" width="125px" style="margin-bottom:5px;"/>
                                        </a>
                                        <div class="custom-file">
                                            <input class="form-control" id="files" name="files" type="file" tabindex="1">
                                            <label class="custom-file-label">Pilih Gambar</label>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="grid simple">
                            <div class="grid-body">
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                    <h5><b><?php echo $title;?> Item</b></h5>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                            <form id="form_name_item" name="form_name_item" method="" action="">
                                                <div class="col-md-12">
                                                    <input id="id_name_item" name="id_name_item" type="hidden" value="" placeholder="id" readonly>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label>Item Name</label>
                                                            <select id="name_name" name="name_name" class="form-control" disabled readonly>
                                                                <option value="0">Pilih</option>
                                                                <option value="1">Terpilih</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8 col-xs-12 col-sm-12 padding-remove-side">
                                                        <div class="col-md-4 col-xs-12 col-sm-12">
                                                            <div class="form-group">
                                                                <label>Item Value</label>
                                                                <input id="name_value" name="name_value" type="text" value="" class="form-control" readonly="true"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8 col-xs-12 col-sm-12 padding-remove-left">
                                                            <div class="form-group">
                                                                <button id="btn_save_name_item" onClick="" class="btn btn-default btn-small" type="button" style="margin-top:22px;">
                                                                    <i class="fas fa-plus-square"></i>
                                                                </button>
                                                            </div>
                                                            <div class="form-group">
                                                                <button id="btn_update_name_item" onClick="" class="btn btn-default btn-small" type="button" style="margin-top:22px;">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12 scroll">
                                            <table id="table_name_item" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Item Name</th>
                                                        <th style="text-align:right;">Item Value</th>
                                                        <th>Item Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn_save_name" class="btn btn-primary" type="button" style="width:45%;">
                        <i class="fas fa-save"></i> 
                        Simpan
                    </button>
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="width:45%;">
                        <i class="fas fa-times"></i>
                        Tutup
                    </button>
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
                <button id="modal-croppie-cancel" type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button id="modal-croppie-save" type="button" class="btn btn-primary">Crop</button>
            </div>
        </div>
    </div>
</div>


