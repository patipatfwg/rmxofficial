<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://static.line-scdn.net/liff/edge/versions/2.12.0/sdk.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <title>LIFF - LINE RMX-E Official Dev</title>
    <style>
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
    <hr>
    <div id="txtUserName"></div>
    <hr>
    <div id="errorMsg"></div>
    <hr>
    <div id="dialogMsg"></div>
    <hr>
    <script language="javascript">
        $(document).ready(function() {
            function closeWindowHandle() {
                if (liff.getOS() == 'web') {
                    console.log('Close');
                } else {
                    liff.closeWindow();
                }
            }

            function showPDPAdialog() {
                $("#dialogMsg").dialog({
                    title: "Informed Consent",
                    draggable: false,
                    resizable: false,
                    dialogClass: 'no-close',
                    width: $(window).width(),
                    height: $(window).height(),
                    'buttons': [{
                        text: "Accept",
                        open: function() {
                            $(this).addClass('acceptButton');
                        },
                        click: function() {
                            $(this).dialog("close");
                        }
                    }, {
                        text: "Decline",
                        open: function() {
                            $(this).addClass('declineButton');
                        },
                        click: function() {
                            liff.closeWindow();
                        }
                    }]
                });
            }

            async function getApi() {
                try {
                    const response = await axios.get('/callApi.php');
                    aa = JSON.parse(response);
                    alert('response: ' + aa);
                } catch (error) {
                    console.error(error);
                }
            }

            function checkUserId(LineUserId) {
                my = "Uae4bfcada214d07661bb5a8779ad4fd3";

                getApi();

                if (LineUserId == my) {
                    data = true;
                } else {
                    data = false;
                }
                return data;
            }

            async function saveData(data) {

            }

            async function checkRegister() {}

            async function showRegisterForm(LineUserId) {
                $("#txtUserName").text('LineUserId: ' + LineUserId);
            }

            function getProfileLiffUserId() {
                liff.getProfile()
                    .then(profile => {
                        const LineUserId = profile.userId;
                        const _checkUserId = checkUserId(LineUserId);
                        if (_checkUserId === true) {
                            closeWindowHandle();
                        } else if (_checkUserId === false) {
                            showPDPAdialog();
                            showRegisterForm(LineUserId);
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
            initializeLiff();
        });
    </script>
</body>

</html>