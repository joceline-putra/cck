<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
$branch_logo = !empty($branch['branch_logo_login']) ? $branch['branch_logo_login'] : site_url() . 'upload/branch/default_logo.png';

$user_id = !empty($this->session->flashdata('user_id')) ? $this->session->flashdata('user_id') : 0;
$user_session = !empty($this->session->flashdata('user_session')) ? $this->session->flashdata('user_session') : 0;
$user_username = !empty($this->session->flashdata('user_username')) ? $this->session->flashdata('user_username') : 0;
$user_phone = !empty($this->session->flashdata('user_phone')) ? $this->session->flashdata('user_phone') : '';
$user_email = !empty($this->session->flashdata('user_email')) ? $this->session->flashdata('user_email') : '';

$message = !empty($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';
$status = !empty($this->session->flashdata('status')) ? $this->session->flashdata('status') : 0;

//If User Session is valid 
if (!empty($user_id) && !empty($user_session)) {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <link href="<?php echo base_url(); ?>assets/core/favicon.png" sizes="16x16 32x32" type="image/png" rel="icon">     
        <link href="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/css/messenger-theme-flat.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="<?php echo base_url(); ?>assets/core/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />    
        <link href="<?php echo base_url(); ?>assets/core/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/core/plugins/select2-4.0.8/css/select2.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="<?php echo base_url(); ?>assets/core/plugins/toastr/toastr.min.css"> 
        <link href="<?php echo base_url(); ?>assets/core/plugins/sweetalert2/sweetalert2.min.css"  rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/webarch/css/webarch.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/core/plugins/jconfirm-3.3.4/dist/jquery-confirm.min.css" rel="stylesheet">    
    </head>
    <style>
        /*@font-family: 'Open Sans', sans-serif!important;*/
        .form-control{
            height: 34px!important;
        }
        .form-label{
            display: block!important;
            text-align: left!important;
        }
        .login-container{
            margin-top:2%!important;
        }
        .login-container > div{
            /*padding: 15px;*/
            background-color: #ffffff;
            margin-bottom: 20px;
            border: 1px solid #cacaca!important;
            padding:0px!important;
        }
        .login-form input{
            font-family: var(--font-family);
            font-size: 14px;
            /*font-weight: 600;*/
        }
        .div-login{
            margin-bottom:6px;
        }
        #btnLogin{
            width: 100%;
        } 
        .select2-container .select2-selection--single {
            height: 34px;
        }
        .select2-selection__rendered {
            padding-top:2px;
            text-align: left!important;
        }
        /*@background-url: '';*/
        /*// Extra small devices (portrait phones, less than 576px)*/
        /*// No media query for `xs` since this is the default in Bootstrap*/

        /*// Small devices (landscape phones, 576px and up)*/
        @media only screen and (min-width: 576px) {
            #body{
                background-color:#f89b1c!important;
                /*background: white url('services/assets/images/wallpaper/corona.png') no-repeat center center fixed; */
                /*background: white url('services/assets/images/wallpaper/pattern.jpg') repeat center center fixed;       */
                /*background: url('https://wallpaperaccess.com/full/417163.jpg') no-repeat center center fixed;   */
            } 
        }

        /*// Medium devices (tablets, 768px and up)*/
        @media only screen and (min-width: 768px) {

        }

        /*// Large devices (desktops, 992px and up)*/
        @media only screen and (min-width: 992px) {

        }

        /*// Extra large devices (large desktops, 1200px and up)*/
        @media only screen and (min-width: 1200px) {

        }


    </style>  
    <body class="error-body no-top" style="background: #cacaca!important;">
        <div class="container-fluid">
            <div class="row login-container column-seperation" style="">
                <div class="col-md-offset-2 col-md-8 col-xs-12 col-sm-12" 
                     style="padding: 30px;
                     background-color: #ffffff;
                     margin-bottom: 20px;
                     /*border-radius:20px;*/
                     border: 4px solid #cacaca!important;">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;padding-left:0px;padding-right:0px;">
                        <img src="<?php echo $branch_logo; ?>" class="img-responsive" style="padding-top:20px;margin:0 auto;width:150px;">
                        <h4 style="margin-bottom:0px;">Halo <b><?php echo $user_username; ?></b>, Selamat Datang di Platform kami</h4>
                        <p>Silahkan melengkapi data anda dibawah ini untuk memulai menggunakan layanan kami</p>        
                        <input id="ui" name="ui" value="<?php echo $user_id;?>" type="hidden">
                        <input id="us" name="us" value="<?php echo $user_session;?>" type="hidden">                           
                        <form action="#" method="post" class="login-form validate" id="register-form" name="register-form">
                            <div class="col-md-7 col-lg-7 col-xs-12 col-sm-12" style="padding-left:0px;padding-right:0px;">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">                                 
                                    <div class="row">
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <label class="form-label">Nama Perusahaan / Usaha</label>
                                            <input class="form-control" id="nama" name="nama" type="text" placeholder="" value="">
                                        </div>
                                    </div>            
                                </div>
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Jenis Usaha *</label>
                                            <select id="specialist" name="specialist" class="form-control">
                                                <option value="0">-- Pilih --</option>
                                            </select>
                                        </div>
                                    </div>       
                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                            <label class="form-label">Pencatatan Stok (Produk)</label>
                                            <select id="with_stock" name="with_stock" class="form-control">
                                                <option value="Yes">Aktifkan</option>
                                                <option value="No">Tidak</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                            <label class="form-label">Pencatatan Jurnal (Keuangan)</label>
                                            <select id="with_journal" name="with_journal" class="form-control">
                                                <option value="Yes">Aktifkan</option>
                                                <option value="No">Tidak</option>
                                            </select>
                                        </div>
                                    </div>                         
                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                            <label class="form-label">Telepon</label>
                                            <input class="form-control" id="telepon" name="telepon" type="text" required placeholder="" value="<?php echo $user_phone; ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                            <label class="form-label">Email</label>
                                            <input class="form-control" id="email" name="email" type="email" required placeholder="" value="<?php echo $user_email; ?>" readonly>
                                        </div>
                                    </div>  
                                </div>                   
                            </div>
                            <div class="col-md-5 col-lg-5 col-xs-12 col-sm-12" style="padding-left:0px;padding-right:0px;">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 form-group padding-remove-side">
                                            <div class="form-group">
                                                <label class="form-label">Alamat *</label>
                                                <textarea id="alamat" name="alamat" type="text" value="" class="form-control" rows="3"/></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 form-group padding-remove-side">
                                            <label class="form-label">Provinsi</label>
                                            <select id="provinsi" name="provinsi" class="form-control">
                                                <option value="0">-- Pilih --</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 form-group padding-remove-side">
                                            <label class="form-label">Kota / Kabupaten</label>
                                            <select id="kota" name="kota" class="form-control">
                                                <option value="0">-- Pilih --</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 form-group padding-remove-side">
                                            <label class="form-label">Kecamatan</label>
                                            <select id="kecamatan" name="kecamatan" class="form-control">
                                                <option value="0">-- Pilih --</option>
                                            </select>
                                        </div>                                      
                                    </div>          
                                </div>                             
                            </div>                     
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="margin-top:20px;">		  	
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 col-sm-12 div-login">
                                        <button id="btnRegister" class="btn pull-right" type="submit" style="width: 100%;background-color:#318fff;color:white;">
                                            <i class="fas fa-hand-point-right"></i>&nbsp;
                                            Proses
                                        </button>
                                    </div>
                                </div><br>              
                            </div>                                    
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTAINER -->

        <!-- BEGIN JS DEPENDECENCIES-->
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script>

        <!-- END CORE JS DEPENDECENCIES-->
        <script src="<?php echo base_url(); ?>assets/core/plugins/select2-4.0.8/js/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/messenger-theme-future.js" type="text/javascript"></script>

        <!-- END -->
        <!-- BEGIN PAGE LEVEL JS -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/demo/demo.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/core/plugins/notifications.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/toastr/toastr.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jconfirm-3.3.4/dist/jquery-confirm.min.js"></script>  
        <script>
            $(document).ready(function () {
                $("#nama").focus();
                // $("#txtsloganname").val('');
                // $("#txttelepon").val('085224980588');
                // $("#txtemail").val('joceline.putra@gmail.com');
                // $("#txtpassword").val('masterjoe00');
                // $("#txtpassword2").val('masterjoe00');  	

                // var form = '#register-form';   
                $('#specialist').select2({
                    placeholder: '--- Pilih ---',
                    minimumInputLength: 0,
                    ajax: {
                        type: "get",
                        url: "<?= base_url('search/manage'); ?>",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            var query = {
                                search: params.term,
                                // tipe: 2, //1=Supplier, 2=Customer
                                source: 'specialist'
                            }
                            return query;
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    templateResult: function (datas) { //When Select on Click
                        if (!datas.id) {
                            return datas.text;
                        }
                        if ($.isNumeric(datas.id) == true) {
                            // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                            return datas.text;
                        } else {
                            return datas.text;
                        }
                    },
                    templateSelection: function (datas) { //When Option on Click
                        if (!datas.id) {
                            return datas.text;
                        }
                        //Custom Data Attribute
                        // $(datas.element).attr('data-alamat', datas.alamat);
                        // $(datas.element).attr('data-telepon', datas.telepon);
                        // $(datas.element).attr('data-email', datas.email);            
                        return datas.text;
                    }
                });
                $('#provinsi').select2({
                    placeholder: '--- Pilih Kota / Kabupaten ---',
                    minimumInputLength: 0,
                    ajax: {
                        type: "get",
                        url: "<?= base_url('search/manage'); ?>",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            var query = {
                                search: params.term,
                                source: 'provinces'
                            }
                            return query;
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    templateSelection: function (data, container) {
                        // Add custom attributes to the <option> tag for the selected option
                        // $(data.element).attr('data-province-id', data.customValue);  
                        // $(data.element).attr('data-province-id', data.customValue);      
                        // $("input[name='satuan']").val(data.satuan);
                        return data.text;
                    }
                });
                $('#kota').select2({
                    placeholder: '--- Pilih Kota / Kabupaten ---',
                    minimumInputLength: 0,
                    ajax: {
                        type: "get",
                        url: "<?= base_url('search/manage'); ?>",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            var province_id = $("#provinsi").find(':selected').val();
                            if (parseInt(province_id) > 0) {
                                var query = {
                                    search: params.term,
                                    province_id: $("#provinsi").find(':selected').val(),
                                    source: 'cities'
                                }
                                return query;
                            } else {
                                notif(0, 'Masukkan Provinsi terlebih dahulu');
                            }
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    templateSelection: function (data, container) {
                        // Add custom attributes to the <option> tag for the selected option
                        // $(data.element).attr('data-province-id', data.province_id);  
                        // $(data.element).attr('data-province-name', data.province_name);      
                        // $("input[name='satuan']").val(data.satuan);
                        return data.text;
                    }
                });
                $('#kecamatan').select2({
                    placeholder: '--- Pilih Kota / Kabupaten ---',
                    minimumInputLength: 0,
                    ajax: {
                        type: "get",
                        url: "<?= base_url('search/manage'); ?>",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            // var province_id = $("#provinsi").find(':selected').val(),
                            var city_id = $("#kota").find(':selected').val();
                            if (parseInt(city_id) > 0) {
                                var query = {
                                    search: params.term,
                                    province_id: $("#provinsi").find(':selected').val(),
                                    city_id: $("#kota").find(':selected').val(),
                                    source: 'districts'
                                }
                                return query;
                            } else {
                                notif(0, 'Masukkan Kota / Kabupaten terlebih dahulu');
                            }
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    templateSelection: function (data, container) {
                        // Add custom attributes to the <option> tag for the selected option
                        // $(data.element).attr('data-province-id', data.province_id);  
                        // $(data.element).attr('data-province-name', data.province_name);    
                        // $(data.element).attr('data-city-id', data.city_id);  
                        // $(data.element).attr('data-city-name', data.city_name);                 
                        // $("input[name='satuan']").val(data.satuan);
                        return data.text;
                    }
                });
                $("#provinsi").on('change', function (e) {
                    $("select[id='kota']").val(0).trigger('change');
                    $("select[id='kecamatan']").val(0).trigger('change');
                });
                $("#kota").on('change', function (e) {
                    $("select[id='kecamatan']").val(0).trigger('change');
                });
                $("#kecamatan").on('change', function (e) {
                });
                $("#register-form").on('submit', (function (e) {
                    e.preventDefault();
                    var next = true;
                    var ui = $("#ui").val();
                    var us = $("#us").val();                    
                    if (us) {
                        if (ui.length == 0) {
                            notif(0, 'Gagal mendaftarkan perusahaan');
                            $("#us").focus();
                            next = false;
                        }
                    }

                    if (next) {
                        if ($("#register-form select[name='specialist']").find(':selected').val() == 0) {
                            notif(0, 'Jenis usaha belum diisi');
                            next = false;
                        }
                    }

                    if (next) {
                        // if ($("#register-form input[name='telepon']").val().length == 0) {
                        //     notif(0, 'Telepon belum diisi');
                        //     $("#telepon").focus();
                        //     next = false;
                        // }
                    }

                    if (next) {
                        // if ($("#register-form input[name='email']").val().length == 0) {
                        //     notif(0, 'Email belum diisi');
                        //     $("#email").focus();
                        //     next = false;
                        // }
                    }

                    if (next) {
                        if ($("#register-form textarea[name='alamat']").val().length == 0) {
                            notif(0, 'Alamat belum diisi');
                            $("#alamat").focus();
                            next = false;
                        }
                    }

                    if (next) {
                        if ($("#register-form select[name='provinsi']").find(':selected').val() == 0) {
                            notif(0, 'Provinsi harus dipilih');
                            next = false;
                        }
                    }

                    if (next) {
                        if ($("#register-form select[name='kota']").find(':selected').val() == 0) {
                            notif(0, 'Kota/Kab harus dipilih');
                            next = false;
                        }
                    }

                    if (next) {
                        if ($("#register-form select[name='kecamatan']").find(':selected').val() == 0) {
                            notif(0, 'Kecamatan harus dipilih');
                            next = false;
                        }
                    }

                    if (next) {
                        var data = {
                            action: 'create-branch',
                            ui:ui,
                            us:us,
                            nama: $("#nama").val(),
                            specialist: $("#specialist").find(':selected').val(),
                            // email: $("#email").val(),
                            // telepon: $("#telepon").val(),
                            email: '',
                            telepon: '',                            
                            alamat: $("#alamat").val(),
                            provinsi: $("#provinsi").find(':selected').val(),
                            kota: $("#kota").find(':selected').val(),
                            kecamatan: $("#kecamatan").find(':selected').val(),
                            with_stock: $("#with_stock").find(':selected').val(),
                            with_journal: $("#with_journal").find(':selected').val(),
                        };
                        $.ajax({
                            type: "POST",
                            url: "<?= base_url('setup/manage'); ?>",
                            data: data,
                            dataType: 'json',
                            beforeSend: function () {
                                $('#btnRegister').removeClass('btn-primary');
                                $('#btnRegister').html('<i class="fas fa-spinner"></i> Silahkan Tunggu...');
                                $('#btnRegister').prop('disabled', true);
                            },
                            success: function (d) {
                                // var base_url = "<?= base_url(); ?>";
                                if (parseInt(d.status) == 1) {
                                    notif(1, d.message);
                                    window.location.href = d.result.return_url;
                                } else {
                                    notif(0, d.message);
                                    $('#btnRegister').addClass('btn-primary');
                                    $('#btnRegister').html('<i class="fa-hand-point-right"></i> Daftar');
                                    $('#btnRegister').prop('disabled', false);
                                }
                            },
                            error: function (xhr, Status, err) {
                                notif(0, 'Error');
                                $('#btnRegister').addClass('btn-primary');
                                $('#btnRegister').html('<i class="fa-hand-point-right"></i> Daftar');
                                $('#btnRegister').prop('disabled', false);
                            }
                        });
                    }
                }));
            });
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            function notif($type, $msg) {
                if (parseInt($type) === 1) {
                    //Toastr.success($msg);
                    Toast.fire({
                        type: 'success',
                        title: $msg
                    });
                } else if (parseInt($type) === 0) {
                    //Toastr.error($msg);
                    Toast.fire({
                        type: 'error',
                        title: $msg
                    });
                }
            }
        </script>        
    </body>
</html>
<?php 
}else{
    redirect('login');
}
?>