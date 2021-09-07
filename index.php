<html>

<head>
    <script charset="utf-8" src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
    <script language="javascript">
        async function loginLiff() {
            if (!liff.isLoggedIn()) {
                alert('loginLiff');
                liff.login();
            } else {
                alert('Welcome');
            }

            window.location.reload();
        }

        async function initializeLiff() {
            var myLiffId = "1656005691-7qXmEbE9";
            liff.init({
                    liffId: myLiffId
                })
                .then(() => {
                    loginLiff();
                })
                .catch((err) => {
                    alert(err);
                });
            console.log('func initializeLiff');
        }
        initializeLiff();
    </script>
</head>

<body>
    Test
</body>

</html>