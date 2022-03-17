<?php
require_once __DIR__ . "/../config/config.php";

class GetJSON {

    private $BdrvUrl;
    
    function __construct() {
        $this -> BdrvUrl = _G_BDRV_URL;
	}

    function getResultAPI($v_api, $arrFilter = NULL) {
        $localFileName = "api" . $v_api . ".json";
        if($v_api == 0) {
            $localFileName = _G_START_FILE_NAME;
        }
        $localFilePath = _G_JSON_DIR . $localFileName;
        if (!file_exists($localFilePath)) {
            return array(FALSE, NULL, "The file `" . $localFilePath . "` was not found !");
        }
        $strJsonContent = file_get_contents($localFilePath);
        $objJsonContent = json_decode($strJsonContent, TRUE);
        //LI 2022-03-13 do filter
        $objJsonContentDoFilter = array();
        foreach ($arrFilter as $k_arrFilter => $v_arrFilter) {
            $arrTmpFilter = explode("|", $v_arrFilter);
            foreach ($objJsonContent as $k_objJsonContent => $v_objJsonContent) {
                foreach ($v_objJsonContent as $k_v_objJsonContent => $v_v_objJsonContent) {
                    if($k_v_objJsonContent == $arrTmpFilter[0]) {
                        if(stripos($v_v_objJsonContent, $arrTmpFilter[1]) !== false) {
                            $objJsonContentDoFilter[] = $v_objJsonContent;
                        }
                    }
                }
            }
        }
        if(!empty($objJsonContentDoFilter)) {
            return array(TRUE, $objJsonContentDoFilter, "The result of API " . $v_api . " was found. The Filter " . $arrTmpFilter[0] . " like " . $arrTmpFilter[1] . " has been applied.");
        } else {
            return array(TRUE, $objJsonContent, "The result of API " . $v_api . " was found.");
        }
    }

    function downloadResultAPI($v_url, $v_api, $v_filter, $arrFilter) {
        if($v_api == "1") {
            if(!empty($arrFilter)) {
                $fieldName = "";
                $fieldValue = "";
                foreach ($arrFilter as $k_arrFilter => $v_arrFilter) {
                    $arrTmp = explode("|", $v_arrFilter);
                    $fieldName = $arrTmp[0];
                    $fieldValue = $arrTmp[1];
                }
                if(!empty($fieldValue)) {
                    $v_url = $v_url . $fieldValue;
                }
            }       
        } else if ($v_api == "2") {
            $v_url = $v_url . "?action=getResultAPI&api=2&recur=n&filter=" . $v_filter;
        } else if ($v_api == "3") {
            if(empty($arrFilter)) {
                $v_url = $v_url . "GetAllData";
            } else {
                $fieldName = "";
                $fieldValue = "";
                foreach ($arrFilter as $k_arrFilter => $v_arrFilter) {
                    $arrTmp = explode("|", $v_arrFilter);
                    $fieldName = $arrTmp[0];
                    $fieldValue = $arrTmp[1];
                }
                if($fieldName == "recallNumber") {
                    $v_url = $v_url . "GetByRecallNumber/" . $fieldValue;
                } else if ($fieldName == "SYSTEM_TYPE_ETXT") {
                    $v_url = $v_url . "GetBySystemType/" . $fieldValue;
                } else if ($fieldName == "SYSTEM_TYPE_FTXT") {
                    $v_url = $v_url . "GetBySystemType/" . $fieldValue;
                }
            }
        } else if ($v_api == "4") {

        }

        $file = fopen("query_v_url.txt", "w");
        fwrite($file, $v_api . "|" . $v_url . "\n\n");
        fclose($file);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $v_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $strObjJson = curl_exec($ch);
        curl_close($ch);

        $localFileName = "api" . $v_api . ".json";
        $localFilePath = _G_JSON_DIR . $localFileName;

        $file = fopen($localFilePath, "w");
        fwrite($file, $strObjJson);
        fclose($file);

        $objJsonFormatter = json_decode($strObjJson, TRUE);
        $strObjJsonFormatter = json_encode($objJsonFormatter, JSON_UNESCAPED_UNICODE);
        return $strObjJsonFormatter;
    }

    function strEncode($str, $action) {
        if($action == "w") {
            $str = mb_convert_encoding($str, "ISO-8859-15", "UTF-8");
        }
        if($action == "r") {
            $str = mb_convert_encoding($str, "UTF-8", "ISO-8859-15");
        }
        return $str;
    }

    function rrmdir($dir, $removeSelf = TRUE) {
		if (is_dir($dir)) {
			$objects = scandir($dir); 
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") { 
					if (is_dir($dir . "/" . $object)) {
						rrmdir($dir . "/" . $object);
					}
					else {
						unlink($dir . "/" . $object);
					}
				}
			}
			if($removeSelf) {
				rmdir($dir);
			}
		}
	}
}
?>