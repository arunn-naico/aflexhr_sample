<?php
/**
 * Plugin Name: Responsive Portfolio
 * Version: 2.0
 *Responsive Portfolio Allow You To Add Unlimited Images To Your Portfolio Integrated With Light Box Preview And Masonry Effect.
* Author: Weblizar
 * Author URI: http://www.weblizar.com
 * Plugin URI: http://weblizar.com/plugins/responsive-portfolio/
 */

/**
 * Constant Variable
 */
define("WEBLIZAR_RP_TEXT_DOMAIN","weblizar_rpg" );
define("WEBLIZAR_RP_PLUGIN_URL", plugin_dir_url(__FILE__));

// Run 'Install' script on plugin activation
register_activation_hook( __FILE__, 'DefaultSettingsPortfolio' );
function DefaultSettingsPortfolio(){
    $DefaultSettingsArray = serialize( array(
        'WL_Hover_Animation' => "fade",
        'WL_Gallery_Layout' => "col-md-6",
        'WL_Image_Border' => "yes",
        'WL_Hover_Color_Opacity' => 0.5,
        'WL_Font_Style' => "Arial",
        'WL_Masonry_Layout' => "no",
		'WL_Gallery_Title' => "yes",
		'WL_Zoom_Animation' => "yes",
    ) );
    add_option("WRP_Portfolio_Settings", $DefaultSettingsArray);
}

//Get Ready Plugin Translation
add_action('plugins_loaded', 'GetReadyTranslationPortfolio');
function GetReadyTranslationPortfolio() {
	load_plugin_textdomain(WEBLIZAR_RP_TEXT_DOMAIN, FALSE, dirname( plugin_basename(__FILE__)).'/languages/' );
}

