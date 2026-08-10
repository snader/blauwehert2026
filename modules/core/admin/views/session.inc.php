<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="300">
    <script>
  if (top === self) {
    window.location.replace("https://brew.musclebrewing.nl/dashboard");
  }
</script>
</head>

<body>    
<?php

    echo time();
    print "-";
    $oCurrentUser = UserManager::getCurrentUser();
    echo $oCurrentUser->userId;

    ?>
</body>

</html>    