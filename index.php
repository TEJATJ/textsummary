
<?php
/*
ARTIFICIAL INTELLIGENCE TERM PAPER (1st SEMESTER 2015-16)

SHREYANS MITTAL 2013A7PS032P (IMPLEMENTATION OF TEXTRANK)

HIMANSHU SINGH DHONI

K. SAI TEJA 2013A7PS034P (IMPLEMETATION OF SIMPLIFIED LESK ALGORITHM)





**********************LESK ALGORITHM IMPLEMENTATION BEGINS**********************
(IMPLEMENTED BY SAI TEJA K 2013A7PS034P)


EXTERNAL LIBRARIES USED: wordnet Dictionary
*/
error_reporting(0);
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "123456");
define("DB_NAME", "wordnet");

require_once("TextSummary.php");
$db=new Database;

$text=file_get_contents("C:/wamp/www/SUW/textsummary/article 8.txt");

$text=strtolower($text);

$ts=new TextSummary($text);
$ts->mainP();
$ts->getSummary(0.65);?>
