<!-- 
    
    Uang Muka Penjualan
    cloning from finance/cash_in

-->
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
    .cashback_checkbox{
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
    .cashback_checkbox:before{
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
    .cashback_checkbox:after{
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
    .ios-toggle:checked + .cashback_checkbox{
        /*box-shadow*/
        -webkit-box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
        -moz-box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
        box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
    }
    .ios-toggle:checked + .cashback_checkbox:before{
        left:calc(100% - 24px);
        /*box-shadow*/
        -webkit-box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
        -moz-box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
        box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
    }
    .ios-toggle:checked + .cashback_checkbox:after{
        /*content:attr(data-on);*/
        left:60px;
        width:36px;
    }    
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
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-5">
                    <div id="div-form-trans" style="display:none;" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-5">
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
                                                <input id="journal_session" name="journal_session" type="hidden" value="0" placeholder="id" readonly>                        
                                            </div>
                                            <div class="col-md-5 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label">Setor Ke *</label>
                                                    <select id="account_kredit" name="account_kredit" class="form-control" disabled readonly>
                                                        <option value="0">-- Pilih / Cari Akun yg di Setor --</option>
                                                    </select>
                                                </div>
                                            </div>        
                                            <div class="clearfix"></div>           
                                            <div class="col-md-5 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label">Terima Dari *</label>
                                                    <select id="kontak" name="kontak" class="form-control" disabled readonly>
                                                        <option value="0">-- Pilih / Cari --</option>
                                                    </select>
                                                </div>
                                            </div>     
                                            <div class="col-md-2 col-xs-12 form-group">
                                                <label class="form-label">Tanggal Transaksi</label>
                                                <div class="col-md-12 col-sm-12 padding-remove-side prs-0">
                                                    <div class="input-append success date col-md-12 col-lg-12 no-padding prs-0">
                                                        <input name="tgl" id="tgl" type="text" class="form-control" readonly="true"
                                                               value="<?php echo $end_date; ?>">
                                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>
                                            </div>                                       
                                            <div class="col-md-3 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label">Nomor</label>
                                                    <input id="nomor" name="nomor" type="text" value="" class="form-control" readonly='true' placeholder="Nomor Otomatis" />
                                                </div>
                                            </div> 
                                            <div class="clearfix"></div>
                                                           
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
                                                                <label class="form-label">Akun Perkiraan / Kredit *</label>
                                                                <select id="account_debit_account" name="account_debit_account" class="form-control" disabled readonly>
                                                                    <option value="0">-- Pilih / Cari Akun yg di Golongkan --</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-7 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                            <div class="col-md-7 col-xs-12 col-sm-12 prs-0">
                                                                <div class="form-group">
                                                                    <label class="form-label">Keterangan</label>
                                                                    <input id="account_debit_note" name="account_debit_note" type="text" value="" class="form-control"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-left prs-0">
                                                                <div class="form-group">
                                                                    <label class="form-label">Jumlah</label>
                                                                    <input id="account_debit_total" name="account_debit_total" type="text" value="" class="form-control"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
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
                                                                        <th>Akun Perkiraan Kredit</th>
                                                                        <th style="text-align:left;">Keterangan</th>
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
                                                        <div class="col-md-12-col-xs-12 col-sm-12 padding-remove-left">
                                                            <div class="form-group">
                                                                <label class="form-label">Keterangan</label>
                                                                <textarea id="keterangan" name="keterangan" type="text" value="" class="form-control" rows="4"></textarea>
                                                            </div>
                                                        </div>                          
                                                    </div>    
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-remove-right">
                                                                <div class="form-group">
                                                                    <label class="form-label" id="asset-checkbox-label">Aktifkan Cashback</label>
                                                                    <div class="toggles">
                                                                        <input type="checkbox" name="checkbox" id="cashback_flag" class="ios-toggle"/>
                                                                        <label class="cashback_checkbox" data-flag="0"></label>	
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 prs-0">
                                                                <div class="form-group">
                                                                    <label class="form-label">Jumlah Cashback</label>
                                                                    <input id="cashback_debit" name="cashback_debit" type="text" value="" class="form-control" readonly='true' placeholder=""/>
                                                                </div>
                                                            </div>     
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="form-label col-md-5 prs-0">Akun Penampung Cashback</label>
                                                                    <div class="col-md-7 prs-0">
                                                                        <select id="cashback_account" name="cashback_account" class="form-control" disabled>
                                                                            <option value="0">Pilih</option>
                                                                            <?php 
                                                                            foreach($account_cashback as $v){
                                                                                echo '<option value="'.$v['account_id'].'">'.$v['account_code'].' - '.$v['account_name'].'</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>           
                                                            </div>	    
                                                        </div>   
                                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="form-label col-md-5 prs-0">Total Rincian Item</label>
                                                                    <div class="col-md-7 prs-0">
                                                                        <input id="total_item" name="total_item" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="form-label col-md-5 prs-0">Subtotal</label>
                                                                    <div class="col-md-7 prs-0">
                                                                        <input id="subtotal" name="subtotal" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="form-label col-md-5 prs-0">Total (Rp)</label>
                                                                    <div class="col-md-7 prs-0">
                                                                        <input id="total_credit" name="total_credit" type="text" value="0" class="form-control"
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
                                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-12 form-group padding-remove-right prs-5">
                                            <label class="form-label">Periode Akhir</label>
                                            <div class="col-md-12 col-sm-12 padding-remove-side">
                                                <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                    <input name="end" id="end" type="text" class="form-control input-sm" readonly="true"
                                                           value="<?php echo $end_date; ?>">
                                                    <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-right prs-5">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label class="form-label">Diterima Dari</label>
                                                    <select id="filter_kontak" name="filter_kontak" class="form-control">
                                                        <option value="0">-- Cari Kontak --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>                                  
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-right prs-5">
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
                                                        <th>Terima Dari</th>
                                                        <th>Setor Ke Akun</th>                          
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
        <div class="tab-pane" id="tab2">

        </div>
    </div>
</div>
</div>
<div class="modal fade" id="modal-contact" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-master" name="form-master" method="" action="">         
                <div class="modal-header" style="background-color: #ffffff;">
                    <h4 style="">Buat Kontak Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <p></p>
                            <p class="text-center">
                                <i class="fas fa-user-plus fa-5x"></i>
                            </p>
                        </div>
                        <div class="col-md-9 col-xs-12"> 
                            <div class="col-md-6 col-sm-12 col-xs-12">              
                                <div class="col-lg-5 col-md-5 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Kode</label>
                                        <input id="kode_contact" name="kode_contact" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input id="nama_contact" name="nama_contact" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <!--
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Perusahaan</label>
                                        <input id="perusahaan_contact" name="perusahaan_contact" type="text" value="" class="form-control"/>
                                    </div>
                                </div>  
                                -->                    
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Telepon</label>
                                        <input id="telepon_1_contact" name="telepon_1_contact" type="text" value="" class="form-control"/>
                                    </div>                          
                                </div>                                                           
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">

                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea id="alamat_contact" name="alamat_contact" type="text" value="" class="form-control"rows="8"/></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input id="email_1_contact" name="email_1_contact" type="text" value="" class="form-control"/>
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
    </div>
</div>
<?php #include 'modal.php'; ?>