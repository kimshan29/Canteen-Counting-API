<!DOCTYPE html>
<html>

<head>

    <title><?php if ($this->uri->segment('2') == 'detail') {
            echo $data->title;
        } else {
            echo $controller_name;
        } ?> | <?php echo $this->appearance->name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name='robots' content='noindex, nofollow'/>
    <meta name="language" content="indonesia">
    <link rel="apple-touch-icon" href="<?php echo base_url() ?>assets/theme/img/apple-touch-icon.png">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light"
          rel="stylesheet" type="text/css">

    <!-- plugins CSS -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/plugins/animate/animate.min.css">
    <link rel="stylesheet"
          href="<?php echo base_url() ?>assets/theme/plugins/simple-line-icons/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/plugins/owl.carousel/assets/owl.carousel.min.css">
    <link rel="stylesheet"
          href="<?php echo base_url() ?>assets/theme/plugins/owl.carousel/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/plugins/magnific-popup/magnific-popup.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/css/theme.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/css/theme-elements.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/css/theme-blog.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/css/theme-shop.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/theme/css/jquery.fullPage.css"/>
    <script src="<?php echo base_url() ?>assets/theme/plugins/modernizr/modernizr.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/css/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/css/custom.css">


</head>
<body data-spy="scroll" data-target="#navSecondary" data-offset="170">
<div class="body">
    <div role="main" class="main">
        <?php echo $page ?>
    </div>
</div>

<!-- plugins -->
<script src="<?php echo base_url() ?>assets/theme/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/plugins/jquery.appear/jquery.appear.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/plugins/jquery.easing/jquery.easing.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/plugins/jquery-cookie/jquery-cookie.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/plugins/common/common.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/plugins/jquery.validation/jquery.validation.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/plugins/jquery.gmap/jquery.gmap.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/plugins/jquery.lazyload/jquery.lazyload.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/plugins/isotope/jquery.isotope.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/plugins/owl.carousel/owl.carousel.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/plugins/vide/vide.min.js"></script>

<!-- Theme Base, Components and Settings -->

<script type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/scrolloverflow.js"></script>

<script type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/jquery.fullPage.js"></script>

<script src="<?php echo base_url() ?>assets/theme/js/theme.js"></script>

<!-- Theme Initialization Files -->
<script src="<?php echo base_url() ?>assets/theme/js/theme.init.js"></script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="<?php echo base_url() ?>assets/theme/js/custom.js"></script>
</body>
</html>
