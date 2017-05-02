<?php
	/*
	Filename: getProdData.php
	Created: 04/29/17
	Purpose: PHP script for saving and retrieving lunar year calendar data from MongoDB database 
	*/

	header("content-type: application/json");

	// Connect to MongoDB
	$mongo= new MongoDB\Driver\Manager("mongodb://annekent.451.csi.miamioh.edu:27017");
	
	// If request is POST, add data to MongoDB
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$content = file_get_contents('php://input');
		$data = json_decode($content, true);
		
		// Check for all required parameters
		if (!array_key_exists('year', $data) || !array_key_exists('data', $data)) {
			echo json_encode(array('status'=>'ERROR', 'msg'=>'Parameters not set'));
			exit();
		}
		
		// Check for data for a given year
		$query = new MongoDB\Driver\Query(array('year'=>$data['year']));
		$result = $mongo->executeQuery('db.miaamia-calendar',$query);
		
		// If data already exists, overwrite existing data
		if (count($result->toArray()) > 0) {
			$write = new MongoDB\Driver\BulkWrite;
			$write->delete(['year'=>$data['year']]);
			$mongo->executeBulkWrite('db.miaamia-calendar',$write);
		}

		$year = array();

		// Insert new data
		$write = new MongoDB\Driver\BulkWrite;
		$write->insert(['year'=>$data['year'], 'data'=> $data['data']]);
		$mongo->executeBulkWrite('db.miaamia-calendar',$write);
		
		echo json_encode(array('status'=>'OK', 'msg'=>'Success'));
		exit();
	}

	// Otherwise, request is to retrieve lunar calendar data
	// If no year parameter supplied, assume current calendar year
	if (!array_key_exists('year', $_GET)) {
		$_GET['year'] = date('Y');
		$_GET['year'] = $_GET['year'] + 1;	
	}

	// Get current year data from MongoDB
	$query = new MongoDB\Driver\Query(array('year'=>$_GET['year']));
	$result = $mongo->executeQuery('db.miaamia-calendar',$query);
	$result = $result->toArray();

	if (count($result) > 0) {
		$result = $result[0];
		$result->data = (array) $result->data;
		$result->data[1]->daysInMonth = (array) $result->data[1]->daysInMonth;
	}

	if (count($result) == 0 || strtotime($result->data[1]->daysInMonth[1]->gregorianDate) > time()) {
		$_GET['year'] = $_GET['year'] - 1;
		$query = new MongoDB\Driver\Query(array('year'=>"".$_GET['year']));
		$result = $mongo->executeQuery('db.miaamia-calendar',$query);
		$result = $result->toArray();

		if (count($result) == 0) {
			echo json_encode(array('status'=>'FAIL', 'msg'=>'No data exists for year'));
			exit();
		}

		$result = $result[0];
		$result->data = (array) $result->data;
	}

	// Set current month index to current month if applicable
	$result->data['curMonthIndex'] = 1;
	for ($m = 1; ($m < 13 && $result->data['isExtraMoon']) || ($m < 12 && !$result->data['isExtraMoon']); $m++) {
		$result->data[$m]->daysInMonth = (array) $result->data[$m]->daysInMonth;
		$time1 = strtotime($result->data[$m]->daysInMonth[1]->gregorianDate);
      		$time2 = strtotime($result->data[$m]->daysInMonth[$result->data[$m]->numOfDaysInMonth]->gregorianDate);
		$time2 = strtotime("tomorrow", $time2) - 1;
      		$time3 = time();
      		if ($time3 >= $time1 && $time3 <= $time2) {
        		$result->data['curMonthIndex'] = $m;
			break;
      		}
	}

	// Return results to user
	echo json_encode(array('status'=>'OK', 'result'=>$result));
	exit();
?>
