<?


function deletefiles($data, $authorization, $User_Domain){
  @$ch = curl_init();
  @curl_setopt($ch, CURLOPT_URL,"http://".$User_Domain."/things/deletefilesbyauthorization");
  @curl_setopt($ch, CURLOPT_POST, 1);
  @curl_setopt($ch, CURLOPT_POSTFIELDS,$data."&authorization=".$authorization);
  @curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
  @curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1);
  @curl_exec ($ch);
  @curl_close ($ch);
}

if($_SERVER['REQUEST_METHOD'] == "POST" && $_SERVER['SERVER_NAME']==$dyno_domain_name && $User_ID!="")
{

  if($TheAction=="Delete_Users_Pic"){
    $CheckTabel = $Sql_Connection_User -> Select_Query($TargetType, "*", "WHERE ".$TargetType."_id='".(int)$Sql_Connection_User->DB_Connection->real_escape_string($_POST["actionsID"])."' AND domain='".$User_Domain."' LIMIT 1");
    $CheckTabel_Row = $CheckTabel->fetch_array();
    if($CheckTabel->num_rows!=0){
      $Pic_Name = $CheckTabel_Row[$TargetType."_photo"];
      @unlink("public/".$Pic_Name);
      if(count($GetImageSize)!=0 || $GetImageSize==false){
        $GetOthers = explode("/",$Pic_Name);
        @unlink("public/".$GetOthers[0]."/".$GetOthers[1]."/".$GetImageSize[1]."/".$GetOthers[2]);
        @unlink("public/".$GetOthers[0]."/".$GetOthers[1]."/".$GetImageSize[2]."/".$GetOthers[2]);
      }
      $Sql_Connection_User -> Update_Query( $TargetType,
                          array(
                          $TargetType."_photo"=>""),
                          "WHERE ".$TargetType."_id=".(int)$Sql_Connection_User->DB_Connection->real_escape_string($_POST["actionsID"]),
                          1,"");
                          echo "OK";
    }else{ header("Location: ".$site_url."/pages/page404"); exit; }
  }






  if($TheAction=="deleteusers"){
    $DeleteitArray = explode(",",$Sql_Connection_User -> DB_Connection -> real_escape_string($_POST["actionsID"]));
    $Check_Main_User = $Sql_Connection_User -> Select_Query("users", "*", "WHERE domain='".$User_Domain."' AND users_main=1 limit 1");
    $Check_Main_User_Row = $Check_Main_User -> fetch_array();
    foreach ($DeleteitArray as  $value) {
      if($Check_Main_User_Row["users_id"]!=$value){
        $AllIds[] = (int)$value;
      }else{
        $ThereIsMain = "ThereIsMain:".$Check_Main_User_Row["users_id"];
      }
    }
    if(count($AllIds)>0){
      @$Sql_Connection_User -> Delete_Query( $TargetType," ".$TargetType."_id IN (".implode(",",$AllIds).")");
    }
    if($ThereIsMain==""){ echo "OK"; }else{ echo $ThereIsMain; }
  }






  if($TheAction=="navigation_toggler"){
    if($navigation_small==""){ $New_navigation_small = "navigation_small"; }else{$New_navigation_small="";}
      $Sql_Connection_User -> Update_Query( "users",
                          array(
                            "users_optional"=>$New_navigation_small),
                            "WHERE users_id=".(int)$User_ID,
                            1,"");
                            echo "OK";
  }






  if($TheAction=="save_user"){
    if(checkvalues($_POST["users_section"],"text")=="error"){ $error[] = "users_section"; }
    if(checkvalues($_POST["users_type"],"text")=="error"){ $error[] = "users_type"; }
    if(checkvalues($_POST["users_fullname"],"text")=="error"){ $error[] = "users_fullname"; }
    if(checkvalues($_POST["users_email"],"email")=="error"){ $error[] = "users_email"; }
    if(checkvalues($_POST["users_phone"],"phone")=="error"){ $error[] = "users_phone"; }
    if(checkvalues($_POST["users_title"],"check")=="error"){ $error[] = "users_title"; }
    if(checkvalues($_POST["users_name"],"check")=="error"){ $error[] = "users_name"; }
    if(checkvalues($_POST["users_password"],"check")=="error"){ $error[] = "users_password"; }
    if(checkvalues($_POST["users_details"],"check")=="error"){ $error[] = "users_details"; }


    $Check_Old_Users_Name = $Sql_Connection_User -> Select_Query("users", "*", "WHERE domain='".$User_Domain."' AND users_id=".(int)$_POST["users_id"]." limit 1");
    $Check_Old_Users_Name_Row = $Check_Old_Users_Name -> fetch_array();

    $Check_Users = $Sql_Connection_User -> Select_Query("users", "*", "WHERE domain='".$User_Domain."' AND users_name='".$_POST["users_name"]."' limit 1");
    $Check_Users_Row = $Check_Users -> fetch_array();
    if($Check_Users->num_rows==1){
      if($Check_Users_Row["users_id"]!=(int)$_POST["users_id"]){
        $error[] = "thereisuser";
      }
    }
    if(count($error)==0){
      if($Check_Old_Users_Name_Row["users_main"]=="1"){
        $users_permissions = $Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_permissions"]);
      }else{
        $users_permissions = $Check_Old_Users_Name_Row["users_permissions"];
      }
      if($_POST["users_password"]!=""){
          $Sql_Connection_User -> Update_Query( "users",
                                  array(
                                    "section"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_section"]),
                                    "users_type"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_type"]),
                                    "users_fullname"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_fullname"]),
                                    "users_email"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_email"]),
                                    "users_phone"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_phone"]),
                                    "users_title"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_title"]),
                                    "users_name"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_name"]),
                                    "users_password"=>sha1($Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_password"])),
                                    "users_permissions"=>$users_permissions,
                                    "users_details"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_details"]),
                                    "users_status"=>"1"),
                                    "WHERE domain='".$User_Domain."' AND users_id=".(int)$_POST["users_id"],
                                    1,"");
        }else{
          $Sql_Connection_User -> Update_Query( "users",
                                  array(
                                    "section"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_section"]),
                                    "users_type"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_type"]),
                                    "users_fullname"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_fullname"]),
                                    "users_email"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_email"]),
                                    "users_phone"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_phone"]),
                                    "users_title"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_title"]),
                                    "users_name"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_name"]),
                                    "users_permissions"=>$users_permissions,
                                    "users_details"=>$Sql_Connection_User->DB_Connection->real_escape_string($_POST["users_details"]),
                                    "users_status"=>"1"),
                                    "WHERE domain='".$User_Domain."' AND users_id=".(int)$_POST["users_id"],
                                    1,"");
        }
        $Sql_Connection_User -> Update_Query( "logs_data",
                            array(
                              "users_name"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["users_name"])),
                              "WHERE users_name=".(int)$Sql_Connection->DB_Connection->real_escape_string($Check_Old_Users_Name_Row["users_name"]),
                              0,"");
        $Sql_Connection_User -> save_log("update","","users");
        echo "OK";
    }else{
      echo implode(",",$error);
    }
  }






  if($TheAction=="Delete_Data_Pic"){
    $CheckTabel = $Sql_Connection -> Select_Query("data_text", "*", "WHERE data_optional LIKE '%".$Pic_Name."%' OR data_attachment LIKE '%".$Pic_Name."%' LIMIT 2");
    $CheckTabel_Row = $CheckTabel->fetch_array();

    $Get_Folder_Name = $Sql_Connection -> Select_Query("settings", "*", "WHERE settings_type='uploadfolder' limit 1");
    $Get_Folder_Name_Row = $Get_Folder_Name->fetch_array();
    $authorization = sha1(date("h d:i").date("Y-i/h").$Get_Folder_Name_Row["settings_option"].date("m/i-d"));

    if($CheckTabel->num_rows!=0){
      if($CheckTabel->num_rows==1){
        $error = false;
        if($CheckTabel_Row["data_attachment"]!=""){
          $OldFiles = explode("{|}",$CheckTabel_Row["data_attachment"]);
          foreach ($OldFiles as $value) {
            if($value==$Pic_Name){
              $CounFiles[] = $value;
            }
          }
        }
        if(count($CounFiles)>1){
          $error = true;
        }
        if($error==false){
          @deletefiles("filename=".$Pic_Name, $authorization, $User_Domain);
          if(count($GetImageSize)!=0 || $GetImageSize==false){
            $GetOthers = explode("/",$Pic_Name);
            @deletefiles("filename=".$GetOthers[0]."/".$GetOthers[1]."/".$GetImageSize[1]."/".$GetOthers[2], $authorization, $User_Domain);
            @deletefiles("filename=".$GetOthers[0]."/".$GetOthers[1]."/".$GetImageSize[2]."/".$GetOthers[2], $authorization, $User_Domain);
          }
        }
      }
      if($TargetAction=="main-pic"){
        @$Sql_Connection -> Update_Query( "data_text",
                              array(
                              "data_optional"=>""),
                              "WHERE data_id=".$ActionsID,
                              1,"");
                              echo "OK";
      }
      elseif ($TargetAction=="one"){
        @$Sql_Connection -> Update_Query( "data_text",
                              array(
                              "data_attachment"=>""),
                              "WHERE data_id=".$ActionsID,
                              1,"");
                              echo "OK";
      }else{
          if($CheckTabel_Row["data_attachment"]!=""){
            $OldFiles = explode("{|}",$CheckTabel_Row["data_attachment"]);
            foreach ($OldFiles as $key=>$value) {
              if($value==$Pic_Name){
                $filekey = $key;
                break;
              }
            }
            unset($OldFiles[$filekey]);
            $NewFiles = $OldFiles;
            if(count($NewFiles)!=0){ $SaveNewFiles = implode("{|}",$NewFiles); }else{ $SaveNewFiles = ""; }
              @$Sql_Connection -> Update_Query( "data_text",
                                    array(
                                    "data_attachment"=>$SaveNewFiles),
                                    "WHERE data_id=".$ActionsID,
                                    1,"");
                                    echo "OK";
          }
      }
    }else{ header("Location: ".$site_url."/pages/page404"); exit; }
  }






  if($TheAction=="save_cat"){
    if(checkvalues($_POST["cat_name"],"text")=="error"){ $error[] = "cat_name"; }
    if(checkvalues($_POST["cat_details"],"text")=="error"){ $error[] = "cat_details"; }

    $Check_Cat = $Sql_Connection -> Select_Query("data_categorys", "*", "WHERE data_categorys_name='".$Sql_Connection->DB_Connection->real_escape_string($_POST["cat_name"])."' AND data_categorys_type='".$TargetType."' limit 1");
    $Check_Cat_Row = $Check_Cat -> fetch_array();
    if($Check_Cat->num_rows==1){
      if($Check_Cat_Row["data_categorys_id"]!=(int)$_POST["cat_id"]){
        $error[] = "thereiscat";
      }
    }
    if(count($error)==0){
      $Get_Old_Cat = $Sql_Connection -> Select_Query("data_categorys", "*", "WHERE data_categorys_id=".(int)$_POST["cat_id"]." LIMIT 1");
      $Get_Old_Cat_Row = $Get_Old_Cat -> fetch_array();
      if($TargetFrom!="balances_codes"){
        $Change_dataTextCatName = $Sql_Connection -> Select_Query("data_text", "*", "WHERE data_categorys='".$Get_Old_Cat_Row["data_categorys_name"]."' LIMIT 1");
        $Change_dataTextCatName_Row = $Change_dataTextCatName -> fetch_array();

        $Sql_Connection -> Update_Query( "data_categorys",
                            array(
                              "data_categorys_name"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["cat_name"]),
                              "data_categorys_details"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["cat_details"]),
                              "data_categorys_status"=>"1"),
                              "WHERE data_categorys_id=".(int)$_POST["cat_id"],
                              1,"");

        if($Change_dataTextCatName->num_rows!=0){
          $Sql_Connection -> Update_Query( "data_text",
                              array(
                                "data_categorys"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["cat_name"])),
                                "WHERE data_type='".$Change_dataTextCatName_Row["data_type"]."' AND data_categorys='".$Change_dataTextCatName_Row["data_categorys"]."'",
                                1,"");
        }

      }else{

        $Change_dataTextCatName = $Sql_Connection -> Select_Query("balances_codes", "*", "WHERE balances_codes_categorys='".$Get_Old_Cat_Row["data_categorys_name"]."' LIMIT 1");
        $Change_dataTextCatName_Row = $Change_dataTextCatName -> fetch_array();

        $Sql_Connection -> Update_Query( "data_categorys",
                            array(
                              "data_categorys_name"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["cat_name"]),
                              "data_categorys_details"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["cat_details"]),
                              "data_categorys_status"=>"1"),
                              "WHERE data_categorys_id=".(int)$_POST["cat_id"],
                              1,"");

        if($Change_dataTextCatName->num_rows!=0){
          $Sql_Connection -> Update_Query( "balances_codes",
                              array(
                                "balances_codes_categorys"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["cat_name"])),
                                "WHERE balances_codes_categorys='".$Change_dataTextCatName_Row["balances_codes_categorys"]."'",
                                1,"");
        }
      }
      $Sql_Connection_User -> save_log("update",strtolower($Get_Old_Cat_Row["data_categorys_type"]),"cat");
      echo "OK";
    }else{
      echo implode(",",$error);
    }
  }






  if($TheAction=="deleteItems"){
    $DeleteitArray = explode(",",$Sql_Connection_User -> DB_Connection -> real_escape_string($_POST["actionsID"]));
    if($TargetType=="cat"){
      $Check_Cat = $Sql_Connection -> Select_Query("data_categorys", "*", "WHERE data_categorys_id IN (".implode(",",$DeleteitArray).") AND data_categorys_type='".$TargetFrom."'");
      $Check_Cat_Row = $Check_Cat -> fetch_array();
      if($Check_Cat->num_rows!=0){
        do{
          if($Check_Cat_Row["data_categorys_type"]!="balances_codes"){
            $Check_data = $Sql_Connection -> Select_Query("data_text", "*", "WHERE lang='".$Check_Cat_Row["lang"]."' AND section='".$Check_Cat_Row["section"]."' AND data_type='".$Check_Cat_Row["data_categorys_type"]."' AND data_categorys='".$Check_Cat_Row["data_categorys_name"]."' LIMIT 1");
            $Check_data_Row = $Check_data -> fetch_array();
            if($Check_data->num_rows!=0){
              $ThereIsData[] = $Check_Cat_Row["data_categorys_id"];
            }else{
              @$Sql_Connection -> Delete_Query( "data_categorys"," data_categorys_id=".$Check_Cat_Row["data_categorys_id"]);
            }
          }else{
            $Check_data = $Sql_Connection -> Select_Query("balances_codes", "*", "WHERE balances_codes_categorys='".$Check_Cat_Row["data_categorys_name"]."' LIMIT 1");
            $Check_data_Row = $Check_data -> fetch_array();
            if($Check_data->num_rows!=0){
              $ThereIsData[] = $Check_Cat_Row["data_categorys_id"];
            }else{
              @$Sql_Connection -> Delete_Query( "data_categorys"," data_categorys_id=".$Check_Cat_Row["data_categorys_id"]);
            }
          }
        } while ($Check_Cat_Row = $Check_Cat -> fetch_array());
      }
      if($ThereIsData==""){
        $Sql_Connection_User -> save_log("delete",$TargetFrom,"cat");
        echo "OK";
      }else{
        echo "ThereIsData:".implode(",",$ThereIsData);
      }
    }elseif ($TargetType=="customers") {
      $Check_customers = $Sql_Connection -> Select_Query("customers", "*", "WHERE customers_id IN (".implode(",",$DeleteitArray).")");
      $Check_customers_Row = $Check_customers -> fetch_array();
      if($Check_customers->num_rows!=0){
        do{
          $Check_data = $Sql_Connection -> Select_Query("orders", "*", "WHERE orders_customers='".$Check_customers_Row["customers_username"]."' LIMIT 1");
          $Check_data_Row = $Check_data -> fetch_array();
          if($Check_data->num_rows!=0){
            $ThereIsData[] = $Check_Cat_Row["customers_id"];
          }else{
            @$Sql_Connection -> Delete_Query( "customers"," customers_id=".$Check_customers_Row["customers_id"]);
          }
        } while ($Check_customers_Row = $Check_customers -> fetch_array());
      }
      if($ThereIsData==""){
        $Sql_Connection_User -> save_log("delete","","customers");
        echo "OK";
      }else{
        echo "ThereIsData:".implode(",",$ThereIsData);
      }
    }elseif ($TargetType=="orders") {
      $Check_orders = $Sql_Connection -> Select_Query("orders", "*", "WHERE orders_id IN (".implode(",",$DeleteitArray).")");
      $Check_orders_Row = $Check_orders -> fetch_array();
      if($Check_orders->num_rows!=0){
        do{
          if($Check_orders_Row["orders_status"]=="1"){
            $ThereIsData[] = $Check_orders_Row["orders_id"];
          }else{
            $Sql_Connection -> Delete_Query( "orders"," orders_id=".$Check_orders_Row["orders_id"]);
          }
        } while ($Check_orders_Row = $Check_orders -> fetch_array());

      }
      if($ThereIsData==""){
        $Sql_Connection_User -> save_log("delete","","orders");
        echo "OK";
      }else{
        echo "ThereIsData:".implode(",",$ThereIsData);
      }
    }elseif ($TargetType=="balances_codes") {
      $Check_balances_codes = $Sql_Connection -> Select_Query("balances_codes", "*", "WHERE balances_codes_id IN (".implode(",",$DeleteitArray).")");
      $Check_balances_codes_Row = $Check_balances_codes -> fetch_array();
      if($Check_balances_codes->num_rows!=0){
        do{
          $Sql_Connection -> Delete_Query( "balances_codes"," balances_codes_id=".$Check_balances_codes_Row["balances_codes_id"]);
        } while ($Check_balances_codes_Row = $Check_balances_codes -> fetch_array());

      }
    }else{
      $Check_data = $Sql_Connection -> Select_Query("data_text", "*", "WHERE data_id IN(".$Sql_Connection_User -> DB_Connection -> real_escape_string($_POST["actionsID"]).")");
      $Check_data_Row = $Check_data -> fetch_array();
      if($Check_data->num_rows!=0){
        do{
          if($Check_data_Row["data_optional"]!=""){
            $Pic_Name = $Check_data_Row["data_optional"];
            @deletefiles("filename=".$Pic_Name, $authorization, $User_Domain);
            if(count($GetImageSize)!=0 || $GetImageSize==false){
              $GetOthers = explode("/",$Pic_Name);
              @deletefiles("filename=".$GetOthers[0]."/".$GetOthers[1]."/".$GetImageSize[1]."/".$GetOthers[2], $authorization, $User_Domain);
              @deletefiles("filename=".$GetOthers[0]."/".$GetOthers[1]."/".$GetImageSize[2]."/".$GetOthers[2], $authorization, $User_Domain);
            }
          }
          if($Check_data_Row["data_attachment"]!=""){
            $All_data_attachment = explode("{|}",$Check_data_Row["data_attachment"]);
            foreach ($All_data_attachment as $value) {
              $Pic_Name = $value;
              @deletefiles("filename=".$Pic_Name, $authorization);
              if(count($GetImageSize)!=0 || $GetImageSize==false){
                $GetOthers = explode("/",$Pic_Name);
                @deletefiles("filename=".$GetOthers[0]."/".$GetOthers[1]."/".$GetImageSize[1]."/".$GetOthers[2], $authorization, $authorization, $User_Domain);
                @deletefiles("filename=".$GetOthers[0]."/".$GetOthers[1]."/".$GetImageSize[2]."/".$GetOthers[2], $authorization, $authorization, $User_Domain);
              }
            }
          }
          $Sql_Connection_User -> save_log("delete",$TargetFrom,"data");

        } while ($Check_data_Row = $Check_data -> fetch_array());
      }

      @$Sql_Connection -> Delete_Query( "data_text","data_id IN(".$Sql_Connection_User -> DB_Connection -> real_escape_string($_POST["actionsID"]).")");
      echo "OK";
    }
  }






  if($TheAction=="save_settings"){
      $Site_Settings = "SiteTitle{|::|}".$Sql_Connection->DB_Connection->real_escape_string($_POST["settings_title"])."{|:::|}"."SiteDescription{|::|}".$Sql_Connection->DB_Connection->real_escape_string($_POST["settings_description"])."{|:::|}"."SiteKeywords{|::|}".$Sql_Connection->DB_Connection->real_escape_string($_POST["settings_keywords"])."{|:::|}"."SiteFacebook{|::|}".$Sql_Connection->DB_Connection->real_escape_string($_POST["settings_facebook"])."{|:::|}".
      "SiteYoutube{|::|}".$Sql_Connection->DB_Connection->real_escape_string($_POST["settings_youtube"])."{|:::|}"."SiteSoundcloud{|::|}".$Sql_Connection->DB_Connection->real_escape_string($_POST["settings_soundcloud"])."{|:::|}"."SiteTwitter{|::|}".$Sql_Connection->DB_Connection->real_escape_string($_POST["settings_twitter"])."{|:::|}"."SiteGoogle{|::|}".$Sql_Connection->DB_Connection->real_escape_string($_POST["settings_google"])."{|:::|}".
      "SiteInstagram{|::|}".$Sql_Connection->DB_Connection->real_escape_string($_POST["settings_instagram"]);

      $Styles = "Header{|::|}".$Sql_Connection->DB_Connection->real_escape_string($_POST["settings_header"])."{|:::|}"."Footer{|::|}".$Sql_Connection->DB_Connection->real_escape_string($_POST["settings_footer"]);

      $checktoallsections = "no";
      if($_POST["checktoallsections"]=="yes"){
        if($Get_UserData_Row["users_main"]=="1"){
          $checktoallsections = "yes";
        }
      }


      if($checktoallsections=="no"){
        $Sql_Connection -> Update_Query( "settings",
                            array(
                              "settings_option"=>$Site_Settings),
                              "WHERE settings_id=".(int)$_POST["settings_id"],
                              1,"");

        $Sql_Connection -> Update_Query( "settings",
                            array(
                              "settings_option"=>$Styles),
                              "WHERE settings_id=".(int)$_POST["style_id"],
                              0,"");
      }else{
        foreach ($All_Sections_Key_Array as $value) {
          $Check_settings = $Sql_Connection -> Select_Query("settings", "*", "WHERE settings_type='Site_Settings' AND section='".$value."' AND lang='".$lang."'");
          $Check_settings_Row = $Check_settings -> fetch_array();
          if($Check_settings->num_rows!=0){
              $Sql_Connection -> Update_Query( "settings",
                                  array(
                                    "settings_option"=>$Site_Settings),
                                    "WHERE settings_id=".$Check_settings_Row["settings_id"],
                                    1,"");
          }else{
            $Sql_Connection -> Insert_Query( "settings",
                                array(
                                "lang"=>$lang,
                                "section"=>$value,
                                "settings_type"=>"Site_Settings",
                                "settings_option"=>$Site_Settings),
                                "","");
          }
          $Check_Style = $Sql_Connection -> Select_Query("settings", "*", "WHERE settings_type='Styles' AND section='".$value."' AND lang='".$lang."'");
          $Check_Style_Row = $Check_Style -> fetch_array();
          if($Check_Style->num_rows!=0){
              $Sql_Connection -> Update_Query( "settings",
                                  array(
                                    "settings_option"=>$Styles),
                                    "WHERE settings_id=".$Check_Style_Row["settings_id"],
                                    "","");
          }else{
            $Sql_Connection -> Insert_Query( "settings",
                                array(
                                "lang"=>$lang,
                                "section"=>$value,
                                "settings_type"=>"Styles",
                                "settings_option"=>$Styles),
                                "","");
          }
        }
      }
      $Sql_Connection_User -> save_log("update","","settings");

      echo "OK";
  }






  if($TheAction=="viewedmessages"){
    $VieweditArray = explode(",",$Sql_Connection_User -> DB_Connection -> real_escape_string($_POST["actionsID"]));
    $Sql_Connection_User -> DoSomething("UPDATE messages SET messages_who = CONCAT(messages_who, ',".$User_Domain."_".$User_Profile."') WHERE messages_id IN (".implode(",",$VieweditArray).") AND FIND_IN_SET('".$User_Domain."_".$User_Profile."', messages_who) = 0");
    echo "OK";
  }






  if($TheAction=="check_important"){
    $GetLang = $Sql_Connection->DB_Connection->real_escape_string($_POST["lang"]);
    $GetSection = $Sql_Connection->DB_Connection->real_escape_string($_POST["section"]);
    $GetId = (int)$Sql_Connection->DB_Connection->real_escape_string($_POST["id"]);
    $GetType = $Sql_Connection->DB_Connection->real_escape_string($TargetFrom);

    $Checksettings = $Sql_Connection -> Select_Query("settings", "*", "WHERE lang='".$GetLang."' AND section='".$GetSection."' AND settings_type='".$GetType."' LIMIT 1");
    $Checksettings_Row = $Checksettings->fetch_array();

    if($Checksettings->num_rows!=0){
      $All_IDS_Array = @explode(",",$Checksettings_Row["settings_option"]);
      if($check_type=="checked"){
        $All_IDS_Array [] = $GetId;
        $All_IDS = @implode(",",$All_IDS_Array);
      }else{
        foreach ($All_IDS_Array as $value) {
          if($value!=""){
            if($value!=$GetId){
              $New_All_IDS_Array[] = $value;
            }
          }
        }
        $All_IDS = @implode(",",$New_All_IDS_Array);
      }

      $Sql_Connection -> Update_Query( "settings",
                          array(
                          "lang"=>$GetLang,
                          "section"=>$GetSection,
                          "settings_type"=>$GetType,
                          "settings_option"=>$All_IDS),
                          "WHERE settings_id=".(int)$Checksettings_Row["settings_id"],
                          1,"");
    }else{
      if($check_type=="checked"){
        $Sql_Connection -> Insert_Query( "settings",
                            array(
                            "lang"=>$GetLang,
                            "section"=>$GetSection,
                            "settings_type"=>$GetType,
                            "settings_option"=>$GetId),
                            "",1);
      }
    }
    $Sql_Connection_User -> save_log("update",$GetType,"important");

    echo "OK";
  }






  if($TheAction=="SaveDataText"){
    if(checkvalues($_POST["data_title"],"text")=="error"){ $error[] = "data_title"; }
    if(checkvalues($_POST["data_details"],"text")=="error"){ $error[] = "data_details"; }
    if(count($error)==0){
      $CheckPic = $Sql_Connection -> Select_Query("data_text", "*", "WHERE  data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"])." LIMIT 1");
      $CheckPic_Row = $CheckPic->fetch_array();
      if($CheckPic_Row["data_type"]!="pages"){
        if($CheckPic_Row["data_optional"]==""){
          $error[] = "mainpic";
        }
      }
    }
    if(count($error)==0){

      $Sql_Connection -> Update_Query( "data_text",
                          array(
                            "data_categorys"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_categorys"]),
                            "data_title"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_title"]),
                            "data_body"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_body"]),
                            "data_details"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_details"]),
                            "data_date"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_date"]),
                            "data_status"=>"1"),
                            "WHERE data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"]),
                            0,"");
      echo "OK";
      $Sql_Connection_User -> save_log("update",strtolower($CheckPic_Row["data_type"]),"data");

    }else{
      echo implode(",",$error);
    }
  }






  if($TheAction=="PagesDataHtml"){
    if(checkvalues($_POST["data_title"],"text")=="error"){ $error[] = "data_title"; }
    if(checkvalues($_POST["data_details"],"text")=="error"){ $error[] = "data_details"; }
    if(count($error)==0){

      $Sql_Connection -> Update_Query( "data_text",
                          array(
                            "data_categorys"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_categorys"]),
                            "data_title"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_title"]),
                            "data_body"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_body"]),
                            "data_details"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_details"]),
                            "data_date"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_date"]),
                            "data_status"=>"1"),
                            "WHERE data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"]),
                            0,"");
      echo "OK";
      $Sql_Connection_User -> save_log("update","pages","data");

    }else{
      echo implode(",",$error);
    }
  }







  if($TheAction=="SaveMedia"){
    if(checkvalues($_POST["data_title"],"text")=="error"){ $error[] = "data_title"; }
    if(checkvalues($_POST["data_details"],"text")=="error"){ $error[] = "data_details"; }
    if(checkvalues($_POST["data_body"],"text")=="error"){ $error[] = "data_body"; }
    if(count($error)==0){
      $CheckPic = $Sql_Connection -> Select_Query("data_text", "*", "WHERE  data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"])." LIMIT 1");
      $CheckPic_Row = $CheckPic->fetch_array();
      if($CheckPic_Row["data_optional"]==""){
        $error[] = "mainpic";
      }
    }
    if(count($error)==0){

      $Sql_Connection -> Update_Query( "data_text",
                          array(
                            "data_categorys"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_categorys"]),
                            "data_title"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_title"]),
                            "data_body"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_body"]),
                            "data_details"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_details"]),
                            "data_date"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_date"]),
                            "data_status"=>"1"),
                            "WHERE data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"]),
                            0,"");
      $Sql_Connection_User -> save_log("update",strtolower($CheckPic_Row["data_type"]),"data");
      echo "OK";
    }else{
      echo implode(",",$error);
    }
  }






  if($TheAction=="SaveCV"){
    if(checkvalues($_POST["data_title"],"text")=="error"){ $error[] = "data_title"; }
    if(checkvalues($_POST["cv_jobe"],"text")=="error"){ $error[] = "cv_jobe"; }
    if(checkvalues($_POST["cv_email"],"email")=="error"){ $error[] = "cv_email"; }
    if(checkvalues($_POST["cv_phone"],"phone")=="error"){ $error[] = "cv_phone"; }
    if(checkvalues($_POST["cv_sub"],"text")=="error"){ $error[] = "cv_sub"; }
    if(count($error)==0){
      $CheckPic = $Sql_Connection -> Select_Query("data_text", "*", "WHERE  data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"])." LIMIT 1");
      $CheckPic_Row = $CheckPic->fetch_array();
      if($CheckPic_Row["data_optional"]==""){
        $error[] = "mainpic";
      }
    }
    if(count($error)==0){
      $data_body = $Sql_Connection->DB_Connection->real_escape_string($_POST["cv_jobe"]) . "{:-:}" . $Sql_Connection->DB_Connection->real_escape_string($_POST["cv_email"]) . "{:-:}" .
                   $Sql_Connection->DB_Connection->real_escape_string($_POST["cv_phone"]) . "{:-:}" . $Sql_Connection->DB_Connection->real_escape_string($_POST["cv_sub"]);
      $Sql_Connection -> Update_Query( "data_text",
                          array(
                            "data_categorys"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_categorys"]),
                            "data_title"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_title"]),
                            "data_body"=>$data_body,
                            "data_details"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_details"]),
                            "data_status"=>"1"),
                            "WHERE data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"]),
                            0,"");
      $Sql_Connection_User -> save_log("update","cv","data");
      echo "OK";
    }else{
      echo implode(",",$error);
    }
  }







  if($TheAction=="SaveBlocks"){
    if(checkvalues($_POST["data_title"],"text")=="error"){ $error[] = "data_title"; }
    if(checkvalues($_POST["data_details"],"text")=="error"){ $error[] = "data_details"; }
    if(checkvalues($_POST["data_optional"],"number")=="error"){ $error[] = "data_optional"; }

    if(count($error)==0){
      $Sql_Connection -> Update_Query( "data_text",
                          array(
                            "data_title"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_title"]),
                            "data_details"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_details"]),
                            "data_optional"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_optional"]),
                            "data_body"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_body"]),
                            "data_status"=>"1"),
                            "WHERE data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"]),
                            0,"");
      $Sql_Connection_User -> save_log("update","blocks","data");
      echo "OK";
    }else{
      echo implode(",",$error);
    }
  }







  if($TheAction=="SaveCodes"){
    if(checkvalues($_POST["data_title"],"text")=="error"){ $error[] = "data_title"; }
    if(checkvalues($_POST["data_details"],"text")=="error"){ $error[] = "data_details"; }
    if(count($error)==0){

      $checktoallsections = "no";
      if($_POST["checktoallsections"]=="yes"){
        $checktoallsections = "yes";
      }


      if($checktoallsections=="no"){
        $Sql_Connection -> Update_Query( "data_text",
                            array(
                              "data_title"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_title"]),
                              "data_details"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_details"]),
                              "data_body"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_body"]),
                              "data_status"=>"1"),
                              "WHERE data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"]),
                              0,"");
      }else{
        foreach ($All_Sections_Key_Array as $value) {
          $Check_data_text = $Sql_Connection -> Select_Query("data_text", "*", "WHERE data_details='".$Sql_Connection->DB_Connection->real_escape_string($_POST["data_details"])."' AND section='".$value."' AND lang='".$lang."'");
          $Check_data_text_Row = $Check_data_text -> fetch_array();
          if($Check_data_text->num_rows!=0){
            $Sql_Connection -> Update_Query( "data_text",
                                array(
                                  "data_title"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_title"]),
                                  "data_details"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_details"]),
                                  "data_body"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_body"]),
                                  "data_status"=>"1"),
                                  "WHERE data_id=".$Check_data_text_Row["data_id"],
                                  0,"");
          }else{
            $Sql_Connection -> Insert_Query( "data_text",
                                array(
                                  "lang"=>$lang,
                                  "section"=>$value,
                                  "data_type"=>"codes",
                                  "data_source"=>"html",
                                  "data_title"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_title"]),
                                  "data_details"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_details"]),
                                  "data_body"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_body"]),
                                  "data_status"=>"1"),
                                  "","");
          }
        }
      }


      $Sql_Connection_User -> save_log("update","codes","data");
      echo "OK";
    }else{
      echo implode(",",$error);
    }
  }





  if($TheAction=="SaveLibrary"){
    if(checkvalues($_POST["data_title"],"text")=="error"){ $error[] = "data_title"; }
    if(checkvalues($_POST["data_type"],"text")=="error"){ $error[] = "data_type"; }
    if(checkvalues($_POST["data_lang"],"text")=="error"){ $error[] = "data_lang"; }
    if(checkvalues($_POST["data_year"],"text")=="error"){ $error[] = "data_year"; }
    if(checkvalues($_POST["data_library_name"],"text")=="error"){ $error[] = "data_library_name"; }
    if(checkvalues($_POST["data_details"],"text")=="error"){ $error[] = "data_details"; }
    if(count($error)==0){
      $data_details = $Sql_Connection->DB_Connection->real_escape_string($_POST["data_type"]) . "{:-:}" . $Sql_Connection->DB_Connection->real_escape_string($_POST["data_lang"]) . "{:-:}" .
                   $Sql_Connection->DB_Connection->real_escape_string($_POST["data_year"]) . "{:-:}" . $Sql_Connection->DB_Connection->real_escape_string($_POST["data_library_name"]);
      $Sql_Connection -> Update_Query( "data_text",
                          array(
                            "data_categorys"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_categorys"]),
                            "data_title"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_title"]),
                            "data_body"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_details"]),
                            "data_details"=>$data_details,
                            "data_status"=>"1"),
                            "WHERE data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"]),
                            0,"");
      $Sql_Connection_User -> save_log("update","library","data");
      echo "OK";
    }else{
      echo implode(",",$error);
    }
  }





  if($TheAction=="SaveProducts"){
    if(checkvalues($_POST["data_title"],"text")=="error"){ $error[] = "data_title"; }
    if(checkvalues($_POST["data_made"],"text")=="error"){ $error[] = "data_made"; }
    if(checkvalues($_POST["data_brand"],"text")=="error"){ $error[] = "data_brand"; }
    if(checkvalues($_POST["data_oneprice"],"number")=="error"){ $error[] = "data_oneprice"; }
    if(checkvalues($_POST["data_wholesaleprice"],"number")=="error"){ $error[] = "data_wholesaleprice"; }
    if(checkvalues($_POST["data_currency"],"text")=="error"){ $error[] = "data_currency"; }
    if(checkvalues($_POST["data_onename"],"text")=="error"){ $error[] = "data_onename"; }
    if(checkvalues($_POST["data_totalinwholesale"],"number")=="error"){ $error[] = "data_totalinwholesale"; }
    if(checkvalues($_POST["data_details"],"text")=="error"){ $error[] = "data_details"; }

    if(count($error)==0){
      $data_Json = array("data_made"=>$_POST["data_made"] , "data_brand"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_brand"]) ,
                   "data_oneprice"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_oneprice"]) , "data_wholesaleprice"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_wholesaleprice"]) ,
                   "data_currency"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_currency"]) , "data_onename"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_onename"]) ,
                   "data_totalinwholesale"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_totalinwholesale"]));
      $Sql_Connection -> Update_Query( "data_text",
                          array(
                            "data_categorys"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_categorys"]),
                            "data_title"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_title"]),
                            "data_body"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_body"]),
                            "data_details"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_details"]),
                            "data_json"=>json_encode($data_Json,JSON_UNESCAPED_UNICODE),
                            "data_status"=>"1"),
                            "WHERE data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"]),
                            0,"");
      $Sql_Connection_User -> save_log("update","products","data");
      echo "OK";
    }else{
      echo implode(",",$error);
    }
  }






  if($TheAction=="SaveCustomers"){
    if(checkvalues($_POST["customers_fullname"],"text")=="error"){ $error[] = "customers_fullname"; }
    if(checkvalues($_POST["customers_username"],"text")=="error"){ $error[] = "customers_username"; }
    if(checkvalues($_POST["customers_password"],"text")=="error"){ $error[] = "customers_password"; }
    if(checkvalues($_POST["customers_email"],"email")=="error"){ $error[] = "customers_email"; }
    if(checkvalues($_POST["customers_phone"],"phone")=="error"){ $error[] = "customers_phone"; }
    if(checkvalues($_POST["customers_address"],"text")=="error"){ $error[] = "customers_address"; }
    if(checkvalues($_POST["customers_city"],"text")=="error"){ $error[] = "customers_city"; }

    $Check_Old_Customers_Name = $Sql_Connection -> Select_Query("customers", "*", "WHERE customers_id=".(int)$_POST["customers_id"]." limit 1");
    $Check_Old_Customers_Name_Row = $Check_Old_Customers_Name -> fetch_array();

    $Check_Customers = $Sql_Connection -> Select_Query("customers", "*", "WHERE customers_username='".$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_username"])."' limit 1");
    $Check_Customers_Row = $Check_Customers -> fetch_array();
    if($Check_Customers->num_rows==1){
      if($Check_Customers_Row["customers_id"]!=(int)$_POST["customers_id"]){
        $error[] = "thereisuser";
      }
    }
    if(count($error)==0){
      if($_POST["customers_password"]==""){
        $Sql_Connection -> Update_Query( "customers",
                            array(
                              "customers_fullname"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_fullname"]),
                              "customers_username"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_username"]),
                              "customers_email"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_email"]),
                              "customers_phone"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_phone"]),
                              "customers_address"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_address"]),
                              "customers_city"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_city"]),
                              "customers_status"=>"1"),
                              "WHERE customers_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_id"]),
                              1,"");
        }else{
          $Sql_Connection -> Update_Query( "customers",
                              array(
                                "customers_fullname"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_fullname"]),
                                "customers_username"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_username"]),
                                "customers_password"=>sha1($Sql_Connection->DB_Connection->real_escape_string($_POST["customers_password"])),
                                "customers_email"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_email"]),
                                "customers_phone"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_phone"]),
                                "customers_address"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_address"]),
                                "customers_city"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_city"]),
                                "customers_status"=>"1"),
                                "WHERE customers_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_id"]),
                                0,"");
        }
        $Sql_Connection -> Update_Query( "orders",
                            array(
                              "orders_customers"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_username"])),
                              "WHERE orders_customers=".(int)$Sql_Connection->DB_Connection->real_escape_string($Check_Old_Customers_Name_Row["customers_username"]),
                              0,"");

        $Sql_Connection -> Update_Query( "balances_codes",
                            array(
                              "balances_codes_customers"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["customers_username"])),
                              "WHERE balances_codes_customers=".(int)$Sql_Connection->DB_Connection->real_escape_string($Check_Old_Customers_Name_Row["customers_username"]),
                              0,"");
        $Sql_Connection_User -> save_log("update","","customers");
        echo "OK";
    }else{
      echo implode(",",$error);
    }
  }








  if($TheAction=="SaveOrders"){
    if(checkvalues($_POST["orders_price"],"number")=="error"){ $error[] = "orders_price"; }
    $Check_Customers = $Sql_Connection -> Select_Query("orders", "*", "WHERE orders_id=".(int)$_POST["orders_id"]." limit 1");
    $Check_Customers_Row = $Check_Customers -> fetch_array();
    $Check_Total_balances = $Sql_Connection -> Select_Query("balances_codes", "SUM(balances_codes_amount)", "WHERE balances_codes_customers='".$Check_Customers_Row["orders_customers"]."'");
    $Check_Total_balances_Row = $Check_Total_balances -> fetch_array();
    if($Check_Total_balances_Row[0]<=$Sql_Connection->DB_Connection->real_escape_string($_POST["orders_price"])){
      $error[] = "totalprice";
    }

    if($Check_Customers->num_rows!=1){
      $error[] = "orders_customers";
    }
    if(count($error)==0){
      if($Check_Orders_Row["orders_messages"]!=""){
        $add_to_messages = json_decode($Check_Orders_Row["orders_messages"],JSON_UNESCAPED_UNICODE);
      }
        $Sql_Connection -> Update_Query( "orders",
                            array(
                              "orders_price"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["orders_price"]),
                              "messages_for_user"=>"1",
                              "orders_status"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["orders_status"])),
                              "WHERE orders_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["orders_id"]),
                              0,"");

        $Sql_Connection_User -> save_log("update","","orders");
        echo "OK";
    }else{
      echo implode(",",$error);
    }
  }








  if($TheAction=="Send_Message_To_User"){
    if(checkvalues($_POST["orders_messages"],"text")=="error"){ $error[] = "orders_messages"; }
    $Check_Orders = $Sql_Connection -> Select_Query("orders", "*", "WHERE orders_id=".(int)$_POST["orders_id"]." limit 1");
    $Check_Orders_Row = $Check_Orders -> fetch_array();
    if($Check_Orders->num_rows!=1){
      $error[] = "orders_customers";
    }
    if(count($error)==0){
      if($Check_Orders_Row["orders_messages"]!=""){
        $add_to_messages = json_decode($Check_Orders_Row["orders_messages"],JSON_UNESCAPED_UNICODE);
      }
      $add_to_messages[] = array('id' => count($add_to_messages)+1, 'body' => $Sql_Connection->DB_Connection->real_escape_string($_POST["orders_messages"]), 'who' => $User_Name);
      $Sql_Connection -> Update_Query( "orders",
                          array(
                            "orders_messages"=>json_encode($add_to_messages,JSON_UNESCAPED_UNICODE),
                            "messages_for_user"=>"1"),
                            "WHERE orders_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["orders_id"]),
                            0,"");
        echo "OK";
    }else{
      echo implode(",",$error);
    }
  }








  if($TheAction=="Delete_Order_Message"){
    $Check_Orders = $Sql_Connection -> Select_Query("orders", "*", "WHERE orders_id=".(int)$_POST["orders_id"]." limit 1");
    $Check_Orders_Row = $Check_Orders -> fetch_array();
    if($Check_Orders->num_rows!=1){
      $error[] = "error";
    }
    if(count($error)==0){
        $add_to_messages = json_decode($Check_Orders_Row["orders_messages"]);
        foreach ($add_to_messages as $value) {
          if($value->id!=(int)$_POST["messages_id"]){
            $New_Add_To_Messages[] =$value;
          }
        }
        $Sql_Connection -> Update_Query( "orders",
                            array(
                              "orders_messages"=>json_encode($New_Add_To_Messages,JSON_UNESCAPED_UNICODE)),
                              "WHERE orders_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["orders_id"]),
                              0,"");
        echo "OK";
    }else{
      echo implode(",",$error);
    }
  }








  if($TheAction=="CancelOrders"){
    $Check_Customers = $Sql_Connection -> Select_Query("orders", "*", "WHERE orders_id=".(int)$_POST["orders_id"]." limit 1");
    $Check_Customers_Row = $Check_Customers -> fetch_array();
    if($Check_Customers_Row["orders_status"]==0 || $Check_Customers_Row["orders_price"]<=0){
      $error = "error";
    }

    if(count($error)==0){
        $Sql_Connection -> Update_Query( "orders",
                            array(
                              "orders_status"=>"0"),
                              "WHERE orders_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["orders_id"]),
                              0,"");
        $Sql_Connection_User -> save_log("update","","orders");
        echo "OK";
    }else{
      echo implode(",",$error);
    }
  }




  if($TheAction=="add_balances_codes"){
    if(checkvalues($_POST["cards_number"],"number")=="error"){ $error[] = "cards_number"; }
    if(checkvalues($_POST["cards_cost"],"number")=="error"){ $error[] = "cards_cost"; }
    if((int)$Sql_Connection->DB_Connection->real_escape_string($_POST["cards_number"])>50){
      $error[] = "cards_number";
    }

    $Get_data_categorys = $Sql_Connection -> Select_Query("data_categorys", "*", "WHERE data_categorys_id='".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["cat_id"])."' limit 1");
    $Get_data_categorys_Row = $Get_data_categorys -> fetch_array();
    if($Get_data_categorys->num_rows!=1){
      $error[] = "error";
    }


    if(count($error)==0){
      for ($i=0; $i <(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["cards_number"]) ; $i++) {
        $randtext = $i;
        if($randtext<10){
          $randtext = $randtext+10;
        }
        $randtext = rand(1,10) + $randtext;
        $balances_codes_text = ($randtext+2).date("mdYHis").($randtext+1)."</br>";
        $balances_codes_passcode = $randtext.date("mY").date("dHis").date("YHis"+1)."</br>";
        $Sql_Connection -> Insert_Query( "balances_codes",
                            array(
                            "balances_codes_categorys"=>$Get_data_categorys_Row["data_categorys_name"],
                            "balances_codes_text"=>$balances_codes_text,
                            "balances_codes_passcode"=>$balances_codes_passcode,
                            "balances_codes_amount"=>(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["cards_cost"]),
                            "balances_codes_customers"=>""),
                            "",
                            1);
      }

      $Sql_Connection_User -> save_log("insert","","balances_codes");
      echo "OK";
    }else{
      echo implode(",",$error);
    }
  }





  if($TheAction=="search_pic_files"){
    $search_data = $Sql_Connection -> Select_Query("data_text", "data_id,data_title,data_optional,data_attachment",  "WHERE data_title LIKE '%".$TargetKey."%' OR data_optional LIKE '%".$TargetKey."%' OR data_attachment LIKE '%".$TargetKey."%' LIMIT 20");
    $search_data_Row = $search_data -> fetch_array();

    if($search_data->num_rows!=0 && $TargetKey!=""){
  		if(count($GetImageSize)==0 || $GetImageSize==false){
  			$Sql_Connection -> Insert_Query( "settings",
  													array(
  													"settings_type"=>"image_size",
  													"settings_option"=>"800:350:165"),
  													"",
  													1);
  			$GetImageSize = explode(":","800:350:165");
  		}
      switch ($TargetType) {
        case "files":
          $file_type="attachments";
          break;
        case "pics":
          $file_type = "main-pic";
          break;
        case "one":
          $file_type = "one";
          break;
        default:
          $file_type = "main-pic";
      }
      do{
        if($search_data_Row["data_optional"]!=""){
          foreach (explode("{|}",$search_data_Row["data_optional"]) as $value) {
            if($value!=""){
              $get_small_one = explode("/",$value);
              $Imagesextensions = array('gif','GIF','jpeg','JPEG','jpg','JPG','png','PNG');
              if(!in_array(substr(strrchr($get_small_one[2], '.'), 1),$Imagesextensions)){
                if($TargetType=="pics"){ continue; }
                $url = "http://".$User_Domain.'/public/style/upload_files_type/'.substr(strrchr($get_small_one[2], '.'), 1);
              }else{
                $url = "http://".$User_Domain."/public/files/".$get_small_one[1].'/'.$GetImageSize[1].'/'.$get_small_one[2];
              }

              $AllData[] = '<div class="col-sm-4">
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="thumbnail">
                                    <img src="'.$url.'" style="height:200px;" />
                                  </div>
                                </div>
                              </div>
                              <div class="row" style="padding:0 0 20px 0;">
                                <div class="col-sm-12">
                                  <input type="submit" class="btn btn-sm btn-borders btn-default get_search_pic_files pull-left" style="width:45%" id="'.$value.'" target="data" file-type="'.$file_type.'" value="استيراد">
                                  <input type="submit" class="btn btn-sm btn-borders btn-danger delete_file_pic pull-right hidden" style="width:45%" target="data" file-type="'.$file_type.'" id="'.$value.'" value="حذف">
                                </div>
                              </div>
                            </div>';
            }
          }
        }
        if($search_data_Row["data_attachment"]!=""){
          foreach (explode("{|}",$search_data_Row["data_attachment"]) as  $value) {
            if($value!=""){
              $get_small_one = explode("/",$value);
              $Imagesextensions = array('gif','GIF','jpeg','JPEG','jpg','JPG','png','PNG');
              if(!in_array(substr(strrchr($get_small_one[2], '.'), 1),$Imagesextensions)){
                if($TargetType=="pics"){ continue; }
                $url = "http://".$User_Domain.'/public/style/upload_files_type/'.substr(strrchr($get_small_one[2], '.'), 1);
              }else{
                $url = "http://".$User_Domain."/public/files/".$get_small_one[1].'/'.$GetImageSize[1].'/'.$get_small_one[2];
              }
              $AllData[] = '<div class="col-sm-4">
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="thumbnail">
                                    <img src="'.$url.'" style="height:200px;" />
                                  </div>
                                </div>
                              </div>
                              <div class="row" style="padding:0 0 20px 0;">
                                <div class="col-sm-12">
                                  <input type="submit" class="btn btn-sm btn-borders btn-default get_search_pic_files pull-left" style="width:45%" id="'.$value.'" target="data" file-type="'.$file_type.'" value="استيراد">
                                  <input type="submit" class="btn btn-sm btn-borders btn-danger delete_file_pic pull-right hidden" style="width:45%" target="data" file-type="'.$file_type.'" id="'.$value.'" value="حذف">
                                </div>
                              </div>
                            </div>';
            }
          }
        }
      } while ($search_data_Row = $search_data -> fetch_array());
      if(count($AllData)>0){
        echo "<br />".implode('',$AllData);
      }
    }else{
      echo '<div class="col-sm-12"><div class="row"><div class="col-sm-12">لايوجد نتائج للبحث، حاول اختيار كلمة مفتاحية اخرى</div></div></div>';
    }
  }






  if($TheAction=="get_search_pic_files"){
    if($TargetType=="main-pic"){
      $Sql_Connection -> Update_Query( "data_text",
                          array(
                            "data_optional"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_pic"])),
                            "WHERE data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"]),
                            0,"");
    }elseif ($TargetType=="one") {
      $Sql_Connection -> Update_Query( "data_text",
                          array(
                            "data_attachment"=>$Sql_Connection->DB_Connection->real_escape_string($_POST["data_pic"])),
                            "WHERE data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"]),
                            0,"");
    }else{
      $CheckPhoto = $Sql_Connection -> Select_Query("data_text", "*", "WHERE data_id='".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"])."' LIMIT 1");
      $CheckPhoto_Row = $CheckPhoto->fetch_array();
      if($CheckPhoto_Row["data_attachment"]!=""){
        $GetPicsArray = @explode(",",$CheckPhoto_Row["data_attachment"]);
      }
      $GetPicsArray[] = $Sql_Connection->DB_Connection->real_escape_string($_POST["data_pic"]);

      $Sql_Connection -> Update_Query( "data_text",
                          array(
                            "data_attachment"=>$Sql_Connection->DB_Connection->real_escape_string(@implode("{|}",$GetPicsArray))),
                            "WHERE data_id=".(int)$Sql_Connection->DB_Connection->real_escape_string($_POST["data_id"]),
                            0,"");
    }
    echo "OK";
  }





}



