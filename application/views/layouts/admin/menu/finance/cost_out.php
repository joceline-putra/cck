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
        <?php #include '_navigation.php'; ?>
        <ul class="nav nav-tabs" role="tablist" style="display:inline;">           
            <li class="active">
                <a href="#tab2" role="tab" class="btn-tab-2" data-toggle="tab" aria-expanded="false">
                <span class="fas fa-calendar-alt"></span> Form Biaya</a>
            </li>     
            <li class="">
                <a href="#tab1" role="tab" class="btn-tab-1" data-toggle="tab" aria-expanded="true" style="cursor:pointer;">
                <span class="fas fa-plus-square"></span> Data Biaya</a>
            </li>                                             
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab2">
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-5">
                    <div id="div-form-trans" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-5">
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
                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right prs-0">
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
                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                        <form id="form-trans" name="form-trans-item" method="" action="">
                                            <div class="col-md-12">
                                                <input id="id_document" name="id_document" type="hidden" value="0" placeholder="id" readonly>
                                                <input id="journal_session" name="journal_session" type="hidden" value="0" placeholder="id" readonly>                        
                                            </div>
                                            <div class="col-md-5 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label">Cara Bayar*</label>
                                                    <select id="account_kredit" name="account_kredit" class="form-control" disabled readonly>
                                                        <option value="0">-- Pilih --</option>
                                                    </select>
                                                </div>
                                            </div>        
                                            <!-- <div class="clearfix"></div>            -->
                                            <!-- <div class="col-md-5 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label">Penerima *</label>
                                                    <select id="kontak" name="kontak" class="form-control" disabled readonly>
                                                        <option value="0">-- Pilih / Cari --</option>
                                                    </select>
                                                </div>
                                            </div>      -->
                                            <div class="col-md-2 col-xs-6 form-group">
                                                <label class="form-label">Tanggal Transaksi</label>
                                                <div class="col-md-12 col-sm-12 padding-remove-side prs-0">
                                                    <div class="input-append success date col-md-12 col-lg-12 no-padding prs-0">
                                                        <input name="tgl" id="tgl" type="text" class="form-control" readonly="true"
                                                               value="<?php echo $end_date; ?>">
                                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>
                                            </div>                                       
                                            <div class="col-md-3 col-xs-12 col-sm-12 prl-0">
                                                <div class="form-group">
                                                    <label class="form-label">Nomor</label>
                                                    <input id="nomor" name="nomor" type="text" value="" class="form-control" readonly='true' placeholder="Nomor Otomatis"/>
                                                </div>
                                            </div>   
                                            <div class="clearfix"></div>
                                            <div class="col-md-12-col-xs-12 col-sm-12 prl-0">
                                                <div class="form-group">
                                                    <label class="form-label">Keterangan</label>
                                                    <textarea id="keterangan" name="keterangan" type="text" value="" class="form-control" rows="4"></textarea>
                                                </div>
                                            </div>                                              
                                            <!-- 
                                            <div class="col-md-2 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label">Cara Pembayaran *</label>
                                                    <select id="cara_pembayaran" name="cara_pembayaran" class="form-control" disabled readonly>
                                                        <option value="0">-- Pilih --</option>
                                                        <option value="1">Tunai</option>
                                                        <option value="2">Transfer</option>
                                                        <option value="3">Cek & Giro</option>
                                                        <option value="4">Kartu Kredit</option>
                                                    </select>
                                                </div>
                                            </div>
                                            -->                                       
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
                                                    <form id="form-trans-item" name="form-trans-item" method="" action="">
                                                        <div class="col-md-12">
                                                            <input id="id_document_item" name="id_document_item" type="hidden" value="" placeholder="id"
                                                                   readonly>
                                                        </div>                          
                                                        <div class="col-md-5 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                            <div class="form-group">
                                                                <label class="form-label">Jenis Biaya *</label>
                                                                <select id="account_debit_account" name="account_debit_account" class="form-control" disabled readonly>
                                                                    <option value="0">-- Pilih --</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-7 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                            <!-- <div class="col-md-7 col-xs-12 col-sm-12 prs-0">
                                                                <div class="form-group">
                                                                    <label class="form-label">Keterangan</label>
                                                                    <input id="account_debit_note" name="account_debit_note" type="text" value="" class="form-control"/>
                                                                </div>
                                                            </div> -->
                                                            <div class="col-md-3 col-xs-12 col-sm-12 prs-0">
                                                                <div class="form-group">
                                                                    <label class="form-label">Jumlah</label>
                                                                    <input id="account_debit_total" name="account_debit_total" type="text" value="" class="form-control"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left prs-0">
                                                                <div class="form-group">
                                                                    <button id="btn-save-item" onClick="" class="btn btn-default btn-small" type="button"
                                                                            style="margin-top:22px;width:100%;">
                                                                        <i class="fas fa-plus-square"></i>
                                                                        Tambah
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </form>   
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side scroll prs-0">
                                                        <div class="table-responsive"> 
                                                            <table id="table-item" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Jenis Biaya</th>
                                                                        <!-- <th style="text-align:left;">Keterangan</th> -->
                                                                        <th style="text-align:right;">Jumlah</th>
                                                                        <th>#</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>                                                                           
                                                </div>
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side prs-0">                        
                                                    </div>    
                                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side prs-0">

                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="col-md-5 prs-0">Total Rincian Item</label>
                                                                    <div class="col-md-7 prs-0">
                                                                        <input id="total_item" name="total_item" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="col-md-5 prs-0">Subtotal</label>
                                                                    <div class="col-md-7 prs-0">
                                                                        <input id="subtotal" name="subtotal" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="col-md-5 prs-0">Total (Rp)</label>
                                                                    <div class="col-md-7 prs-0">
                                                                        <input id="total_debit" name="total_debit" type="text" value="0" class="form-control"
                                                                               style="text-align:right;" readonly='true' />
                                                                    </div>
                                                                </div>
                                                            </div>                                                        
                                                        </div>
                                                    </div>
                                                </div>                          
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0"
                                                         style="margin-top: 10px;margin-bottom:10px;">
                                                        <div class="form-group">
                                                            <div class="pull-left">                            
                                                                <button id="btn-journal" class="hide btn btn-default btn-small" type="button">
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
                </div>
            </div>    
            <div class="tab-pane" id="tab1">
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-5">
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-5">
                        <div class="grid simple">
                            <div class="grid-body">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                        <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                            <h5><b>Data <?php echo $title; ?></b></h5>
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-12">
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
                                        <!-- <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-right prs-5">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label class="form-label">Penerima *</label>
                                                    <select id="filter_kontak" name="filter_kontak" class="form-control">
                                                        <option value="0">-- Cari Kontak --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>                                   -->
                                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label">Cabang</label>
                                                <select id="filter_branch" name="filter_branch" class="form-control">
                                                    <option value="All">Semua</option>
                                                    <?php
                                                    foreach($branch as $val){
                                                        echo '<option value="'.$val['branch_id'].'">'.$val['branch_name'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 form-group padding-remove-right prs-5">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">                                            
                                                <label class="form-label">Cari</label>
                                                <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                            </div>
                                        </div>                                 
                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group prs-5">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
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
                                    <div class="col-md-12 col-xs-12 col-sm-12 prs-5">
                                        <div class="table-responsive"> 
                                            <table id="table-data" class="table table-bordered" data-limit-start="0" data-limit-end="10"
                                                style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Nomor</th>
                                                        <th>Cabang</th>                                                        
                                                        <th>Bayar Menggunakan</th>
                                                        <th>Keterangan</th>
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
    </div>
</div>
</div>
<?php include 'modal.php'; ?>