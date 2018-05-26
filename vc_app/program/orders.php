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
    $Get_Orders = $Sql_Connection -> Select_Query("orders", "*", "
                                                                  LEFT Join data_text
                                                                    on data_text.data_id = orders.orders_products_id AND orders_type='منتج'
                                                                  Join customers
                                                                    on customers.customers_username = orders.orders_customers
                                                                  ORDER BY orders_id DESC LIMIT ".$from.", ".$to);
    $Get_Orders_Row = $Get_Orders -> fetch_array();
    $Get_Orders_Total = $Sql_Connection->Select_Query("orders", "*", "");



  }else{


    $Edit_data_Orders = $Sql_Connection -> Select_Query("orders", "*", "
                                                                        LEFT Join data_text
                                                                          on data_text.data_id = orders.orders_products_id
                                                                        Join customers
                                                                          on customers.customers_username = orders.orders_customers
                                                                        WHERE orders.orders_id='".$DataId."' limit 1");
    $Edit_data_Orders_Row = $Edit_data_Orders -> fetch_array();
    $JqueryVars = 'var actionsids = "'.$Edit_data_Orders_Row["orders_id"].'";';

    $Sql_Connection -> Update_Query( "orders",
                        array(
                          "messages_for_user"=>"1"),
                          "WHERE orders_id=".$Edit_data_Orders_Row["orders_id"],
                          0,"");


    $Check_Total_balances = $Sql_Connection -> Select_Query("balances_codes", "SUM(balances_codes_amount)", "WHERE balances_codes_customers='".$Edit_data_Orders_Row["orders_customers"]."'");
    $Check_Total_balances_Row = $Check_Total_balances -> fetch_array();
  }

  $backUrl = $site_url."/panel/orders/".$pagenum;

}else{
  header("Location: ".$site_url."/panel/page404");
  exit;
}
?>
