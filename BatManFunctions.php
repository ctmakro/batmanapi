<?php
include("BatManEssentials.php");

function endl(){echo"<br>";}

$debug=0;
if($debug==1){
echo "batmanapi test, ";
echo phpversion();

endl();
}

/*
sid is the unique system id to index a battery inside database.
fid is the unique factory id of a battery.
*/

//list of keys and key prefixes
$sid_incremental ="sidi";
$hid_incremental="hidi";
$BatFieldPrefix = "batfield:";
$BatFidPrefix="batfid:";
$BatList = "batlist";
$HIDPrefix = "hid:";
$BatHistoryListPrefix="bathist:";
$HistoryList ="historylist";
$StampFormat="YmdHis";

//returns sid on success, returns false on failure.
//fid for factory id, on the label.
function NewBat($fid){
  global $BatList;

  if(TestFID($fid)){
    //if this fid is already in library;
    return false;
  }
  $sid=NewSID();
  SetFID($sid,$fid);

  $r=redisLink();
  $r->lpush($BatList,$sid);

  $hid=NewHistory($sid);
  SetHistoryField($hid,"event","newbat");

  return $sid;
}

function NewSID(){
  global $sid_incremental;
  return redisLink()->incr($sid_incremental);
}

function NewHID(){
  global $hid_incremental;
  return redisLink()->incr($hid_incremental);
}

//set the fid of a battery.
function SetFID($sid,$fid){
  global $BatFidPrefix;

  SetBatField($sid,"fid",$fid);
  $r=redisLink();
  $r->set($BatFidPrefix.$fid,$sid);
}

//test if an FID exists.
function TestFID($fid){
  global $BatFidPrefix;
  $r=redisLink();
  return $r->exists($BatFidPrefix.$fid);
}

function GetBatList($begin,$end)
{
  global $BatList;
  $r=redisLink();
  return $r->lrange($BatList,$begin,$end);
}

function GetBatField($sid,$field){
  global $BatFieldPrefix;
  $r=redisLink();
  return $r->hget($BatFieldPrefix.$sid,$field);
}

function GetBatFieldAll($sid)
{
  global $BatFieldPrefix;
  $r=redisLink();
  return $r->hgetall($BatFieldPrefix.$sid);
}

function SetBatField($sid,$field,$value){
  global $BatFieldPrefix;
  $r=redisLink();
    $r->hset($BatFieldPrefix.$sid,$field,$value);
}

function GetBatSID($fid){
  global $BatFidPrefix;
  $r=redisLink();
  return $r->get($BatFidPrefix.$fid);
}

function GetNumBat(){
  global $BatList;
  return redisLink()->llen($BatList);
}

//add a new history record for sid battery.
function NewHistory($sid)
{
  global $BatHistoryListPrefix;
  global $HistoryList;

$r=redisLink();
$hid=NewHID();
$r->lpush($BatHistoryListPrefix.$sid,$hid);
$r->lpush($HistoryList,$hid);

SetHistoryField($hid,"time",Stamp());
return $hid;
}

function SetHistoryField($hid,$field,$value)
{
  global $HIDPrefix;
    redisLink()->hset($HIDPrefix.$hid,$field,$value);
}

function Stamp(){
  global $StampFormat;
  date_default_timezone_set("UTC");
  return date($StampFormat);
}

//get the list of history:sid
function GetBatHistoryAll($sid){GetBatHistoryRange(0,-1);}

//get the top x list of history:sid
function GetBatHistoryTop($sid,$number){GetBatHistoryRange($sid,0,$number-1);}

function GetBatHistoryRange($sid,$start,$end){
  global $BatHistoryListPrefix;
  return redisLink()->lrange($BatHistoryListPrefix.$sid,$start,$end);
}

//now parse the request from either GET or POST.

function parserequest()
{
  $operation=gt("operation");
  if($operation){

  }
  else{
  echo "no operation assigned";
  }

}



?>
