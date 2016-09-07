<?php
	
function jsonDecode($data) {

	if (empty($data)) return array();

    $items = json_decode($data, true);

    if ($items == NULL){
        throw new Exception('JSON items could not be decoded');
    }

    return $items;
}

function isValidEmail($email){ 
    return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
}

function html_spaces($number=1) {
    $result = "";
    for($i = 1; $i <= $number; $i++) {
        $result .= "&nbsp;";
    }
    return $result;
}

?>