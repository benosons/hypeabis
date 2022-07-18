<? header("Content-Type: application/rss+xml; charset=UTF-8"); ?>
<?= "<?xml version='1.0' encoding='UTF-8'?>"; ?>

<rss version='2.0'>
  <channel>
    <title>Bisnis Muda</title>
    <description>Bisnismuda.id merupakan portal untuk sharing dan belajar investasi, pengelolaan keuangan, dan wirausaha.</description>
    <link>https://bisnismuda.id</link>
    
    <? foreach($contents as $content) { ?>
      <? 
        $image_size_array = get_headers(base_url() . "assets/content/thumb/" . $content->content_pic_thumb, 1);
        $image_size = $image_size_array['Content-Length'];
        $image_mime_array = getimagesize(base_url() . "assets/content/thumb/" . $content->content_pic_thumb);
        $image_mime = $image_mime_array['mime'];
      ?>
    
      <item>
        <title><?= $content->title; ?></title>
        <? if(isset($content->short_desc) && strlen(trim($content->short_desc)) > 0){ ?>
        <description><?= (strlen($content->short_desc) > 300 ? substr($content->short_desc, 0, 297) . '...' : $content->short_desc); ?></description>
        <? } ?>
        <pubDate><?= date('D, d M Y H:i:s O',strtotime($content->publish_date)); ?></pubDate>
        <link><?= base_url(); ?>read/<?= $content->id_content; ?>/<?= strtolower(url_title($content->title)); ?></link>
        <guid><?= base_url(); ?>read/<?= $content->id_content; ?>/<?= strtolower(url_title($content->title)); ?></guid>
        <category><?= $content->category_name; ?></category>
      </item>
    <? } ?>
    
  </channel>
</rss>
