<?php
  include('LunarMonth.php');
  $date = '2017-1-5';
  $firstDayOfYear = '2016-02-10';
  $isExtraMoon = false;
  $removalDays = array();
  $removalDays[0] = '2017-01-06';
  $month = new LunarMonth($firstDayOfYear, $date, $isExtraMoon, $removalDays);
  var_dump($month);
?>
