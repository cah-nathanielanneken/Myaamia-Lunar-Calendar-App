<?php
  function dates_month($month, $year) {
    $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $dates_month = array();

    for ($i = 1; $i <= $num; $i++) {
        $mktime = mktime(0, 0, 0, $month, $i, $year);
        $date = date("d-M-Y", $mktime);
        $dates_month[$i] = $date;
    }

    return $dates_month;
  }

  function lunar_month($dayOfLunarYear) {
    if ($dayOfLunarYear <= 30) {
      return 'mahkoonsa kiilhswa';
    } else if ($dayOfLunarYear <= 59) {
      return 'aanteekwa kiilhswa';
    } else if ($dayOfLunarYear <= 89) {
      return 'cecaahkwa kiilhswa';
    } else if ($dayOfLunarYear <= 118) {
      return 'wiihkoowia kiilhswa';
    } else if ($dayOfLunarYear <= 148) {
      return 'paaphsaahka niipinwiki';
    } else if ($dayOfLunarYear <= 177) {
      return 'kiišiinkwia kiilhswa';
    } else if ($dayOfLunarYear <= 207) {
      return 'mihšiiwia kiilhswa';
    } else if ($dayOfLunarYear <= 236) {
      return 'šaašaakayolia kiilhswa';
    } else if ($dayOfLunarYear <= 266) {
      return 'kiiyolia kiilhswa';
    } else if ($dayOfLunarYear <= 295) {
      return 'ayaapeensa kiilhswa';
    } else if ($dayOfLunarYear <= 325) {
      return 'ayaapia kiilhswa';
    } else if ($dayOfLunarYear <= 354) {
      return 'mahkwa kiilhswa';
    }
  }

  $month = dates_month(date('m'), date('y'));
  foreach ($month as &$day) {
    $dayName = date('l', strtotime($day));
    switch ($dayName) {
      case 'Monday':
        $day = 'nkotakone';
        break;
      case 'Tuesday':
        $day = 'niišakone';
        break;
      case 'Wednesday':
        $day = 'nihsokone';
        break;
      case 'Thursday':
        $day = 'niiyakone';
        break;
      case 'Friday':
        $day = 'yaalanokone';
        break;
      case 'Saturday':
        $day = 'kaakaathsokone';
        break;
      case 'Sunday':
        $day = 'eelaamini-kiišikahki';
        break;
    }
  }
  $dayDiff = time() - strtotime('2016-02-10');
  $dayOfLunarYear = floor($dayDiff / (60 * 60 * 24)) % 354;
  $monthName = lunar_month($dayOfLunarYear);
  echo $monthName; 
?>
