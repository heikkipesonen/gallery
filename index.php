<?php
/*




shitty(tm) php image gallery management

heikki pesonen
2014




*/
require_once('environment.php');
error_reporting(E_ALL);
ini_set('display_errors', 'On'); 

session_start();
function __autoload($class){
	$dir = 'class';
	require_once(__DIR__.'/'.$dir.'/'.$class.'.php');
}
require_once('conf.php');

//define('IMAGE_MAXSIZE', Util::get_max_fileupload_size());

$allowedViews = array('photos','gallery');
$data = array('view'=>'login');

foreach ($_REQUEST as $key=> $value){
	if (!is_array($value)){
		$data[$key] = Util::clearXss( $value );
	} else {
		$data[$key] = array();
		foreach ($value as $k => $v){
			$data[$key][$k] = Util::clearXss($v);
		}
	}
}

if (isset($data['view'])){
	$controller = 'controller/'.$data['view'].'Controller.php';	

	if (file_exists($controller)){
		require_once($controller);
	}

	if (isset($data['slug']) && isset($data['gallery']) && isset($data['key'])){
		if (!in_array($data['view'], $allowedViews)){
				$view = 'gallery';		
		} else {
			$view = $data['view'];
		}
	} else if (file_exists('view/'.$data['view'].'.php') && Photo::isLogin()){	
		$view = $data['view'];
	}  else if (Photo::isLogin()) {
		if (!Photo::isAdmin()){
			$view = 'gallerylist';		
		} else {
			$view = 'main';
		}
	} else {
		$view = 'login';
	}
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
	<ul class="nav">
<?php
if (Photo::isAdmin()){
?>
		<a href="/<?php echo BASE_URL;?>/"><li><i class="fa fa-th-large"></i></li></a>
		<a href="/<?php echo BASE_URL;?>/list"><li><i class="fa fa-list"></i></li></a>
		<a href="/<?php echo BASE_URL;?>/orders"><li><i class="fa fa-inbox"></i></li></a>
<?php
}

if (Photo::isLogin()){

?>
		<a href="/<?php echo BASE_URL;?>/logout"><li><i class="fa fa-sign-out"></i></li></a>
<?php } ?>
	</ul>
		<div class="view col-md-12" id="<?php echo $view; ?>">
			<?php
			include('view/'.$view.'.php');
			?>
		</div>
	</div>
</div>
<script type="text/javascript" src="/<?php echo BASE_URL; ?>/js/jquery.js"></script>
<script type="text/javascript" src="/<?php echo BASE_URL; ?>/js/bootstrap.min.js"></script>