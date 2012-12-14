<?php
// Script Voting - http://coursesweb.net/php-mysql/

define('SVOTING', 'txt');        // change 'txt' with 'mysql' if you want to save voting data in MySQL

// HERE define data for connecting to MySQL database (MySQL server, user, password, database name)
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', 'passdb');
define('DBNAME', 'dbname');

// if NRVOT is 0, the user can vote multiple items in a day, if it is 1, the user can vote only one item in a day
define('NRVOT', 0);

// If you want than only the logged users to can vote the element(s) on page, sets USRVOTE to 0
// And sets $_SESSION['username'] with the session that your script uses to keep logged users
define('USRVOTE', 1);
if(USRVOTE !== 1) {
  if(!isset($_SESSION)) session_start();
  if(isset($_SESSION['username'])) define('VOTER', $_SESSION['username']);
}

     /* From Here no need to modify */

if(!headers_sent()) header('Content-type: text/html; charset=utf-8');      // header for utf-8

include('class.voting.php');        // Include Voting class
$obVot = new Voting();

// if data from POST 'elm' and 'vote'
if(isset($_POST['elm']) && isset($_POST['vote'])) {
  // removes tags and external whitespaces from 'elm'
  $_POST['elm'] = array_map('strip_tags', $_POST['elm']);
  $_POST['elm'] = array_map('trim', $_POST['elm']);
  if(!empty($_POST['vote'])) $_POST['vote'] = intval($_POST['vote']);

  echo $obVot->getVoting($_POST['elm'], $_POST['vote']);
}