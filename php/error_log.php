<?
session_start();
$name = explode('/', $_SERVER['SCRIPT_NAME']);
$server_name = $name[count($name) - 2];
$tailfile = "/var/log/httpd/".$server_name."/error_log";
#$tailfile = "/www/".$server_name."/logs/error_log";
$lines = file($tailfile);
$n = count($lines);
$var = $server_name . "_lastn";
if ($_SESSION[$var]) {
  $new = $n - $_SESSION[$var];
  $i = 5 + $new;
} else {
  $i = 5;
  $_SESSION[$var] = $n;
}
print "Last $i of $n lines:<br/>";
for($j = $n - $i; $j < $n; $j++) {
  print $lines[$j] . "<br/>";
}
