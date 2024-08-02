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
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
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
                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                        <div class="pull-right">
                                            <!-- <button id="btn-export" onClick="" class="btn btn-default btn-small" type="button"
                                                    style="display: none;">
                                                <i class="fas fa-file-excel"></i>
                                                Ekspor Excel
                                            </button>     
                                            <button id="btn-print-all" onClick="" class="btn btn-default btn-small" type="button"
                                                    style="display: inline;">
                                                <i class="fas fa-print"></i>
                                                Print <?php #echo $title; ?>
                                            </button>                                                          -->
                                            <button id="btn-new" onClick="" class="btn btn-success btn-small" type="button"
                                                    style="display: inline;">
                                                <i class="fas fa-plus"></i>
                                                Buat <?php echo $title; ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                    <div class="col-md-6 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                        <div class="hide col-lg-4 col-md-4 col-xs-12 col-sm-12 form-group padding-remove-right prs-5">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label">Group</label>
                                                <select id="filter_account_group" name="filter_account_group" class="form-control" readonly disabled>
                                                    <option value="0">-- Semua --</option>
                                                    <option value="1">Asset</option>
                                                    <option value="2">Liabilitas</option>
                                                    <option value="3">Ekuitas</option>
                                                    <option value="4">Pendapatan</option>
                                                    <option value="5" selected>Biaya</option>                             
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="hide col-lg-5 col-md-5 col-xs-8 col-sm-8 form-group padding-remove-right prs-5">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label">Group Sub</label>
                                                <select id="filter_account_group_sub" name="filter_account_group_sub" class="form-control" readonly disabled>
                                                    <option value="0">-- Semua --</option>
                                                    <option value="1">Akun Piutang</option>
                                                    <option value="2">Aktiva Lancar Lainnya</option>
                                                    <option value="3">Kas & Bank</option>
                                                    <option value="4">Persediaan</option>
                                                    <option value="5">Aktiva Tetap</option>
                                                    <option value="6">Aktiva Lainnya</option>
                                                    <option value="7">Depresiasi & Amortisasi</option>
                                                    <option value="8">Akun Hutang</option>
                                                    <option value="10">Kewajiban Lancar Lainnya</option>
                                                    <option value="12">Ekuitas</option>
                                                    <option value="13">Pendapatan</option>
                                                    <option value="14">Pendapatan Lainnya</option>
                                                    <option value="15">Harga Pokok Penjualan</option>
                                                    <option value="16" selected>Beban</option>  
                                                    <option value="17">Beban Lainnya</option>                        
                                                </select>
                                            </div>
                                        </div>      
                                        <div class="hide col-lg-3 col-md-3 col-xs-4 col-sm-4 form-group padding-remove-right prs-5">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label">Status</label>
                                                <select id="filter_account_flag" name="filter_account_flag" class="form-control" readonly disabled>
                                                    <option value="ALL">-- Semua --</option>
                                                    <option value="1" selected>Aktif</option>
                                                    <option value="0">Nonaktif</option>
                                                    <option value="4">Terhapus</option>                       
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12 padding-remove-side prs-0">                                                                 
                                        <div class="col-lg-8 col-md-8 col-xs-7 col-sm-7 form-group padding-remove-right prs-5">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label">Cari</label>
                                                <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                            </div>
                                        </div>                                 
                                        <div class="col-lg-4 col-md-4 col-xs-5 col-sm-5 form-group prs-5">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label">Tampil</label>
                                                <select id="filter_length" name="filter_length" class="form-control">
                                                    <option value="10">10 Baris</option>
                                                    <option value="25">25 Baris</option>
                                                    <option value="50">50 Baris</option>
                                                    <option value="100">100 Baris</option>
                                                    <option value="-1">Semua</option>
                                                </select>
                                            </div>
                                        </div>     
                                    </div>              
                                </div>  
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table id="table-data" class="table table-bordered display nowrap" data-limit-start="0" data-limit-end="10" style="width:100%;">
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

