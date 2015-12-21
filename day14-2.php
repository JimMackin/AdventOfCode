<?php


function getReindeerDetails($in){
    $list = explode("\n",$in);
    $pattern = "/^(.*) can fly ([0-9]*) km\/s for ([0-9]*) seconds, but then must rest for ([0-9]*) seconds\.$/";
    $details = array();
    foreach($list as $item){
        if(!$item){
            continue;
        }
        $matches = array();
        preg_match($pattern,$item,$matches);
        $details[$matches[1]] = array('speed' => $matches[2],'time' => $matches[3], 'rest' => $matches[4],'inRest'=>false,'curPeriod'=>$matches[3],'curDistance'=>0);
    }
    return $details;
}

function calcWinner($in, $seconds){
    $reindeerDetails = getReindeerDetails($in);
    $maxDistance = 0;
    $arrPoints = array();
    for($x = 0; $x < $seconds; $x++){
        $curMax = 0;
        $curWinner = array();
        foreach($reindeerDetails as $name => $reindeer){
            if($reindeer['inRest']){
                $reindeerDetails[$name]['curPeriod']--;
                if($reindeerDetails[$name]['curPeriod'] <= 0){
                    $reindeerDetails[$name]['curPeriod'] = $reindeer['time'];
                    $reindeerDetails[$name]['inRest'] = false;
                }
            }else{
                $reindeerDetails[$name]['curPeriod']--;
                $reindeerDetails[$name]['curDistance'] += $reindeer['speed'];
                if($reindeerDetails[$name]['curPeriod'] <= 0){
                    $reindeerDetails[$name]['curPeriod'] = $reindeer['rest'];
                    $reindeerDetails[$name]['inRest'] = true;
                }
            }
            if($reindeerDetails[$name]['curDistance'] > $curMax){
                $curMax = $reindeerDetails[$name]['curDistance'];
                $curWinner = array($name);
            }elseif($reindeerDetails[$name]['curDistance'] == $curMax){
                $curWinner[] = $name;
            }
        }
        foreach($curWinner as $winner){
            if(empty($arrPoints[$winner])){
                $arrPoints[$winner] = 0;
            }
            $arrPoints[$winner]++;
        }
    }
    print_r($arrPoints);
}




$seconds = 2503;
//$seconds = 10;
calcWinner(file_get_contents('day14.txt'), $seconds);
