<?php // exit if uninstall constant is not defined

if (!defined('WP_UNINSTALL_PLUGIN')) exit;

// remove plugin options
delete_option( 'mc6397ppu_duration_hours' );
delete_option( 'mc6397ppu_duration_minutes' );
delete_option( 'mc6397ppu_duration_seconds' );
