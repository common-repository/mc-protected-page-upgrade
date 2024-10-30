<?php
/*
Plugin Name: MC Protected Page Upgrade
Plugin URI: https://https://mid-coast.com/mc6397-protected-page-upgrade
Description: Set the time allowed before a password must be again entered for a password-protected post or page. Also enhances the page password form.
Version: 2.5.4
Author: Mike Hickcox
Author URI: https://Mid-Coast.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
    Copyright (C)2024 Mike Hickcox
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program. If not, see https://www.gnu.org/licenses.
*/
	defined('ABSPATH') or die( 'Not allowed to view this file.' );
	// INCLUDE NEEDED FILE
     include 'inc/mc6397ppu_update-form.php';
	// ADD OPTIONS
	add_option('mc6397ppu_duration_hours',' ');
	add_option('mc6397ppu_duration_minutes',' ');
	add_option('mc6397ppu_duration_seconds',' ');
	// REGISTER SETTINGS
	function register_mc6397_page_pwd_duration_setting () {
    add_settings_section(
        'ppu-settings-section',
        '',
        'mc6397ppu_settings_section_callback',
        'mc6397-page-pwd-duration'
    );
    add_settings_field(
        'mc6397ppu_duration_hours', 
        'HOURS before the password is required again',
        'mc6397ppu_duration_hours_input_callback',
        'mc6397-page-pwd-duration',
        'ppu-settings-section');
    add_settings_field(
        'mc6397ppu_duration_minutes', 
        'MINUTES before the password is required again',
        'mc6397ppu_duration_minutes_input_callback',
        'mc6397-page-pwd-duration',
        'ppu-settings-section');
    add_settings_field(
        'mc6397ppu_duration_seconds', 
        'SECONDS before the password is required',
        'mc6397ppu_duration_seconds_input_callback',
        'mc6397-page-pwd-duration',
        'ppu-settings-section');
    register_setting('mc6397-page-pwd-duration', 'mc6397ppu_duration_hours'); 
	register_setting('mc6397-page-pwd-duration', 'mc6397ppu_duration_minutes'); 
	register_setting('mc6397-page-pwd-duration', 'mc6397ppu_duration_seconds'); 
}
	// ADD SETTINGS LINK TO MENU
	function mc6397ppu_expire_plugin_menu() {
    add_options_page('MC Protected Page Upgrade', 
                     'MC Protected Page Upgrade', 
                     'manage_options', 
                     'mc6397-page-pwd-duration', 
                     'mc6397ppu_expire_options');
}
	// CALL DATA
	function mc6397ppu_duration_hours_input_callback() {
	echo '<input name="mc6397ppu_duration_hours" id="mc6397ppu_duration_hours" type="number" min="0" max="240" value="' . (string) ((int) get_option('mc6397ppu_duration_hours')) . '"/> <br>Range: 0-240';
}
	function mc6397ppu_duration_minutes_input_callback() {
	echo '<input name="mc6397ppu_duration_minutes" id="mc6397ppu_duration_minutes" type="number" min="0" max="59" value="' . (string) ((int) get_option('mc6397ppu_duration_minutes')) . '"/> <br>Range: 0-59';
}
	function mc6397ppu_duration_seconds_input_callback() {
    echo '<input name="mc6397ppu_duration_seconds" id="mc6397ppu_duration_seconds" type="number" min="0" max="59" value="' . (string) ((int) get_option('mc6397ppu_duration_seconds')) . '"/> <br>Range: 0-59';
}
	function mc6397ppu_settings_section_callback() {
    echo '';
}
	function mc6397ppu_expire_options() {
?>
	<div class="bootstrap-wrapper" > <br/>
		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/MC-PPU-Head.jpg'; ?>">
		<h3>These settings determine when a user must again enter the password to access a password-protected post or page on this website. Note: The password itself is set by going to Dashboard / Pages / All Pages / Find the page you want and use the dropdown for Quick Edit / Enter what you want in the Password field. </h3><strong>Enter any combination of hours, minutes, and seconds to set access time.<br>Leave all fields empty (zero) to require users to always enter the password.</strong>
        <form method="post" action="options.php">
            <?php settings_fields('mc6397-page-pwd-duration');
                  do_settings_sections('mc6397-page-pwd-duration');
                  submit_button(); ?>
        </form>
    </div>
<?php
}
	// SET THE DURATION
	function mc6397ppu_expire_password(){
    if (isset ( $_COOKIE['wp-postpass_' . COOKIEHASH] ) ) {
        if (!isset ($_COOKIE['mc6397ppu_expire_plugin'] ) ) {
            $expire = time() + esc_attr (get_option('mc6397ppu_duration_hours'))*(3600) + esc_attr (get_option('mc6397ppu_duration_minutes'))*(60) + esc_attr (get_option('mc6397ppu_duration_seconds'))*(1);
            $cookieval = esc_attr ($_COOKIE['wp-postpass_' . COOKIEHASH]);
            setcookie( 'wp-postpass_' . COOKIEHASH , $cookieval, $expire, COOKIEPATH);
            setcookie( 'mc6397ppu_expire_plugin', 'enabled', $expire, COOKIEPATH);
        }
    }
}
	add_action('wp','mc6397ppu_expire_password');
	if(is_admin()) {
    add_action('admin_menu', 'mc6397ppu_expire_plugin_menu');
    add_action('admin_init', 'register_mc6397_page_pwd_duration_setting');
}
	// ADD SETTINGS LINK ON PLUGINS PAGE
	function mc6397_page_pwd_duration_link($links) { 
		$settings_link = '<a href="options-general.php?page=mc6397-page-pwd-duration">Settings</a>'; 
		array_unshift($links, $settings_link); 
		return $links; 
}
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'mc6397_page_pwd_duration_link' );