<div class="modal fade" id="modal-account" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-master" name="form-master" method="" action="">         
                <div class="modal-header" style="background-color: #6F7A8A;">
                    <h4 style="color:white;">Buat Akun Perkiraan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">  
                            <div class="col-md-12">
                                <input id="id_document" name="id_document" type="hidden" value="" placeholder="id" readonly>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">   
                                <div class="hide col-lg-5 col-md-5 col-xs-4 padding-remove-left">
                                    <div class="form-group">                        
                                        <label class="form-label">Group Akun *</label>
                                        <select id="group" name="group" class="form-control" disabled readonly>
                                            <option value="0">-- Pilih --</option>
                                            <option value="1">Asset</option>
                                            <option value="2">Liabilitas</option>
                                            <option value="3">Ekuitas</option>
                                            <option value="4">Pendapatan</option>
                                            <option value="5" selected>Biaya</option>          
                                        </select>
                                    </div>
                                </div> 
                                <div class="hide col-lg-7 col-md-7 col-xs-8 padding-remove-side">
                                    <div class="form-group">                        
                                        <label class="form-label">Group Sub Akun *</label>
                                        <select id="group_sub" name="group_sub" class="form-control" disabled readonly>
                                            <option value="0">-- Pilih --</option>
                                            <option value="1">Akun Piutang</option>
                                            <option value="2">Aktiva Lancar Lainnya</option>
                                            <option value="3">Kas & Bank</option>
                                            <option value="4">Persediaan</option>
                                            <option value="5">Aktiva Tetap</option>
                                            <option value="6">Aktiva Lainnya</option>
                                            <option value="7">Depresiasi & Amortisasi</option>
                                            <option value="8">Akun Hutang</option>
                                            <option value="10">Kewajiban Lancar Lainnya</option>
                                            <option value="12">Ekuitas</option>
                                            <option value="13">Pendapatan</option>
                                            <option value="14">Pendapatan Lainnya</option>
                                            <option value="15">Harga Pokok Penjualan</option>
                                            <option value="16" selected>Beban</option>  
                                            <option value="17">Beban Lainnya</option>        
                                        </select>
                                    </div>
                                </div>                             
                                <div class="clearfix"></div>
                                <div class="col-lg-5 col-md-5 col-xs-12 padding-remove-left">
                                    <div class="form-group">
                                        <label class="form-label">Kode *</label>
                                        <input id="kode" name="kode" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>                                                                                   
                                <div class="col-lg-7 col-md-7 col-xs-12 padding-remove-side prs-0">
                                    <div class="form-group">
                                        <label class="form-label">Nama *</label>
                                        <input id="nama" name="nama" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>                
                                <div class="col-lg-6 col-md-12 col-xs-6 padding-remove-side prr-5 prs-0">
                                    <div class="form-group">
                                        <label class="form-label">Status Akun</label>
                                        <!-- <select id="status" name="status" class="form-control" disabled readonly> -->
                                            <!-- <option value="">select</option> -->
                                        <div class="radio radio-success">
                                            <?php
                                            $status_values = array(
                                                '1' => 'Aktif',
                                                '0' => 'Nonaktif',
                                                '4' => 'Hapus'
                                            );

                                            foreach ($status_values as $value => $display_text) {
                                                $checked = '';
                                                if($value == 1){
                                                    $checked = 'checked="checked"';
                                                }
                                                // echo '<option value="' . $value . '" ' . $selected . '>' . $display_text . '</option>';
                                                echo '<input id="'.strtolower($display_text).'" type="radio" name="status" value="'.intval($value).'" '.$checked.'><label for="'.strtolower($display_text).'">'.$display_text.'</label>';
                                            }
                                            ?>
                                        <!-- </select> -->
                                        </div>                                        
                                    </div>         
                                </div>
                                <div class="hide col-lg-6 col-md-12 col-xs-6 padding-remove-right prs-0">
                                    <div class="form-group">
                                        <label class="form-label">Pantau Di Beranda</label>
                                        <!-- <select id="account_locked" name="account_locked" class="form-control" disabled readonly> -->
                                        <div class="radio radio-primary">
                                            <?php
                                            $status_values = array(
                                                '0' => 'Tidak',
                                                '1' => 'Ya',
                                            );

                                            foreach ($status_values as $value => $display_text) {
                                                $checked = '';
                                                if($value == 0){
                                                    $checked = 'checked="checked"';
                                                }                                                
                                                // echo '<option value="' . $value . '" ' . $selected . '>' . $display_text . '</option>';
                                                echo '<input id="'.strtolower($display_text).'" type="radio" name="account_locked" value="'.intval($value).'" '.$checked.'><label for="'.strtolower($display_text).'">'.$display_text.'</label>';                                                
                                            }
                                            ?>
                                        </div>
                                        <!-- </select> -->
                                    </div>         
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-cancel" onClick="formCancel();" class="btn btn-warning btn-small" type="reset" style="display: none;">
                        <i class="fas fa-ban"></i> 
                        Batal
                    </button>                                                                  
                    <button id="btn-save" onClick="" class="btn btn-primary btn-small" type="button" style="display:none;">
                        <i class="fas fa-save"></i>                                 
                        Simpan
                    </button>                                        
                    <button id="btn-update" class="btn btn-info btn-small" type="button" style="display: none;">
                        <i class="fas fa-edit"></i> 
                        Perbarui
                    </button> 
                    <button id="btn-delete" class="btn btn-danger btn-small" type="button" style="display: none;">
                        <i class="fas fa-trash"></i> 
                        Hapus
                    </button>                    
                </div>
            </form>      
        </div>
    </div>
</div>