<?
if((in_array($TargetFrom,$Users_Permissions_Array) || $Get_UserData_Row["users_main"]=="1") && in_array($TargetFrom,$Domains_Programs_Array)){

  if($DataId==""){
    $Get_data_text = $Sql_Connection -> Select_Query("data_text", "*", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND data_type='".$TargetFrom."' ORDER BY data_id DESC limit 20");
    $Get_data_text_Row = $Get_data_text -> fetch_array();

    $Get_Users_Status = $Sql_Connection -> Select_Query("data_text", "*", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND data_type='".$TargetFrom."' AND data_status='0' LIMIT 1");
    $Get_Users_Status_Row = $Get_Users_Status -> fetch_array();
    if($Get_Users_Status -> num_rows>0){
      $JqueryVars = "ShowError('هنالك عناصر غير مفعلة مضللة باللون الاحمر، يرجى ملئ الحقول المطلوبة للمحتوى');";
    }

  }else{
    $Edit_data_text = $Sql_Connection -> Select_Query("data_text", "*", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND data_id='".$DataId."' limit 1");
    $Edit_data_text_Row = $Edit_data_text -> fetch_array();
    $JqueryVars .= 'var actionsids = "'.$Edit_data_text_Row["data_id"].'";';
    if($AddType=="html"){
      $JqueryVars .= 'var uploadtype = "just_attachments";';
    }
    if($AddType=="html"){
      $JqueryVars .= 'var uploadtype = "just_attachments";';
    }
    if($TargetFrom=="videos" || $TargetFrom=="sounds"){
      $JqueryVars .= 'var uploadtype = "'.$TargetFrom.'";';
    }


    if($TargetFrom=="products"){
      $products_data_json = json_decode($Edit_data_text_Row["data_json"]);
    }


    $ActiveUploadScript = true;

    if($TargetFrom=="cv"){
      $data_body = $Edit_data_text_Row["data_details"];
      $data_fields = explode("{:-:}",$Edit_data_text_Row["data_body"]);
    }

    if($TargetFrom=="library"){
      $data_fields = explode("{:-:}",$Edit_data_text_Row["text_details"]);
    }


    if($Edit_data_text_Row["data_optional"]!=""){
      $DoPicsArray = @explode("{|}",$Edit_data_text_Row["data_optional"]);
      foreach ($DoPicsArray as $value) {
        if($value!=""){
          $MainImg .= '<div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="thumbnail">
                          <img src="'.GetPicWithSize($value,"350",$User_Domain).'" style="height:200px;" />
                        </div>
                      </div>
                    </div>
                    <div class="row" style="padding:0 0 20px 0;">
                      <div class="col-sm-12">
                        <input type="submit" class="btn btn-sm btn-borders btn-danger delete_file_pic pull-right" style="width:45%" file-type="main-pic" target="data" id="'.$value.'" value="حذف الصورة">
                      </div>
                    </div>
                  </div>';

        }
      }
    }
    if($MainImg!=""){ $HideImages=" hidden";}

    if($Edit_data_text_Row["data_attachment"]!=""){
      $DoPicsArray = @explode("{|}",$Edit_data_text_Row["data_attachment"]);
      foreach ($DoPicsArray as $value) {
        if($value!=""){
          $fileext = substr(strrchr($value, '.'), 1);
          if(in_array($fileext,$Imagesextensions)){
  					$File_URL = GetPicWithSize($value,"350",$User_Domain);
  				}else{
  					$File_URL = $style_dir.'style/upload_files_type/'.$fileext.".png";
  				}
          switch ($TargetFrom) {
    				case "cv":
    					$FileSizeCol = "12";
              $file_type = "one";
    					break;
    				case "library":
              $FileSizeCol = "12";
              $file_type = "one";
    					break;
    				default:
    					$FileSizeCol = "4";
              $file_type="attachments";
              $add_to_editor_code = '<input type="submit" class="btn btn-sm btn-borders btn-default add_to_editor pull-left" style="width:45%" target="data"  id="'.$value.'" file-type="attachments" value="اضافة">';
    			}
          $attachmentsIimg[] .= '<div class="col-sm-'.$FileSizeCol.'">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="thumbnail">
                          <img src="'.$File_URL.'"  style="height:200px;" />
                        </div>
                      </div>
                    </div>
                    <div class="row" style="padding:0 0 20px 0;">
                      <div class="col-sm-12">
                        <input type="submit" class="btn btn-sm btn-borders btn-danger delete_file_pic pull-right" style="width:45%" target="data" file-type="'.$file_type.'" id="'.$value.'" value="حذف">
                        '.$add_to_editor_code.'
                      </div>
                    </div>
                  </div>';
        }
      }
    }

    if(count($attachmentsIimg)!=0){
      $attachmentsIimg = implode("",$attachmentsIimg);
      $HideFiles=" hidden";
    }



    $Get_data_categorys = $Sql_Connection -> Select_Query("data_categorys", "*", "WHERE lang='".$lang."' AND section='".$SectionsVal."' AND data_categorys_type='".$TargetFrom."' ORDER BY data_categorys_name ASC");
    $Get_data_categorys_Row = $Get_data_categorys -> fetch_array();
    do{
      $All_Data_Categorys_Array [] = $Get_data_categorys_Row["data_categorys_name"];
    } while ($Get_data_categorys_Row = $Get_data_categorys -> fetch_array());
  }

  if($AddType!="html" && $TargetFrom!="videos" && $TargetFrom!="sounds"){
    if($lang=="en"){
      $tinymceCode ="<script>
                    tinymce.init({
                      selector: 'textarea',
                      height: 400,
                      plugins: [
                        'advlist autolink lists link image charmap',
                        'searchreplace visualblocks code',
                        'insertdatetime media table contextmenu paste code',
                        'directionality'
                      ],
                      toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image | ltr rtl',
                      content_css: [
                        'js/tinymce/codepen.min.css'
                      ]
                      });
                    </script>";
    }else{
      $tinymceCode ="<script>
                    tinymce.init({
                      selector: 'textarea',
                      height: 400,
                      plugins: [
                        'advlist autolink lists link image charmap',
                        'searchreplace visualblocks code',
                        'insertdatetime media table contextmenu paste code',
                        'directionality'
                      ],
                      directionality : 'rtl',
                      language_url : '".$style_dir."/style/assets/js/ar.js' ,
                      toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image | ltr rtl',
                      content_css: [
                        'js/tinymce/codepen.min.css'
                      ]
                      });
                    </script>";
    }
  }




    switch ($TargetFrom) {
      case "news":
        $type_string = "الاخبار";
        $type_string_one = "خبر";
        $title_string = "لوحة التحكم بالمحتوى الاخباري ";
        $total_data_string = "مجموع الاخبار";
        $nodata = "لايوجد اخبار، بالامكان اضافة الاخبار من خلال زر اضافة خبر جديد";
        $SaveClassName = "SaveDataText";
        break;
      case "pages":
        $type_string = "الصفحات";
        $type_string_one = "صفحة";
        $title_string = "لوحة التحكم بمحتوى الصفحات ";
        $total_data_string = "مجموع الصفحات";
        $nodata = "لايوجد صفحات، بالامكان اضافة الصفحات من خلال زر اضافة صفخة جديدة";
        $SaveClassName = "SaveDataText";
        break;
      case "videos":
        $type_string = "الفيديو";
        $type_string_one = "فيديو";
        $type_string_link = "YouTube";
        $title_string = "لوحة التحكم بمحتوى الفيديو ";
        $total_data_string = "مجموع مقاطع الفديو";
        $nodata = "لايوجد فيديو، بالامكان اضافة الفيديو من خلال زر اضافة فيدو جديد";
        $SaveClassName = "SaveMedia";
        break;
      case "sounds":
        $type_string = "الصوتيات";
        $type_string_one = "صوت";
        $type_string_link = "SoundCloud";
        $title_string = "لوحة التحكم بمحتوى الصوتيات ";
        $total_data_string = "مجموع المقاطع الصوتية";
        $nodata = "لايوجد صوتيات، بالامكان اضافة صوتيات من خلال زر اضافة صوت جديد";
        $SaveClassName = "SaveMedia";
        break;
      case "products":
        $type_string = "المنتجات";
        $type_string_one = "منتج";
        $title_string = "لوحة التحكم بمحتوى المنتجات ";
        $total_data_string = "مجموع المنتجات";
        $nodata = "لايوجد منتجات، بالامكان اضافة المنتجات من خلال زر اضافة منتج جديد";
        $SaveClassName = "SaveProducts";
        break;
      case "library":
        $type_string = "الكتب";
        $type_string_one = "كتاب";
        $title_string = "لوحة التحكم بمحتوى المكتبة ";
        $total_data_string = "مجموع الكتب";
        $nodata = "لايوجد كتب، بالامكان اضافة الكتب من خلال زر اضافة كتاب جديد";
        $SaveClassName = "SaveLibrary";
        break;
      case "cv":
        $type_string = "السير الذاتية";
        $type_string_one = "سيرة ذاتية";
        $title_string = "لوحة التحكم بمحتوى السيرة الذاتية ";
        $total_data_string = "مجموع السير الذاتية";
        $nodata = "لايوجد سير ذاتية، بالامكان اضافة السير الذاتية من خلال زر اضافة سيرة ذاتية";
        $SaveClassName = "SaveCV";
        break;
      case "blocks":
        $type_string = "ادارة القوائم";
        $type_string_one = "قائمة";
        $title_string = "لوحة التحكم بادارة القوائم ";
        $nodata = "لايوجد قوائم. بالامكان اضافة القوائم من خلال زر اضافة قائمة";
        $SaveClassName = "SaveBlocks";
        break;
      case "codes":
        $type_string = "محتوى خاص";
        $type_string_one = "محتوى خاص";
        $title_string = "لوحة التحكم بادارة الكودات المحتوى الخاص";
        $nodata = "لايوجد محتوى خاص، بالامكان اضافة محتويات خاصة من خلال زر اضافة محتوى خاص";
        $SaveClassName = "SaveCodes";
        break;
      default:
        header("Location: ".$site_url."/panel/page404");
        exit;
    }







    $backUrl = $site_url."/panel/data_view/".$TargetFrom;

}else{
  header("Location: ".$site_url."/panel/page404");
  exit;
}
?>
