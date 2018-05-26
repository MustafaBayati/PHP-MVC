<?php
if(in_array("settings",$Users_Permissions_Array) || $Get_UserData_Row["users_main"]=="1"){
  
  if($SettingsSections!=""){
    $Get_sections = $Sql_Connection -> Select_Query("settings", "*", "WHERE lang='".$lang."' AND section='".$SettingsSections."' AND settings_type='Site_Settings' limit 1");
    $Get_sections_Row = $Get_sections -> fetch_array();
    if($Get_sections -> num_rows>0){
      $Get_sections_Befor_Array = explode("{|:::|}",$Get_sections_Row["settings_option"]);
      foreach ($Get_sections_Befor_Array as $key => $value) {
        if($value!=""){
          $Get_sections_Get_Array = explode("{|::|}",$value);
          if(count($Get_sections_Get_Array)>0){
            $Get_Settings_Sections_Array[$Get_sections_Get_Array[0]] = $Get_sections_Get_Array[1];
          }
        }
      }
      $JqueryVars .= 'var actionssettingsids = "'.$Get_sections_Row["settings_id"].'";';
    }else{
      $Sql_Connection -> Insert_Query( "settings",
                          array(
                            "lang"=>$lang,
                            "section"=>$SettingsSections,
                            "settings_type"=>"Site_Settings",
                            "settings_option"=>""),
                            "",
                            1,"");
      $JqueryVars .= 'var actionssettingsids = "'.$Sql_Connection -> Get_Insert_Update_id().'";';
    }

    $Get_Settings_Style = $Sql_Connection -> Select_Query("settings", "*", "WHERE lang='".$lang."' AND section='".$SettingsSections."' AND settings_type='Styles' limit 1");
    $Get_Settings_Style_Row = $Get_Settings_Style -> fetch_array();
    if($Get_Settings_Style -> num_rows>0){
      $Get_Settings_Style_Befor_Array = explode("{|:::|}",$Get_Settings_Style_Row["settings_option"]);
      foreach ($Get_Settings_Style_Befor_Array as $key => $value) {
        if($value!=""){
          $Get_Settings_Style_Get_Array = explode("{|::|}",$value);
          $Get_Settings_Style_Array[$Get_Settings_Style_Get_Array[0]] = $Get_Settings_Style_Get_Array[1];
        }
      }
      $JqueryVars .= 'var actionsstyleids = "'.$Get_Settings_Style_Row["settings_id"].'";';
    }else{
      $Sql_Connection -> Insert_Query( "settings",
                          array(
                            "lang"=>$lang,
                            "section"=>$SettingsSections,
                            "settings_type"=>"Styles",
                            "settings_option"=>""),
                            "",
                            1,"");
      $JqueryVars .= 'var actionsstyleids = "'.$Sql_Connection -> Get_Insert_Update_id().'";';
    }

  }

  $backUrl = $site_url."/panel/settings";

}else{
  header("Location: ".$site_url."/panel/page404");
  exit;
}
?>
