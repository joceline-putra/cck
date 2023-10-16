<style>
    .scroll { 
        margin-top:4px;
        margin-bottom: 8px;
        margin-left:4px;
        margin-right: 4px; 
        padding:4px; 
        /*background-color: green; */
        width: 100%; 
        height: 400px; 
        overflow-x: hidden; 
        overflow-y: auto; 
        text-align:justify; 
    } 
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
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
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                            <h5><b>Statistik Pembelian</b></h5>                             
                                            <div class="col-md-2 col-xs-4 form-group padding-remove-left">
                                                <label class="form-label">Periode Awal</label>
                                                <div class="col-md-12 col-sm-12 padding-remove-side">
                                                    <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                        <input name="start" id="start" type="text" 
                                                               class="form-control input-sm" readonly="true"
                                                               value="<?php echo $first_date; ?>">
                                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>  
                                            </div>

                                            <div class="col-md-2 col-xs-4 form-group">
                                                <label class="form-label">Periode Akhir</label>
                                                <div class="col-md-12 col-sm-12 padding-remove-side">
                                                    <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                        <input name="end" id="end" type="text" 
                                                               class="form-control input-sm" readonly="true"
                                                               value="<?php echo $end_date; ?>">
                                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>  
                                            </div> 

                                            <div class="hide col-md-3 col-xs-4 form-group">
                                                <label class="form-label">Action</label>
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <!--
                                                    <button id="btn-preview" class="btn btn-primary" type="button" style="padding:5px 12px;"
                                                      data-action="1" data-request="1">
                                                      <i class="fa fa-list-alt"></i>&nbsp;&nbsp;Print & Preview
                                                    </button>
                                                    -->
                                                    <div class="btn-group"> 
                                                        <a class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 
                                                            <i class="fas fa-print"></i>&nbsp;&nbsp;Print&nbsp;&nbsp;<span class="fa fa-angle-down"></span> 
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a href="#" class="btn-print-all" data-action="1" data-request="report_pembelian">
                                                                    <i class="fas fa-file-pdf"></i>&nbsp;&nbsp;PDF
                                                                </a>
                                                            </li>
                                                            <li class="hide">
                                                                <a href="#" class="btn-print-all" data-action="2" data-request="2">
                                                                    <i class="fas fa-file-excel"></i>&nbsp;&nbsp;Excel
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>  
                                            <div class="col-md-8 col-xs-12 form-group padding-remove-side">
                                                <div class="pull-right">
                                                    <label class="form-label">&nbsp;</label>
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <button id="btn-preview" class="btn btn-primary" type="button" style="padding:5px 12px;"
                                                                data-action="1" data-request="1">
                                                            <i class="fa fa-list-alt"></i>&nbsp;&nbsp;Print & Preview
                                                        </button>
                                                        <div class="btn-group"> 
                                                            <a class="btn btn-success btn-small dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 
                                                                <i class="fas fa-plus"></i>&nbsp;&nbsp;Buat Transaksi&nbsp;&nbsp;<span class="fa fa-angle-down"></span> 
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-statistic" style="">
                                                                <li>
                                                                    <a href="<?php echo base_url('quotation/order/new'); ?>" class="btn-default">
                                                                        <i class="fas fa-file-alt"></i>&nbsp;&nbsp;Penawaran Harga
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="<?php echo base_url('sales/order/new'); ?>" class="btn-default">
                                                                        <i class="fas fa-file-alt"></i>&nbsp;&nbsp;Pesanan Penjualan
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="<?php echo base_url('sales/buy/new'); ?>" class="btn-default">
                                                                        <i class="fas fa-file-alt"></i>&nbsp;&nbsp;Penagihan Penjualan
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="<?php echo base_url('sales/return/new'); ?>" class="btn-default">
                                                                        <i class="fas fa-file-alt"></i>&nbsp;&nbsp;Retur Penjualan
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="<?php echo base_url('finance/accout_receivable/new'); ?>" class="btn-default">
                                                                        <i class="fas fa-file-alt"></i>&nbsp;&nbsp;Pembayaran Piutang
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
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                        <div class="grid simple">
                            <div class="grid-body">                   
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                        <div class="col-md-4 col-xs-12 col-sm-12" style="padding-left: 0;">               
                                            <div class="grid simple">
                                                <div class="grid-body">                   
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                                            <h5><b><i class="fas fa-file-alt fa-2x"></i> Pembelian Belum Dibayar</b></h5>      
                                                            <h3 id="card-1">Rp 0</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>
                                        <div class="col-md-4 col-xs-12 col-sm-12" style="padding-left: 0;">                      
                                            <div class="grid simple">
                                                <div class="grid-body">                   
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                                            <h5><b><i class="fas fa-calendar-minus fa-2x"></i> Pembelian Jatuh Tempo</b></h5>
                                                            <h3 id="card-2">Rp 0</h3>                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                       
                                        </div>
                                        <div class="col-md-4 col-xs-12 col-sm-12" style="padding-left: 0;">
                                            <div class="grid simple">
                                                <div class="grid-body">                   
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                                            <h5><b><i class="fas fa-money-bill fa-2x"></i> Pelunasan Terakhir</b></h5>                              
                                                            <h3 id="card-3">Rp 0</h3>                            
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
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                        <div class="grid simple">
                            <div class="grid-body">                   
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                        <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">               
                                            <div class="grid simple">
                                                <div class="grid-body">                   
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                                            <h5><b><i class="fas fa-table"></i> Total Pemasukan</b></h5>     
                                                            <canvas id="chart-one"></canvas> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">                      
                                            <div class="grid simple">
                                                <div class="grid-body">                   
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                                            <h5><b><i class="fas fa-table"></i> Total Pengeluaran</b></h5>
                                                            <canvas id="chart-two"></canvas>                               
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                       
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">                      
                                            <div class="grid simple">
                                                <div class="grid-body">                   
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                                            <h5><b><i class="fas fa-table"></i> Total Keseluruhan</b></h5>
                                                            <canvas id="chart-three"></canvas>                               
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
        </div>
    </div>
</div>