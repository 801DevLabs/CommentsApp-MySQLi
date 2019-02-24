<?php
// REDIRECT LOGGED IN USERS TO THE COMMENTS PAGE
if(!empty($_COOKIE['username'])){
    header('Location: comments.php');
}

require_once("includes/vars.php");
require_once("includes/header.php");

// CREATE CONNECTION TO THE DATABASE
$dbconnection = mysqli_connect(HOST, USER, PASSWORD, DB_NAME) or die ("Connection Failed");

if(isset($_POST['submit'])){
    // SET LOGIN VARIABLES
    $username = mysqli_real_escape_string($dbconnection, trim($_POST['username']));
    $password = mysqli_real_escape_string($dbconnection, trim($_POST['password']));
    
    // SET ERROR VARIABLES
    $hasErrors = false;
    $username_err = '';
    $password_err = '';
    
    // SEND ERROR MESSAGE IF USERNAME FIELD IS EMPTY
    if(empty($username)){
        $hasErrors = true;
        $username_err = 'Please enter a username';
        $feedback = 'Invalid username or password';
    }
    
    // SEND ERROR IF PASSWORD FIELD IS EMPTY
    if(empty($password)){
        $hasErrors = true;
        $password_err = 'Please enter a password';
        $feedback = 'Invalid username or password';
    }
    
    if($hasErrors == false) {
        // USERNAME AND PASSWORD BOTH HAVE DATA
        
        // CHECK TO SEE IF USERNAME EXISTS
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($dbconnection, $query) or die ("Query Failed");
        $usernameRowCount = mysqli_num_rows($result);
        
        if($usernameRowCount > 0) {
            // USERNAME EXISTS
            // CHECK TO SEE IF PASSWORD EXISTS
            $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
            $result = mysqli_query($dbconnection, $query) or die ("Query Failed");
            $usernameAndPasswordRowCount = mysqli_num_rows($result);
            if($usernameAndPasswordRowCount > 0) {
                // USERNAME AND PASSWORD MATCH
                // SET COOKIES FOR LOGGED IN USER
                setcookie('username', $username, time() + (60*60*24*30));
                
                // CLOSE THE CONNECTION
                mysqli_close($dbconnection);
                
                // REDIRECT TO THE COMMENTS PAGE
                header('Location: comments.php');
            } else {
                $feedback = 'Invalid username or password';
            }
        } else {
            $feedback = 'Invalid username or password';
        }
    
    }
    
}

?>

<div class="top-header d-flex align-items-center p-3 my-3 text-white-50 bg-white rounded shadow-sm">
    <div class="lh-100">
        <h6 class="mb-0 text-gray-dark lh-100">Please Log in to Comment</h6>
    </div>
</div>

<?php
if(!empty($feedback)) {
    echo '<div class="p-3 bg-white rounded shadow-sm"><div class="lh-100">';
    echo '<div class="mb-0 lh-100 alert alert-danger" role="alert">'.$feedback.'</div></div></div>';
}
?>

<div class="my-3 p-3 bg-white rounded shadow-sm">

    <form action="index.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control <?php echo(!empty($username_err)) ? 'is-invalid' : ''; ?>" id="username" aria-describedby="emailHelp" placeholder="Enter username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control <?php echo(!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password" placeholder="Password">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<?php
require_once("includes/footer.php");
?>