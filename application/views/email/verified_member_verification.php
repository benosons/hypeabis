<h1 style="Margin-top:0;color:#565656;font-weight:500;font-size:24px;Margin-bottom:18px;font-family:sans-serif;line-height:42px">
  <?= $subject; ?>
</h1>
<p style="Margin-top:0;color:#565656;font-family:sans-serif;font-size:14px;line-height:25px;Margin-bottom:25px">
  <?php if ($is_accepted === '1'): ?>
    Selamat, verifikasi Verified Member anda berhasil.<br/>
  <?php else: ?>
    Maaf, verifikasi <b>Verified Member</b> anda masih belum berhasil.<br/>
    <br/>

    
    <span style="display:block;border-top:1px solid #ccc;border-bottom:1px solid #ccc;padding:10px;">
      <b>Alasan Penolakan</b><br/>
      <?php echo $reject_description ?>
    </span>
  <?php endif; ?>

  <br/>
  
  Best Regards,<br/>
</p>
