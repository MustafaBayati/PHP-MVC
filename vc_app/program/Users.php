<?php
if($DataId==""){
    $Get_Users = $Sql_Connection_User -> Select_Query("users", "*", "WHERE domain='".$User_Domain."' ORDER BY users_fullname ASC");
    $Get_Users_Row = $Get_Users -> fetch_array();

    $Get_Status = $Sql_Connection_User -> Select_Query("users", "*", "WHERE domain='".$User_Domain."' AND users_status=0 LIMIT 1");
    $Get_Status_Row = $Get_Status -> fetch_array();
    if($Get_Users_Status -> num_rows>0){
      $JqueryVars = "ShowError('هنالك عناصر غير مفعلة مضللة باللون الاحمر، يرجى ملئ الحقول المطلوبة للمستخدمين');";
    }


  }else{
    $Edit_Users = $Sql_Connection_User -> Select_Query("users", "*", "WHERE domain='".$User_Domain."' AND users_id = ".(int)$Sql_Connection_User -> DB_Connection -> real_escape_string($DataId)."");
    $Edit_Users_Row = $Edit_Users -> fetch_array();
    if($Edit_Users -> num_rows == 0){
      header("Location: ".$site_url."/panel/page404");
    }


    $ActiveUploadScript = ture;
    $JqueryVars = "var uploadtype = 'users'; ";
    $JqueryVars .= 'var actionsids = "'.$Edit_Users_Row["users_id"].'";';

    if($Edit_Users_Row["users_photo"]!=""){
      $DoPicsArray = @explode("{|}",$Edit_Users_Row["users_photo"]);
      foreach ($DoPicsArray as $value) {
        if($value!=""){
          $img .= '<div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="thumbnail">
                          <img src="'.$style_dir.$value.'" style="height:200px;" />
                        </div>
                      </div>
                    </div>
                    <div class="row" style="padding:0 0 20px 0;">
                      <div class="col-sm-12">
                        <input type="submit" class="btn btn-sm btn-borders btn-danger delete_file_pic pull-right" style="width:45%" file-type="main-pic" target="users" id="'.$value.'" value="حذف الصورة">
                      </div>
                    </div>
                  </div>';
        }
      }
    }
    if($img!=""){ $HideImages=" hidden";}
  }

  function GetUserSectionsName($All_Sections_Array,$Value){
    if($Value!=""){
      if($Value!="all"){
        $Value_Array = explode(",",$Value);
        foreach ($Value_Array as $Get_Value) {
          foreach ($All_Sections_Array as $key => $value) {
            if($key==$Get_Value){
              $SaveValue []= $value;
              break;
            }
          }
        }
        return implode(" - ",$SaveValue);
      }else{
        return "كل الاقسام";
      }
    }else{
      return "";
    }
  }


  $backUrl = $site_url."/panel/users/".$PageNum;
?>
