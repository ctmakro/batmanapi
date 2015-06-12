<?php
include("BatManFunctions.php");

$fid=gt('fid');

if($fid)
{
  $sid=NewBat($fid);
  if($sid)
  {
    echo $sid;
  }
  else
  {
    $sid=GetBatSID($fid);
    echo "existed,$sid";
  }
}
else
{
  echo "please provide fid";
}
?>