// Register Custom Post Type
function ResponsivePortfolio() {

    $labels = array(
        'name'                => _x( 'Responsive Portfolio', 'Responsive Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'singular_name'       => _x( 'Responsive Portfolio', 'Responsive Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'menu_name'           => __( 'Responsive Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'parent_item_colon'   => __( 'Parent Item:', WEBLIZAR_RP_TEXT_DOMAIN ),
        'all_items'           => __( 'All Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'view_item'           => __( 'View Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'add_new_item'        => __( 'Add New Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'add_new'             => __( 'Add New Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'edit_item'           => __( 'Edit Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'update_item'         => __( 'Update Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'search_items'        => __( 'Search Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'not_found'           => __( 'No Gallery Found', WEBLIZAR_RP_TEXT_DOMAIN ),
        'not_found_in_trash'  => __( 'No Gallery found in Trash', WEBLIZAR_RP_TEXT_DOMAIN ),
    );
    $args = array(
        'label'               => __( 'Responsive Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'description'         => __( 'Responsive Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'labels'              => $labels,
        'supports'            => array( 'title', '', '', '', '', '', '', '', '', '', '', ),
        //'taxonomies'          => array( 'category', 'post_tag' ),
         'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-format-gallery',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'capability_type'     => 'page',
    );
    register_post_type( 'responsive-portfolio', $args );

}

// Hook into the 'init' action
add_action( 'init', 'ResponsivePortfolio', 0 );


/**
 * Add Meta Box & load required CSS and JS for interface
 */
add_action('admin_init','ResponsivePortfolio_init');
function ResponsivePortfolio_init() {
    add_meta_box('ResponsivePortfolio_meta', __('Add New Images', WEBLIZAR_RP_TEXT_DOMAIN), 'responsive_portfolio_function', 'responsive-portfolio', 'normal', 'high');
    add_action('save_post','responsive_portfolio_meta_save');
	add_meta_box(__('Plugin Shortcode', WEBLIZAR_RP_TEXT_DOMAIN) , __('Plugin Shortcode', WEBLIZAR_RP_TEXT_DOMAIN), 'rp_plugin_shortcode', 'responsive-portfolio', 'side', 'low');
	add_meta_box(__('Show us some love, Rate Us', WEBLIZAR_RP_TEXT_DOMAIN) , __('Show us some love, Rate Us', WEBLIZAR_RP_TEXT_DOMAIN), 'Rate_us_meta_box_function_portfolio', 'responsive-portfolio', 'side', 'low');
	 add_meta_box(__('Upgrade To Pro Version', WEBLIZAR_RP_TEXT_DOMAIN) , __('Upgrade To Pro Version', WEBLIZAR_RP_TEXT_DOMAIN), 'wrp_upgrade_to_pro_function', 'responsive-portfolio', 'side', 'low');
    
	add_meta_box(__('Pro Features', WEBLIZAR_RP_TEXT_DOMAIN) , __('Pro Features', WEBLIZAR_RP_TEXT_DOMAIN), 'wrp_pro_features', 'responsive-portfolio', 'side', 'low');
		
	wp_enqueue_script('theme-preview');
    wp_enqueue_script('rpg-media-uploads',WEBLIZAR_RP_PLUGIN_URL.'js/rpg-media-upload-script.js',array('media-upload','thickbox','jquery'));
    wp_enqueue_style('dashboard');
    wp_enqueue_style('rpg-meta-css', WEBLIZAR_RP_PLUGIN_URL.'css/rpg-meta.css');
    wp_enqueue_style('thickbox');
}

	/**
	 * Rate Us Meta Box
	 */
	function Rate_us_meta_box_function_portfolio() { ?>
		<style>
		.fag-rate-us span.dashicons{
			width: 30px;
			height: 30px;
		}
		.fag-rate-us span.dashicons-star-filled:before {
			content: "\f155";
			font-size: 30px;
		}
		</style>
		<div align="center">
			<p>Please Review & Rate Us On WordPress</p>
			<a class="upgrade-to-pro-demo .fag-rate-us" style=" text-decoration: none; height: 40px; width: 40px;" href="http://wordpress.org/plugins/responsive-portfolio/" target="_blank">
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
			</a>
		</div>
		<div class="upgrade-to-pro-demo" style="text-align:center;margin-bottom:10px;margin-top:10px;">
			<a href="http://wordpress.org/plugins/responsive-portfolio/" target="_blank" class="button button-primary button-hero">RATE US</a>
		</div>
		<?php
	}
function wrp_upgrade_to_pro_function(){
?>
<div class="upgrade-to-pro-demo" style="text-align:center;margin-bottom:10px;margin-top:10px;">
	<a href="http://demo.weblizar.com/responsive-portfolio-pro/"  target="_new" class="button button-primary button-hero">View Live Demo</a>
</div>
<div class="upgrade-to-pro-admin-demo" style="text-align:center;margin-bottom:10px;">
	<a href="http://weblizar.com/plugins/responsive-portfolio-pro/" target="_new" class="button button-primary button-hero">View Admin Demo</a>
</div>
<div class="upgrade-to-pro" style="text-align:center;margin-bottom:10px;">
	<a href="http://weblizar.com/plugins/responsive-portfolio-pro/" target="_new" class="button button-primary button-hero">Upgarde To Pro</a>
</div>
<?php
}

function wrp_pro_features(){
	?>

	<ul style="">
				<li class="plan-feature">Responsive Design</li>
				<li class="plan-feature">8 Gallery Layout</li>
				<li class="plan-feature">Unlimited Hover Color</li>
				<li class="plan-feature">10 Types of Hover Color Opacity</li>
				<li class="plan-feature">Isotope/Masonry Effects</li>
				<li class="plan-feature">Album View Gallery</li>
				<li class="plan-feature">Youtube/Vimeo Video</li>
				<li class="plan-feature">Extrenal Link Portfolio</li>
				<li class="plan-feature">Carousel Slider</li>
				<li class="plan-feature">Gallery Pagination</li>
				<li class="plan-feature">All Gallery Shortcode</li>
				<li class="plan-feature">Each Gallery has Unique Shortcode</li>
				<li class="plan-feature">8 Types of Hover Animation</li>
				<li class="plan-feature">5 Types of Gallery Design Layout</li>
				<li class="plan-feature">500+ of Font Style</li>
				<li class="plan-feature">4 types Of Lightbox Integrated</li>
				<li class="plan-feature">Drag and Drop image Position</li>
				<li class="plan-feature">Multiple Image uploader</li>
				<li class="plan-feature">Shortcode Button on post or page</li>
				<li class="plan-feature">Unique settings for each gallery</li>
				<li class="plan-feature">Hide/Show gallery Title and label</li>
				<li class="plan-feature">Font icon Customization</li>
				<li class="plan-feature">Google Fonts</li>
				
				
			</ul>
	<?php 
} 
/**
plugin shortcode
**/
function rp_plugin_shortcode(){
?>
<p>Use below shortcode in any Page/Post to publish your Responsive Portfolio</p>
		<input readonly="readonly" type="text" value="<?php echo "[WRP id=".get_the_ID()."]"; ?>"> 
<?php
} 


/**
 * Meta box interface design
 */
function responsive_portfolio_function() {
    $rpg_all_photos_details = unserialize(get_post_meta( get_the_ID(), 'rpg_all_photos_details', true));
    $TotalImages =  get_post_meta( get_the_ID(), 'rpg_total_images_count', true );
    $i = 1;
    ?>
	<style>
		#titlediv #title {
		margin-bottom:15px;
		}
	</style>
    <input type="hidden" id="count_total" name="count_total" value="<?php if($TotalImages==0){ echo 0; } else { echo $TotalImages; } ?>"/>
    <div style="clear:left;"></div>

    <?php
    /* load saved photos into gallery */
    if($TotalImages) {
        foreach($rpg_all_photos_details as $rpg_single_photos_detail) {
            $name = $rpg_single_photos_detail['rpg_image_label'];
            $url = $rpg_single_photos_detail['rpg_image_url'];
            ?>
                <div class="rpg-image-entry" id="rpg_img<?php echo $i; ?>">
                        <a class="gallery_remove" href="#gallery_remove" id="rpg_remove_bt<?php echo $i; ?>"onclick="remove_meta_img(<?php echo $i; ?>)"><img src="<?php echo  WEBLIZAR_RP_PLUGIN_URL.'images/Close-icon.png'; ?>" /></a>
                        <img src="<?php echo  $url; ?>" class="rpg-meta-image" alt=""  style="">
                        <input type="button" id="upload-background-<?php echo $i; ?>" name="upload-background-<?php echo $i; ?>" value="Upload Image" class="button-primary" onClick="weblizar_image('<?php echo $i; ?>')" />
                        <input type="text" id="rpg_img_url<?php echo $i; ?>" name="rpg_img_url<?php echo $i; ?>" class="rpg_label_text"  value="<?php echo  $url; ?>"  readonly="readonly" style="display:none;" />
                        <input type="text" id="image_label<?php echo $i; ?>" name="image_label<?php echo $i; ?>" placeholder="Enter Image Label" class="rpg_label_text" value="<?php echo $name; ?>">
                </div>
            <?php
            $i++;
        } // end of foreach
    } else {
        $TotalImages = 0;
    }
    ?>


    <div id="append_rpg_img">
    </div>
    <div class="rpg-image-entry add_rpg_new_image" onclick="add_rpg_meta_img()">
            <div class="dashicons dashicons-plus"></div>
            <p><?php _e('Add New Image', WEBLIZAR_RP_TEXT_DOMAIN); ?></p>
    </div>
    <div style="clear:left;"></div>
    <script>
    var rpg_i = parseInt(jQuery("#count_total").val());
    function add_rpg_meta_img() {
        rpg_i = rpg_i + 1;

        var rpg_output = '<div class="rpg-image-entry" id="rpg_img'+ rpg_i +'">'+
                            '<a class="gallery_remove" href="#gallery_remove" id="rpg_remove_bt' + rpg_i + '"onclick="remove_meta_img(' + rpg_i + ')"><img src="<?php echo  WEBLIZAR_RP_PLUGIN_URL.'images/Close-icon.png'; ?>" /></a>'+
                            '<img src="<?php echo  WEBLIZAR_RP_PLUGIN_URL.'images/rpg-default.jpg'; ?>" class="rpg-meta-image" alt=""  style="">'+
                            '<input type="button" id="upload-background-' + rpg_i + '" name="upload-background-' + rpg_i + '" value="Upload Image" class="button-primary" onClick="weblizar_image(' + rpg_i + ')" />'+
                            '<input type="text" id="rpg_img_url'+ rpg_i +'" name="rpg_img_url'+ rpg_i +'" class="rpg_label_text"  value="<?php echo  WEBLIZAR_RP_PLUGIN_URL.'images/rpg-default.jpg'; ?>"  readonly="readonly" style="display:none;" />'+
                            '<input type="text" id="image_label'+ rpg_i +'" name="image_label'+ rpg_i +'" placeholder="Enter Image Label" class="rpg_label_text"   >'+
                        '</div>';
        jQuery(rpg_output).hide().appendTo("#append_rpg_img").slideDown(500);
        jQuery("#count_total").val(rpg_i);
    }

    function remove_meta_img(id){
        jQuery("#rpg_img"+id).slideUp(600, function(){
            jQuery(this).remove();
        });

        count_total = jQuery("#count_total").val();
        count_total = count_total - 1;
        var id_i= id + 1;

        for(var i=id_i;i<=rpg_i;i++){
            var j = i-1;
            jQuery("#rpg_remove_bt"+i).attr('onclick','remove_meta_img('+j+')');
            jQuery("#rpg_remove_bt"+i).attr('id','rpg_remove_bt'+j);
            jQuery("#rpg_img_url"+i).attr('name','rpg_img_url'+j);
            jQuery("#image_label"+i).attr('name','image_label'+j);
            jQuery("#rpg_img_url"+i).attr('id','rpg_img_url'+j);
            jQuery("#image_label"+i).attr('id','image_label'+j);

            jQuery("#rpg_img"+i).attr('id','rpg_img'+j);
        }
        jQuery("#count_total").val(count_total);
        rpg_i = rpg_i - 1;
    }
    </script>
    <?php
}


/**
 * Save All Photo Gallery Images
 */
function responsive_portfolio_meta_save() {
    if(isset($_POST['post_ID'])) {
        $post_ID = $_POST['post_ID'];
        $post_type = get_post_type($post_ID);
        if($post_type == 'responsive-portfolio') {
            $TotalImages = $_POST['count_total'];
            $ImagesArray = array();
            if($TotalImages) {
                for($i=1; $i <= $TotalImages; $i++) {
                    $image_label = "image_label".$i;
                    $name = $_POST['image_label'.$i];
                    $url = $_POST['rpg_img_url'.$i];
                    $ImagesArray[] = array(
                        'rpg_image_label' => $name,
                        'rpg_image_url' => $url
                    );
                }
                update_post_meta($post_ID, 'rpg_all_photos_details', serialize($ImagesArray));
                update_post_meta($post_ID, 'rpg_total_images_count', $TotalImages);
            } else {
                $TotalImages = 0;
                update_post_meta($post_ID, 'rpg_total_images_count', $TotalImages);
                $ImagesArray = array();
                update_post_meta($post_ID, 'rpg_all_photos_details', serialize($ImagesArray));
            }
        }
    }
}


/**
 * Weblizar Responsive Gallery Short Code Detect Function
 */
function WeblizarResponsivePortfolioShortCodeDetect() {
    global $wp_query;
    $Posts = $wp_query->posts;
    $Pattern = get_shortcode_regex();

    foreach ($Posts as $Post) {
        if( preg_match_all( '/'. $Pattern .'/s', $Post->post_content, $Matches ) && array_key_exists( 2, $Matches ) && in_array( 'WRP', $Matches[2] ) ) {
            /**
             * js scripts
             */
       
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-litelighter',WEBLIZAR_RP_PLUGIN_URL.'js/jquery-litelighter.js', array('jquery'));
            wp_enqueue_script('jquery-rebox',WEBLIZAR_RP_PLUGIN_URL.'js/jquery-rebox.js', array('jquery'));

            
			wp_enqueue_script('modernizr.custom', WEBLIZAR_RP_PLUGIN_URL.'js/modernizr.custom.js', array('jquery'));
			wp_enqueue_script('imagesloaded11', WEBLIZAR_RP_PLUGIN_URL.'js/masonry.pkgd.min.js', array('jquery'));
			wp_enqueue_script('imagesloaded', WEBLIZAR_RP_PLUGIN_URL.'js/imagesloaded.js', array('jquery'));
			wp_enqueue_script('classie', WEBLIZAR_RP_PLUGIN_URL.'js/classie.js', array('jquery'));
			wp_enqueue_script('AnimOnScroll', WEBLIZAR_RP_PLUGIN_URL.'js/AnimOnScroll.js', array('jquery'));
            
	/**   
             * css scripts
             */
		  wp_enqueue_style('rpgp-boot-strap-css', WEBLIZAR_RP_PLUGIN_URL.'css/bootstrap.css');
           wp_enqueue_style('rpgp-img-gallery-css', WEBLIZAR_RP_PLUGIN_URL.'css/img-gallery.css');
		   wp_enqueue_style('component', WEBLIZAR_RP_PLUGIN_URL.'css/component.css');
		   wp_enqueue_style('jquery-rebox-css', WEBLIZAR_RP_PLUGIN_URL.'css/jquery-rebox.css');

      /**
             * font awesome css
             */
            
          wp_enqueue_script( 'hover-img', WEBLIZAR_RP_PLUGIN_URL.'js/jquery.hoverdir-top.js', array('jquery'), '', true );
		  wp_enqueue_script( 'lightbox-script', WEBLIZAR_RP_PLUGIN_URL.'js/lightbox-script.js', array('jquery'), '', true );
		
			
            break;
        } //end of if
    } //end of foreach
}
add_action( 'wp', 'WeblizarResponsivePortfolioShortCodeDetect' );

/**
 * Settings Page for Responsive Gallery
 */
add_action('admin_menu' , 'WRP_SettingsPage');

function WRP_SettingsPage() {
    add_submenu_page('edit.php?post_type=responsive-portfolio', __('Settings', WEBLIZAR_RP_TEXT_DOMAIN), __('Settings', WEBLIZAR_RP_TEXT_DOMAIN), 'administrator', 'image-portfolio-settings', 'image_portfolio_settings_page_function');
  add_submenu_page('edit.php?post_type=responsive-portfolio', 'Pro Screenshots', 'Pro Screenshots', 'administrator', 'get-responsive-portfolio-pro-plugin', 'get_portfolio_pro_page_function');
 }

/**
 * Photo Gallery Settings Page
 */
function image_portfolio_settings_page_function() {
    //css
    wp_enqueue_style('wl-font-awesome-4', WEBLIZAR_RP_PLUGIN_URL.'css/font-awesome-4.0.3/css/font-awesome.min.css');
    require_once("responsive-portfolio-settings.php");
}
/**
 * Get Responsive Portfolio Pro Plugin Page
 */
function get_portfolio_pro_page_function() {
    //css
    wp_enqueue_style('wl-font-awesome-4', WEBLIZAR_RP_PLUGIN_URL.'css/font-awesome-4.0.3/css/font-awesome.min.css');
    wp_enqueue_style('wl-pricing-table-css', WEBLIZAR_RP_PLUGIN_URL.'css/pricing-table.css');
    wp_enqueue_style('wl-boot-strap-admin', WEBLIZAR_RP_PLUGIN_URL.'css/bootstrap-admin.css');
    require_once("get-responsive-gallery-pro.php");
}

/**
 * Responsive Gallery Short Code [WRG]
 */
require_once("responsive-portfolio-short-code.php");