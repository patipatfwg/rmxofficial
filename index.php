<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://static.line-scdn.net/liff/edge/versions/2.12.0/sdk.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <title>LIFF - LINE RMX-E Official Dev</title>
</head>

<body>
    <div id="txtUserName"></div>
    <div id="txtdisplayName"></div>
    <div id="errorMsg"></div>
    <div id="dialogMsg"></div>
    <script language="javascript">
        $(document).ready(function() {
            function showPDPAdialog() {
                $("#dialogMsg").dialog({
                    "title": "PDPA",
                    "closeOnMaskClick": false,
                    "show": true,
                    "modal": true
                });
                
                return true;
            }

            function checkUserId(LineUserId) {
                return false;
            }

            async function saveData(data) {

            }

            async function checkRegister() {}

            async function showRegisterForm(profile) {
                LineDisplayName = profile.displayName;
                LineUserId = profile.userId;
                _checkUserId = checkUserId(LineUserId);
                _checkUserId === true ?? liff.closeWindow();
                if (_checkUserId === false) {
                    $("#txtUserName").text('LineUserId: ' + LineUserId);
                    $("#txtdisplayName").text('LineDisplayName: ' + LineDisplayName);
                }
            }

            async function validateLiffUserId() {
                liff.getProfile().then((profile) => {
                    _showRegisterForm = showPDPAdialog();
                    _showRegisterForm === true ? showRegisterForm(profile) : liff.closeWindow();

                    //CheckAccessTokenExpire

                    //InsertAccessToken

                    //ChangeMemberRichmenu



                }).catch((err) => {
                    $("#errorMsg").text('validateLiffUserId: ' + err);
                });
            }

            async function initializeLiff() {
                myLiffId = "1656005691-7qXmEbE9";
                await liff.init({
                        liffId: myLiffId
                    })
                    .then(() => {
                        liff.isLoggedIn() ? validateLiffUserId() : liff.login();
                    })
                    .catch((err) => {
                        $("#errorMsg").text('initializeLiff: ' + err);
                    });
            }
            initializeLiff();
        });
    </script>
</body>

</html>