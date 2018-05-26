/*****************************************************
* Author: Mustafa bayati
* Year: 2017
******************************************************
The project
===========================
-- Simple MVC.
===========================

Requirements
===========================
-- PHP >5.2 & MySqli compiler
-- Enabled mod_rewrite in PHP
===========================

Installation
===========================
-- Edit RewriteBase in (mvc/.htaccess) to new folder name or keep it.
===========================


How to use
===========================
-- Add view files in (public/) folder
-- Add your controllers to (vc_app/controllers/)
-- Every class name in new controllers file mean new page, eg: pages.php with (class pages extends Controller) mean http://domain/mvc/pages.
-- By default first page is home_page.php class with index method you can change it in (vc_app/core/app.php).
-- You can add more parameters in any class eg: in pages class you can add more parameters for index function  (public function index($Lang_Value="en", $DataId, $moreids, $target)).
===========================
