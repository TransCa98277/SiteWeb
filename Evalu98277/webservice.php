<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
require_once __DIR__ . "/class/getjson.class.php";
$objGetJSON = new GetJSON();
if (!file_exists(_G_TEMP_DIR)) {
	mkdir(_G_TEMP_DIR);
}
$v_action = $_REQUEST['action'];

if($v_action == "fileUpload") {
    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        move_uploaded_file($_FILES['file']['tmp_name'], _G_JSON_DIR . $_FILES['file']['name']);
    }
    if(file_exists(_G_JSON_DIR . $_FILES['file']['name'])) {
        echo json_encode(array(TRUE, "Upload Successful !"));
    } else {
        echo json_encode(array(FALSE, "Upload Failed !"));
    }

} else if ($v_action == "doPost") {

    $v_apiPrev = $_REQUEST['apiPrev'];
    $v_apiNext = $_REQUEST['apiNext'];

    if(!in_array($v_apiPrev, unserialize(_G_API_NO)) || !in_array($v_apiNext, unserialize(_G_API_NO))) {
        echo "<div style='color:red;'>API number incorrect ! API number should be " . implode(" or ", unserialize(_G_API_NO)) . ".</div>";
        die;
    }

    $objResultAPI = $objGetJSON -> getResultAPI($v_apiPrev);
    if(!$objResultAPI[0]) {
        echo "<div style='color:red;'>" . $objResultAPI[2] . "</div>";
        die;
    }

    $v_url = $g_dictApiUrl[$v_apiNext];
    if($v_apiNext == "1") {
                     
    } else if ($v_apiNext == "2") {
        $v_url = $v_url . "?action=receivePost";
    } else if ($v_apiNext == "3") {
        $v_url = $v_url . "AcceptInput";
    } else if ($v_apiNext == "4") {
        
    }
    
    $strJsonContent = json_encode($objResultAPI[1]);

    $file = fopen("query_v_url.txt", "w");
    fwrite($file, $v_apiNext . "|" . $v_url . "\n\n");
    fclose($file);

    $ch = curl_init($v_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $strJsonContent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($strJsonContent)));
    //curl_setopt($ch, CURLOPT_TIMEOUT, 180);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    $result = curl_exec($ch);
    curl_close($ch);

    if ($v_apiNext == "2") {
        if(!empty($result)) {
            echo json_encode(array(TRUE, "The data has been posted to API " . $v_apiNext . ". The processed data will be displayed on the web page."));
        } else {
            echo json_encode(array(FALSE, "Communication failed with API " . $v_apiNext . ", please check if the API " . $v_apiNext . " is running or try with another API."));
        }
    } else {
        echo json_encode(array(TRUE, "POST has been done. The processed data will be displayed on the web page."));
    }

} else if ($v_action == "getResultAPI") {

    $v_api = $_REQUEST['api'];
    if(!in_array($v_api, unserialize(_G_API_NO))) {
        echo "<div style='color:red;'>API number incorrect ! API number should be " . implode(" or ", unserialize(_G_API_NO)) . ".</div>";
        die;
    }
    $arrFilter = json_decode($_REQUEST['filter'], TRUE);
    if($v_api == "0") {
        $objResultAPI = $objGetJSON -> getResultAPI($v_api, $arrFilter);
        if(!$objResultAPI[0]) {
            echo "<div style='color:red;'>" . $objResultAPI[2] . "</div>";
            die;
        }
        echo json_encode($objResultAPI[1]);

    } else {

        echo $objGetJSON -> downloadResultAPI($g_dictApiUrl[$v_api], $v_api, $_REQUEST['filter'], $arrFilter);

    }

} else {
    if(empty($v_action)) {
        echo "<div style='color:red;'>Action is NULL !</div>";
    } else {
        echo "<div style='color:red;'>Action error : " . $v_action . " !</div>";
    }
    die;
}

?>