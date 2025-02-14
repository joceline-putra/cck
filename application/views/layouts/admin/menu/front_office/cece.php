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
    .popover {
        z-index: 9999;
    }    
    .table-responsive{
        overflow-x: unset;
    }
    /* Radio Custom */
    .radio_group input{
        margin:0;padding:0;
        -webkit-appearance:none;
        -moz-appearance:none;
        appearance:none;
    }
    .radio_group input:active +.radio_group_label{
        opacity: .9;
    }
    .radio_group input:checked +.radio_group_label{
        -webkit-filter: none;-moz-filter: none;filter: none;
    }
    .radio_group_label{
        padding-top:20px;
        color:white;
        font-size:14px;
        font-weight:bold;
        letter-spacing:1px;
        text-align: center;
        cursor:pointer;
        background-size:contain;
        background-repeat:no-repeat;
        display:inline-block;
        width:100px;height:70px;
        /* -webkit-transition: all 100ms ease-in;
        -moz-transition: all 100ms ease-in;
                transition: all 100ms ease-in;
        -webkit-filter: brightness(1.8) grayscale(1) opacity(.7);
        -moz-filter: brightness(1.8) grayscale(1) opacity(.7);
                filter: brightness(1.8) grayscale(1) opacity(.7); */
    }
    .radio_group_label:hover{
        -webkit-filter: brightness(1.2) grayscale(.5) opacity(.9);
        -moz-filter: brightness(1.2) grayscale(.5) opacity(.9);
                filter: brightness(1.2) grayscale(.5) opacity(.9);
    }
    .radio_bg{
        background-color:#12651c;
    }

    .radio_bg_not_ready{
        background-color:#651215;
    }    
    
    .visa{
        /* background-image:url(http://i.imgur.com/lXzJ1eB.png); */
    }
    .mastercard{
        /* background-image:url(http://i.imgur.com/SJbRQF7.png); */
    }
    /* Extras */
    /* a:visited{color:#888}
    a{color:#444;text-decoration:none;}
    p{margin-bottom:.3em;}     */
