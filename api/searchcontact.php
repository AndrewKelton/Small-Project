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
    $userID = $inData["userID"];
    $firstName = $inData["firstName"] ?? "";
    $lastName = $inData["lastName"] ?? "";
    $pageNumber = max(1, $inData["PageNumber"] ?? 1);


    // rows per page
    $rowsPerPage = 20;
    // pagination offset
    $offsetRow = ($pageNumber - 1) * $rowsPerPage;

    // database connection
    $conn = new mysqli($host, $user, $pwd, $db);

    // database connection error
    if ($conn->connect_errno) {
        http_response_code(400);
        header('Content-type: text/plain');
        echo $conn->connect_error;
        exit();
    }

    // Case 1: Both empty — return empty result
    if ($firstName === "" && $lastName === "") {
        header('Content-type: application/json');
        echo json_encode([]);
        $conn->close();
        exit();
    }

    // Case 2: Only first name provided
    if ($firstName !== "" && $lastName === "") {
        $stmt = $conn->prepare("SELECT * FROM Contacts WHERE UserID = ? AND LOWER(FirstName) LIKE ? LIMIT ? OFFSET ?");
        $firstNameParam = "%" . strtolower($firstName) . "%";
        $stmt->bind_param("isii", $userID, $firstNameParam, $rowsPerPage, $offsetRow);
    }
    // Case 3: Only last name provided
    else if ($firstName === "" && $lastName !== "") {
        $stmt = $conn->prepare("SELECT * FROM Contacts WHERE UserID = ? AND LOWER(LastName) LIKE ? LIMIT ? OFFSET ?");
        $lastNameParam = "%" . strtolower($lastName) . "%";
        $stmt->bind_param("isii", $userID, $lastNameParam, $rowsPerPage, $offsetRow);
    }
    // Case 4: Both first and last name provided
    else {
        $stmt = $conn->prepare("SELECT * FROM Contacts WHERE UserID = ? AND (LOWER(FirstName) LIKE ? OR LOWER(LastName) LIKE ?) LIMIT ? OFFSET ?");
        $firstNameParam = "%" . strtolower($firstName) . "%";
        $lastNameParam = "%" . strtolower($lastName) . "%";
        $stmt->bind_param("issii", $userID, $firstNameParam, $lastNameParam, $rowsPerPage, $offsetRow);
    }

    if (!$stmt) {
        http_response_code(500);
        header('Content-type: application/json');
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        $conn->close();
        exit();
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Collect all matching records
    $contacts = [];
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }

    $stmt->close();
    $conn->close();

    // Return results as JSON
    header('Content-type: application/json');
    echo json_encode($contacts);

?>