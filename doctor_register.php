<?php
// Include the database connection file
include("connection.php");

$error_message = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $location = $_POST['location'];
    $speciality = $_POST['speciality'];
    $phone = $_POST['phone'];

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
        $stmt = $database->prepare("SELECT * FROM doctor WHERE docemail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Handle email already registered error
            $error_message = "Email is already registered!";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
            // Insert new doctor into database using prepared statement
            $full_name = $first_name . ' ' . $last_name; // Concatenate first name and last name
            $stmt_doctor = $database->prepare("INSERT INTO doctor (docemail, docname, docpassword, doclocation, specialties, doctel) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_doctor->bind_param("ssssss", $email, $full_name, $hashed_password, $location, $speciality, $phone);
        
            $stmt_doctor->execute();
        
            // Add doctor to webuser table
            $stmt_webuser = $database->prepare("INSERT INTO webuser (email, usertype) VALUES (?, ?)");
            $usertype = 'd'; // Assuming 'd' represents doctor
            $stmt_webuser->bind_param("ss", $email, $usertype);
            $stmt_webuser->execute();
            
            // Redirect to doctor's dashboard upon successful registration
            exit(); // Stop further execution
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
