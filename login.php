<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link rel="stylesheet" href="./style.css" />
    <link rel="stylesheet" href="./assets/css/style.css" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" />
    <title>Patient Login</title>
</head>
<body>
    <?php
    session_start();
    $_SESSION["user"]="";
    $_SESSION["usertype"]="";
    date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d');
    $_SESSION["date"]=$date;
    include("connection.php");
    $error = ''; 

    if ($_POST) {
        $email = $_POST['useremail'];
        $password = $_POST['userpassword'];
        $error = '<label for="promter" class="form-label"></label>';
        $result = $database->query("SELECT * FROM webuser WHERE email='$email'");
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $utype = $row['usertype'];
            if ($utype == 'p') {
                $checker = $database->query("SELECT * FROM patient WHERE pemail='$email'");
                $password_column = 'ppassword';
            } elseif ($utype == 'a') {
                $checker = $database->query("SELECT * FROM admin WHERE aemail='$email'");
                $password_column = 'apassword';
            } elseif ($utype == 'd') {
                $checker = $database->query("SELECT * FROM doctor WHERE docemail='$email'");
                $password_column = 'docpassword';
            }
            if ($checker->num_rows == 1) {
                $row = $checker->fetch_assoc();
                if (array_key_exists($password_column, $row)) {
                    $hashed_password_from_db = $row[$password_column];
                    if (password_verify($password, $hashed_password_from_db)) {
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = $utype;
                        if ($utype == 'p') {
                            header('location: patient/index.php');
                        } elseif ($utype == 'a') {
                            header('location: admin/index.php');
                        } elseif ($utype == 'd') {
                            header('location: doctor/index.php');
                        }
                    } else {
                        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                    }
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Error: Password column not found</label>';
                }
            } else {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We can\'t find any account for this email.</label>';
            }
        } else {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We can\'t find any account for this email.</label>';
        }
    }
    ?>

    <div class="emergency-contact">
        <header class="navbar">
        <div class="logo">
        <img src="./assets/images/Logo.png" class="logo-large" alt="Large Logo">
        <img src="./assets/images/Logo.png" class="logo-small" alt="Small Logo">
      </div>
        </header>
        <main class="emergency-contact-inner">
            <section class="frame-parent">
                <div class="login-container">
                    <h2>Patient Login</h2>
                    <form action="" method="POST">
                        <div class="input-group">
                            <input type="email" name="useremail" placeholder="Email" required>
                            <input type="password" name="userpassword" placeholder="Password" required>
                        </div>
                        <div class="form-actions">
                            <a href=""><p class="forgot-password">Forgot Password?</p></a>
                            <button type="submit" id="submit">Login</button>
                        </div>
                        <div>
                            <?php echo $error ?>
                        </div>
                        <p class="register-links">Don't have an account? 
    <a href="patient-signup.html" class="register-link">Register as a Patient</a> 
    <a href="doctor-signup.html" class="register-link">Register as a Doctor</a>
</p>

                    </form>
                </div>
                <img class="public-health-rafiki-1" loading="lazy" alt="" src="./assets/images/Public health-rafiki 1.png" />
            </section>
        </main>
    </div>
    <script>
        document.getElementById("elementButtonSmallFilledContainer").addEventListener("click", function () {
            window.location.href = "index.html";
        });
        document.getElementById("elementButtonSmallFilledContainer1").addEventListener("click", function () {
            window.location.href = "LoginDoctor.html"; 
        });
        document.getElementById("elementButtonSmallFilledContainer2").addEventListener("click", function () {
            window.location.href = "LoginPatient.html"; 
        });
    </script>
</body>
</html>
