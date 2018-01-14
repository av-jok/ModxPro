{var $site_url = $_modx->config.site_url}
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>modx.pro</title>
    <style type="text/css">
        body {
            background: #f7f7f7;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, serif;
            font-size: 14px;
            color: #293034;
        }
        table { border-spacing: 0; width: 100%; }
        table td { margin: 0; }
        body > table { width: 600px; margin: auto; }
        a { color: #1abc9c; outline: none; text-decoration: none; }
        p { font-size: 16px; line-height: 22px; }
        h1 { font-size: 22px; margin: 0 0 20px 0; font-weight: normal; }
        h1.no-margin { margin: 0; }
        h2 { font-size: 16px; margin: 5px 0 20px 0; font-weight: normal; color: #999999; }
        pre { width: 540px; overflow: auto; background: #efefef; padding: 5px; border-radius: 5px; font-size: 14px;
            font-family: Verdana, monospace;
        }
        small { font-size: 14px; color: #999999; }
        .main-logo { padding: 35px 0; text-align: center; }
        .main-logo img { width: 125px; height: 30px; border: 0; }
        .content {
            height: 100px;
            vertical-align: top;
            background: #ffffff;
            border: 1px solid #e1ddcb;
            border-radius: 5px;
            padding: 30px;
        }
        .products { padding-top: 10px; }
        .products td { vertical-align: top; }
        .products td.logo { border: 1px solid #cfcfcf; }
        .products td.logo img { width: 120px; height: 90px; }
        .products td.title { padding: 0 10px; }
        .products td.title a { color: inherit; }
        .products td.price { white-space: nowrap; text-align: right; }
        .products strong { font-size: 16px; }
        .products small { display: block; padding-top: 10px; }
        .products tfoot td { border-top: 1px solid #cfcfcf; padding-top: 20px; }
        {block 'style'}{/block}
        .footer td { padding: 35px 0; }
        .footer .left { width: 150px; padding-left: 30px; }
        .footer .center a { vertical-align: middle; width: 30px; height: 30px; display: inline-block; }
        .footer .center img { width: 30px; height: 30px; }
        .footer .right { text-align: right; text-transform: uppercase; }
        .footer .right a { color: #999999; font-weight: bold; padding-right: 30px; }
        .red { color: #e74c3c; }
        .green { color: #1abc9c; }
    </style>
</head>
<body>
<table class="main">
    <thead>
    <tr>
        <td class="main-logo">
            <a href="{$site_url}" target="_blank">
                <img src="{$site_url}assets/components/modxpro/img/newsletters/logo.png" alt="{'site_name' | config}"/>
            </a>
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        {block 'content-wrapper'}
            <td class="content">
                {block 'content'}{/block}
            </td>
        {/block}
    </tr>
    <tr>
        <td>
            <!--
            <table class="footer">
                <tr>
                    <td class="left">
                        {$.en ? 'Subscribe to us' : 'Подпишись на нас'}
                    </td>
                    <td class="center">
                        <a href="https://vk.com/modxaddons" target="_blank">
                            <img src="{$site_url}assets/components/modxpro/img/email-vk.png" alt="Vkontakte"/>
                        </a>
                        <a href="https://www.facebook.com/modstore.pro/" target="_blank">
                            <img src="{$site_url}assets/components/modxpro/img/email-fb.png" alt="Facebook"/>
                        </a>
                        <a href="https://twitter.com/modstore_pro" target="_blank">
                            <img src="{$site_url}assets/components/modxpro/img/email-tw.png" alt="Twitter"/>
                        </a>
                        <a href="https://modx.pro/chat/" target="_blank">
                            <img src="{$site_url}assets/components/modxpro/img/email-sl.png" alt="Slack"/>
                        </a>
                    </td>
                    <td class="right">
                        <a href="{$site_url}" target="_blank">modx.pro</a>
                    </td>
                </tr>
            </table>
            -->
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>