<?php
if($Get_UserData_Row["users_main"]=="1"){
    if($pagenum<=1){
      $from = 0;
      $to = 100;
    }else{
      $from = ($pagenum-1) * 100;
      $to = ($pagenum * 100);
    }
    $Get_logs = $Sql_Connection_User -> Select_Query("logs_data", "*", "WHERE domain ='".$User_Domain."' ORDER BY logs_id DESC LIMIT ".$from.", ".$to);
    $Get_logs_Row = $Get_logs -> fetch_array();
    $Get_logs_Total = $Sql_Connection_User->Select_Query("logs_data", "*", "WHERE domain ='".$User_Domain."'");

}else{
  header("Location: ".$site_url."/panel/page404");
  exit;
}
?>
