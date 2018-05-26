<?
// Home page class, as default home page class is the first page call in wesite
// you can change the main function by cahnge (protected $controller = 'home_page';) in core/app.php
class home_page extends Controller {
		public function index($Lang_Value="en"){
			require_once("vc_app/config.php");
			require_once 'public/php_'.$Lang_Value.'/'.$Lang_Value.'_header.php';
			require_once 'public/php_'.$Lang_Value.'/'.$Lang_Value.'_index.php';
			require_once 'public/php_'.$Lang_Value.'/'.$Lang_Value.'_footer.php';
		}

  }
?>
