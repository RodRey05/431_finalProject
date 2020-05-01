<?php
    session_start();

    $username = "";
    $email = "";
    $errors = array();

    // connect to the database
    $db = mysqli_connect('localhost', 'root', '', 'registration');

    // if the register button is clicked
    if(isset($_POST['register']))
    {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
        $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

        // make sure that form fields are filled out properly
        if(empty($username))
        {
            // add error to the errors array
            array_push($errors, "Username required");
        }

        if(empty($email))
        {
            // add error to the errors array
            array_push($errors, "Email required");
        }

        if(empty($password_1))
        {
            // add error to the errors array
            array_push($errors, "Password required");
        }

        if($password_1 != $password_2)
        {
            array_push($errors, "The two passwords do not match");
        }
        
        // if there are no errors then save the user to the database
        if(count($errors) == 0)
        {
            $password = md5($password_1); // encrypt password
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email','$password')";

            mysqli_query($db, $sql);

            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in!!";

            // redirect to the homepage
            header('location: index.php');


        }

    }

    // log user in from the login page
    if(isset($_POST['login']))
    {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $password = mysqli_real_escape_string($db, $_POST['password']);

        // make sure that form fields are filled out properly
        if(empty($username))
        {
            // add error to the errors array
            array_push($errors, "Username required");
        }

        if(empty($password))
        {
            // add error to the errors array
            array_push($errors, "Password required");
        }

        if(count($errors) == 0 )
        {
            // encrypt the password before comparing with that from the database
            $password = md5($password);

            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = mysqli_query($db, $query);
            if(mysqli_num_rows($result) == 1)
            {
                // log the user in

            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in!!";
            header('location: index.php');

            } 
            else
            {
                array_push($errors, "Wrong username/password");
                header('location: login.php');

            }
        

        }

    }

    // logout
    if(isset($_GET['logout']))
    {
        session_destroy();
        unset($_SESSION['username']);
        header('location: login.php');

    }
?>