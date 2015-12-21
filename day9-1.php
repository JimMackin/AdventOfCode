<?php

function addToRoutes($start,$end,$dist,$arr){
    $arr = addToRoute($start,$end,$dist,$arr);
    $arr = addToRoute($end,$start, $dist,$arr);
    return $arr;
}
function addToRoute($start, $end, $dist, $arr){
if(empty($arr[$start])){
        $arr[$start] = array();
    }
    if(empty($arr[$start][$end])){
        $arr[$start][$end] = array();
    }
    $arr[$start][$end] = $dist;
    return $arr;
}

function getAllRoutes($places){
    if(!$places){
         yield $places;
         return;
    }
    if(count($places) == 1){
        yield $places;
        return;
    }
    foreach($places as $key => $place){
        $tmp = $places;
        unset($tmp[$key]);
        foreach(getAllRoutes($tmp) as $subRoute){
            $route = array($place);
            yield array_merge($route,$subRoute);
        }
    }
}

function calcDistance($route, $routeDistances){
    $dist = 0;
    $prevDest = '';
    foreach($route as $dest){
        if($prevDest){
            $dist += $routeDistances[$prevDest][$dest];
        }
        $prevDest = $dest;
    }
    return $dist;
}

function processDistances($in){
    $dirs = explode("\n",$in);
    $routes = array();
    $places = array();
    $patt = "/(.*) to (.*) = ([0-9]*)/";
    foreach($dirs as $dir){
        if(!$dir){
            continue;
        }
        $matches = array();
        preg_match($patt,$dir,$matches);
        $routes = addToRoutes($matches[1],$matches[2],$matches[3],$routes);
        $places[] = $matches[1];
        $places[] = $matches[2];
    }
    $places = array_unique($places);
    $currentLowestDist = PHP_INT_MAX;
    $currentLowestRoute = array();
    foreach(getAllRoutes($places) as $route){
        $dist = calcDistance($route,$routes);
        if($dist < $currentLowestDist){
            $currentLowestDist = $dist;
            $currentLowestRoute = $route;
        }
    }
echo "Lowest Distance: ".$currentLowestDist."\n";
}

$in = "Faerun to Tristram = 65
Faerun to Tambi = 129
Faerun to Norrath = 144
Faerun to Snowdin = 71
Faerun to Straylight = 137
Faerun to AlphaCentauri = 3
Faerun to Arbre = 149
Tristram to Tambi = 63
Tristram to Norrath = 4
Tristram to Snowdin = 105
Tristram to Straylight = 125
Tristram to AlphaCentauri = 55
Tristram to Arbre = 14
Tambi to Norrath = 68
Tambi to Snowdin = 52
Tambi to Straylight = 65
Tambi to AlphaCentauri = 22
Tambi to Arbre = 143
Norrath to Snowdin = 8
Norrath to Straylight = 23
Norrath to AlphaCentauri = 136
Norrath to Arbre = 115
Snowdin to Straylight = 101
Snowdin to AlphaCentauri = 84
Snowdin to Arbre = 96
Straylight to AlphaCentauri = 107
Straylight to Arbre = 14
AlphaCentauri to Arbre = 46
";

processDistances($in);
