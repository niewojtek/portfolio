<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("quote_form.php", ["title" => "Get Quote"]);
    }
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // TODO
        // validate submission        
        if (empty($_POST["symbol"]))
        {
            apologize("You must provide a symbol.");
        }
        else if (lookup($_POST["symbol"]) === false)
	    {
	        apologize("There are no share for this symbol.");	
	    }
		
        $stock = lookup($_POST["symbol"]);
        
        render("quote.php", ["title" => "Quote", "stock" => $stock]);
    }    

?>
