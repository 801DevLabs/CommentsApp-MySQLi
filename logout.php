<?php
// DELETE THE COOKIES BY SETTING THEIR EXPIRATION DATE TO AN HOUR AGO (3600)
setcookie('username', '', time()-3600);

// REDIRECT TO HOME PAGE
header('Location: index.php');
?>