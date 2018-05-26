<?php
date_default_timezone_set('UTC');
if($authorization==""){
	header("Location: ".$site_url."/pages/page404");
	exit;
}
$Get_Authorization = $Sql_Connection -> Select_Query("settings", "*", "WHERE settings_type='uploadfolder' limit 1");
$Get_Authorization_Row = $Get_Authorization->fetch_array();
$SiteAuthorization = sha1(date("d").date("Y/D").date("m/D"));
if($_SERVER['REQUEST_METHOD'] == "POST" && $authorization==$SiteAuthorization)
{
  $Files_upload_accept_extensions = "image/*,.doc, .docx,.mp3, .zip,.rar,.pdf";
  $Images_upload_accept_extensions = "image/*";


  $Imagesextensions = array('gif','GIF','jpeg','JPEG','jpg','JPG','png','PNG');
  $Filessextensions = array("rar","zip","pdf","doc","docx","mp3","avi","flv");

  $GetImageSize = explode(":","800:350:165");

	function DoFolders($uploadfolderName, $Size1, $Size2){
		if(@!is_dir("public/files/")) {
			@mkdir("public/files/");
			$myfile1 = @fopen("public/files/.htaccess", "w");
			$txt1 = "Options -Indexes"."\n"."RewriteRule ^.*\.php$ - [F,L,NC]";
			fwrite($myfile1, $txt1);
			fclose($myfile1);
		}
		if(@!is_dir("public/files/".$uploadfolderName)) {
				@mkdir("public/files/".$uploadfolderName);
				$myfile2 = @fopen("public/files/".$uploadfolderName."/.htaccess", "w");
				$txt2 = "Options -Indexes"."\n"."RewriteRule ^.*\.php$ - [F,L,NC]";
				fwrite($myfile2, $txt2);
				fclose($myfile2);
		}
		if(@!is_dir("public/files/".$uploadfolderName."/".$Size1)) {
			@mkdir("public/files/".$uploadfolderName."/".$Size1);
			$myfile3 = @fopen("public/files/".$uploadfolderName."/".$Size1."/.htaccess", "w");
			$txt3 = "Options -Indexes"."\n"."RewriteRule ^.*\.php$ - [F,L,NC]";
			fwrite($myfile3, $txt3);
			fclose($myfile3);
		}
		if(@!is_dir("public/files/".$uploadfolderName."/".$Size2)) {
			@mkdir("public/files/".$uploadfolderName."/".$Size2);
			$myfile4 = @fopen("public//files/".$uploadfolderName."/".$Size2."/.htaccess", "w");
			$txt4 = "Options -Indexes"."\n"."RewriteRule ^.*\.php$ - [F,L,NC]";
			fwrite($myfile4, $txt4);
			fclose($myfile4);
		}
	}


	if($_FILES['img_file']['tmp_name']==true){
		$error = '';
		$img = '';
		$Get_Folder_Name = $Sql_Connection -> Select_Query("settings", "*", "WHERE settings_type='uploadfolder' ");
		$Get_Folder_Name_Row = $Get_Folder_Name->fetch_array();

		if($Get_Folder_Name->num_rows==0){
			$Sql_Connection -> Insert_Query( "settings",
													array(
													"settings_type"=>"uploadfolder",
													"settings_option"=>"datafolder_1:1"),
													"",
													1);
			$GetFolderName = explode(":","datafolder_1:1");
		}else{
			$GetFolderName = explode(":",$Get_Folder_Name_Row['settings_option']);
		}

		$uploadfolderName = $GetFolderName[0];
		$GetFolderNumber = $GetFolderName[1];

		if(count($GetImageSize)==0 || $GetImageSize==false){
			$Sql_Connection -> Insert_Query( "settings",
													array(
													"settings_type"=>"image_size",
													"settings_option"=>"800:350:165"),
													"",
													1);
			$GetImageSize = explode(":","800:350:165");
		}

		if($Get_Folder_Name->num_rows==0 || $Get_Image_Size->num_rows==0){
			DoFolders($uploadfolderName,$GetImageSize[1],$GetImageSize[2]);
		}

		if($GetFolderNumber>500){
			$DoNewFolder = explode("_",$uploadfolderName);
			$NewFloderNumber = $DoNewFolder[1]+1;
			$uploadfolderName = $DoNewFolder[0]."_".$NewFloderNumber;
			DoFolders($uploadfolderName,$GetImageSize[1],$GetImageSize[2]);
			$Sql_Connection -> Update_Query( "settings",
													array(
													"settings_option"=>$uploadfolderName.":1"),
													"WHERE settings_type='uploadfolder'",
													1);
													$GetFolderNumber=0;
		}

		foreach($_FILES['img_file']['tmp_name'] as $key => $tmp_name )
		{
			$filename = $_FILES["img_file"]["name"][$key];
			$filepath = $_FILES["img_file"]["tmp_name"][$key];
			$fileext = substr(strrchr($filename, '.'), 1);
			$FilesName = $User_ID."_".date("Ymd_his")."_".rand(10,100);
			$FilesName = $FilesName.$key.".".$fileext;
			$SendFileType = "";

			if(in_array($fileext,$Imagesextensions) || in_array($fileext,$Filessextensions)){
				if(in_array($fileext,$Imagesextensions)){
					list($width, $height) = getimagesize($filepath);
					if($width>0){
							DoFolders($uploadfolderName,$GetImageSize[1],$GetImageSize[2]);
							@resize($width,"public/files/".$uploadfolderName."/".$FilesName,$filepath);
							@resize($GetImageSize[1],"public/files/".$uploadfolderName."/".$GetImageSize[1]."/".$FilesName,$filepath);
							@resize($GetImageSize[2],"public/files/".$uploadfolderName."/".$GetImageSize[2]."/".$FilesName,$filepath);
					}
				}else{
					DoFolders($uploadfolderName,$GetImageSize[1],$GetImageSize[2]);
					move_uploaded_file($filepath,"public/files/".$uploadfolderName."/".$FilesName);
				}



				$GetFolderNumber ++;
				$NewNumber = $GetFolderNumber;
				$Sql_Connection -> DoSomething("update settings set settings_option='".$uploadfolderName.":".$NewNumber."' WHERE settings_type='uploadfolder'");


				if($TargetType=="data"){
					$TargetTypeDB = "data_text";
				}else {
					$TargetTypeDB = $TargetType;
				}

				if(in_array($fileext,$Imagesextensions)){
					$File_URL = $style_dir."files/".$uploadfolderName.'/'.$GetImageSize[2].'/'.$FilesName;
				}else{
					$File_URL = $style_dir.'style/upload_files_type/'.$fileext.".png";
				}


				$CheckPhoto = $Sql_Connection -> Select_Query($TargetTypeDB, "*", "WHERE id='".(int)$Sql_Connection->DB_Connection->real_escape_string($ActionsIds)."' LIMIT 1");
				$CheckPhoto_Row = $CheckPhoto->fetch_array();

				if($TargetAction=="main-pic" || $TargetAction=="one"){
					$file_type = "main-pic";
					$file_delete_text = "حذف الصورة";
					if($TargetAction=="one"){ $file_type = "one"; $file_delete_text = "حذف"; }
					$img .= '<div class="col-sm-12">
										<div class="row">
											<div class="col-sm-12">
												<div class="thumbnail">
													<img src="'.$File_URL.'" style="height:200px;" />
												</div>
											</div>
										</div>
										<div class="row" style="padding:0 0 20px 0;">
											<div class="col-sm-12">
												<a class="btn btn-sm btn-borders btn-danger delete_file_pic pull-right" style="width:45%" file-type="'.$file_type.'" target="'.$TargetType.'" id="files/'.$uploadfolderName.'/'.$FilesName.'">'.$file_delete_text.'</a>
											</div>
										</div>
									</div>';
					if($TargetType=="data"){
						if($TargetAction!="one"){
							$Sql_Connection -> Update_Query( $TargetTypeDB,
																	array(
																	"data_optional"=>'files/'.$uploadfolderName.'/'.$FilesName),
																	"WHERE id=".(int)$Sql_Connection->DB_Connection->real_escape_string($ActionsIds),
																		1);
						}else{
							$Sql_Connection -> Update_Query( $TargetTypeDB,
																	array(
																	"data_attachment"=>'files/'.$uploadfolderName.'/'.$FilesName),
																	"WHERE id=".(int)$Sql_Connection->DB_Connection->real_escape_string($ActionsIds),
																		1);
						}
					}else{
						$Sql_Connection -> Update_Query( $TargetTypeDB,
																array(
																$TargetType."_photo"=>'files/'.$uploadfolderName.'/'.$FilesName),
																"WHERE id=".(int)$Sql_Connection->DB_Connection->real_escape_string($ActionsIds),
																	1);
					}

				}else{

					$img .= '<div class="col-sm-4">
										<div class="row">
											<div class="col-sm-12">
												<div class="thumbnail">
													<img src="'.$File_URL.'" style="height:200px;" />
												</div>
											</div>
										</div>
										<div class="row" style="padding:0 0 20px 0;">
											<div class="col-sm-12">
												<a class="btn btn-sm btn-borders btn-danger delete_file_pic pull-right" style="width:45%" file-type="attachments" target="'.$TargetType.'" id="files/'.$uploadfolderName.'/'.$FilesName.'" >حذف</a>
												<a class="btn btn-sm btn-borders btn-default add_to_editor pull-left" style="width:45%" id="files/'.$uploadfolderName.'/'.$FilesName.'" >اضافة</a>
											</div>
										</div>
									</div>';

					$GetPicsArray = "";
					if($TargetType=="data"){
						if($CheckPhoto_Row["data_attachment"]!=""){
							$GetPicsArray = @explode(",",$CheckPhoto_Row["data_attachment"]);
						}
						$GetPicsArray[] = 'files/'.$uploadfolderName.'/'.$FilesName;
						$Sql_Connection -> Update_Query( $TargetTypeDB,
																array(
																"data_attachment"=>$Sql_Connection->DB_Connection->real_escape_string(@implode("{|}",$GetPicsArray))),
																"WHERE id=".(int)$Sql_Connection->DB_Connection->real_escape_string($ActionsIds),
																1);
					}else{
						if($CheckPhoto_Row[$TargetType."_photo"]!=""){
							$GetPicsArray = @explode(",",$CheckPhoto_Row[$TargetType."_photo"]);
						}
						$GetPicsArray[] = 'files/'.$uploadfolderName.'/'.$FilesName;
						$Sql_Connection -> Update_Query( $TargetTypeDB,
																array(
																$TargetType."_photo"=>$Sql_Connection->DB_Connection->real_escape_string(@implode("{|}",$GetPicsArray))),
																"WHERE id=".(int)$Sql_Connection->DB_Connection->real_escape_string($ActionsIds),
																1);
					}
				}

			}else{
				$error = 'هناك مشكلة في رفع الصورة، يرجى التأكد من الامتداد على ان يكون '.implode(",",$Imagesextensions).",".implode(",",$Filessextensions).' وبمساحة لاتزيد عن 4 ميكابايت';			}
		}
		echo (json_encode(array('error' => $error, 'img' => $img)));
	}
}else{
	header("Location: ".$site_url."/pages/page404");
	exit;
}
?>
