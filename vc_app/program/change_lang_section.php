<?
if($_POST){
  require_once ("vc_app/program/SQL.php");
  if($_POST["change_section"]!=""){
    if(!in_array($_POST["change_section"],$All_Sections_Key_Array)){
      header("Location: ".$site_url."/pages/page404"); exit;
    }else{
      if($Get_UserData_Row["section"]!="all"){
        if($_POST["change_section"] != $Get_UserData_Row["section"]){
          header("Location: ".$site_url."/pages/page404");
          exit;
        }
      }
      $_SESSION["panel_section"] = $_POST["change_section"];
      $Sql_Connection_User -> Update_Query( "users",
                                array(
                                "users_panel_section"=>$_SESSION["panel_section"]),
                                "WHERE users_id=".$User_ID,
                                0,"");
    }
    echo "OK";
  }

  if($_POST["change_lang"]!=""){
    require_once ("vc_app/config.php");
    switch ($_POST["change_lang"]) {
        case "ar":
            $_SESSION["panel_lang"] = "ar";
            break;
        case "en":
            $_SESSION["panel_lang"] = "en";
            break;
        case "kr":
            $_SESSION["panel_lang"] = "kr";
            break;
        default:
            $_SESSION["panel_lang"] = "ar";
            break;
    }
    $Sql_Connection_User -> Update_Query( "users",
                              array(
                              "users_panel_lang"=>$_SESSION["panel_lang"]),
                              "WHERE users_id=".$User_ID,
                              0,"");
    echo "OK";
  }

}
?>
