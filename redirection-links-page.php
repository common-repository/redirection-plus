<?php

/**
 * Redirection links page config
 */

require 'redirection-links-table-core.php';

// Redirection links status page
function rpp_redirection_links() {
 	$linksTable = new RPP_Redirection_Links_Table();

  	echo '<div class="wrap">';

  	$linksTable->prepare_items();

    echo    '<form method="post">';

    $linksTable->display();

    echo    '</form>';
  	echo '</div>';    
}






