<?php
  include('LunarMonth.php');
  if (!isset($_GET['date']) || !isset($_GET['direction'])) {
    echo 'Date or direction not set!';
    exit();
  }
  $day = $_GET['date'];
  if ($_GET['direction']==='previous') {
    $day = date('Y-m-d', strtotime($day .' -1 day'));
  } else {
    $day = date('Y-m-d', strtotime($day .' +1 day'));
  }
  $lunarMonth = new LunarMonth($day);
  
  header('Content-Type: text/html');
?>
<div class="col-md-offset-2 col-sm-offset-2 col-md-10 col-sm-10 parchment">
    <?php
      $firstMonthDate = $lunarMonth->daysInMonth[1]->gregorianDate;
      $lastMonthDate = end($lunarMonth->daysInMonth);
      $lastMonthDate = $lastMonthDate->gregorianDate;
    ?>
    <a href="#" onclick="getPreviousMonth('<? echo $firstMonthDate; ?>')"><< Previous Month</a>
    <a href="#" onclick="getNextMonth('<? echo $lastMonthDate; ?>')" style="float:right">Next Month >></a>
    <table id="calendar" align="center">
      <?php
        $lastMonthDate = strtok($lastMonthDate, '-');
        print "<tr><td colspan='7'><h2 id='monthName'>{$lunarMonth->myaamiaName} {$lastMonthDate}</h2><div><h3 id='engMonthName'>{$lunarMonth->englishName}</h3></div></td></tr>";
      ?>
      <tr> <!-- Day Names -->
	<td>eelaamini-kii&#353;ikahki</td>
	<td>nkotakone</td>
	<td>niis&#353;akone</td>
	<td>nihsokone</td>
	<td>niiyakone</td>
	<td>yaalanokone</td>
	<td>kaakaathsokone</td>
      </tr>
      <tr id="calWeek-0">
      <?php
	$daysOfWeek = array('eelaamini-kiišikahki','nkotakone','niišakone','nihsokone','niiyakone','yaalanokone','kaakaathsokone');
	$i;
	for ($i = 0; $i < 7; $i++) {
  	  if ($lunarMonth->daysInMonth[1]->myaamiaName != $daysOfWeek[$i]) {
  	    print "<td><div class='miami-label'></div><div class='gregDate'></div></td>";
 	  } else {
  	    break;
	  }
	}
	$dateIndex = 1;
	for ($week = 1; $week < 6; $week++) {
  	  for (; $i < 7; $i++) {
  	    if ($dateIndex > count($lunarMonth->daysInMonth)) {
	      print "<td><div class='miami-label'></div><div class='gregDate'></div></td>";
 	    } else {
  	      $gregDate = date('m/d', strtotime($lunarMonth->daysInMonth[$dateIndex]->gregorianDate));
  	      print "<td><div class='miami-label'>{$lunarMonth->daysInMonth[$dateIndex]->dayOfLunarMonth}</div><div class='gregDate'>{$gregDate}</div></td>";
	      $dateIndex++;
	    }
	  }
	  print '</tr>';
	  if ($week != 5) {
	    print "<tr id='calWeek-{$week}'>";
	  }
	  $i = 0;
	}
      ?>
    </table>
  </div>
