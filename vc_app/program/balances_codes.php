<?php
if((in_array($TargetFrom,$Users_Permissions_Array) || $Get_UserData_Row["users_main"]=="1") && in_array($TargetFrom,$Domains_Programs_Array)){
  if($ViewAction==false){
    if($DataId==""){
      $Get_data_categorys = $Sql_Connection -> Select_Query("data_categorys", "* ,
      (SELECT COUNT(balances_codes.balances_codes_id) FROM balances_codes WHERE balances_codes.balances_codes_categorys = data_categorys.data_categorys_name) as total_balances_codes ,
      (SELECT COUNT(balances_codes.balances_codes_id) FROM balances_codes WHERE balances_codes.balances_codes_categorys = data_categorys.data_categorys_name AND balances_codes_customers!='') as total_sales" ,
      "WHERE data_categorys_type='balances_codes' ORDER BY data_categorys_name ASC");
      $Get_data_categorys_Row = $Get_data_categorys -> fetch_array();
    }else{

      $Edit_data_categorys = $Sql_Connection -> Select_Query("data_categorys", "*", "WHERE data_categorys_id='".$DataId."' limit 1");
      $Edit_data_categorys_Row = $Edit_data_categorys -> fetch_array();
      $JqueryVars = 'var actionsids = "'.$Edit_data_categorys_Row["data_categorys_id"].'";';
      $JqueryVars .= 'var actionstype = "'.$Edit_data_categorys_Row["data_categorys_type"].'";';

      $backUrl = $site_url."/panel/balances_codes";

    }
  }else{
    $Get_data_categorys_name = $Sql_Connection -> Select_Query("data_categorys", "*", "WHERE data_categorys_id='".$DataId."' limit 1");
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
    $View_Data = $Sql_Connection -> Select_Query("balances_codes", "*", "WHERE balances_codes_categorys='".$Get_data_categorys_name_Row["data_categorys_name"]."' ORDER BY balances_codes_id DESC LIMIT ".$from.", ".$to);
    $View_Data_Row = $View_Data -> fetch_array();
    $Get_Data_Total = $Sql_Connection->Select_Query("balances_codes", "*", "WHERE balances_codes_categorys='".$Get_data_categorys_name_Row["data_categorys_name"]."'");


    $Get_data_categorys = $Sql_Connection -> Select_Query("data_categorys", "*", "WHERE data_categorys_name='".$Get_data_categorys_name_Row["data_categorys_name"]."' limit 1");
    $Get_data_categorys_Row = $Get_data_categorys -> fetch_array();
    $JqueryVars = 'var actionsids = "'.$Get_data_categorys_Row["data_categorys_id"].'";';
  }
}else{
  header("Location: ".$site_url."/panel/page404");
  exit;
}
?>
