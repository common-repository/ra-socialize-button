<?php
   /*
   Plugin Name: RA-Socialize Button
   Plugin URI: http://blog.ecafechat.com/rashids-socialize-button/
   Description: RA-Socialize Button adds a Google+, twitter and facebook button to your blog post.
   Version: 2.2
   Author: Rashid Azar 
   Author URI: http://blog.ecafechat.com/
   
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAra_get_gplus_optionsR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

   */
//include_once 'stamperz_plugin.php';

$socialize = new RASocialize();

add_filter('the_content', array($socialize, 'ra_socialize'));
add_action ('wp_enqueue_scripts' ,array($socialize, 'ra_gplus_script'));
add_action ('wp_enqueue_scripts' ,array($socialize, 'ra_twitter_script'));
add_action("admin_menu", array($socialize, "ra_socialize_button_options"));

class RASocialize {
	protected $_gplus_script_name = 'google-plusone';
	protected $_gplus_script_src  = 'https://apis.google.com/js/plusone.js';
	protected $_twitter_script_name = 'twitter';
	protected $_twitter_script_src  = 'http://platform.twitter.com/widgets.js';
	
	static protected $_ra_option_page_title = 'Rashid\'s Socialize Button';
	
	static protected $_ra_option_mainmenu_title = 'RA-Socialize';
	static protected $_ra_option_gpmenu_title = 'Google Plus';
	static protected $_ra_option_fbmenu_title = 'Facebook';
	static protected $_ra_option_twmenu_title = 'Twitter';
	
	static protected $_ra_option_mainmenu_slug= 'ra_socialize_button';
	static protected $_ra_option_gpmenu_slug = 'ra_gplus';
	static protected $_ra_option_fbmenu_slug = 'ra_fb';
	static protected $_ra_option_twmenu_slug = 'ra_twitter';
	
	
	static protected $_ra_option_parent_slug = 'ra_fb_likebox';
	static protected $_ra_option_capability  = 'manage_options';
	static protected $_ra_option_icon        = 'rashid.jpg';
	
	static protected $_ra_gplus = array(
									'size'  => 'ra_sb_gplus_size',
									'anon'  => 'ra_sb_gplus_anon',
									'index' => 'ra_sb_gplus_index',
									'feed'  => 'ra_sb_gplus_feed'
								);
								
	static protected $_ra_fb = array(
									'url'  => 'ra_sb_fb_url',
									'send'  => 'ra_sb_fb_send',
									'layout' => 'ra_sb_fb_layout',
									'width'  => 'ra_sb_fb_width',
									'faces'  => 'ra_sb_fb_faces',
									'action'  => 'ra_sb_fb_action',
									'colour'  => 'ra_sb_fb_colour',
									'font'  => 'ra_sb_fb_font',
									'index' => 'ra_sb_fb_index',
									'feed'  => 'ra_sb_fb_feed'
								);
	
	function ra_socialize($content) {
		global $post;
		 //if(!is_feed() && !is_home()) {
	        $content .= '<div class="share-this">'.
							$this->ra_twitter().
							$this->ra_gplus(get_permalink($post->ID)).
							$this->ra_facebook(get_permalink($post->ID)).
						'</div>';
	    //}
	    return $content;
	}
	
	function ra_get_gplus_options() {
		$_ra_options = array(
				'size' 	 => stripslashes(get_option(self::$_ra_gplus['size'])),
				'anon'   => stripslashes(get_option(self::$_ra_gplus['anon'])),
				'index'  => stripslashes(get_option(self::$_ra_gplus['index'])),
				'feed' 	 => stripslashes(get_option(self::$_ra_gplus['feed']))
		);
		
		return $_ra_options;
	}
	
	function ra_get_fb_options() {
		$_ra_options = array(
				'url' 	 	=> stripslashes(get_option(self::$_ra_fb['url'])),
				'send'   	=> stripslashes(get_option(self::$_ra_fb['send'])),
				'layout'  	=> stripslashes(get_option(self::$_ra_fb['layout'])),
				'width' 	=> stripslashes(get_option(self::$_ra_fb['width'])),
				'faces' 	=> stripslashes(get_option(self::$_ra_fb['faces'])),
				'action' 	=> stripslashes(get_option(self::$_ra_fb['action'])),
				'colour' 	=> stripslashes(get_option(self::$_ra_fb['colour'])),
				'font' 	 	=> stripslashes(get_option(self::$_ra_fb['font'])),
				'index'  => stripslashes(get_option(self::$_ra_fb['index'])),
				'feed' 	 => stripslashes(get_option(self::$_ra_fb['feed']))
		);
		
		return $_ra_options;
	}
	