if( $_SERVER['SERVER_NAME']==$dyno_domain_name && $User_ID!="")
{
  if($TheAction=="add_user"){
    $Sql_Connection_User -> save_log("insert","","users");
      $Sql_Connection_User -> Insert_Query( "users",
                          array(
                            "domain"=>$User_Domain),
                            "",
                            1,"");
                            header("Location: ".$site_url."/panel/users/1/".$Sql_Connection_User -> Get_Insert_Update_id());
  }

  if($TheAction=="add_cat"){
    $Sql_Connection_User -> save_log("insert",$TargetFrom,"cat");
      $Sql_Connection -> Insert_Query( "data_categorys",
                          array(
                            "lang"=>$lang,
                            "section"=>$SectionsVal,
                            "data_categorys_type"=>$TargetFrom),
                            "",
                            1,"");

                            if($TargetFrom!="balances_codes"){
                              header("Location: ".$site_url."/panel/cat/".$TargetFrom."/".$Sql_Connection -> Get_Insert_Update_id());
                            }else{
                              header("Location: ".$site_url."/panel/balances_codes/add_balances_codes/".$Sql_Connection -> Get_Insert_Update_id());
                            }
  }

  if($TheAction=="add_data"){

    if($TargetFrom=="customers"){
      $Sql_Connection_User -> save_log("insert",$TargetFrom,"data");
      $Sql_Connection -> Insert_Query( "customers",
                            array("customers_status"=>"0"),
                            "",
                            1,"");
                            header("Location: ".$site_url."/panel/customers_view/".$TargetFrom."/".$Sql_Connection -> Get_Insert_Update_id()."/1");
    }else{
      $Get_data_categorys = $Sql_Connection -> Select_Query("data_categorys", "*", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND data_categorys_type='".$TargetFrom."' ORDER BY data_categorys_name ASC");
      $Get_data_categorys_Row = $Get_data_categorys -> fetch_array();
      if($Get_data_categorys->num_rows!=0){
        $Sql_Connection_User -> save_log("insert",$TargetFrom,"data");
        $Sql_Connection -> Insert_Query( "data_text",
                            array(
                              "lang"=>$lang,
                              "section"=>$SectionsVal,
                              "data_source"=>$AddType,
                              "data_type"=>$TargetFrom),
                              "",
                              1,"");
                              header("Location: ".$site_url."/panel/data_view/".$TargetFrom."/".$Sql_Connection -> Get_Insert_Update_id()."/1/".$AddType);
      }else{
        if($TargetFrom=="blocks"){
          $Sql_Connection_User -> save_log("insert",$TargetFrom,"data");
          $Sql_Connection -> Insert_Query( "data_text",
                              array(
                                "lang"=>$lang,
                                "section"=>$SectionsVal,
                                "data_source"=>$AddType,
                                "data_type"=>$TargetFrom),
                                "",
                                1,"");
                                header("Location: ".$site_url."/panel/data_view/".$TargetFrom."/".$Sql_Connection -> Get_Insert_Update_id()."/1/".$AddType);
        }elseif ($TargetFrom=="codes") {
          $Sql_Connection_User -> save_log("insert",$TargetFrom,"data");
          $Sql_Connection -> Insert_Query( "data_text",
                              array(
                                "lang"=>$lang,
                                "section"=>$SectionsVal,
                                "data_source"=>$AddType,
                                "data_type"=>$TargetFrom),
                                "",
                                1,"");
                                header("Location: ".$site_url."/panel/data_view/".$TargetFrom."/".$Sql_Connection -> Get_Insert_Update_id()."/1/".$AddType);
        }else{
          header("Location: ".$site_url."/panel/cat/".$TargetFrom);
        }
      }
    }
  }


}
?>
