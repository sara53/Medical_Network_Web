<?php


	include("database_con.php");
    $db_database_name = $db_name;
	
	// All incoming requests must have the property 'action'
	if(!isset($_POST['action'])){
		echo '"action" not defined';
		exit();
	}
	$action = $_POST['action'];
	

	// default response = REJECT
	$final_result_arr = array(0);
	
	
	// determining table to perform operations on
	if(isset($_POST['table'])){
		$table = $_POST['table'];
		######### Table: doctor ############
		if($table == "doctor"){
			$avail_cols = array('id', 	'name', 'phone', 	'address', 'hospital_id', 	'specialty_id', 'email', 	'password', 	'image', 'about');
			$is_varchar = array(0,		1,		1,			1,			0,				0,				1,			1,				1,			1);		
		}
		######### Table: hospital ############
		if($table == "hospital"){
			$avail_cols = array('id', 	'name', 'city', 	'detailed_address', 	'email', 	'password', 'image',	'about');
			$is_varchar = array(0,		1,		1,			1,						1,			1,				1,		1);
		}
		######### Table: medical_records ############
		if($table == "medical_records"){
			$avail_cols = array('id', 	'patient_id', 	'date_and_time', 	'overall_condition', 	'details', 	'doctor_id');
			$is_varchar = array(0,		0,				1,					1,						1,			0);
		}
		######### Table: patient ############
		if($table == "patient"){
			$avail_cols = array('id', 	'name', 'phone', 	'address',  'email', 	'password', 	'image', 'about');
			$is_varchar = array(0,		1,		1,			1,			1,			1,				1,			1);		
		}
		######### Table: patient_doctor ############
		if($table == "patient_doctor"){
			$avail_cols = array('patient_id', 	'doctor_id');
			$is_varchar = array(0,				0);
		}
		######### Table: specialties ############
		if($table == "specialties"){
			$avail_cols = array('id', 	'name', 	'hospital_id');
			$is_varchar = array(0,		1,			0);
		}
		
	}// end determining table to perform operations on
	
	
	
	
	// Check table exists
	if(isset($avail_cols)){
	
		
		//_____________________ Action = select _______________________
		if($action == 'select'){	
			
			
			// get string of col names
			$cols_string = "";
			$seperator = "";
			foreach($avail_cols as $col){
				if(isset($_POST["select_" . $col])){
					$cols_string .= " $seperator $col";
					$seperator = ",";
				}
			}
			unset($col);
			
			// use * if no cols specified
			if($cols_string == "")$cols_string = "*";
			
			// get string of where and where condition
			$where_condition_string = "";
			$seperator = "";
			$counter = 0;
			foreach($avail_cols as $col){
				if(isset($_POST["where_" . $col])){
					$quote = "'";
					if(!$is_varchar[$counter])$quote = "";
					$col_val = $quote . $_POST["where_" . $col] . $quote;
					$where_condition_string .= " $seperator $col  =  $col_val";
					$seperator = 'AND';
				}
				$counter++;
			}
			unset($col);
			$where_string = "where";
			
			// don't use where if no condition specified
			if($where_condition_string == "")$where_string = "";
			$where_string .=  $where_condition_string . ";";
			$query_string = "select $cols_string from $table $where_string";
			
			// now we have our query
			$final_result_arr = array(1);
			$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
			$result = mysqli_query($con, $query_string);
			if($result){
				$counter = 0;
				while($row = mysqli_fetch_assoc($result)){
					$final_result_arr[] = $row ;
					$counter++;
				}
				if($counter == 0)
					$final_result_arr[0] = 0;
				
			}
		
		
		}// end action = select
		
	
	
	
		//_____________________ Action = insert _______________________
		if($action == 'insert'){	
			
			$cols_string = "";
			$vals_string = "";
			$seperator = "";
			$counter = 0;
			
			foreach($avail_cols as $col){
				if(isset($_POST[$col])){
					$quote = "'";
					if(!$is_varchar[$counter])$quote = "";
					$col_val = $quote . $_POST[$col] . $quote;
					$cols_string .= " $seperator $col";
					$vals_string .= " $seperator $col_val";
					$seperator = ',';
				}
				$counter++;
			}
			unset($col);
			
			if($cols_string != "" && $vals_string != ""){
				$query_string = "insert into $table ( $cols_string ) values ( $vals_string );";
				$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
				if(mysqli_query($con, $query_string)){
					$final_result_arr = array(1);
				}
				
			}
			
		}// end action = insert
		
		
		
		//_____________________ Action = update _______________________
		if($action == 'update'){	
		
			$name_equal_value_pairs = "";
			$where_string = "where";
			$where_condition = "";
			
			$counter = 0;
			$seperator = "";
			foreach($avail_cols as $col){
				if(isset($_POST["set_" . $col])){
					$quote = "'";
					if(!$is_varchar[$counter])$quote = "";
					$col_val = $quote . $_POST["set_" . $col] . $quote;
					$name_equal_value_pairs .= " $seperator $col = $col_val";
					$seperator = ',';
				}
				$counter++;
			}
			unset($col);
			
			$counter = 0;
			$seperator = "";
			foreach($avail_cols as $col){
				if(isset($_POST["where_" . $col])){
					$quote = "'";
					if(!$is_varchar[$counter])$quote = "";
					$col_val = $quote . $_POST["where_" . $col] . $quote;
					$where_condition .= " $seperator $col = $col_val";
					$seperator = 'AND';
				}
				$counter++;
			}
			unset($col);
			
			if($where_condition == "")
				$where_string = "";
			else
				$where_string .= " $where_condition";
			
			
			if($name_equal_value_pairs != ""){
				$query_string = "update $table set $name_equal_value_pairs $where_string ;";
				$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
				if(mysqli_query($con, $query_string)){
					$final_result_arr = array(1);
				}
			}
		
		}// end action = update
		
		
		
		//_____________________ Action = delete _______________________
		if($action == 'delete'){	
			
			$where_string = "where";
			$where_condition = "";
			
			$counter = 0;
			$seperator = "";
			foreach($avail_cols as $col){
				if(isset($_POST["where_" . $col])){
					$quote = "'";
					if(!$is_varchar[$counter])$quote = "";
					$col_val = $quote . $_POST["where_" . $col] . $quote;
					$where_condition .= " $seperator $col = $col_val";
					$seperator = 'AND';
				}
				$counter++;
			}
			unset($col);
			
			if($where_condition == "")
				$where_string = "";
			else
				$where_string .= " $where_condition";
		
			$query_string = "delete from $table $where_string ;";
			$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
			if(mysqli_query($con, $query_string)){
				$final_result_arr = array(1);
			}
		
		}// end action = delete
	
	
	
	}// end check table exists
	
	
	
	
	//-------------------------- Special Cases ------------------------------ //
	// show hospitals
	if($action == 'show hospitals'){
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		$city = $_POST['city'];
		$result = mysqli_query($con, "select * from hospital where city like '%$city%' order by city ASC");
		$counter = 0;
		$final_result_arr = array(1);
		while($row = mysqli_fetch_assoc($result)){
			$counter++;
			$final_result_arr[] =$row;
		}
		if($counter == 0){
			$final_result_arr = array(0);
		}
	}
	
	// get cities filter
	if($action == 'get cities filter'){
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		$final_result_arr = array(1);
		$first_element = array('city' => '<No Filter>');
		$final_result_arr[] = $first_element;
		$result = mysqli_query($con, "select city from hospital group by city order by city ASC");
		while($row = mysqli_fetch_assoc($result)){
			$final_result_arr[] = $row;
		}
		
	}
	
	// show doctor
	if($action == 'show doctor'){
		$id = $_POST['doctor_id'];
		$final_result_arr = array(1);
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		$query_string = "SELECT id, name, phone, address, hospital_id, specialty_id, email, password, image FROM `doctor` WHERE id = $id;";
		$result = mysqli_query($con, $query_string);
		if($result){
			while($row = mysqli_fetch_assoc($result)){
				$hos_id = $row['hospital_id'];
				if($hos_id == -1){
					$hos_name = "none";
				} else {
					$result2 =  mysqli_query($con, "select name from hospital where id = $hos_id;");
					$hos_row = mysqli_fetch_assoc($result2);
					$hos_name = $hos_row['name'];
				}
				
				$spec_id = $row['specialty_id'];
				if($spec_id == -1){
					$spec_name = "none";
				} else {
					$result2 =  mysqli_query($con, "select name from specialties where id = $spec_id;");
					$spec_row = mysqli_fetch_assoc($result2);
					$spec_name = $spec_row['name'];
				}
				$row['hospital.name'] = $hos_name;
				$row['specialties.name'] = $spec_name;
				$final_result_arr[] = $row ;
			}
		}
	}
	
	
	// show doctors of patient
	if($action == 'show doctors of patient'){
		$id = $_POST['patient_id'];
		$final_result_arr = array(1);
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		$query_string = "SELECT id, name, phone, address, hospital_id, specialty_id, email, password, image FROM `doctor` WHERE id in (SELECT patient_doctor.doctor_id from patient_doctor WHERE patient_doctor.patient_id = $id);";
		$result = mysqli_query($con, $query_string);
		if($result){
			while($row = mysqli_fetch_assoc($result)){
				$hos_id = $row['hospital_id'];
				if($hos_id == -1){
					$hos_name = "none";
				} else {
					$result2 =  mysqli_query($con, "select name from hospital where id = $hos_id;");
					$hos_row = mysqli_fetch_assoc($result2);
					$hos_name = $hos_row['name'];
				}
				
				$spec_id = $row['specialty_id'];
				if($spec_id == -1){
					$spec_name = "none";
				} else {
					$result2 =  mysqli_query($con, "select name from specialties where id = $spec_id;");
					$spec_row = mysqli_fetch_assoc($result2);
					$spec_name = $spec_row['name'];
				}
				$row['hospital.name'] = $hos_name;
				$row['specialties.name'] = $spec_name;
				$final_result_arr[] = $row ;
			}
		}
	}
	
	// show patients of doctor
	if($action == 'show patients of doctor'){
		$id = $_POST['doctor_id'];
		$final_result_arr = array(1);
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		$query_string = "SELECT patient.id, patient.name, patient.phone, patient.address, patient.email, patient.password, patient.image from patient where patient.id in (SELECT patient_doctor.patient_id from patient_doctor where patient_doctor.doctor_id = $id);";
		$result = mysqli_query($con, $query_string);
		if($result){
			while($row = mysqli_fetch_assoc($result)){
				$final_result_arr[] = $row ;
			}
		}
	}
	
	
	// show doctors of hospital
	if($action == 'show doctors of hospital'){
		$id = $_POST['hospital_id'];
		$final_result_arr = array(1);
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		$query_string = "SELECT id, name, phone, address, hospital_id, specialty_id, email, password, image FROM `doctor` WHERE hospital_id=$id;";
		$result = mysqli_query($con, $query_string);
		if($result){
			while($row = mysqli_fetch_assoc($result)){
				$hos_id = $row['hospital_id'];
				if($hos_id == -1){
					$hos_name = "none";
				} else {
					$result2 =  mysqli_query($con, "select name from hospital where id = $hos_id;");
					$hos_row = mysqli_fetch_assoc($result2);
					$hos_name = $hos_row['name'];
				}
				
				$spec_id = $row['specialty_id'];
				if($spec_id == -1){
					$spec_name = "none";
				} else {
					$result2 =  mysqli_query($con, "select name from specialties where id = $spec_id;");
					$spec_row = mysqli_fetch_assoc($result2);
					$spec_name = $spec_row['name'];
				}
				$row['hospital.name'] = $hos_name;
				$row['specialties.name'] = $spec_name;
				$final_result_arr[] = $row ;
			}
		}
	}
	
	
	// show medical records of patient
	if($action == 'show medical records of patient'){
		$id = $_POST['patient_id'];
		$final_result_arr = array(1);
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		$query_string = "SELECT medical_records.id, medical_records.patient_id, patient.name as `patient.name`, medical_records.date_and_time, medical_records.overall_condition, medical_records.details, medical_records.doctor_id, doctor.name as `doctor.name` FROM `medical_records` , `patient`, `doctor` WHERE medical_records.patient_id = patient.id and medical_records.doctor_id = doctor.id and patient.id=$id order by medical_records.id DESC; ";
		$result = mysqli_query($con, $query_string);
		if($result){
			while($row = mysqli_fetch_assoc($result)){
				$final_result_arr[] = $row ;
			}
		}
	}
	
	// show specialties of hospital
	if($action == 'show specialties of hospital'){
		$id = $_POST['hospital_id'];
		$final_result_arr = array(1);
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		$query_string = "SELECT specialties.id, specialties.name, specialties.hospital_id, hospital.name as 'hospital.name' FROM specialties, hospital where specialties.hospital_id = hospital.id and specialties.hospital_id = $id;";
		$result = mysqli_query($con, $query_string);
		if($result){
			while($row = mysqli_fetch_assoc($result)){
				$final_result_arr[] = $row ;
			}
		}
	}
	
	// delete doctor from hospital
	if($action == 'delete doctor from hospital'){
		$doc_id_arr = $_POST['doctor_id_arr'];
		$tokens = explode(" ", $doc_id_arr);
		$id_str = "";
		$seperator = "";
		$prefix = "";
		for($i = 0; $i < count($tokens); $i++){
			$id_str .= $seperator . ' ' . $prefix . ' ' . $tokens[$i] . ' ';
			$seperator = 'OR';
			$prefix = 'id = ';
		}
		$final_result_arr = array(1);
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		mysqli_query($con, "update doctor set hospital_id =-1, specialty_id = -1 where id = $id_str;");
	}
	
	// delete specialty from hospital
	if($action == 'delete specialty from hospital'){
		$spec_id_arr = $_POST['specialty_id_arr'];
		$tokens = explode(" ", $spec_id_arr);
		$id_str = "";
		$id_str2 = "";
		$seperator = "";
		$prefix = "";
		$prefix2 = "";
		for($i = 0; $i < count($tokens); $i++){
			$id_str .= $seperator . ' ' . $prefix . ' ' . $tokens[$i] . ' ';
			$id_str2 .= $seperator . ' ' . $prefix2 . ' ' . $tokens[$i] . ' ';
			$seperator = 'OR';
			$prefix = 'specialty_id = ';
			$prefix2 = 'id = ';
		}
		$final_result_arr = array(1);
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		mysqli_query($con, "update doctor set specialty_id = -1 where specialty_id = $id_str;");
		mysqli_query($con, "delete from specialties where id = $id_str2;");
	}
	
	// delete patient from doctor
	if($action == 'delete patient from doctor'){
		$patient_id_arr = $_POST['patient_id_arr'];
		$doc_id = $_POST['doctor_id'];
		$tokens = explode(" ", $patient_id_arr);
		$patient_id_str = "";
		$seperator = "";
		$prefix = "";
		for($i = 0; $i < count($tokens); $i++){
			$patient_id_str .= $seperator . ' ' . $prefix . ' ' . $tokens[$i] . ' ';
			$seperator = 'OR';
			$prefix = 'patient_id = ';
		}
		$final_result_arr = array(1);
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		mysqli_query($con, "delete from patient_doctor where ( patient_id = $patient_id_str ) and doctor_id = $doc_id;");
	}
	
	// add patient for doctor
	if($action == 'add patient for doctor'){
		$patient_id = $_POST['patient_id'];
		$doc_id = $_POST['doctor_id'];
		$final_result_arr = array(1);
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		$result_1 = mysqli_query($con, "select * from patient where id = $patient_id ;");
		if($result_1){
			if($row = mysqli_fetch_assoc($result_1)){
				$result = mysqli_query($con, "select * from patient_doctor where patient_id = $patient_id and doctor_id = $doc_id;");
				if(mysqli_fetch_assoc($result)){
					$final_result_arr = array(0);
				} else {
					mysqli_query($con, "insert into patient_doctor(patient_id, doctor_id) values($patient_id, $doc_id);");
					$final_result_arr = array(1);
				}
			} else {
				$final_result_arr = array(-2);
			}
		} else {
			$final_result_arr = array(-2);
		}
	}
	
	// delete medical record from doctor
	if($action == 'delete medical record from doctor'){
		$rec_id_arr = $_POST['medical_record_id_arr'];
		$tokens = explode(" ", $rec_id_arr);
		$id_str = "";
		$seperator = "";
		$prefix = "";
		for($i = 0; $i < count($tokens); $i++){
			$id_str .= $seperator . ' ' . $prefix . ' ' . $tokens[$i] . ' ';
			$seperator = 'OR';
			$prefix = 'id = ';
		}
		$final_result_arr = array(1);
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		mysqli_query($con, "delete from medical_records where id = $id_str;");
		
	}
	
	// filter hospitals by city
	if($action == 'filter hospitals by city'){
		$city = $_POST['city'];
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		$result = mysqli_query($con, "select * from hospital where city like '%$city%';");
		if($result){
			$final_result_arr = array(1);
			$counter = 0;
			while($row = mysqli_fetch_assoc($result)){
				$final_result_arr[] = $row;
				$counter++;
			}
			if($counter == 0){
				$final_result_arr = array(0);
			}
		}
	}
	
	// add specialty for hospital
	if($action == 'add specialty for hospital'){
		$hos_id = $_POST['hospital_id'];
		$spec_name = $_POST['specialty_name'];
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		$result = mysqli_query($con, "select * from specialties where name like '%$spec_name%';");
		if($row = mysqli_fetch_assoc($result)){
			$final_result_arr = array(0);
		} else {
			mysqli_query($con, "insert into specialties(name, hospital_id) values('$spec_name', $hos_id);");
			$final_result_arr = array(1);
		}
	}
	
	// update hospital for doctor
	if($action == 'update hospital for doctor'){
		$doc_id = $_POST['doctor_id'];
		$hos_id = $_POST['hospital_id'];
		$final_result_arr = array(1);
		$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
		mysqli_query($con, "update doctor set hospital_id = $hos_id, specialty_id = -1 where id = $doc_id");
	}
	//-----------------------------------------------------------------------------------------
	
	
	
	// send result back
	echo json_encode($final_result_arr);
?>




