<?php
DEFINE("_G_BDRV_URL", "https://data.tc.gc.ca/v1.3/api/eng/vehicle-recall-database/recall-summary/recall-number/[recall-number]?format=json");
DEFINE("_G_TEMP_DIR", __DIR__ . "/../tmp/");
DEFINE("_G_JSON_DIR", __DIR__ . "/../json/");

$g_dictParameterStepAPI = array();
$g_dictParameterStepAPI["1"][] = "MANUFACTURER_RECALL_NO_TXT";
$g_dictParameterStepAPI["2"][] = "CATEGORY_ETXT";
$g_dictParameterStepAPI["2"][] = "CATEGORY_FTXT";
$g_dictParameterStepAPI["3"][] = "SYSTEM_TYPE_ETXT";
$g_dictParameterStepAPI["3"][] = "SYSTEM_TYPE_FTXT";
$g_dictParameterStepAPI["4"][] = "NOTIFICATION_TYPE_ETXT";
$g_dictParameterStepAPI["4"][] = "NOTIFICATION_TYPE_FTXT";

DEFINE("_G_API_NO", serialize(array("0", "1", "2", "3", "4")));
DEFINE("_G_MY_API", "2");
DEFINE("_G_START_FILE_NAME", "CSCompVehicleRecallStart.json");

$g_dictApiUrl = array();
$g_dictApiUrl[0] = "http://localhost/Evalu98277/webservice.php";//web page controller
$g_dictApiUrl[1] = "http://localhost:5000/api1/Vehicles/";//API1
$g_dictApiUrl[2] = "http://localhost/TransCa98277/webservice.php";//API2
$g_dictApiUrl[3] = "http://localhost:5004/api/VehicleRecall/";//API3
$g_dictApiUrl[4] = "";//API4
?>