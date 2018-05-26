<?php
if((in_array($TargetFrom,$Users_Permissions_Array) || $Get_UserData_Row["users_main"]=="1") && in_array($TargetFrom,$Domains_Programs_Array)){
  if($ViewAction==false){
    if($DataId==""){
      $Get_data_categorys = $Sql_Connection -> Select_Query("data_categorys",
      "data_categorys.data_categorys_id,data_categorys.lang, data_categorys.section, data_categorys.data_categorys_type ,data_categorys.data_categorys_name ,data_categorys.data_categorys_details, data_categorys.data_categorys_attachment, data_categorys.data_categorys_status,
      (SELECT COUNT(data_text.data_id) FROM data_text WHERE data_text.data_categorys = data_categorys.data_categorys_name AND data_text.data_type = data_categorys.data_categorys_type AND data_text.section = data_categorys.section AND data_text.lang = data_categorys.lang) as data_text_count",
      "WHERE data_categorys.lang='".$lang."' AND data_categorys.section='".$SectionsVal."' AND data_categorys.data_categorys_type='".$TargetFrom."' ORDER BY data_categorys.data_categorys_name ASC");
      $Get_data_categorys_Row = $Get_data_categorys -> fetch_array();

      $Get_Status = $Sql_Connection -> Select_Query("data_categorys", "*", "WHERE data_categorys.lang='".$lang."' AND data_categorys.section='".$SectionsVal."' AND data_categorys.data_categorys_type='".$TargetFrom."' AND data_categorys_status=0 LIMIT 1");
      $Get_Status_Row = $Get_Status -> fetch_array();
      if($Get_Status -> num_rows>0){
        $JqueryVars = "ShowError('هنالك عناصر غير مفعلة مضللة باللون الاحمر، يرجى ملئ الحقول المطلوبة للاقسام');";
      }

    }else{
      $Edit_data_categorys = $Sql_Connection -> Select_Query("data_categorys", "*", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND data_categorys_id='".$DataId."' limit 1");
      $Edit_data_categorys_Row = $Edit_data_categorys -> fetch_array();
      $JqueryVars .= 'var actionsids = "'.$Edit_data_categorys_Row["data_categorys_id"].'";';
      $JqueryVars .= 'var actionstype = "'.$Edit_data_categorys_Row["data_categorys_type"].'";';
      $ActiveUploadScript=true;
    }
  }else{
    $Get_data_categorys_name = $Sql_Connection -> Select_Query("data_categorys", "*", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND data_categorys_id='".$DataId."' AND data_categorys_status=1 limit 1");
    $Get_data_categorys_name_Row = $Get_data_categorys_name -> fetch_array();
    if($Get_data_categorys_name->num_rows==0){
      header("Location: ".$site_url."/panel/page404");
      exit;
    }

    if($pagenum<=1){
      $from = 0;
      $to = 20;
    }else{
      $from = ($pagenum-1) * 20;
      $to = ($pagenum * 20);
    }

    $View_Data = $Sql_Connection -> Select_Query("data_text", "*", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND data_type='".$Get_data_categorys_name_Row["data_categorys_type"]."' AND data_categorys='".$Get_data_categorys_name_Row["data_categorys_name"]."' ORDER BY data_id DESC LIMIT ".$from.", ".$to);
    $View_Data_Row = $View_Data -> fetch_array();
    $Get_Data_Total = $Sql_Connection->Select_Query("data_text", "*", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND data_type='".$Get_data_categorys_name_Row["data_categorys_type"]."' AND data_categorys='".$Get_data_categorys_name_Row["data_categorys_name"]."'");

    $Get_important_data = $Sql_Connection -> Select_Query("settings", "*", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND settings_type='important_".$Get_data_categorys_name_Row["data_categorys_type"]."' LIMIT 1");
    $Get_important_data_Row = $Get_important_data->fetch_array();
    $Get_important_data_Array = explode(",",$Get_important_data_Row["settings_option"]);
  }




    switch ($TargetFrom) {
      case "news":
        $TargetString = "الاخبار";
        $title_string = "لوحة التحكم بالاقسام الاخبارية ";
        $total_data_string = "مجموع الاخبار";
        break;
      case "pages":
        $TargetString = "الصفحات";
        $title_string = "لوحة التحكم باقسام الصفحات ";
        $total_data_string = "مجموع الصفحات";
        break;
      case "videos":
        $TargetString = "الفيديو";
        $title_string = "لوحة التحكم باقسام الفيديو ";
        $total_data_string = "مجموع مقاطع الفديو";
        break;
      case "sounds":
        $TargetString = "الصوتيات";
        $title_string = "لوحة التحكم باقسام الصوتيات ";
        $total_data_string = "مجموع المقاطع الصوتية";
        break;
      case "products":
        $TargetString = "المنتجات";
        $title_string = "لوحة التحكم باقسام المنتجات ";
        $total_data_string = "مجموع المنتجات";
        break;
      case "library":
        $TargetString = "المكتبة";
        $title_string = "لوحة التحكم باقسام المكتبة ";
        $total_data_string = "مجموع الكتب";
        break;
      case "cv":
        $TargetString = "السيرة الذاتية";
        $title_string = "لوحة التحكم باقسام السيرة الذاتية ";
        $total_data_string = "مجموع السير الذاتية";
        break;
      default:
        header("Location: ".$site_url."/panel/page404");
        exit;
    }



    $ActiveUploadScript=false;


  $backUrl = $site_url."/panel/cat/".$TargetFrom;

}else{
  header("Location: ".$site_url."/panel/page404");
  exit;
}

?>
