<?php
    function getRequestInfo() {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj) {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithError($msg){
        $retValue = '{"error":"' . $msg . '"}';
        sendResultInfoAsJson($retValue);
    }
    function returnContactInfo($id, $firstName, $lastName, $email, $phone) {
        $retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","email":"' . $email . '","phone":"' . $phone . '","error":""}';
        sendResultInfoAsJson($retValue);
    }

    function returnContacts($contacts){
        $retValue = '{"Contacts":' . json_encode($contacts) . ',"error": ""}';
        sendResultInfoAsJson($retValue);
    }
	function returnUserInfo($id, $firstName, $lastName){
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>