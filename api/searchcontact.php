<?php
    // Load .env file
    $envFile = DIR . '/../.env';
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
    $firstName = $inData["firstName"] ?? "";
    $lastName = $inData["lastName"] ?? "";

    // database connection
    $conn = new mysqli($host, $user, $pwd, $db);

    // database connection error
    if ($conn->connect_errno) {
        http_response_code(400);
        header('Content-type: text/plain');
        echo $conn->connect_error;
        exit();
    }
    // Validation
    if (empty($contactID)) {
        returnWithError("Contact ID is required");
        $conn->close();
        exit();
    }
    // Check if contact exists
    $stmt = $conn->prepare("SELECT * FROM Contacts WHERE ID = ?");
    $stmt->bind_param("i", $contactID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        returnWithError("Contact not found");
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt->close();

    // Delete the contact
    $stmt = $conn->prepare("DELETE FROM Contacts WHERE ID = ? LIMIT 1");
    $stmt->bind_param("i", $contactID);

    // Build search query with partial, case-insensitive matching
    // Returns records where EITHER firstName OR lastName partially matches
    $stmt = $conn->prepare("SELECT * FROM Contacts WHERE LOWER(firstName) LIKE ? OR LOWER(lastName) LIKE ?");

    if (!$stmt) {
        http_response_code(500);
        header('Content-type: application/json');
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        $conn->close();
        exit();
    }

    // Add wildcards for partial matching
    $firstNameParam = "%" . strtolower($firstName) . "%";
    $lastNameParam = "%" . strtolower($lastName) . "%";

    $stmt->bind_param("ss", $firstNameParam, $lastNameParam);
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
