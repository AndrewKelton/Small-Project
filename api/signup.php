<?php
    $inData = getRequestInfo();

    // Fill in with exact SQL database specs
    $host = "localhost:8000";
    $db = "XXXXXXX";
    $user = "root";
    $pwd = "XXXXXX";

    // Sign-up parameters
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $login = $inData["login"];
    $password = $inData["password"];

    $conn = new mysqli($host, $user, $pwd, $db);

    if ($conn->connect_errno){
        http_response_code(400);
        header('Content-type: text/plain');
        echo $conn->connect_error;
        exit();
    } 

    // Validation
    if(empty($firstName) || empty($lastName) || empty($login) || empty($password)){
        http_response_code(400);
        header('Content-type: application/json');
        echo json_encode(["Error" => "All fields required"]);
        $conn->close();
        exit();
    }

    // Check password length
    if(strlen($password) < 5) {
        returnWithError("Password but be at least 5 characters");
        exit();
    }

    // Check if login exists
    $stmt = $conn->prepare("SELECT ID FROM Users WHERE Login=?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0){
        returnWithError("Username already exists!");
    }
    else {
        // Create new user
        $stmt = $conn->prepare("INSERT INTO Users (FirstName, LastName, Login, Password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $login, $password);

        if ($stmt->execute()){
            $newUserID = $conn->insert_id;
            returnWithInfo($firstName, $lastName, $newUserID);
        }
        else {
            returnWithError("Error: Failed to create new account");
        }
    }

    $stmt->close();
    $conn->close();


?>