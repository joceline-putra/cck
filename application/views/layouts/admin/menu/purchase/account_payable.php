<style>
    .scroll {
        margin-top: 4px;
        margin-bottom: 8px;
        margin-left: 4px;
        margin-right: 4px;
        padding: 4px;
        /*background-color: green; */
        width: 100%;
        height: 250px;
        overflow-x: hidden;
        overflow-y: auto;
        text-align: justify;
    }
    .btn-modal-riwayat-pembayaran{
        text-decoration: underline;
    }
    .form-trans-item-input{
        text-align:right;
    }
</style>
<style>
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
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                    <div id="div-form-trans" style="display:none;" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
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
                                        <button id="btn-cancel" class="btn btn-small" type="reset"
                                                style="display: inline;">
                                            <i class="fas fa-times"></i>
                                            Tutup
                                        </button>                    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                        <form id="form-trans" name="form-trans-item" method="" action="">
                                            <div class="col-md-12">
                                                <input id="id_document" name="id_document" type="hidden" value="0" placeholder="id" readonly>
                                            </div>
                                            <div class="col-md-5 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label">Penerima *</label>
                                                    <select id="kontak" name="kontak" class="form-control" disabled readonly>
                                                        <option value="0">-- Cari Kontak --</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label">Cara Pembayaran *</label>
                                                    <select id="cara_pembayaran" name="cara_pembayaran" class="form-control">
                                                        <option value="0">-- Pilih --</option>
                                                        <option value="1">Tunai</option>
                                                        <option value="2">Transfer</option>
                                                        <option value="6">Cek & Giro</option>
                                                        <!-- <option value="4">Kartu Kredit</option> -->
                                                    </select>
                                                </div>
                                            </div>                                                          
                                            <div class="col-md-4 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label">Bayar Dari *</label>
                                                    <select id="account_kredit" name="account_kredit" class="form-control" disabled readonly>
                                                        <option value="0">-- Cari Sumber Keluar--</option>
                                                    </select>
                                                </div>
                                            </div>        
                                            <div class="clearfix"></div>                                     
                                            <div class="col-md-2 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label class="form-label">Tanggal Transaksi</label>
                                                    <div class="col-md-12 col-sm-12 padding-remove-side prs-0">
                                                        <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                            <input name="tgl" id="tgl" type="text" class="form-control" readonly="true"
                                                                value="<?php echo $end_date; ?>">
                                                            <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                       
                                            <div class="col-md-3 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label">Nomor</label>
                                                    <input id="nomor" name="nomor" type="text" value="" class="form-control" readonly='true' placeholder="Nomor Otomatis" />
                                                </div>
                                            </div> 

                                            <div class="col-md-12 col-sm-12 col-xs-12">

                                                <div class="col-md-12 col-xs-12 col-sm-12" style="padding-left: 0;
                                                     padding-right: 0;
                                                     border-top:2px solid #e5e9ec;
                                                     margin-top:20px;
                                                     margin-bottom: 20px;
                                                     padding-top:10px;
                                                     padding-bottom:10px;
                                                     border-bottom:2px solid #e5e9ec;">
                                                    <h5><b>Rincian <?php echo $title; ?></b></h5>
                                                    <!--
                                                    <form id="form-trans-item" name="form-trans-item" method="" action="">
                                                      <div class="col-md-12">
                                                        <input id="id_document_item" name="id_document_item" type="hidden" value="" placeholder="id"
                                                          readonly>
                                                      </div>                          
                                                      <div class="col-md-5 col-xs-12 col-sm-12 padding-remove-side">
                                                        <div class="form-group">
                                                          <label>Akun Pembayaran *</label>
                                                          <select id="account_debit_account" name="account_debit_account" class="form-control" disabled readonly>
                                                            <option value="0">-- Pilih / Cari --</option>
                                                          </select>
                                                        </div>
                                                      </div>
                                                      <div class="col-md-7 col-xs-12 col-sm-12 padding-remove-side">
                                                        <div class="col-md-7 col-xs-12 col-sm-12">
                                                          <div class="form-group">
                                                            <label>Keterangan</label>
                                                            <input id="account_debit_note" name="account_debit_note" type="text" value="" class="form-control"/>
                                                          </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-left">
                                                          <div class="form-group">
                                                            <label>Jumlah</label>
                                                            <input id="account_debit_total" name="account_debit_total" type="text" value="" class="form-control"/>
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
                                                    </form>   
                                                    -->
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> <!-- class="scroll"-->
                                                        <div class="table-responsive">
                                                            <table id="table-item" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nomor Pembelian</th>
                                                                        <th style="text-align:left;">Keterangan</th>
                                                                        <th>Tgl Jatuh Tempo</th>                                  
                                                                        <th style="text-align:right;">Total</th>
                                                                        <th style="text-align:right;">Sisa Tagihan</th>                                  
                                                                        <th style="text-align:right;">Jumlah Dibayar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="table-item-tbody">
                                                                </tbody>
                                                                <tfoot id="table-item-tfoot">
                                                                </tfoot>                              
                                                            </table>
                                                        </div>
                                                    </div>                                                                           
                                                </div>
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side prs-00">
                                                        <div class="col-md-12-col-xs-12 col-sm-12 padding-remove-left">
                                                            <div class="form-group">
                                                                <label class="form-label">Keterangan</label>
                                                                <textarea id="keterangan" name="keterangan" type="text" value="" class="form-control" rows="4"></textarea>
                                                            </div>
                                                        </div>                          
                                                    </div>    
                                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side prs-0">

                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                            <!-- 
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                              <div class="form-group">
                                                                <label class="col-md-5">Total Rincian Item</label>
                                                                <div class="col-md-7">
                                                                  <input id="total_item" name="total_item" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                </div>
                                                              </div>
                                                            </div>
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                              <div class="form-group">
                                                                <label class="col-md-5">Subtotal</label>
                                                                <div class="col-md-7">
                                                                  <input id="subtotal" name="subtotal" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                </div>
                                                              </div>
                                                            </div> -->
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="col-md-5 form-label">Total Sisa Tagihan (Rp)</label>
                                                                    <div class="col-md-7">
                                                                        <input id="total_sisa" name="total_sisa" type="text" value="0" class="form-control"
                                                                               style="text-align:right;" readonly='true' />
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="col-md-5 form-label">Total Jumlah yg Dibayar (Rp)</label>
                                                                    <div class="col-md-7">
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
                                                                    Simpan Pembayaran Hutang
                                                                </button>
                                                                <button id="btn-update" class="btn btn-default btn-small" type="button"
                                                                        style="display: none;" data-id="0">
                                                                    <i class="fas fa-check-square"></i>
                                                                    Perbarui
                                                                </button>                              
                                                                <button id="btn-print" class="btn btn-default btn-small" type="button"
                                                                        style="display: none;" data-id="0">
                                                                    <i class="fas fa-print"></i>
                                                                    Cetak
                                                                </button>                                    
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-5">
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
                                        <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                            <h5><b>Data <?php echo $title; ?></b></h5>
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-12">
                                            <div class="pull-right">
                                                <!--
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
                                                -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-5">
                                            <label class="form-label">Periode Awal</label>
                                            <div class="col-md-12 col-sm-12 padding-remove-side">
                                                <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                    <input name="start" id="start" type="text" class="form-control input-sm" readonly="true"
                                                           value="<?php echo $first_date; ?>">
                                                    <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-5">
                                            <label class="form-label">Periode Akhir</label>
                                            <div class="col-md-12 col-sm-12 padding-remove-side">
                                                <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                    <input name="end" id="end" type="text" class="form-control input-sm" readonly="true"
                                                           value="<?php echo $end_date; ?>">
                                                    <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                </div>
                                            </div>
                                        </div>     
                                        <div class="col-lg-6 col-md-3 col-xs-7 col-sm-7 form-group padding-remove-right prs-5">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label">Cari</label>
                                                <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                            </div>
                                        </div>                                
                                        <div class="col-lg-2 col-md-2 col-xs-5 col-sm-5 form-group prs-5">
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
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="">
                                        <!--
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                                            <label class="form-label">Status</label>
                                            <select id="filter_paid_" name="filter_paid_" class="form-control">
                                                <option value="ALL">Lunas/Belum</option>
                                                <option value="1">Lunas</option>
                                                <option value="0">Belum Lunas</option>
                                            </select>
                                            </div> 
                                        -->
                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right prs-5">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label">Metode Pembayaran</label>
                                                <select id="filter_paid_type" name="filter_length" class="form-control">
                                                    <option value="0">Semua</option>
                                                    <option value="1">Tunai / Kas</option>
                                                    <option value="2">Transfer</option>
                                                    <option value="3">Kartu Kredit</option>
                                                    <option value="4">Kartu Debit</option>
                                                    <option value="5">Digital Payment</option>
                                                    <option value="6">Cek & Giro</option>                        
                                                </select>
                                            </div>
                                        </div>   
                                        <div class="col-md-6 col-xs-12 col-sm-12  prs-5">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label class="form-label">Supplier Penerima</label>
                                                    <select id="filter_kontak" name="filter_kontak" class="form-control">
                                                        <option value="0">-- Cari Kontak --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>                     
                                        <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-left prs-5">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label class="form-label">Bayar Menggunakan</label>
                                                    <select id="filter_account" name="filter_account" class="form-control">
                                                        <option value="0">Semua</option>
                                                    </select>
                                                </div>
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
                                                        <th>Bayar Kepada Supplier</th>
                                                        <th>Total</th>
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
            </div>
        </div>
        <div class="tab-pane" id="tab2">
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="modal-trans-diskon" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ffffff;">
                <h4 style="">Pasang Diskon</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <style>
                            .btn-diskon {
                                cursor: pointer;
                                padding-bottom: 20px;
                            }

                            .btn-diskon>div {
                                background-color: #f94545;
                                height: 100px;
                            }

                            .btn-diskon:hover>div {
                                background-color: #616161;
                                height: 100px;
                            }

                            .btn-diskon>div>h4 {
                                padding-top: 28px;
                                font-size: 42px;
                                font-weight: 800;
                                color: white;
                                text-align: center;
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
            <div class="modal-header" style="background-color: #ffffff;">
                <h4 style="">Sukses Menyimpan</h4>
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
            <div class="modal-header" style="background-color: #ffffff;">
                <h4 style="">Tambahkan Catatan</h4>
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
<div class="modal fade" id="modal-journal-item-edit" role="dialog" style="" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ffffff;">
                <h4 id="modal-journal-item-edit-title" style="">Edit Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 col-xs-12">
                        <p></p>
                        <p class="text-center">
                            <i class="fas fa-edit fa-8x"></i>
                        </p>
                    </div>
                    <div class="col-md-10 col-xs-12">
                        <!-- Disini -->
                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                            <form id="form-edit-item" name="form-journal-item" method="" action="">
                                <div class="col-md-12">
                                  <!-- <input id="id_document_item" name="id_document_item" type="hidden" value="" placeholder="id" readonly> -->
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Akun *</label>
                                            <select id="e_account" name="e_account" class="form-control" disabled readonly>
                                                <option value="0">-- Cari Akun--</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                        <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label>Keterangan</label>
                                                <input id="e_note" name="e_note" type="text" value="" class="form-control" readonly='true'/>
                                            </div>
                                        </div>                   
                                        <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label>Jumlah</label>
                                                <input id="e_total_debit" name="e_total_debit" type="text" value="" class="form-control" readonly='true'/>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-left">
                                          <div class="form-group">
                                            <label>Kredit</label>
                                            <input id="e_total_credit" name="e_total_credit" type="text" value="" class="form-control" readonly='true'/>
                                          </div>
                                        </div> 
                                        -->                   
                                        <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <button id="btn-update-item" data-journal-item-id="0" onClick="" class="btn btn-primary btn-small" type="button" style="margin-top:22px;">
                                                    <i class="fas fa-check-square"></i>
                                                    Perbarui
                                                </button> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                        <!--  End Disini -->
                    </div>
                </div>                                         
            </div>
            <!--
            <div class="modal-footer flex-center">
              <button id="btn-save-item-discount" class="btn btn-success" data-id="">
                <i class="fas fa-print white"></i> Pasang
              </button>
              <button type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Tutup</button>        
            </div>
            -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-contact" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-master" name="form-master" method="" action="">         
                <div class="modal-header" style="background-color: #ffffff;">
                    <h4 style="">Buat Supplier Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <p></p>
                            <p class="text-center">
                                <i class="fas fa-user-plus fa-4x"></i>
                            </p>
                        </div>
                        <div class="col-md-9 col-xs-12"> 
                            <div class="col-md-6 col-sm-12 col-xs-12">              
                                <div class="col-lg-5 col-md-5 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Kode</label>
                                        <input id="kode" name="kode" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input id="nama" name="nama" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Perusahaan</label>
                                        <input id="perusahaan" name="perusahaan" type="text" value="" class="form-control"/>
                                    </div>
                                </div>                      
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Telepon</label>
                                        <input id="telepon_1" name="telepon_1" type="text" value="" class="form-control"/>
                                    </div>                          
                                </div>                                                           
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">

                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea id="alamat" name="alamat" type="text" value="" class="form-control"rows="8"/></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input id="email_1" name="email_1" type="text" value="" class="form-control"/>
                                    </div>                          
                                </div>                                              
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-save-contact" onClick="" class="btn btn-primary btn-small" type="button" style="">
                        <i class="fas fa-save"></i>                                 
                        Simpan Kontak Baru
                    </button>    
                    <button class="btn btn-outline-danger waves-effect btn-small" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i>                                 
                        Batal
                    </button>                   
                </div>
            </form>      
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-account" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-account" name="form-account" method="" action="">         
                <div class="modal-header" style="background-color: #ffffff;">
                    <h4 style="">Buat Akun Perkiraan Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <p></p>
                            <p class="text-center">
                                <i class="fas fa-balance-scale fa-4x"></i>
                            </p>
                        </div>
                        <div class="col-md-9 col-xs-12"> 
                            <div class="col-md-12 col-sm-12 col-xs-12">              
                                <div class="col-lg-5 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Kode Akun Perkiraan</label>
                                        <input id="kode-akun" name="kode-akun" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Nama Akun Perkiraan</label>
                                        <input id="nama-akun" name="nama-akun" type="text" value="" class="form-control"/>
                                    </div>
                                </div>                                                           
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-save-account" onClick="" class="btn btn-primary btn-small" type="button" style="">
                        <i class="fas fa-save"></i>                                 
                        Simpan Akun Perkiraan Baru
                    </button>    
                    <button class="btn btn-outline-danger waves-effect btn-small" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i>                                 
                        Batal
                    </button>                   
                </div>
            </form>      
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-riwayat" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-trans" name="form-trans" method="" action="">         
                <div class="modal-header" style="background-color: #ffffff;">
                    <h4 style="" id="modal-riwayat-title">Modal Title</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="modal-riwayat-body" class="col-md-12 col-xs-12"> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <!-- <button id="" onClick="" class="btn btn-primary btn-small" type="button" style="">
                      <i class="fas fa-save"></i>                                 
                      Button
                    </button> -->    
                    <button class="btn btn-outline-danger waves-effect btn-small" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>                   
                </div>
            </form>      
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>