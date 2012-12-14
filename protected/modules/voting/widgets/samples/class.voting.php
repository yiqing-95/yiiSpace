<?php
// Script Voting - http://coursesweb.net/
class Voting {
  // properties
  static protected $conn = false;            // stores the connection to mysql
  public $affected_rows = 0;        // number of affected, or returned rows in SQL query
  protected $voter = '';                    // the user who vote, or its IP
  protected $nrvot = 0;                 // if it is 1, the user can vote only one item in a day, 0 for multiple items
  protected $svoting = 'mysql';         // 'mysql' to register data in database, any other value register in TXT files
  public $votitems = 'voting';        // Table /or file_name to store items that are voted
  public $votusers = 'votusers';             // Table /or filename that stores the users who voted in current day
  protected $tdy;                // will store the number of current day
  public $eror = false;          // to store and check for errors

  // constructor
  public function __construct() {
    // sets $nrvot, $svoting, $voter, and $tdy properties
    if(defined('NRVOT')) $this->nrvot = NRVOT;
    if(defined('SVOTING')) $this->svoting = SVOTING;
    if(defined('USRVOTE') && USRVOTE === 0) { if(defined('VOTER')) $this->voter = VOTER; }
    else $this->voter = $_SERVER['REMOTE_ADDR'];
    $this->tdy = date('j');

    // if set to use TXT files, set the path and name of the files
    if($this->svoting != 'mysql') {
      $this->votitems = '../votingtxt/'.$this->votitems.'.txt';
      $this->votusers = '../votingtxt/'.$this->votusers.'.txt';
    }
  }

  // for connecting to mysql
  protected function setConn() {
    try {
      // Connect and create the PDO object
      self::$conn = new PDO("mysql:host=".DBHOST."; dbname=".DBNAME, DBUSER, DBPASS);

      // Sets to handle the errors in the ERRMODE_EXCEPTION mode
      self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      self::$conn->exec('SET CHARACTER SET utf8');      // Sets encoding UTF-8
      
    }
    catch(PDOException $e) {
      $this->eror = 'Unable to connect to MySQL: '. $e->getMessage();
    }
  }

  // Performs SQL queries
  public function sqlExecute($sql) {
    if(self::$conn===false OR self::$conn===NULL) $this->setConn();      // sets the connection to mysql
    $re = true;           // the value to be returned

    // if there is a connection set ($conn property not false)
    if(self::$conn !== false) {
      // gets the first word in $sql, to determine whenb SELECT query
      $ar_mode = explode(' ', trim($sql), 2);
      $mode = strtolower($ar_mode[0]);

      // performs the query and get returned data
      try {
        if($sqlprep = self::$conn->prepare($sql)) {
          // execute query
          if($sqlprep->execute()) {
            // if $mode is 'select', gets the result_set to return
            if($mode == 'select') {
              $re = array();
              // if fetch() returns at least one row (not false), adds the rows in $re for return
              if(($row = $sqlprep->fetch(PDO::FETCH_ASSOC)) !== false){
                do {
                  // check each column if it has numeric value, to convert it from "string"
                  foreach($row AS $k=>$v) {
                    if(is_numeric($v)) $row[$k] = $v + 0;
                  }
                  $re[] = $row;
                }
                while($row = $sqlprep->fetch(PDO::FETCH_ASSOC));
              }
              $this->affected_rows = count($re);                   // number of returned rows
            }
          }
          else $this->eror = 'Cannot execute the sql query';
        }
        else {
          $eror = self::$conn->errorInfo();
          $this->eror = 'Error: '. $eror[2];
        }
      }
      catch(PDOException $e) {
        $this->eror = $e->getMessage();
      }
    }

    // sets to return false in case of error
    if($this->eror !== false) { echo $this->eror; $re = false; }
    return $re;
  }

