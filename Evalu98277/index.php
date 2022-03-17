<?php
/**
 * Examen-20-MOT-EA-HRS-98277_Team 1 Test Page - Team Member : Richard TCHICOU (API 1), Xiaogang LI (API 2), Joseph KASUMBA (API 3)
 * 
 * @author : Xiaogang LI
 * @version : 1.0
 * @date : 2022-03-17
 * 
 * This page is for control the data flow between the different APIs
 */
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TransCA98277 Test Page - Team Member : Richard TCHICOU (API 1), Xiaogang LI (API 2), Joseph KASUMBA (API 3)</title>
    <link rel="stylesheet" href="js/lib/jquery-ui-1.13.1/jquery-ui.css">
    <script src="js/lib/jquery-3.6.0/jquery.js"></script>
    <script src="js//lib/jquery-ui-1.13.1/jquery-ui.js"></script>
    <script src="js/index.js"></script>
  </head>
  <body>
    <div style="font-size:20px;color:blue;">Examen-20-MOT-EA-HRS-98277_Team 1 Test Page - Team Member : Richard TCHICOU (API 1), Xiaogang LI (API 2), Joseph KASUMBA (API 3)<br>This page is for control the data flow between the different APIs</div><br>
    <div>
        <table>
            <tr>
                <td>
                    Upload Start File 
                </td>
                <td>
                    <input id="startFile" type="file" name="startFile" />
                </td>
                <td>
                    <button id="btnUpload" style="font-size:10px;">UPLOAD</button>
                </td>
            </tr>
            <tr>
                <td>
                    GET RESULT FROM 
                </td>
                <td>
                    <select id="fromAPI" style="width:100px;">
                        <option value="0">StartFile</option>
                        <option value="1">API 1</option>
                        <option value="2">API 2</option>
                        <option value="3">API 3</option>
                        <option value="4">API 4</option>
                    </select>
                </td>
                <td>
                    &nbsp;Filter : Recall Number contains <input type="text" id="txtRecallNumber" />
                </td>
                <td>
                    <button id="btnGetResultAPI" style="font-size:10px;">APPLY FILTER</button>&nbsp;<button id="btnClearFilter" style="font-size:10px;">CLEAR FILTER</button>
                </td>
            </tr>
            <tr>
                <td>
                    POST RESULT TO
                </td>
                <td>
                    <select id="toAPI" style="width:100px;">
                        <option value="1">API 1</option>
                        <option value="2">API 2</option>
                        <option value="3">API 3</option>
                        <option value="4">API 4</option>
                    </select>
                </td>
                <td>
                    <button id="btnDoPost" style="font-size:10px;">POST</button>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <div>
        <table>
            <tr>
                <td id="tdTitleAPI"></td>
            </tr>
            <tr trFilterAPI style="display:none;">
                <td>
                    Filter&nbsp;:&nbsp;<select id="selFilterBy"></select>&nbsp;contains&nbsp;<input type="text" id="txtFilterValue" />&nbsp;<button id="btnDoSearch" style="font-size:10px;">SEARCH BY KEYWORDS</button>&nbsp;<button id="btnClearFilterAPI" style="font-size:10px;" onclick="$('#btnClearFilter').trigger('click');">CLEAR FILTER</button>
                </td>
            </tr>
            <tr>
                <td>
                    <div id="divMsg" style="color:blue;">
                    </div>
                </td>
            </tr>
        </table>  
    </div>
  </body>
</html>

<?php
    require_once __DIR__ . "/lib/dialog.loading.php"
?>
