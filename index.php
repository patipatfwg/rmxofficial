<html>

<head>
    <script charset="utf-8" src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
    <script language="javascript">
        function initializeLiff(myLiffId) {
            var myLiffId = "1656005691-7qXmEbE9";
            liff.init({
                    liffId: myLiffId
                })
                .then(() => {
                    initializeApp();
                })
                .catch((err) => {
                    document.getElementById("liffAppContent").classList.add('hidden');
                    document.getElementById("liffInitErrorMessage").classList.remove('hidden');
                });
        }
    </script>
</head>

<body>
    <div id="liffAppContent"></div>
    <div id="liffInitErrorMessage"></div>
</body>

</html>