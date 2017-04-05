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

