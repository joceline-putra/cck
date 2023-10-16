<?php
$branch_logo = !empty($branch['branch_logo_login']) ? $branch['branch_logo_login'] : site_url() . 'upload/branch/default_logo.png';

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");

// $return_url = $this->session->userdata('url_before');
$message = !empty($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';
$status = !empty($this->session->flashdata('status')) ? $this->session->flashdata('status') : 0;
$activation = !empty($this->session->flashdata('activation_session')) ? $this->session->flashdata('activation_session') : '';
if ($status == 0) {
    redirect(base_url(), 'refresh');
}

$project = ($_SERVER['SERVER_NAME'] == 'localhost') ? strtoupper(substr($_SERVER['PHP_SELF'], 5, 3)) : 'Admin';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>Password Recovery</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <link href="<?php echo base_url(); ?>assets/core/favicon.png" sizes="16x16 32x32" type="image/png" rel="icon"> 
        <link href="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/css/messenger-theme-flat.css" rel="stylesheet" type="text/css" media="screen" />  
        <link href="<?php echo base_url(); ?>assets/core/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />    
        <link href="<?php echo base_url(); ?>assets/core/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/core/plugins/toastr/toastr.min.css"> 
        <link href="<?php echo base_url(); ?>assets/core/plugins/sweetalert2/sweetalert2.min.css"  rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/webarch/css/custom.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/webarch/css/webarch.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
    </head>
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
    <body class="error-body no-top" style="background:var(--back-login)!important;">
        <div class="container-fluid">
            <div class="row login-container column-seperation">
                <div class="col-md-offset-5 col-md-2 col-xs-12 col-sm-12" style="padding: 30px;background-color: #ffffff;margin-bottom: 20px;/*border-radius:20px;*/border: 4px solid #cacaca!important;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                        <img src="<?php echo $branch_logo; ?>" class="img-responsive" style="padding-top:20px;margin:0 auto;">
                        <h4 style="margin-bottom:0px;">Reset Password Anda</h4>
                        <p><?php echo $message; ?></p>           
                        <form action="#" method="post" class="reset-form validate" id="reset-form" name="reset-form">  
                            <input class="form-control" id="action" name="action" type="hidden" required readonly value="password-update">
                            <input class="form-control" id="activation" name="activation" type="hidden" required readonly value="<?php echo $activation; ?>">                 
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <label class="form-label" style="text-align:left;display:block;">Password</label>
                                    <input class="form-control" id="txtpassword" name="password" type="password" required placeholder="" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="form-label" style="text-align:left;display:block;">Konfirmasi Password</label>
                                    <input class="form-control" id="txtpassword" name="password2" type="password" required placeholder="" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-xs-12 col-sm-12 div-login">
                                    <button id="btnLogin" class="btn pull-right" type="submit" style="background-color:#318fff;color:white;">
                                        <i class="fas fa-lock-open"></i>&nbsp;
                                        Process
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="control-group col-md-12 col-sm-12 col-xs-12" style="text-align: center;padding-bottom:10px;">
                                    <div class="checkbox checkbox check-success">
                                    </div>
                                </div>
                            </div>              
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/messenger-theme-future.js" type="text/javascript"></script>
        <!-- <script src="<?php echo base_url(); ?>assets/core/plugins/jconfirm-3.3.4/dist/jquery-confirm.min.js"></script>		 -->
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/demo/demo.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/js/notifications.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/toastr/toastr.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/js/jquery.redirect.js" type="text/javascript"></script>    
        <script>
            $(document).ready(function () {
                var form = '#reset-form';
                var operator = true;

                $("#reset-form").on('submit', (function (e) {
                    e.preventDefault();
                    var next = true;

                    if ($("#reset-form input[name='password']").val().length == 0) {
                        notif(0, 'Password belum diisi');
                        $("#txtpassword").focus();
                        next = false;
                    }

                    if (next) {
                        if ($("#reset-form input[name='password']").val().length == 0) {
                            notif(0, 'Password belum diisi');
                            $("#txtpassword").focus();
                            next = false;
                        }
                    }

                    if (next) {
                        $.ajax({
                            type: "POST",
                            url: "<?= base_url('login/manage'); ?>",
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
            function getCookie() {
                var hsl = 0;
                var name = "us=";
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++)
                {
                    var c = ca[i].trim();
                    if (c.indexOf(name) == 0)
                        return c.substring(name.length, c.length);
                }
                return hsl;
            }
        </script>
    </body>
</html>