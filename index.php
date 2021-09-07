<html>

<head>
    <script src="https://static.line-scdn.net/liff/edge/versions/2.12.0/sdk.js"></script>

</head>

<body>
    Test

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
                    await loginLiff();
                })
                .catch((err) => {
                    alert(err);
                });
        }
        initializeLiff();
    </script>
</body>

</html>