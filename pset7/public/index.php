<?php

    // configuration
    require("../includes/config.php"); 

    $cash = query("SELECT cash
                   FROM users WHERE id = ?",
                   $_SESSION["id"]);
    // remember user's deposit by storing user's cash
    $_SESSION["cash"] = $cash[0]["cash"];

    // query database for users shares
    $rows = query("SELECT symbol, shares
                   FROM portfolios WHERE id = ?",
				   $_SESSION["id"]);
    
    // initialize an array of associative arrays, each of which represents 
    // a position (i.e., a stock owned).
    $positions = [];
    foreach ($rows as $row)
    {
		// query Yahoo Finance for stocks' prices
        $stock = lookup($row["symbol"]);
        if ($stock !== false)
        {
            $positions[] = [
                "symbol" => $row["symbol"],
                "name"   => $stock["name"],
                "shares" => $row["shares"],
                "price"  => $stock["price"],
                "total"  => $stock["price"] * $row["shares"]
            ];
        }
    }

    // render portfolio
    render("portfolio.php", ["title" => "Portfolio",
                             "positions" => $positions]);

?>
