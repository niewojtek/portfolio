<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // query database for user
        $symbols = query("SELECT symbol
                          FROM portfolios WHERE id = ?",
                          $_SESSION["id"]);

        render("sell.php", ["title" => "Sell", "symbols" => $symbols]);
    }
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission        
        if (empty($_POST["symbol"]))
        {
            apologize("You must select a stock to sell.");
        }

        $stock = lookup($_POST["symbol"]);

        $shares = query("SELECT shares 
		                 FROM portfolios WHERE id = ?",
                         $_SESSION["id"]);

        $add_cash = $shares[0]["shares"] * $stock["price"];
        $_SESSION["cash"] += $add_cash;
        
        query("DELETE FROM portfolios
               WHERE id = ? AND symbol = ?",
               $_SESSION["id"], $_POST["symbol"]);

        query("UPDATE users 
		       SET cash = cash + ? WHERE id = ?",
               $add_cash, $_SESSION["id"]);

        query("INSERT INTO history 
		       (id, transaction, date, symbol, shares, price)
               VALUES (?, 'SELL', NOW(), ?, ?, ?) ",
               $_SESSION["id"], strtoupper($_POST["symbol"]),
               $shares[0]["shares"], $stock["price"]);

        $values = [
                "transaction" => "SELL",
                "name"        => $stock["name"],
                "symbol"      => $stock["symbol"],
                "shares"      => $shares[0]["shares"],                
                "price"       => $stock["price"]
        ];

        mailReceipt($values);

        // redirect to portfolio
        redirect("/");
    }

?>
