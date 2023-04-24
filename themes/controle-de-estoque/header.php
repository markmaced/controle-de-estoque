<?php header("Content-Type: text/html; charset=UTF-8");
/**
 * @package Marcos Macedo
 */
?>
<!DOCTYPE html>

<meta http-equiv="Content-Language" content="pt-br"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<?php wp_head() ?>
<link rel="shortcut icon" href="<?php echo get_template_directory_uri() ?>/assets/images/favicon.png" />

<body <?php body_class() ?>>