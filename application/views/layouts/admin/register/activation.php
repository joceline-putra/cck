<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
$branch_logo = !empty($branch['branch_logo_login']) ? $branch['branch_logo_login'] : site_url() . 'upload/branch/default_logo.png';
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
        <link href="<?php echo base_url(); ?>assets/core/plugins/toastr/toastr.min.css"> 
        <link href="<?php echo base_url(); ?>assets/core/plugins/sweetalert2/sweetalert2.min.css"  rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/webarch/css/webarch.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
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
            margin-top:10%!important;
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
            font-weight: 600;
        }
        .div-login{
            margin-bottom:6px;
        }
        #btnLogin{
            width: 100%;
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
                <div class="col-md-offset-5 col-md-3 col-xs-12 col-sm-12" style="padding: 30px;background-color: #ffffff;margin-bottom: 20px; /*border-radius:20px;*/ border: 4px solid #cacaca!important;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;padding:20px 0px">
                    <!-- <img src="<?php echo $branch_logo; ?>" class="img-responsive" style="padding-top:20px;margin:0 auto;"> -->
                        <i class="fas fa-clipboard-check fa-4x" style="color:#5ac736;"></i>
                        <h4 style="margin-bottom:0px;">Terima Kasih telah Verifikasi akun anda</h4>
                        <p style="padding-top: 20px;">Halo <b><?php echo $fullname; ?></b>,<br>Sekarang anda dapat masuk menggunakan akun anda</p>           
                        <form action="#" method="post" class="login-form validate" id="register-form" name="register-form">

                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="margin-top:20px;">		  	
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 col-sm-12 div-login">
                                        <button id="btnBack" class="btn pull-right" type="button" style="width: 100%;background-color:#318fff;color:white;">
                                            <i class="fas fa-sign-in-alt"></i>&nbsp;
                                            Masuk
                                        </button>
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
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/demo/demo.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/core/plugins/notifications.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/plugins/toastr/toastr.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/plugins/sweetalert2/dist/sweetalert2.min.js"></script>
        <script>
            $(document).ready(function () {
                $(document).on("click", "#btnBack", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var url = "<?php echo base_url('login'); ?>";
                    window.open(url, '_self');
                });
            });
        </script>        
    </body>
</html>