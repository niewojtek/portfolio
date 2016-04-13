<?php

    require(__DIR__ . "/../includes/config.php");
     
    $geo = preg_split('/[\ \n\,]+/', $_GET["geo"]);
     
    // numerically indexed array of places
    $places = [];
    
    // TODO: search database for places matching $_GET["geo"]
    $q = "SELECT * FROM places WHERE";
    for ($i = 0, $count = count($geo); $i < $count; $i++)
    {
        $q = $q . " address LIKE '% " . $geo[$i] . " %'";
       
        if ($i < ($count - 1))
        {
           $q .= " AND";
        }
    }

    $places = query($q);
   
    // output places as JSON (pretty-printed for debugging convenience)
    header("Content-type: application/json");
    print(json_encode($places, JSON_PRETTY_PRINT));

?>
