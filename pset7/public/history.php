<?php

    // configuration
    require("../includes/config.php");

   

   if ($_SERVER["REQUEST_METHOD"] == "GET")
   {

        // query database for the history table
        $rows = query("SELECT * FROM history WHERE id = ? ORDER BY date DESC", $_SESSION["id"]);

        $history_positions = [];
    foreach ($rows as $row)
    {
        
            $history_positions[] = [
                "transaction" => $row["transaction"],
                "date"        => $row["date"],
                "symbol"      => $row["symbol"],
                "shares"      => $row["shares"],
                "price"       => $row["price"]
            ];
        
   }

    // render history
    render("history.php", ["title" => "History", "history_positions" => $history_positions]);

    }

    
    

?>
