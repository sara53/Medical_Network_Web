<?php

			
			
			//**************** UPLOADING IMAGE ***********************************//
			
			function upload_image($table_name, $id){
			
				global $image_error;
			
				if (isset($_POST['uploadImage'])){
					$filename = $_FILES["file"]["name"];
					$file_basename = substr($filename, 0, strripos($filename, '.')); // get file name
					$file_ext = substr($filename, strripos($filename, '.')); // get file extension
					$filesize = $_FILES["file"]["size"];
					$allowed_file_types = array('.png','.jpg','.jpeg', '.bmp');	

					if (in_array($file_ext,$allowed_file_types) && ($filesize < 1000000))
					{	
						// Rename file
						$newfilename = "pic" . $table_name . $id . $file_ext;
						move_uploaded_file($_FILES["file"]["tmp_name"], "images/" . $newfilename);
						include("database_con.php");
						mysqli_query($con, "update $table_name set image = 'images/$newfilename' where id = $id") or die("DIEEEEEE");
						return TRUE;
						
					}
					elseif (empty($file_basename))
					{	
						// file selection error
						$image_error = "Please select image";
					} 
					elseif ($filesize > 1000000)
					{	
						// file size error
						$image_error  = "image size is too large.";
					}
					else
					{
						// file type error
						$image_error = "Only the following types are allowed <br/>";
						foreach($allowed_file_types as $fileType){
							$image_error .= $fileType . '  ';
						}
						unlink($_FILES["file"]["tmp_name"]);
					}
				}
				return FALSE;
			}
			
			//************************ END UPLOADING IMAGE ***********************************//


?>