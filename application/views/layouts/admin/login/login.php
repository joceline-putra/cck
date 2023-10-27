<?php
$branch_logo = !empty($branch['branch_logo_login']) ? $branch['branch_logo_login'] : site_url() . 'upload/branch/default_logo.png';
// var_dump($branch_logo);die;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");

$return_url = $this->session->userdata('url_before');
if (!empty($return_url)) {
    $set_url = $return_url;
} else {
    $set_url = '';
}

$login_back = 0;
if (!empty($_COOKIE[site_url()])) {
    $login_back = 1;
} else {
    $login_back = 0;
}
// var_dump($login_back);die;

if (!empty($return_url)) {
    $teks_1 = 'Akses ke dokumen';
    $teks_2 = 'Verifikasikan bahwa ini adalah anda';
} else {
    if ($login_back == 0) {
        $teks_1 = 'Welcome';
        $teks_2 = 'Please use your account';
    } else {
        $teks_1 = 'Welcome back';
        $teks_2 = 'Verify your password';
    }
}

// $return_url = $this->session->userdata('url_before');
$message = !empty($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';
$status = !empty($this->session->flashdata('status')) ? $this->session->flashdata('status') : 0;
$project = ($_SERVER['SERVER_NAME'] == 'localhost') ? strtoupper(substr($_SERVER['PHP_SELF'], 5, 3)) : 'Admin';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title><?php echo $project; ?> Masuk</title>
        <!-- <link rel="manifest" href="<?php #echo site_url();?>manifest.json"> -->
    
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <link href="<?php echo base_url(); ?>assets/core/favicon.png" sizes="16x16 32x32" type="image/png" rel="icon"> 
        <link href="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/css/messenger-theme-flat.css" rel="stylesheet" type="text/css" media="screen" />  

        <!-- BEGIN PLUGIN CSS -->
        <link href="<?php echo base_url(); ?>assets/core/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />    
        <link href="<?php echo base_url(); ?>assets/core/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!--Sweet Alert-->
        <link href="<?php echo base_url(); ?>assets/core/plugins/toastr/toastr.min.css"> 
        <link href="<?php echo base_url(); ?>assets/core/plugins/sweetalert2/sweetalert2.min.css"  rel="stylesheet" type="text/css"/>
        <!-- END PLUGIN CSS -->
        <!-- BEGIN CORE CSS FRAMEWORK -->
        <link href="<?php echo base_url(); ?>assets/core/css/custom.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/core/css/webarch.css" rel="stylesheet" type="text/css" />
        <!-- END CORE CSS FRAMEWORK -->
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
    </head>
    <!-- END HEAD -->
    <style>
        /*@font-family: 'Open Sans', sans-serif!important;*/
        .form-control{
            height: 34px!important;
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
    <!-- BEGIN BODY -->
    <!-- <body class="error-body no-top" style="background: darkcyan url('assets/webarch/img/pattern.jpg') repeat center center fixed;"> -->
    <body class="error-body no-top" style="background:var(--back-login)!important;">
        <div class="container-fluid">
            <div class="row login-container column-seperation" style="">
                <div class="col-md-offset-5 col-md-2 col-xs-12 col-sm-12" 
                     style="padding: 30px;
                     background-color: #ffffff;
                     margin-bottom: 20px;
                     /*border-radius:20px;*/
                     border: 4px solid #cacaca!important;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                        <img src="<?php echo $branch_logo; ?>" class="img-responsive" style="padding-top:20px;margin:0 auto;">
                        <h4 style="margin-bottom:0px;"><?php echo $teks_1; ?></h4>
                        <p><?php echo $teks_2; ?></p>   
                        <?php
                            if(strlen($message) > 1){ 
                                echo '<p style="color:green;">'.$message.'</p>'; 
                            }
                        ?>           
                        <form action="#" method="post" class="login-form validate" id="login-form" name="login-form">
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <!-- <label class="form-label hide">Url</label> -->
                                    <input class="form-control" id="url" name="url" type="hidden" value="<?php echo $return_url; ?>">
                                </div>
                            </div>     
                            <?php
                            // var_dump($_COOKIE['us']);die;
                            if ($login_back === 1) {
                                ?>          
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <span class="fas fa-user" style="font-size: 28px;color:#f89c1b;"></span>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label class="form-label" style="font-size: 16px;"><b><?php echo ucwords($_COOKIE[site_url()]); ?></b></label>
                                        </div>              
                                        <input id="txtusername" name="username" type="hidden" required value="<?php echo $_COOKIE[site_url()]; ?>" class="form-control" placeholder="Usernames"  
                                               style="">
                                    </div>
                                </div>          
                                <?php
                            } else {
                                ?>                          
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <!-- <label class="form-label">Username</label> -->
                                        <input class="form-control" id="txtusername" name="username" type="text" required placeholder="Username" value="">
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="form-group col-md-12">
                                      <!-- <label class="form-label">Password</label> <span class="help"></span> -->
                                    <input class="form-control" id="txtpassword" name="password" type="password" required placeholder="Password" value="">
                                </div>
                            </div>
                            <div class="hide row">
                                <div class="control-group col-md-10">
                                    <div class="checkbox checkbox check-success">
                                        <!-- <a class="" href="#" onclick="modalReset();">Tidak Bisa Masuk?</a>&nbsp;&nbsp; -->
                                        <input id="reminder" name="reminder" type="checkbox" value="check">
                                        <label for="reminder">Selalu Ingat Saya</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-xs-12 col-sm-12 div-login">
                                    <button id="btnLogin" class="btn pull-right" type="submit" style="background-color:#318fff;color:white;">
                                        <i class="fas fa-lock-open"></i>&nbsp;
                                        Masuk
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="control-group col-md-12 col-sm-12 col-xs-12" style="text-align: center;padding-bottom:10px;">
                                    <div class="checkbox checkbox check-success">
                                        <?php if ($login_back == 1) {
                                            ?>
                                            <a id="btn-change-account" href="#">Ganti Akun</a>&nbsp;&nbsp;
                                            <a id="btn-forgot-password" href="#">Lupa Password</a>&nbsp;&nbsp;                  
                                            <!-- <a id="btn-register" href="#">Daftar</a>&nbsp;&nbsp; -->
                                        <?php } else {
                                            ?>
                                            <a id="btn-forgot-password" href="#">Lupa Password</a>&nbsp;&nbsp;
                                            <!-- <a id="btn-register" href="#">Daftar</a>&nbsp;&nbsp; -->
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>              
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTAINER -->
        <div id="modal-reset" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="myModalLabel" class="semi-bold">Reset Password</h4>
                        <p class="no-margin">Mendaftar disini untuk login ke system :</p>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="active">
                                        <a href="#tab11" role="tab" data-toggle="tab" aria-expanded="true">Reset</a>
                                    </li>
                                    <li class="">
                                        <a href="#tab22" role="tab" data-toggle="tab" aria-expanded="false">Trouble</a>
                                    </li>
                                </ul>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="#grid-config" data-toggle="modal" class="config"></a>
                                    <a href="javascript:;" class="reload"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab11">
                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">     
                                            <form id="form-reset" action="#">
                                                <div class="col-md-12">
                                                    <h5>Permintaan Reset Password</h5>
                                                    <div class="row form-row">
                                                        <div class="col-md-12">
                                                            <input name="resetEmail" type="text" class="form-control" placeholder="Address">
                                                        </div>
                                                    </div>
                                                    <div class="row small-text">
                                                        <p class="col-md-12">
                                                            Note: Masukkan email terdaftar jika anda lupa dengan kata sandi, password akan di kirim ke email anda.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="form-actions">
                                                    <div class="pull-left">
                                                        <div class="checkbox checkbox check-success   ">
                                                            <input type="checkbox" value="1" id="chkTerms">
                                                            <label for="chkTerms">Saya setuju dengan reset password ini. </label>
                                                        </div>
                                                    </div>
                                                    <div class="pull-right">
                                                        <button class="btn btn-danger btn-cons" type="button" onclick="resetPassword();"><i class="icon-ok"></i> Reset </button>
                                                    </div>
                                                </div>


                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab22">
                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">     

                                        </div>  
                                    </div>
                                </div> 
                            </div>
                        </div>  
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>


        <!-- BEGIN JS DEPENDECENCIES-->
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
        <!-- END CORE JS DEPENDECENCIES-->

        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/messenger-theme-future.js" type="text/javascript"></script>

        <!-- END -->
        <!-- BEGIN PAGE LEVEL JS -->
        <!-- <script src="<?php echo base_url(); ?>assets/core/plugins/jconfirm-3.3.4/dist/jquery-confirm.min.js"></script>		 -->
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/demo/demo.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/notifications.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/toastr/toastr.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/sweetalert2/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery.redirect.js" type="text/javascript"></script>    

        <script>
            $(document).ready(function () {
                var form = '#login-form';
                var operator = true;
                // $("#txtusername").focus();
                var login_back = parseInt("<?php echo $login_back; ?>");
                if (login_back === 0) {
                    $("#txtusername").focus();
                    // console.log('User please');
                } else {
                    $("#txtpassword").focus();
                    // console.log('Password please');    
                }

                $("#login-form").on('submit', (function (e) {
                    e.preventDefault();
                    var next = true;

                    if ($("#login-form input[name='username']").val().length == 0) {
                        notif(0, 'Username belum diisi');
                        $("#txtusername").focus();
                        next = false;
                    }

                    if (next) {
                        if ($("#login-form input[name='password']").val().length == 0) {
                            notif(0, 'Password belum diisi');
                            $("#txtpassword").focus();
                            next = false;
                        }
                    }

                    if (next) {
                        $.ajax({
                            type: "POST",
                            url: "<?= base_url('login/authentication'); ?>",
                            data: $(form).serialize(),
                            dataType: 'json',
                            beforeSend: function () {
                                $('#btnLogin').removeClass('btn-primary');
                                $('#btnLogin').html('<i class="fas fa-spinner"></i> Please wait...');
                                $('#btnLogin').prop('disabled', true);
                            },
                            success: function (d) {
                                var url_before = $("#url").val();
                                var base_url = "<?= base_url(); ?>";
                                if (parseInt(d.status) == 1) {
                                    notif(1, d.message);
                                    window.location.href = d.result.return_url;
                                } else if (parseInt(d.status) == 2) {
                                    notif(1, d.message);
                                    setTimeout(function() {
                                        window.location.href = d.result.return_url;
                                    }, 1000);
                                } else {
                                    notif(0, d.message);
                                    $('#btnLogin').addClass('btn-primary');
                                    $("#login-form input[name='password']").val('');
                                    $('#btnLogin').html('<i class="fas fa-lock-open"></i> Enter');
                                    $('#btnLogin').prop('disabled', false);
                                }
                            },
                            error: function (xhr, Status, err) {
                                notif(0, 'Error');
                                $('#btnLogin').addClass('btn-primary');
                                $("#login-form input[name='password']").val('');
                                $('#btnLogin').html('<i class="fas fa-lock-open"></i> Enter');
                                $('#btnLogin').prop('disabled', false);
                            }
                        });
                    }
                }));
                function autoLogin(operator) {
                    if (operator == true) {
                        // notif(1,'Auto Login Mode');        
                        // $("#txtusername").val('demo');
                        // $("#txtpassword").val('demo');  
                        // document.getElementById("btnLogin").click();
                    }
                }
                autoLogin(operator);
                $(document).on("click", "#btn-change-account", function (e) {
                    var set_url = "<?php echo site_url(); ?>" + "login/remove_cookie";
                    $.redirect(set_url, null, "POST", "_self");
                });
                $(document).on("click", "#btn-forgot-password", function (e) {
                    var set_url = "<?php echo site_url(); ?>" + "password";
                    $.redirect(set_url, null, "POST", "_self");
                });
                $(document).on("click", "#btn-register", function (e) {
                    var set_url = "<?php echo site_url(); ?>" + "register";
                    $.redirect(set_url, null, "POST", "_self");
                });
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
            function loader($stat) {
                if ($stat == 1) {
                    swal({
                        title: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
                        html: '<span style="font-size: 14px;">Loading...</span>',
                        width: '20%',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                } else if ($stat == 0) {
                    swal.close();
                }
            }
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
            function notifSuccess(msg) {
                Messenger().post({
                    message: msg,
                    type: 'success',
                    showCloseButton: true
                });
            }
            function notifError(msg) {
                Messenger().post({
                    message: msg,
                    type: 'error',
                    showCloseButton: true
                });
            }
            function getCookie() {
                var hsl = 0;
                var name = "us=";
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i].trim();
                    if (c.indexOf(name) == 0)
                        return c.substring(name.length, c.length);
                }
                return hsl;
            }
            function reminder() {
                var getcookie = getCookie();
                if (getcookie !== 0) {
                    login();
                }
            }
            /*
                function removeCookie(){
                    var url = "<?= base_url('Login/remove_cookie'); ?>";
                    var data = {
                    action: 'remove_cookie'    
                    };
                    $.ajax({
                    type: "POST",     
                    url: url,
                    data: data,
                    dataType: 'json',
                    cache: 'false',    
                    beforeSend:function(){},
                    success:function(d){
                    window.location.href = '<?= base_url(); ?>';
                    },
                    error:function(xhr, Status, err){
                    notifError(err);
                    }
                    });
                }      
                function registerUser(){
                
                    $("#modal-signup").modal('toggle');
                }  
                function forgotPasswordUser(){
                
                    $("#modal-reset").modal('toggle');
                }
            */
        </script>
	<script>

		// UpUp.start({ 'content-url' : 'https://app.aspri.cloud/' });
        
        /* 
        var BASE_URL = '<?= base_url() ?>';
        document.addEventListener('DOMContentLoaded', init, false);
        function init() {
            if ('serviceWorker' in navigator && navigator.onLine) {
                navigator.serviceWorker.register( BASE_URL + 'manifest_sw.js')
                .then((reg) => {
                    console.log('ServiceWorker: Registered'); console.log(reg);                    
                }, (err) => {
                    console.error('ServiceWorker: Failed'); console.log(err);
                });
            }
        }
        */
	</script>            
    </body>
</html>