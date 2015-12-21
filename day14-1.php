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
        $details[$matches[1]] = array('speed' => $matches[2],'time' => $matches[3], 'rest' => $matches[4]);
    }
    return $details;
}

function calcWinner($in, $seconds){
    $reindeerDetails = getReindeerDetails($in);
    $maxDistance = 0;
    foreach($reindeerDetails as $name => $reindeer){
        $distance = calcDistance($seconds,$reindeer);
        if($distance > $maxDistance){
            $maxDistance = $distance;
        }
    }
    echo "Winning reindeer went $maxDistance\n";
}

function calcDistance($seconds, $reindeer){
    $elapsed = 0;
    $distance = 0;
    $inRest = false;
    while($elapsed < $seconds){
        if($inRest){
            $elapsed += $reindeer['rest'];
            $inRest = false;
        }else{
            $timeLeft = min($seconds-$elapsed,$reindeer['time']);
            $distance += $reindeer['speed'] * $timeLeft;
            $inRest = true;
            $elapsed += $timeLeft;
        }
    }
    return $distance;
}





calcWinner(file_get_contents('day14.txt'), 2503);
