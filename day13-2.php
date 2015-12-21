<?php

function calcHappiness($in){
    $happyMap = getHappyMap($in);
    $people = array_keys($happyMap);
    $people[] = 'Me';
    $perms = pc_permute($people);
    $maxHappy = 0;
    foreach($perms as $perm){
        $thisHappy = calcPermHappiness($perm,$happyMap);
        if($thisHappy > $maxHappy){
            $maxHappy = $thisHappy;
        }
    }
    echo "Max happy is $maxHappy\n";
}

function calcPermHappiness($perm,$happyMap){
    $happyCount = 0;
    $lastPerson = $perm[count($perm)-1];
    foreach($perm as $person){
        if($lastPerson == 'Me' || $person == 'Me'){
            $happyCount += 0;
        }else{
            $happyCount += $happyMap[$lastPerson][$person] + $happyMap[$person][$lastPerson];
        }
        $lastPerson = $person;
    }
    return $happyCount;
}
function pc_permute($items, $perms = array( )) {
    if (empty($items)) {
        yield $perms;
    }  else {
        for ($i = count($items) - 1; $i >= 0; --$i) {
             $newitems = $items;
             $newperms = $perms;
             list($foo) = array_splice($newitems, $i, 1);
             array_unshift($newperms, $foo);
             foreach(pc_permute($newitems, $newperms) as $subPerms){
                 yield $subPerms;
             }
         }
    }
}

function getHappyMap($in){
    $list = explode("\n",$in);
    $happyMap = array();
    $patt = "/^(.*) would (.*) ([0-9]*) happiness units by sitting next to (.*)\.$/";
    foreach($list as $item){
        if(!$item){
            continue;
        }
        $matches = array();
        preg_match($patt,$item,$matches);
        if(!$matches){
            echo "Bad Item $item\n";
            die();
        }
        $happyMap = addToMap($happyMap,$matches[1],$matches[4],$matches[2],$matches[3]);
    }
    return $happyMap;
}

function addToMap($map,$p1,$p2,$action,$amount){
    if($action == "lose"){
        $amount = 0 - $amount;
    }
    if(empty($map[$p1])){
        $map[$p1] = array();
    }
    $map[$p1][$p2] = $amount;
    return $map;
}

calcHappiness(file_get_contents("day13.txt"));
