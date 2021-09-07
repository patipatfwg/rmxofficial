<html>

<head>
    <script charset="utf-8" src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
    <script language="javascript">
        async function loginLiff() {
            if (!liff.isLoggedIn()) {
                console.log('func loginLiff');
                await liff.login();
            } else {
                alert('Welcome');
            }

            window.location.reload();
        }

        async function initializeLiff() {
            console.log('func initializeLiff');
            myLiffId = "1656005691-7qXmEbE9";
            await liff.init({
                    liffId: myLiffId
                })
                .then(() => {
                    loginLiff();
                })
                .catch((err) => {
                    alert(err);
                });
        }
        initializeLiff();
    </script>
</head>

<body>
    Test
</body>

</html>