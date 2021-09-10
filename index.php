<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://static.line-scdn.net/liff/edge/versions/2.12.0/sdk.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <title>LIFF - LINE RMX-E Official Dev</title>
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
            function showPDPAdialog() {
                $("#dialogMsg").dialog({
                    "title": "PDPA",
                    "closeOnMaskClick": false,
                    "show": true,
                    "modal": true,
                    width: auto,
                    height: auto,
                });
                return true;
            }

            function checkUserId(LineUserId) {
                return false;
            }

            async function saveData(data) {

            }

            async function checkRegister() {}

            async function showRegisterForm(LineUserId) {
                _checkUserId = checkUserId(LineUserId);
                _checkUserId === true ?? liff.closeWindow();
                if (_checkUserId === false) {
                    $("#txtUserName").text('LineUserId: ' + LineUserId);
                }
            }

            function validateLiffUserId() {
                // liff.closeWindow();
                liff.getProfile()
                    .then(profile => {
                        const LineUserId = profile.userId;
                        $("#txtUserName").text('LineUserId: ' + LineUserId);
                    })
                    .catch((err) => {
                        console.log('validateLiffUserId: ', err);
                    });
            }

            async function initializeLiff() {
                myLiffId = "1656005691-7qXmEbE9";
                await liff.init({
                        liffId: myLiffId
                    })
                    .then(() => {

                        liff.isLoggedIn() ? validateLiffUserId() : liff.login();


                        // if (liff.isLoggedIn()) {
                        // liff.getProfile().then((profile) => {
                        // _showRegisterForm = showPDPAdialog();
                        // alert(_showRegisterForm);
                        // _showRegisterForm === true ? showRegisterForm(profile) : liff.closeWindow();
                        // }).catch((err) => {
                        // $("#errorMsg").text('validateLiffUserId: ' + err);
                        // });
                        // liff.closeWindow();
                        // } else {
                        // liff.login();
                        // }
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