  // returns JSON string with item:['vote', 'nvotes', renot] for each element in $items array 
  public function getVoting($items, $vote = '') {
    $votstdy = $this->votstdyCo($items);     // gets from Cookie array with items voted by the user today

    // if $vote not empty, perform to register the vote, $items contains one item to vote
    if(!empty($vote)) {
      // if $voter empty means user not loged
      if($this->voter === '') return "alert('Vote Not registered.\\nYou must be logged in to can vote')";
      else {
        // gets array with items voted today from mysql, or txt-files (according to $svoting), and merge unique to $votstdy
        if($this->svoting == 'mysql') {
          $votstdy = array_unique(array_merge($votstdy, $this->votstdyDb()));
        }
        else {
          $all_votstdy = $this->votstdyTxt();     // get 2 array: 'all'-rows voted today, 'day'-items by voter today
          $votstdy = array_unique(array_merge($votstdy, $all_votstdy[$this->tdy]));
        }

        // if already voted, add in cookie, returns JSON from which JS alert message and will reload the page
        // else, accesses the method to add the new vote, in mysql or TXT file
        if(in_array($items[0], $votstdy) || ($this->nrvot === 1 && count($votstdy) > 0)) {
          $votstdy[] = $items[0];
          setcookie("votings", implode(',', array_unique($votstdy)), strtotime('tomorrow'));
          return '{"'.$items[0].'":[0,0,3]}';
        }
        else if($this->svoting == 'mysql') $this->setVotDb($items, $vote, $votstdy);       // add the new vote in mysql
        else $this->setVotTxt($items, $vote, $all_votstdy);          // add the new vote, and voter in TXT files

       array_push($votstdy, $items[0]);        // adds curent item as voted
     }
    }

    // if $nrvot is 1, and $votstdy has item, set $setvoted=1 (user already voted today)
    // else, user can vote multiple items, after Select is checked if already voted the existend $item
    $setvoted = ($this->nrvot === 1 && count($votstdy) > 0) ? 1 : 0;

    // get array with items and their votings from mysql or TXT file
    $votitems = ($this->svoting == 'mysql') ? $this->getVotDb($items, $votstdy, $setvoted) : $this->getVotTxt($items, $votstdy, $setvoted);

    return json_encode($votitems);
  }

  // insert /update rating item in #votitems, delete rows in $votusers which are not from today, insert $voter in $votusers
  protected function setVotDb($items, $vote, $votstdy) {
    $this->sqlExecute("INSERT INTO `$this->votitems` (`item`, `vote`) VALUES ('".$items[0]."', $vote) ON DUPLICATE KEY UPDATE `vote`=`vote`+$vote, `nvotes`=`nvotes`+1");

    $this->sqlExecute("DELETE FROM `$this->votusers` WHERE `day`!=$this->tdy");

    $this->sqlExecute("INSERT INTO `$this->votusers` (`day`, `voter`, `item`) VALUES ($this->tdy, '$this->voter', '".$items[0]."')");

    // add curent voted item to the others today, and save them as string ',' in cookie (till the end of day)
    $votstdy[] = $items[0];
    setcookie("votings", implode(',', array_unique($votstdy)), strtotime('tomorrow'));
  }

  // select 'vote' and 'nvotes' of each element in $items, $votstdy stores items voted by the user today
  // returns array with item:['vote', 'nvotes', renot] for each element in $items array
  protected function getVotDb($items, $votstdy, $setvoted) {
    $re = array_fill_keys($items, array(0,0,$setvoted));    // makes each value of $items as key with an array(0,0,0)

    function addSlhs($elm){return "'".$elm."'";}      // function to be used in array_map(), adds "'" to each $elm
    $resql = $this->sqlExecute("SELECT * FROM `$this->votitems` WHERE `item` IN(".implode(',', array_map('addSlhs', $items)).")");
    if($this->affected_rows > 0) {
      for($i=0; $i<$this->affected_rows; $i++) {
        $voted = in_array($resql[$i]['item'], $votstdy) ? $setvoted + 1 : $setvoted;   // add 1 if the item was voted by the user today
        $re[$resql[$i]['item']] = array($resql[$i]['vote'], $resql[$i]['nvotes'], $voted);
      }
    }

    return $re;
  }

