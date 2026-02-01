<?php
    // Load .env file
    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                putenv(trim($key) . '=' . trim($value));
            }
        }
    }

    require 'helpers.php';

    $inData = getRequestInfo(); 

    // Get DB credentials
    $host = getenv('DB_HOST');
    $db = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pwd = getenv('DB_PASS');

    $userID = $inData["userID"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $email = $inData["email"];
    $phone = $inData["phone"];
    
    $conn = new mysqli($host, $user, $pwd, $db);

    if ($conn->connect_error) {
        http_response_code(400);
        header('Content-type: text/plain');
        echo $conn->connect_error;
        exit();
    }

    // Create contact logic here
    $stmt = $conn->prepare("INSERT INTO Contacts (UserID, FirstName, LastName, Email, Phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $userID, $firstName, $lastName, $email, $phone);

    if ($stmt->execute() === TRUE) {
        $contactID = $conn->insert_id;
        returnContactInfo($contactID, $firstName, $lastName, $email, $phone);
    } else 
        http_response_code(400);
    
    $stmt->close();
    $conn->close();

    // Validation
    if (empty($firstName) || empty($lastName) || empty($userID)) {
        returnWithError("First name, last name, and user ID are required");
        $conn->close();
        exit();
    }
?>
