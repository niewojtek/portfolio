<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form      
        render("buy.php", ["title" => "Buy"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // TODO
        // validate submission        
        if (empty($_POST["symbol"]))
        {
            apologize("You must specify a stock to buy.");
        }
        else if (empty($_POST["shares"]))
        {
            apologize("You must specify a number of shares.");
        }
        // return true if and only if $_POST["shares"] contains a non-negative integer
        else if (!preg_match("/^\d+$/", $_POST["shares"]))
        {
            apologize("Invalid number of shares.");
        }
        
		// query Yahoo Finance for stocks' prices
        $stock = lookup($_POST["symbol"]);
        
        if ($stock === false)
        {
            apologize("There are no share for this symbol.");
        }
        
	    $cost = $_POST["shares"] * $stock["price"];


        if (bccomp($_SESSION["cash"], $cost, 3) < 0)
        {
            apologize("You can't afford that.");
        }
        else
        {
            query("INSERT INTO portfolios (id, symbol, shares)
                   VALUES (?, ?, ?)
                   ON DUPLICATE KEY UPDATE shares = shares + VALUES(shares)",
            $_SESSION["id"], strtoupper($_POST["symbol"]), $_POST["shares"]);

            query("UPDATE users
                   SET cash = cash - ? WHERE id = ?", 
				   $cost, $_SESSION["id"]);

            query("INSERT INTO history (id, transaction, date, symbol, shares,
			       price)
				   VALUES (?, 'BUY', NOW(), ?, ?, ?) ",
                   $_SESSION["id"], strtoupper($_POST["symbol"]), 
                   $_POST["shares"], $stock["price"]);  
            
            $values = [
                "transaction" => "BUY",
                "name" => $stock["name"],
                "symbol" => $stock["symbol"],
                "shares" => $_POST["shares"],                
                "price" => $stock["price"]
            ];

            // send a receipt using settings in /includes/functions.php
            mailReceipt($values);
        }

        // redirect to portfolio
	    redirect("/");

    }

?>
