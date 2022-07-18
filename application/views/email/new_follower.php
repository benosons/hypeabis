<h1 style="Margin-top:0;color:#565656;font-weight:500;font-size:24px;Margin-bottom:18px;font-family:sans-serif;line-height:42px">
  <?= $subject; ?>
</h1>
<p style="Margin-top:0;color:#565656;font-family:sans-serif;font-size:14px;line-height:25px;Margin-bottom:25px">
  Selamat, anda memiliki follower baru.<br/>
  
  <table style="width:100%;border-top:1px solid #ccc;border-bottom:1px solid #ccc;">
    <tr>
      <td style="padding:10px;vertical-align:top;width:70px;">
        <img src="<?php echo base_url() . 'assets/user/' . ($user->picture ?: 'default.png') ?>" alt="<?php echo $user->name ?>" width="70" height="70" style="border-radius: 50%;">
      </td>
      <td style="padding:10px;vertical-align:top;width:60%;">
        <h2 style="margin:0 0 5px;">
          <?php echo $user->name ?>
          <?php if ($user->verified): ?>
            <a href="#">
              <img src="<?= base_url(); ?>files/frontend/images/verified.png" class="lazy" alt="Verified member" title="Verified member" height="21" style="vertical-align: text-top;"/>
            </a>
          <?php endif; ?>
        </h2>
        <?php if (isset($user->level) && strlen(trim($user->level)) > 0): ?>
          <label class="label" style="padding:1px 9px 3px 9px;font-size:12px;text-shadow:none;background-color:#e6e6e6;font-weight:600;color:#626262;border-radius:.25em;background:#<?php echo  $user->bg_color; ?>;color:#<?php echo  $user->text_color; ?>">
            <?php echo  $user->level; ?>
          </label>
        <?php endif; ?>
        <?php if (!empty(trim($user->profile_desc))): ?>
          <p style="margin-top:10px;margin-bottom:0px;color:#565656;font-family:sans-serif;font-size:12px;line-height:1.5;">
            <?php echo $user->profile_desc ?>
          </p>
        <?php endif; ?>
      </td>
      <td style="padding:10px;">
        <?php if (!$is_following): ?>
          <a href="<?php echo base_url() ?>author/<?php echo $user->id_user ?>/<?php echo strtolower(url_title($user->name)) ?>/follow"  style="white-space:nowrap;border:1px solid #ffffff;display:block;font-size:13px;font-weight:bold;line-height:15px;outline-style:solid;outline-width:2px;padding:10px 30px;text-align:center;text-decoration:none!important;color:#fff!important;font-family:sans-serif;background-color:<?= $color_theme; ?>;outline-color:<?= $color_theme; ?>" target="_blank">
            Follow
          </a>
        <?php else: ?>
          &nbsp;
        <?php endif; ?>
      </td>
    </tr>
  </table>


  <br/>
  <br/>
  
  Best Regards,<br/>
</p>
