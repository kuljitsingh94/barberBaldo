<?php 
date_default_timezone_set('America/Los_Angeles');
//echo date("F j, Y, g:i a",mktime(13, 0, 0, 5, 4, 2018));
//echo date("F j, Y, g:i a");
function getMonth($unixTime) {
    return date("F", $unixTime);
}
function getDay($unixTime) {
    return date("j", $unixTime);
}
function getYear($unixTime) {
    return date("Y", $unixTime);
}
function getHour($unixTime) {
    return date("g", $unixTime);
}
function getMinute($unixTime) {
    return date("i", $unixTime);
}
function getAMPM($unixTime) {
    return date("a", $unixTime);
}
?>
