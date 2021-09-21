<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://static.line-scdn.net/liff/edge/versions/2.12.0/sdk.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <title>LIFF - LINE RMX-E Official Dev</title>
    <style>
        body {
            background-color: #fdd779;
        }

        .no-close .ui-dialog-titlebar-close {
            display: none
        }

        .ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset {
            text-align: center;
            float: none !important;
        }

        .ui-button.acceptButton {
            font-size: large;
            border: 1px solid #00b050;
            background-color: #00b050;
            color: #fff;
        }

        .ui-button.declineButton {
            font-size: large;
            border: 0px solid #fff;
            background-color: #fff;
            color: #FF0000;
        }
    </style>
</head>

<body>

    <div id="dialogMsg"></div>
    <div class="container">
        <div class="col-12">
            <form id="registerForm">
                <h1>Register Member</h1>
                <div class="form-group">
                    <label>Company Code </label>
                    <select class="form-control" name="CompanyCode" id="CompanyCode">
                    </select>
                </div>
                <div class="form-group" id="registerSecond" hidden>
                    <p>
                        <label>LineID </label>
                        <input type="text" class="form-control" id="txtLineUserId" disabled></input>
                    <p>
                        <label>Email </label>
                        <input  type="text" class="form-control" id="txtLineEmail"></input>
                    <p>
                        <label>FirstName </label>
                        <input type="text" class="form-control"></input>
                    <p>
                        <label>LastName </label>
                        <input type="text" class="form-control"></input>

                    <p>
                        <label>MobileNumber </label>
                        <input type="text" class="form-control" id="MobileNumber"></input>
                    <p>
                        <button type="button" class="btn btn-success" id="save">Save</button>
                </div>
            </form>
        </div>
    </div>
    <div id="errorMsg"></div>
    <div id="form-click-to-call"></div>
    <script language="javascript">
        $(document).ready(function() {
            $('#CompanyCode').on('change', function() {
                companyCode = this.value;
                if (companyCode == '00000') {
                    $("#registerSecond").hide();
                } else {
                    $("#registerSecond").show();
                }

            });
            $("#save").click(function() {
                var MobileNumber = $("#MobileNumber").val();
                alert(MobileNumber);
            });

            function closeWindowHandle() {
                if (liff.getOS() == 'web') {
                    function closeWindow() {
                        let new_window =
                            open(location, '_self');
                        new_window.close();
                        return false;
                    }
                    closeWindow();
                } else {
                    liff.closeWindow();
                }
            }

            function showPDPAdialog() {
                const dialogMsgtitle = "หนังสือให้ความยินยอมในการเปิดเผยข้อมูล";
                const AcceptBtn = "ยอมรับ";
                const DeclineBtn = "ไม่" + AcceptBtn;

                $("#dialogMsg").dialog({
                    title: dialogMsgtitle,
                    draggable: false,
                    resizable: false,
                    dialogClass: 'no-close',
                    width: $(window).width(),
                    height: $(window).height(),
                    'buttons': [{
                        text: AcceptBtn,
                        open: function() {
                            $(this).addClass('acceptButton');
                        },
                        click: function() {
                            $("#registerForm").show();
                            $(this).dialog("close");
                        }
                    }, {
                        text: DeclineBtn,
                        open: function() {
                            $(this).addClass('declineButton');
                        },
                        click: function() {
                            liff.closeWindow();
                        }
                    }]
                });
            }

            async function selectCompanyCode() {
                // CompanyCode
            }

            async function getCompanyList() {
                try {
                    const url = '/callApi.php';
                    const params = {
                        menutype: 'getCompanyList'
                    };
                    const response = await axios.post(url, params);
                    const res = JSON.stringify(response.data.body);
                    obj = JSON.parse(res);
                    let dropdown = $("#CompanyCode");
                    dropdown.append("<option value='00000' default>Select Company</option>");
                    idDuplicate = '';
                    obj.forEach(function(e) {
                        let option = $('<option></option>');
                        id = obj[0];
                        name = obj[1];
                        if (idDuplicate != id) {
                            append = true;
                        } else if (idDuplicate == '') {
                            append = true;
                        } else {
                            append = false;
                        }

                        if (append == true) {
                            option.val(id);
                            option.html(name);
                            dropdown.append(option);
                            idDuplicate = id;
                        }
                    });
                } catch (error) {
                    console.log(error);
                }
            }

            async function getUserIdApi(userid) {
                try {
                    const url = '/callApi.php';
                    const params = {
                        menutype: 'getUserId',
                        userId: userid
                    };
                    const response = await axios.post(url, params);
                    const res = JSON.stringify(response.data.result);
                    return res;
                } catch (error) {
                    console.log(error);
                }

            }

            function checkUserId(LineUserId) {
                const data = false;
                try {
                    const data = getUserIdApi(LineUserId);
                } catch (error) {
                    console.log(error);
                }
                return data;
            }

            async function saveData(data) {

            }

            async function checkRegister() {}

            async function showRegisterForm(LineUserId, LineDisplayName, LineEmail) {
                $("#txtLineUserId").val(LineUserId);
                $("#txtLineEmail").val(LineEmail);
            }

            function getProfileLiffUserId() {
                liff.getProfile()
                    .then(profile => {
                        try {
                            const LineUserId = profile.userId;
                            const _checkUserId = checkUserId(LineUserId);
                            if (_checkUserId === true) {
                                closeWindowHandle();
                            } else if (_checkUserId === false) {
                                showPDPAdialog();
                                getCompanyList();
                                const LineDisplayName = profile.displayName;
                                const LineEmail = liff.getDecodedIDToken().email;
                                showRegisterForm(LineUserId, LineDisplayName, LineEmail);
                            }
                        } catch (error) {
                            console.error(error);
                        }
                    })
                    .catch((err) => {
                        console.log('getProfile: ', err);
                    });
            }

            async function initializeLiff() {
                myLiffId = "1656005691-7qXmEbE9";
                await liff.init({
                        liffId: myLiffId
                    })
                    .then(() => {
                        liff.isLoggedIn() ? getProfileLiffUserId() : liff.login();
                    })
                    .catch((err) => {
                        $("#errorMsg").text('initializeLiff: ' + err);
                    });
            }

            //initState
            $("#registerForm").hide();
            // $("#registerSecond").hide();
            initializeLiff();
        });
    </script>
</body>

</html>