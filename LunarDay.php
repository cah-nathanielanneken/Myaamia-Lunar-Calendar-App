<?php
  class LunarDay {
    public $myaamiaName;
    public $gregorianNumber;
    public $englishName;

    function __construct($curDate) {
      $this->myaamiaName = $this->convert_day($curDate);
      $d = date_parse_from_format('Y-m-d', $curDate);
      $this->gregorianNumber = $d['day'];
      $this->englishName = date('l', strtotime($curDate));
    }

    public function convert_day($date) {
      $day = '';
      $dayName = date('l', strtotime($date));
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
      return $day;
    }
  }
?>
