<?php
// CHECK TO SEE IF USER IS LOGGED IN
$loggedIn = false;
if(!empty($_COOKIE['username'])){
    $loggedIn = true;
}
?>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
<div class="container">
<a class="navbar-brand" href="/">Thrive Chat</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav ml-auto">
        <?php
        if($loggedIn == true) {
            // USER GREETING
            echo '<li class="nav-item active">';
            echo '<a class="nav-link" href="#">';
            echo 'Hello, ' . $username;
            echo '</a>';
            // LOGOUT MENU ITEM
            echo '</li>';
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="logout.php">Logout</a>';
            echo '</li>';
        }
        ?>
    </ul>
</div>
</div>
</nav>