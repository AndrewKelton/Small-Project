<?php
    $inData = getRequestInfo();

    // Fill in with exact SQL database specs
    // get from .env file
    // $host = "localhost:8000";
    // $db = "XXXXXXX";
    // $user = "root";
    // $pwd = "XXXXXX";
    $host = getenv('DB_HOST');
    $db = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pwd = getenv('DB_PASS');

    $ID = 0;
    $firstName = "";
    $lastName = "";

    $conn = new mysqli($host, $user, $pwd, $db);
 
    if($conn->connect_errno){
        http_response_code(400);
        header('Content-type: text/plain');
        echo $conn->connect_error;
        exit();
    }
    else {
        $stmt = $conn->prepare("SELECT ID, FirstName, LastName FROM Users WHERE (Login=? AND Password=?)");
        $stmt->bind_param("ss", $inData["login"], $inData["password"]);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()){
            returnWithInfo($row['FirstName'], $row['LastName'], $row['ID']);
        }
        else{
            returnWithError("No records found");
        }
        $stmt->close();
        $conn->close();
    }

    function getRequestInfo(){
        $data = json_decode(file_get_contents('php://input'), true);
        return $data;
    }
    
    function sendResultInfoAsJson($obj){
        header('Content-type: application/json');
        echo $obj;
    }   

    function returnWithError( $err )
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $firstName, $lastName, $id )
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>