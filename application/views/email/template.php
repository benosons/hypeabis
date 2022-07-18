<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?= (isset($subject) ? $subject : ''); ?></title>
</head>

<body bgcolor="#f5f5f5" style="font-family:sans-serif">
<div style="margin:0;padding:0;min-width:100%;background-color:#f5f5f5;padding-bottom:0px;">
    
    <center style="display:table;table-layout:fixed;width:100%;min-width:700px;background-color:#f5f5f5">
    	<table style="border-collapse:collapse;border-spacing:0;width:650px;min-width:650px"><tbody><tr><td style="padding:0;vertical-align:top;font-size:1px;line-height:1px">&nbsp;</td></tr></tbody></table>
		
      <!-- HEADER -->
      <!-- LOGO -->
      <table style="border-collapse:collapse;border-spacing:0;Margin-left:auto;Margin-right:auto;width:602px;margin-top:40px;">
        <tbody><tr><td style="padding:0;vertical-align:top;padding-top:6px;padding-bottom:20px;"><div style="font-size:20px;font-weight:600;line-height:32px;color:#1a3562;font-family:sans-serif;text-align:center" align="center">
          <img style="border:0;display:block;Margin-left:auto;Margin-right:auto;Margin-bottom:30px;max-width:200px;height:auto;" src="<?= base_url(); ?>assets/logo/<?= (isset($global[0]->logo) ? $global[0]->logo : ''); ?>" width="<?= (isset($global[0]->logo_width) ? $global[0]->logo_width : ''); ?>" height="<?= (isset($global[0]->logo_height) ? $global[0]->logo_height : ''); ?>" alt="<?= (isset($global[0]->website_name) ? $global[0]->website_name : ''); ?>" title="<?= (isset($global[0]->website_name) ? $global[0]->website_name : ''); ?>">
        </div></td></tr>
      </tbody></table>
      <!-- LOGO END -->

      <!-- EMAIL CONTENT -->
      <table style="Margin-left:auto;Margin-right:auto;">
          <tbody><tr>
            <td style="padding:0;vertical-align:top">
              <table style="border-collapse:collapse;border-spacing:0;Margin-left:auto;Margin-right:auto;width:600px;background-color:#ffffff;font-size:14px;table-layout:fixed">
                <tbody><tr>
                  <td style="padding:0;vertical-align:top;text-align:left">
                    <div><div style="font-size:32px;line-height:32px">&nbsp;</div></div>
                      <table style="border-collapse:collapse;border-spacing:0;table-layout:fixed;width:100%">
                        <tbody><tr>
                          <td style="padding:0;vertical-align:top;padding-left:32px;padding-right:32px;word-break:break-word;word-wrap:break-word">
                            
                            <?= (isset($email_content) ? $email_content : ''); ?>
            
                          </td>
                        </tr>
                      </tbody></table>
                    
                    <div style="font-size:8px;line-height:8px">&nbsp;</div>
                  </td>
                </tr>
              </tbody></table>
            </td>
          </tr>
      </tbody></table>
      <!-- EMAIL CONTENT END -->

      <!-- FOOTER -->
      <table style="Margin-left:auto;Margin-right:auto;">
        <tbody><tr>
          <td style="padding:0;vertical-align:top" align="center">
          <table style="border-collapse:collapse;border-spacing:0;Margin-left:auto;Margin-right:auto;width:600px;background-color:#ffffff;font-size:14px;table-layout:fixed">
            <tbody>

            <tr>
            <td style="padding:0;vertical-align:top;text-align:center;">
              <div><div style="font-size:32px;line-height:20px">&nbsp;</div></div>

              <p style="Margin-top:0;color:#565656;font-family:sans-serif;font-size:12px;line-height:25px;Margin-bottom:5px">
              - This email address cannot accept incoming email. Please do not reply this message -
              </p>

              <div style="font-size:8px;line-height:20px">&nbsp;</div>
            </td>
            </tr>

          </tbody></table>
          </td>
        </tr>
      </tbody></table>

      <table style="border-collapse:collapse;border-spacing:0;Margin-left:auto;Margin-right:auto">
        <tr>
          <td style="height:40px;">&nbsp;</td>
        </tr>
      </table>
      <!-- FOOTER END -->
      
    </center>
</div>
</body>
</html>