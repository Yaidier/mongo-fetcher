<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mongo_Fetcher
 * @subpackage Mongo_Fetcher/admin/partials
 */

$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : null;
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <?php settings_errors(); ?>

    <nav class="nav-tab-wrapper">
      <a href="?page=mongo_fetcher_plugin" class="nav-tab <?php if( $tab===null ) : ?> nav-tab-active <?php endif; ?>">Sync with Mongo</a>
      <a href="?page=mongo_fetcher_plugin&tab=settings" class="nav-tab <?php if( $tab==='settings' ) : ?> nav-tab-active <?php endif; ?>">Settings</a>
    </nav>

    <div class="tab-content">
        {{render_tab_content}}
    </div>

</div>
