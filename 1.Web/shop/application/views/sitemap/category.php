<?php
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
?>
<urlset>
    <?php if( $results ){ ?>
	<?php foreach ( $results as $row ){ ?>
	<url>
        <loc><?php echo base_url('cat_'.$row['id'].'.html'); ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>hourly</changefreq>
        <priority>0.9</priority>
    </url>
    <?php } ?>
    <?php } ?>
</urlset>