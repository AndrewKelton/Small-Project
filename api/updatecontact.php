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

    $host = getenv('DB_HOST');
    $db = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pwd = getenv('DB_PASS');

    $contactID = $inData["contactID"]; 
    $userID = $inData["userID"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $email = $inData["email"];
    $phone = $inData["phone"];


    $conn = new mysqli($host, $user, $pwd, $db);
 
    if ($conn->connect_errno) {
        http_response_code(400);
        header('Content-type: text/plain');
        echo $conn->connect_error;
        exit();
    }

    $stmt = $conn->prepare(
        "SELECT * FROM Contacts WHERE ID = ?"
    );
    
    $stmt->bind_param("i", $contactID);
    $stmt->execute(); 
    $result = $stmt->get_result();
    if($result->num_rows === 0) {
        http_response_code(400);
        $stmt->close();
        $conn->close();
        return;
    }


    $stmt = $conn->prepare(
        "UPDATE Contacts
        SET FirstName = ?, LastName = ?, Email = ?, Phone = ?
        WHERE ID = ?
        LIMIT 1"
    );

    $stmt->bind_param(
        "ssssi", 
         $firstName,
         $lastName, 
         $email, 
         $phone,
         $contactID
    );

    $result = $stmt->execute();

    if ($result === TRUE) 
        returnContactInfo($contactID, $firstName, $lastName, $email, $phone);
    else 
        http_response_code(400);
    
    $stmt->close();
    $conn->close();
?>