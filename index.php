<?php
session_start();
function __autoload($class){
	$dir = 'class';

	if (file_exists($dir.'/'.$class.'.php')){
		require_once($dir.'/'.$class.'.php');
	}
}
require_once('conf.php');

define('IMAGE_MAXSIZE', Util::get_max_fileupload_size());

$allowedViews = ['photos','gallery'];


if (isset($_REQUEST['view']) && isset($_REQUEST['slug']) && isset($_REQUEST['gallery']) && isset($_REQUEST['key'])){
	if (!in_array($_REQUEST['view'], $allowedViews)){
		$view = 'gallery';		
	} else {
		$view = $_REQUEST['view'];
	}
} else if (isset($_REQUEST['view']) && file_exists('view/'.$_REQUEST['view'].'.php') && Photo::isLogin()){	
	$view = $_REQUEST['view'];
} else if (Photo::isLogin()) {
	if (!Photo::isAdmin()){
		$view = 'gallerylist';		
	} else {
		$view = 'main';
	}
} else {
	$view = 'login';
}

?>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="/<?php echo BASE_URL; ?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="/<?php echo BASE_URL; ?>/css/font-awesome.min.css">
	<link rel="stylesheet" href="/<?php echo BASE_URL; ?>/css/style.css">
</head>

<div id="wrapper">
	<div class="page">
		
<?php
if (Photo::isAdmin()){
?>	
	<ul class="nav">
		<a href="/<?php echo BASE_URL;?>/"><li><i class="fa fa-th-large"></i></li></a>
		<a href="/<?php echo BASE_URL;?>/client"><li><i class="fa fa-user"></i></li></a>
		<a href="/<?php echo BASE_URL;?>/list"><li><i class="fa fa-list"></i></li></a>
		<a href="/<?php echo BASE_URL;?>/orders"><li><i class="fa fa-inbox"></i></li></a>

		<a href="/<?php echo BASE_URL;?>/logout"><li><i class="fa fa-sign-out"></i></li></a>
	</ul>
<?php
}
?>
		<div class="view col-md-12 <?php if (!Photo::isAdmin()) echo 'full-width'; ?>" id="<?php echo $view; ?>">
			<?php
			include('view/'.$view.'.php');
			?>
		</div>
	</div>
</div>
<script type="text/javascript" src="/<?php echo BASE_URL; ?>/js/jquery.js"></script>
<script type="text/javascript" src="/<?php echo BASE_URL; ?>/js/bootstrap.min.js"></script>