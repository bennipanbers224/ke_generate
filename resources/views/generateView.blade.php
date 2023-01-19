<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
            }

            header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 60px;

                /** Extra personal styles **/
                background-color: #1dbb90;
                color: white;
                text-align: center;
                line-height: 35px;
            }

            header p{
                font-size: 25px;
                margin-top: 8px;
            }

            footer {
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
                height: 60px; 
                font-size: 20px !important;
                color: white; !important;

                /** Extra personal styles **/
                background-color: #1dbb90;
                text-align: center;
                line-height: 35px;
            }
        </style>
</head>
<body>

    <!-- Define header and footer blocks before your content -->
    <header>
            <p>Nicesnippets.com</p>
        </header>

        <footer>
            <div style="margin-top: 8px !important">Copyright © <?php echo date("Y");?> . All rights reserved.</div>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,

                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo

                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </p>
        </main>
    </body>
    
</body>
</html>