	static function ra_socialize_button_options(){
		/*
		 * RA-Socialize
		 * --Google Plus
		 * --Facebook
		 * --Twitter
		 */
		add_menu_page(
				__(self::$_ra_option_page_title), 
				self::$_ra_option_mainmenu_title, 
				self::$_ra_option_capability, 
				self::$_ra_option_mainmenu_slug,
				array('RASocialize', 'ra_socialize_gplus_page'),
				plugin_dir_url(__FILE__).self::$_ra_option_icon
			);
		add_submenu_page(
				self::$_ra_option_mainmenu_slug,
				__("Google Plus &lsaquo; " . self::$_ra_option_page_title), 
				self::$_ra_option_gpmenu_title, 
				self::$_ra_option_capability, 
				self::$_ra_option_gpmenu_slug, 
				array('RASocialize', 'ra_socialize_gplus_page'),
				plugin_dir_url(__FILE__).self::$_ra_option_icon
			);
		add_submenu_page(
				self::$_ra_option_mainmenu_slug,
				__("Facebook &lsaquo; " . self::$_ra_option_page_title), 
				self::$_ra_option_fbmenu_title, 
				self::$_ra_option_capability, 
				self::$_ra_option_fbmenu_slug, 
				array('RASocialize', 'ra_socialize_fb_page'),
				plugin_dir_url(__FILE__).self::$_ra_option_icon
			);
		/*add_submenu_page(
				self::$_ra_option_mainmenu_slug,
				__("Twitter &lsaquo; " . self::$_ra_option_page_title), 
				self::$_ra_option_twmenu_title, 
				self::$_ra_option_capability, 
				self::$_ra_option_twmenu_slug, 
				array('RASocialize', 'ra_socialize_twitter_page'),
				plugin_dir_url(__FILE__).self::$_ra_option_icon
			);*/
		remove_submenu_page(self::$_ra_option_mainmenu_slug, self::$_ra_option_mainmenu_slug);
	}
	
	static function ra_socialize_gplus_page() {
		if(isset($_POST['ra_submit'])){
			if(!empty($_POST['ra_size'])) 	update_option(self::$_ra_gplus['size']	, $_POST['ra_size']);
			if(!empty($_POST['ra_anon'])) 	update_option(self::$_ra_gplus['anon']	, $_POST['ra_anon']);
			if(!empty($_POST['ra_index'])) 	update_option(self::$_ra_gplus['index']	, $_POST['ra_index']);
			if(!empty($_POST['ra_feed'])) 	update_option(self::$_ra_gplus['feed']	, $_POST['ra_feed']);
		?>
			<div id="message" class="updated fade"><p><strong><?php _e('Options saved successfully.'); ?></strong></p></div>
		<?php	
		}
		$option_value = self::ra_get_gplus_options();
		
		?>
		<div class="wrap">
			<h2><?php _e("Rashid's Socialize Google Plus Options");?></h2><br />
			<!-- Administration panel form -->
			<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<h3>General Settings</h3>
				<table>
					<tr>
						<td width="150"><b>Size:</b></td>
						<td>
							<select name="ra_size">
								<option value="small" <?php if($option_value['size']=="small") echo "selected='selected'";?>>small</option>
								<option value="medium" <?php if($option_value['size']=="medium") echo "selected='selected'";?>>medium</option>
								<option value="standard" <?php if($option_value['size']=="standard") echo "selected='selected'";?>>standard</option>
								<option value="tall" <?php if($option_value['size']=="tall") echo "selected='selected'";?>>tall</option>
							</select>
						</td>
					</tr>
			        <tr>
			        	<td width="150"><b>Annotation:</b></td>
			        	<td>
							<select name="ra_anon">
								<option value="bubble" <?php if($option_value['anon']=="bubble") echo "selected='selected'";?>>bubble</option>
								<option value="inline" <?php if($option_value['anon']=="inline") echo "selected='selected'";?>>inline</option>
								<option value="none" <?php if($option_value['anon']=="none") echo "selected='selected'";?>>none</option>
							</select>
						</td>
			        </tr>
			        <tr>
			        	<td width="150"><b>Display on index page:</b></td>
			        	<td>
							<select name="ra_index">
								<option value="yes" <?php if($option_value['index']=="yes") echo "selected='selected'";?>>yes</option>
								<option value="no" <?php if($option_value['index']=="no") echo "selected='selected'";?>>no</option>
							</select>
						</td>
			        </tr>
			        <tr>
			        	<td width="150"><b>Display in feeds:</b></td>
			        	<td>
							<select name="ra_feed">
								<option value="yes" <?php if($option_value['feed']=="yes") echo "selected='selected'";?>>yes</option>
								<option value="no" <?php if($option_value['feed']=="no") echo "selected='selected'";?>>no</option>
							</select>
						</td>
			        </tr>
			        <tr height="60">
						<td></td>
						<td><input type="submit" name="ra_submit" value="Update Options" style="background-color:#CCCCCC;font-weight:bold;"/></td>
					</tr>
				</table>
			</form>
		</div>
		<?php
	}
	
