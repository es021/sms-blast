<?php
include_once '../config.php';
include_once '../functions.php';

?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="format-detection" content="telephone=no">

        <meta name="msapplication-tap-highlight" content="no">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">

        <script>
            var SERVICE_URL = "<?= SERVICE_URL ?>";
            var JS_VERSION = "<?= JS_VERSION ?>";
            var CSS_VERSION = "<?= CSS_VERSION ?>";
            var HTML_VERSION = "<?= HTML_VERSION ?>";
        </script>

        <script src="lib/jquery.js"></script>
        <script src="lib/jquery.validate.min.js"></script>
        <script src="lib/jquery-tmpl/jquery.tmpl.min.js"></script>


        <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="lib/bootstrap/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/font-awesome.css">


        <link rel="stylesheet" href="css/my-app.css?v=<?= CSS_VERSION ?>">

        <script type="text/javascript" src="js/functions.js?v=<?= JS_VERSION ?>"></script>
        <script type="text/javascript" src="js/model/Constant.js?v=<?= JS_VERSION ?>"></script>
        <script type="text/javascript" src="js/model/Ajax.js?v=<?= JS_VERSION ?>"></script>
        <script type="text/javascript" src="js/model/DOM.js?v=<?= JS_VERSION ?>"></script>
        <script type="text/javascript" src="js/model/View.js?v=<?= JS_VERSION ?>"></script>
        <script type="text/javascript" src="js/model/Form.js?v=<?= JS_VERSION ?>"></script>
        <script type="text/javascript" src="js/model/Service.js?v=<?= JS_VERSION ?>"></script>
        <script type="text/javascript" src="js/model/MobileModal.js?v=<?= JS_VERSION ?>"></script>
        <script type="text/javascript" src="js/model/SubNav.js?v=<?= JS_VERSION ?>"></script>
        <title>SMS Blast</title>
    </head>
    <body>

        <!-- page template -->   

        <script id="headerTemplate" type="text/x-jquery-tmpl">
            {{if page_id != 'home' && page_id !='log_in'}}
            <div class="subtitle">    
            SMS Blast                    
            </div>
            {{html title}}
            {{else}}
            SMS Blast
            <div class="subtitle">    
            powered by Westports IT                    
            </div>
            {{/if}}
        </script>


        <div id="main_page" class="page text-center">
            <div id="header" class="header"></div>

            <div id="content" class="content">
            </div>


            <div id="footer" class="footer">
                <span class="logged_out">
                    <i id="home" class="fa fa-home"></i>
                    <i id="log_in" class="fa fa-sign-in"></i>
                </span>
                <span class="logged_in">
                    <i id="home" class="fa fa-home"></i>
                    <i id="send_message" class="fa fa-envelope"></i>
                    <i id="contacts" class="fa fa-user"></i>
                    <i id="draft_sent" class="fa fa-paper-plane"></i>
                    <i id="settings" class="fa fa-cog"></i>
                </span>
            </div>
        </div>

        <script type="text/javascript" src="js/index.js?v=<?= JS_VERSION ?>"></script>
    </body>
</html>





