<h1 style="Margin-top:0;color:#565656;font-weight:500;font-size:24px;Margin-bottom:18px;font-family:sans-serif;line-height:42px">
  <?= $subject; ?>
</h1>
<p style="Margin-top:0;color:#565656;font-family:sans-serif;font-size:14px;line-height:25px;Margin-bottom:25px">
  Nama: <?= $contact_data[0]->name; ?><br/>
  Email: <?= $contact_data[0]->email; ?><br/>
  Nomor kontak: <?= $contact_data[0]->phone; ?><br/><br/>
  <?= nl2br($contact_data[0]->message); ?>
</p>