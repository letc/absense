<?php

// we will return this count at the end, it contains number of people online
$count = 0;

if(isset($_GET['id'])){
	// getting user id from post api
	$id = $_GET['id'];
	// getting saved json data of users
	$JSON = file_get_contents("data.json");
	// parsing file content to json string
	$arr = json_decode($JSON, true);
	// storing current time
	$curr_mytime = time();
	$rec_found = FALSE;
	for($i = 0; $i < count($arr['USER_DATA']); $i++){
		// if id matches user id, then updating current time
		if($arr['USER_DATA'][$i]['id'] == $id){
			$rec_found = TRUE;
			$arr['USER_DATA'][$i]['date'] = $curr_mytime;
		}
		else{
			// if stored id is not user id then checking time difference and updating count
			if(get_time_difference(date('m/d/Y h:i:s', $curr_mytime), date('m/d/Y h:i:s', $arr['USER_DATA'][$i]['date']))){
				$count++;
			}
		}
	}
	
	// if user id is not in the record then adding it
	if(!$rec_found){
		$new_ele = array('id'=>$id, 'date'=>$curr_mytime);
		
		array_push($arr['USER_DATA'],$new_ele);
	}
	$updated_content = json_encode($arr, true);
	file_put_contents("data.json", $updated_content);
    
	// creating response
	$response = array('count'=>$count);
	$responsejson = json_encode($response, true);
	print_r($responsejson);
	die();
} 
else {
	$response = array('count'=>$count);
	$responsejson = json_encode($response, true);
	print_r($responsejson);
	die();
}

function get_time_difference($date1, $date2) : bool {
		
		$flag = FALSE;
		$diff = strtotime($date1) - strtotime($date2);
		$fullDays    = floor($diff/(60*60*24));
		$fullHours   = floor(($diff-($fullDays*60*60*24))/(60*60));   
		$fullMinutes = floor(($diff-($fullDays*60*60*24)-($fullHours*60*60))/60);
		
		if($fullDays == 0 && $fullHours == 0 && ($fullMinutes > -10 && $fullMinutes < 10)){
			$flag = TRUE;
		}
		return $flag;
	}

?>

