<?php
  include('LunarMonth.php');
  $day = '2018-11-07';
  $lunarm = new LunarMonth($day);
  print_r ($lunarm->daysInMonth);
?>
