<?php
// Include the database connection file
include("connection.php");

$error_message = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data and sanitize it
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $location = htmlspecialchars(trim($_POST['location']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $age = intval($_POST['age']); // Assuming age is an integer

    // Define a secure password pattern using regular expression
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

    // Check if passwords match
    if ($password !== $confirm_password) {
        // Handle password mismatch error
        $error_message = "Passwords do not match!";
    } elseif (!preg_match($password_pattern, $password)) {
        // Check if the password meets the secure pattern
        $error_message = "Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, one number, and one special character.";
    } else {
        // Check if email is already registered
        $stmt = $database->prepare("SELECT * FROM patient WHERE pemail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Handle email already registered error
            $error_message = "Email is already registered!";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
            // Insert new patient into the database using prepared statement
            $full_name = $first_name . ' ' . $last_name;
            $stmt_patient = $database->prepare("INSERT INTO patient (pemail, pname, ppassword, paddress, page, ptel, pgender) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_patient->bind_param("ssssiss", $email, $full_name, $hashed_password, $location, $age, $phone, $gender);
        
            if ($stmt_patient->execute()) {
                // Add patient to webuser table
                $stmt_webuser = $database->prepare("INSERT INTO webuser (email, usertype) VALUES (?, ?)");
                $usertype = 'p'; // Assuming 'p' represents patient
                $stmt_webuser->bind_param("ss", $email, $usertype);
                if ($stmt_webuser->execute()) {
                    exit();
                } else {
                    $error_message = "Error: Could not insert into webuser table. " . $stmt_webuser->error;
                }
            } else {
                $error_message = "Error: Could not insert into patient table. " . $stmt_patient->error;
            }
        }
    }
} else {
    // Handle invalid request method
    $error_message = "Invalid request method!";
}

// Return error message to client-side if any
echo $error_message;
exit(); // Stop further execution
?>
