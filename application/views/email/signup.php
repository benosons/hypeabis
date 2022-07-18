<h1 style="Margin-top:0;color:#565656;font-weight:500;font-size:24px;Margin-bottom:18px;font-family:sans-serif;line-height:42px">
  <?= $subject; ?>
</h1>
<p style="Margin-top:0;color:#565656;font-family:sans-serif;font-size:14px;line-height:25px;Margin-bottom:25px">
  Terima kasih telah bergabung. Proses registrasi anda hampir selesai. Klik tombol dibawah ini untuk mengaktifkan akun anda.<br/><br/>
  
  <u></u><a href="<?= base_url(); ?>page/activation/<?= (isset($verification_key) ? $verification_key : ''); ?>" style="border:1px solid #ffffff;display:inline-block;font-size:13px;font-weight:bold;line-height:15px;outline-style:solid;outline-width:2px;padding:10px 30px;text-align:center;text-decoration:none!important;color:#fff!important;font-family:sans-serif;background-color:<?= $color_theme; ?>;outline-color:<?= $color_theme; ?>" target="_blank">
    Aktifkan Akun
  </a><u></u><br/><br/>
  
  Best Regards,<br/>
</p>