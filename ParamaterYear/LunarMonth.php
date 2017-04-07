<?php
  include('LunarDay.php');
  class LunarMonth {
    public $myaamiaName;
    public $daysInMonth;
    public $englishName;
    public $numOfDaysInMonth;
    public $removalDays;

    function __construct($firstDayOfYear, $curDate, $isExtraMoon, $daysInFirstMonth, $removalDays) {
      $dayDiff = ceil((strtotime($curDate) - strtotime($firstDayOfYear))/(60*60*24));
      $dayOfLunarYear = $this->get_day_of_lunar_year($dayDiff, $removalDays, $firstDayOfYear);
      $this->set_month_info($dayOfLunarYear, $isExtraMoon, $removalDays, $daysInFirstMonth);
      $this->daysInMonth = $this->create_days_for_month($dayOfLunarYear,$curDate, $removalDays, $isExtraMoon);
    }
  
    public function get_day_of_lunar_year($dayDiff, $removalDays, $firstDayOfYear) {
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

    public function create_days_for_month($dayOfLunarYear,$curDate, $removalDays, $isExtraMoon) {
      $days = array();
      if ($dayOfLunarYear == -1) {
        return $days;
      } else if ($isExtraMoon) {
        $dayOfLunarYear = $dayOfLunarYear - 29;
      }
      $dayOfLunarMonth = $this->lunar_day_of_month($dayOfLunarYear);
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
        $dayOfLunarYear = $dayOfLunarYear - 29;
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
      } else if ($dayOfLunarYear <= 30) {
        $this->numOfDaysInMonth = $day2;
        $this->englishName = 'Young Bear Moon';
        $this->myaamiaName = 'mahkoonsa kiilhswa';
      } else if ($dayOfLunarYear <= 59) {
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Crow Moon';
        $this->myaamiaName = 'aanteekwa kiilhswa';
      } else if ($dayOfLunarYear <= 89) {
        $this->numOfDaysInMonth = $day2;
        $this->englishName = 'Sandhill Crane Moon';
        $this->myaamiaName = 'cecaahkwa kiilhswa';
      } else if ($dayOfLunarYear <= 118) {
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Whippoorwill Moon';
        $this->myaamiaName = 'wiihkoowia kiilhswa';
      } else if ($dayOfLunarYear <= 148) {
        $this->numOfDaysInMonth = $day2;
        $this->englishName = 'Mid-Summer Moon';
        $this->myaamiaName = 'paaphsaahka niipinwiki';
      } else if ($dayOfLunarYear <= 177) { 
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Green Corn Moon';
        $this->myaamiaName = 'kiišiinkwia kiilhswa';
      } else if ($dayOfLunarYear <= 207) {
        $this->numOfDaysInMonth = $day2;
        $this->englishName = 'Elk Moon';
        $this->myaamiaName = 'mihšiiwia kiilhswa';
      } else if ($dayOfLunarYear <= 236) {
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Grass Burning Moon';
        $this->myaamiaName = 'šaašaakayolia kiilhswa';
      } else if ($dayOfLunarYear <= 266) {
        $this->numOfDaysInMonth = $day2;
        $this->englishName = 'Smokey Burning Moon';
        $this->myaamiaName = 'kiiyolia kiilhswa';
      } else if ($dayOfLunarYear <= 295) {
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Young Buck Moon';
        $this->myaamiaName = 'ayaapeensa kiilhswa';
      } else if ($dayOfLunarYear <= 325) {
        $this->numOfDaysInMonth = $day2;
        $this->englishName = 'Buck Moon';
        $this->myaamiaName = 'ayaapia kiilhswa';
      } else if ($dayOfLunarYear <= 354) {
        $this->numOfDaysInMonth = $day1;
        $this->englishName = 'Bear Moon';
        $this->myaamiaName = 'mahkwa kiilhswa';
      }
    }

    public function lunar_day_of_month($dayOfLunarYear) {
      if ($dayOfLunarYear <= 0) {
        return 29 + $dayOfLunarYear;
      } elseif ($dayOfLunarYear <= 30) {
        return $dayOfLunarYear;
      } else if ($dayOfLunarYear <= 59) {
        return $dayOfLunarYear - 30;
      } else if ($dayOfLunarYear <= 89) {
        return $dayOfLunarYear - 59;
      } else if ($dayOfLunarYear <= 118) {
        return $dayOfLunarYear - 89;
      } else if ($dayOfLunarYear <= 148) {
        return $dayOfLunarYear - 118;
      } else if ($dayOfLunarYear <= 177) {
        return $dayOfLunarYear - 148;
      } else if ($dayOfLunarYear <= 207) {
        return $dayOfLunarYear - 177;
      } else if ($dayOfLunarYear <= 236) {
        return $dayOfLunarYear - 207;
      } else if ($dayOfLunarYear <= 266) {
        return $dayOfLunarYear - 236;
      } else if ($dayOfLunarYear <= 295) {
        return $dayOfLunarYear - 266;
      } else if ($dayOfLunarYear <= 325) {
        return $dayOfLunarYear - 295;
      } else if ($dayOfLunarYear <= 354) {
        return $dayOfLunarYear - 325;
      }
    }
  }
?>
