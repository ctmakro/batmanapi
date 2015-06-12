<?php
include("BatManFunctions.php");

function AddHist($sid)
{
  //add history record to sid.
  $fields=gt('fields');
  if($fields)
  {
    $fa=explode(',', $fields); //create an array for fields and their value
    $len=count($fa);
    if($len%2==1)
    {
      echo "fields and values not in pairs";
    }
    else
    {
      $hid=NewHistory($sid);
      echo "$hid,";
      for($i=0;$i<$len;$i+=2){
        SetHistoryField($hid,$fa[$i],$fa[$i+1]);
        echo $fa[$i]."->".$fa[$i+1].",";
      }
    }
  }
  else {
    echo "fields not provided";
    return;
  }


}


$fid=gt('fid');
$sid=gt('sid');

//if sid provided
if($sid){
  //allgood
  AddHist($sid);
}
else {
  if($fid)//if fid provided
  {
    $sid=GetBatSID($fid);
    if($sid)//if valid
    {
      AddHist($sid);
    }
    else {
      echo "fid no match";
    }
  }
  else
  {
    echo "please provide fid or sid";
  }
}
?>
