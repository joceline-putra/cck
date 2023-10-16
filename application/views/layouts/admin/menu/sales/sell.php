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
    #div_payment_method{

    }
    #div_payment_method > div{
        border:1px solid black;
        padding-top: 8px;
        padding-bottom: 4px;
    }    
    #div_payment_method > div:hover b{
        font-size:14px;
    }        
    #div_payment_method > a{

    }
    #div_payment_method > a:hover{

    }
    #div_payment_method a > div{
        text-align:center;
        
    }
    #div_payment_method img{
        margin:0 auto;
    }    
</style>
<style>
    /* Large desktops and laptops */
    @media (min-width: 1200px) {
        /* .prs-0{
            padding-left: 0px!important;
            padding-right: 0px!important;    
        }
        .prs-5{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }   
        .prs-2{
            padding-left: 2.5px!important;
            padding-right: 2.5px!important;    
        }              
        .prl-2{
            padding-left: 2.5px!important;
        }
        .prr-2{
            padding-right: 2.5px!important;
        }   */
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
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-5">
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
                                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                        <form id="form-trans" name="form-trans" method="" action="">
                                                            <input id="tipe" type="hidden" value="<?php echo $identity; ?>">
                                                            <div class="col-md-12 col-xs-12 col-sm-12 prs-0">
                                                                <input id="id_document" name="id_document" type="hidden" value="0" placeholder="id" readonly>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-xs-12 prs-0">
                                                                <div class="col-md-4 col-sm-12 col-xs-12 padding-remove-left prs-5">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Kepada Customer *</label>
                                                                                <select id="kontak" name="kontak" class="form-control" disabled readonly>
                                                                                    <option value="0">-- Pilih / Cari --</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Alamat Customer</label>
                                                                                <textarea id="alamat" name="alamat" class="form-control" rows="2" style="width:100%;height:68px!important;"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-5">
                                                                        <div class="col-md-7 col-xs-6 col-sm-12 padding-remove-left prs-5">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Email</label>
                                                                                <input id="email" name="email" type="text" value="" class="form-control"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-5 col-xs-6 col-sm-12 padding-remove-side">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Telepon</label>
                                                                                <input id="telepon" name="telepon" type="text" value="" class="form-control"/>
                                                                            </div>
                                                                        </div>                                    
                                                                    </div>                                                                    
                                                                </div>
                                                                <div class="col-md-4 col-sm-12 col-xs-12 padding-remove-side prs-5">
                                                                    <div class="col-md-6 col-xs-6 col-sm-12 padding-remove-side prs-5">
                                                                        <div class="col-md-12 col-xs-12 form-group prs-0">
                                                                            <label class="form-label">Tgl Penjualan</label>
                                                                            <div class="col-md-12 col-sm-12 padding-remove-side">
                                                                                <div class="input-append success date col-md-12 col-lg-12 no-padding prs-0">
                                                                                    <input name="tgl" id="tgl" type="text" class="form-control" readonly="true"
                                                                                           value="<?php echo $end_date; ?>" data-value="">
                                                                                    <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                    <div class="col-md-6 col-xs-6 col-sm-12 padding-remove-side prs-5">
                                                                        <div class="col-md-12 col-xs-12 form-group prs-0">
                                                                            <label class="form-label">Tgl Jth Tempo</label>
                                                                            <div class="col-md-12 col-sm-12 padding-remove-side">
                                                                                <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                                                    <input name="tgl_tempo" id="tgl_tempo" type="text" class="form-control" readonly="true"
                                                                                           value="<?php echo $end_date_due; ?>" data-value="">
                                                                                    <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                    <div class="col-md-12 col-xs-6 col-sm-12 padding-remove-side prs-5">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 form-group prs-5">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Syarat Pembayaran</label>
                                                                                <select id="syarat_pembayaran" name="syarat_pembayaran" class="form-control">
                                                                                    <!-- <option value="15">Net 15 Hari</option>
                                                                                    <option value="30" selected>Net 30 Hari (1 bulan)</option>
                                                                                    <option value="60">Net 60 Hari (2 bulan)</option>
                                                                                    <option value="90">Net 90 Hari (3 bulan)</option>
                                                                                    <option value="0">Cash (COD)</option> -->
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 form-group prs-5">
                                                                            <div class="form-group prs-5">
                                                                                <label class="form-label">Gudang Pengambilan Stok</label>
                                                                                <select id="gudang" name="gudang" class="form-control" disabled readonly>
                                                                                    <option value="0">-- Pilih / Cari --</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>                                                                                                         
                                                                </div>
                                                                <div class="col-md-4 col-sm-12 col-xs-12 padding-remove-right prs-5">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-5">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left prs-5">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Nomor Dokumen *</label>
                                                                                <input id="nomor" name="nomor" type="text" value="" class="form-control" placeholder="Otomatis jika dikosongkan" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left prs-5">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Nomor PO Customer</label>
                                                                                <input id="nomor_ref" name="nomor_ref" type="text" value="" class="form-control"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 col-xs-6 col-sm-6 padding-remove-left prs-0">
                                                                            <div class="form-group prs-5">
                                                                                <label class="form-label">Sales By</label>
                                                                                <select id="trans_sales_id" name="trans_sales_id" class="form-control" disabled readonly>
                                                                                    <option value="0">-- Pilih / Cari --</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <!-- <div class="clearfix"></div> -->
                                                                        <div class="col-md-7 col-xs-6 col-sm-6 padding-remove-left prs-0">
                                                                            <div class="form-group prs-5">
                                                                                <label class="form-label">Supir</label>
                                                                                <select id="trans_vehicle_person" name="trans_vehicle_person" class="form-control" disabled readonly>
                                                                                    <option value="0">-- Pilih / Cari --</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-5 col-xs-6 col-sm-12 padding-remove-left prs-5">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Nomor Plat</label>
                                                                                <input id="trans_vehicle_plate_number" name="trans_vehicle_plate_number" type="text" value="" class="form-control"/>
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
                                        <div class="grid-body">
                                            <h5><b>Daftar Item <?php echo $title; ?></b></h5>
                                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                <form id="form-trans-item" name="form-trans-item" method="" action="">
                                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                                        <input id="id_document_item" name="id_document_item" type="hidden" value="" placeholder="id"
                                                               readonly>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                            <div class="col-md-5 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                <div class="form-group">
                                                                    <label class="form-label">Produk *</label>
                                                                    <select id="produk" name="produk" class="form-control" disabled readonly>
                                                                        <option value="0">-- Pilih / Cari --</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-7 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                <div class="col-md-2 col-xs-4 col-sm-12 prs-0 prr-2">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Info Stok</label>
                                                                        <input id="stock" name="stock" type="text" value="" class="form-control"
                                                                               readonly='true'/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-xs-8 col-sm-12 padding-remove-left prs-0 prl-2">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Satuan</label>
                                                                        <input id="satuan" name="satuan" type="text" value="" class="form-control"
                                                                               readonly='true'/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-xs-6 col-sm-12 padding-remove-left prs-0 prr-2">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Qty</label>
                                                                        <input id="qty" name="qty" type="text" value="1" class="form-control" readonly='true'/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-xs-6 col-sm-12 padding-remove-left prs-0 prl-2">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Qty Koli</label>
                                                                        <input id="qty_pack" name="qty_pack" type="text" value="1" class="form-control" readonly='true' />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-left">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Harga Satuan</label>
                                                                        <input id="harga" name="harga" type="text" value="" class="form-control"
                                                                               readonly='true' />
                                                                        <p id="harga_comment" style="color:#34941b;"></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                            <div class="col-md-2 col-xs-6 col-sm-12 padding-remove-left prs-0 prr-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Diskon [%]</label>
                                                                    <input id="diskon_persen" name="diskon_persen" type="text" value="" class="form-control"
                                                                           readonly='true' />
                                                                </div>
                                                            </div>                              
                                                            <div class="col-md-2 col-xs-6 col-sm-12 padding-remove-left prs-0 prl-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Diskon</label>
                                                                    <input id="diskon" name="diskon" type="text" value="" class="form-control"
                                                                           readonly='true' />
                                                                </div>
                                                            </div>                              
                                                            <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                                                <div class="form-group">
                                                                    <label class="form-label">Ppn</label>
                                                                    <select id="ppn_item" name="ppn_item" class="form-control">
                                                                        <?php
                                                                        foreach ($tax as $t):
                                                                            echo "<option value=" . $t['tax_percent'] . ">" . $t['tax_name'] . "</option>";
                                                                        endforeach;
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>                                
                                                            <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                                                <div class="form-group">
                                                                    <label class="form-label">Jumlah</label>
                                                                    <input id="jumlah" name="jumlah" type="text" value="" class="form-control"
                                                                           readonly='true' disabled="true"/>
                                                                </div>
                                                            </div>                                                                                                                
                                                            <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-side">
                                                                <div class="form-group">
                                                                    <button id="btn-save-item" onClick="" class="btn btn-default btn-small" type="button"
                                                                            style="margin-top:22px;width:100%;">
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
                                            <div class="col-md-12 col-xs-12 col-sm-12 prs-0 padding-remove-side scroll">
                                                <div class="table-responsive">
                                                    <table id="table-item" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                                                        <thead>
                                                            <tr>
                                                                <th>Produk</th>
                                                                <th style="text-align:right;">Qty / Unit</th>
                                                                <th style="text-align:right;">Harga</th>     
                                                                <th style="text-align:left;">Lokasi Pengambilan</th>
                                                                <th style="text-align:right;">Koli</th>                              
                                                                <th style="text-align:right;">Diskon</th>
                                                                <th style="text-align:left;">Ppn</th>
                                                                <th style="text-align:right;">Jumlah</th>
                                                                <th>#</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                        <div class="form-group">
                                                            <label class="col-md-5 col-xs-12 padding-remove-side prs-0 form-label">Total Produk</label>
                                                            <div class="col-md-7 col-xs-12 prs-0">
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
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                            <div class="form-group">
                                                                <label class="col-md-5 col-xs-12 prs-0 form-label">Subtotal</label>
                                                                <div class="col-md-7 col-xs-12 prs-0">
                                                                    <input id="subtotal" name="subtotal" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                </div>
                                                            </div>
                                                        </div>                  
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                          <div class="form-group">
                                                            <label class="col-md-5 col-xs-12 prs-0 form-label">Diskon per Produk</label>
                                                            <div class="col-md-7 col-xs-12 prs-0">
                                                              <input id="total_diskon_baris" name="total_diskon_baris" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                            </div>
                                                          </div>
                                                        </div>
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                          <div class="form-group">
                                                            <label class="col-md-5 col-xs-12 prs-0 form-label">Diskon Nota</label>
                                                            <div class="col-md-7 col-xs-12 prs-0">
                                                              <input id="total_diskon" name="total_diskon" type="text" value="0" class="form-control" style="text-align:right;"/>
                                                            </div>
                                                          </div>                            
                                                        </div>                                                    
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                            <div class="form-group">
                                                                <label class="col-md-5 col-xs-12 prs-0 form-label">Ppn</label>
                                                                <div class="col-md-7 col-xs-12 prs-0">
                                                                    <input id="total_ppn" name="total_ppn" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                            <div class="form-group">
                                                                <label class="col-md-5 col-xs-12 prs-0 form-label">Total (Rp)</label>
                                                                <div class="col-md-7 col-xs-12 prs-0">
                                                                    <input id="total" name="total" type="text" value="0" class="form-control"
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
                                                            <button id="btn-approval" class="hide btn btn-default btn-small" type="button">
                                                                <i class="fas fa-file-signature"></i>
                                                                Minta Persetujuan
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

                                                            <!--<button id="btn-print" class="btn btn-default btn-small" type="button" data-id="0" data-number="0"
                                                              style="display: none;" data-id="0">
                                                              <i class="fas fa-print"></i>
                                                              Cetak
                                                            </button> -->
                                                            <button id="btn-print" style="display: none;" data-id="0" data-number="0" data-session="0" class="btn btn-default btn-small dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 
                                                                <i class="fas fa-print"></i>&nbsp;&nbsp;Cetak&nbsp;&nbsp;<span class="fa fa-angle-down"></span> 
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a href="#" class="btn-print-dropdown" data-action="print">
                                                                        <i class="fas fa-print"></i>&nbsp;&nbsp;Tagihan
                                                                    </a>
                                                                </li>
                                                                <li class="">
                                                                    <a href="#" class="btn-print-dropdown" data-action="print_history">
                                                                        <i class="fas fa-print"></i>&nbsp;&nbsp;Tagihan + Riwayat
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" class="btn-print-dropdown" data-action="print_group">
                                                                        <i class="fas fa-print"></i>&nbsp;&nbsp;Tagihan Rapi
                                                                    </a>
                                                                </li>                                
                                                                <li class="">
                                                                    <a href="#" class="btn-print-dropdown" data-action="print_delivery">
                                                                        <i class="fas fa-print"></i>&nbsp;&nbsp;Surat Jalan
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" class="btn-print-dropdown print-file" data-action="print_struk">
                                                                        <i class="fas fa-print"></i>&nbsp;&nbsp;Struk
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
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-5">
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
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                    <!--
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-right">
                                        <label class="form-label">Periode</label>
                                        <div class="col-md-12 col-sm-12 padding-remove-side">
                                            <div id="filter_date" data-start="" data-end="" class="filter-daterangepicker" style="background: #ecf0f2;padding-top:6px;padding-bottom:2px;border:0px;">
                                                <i class="fas fa-calendar-alt"></i>&nbsp;
                                                <span></span> 
                                                &nbsp;&nbsp;&nbsp;<i class="fas fa-caret-down" style="position: absolute;right: 22px;top: 8px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                    -->                          
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
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-right prs-5">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                            <label class="form-label">Customer</label>
                                            <select id="filter_kontak" name="filter_kontak" class="form-control">
                                                <option value="0">-- Semua --</option>
                                            </select>
                                        </div>
                                    </div>                    
                                    <div class="col-lg-3 col-md-3 col-xs-7 col-sm-7 form-group padding-remove-right prs-5">
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
                                <div class="col-md-12 col-xs-12 col-sm-12 prs-5">
                                    <div class="table-responsive">
                                        <table id="table-data" class="table table-bordered" data-limit-start="0" data-limit-end="10"
                                            style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Nomor</th>
                                                    <th>Customer</th>
                                                    <th>Tagihan</th>
                                                    <th>Sisa Tagihan</th>
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

<div class="modal fade" id="modal-trans-save" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f3f5f6;">
                <h4 id="modal-trans-save-title" style="color:black;">Transaksi Penjualan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!--
                        <div class="col-md-3 col-xs-12">
                            <p></p>
                            <p class="text-center">
                                <i class="fas fa-print fa-4x"></i>
                            </p>
                        </div>
                    -->
                    <div class="col-md-5 col-xs-12">
                      <!-- <p>Berhasil menyimpan transaksi penjualan, silahkan melanjutkan</p> -->
                        <table class="table">
                            <tr>
                                <td>Nomor</td>
                                <td class="modal-trans-number" data-trans-number="">:</td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td class="modal-trans-date" data-trans-date="">:</td>
                            </tr>        
                            <tr>
                                <td>Total</td>
                                <td class="modal-trans-total" data-trans-total="">:</td>
                            </tr>
                            <tr>
                                <td>Customer</td>
                                <td>:<input id="modal-contact-name" name="modal-contact-name" value="" style="border:none!important;"></td>
                            </tr>         
                            <tr>
                                <td>Telepon</td>
                                <td>:<input id="modal-contact-phone" name="modal-contact-phone" value="" style="border:none!important;"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-7 col-xs-12">
                        <form id="form_modal_trans_save">
                            <!--
                            <b>Metode Pembayaran</b>
                            <div id="div_payment_method" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                <div class="col-md-3 col-xs-6 col-sm-12 padding-remove-side">
                                    <a href="#" class="a_payment_method" data-id="1">
                                        <img src="<?php #echo site_url('upload/type_paid/cash1.png'); ?>" class="img-responsive">
                                        <div style="width:100%;"><b>Cash</b></div>
                                    </a>
                                </div> 
                                <div class="col-md-3 col-xs-6 col-sm-12 padding-remove-side">
                                    <a href="#" class="a_payment_method" data-id="2">
                                        <img src="<?php #echo site_url('upload/type_paid/tf1.png'); ?>" class="img-responsive">
                                        <div style="width:100%;"><b>Bank Transfer</b></div>
                                    </a>
                                </div> 
                                <div class="col-md-3 col-xs-6 col-sm-12 padding-remove-side">
                                    <a href="#" class="a_payment_method" data-id="3">
                                        <img src="<?php #echo site_url('upload/type_paid/edc1.png'); ?>" class="img-responsive">
                                        <div style="width:100%;"><b>EDC Card</b></div>
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6 col-sm-12 padding-remove-side">
                                    <a href="#" class="a_payment_method" data-id="5">
                                        <img src="<?php #echo site_url('upload/type_paid/qr1.png'); ?>" class="img-responsive">
                                        <div style="width:100%;"><b>QRIS</b></div>
                                    </a>
                                </div>   
                                <div class="col-md-3 col-xs-6 col-sm-12 padding-remove-side">
                                    <a href="#" class="a_payment_method" data-id="4">
                                        <img src="<?php #echo site_url('upload/type_paid/free1.png'); ?>" class="img-responsive">
                                        <div style="width:100%;"><b>Gratis</b></div>
                                    </a>
                                </div>                                                                                                                                                         
                            </div>
                            -->
                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Metode Pembayaran</label>
                                    <select id="modal_metode_pembayaran" name="modal_metode_pembayaran" class="form-control">
                                        <option value="0">-- Pilih --</option>
                                        <?php 
                                        foreach($type_paid as $k):
                                            echo "<option value=".$k['paid_id'].">".$k['paid_name']."</option>";
                                        endforeach;
                                        ?>                                   
                                    </select>
                                </div>
                            </div>
                            <div id="modal_metode_pembayaran_cash" style="display: none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Setor ke</label>
                                        <select id="modal_akun_cash" name="modal_akun_cash" class="form-control" disabled readonly>
                                            <option value="0">-- Pilih --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="modal_metode_pembayaran_transfer" style="display: none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Transfer ke Bank</label>
                                        <select id="modal_akun_transfer" name="modal_akun_transfer" class="form-control" disabled readonly>
                                            <option value="0">-- Pilih --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-left">
                                    <div class="form-group">
                                        <label class="form-label">Nama Pengirim Transfer</label>
                                        <input id="modal_nama_pengirim" name="modal_nama_pengirim" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>                                
                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Nomor Ref Bukti Transfer</label>
                                        <input id="modal_nomor_ref_transfer" name="modal_nomor_ref_transfer" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                            </div>
                            <div id="modal_metode_pembayaran_qris" style="display: none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Akun Penampung QRIS</label>
                                        <select id="modal_akun_qris" name="modal_akun_qris" class="form-control" disabled readonly>
                                            <option value="0">-- Pilih --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>                            
                            <div id="modal_metode_pembayaran_edc" style="display: none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">EDC Bank</label>
                                        <select id="modal_akun_edc" name="modal_akun_edc" class="form-control" disabled readonly>
                                        <option value="0">-- Pilih --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-left">
                                        <div class="form-group">
                                            <label class="form-label">Bank Penerbit Kartu</label>
                                            <select id="modal_bank_penerbit" name="modal_bank_penerbit" class="form-control" disabled readonly>
                                                <option value="0">Pilih</option>
                                                <option value="1">BCA</option>
                                                <option value="2">BNI</option>
                                                <option value="3">BRI</option>
                                                <option value="4">Mandiri</option>
                                                <option value="5">DBS</option>
                                                <option value="6">Standard Chartered</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Valid Tahun</label>
                                            <input id="modal_valid_tahun" name="modal_valid_tahun" type="text" value="" class="form-control" readonly='true'/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-right">
                                        <div class="form-group">
                                            <label class="form-label">Valid Bulan</label>
                                            <input id="modal_valid_bulan" name="modal_valid_bulan" type="text" value="" class="form-control" readonly='true'/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-left">
                                    <div class="form-group">
                                        <label class="form-label">Jenis Kartu</label>
                                        <select id="modal_jenis_kartu" name="modal_jenis_kartu" class="form-control" disabled readonly>
                                            <option value="0">-- Pilih --</option>
                                            <option value="1">Visa</option>
                                            <option value="2">MasterCard</option>
                                            <option value="3">American Express</option>
                                        </select>
                                    </div>
                                </div>                                
                                <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Nomor Kartu</label>
                                        <input id="modal_nomor_kartu" name="modal_nomor_kartu" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-right">
                                    <div class="form-group">
                                        <label class="form-label">Nama Pemilik Kartu</label>
                                        <input id="modal_nama_pemilik" name="modal_nama_pemilik" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Nomor Transaksi</label>
                                        <input id="modal_kode_transaksi" name="modal_kode_transaksi" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Grand Total</label>
                                        <input id="modal_total_before" name="modal_total_before" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-md-8 col-xs-8 col-sm-8 padding-remove-side prs-0">
                                    <div class="form-group">
                                        <label class="form-label">Klaim Voucher / Promo</label>
                                        <input id="modal_payment_voucher" name="modal_payment_voucher" type="text" value="" class="form-control" style="border-radius:0px!important;width:65%;float:left;"/>
                                        <button id="btn-voucher-search" data-id="1" type="button" class="btn btn-mini btn-small" style="width:35%;float:left;height:28px;border-radius:0px;">
                                            <span class="fas fa-check-double"></span> Gunakan
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-4 col-sm-4 padding-remove-right prl-2">
                                    <div class="form-group">
                                        <label class="form-label">Nilai Voucher</label>
                                        <input id="modal_payment_voucher_nominal" name="modal_payment_voucher_nominal" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>    
                                <div class="clearfix"></div>                                
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                    <div class="form-group">
                                        <label class="form-label">Total Tagihan</label>
                                        <input id="modal_total" name="modal_total" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                    <div class="form-group">
                                        <label class="form-label">Masukkan Jumlah (Rp)</label>
                                        <input id="modal_total_bayar" name="modal_total_bayar" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right prl-2">
                                    <div class="form-group">
                                        <label class="form-label">Kembali</label>
                                        <input id="modal_total_kembali" name="modal_total_kembali" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>              
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer flex-center">
				<button href="#" class="btn-close-from-trans-save-modal btn btn-info" 
					data-id="" data-number="">
					<span class="fas fa-file-invoice white"></span> Rubah Sebagai Piutang
				</button>
                <button href="#" class="btn-pay-from-modal btn btn-success" 
					data-contact-id="" data-trans-id="">
					<span class="fas fa-cash-register white"></span> Bayar Langsung
				</button>
				<button href="#" class="btn-delete btn-delete-from-modal btn btn-danger" 
					data-id="" data-number="" data-contact-name="" data-total="" data-date="">
					<span class="fas fa-trash"></span> Batalkan Penjualan
				</button>
				<button type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">
					<span class="fas fa-times"></span> Tutup
				</button>            
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-trans-print" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f3f5f6;">
                <h4 style="color:black;">Pembayaran Berhasil</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 col-xs-4 padding-remove-side">
                        <p class="text-center">
                            <i class="fas fa-print fa-4x"></i>
                        </p>
                        <p class="text-center"><b>LUNAS</b></p>
                    </div>
                    <div class="col-md-8 col-xs-8 padding-remove-side">
                        <table class="table">      
                            <tr>
                                <td>Nomor</td>
                                <td class="modal-print-trans-number">:</td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td class="modal-print-trans-date">:</td>
                            </tr>                            
                            <tr>
                                <td>Metode Pembayaran</td>
                                <td class="modal-print-trans-paid-type-name">:</td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td class="modal-print-trans-total">:</td>
                            </tr>
                            <tr>
                                <td>Dibayar</td>
                                <td class="modal-print-trans-total-paid">:</td>
                            </tr>
                            <tr>
                                <td>Kembalian</td>
                                <td class="modal-print-trans-total-change">:</td>
                            </tr>                                          
                        </table>
                    </div>         
                </div>
            </div>
            <div class="modal-footer flex-center">
                <?php if($whatsapp_config == 1){ ?>
                <button type="button" id="modal-btn-print-whatsapp" class="btn-print-whatsapp btn btn-primary" 
                    data-id="" data-contact-id="">
					<span class="fab fa-whatsapp white"></span> Kirim via WhatsApp
				</button>
                <?php } ?>
				<button type="button" id="modal-btn-print" class="btn-print-from-modal btn btn-success" 
                    data-id="">
					<span class="fas fa-file-invoice white"></span> Print Tagihan
				</button>
				<button type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">
					<span class="fas fa-times"></span> Tutup
				</button>                    
            </div>
        </div>
    </div>
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
</div>
<div class="modal fade" id="modal-trans-item-edit" role="dialog" style="" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ffffff;">
                <h4 id="modal-trans-item-edit-title" style="">Tambahkan Produk Tambahan</h4>
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
                            <form id="form-edit-item" name="form-trans-item" method="" action="">
                                <div class="col-md-12">
                                  <!-- <input id="id_document_item" name="id_document_item" type="hidden" value="" placeholder="id" readonly> -->
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Produk *</label>
                                            <select id="e_produk" name="e_produk" class="form-control" disabled readonly>
                                                <option value="0">-- Cari Item Produk Tambahan--</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <textarea id="e_keterangan" name="e_keterangan" type="text" value="" class="form-control"rows="2"/></textarea>
                                        </div>
                                    </div>                  
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                        <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label>Satuan</label>
                                                <input id="e_satuan" name="e_satuan" type="text" value="" class="form-control" readonly='true'/>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label>Harga</label>
                                                <input id="e_harga" name="e_harga" type="text" value="" class="form-control" readonly='true'/>
                                            </div>
                                        </div>               
                                        <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label>Qty</label>
                                                <input id="e_qty" name="e_qty" type="text" value="1" class="form-control" readonly='true'/>
                                            </div>                            
                                        </div>  
                                        <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label>Ppn</label>
                                                <select id="e_ppn_item" name="e_ppn_item" class="form-control">
                                                    <?php
                                                    foreach ($tax as $t):
                                                        echo "<option value=" . $t['tax_percent'] . ">" . $t['tax_name'] . "</option>";
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>                                       
                                        <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label>Subtotal</label>
                                                <input id="e_subtotal" name="e_subtotal" type="text" value="" class="form-control" readonly='true'/>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <button id="btn-update-item" data-trans-item-id="0" onClick="" class="btn btn-primary btn-small" type="button" style="margin-top:22px;">
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
</div>

<!-- Other -->
<div class="modal fade" id="modal-contact" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-master" name="form-master" method="" action="">         
                <div class="modal-header" style="background-color: #ffffff;">
                    <h4 style="">Buat Customer Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body padding-remove-side">
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
                                        <label class="form-label">Kode *</label>
                                        <input id="kode_contact" name="kode_contact" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Nama Customer *</label>
                                        <input id="nama_contact" name="nama_contact" type="text" value="" class="form-control"/>
                                    </div>
                                </div>                      
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Telepon *</label>
                                        <!-- <input class="form-control" id="code" name="code" type="text" required placeholder="" value="+62" style="width:30%;float:left;" readonly> -->
                                        <input id="telepon_1_contact" name="telepon_1_contact" type="text" value="" class="form-control"/>
                                    </div>                          
                                </div>                                                          
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Alamat</label>
                                        <textarea id="alamat_contact" name="alamat_contact" type="text" value="" class="form-control"rows="8"/></textarea>
                                    </div>
                                </div>                             
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input id="email_1_contact" name="email_1_contact" type="text" value="" class="form-control"/>
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-save-contact" onClick="" class="btn btn-primary btn-small" type="button" style="">
                        <i class="fas fa-save"></i>                                 
                        Simpan
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
</div>
<div class="modal fade" id="modal-product" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-product" name="form-product" method="" action="">         
                <div class="modal-header" style="background-color: #ffffff;">
                    <h4 style="">Buat Produk Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body padding-remove-side">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <p></p>
                            <p class="text-center">
                                <i class="fas fa-boxes fa-5x"></i>
                            </p>
                        </div>
                        <div class="col-md-9 col-xs-12"> 
                            <div class="col-md-12 col-sm-12 col-xs-12">              
                                <div class="col-lg-5 col-md-5 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Kode Produk / SKU / PLU</label>
                                        <input id="kode_barang" name="kode_barang" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-3 col-xs-12 padding-remove-left">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input id="nama_barang" name="nama_barang" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Satuan</label>
                                        <select id="satuan_barang" name="satuan_barang" class="form-control">
                                            <option value="0">Pilih</option>
                                        </select>
                                    </div>
                                </div>                                                                                 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-save-product" onClick="" class="btn btn-primary btn-small" type="button" style="">
                        <i class="fas fa-save"></i>                                 
                        Simpan
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
</div>

<!-- PENDING Sales Order -> Penjualan -->
<div class="modal fade" id="modal-order" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-order" name="form-order" method="" action="">         
                <div class="modal-header" style="background-color: #ffffff;">
                    <h4 style="">Sales Order -> Penjualan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!--<div class="col-md-3 col-xs-12">
                          <p></p>
                          <p class="text-center">
                            <i class="fas fa-boxes fa-5x"></i>
                          </p>
                        </div>
                        <div class="col-md-9 col-xs-12"> 
                          <div class="col-md-12 col-sm-12 col-xs-12">              
                            <div class="col-lg-5 col-md-5 col-xs-12 padding-remove-side">
                              <div class="form-group">
                                <label>Kode Produk / SKU / PLU</label>
                                <input id="kode_barang" name="kode_barang" type="text" value="" class="form-control"/>
                              </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                              <div class="form-group">
                                <label>Nama</label>
                                <input id="nama_barang" name="nama_barang" type="text" value="" class="form-control"/>
                              </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                              <div class="form-group">
                                <label>Satuan</label>
                                <select id="satuan_barang" name="satuan_barang" class="form-control">
                                  <option value="0">-- Pilih / Cari --</option>
                                </select>
                              </div>
                            </div>                                                                                 
                          </div>
                        </div> -->
                        <div class="col-md-12 col-xs-12">
                            <table id="table-order" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th style="text-align:right;">Qty / Unit</th>
                                        <th style="text-align:right;">Harga</th>     
                                        <th style="text-align:left;">Lokasi Pengambilan</th>
                                        <th style="text-align:right;">Coli</th>
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
                <div class="modal-footer flex-center">
                    <!-- <button id="btn-save-product" onClick="" class="btn btn-primary btn-small" type="button" style=""> -->
                      <!-- <i class="fas fa-save"></i> -->
                    <!-- Simpan -->
                    <!-- </button>-->
                    <button class="btn btn-outline-danger waves-effect btn-small" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i>                                 
                        Batal
                    </button>                   
                </div>
            </form>      
        </div>
    </div>
</div>

<!-- Not Used -->
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
    </div>
</div>