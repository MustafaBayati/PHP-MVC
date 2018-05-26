<?php
class pages extends Controller {

		// $DataId to pass id parameters to code, you can add more and call it
		public function index($Lang_Value="en", $DataId){
			require_once("vc_app/config.php");
			if($DataId==""){
				header('Location: '.$site_url."/pages/page404");
			}
			require_once 'public/php_'.$Lang_Value.'/'.$Lang_Value.'_header.php';
			require_once 'public/php_'.$Lang_Value.'/'.$Lang_Value.'_pages.php';
			require_once 'public/php_'.$Lang_Value.'/'.$Lang_Value.'_footer.php';
		}


		public function page404(){
			$CurrentPage = "page404";
			require_once ("vc_app/config.php");

			require_once 'public/php_en/en_header.php';
			$this->model("page404");
			require_once 'public/php_en/en_footer.php';
		}

}
