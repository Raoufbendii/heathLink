<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Doctors</title>
    <style>
        .main-container {
            display: flex;
            height: 100vh;
        }

        .menu {
            /* Your existing menu styles here */
        }

        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .chat-box {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
            max-height: calc(100vh - 150px);
        }

        .message {
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 10px;
            width: 100%;
            word-wrap: break-word;
        }

        .received {
            background-color: #d6e6ff;
            text-align: left;
        }

        .sent {
            background-color: #f0f0f0;
            text-align: right;
        }

        .chat-input {
            padding: 20px;
            background-color: #ffffff;
            border-top: 1px solid #ccc;
            display: flex;
            align-items: center;
        }

        .chat-input input {
            flex-grow: 1;
            margin-right: 10px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 25px;
            outline: none;
            transition: border-color 0.3s;
            font-size: 16px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow-wrap: break-word;
            word-break: break-all;
            text-align: left;
        }

        .chat-input input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .chat-input button {
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .chat-input button i {
            margin-right: 5px;
        }

        .chat-input button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .chat-input button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        }

        @media (max-width: 768px) {
            .button-container {
                flex-direction: column;
            }

            .dashboard-image img {
                margin: 10px 0;
            }
        }
        @keyframes typing {
    0% {
        opacity: 0.5;
    }
    50% {
        opacity: 1;
    }
    100% {
        opacity: 0.4;
    }
}

.typing-animation::after {
    content: ".";
    animation: typing 1s infinite;
}


        .specialist-buttons {
            margin-top: 20px;
        }
        .specialist-buttons button {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            background-color: #e74c3c;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php

//learn from w3schools.com

session_start();

if(isset($_SESSION["user"])){
    if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
        header("location: ../login.php");
    }else{
        $useremail=$_SESSION["user"];
    }

}else{
    header("location: ../login.php");
}


//import database
include("../connection.php");

$sqlmain= "select * from patient where pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s",$useremail);
$stmt->execute();
$result = $stmt->get_result();
$userfetch = $result->fetch_assoc(); // Fetch the result from the executed query
if ($userfetch) {
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];
}



//echo $userid;
//echo $username;



date_default_timezone_set('Asia/Kolkata');

$today = date('Y-m-d');


//echo $userid;
?>

    <div class="main-container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username, 0, 13) ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                    <td class="menu-btn menu-icon-home menu-icon-home-active" >
                        <a href="index.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
    <td class="menu-btn menu-icon-chat menu-active">
        <a href="chat.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Chat</p></div></a>
    </td>                
    <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-schedule">
                        <a href="schedule-patient.php" class="non-style-link-menu"><div><p class="menu-text">Schedule</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>

                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
</a>
</td>
</tr>
</table>
</div>
<div class="chat-container">
<td colspan="1" class="nav-bar">
<p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;">Patient HealthBot Chat</p>
</td>
<td width="25%">
<div class="chat-box" id="chat-box">
<div class="message received">
<p>Hello! How can I assist you today?</p>
</div>
<!-- Previous chat messages go here -->
</div>
<div class="chat-input">
<input id="user-input" type="text" placeholder="Type your message...">
<button id="send-btn" type="button"><i class="fas fa-paper-plane"></i> Send</button>
</div>
</td>
</div>
</div>
<script>
    const chatBox = document.getElementById('chat-box');
    const userInput = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');

  // Generate a session ID with current date and time
let sessionID = generateSessionID();

function generateSessionID() {
    const currentDate = new Date();
    const randomString = Math.random().toString(36).substr(2, 9);
    const formattedDate = currentDate.toISOString().split('T')[0].replace(/-/g, '');
    const formattedTime = currentDate.toTimeString().split(' ')[0].replace(/:/g, '');
    return `${randomString}-${formattedDate}-${formattedTime}`;
}

    // Check if the required elements exist before adding event listeners
    if (sendBtn && userInput) {
        sendBtn.addEventListener('click', sendMessage);
        userInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    } else {
        console.error('Required elements not found in the DOM.');
    }

    function sendMessage() {
    const userInput = document.getElementById('user-input').value.trim();
    if (userInput !== '') {
        displayMessage(userInput, 'sent');
        displayTypingAnimation(); // Add typing animation while waiting for response

        sendMessageToServer(userInput)
            .then(response => {
                removeTypingAnimation(); // Remove typing animation once response received
                
                // Check if specialists are not empty
                if (response.spe && response.spe.length > 0) {
                    // Display specialists
                    displaySpecialists(response.spe);
                }

                // Display AI response
                displayMessage(response.response, 'received');
            })
            .catch(error => {
                console.error('Error sending message:', error);
                removeTypingAnimation(); // Remove typing animation if error occurs
                displayMessage('Oops! Something went wrong.', 'received');
            });

        document.getElementById('user-input').value = '';
    }
}

function displaySpecialists(specialists) {
    // Remove any existing specialist buttons before appending new ones
    const existingSpecialistDiv = chatBox.querySelector('.specialist-buttons');
    if (existingSpecialistDiv) {
        existingSpecialistDiv.remove();
    }
    
    // Append specialist buttons only if there are specialists
    if (specialists.length > 0) {
        specialists.forEach(specialist => {
            const specialistButton = document.createElement('button');
            specialistButton.textContent = specialist;
            specialistButton.onclick = function() {
                // Handle specialist button click event
                // You can implement your logic here, like forwarding the user to a specialist page
                console.log('Clicked on specialist:', specialist);
            };
            
            // Create a container div for each specialist button
            const specialistDiv = document.createElement('div');
            specialistDiv.classList.add('specialist-buttons');
            specialistDiv.appendChild(specialistButton);
            
            chatBox.appendChild(specialistDiv);
        });

        chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
    }
}






function displayTypingAnimation() {
    const typingIndicator = document.createElement('div');
    typingIndicator.classList.add('message', 'received');
    typingIndicator.innerHTML = `<p><span class="typing-animation">...</span></p>`;
    chatBox.appendChild(typingIndicator);
    chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
}

function removeTypingAnimation() {
    const typingIndicator = chatBox.querySelector('.typing-animation');
    if (typingIndicator) {
        typingIndicator.parentNode.parentNode.remove();
    }
}

function displayMessage(message, messageType) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', messageType);
    messageDiv.innerHTML = `<p>${message}</p>`;
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
}


    function sendMessageToServer(message) {
        const serverUrl = 'http://127.0.0.1:7000/chat';
        return fetch(serverUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ prompt: message, sessionID: sessionID }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        });
    }
</script></body>
</html>