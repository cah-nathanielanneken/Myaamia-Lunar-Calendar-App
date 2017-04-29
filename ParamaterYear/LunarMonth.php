<?php
  /*
     Filename: LunarMonth.php
     Created: 04/29/17
     Purpose: PHP Script to create an object oriented LunarMonth
  */

  include('LunarDay.php');
  class LunarMonth {
    public $myaamiaName;
    public $daysInMonth;
    public $englishName;
    public $numOfDaysInMonth;
    public $removalDays;

    function __construct($firstDayOfYear, $curDate, $isExtraMoon, $daysInFirstMonth, $removalDays) {
      // Calculate the difference in days between the current date and the first day of the lunar year
      $dayDiff = ceil((strtotime($curDate) - strtotime($firstDayOfYear))/(60*60*24));
      // Calculate the current day of Lunar Year
      $dayOfLunarYear = $this->get_day_of_lunar_year($dayDiff, $removalDays, $firstDayOfYear);
      $this->set_month_info($dayOfLunarYear, $isExtraMoon, $removalDays, $daysInFirstMonth);
      // Create and add all lunar days to lunar month
      $this->daysInMonth = $this->create_days_for_month($dayOfLunarYear,$curDate, $removalDays, $isExtraMoon, $daysInFirstMonth);
    }
  
    public function get_day_of_lunar_year($dayDiff, $removalDays, $firstDayOfYear) {
      // Calculate the day of the current lunar year
      $daySub = 0;
      foreach ($removalDays as &$removalDay) {
        $tempDayDiff = ceil((strtotime($removalDay) - strtotime($firstDayOfYear))/(60*60*24)); 
        if ($tempDayDiff < $dayDiff) {
          $daySub = $daySub + 1;
        } else if ($tempDayDiff == $dayDiff) {
          return -1;
        }
      }
      return $dayDiff - $daySub;    
    }

    public function create_days_for_month($dayOfLunarYear,$curDate, $removalDays, $isExtraMoon, $daysInFirstMonth) {
      // Create all lunar days for lunar month
      $days = array();
      if ($dayOfLunarYear == -1) {
        return $days;
      } else if ($isExtraMoon) {
        $dayOfLunarYear = $dayOfLunarYear - $daysInFirstMonth;
      }
      $dayOfLunarMonth = $this->lunar_day_of_month($dayOfLunarYear, $daysInFirstMonth, $isExtraMoon);
      $firstDate = new DateTime($curDate);
      $firstDate->sub(new DateInterval('P'.$dayOfLunarMonth.'D'));
      for ($i = 1; $i <= $this->numOfDaysInMonth; $i++) {
        if (in_array(date_format($firstDate, 'Y-m-d'), $removalDays)) {
          $i--;
        } else {
          $days[$i] = new LunarDay(date_format($firstDate,'Y-m-d'), $i);
        }
        $firstDate->add(new DateInterval('P1D'));
      }
      return $days;
    }

    public function set_month_info($dayOfLunarYear, $isExtraMoon, $removalDays, $daysInFirstMonth) {
      if ($dayOfLunarYear == -1) {
        $this->numOfDaysInMonth = -1;
        $this->englishName = 'Day Removed';
        $this->myaamiaName = 'Day Removed';
        return;
      } 
      if ($isExtraMoon) {
        $dayOfLunarYear = $dayOfLunarYear - $daysInFirstMonth;
	$day1 = $daysInFirstMonth;
	$day2 = ($daysInFirstMonth == 29 ? 30 : 29);
      } else {
	$day1 = ($daysInFirstMonth == 29 ? 30 : 29);
	$day2 = $daysInFirstMonth;
      }
      $this->removalDays = $removalDays; 
      if ($dayOfLunarYear <= 0) {
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Lost Moon';
        $this->myaamiaName = 'waawiita kiilhswa';
      } else if ($dayOfLunarYear <= $day2) {
        $this->numOfDaysInMonth = $day2;
        $this->englishName = 'Young Bear Moon';
        $this->myaamiaName = 'mahkoonsa kiilhswa';
      } else if ($dayOfLunarYear <= $day2 + $day1) {
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Crow Moon';
        $this->myaamiaName = 'aanteekwa kiilhswa';
      } else if ($dayOfLunarYear <= ($day2 * 2) + $day1) {
        $this->numOfDaysInMonth = $day2;
        $this->englishName = 'Sandhill Crane Moon';
        $this->myaamiaName = 'cecaahkwa kiilhswa';
      } else if ($dayOfLunarYear <= ($day2 * 2) + ($day1 * 2)) {
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Whippoorwill Moon';
        $this->myaamiaName = 'wiihkoowia kiilhswa';
      } else if ($dayOfLunarYear <= ($day2 * 3) + ($day1 * 2)) {
        $this->numOfDaysInMonth = $day2;
        $this->englishName = 'Mid-Summer Moon';
        $this->myaamiaName = 'paaphsaahka niipinwiki';
      } else if ($dayOfLunarYear <= ($day2 * 3) + ($day1 * 3)) { 
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Green Corn Moon';
        $this->myaamiaName = 'kiišiinkwia kiilhswa';
      } else if ($dayOfLunarYear <= ($day2 * 4) + ($day1 * 3)) {
        $this->numOfDaysInMonth = $day2;
        $this->englishName = 'Elk Moon';
        $this->myaamiaName = 'mihšiiwia kiilhswa';
      } else if ($dayOfLunarYear <= ($day2 * 4) + ($day1 * 4)) {
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Grass Burning Moon';
        $this->myaamiaName = 'šaašaakayolia kiilhswa';
      } else if ($dayOfLunarYear <= ($day2 * 5) + ($day1 * 4)) {
        $this->numOfDaysInMonth = $day2;
        $this->englishName = 'Smokey Burning Moon';
        $this->myaamiaName = 'kiiyolia kiilhswa';
      } else if ($dayOfLunarYear <= ($day2 * 5) + ($day1 * 5)) {
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Young Buck Moon';
        $this->myaamiaName = 'ayaapeensa kiilhswa';
      } else if ($dayOfLunarYear <= ($day2 * 6) + ($day1 * 5)) {
        $this->numOfDaysInMonth = $day2;
        $this->englishName = 'Buck Moon';
        $this->myaamiaName = 'ayaapia kiilhswa';
      } else if ($dayOfLunarYear <= ($day2 * 6) + ($day1 * 6)) {
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Bear Moon';
        $this->myaamiaName = 'mahkwa kiilhswa';
      }
    }

    public function lunar_day_of_month($dayOfLunarYear, $daysInFirstMonth, $isExtraMoon) {
      if ($isExtraMoon) {
	$day1 = $daysInFirstMonth;
	$day2 = ($daysInFirstMonth == 29 ? 30 : 29);
      } else {
	$day1 = ($daysInFirstMonth == 29 ? 30 : 29);
	$day2 = $daysInFirstMonth;
      }
      if ($dayOfLunarYear <= 0) {
        return ($daysInFirstMonth + $dayOfLunarYear);
      } elseif ($dayOfLunarYear <= $day2) {
        return $dayOfLunarYear;
      } else if ($dayOfLunarYear <= $day2 + $day1) {
        return ($dayOfLunarYear - ($day2));
      } else if ($dayOfLunarYear <= ($day2 * 2) + $day1) {
        return ($dayOfLunarYear - (($day2) + $day1));
      } else if ($dayOfLunarYear <= ($day2 * 2) + ($day1 * 2)) {
        return ($dayOfLunarYear - (($day2 * 2) + ($day1)));
      } else if ($dayOfLunarYear <= ($day2 * 3) + ($day1 * 2)) {
        return ($dayOfLunarYear - (($day2 * 2) + ($day1 * 2)));
      } else if ($dayOfLunarYear <= ($day2 * 3) + ($day1 * 3)) {
        return ($dayOfLunarYear - (($day2 * 3) + ($day1 * 2)));
      } else if ($dayOfLunarYear <= ($day2 * 4) + ($day1 * 3)) {
        return ($dayOfLunarYear - (($day2 * 3) + ($day1 * 3)));
      } else if ($dayOfLunarYear <= ($day2 * 4) + ($day1 * 4)) {
        return ($dayOfLunarYear - (($day2 * 4) + ($day1 * 3)));
      } else if ($dayOfLunarYear <= ($day2 * 5) + ($day1 * 4)) {
        return ($dayOfLunarYear - (($day2 * 4) + ($day1 * 4)));
      } else if ($dayOfLunarYear <= ($day2 * 5) + ($day1 * 5)) {
        return ($dayOfLunarYear - (($day2 * 5) + ($day1 * 4)));
      } else if ($dayOfLunarYear <= ($day2 * 6) + ($day1 * 5)) {
        return ($dayOfLunarYear - (($day2 * 5) + ($day1 * 5)));
      } else if ($dayOfLunarYear <= ($day2 * 6) + ($day1 * 6)) {
        return ($dayOfLunarYear - (($day2 * 6) + ($day1 * 5)));
      }
    }
  }
?>