</style>
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="grid simple">
            <div class="grid-body">
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding-bottom:10px;">
                    <div class="row">
                        <b>Waktu Sekarang</b>
                        <div id="clock"></div>
                    </div>
                </div>
            </div>
        </div>        
    </div>    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <ul class="nav nav-tabs" role="tablist" style="display:inline;">      
            <li class="active">
                <a href="#tab1" role="tab" class="btn-tab-1" data-toggle="tab" aria-expanded="true" style="cursor:pointer;">
                <span class="fas fa-plus-square"></span> Form</a>
            </li>           
            <li class="">
                <a href="#tab2" role="tab" class="btn-tab-2" data-toggle="tab" aria-expanded="false">
                <span class="fas fa-calendar-alt"></span> Check In</a>
            </li>                                            
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <!-- 
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                        <h5><b>Data <?php #echo $title;?></b></h5>
                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                        <div class="pull-right">
                            <button id="btn_export_order" onClick="" class="btn btn-default btn-small" type="button" style="display: inline;">
                                <i class="fas fa-file-excel"></i>
                                Ekspor Excel
                            </button>
                            <button id="btn_new_order_2" class="btn btn-success btn-small" type="button" style="display: inline;">
                                <i class="fas fa-check-double"></i>
                                Status Kamar
                            </button>
                            <button id="btn_new_order" class="btn btn-success btn-small" type="button" style="display: inline;">
                                <i class="fas fa-plus"></i>
                                Buat <?php #echo $title; ?> Baru
                            </button>
                        </div>
                    </div>
                </div>
                     -->
                <!-- Child Tab-Pane -->
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                    <form id="form_booking" name="form_booking">
                    <ul class="nav nav-tabs hide" role="tablist">      
                        <li class="active"><a href="#tab11" role="tab" class="btn-tab-11" data-toggle="tab" aria-expanded="true" style="cursor:pointer;">11 Cabang</a></li>           
                        <li class=""><a href="#tab12" role="tab" class="btn-tab-12" data-toggle="tab" aria-expanded="false">12 Tipe</a></li>     
                        <li class=""><a href="#tab13" role="tab" class="btn-tab-13" data-toggle="tab" aria-expanded="false">13 Jenis Kamar</a></li>     
                        <li class=""><a href="#tab14" role="tab" class="btn-tab-14" data-toggle="tab" aria-expanded="false">14 Nomor Kamar</a></li>     
                        <li class=""><a href="#tab15" role="tab" class="btn-tab-15" data-toggle="tab" aria-expanded="false">15 Tgl Checkin</a></li>     
                        <li class=""><a href="#tab16" role="tab" class="btn-tab-16" data-toggle="tab" aria-expanded="false">16 Customer</a></li>     
                        <li class=""><a href="#tab17" role="tab" class="btn-tab-17" data-toggle="tab" aria-expanded="false">17 Bukti Bayar</a></li>
                        <li class=""><a href="#tab18" role="tab" class="btn-tab-18" data-toggle="tab" aria-expanded="false">18 Foto KTP</a></li>
                        <li class=""><a href="#tab19" role="tab" class="btn-tab-19" data-toggle="tab" aria-expanded="false">19 Foto Plat</a></li>                                                                                                                                                                                                 
                        <li class=""><a href="#tab20" role="tab" class="btn-tab-20" data-toggle="tab" aria-expanded="false">20 Konfirmasi</a></li>                                                                                                                                                                                                 
                    </ul>                        
                    <div class="tab-content">
                        <div class="tab-pane tab-pane-sub active" id="tab11" for="lily">
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Cabang</label>
                                    <div class="radio_group">
                                        <?php 
                                        foreach($branch_cece as $i => $v){
                                            $c = '';
                                            if($i==0){
                                                // $c = 'checked';
                                            }
                                        ?>
                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                            <input id="branch_<?php echo $v['branch_id']; ?>" type="radio" name="order_branch_id" value="<?php echo $v['branch_id']; ?>" data-name="<?php echo $v['branch_name']; ?>" <?php echo $c; ?>>
                                            <label class="radio_group_label radio_bg" for="branch_<?php echo $v['branch_id']; ?>" style="width:100%;height:124px;word-wrap: normal;"><?php echo $v['branch_name']; ?></label>
                                        </div>
                                        <?php 
                                        } 
                                        ?>
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <div class="tab-pane tab-pane-sub" id="tab12" for="bulanan or harian">
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Tipe Pesanan</label>
                                    <div class="radio_group">
                                        <!-- <input id="order_ref_price_id_0" type="radio" name="order_ref_price_id" value="0" data-name="Promo">
                                        <label class="radio_group_label radio_bg" for="order_ref_price_id_0">Promo</label> -->
                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                            <input id="order_ref_price_id_1" type="radio" name="order_ref_price_id" value="1" data-name="Bulanan">
                                            <label class="radio_group_label radio_bg" for="order_ref_price_id_1" style="width:100%;height:124px;word-wrap: normal;">Bulanan</label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                            <input id="order_ref_price_id_2" type="radio" name="order_ref_price_id" value="2" data-name="Harian">
                                            <label class="radio_group_label radio_bg" for="order_ref_price_id_2" style="width:100%;height:124px;word-wrap: normal;">Harian</label>
                                        </div>  
                                        <!-- <input id="order_ref_price_id_3" type="radio" name="order_ref_price_id" value="3" data-name="Midnight">
                                        <label class="radio_group_label radio_bg" for="order_ref_price_id_3">Midnight</label> -->
                                        <!-- </div>
                                    <div class="radio_group"> -->
                                        <!-- <input id="order_ref_price_id_4" type="radio" name="order_ref_price_id" value="4" data-name="4 Jam">
                                        <label class="radio_group_label radio_bg" for="order_ref_price_id_4">4 Jam</label>
                                        <input id="order_ref_price_id_5" type="radio" name="order_ref_price_id" value="5" data-name="2 Jam">
                                        <label class="radio_group_label radio_bg" for="order_ref_price_id_5">2 Jam</label>                                             -->
                                    </div>                                      
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="margin-top:20px;">
                                <button id="btn_tab_12b" class="btn btn-default" type="button" style="width:100%;">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>                                                                                                       
                            </div>                                                        
                        </div>                 
                        <div class="tab-pane tab-pane-sub" id="tab13" for="jenis kamar">
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Jenis Kamar</label>
                                    <div id="order_ref_id" class="radio_group">
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="margin-top:20px;">
                                <button id="btn_tab_13b" class="btn btn-default" type="button" style="width:100%;">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>                                                                                                       
                            </div>                               
                        </div>     
                        <div class="tab-pane tab-pane-sub" id="tab14" for="nomor kamar">
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Nomor Kamar</label>
                                    <div id="order_product_id" class="radio_group">
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="margin-top:20px;">
                                <button id="btn_tab_14b" class="btn btn-default" type="button" style="width:100%;">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>                                                                                                       
                            </div>                            
                        </div> 
                        <div class="tab-pane tab-pane-sub" id="tab15" for="jam masuk">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Durasi Menginap</label>
                                    <div class="controls">
                                        <select name="order_duration" id="order_duration" class="form-control" style="width:100%">
                                            <option value="30" selected> 1 Bulan (30 hari)</option>
                                            <option value="60"> 2 Bulan (60 hari)</option>
                                            <option value="90"> 3 Bulan (90 hari)</option>                                                                                        
                                        </select>
                                    </div>
                                </div>
                            </div>                             
                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-12 form-group padding-remove-side">
                                <label class="form-label">Tanggal Mulai</label>
                                <div class="col-md-12 col-sm-12 padding-remove-side">
                                    <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                        <!-- <input name="order_start_date" id="order_start_date" type="text" class="form-control input-sm" readonly="true" 
                                        value="<?php #echo $booking_start_date;?>" data-original="<?php #echo $booking_start_date;?>"> -->
                                        <input name="order_start_date" id="order_start_date" type="text" class="form-control input-sm" readonly="true" 
                                        value="<?php echo $booking_start_date;?>" data-original="<?php echo $booking_start_date;?>">                                        
                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-12 form-group padding-remove-side">
                                <label class="form-label">Tanggal Akhir</label>
                                <div class="col-md-12 col-sm-12 padding-remove-side">
                                    <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                        <input name="order_end_date" id="order_end_date" type="text" class="form-control input-sm" readonly="true" 
                                        value="<?php echo $booking_end_date;?>" data-original="<?php echo $booking_end_date;?>">                                        
                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                </div>
                            </div>                                                                           
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Jam Check-In</label>
                                        <div class="controls">
                                            <select name="order_start_hour" id="order_start_hour" class="form-control" style="width:100%">
                                                <?php
                                                $clock = array(
                                                    "01:00","02:00","03:00","04:00","05:00","06:00","07:00","08:00","09:00","10:00",
                                                    "11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00",
                                                    "21:00","22:00","23:00"                                                                        
                                                );
                                                foreach($clock as $val){
                                                    $checked = '';
                                                    if($val == "14:00"){
                                                        $checked = "selected";
                                                    }
                                                    echo '<option value="'.$val.'" '.$checked.'>'.$val.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>  
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Jam Check-Out</label>
                                        <div class="controls">
                                            <select name="order_end_hour" id="order_end_hour" class="form-control" style="width:100%">
                                                <?php
                                                $clock = array(
                                                    "01:00","02:00","03:00","04:00","05:00","06:00","07:00","08:00","09:00","10:00",
                                                    "11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00",
                                                    "21:00","22:00","23:00"                                                                        
                                                );
                                                foreach($clock as $val){
                                                    $checked = '';
                                                    if($val == "12:00"){
                                                        $checked = "selected";
                                                    }                                                                        
                                                    echo '<option value="'.$val.'" '.$checked.'>'.$val.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>                                                      
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Harga</label>
                                        <input id="order_price" name="order_price" type="text" value="" class="form-control" readonly/>
                                    </div>
                                </div>                                                       
                            </div>       
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="margin-top:20px;">
                                <button id="btn_tab_15b" class="btn btn-default" type="button" style="width:45%;">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>                                       
                                <button id="btn_tab_15" class="btn btn-primary" type="button" style="width:50%;">
                                    Selanjutnya <i class="fas fa-arrow-right"></i>
                                </button>                                                                                                       
                            </div>                              
                        </div>
                        <div class="tab-pane tab-pane-sub" id="tab16" for="nama dan telepon">
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Nama</label>
                                        <input id="order_contact_name" name="order_contact_name" type="text" value="" class="form-control"/>
                                    </div>
                                </div>                                                         
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Telepon</label>
                                        <input id="order_contact_phone" name="order_contact_phone" type="text" value="" class="form-control"/>
                                    </div>
                                </div>                                                    
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="margin-top:20px;">
                                <button id="btn_tab_16b" class="btn btn-default" type="button" style="width:45%;">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>                                    
                                <button id="btn_tab_16" class="btn btn-primary" type="button" style="width:50%;">
                                    <i class="fas fa-arrow-right"></i> Selanjutnya
                                </button>                                                                             
                            </div>                                                          
                        </div>   
                        <div class="tab-pane tab-pane-sub" id="tab17" for="ktp">
                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Foto KTP *</label>
                                    <a class="files_link_2" href="<?= site_url('upload/noimage.png'); ?>">
                                        <img id="files_preview_2" src="<?= site_url('upload/noimage.png'); ?>" class="img-responsive" height="120px" width="240px" style="margin-bottom:5px;"/>
                                    </a>
                                    <div class="custom-file">
                                        <input class="form-control" id="files_2" name="files_2" type="file" tabindex="1">
                                    </div>
                                </div>
                            </div>      
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="margin-top:20px;">                            
                                <button id="btn_tab_17b" class="btn btn-default" type="button" style="width:45%;">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>                             
                                <button id="btn_tab_17" class="btn btn-primary" type="button" style="width:50%;">
                                    <i class="fas fa-arrow-right"></i> Selanjutnya
                                </button>                                                     
                            </div>
                        </div>
                        <div class="tab-pane tab-pane-sub" id="tab18" for="kendaraan">
                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Foto Form Sewa *</label>
                                    <a class="files_link_3" href="<?= site_url('upload/noimage.png'); ?>">
                                        <img id="files_preview_3" src="<?= site_url('upload/noimage.png'); ?>" class="img-responsive" height="120px" width="240px" style="margin-bottom:5px;"/>
                                    </a>
                                    <div class="custom-file">
                                        <input class="form-control" id="files_3" name="files_3" type="file" tabindex="1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Foto Plat Kendaraan *</label>
                                    <a class="files_link_4" href="<?= site_url('upload/noimage.png'); ?>">
                                        <img id="files_preview_4" src="<?= site_url('upload/noimage.png'); ?>" class="img-responsive" height="120px" width="240px" style="margin-bottom:5px;"/>
                                    </a>
                                    <div class="custom-file">
                                        <input class="form-control" id="files_4" name="files_4" type="file" tabindex="1">
                                    </div>
                                </div>
                            </div>                               
                            <div class="col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Nomor Plat Kendaraan</label>
                                    <div class="controls">
                                        <input name="order_vehicle_plate_number" id="order_vehicle_plate_number" type="text" class="form-control input-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Jumlah Kendaraan yg di bawa</label>
                                    <div class="controls">
                                        <input name="order_vehicle_count" id="order_vehicle_count" type="text" class="form-control input-sm" value="1">
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Biaya Parkir</label>
                                    <div class="controls">
                                        <input name="order_vehicle_cost" id="order_vehicle_cost" type="text" class="form-control input-sm" value="0">
                                    </div>
                                </div>
                            </div>                                                           
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="margin-top:20px;">                            
                                <button id="btn_tab_18b" class="btn btn-default" type="button" style="width:45%;">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>                             
                                <button id="btn_tab_18" class="btn btn-primary" type="button" style="width:50%;">
                                    <i class="fas fa-arrow-right"></i> Selanjutnya
                                </button>                                                     
                            </div>
                        </div>
                        <div class="tab-pane tab-pane-sub" id="tab19" for="bukti bayar">
                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Bukti Bayar *</label>
                                        <a class="files_link_1" href="<?= site_url('upload/noimage.png'); ?>">
                                            <img id="files_preview_1" src="<?= site_url('upload/noimage.png'); ?>" class="img-responsive" height="120px" width="240px" style="margin-bottom:5px;"/>
                                        </a>
                                        <div class="custom-file">
                                            <input class="form-control" id="files_1" name="files_1" type="file" tabindex="1">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6 col-sm-6 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Cara Bayar</label>
                                        <select id="paid_payment_type" name="paid_payment_type"class="form-control">
                                            <option value="FULL" selected>Full</option>
                                            <option value="DP">DP</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6 col-sm-6 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Metode</label>
                                        <select id="paid_payment_method" name="paid_payment_method"class="form-control">
                                            <option value="TRANSFER" selected>Transfer</option>
                                            <option value="CASH">Tunai</option>
                                            <option value="ALL">Tunai & Transfer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">    
                                    <div class="div_payment_single col-md-12 col-xs-12 col-sm-6 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Jumlah Byr (Rp)</label>
                                            <input id="paid_total" name="paid_total" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="div_payment_double col-md-6 col-xs-6 col-sm-6 padding-remove-side" style="display:none;">
                                        <div class="form-group">
                                            <label class="form-label">Jumlah Tunai (Rp)</label>
                                            <input id="paid_total_cash" name="paid_total_cash" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="div_payment_double col-md-6 col-xs-6 col-sm-6 padding-remove-side" style="display:none;">
                                        <div class="form-group">
                                            <label class="form-label">Jumlah Transfer (Rp)</label>
                                            <input id="paid_total_transfer" name="paid_total_transfer" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="margin-top:20px;">
                                <button id="btn_tab_19b" class="btn btn-default" type="button" style="width:45%;">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>                                 
                                <button id="btn_tab_19" class="btn btn-primary" type="button" style="width:50%;">
                                    <i class="fas fa-arrow-right"></i> Selanjutnya
                                </button>                                                     
                            </div>
                        </div>                                                                           
                        <div class="tab-pane tab-pane-sub" id="tab20" for="konfirmasi">
                            <div class="col-md-12 col-xs-12 table-responsive">
                                <table id="table_confirm" class="table table-bordered" style="width:100%;">
                                </table>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="margin-top:20px;">
                                <button id="btn_tab_20b" class="btn btn-default" type="button" style="width:45%;">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>                             
                                <button id="btn_save_order" class="btn btn-primary" type="button" style="width:50%;">
                                    <i class="fas fa-arrow-right"></i> Proses
                                </button>   
                            </div>
                        </div>                      
                    </div>
                    </form>
                </div>
                <!-- End of Child Tab-Pane -->

            </div>
            <div class="tab-pane" id="tab2">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                        <div class="grid simple">
                            <div class="grid-body">
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                    <div class="row">
                                        <!--
                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                            <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                                <h5><b>Data <?php echo $title;?></b></h5>
                                            </div>
                                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                                <div class="pull-right">
                                                    <button id="btn_export_order" onClick="" class="btn btn-default btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-file-excel"></i>
                                                        Ekspor Excel
                                                    </button>
                                                    <button id="btn_new_order_2" class="btn btn-success btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-check-double"></i>
                                                        Status Kamar
                                                    </button>
                                                    <button id="btn_new_order" class="btn btn-success btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-plus"></i>
                                                        Buat <?php #echo $title; ?> Baru
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        -->
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                            <div class="clearfix"></div>

                                            <div class="col-md-12 col-xs-12">                                               
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
                                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 form-group padding-remove-right prs-15">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <label class="form-label">Cari</label>
                                                        <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian No.Book / Kontak / Telepon"/>
                                                    </div>
                                                </div>          
                                                <div class="clearfix"></div>
                                                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <label class="form-label">Status</label>
                                                        <select id="filter_flag_checkin" name="filter_flag_checkin" class="form-control">
                                                            <option value="All" selected>Semua</option>
                                                            <option value="0">Belum Check-In</option>
                                                            <option value="1">Check-In</option>
                                                            <option value="2">Check-Out</option>
                                                            <!-- <option value="4">Batal</option>                                                                                                                         -->
                                                        </select>
                                                    </div>
                                                </div>                                                  
                                                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <label class="form-label">Cabang</label>
                                                        <select id="filter_branch" name="filter_branch" class="form-control">
                                                            <?php 
                                                            if($session['user_data']['user_group_id'] < 3){
                                                                echo "<option value='All'>Semua</option>";
                                                            }
                                                            ?>
                                                            <?php
                                                            foreach($branch_cece as $val){
                                                                echo '<option value="'.$val['branch_id'].'">'.$val['branch_name'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>    
                                                <!-- <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <label class="form-label">Type</label>
                                                        <select id="filter_ref_price" name="filter_ref_price" class="form-control">
                                                            <option value="All">Semua</option>
                                                            <option value="0">Bulanan</option>                                                                                                                        
                                                        </select>
                                                    </div>
                                                </div>   -->

                                                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <label class="form-label">Jenis Kamar</label>
                                                        <select id="filter_ref" name="filter_ref" class="form-control">
                                                            <option value="All">Semua</option>
                                                            <?php
                                                            foreach($ref as $b){
                                                                echo '<option value="'.$b['branch_id'].'">'.$b['branch_name'].' - '.$b['ref_name'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>    
                                                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <label class="form-label">Pembayaran</label>
                                                        <select id="filter_paid_flag" name="filter_paid_flag" class="form-control">
                                                            <option value="All">Semua</option>
                                                            <option value="1">Lunas</option>
                                                            <option value="0">DP</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <label class="form-label">Metode Bayar</label>
                                                        <select id="filter_paid_payment_method" name="paid_payment_method" class="form-control">
                                                            <option value="All">Semua</option>
                                                            <option value="CASH">CASH</option>
                                                            <option value="TRANSFER">TRANSFER</option>
                                                            <option value="CASH_AND_TRANSFER">CASH & TRANSFER</option>                                                            
                                                        </select>
                                                    </div>
                                                </div>                                                                                               
                                                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
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
                                            <!--
                                            <div class="col-lg-12 col-md-12 col-xs-16 col-sm-16 form-group">
                                                <div class="panel-group" id="accordion" data-toggle="collapse" style="background-color: #eaeaea;border: 1px solid #eaeaea;margin-bottom:0px;">
                                                    <div id="panel-one" class="panel panel-default" style="display:inline;">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a class="" data-toggle="collapse" data-parent="#accordion" href="#collapseFilter">
                                                                    <i class="fa fa-filter"></i> 
                                                                    Filter
                                                                </a>
                                                            </h4>                                                       
                                                        </div>
                                                        <div id="collapseFilter" class="panel-collapse collapse">
                                                            <div class="panel-body" style="padding:0px;">                                                                    
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                                   
                                            </div>
                                            -->
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12" style="padding-top:10px;">
                                            <div class="table-responsive">
                                                <table id="table_order" class="table table-bordered" style="width:100%;">
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
<!-- Modal -->
<div class="modal fade" id="modal_croppie_1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div id="modal_croppie_canvas_1"></div>
            </div>
            <div class="modal-footer">
                <button id="modal_croppie_cancel_1" type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button id="modal_croppie_save_1" type="button" class="btn btn-primary">Crop</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_croppie_2" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div id="modal_croppie_canvas_2"></div>
            </div>
            <div class="modal-footer">
                <button id="modal_croppie_cancel_2" type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button id="modal_croppie_save_2" type="button" class="btn btn-primary">Crop</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_croppie_3" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div id="modal_croppie_canvas_3"></div>
            </div>
            <div class="modal-footer">
                <button id="modal_croppie_cancel_3" type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button id="modal_croppie_save_3" type="button" class="btn btn-primary">Crop</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_croppie_4" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div id="modal_croppie_canvas_4"></div>
            </div>
            <div class="modal-footer">
                <button id="modal_croppie_cancel_4" type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button id="modal_croppie_save_4" type="button" class="btn btn-primary">Crop</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-trans-print" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f3f5f6;">
                <h4 style="color:black;text-align:left;"><b id="modal-print-title">Berhasil Tersimpan</b></h4>
                <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="position:relative;top:-38px;float:right;">
                    <i class="fas fa-times"></i>                                 
                    Tutup
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-xs-12 padding-remove-side">
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
                                <td>Kontak</td>
                                <td>:<input id="modal-print-contact-name" name="modal-contact-name" value="" style="border:none!important;"></td>
                            </tr>         
                            <tr>
                                <td>Telepon</td>
                                <td>:<input id="modal-print-contact-phone" name="modal-contact-phone" value="" style="border:none!important;"></td>
                            </tr>   
                            <tr>
                                <td>Cabang</td>
                                <td class="modal_print_branch_name">:</td>
                            </tr>
                            <tr>
                                <td>Check-In</td>
                                <td class="modal_print_start_date">:</td>
                            </tr>
                            <tr>
                                <td>Kamar</td>
                                <td class="modal_print_product_name">:</td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td class="modal_print_total">:</td>
                            </tr>
                            <tr>
                                <td>Total Dibayar</td>
                                <td class="modal_print_total_paid">:</td>
                            </tr>
                        </table>
                    </div>           
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn_send_whatsapp btn btn-primary" 
                    data-order-id="0" data-order-number="" data-order-date="" data-total="" data-contact-id="" data-contact-name="" data-contact-phone="" style="width:45%;">
					<span class="fab fa-whatsapp white"></span> Kirim WhatsApp
				</button>
				<button type="button" id="btn_print_trans" class="btn_print_order btn btn-success" 
                    data-order-id="0" data-order-number="0" data-order-session="" style="width:45%;">
					<span class="fas fa-file-invoice white"></span> Cetak Struk
				</button>                  
            </div>
        </div>
    </div>
</div>