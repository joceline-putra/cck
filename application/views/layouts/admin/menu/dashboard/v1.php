<style>
    .collapse.in{
        background-color:rgb(243, 243, 243); 
    }

    #table-collapse-four td, #table-collapse-five td{
        padding: 4px 12px!important;
    }
    /*  
    .add-on{
            background-color: white!important;
            color:#454b50!important;
            height:0px!important;
            margin-top: 1px;
            margin-right: 1px;
    }*/
    .orange{
        background-color: #ef6605;
    }
    .sunset-orange{
        background-color: #f35958;
    }
    .grid-title h5{
        font-size:12px!important;
    }
    h4{
        font-weight: 600;
    }
</style> 
<?php
if ($session['user_data']['user_id'] == 0) {
} else {
    ?>

    <!-- Dashboard For User -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
            <div class="panel-group" id="accordion" data-toggle="collapse">
                <div id="panel-zero" class="panel panel-default" style="display:none;">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseZero">
                                <span id="badge-permintaan-approval" class="badge badge-danger"></span> <i class="hide fa fa-lock"></i> 
                                Dokumen Membutuhkan Persetujuan Anda 
                            </a>
                        </h4>
                    </div>
                    <div id="collapseZero" class="panel-collapse collapse">
                        <div class="panel-body" style="padding:0px;">
                            <table class="table" id="table-request-approval" style="background-color:white; ">
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- /Permintaan persetujuan -->
            </div>  
        </div> 
        <div class="hide col-md-12 col-sm-12 col-xs-12">
            <div class="grid simple">
                <div class="grid-body">
                    <!-- payment type -->
                    <div class="col-md-2 col-sm-2 col-xs-6 m-b-10">
                        <div class="tiles white ">
                            <div class="tiles-body">
                                <div class="controller">
                                    <a onclick="get_payment_method(1);" class="reload"></a>
                                    <a href="javascript:;" class="remove hide"></a>
                                </div>
                                <div class="tiles-title">Cash</div>
                                <div class="heading"> <span class="animate-number payment cash" data-id="1">0</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-6 m-b-10">
                        <div class="tiles sunset-orange ">
                            <div class="tiles-body">
                                <div class="controller">
                                    <a onclick="get_payment_method(2);" class="reload"></a>
                                    <a href="javascript:;" class="remove hide"></a>
                                </div>
                                <div class="tiles-title">Debit/Credit</div>
                                <div class="heading"> <span class="animate-number payment card" data-id="2">0</span> </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-6 m-b-10">
                        <div class="tiles blue ">
                            <div class="tiles-body">
                                <div class="controller">
                                    <a onclick="get_payment_method(3);" class="reload"></a>
                                    <a href="javascript:;" class="remove hide"></a>
                                </div>
                                <div class="tiles-title">Dana</div>
                                <div class="heading"> <span class="animate-number payment dana" data-id="3">0</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-6 m-b-10">
                        <div class="tiles green ">
                            <div class="tiles-body">
                                <div class="controller">
                                    <a onclick="get_payment_method(4);" class="reload"></a>
                                    <a href="javascript:;" class="remove hide"></a>
                                </div>
                                <div class="tiles-title">GOPAY</div>
                                <div class="heading"> <span class="animate-number payment gopay" data-id="4">0</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-6 m-b-10">
                        <div class="tiles purple ">
                            <div class="tiles-body">
                                <div class="controller">
                                    <a onclick="get_payment_method(5);" class="reload"></a>
                                    <a href="javascript:;" class="remove hide"></a>
                                </div>
                                <div class="tiles-title">OVO</div>
                                <div class="heading"> <span class="animate-number payment ovo" data-id="5">0</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-6 m-b-10">
                        <div class="tiles orange ">
                            <div class="tiles-body">
                                <div class="controller">
                                    <a onclick="get_payment_method(6);" class="reload"></a>
                                    <a href="javascript:;" class="remove hide"></a>
                                </div>
                                <div class="tiles-title">ShopeePay</div>
                                <div class="heading"> <span class="animate-number payment shopeepay" data-id="6">0</span> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>      
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="grid simple">
                <div class="grid-title no-border">
                    <h5>Grafik Pemasukan & Biaya Operasional</h5>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                        <a href="javascript:;" class="reload" onclick="chart_last_order(1);"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>      
                <div class="grid-body no-border" style="padding:10px;">
                    <div class="col-md-12 col-sm-12 padding-remove-side" style="">
                        <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">               -->
                        <canvas id="chart-one" width="400" height="200"></canvas>
                        <!-- </div>   -->
                    </div>       
                </div>
            </div>
        </div>  
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="grid simple">
                <div class="grid-title no-border">
                    <h5>Grafik Jual Beli</h5>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>      
                <div class="grid-body no-border" style="padding:10px;">                   
                    <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                    <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                        <canvas id="chart-two" width="400" height="200"></canvas>                               
                    </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="grid simple">
                <div class="grid-title no-border">
                    <h5>Grafik Pergerakan Transaksi</h5>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>      
                <div class="grid-body no-border" style="padding:10px;">                   
                    <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                    <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                        <canvas id="chart-three" width="400" height="200"></canvas>                               
                    </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>    
        <div class="clearfix"></div>
        <div class="col-md-12 col-sm-12 col-xs-12">        
            <div class="grid simple">
                <div class="grid-body" style="padding-bottom: 4px;">
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="grid simple">
                            <div class="grid-title no-border" style="padding-bottom: 0px;padding-top:10px;background-color: #DB2E59;">
                                <h5 class="" style="width:70%;color:white;"><b><i class="fas fa-shopping-cart" style="color:white;"></i> Pembelian Bulan Ini</b></h5>
                                <div class="tools">
                                    <!-- <a href="javascript:;" class="collapse" style="color:white;"></a> -->
                                    <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                                    <a href="javascript:;" class="reload" style="color:white;"></a>
                                    <a href="javascript:;" class="remove" style="color:white;"></a>
                                </div>
                            </div>      
                            <div class="grid-body no-border" style="padding:0px 0px 10px 0px;background-color: #DB2E59;">                   
                                <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                                <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                                        <!-- <canvas id="chart-four" width="400" height="180"></canvas>                                -->
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12">

                                    <h4 id="total-buy-month" class="" style="margin:0px;color:white;">Rp. 0</h4>                            
                                </div>          
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="grid simple">
                            <div class="grid-title no-border" style="padding-bottom: 0px;padding-top:10px;background-color: #0090d9;">
                                <h5 class="" style="width:70%;color:white;"><b><i class="fas fa-cash-register" class="" style="color:white;"></i> 
                                        Penjualan Bulan Ini</b></h5>
                                <div class="tools">
                                    <!-- <a href="javascript:;" class="collapse" style="color:white;"></a> -->
                                    <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                                    <a href="javascript:;" class="reload" style="color:white;"></a>
                                    <a href="javascript:;" class="remove" style="color:white;"></a>
                                </div>
                            </div>      
                            <div class="grid-body no-border" style="padding:0px 0px 10px 0px;background-color: #0090d9;">                   
                                <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                                <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                                        <!-- <canvas id="chart-four" width="400" height="180"></canvas>                                -->
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12">

                                    <h4 id="total-sell-month" class="" style="margin:0px;color:white;">Rp. 0</h4>                            
                                </div>          
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="grid simple">
                            <div class="grid-title no-border" style="padding-bottom: 0px;padding-top:10px;background-color: #0aa699;">
                                <h5 class="" style="width:70%;color:white;"><b><i class="fas fa-arrow-circle-down" style="color:white;"></i> Pemasukan Bulan Ini</b></h5>
                                <div class="tools">
                                    <!-- <a href="javascript:;" class="collapse" style="color:white;"></a> -->
                                    <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                                    <a href="javascript:;" class="reload" style="color:white;"></a>
                                    <a href="javascript:;" class="remove" style="color:white;"></a>
                                </div>
                            </div>      
                            <div class="grid-body no-border" style="padding:0px 0px 10px 0px;background-color: #0aa699;">                   
                                <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                                <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                                        <!-- <canvas id="chart-four" width="400" height="180"></canvas>                                -->
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12">

                                    <h4 id="total-cash-in-month" class="" style="margin:0px;color:white;">Rp. 0</h4>                            
                                </div>          
                                <!-- </div> -->
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="grid simple">
                            <div class="grid-title no-border" style="padding-bottom: 0px;padding-top:10px;background-color: #ef6605;">
                                <h5 class="" style="width:70%;color:white"><b><i class="fas fa-arrow-circle-up" style="color:white;"></i> Biaya Bulan Ini</b></h5>
                                <div class="tools">
                                    <!-- <a href="javascript:;" class="collapse" style="color:white;"></a> -->
                                    <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                                    <a href="javascript:;" class="reload" style="color:white;"></a>
                                    <a href="javascript:;" class="remove" style="color:white;"></a>
                                </div>
                            </div>      
                            <div class="grid-body no-border" style="padding:0px 0px 10px 0px;background-color: #ef6605;">                   
                                <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                                <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                                        <!-- <canvas id="chart-four" width="400" height="180"></canvas>                                -->
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12">

                                    <h4 id="total-cash-out-month" class="" style="margin:0px;color:white;">Rp. 0</h4>                            
                                </div>          
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="margin-top:10px;">
            <div class="col-lg-7 col-md-12 col-xs-12 padding-remove-side">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active">
                        <a href="#tab1" role="tab" data-toggle="tab" aria-expanded="true">
                            <i class="fas fa-chalkboard-teacher"></i> Aktivitas
                        </a>
                    </li>
                    <li class="">
                        <a href="#tab2" onclick="" role="tab" data-toggle="tab" aria-expanded="false">
                            <i class="fas fa-chart-bar"></i> Statistik
                        </a>
                    </li>                                    
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1">
                        <div class="col-md-12 col-sm-12 padding-remove-side">
                        </div>            
                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                            <div class="grid simple">
                                <div class="grid-body">
                                    <div class="col-md-12 col-sm-12 padding-remove-side">
                                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 padding-remove-left">
                                            <!-- <label class="form-label">Tanggal</label> -->
                                            <div id="filter_date" data-start="<?php echo $end_date; ?>" data-end="<?php echo $end_date; ?>" class="filter-daterangepicker" style="padding-top:4px;padding-bottom:4px;">
                                                <i class="fas fa-calendar-alt"></i>&nbsp;
                                                <span></span> 
                                                &nbsp;&nbsp;&nbsp;<i class="fas fa-caret-down" style="position: absolute;right: 24px;top: 7px;"></i>
                                            </div>
                                        </div>									
                                        <div class="form-group col-md-4 col-sm-12 col-xs-12 padding-remove-left">
                                            <!-- <label class="form-label" style="font-size: 12px;margin-bottom: 0!important;">User</label> -->
                                            <div class="">
                                                <select name="dashboard_user" id="dashboard_user" style="width:100%">
                                                    <option value="0">Semua User</option>                                                 
                                                    <?php
                                                    foreach ($usernya as $i => $v) {
                                                        echo "<option value=" . $v['user_id'] . ">" . ucwords(strtolower($v['user_username'])) . "</option>";
                                                    }
                                                    ?>                       
                                                </select>
                                            </div>
                                        </div>													
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 padding-remove-side">
                                <div class="row tiles-container m-b-10">
                                    <!-- <div class="col-md-12"> -->
                                    <div class="m-l-12 ">
                                        <div class="tiles grey p-t-5 p-b-5 p-l-25 ">
                                            <!-- <h5 class="text-black semi-bold">MOST POPULAR</h5> -->
                                        </div>
                                        <div id="dashboard-notif" class="tiles white">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hide grid-body no-border" style="">
                                <div class="post">
                                    <div class="user-profile-pic-wrapper">
                                        <div class="user-profile-pic-normal"> <img width="35" height="35" data-src-retina="assets/img/profiles/avatar_small2x.jpg" data-src="assets/img/profiles/avatar_small.jpg" src="" alt=""> </div>
                                    </div>
                                    <div class="info-wrapper">
                                        <div class="info" style="padding-left:5px;padding-bottom: 5px;"> 
                                            Hi I have installed agent to monitor the usage of the droplet done via Anturis and lately there was a lot of down time. One that caught my eye was this Incident report
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab2">                     
                    </div>                                                    
                </div>
            </div>
            <div class="col-lg-5 col-md-12 col-xs-12 padding-remove-side">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-right">
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                        <div class="grid simple">
                            <div class="grid-title no-border">
                                <h5>Biaya Operasional Terpantau</h5>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                                    <a onclick="chart_expense();" href="javascript:;" class="reload"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                            </div>      
                            <div class="grid-body no-border" style="padding:10px;">                   
                                <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                                <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                                    <canvas id="chart-expense" width="400" height="180"></canvas>                               
                                </div>
                                <!-- </div> -->
                                <table class="table no-more-tables m-t-20 m-b-30">
                                    <thead>
                                        <tr>
                                            <td style="">Akun</td>
                                            <td style="text-align:right;">Nilai Saat ini</td>
                                            <!-- <td style="text-align:left;">Pergerakan Terakhir</td>-->
                                        </tr>
                                    </thead>
                                    <tbody class="top-expense-data">
                                        <tr class="expense-no-data">
                                            <td colspan="2" style="text-align: center;">-- Data tidak tersedia --</td>
                                        </tr>
                                    </tbody>
                                </table>        
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                        <div class="grid simple">
                            <div class="grid-title no-border">
                                <h5>Pantauan Akun Realtime</h5>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                                    <a onclick="chart_account();" href="javascript:;" class="reload"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                            </div>      
                            <div class="grid-body no-border" style="padding:10px;">                   
                                <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                                <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                                    <canvas id="chart-account" width="400" height="180"></canvas>                               
                                </div>
                                <!-- </div> -->
                                <table class="table no-more-tables m-t-20 m-b-30">
                                    <thead>
                                        <tr>
                                            <td style="">Akun</td>
                                            <td style="text-align:right;">Nilai Saat ini</td>
                                            <!-- <td style="text-align:left;">Pergerakan Terakhir</td>-->
                                        </tr>
                                    </thead>
                                    <tbody class="top-account-data">
                                        <tr class="account-no-data">
                                            <td colspan="2" style="text-align: center;">-- Data tidak tersedia --</td>
                                        </tr>
                                    </tbody>
                                </table>        
                            </div>
                        </div>
                    </div>
                    <div id="top_buy_data" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="display:none;">
                        <div class="grid simple">
                            <div class="grid-title no-border">
                                <h5>10 Pembelian Produk</h5>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a onclick="" href="javascript:;" class="reload"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                            </div>      
                            <div class="grid-body no-border" style="padding:10px;">                   
                                <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                                <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                                <!-- <canvas id="chart-four" width="400" height="180"></canvas>-->
                                </div>
                                <!-- </div> -->
                                <table class="table no-more-tables">
                                    <thead>
                                        <tr>
                                            <td>Produk</td>
                                            <!-- <td>Harga</td> -->
                                            <td class="text-right">Qty</td>
                                            <!-- <td>Pergerakan</td> -->
                                        </tr>
                                    </thead>
                                    <tbody class="top-buy-data">
                                        <tr class="buy-no-data">
                                            <td colspan="2" style="text-align: center;">-- Data tidak tersedia --</td>
                                        </tr>
                                    </tbody>
                                </table>      
                            </div>
                        </div>
                    </div>
                    <div id="top_contact" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="display:none;">
                        <div class="grid simple">
                            <div class="grid-title no-border">
                                <h5>Top Contact Payment</h5>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                                    <a onclick="" href="javascript:;" class="reload"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                            </div>      
                            <div class="grid-body no-border" style="padding:10px;">                   
                                <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                                <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                                <!-- <canvas id="chart-four" width="400" height="180"></canvas>                                -->
                                </div>
                                <!-- </div> -->
                                <table id="table-top-contact" class="table no-more-tables">
                                    <thead>
                                        <tr>
                                            <td>Kontak</td>
                                            <td style="text-align:right;">Total</td>
                                            <!-- <td>Pergerakan Terakhir</td> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="text-align: center;">-- Data tidak tersedia --</td>
                                        </tr>
                                    </tbody>
                                </table>      
                            </div>
                        </div>
                    </div>
                    <div id="top_buy_overdue" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="display:none;">
                        <div class="grid simple">
                            <div class="grid-title no-border">
                                <h5>Hutang Sampai Hari Ini</h5>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                                    <a onclick="top_product(1);" href="javascript:;" class="reload"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                            </div>      
                            <div class="grid-body no-border" style="padding:10px;">                   
                                <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                                <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                                <!-- <canvas id="chart-four" width="400" height="180"></canvas>-->
                                </div>
                                <!-- </div> -->
                                <table id="table-top-buy-overdue" class="table no-more-tables">
                                    <thead>
                                        <tr>
                                            <td>Status Pembelian</td>
                                            <td style="text-align:right;">Total</td>
                                            <!-- <td>Pergerakan Terakhir</td> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="text-align: center;">-- Data tidak tersedia --</td>
                                        </tr>
                                    </tbody>
                                </table>      
                            </div>
                        </div>
                    </div>
                    <div id="top_sell_overdue" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="display:none;">
                        <div class="grid simple">
                            <div class="grid-title no-border">
                                <h5>Tagihan Sampai Hari Ini</h5>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                                    <a onclick="top_product(1);" href="javascript:;" class="reload"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                            </div>      
                            <div class="grid-body no-border" style="padding:10px;">                   
                                <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                                <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                                <!-- <canvas id="chart-four" width="400" height="180"></canvas>-->
                                </div>
                                <!-- </div> -->
                                <table id="table-top-sell-overdue" class="table no-more-tables">
                                    <thead>
                                        <tr>
                                            <td>Status Penjualan</td>
                                            <td style="text-align:right;">Total</td>
                                            <!-- <td>Pergerakan Terakhir</td> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="text-align: center;">-- Data tidak tersedia --</td>
                                        </tr>
                                    </tbody>
                                </table>      
                            </div>
                        </div>
                    </div>        
                    <div id="top_sell_data" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="display:none;">
                        <div class="grid simple">
                            <div class="grid-title no-border">
                                <h5>10 Penjualan Produk</h5>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <!-- <a href="#grid-config" data-toggle="modal" class="config"></a> -->
                                    <a onclick="top_product(2);" href="javascript:;" class="reload"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                            </div>      
                            <div class="grid-body no-border" style="padding:10px;">                   
                                <!-- <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> -->
                                <div class="col-md-12 col-sm-12 padding-remove-side" style="">          
                                <!-- <canvas id="chart-four" width="400" height="180"></canvas>                                -->
                                </div>
                                <!-- </div> -->
                                <table class="table no-more-tables">
                                    <thead>
                                        <tr>
                                            <td>Produk</td>
                                            <!-- <td>Harga</td> -->
                                            <td class="text-right">Qty</td>
                                            <!-- <td>Pergerakan</td> -->
                                        </tr>
                                    </thead>                 
                                    <tbody class="top-sell-data">
                                        <tr class="sell-no-data">
                                            <td colspan="2" style="text-align: center;">-- Data tidak tersedia --</td>
                                        </tr>
                                    </tbody>
                                </table>     
                            </div>
                        </div>
                    </div>  
                </div>      
            </div>    
        </div>  
    </div>
    <?php
}
?>   