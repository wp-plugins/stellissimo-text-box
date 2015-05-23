<?php
/* 
 * Plugin Name: Stellissimo Text Box
 * Plugin URI: http://www.overclokk.net/stellissimo-text-box-per-wordpress
 * Description: This plugin add a box containing text/html you want show at the end of each article
 * Version: 1.1.2
 * Author: Enea Overclokk
 * Author URI: http://www.overclokk.net
 *
 * @package Stellissimo Text Box
 * @since 1.0.0
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// ================== ADD text ================== //
add_filter('the_content', 'stellissimo_output_text_box');

function stellissimo_output_text_box($content)
{

   $return = $content;
   $return .= '<div class="stellissimo-container" style="background-color:' . get_option('stellissimo_box_bg_color') . '">
				   <p>' . get_option('stellissimo_text_content') . '</p>
               </div>';
   return $return;
}

// ================== END ADD text ================= //

// ======================= ENQUEUE REQUIRED SCRIPT AND STYLE ====================================== //
function stellissimo_enqueue_required_scripts()
{
    wp_enqueue_style('stellissimo-style', WP_PLUGIN_URL . '/stellissimo-text-box/css/style.css');
   
}
add_action('init', 'stellissimo_enqueue_required_scripts');
// ======================= END ENQUEUE REQUIRED SCRIPT AND STYLE ================================ //

// ======================== SET DEFAULT OPTION ON PLUGIN ACTIVATION =============================== //
function stellissimo_activate_set_default_options()
{
   add_option('stellissimo_text_content', 'Enter the text or HTML code here');
   add_option('stellissimo_box_bg_color', 'FFF');
}

register_activation_hook( __FILE__, 'stellissimo_activate_set_default_options');
// ========================= END SET DEFAULT OPTION ON PLUGIN ACTIVATION ========================== //

// ======================== SET OPTIONS GROUP =================================== //
function stellissimo_register_options_group()
{
   register_setting('stellissimo_options_group', 'stellissimo_text_content');
   register_setting('stellissimo_options_group', 'stellissimo_box_bg_color');
}

add_action ('admin_init', 'stellissimo_register_options_group');
// ====================== END SET OPTIONS GROUP ================================ //


// ===================== CREATE AND ADD OPTTION PAGE =================================== //
function stellissimo_add_option_page()
{
   add_options_page('Stellissimo Options', 'Stellissimo Options', 'administrator', 'stellissimo-options-page', 'stellissimo_update_options_form');
}

add_action('admin_menu', 'stellissimo_add_option_page');

function stellissimo_update_options_form()
{
   ?>
   <div class="wrap">
       <div class="icon32" id="icon-options-general"><br /></div>
       <h2>Text Box Configuration</h2>
       <p>&nbsp;</p>
       <form method="post" action="options.php">
           <?php settings_fields('stellissimo_options_group'); ?>
           <table class="form-table">
               <tbody>
                   <tr valign="top">
                   <th scope="row"><label for="stellissimo_box_bg_color">Box Color:</label></th>
                       <td>
                           <input type="text" id="stellissimo_box_bg_color" value="<?php echo get_option('stellissimo_box_bg_color'); ?>" name="stellissimo_box_bg_color" />
                           <div id="stellissimo-colorpicker"></div>  
						   <span class="description">Background color</span>
                       </td>
                   </tr>
                   <tr valign="top">
                       <th scope="row"><label for="stellissimo_text_content">Box Text</label></th>
                           <td>
                               <textarea id="stellissimo_text_content" name="stellissimo_text_content" style="width:400px; height:200px"><?php echo get_option('stellissimo_text_content'); ?></textarea>
                               <span class="description"><br>Insert here a TEXT or HTML code, this will be show in each pages and posts</span>    
                           </td>
                   </tr>
                   <tr valign="top">
                       <th scope="row"></th>
                           <td>
                               <p class="submit">
                                   <input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Save Changes') ?>" />
                               </p>
                           </td>
                   </tr>
               </tbody>
           </table>
           
       </form>
   </div>
   <?php
}
// =============================== END CREATE AND ADD OPTION PAGE =============================== //

// =============================== ADD COLORPICKER ======================================== //

function stellissimo_farbtastic_load()
{
  wp_enqueue_style( 'farbtastic' );
  wp_enqueue_script( 'farbtastic' );
}

function stellissimo_colorpicker_custom_script()
{
	?>
	<script type="text/javascript">
 
  		jQuery(document).ready(function() {
    		jQuery('#stellissimo-colorpicker').hide();
    		jQuery('#stellissimo-colorpicker').farbtastic("#stellissimo_box_bg_color");
    		jQuery("#stellissimo_box_bg_color").click(function(){jQuery('#stellissimo-colorpicker').slideToggle()});
  		});
 
	</script>
	<?php
}
if(isset($_GET['page']) AND $_GET['page'] == 'stellissimo-options-page')
{
	add_action('init', 'stellissimo_farbtastic_load');
	add_action('admin_footer', 'stellissimo_colorpicker_custom_script');
}

// ================================ END ADD COLORPICKER ===================================== //