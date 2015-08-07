<?php
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
?>
<urlset>
    <?php if( $results ){ ?>
	<?php foreach ( $results as $row ){ ?>
	<url>
        <loc><?php echo base_url('goods_'.$row['id'] . '.html'); ?></loc>
        <lastmod><?php echo date('Y-m-d',$row['create_time']); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <?php } ?>
    <?php } ?>
</urlset>