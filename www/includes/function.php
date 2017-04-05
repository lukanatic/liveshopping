<?php
	function doAdminRegister($conn, $input){
				//hash the password
	 		$hash = password_hash($input['password'], PASSWORD_BCRYPT);

	 		//INSERT DATA INTO TABLE
	 		$stmt = $conn->prepare("INSERT INTO admin(fname,lname,email,hash) VALUES (:fn,:ln,:e,:h)");

		 		$stmt->execute([':fn' => $input['fname'],
	 		 			':ln' => $input['lname'],
	 		 			':e' => $input['email'],
	 		 			':h' => $hash]);
	}


	function doAdminLogin($conn, $input){
	 		//INSERT DATA INTO TABLE
	 		$stmt = $conn->prepare("SELECT * FROM  admin WHERE email = :e  ");

	 		//bind params

	 		$stmt->bindParam(":e", $input['email']);
	 		$stmt->execute();
	 		$count = $stmt->rowCount();


	 		if($count == 1){
	 		
	 		$result = $stmt->fetch(PDO::FETCH_ASSOC);

	 		if(password_verify($input['password'],$result['hash'])){	 		

	 			header("Location:dashboard.php");
			}else{

				$login_error = "Invalid Username and/or Password";
				header("Location:login.php?login_error=$login_error");

				}														


	 		}

		}
function fileUpload($files,$error,$pic){
			 define('MAX_FILE_SIZE', "2097152");

    #allowed extentions

    $ext = ["image/jpg","image/jpeg","image/png"];

     if(empty($files[$pic]['name']))
                  {
            $error[$pic] = "Please choose a file";
              }

                   if($files[$pic]['size'] > MAX_FILE_SIZE)
                  {
                         $error[$pic] = "File exceeds maximum sixe. Maximum size:" . MAX_FILE_SIZE;
                  }

		  #check file type/extention
       if(!in_array($files[$pic]['type'], $ext))
                  {

                        $error[$pic] = "Invalid file type";
                  }


	    #generate random number to append
                  $rnd = rand(000000000000, 999999999999);

    	# strip filename for spaces
                  $strip_name = str_replace("", "_",$_FILES['pic']['name'] );
                  $filename = $rnd.$strip_name;
                  $destination = 'uploads/' .$filename;


        if(!move_uploaded_file($files[$pic]['tmp_name'], $destination))
                  {
                    $error[$pic] = "file upload failed";
                  }

		}
	 	
function doesEmailExist($conn, $email){
			$result = false;

			$stmt = $dbconn->prepare("SELECT email FROM admin WHERE  ");

			#bind parameter
			$stmt->bindParam(":e", $email);
			$stmt->execute();

			#get number of rolls returned
			$count = $stmt->rowCount();

			if($count > 0){
				$result = true;
			}

			return $result;	
		}


	function displayError($show,$input){

			if(isset($show[$input])){


				echo '<span class="err">'.$show[$input]. '</span>' ;
				return true;
        }
	}
	function showCategory($conn){
				$stmt = $conn->prepare("SELECT * FROM category");
				 $stmt->execute();
				 $result = "";

	 		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	 			$category_id = $row['category_id'];
	 			$category_name = $row['category_name'];
	 			
	 			 $result .= "<tr>";
	 			  $result .= "<td>" .$category_id.  "</td>";
	 			   $result .= "<td>" .$category_name.  "</td>";

	 			 $result .=   "<td><a href='category.php?action=edit&category_id=$category_id&category_name=$category_name'>edit</a></td>";
					$result .=	 "<td><a href='category.php?act=delete&category_id=$category_id'>delete</a></td> ";
	 			     $result .= "</tr>";
	 		}
	  return $result;

	}

function editCategory($conn,$input){

		$stmt = $conn->prepare("UPDATE  category SET category_name = :cn WHERE category_id = :i ");

		$stmt->bindParam(":cn", $input['category_name']);
		$stmt->bindParam(":i", $input['category_id']);
		 $stmt->execute();
		 	$success = "category edited!";
  		header("Location:category.php?success=$success");

	}

	function deleteCat($conn, $input){


		$stmt = $conn->prepare("DELETE FROM  category WHERE category_id = :i ");

		$stmt->bindParam(":i", $input);
		 $stmt->execute();
		 $success = "category deleted!";
  		header("Location:category.php?success=$success");

}
	

	function getCategory($conn)
	{
			$stmt = $conn->prepare("SELECT * FROM category ");
				 $stmt->execute();
				 $result = "";

	 		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	 			$category_id = $row['category_id'];
	 			$category_name = $row['category_name'];

	 			$result .= "<option value=$category_id>"  .$category_name ."</option>";

	 		}
	 		return $result;
	}



