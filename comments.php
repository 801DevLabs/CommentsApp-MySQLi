<?php
// REDIRECT USERS WHO ARE NOT LOGGED IN TO THE HOME PAGE
if(empty($_COOKIE['username'])){
    header('Location: /');
}

// GET USERNAME FROM COOKIE
$username = $_COOKIE['username'];

// REQUIRE FILES FOR COMMENTS PAGE
require_once("includes/vars.php");
require_once("includes/header.php");

// GET USER ID
$dbconnection = mysqli_connect(HOST, USER, PASSWORD, DB_NAME) or die ("Connection Failed");
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($dbconnection, $query) or die ("Query Failed");
$return = mysqli_fetch_array($result);
$user_id = $return['id'];

// SAVE USER COMMENT TO DATABASE
if(isset($_POST['submit'])){
    // GET DATA FROM USER CONTENT
    $comment = mysqli_real_escape_string($dbconnection, trim($_POST['comment']));
    
    // INSERT COMMENT INTO DATABASE
    $query = "INSERT INTO comments (comment, user_id) VALUES ('$comment', '$user_id')";
    $result = mysqli_query($dbconnection, $query) or die ("Query Failed");
    $comment_feedback = 'Comment Submitted Successfully';
}

?>

<div class="top-header d-flex align-items-center p-3 my-3 text-white-50 bg-white rounded shadow-sm">
    <div class="lh-100">
        <h6 class="mb-0 text-gray-dark lh-100">Comments App</h6>
    </div>
</div>

<?php
if(!empty($comment_feedback)) {
    echo '<div class="p-3 bg-white rounded shadow-sm"><div class="lh-100">';
    echo '<div class="mb-0 lh-100 alert alert-success" role="alert">'.$comment_feedback.'</div></div></div>';
}
?>

<div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6 class="border-bottom border-gray pb-2 mb-0">Recent Comments</h6>
    <?php
    // QUERY THE DATABASE FOR COMMENTS, INNER JOIN USERS TABLE ON USER_ID, LIMIT COMMENTS TO 5
    $query = "SELECT *,
                comments.comment as userComment,
                users.username as userName,
                users.color as userColor
                FROM comments
                INNER JOIN users
                ON comments.user_id = users.id
                ORDER BY comments.id DESC
                LIMIT 5
                ";
    $result = mysqli_query($dbconnection, $query) or die ("Query Failed");
    while($row = mysqli_fetch_array($result)){

        echo '<div class="media text-muted pt-3">';

        echo '<svg class="bd-placeholder-img mr-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 32x32">';

        echo '<rect width="100%" height="100%" fill='.$row['userColor'].' />';

        echo '<text x="50%" y="50%" fill="#007bff" dy=".3em"></text></svg>';

        echo '<p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">';

        echo '<strong class="d-block text-gray-dark">@'.$row['userName'].'</strong>';

        echo ''.$row['userComment'].'</p></div>';
    }
    ?>
</div>

<div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6>Leave a Comment</h6>

    <form action="comments.php" method="POST">
        <div class="form-group">
            <textarea class="form-control" name="comment" id="comment" rows="3" required></textarea>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php
require_once("includes/footer.php");
?>