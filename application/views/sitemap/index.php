<?= '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
 xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd"
 xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  <sitemap>
    <loc><?= base_url(); ?>sitemap-index.xml</loc>
  </sitemap>
  
  <?
    $now = new DateTime( "30 days ago", new DateTimeZone('Asia/Jakarta'));
    $interval = new DateInterval( 'P1D');
    $period = new DatePeriod( $now, $interval, 30);
  ?>
  <? foreach( $period as $day) { ?>
  <sitemap>
    <loc><?= base_url(); ?>sitemap-<?= $day->format( 'Ymd'); ?>.xml</loc>
  </sitemap>
  <? } ?>
</sitemapindex>