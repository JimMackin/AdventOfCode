<?php

function findSue($in, $facts){
  $sues = getSues($in);
  foreach($sues as $num => $sue){
    $allMatch = true;
    foreach($facts as $property => $amount){
      if(!isset($sue[$property])){
        continue;
      }elseif(($property == 'trees' || $property == 'cats')){
        if($sue[$property] <= $amount){
        //echo "Property [$property] doesn't match for $num val is ".$sue[$property]." amount is $amount\n";
        $allMatch = false;
        break;
      }
      }elseif(($property == 'pomeranians' || $property == 'goldfish')){
        if($sue[$property] >= $amount){
        //echo "Property $property doesn't match for $num val is ".$sue[$property]." amount is $amount\n";
        $allMatch = false;
        break;
      }
      }elseif($sue[$property] != $amount){
        //echo "Property $property doesn't match for $num val is ".$sue[$property]." amount is $amount\n";
        $allMatch = false;
        break;
      }
    }
    if($allMatch){
      echo "Sue $num matches!\n";
    }
  }
}

function getSues($in){
    $sues = array();
    $pattern = "/^Sue ([0-9]*): (.*)$/";
    foreach(explode("\n",$in) as $sueLine){
      if(!$sueLine){
        continue;
      }
      $matches = array();
      preg_match($pattern, $sueLine,$matches);
      $num = $matches[1];
      $details = $matches[2];
      $sues[$num] = array();
      foreach(explode(',',$details) as $detail){
        if(!$detail){
          continue;
        }
        $bits = explode(':',$detail);
        $sues[$num][trim($bits[0])] = trim($bits[1]);
      }
    }
    return $sues;
}

$facts = array(
  'children' => 3,
'cats' => 7,
'samoyeds' => 2,
'pomeranians' => 3,
'akitas' => 0,
'vizslas' => 0,
'goldfish' => 5,
'trees' => 3,
'cars' => 2,
'perfumes' => 1,
);
findSue(file_get_contents('day16.txt'), $facts);
