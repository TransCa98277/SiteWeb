var g_dictApiUrl = new Array();
g_dictApiUrl[0] = "http://localhost/Evalu98277/webservice.php";//web page controller
g_dictApiUrl[1] = "http://localhost:5000/api1/Vehicles/";//API1
g_dictApiUrl[2] = "http://localhost/TransCa98277/webservice.php";//API2
g_dictApiUrl[3] = "http://localhost:5004/api/VehicleRecall/";//API3
g_dictApiUrl[4] = "";//API4

var g_dictParameterStepAPI = {
    "1": ["MANUFACTURER_RECALL_NO_TXT"],
    "2": ["CATEGORY_ETXT", "CATEGORY_FTXT"],
    "3": ["SYSTEM_TYPE_ETXT", "SYSTEM_TYPE_FTXT"],
    "4": ["NOTIFICATION_TYPE_ETXT", "NOTIFICATION_TYPE_FTXT"]
};

var selFilterBy_default = "";

function getResultAPI() {
    $("#divMsg").html("");
    $("[trFilterAPI]").hide();
    var v_api = $("#fromAPI").val();
    var t_api = $("#fromAPI option:selected").text()
    var v_apiUrl = g_dictApiUrl[v_api];
    if (isEmpty(v_apiUrl)) {
        alert("URL of " + t_api + " is NULL, can not load the result of " + t_api + ", please try with another API.");
        return;
    }
    var arrFilter = new Array();
    if(!isEmpty($("#txtRecallNumber").val())) {
        arrFilter.push("recallNumber|" + $("#txtRecallNumber").val());
    }
    if(!isEmpty($("#selFilterBy").val()) && !isEmpty($("#txtFilterValue").val())) {
        arrFilter.push($("#selFilterBy").val() + "|" + $("#txtFilterValue").val());
    }
    $("#msgLoading").html("Loading the result from " + t_api + " ...");
    $("#divLoading").dialog("open");
    $.post(g_dictApiUrl[0], {
        "action": "getResultAPI",
        "api": v_api,
        "recur": "n",
        "filter": JSON.stringify(arrFilter)
    }, function (data, textStatus) {
        $("#divLoading").dialog("close");
        loadFilter(parseInt($("#fromAPI").val()));
        $("#divMsg").html(data);
    });
}

function loadFilter(apiNo) {
    $("[trFilterAPI]").hide();
    var arrHtmlFilterBy = new Array();
    if (!isEmpty(g_dictParameterStepAPI[apiNo])) {
        g_dictParameterStepAPI[apiNo].forEach(element => {
            arrHtmlFilterBy.push("<option value='" + element + "'>" + element + "</option>");
        });
        if (!isEmpty(arrHtmlFilterBy)) {
            $("#selFilterBy").html(arrHtmlFilterBy.join(""));
            $("[trFilterAPI]").show();
        } else {
            $("[trFilterAPI]").hide();
        }
    } else {
        $("[trFilterAPI]").hide();
    }
    if(!isEmpty(selFilterBy_default)) {
        $("#selFilterBy").val(selFilterBy_default);
    }
}

function isEmpty(val) {
    if (val != "" && val != null && typeof (val) !== "undefined") {
        return false;
    } else {
        return true;
    }
}

$(function () {

    $("#selFilterBy").change(function() {
        selFilterBy_default = $(this).val();
    });

    $('#btnUpload').button({
        icons: {
            primary: "ui-icon-arrowthick-1-n"
        }
    }).on('click', function() {
        if(isEmpty($('#startFile').val())) {
            alert("Please select a file to upload.");
            return;
        }
        var file_data = $('#startFile').prop('files')[0];
        var form_data = new FormData();                  
        form_data.append('file', file_data);                             
        $.ajax({
            url: g_dictApiUrl[0] + '?action=fileUpload',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,                         
            type: 'post',
            success: function(php_script_response){
                var objData = $.parseJSON(php_script_response);
                alert(objData[1]);
                if(objData[0]) {
                    $("#fromAPI").val("0");
                    getResultAPI();
                }
            }
         });
    });

    $("#tdTitleAPI").html("Result of " + $("#fromAPI option:selected").text() + " : ");
    getResultAPI();

    $("#fromAPI").change(function() {
        $("#tdTitleAPI").html("Result of " + $("#fromAPI option:selected").text() + " : ");
        getResultAPI();
    });

    $("#btnGetResultAPI").button({
        icons: {
            primary: "ui-icon-arrowthick-1-s"
        }
    }).click(function () {
        $("#txtFilterValue").val("");
        getResultAPI();
    });

    $("#btnClearFilter").button({
        icons: {
            primary: "ui-icon-refresh"
        }
    }).click(function () {
        $("#txtRecallNumber").val("");
        $("#txtFilterValue").val("");
        getResultAPI();
    });

    $("#btnClearFilterAPI").button({
        icons: {
            primary: "ui-icon-refresh"
        }
    })

    $("#btnDoPost").button({
        icons: {
            primary: "ui-icon-arrowthick-1-e"
        }
    }).click(function () {

        var v_apiPrev = $("#fromAPI").val();
        var t_apiPrev = $("#fromAPI option:selected").text();

        var v_apiNext = $("#toAPI").val();
        var t_apiNext = $("#toAPI option:selected").text();

        var v_apiUrl = g_dictApiUrl[v_apiNext];
        if (isEmpty(v_apiUrl)) {
            alert("URL of " + t_apiNext + " is NULL, please try with another API.");
            return;
        }
        if (parseInt(v_apiPrev) >= parseInt(v_apiNext)) {
            alert("Can not post " + t_apiPrev + " to " + t_apiNext + " !");
            return;
        }
        var v_contentPrev = $("#divMsg").html();
        if(isEmpty(v_contentPrev)) {
            alert("The result of select API is NULL, can not POST, please try with another API.");
            $("#toAPI").focus();
            return;
        }
        if (!confirm("The system will POST " + t_apiPrev + " to " + t_apiNext + ", continue ?")) {
            return;
        }
        $("#msgLoading").html("Posting the result of " + t_apiPrev + " to " + t_apiNext + " ...");
        $("#divLoading").dialog("open");
        $.post(g_dictApiUrl[0], {
            "action" : "doPost",
            "apiPrev" : v_apiPrev,
            "apiNext" : v_apiNext
        }, function (data, textStatus) {
            $("#divLoading").dialog("close");
            setTimeout(function(){
                var objData = $.parseJSON(data);
                alert(objData[1]);
                if(objData[0]) {
                    if(parseInt(v_apiNext) < 4) {
                        $("#fromAPI").val(parseInt(v_apiNext));
                        $("#toAPI").val(parseInt(v_apiNext)+1);
                        getResultAPI();
                    }
                }
            }, 500);
            
        });
    });

    $("#btnDoSearch").button({
        icons: {
            primary: "ui-icon-search"
        }
    }).click(function () {
        $("#txtRecallNumber").val("");
        getResultAPI();
    });
});