	static function ra_socialize_fb_page() {
		if(isset($_POST['ra_submit'])){
			if(!empty($_POST['ra_url'])) 	update_option(self::$_ra_fb['url']	, $_POST['ra_url']);
			update_option(self::$_ra_fb['send']	, 'false');
			if(!empty($_POST['ra_layout'])) update_option(self::$_ra_fb['layout']	, $_POST['ra_layout']);
			if(!empty($_POST['ra_width'])) 	update_option(self::$_ra_fb['width']	, $_POST['ra_width']);
			if(!empty($_POST['ra_faces'])) 	update_option(self::$_ra_fb['faces']	, $_POST['ra_faces']);
			if(!empty($_POST['ra_action'])) 	update_option(self::$_ra_fb['action']	, $_POST['ra_action']);
			if(!empty($_POST['ra_colour'])) update_option(self::$_ra_fb['colour']	, $_POST['ra_colour']);
			if(!empty($_POST['ra_font'])) 	update_option(self::$_ra_fb['font']	, $_POST['ra_font']);
			if(!empty($_POST['ra_index'])) 	update_option(self::$_ra_fb['index']	, $_POST['ra_index']);
			if(!empty($_POST['ra_feed'])) 	update_option(self::$_ra_fb['feed']	, $_POST['ra_feed']);
		?>
			<div id="message" class="updated fade"><p><strong><?php _e('Options saved successfully.'); ?></strong></p></div>
		<?php	
		}
		$option_value = self::ra_get_fb_options();
		
		?>
		<div class="wrap">
			<h2><?php _e("Rashid's Socialize Facebook Options");?></h2><br />
			<!-- Administration panel form -->
			<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<h3>General Settings</h3>
				<table>
					<tr>
						<td width="150"><b>Layout Style:</b></td>
						<td>
							<select name="ra_layout">
								<option value="standard" <?php if($option_value['layout']=="standard") echo "selected='selected'";?>>standard</option>
								<option value="button_count" <?php if($option_value['layout']=="button_count") echo "selected='selected'";?>>buttun_count</option>
								<option value="box_count" <?php if($option_value['layout']=="box_count") echo "selected='selected'";?>>box_count</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150"></td>
						<td>(determines the size and amount of social context next to the button)</td>
					</tr>
					<tr>
						<td width="150"><b>Width:</b></td>
						<td><input type="text" name="ra_width" value="<?php echo $option_value['width'];?>"/>px</td>
					</tr>
					<tr>
						<td width="150"></td>
						<td>(Width of the facebook like box)</td>
					</tr>
			        <tr>
			        	<td width="150"><b>Verb to Display:</b></td>
						<td>
							<select name="ra_action">
								<option value="like" <?php if($option_value['action']=="like") echo "selected='selected'";?>>like</option>
								<option value="recommend" <?php if($option_value['action']=="recommend") echo "selected='selected'";?>>recommend</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150"></td>
						<td>(Select the verb you want to display)</td>
					</tr>
			        <tr>
			        	<td width="150"><b>Color Scheme:</b></td>
						<td>
							<select name="ra_color">
								<option value="light" <?php if($option_value['color']=="light") echo "selected='selected'";?>>light</option>
								<option value="dark" <?php if($option_value['color']=="dark") echo "selected='selected'";?>>dark</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150"></td>
						<td>(Select the color scheme you want to display)</td>
					</tr>
					<tr>
						<td width="150"><b>Show Faces:</b></td>
						<td>
							<select name="ra_faces">
								<option value="true" <?php if($option_value['faces']=="true") echo "selected='selected'";?>>Yes</option>
								<option value="false" <?php if($option_value['faces']=="false") echo "selected='selected'";?>>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150"></td>
						<td>(Select the option to show the faces)</td>
					</tr>
					<tr>
						<td width="150"><b>Font</b></td>
						<td>
							<select name="ra_font">
								<option value="ariel" <?php if($option_value['font']=="ariel") echo "selected='selected'";?>>ariel</option>
								<option value="lucida grande" <?php if($option_value['font']=="lucida grande") echo "selected='selected'";?>>lucida grande</option>
								<option value="segoi ui" <?php if($option_value['font']=="segoi ui") echo "selected='selected'";?>>segoi ui</option>
								<option value="tahoma" <?php if($option_value['font']=="tahoma") echo "selected='selected'";?>>tahoma</option>
								<option value="trebuchet ms" <?php if($option_value['font']=="trebuchet ms") echo "selected='selected'";?>>trebuchet ms</option>
								<option value="verdana" <?php if($option_value['font']=="verdana") echo "selected='selected'";?>>verdana</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150"></td>
						<td>(Select the font for the plugin)</td>
					</tr>
			        <tr>
			        	<td width="150"><b>Display on index page:</b></td>
			        	<td>
							<select name="ra_index">
								<option value="yes" <?php if($option_value['index']=="yes") echo "selected='selected'";?>>yes</option>
								<option value="no" <?php if($option_value['index']=="no") echo "selected='selected'";?>>no</option>
							</select>
						</td>
			        </tr>
			        <tr>
			        	<td width="150"><b>Display in feeds:</b></td>
			        	<td>
							<select name="ra_feed">
								<option value="yes" <?php if($option_value['feed']=="yes") echo "selected='selected'";?>>yes</option>
								<option value="no" <?php if($option_value['feed']=="no") echo "selected='selected'";?>>no</option>
							</select>
						</td>
			        </tr>
			        <tr height="60">
						<td></td>
						<td><input type="submit" name="ra_submit" value="Update Options" style="background-color:#CCCCCC;font-weight:bold;"/></td>
					</tr>
				</table>
			</form>
		</div>
		<?php
	}
	
