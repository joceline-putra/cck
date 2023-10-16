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
    <?php include '_navigation.php';?>
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
                      <h5><b>Statistik Checkup</b></h5>                             
                      <div class="col-md-2 col-xs-4 form-group padding-remove-left">
                        <label class="form-label">Periode Awal</label>
                          <div class="col-md-12 col-sm-12 padding-remove-side">
                            <div class="input-append success date col-md-12 col-lg-12 no-padding">
                              <input name="start" id="start" type="text" 
                                class="form-control input-sm" readonly="true"
                                value="<?php echo $first_date;?>">
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
                                value="<?php echo $end_date;?>">
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
                                  <a href="<?php echo base_url('checkup/medicine/new');?>" class="btn-default">
                                    <i class="fas fa-file-alt"></i>&nbsp;&nbsp;Checkup Kesehatan
                                  </a>
                                </li>
                                <li>
                                  <a href="<?php echo base_url('checkup/laboratory/new');?>" class="btn-default">
                                    <i class="fas fa-file-alt"></i>&nbsp;&nbsp;Checkup Laboratorium
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
                              <h5><b><i class="fas fa-file-alt fa-2x"></i> Pembelian Belum Terbayar</b></h5>      
                              <h3 id="total-cash-balance">0</h3>
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
                              <h3 id="total-cash-in-month">Rp 0</h3>                            
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
                              <h3 id="total-cash-out-month">Rp 0</h3>                          
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
                              <h5><b>Saldo Akun Terpantau</b></h5>
                              <table id="table-top-cash-bank" class="table no-more-tables m-t-15 m-b-15">
                                <thead>
                                  <tr>
                                    <th style="width:9%">Akun</th>
                                    <th class="text-right" style="width:6%">Saldo</th>
                                    <th style="width:1%"></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr class="">
                                    <td class="text-center" colspan="3">Tidak ada data</td>
                                  </tr>                                   
                                </tbody>
                              </table> 
                              <h6 style="text-align:center;"><b><a href="#" class="btn-show-all" data-id="0">Lihat Semua</a></b></h6>
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
                              <h5><b>Top Kontak Terbanyak</b></h5> 
                              <table id="table-top-contact" class="table no-more-tables m-t-15 m-b-15">
                                <thead>
                                  <tr>
                                    <th style="width:9%">Kontak</th>
                                    <th class="text-right" style="width:6%">Total</th>
                                    <th style="width:1%"></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td class="text-center" colspan="3">Tidak ada data</td>
                                  </tr>                                 
                                </tbody>
                              </table>       
                              <h6 style="text-align:center;"><b><a href="#" class="btn-show-all" data-id="1">Lihat Semua</a></b></h6>     
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
                              <h5><b>Top Biaya Terbanyak</b></h5>     
                              <table id="table-top-expense" class="table no-more-tables m-t-15 m-b-15">
                                <thead>
                                  <tr>
                                    <th style="width:9%">Akun</th>
                                    <th class="text-right" style="width:6%">Jumlah</th>
                                    <th style="width:1%"></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td class="text-center" colspan="3">Tidak ada data</td>
                                  </tr>                                  
                                </tbody>
                              </table>     
                              <h6 style="text-align:center;"><b><a href="#" class="btn-show-all" data-id="2">Lihat Semua</a></b></h6>     
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
                              <h5><b>Total Pemasukan</b></h5>     
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
                              <h5><b>Total Pengeluaran</b></h5>
                              <canvas id="chart-two"></canvas>                               
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

<!-- Modal -->
<div class="modal fade" id="modal-statistic" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <form id="form-modal" name="form-modal" method="" action="">
        <div class="modal-header" style="background-color: #f3f5f6;">
          <h4 id="modal-title" style="">Modal Title</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="color:black;">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">  
              <table class="table no-more-tables m-t-15 m-b-15">
                <thead>
                  <tr>
                    <th style="width:9%">Akun</th>
                    <th class="text-right" style="width:6%">Jumlah</th>
                    <th style="width:1%"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-center" colspan="3">Tidak ada data</td>
                  </tr>                                  
                </tbody>
              </table>     
            </div>          
            <!--
            <div class="col-md-3 col-xs-12">
              <p></p>
              <p class="text-center">
                <i class="fas fa-boxes fa-4x"></i>
              </p>
            </div>
            <div class="col-md-9 col-xs-12"> 
              <div class="col-md-12 col-sm-12 col-xs-12">              
                <div class="col-lg-5 col-md-5 col-xs-12 padding-remove-side">
                  <div class="form-group">
                    <label>Kode</label>
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
            -->
          </div>
        </div>
        <div class="modal-footer">
          <!--
          <button id="btn-save" class="btn btn-primary btn-small" type="button" style="">
            <i class="fas fa-save"></i>
            Action
          </button>  
          -->  
          <button class="btn btn-outline-danger waves-effect btn-small" type="button" data-dismiss="modal">
            <i class="fas fa-times"></i>                                 
            Tutup
          </button>                   
        </div>
      </form>      
    </div>
  </div>
</div>