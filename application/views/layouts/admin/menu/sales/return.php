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
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-5">
                    <div id="div-form-trans" style="display:none;" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
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
                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-5">
                                        <form id="form-trans" name="form-trans-item" method="" action="">
                                            <div class="col-md-12">
                                                <input id="id_document" name="id_document" type="hidden" value="0" placeholder="id" readonly>
                                            </div>      
                                            <div class="col-md-3 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label" style="padding-bottom: 4px;">Diambil dari Penjualan</label><br>
                                                    <!-- <input id="trans_number" name="trans_number" type="text" value="<?php echo $post_result['trans_number']; ?>" class="form-control" readonly='true' placeholder=""/> -->
                                                    <?php
                                                    $next = true;
                                                    if (!empty($post_result['trans_number'])) {
                                                        ?>
                                                        <a href="#" id="btn-print-trans-source" class="" style="cursor: pointer;" data-trans-session="<?php echo $post_result['trans_session']; ?>" data-trans-id="<?php echo $post_result['trans_id']; ?>">
                                                            <i class="fas fa-file-alt"></i> <?php echo $post_result['trans_number']; ?>
                                                        </a>
                                                        <?php } else {
                                                        ?>
                                                        <a href="#" id="btn-print-trans-source" class="" style="cursor: pointer;" data-trans-session="0" data-trans-id="0">
                                                        </a>
                                                        <?php
                                                        $next = false;
                                                    }
                                                    ?>
                                                </div>
                                            </div>   
                                            <div class="col-md-2 col-xs-12">
                                                <div class="form-group">
                                                    <label class="form-label">Tgl Penjualan</label>
                                                    <div class="col-md-12 col-sm-12 padding-remove-side prs-0">
                                                        <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                            <input name="trans_date" id="trans_date" type="text" class="form-control" readonly="true"
                                                                value="<?php echo ($next) ? $post_result['trans_date_format'] : ''; ?>">
                                                            <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                       
                                            <div class="col-md-7 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label">Customer *</label>
                                                    <select id="kontak" name="kontak" class="form-control" disabled readonly>
                                                        <?php
                                                        if ($next) {
                                                            $contact_id = $post_result['contact_id'];
                                                            $contact_code = !empty($post_result['contact_code']) ? $post_result['contact_code'] : ' ';
                                                            $contact_name = $post_result['contact_name'];
                                                            echo "<option value=" . $contact_id . " selected>" . $contact_code . ' ' . $contact_name . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>  
                                            <div class="col-md-7 col-xs-12 col-sm-12">
                                                <div class="col-md-12 col-xs-6 col-sm-12 padding-remove-side prs-0">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                        <div class="form-group">
                                                            <label class="form-label">Alamat Customer</label>
                                                            <textarea id="trans_contact_address" name="trans_contact_address" class="form-control" rows="4"><?php echo ($next) ? $post_result['trans_contact_address'] : ''; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-xs-6 col-sm-12 padding-remove-side prs-0 prl-2">
                                                    <div class="col-md-7 col-xs-12 col-sm-12 padding-remove-left prs-0">
                                                        <div class="form-group">
                                                            <label class="form-label">Email</label>
                                                            <input id="trans_contact_email" name="trans_contact_email" type="text" value="<?php echo ($next) ? $post_result['trans_contact_email'] : ''; ?>" class="form-control"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                        <div class="form-group">
                                                            <label class="form-label">Telepon</label>
                                                            <input id="trans_contact_phone" name="trans_contact_phone" type="text" value="<?php echo ($next) ? $post_result['trans_contact_phone'] : ''; ?>" class="form-control"/>
                                                        </div>
                                                    </div>                                    
                                                </div>                      
                                            </div>     
                                            </div>     
                                            <div class="col-md-5 col-xs-12 col-sm-12">
                                                <div class="col-md-6 col-xs-12 form-group prs-0">
                                                    <label class="form-label">Tgl Transaksi</label>
                                                    <div class="col-md-12 col-sm-12 padding-remove-side prs-5">
                                                        <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                            <input name="tgl" id="tgl" type="text" class="form-control" readonly="true" value="<?php echo $end_date; ?>">
                                                            <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                    </div>
                                                </div>                                       
                                                <div class="col-md-6 col-xs-12 col-sm-6 prs-5">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side prs-0">
                                                        <label class="form-label">Nomor</label>
                                                        <input id="nomor" name="nomor" type="text" value="" class="form-control" readonly='true' placeholder="Nomor Otomatis" />
                                                    </div>
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
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side scroll prs-0">
                                                        <div class="table-responsive">
                                                            <table id="table-item" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                                                                <?php
                                                                if ($next) {
                                                                    $retur_data = $post_result['return_data'];
                                                                    if (count($retur_data) > 0) {
                                                                        ?>
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Produk</th>
                                                                                <th style="text-align:right;">Qty Jual</th>
                                                                                <th>Yg Bisa di Retur</th>                                  
                                                                                <th style="text-align:right;">Qty Retur</th>
                                                                                <th style="text-align:left;">Satuan</th>
                                                                                <th style="text-align:left;">Lokasi Penempatan</th>                                  
                                                                                <th style="text-align:right;">Harga Satuan</th>
                                                                                <th style="text-align:left;">Pajak</th>                                    
                                                                                <th style="text-align:right;">Jumlah</th>                                  
                                                                            </tr>
                                                                        </thead>                                
                                                                        <tbody id="table-item-tbody">
                                                                            <?php foreach ($retur_data as $v) { ?>
                                                                                <tr class="tr-trans-item-id" data-trans-item-id="<?php echo $v['trans_item_id']; ?>">
                                                                                    <td><a class="btn-modal-produk" href="#" 
                                                                                        data-trans-item-id="<?php echo $v['trans_item_id']; ?>">
                                                                                            <?php echo $v['product_name']; ?></a>
                                                                                    </td>
                                                                                    <td style="text-align:right;"><?php echo $v['trans_item_out_qty']; ?></td>
                                                                                    <td style="text-align:right;"><?php echo $v['qty_ready_for_return']; ?></td>
                                                                                    <td style="text-align:right;">
                                                                                        <input name="form-trans-item-input" type="text" class="form-control form-trans-item-input"
                                                                                            data-trans-item-id="<?php echo $v['trans_item_id']; ?>" 
                                                                                            data-trans-id="<?php echo $v['trans_item_trans_id']; ?>"
                                                                                            data-trans-item-qty="<?php echo $v['trans_item_out_qty']; ?>"
                                                                                            data-trans-item-price="<?php echo $v['trans_item_out_price']; ?>"
                                                                                            data-trans-item-sell-price="<?php echo $v['trans_item_sell_price']; ?>"
                                                                                            data-trans-ref="<?php echo $v['last_ref']; ?>"
                                                                                            data-trans-item-unit="<?php echo $v['trans_item_unit']; ?>"
                                                                                            data-trans-item-product-id="<?php echo $v['trans_item_product_id']; ?>"
                                                                                            data-trans-item-product-type="<?php echo $v['trans_item_product_type']; ?>"
                                                                                            data-trans-item-location="<?php echo $v['location_id']; ?>"                                                        
                                                                                            data-trans-item-qty-ready="<?php echo $v['qty_ready_for_return']; ?>"
                                                                                            data-trans-item-ppn="<?php echo $v['trans_item_ppn']; ?>"
                                                                                            data-trans-item-ppn-value="<?php echo $v['trans_item_ppn_value']; ?>"                                             
                                                                                            value="0.00" 
                                                                                            >      
                                                                                    </td>
                                                                                    <td><?php echo $v['trans_item_unit']; ?></td>
                                                                                    <td><?php echo $v['location_name']; ?></td>
                                                                                    <td style="text-align:right;">
                                                                                    <!-- <a class="btn-modal-riwayat-pembayaran" href="#" data-trans-item-id="<?php #echo $v['trans_item_id']; ?>">Rp. 1,000.00</a> -->
                                                                                        <?php echo number_format($v['trans_item_sell_price'], 2, '.', ','); ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php
                                                                                        $ppn = "Non Ppn";
                                                                                        if ($v['trans_item_ppn'] == 1) {
                                                                                            $ppn = "PPN " . number_format($v['trans_item_ppn_value'], 0);
                                                                                        }
                                                                                        echo $ppn;
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="td-trans-item-total" style="text-align:right;" data-value="0.00" data-value-ppn="0.00">0.00</td>
                                                                                </tr>      
                                                                                <?php }
                                                                            ?>
                                                                        </tbody>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">
                                                        <div class="col-md-12-col-xs-12 col-sm-12 padding-remove-left">
                                                            <div class="form-group">
                                                                <label class="form-label">Keterangan</label>
                                                                <textarea id="keterangan" name="keterangan" type="text" value="" class="form-control" rows="4"></textarea>
                                                            </div>
                                                        </div>                          
                                                    </div>    
                                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side prs-0">

                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                            <!--<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                              <div class="form-group">
                                                                <label class="col-md-5">Total Rincian Item</label>
                                                                <div class="col-md-7">
                                                                  <input id="total_item" name="total_item" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                </div>
                                                              </div>
                                                            </div>
                                                            -->
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="col-md-5 form-label">Subtotal (Rp)</label>
                                                                    <div class="col-md-7">
                                                                        <input id="total_dpp" name="total_dpp" type="text" value="0" class="form-control"
                                                                               style="text-align:right;" readonly='true' />
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="col-md-5 form-label">PPN (Rp)</label>
                                                                    <div class="col-md-7">
                                                                        <input id="total_ppn" name="total_ppn" type="text" value="0" class="form-control"
                                                                               style="text-align:right;" readonly='true' />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="col-md-5 form-label">Total (Rp)</label>
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
                                                                    Simpan Retur Penjualan
                                                                </button>
                                                                <!--
                                                                <button id="btn-update" class="btn btn-default btn-small" type="button"
                                                                  style="display: none;" data-id="0">
                                                                  <i class="fas fa-check-square"></i>
                                                                  Perbarui
                                                                </button> 
                                                                -->                             
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
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
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
                                    </div>                    
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                        <div class="table-responsive">
                                            <table id="table-data" class="table table-bordered" data-limit-start="0" data-limit-end="10"
                                                style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Nomor</th>
                                                        <th>Customer</th>                          
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