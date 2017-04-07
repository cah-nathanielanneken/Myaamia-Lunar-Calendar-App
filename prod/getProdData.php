<?php
	header("content-type: application/json");

	$mongo= new MongoDB\Driver\Manager("mongodb://annekent.451.csi.miamioh.edu:27017");
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$content = file_get_contents('php://input');
		$data = json_decode($content, true);
		
		if (!array_key_exists('year', $data) || !array_key_exists('data', $data)) {
			echo json_encode(array('status'=>'ERROR', 'msg'=>'Parameters not set'));
			exit();
		}
		

		$query = new MongoDB\Driver\Query(array('year'=>$data['year']));
		$result = $mongo->executeQuery('db.miaamia-calendar',$query);
		
		if (count($result->toArray()) > 0) {
			$write = new MongoDB\Driver\BulkWrite;
			$write->delete(['year'=>$data['year']]);
			$mongo->executeBulkWrite('db.miaamia-calendar',$write);
		}

		$year = array();

		$write = new MongoDB\Driver\BulkWrite;
		$write->insert(['year'=>$data['year'], 'data'=> $data['data']]);
		$mongo->executeBulkWrite('db.miaamia-calendar',$write);
		
		echo json_encode(array('status'=>'OK', 'msg'=>'Success'));
		exit();
	}

	if (!array_key_exists('year', $_GET)) {
		$_GET['year'] = date('Y');
	}

	$query = new MongoDB\Driver\Query(array('year'=>$_GET['year']));
	$result = $mongo->executeQuery('db.miaamia-calendar',$query);
	$result = $result->toArray()[0];
	$result->data = (array) $result->data;
	$result->data['curMonthIndex'] = -1;
	for ($m = 1; ($m < 13 && $result->data['isExtraMoon']) || ($m < 12 && !$result->data['isExtraMoon']); $m++) {
		$result->data[$m]->daysInMonth = (array) $result->data[$m]->daysInMonth;
		$time1 = strtotime($result->data[$m]->daysInMonth[1]->gregorianDate);
      		$time2 = strtotime($result->data[$m]->daysInMonth[$result->data[$m]->numOfDaysInMonth]->gregorianDate);
      		$time3 = time();
      		if ($time3 >= $time1 && $time3 <= $time2) {
        		$result->data['curMonthIndex'] = $m;
			break;
      		}
	}

	echo json_encode(array('status'=>'OK', 'result'=>$result));
	exit();
?>
