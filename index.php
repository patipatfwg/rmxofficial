<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://static.line-scdn.net/liff/edge/versions/2.12.0/sdk.js"></script>
    <title>LIFF - LINE RMX-E Official Dev</title>
</head>

<body>
    <div id="txtUserName"></div>
    <div id="txtdisplayName"></div>
    <div id="errorMsg"></div>
    <script language="javascript">
        $(document).ready(function() {
            function showPDPAdialog() {
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
                alert(_checkUserId);
                if (_checkUserId === false) {
                    $("#txtUserName").text('LineUserId: ' + LineUserId);
                    $("#txtdisplayName").text('LineDisplayName: ' + LineDisplayName);
                } else if (_checkUserId === true) {
                    liff.closeWindow();
                }

            }

            async function validateLiffUserId() {
                liff.getProfile().then((profile) => {
                    _showRegisterForm = showPDPAdialog();
                    if (_showRegisterForm == true) {
                        showRegisterForm(profile);
                    } else {
                        liff.closeWindow();
                    }


                    //CheckAccessTokenExpire

                    //InsertAccessToken

                    //ChangeMemberRichmenu



                }).catch((err) => {
                    console.error(err)
                });
            }

            async function initializeLiff() {
                myLiffId = "1656005691-7qXmEbE9";
                await liff.init({
                        liffId: myLiffId
                    })
                    .then(() => {
                        if (liff.isLoggedIn()) {
                            validateLiffUserId();
                        } else {
                            liff.login();
                        }

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