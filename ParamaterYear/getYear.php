<?php
  include('LunarMonth.php');

  header('Content-Type: application/json');

  $_POST = json_decode(file_get_contents('php://input'), True);

  if (!isset($_POST['firstDayOfYear']) || !isset($_POST['isExtraMoon']) || !isset($_POST['removalDays']) || !isset($_POST['daysInFirstMonth'])) {
	    $err = array();
	    $err['errors'] = 'Did not provide all valid parameters';
	    echo json_encode($err);
	    exit();
  }

  if ($_POST['isExtraMoon'] == "True") {
	$isExtraMoon = True;
  } else {
	$isExtraMoon = False;
  }

  if ($_POST['daysInFirstMonth'] == '29') {
	$_POST['daysInFirstMonth'] = 29;
  } else {
	$_POST['daysInFirstMonth'] = 30;
  }

  $date = date('Y-m-d', strtotime($_POST['firstDayOfYear']. ' + 1 days'));
  $month = new LunarMonth($_POST['firstDayOfYear'], $date, $isExtraMoon, $_POST['daysInFirstMonth'], $_POST['removalDays']);
  $year = array();
  $year['isExtraMoon'] = $isExtraMoon;
  $year[1] = $month;

  $d = $date;
  for ($m = 1; ($m < 13 && $isExtraMoon) || ($m < 12 && !$isExtraMoon); $m++) {
    $d = date('Y-m-d', strtotime($d. ' + '.$year[$m]->numOfDaysInMonth. ' days'));
    $year[$m + 1] = new LunarMonth($_POST['firstDayOfYear'], $d, $isExtraMoon, $_POST['daysInFirstMonth'], $_POST['removalDays']);
  }

  echo json_encode($year);
?>
