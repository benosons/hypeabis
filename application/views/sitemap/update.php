<?= '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
  <url>
    <loc><?= base_url(); ?></loc>
    <lastmod><?= date('Y-m-d'); ?>T<?= date('H:i:s'); ?>+07:00</lastmod>
    <changefreq>hourly</changefreq>
    <priority>1</priority>
  </url>
  
  <? foreach($categories as $x => $category){ ?>
  <? if(isset($category['child']) && is_array($category['child']) && count($category['child']) > 0){ ?>
  <? foreach($category['child'] as $y => $child){ ?>
  <url>
    <loc><?= base_url(); ?>category/<?= $child['id_category']; ?>/<?= strtolower(url_title($child['category_name'])); ?></loc>
     <lastmod><?= date('Y-m-d'); ?>T<?= date('H:i:s'); ?>+07:00</lastmod>
    <changefreq>hourly</changefreq>
    <priority>1</priority>
  </url>
  <? } ?>
  <? } else { ?>
  <url>
    <loc><?= base_url(); ?>category/<?= $category['id_category']; ?>/<?= strtolower(url_title($category['category_name'])); ?></loc>
     <lastmod><?= date('Y-m-d'); ?>T<?= date('H:i:s'); ?>+07:00</lastmod>
    <changefreq>hourly</changefreq>
    <priority>1</priority>
  </url>
  <? } ?>
  <? } ?>
  
  <? foreach($contents as $content) { ?>
  <url>
    <loc><?= base_url(); ?>read/<?= $content->id_content; ?>/<?= strtolower(url_title($content->title)); ?></loc>
     <lastmod><?= date('Y-m-d'); ?>T<?= date('H:i:s'); ?>+07:00</lastmod>
    <changefreq>hourly</changefreq>
    <priority>1</priority>
  </url>
  <? } ?>
</urlset>