	function ra_gplus($permalink) {
		$size = '';
		$anon = '';
		
		$gp_opt = self::ra_get_gplus_options();
		
		if($gp_opt['size'] != "standard") {
			$size = 'size="' . $gp_opt['size'] . '"';
		}
		
		if($gp_opt['anon'] != "inline") {
			$anon = 'annotation="' . $gp_opt['anon'] . '"';
		}
		
		if(is_feed() && $gp_opt['feed'] == "no" || is_home() && $gp_opt['index'] == "no") {
			return "";
		} else {
			return '<div class="plusone" style="width:70px;float:left"><g:plusone '.$size.' '.$anon.' href="'.$permalink.'"></g:plusone></div>';
		}
	}
	
	function ra_gplus_script() {
		wp_enqueue_script($this->_gplus_script_name, $this->_gplus_script_src, array(), null);
	}
	
	function ra_twitter_script() {
		wp_enqueue_script($this->_twitter_script_name, $this->_twitter_script_src, array(), null);
	}
	
	function ra_twitter() {
		return '<div id="twitter-share-button" style="width:100px;float:left"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a></div>';
	}
	
	function ra_facebook($permalink) {
		$option_value = self::ra_get_fb_options();
		
		if(is_feed() && $gp_opt['feed'] == "no" || is_home() && $gp_opt['index'] == "no") {
			return "";
		} else {
			return '<div class="facebook-share-button">
					<iframe src="https://www.facebook.com/plugins/like.php?href='.
									urlencode($permalink).
									'&amp;send='.$option_value['send'].
									'&amp;layout='.$option_value['layout'].
									'&amp;width='.$option_value['width'].
									'&amp;show_faces='.$option_value['faces'].
									'&amp;action='.$option_value['action'].
									'&amp;colorscheme='.$option_value['colour'].
									'&amp;font='.$option_value['font'].
									'&amp;height=21" 
						scrolling="no" 
						frameborder="0" 
						style="border:none; overflow:hidden; width:'.$option_value['width'].'px; height:21px;" 
						allowTransparency="true"></iframe>
				</div>';
		}
	}
}