  // add /update rating item in TXT file, keep rows from today in $votusers, and add new row with $voter
  protected function setVotTxt($items, $vote, $all_votstdy) {
    // get the rows from file with items, if exists
    if(file_exists($this->votitems)) {
      $rows = file($this->votitems, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $nrrows = count($rows);

      // if exist rows registered, get array for each row, with - item^vote^nvotes
      // if row with item, update it and stop, else, add the row at the end
      if($nrrows > 0) {
        for($i=0; $i<$nrrows; $i++) {
          $row = explode('^', $rows[$i]);
          if($row[0] == $items[0]) {
            $rows[$i] = $items[0].'^'.($row[1] + $vote).'^'.($row[2] + 1);
            $rowup = 1; break;
          }
        }
      }
    }
    if(!isset($rowup)) $rows[] = $items[0].'^'.$vote.'^1';

    file_put_contents($this->votitems, implode(PHP_EOL, $rows));      // save the items in file

    // add row with curent item voted and the voter (today^voter^item), and save all the rows
    $all_votstdy['all'][] = $this->tdy.'^'.$this->voter.'^'.$items[0];
    file_put_contents($this->votusers, implode(PHP_EOL, $all_votstdy['all']));

    // add curent voted item to the others today, and save them as string ',' in cookie (till the end of day)
    $all_votstdy[$this->tdy][] = $items[0];
    setcookie("votings", implode(',', array_unique($all_votstdy[$this->tdy])), strtotime('tomorrow'));
  }

  // get from TXT 'vote' and 'nvotes' of each element in $items, $votstdy stores items voted by the user today
  // returns array with item:['vote', 'nvotes', renot] for each element in $items array
  protected function getVotTxt($items, $votstdy, $setvoted) {
    $re = array_fill_keys($items, array(0,0,$setvoted));    // makes each value of $items as key with an array(0,0,0)

    if(file_exists($this->votitems)) {
      $rows = file($this->votitems, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $nrrows = count($rows);

      // if exist rows registered, get array for each row, with - item^vote^nvotes
      // if row with item is in $items, add its data in $re
      if($nrrows > 0) {
        for($i=0; $i<$nrrows; $i++) {
          $row = explode('^', $rows[$i]);
          $voted = in_array($row[0], $votstdy) ? $setvoted + 1 : $setvoted;   // add 1 if the item was voted by the user today
          if(in_array($row[0], $items)) $re[$row[0]] = array($row[1], $row[2], $voted);
        }
      }
    }

    return $re;
  }

  // gets and returns from Cookie an array with items voted by the user ($voter) today
  protected function votstdyCo() {
    $votstdy = array();

    // if exists cookie 'votings', adds items voted today in $votstdy (array_filter() - removes null, empty elements)
    if(isset($_COOKIE['votings'])) {
      $votstdy = array_filter(explode(',', $_COOKIE['votings']));     // cookie stores string with: item1, item2, ...
    }

    return $votstdy;
  }

  // returns from mysql an array with items voted by the user today
  protected function votstdyDb() {
    $votstdy = array();
    $resql = $this->sqlExecute("SELECT `item` FROM `$this->votusers` WHERE `day`=$this->tdy AND `voter`='$this->voter'");
    if($this->affected_rows > 0) {
      for($i=0; $i<$this->affected_rows; $i++) {
        $votstdy[] = $resql[$i]['item'];
      }
    }

    return $votstdy;
  }

  // returns from TXT file an array with 2 arrays: all rows voted today, and items voted by the user today
  protected function votstdyTxt() {
    $re['all'] = array();  $re[$this->tdy] = array();
    if(file_exists($this->votusers)) {
      $rows = file($this->votusers, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $nrrows = count($rows);

      // if exist rows registered, get array for each row, with - day^voter^item , compare 'day', and 'voter'
      if($nrrows > 0) {
        for($i=0; $i<$nrrows; $i++) {
          $row = explode('^', $rows[$i]);
          if($row[0] == $this->tdy) {
            $re['all'][] = $rows[$i];
            if($row[1] == $this->voter) $re[$this->tdy][] = $row[2];
          }
        }
      }
    }

    return $re;
  }
}