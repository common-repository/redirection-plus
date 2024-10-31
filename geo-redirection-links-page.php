<?php

/**
 * Geo Redirection links page config
 */

require 'geo-redirection-links-table-core.php';

function rpp_geo_redirection_links() {
 	$linksTable = new RPP_Geo_Redirection_Links_Table();

  	echo '<div class="wrap">';

  	$linksTable->prepare_items();

    echo    '<form method="post">';

    $linksTable->display();

    echo    '</form>';
  	echo '</div>';    
}






