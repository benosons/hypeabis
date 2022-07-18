<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<style>
			html{
				width : 100%;
				padding : 0;
				margin : 40px 0px;
			}
			body{
				width : 100%;
				padding : 0;
				margin : 0;
        font-family: 'Helvetica' !important;
        font-weight: normal !important;
        font-style: normal !important;
        font-variant: normal !important;
        letter-spacing: 0.5px !important;
        line-height : 20px !important;
				font-size:12px;
				color : #424242;
			}
			table{
				margin : 0px auto;
				border : 1px solid #6b6b6b;
			}
			td{
        padding : 6px 0px;
				border : 1px solid #6b6b6b;
			}
			.header{
				position:relative;
				border : 0px;
				margin : -40px 0 0 0;
			}
			.wrapper{
				padding : 60px 65px 20px 65px;
			}
			.text-big{
        letter-spacing: 0px !important;
        font-weight: bold!important;
        color: <?= $color_theme; ?>!important;
				font-size : 24px;
        line-height: 40px;
			}
      .text-medium{
        font-weight: bold!important;
				font-size : 20px;
        line-height: 36px;
      }
      .red{
        color:#f20000 !important;
      }
			.global{
				margin : 0px 0 0 0;
			}
			.table-item{
				border-left : none;
				border-bottom : none;
				border-right: none;
				border-top: 1px solid #dddddd;
				margin-bottom : 20px;
			}
			.table-item td{
				padding : 15px 10px 15px 10px;
				border : none;
				border-bottom: 1px solid #dddddd;
			}
			.table_container{
				border : none;
				margin : 0px;
				text-align : left;
			}
			.table_container td{
				padding : 0px;
				border : none;
				text-align : left;
			}
		</style>
	</head>
	
	
	<body>
		<!-- BLACK LINE -->
		<table class="header" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="100%" style="background:<?= $color_theme; ?>;height:4px;border:none;outline:none;"></td>
			</tr>
		</table>
		<!-- BLACK LINE END -->
		
		<div class="wrapper" style="background:#ffffff;margin-top:-50px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:none;">
				<tr style="border:none;">
					<td width="50%" valign="top" style="border:none;">
						<img src="<?= base_url(); ?>assets/logo/<?= (isset($global_data[0]->logo) ? $global_data[0]->logo : ''); ?>" width="<?= (isset($global_data[0]->logo_width) ? ($global_data[0]->logo_width / 1.5) : ''); ?>" height="<?= (isset($global_data[0]->logo_height) ? ($global_data[0]->logo_height / 1.5) : ''); ?>" alt="<?= (isset($global_data[0]->website_name) ? $global_data[0]->website_name : ''); ?>" title="<?= (isset($global_data[0]->website_name) ? $global_data[0]->website_name : ''); ?>">
					</td>
					<td width="50%" valign="top" style="padding-left:40px;text-align:right;border:none">
						<p class="global">
							<?= (isset($global_data[0]->address) ? nl2br($global_data[0]->address) : ''); ?><br/>
							<?= (isset($global_data[0]->email) ? 'Email: ' . $global_data[0]->email : ''); ?><br/>
							<?= (isset($global_data[0]->phone1) ? 'Phone: ' . $global_data[0]->phone1 : ''); ?>
						</p>
					</td>
				</tr>
			</table>
		</div>
		
		<div class="wrapper" style="margin-top:10px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:none;">
        <tr style="border:none;">
					<td width="100%" style="border:none;text-align:center;" valign="top">
						<span class="text-big"><b>DONATION RECEIPT</b></span><br/><br/><br/>
					</td>
				</tr>
      </table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:none;">
				<tr style="border:none;">
					<td width="70%" style="border:none;text-align:left;line-height:40px;" valign="top">
						Issued date:<br/>
            <span class="text-medium" style="margin-top:10px;"><?= date('d F Y', strtotime($donation_data[0]->donation_date)); ?></span><br/>
					</td>
					<td width="30%" style="text-align:left;border:none;line-height:40px;" valign="top">
						Donation number:<br/>
            <span class="text-medium" style="margin-top:10px;"><?= $donation_data[0]->donation_number; ?></span><br/>
					</td>
				</tr>
			</table>
			
      <br/><br/><br/>
      
			<table class="table-item" cellspacing="0" width="100%">
				<tr style="background:#f5f5f5;padding-top:10px;padding-bottom:10px;">
					<td width="40%"><b>Donatur Information</b></td>
					<td width="60%"><b>Donation</b></td>
				</tr>
			
        <tr>
          <td valign="top" style="line-height:24px;">
            <? if($donation_data[0]->donation_anonymous != '1'){ ?>
              <?= $donation_data[0]->donation_name; ?><br/>
              Email: <?= $donation_data[0]->donation_email; ?><br/>
              Phone: <?= $donation_data[0]->donation_contact_number; ?>
            <? } else { ?>
              - NN -
            <? } ?>
          </td>
          <td valign="top" style="line-height:24px;">
            <span class="text-big">Rp<?= number_format($donation_data[0]->donation_amount, 0, '', '.'); ?></span><br/>
            <i>(<?= $this->global_lib->convertNumber($donation_data[0]->donation_amount . '.00'); ?> Rupiah)</i><br/>
            Designated: 
            <? if($donation_data[0]->id_project > 0  || $donation_data[0]->id_projectcategory > 0){ ?>
              <?= (isset($donation_data[0]->title) && strlen(trim($donation_data[0]->title)) > 0 ? $donation_data[0]->title . ' - ' : ''); ?>
              <?= (isset($donation_data[0]->project_name) && strlen(trim($donation_data[0]->project_name)) > 0 ? $donation_data[0]->project_name : ''); ?>
            <? } else { ?>
              General donation
            <? } ?>
          </td>
        </tr>
			</table>
      
      <p style="text-align:left;line-height:16px;font-size:10px;">
        <? $receipt_hash = sha1($donation_data[0]->donation_number . $donation_data[0]->id_donation . $donation_data[0]->donation_email . $this->config->item('binari_salt')); ?>
        This document can be considered as original document if the contents are the same as the document at the URL below:<br/>
        <a href="<?= base_url(); ?>donate/receipt/<?= $donation_data[0]->donation_number; ?>/<?= $receipt_hash; ?>">
          <?= base_url(); ?>donate/receipt/<?= $donation_data[0]->donation_number; ?>/<?= $receipt_hash; ?>
        </a><br/>
        * Valid document URL will always pointed from https://gnindonesia.org<br/>
        * This document does not require a signature because it is printed computerized.
      </p>
			
      <br/><br/>
      <table class="table-item" cellspacing="0" width="100%" style="border:none;">
        <tr>
          <td valign="top" width="20%" style="border-bottom:none;"></td>
          
          <td valign="top" width="60%" style="border-bottom:none;">
            <center><span class="text-medium">Thank You</span></center>
            <p align="center" style="text-align:center;line-height:24px;font-size:12px;">
              <i>
                For your contribution through Gugah Nurani Indonesia.<br/>
                Your generous support contributed to realize child protection and family welfare in Community Development Project where our organization work in.
              </i>
              <br/><br/><br/><br/>
              <!--<span class="text-medium">Best Regards,</span>
              <br/><br/><br/><br/>
              <u>Stephanie Hellyanti Wimono</u><br/>
              Fundraising Manager-->
            </p>
          </td>
          
          <td valign="top" width="20%" style="border-bottom:none;"></td>
        </tr>
			</table>
      
		</div>
	</body>
	
</html>