function productUpload($conn,$files,$error,$pic,$input){


			 define('MAX_FILE_SIZE', "2097152");

    #allowed extentions

    $ext = ["image/jpg","image/jpeg","image/png"];

     if(empty($files[$pic]['name']))
                  {
            $error[$pic] = "Please choose a file";

                  }

                   if($files[$pic]['size'] > MAX_FILE_SIZE)
                  {
                         $error[$pic] = "File exceeds maximum sixe. Maximum size:" . MAX_FILE_SIZE;
                  }

  #check file type/extention
       if(!in_array($files[$pic]['type'], $ext))
                  {
                        $error[$pic] = "Invalid file type";
                  }


    #generate random number to append
                  $rnd = rand(000000000000, 999999999999);

    # strip filename for spaces
                  $strip_name = str_replace("", "_",$_FILES['pic']['name'] );
                  $filename = $rnd.$strip_name;
                  $destination = 'uploads/' .$filename;


        if(!move_uploaded_file($files[$pic]['tmp_name'], $destination))
                  {

                    $error[$pic] = "file upload failed";
                  }

	 			 if(empty($error))
                 {


                  $stmt = $conn->prepare("INSERT INTO book (title,author,category_id,price,year,isbn,image_path) 
                  	VALUES (:ti,:au,:ca,:pr,:ye,:i,:de)");

	 		//bind params

	 			$data = [
	 					':ti' => $input['title'],
	 					':au' => $input['author'],
	 					':ca' => $input['cat'],
	 					':pr' => $input['price'],
	 					':ye' => $input['year'],
	 					':i' => $input['isbn'],
	 					':de' => $destination,

	 					];
	 			$stmt->execute($data);

                  $success = "Product Added";
                  header("Location:add_products.php?success=$success");

                 }

             else
                 
                {
                    
                    foreach ($error as $err) 
                     {


                 echo $err. "</br>";
                
                    }

               }

		}
	 
	function viewProducts($conn){
				$stmt = $conn->prepare("SELECT * FROM book ");
				 $stmt->execute();
				 $result = "";

	 		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	 			$book_id = $row['book_id'];
	 			$title = $row['title'];
	 			$author = $row['author'];
	 			$category_id = $row['category_id'];
	 			$price = $row['price'];
	 			$year = $row['year'];
	 			$isbn = $row['isbn'];
	 			$image_path = $row['image_path'];
	 			
	 			 $result .= "<tr>";
	 			 $result .= "<td>" .$title.  "</td>";
	 			 $result .= "<td>" .$author.  "</td>";
	 			 $result .= "<td>" .$price.  "</td>";
	 			 $result .= "<td>" .$year.  "</td>";
	 			 $result .= "<td>" .$isbn.  "</td>";
	 			 $result .= "<td><img src='$image_path'  height='100px' width='100px' /></td>";
	 			 $result .=   "<td><a href='edit_products.php?book_id=$book_id'>edit</a></td>";
					$result .=	 "<td><a href='product.php?delete=$book_id'>delete</a></td> ";
	 			     $result .= "</tr>";

	 		}
	  return $result;

	}	

	function seeProducts($conn){
				$stmt = $conn->prepare("SELECT * FROM book ");
				 $stmt->execute();
				 $result = "";

	 		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	 			$book_id = $row['book_id'];
	 			$title = $row['title'];
	 			$author = $row['author'];
	 			$year = $row['year'];
	 			
	 			 $result .= "<tr>";
	 			 $result .= "<td>" .$title.  "</td>";
	 			 $result .= "<td>" .$author.  "</td>";
	 			 $result .= "<td>" .$year.  "</td>";
	 			 $result .=   "<td><a href='edit_products.php?book_id=$book_id'>edit</a></td>";
				$result .=	 "<td><a href='product.php?delete=$book_id'>delete</a></td> ";
	 			 $result .= "</tr>";

	 		}
	  return $result;

	}	
	 	
	 function deleteProduct($conn, $input){


		$stmt = $conn->prepare("DELETE FROM  book WHERE book_id = :i ");

		$stmt->bindParam(":i", $input);
		 $stmt->execute();
		 $success = "Product deleted!";
  		header("Location:product.php?success=$success");

}

function editProduct($conn,$files,$error,$pic,$input){

			 define('MAX_FILE_SIZE', "2097152");

    #allowed extentions

    $ext = ["image/jpg","image/jpeg","image/png"];

     if(empty($files[$pic]['name']))
                  {
            $error[$pic] = "Please choose a file";

                  }
                   if($files[$pic]['size'] > MAX_FILE_SIZE)
                  {
                         $error[$pic] = "File exceeds maximum sixe. Maximum size:" . MAX_FILE_SIZE;
                  }

  #check file type/extention
       if(!in_array($files[$pic]['type'], $ext))
                  {

                        $error[$pic] = "Invalid file type";

                  }


    #generate random number to append
                  $rnd = rand(000000000000, 999999999999);

    # strip filename for spaces
                  $strip_name = str_replace("", "_",$_FILES['pic']['name'] );
                  $filename = $rnd.$strip_name;
                  $destination = 'uploads/' .$filename;


        if(!move_uploaded_file($files[$pic]['tmp_name'], $destination))
                  {

                    $error[$pic] = "file upload failed";
                  }
	 			 else
                 {

                  $stmt = $conn->prepare("UPDATE book  
                  	SET title =:t,
                  		author = :a,
                  		category_id = :c,
                  		price = :p,
                  		year = :y,
                  		isbn =:i,
                  		image_path =:im 

                  	WHERE book_id = :id");

	 		//bind params

	 			$data = [
	 					':t' => $input['title'],
	 					':a' => $input['author'],
	 					':c' => $input['cat'],
	 					':p' => $input['price'],
	 					':y' => $input['year'],
	 					':i' => $input['isbn'],
	 					':id' => $input['book_id'],
	 					':im' => $destination,

	 					];
	 			if($stmt->execute($data)){;

                  $success = "Product Edited";
                  header("Location:product.php?success=$success");

                 }
             else
                {
                 $success = "Product Edit failed";
                  header("Location:product.php?success=$success");
               }
		}

}
