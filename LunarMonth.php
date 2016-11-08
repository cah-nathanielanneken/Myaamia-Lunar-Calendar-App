<?php
  include('LunarDay.php');
  class LunarMonth {
    public $myaamiaName;
    public $daysInMonth;
    public $englishName;
    public $numOfDaysInMonth;

    function __construct($curDate) {
      $dayDiff = floor((strtotime($curDate) - strtotime('2016-02-09'))/(60*60*24));
      $dayOfLunarYear = $this->get_day_of_lunar_year($dayDiff);
      $this->myaamiaName = $this->lunar_month($dayOfLunarYear);
      $this->englishName = $this->english_lunar_month($dayOfLunarYear);
      $this->numOfDaysInMonth = $this->day_count($dayOfLunarYear);
      $this->daysInMonth = $this->create_days_for_month($dayOfLunarYear,$curDate);
    }
  
    public function get_day_of_lunar_year($dayDiff) {
      $count = 1;
      $dayOfYear = $dayDiff;
      for (; $dayOfYear > 354; $dayOfYear = $dayOfYear - 354) {
        if ($count % 3 == 0) {
          if ($dayOfYear <= 383) {
            break;
          }
          $dayOfYear = $dayOfYear - 29;
        }
        $count++;
      }
      if ($count % 3 == 0) {
        $dayOfYear = $dayOfYear - 29;
      }
      return $dayOfYear;
    }

    public function create_days_for_month($dayOfLunarYear,$curDate) {
      $days = array();
      $dayOfLunarMonth = $this->lunar_day_of_month($dayOfLunarYear);
      $dayOfLunarMonth--;
      $firstDate = new DateTime($curDate);
      $firstDate->sub(new DateInterval('P'.$dayOfLunarMonth.'D'));
      for ($i = 1; $i <= $this->numOfDaysInMonth; $i++) {
        $days[$i] = new LunarDay(date_format($firstDate,'Y-m-d'));
        $firstDate->add(new DateInterval('P1D'));
      }
      return $days;
    }

    public function day_count($dayOfLunarYear) {
      if ($dayOfLunarYear <= 0) {
        return 29;
      } else if ($dayOfLunarYear <= 30) {
        return 30;
      } else if ($dayOfLunarYear <= 59) {
        return 29;
      } else if ($dayOfLunarYear <= 89) {
        return 30;
      } else if ($dayOfLunarYear <= 118) {
        return 29;
      } else if ($dayOfLunarYear <= 148) {
        return 30;
      } else if ($dayOfLunarYear <= 177) {
        return 29;
      } else if ($dayOfLunarYear <= 207) {
        return 30;
      } else if ($dayOfLunarYear <= 236) {
        return 29;
      } else if ($dayOfLunarYear <= 266) {
        return 30;
      } else if ($dayOfLunarYear <= 295) {
        return 29;
      } else if ($dayOfLunarYear <= 325) {
        return 30;
      } else if ($dayOfLunarYear <= 354) {
        return 29;
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

   public function english_lunar_month($dayOfLunarYear) {
      if ($dayOfLunarYear <= 0) {
        return 'Lost Moon';
      } else if ($dayOfLunarYear <= 30) {
        return 'Young Bear Moon';
      } else if ($dayOfLunarYear <= 59) {
        return 'Crow Moon';
      } else if ($dayOfLunarYear <= 89) {
        return 'Sandhill Crane Moon';
      } else if ($dayOfLunarYear <= 118) {
        return 'Whippoorwill Moon';
      } else if ($dayOfLunarYear <= 148) {
        return 'Mid-Summer Moon';
      } else if ($dayOfLunarYear <= 177) {
        return 'Green Corn Moon';
      } else if ($dayOfLunarYear <= 207) {
        return 'Elk Moon';
      } else if ($dayOfLunarYear <= 236) {
        return 'Grass Burning Moon';
      } else if ($dayOfLunarYear <= 266) {
        return 'Smokey Burning Moon';
      } else if ($dayOfLunarYear <= 295) {
        return 'Young Buck Moon';
      } else if ($dayOfLunarYear <= 325) {
        return 'Buck Moon';
      } else if ($dayOfLunarYear <= 354) {
        return 'Bear Moon';
      }
    }

    public function lunar_month($dayOfLunarYear) {
      if ($dayOfLunarYear <= 0) {
        return 'waawiita kiilhswa';
      } else if ($dayOfLunarYear <= 30) {
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
  }
?>
