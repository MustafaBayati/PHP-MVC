<?php
if($Get_UserData_Row["users_main"]=="1"){
  if($DataId==""){
    if($pagenum<=1){
      $from = 0;
      $to = 20;
    }else{
      $from = ($pagenum-1) * 20;
      $to = ($pagenum * 20);
    }
    $Get_Messages = $Sql_Connection_User -> Select_Query("messages", "*", "ORDER BY messages_id DESC LIMIT ".$from.", ".$to);
    $Get_Messages_Row = $Get_Messages -> fetch_array();
    $Get_Messages_Total = $Sql_Connection_User->Select_Query("messages", "*", "");
  }else{
    $Sql_Connection_User -> DoSomething("UPDATE messages SET messages_who = CONCAT(messages_who, ',".$User_Domain."_".$User_Profile."') WHERE messages_id=".$DataId." AND FIND_IN_SET('".$User_Domain."_".$User_Profile."', messages_who) = 0");

    $View_Messages = $Sql_Connection_User -> Select_Query("messages", "*", "WHERE messages_id='".$DataId."' limit 1");
    $View_Messages_Row = $View_Messages -> fetch_array();
  }

  $backUrl = $site_url."/panel/messages/".$pagenum;

}else{
  header("Location: ".$site_url."/panel/page404");
  exit;
}
?>
