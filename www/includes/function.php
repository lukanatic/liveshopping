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
