<!DOCTYPE html>
<html lang="en" xmlns="https://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title>{TITLE}</title>
    <!--[if mso]>
    <style>
        table {border-collapse:collapse;border-spacing:0;border:none;margin:0;}
        div, td {padding:0;}
        div {margin:0 !important;}
    </style>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>


        table {
            font-family: Arial, sans-serif;
        }

        #bodycontent {
          padding: 15px;
        }

        #bodycontent a.button {
          display:inline-block;
          text-decoration: none;
          background: #990000;
          color:#fff;
          padding: 5px 10px;
        }

        #bodycontent a.button:hover {
          background: blue;
        }

        #bodycontent .code {
          display: inline-block;
          font-weight:bold;
          font-size: 16px;
          padding: 10px 5px;
          background: #404040;
          color: #fff;
        }

    </style>
</head>
<body style="margin:0;padding:0;word-spacing:normal;background-color: #f6f6f6;">
    <div role="article" aria-roledescription="email" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#939297;">
      <!-- Email body starts here -->
      <table role="presentation" style="width:100%;border:none;border-spacing:0;">
          <tr>
              <td align="center" style="padding:0;">
                <!--[if mso]>
                  <table role="presentation" align="center" style="width:600px;">
                  <tr>
                  <td>
                  <![endif]-->

                    <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">

                      <!-- Header starts here -->
                      <tr>
                        <td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;">
                            <a href="<?php echo $this->wire('input')->httpHostUrl();?>" style="text-decoration:none;"><img style="display: block; height: auto; border: 0; width: 500px; max-width: 100%;" src="<?php echo $this->wire('input')->httpHostUrl().$this->wire('config')->urls->siteModules.'FrontendForms/images/logo.jpg';?>" alt="Logo" width="600" height="600"></a>
                        </td>
                      </tr>
                      <!-- Header ends here -->

                      <!-- Main body starts here -->
                      <tr id="bodycontent">
                        <td style="padding:30px;background-color:#ffffff;">
                          <table>
                            <tr id="subject">
                              <td style="padding:15px 0"><h2>{SUBJECT}</h2></td>
                            </tr>
                            <tr id="text">
                              <td style="padding:15px 0">{BODY}</td>
                            </tr>
                          </table>

                        </td>
                      </tr>
                      <!-- Main body  ends here -->

                      <!-- Footer starts here -->
                      <tr>
                        <td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#cccccc;">
                            <?php echo $this->wire('input')->httpHostUrl().$this->wire('config')->urls->siteModules.'FrontendForms/images/logo.jpg';?>

                        </td>
                      </tr>
                      <!-- Footer  ends here -->

                    </table>

                  <!--[if mso]>
                  </td>
                  </tr>
                  </table>
                <![endif]-->
              </td>
          </tr>
      </table>
      <!-- Email body ends here -->
    </div>
</body>
</html>
