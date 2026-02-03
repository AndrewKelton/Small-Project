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

    // frontend input parameters
    $userID = $inData["UserID"];

    // database connection
    $conn = new mysqli($host, $user, $pwd, $db);

    // database connection error
    if ($conn->connect_error) {
        http_response_code(400);
        header('Content-type: text/plain');
        echo $conn->connect_error;
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM Contacts WHERE UserID = ?");
    $stmt->bind_param("i", $userID);

    if($stmt->execute()) {
        $result = $stmt->get_result();
        $contacts = $result->fetch_all(MYSQLI_ASSOC);

        sendResultInfoAsJson(json_encode($contacts));
    }
    else {
        returnWithError("MySQL statment failed to run");
        exit();
    }

    $stmt->close();
    $conn->close();
?>