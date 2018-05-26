<?
$TotalVisitars = $Sql_Connection -> Select_Query("data_text", "sum(data_hits)", "WHERE lang='".$lang."' AND section='".$SectionsVal."'");
$TotalVisitars_Row = $TotalVisitars -> fetch_array();

$Max_Visit_Was = $Sql_Connection -> Select_Query("data_text", "lang,section,data_title", "WHERE lang='".$lang."' AND section='".$SectionsVal."' ORDER BY data_hits DESC LIMIT 1");
$Max_Visit_Was_Row = $Max_Visit_Was -> fetch_array();

$Get_Total_News = $Sql_Connection -> Select_Query("data_text", "COUNT(*)", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND data_type='news'");
$Get_Total_News_Row = $Get_Total_News -> fetch_array();

$Get_Total_Pages = $Sql_Connection -> Select_Query("data_text", "COUNT(*)", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND data_type='pages'");
$Get_Total_Pages_Row = $Get_Total_Pages -> fetch_array();

$Get_Total_Products = $Sql_Connection -> Select_Query("data_text", "COUNT(*)", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND data_type='products'");
$Get_Total_Products_Row = $Get_Total_Products -> fetch_array();

?>
