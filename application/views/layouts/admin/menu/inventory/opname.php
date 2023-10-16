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
        .prl-5{
            padding-left: 5px!important;
        }
        .prr-5{
            padding-right: 5px!important;
        }            
    }    
</style>
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                    <div id="div-form-trans" style="display: none;" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                        <div class="grid simple">
                            <div class="grid-body">
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                        <div class="grid simple">
                                            <div class="grid-body">
                                                <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                                    <h5><b><?php echo $title; ?></b></h5>
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
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                        <form id="form-trans" name="form-trans" method="" action="">
                                                            <input id="tipe" type="hidden" value="<?php echo $identity; ?>">
                                                            <div class="col-md-12">
                                                                <input id="id_document" name="id_document" type="hidden" value="0" placeholder="id" readonly>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                <div class="col-md-4 col-sm-12 col-xs-12 padding-remove-left prs-0">
                                                                    <div class="col-md-12 col-xs-6 col-sm-6 padding-remove-side prs-0 prr-2">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Karyawan Pelaksana *</label>
                                                                                <select id="kontak" name="kontak" class="form-control" disabled readonly>
                                                                                    <option value="0">-- Pilih / Cari --</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 col-xs-6 col-sm-6 padding-remove-side prs-0">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Telepon</label>
                                                                            <input id="telepon" name="telepon" type="text" value="" class="form-control"/>
                                                                        </div>                                   
                                                                    </div>                                                                    
                                                                </div>
                                                                <div class="col-md-4 col-sm-12 col-xs-12 prs-0">
                                                                    <div class="col-md-12 col-xs-6 col-sm-6 padding-remove-left prs-0">
                                                                        <div class="col-md-12 col-xs-12 form-group prs-0">
                                                                            <label class="form-label">Tanggal Transaksi</label>
                                                                            <div class="col-md-12 col-sm-12 padding-remove-side prs-0">
                                                                                <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                                                    <input name="tgl" id="tgl" type="text" class="form-control" readonly="true"
                                                                                           value="<?php echo $end_date; ?>" data-value="">
                                                                                    <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                    <!--
                                                                        <div class="col-md-12 col-xs-6 col-sm-12 padding-remove-left">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 form-group">
                                                                            <div class="form-group">
                                                                            <label class="form-label">Syarat Pembayaran</label>
                                                                            <select id="syarat_pembayaran" name="syarat_pembayaran" class="form-control">
                                                                                <option value="15">Net 15 Hari</option>
                                                                                <option value="30" selected>Net 30 Hari (1 bulan)</option>
                                                                                <option value="60">Net 60 Hari (2 bulan)</option>
                                                                                <option value="90">Net 90 Hari (3 bulan)</option>
                                                                                <option value="0">Custom</option>                                          
                                                                            </select>
                                                                            </div>
                                                                        </div>
                                                                        </div>
                                                                    --> 
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left prs-0">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 form-group prs-0">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Gudang Penempatan Stok</label>
                                                                                <select id="gudang" name="gudang" class="form-control" disabled readonly>
                                                                                    <option value="0">-- Pilih / Cari --</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>                                                                                                         
                                                                </div>
                                                                <div class="col-md-4 col-sm-12 col-xs-12 padding-remove-right prs-0">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left prs-0">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Nomor Dokumen *</label>
                                                                                <input id="nomor" name="nomor" type="text" value="" class="form-control" placeholder="Otomatis jika dikosongkan" />
                                                                            </div>
                                                                        </div>
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
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
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
                                            <h5><b>Daftar Item <?php echo $title; ?></b></h5>
                                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                <form id="form-trans-item" name="form-trans-item" method="" action="">
                                                    <div class="col-md-12">
                                                        <input id="id_document_item" name="id_document_item" type="hidden" value="" placeholder="id"
                                                               readonly>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                        <div class="col-md-5 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                            <div class="form-group">
                                                                <label class="form-label">Produk *</label>
                                                                <select id="produk" name="produk" class="form-control" disabled readonly>
                                                                    <option value="0">-- Pilih / Cari --</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-7 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                            <div class="col-md-3 col-xs-6 col-sm-6 prs-0 prr-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Stok Saat Ini</label>
                                                                    <input id="stok" name="stok" type="text" value="" class="form-control"
                                                                           readonly='true'/>
                                                                </div>
                                                            </div>                              
                                                            <div class="col-md-2 col-xs-6 col-sm-6 prs-0">
                                                                <div class="form-group">
                                                                    <label class="form-label">Satuan</label>
                                                                    <input id="satuan" name="satuan" type="text" value="" class="form-control"
                                                                           readonly='true'/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 col-xs-6 col-sm-6 padding-remove-left prs-0 prr-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Qty</label>
                                                                    <input id="qty" name="qty" type="text" value="1" class="form-control" readonly='true' />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-xs-6 col-sm-6 padding-remove-left prs-0">
                                                                <div class="form-group">
                                                                    <label class="form-label">Harga Satuan</label>
                                                                    <input id="harga" name="harga" type="text" value="" class="form-control"
                                                                           readonly='true' />
                                                                </div>
                                                            </div>                                                                                  
                                                            <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                                                <div class="form-group">
                                                                    <button id="btn-save-item" onClick="" class="btn btn-default btn-small" type="button"
                                                                            style="margin-top:22px;">
                                                                        <i class="fas fa-plus-square"></i>
                                                                        Tambah
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </form>
                                            </div>                      
                                            <div class="col-md-12 col-xs-12 col-sm-12 table-responsive padding-remove-side scroll">
                                                <table id="table-item" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                                                    <thead>
                                                        <tr>
                                                            <th>Produk</th>
                                                            <th style="text-align:right;">Qty / Unit</th>
                                                            <th style="text-align:right;">Harga</th>
                                                            <th style="text-align:right;">Total</th>
                                                            <th style="text-align:left;">Lokasi Penempatan</th>                              
                                                            <th>#</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                        <div class="form-group">
                                                            <label class="col-md-5 padding-remove-side form-label">Total Produk</label>
                                                            <div class="col-md-7 prs-0">
                                                                <input id="total_produk" name="total_produk" type="text" value="0" class="form-control"
                                                                       style="text-align:right;" readonly='true' />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                      <div class="form-group">
                                                        <label class="col-md-5">Diskon %</label>
                                                        <div class="col-md-7">
                                                          <input id="diskon" name="diskon" type="text" value="0" class="form-control" style="cursor:pointer;text-align:right;" readonly='true'/>
                                                        </div>
                                                      </div>                            
                                                    </div>
                                                    -->
                                                    <div class="col-md-12-col-xs-12 col-sm-12 padding-remove-left prs-0">
                                                        <div class="form-group">
                                                            <label class="form-label">Keterangan</label>
                                                            <textarea id="keterangan" name="keterangan" type="text" value="" class="form-control" rows="4"></textarea>
                                                        </div>
                                                    </div>                                                                 
                                                </div>    
                                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                    <!--
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                      <div class="form-group">
                                                        <label class="col-md-5">Subtotal</label>
                                                        <div class="col-md-7">
                                                          <input id="subtotal" name="subtotal" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    -->

                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                        <div class="col-md-12 col-xs-6 col-sm-6 padding-remove-side prs-0 prr-2" style="margin-bottom:4px;">
                                                            <div class="form-group">
                                                                <label class="col-md-5 form-label prs-0">Subtotal</label>
                                                                <div class="col-md-7 prs-0">
                                                                    <input id="subtotal" name="subtotal" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-xs-6 col-sm-6 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                            <div class="form-group">
                                                                <label class="col-md-5 form-label prs-0">Total (Rp)</label>
                                                                <div class="col-md-7 prs-0">
                                                                    <input id="total" name="total" type="text" value="0" class="form-control"
                                                                           style="text-align:right;" readonly='true' />
                                                                </div>
                                                            </div>
                                                        </div>                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"
                                                     style="margin-top: 10px;margin-bottom:10px;">
                                                    <div class="form-group">
                                                        <div class="pull-left">                            
                                                            <button id="btn-journal" class="btn btn-default btn-small" type="button">
                                                                <i class="fas fa-clipboard-check"></i>
                                                                Jurnal Entri
                                                            </button>                                                                                                                 
                                                        </div>
                                                        <div class="pull-right">
                                                            <button id="btn-cancel" class="btn btn-warning btn-small" type="reset"
                                                                    style="display: inline;">
                                                                <i class="fas fa-times"></i>
                                                                Batal
                                                            </button>
                                                            <button id="btn-save" class="btn btn-primary btn-small" type="button"
                                                                    style="display: inline;">
                                                                <i class="fas fa-save"></i>
                                                                Simpan
                                                            </button>
                                                            <!--
                                                            <button id="btn-edit" class="btn btn-default btn-small" type="button"
                                                              style="display: inline;">
                                                              <i class="fas fa-edit"></i>
                                                              Ubah
                                                            </button>
                                                            -->
                                                            <button id="btn-update" class="btn btn-default btn-small" type="button"
                                                                    style="display: none;" data-id="0">
                                                                <i class="fas fa-check-square"></i>
                                                                Perbarui
                                                            </button>                              
                                                            <button id="btn-print" class="btn btn-default btn-small" type="button" data-id="0" data-number="0"
                                                                    style="display: none;" data-id="0">
                                                                <i class="fas fa-print"></i>
                                                                Cetak
                                                            </button>                                                            
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
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                    <div class="grid simple">
                        <div class="grid-body">
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
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-5" style="padding-top:8px;">
                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-0 prr-2">
                                        <label class="form-label">Periode Awal</label>
                                        <div class="col-md-12 col-sm-12 padding-remove-side prl-5">
                                            <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                <input name="start" id="start" type="text" class="form-control input-sm" readonly="true"
                                                       value="<?php echo $first_date; ?>">
                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-0">
                                        <label class="form-label">Periode Akhir</label>
                                        <div class="col-md-12 col-sm-12 padding-remove-side prl-5">
                                            <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                <input name="end" id="end" type="text" class="form-control input-sm" readonly="true"
                                                       value="<?php echo $end_date; ?>">
                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 form-group prs-0 prs-5">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                            <label class="form-label">Gudang</label>
                                            <select id="filter_location" name="filter_location" class="form-control">
                                                <option value="0">-- Semua --</option>
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 form-group padding-remove-left">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                            <label class="form-label">Karyawan</label>
                                            <select id="filter_kontak" name="filter_kontak" class="form-control">
                                                <option value="0">-- Semua --</option>
                                            </select>
                                        </div>
                                    </div>                    
                                    <div class="col-lg-9 col-md-9 col-xs-6 col-sm-6 form-group padding-remove-right">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prr-5">                                        
                                            <label class="form-label">Cari</label>
                                            <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                        </div>
                                    </div>                                 
                                    <div class="col-lg-3 col-md-3 col-xs-6 col-sm-6 form-group prs-0">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prr-5">                                        
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
                                        <table id="table-data" class="table table-bordered" data-limit-start="0" data-limit-end="10" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Nomor</th>
                                                    <th>Penangung Jawab</th>
                                                    <th>Total Nilai Barang</th>                    
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
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