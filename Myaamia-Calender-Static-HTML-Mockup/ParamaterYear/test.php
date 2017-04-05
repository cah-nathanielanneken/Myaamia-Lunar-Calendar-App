<?php
  include('LunarMonth.php');
  $firstDayOfYear = "2017-01-29";
  $date = "2017-01-30";
  $isExtraMoon = False;
  $removalDays = array();
  $month = new LunarMonth($firstDayOfYear, $date, $isExtraMoon, $removalDays);
  var_dump($month);
?>
