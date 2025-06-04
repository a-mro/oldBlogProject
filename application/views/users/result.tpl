<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8"/>
    <?php if(!isset($userData) and  !isset($errors)) {echo '<meta http-equiv="refresh" content="2;url=/posts/index/" />';} ?>
    <title>Blog user</title>
    <style><?php require_once HOME . DS . 'application' . DS . 'includes' . DS . 'main.css.php';?></style>
</head>
<body>
<div id="container">

    <?php include HOME . DS . 'application' . DS . 'includes' . DS . 'header.php';?>
    <?php include HOME . DS . 'application' . DS . 'includes' . DS . 'navbar.php';?>


    <div id="main">
        <h1><?php if (isset($title)) echo $title; ?></h1>
        <h2><?php echo $resType;?></h2>
        <br>
        <p><?php if (isset($logresult)) echo $logresult;?></p>
        <?php
        if (isset($errors)) 
        {
            echo '<ul>';
        foreach ($errors as $e)
        {
        echo '
        <li>' . $e . '</li>
        ';
        }
        echo '</ul>';
        }

        if (isset($saveError))
        {
        echo "<h2>Error saving data. Please try again.</h2>" . $saveError;
        }
        ?>
        <?php if(isset($userData)){ ?>
        <h2>Registered data</h2>
        <div class="formregis">
            <label>Username</label><input value='<?php if(isset($userData)) echo $userData['userName']; ?>' type="text"
            name="username" disabled><br>
            <label>Name</label><input value='<?php if(isset($userData)) echo $userData['firstName']; ?>' type="text"
            name="name" disabled><br>
            <label>LastName</label><input value='<?php if(isset($userData)) echo $userData['lastName']; ?>' type="text"
            name="last_name" disabled><br>
            <label>Email</label><input value='<?php if(isset($userData)) echo $userData['email']; ?>' type="text"
            name="email" disabled><br>
            <label>Password</label><input value='<?php if(isset($userData)) echo $userData['password']; ?>'
            type="password" name="password" disabled><br>
        </div>
        <a href="/users/index">Back to index</a>


        <?php } ?>
        <?php if(isset($errors)){?>
        <a href="/users/register">Back to register form</a>
        <?php } ?>
    </div>


    <?php include HOME . DS . 'application' . DS . 'includes' . DS . 'footer.php';?>
</div>

</body>
</html>