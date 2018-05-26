<?php
if((in_array($TargetFrom,$Users_Permissions_Array) || $Get_UserData_Row["users_main"]=="1") && in_array($TargetFrom,$Domains_Programs_Array)){
  if($DataId==""){
    if($pagenum<=1){
      $from = 0;
      $to = 20;
    }else{
      $from = ($pagenum-1) * 20;
      $to = ($pagenum * 20);
    }
    $Get_Customers = $Sql_Connection -> Select_Query("customers", "*", "ORDER BY customers_id DESC LIMIT ".$from.", ".$to);
    $Get_Customers_Row = $Get_Customers -> fetch_array();
    $Get_Customers_Total = $Sql_Connection->Select_Query("customers", "*", "");
  }else{
    $Edit_data_Customers = $Sql_Connection -> Select_Query("customers", "`customers_id`, `customers_fullname`, `customers_username`, `customers_password`, `customers_email`, `customers_phone`, `customers_address`, `customers_city`, `customers_status`
    ,(SELECT SUM(balances_codes_amount) FROM balances_codes WHERE balances_codes.balances_codes_customers=customers.customers_username) as balances_codes_amounts",
    "WHERE customers_id='".$DataId."' limit 1");
    $Edit_data_Customers_Row = $Edit_data_Customers -> fetch_array();
    $JqueryVars = 'var actionsids = "'.$Edit_data_Customers_Row["customers_id"].'";';

    $Get_Balances_Codes = $Sql_Connection -> Select_Query("balances_codes", "*", "
                                                                  WHERE balances_codes_customers='".$Edit_data_Customers_Row["customers_username"]."'
                                                                  ORDER BY balances_codes_id DESC");
    $Get_Balances_Codes_Row = $Get_Balances_Codes -> fetch_array();
  }

  $backUrl = $site_url."/panel/customers/".$pagenum;

}else{
  header("Location: ".$site_url."/panel/page404");
  exit;
}
?>
