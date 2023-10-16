<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php $title = isset($title) ? $title : "Welcome"; echo $title; ?></title>

    <meta name="keywords" content="<?php echo $keywords; ?>"/>
    <meta name="description" content="<?php echo $description?>">
    <meta name="author" content="<?php echo $author?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $asset; ?>assets/images/icons/favicon.png">


    <script>
        WebFontConfig = {
            google: {
                families: ['Open+Sans:300,400,600,700,800', 'Poppins:300,400,500,600,700,800', 'Oswald:300,400,500,600,700,800']
            }
        };
        (function(d) {
            var wf = d.createElement('script'),
                s = d.scripts[0];
            wf.src = '<?php echo $asset; ?>assets/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    <!-- Link CSS -->
    <?php include 'link_css.php'; ?>
</head>

<body>
    <div class="page-wrapper">
        <!--
            <div class="top-notice bg-primary text-white">
                <div class="container text-center">
                    <h5 class="d-inline-block">Get Up to <b>40% OFF</b> New-Season Styles</h5>
                    <a href="category.html" class="category">MEN</a>
                    <a href="category.html" class="category ml-2 mr-3">WOMEN</a>
                    <small>* Limited time only.</small>
                    <button title="Close (Esc)" type="button" class="mfp-close">×</button>
                </div>
            </div>
        -->
        <?php include 'header.php'; ?>
        <?php if (!empty($_content)) { $this->load->view($_content); } ?>
        <?php include "footer.php"; ?> 
    </div>
    <?php include 'link_js.php'; ?>
</html>