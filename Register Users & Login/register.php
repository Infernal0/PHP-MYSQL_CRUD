<?php
include "config.php";

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
    $image = $_FILES['file']['name'];
    $image_size = $_FILES['file']['size'];
    $image_tmp_name = $_FILES['file']['tmp_name'];
    $image_folder = 'images/' . $image;

    $select = mysqli_query($conn, "SELECT * FROM user_form WHERE email = '$email'") or die('connect failed');
    if (mysqli_num_rows($select) > 0) {
        $message[] = 'User already exists';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Confirm password does not match';
        } elseif ($image_size > 2000000) {
            $message[] = 'Image is too large';
        } else {
            $insert = mysqli_query($conn, 
                "INSERT INTO users(name, email, password, img) VALUES('$name', '$email', '$pass', '$image')"
            ) or die('query failed');
            
            if ($insert) {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Registered successfully';
                header('location: login.php');
            } else {
                $message[] = 'Registration failed';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registeration</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <main>
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Register Now</h3>
            <?php
                if(isset($message)){
                    foreach($message as $message){
                        echo "
                            <div class='message'>".$message."</div>
                        ";
                    }
                }
            ?>
            <input type="text" name="name" placeholder="Type Your Name" required>
            <input type="email" name="email" placeholder="Type Your Email" required>
            <input type="password" name="password" placeholder="Type Password" required>
            <input type="password" name="cpassword" placeholder="Confirm Password" required>
            <input type="file" name="file" accept="image/jpg, image/jpeg, image/png">
            <button type="submit" name="submit">Register Now</button>
            <p>aleardy have an account? <a href="login.php">Login Now</a></p>
        </form>
    </main>
</body>
</html>