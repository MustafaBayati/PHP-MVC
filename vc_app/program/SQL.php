<?php
require_once ("vc_app/config.php");
require_once ("vc_app/class/SQL_Class.php");
require_once ("vc_app/class/Security_Class.php");
require_once ("vc_app/class/Mobile_Detect.php");


function GetLangArabicValue($Key){
  switch ($Key) {
      case "ar":
          return "اللغة العربية";
          break;
      case "en":
          return "اللغة الانكليزية";
          break;
      case "kr":
          return "اللغة الكوردية";
          break;
  }
}


function GetPicWithSize($Pic, $Size=""){
  include("vc_app/config.php");
  if($Size==""){
    return $User_Domain.$Pic;
  }else{
    $GetPic = explode("/",$Pic);
    return $User_Domain."files/".$GetPic[1]."/".$Size."/".$GetPic[2];
  }
}


function CheckIfInt($Key){
  $Key_ID = intval($Key);
  if(is_int($Key_ID)){
    return $Key_ID;
  }else{
    return "error";
  }
}


if($PageNum!=""){
  $Check_Pagenum = $Security_Class->Check_DB_Security((int)$PageNum);
  if(is_numeric($Check_Pagenum) && $Check_Pagenum>0){
    $PageNum = $Check_Pagenum;
  }else{
    $PageNum = 1;
  }
}


if($Search_Key!=""){
  $Check_SearchKey = $Security_Class->Check_DB_Security($Search_Key);
  if(is_numeric($Check_Pagenum) && $Check_Pagenum>0){
    $PageNum = $Check_Pagenum;
  }else{
    $PageNum = 1;
  }
}


if($DataId!=""){
  $Check_DataID = $Security_Class->Check_DB_Security((int)$DataId);
  if(is_numeric($Check_DataID) && $Check_DataID!=0){
    $DataId = $Check_DataID;
  }else{
    //header("Location: ".$site_url."/pages/page404");
  }
}



$Get_Settings = $Sql_Connection -> Select_Query("settings", "*", "WHERE lang='".$Lang_Value."' AND section='".$Section_Value."'");
$Get_Settings_Row = $Get_Settings->fetch_array();
do {
  $All_Settings[$Get_Settings_Row["settings_type"]] = $Get_Settings_Row["settings_option"];
} while ($Get_Settings_Row = $Get_Settings -> fetch_array());


$GetStylesData = explode("{|:::|}",$All_Settings["Styles"]);
foreach ($GetStylesData as $value) {
  $SaveCode = "";
  $SaveCode = explode("{|::|}",$value);
  $GetStyles[$SaveCode[0]] = $SaveCode[1];
}


$GetSiteSettings = explode("{|:::|}",$All_Settings["Site_Settings"]);
foreach ($GetSiteSettings as $value) {
  $SaveCode = "";
  $SaveCode = explode("{|::|}",$value);
  $GetSettings[$SaveCode[0]] = $SaveCode[1];
}






// Please Put last anything here
//$Get_Last_News = $Sql_Connection -> Select_Query("data_text", "*", "WHERE data_type='news' order by data_id desc LIMIT 10");
//$Get_Last_News_Row = $Get_Last_News->fetch_array();

// you can use this to check mobile browser for Styles
//if($detect->isMobile() != true){ echo "its not mobile"; }

// to clean text-post-get coming from end user befor do anything
//$Search_Key = $Security_Class->Check_DB_Security("any text");




$Header_Value = $GetStyles["Header"];
$Footer_Value = $GetStyles["Footer"];

$Title_Value       = $GetSettings["SiteTitle"];
$Description_Value = $GetSettings["SiteDescription"];
$Keywords_Value    = $GetSettings["SiteKeywords"];
$Facebook_Value    = $GetSettings["SiteFacebook"];
$Youtube_Value     = $GetSettings["SiteYoutube"];
$Soundcloud_Value  = $GetSettings["SiteSoundcloud"];
$Twitter_Value     = $GetSettings["SiteTwitter"];
$Google_Value      = $GetSettings["SiteGoogle"];
$Instagram_Value   = $GetSettings["SiteInstagram"];

$important_news_Value     = $All_Settings["important_news"];
$important_pages_Value    = $All_Settings["important_pages"];
$important_sounds_Value   = $All_Settings["important_sounds"];
$important_videos_Value   = $All_Settings["important_videos"];
$important_products_Value = $All_Settings["important_products"];
$important_cv_Value       = $All_Settings["important_cv"];
?>
