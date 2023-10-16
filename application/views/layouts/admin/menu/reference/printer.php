<style>
    select{min-height: 28px!important; height: 28px!important;} 
    .form-control{padding:0px 8px!important;}
</style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<div class="row">
    <div class="col-md-12">
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div id="div-form-printer" style="display: none;" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                        <div class="col-md-4 col-sm-12 col-xs-12 padding-remove-left">
                            <div class="grid simple">
                                <div class="grid-body">
                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                        <h5><b><?php echo $title;?></b></h5>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side"> 
                                                <form id="form-printer" name="form-printer" method="" action="">
                                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                                        <div class="form-group">
                                                            <input id="printer_id" name="printer_id" type="hidden" value="" placeholder="id" readonly>
                                                            <input id="printer_session" name="printer_session" type="hidden" value="" placeholder="session" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                            <div class="form-group">
                                                                <label class="form-label">Nama Printer Sharing</label>
                                                                <input id="printer_name" name="printer_name" type="text" value="" class="form-control" readonly='true'/>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                            <div class="form-group">
                                                                <label class="form-label">IP Address Sharing</label>
                                                                <input id="printer_ip" name="printer_ip" type="text" value="" class="form-control" readonly='true'/>
                                                            </div>
                                                        </div>                                                        
                                                        <div class="col-ld-12 col-md-12 padding-remove-side">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                                <div class="form-group">
                                                                    <label class="form-label">Tipe</label>
                                                                    <select id="printer_type" name="printer_type" class="form-control" style="width:100%;">
                                                                        <option value="0">Pilih</option>
                                                                        <option value="1">Deskjet</option>
                                                                        <option value="2">Dot Matrik</option>
                                                                        <option value="3">Label Works</option>
                                                                        <option value="4">Receipt Thermal</option>                                                                                                                                                
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-xs-12 form-group padding-remove-side">
                                                                <div class="form-group">
                                                                    <label class="form-label">Status *</label>
                                                                    <select id="printer_flag" name="printer_flag" class="form-control" style="width:100%;">
                                                                        <option value="0">Nonaktif</option>
                                                                        <option value="1">Aktif</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top: 10px;">
                                                        <div class="form-group">
                                                            <div class="pull-right">
                                                                <button id="btn_cancel_printer" class="btn btn-warning btn-small" type="reset" style="display: none;">
                                                                    <i class="fas fa-ban"></i> 
                                                                    Cancel
                                                                </button>
                                                                <button id="btn_save_printer" class="btn btn-primary btn-small" type="button" style="display:none;">
                                                                    <i class="fas fa-save"></i>
                                                                    Save
                                                                </button>
                                                                <button id="btn_update_printer" class="btn btn-info btn-small" type="button" style="display: none;">
                                                                    <i class="fas fa-edit"></i> 
                                                                    Update
                                                                </button> 
                                                                <button id="btn_delete_printer" class="btn btn-danger btn-small" type="button" style="display: none;">
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
                        <div class="col-md-8 col-sm-12 col-xs-12 padding-remove-right">
                            <div class="grid simple">
                                <div class="grid-body">
                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                        <h5><b><?php echo $title;?> Item</b></h5>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                <form id="form-printer-item" name="form-printer-item" method="" action="">
                                                    <div class="col-md-12">
                                                        <input id="id_printer_item" name="id_printer_item" type="hidden" value="" placeholder="id" readonly>
                                                    </div>                                                 
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <!--
                                                        <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-side">
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                <div class="col-lg-12 col-md-12 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Keterangan</label>
                                                                        <textarea id="printer_paper_design" name="printer_paper_design" type="text" value="" class="form-control" rows="4"/></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                                                                 
                                                        </div>-->                                         
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                            <div class="col-md-2 col-xs-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label>Lebar (mm)</label>
                                                                    <input id="printer_paper_width" name="printer_paper_width" type="text" value="" class="form-control" readonly="true"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 col-xs-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label>Panjang (mm)</label>
                                                                    <input id="printer_paper_height" name="printer_paper_height" type="text" value="" class="form-control" readonly="true"/>
                                                                </div>
                                                            </div>                                                        
                                                            <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-left">
                                                                <div class="form-group">
                                                                    <button id="btn_save_printer_item" onClick="" class="btn btn-default btn-small" type="button" style="margin-top:22px;">
                                                                        <i class="fas fa-plus-square"></i>
                                                                        Tambah
                                                                    </button>
                                                                    <button id="btn_update_printer_item" style="display:none;margin-top:22px;" onClick="" class="btn btn-default btn-small" type="button">
                                                                        <i class="fas fa-edit"></i>
                                                                        Perbarui
                                                                    </button>
                                                                    <button id="btn_cancel_printer_item" style="display:none;margin-top:22px;" onClick="" class="btn btn-default btn-small" type="button">
                                                                        <i class="fas fa-ban"></i>
                                                                        Batal
                                                                    </button>                                                                                                                                
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-md-12 col-xs-12 col-sm-12 scroll">
                                                <table id="table_printer_item" class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align:right;">Width (mm)</th>
                                                            <th style="text-align:right;">Height (mm)</th>                                                        
                                                            <th>Action</th>
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
                                            <div class="col-md-3 col-xs-12 col-sm-12" style="padding-left: 0;">
                                                <h5><b>Data <?php echo $title;?></b></h5>
                                            </div>
                                            <div class="col-md-9 col-xs-12 col-sm-12 padding-remove-right">
                                                <div class="pull-right">
                                                    <!--
                                                    <button id="btn_export_printer" onClick="" class="btn btn-default btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-file-excel"></i>
                                                        Ekspor Excel
                                                    </button>
                                                    -->
                                                    <button id="btn_test_matrix_printer" data-id="1" data-session="SESSONDATA" class="btn btn-default btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-print"></i>
                                                        Print Matrix Test
                                                    </button>
                                                    <button id="btn_test_barcode_printer" data-id="1" data-session="SESSONDATA" class="btn btn-default btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-print"></i>
                                                        Print Barcode Test
                                                    </button>
                                                    <button id="btn_test_qrcode_printer" data-id="1" data-session="SESSONDATA" class="btn btn-default btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-print"></i>
                                                        Print Qrcode Test
                                                    </button>
                                                    <button id="btn_test_bluetooth_printer" data-id="1" data-session="SESSONDATA" class="btn btn-default btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-print"></i>
                                                        Print Bluetooth Test
                                                    </button>                                                    
                                                    <button id="btn_new_printer" class="btn btn-success btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-plus"></i>
                                                        Buat <?php echo $title; ?> Baru
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
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
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 form-group padding-remove-right">
                                                <label class="form-label">Cari</label>
                                                <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group">
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
                                        <div class="col-md-12 col-xs-12 col-sm-12 table-responsive" style="padding-top:10px;">
                                            <table id="table-data" class="table table-bordered" style="width:100%;">
                                            </table>
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
