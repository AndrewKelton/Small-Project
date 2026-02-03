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

    // Fill in with exact SQL database specs
    $host = getenv('DB_HOST');
    $db = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pwd = getenv('DB_PASS');

    $ID = 0;
    $firstName = "";
    $lastName = "";

    $conn = new mysqli($host, $user, $pwd, $db);
 
    // error check for db connection
    if($conn->connect_errno) {
        http_response_code(400);
        header('Content-type: text/plain');
        echo $conn->connect_error;
        exit();
    }
 
    // prepare mysql statement to find user in table
    $stmt = $conn->prepare("SELECT ID, FirstName, LastName FROM Users WHERE (Login=? AND Password=?)");

    // check if stmt is null -> mysql error
    if (!$stmt) {
        echo json_encode([
            "id" => 0,
            "error" => $conn->error
        ]);
        exit();
    }

    $stmt->bind_param("ss", $inData["login"], $inData["password"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        returnUserInfo( $row['ID'], $row['FirstName'], $row['LastName']);
    }
    else {
        $err = "No records found";
        $retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }
    
    $stmt->close();
    $conn->close();
?>