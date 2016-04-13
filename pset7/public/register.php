<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // TODO
        if (empty($_POST["username"]))
        {
            apologize("You must provide a username.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide a password.");
        }
        else if ($_POST["password"] != $_POST["confirmation"])
        {
            apologize("Those passwords did not match.");
        }
        else if (empty($_POST["email"]))
        {
            apologize("You must provide an email.");
        }

        // query database for user
        $rows = query("SELECT * 
                       FROM users WHERE username = ?",
                       $_POST["username"]);

        // check for false the result of quering the database for the user
        if (count($rows) > 0)
        {                    
            // else apologize
            apologize("That username appears to be taken.");
        }
        else if (count($rows) == 0)
        {
            // insert a new user into your database
            query("INSERT INTO users (username, hash, cash, email)
                   VALUES(?, ?, 10000.00, ?)",
                   $_POST["username"], crypt($_POST["password"]),
                   $_POST["email"]);

            // find out which id was assigned to the last added user
            $rows = query("SELECT LAST_INSERT_ID() AS id");
            $id = $rows[0]["id"];

            // remember that user's now logged in by storing user's ID in session
            $_SESSION["id"] = $id;

            // after successful registration, log the new user in, redirect to
            // index.php
            redirect("/index.php");
        }        

    }

?>
