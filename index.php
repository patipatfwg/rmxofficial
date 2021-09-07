<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://static.line-scdn.net/liff/edge/versions/2.12.0/sdk.js"></script>
    <title>LIFF - LINE RMX-E Official Dev</title>
</head>

<body>
    <hr>Test
    <hr>
    <div id="errorMsg"></div>
    <script language="javascript">
        $(document).ready(function() {
            async function checkUserId() {
                window.location.reload();
            }

            async function initializeLiff() {
                console.log('func initializeLiff');
                myLiffId = "1656005691-7qXmEbE9";
                await liff.init({
                        liffId: myLiffId
                    })
                    .then(() => {
                        if (liff.isLoggedIn()) {
                            checkUserId();
                        } else {
                            liff.login();
                        }
                    })
                    .catch((err) => {
                       $("#errorMsg").text(err)
                    });
            }
            initializeLiff();
        });
    </script>
</body>

</html>