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

    #table-item, #table-item-2, #table-item-3 td{
        background-color: white;
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php include '_navigation.php';?>
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
                                                            <div class="col-md-12 col-sm-12 col-xs-12 prs-0 prl-5 prr-5">
                                                                <div class="col-md-3 col-sm-6 col-xs-6 padding-remove-left">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Pelaksana *</label>
                                                                                <select id="kontak" name="kontak" class="form-control" disabled readonly>
                                                                                    <option value="0">-- Pilih / Cari --</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--
                                                                        <div class="col-md-12 col-xs-6 col-sm-12 padding-remove-side">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <div class="form-group">
                                                                            <label class="form-label">Alamat Customer</label>
                                                                            <textarea id="alamat" name="alamat" class="form-control" rows="4"></textarea>
                                                                            </div>
                                                                        </div>
                                                                        </div>
                                                                        <div class="col-md-12 col-xs-6 col-sm-12 padding-remove-side">
                                                                        <div class="col-md-7 col-xs-12 col-sm-12 padding-remove-left">
                                                                            <div class="form-group">
                                                                            <label class="form-label">Email</label>
                                                                            <input id="email" name="email" type="text" value="" class="form-control"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-5 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <div class="form-group">
                                                                            <label class="form-label">Telepon</label>
                                                                            <input id="telepon" name="telepon" type="text" value="" class="form-control"/>
                                                                            </div>
                                                                        </div>                                    
                                                                        </div> 
                                                                    -->                                                                   
                                                                </div>
                                                                <div class="col-md-3 col-sm-6 col-xs-6 padding-remove-left prs-0">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                        <div class="col-md-12 col-xs-12 form-group prs-0">
                                                                            <label class="form-label">Tanggal Transaksi</label>
                                                                            <div class="col-md-12 col-sm-12 padding-remove-side">
                                                                                <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                                                    <input name="tgl" id="tgl" type="text" class="form-control" readonly="true"
                                                                                           value="<?php echo $end_date; ?>" data-value="">
                                                                                    <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>                                                                                                           
                                                                </div>
                                                                <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Nomor Dokumen *</label>
                                                                            <input id="nomor" name="nomor" type="text" value="" class="form-control" placeholder="Otomatis jika dikosongkan" readonly="true"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 col-xs-6 col-sm-6 padding-remove-side prs-0">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Nomor Referensi</label>
                                                                            <input id="nomor_ref" name="nomor_ref" type="text" value="" class="form-control"/>
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
                                        <div class="grid-body prs-0">
                                            <!-- Bahan -->
                                            <div class="col-md-12 col-xs-12 prs-0" style="background-color: #ffe1e1;margin-bottom: 20px;padding-top:10px;">
                                                <div class="col-md-6 col-xs-12 col-sm-12 prs-5" style="padding-left: 0;">
                                                    <h5><b>Daftar Bahan Baku <?php echo $title; ?></b></h5>
                                                </div>
                                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right prs-5">
                                                    <div class="pull-right">
                                                        <button id="btn-recipe-search" class="hide btn btn-default btn-small" type="button">
                                                            <i class="fas fa-list-alt"></i>
                                                            Pakai Resep
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                    <form id="form-trans-item" name="form-trans-item" method="" action="">
                                                        <div class="col-md-12">
                                                            <input id="id_document_item" name="id_document_item" type="hidden" value="" placeholder="id" readonly>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                <div class="form-group">
                                                                    <label class="form-label">Produk / Bahan *</label>
                                                                    <select id="produk" name="produk" class="form-control" disabled readonly>
                                                                        <option value="0">-- Pilih / Cari --</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                <div class="form-group">
                                                                    <label class="form-label">Lokasi Pengambilan</label>
                                                                    <select id="gudang" name="gudang" class="form-control" disabled readonly>
                                                                        <option value="0">-- Pilih / Cari --</option>
                                                                    </select>
                                                                </div>
                                                            </div>                                
                                                            <div class="col-md-6 col-xs-12 col-sm-12 prs-0">
                                                                <div class="col-md-3 col-xs-4 col-sm-4 padding-remove-left prr-2">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Info Stok</label>
                                                                        <input id="qty_stock" name="qty_stock" type="text" value="" class="form-control" readonly='true' />
                                                                    </div>
                                                                </div>                                
                                                                <div class="col-md-2 col-xs-4 col-sm-4 padding-remove-left">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Satuan</label>
                                                                        <input id="satuan" name="satuan" type="text" value="" class="form-control"
                                                                               readonly='true'/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 col-xs-4 col-sm-4 padding-remove-left prl-2">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Qty</label>
                                                                        <input id="qty" name="qty" type="text" value="0" class="form-control" readonly='true' />
                                                                    </div>
                                                                </div>
                                                                <!--                                
                                                                    <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Qty X Pack</label>
                                                                        <input id="qty_pack" name="qty_pack" type="text" value="1" class="form-control" readonly='true' />
                                                                    </div>
                                                                    </div>                                                                    
                                                                    <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                                                    <div class="form-group">
                                                                        <label>Harga Satuan</label>
                                                                        <input id="harga" name="harga" type="text" value="" class="form-control"
                                                                        readonly='true' />
                                                                    </div>
                                                                    </div>
                                                                    <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-left">
                                                                    <div class="form-group">
                                                                        <label>Ppn</label>
                                                                        <select id="ppn_item" name="ppn_item" class="form-control">
                                                                        <option value="0">Non-Ppn</option>
                                                                        <option value="1">Ppn</option>
                                                                        </select>
                                                                    </div>
                                                                    </div>
                                                                -->                                                                                   
                                                                <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                                                    <div class="form-group">
                                                                        <button id="btn-save-item" onClick="" class="btn btn-default btn-small" type="button"
                                                                                style="margin-top:18px;">
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
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <div class="table-responsive">
                                                        <table id="table-item" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                                                            <thead>
                                                                <tr>
                                                                    <th>Produk / Bahan</th>
                                                                    <th>Lokasi Pengambilan</th>                                  
                                                                    <th style="text-align:right;">Qty / Satuan</th>
                                                                    <th style="text-align:right;">Harga Bobot</th>                                                           
                                                                    <th style="text-align:right;">Total</th>
                                                                    <th>#</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Biaya -->
                                            <div class="col-md-12 col-xs-12 prs-0" style="background-color: #dfe7ff;margin-bottom: 20px;padding-top:10px;">
                                                <h5 class="prs-5"><b>Daftar Biaya <?php echo $title; ?></b></h5>
                                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                    <form id="form-trans-item-2" name="form-trans-item-2" method="" action="">
                                                        <div class="col-md-12">
                                                            <input id="id_document_item_2" name="id_document_item_2" type="hidden" value="" placeholder="id" readonly>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                            <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                <div class="form-group">
                                                                    <label>Biaya *</label>
                                                                    <select id="biaya_2" name="biaya_2" class="form-control" disabled readonly>
                                                                        <option value="0">-- Pilih / Cari --</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                <div class="col-md-6 col-xs-12 col-sm-12 prs-0 prr-2">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Keterangan</label>
                                                                        <input id="keterangan_2" name="keterangan_2" type="text" value="" class="form-control" readonly='true' />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-xs-6 col-sm-6 padding-remove-left prs-0">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Persentase %</label>
                                                                        <input id="persen_2" name="persen_2" type="text" value="100" class="form-control" readonly='true' />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-xs-6 col-sm-6 padding-remove-left prs-0">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Harga</label>
                                                                        <input id="harga_2" name="harga_2" type="text" value="" class="form-control" readonly='true' disabled="true"/>
                                                                    </div>
                                                                </div>                                  
                                                                <!--
                                                                    <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-left">
                                                                    <div class="form-group">
                                                                        <label>Ppn</label>
                                                                        <select id="ppn_item_2" name="ppn_item_2" class="form-control">
                                                                        <option value="0">Non-Ppn</option>
                                                                        <option value="1">Ppn</option>
                                                                        </select>
                                                                    </div>
                                                                    </div>
                                                                -->                                                                                   
                                                                <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                                                    <div class="form-group">
                                                                        <button id="btn-save-item-2" onClick="" class="btn btn-default btn-small" type="button"
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
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <div class="table-responsive">
                                                        <table id="table-item-2" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                                                            <thead>
                                                                <tr>
                                                                    <th>Biaya</th>
                                                                    <th>Keterangan</th>
                                                                    <th style="text-align:right;">Harga</th>
                                                                    <th>#</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>                            
                                            </div>
                                            <!-- Produk -->
                                            <div class="col-md-12 col-xs-12 prs-0" style="background-color: #b7ecca;margin-bottom: 10px;padding-top:10px;">
                                                <h5 class="prs-5"><b>Daftar Produk Jadi <?php echo $title; ?></b></h5>
                                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                    <form id="form-trans-item-3" name="form-trans-item-3" method="" action="">
                                                        <div class="col-md-12">
                                                            <input id="id_document_item_3" name="id_document_item_3" type="hidden" value="" placeholder="id" readonly>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                            <div class="col-md-5 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                <div class="form-group">
                                                                    <label class="form-label">Produk Jadi *</label>
                                                                    <select id="produk_3" name="produk_3" class="form-control" disabled readonly>
                                                                        <option value="0">-- Pilih / Cari --</option>
                                                                    </select>
                                                                </div>
                                                            </div>                                 
                                                            <div class="col-md-7 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-left prs-0">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 form-group prs-0">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Lokasi Penempatan</label>
                                                                            <select id="gudang_3" name="gudang_3" class="form-control" disabled readonly>
                                                                                <option value="0">-- Pilih / Cari --</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-xs-5 col-sm-5 prs-0 prr-2">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Satuan</label>
                                                                        <input id="satuan_3" name="satuan_3" type="text" value="" class="form-control"
                                                                               readonly='true'/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-xs-7 col-sm-7 padding-remove-left prs-0">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Qty</label>
                                                                        <input id="qty_3" name="qty_3" type="text" value="1" class="form-control" readonly='true' />
                                                                    </div>
                                                                </div>
                                                                <!--
                                                                <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                                                  <div class="form-group">
                                                                    <label>Harga Satuan</label>
                                                                    <input id="harga_3" name="harga_3" type="text" value="" class="form-control"
                                                                      readonly='true' />
                                                                  </div>
                                                                </div>
                                                                <div class="col-md-3 col-xs-12 col-sm-12 padding-remove-left">
                                                                  <div class="form-group">
                                                                    <label>Ppn</label>
                                                                    <select id="ppn_item_3" name="ppn_item_3" class="form-control">
                                                                      <option value="0">Non-Ppn</option>
                                                                      <option value="1">Ppn</option>
                                                                    </select>
                                                                  </div>
                                                                </div>
                                                                -->                                                                                   
                                                                <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                                                    <div class="form-group">
                                                                        <button id="btn-save-item-3" onClick="" class="btn btn-default btn-small" type="button"
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
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <div class="table-responsive">
                                                        <table id="table-item-3" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                                                            <thead>
                                                                <tr>
                                                                    <th>Produk Jadi</th>
                                                                    <th>Lokasi Penempatan</th>
                                                                    <th style="text-align:right;">Qty / Satuan</th>
                                                                    <th style="text-align:right;">Harga Satuan / HPP</th>
                                                                    <th style="text-align:right;">Total</th>
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
                                                    <div class="hide col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                        <div class="form-group">
                                                            <label class="col-md-5 padding-remove-side">Total Produk</label>
                                                            <div class="col-md-7">
                                                                <input id="total_produk" name="total_produk" type="text" value="0" class="form-control"
                                                                       style="text-align:right;" readonly='true' />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0 prr-5 prl-5" style="margin-bottom:4px;">
                                                        <div class="form-group">
                                                            <label class="col-md-5 padding-remove-side prs-0">Harga Produk Satuan</label>
                                                            <div class="col-md-7 prs-0">
                                                                <input id="total_hpp_product_per_unit" name="total_hpp_product_per_unit" type="text" value="0" class="form-control"
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
                                                    <div class="col-md-12-col-xs-12 col-sm-12 padding-remove-left prl-5 prr-5">
                                                        <div class="form-group">
                                                            <label class="form-label">Keterangan</label>
                                                            <textarea id="keterangan" name="keterangan" type="text" value="" class="form-control" rows="4"></textarea>
                                                        </div>
                                                    </div>                          
                                                </div>    
                                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">
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
                                                        <div class="col-md-12 col-xs-4 col-sm-4 padding-remove-side prs-0 prr-2" style="margin-bottom:4px;">
                                                            <div class="form-group">
                                                                <label class="col-md-5 prs-0">Total Bahan Baku</label>
                                                                <div class="col-md-7 prs-0">
                                                                    <input id="total_bahan" name="total_bahan" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <div class="col-md-12 col-xs-4 col-sm-4 padding-remove-side" style="margin-bottom:4px;">
                                                            <div class="form-group">
                                                                <label class="col-md-5 prs-0">Total Biaya</label>
                                                                <div class="col-md-7 prs-0">
                                                                    <input id="total_biaya" name="total_biaya" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                </div>
                                                            </div>
                                                        </div>    
                                                        <!--                                                 
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Total HPP</label>
                                                                    <div class="col-md-7">
                                                                    <input id="total_hpp" name="total_hpp" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                        -->
                                                        <div class="col-md-12 col-xs-4 col-sm-4 padding-remove-side prs-0 prl-2" style="margin-bottom:4px;">
                                                            <div class="form-group">
                                                                <label class="col-md-5 prs-0">Total (Rp)</label>
                                                                <div class="col-md-7 prs-0">
                                                                    <input id="total" name="total" type="text" value="0" class="form-control" style="text-align:right;" readonly='true' />
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
                                                            <button id="btn-print" class="btn btn-default btn-small" type="button" data-id="0" data-number="0" data-session="0"
                                                                    style="display: none;">
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
                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-0">
                                        <label class="form-label">Periode Awal</label>
                                        <div class="col-md-12 col-sm-12 padding-remove-side">
                                            <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                <input name="start" id="start" type="text" class="form-control input-sm" readonly="true"
                                                       value="<?php echo $first_date; ?>">
                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-0">
                                        <label class="form-label">Periode Akhir</label>
                                        <div class="col-md-12 col-sm-12 padding-remove-side">
                                            <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                <input name="end" id="end" type="text" class="form-control input-sm" readonly="true"
                                                       value="<?php echo $end_date; ?>">
                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-right">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                            <label class="form-label">Karyawan</label>
                                            <select id="filter_kontak" name="filter_kontak" class="form-control">
                                                <option value="0">-- Semua --</option>
                                            </select>
                                        </div>
                                    </div>                    
                                    <div class="col-lg-3 col-md-3 col-xs-6 col-sm-6 form-group padding-remove-right">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prr-5">  
                                            <label class="form-label">Cari</label>
                                            <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                        </div>
                                    </div>                                 
                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group prs-0 prs-5">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0 prr-5">
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
                                                    <th>Karyawan</th>
                                                    <th>Total Harga</th>                      
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
<div class="modal fade" id="modal-recipe" role="dialog" style="" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: white;">
                <h4 id="modal-trans-item-edit-title" style="">Resep</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                            <form id="form-edit-item" name="form-trans-item" method="" action="">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Resep Barang *</label>
                                            <select id="r_produk" name="r_produk" class="form-control">
                                                <option value="0">-- Cari Resep --</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <!--
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
                                            <option value="0">Non-Ppn</option>
                                            <option value="1">Ppn</option>
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
                                    -->
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
<div class="modal fade" id="modal-trans-note" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #05534d;">
                <h4 style="color:white;">Tambahkan Catatan</h4>
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
<div class="modal fade" id="modal-trans-item-edit" role="dialog" style="" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #05534d;">
                <h4 id="modal-trans-item-edit-title" style="color:white;">Tambahkan Produk Tambahan</h4>
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
                                                    <option value="0">Non-Ppn</option>
                                                    <option value="1">Ppn</option>
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
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-contact" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-master" name="form-master" method="" action="">         
                <div class="modal-header" style="background-color: #6F7A8A;">
                    <h4 style="color:white;">Buat Kontak Baru</h4>
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
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Perusahaan</label>
                                        <input id="perusahaan_contact" name="perusahaan_contact" type="text" value="" class="form-control"/>
                                    </div>
                                </div>                      
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
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-product" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-product" name="form-product" method="" action="">         
                <div class="modal-header" style="background-color: #6F7A8A;">
                    <h4 style="color:white;">Buat Barang Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                                        <label>Kode Barang / SKU / PLU</label>
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
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-search-stock" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-size" class="modal-dialog">
        <div class="modal-content">
            <form id="form-modal-search-stock">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Cari Stok Barang
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="">
                        <span aria-hidden="true" style="color:#888888;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background: white!important;">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <!-- <label class="form-label">Cari Stok Barang Setiap Gudang</label> -->
                                <select id="header-goods" name="header-goods" class="form-control">
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <table id="table-stock" class="table">
                                <thead>
                                <th>Gudang</th>
                                <th class="text-right">Stok</th>
                                <th>Action</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="2">Data tidak ada</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-recipe" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-size" class="modal-dialog">
        <div class="modal-content">
            <form id="form-modal-search-stock">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Resep
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="">
                        <span aria-hidden="true" style="color:#888888;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background: white!important;">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">  
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Komponen Barang</label>
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
                        <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top:20px;">
                            <b><i class="fas fa-list-alt"></i> Daftar Komponen Barang / Bahan</b>              
                            <table id="table-recipe" class="table">
                                <thead>
                                <th>Barang / Bahan</th>
                                <th class="text-right">Qty</th>
                                <th class="text-left">Satuan</th>                  
                                <th>Action</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4">Tidak ada data</td>
                                    </tr>                 
                                </tbody>
                            </table>
                        </div>   
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <b><i class="fas fa-sign-out-alt"></i> Daftar Diatas Akan Menghasilkan Barang</b> 
                            <table id="table-recipe-result" class="table">
                                <thead>
                                <th>Barang Jadi</th>
                                <th class="text-right">Qty</th>
                                <th class="text-left">Satuan</th>     
                                </thead>
                                <tbody>
                                    <tr>
                                        <td id="modal-product-name"></td>
                                        <td class="text-right">1</td>
                                        <td id="modal-product-unit"></td>
                                    </tr>                  
                                </tbody>
                            </table>              
                        </div>             
                    </div>
                </div>
                <div class="modal-footer">            
                </div>
            </form>
        </div>
    </div>
</div>