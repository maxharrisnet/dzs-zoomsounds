<?php
//error_log("ceva");

class DZSAudioPlayer {

    public $thepath;
    public $base_url;
    public $base_path;
    public $admin_capability = 'manage_options';
    public $dbname_mainitems = 'dzsap_items';
    public $dbname_mainitems_configs = 'dzsap_vpconfigs';
    public $dbname_options = 'dzsap_options';
    public $dbname_dbs = 'dzsap_dbs';
    public $adminpagename = 'dzsap_menu';
    public $adminpagename_configs = 'dzsap_configs';
    public $adminpagename_mo = 'dzsap-mo';
    public $adminpagename_autoupdater = 'dzsap-autoupdater';
    public $adminpagename_about = 'dzsap-about';
    public $page_mainoptions_link = 'dzsap-mo';
    public $the_shortcode = 'zoomsounds';
    public $mainitems;
    public $mainitems_configs;
    public $mainoptions;
    public $sliders_index = 0;
    public $sliders__player_index = 0;
    public $cats_index = 0;
    public $dbs = array();
    public $currDb = '';
    public $vpconfigsstr = '';
    public $currSlider = '';
    public $sliderstructure = '';
    public $videoplayerconfig = '';
    public $pluginmode = "plugin";
    public $alwaysembed = "on";
    public $httpprotocol = 'https';
    public $sample_data = array();
    private $dbname_sample_data = 'dzsap_sample_data';

    public $options_item_meta = array();
    public $og_data = array();


    public $has_generated_product_player = false;
    public $db_has_read_mainitems = false;


    public $options_array_player = array();
    public $options_slider = array();
    public $options_slider_categories_lng = array();
    public $item_meta_categories_lng = array();


    private $usecaching = true;
    private $sw_enable_multisharer = false;
    private $debug = false;

    private $wc_called_loop_from = '';

    public $taxname_sliders = 'dzsap_sliders';



    public $svg_star = '<svg enable-background="new -1.23 -8.789 141.732 141.732" height="141.732px" id="Livello_1" version="1.1" viewBox="-1.23 -8.789 141.732 141.732" width="141.732px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Livello_100"><path d="M139.273,49.088c0-3.284-2.75-5.949-6.146-5.949c-0.219,0-0.434,0.012-0.646,0.031l-42.445-1.001l-14.5-37.854   C74.805,1.824,72.443,0,69.637,0c-2.809,0-5.168,1.824-5.902,4.315L49.232,42.169L6.789,43.17c-0.213-0.021-0.43-0.031-0.646-0.031   C2.75,43.136,0,45.802,0,49.088c0,2.1,1.121,3.938,2.812,4.997l33.807,23.9l-12.063,37.494c-0.438,0.813-0.688,1.741-0.688,2.723   c0,3.287,2.75,5.952,6.146,5.952c1.438,0,2.766-0.484,3.812-1.29l35.814-22.737l35.812,22.737c1.049,0.806,2.371,1.29,3.812,1.29   c3.393,0,6.143-2.665,6.143-5.952c0-0.979-0.25-1.906-0.688-2.723l-12.062-37.494l33.806-23.9   C138.15,53.024,139.273,51.185,139.273,49.088"/></g><g id="Livello_1_1_"/></svg>';


	public $allowed_tags = array(
		'p'=>array(
			'class'=>array(),
			'style' => array(),
		),
		'strong'=>array(),
		'em'=>array(),
		'br'=>array(),
		'a'=>array(
			'href' => array(),
			'target' => array(),
			'style' => array(),
			'class' => array(),
		),
		'span'=>array(
			'style' => array(),
			'class' => array(),
		),
		'i'=>array(
			'style' => array(),
			'class' => array(),
		),
	);




	function __construct() {
        if ($this->pluginmode == 'theme') {
            $this->thepath = THEME_URL . 'plugins/dzs-zoomsounds/';
        } else {
            $this->thepath = plugins_url('', __FILE__) . '/';
        }


        $this->base_path = dirname(__FILE__) . '/';

        $this->base_url = $this->thepath;

        //clear database
        //update_option($this->dbname_dbs, '');


//return false;



	    add_action('init', array($this, 'handle_init'));
	    add_action('init', array($this, 'handle_init_end'),900);

		add_action('widgets_init', array($this, 'handle_widgets_init'));

//        error_log(print_rrr($_GET));

//        echo 'ceva';
        include( dirname(__FILE__).'/woo/woo-plugin.php' );




	    if(isset($_GET['taxonomy']) && $_GET['taxonomy']==$this->taxname_sliders){
		    include_once('admin/sliders_admin.php');
		    add_action('in_admin_footer','dzsap_sliders_admin');


	    }


	    add_action( 'edited_'.$this->taxname_sliders, array($this,'dzsap_sliders_save_taxonomy_custom_meta'));
    }



	function handle_init() {
		global $pagenow;



		$this->item_meta_categories_lng = array(
			'misc'=>esc_html__("Miscellaneous",'dzsap'),
			'extra_html'=>esc_html__("Extra HTML",'dzsap'),
		);


		$this->db_read_default_opts();

		$this->post_options();






		require_once("class_parts/options_array_player.php");


		if (isset($_POST['deleteslider'])) {
			//print_r($this->mainitems);
			if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename) {
				unset($this->mainitems[$_POST['deleteslider']]);
				$this->mainitems = array_values($this->mainitems);
				$this->currSlider = 0;
				//print_r($this->mainitems);
				update_option($this->dbname_mainitems, $this->mainitems);
			}


			if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_configs) {
				unset($this->mainitems_configs[$_POST['deleteslider']]);
				$this->mainitems_configs = array_values($this->mainitems_configs);
				$this->currSlider = 0;
				//print_r($this->mainitems);
				update_option($this->dbname_mainitems_configs, $this->mainitems_configs);
			}
		}

		//echo get_admin_url('', 'options-general.php?page=' . $this->adminpagename) . dzs_curr_url();
		//echo $newurl;

		$uploadbtnstring = '<button class="button-secondary action upload_file ">'.__("Upload").'</button>';



		if ($this->mainoptions['usewordpressuploader'] != 'on') {
			$uploadbtnstring = '<div class="dzs-upload">
<form name="upload" action="#" method="POST" enctype="multipart/form-data">
    	<input type="button" value="Upload" class="btn_upl"/>
        <input type="file" name="file_field" class="file_field"/>
        <input type="submit" class="btn_submit"/>
</form>
</div>
<div class="feedback"></div>';
		}

		///==== important: settings must have the class mainsetting
		$this->sliderstructure = '<div class="slider-con" style="display:none;">

        <div class="settings-con">
        <h4>' . __('General Options', 'dzsap') . '</h4>
        <div class="setting type_all">
            <div class="setting-label">' . __('ID', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting main-id" name="0-settings-id" value="default"/>
            <div class="sidenote">' . __('Choose an unique id.', 'dzsap') . '</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Gallery Skin', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-galleryskin">
                <option>skin-wave</option>
                <option>skin-default</option>
                <option>skin-aura</option>
            </select>
        </div>
        <div class="setting type_all vpconfig-wrapper">
            <div class="setting-label">' . __('ZoomSounds Player Configuration', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme vpconfig-select" name="0-settings-vpconfig">
                <option value="default">' . __('default', 'dzsap') . '</option>
                ' . $this->vpconfigsstr . '
            </select>
            <div class="sidenote" style="">' . __('setup these inside the <strong>ZoomSounds Player Configs</strong> admin', 'dzsap') . ' <a id="quick-edit" class="quick-edit-vp" href="'.admin_url('admin.php?page=' . $this->adminpagename_configs.'&currslider=0&from=shortcodegenerator').'" class="sidenote" style="cursor:pointer;">'.__("Quick Edit ").'</a></div>
            <div class="edit-link-con"></div>
        </div>';


		$lab = 'mode';
		$this->sliderstructure.='<div class="setting type_all">
            <div class="setting-label">' . __('Mode', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme dzs-dependency-field" name="0-settings-'.$lab.'">
                <option value="mode-normal">'.__("Default").'</option>
                <option value="mode-showall">'.__("Show All").'</option>
            </select>
            <div class="sidenote">' . sprintf(__('%sshow all%s lists the players one below the other ', 'dzsap'),'<strong>','</strong>') . '</div>
        </div>';



		$dependency = array(

			array(
				'element'=>'0-settings-mode',
				'value'=>array('mode-normal'),
			),
		)
		;

		$aux = json_encode($dependency);
		$aux_dependency_for_mode_normal = str_replace('"','{quotquot}',$aux);




		$dependency = array(

			array(
				'element'=>'0-settings-mode',
				'value'=>array('mode-showall'),
			),
		)
		;

		$aux = json_encode($dependency);
		$aux_dependency_for_mode_show_all = str_replace('"','{quotquot}',$aux);



		/*
		 *
		 *
		 *
		 *
		 */


		/*
		 *
		<div class="setting type_all">
			<div class="setting-label">' . __('Player Navigation', 'dzsap') . '</div>
			<select class="textinput mainsetting styleme" name="0-settings-player_navigation">
				<option value="default">' . __('Default', 'dzsap') . '</option>
				<option value="off">' . __('Force Disable', 'dzsap') . '</option>
				<option value="on">' . __('Force Enable', 'dzsap') . '</option>
			</select>
			<div class="sidenote">' . __('Default will decide automatically if the player needs navigation or no', 'dzsap') . '</div>
		</div>
		 *
		 *
		 */

		$this->sliderstructure.='
        
        
        <div class="setting type_all" data-dependency=&quot;'.$aux_dependency_for_mode_show_all.'&quot;>
            <div class="setting-label">' . __('Enable number indicator', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_mode_showall_show_number">
                <option value="on">' . __('on', 'dzsap') . '</option>
                <option value="off">' . __('off', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . __('Disable arrows for gallery navigation on the player ', 'dzsap') . '</div>
        </div>
        
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Disable Player Navigation', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-disable_player_navigation">
                <option value="off">' . __('off', 'dzsap') . '</option>
                <option value="on">' . __('on', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . __('Disable arrows for gallery navigation on the player ', 'dzsap') . '</div>
        </div>
        
        <div class="setting">
            <div class="setting-label">' . __('Background', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting with-colorpicker" name="0-settings-bgcolor" value="transparent"/><div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
        </div>
        
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Linking', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_enable_linking">
                <option value="off">' . __('off', 'dzsap') . '</option>
                <option value="on">' . __('on', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . __('when selecting a track in the menu the link will update to reflect the new track selected', 'dzsap') . '</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Order by', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-orderby">
                <option value="custom">' . __('default', 'dzsap') . '</option>
                <option value="rand">' . __('random', 'dzsap') . '</option>
                <option value="ratings">' . __('ratings', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . __('random or drag and drop', 'dzsap') . '</div>
        </div>
        
        
        
        
        
        <br>
        <div class="dzstoggle toggle1" rel=""  data-dependency=&quot;'.$aux.'&quot;>
<div class="toggle-title" style="">' . __('Menu Options', 'dzsap') . '</div>
<div class="toggle-content">


<div class="setting type_all" data-dependency=&quot;'.$aux.'&quot;>
            <div class="setting-label">' . __('Menu Position', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-menuposition">
                <option>bottom</option>
                <option>none</option>
                <option>top</option>
            </select>
        </div>
        <div class="setting type_all" data-dependency=&quot;'.$aux.'&quot; >
            <div class="setting-label">' . __('Menu State', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-design_menu_state">
                <option value="open">'.__("Open").'</option>
                <option value="closed">'.__("Closed").'</option>
            </select>
            <div class="sidenote">' . __('If you set this to closed, you should enable the <strong>Menu State Button</strong> below. ', 'dzsap') . '</div>
        </div>
        <div class="setting type_all" data-dependency=&quot;'.$aux.'&quot;>
            <div class="setting-label">' . __('Menu State Button', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-design_menu_show_player_state_button">
                <option>off</option>
                <option>on</option>
            </select>
        </div>
        <div class="setting type_all" >
            <div class="setting-label">' . __('Facebook Share', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-menu_facebook_share">
                <option>auto</option>
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('enable a facebook share button in the menu ', 'dzsap') . '</div>
        </div>
        <div class="setting type_all" >
            <div class="setting-label">' . __('Like Button', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-menu_like_button">
                <option>auto</option>
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('enable a like button in the menu ', 'dzsap') . '</div>
        </div>


</div>
</div>


        <div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Autoplay Options', 'dzsap') . '</div>
<div class="toggle-content">


        <div class="setting type_all">
            <div class="setting-label">' . __('Cue First Media', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-cuefirstmedia">
                <option value="on">' . __('on', 'dzsap') . '</option>
                <option value="off">' . __('off', 'dzsap') . '</option>
            </select>
        </div>

        <div class="setting type_all">
            <div class="setting-label">' . __('Autoplay', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplay">
                <option value="on">' . __('on', 'dzsap') . '</option>
                <option value="off">' . __('off', 'dzsap') . '</option>
            </select>
        </div>
        
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Autoplay Next', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplaynext">
                <option value="on">' . __('on', 'dzsap') . '</option>
                <option value="off">' . __('off', 'dzsap') . '</option>
            </select>
        </div>
</div>
</div>
        
        
        <div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Play / Like Settings', 'dzsap') . '</div>
<div class="toggle-content">


<div class="setting type_all">
            <div class="setting-label">' . __('Enable Play Count', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_views">
                <option value="off">' . __('off', 'dzsap') . '</option>
                <option value="on">' . __('on', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . __('enable play count - warning: the media file has to be attached to a library item ( the Link To Media field .. ) ', 'dzsap') . '</div>
        </div>


<div class="setting type_all">
            <div class="setting-label">' . __('Enable Downloads Counter', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_downloads_counter">
                <option value="off">' . __('off', 'dzsap') . '</option>
                <option value="on">' . __('on', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . __('enable download count - warning: the media file has to be attached to a library item ( the Link To Media field .. ) ', 'dzsap') . '</div>
        </div>

        <div class="setting type_all">
            <div class="setting-label">' . __('Enable Like Count', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_likes">
                <option value="off">' . __('off', 'dzsap') . '</option>
                <option value="on">' . __('on', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . __('enable like count - warning: the media file has to be attached to a library item ( the Link To Media field .. ) ', 'dzsap') . '</div>
        </div>


        <div class="setting type_all">
            <div class="setting-label">' . __('Enable Rating', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_rates">
                <option value="off">' . __('off', 'dzsap') . '</option>
                <option value="on">' . __('on', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . __('enable rating - warning: the media file has to be attached to a library item ( the Link To Media field .. ) ', 'dzsap') . '</div>
        </div>



</div>
</div>





        
        
        <div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Force Dimensions', 'dzsap') . '</div>
<div class="toggle-content">


        <div class="setting type_all">
            <div class="setting-label">' . __('Force Width', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-width" value=""/>
            <div class="sidenote">' . __('Force a fix width, leave blank for responsive mode ', 'dzsap') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Force Height', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-height" value=""/>
            <div class="sidenote">' . __('Force a fix height, leave blank for default mode ', 'dzsap') . '</div>
        </div>
        
        
        
        <div class="setting type_all" data-dependency=&quot;'.$aux.'&quot;>
            <div class="setting-label">' . __('Menu Maximum Height', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-design_menu_height" value="default"/>
        </div>
        
</div>
</div>

        

        </div><!--end settings con-->

        <div class="master-items-con mode_all">
        <div class="items-con "></div>
        <a href="#" class="add-item"></a>
        </div><!--end master-items-con-->
        <div class="clear"></div>
        </div>';
		$this->itemstructure = $this->generate_item_structure();




		/*
		 *
		 *
		 *
		<div class="setting">
			<div class="setting-label">' . __('Background', 'dzsap') . '</div>
			<input type="text" class="textinput mainsetting with-colorpicker" name="0-settings-bgcolor" value="transparent"/><div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
		</div>
		 *
		 *
		 */


		// todo: why do we have this ?
		include_once "class_parts/vpconfig.php";



		// --- check posts
		if(isset($_GET['dzsap_shortcode_builder']) && $_GET['dzsap_shortcode_builder']=='on'){
//            dzsprx_shortcode_builder();

			include_once(dirname(__FILE__).'/tinymce/popupiframe.php');
			define('DONOTCACHEPAGE', true);
			define('DONOTMINIFY', true);

		}


		if(isset($_GET['dzsap_shortcode_player_builder']) && $_GET['dzsap_shortcode_player_builder']=='on'){
//            dzsprx_shortcode_builder();

			include_once(dirname(__FILE__).'/shortcodegenerator/generator_player.php');
			define('DONOTCACHEPAGE', true);
			define('DONOTMINIFY', true);

		}



		if($this->mainoptions['replace_powerpress_plugin']=='on'){

			add_filter('the_content', array($this,'filter_the_content'));
		}


		add_shortcode('dzsap_show_curr_plays', array($this, 'show_curr_plays'));
		add_shortcode('zoomsounds_player', array($this, 'shortcode_player'));
		add_shortcode('zoomsounds_player_comment_field', array($this, 'shortcode_player_comment_field'));

		add_shortcode('dzsap_woo_grid', array($this, 'shortcode_woo_grid'));
		add_shortcode('player_button', array($this, 'shortcode_player_button'));
		add_shortcode($this->the_shortcode, array($this, 'show_shortcode'));
		add_shortcode($this->the_shortcode.'_in_lightbox', array($this, 'show_shortcode_lightbox'));
		add_shortcode('dzs_' . $this->the_shortcode, array($this, 'show_shortcode'));
		add_shortcode('' . $this->the_shortcode.'_showcase', array($this, 'shortcode_showcase'));
		add_shortcode('dzsap_wishlist', array($this, 'shortcode_wishlist'));
		

		if($this->mainoptions['replace_playlist_shortcode'] == 'on'){

			add_shortcode('playlist', array($this, 'shortcode_playlist'));

//            add_editor_style( $this->base_url . 'audioplayer/audioplayer.css' );
		}
		if($this->mainoptions['replace_audio_shortcode'] && $this->mainoptions['replace_audio_shortcode']!=='off'){

			add_shortcode('audio', array($this, 'shortcode_audio'));
		}



		add_filter('attachment_fields_to_edit', array($this, 'filter_attachment_fields_to_edit'), 10, 2);
		add_filter('attachment_fields_to_save', array($this, "filter_attachment_fields_to_save"), null, 2);


//        return false;
		add_action('admin_init', array($this, 'handle_admin_init'));

		add_action('wp_ajax_dzs_get_attachment_src', array($this, 'ajax_get_attachment_src'));
		add_action('wp_ajax_dzsap_ajax', array($this, 'post_save'));
		add_action('wp_ajax_dzsap_save_configs', array($this, 'post_save_configs'));
		add_action('wp_ajax_dzsap_ajax_mo', array($this, 'post_save_mo'));
		add_action('wp_ajax_dzsap_delete_pcm', array($this, 'ajax_delete_pcm'));
		add_action('wp_ajax_dzsap_parse_content_to_shortcode', array($this, 'ajax_parse_content_to_shortcode'));
		add_action('wp_ajax_dzsap_send_queue_from_sliders_admin', array($this, 'ajax_send_queue_from_sliders_admin'));



		// -- getdemo.php
		add_action('wp_ajax_dzsap_import_item_lib', array($this, 'ajax_import_item_lib'));

		add_action('wp_ajax_dzsap_front_submitcomment', array($this, 'ajax_front_submitcomment'));
		add_action('wp_ajax_dzsap_get_thumb_from_meta', array($this, 'ajax_get_thumb_from_meta'));
		add_action('wp_ajax_dzsap_submit_download', array($this, 'ajax_submit_download'));
		add_action('wp_ajax_dzsap_submit_views', array($this, 'ajax_submit_views'));
		add_action('wp_ajax_dzsap_submit_like', array($this, 'ajax_submit_like'));
		add_action('wp_ajax_dzsap_retract_like', array($this, 'ajax_retract_like'));
		add_action('wp_ajax_dzsap_submit_rate', array($this, 'ajax_submit_rate'));
		add_action('wp_ajax_dzsap_get_pcm', array($this, 'ajax_get_pcm'));
		add_action('wp_ajax_nopriv_dzsap_get_pcm', array($this, 'ajax_get_pcm'));
		add_action('wp_ajax_dzsap_add_to_wishlist', array($this, 'ajax_add_to_wishlist'));
		add_action('wp_ajax_nopriv_dzsap_add_to_wishlist', array($this, 'ajax_add_to_wishlist'));

		add_action('wp_ajax_nopriv_dzsap_front_submitcomment', array($this, 'ajax_front_submitcomment'));
		add_action('wp_ajax_nopriv_dzsap_submit_download', array($this, 'ajax_submit_download'));
		add_action('wp_ajax_nopriv_dzsap_submit_views', array($this, 'ajax_submit_views'));
		add_action('wp_ajax_nopriv_dzsap_submit_like', array($this, 'ajax_submit_like'));
		add_action('wp_ajax_nopriv_dzsap_retract_like', array($this, 'ajax_retract_like'));
		add_action('wp_ajax_nopriv_dzsap_submit_rate', array($this, 'ajax_submit_rate'));
		add_action('wp_ajax_dzsap_submit_pcm', array($this, 'ajax_submit_pcm'));
		add_action('wp_ajax_nopriv_dzsap_submit_pcm', array($this, 'ajax_submit_pcm'));



		add_action('wp_ajax_dzsap_delete_notice', array($this, 'ajax_delete_notice'));
		add_action('wp_ajax_dzsap_activate', array($this, 'ajax_activate_license'));
		add_action('wp_ajax_dzsap_deactivate', array($this, 'ajax_deactivate_license'));

		add_action('wp_ajax_ajax_dzsap_insert_sample_tracks', array($this, 'ajax_submit_sample_tracks'));
		add_action('wp_ajax_nopriv_ajax_dzsap_insert_sample_tracks', array($this, 'ajax_submit_sample_tracks'));

		add_action('wp_ajax_ajax_dzsap_remove_sample_tracks', array($this, 'ajax_remove_sample_tracks'));
		add_action('wp_ajax_nopriv_ajax_dzsap_remove_sample_tracks', array($this, 'ajax_remove_sample_tracks'));


		if ($this->mainoptions['activate_comments_widget']=='on') {
			add_action('wp_dashboard_setup', array($this, 'wp_dashboard_setup'));
		}



		if ($this->mainoptions['enable_raw_shortcode']=='on') {
			remove_filter('the_content', 'wpautop');
			remove_filter('the_content', 'wptexturize');
			add_filter('the_content', array($this, 'my_formatter'), 99);
		}



		if ($this->mainoptions['tinymce_disable_preview_shortcodes'] != 'on') {
//            add_filter('mce_external_plugins', array( &$this, 'tinymce_external_plugins' ));
//            add_filter('tiny_mce_before_init', array( $this, 'myformatTinyMCE' ) );
		}


		if ($this->mainoptions['analytics_enable'] == 'on') {
			add_action('wp_dashboard_setup', array($this, 'wp_dashboard_setup'));
			include_once("class_parts/analytics.php");
		}


		add_action('admin_menu', array($this, 'handle_admin_menu'));
		add_action('admin_head', array($this, 'handle_admin_head'));
		add_action('admin_footer', array($this, 'handle_admin_footer'));


		add_action('wp_footer', array($this, 'handle_wp_footer'));
		add_action('wp_head', array($this, 'handle_wp_head'));


		add_action('add_meta_boxes',array($this,'handle_add_meta_boxes'));

		add_action('save_post',array($this,'admin_meta_save'));



//		add_filter('script_loader_tag', array($this,'filter_add_defer_attribute'), 10, 2);


//        add_action('woocommerce_after_main_content',array($this,'handle_woocommerce_after_main_content'));
//        add_action('woocommerce_after_shop_loop_item',array($this,'handle_woocommerce_after_shop_loop_item'));
//        add_action('woocommerce_shop_loop_item_title',array($this,'handle_woocommerce_shop_loop_item_title'));




		if($this->mainoptions['wc_single_product_player'] && $this->mainoptions['wc_single_product_player']!='off'){


//            echo ' $this->mainoptions[\'wc_loop_player_position\'] -  '.$this->mainoptions['wc_loop_player_position'];
			if($this->mainoptions['wc_single_player_position']=='top'){


				add_action('woocommerce_single_product_summary',array($this,'handle_woocommerce_single_product_summary'));
			}
			if($this->mainoptions['wc_single_player_position']=='overlay'){
				add_action('woocommerce_single_product_summary',array($this,'handle_woocommerce_single_product_summary'));
			}
			if($this->mainoptions['wc_single_player_position']=='bellow'){

//                echo 'hmm';
				add_action('woocommerce_single_product_summary',array($this,'handle_woocommerce_single_product_summary'));
			}


		}
		if($this->mainoptions['wc_loop_product_player'] && $this->mainoptions['wc_loop_product_player']!='off'){


//            echo ' $this->mainoptions[\'wc_loop_player_position\'] -  '.$this->mainoptions['wc_loop_player_position'];
			if($this->mainoptions['wc_loop_player_position']=='top'){
				add_action('woocommerce_before_shop_loop_item',array($this,'handle_woocommerce_before_shop_loop_item'));
			}


			if($this->mainoptions['wc_loop_player_position']=='overlay'){
				add_action('woocommerce_before_shop_loop_item_title',array($this,'handle_woocommerce_before_shop_loop_item'));
			}

			if($this->mainoptions['wc_loop_player_position']=='bellow'){
				add_action('woocommerce_after_shop_loop_item',array($this,'handle_woocommerce_before_shop_loop_item'));
			}


		}


//        add_action('woocommerce_product_thumbnails', array($this, 'test'));
//        add_action('woocommerce_add_to_cart',array($this,'handle_woocommerce_add_to_cart'));
//        add_action('woocommerce_before_shop_loop',array($this,'handle_woocommerce_before_shop_loop'));
//        add_action('woocommerce_after_cart',array($this,'handle_woocommerce_after_cart'));
//        add_filter('wc_add_to_cart_message',array($this,'filter_wc_add_to_cart_message'));




//        include( dirname(__FILE__).'/woo/woo-plugin.php' );





		if(isset($_GET) && isset($_GET['load-lightbox-css']) && $_GET['load-lightbox-css']=='on'){

			header("Content-type: text/css");
			?>
            .dzsap-main-con.loaded-item {
            opacity: 1;
            visibility: visible; }

            .dzsap-main-con.loading-item {
            opacity: 1;
            visibility: visible; }

            .dzsap-main-con {
            z-index: 5555;
            position: fixed;
            width: 100%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            top: 0;
            left: 0;
            transition-property: opacity, visibility;
            transition-duration: 0.3s;
            transition-timing-function: ease-out; }

            .dzsap-main-con .overlay-background {
            background-color: rgba(50, 50, 50, 0.5);
            position: absolute;
            width: 100%;
            height: 100%; }

            .dzsap-main-con .box-mains-con {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none; }

            .dzsap-main-con .box-main {
            pointer-events: auto;
            max-width: 100%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate3d(-50%, -50%, 0);
            transition-property: left, opacity;
            transition-duration: 0.3s;
            transition-timing-function: ease-out; }

            .dzsap-main-con.transition-slideup.loaded-item .transition-target {
            opacity: 1;
            visibility: visible;
            transform: translate3d(0, 0, 0); }

            .dzsap-main-con.transition-slideup .transition-target {
            opacity: 0;
            visibility: hidden;
            transform: translate3d(0, 50px, 0);
            transition-property: all;
            transition-duration: 0.3s;
            transition-timing-function: ease-out; }

            .dzsap-main-con .box-main-media-con {
            max-width: 100%; }

            .dzsap-main-con .box-main .close-btn-con {
            position: absolute;
            right: -15px;
            top: -15px;
            z-index: 5;
            cursor: pointer;
            width: 30px;
            height: 30px;
            background-color: #dadada;
            border-radius: 50%; }

            .dzsap-main-con.gallery-skin-default .box-main-media {
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.3); }

            .dzsap-main-con .box-main-media-con .box-main-media {
            transition-property: width, height;
            transition-duration: 0.3s;
            transition-timing-function: ease-out; }

            .box-main-media.type-inlinecontent {
            background-color: #ffffff;
            padding: 15px; }

            .dzsap-main-con.skin-default .box-main:not(.with-description) .real-media {
            border-radius: 5px; }

            .dzsap-main-con .box-main-media-con .box-main-media > .real-media {
            width: 100%;
            height: 100%; }





            .real-media .hidden-content-for-zoombox, .real-media > .hidden-content {
            display: block !important; }

            .hidden-content {
            display: none !important; }

            .social-icon {
            margin-right: 3px;
            position: relative; }

            .social-icon > .fa {
            font-size: 30px;
            color: #999;
            transition-property: color;
            transition-duration: 0.3s;
            transition-timing-function: cubic-bezier(0.165, 0.84, 0.44, 1); }

            .social-icon > .the-tooltip {
            line-height: 1;
            padding: 6px 5px;
            background: rgba(0, 0, 0, 0.7);
            color: #FFFFFF;
            font-family: "Lato", "Open Sans", arial;
            font-size: 11px;
            font-weight: bold;
            position: absolute;
            left: 8px;
            white-space: nowrap;
            pointer-events: none;
            bottom: 100%;
            margin-bottom: 7px;
            opacity: 0;
            visibility: hidden;
            transition-property: opacity,visibility;
            transition-duration: 0.3s;
            transition-timing-function: cubic-bezier(0.165, 0.84, 0.44, 1); }


            .social-icon:hover > .the-tooltip{
            opacity:1;
            visibility: visible;
            }

            .social-icon > .the-tooltip:before {
            content: "";
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 6px 6px 0 0;
            border-color: rgba(0, 0, 0, 0.7) transparent transparent transparent;
            position: absolute;
            left: 0;
            top: 100%; }

            h6.social-heading {
            display: block;
            text-transform: uppercase;
            font-family: "Lato",sans-sarif;
            font-size: 11px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #222222; }

            .field-for-view {
            background-color: #f0f0f0;
            line-height: 1;
            color: #555;
            padding: 8px;
            white-space: nowrap;
            font-size: 13px;
            overflow: hidden;
            text-overflow: ellipsis;
            font-family: 'Monospaced', Arial; }

            textarea.field-for-view {
            width: 100%;
            white-space: pre-line;
            line-height: 1.75; }
			<?php
			die();
		}















		$post_id = '';
		if (isset($_GET['post']) && $_GET['post'] != '') {
			$post_id = $_GET['post'];
		}

		if($this->mainoptions['try_to_hide_url']=='on'){

			if (!session_id()) {
				session_start();
			}
		}






		if(isset($_GET['dzsap_action']) && $_GET['dzsap_action']) {
//            dzsprx_shortcode_builder();

			if ($_GET['dzsap_action'] == 'generatenonce') {

				$id = $_GET['id'];


				$lab = 'dzsap_nonce_for_' . $id . '_ip_' . $_SERVER['REMOTE_ADDR'];
				$lab = $this->clean($lab);





				$nonce = rand(0,10000);

				//                $id = $it['id'];


//                error_log('id - '.$id);


				if($_SESSION[$lab]){

					$nonce = $_SESSION[$lab];
				}else{

					$_SESSION[$lab] = $nonce;
				}

				$src = site_url().'/index.php?dzsap_action=get_track_source&id='.$id.'&'.$lab.'='.$nonce;


				echo $src;

				die();

			}
		}


		if(isset($_GET['dzsap_action']) && $_GET['dzsap_action']){
//            dzsprx_shortcode_builder();

			if($_GET['dzsap_action']=='get_track_source'){

				$id = $_GET['id'];


				$po = (get_post($id));


				$src_url = '';



				$src_url = get_post_meta($po->ID, 'dzsap_woo_product_track', true);



				$playerid='';
				$args=array();
				if($src_url == ''){
					$src_url = $this->get_track_source($po->ID,$playerid, $args);
				}



//                echo '$src_url - '.$src_url;



//                error_log('$src_url -> '.$src_url);


				if($id && $src_url) {


//            echo 'whaaa';
					$this->sliders__player_index++;

					$fout = '';



//                    print_r($_SESSION);


//                    error_log(print_rrr($_SESSION));
//                    error_log(print_rrr($_GET));




					$lab = 'dzsap_nonce_for_'.$id.'_ip_'.$_SERVER['REMOTE_ADDR'];
					$lab =$this->clean($lab);

					if($_SESSION[$lab]==$_GET[$lab] ){




						$extension = "mp3";
						$mime_type = "audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3";
//
//
//                        error_log('src - '.$src);
//
//
//                        error_log('fileSize - '.$fileSize);
//                        error_log('fileSize - '.$fileSize);
//
////                        header('HTTP/1.1 206 Partial Content'); // Allows scanning in a stream.



//                        print_rr($_SERVER);
//                        echo 'site_url - '.site_url().'<br>';
//                        echo 'dirname(dirname(dirname(dirname(__FILE__)))) - '.dirname(dirname(dirname(dirname(__FILE__)))).'<br>';
//                        echo '$src_url - '.$src_url;



						if(strpos($src_url,site_url() )!==false){

							$src = str_replace(site_url(),dirname(dirname(dirname(dirname(__FILE__)))), $src_url);
							$fileSize = filesize($src);

							header('Accept-Ranges: bytes'); // Allows scanning in a stream based on byte count.
							header('Content-type: '.$mime_type);
//                        header("Content-transfer-encoding: binary");
							header('Content-length: ' . $fileSize);
							header('Content-Range: bytes '.'0'.'-'.$fileSize); // This tells the player what byte we're starting with.
							header('Content-Disposition:  filename="' . $src);
							header('X-Pad: avoid browser bug');
							header('Cache-Control: no-cache');


							readfile($src);
							die();
						}
//
//
////                        echo file_get_contents($src);
//                        readfile($src);







//                        $_SESSION['dzsap_nonce_for_'.$id] = 'dada';


						$file = '';
						if(strpos($src_url,site_url())!==false){
							$file = str_replace(site_url(), dirname(dirname(dirname(dirname(__FILE__)))), $src_url);

						}else{




							if (ini_get('allow_url_fopen')) {
								echo file_get_contents($src_url);
							} else {


								$ch = curl_init($src_url);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($ch, CURLOPT_HEADER, 0);
								curl_setopt($ch, CURLOPT_TIMEOUT, 10);
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
								$cache = curl_exec($ch);
								curl_close($ch);

								echo $cache;
							}
							die();
						}

						$content_type = 'application/octet-stream';





						@error_reporting(0);

						// Make sure the files exists, otherwise we are wasting our time
						if (!file_exists($file)) {
							header("HTTP/1.1 404 Not Found");
							exit;
						}

						// Get file size
						$filesize = sprintf("%u", filesize($file));

						// Handle 'Range' header
						if(isset($_SERVER['HTTP_RANGE'])){
							$range = $_SERVER['HTTP_RANGE'];
						}elseif($apache = apache_request_headers()){
							$headers = array();
							foreach ($apache as $header => $val){
								$headers[strtolower($header)] = $val;
							}
							if(isset($headers['range'])){
								$range = $headers['range'];
							}
							else $range = FALSE;
						} else $range = FALSE;

						//Is range
						if($range){
							$partial = true;
							list($param, $range) = explode('=',$range);
							// Bad request - range unit is not 'bytes'
							if(strtolower(trim($param)) != 'bytes'){
								header("HTTP/1.1 400 Invalid Request");
								exit;
							}
							// Get range values
							$range = explode(',',$range);
							$range = explode('-',$range[0]);
							// Deal with range values
							if ($range[0] === ''){
								$end = $filesize - 1;
								$start = $end - intval($range[0]);
							} else if ($range[1] === '') {
								$start = intval($range[0]);
								$end = $filesize - 1;
							}else{
								// Both numbers present, return specific range
								$start = intval($range[0]);
								$end = intval($range[1]);
								if ($end >= $filesize || (!$start && (!$end || $end == ($filesize - 1)))) $partial = false; // Invalid range/whole file specified, return whole file
							}
							$length = $end - $start + 1;
						}
						// No range requested
						else $partial = false;

						// Send standard headers
						header("Content-Type: $content_type");
						header("Content-Length: $filesize");
						header('Accept-Ranges: bytes');

						// send extra headers for range handling...
						if ($partial) {
							header('HTTP/1.1 206 Partial Content');
							header("Content-Range: bytes $start-$end/$filesize");
							if (!$fp = fopen($file, 'rb')) {
								header("HTTP/1.1 500 Internal Server Error");
								exit;
							}
							if ($start) fseek($fp,$start);
							while($length){
								set_time_limit(0);
								$read = ($length > 8192) ? 8192 : $length;
								$length -= $read;
								print(fread($fp,$read));
							}
							fclose($fp);
						}
						//just send the whole file
						else readfile($file);
						exit;


						/*
						*/
					}else{

						die("nonce not correct ".$_SESSION[$lab].'|'.$_GET[$lab]);
					}



//                    echo 'src - '.$src;



//        print_r($its); print_r($margs); echo 'alceva'.$fout;
				}


				die();

			}

		}

		//wp_deregister_script('jquery');        wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"), false, '1.9.0');
		wp_enqueue_script('jquery');
		if (is_admin()) {
			wp_enqueue_style('dzsap_admin_global', $this->base_url . 'admin/admin_global.css');
			wp_enqueue_script('dzsap_admin_global', $this->base_url . 'admin/admin_global.js');
			if ($this->mainoptions['activate_comments_widget']=='on') {
				wp_enqueue_script('googleapi', 'https://www.google.com/jsapi');
			}

			wp_enqueue_style('ultibox', $this->base_url . 'libs/ultibox/ultibox.css');
			wp_enqueue_script('ultibox', $this->base_url . 'libs/ultibox/ultibox.js');

			if ($pagenow == 'post.php') {
				$po = get_post($post_id);
				if ($po && $po->post_type == 'attachment') {
					wp_enqueue_media();
				}
			}



			wp_enqueue_style('dzsselector', $this->base_url.'libs/dzsselector/dzsselector.css');
			wp_enqueue_script('dzsselector', $this->base_url.'libs/dzsselector/dzsselector.js');

			if (isset($_GET['page'])) {
//                echo $this->adminpagename_mo;
				if($_GET['page']==$this->adminpagename_mo){

					wp_enqueue_style('dzs.dzstoggle', $this->base_url . 'dzstoggle/dzstoggle.css');
					wp_enqueue_script('dzs.dzstoggle', $this->base_url . 'dzstoggle/dzstoggle.js');

					$this->enqueue_fontawesome();
					wp_enqueue_style('dzstabsandaccordions', $this->thepath . 'libs/dzstabsandaccordions/dzstabsandaccordions.css');
					wp_enqueue_script('dzstabsandaccordions', $this->thepath . "libs/dzstabsandaccordions/dzstabsandaccordions.js");
				}
			}

			if (isset($_GET['page']) && ($_GET['page'] == $this->adminpagename || $_GET['page'] == $this->adminpagename_configs)) {
				wp_enqueue_media();
				$this->admin_scripts();


				wp_enqueue_style('ultibox', $this->base_url . 'libs/ultibox/ultibox.css');
				wp_enqueue_script('ultibox', $this->base_url . 'libs/ultibox/ultibox.js');


				$url = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';

				if($this->mainoptions['fontawesome_load_local']=='on'){
					$url = $this->base_url.'libs/fontawesome/font-awesome.min.css';
				}


				wp_enqueue_style('fontawesome',$url);
				wp_enqueue_style('dzstabsandaccordions', $this->thepath . 'libs/dzstabsandaccordions/dzstabsandaccordions.css');
				wp_enqueue_script('dzstabsandaccordions', $this->thepath . "libs/dzstabsandaccordions/dzstabsandaccordions.js");
			}


			if(isset($_GET['taxonomy']) && $_GET['taxonomy']=='dzsap_sliders'){
				wp_enqueue_script('jquery-ui-sortable');
				$url = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';

				if($this->mainoptions['fontawesome_load_local']=='on'){
					$url = $this->base_url.'libs/fontawesome/font-awesome.min.css';
				}


				wp_enqueue_style('fontawesome',$url);
				wp_enqueue_style('dzs.tooltip', $this->base_url . 'dzstooltip/dzstooltip.css');




				wp_enqueue_media();
			}

			if (isset($_GET['page']) && $_GET['page'] == 'dzsap-dc') {
				wp_enqueue_style('dzsap-dc.style', $this->base_url . 'deploy/designer/style/style.css');
				wp_enqueue_script('dzs.farbtastic', $this->base_url . "libs/farbtastic/farbtastic.js");
				wp_enqueue_style('dzs.farbtastic', $this->base_url . 'libs/farbtastic/farbtastic.css');
				wp_enqueue_script('dzsap-dc.admin', $this->base_url . 'deploy/designer/js/admin.js');
			}

			if (isset($_GET['page']) && $_GET['page'] == $this->page_mainoptions_link) {

				wp_enqueue_style('dzscheckbox', $this->base_url . 'libs/dzscheckbox/dzscheckbox.css');


				if(isset($_GET['dzsap_shortcode_builder']) && $_GET['dzsap_shortcode_builder']=='on'){

					wp_enqueue_style('dzsap_shortcode_builder_style', $this->base_url . 'tinymce/popup.css');
					wp_enqueue_script('dzsap_shortcode_builder', $this->base_url . 'tinymce/popup.js');
					wp_enqueue_style('dzs.tabsandaccordions', $this->base_url . 'libs/dzstabsandaccordions/dzstabsandaccordions.css');
					wp_enqueue_script('dzs.tabsandaccordions', $this->base_url . 'libs/dzstabsandaccordions/dzstabsandaccordions.js');
					wp_enqueue_media();


					wp_enqueue_style('ultibox', $this->base_url . 'libs/ultibox/ultibox.css');
					wp_enqueue_script('ultibox', $this->base_url . 'libs/ultibox/ultibox.js');
				}else{



					if(isset($_GET['dzsap_shortcode_player_builder']) && $_GET['dzsap_shortcode_player_builder']=='on'){


						wp_enqueue_style('dzsap_shortcode_builder_style', $this->base_url . 'tinymce/popup.css');
						wp_enqueue_style('dzsap_shortcode_player_builder_style', $this->base_url . 'shortcodegenerator/generator_player.css');
						wp_enqueue_script('dzsap_shortcode_player_builder', $this->base_url . 'shortcodegenerator/generator_player.js');

						wp_enqueue_style('dzs.tabsandaccordions', $this->base_url . 'libs/dzstabsandaccordions/dzstabsandaccordions.css');
						wp_enqueue_script('dzs.tabsandaccordions', $this->base_url . 'libs/dzstabsandaccordions/dzstabsandaccordions.js');
						wp_enqueue_media();


						wp_enqueue_style('ultibox', $this->base_url . 'libs/ultibox/ultibox.css');
						wp_enqueue_script('ultibox', $this->base_url . 'libs/ultibox/ultibox.js');


						wp_enqueue_style('dzs.tooltip', $this->base_url . 'dzstooltip/dzstooltip.css');
					}else{

						wp_enqueue_style('dzsap_admin', $this->base_url . 'admin/admin.css');
						wp_enqueue_script('dzsap_admin', $this->base_url . "admin/admin.js");
						wp_enqueue_script('jquery-ui-core');
						wp_enqueue_script('jquery-ui-sortable');
						wp_enqueue_script('jquery-ui-slider');
						$url = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css";
						wp_enqueue_style('jquery-ui-smoothness', $url, false, null);
						wp_enqueue_script('dzs.farbtastic', $this->base_url . "libs/farbtastic/farbtastic.js");
						wp_enqueue_style('dzs.farbtastic', $this->base_url . 'libs/farbtastic/farbtastic.css');
					}

				}


			}

			if (current_user_can('edit_posts') || current_user_can('edit_pages') || current_user_can('dzsap_make_shortcode')) {

				wp_enqueue_script('dzsap_htmleditor', $this->base_url . 'tinymce/plugin-htmleditor.js');
				wp_enqueue_script('dzsap_configreceiver', $this->base_url . 'tinymce/receiver.js');
			}






		} else {
			if (isset($this->mainoptions['always_embed']) && $this->mainoptions['always_embed'] == 'on') {
				$this->front_scripts();
				wp_enqueue_style('ultibox', $this->base_url . 'libs/ultibox/ultibox.css');
				wp_enqueue_script('ultibox', $this->base_url . 'libs/ultibox/ultibox.js');
			}
		}


		$this->register_links();




		$this->check_posts_init();



		if (isset($_POST['action'])) {


			if ( $_POST['action'] == 'dzsap_send_total_time_for_track' ) {


			    if(isset($_POST['id_track'])){

				    $po_id = $_POST['id_track'];


				    update_post_meta( $po_id, 'dzsap_total_time', $_POST['postdata'] );
                }


				die();
			}
		}



		if (isset($_GET['action'])) {



		    // -- download get
			if($_GET['action']=='dzsap_download'){


				if(isset($_GET['id'])&&$_GET['id']){

					$po = get_post($_GET['id']);

//                    print_r($po);


                    $title = 'song';


                    if($po && $po->post_title){
                        $title = $po->post_title;
                    }


					if($this->mainoptions['allow_download_only_for_registered_users']=='on'){
						global $current_user;

//                        print_rr($current_user);


						if($current_user->ID){


							if($this->mainoptions['allow_download_only_for_registered_users_capability'] && $this->mainoptions['allow_download_only_for_registered_users_capability']!='read'){
								if(current_user_can($this->mainoptions['allow_download_only_for_registered_users_capability'])){

								}else{

									die(__("You do not have permission",'dzsap'));
								}
							}
						}else{

							die(__("You need to register",'dzsap'));
						}
					}


					$filename = '';

					$path = '';
					if($po->post_type=='product'){


						$filename = $this->get_track_source($po->ID,$ceva,$cevamargs);

						if(strpos($filename,site_url())!==false){
							$path = str_replace(site_url(),ABSPATH,$filename);
						}
					}
					if($po->post_type=='attachment'){

						$filename = wp_get_attachment_url($po->ID);
						$path = get_attached_file( $po->ID );
					}
					if($po->post_type=='dzsap_items'){

						$ceva = 0;
						$cevamargs =array();
					    $filename = $this->get_track_source($po->ID,$ceva,$cevamargs);

					    $path = '';

					    if(strpos($filename,site_url())!==false){
					        $path = str_replace(site_url(),ABSPATH,$filename);
                        }


//					    echo site_url();
//					    echo ABSPATH;
//					    error_log('$filename - '.$filename);
					}

					if($filename==''){
						if($filename==''){
							if(function_exists('get_field')){
								$arr = get_field('scratch_preview',$po->ID);


								if($arr){

									$media = wp_get_attachment_url($arr);

//                echo 'media - '.$media;
									$filename = $media;
								}
							}
						}
					}

//                    echo $filename;


					$extension = 'mp3';
					$content_type = 'application/octet-stream';

					 // -- dzs ap download
					if(strpos($filename,'.m4a')!==false){
						$extension = 'm4a';
						$content_type = 'audio/mp4';
//						$content_type = 'audio/x-m4a';
					}
					if(strpos($filename,'.wav')!==false){
						$extension = 'wav';
						$content_type = 'audio/wav';
//						$content_type = 'audio/x-m4a';
					}


//                    echo $filename;

					header("Pragma: public");
					header("Expires: 0");
//					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//					header("Cache-Control: public");
//					header("Content-Description: File Transfer");
					header("Content-Type: '.$content_type.'");
					header("Content-Disposition: attachment; filename=\"".strtolower($title).".".$extension."\"");
					header("Content-Transfer-Encoding: binary");
//                    header("Content-Length: ".filesize($filename));


//                    $path = '';


                    if($path){

	                    header('Content-Length: '.filesize($path));

	                    readfile($path);
                    }else{

	                    echo  file_get_contents($filename);
                    }





					$this->insert_activity(array(
						'id_video'=>$po->ID,
						'type'=>'download',
					));




                    // --end id


				}else{
					if(isset($_GET['link'])&&$_GET['link']){

//                        $aux  =$_GET['link'];
						$aux = explode('/',$_GET['link']);
						$filename = $aux[count($aux)-1];

						$filename=html_entity_decode($filename);

//                        echo $filename;
//                        print_r($aux);


						$extension = 'mp3';

						// -- dzsap download from link

						if(strpos($_GET['link'],'.m4a')!==false){
							$extension = 'm4a';
						}

						header("Pragma: public");
						header("Expires: 0");
						header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
						header("Cache-Control: public");
						header("Content-Description: File Transfer");
						header("Content-type: application/octet-stream");
						header("Content-Disposition: attachment; filename=\"".$filename.".".$extension."\"");
						header("Content-Transfer-Encoding: binary");


						echo  file_get_contents($_GET['link']);


					}else{

						echo __("You need to set media id");
					}

				}
				die();
			}
		}


		if(isset($_GET['dzsap_action']) && $_GET['dzsap_action']=='load_charts_html') {
			$yesterday = date("d M", time() - 60 * 60 * 24);
			$days_2 = date("d M", time() - 60 * 60 * 24 * 2);
			$days_3 = date("d M", time() - 60 * 60 * 24 * 3);

//	            echo 'hmm-'.$yesterday.'-'.$days_2;

//                $yesterday = 'ceva';
//	            $days_2 = 'ceva2';

			// -- chart

			$trackid = $_POST['postdata'];
			$arr = array(
				'labels'=>array(__('Track'),__('Views'),__('Likes')),
				'lastdays'=>array(
					array(

						$days_3,
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'4',
							'day_end'=>'3',
							'type'=>'view',
							'get_count'=>'off',
						)),
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'4',
							'day_end'=>'3',
							'type'=>'like',
							'get_count'=>'off',
						)),
					),
					array(

						$days_2,
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'3',
							'day_end'=>'2',
							'type'=>'view',
						)),
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'3',
							'day_end'=>'2',
							'type'=>'like',
						)),
					),

					array(

						$yesterday,
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'2',
							'day_end'=>'1',
							'type'=>'view',
						)),
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'2',
							'day_end'=>'1',
							'type'=>'like',
						)),
					),
					array(

						__("Today"),
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'1',
							'day_end'=>'0',
							'type'=>'view',
						)),
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'1',
							'day_end'=>'0',
							'type'=>'like',
						)),

					),
				),

			);

//	            error_log(print_r($arr,true));



			?>
            <div class="hidden-data"><?php echo json_encode($arr); ?></div>


			<?php



			$last_month = date("M", time() - 60 * 60 * 31);
			$month_2 = date("M", time() - 60 * 60 * 24 * 62);
			$month_3 = date("M", time() - 60 * 60 * 24 * 93);


//	            echo 'hmm-'.$yesterday.'-'.$days_2;

//                $yesterday = 'ceva';
//	            $days_2 = 'ceva2';

			$trackid = $_POST['postdata'];
			$arr = array(
				'labels'=>array(__('Track'),__('Minutes watched')),
				'lastdays'=>array(
					array(

						$month_3,
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'120',
							'day_end'=>'90',
							'type'=>'timewatched',
							'get_count'=>'off',
							'id_user'=>'0',
						)),
					),
					array(

						$month_2,
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'90',
							'day_end'=>'60',
							'type'=>'timewatched',
							'get_count'=>'off',
							'id_user'=>'0',
						)),
					),
					array(

						$last_month,
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'60',
							'day_end'=>'30',
							'type'=>'timewatched',
							'get_count'=>'off',
							'id_user'=>'0',
						)),
					),

					array(

						"This month",
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'30',
							'day_end'=>'0',
							'type'=>'timewatched',
							'get_count'=>'off',
							'id_user'=>'0',
						)),
					),
				),

			);

//	            error_log(print_r($arr,true));

			?>
            <div class="hidden-data-time-watched"><?php echo json_encode($arr); ?></div>
			<?php

			$last_month = date("M", time() - 60 * 60 * 31);
			$month_2 = date("M", time() - 60 * 60 * 24 * 62);
			$month_3 = date("M", time() - 60 * 60 * 24 * 93);


//	            echo 'hmm-'.$yesterday.'-'.$days_2;

//                $yesterday = 'ceva';
//	            $days_2 = 'ceva2';


			// -- time watched
			$trackid = $_POST['postdata'];
			$arr = array(
				'labels'=>array(__('Track'),__('Number of plays')),
				'lastdays'=>array(
					array(

						$month_3,
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'120',
							'day_end'=>'90',
							'type'=>'view',
							'get_count'=>'off',
							'id_user'=>'0',
						)),
					),
					array(

						$month_2,
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'90',
							'day_end'=>'60',
							'type'=>'view',
							'get_count'=>'off',
							'id_user'=>'0',
						)),
					),
					array(

						$last_month,
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'60',
							'day_end'=>'30',
							'type'=>'view',
							'get_count'=>'off',
							'id_user'=>'0',
						)),
					),

					array(

						"This month",
						$this->mysql_get_track_activity($trackid, array(
							'get_last'=>'day',
							'day_start'=>'30',
							'day_end'=>'0',
							'type'=>'view',
							'get_count'=>'off',
							'call_from'=>'debug',
							'id_user'=>'0',
						)),
					),
				),

			);
			error_log(print_r($arr,true));
			?>
            <div class="hidden-data-month-viewed"><?php echo json_encode($arr); ?></div>

            <div class="dzs-row">
                <div class="dzs-col-md-8">
                    <div class="trackchart">

                    </div>
                </div>
                <div class="dzs-col-md-4">
                    <div class="dzs-row">

                        <div class="dzs-col-md-6">
                            <h6><?php echo __("Likes Today"); ?></h6>
                            <div><span class="the-number"><?php


									$aux = $this->mysql_get_track_activity($trackid, array(
										'get_last'=>'on',
										'interval'=>'24',
										'type'=>'like',
									));

									echo $aux;

									?></span> <span class="the-label"><?php ?></span> </div>
                        </div>
                        <div class="dzs-col-md-6">
                            <h6><?php echo __("Plays Today"); ?></h6>
                            <div><span class="the-number"><?php


									$aux = $this->mysql_get_track_activity($trackid, array(
										'get_last'=>'on',
										'interval'=>'24',
										'type'=>'view',
									));

									echo $aux;

									?></span> <span class="the-label"><?php ?></span> </div>
                        </div>
                    </div>

                    <div class="dzs-row">
                        <div class="dzs-col-md-6">


                            <h6><?php echo __("Likes This Week"); ?></h6>
                            <div><span class="the-number"><?php


									$aux = $this->mysql_get_track_activity($trackid, array(
										'get_last'=>'on',
										'interval'=>'144',
										'type'=>'like',
									));

									echo $aux;

									?></span> <span class="the-label"><?php ?></span> </div>
                        </div>

                        <div class="dzs-col-md-6">
                            <h6><?php echo __("Plays This Week"); ?></h6>
                            <div><span class="the-number"><?php


									$aux = $this->mysql_get_track_activity($trackid, array(
										'get_last'=>'on',
										'interval'=>'144',
										'type'=>'view',
									));

									echo $aux;

									?></span> <span class="the-label"><?php ?></span> </div>
                        </div>
                    </div>
                    <div class="dzs-row">

                        <div class="dzs-col-md-6">
                            <h6><?php echo __("Likes this month"); ?></h6>
                            <div><span class="the-number"><?php


									$aux = $this->mysql_get_track_activity($trackid, array(
										'get_last'=>'on',
										'interval'=>'720',
										'type'=>'like',
									));

									echo $aux;

									?></span> <span class="the-label"><?php ?></span> </div>
                        </div>
                        <div class="dzs-col-md-6">
                            <h6><?php echo __("Plays this month"); ?></h6>
                            <div><span class="the-number"><?php


									$aux = $this->mysql_get_track_activity($trackid, array(
										'get_last'=>'on',
										'interval'=>'720',
										'type'=>'view',
									));

									echo $aux;

									?></span> <span class="the-label"><?php ?></span> </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="dzs-row">

                <div class="dzs-col-md-6">
                    <div class="trackchart-time-watched">

                    </div>
                </div>

                <div class="dzs-col-md-6">
                    <div class="trackchart-month-viewed">

                    </div>
                </div>
            </div>
			<?php

			die();

		}



		if(isset($_POST['action'])){

		    // -- delete waveforms
		    if($_POST['action']=='dzsap_delete_waveforms'){



			    $nonce = $_REQUEST['nonce'];
			    if ( ! wp_verify_nonce( $nonce, 'dzsap_delete_waveforms_nonce' ) ) {
				    // This nonce is not valid.
				    die( 'Security check' );
			    }


			    global $wpdb;

			    $wpdb->query(
				    $wpdb->prepare(
					    "DELETE FROM $wpdb->options
		 WHERE option_name LIKE %s
		",
					    'dzsap_pcm_data%'
				    )
			    );






		    }
		    if($_POST['action']=='dzsap_delete_times'){



			    $nonce = $_REQUEST['nonce'];
			    if ( ! wp_verify_nonce( $nonce, 'dzsap_delete_times_nonce' ) ) {
				    // This nonce is not valid.
				    die( 'Security check' );
			    }


			    global $wpdb;


			    $wpdb->query(
				    $wpdb->prepare(
					    "DELETE FROM $wpdb->options
		 WHERE option_name LIKE %s
		",
					    'dzsap_total_time%'
				    )
			    );






		    }
		    if($_POST['action']=='dzsap_duplicate_dzsap_configs'){

			    if (isset($_POST['slidernr'])) {
				    if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_configs) {
					    $aux = ($this->mainitems_configs[$_POST['slidernr']]);
					    array_push($this->mainitems_configs, $aux);
					    $this->mainitems_configs = array_values($this->mainitems_configs);
					    $this->currSlider = count($this->mainitems_configs) - 1;
					    update_option($this->dbname_mainitems_configs, $this->mainitems_configs);

					    wp_redirect(admin_url('admin.php?page=dzsap_configs&currslider='.$this->currSlider));
					    exit;
				    }
			    }
            }
        }

		if(isset($_POST['action'])){
			if($_POST['action']=='dzsap_duplicate_dzsap_slider'){

				if (isset($_POST['slidernr'])) {
					if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename) {
						$aux = ($this->mainitems[$_POST['slidernr']]);
						array_push($this->mainitems, $aux);
						$this->mainitems = array_values($this->mainitems);
						$this->currSlider = count($this->mainitems) - 1;
						update_option($this->dbname_mainitems, $this->mainitems);

						wp_redirect(admin_url('admin.php?page=dzsap_menu&currslider='.$this->currSlider));
						exit;
					}
				}
			}
		}




















		if(function_exists('vc_add_shortcode_param')) {
//            add_shortcode_param('dzsvcs_toggle_begin', 'vc_dzsvcs_toggle_begin' );
//            add_shortcode_param('dzsvcs_toggle_end', 'vc_dzsvcs_toggle_end' );
			vc_add_shortcode_param('dzs_add_media_att', 'vc_dzs_add_media_att');
		}

		include_once($this->base_path . 'vc/part-vcintegration.php');
	}

	// --- end handle_init END
    // -----------------------




    function handle_init_end(){


        if($this->mainoptions['replace_powerpress_plugin']=='on'){

            add_shortcode('powerpress', array($this, 'powerpress_shortcode_player'));
        }










//        echo "get_option('dzsap_sample_data_installed') - ".get_option('dzsap_sample_data_installed');

	    if( !(get_option('dzsap_sample_data_installed')) ){


		    $tax = $this->taxname_sliders;
		    $reference_term = get_term_by( 'slug', 'gallery-1-copy', $tax );

//		    echo '$reference_term - ';print_rr($reference_term);
		    if($reference_term){

		    }else{

			    $file_cont = file_get_contents('sampledata/dzsap_export_gallery.txt',true);

//			    error_log('trying to import - '.$file_cont);
			    $sw_import = $this->import_slider($file_cont);
		    }


		    update_option('dzsap_sample_data_installed','on');

//	        echo ' $file_cont - '.$file_cont;
//	        echo ' $sw_import - '.$sw_import;
	    }

    }


	function powerpress_shortcode_player(){




        global $powerpress_feed,$post;
        //            print_rr($powerpress_feed);

        // PowerPress settings:
        $GeneralSettings = get_option('powerpress_general');
        //                print_rr($GeneralSettings);


        $feed_slug = 'podcast';


        $EpisodeData = null;
        if(function_exists('powerpress_get_enclosure_data')){

            $EpisodeData = powerpress_get_enclosure_data($post->ID, $feed_slug);
        }

        //            print_rr($EpisodeData);


        if($EpisodeData && isset($EpisodeData['url'])){








            //            echo 'whaaa';
            $this->sliders__player_index++;

            //                $fout = '';


            $src = get_post_meta($post->ID, 'dzsap_woo_product_track', true);



            $this->front_scripts();

            $margs = $this->powerpress_generate_margs();


            $args = array(

            );

            $margs['called_from']='pooowerpress';
            $aux = $this->shortcode_player($margs);



            return $aux;


        }

    }


	function dzsap_sliders_save_taxonomy_custom_meta( $term_id ) {


//		error_log('trying to save term meta '.$term_id);
//		error_log(print_rr($_POST,array('echo'=>false)));
		if ( isset( $_POST['term_meta'] ) ) {
			$t_id = $term_id;
			$term_meta = get_option( "taxonomy_$t_id" );
			$cat_keys = array_keys( $_POST['term_meta'] );
			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['term_meta'][$key] ) ) {
					$term_meta[$key] = $_POST['term_meta'][$key];
				}
			}
			// Save the option array.
			update_option( "taxonomy_$t_id", $term_meta );
		}
	}


    function db_read_mainitems(){




//        echo '$this->db_has_read_mainitems - '.$this->db_has_read_mainitems;
        if($this->db_has_read_mainitems==false){

            $currDb = '';
            if (isset($_GET['dbname'])) {
                $this->currDb = $_GET['dbname'];
                $currDb = $_GET['dbname'];
            }


            if($this->mainoptions['playlists_mode']=='normal'){


                $tax = $this->taxname_sliders;

//                echo ' tax - '.$tax;

	            $terms = get_terms( $tax, array(
		            'hide_empty' => false,
	            ) );




	            $this->mainitems = array();
	            foreach ($terms as $tm){
	                $aux = array(
	                        'label'=>$tm->name,
	                        'value'=>$tm->slug,
                    );

	                array_push($this->mainitems, $aux);
                }

//	            print_rr($terms);

            }else{
	            if (isset($_GET['currslider'])) {
		            $this->currSlider = $_GET['currslider'];
	            } else {
		            $this->currSlider = 0;
	            }




	            $this->dbs = get_option($this->dbname_dbs);
	            //$this->dbs = '';
	            if ($this->dbs == '') {
		            $this->dbs = array('main');
		            update_option($this->dbname_dbs, $this->dbs);
	            }
	            if (is_array($this->dbs) && !in_array($currDb, $this->dbs) && $currDb != 'main' && $currDb != '') {
		            array_push($this->dbs, $currDb);
		            update_option($this->dbname_dbs, $this->dbs);
	            }
	            //echo 'ceva'; print_r($this->dbs);
	            if ($currDb != 'main' && $currDb != '') {
		            $this->dbname_mainitems.='-' . $currDb;
	            }

	            $this->mainitems = get_option($this->dbname_mainitems);


	            if (is_array($this->mainitems)==false) {
		            $aux = 'a:2:{i:0;a:3:{s:8:"settings";a:17:{s:2:"id";s:20:"playlist_wave_simple";s:5:"width";s:0:"";s:6:"height";s:0:"";s:11:"galleryskin";s:9:"skin-wave";s:12:"menuposition";s:6:"bottom";s:17:"design_menu_state";s:4:"open";s:36:"design_menu_show_player_state_button";s:3:"off";s:18:"design_menu_height";s:7:"default";s:13:"cuefirstmedia";s:2:"on";s:8:"autoplay";s:2:"on";s:12:"autoplaynext";s:2:"on";s:25:"disable_player_navigation";s:3:"off";s:7:"bgcolor";s:11:"transparent";s:8:"vpconfig";s:20:"skinwavewithcomments";s:12:"enable_views";s:3:"off";s:12:"enable_likes";s:3:"off";s:12:"enable_rates";s:3:"off";}i:0;a:17:{s:4:"type";s:5:"audio";s:6:"source";s:78:"http://www.stephaniequinn.com/Music/Allegro%20from%20Duet%20in%20C%20Major.mp3";s:19:"soundcloud_track_id";s:0:"";s:23:"soundcloud_secret_token";s:0:"";s:9:"sourceogg";s:0:"";s:15:"linktomediafile";s:0:"";s:5:"thumb";s:101:"https://lh5.googleusercontent.com/-RhXJ4O5JiEQ/UoKDBeGx5-I/AAAAAAAAAEU/Dkace1QwAKU/s80/smalllogo2.jpg";s:8:"playfrom";s:0:"";s:7:"bgimage";s:0:"";s:21:"play_in_footer_player";s:3:"off";s:10:"extra_html";s:0:"";s:15:"extra_html_left";s:0:"";s:27:"extra_html_in_controls_left";s:0:"";s:28:"extra_html_in_controls_right";s:0:"";s:15:"menu_artistname";s:4:"Tony";s:13:"menu_songname";s:4:"Tail";s:14:"menu_extrahtml";s:0:"";}i:1;a:17:{s:4:"type";s:5:"audio";s:6:"source";s:45:"http://www.stephaniequinn.com/Music/Canon.mp3";s:19:"soundcloud_track_id";s:0:"";s:23:"soundcloud_secret_token";s:0:"";s:9:"sourceogg";s:0:"";s:15:"linktomediafile";s:0:"";s:5:"thumb";s:101:"https://lh5.googleusercontent.com/-RhXJ4O5JiEQ/UoKDBeGx5-I/AAAAAAAAAEU/Dkace1QwAKU/s80/smalllogo2.jpg";s:8:"playfrom";s:0:"";s:7:"bgimage";s:0:"";s:21:"play_in_footer_player";s:3:"off";s:10:"extra_html";s:0:"";s:15:"extra_html_left";s:0:"";s:27:"extra_html_in_controls_left";s:0:"";s:28:"extra_html_in_controls_right";s:0:"";s:15:"menu_artistname";s:4:"Tony";s:13:"menu_songname";s:5:"Cairn";s:14:"menu_extrahtml";s:0:"";}}i:1;a:4:{s:8:"settings";a:17:{s:2:"id";s:21:"gallery_with_comments";s:5:"width";s:0:"";s:6:"height";s:0:"";s:11:"galleryskin";s:9:"skin-aura";s:12:"menuposition";s:6:"bottom";s:17:"design_menu_state";s:4:"open";s:36:"design_menu_show_player_state_button";s:3:"off";s:18:"design_menu_height";s:7:"default";s:13:"cuefirstmedia";s:2:"on";s:8:"autoplay";s:2:"on";s:12:"autoplaynext";s:2:"on";s:25:"disable_player_navigation";s:3:"off";s:7:"bgcolor";s:11:"transparent";s:8:"vpconfig";s:20:"skinwavewithcomments";s:12:"enable_views";s:2:"on";s:12:"enable_likes";s:2:"on";s:12:"enable_rates";s:3:"off";}i:0;a:17:{s:4:"type";s:5:"audio";s:6:"source";s:78:"http://www.stephaniequinn.com/Music/Allegro%20from%20Duet%20in%20C%20Major.mp3";s:19:"soundcloud_track_id";s:0:"";s:23:"soundcloud_secret_token";s:0:"";s:9:"sourceogg";s:0:"";s:15:"linktomediafile";s:1:"1";s:5:"thumb";s:74:"https://placeholdit.imgix.net/~text?txtsize=22&txt=placeholder&w=300&h=300";s:8:"playfrom";s:0:"";s:7:"bgimage";s:0:"";s:21:"play_in_footer_player";s:3:"off";s:10:"extra_html";s:0:"";s:15:"extra_html_left";s:0:"";s:27:"extra_html_in_controls_left";s:0:"";s:28:"extra_html_in_controls_right";s:0:"";s:15:"menu_artistname";s:8:"Artist 1";s:13:"menu_songname";s:7:"Track 1";s:14:"menu_extrahtml";s:0:"";}i:1;a:17:{s:4:"type";s:5:"audio";s:6:"source";s:45:"http://www.stephaniequinn.com/Music/Canon.mp3";s:19:"soundcloud_track_id";s:0:"";s:23:"soundcloud_secret_token";s:0:"";s:9:"sourceogg";s:0:"";s:15:"linktomediafile";s:1:"2";s:5:"thumb";s:74:"https://placeholdit.imgix.net/~text?txtsize=33&txt=placeholder&w=300&h=300";s:8:"playfrom";s:0:"";s:7:"bgimage";s:0:"";s:21:"play_in_footer_player";s:3:"off";s:10:"extra_html";s:0:"";s:15:"extra_html_left";s:0:"";s:27:"extra_html_in_controls_left";s:0:"";s:28:"extra_html_in_controls_right";s:0:"";s:15:"menu_artistname";s:8:"Artist 1";s:13:"menu_songname";s:7:"Track 1";s:14:"menu_extrahtml";s:0:"";}i:2;a:17:{s:4:"type";s:5:"audio";s:6:"source";s:93:"http://www.stephaniequinn.com/Music/Handel%20-%20Entrance%20of%20the%20Queen%20of%20Sheba.mp3";s:19:"soundcloud_track_id";s:0:"";s:23:"soundcloud_secret_token";s:0:"";s:9:"sourceogg";s:0:"";s:15:"linktomediafile";s:4:"1000";s:5:"thumb";s:0:"";s:8:"playfrom";s:0:"";s:7:"bgimage";s:0:"";s:21:"play_in_footer_player";s:3:"off";s:10:"extra_html";s:0:"";s:15:"extra_html_left";s:0:"";s:27:"extra_html_in_controls_left";s:0:"";s:28:"extra_html_in_controls_right";s:0:"";s:15:"menu_artistname";s:8:"Artist 3";s:13:"menu_songname";s:7:"Track 3";s:14:"menu_extrahtml";s:0:"";}}}';
		            $this->mainitems = unserialize($aux);
		            //$this->mainitems = array();
		            update_option($this->dbname_mainitems, $this->mainitems);
            }


            }

            $this->db_has_read_mainitems = true;
        }

    }

    function ajax_parse_content_to_shortcode(){


        error_log('$_POST[\'postdata\'] - '.$_POST['postdata']);

        echo do_shortcode(stripslashes($_POST['postdata']));

        die();
    }
    function db_read_default_opts(){

        global $pagenow;

//        echo $pagenow;




        if (isset($_GET['currslider'])) {
            $this->currSlider = $_GET['currslider'];
        } else {
            $this->currSlider = 0;
        }








        if(isset($_GET) && isset($_GET['dzsap_debug']) && $_GET['dzsap_debug']=='on'){
            $this->debug = true;
        }




        if(defined('dzsap_db_mainitems_configs')){

//            echo "YES".dzsap_db_mainitems_configs;
            $this->mainitems_configs = unserialize(dzsap_db_mainitems_configs);

//            print_rr($this->mainitems_configs);
        }else{

//            print_rr($this->dbname_mainitems_configs);
//            print_rr(get_option($this->dbname_mainitems_configs));
            $this->mainitems_configs = get_option($this->dbname_mainitems_configs);

        }

	    include("class_parts/options-item-meta.php");
//        echo '$this->mainitems_configs -> '; print_rr($this->mainitems_configs);


        //cho 'ceva'.is_array($this->mainitems_configs);
        if ($this->mainitems_configs == '' || (is_array($this->mainitems_configs) && count($this->mainitems_configs) == 0)) {
//            echo 'ceva';
            $this->mainitems_configs = array();
            $aux = 'a:3:{i:0;a:1:{s:8:"settings";a:7:{s:2:"id";s:20:"skinwavewithcomments";s:7:"skin_ap";s:9:"skin-wave";s:20:"settings_backup_type";s:4:"full";s:21:"skinwave_dynamicwaves";s:3:"off";s:23:"skinwave_enablespectrum";s:3:"off";s:22:"skinwave_enablereflect";s:2:"on";s:24:"skinwave_comments_enable";s:2:"on";}}i:1;a:1:{s:8:"settings";a:13:{s:2:"id";s:11:"footer-wave";s:7:"skin_ap";s:9:"skin-wave";s:20:"settings_backup_type";s:4:"full";s:14:"disable_volume";s:7:"default";s:19:"enable_embed_button";s:3:"off";s:8:"playfrom";s:3:"off";s:14:"colorhighlight";s:6:"111111";s:21:"skinwave_dynamicwaves";s:3:"off";s:23:"skinwave_enablespectrum";s:3:"off";s:22:"skinwave_enablereflect";s:2:"on";s:24:"skinwave_comments_enable";s:3:"off";s:13:"skinwave_mode";s:5:"small";s:23:"enable_alternate_layout";s:3:"off";}}i:2;a:1:{s:8:"settings";a:13:{s:2:"id";s:17:"example-skin-aria";s:7:"skin_ap";s:9:"skin-aria";s:20:"settings_backup_type";s:6:"simple";s:14:"disable_volume";s:7:"default";s:19:"enable_embed_button";s:3:"off";s:8:"playfrom";s:3:"off";s:14:"colorhighlight";s:6:"111111";s:21:"skinwave_dynamicwaves";s:3:"off";s:23:"skinwave_enablespectrum";s:3:"off";s:22:"skinwave_enablereflect";s:2:"on";s:24:"skinwave_comments_enable";s:3:"off";s:13:"skinwave_mode";s:6:"normal";s:23:"enable_alternate_layout";s:3:"off";}}}';
            $this->mainitems_configs = unserialize($aux);
//            print_r($this->mainitems_configs);
            //$this->mainitems = array();


            // TODO: saving
            update_option($this->dbname_mainitems_configs, $this->mainitems_configs);
        }
        $this->vpconfigsstr = '';
        //print_r($this->mainitems_configs);
        $i23 = 0;


        foreach ($this->mainitems_configs as $vpconfig) {
            //print_r($vpconfig);
            $this->vpconfigsstr .='<option data-sliderlink="'.$i23.'" value="' . $vpconfig['settings']['id'] . '">' . $vpconfig['settings']['id'] . '</option>';

            $i23++;
        }



        if(defined('dzsap_db_sample_data')){
            $this->sample_data = unserialize(dzsap_db_sample_data);
        }else{

            $this->sample_data = get_option($this->dbname_sample_data);
        }




        $defaultOpts = array(
            'usewordpressuploader' => 'on',
            'embed_prettyphoto' => 'on',
            'embed_masonry' => 'on',
            'is_safebinding' => 'on',
            'tinymce_disable_preview_shortcodes' => 'off',
            'use_api_caching' => 'on',
            'debug_mode' => 'off',
            'try_to_cache_total_time' => 'on',
            'dzsap_purchase_code_binded' => 'off',
            'dzsap_purchase_code' => '',
            'extra_css' => '',
            'track_downloads' => 'off',
            'analytics_table_created' => 'off',
            'extra_js' => '',
            'js_init_timeout' => '',
            'download_link_links_directly_to_file' => 'off',
            'force_autoplay_when_coming_from_share_link' => 'off',
            'replace_playlist_shortcode' => 'off',
            'replace_audio_shortcode' => 'off',
            'replace_audio_shortcode_extra_args' => '',
            'replace_audio_shortcode_play_in_footer' => 'off',
            'fontawesome_load_local' => 'off',
            'replace_powerpress_plugin' => 'off',
            'powerpress_read_category_xml' => 'off',
            'pcm_data_try_to_generate' => 'on',
            'enable_global_footer_player' => 'off',
            'skinwave_wave_mode' => 'canvas',
            'skinwave_wave_mode_canvas_reflection_size' => '0.25',
            'skinwave_wave_mode_canvas_waves_number' => '3',
            'skinwave_wave_mode_canvas_waves_padding' => '1',
            'skinwave_wave_mode_canvas_normalize' => 'on',
            'allow_download_only_for_registered_users' => 'off',
            'allow_download_only_for_registered_users_capability' => 'read',
            'admin_close_otheritems' => 'on',
            'force_file_get_contents' => 'off',
            'pcm_notice' => 'off',
            'notice_no_media' => 'on',
            'wpdb_enable' => 'off',
            'color_waveformbg' => '111111', //==no hash
            'color_waveformprog' => 'ef6b13',
            'settings_wavestyle' => 'reflect',
            'soundcloud_api_key' => '',
            'wc_single_product_player' => 'off',
            'wc_single_product_player_shortcode' => '',
            'wc_loop_product_player' => 'off',
            'wc_product_play_in_footer' => 'off',
            'try_to_hide_url' => 'off',
            'sample_time_pseudo' => '',
            'wc_single_player_position' => 'top',
            'wc_loop_player_position' => 'top',
            'play_remember_time' => '120',
            'activate_comments_widget' => 'off',
            'settings_trigger_resize' => 'off',
            'wavesurfer_pcm_length' => '200',
            'mobile_disable_footer_player' => 'off',
            'enable_raw_shortcode' => 'off',
            'developer_check_for_bots_and_dont_reveal_source' => 'off',
            'enable_auto_backup' => 'on',
            'aws_enable_support' => 'off',
            'aws_key' => '',
            'aws_key_secret' => '',
            'aws_region' => '',
            'aws_bucket' => '',
            'dzsap_meta_post_types' => array('dzsap_items','products'),
            'www_handle' => 'default',
            'dzsap_sliders_rewrite' => 'audio-sliders',
            'str_likes_part1' => '<span class="btn-zoomsounds btn-like"><span class="the-icon">{{heart_svg}}</span><span class="the-label hide-on-active">Like</span><span class="the-label show-on-active">Liked</span></span>',
            'str_views' => '<div class="counter-hits"><i class="fa fa-play"></i><span class="the-number">{{get_plays}}</span></div>',
            'str_downloads_counter' => '<div class="counter-hits"><i class="fa fa-cloud-download"></i><span class="the-number">{{get_downloads}}</span></div>',
            'str_likes_part2' => '<div class="counter-likes"><i class="fa fa-heart"></i><span class="the-number">{{get_likes}}</span></div>',
            'str_rates' => '<div class="counter-rates"><span class="the-number">{{get_rates}}</span> rates</div>',
            'waveformgenerator_multiplier' => '1',
            'use_external_uploaddir' => 'on',
            'always_embed' => 'off',
            'single_index_seo_disable' => 'off',
            'loop_playlist' => 'on',
            'try_to_get_id3_thumb_in_frontend' => 'off',
            'failsafe_repair_media_element' => 'off',
            'construct_player_list_for_sync' => 'off',
            'i18n_buy' => '',
            'i18n_play' => '',
            'i18n_title' => '',
            'i18n_free_download' => __("Free Download"),
            'i18n_register_to_download' => '',
            'register_to_download_opens_in_new_link' => 'off',
            'dzsap_categories_rewrite' => __("Audio Category"),
            'dzsap_tags_rewrite' => __("Song tags"),
            'analytics_enable' => 'off',
            'analytics_enable_location' => 'off',
            'analytics_enable_user_track' => 'off',


            'keyboard_show_tooltips'=>'off',
            'keyboard_play_trigger_step_back'=>'off',
            'keyboard_step_back_amount'=>'5',
            'keyboard_step_back'=>'37',
            'keyboard_step_forward'=>'39',
            'keyboard_pause_play'=>'32',
            'keyboard_sync_players_goto_prev'=>'',
            'keyboard_sync_players_goto_next'=>'',

            'analytics_galleries' => '', // -- deprecated

            'dzsaap_enable_youtube' => 'off',
            'dzsaap_enable_unregistered_submit' => 'off',
            'dzsaap_disable_self_hosted_audio_upload' => 'off',
            'dzsaap_default_portal_upload_type' => 'audio',
            'dzsaap_redirect_url_after_submission' => '{{newtrack}}',
            'dzsaap_default_thumbnail_image' => 'http://oldengineering.co.uk/wp-content/uploads/2017/06/gravatar-60-grey.jpg',
        );



        if(defined('dzsap_db_mainoptions')){
            $this->mainoptions = unserialize(dzsap_db_mainoptions);
        }else{

            $this->mainoptions = get_option($this->dbname_options);
        }



        // -- default opts / inject into db
        if ($this->mainoptions == '') {
            // -- new install

            $defaultOpts['playlists_mode']='normal';


            $this->mainoptions = $defaultOpts;
            update_option($this->dbname_options, $this->mainoptions);
        }else{

            // -- previous install

	        $defaultOpts['playlists_mode']='legacy';
        }

        $this->mainoptions = array_merge($defaultOpts, $this->mainoptions);
        //print_r($this->mainoptions);
        //===translation stuff
        load_plugin_textdomain('dzsap', false, basename(dirname(__FILE__)) . '/languages');


        if($this->mainoptions['i18n_buy']==''){
            $this->mainoptions['i18n_buy']= __("Buy",'dzsap');
        }
        if($this->mainoptions['i18n_play']==''){
            $this->mainoptions['i18n_play']= __("Play",'dzsap');
        }
        if($this->mainoptions['i18n_title']==''){
            $this->mainoptions['i18n_title']= __("Title",'dzsap');
        }
        if($this->mainoptions['i18n_register_to_download']==''){
            $this->mainoptions['i18n_register_to_download']= __("Register to download",'dzsap');
        }


        if(isset($_GET['page']) && $_GET['page']=='dzsap_menu'){
            if($this->mainoptions['playlists_mode']=='normal'){

                wp_redirect(admin_url('edit-tags.php?taxonomy=dzsap_sliders&post_type=dzsap_items'));
                exit;
            }
        }


        if($pagenow=='admin.php' || $this->mainoptions['always_embed'] == 'on'){


            if($this->mainoptions['playlists_mode']=='legacy'){

	            $this->db_read_mainitems();
            }
        }


    }


	function analytics_get() {
		$this->analytics_views = get_option('dzsap_analytics_views');
		$this->analytics_minutes = get_option('dzsap_analytics_minutes');


		if ($this->mainoptions['analytics_enable_user_track'] == 'on') {
			$this->analytics_users = get_option('dzsap_analytics_users');


			if ($this->analytics_users == false) {
				$this->analytics_users = array();
			}
		}


	}

    function analytics_submit_into_table($pargs){



	    $margs = array(
		    'call_from'=>'default',
		    'type'=>'view',
	    );






	    $margs = array_merge($margs, $pargs);



	    $date = date('Y-m-d H:i:s');

//                $date = date("Y-m-d", time() - 60 * 60 * 24);

        $id = '';

        if(isset($_POST['video_analytics_id']) && $_POST['video_analytics_id']){

	        $id = $_POST['video_analytics_id'];
        }
        if(isset($_POST['playerid']) && $_POST['playerid']){

	        $id = $_POST['playerid'];
        }

	    $id = str_replace('ap', '', $id);

	    $country = '0';

	    if ($this->mainoptions['analytics_enable_location'] == 'on') {

//                    print_r($_SERVER);

		    if ($_SERVER['REMOTE_ADDR']) {

//                        $aux = wp_file


			    $request = wp_remote_get('https://ipinfo.io/' . $_SERVER['REMOTE_ADDR'] . '/json');
			    $response = wp_remote_retrieve_body($request);
			    $aux_arr = json_decode($response);
//                        print_r($aux_arr);

			    if ($aux_arr) {
				    $country = $aux_arr->country;
			    }
		    }
	    }


	    $userid = '';
	    $userid = get_current_user_id();
	    if ($this->mainoptions['analytics_enable_user_track'] == 'on') {

		    if ( isset($_POST['dzsap_curr_user']) && $_POST['dzsap_curr_user'] ) {
			    $userid = $_POST['dzsap_curr_user'];
		    }
	    }



	    $playerid = $id;

//		        print_rr($_COOKIE);

	    if(isset($_COOKIE["dzsap_".$margs['type']."submitted-" . $playerid]) && $_COOKIE["dzsap_".$margs['type']."submitted-" . $playerid]=='1'){

	    }else{




		    if($margs['type']=='view'){

		    $nr_views = get_post_meta($id, '_dzsap_views',true);

		    $nr_views = intval($nr_views);



			    update_post_meta($id, '_dzsap_views',++$nr_views);
            }



		    if($margs['type']=='like'){

			    $nr_views = get_post_meta($id, '_dzsap_likes',true);

			    $nr_views = intval($nr_views);



			    update_post_meta($id, '_dzsap_likes',++$nr_views);
		    }







//                $date = date("Y-m-d", time() - 60 * 60 * 24);






		    $currip = $this->misc_get_ip();


		    if($margs['type']=='view'){
		    }

		    setcookie("dzsap_".$margs['type']."submitted-" . $playerid, 1, time() + 36000, COOKIEPATH);



		    global $wpdb;





		    $table_name = $wpdb->prefix.'dzsap_activity';





		    if($this->mainoptions['analytics_enable_user_track']=='on'){

			    // -- date precise
			    $date = date('Y-m-d H:i:s');
			    $wpdb->insert(
				    $table_name,
				    array(
					    'ip' => $currip,
					    'country' => $country,
					    'type' => $margs['type'],
					    'val' => 1,
					    'id_user' => $userid,
					    'id_video' => $playerid,
					    'date' => $date,
				    )
			    );
		    }else{


			    // -- date more generic for select matches
			    $date = date('Y-m-d');






			    // -- submit to total plays for today

			    $query = 'SELECT * FROM '.$table_name.' WHERE id_user = \'0\' AND date=\''.$date.'\'  AND type=\''.$margs['type'].'\' AND id_video=\''.($playerid).'\'';
			    if($this->mainoptions['analytics_enable_location']=='on' && $country){
				    $query.=' AND country=\''.$country.'\'';
			    }
			    $results = $wpdb->get_results($query , OBJECT );


			    if(is_array($results) && count($results)>0){


				    $val = intval($results[0]->val);
				    $newval = $val+1;

				    $wpdb->update(
					    $table_name,
					    array(
						    'val' => $val+1,
					    ),
					    array( 'ID' => $results[0]->id ),
					    array(
						    '%s',	// value1
					    ),
					    array( '%d' )
				    );


			    }else{

				    $wpdb->insert(
					    $table_name,
					    array(
						    'ip' => 0,
						    'type' => $margs['type'],
						    'id_user' => 0,
						    'id_video' => $playerid,
						    'date' => $date,
						    'val' => 1,
						    'country' => $country,
					    )
				    );
			    }

		    }




		    echo $nr_views;


















		    $query = 'SELECT * FROM '.$table_name.' WHERE id_user = \'0\' AND date=\''.$date.'\'  AND type=\''.'view'.'\' AND id_video=\''.(0).'\'';
		    if($this->mainoptions['analytics_enable_location']=='on' && $country){
			    $query.=' AND country=\''.$country.'\'';
		    }
		    $results = $wpdb->get_results($query , OBJECT );


		    if(is_array($results) && count($results)>0){


			    $val = intval($results[0]->val);
			    $newval = $val+1;

			    $wpdb->update(
				    $table_name,
				    array(
					    'val' => $val+1,
				    ),
				    array( 'ID' => $results[0]->id ),
				    array(
					    '%s',	// value1
				    ),
				    array( '%d' )
			    );


		    }else{

			    $wpdb->insert(
				    $table_name,
				    array(
					    'ip' => 0,
					    'type' => 'view',
					    'id_user' => 0,
					    'id_video' => 0,
					    'date' => $date,
					    'val' => 1,
					    'country' => $country,
				    )
			    );
		    }



		    die();

//		            echo 'success';
	    }



    }
    function shortcode_player_comment_field(){

        $fout = '';

        global $current_user;


        if($current_user->ID){
            $fout.= '<div class="zoomsounds-comment-wrapper">
                <div class="zoomsounds-comment-wrapper--avatar divimage" style="background-image: url(http://www.gravatar.com/avatar/?d=identicon);"></div>
                <div class="zoomsounds-comment-wrapper--input-wrap">
                    <input type="text" class="comment_text" placeholder="'.__("Write a comment").'"/>
                    <input type="text" class="comment_email" placeholder="'.__("Your email").'"/>
                    <!--<input type="text" class="comment_user" placeholder="'.__("Your display name").'"/>-->
                </div>

                <div class="zoomsounds-comment-wrapper--buttons">
                    <span class="dzs-button-dzsap comments-btn-cancel">'.__("Cancel").'</span>
                    <span class="dzs-button-dzsap comments-btn-submit">'.__("Submit").'</span>
                </div>
            </div>';
        }else{
            $fout.=__("You need to be logged in to comment");
        }


        return $fout;


    }



    function handle_woocommerce_after_main_content(){
        echo 'woocommerce_after_main_content';
    }
    function handle_woocommerce_after_shop_loop_item(){
        echo 'woocommerce_after_shop_loop_item';
    }

    function handle_woocommerce_shop_loop_item_title(){
        echo 'woocommerce_shop_loop_item_title';
    }
    function handle_woocommerce_single_product_summary(){
//        echo 'woocommerce_single_product_summary';



        global $post;

//        echo 'whaaa';


        if($this->has_generated_product_player){
            return false;
        }

        $this->wc_called_loop_from = 'single';

        $id = 0;

        if($post && $post->ID){
            $id = $post->ID;
        }

        $product = wc_get_product($id);



//        print_rr($post);
//        print_rr($product);
        if($product->is_type('grouped')){
            $children = $product->get_children();

//            print_rr($children);


            $ids = '';



            foreach ($children as $poid){
                if(get_post_meta($poid,'dzsap_woo_product_track',true)){

//                    echo 'whaaa';
                    if($ids){
                        $ids.=',';
                    }
                    $ids.=$poid;
                }
            }



//            echo 'ids - '.$ids;



            $fout = '';
            $iout = ''; //items parse






            echo '<div class="wc-dzsap-wrapper for-dzsag ';

            if($this->mainoptions['wc_single_player_position']=='overlay') {
                echo 'go-after-thumboverlay ';
            }

            echo '">';

            if($ids){

                echo $this->shortcode_showcase(array(

                    'feed_from'=>'audio_items',
                    'ids'=>$ids,
                ));



                $this->has_generated_product_player = true;
            }

            echo '</div>';

        }else{
            $args = array(

                'call_from'=>'handle_woocommerce_single_product_summary',
                'extra_classes'=>' from-wc_generate_player from-wc_single',
            );
            $this->wc_generate_player($id, $args);

        }





    }


    function wc_generate_player($id,$pargs=array()){








        $margs = array(
            'call_from'=>'default',
            'extra_classes'=>' from-wc_generate_player',
        );






        $margs = array_merge($margs, $pargs);



        if($this->has_generated_product_player){
            return false;
        }

        $this->has_generated_product_player= false;


        $post = get_post($id);

        $player_position = $this->mainoptions['wc_loop_player_position'];


        if(strpos($margs['extra_classes'],'from-wc_single')!==false){
            $player_position = $this->mainoptions['wc_single_player_position'];

//            echo '$this->mainoptions[\'wc_single_player_position\'] - '.$this->mainoptions['wc_single_player_position'];

        }


        if($id && get_post_meta($post->ID,'dzsap_woo_product_track',true)) {

//            echo 'whaaa';
            $this->sliders__player_index++;

            $fout = '';


            $src = get_post_meta($post->ID, 'dzsap_woo_product_track', true);


//            print_rr($post);


            $this->front_scripts();

            $args = array(
                    'mp3' => $src,
                   'config' => $this->mainoptions['wc_single_product_player'],
                );

//        $args = array_merge($args, $atts);

//        print_r($args);
            $args['source'] = $args['mp3'];
            $args['product_id'] = $id;
            $args['called_from'] = 'single_product_summary';
            $args['config'] = $this->mainoptions['wc_single_product_player'];
            $args['extra_classes'] = $margs['extra_classes'];


            if($this->wc_called_loop_from=='loop'){

                $args['config'] = $this->mainoptions['wc_loop_product_player'];
            }

            $playerid = '';
            if($this->mainoptions['wc_product_play_in_footer']=='on'){
                $args['faketarget']='.dzsap_footer';
            }



//            print_rr($margs);
            if($player_position=='overlay'){

                $args['extra_classes']=' prevent-bubble';

//                echo 'shift extra_classes';
            }

            if(strpos($args['source'],'https://soundcloud.com')!==false){
                $args['type']='soundcloud';
            }


	        $args['songname']=$post->post_title;
	        $args['menu_songname']=$post->post_title;

	        // Get user object
            $recent_author = get_user_by( 'ID', $post->post_author );
            // Get user display name
	        $args['artistname'] = $recent_author->display_name;
	        $args['menu_artistname'] = $recent_author->display_name;

            echo '<div class="wc-dzsap-wrapper  ';

//            print_rr($margs);
//            echo '$player_position - '.$player_position;
            if($player_position=='overlay') {
                echo 'go-to-thumboverlay center-ap-inside ';
            }

            echo '">';
//            echo 'whaa';


            $args['thumb_for_parent']=$this->get_thumbnail($id);


$it = $post;
if(get_post_meta($it->ID,'dzsap_woo_sample_time_start',true)){
    $args['sample_time_start']=get_post_meta($it->ID,'dzsap_woo_sample_time_start',true);
}
if(get_post_meta($it->ID,'dzsap_woo_sample_time_end',true)){
    $args['sample_time_end']=get_post_meta($it->ID,'dzsap_woo_sample_time_end',true);
}
if(get_post_meta($it->ID,'dzsap_woo_sample_time_total',true)){
    $args['sample_time_total']=get_post_meta($it->ID,'dzsap_woo_sample_time_total',true);
}
//            'sample_time_start' => get_post_meta($it->ID,'dzsap_woo_sample_time_start',true),
//                    'sample_time_end' => get_post_meta($it->ID,'dzsap_woo_sample_time_end',true),
//                    'sample_time_total' => get_post_meta($it->ID,'dzsap_woo_sample_time_total',true),

            $args['autoplay'] = 'off';


if($margs['call_from']=='handle_woocommerce_single_product_summary' && $this->mainoptions['wc_single_product_player_shortcode']){


    $aux = $this->mainoptions['wc_single_product_player_shortcode'];

    $aux = $this->sanitize_from_shortcode_pattern($aux,$post);

    echo do_shortcode($aux);
}else{

	echo $this->shortcode_player($args, '');
}

            echo '</div>';

//        print_r($its); print_r($margs); echo 'alceva'.$fout;
        }

    }


    function sanitize_from_shortcode_pattern($aux,$argpo){


	    $a = array();
	    $b = array();
	    $src = $this->get_track_source($argpo->ID,$a,$b);


	    $type = 'audio';

//            print_r($margs);
	    if($argpo->post_type=='dzsap_items'){
		    $type = get_post_meta($argpo->ID,'dzsap_meta_type',true);
	    }

	    $aux = str_replace('{{source}}',$src,$aux);
	    $aux = str_replace('{{postid}}',$argpo->ID,$aux);
	    $aux = str_replace('{{thumb}}',$this->get_thumbnail($argpo->ID),$aux);
	    $aux = str_replace('{{type}}',$type,$aux);

	    return $aux;
    }


    function handle_woocommerce_before_shop_loop_item(){
//        echo 'woocommerce_single_product_summary';


//        echo 'whaa';

        global $post;




        $this->wc_called_loop_from = 'loop';
        if($post && $post->ID && get_post_meta($post->ID,'dzsap_woo_product_track',true)){


            $args = array(

                'extra_classes'=>' from-wc_generate_player from-wc_loop',
            );
            $this->wc_generate_player($post->ID, $args);

        }
//        print_r($its); print_r($margs); echo 'alceva'.$fout;



    }
    function handle_woocommerce_before_shop_loop(){
        echo 'woocommerce_before_shop_loop';
    }


    function handle_woocommerce_add_to_cart(){
        echo 'woocommerce_add_to_cart';
    }
    function handle_woocommerce_after_cart(){
        echo 'woocommerce_after_cart';
    }

    function filter_wc_add_to_cart_message($fout){
        return  'wc_add_to_cart_message'.$fout.'wc_add_to_cart_message_end';
    }


	function generate_pcm($che, $pargs = array()){



		$margs = array(
			'generate_only_pcm' => false, // -- generate only the pcm not the markup
		);

		if (is_array($pargs) == false) {
			$pargs = array();
		}

		$margs = array_merge($margs, $pargs);


	    $cheid = '';








		if(isset($che->post_title)){
			$che = (array) $che;

			$args = array();
			$che['source'] = $this->get_track_source($che['ID'], $che['ID'],$args);
			$che['playerid'] = $che['id'];
		}



		$fout = '';
		$lab = '';

		if(isset($che['playerid']) && $che['playerid']){
			$lab = $che['playerid'];
		}


		$pcm = '';

//        echo 'generate_pcm'; print_rr($che);


		$lab  = 'dzsap_pcm_data_'.$this->clean($lab);




		$pcm = get_option($lab);

//                echo 'pcm - '.$pcm. ' - source ( dzsap_pcm_data_'.$this->clean($che['source']).' ) |||'."\n\n";
//                echo ' source ( dzsap_pcm_data_'.$this->clean($che['source']).' )';

		if($pcm=='' || $pcm=='[]' ||  strpos($pcm,',')===false ||  strpos($pcm,'null')===false){

			$lab = $che['source'];
			$lab  = 'dzsap_pcm_data_'.$this->clean($lab);




			$pcm = get_option($lab);

		}
		if($pcm=='' || $pcm=='[]' ||  strpos($pcm,',')===false ||  strpos($pcm,'null')===false){

			if(isset($che['linktomediafile'])){
				if($che['linktomediafile']){
					$lab  = 'dzsap_pcm_data_'.$che['linktomediafile'];
				}
			}
			$pcm = get_option($lab);

//            if( ( $pcm == '' || $pcm== '[]') && isset($che['playerid']) && $che['playerid']){
//                $lab  = 'dzsap_pcm_data_'.$che['playerid'];
//                $pcm = get_option($lab);
//            }

//                    echo 'lab - '.$lab;
//                    $lab = 'dzsap_pcm_data_735';

//                    echo 'lab - '.$lab;

//                    echo 'pcm - '.$pcm;

		}

//                print_r($che);
//		echo 'lab - '.$lab.' ||| ';
//		echo '$pcm - '.$pcm.' ||| ';


		if($pcm && $pcm!='[]' && strpos($pcm,',')!==false && strpos($pcm,'null')!==false){
			$fout.= ' data-pcm=\''.stripslashes($pcm).'\'';
		}

		if($margs['generate_only_pcm']){
		    $fout = stripslashes($pcm);
        }

		return $fout;
	}

    function ajax_add_to_wishlist(){


	    $arr_wishlist = $this->get_wishlist();



        if($_POST['wishlist_action']=='add'){
//	        echo 'addd';
	        array_push($arr_wishlist,$_POST['playerid']);

//	        echo '$arr_wishlist 1 - '; print_rr($arr_wishlist);
        }else{

	        foreach ($arr_wishlist  as $lab=>$val){
	            if($val==$_POST['playerid']){
	                unset($arr_wishlist[$lab]);
                }
            }
        }

//        echo '$_POST - '; print_rr($_POST);
//        echo 'playerid - '; print_rr($_POST['playerid']);
//        echo '$arr_wishlist - '; print_rr($arr_wishlist);


        update_user_meta(get_current_user_id(),'dzsap_wishlist',json_encode($arr_wishlist));




	    die();
    }
    function ajax_get_pcm(){
        echo '';



        $id = '';


        if(isset($_POST['playerid']) && $_POST['playerid']){
            $id = $_POST['playerid'];

        }else{

            if(isset($_POST['source']) && $_POST['source']){
                $id = ($_POST['source']);
            }
        }



        echo $this->generate_pcm($_POST,array(
                'generate_only_pcm'=>true
        ));

        die();

        $id = $this->clean($id);


        $fout = '';
        $lab  = 'dzsap_pcm_data_'.$id;




        $pcm = '';
        $pcm = get_option($lab);

//        echo 'pcm - '.$pcm. ' - source ( $lab - '.$lab.' ) |||'."\n\n";
//                echo ' source ( dzsap_pcm_data_'.$this->clean($che['source']).' )';

        if($pcm=='' || $pcm=='[]'){

//            if(isset($che['linktomediafile'])){
//                if($che['linktomediafile']){
//                    $lab  = 'dzsap_pcm_data_'.$che['linktomediafile'];
//                }
//            }



            // -- its ok because playerid is prioritary
            if( ( $pcm == '' || $pcm== '[]') && isset($_POST['source']) && $_POST['source']){
                $lab  = 'dzsap_pcm_data_'.$_POST['source'];
                $pcm = get_option($lab);
            }

//                    echo 'lab - '.$lab;
//                    $lab = 'dzsap_pcm_data_735';

//                    echo 'lab - '.$lab;

//                    echo 'pcm - '.$pcm;

        }


        echo $pcm;




        die();
    }

    function filter_the_content($fout){

//        echo 'what what';

//        $fout='ceva'.$fout;





        if($this->mainoptions['replace_powerpress_plugin']=='on'){

            global $post;

            global $powerpress_feed;
//            print_rr($powerpress_feed);

// PowerPress settings:
            $GeneralSettings = get_option('powerpress_general');
//                print_rr($GeneralSettings);


            $feed_slug = 'podcast';


            $EpisodeData = null;
            if(function_exists('powerpress_get_enclosure_data')){

                $EpisodeData = powerpress_get_enclosure_data($post->ID, $feed_slug);
            }

//            print_rr($EpisodeData);


            if($EpisodeData && isset($EpisodeData['url'])){








//            echo 'whaaa';
                $this->sliders__player_index++;

//                $fout = '';


                $src = get_post_meta($post->ID, 'dzsap_woo_product_track', true);



                $this->front_scripts();

                $margs = $this->powerpress_generate_margs();


                $args = array(

                );

	            $margs['autoplay'] = 'off';
                $aux = $this->shortcode_player($margs);



                return $aux.$fout;


            }

        }


        return $fout;
    }

    function powerpress_generate_margs(){

        global $post;

        global $powerpress_feed;
//            print_rr($powerpress_feed);

// PowerPress settings:
        $GeneralSettings = get_option('powerpress_general');
//                print_rr($GeneralSettings);


        $feed_slug = 'podcast';

        $margs = array();

        $EpisodeData = powerpress_get_enclosure_data($post->ID, $feed_slug);

//            print_rr($EpisodeData);


        if($EpisodeData && isset($EpisodeData['url'])) {


//            echo 'whaaa';
            $this->sliders__player_index++;

//                $fout = '';


            $src = get_post_meta($post->ID, 'dzsap_woo_product_track', true);


            $this->front_scripts();

            $margs = array('config' => 'powerpress_player',);

//        $margs = array_merge($margs, $atts);

//        print_r($margs);
            $margs['source'] = $EpisodeData['url'];
            $margs['called_from'] = 'powerpress';
            $margs['playerid'] = $post->ID;
            $margs['config'] = 'powerpress_player';
            $margs['artistname'] = $post->post_title;
//            $margs['js_settings_extrahtml_in_float_right'] = '<div><span class="display-inline-block">Share:</span>&nbsp;&nbsp;&nbsp;<div class="display-inline-block dzstooltip-con" style=";"><div class="the-icon-bg"></div> <span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black align-right" style="width: auto; white-space: nowrap;">Share on Twitter</span><i class=" svg-icon fa fa-twitter" style="color: #5aacff;"></i></div>   <div class="display-inline-block dzstooltip-con" style=";"><div class="the-icon-bg"></div> <span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black align-right" style="width: auto; white-space: nowrap;">Share on Facebook</span><i class=" svg-icon fa fa-facebook-square" style="color: #2288ff;"></i></div> </div><br><a class="button-grad " href="{{meta2val}}">                                        <i class="fa fa-apple"></i>                                        <span class="i-label">iTunes</span>                                    </a>  <a class="button-grad " href="{{meta1val}}">                                        <i class="fa fa-rss"></i>                                        <span class="i-label">RSS</span>                                    </a>  <a class="button-grad dzsap-multisharer-but " href="#">                                        <i class="fa fa-share "></i>                                        <span class="i-label">Embed</span>                                    </a>  ';

            if (get_the_post_thumbnail_url($post)) {

                $margs['thumb'] = get_the_post_thumbnail_url($post);
            }



            $categories = get_the_terms( $post->ID, 'category' );

//            print_rr($post);
//            print_rr($categories);
            if ( ! $categories || is_wp_error( $categories ) )
                $categories = array();

            $categories = array_values( $categories );


            if(count($categories)){



            }
            foreach ( $categories as $key => $val ) {
//                print_rr($val);


                // Get the URL of this category
                $category_link = get_category_link( $val->term_id );

//                print_rr($category_link);
//                libxml_use_internal_errors(false);
//                $myXMLData = DZSHelpers::get_contents($category_link.'feed');




                global $dzsap_got_category_feed;



                $lasttime = get_option('dzsap_last_read_category');

//                echo 'lasttime - '.$lasttime.'<br>';
//                echo 'lasttime ... time()-8 -> '.($lasttime-8).'<br>';
//                echo 'time() - '.time().'<br>';
//                echo 'lasttime==false - '.$lasttime==false.'<br>';
//                echo 'lasttime ... time()-8 - '.(($lasttime<time())-8).'<br>';






                $myXMLData = '';

                if(get_option('taxonomy_'.$val->term_id)){
                    $aux = get_option('taxonomy_'.$val->term_id);

//                    print_rr($aux);

                    if(isset($aux['feed_xml'])){
                        $myXMLData = $aux['feed_xml'];
                    }


                    $myXMLData = stripslashes($myXMLData);
                }



                if($myXMLData=='' && $this->mainoptions['powerpress_read_category_xml']=='on' && ($lasttime==false || $lasttime<time()-15)){




                    if($this->debug){

                        print_rr($category_link.'feed') ;
                    }
                    update_option('dzsap_last_read_category',time());
                    $myXMLData = @file_get_contents($category_link.'feed');
//                    $myXMLData = @file_get_contents('https://www.almightyballer.com/category/a-team/buzz-beat/feed/');
//                    $myXMLData = DZSHelpers::get_contents('https://www.almightyballer.com/category/a-team/buzz-beat/feed/');




//                    echo file_get_contents('https://www.almightyballer.com/category/a-team/buzz-beat/feed/');

                    if($this->debug) {
                        echo '<pre class="hmm">';   print_r($myXMLData);  echo '</pre>';
                    }

//                    echo 'yes';

                    $dzsap_got_category_feed = true;

                }

                if($myXMLData){

//                    print_rr($myXMLData);

                    if(strpos($myXMLData,'<?xml')!==false && strpos($myXMLData,'<?xml')<30){

//                        $myXMLData=preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $myXMLData);;


                        try{
//                            $xml = simplexml_load_string($myXMLData);


//                        echo '<pre class="the-xml">';print_r($xml);echo '</pre>';

                            preg_match_all("/<itunes:image href=\"(.*?)\"/", $myXMLData, $output_array);;

                            if(count($output_array[1])){
                                $margs['thumb'] = $output_array[1][0];

                            }

                            preg_match_all("/\<title\>(.*?)<\/title>/", $myXMLData, $output_array);

                            if(count($output_array[1])){
                                $margs['songname'] = $output_array[1][0];

                            }


//                            if($xml){
//
//
//                                if($xml->channel->image[1]->url->__toString()){
//                                    $margs['thumb']=$xml->channel->image[1]->url->__toString();
//                                }
//
//                                if($xml->channel->title->__toString()){
//                                    $margs['songname']=$xml->channel->title->__toString();
//                                }
//
//
//
//
//                            }
                        }catch(Exception $e){
                            echo 'xml error'; error_log(print_rrr($e));
                        }

                    }

                    $margs['cat_feed_data'] = $myXMLData;



                }

            }

        }


        return $margs;


    }



    function handle_add_meta_boxes() {



        add_meta_box('dzsap_footer_player_options',__('Footer Player Settings'),array($this,'admin_meta_options'),'page','normal','high');
        add_meta_box('dzsap_footer_player_options',__('Footer Player Settings'),array($this,'admin_meta_options'),'post','normal','high');



        add_meta_box('dzsap_waveform_generation',__('ZoomSounds Waveforms'),array($this,'admin_meta_download_waveforms'),'download','normal','high');






        add_meta_box('dzsap_meta_options', esc_html__('Audio Item Settings','dzsap'), array($this, 'dzsap_admin_meta_options'), 'dzsap_items', 'normal');




        $meta_post_array = $this->mainoptions['dzsap_meta_post_types'];


        if($meta_post_array && is_array($meta_post_array) && count($meta_post_array)){


            foreach ($meta_post_array as $post_type){
                if($post_type=='dzsap_items'){
                    continue;
                }


                add_meta_box('dzsap_meta_options', __('Audio Item Settings'), array($this, 'dzsap_admin_meta_options'), $post_type, 'normal');

            }
        }

        //add_meta_box( 'attachment_video_thumb', __( 'Thumbnail', 'dzsap' ), array($this,'admin_meta_attachment_video_thumb'), 'attachment', 'normal' );

//        if ($this->db_mainoptions['enable_meta_for_pages_too'] == 'on') {
//            add_meta_box('dzsap_meta_options',__('DZS ZoomFolio Settings'),array($this,'admin_meta_options'),'page','normal','high');
//            add_meta_box('dzsap_meta_gallery',__('Item Gallery','dzsap'),array($this,'admin_meta_gallery'),'page','side');
//        }
    }

    function admin_meta_download_waveforms(){

        global $post;

        $po_id = $post->ID;

        $aux = '';
        $uploadbtnstring = '<button class="button-secondary action upload_file ">'.esc_html__('Upload','dzsap').'</button>';



        if($this->mainoptions['skinwave_wave_mode']!='canvas') {

            $lab = 'dzsap_meta_waveformbg';
            $val = get_post_meta($po_id, $lab, true);

            $aux .= '<div class="setting type_all type_mediafile_hide">
            <h4 class="setting-label">' . __('WaveForm Background Image', 'dzsap') . '</h4>
' . DZSHelpers::generate_input_text($lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . $uploadbtnstring . ' <span class="aux-wave-generator"><button class="btn-autogenerate-waveform-bg button-secondary">' . __("Auto Generate") . '</button></span>
            <div class="sidenote">' . __('Optional waveform image / ', 'dzsap') . ' / ' . __('Only for skin-wave', 'dzsap') . '</div>
        </div>';


            //simple with upload and wave generator
            $lab = 'dzsap_meta_waveformprog';
            $val = get_post_meta($po_id, $lab, true);

            $aux .= '<div class="setting type_all type_mediafile_hide">
            <h4 class="setting-label">' . __('WaveForm Progress Image', 'dzsap') . '</h4>
' . DZSHelpers::generate_input_text($lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . $uploadbtnstring . ' <span class="aux-wave-generator"><button class="btn-autogenerate-waveform-prog button-secondary">Auto Generate</button></span>
            <div class="sidenote">' . __('Optional waveform image / ', 'dzsap') . ' / ' . __('Only for skin-wave', 'dzsap') . '</div>
        </div>';
        }

        echo $aux;
    }



    function dzsap_admin_meta_options() {
        global $post, $wp_version;
        $struct_uploader = '<div class="dzsap-wordpress-uploader">
<a href="#" class="button-secondary">' . __('Upload', 'dzsap') . '</a>
</div>';
        //$wp_version = '3.4.1';
        if ($wp_version < 3.5) {
            $struct_uploader = '<div class="dzs-single-upload">
<input id="files-upload" class="" name="file_field" type="file">
</div>';
        }
        ?>
        <div class="select-hidden-con">
            <input type="hidden" name="dzs_nonce" value="<?php echo wp_create_nonce('dzs_nonce'); ?>"/>




            <div class="dzs-setting">
                <h4><?php echo __('Thumbnail', 'dzsap'); ?></h4>
                <?php echo DZSHelpers::generate_input_text('dzsap_thumb', array('class' => 'input-big-image main-thumb', 'def_value' => '', 'seekval' => get_post_meta($post->ID, 'dzsap_thumb', true))); ?>
                <?php echo $struct_uploader; ?>
                <button style="display: inline-block; vertical-align: top;" class="refresh-main-thumb button-secondary">
                    Auto Generate
                </button>
                <div
                    class='sidenote'><?php echo __('select a thumbnail for the video ( can auto generate if it is an Vimeo or YouTube track )', 'dzsap'); ?></div>
            </div>

            <div class="dzs-setting">
                <h4><?php echo __('Extra Classes', 'dzsap'); ?></h4>
                <?php echo DZSHelpers::generate_input_text('dzsap_extra_classes', array('class' => '', 'def_value' => '', 'seekval' => get_post_meta($post->ID, 'dzsap_extra_classes', true))); ?>
                <div
                    class='sidenote'><?php echo __('[advanced] some extra classes that you want added to the portfolio item', 'dzsap'); ?></div>
            </div>

        </div>

        <?php
        include_once('class_parts/item-meta.php');

        wp_enqueue_style('dzsselector', $this->base_url.'libs/dzsselector/dzsselector.css');
        wp_enqueue_script('dzsselector', $this->base_url.'libs/dzsselector/dzsselector.js');
    }


    function ajax_get_attachment_src(){

        $fout = wp_get_attachment_image_src($_POST['id'], 'full');


        error_log('ajax_get_attachment_src'.print_r($fout,true));

        $fout2 = wp_get_attachment_thumb_url($_POST['id']);
	    error_log(print_r($fout2,true));

        echo $fout[0];
        die();
    }


    function admin_meta_options() {
        global $post,$wp_version;
        $struct_uploader = '
<a href="#" class="button-secondary upload-for-target">'.__('Upload','dzsap').'</a>
';
        //$wp_version = '3.4.1';
        if ($wp_version < 3.5) {
            $struct_uploader = '<div class="dzs-single-upload">
<input id="files-upload" class="" name="file_field" type="file">
</div>';
        }


        $vpconfigs_arr = array(
            array('lab'=>'default', 'val'=>'default')
        );

        $i23=0;
        foreach ($this->mainitems_configs as $vpconfig) {
            //print_r($vpconfig);


            $auxa = array(
                'lab'=>$vpconfig['settings']['id'],
                'val'=>$vpconfig['settings']['id'],
                'extraattr'=>'data-sliderlink="'.$i23.'"',
            );

            array_push($vpconfigs_arr, $auxa);

            $i23++;
        }



        ?>
        <div class="dzsap-meta-bigcon">
            <input type="hidden" name="dzs_nonce" value="<?php echo wp_create_nonce('dzs_nonce'); ?>" />


            <?php
            ?>





            <div class="dzs-setting">
                <?php
                $lab = 'dzsap_footer_enable';

                echo DZSHelpers::generate_input_text($lab,array(
                        'class' => 'fake-input',
                    'def_value' => '',
                    'seekval' => 'off',
                    'input_type' => 'hidden',
                ));
                ?>
                <h4><?php echo __('Enable Sticky Player','dzsap'); ?></h4>
                <?php

                echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting dzs-dependency-field', 'val' => 'on','seekval' => get_post_meta($post->ID,$lab,true))).'
                                        <label for="'.$lab.'"></label>
                                    </div>';



                // -- for future we can do a logical set like "(" .. ")" .. "AND" .. "OR"
                $dependency = array(

                    array(
                        'lab'=>'dzsap_footer_enable',
                        'val'=>array('on'),
                    ),
//                    'relation'=>'AND',
                );


                ?>

            </div>



            <div data-dependency='<?php echo json_encode($dependency); ?>'>

                <?php


                $feed_type = array(
                    array(
                            'lab'=>'parent',
                        'val'=>'parent',
                    ),
                    array(
                            'lab'=>'custom',
                        'val'=>'custom',
                    ),
                );
                ?>




                <div class="dzs-setting "  >
                    <h4><?php echo __('Feed Type','dzsap'); ?></h4>
                    <?php
                    $lab = 'dzsap_footer_feed_type';
                    echo DZSHelpers::generate_select($lab,array('class' => 'dzs-style-me  dzs-dependency-field opener-listbuttons','options' => $feed_type,'seekval' => get_post_meta($post->ID,$lab,true)));


                    ?>

                    <ul class="dzs-style-me-feeder">

                        <div class="bigoption">
                            <span class="option-con"><img src="<?php echo $this->base_url; ?>tinymce/img/footer_type_parent.png"><span class="option-label"><?php echo __("Parent Player"); ?></span></span>
                        </div>

                        <div class="bigoption">
                            <span class="option-con"><img src="<?php echo $this->base_url; ?>tinymce/img/footer_type_media.png"><span class="option-label"><?php echo __("Custom Media"); ?></span></span>
                        </div>

                    </ul>


                    <div class="sidenote">
                        <?php echo __("Select parent player for the sticky player to await being played from the outside ( a track on the page or select custom media to set a custom mp3 to play directly in the sticky player."); ?>
                    </div>

                </div>



                <div class="dzs-setting vpconfig-wrapper"  >
                    <h4><?php echo __('Player configuration','dzsap'); ?></h4>
                    <?php
                    $lab = 'dzsap_footer_vpconfig';
                    echo DZSHelpers::generate_select($lab,array('class' => 'vpconfig-select styleme','options' => $vpconfigs_arr,'seekval' => get_post_meta($post->ID,$lab,true))); ?>

                    <div class="edit-link-con" style="margin-top: 10px;"></div>

                </div>


                <?php



                // -- for future we can do a logical set like "(" .. ")" .. "AND" .. "OR"
                $dependency = array(

                    array(
                        'lab'=>'dzsap_footer_feed_type',
                        'val'=>array('custom'),
                    ),
//                    'relation'=>'AND',
                );



                ?>

            <div class="dzs-setting" data-dependency='<?php echo json_encode($dependency); ?>'>
                <h4><?php echo __('Featured Media','dzsap'); ?></h4>
                <?php
                $lab = 'dzsap_footer_featured_media';
                echo DZSHelpers::generate_input_text($lab,array('class' => 'input-big-image upload-target-prev','def_value' => '','seekval' => get_post_meta($post->ID,$lab,true))); ?>
                <?php echo $struct_uploader; ?>

            </div>

            <?php



            ?>



            <div class="dzs-setting "  data-dependency='<?php echo json_encode($dependency); ?>'>
                <h4><?php echo __('Media Type','dzsap'); ?></h4>
                <?php
                $types_arr = array(
                    array('lab'=>'audio','val'=>'audio'),
                    array('lab'=>'shoutcast','val'=>'shoutcast'),
                    array('lab'=>'soundcloud','val'=>'soundcloud'),
                    array('lab'=>'youtube','val'=>'youtube'),
                    array('lab'=>'fake','val'=>'fake'),
                );
                $lab = 'dzsap_footer_type';
                echo DZSHelpers::generate_select($lab,array('class' => ' styleme','options' => $types_arr,'seekval' => get_post_meta($post->ID,$lab,true))); ?>

                <div class="edit-link-con"></div>

            </div>


            </div>



        </div>



        <?php
    }

    function admin_meta_save($post_id) {
        global $post;
        if (!$post) {
            return;
        }
        /* Check autosave */
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        if (isset($_REQUEST['dzs_nonce'])) {
            $nonce = $_REQUEST['dzs_nonce'];
            if (!wp_verify_nonce($nonce,'dzs_nonce'))
                wp_die('Security check');
        }
        if (is_array($_POST)) {
            $auxa = $_POST;
            foreach ($auxa as $label => $value) {

                //print_r($label); print_r($value);
                if (strpos($label,'dzsap_') !== false) {
                    dzs_savemeta($post->ID,$label,$value);
                }
            }
        }
    }

	function filter_add_defer_attribute($tag, $handle) {
		// add script handles to the array below
		$scripts_to_defer = array('dzsap');

		foreach($scripts_to_defer as $defer_script) {
			if ($defer_script === $handle) {

			    //  async defer="defer"
				return str_replace(' src', ' src', $tag);
			}
		}
		return $tag;
	}


    function filter_woocommerce_get_settings_pages( $settings ) {
//        echo 'hmmdada';
//        $settings[] =
//        return $settings;
    }


    function handle_wp_footer(){
        global $post, $wp_query;





        $footer_player_enabled = false;
        $footer_player_source = 'fake';
        $footer_player_config = 'fake';
        $footer_player_type = 'fake';



        if($this->mainoptions['enable_global_footer_player']!='off'){

            $footer_player_enabled = true;
            $footer_player_source = 'fake';
            $footer_player_type = 'fake';
            $footer_player_config = $this->mainoptions['enable_global_footer_player'];
        }

        if($wp_query && $wp_query->post) {
            if ( (get_post_meta($wp_query->post->ID, 'dzsap_footer_featured_media', true) || get_post_meta($wp_query->post->ID, 'dzsap_footer_enable', true)=='on') && get_post_meta($wp_query->post->ID, 'dzsap_footer_enable', true)!='off')  {

                $footer_player_enabled = true;




//               echo 'get_post_meta($wp_query->post->ID, \'dzsap_footer_type\', true) - '.get_post_meta($wp_query->post->ID, 'dzsap_footer_type', true);
                $footer_player_config = get_post_meta($wp_query->post->ID,'dzsap_footer_vpconfig',true);
                if (get_post_meta($wp_query->post->ID, 'dzsap_footer_feed_type', true)=='custom') {

                    $footer_player_source = get_post_meta($wp_query->post->ID,'dzsap_footer_featured_media',true);
                    $footer_player_type = get_post_meta($wp_query->post->ID,'dzsap_footer_type',true);

                }
            }
        }




        if($footer_player_enabled){
            if($footer_player_source){

                $this->front_scripts();



                $vpsettingsdefault = array(
                    'id' => 'default',
                    'skin_ap' => 'skin-wave',
                    'settings_backup_type' => 'full',
                    'skinwave_dynamicwaves' => 'off',
                    'skinwave_enablespectrum' => 'off',
                    'skinwave_enablereflect' => 'on',
                    'skinwave_comments_enable' => 'off',
                    'skinwave_mode' => 'normal',
                );



                $cue = 'on';
                if($footer_player_type==='fake'){

                    $cue = 'off';


                }

                $args = array(
                    'player_id'=>'dzsap_footer',

                    'source' => $footer_player_source,
                    'cue' => $cue,
                    'config' => $footer_player_config,
                    'autoplay' => 'off',
                    'type' => $footer_player_type,
                );


                $vpconfig_k = -1;
                $vpconfig_id = $footer_player_config;
                for ($i = 0; $i < count($this->mainitems_configs); $i++) {
                    if ((isset($vpconfig_id)) && ($vpconfig_id == $this->mainitems_configs[$i]['settings']['id'])) {
                        $vpconfig_k = $i;
                    }
                }



                if ($vpconfig_k > -1) {
                    $vpsettings = $this->mainitems_configs[$vpconfig_k];
                } else {
                    $vpsettings['settings'] = $vpsettingsdefault;
                }





//                print_r($vpsettings);


//                echo 'hmm';


                echo '<div class="dzsap-sticktobottom-placeholder dzsap-sticktobottom-placeholder-for-'.$vpsettings['settings']['skin_ap'].'"></div>
<section class="dzsap-sticktobottom ';



                // TODO: redundant I guess ( already handled by js )
                if( (isset($vpsettings['settings']['skin_ap'])==false ||
                   $vpsettings['settings']['skin_ap']=='skin-wave' ) &&
                   ( isset($vpsettings['settings']['skinwave_mode']) && $vpsettings['settings']['skinwave_mode']=='small'
                   )
                ){
                    echo ' dzsap-sticktobottom-for-skin-wave';
                }

//                print_r($vpsettings); echo 'ceva';

                if(isset($vpsettings['settings']['skin_ap'])==false || ($vpsettings['settings']['skin_ap']=='skin-silver')){
                    echo ' dzsap-sticktobottom-for-skin-silver';
                }




                echo '">';

                echo '<div class="dzs-container">';


                if(isset($vpsettings['settings']['enable_footer_close_button'])==false || ($vpsettings['settings']['enable_footer_close_button']=='on')){
                    echo '<div class="sticktobottom-close-con"><svg version="1.1" class="svg-icon icon-hide" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="144.883px" height="145.055px" viewBox="0 0 144.883 145.055" enable-background="new 0 0 144.883 145.055" xml:space="preserve"> <g> <g> <g> <g> <g> <path fill="#5A5B5D" d="M72.527,145.055C32.535,145.055,0,112.52,0,72.527S32.535,0,72.527,0c37.921,0,69.7,29.6,72.35,67.387 c0.097,1.377-0.942,2.572-2.319,2.669c-1.384,0.087-2.571-0.941-2.669-2.319C137.423,32.557,107.834,5,72.527,5 C35.293,5,5,35.293,5,72.527s30.293,67.527,67.527,67.527c35.271,0,64.858-27.525,67.355-62.665 c0.098-1.377,1.302-2.396,2.672-2.316c1.377,0.099,2.414,1.294,2.316,2.672C142.188,115.488,110.41,145.055,72.527,145.055z"/> </g> </g> <g> <g> <g> <path fill="#5A5B5D" d="M45.658,101.897c-0.64,0-1.279-0.244-1.768-0.732c-0.977-0.976-0.977-2.559,0-3.535l25.102-25.103 L43.891,47.425c-0.977-0.977-0.977-2.56,0-3.535c0.977-0.977,2.559-0.977,3.535,0l26.869,26.87 c0.977,0.977,0.977,2.559,0,3.535l-26.869,26.87C46.938,101.653,46.298,101.897,45.658,101.897z"/> </g> </g> <g> <g> <path fill="#5A5B5D" d="M99.396,101.896c-0.64,0-1.279-0.244-1.768-0.732L70.76,74.295c-0.977-0.977-0.977-2.559,0-3.535 l26.869-26.87c0.977-0.977,2.559-0.977,3.535,0c0.977,0.976,0.977,2.559,0,3.535L76.062,72.527l25.102,25.102 c0.977,0.977,0.977,2.559,0,3.535C100.676,101.652,100.036,101.896,99.396,101.896z"/> </g> </g> </g> </g> </g> </g> </svg><svg version="1.1" class="svg-icon icon-show" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="148.025px" height="148.042px" viewBox="0 0 148.025 148.042" enable-background="new 0 0 148.025 148.042" xml:space="preserve"> <g> <g> <g> <g> <g> <g> <path fill="#5A5B5D" d="M74.038,148.042c-8.882,0-17.778-1.621-26.329-4.873C14.546,130.561-5.043,96.09,1.132,61.206 c0.241-1.359,1.537-2.268,2.897-2.026c1.359,0.241,2.267,1.538,2.026,2.897c-5.757,32.523,12.508,64.662,43.431,76.418 c17.222,6.551,35.964,6.003,52.771-1.544c16.809-7.547,29.672-21.188,36.221-38.411c6.552-17.222,6.004-35.963-1.543-52.771 c-7.546-16.809-21.188-29.672-38.411-36.222C68.706-1.792,35.266,8.613,17.206,34.85c-0.783,1.138-2.338,1.424-3.478,0.642 c-1.137-0.783-1.424-2.34-0.642-3.478C32.458,3.874,68.324-7.283,100.301,4.873c18.472,7.024,33.103,20.821,41.195,38.848 c8.094,18.027,8.682,38.127,1.655,56.597c-7.023,18.472-20.819,33.102-38.846,41.195 C94.624,145.859,84.342,148.041,74.038,148.042z"/> </g> </g> </g> <g> <g> <g> <g> <g> <path fill="#5A5B5D" d="M53.523,111.167c-0.432,0-0.863-0.111-1.25-0.335c-0.773-0.446-1.25-1.271-1.25-2.165V39.376 c0-0.894,0.477-1.719,1.25-2.165c0.773-0.447,1.727-0.447,2.5,0l60.014,34.646c0.773,0.446,1.25,1.271,1.25,2.165 s-0.477,1.719-1.25,2.165l-60.014,34.645C54.387,111.056,53.955,111.167,53.523,111.167z M56.023,43.706v60.631 l52.514-30.314L56.023,43.706z"/> </g> </g> </g> </g> </g> </g> </g> </g> </svg> </div>';
                }



                $aux = array('called_from'=> 'footer_player');

                $args = array_merge($args, $aux);


//                echo 'args - '; print_rr($args);


                echo $this->shortcode_player($args);



                echo '</div>';
                echo '</section>';


            }
        }


        if($this->mainoptions['extra_js']){
            echo '<script>';
            echo stripslashes($this->mainoptions['extra_js']);
            echo '</script>';
        }



        if($this->og_data && count($this->og_data)){

            $image = '';
//            if (get_post_meta($post->ID, 'dzsvp_thumb', true)) {
//                $image = get_post_meta($post->ID, 'dzsvp_thumb', true);
//            }else{
//
//                $image = $this->sanitize_id_to_src( get_post_thumbnail_id($post->ID) );
//            }


            $image=$this->og_data['image'];



            echo '<meta property="og:title" content="' . $this->og_data['title'] . '" />';

            echo '<meta property="og:description" content="' . strip_tags($this->og_data['description']) . '" />';

            if($image){

                echo '<meta property="og:image" content="' . $image . '" />';
            }


        }

        /*
         *
         * <h6 class="social-heading">Social Networks</h6> <a class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://www.facebook.com/sharer.php?u={{replacewithcurrurl}}&amp;title=test&quot;); return false;"><i class="fa fa-facebook-square"></i><span class="the-tooltip">SHARE ON FACEBOOK</span></a> <a class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://twitter.com/share?url={{replacewithcurrurl}}&amp;text=Check this out!&amp;via=ZoomPortal&amp;related=yarrcat&quot;); return false;"><i class="fa fa-twitter"></i><span class="the-tooltip">SHARE ON TWITTER</span></a> <a class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://plus.google.com/share?url={{replacewithcurrurl}}&quot;); return false; "><i class="fa fa-google-plus-square"></i><span class="the-tooltip">SHARE ON GOOGLE PLUS</span></a> <a class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://www.linkedin.com/shareArticle?mini=true&amp;url={{replacewithcurrurl}}&amp;title=Check%20this%20out%20&amp;summary=&amp;source=http://localhost:8888/soundportal/source/index.php?page=page&amp;page_id=20&quot;); return false; "><i class="fa fa-linkedin"></i><span class="the-tooltip">SHARE ON LINKEDIN</span></a> <a class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://pinterest.com/pin/create/button/?url={{replacewithcurrurl}}&amp;text=Check this out!&amp;via=ZoomPortal&amp;related=yarrcat&quot;); return false;"><i class="fa fa-pinterest"></i><span class="the-tooltip">SHARE ON PINTEREST</span></a>
         *
         *
         *
         */


        if($this->sw_enable_multisharer){
            ?><script>
            window.dzsap_social_feed_for_social_networks = '<h6 class="social-heading"><?php echo addslashes(__("Social Networks",'dzsap')); ?></h6> <a class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://www.facebook.com/sharer.php?u={{shareurl}}&amp;title=test&quot;); return false;"><i class="fa fa-facebook-square"></i><span class="the-tooltip"><?php echo addslashes(__("SHARE ON",'dzsap')); ?> FACEBOOK</span></a> <a class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://twitter.com/share?url={{shareurl}}&amp;text=Check this out!&amp;via=ZoomPortal&amp;related=yarrcat&quot;); return false;"><i class="fa fa-twitter"></i><span class="the-tooltip"><?php echo addslashes(__("SHARE ON", 'dzsap')); ?> TWITTER</span></a> <a class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://plus.google.com/share?url={{shareurl}}&quot;); return false; "><i class="fa fa-google-plus-square"></i><span class="the-tooltip"><?php echo addslashes(__("SHARE ON",'dzsap')); ?> GOOGLE PLUS</span></a> <a class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://www.linkedin.com/shareArticle?mini=true&amp;url={{shareurl}}&amp;title=Check%20this%20out%20&amp;summary=&amp;source={{shareurl}}); return false; "><i class="fa fa-linkedin"></i><span class="the-tooltip"><?php echo addslashes(__("SHARE ON",'dzsap')); ?> LINKEDIN</span></a> <a class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://pinterest.com/pin/create/button/?url={{shareurl}}&amp;text=Check this out!&amp;via=ZoomPortal&amp;related=yarrcat&quot;); return false;"><i class="fa fa-pinterest"></i><span class="the-tooltip"><?php echo addslashes(__("SHARE ON",'dzsap')); ?> PINTEREST</span></a>';


            window.dzsap_social_feed_for_share_link = '<h6 class="social-heading"><?php echo addslashes(__("Share Link",'dzsap')); ?></h6> <div class="field-for-view field-for-view-link-code">{{replacewithcurrurl}}</div>';


            window.dzsap_social_feed_for_embed_link = ' <h6 class="social-heading"><?php echo addslashes(__("Embed Code",'dzsap')); ?></h6> <div class="field-for-view field-for-view-embed-code">{{replacewithembedcode}}</div>';
            </script>
            <?php
        }



if( ( $this->mainoptions['wc_loop_product_player'] && $this->mainoptions['wc_loop_product_player']!='off' )  || ($this->mainoptions['wc_single_product_player'] && $this->mainoptions['wc_single_product_player']!='off')) {


//            echo ' $this->mainoptions[\'wc_loop_player_position\'] -  '.$this->mainoptions['wc_loop_player_position'];




    $player_position = $this->mainoptions['wc_loop_player_position'];




    if ($this->mainoptions['wc_loop_player_position'] == 'overlay') {


        ?><script>
jQuery(document).ready(function($){

    var _body = $('body').eq(0);

    if(_body.hasClass('single-product')){

        console.info("HMM");
        <?php

        if ($this->mainoptions['wc_single_player_position'] == 'overlay') {
        ?>
        var _c = $('.woocommerce-product-gallery__wrapper').eq(0);
        _c.append($('.go-to-thumboverlay').eq(0));
        var _c2 = $('.go-to-thumboverlay').eq(0);
        _c.css({

            'position': 'relative'
            ,'display': 'block'
        })
        _c2.css({
            'position': 'absolute'
            ,'width':'100%'
            ,'height':'100%'
            ,'top':'0'
            ,'left':'0'
        })
        _c.append($('.go-after-thumboverlay').eq(0));
        var _c2 = $('.go-after-thumboverlay').eq(0);
        _c2.css({
//            'position': 'absolute'
//            ,'width':'100%'
//            ,'height':'100%'
//            ,'top':'0'
//            ,'left':'0'
        });
        <?php
        }
        ?>
    }else{


        $('.go-to-thumboverlay').each(function(){
            var _t = $(this);


            console.log('_t - ',_t, _t.siblings('.wp-post-image'));

            if(_t.siblings('.wp-post-image').length){
                _t.parent().css({

                    'position': 'relative'
                    ,'display': 'block'
                })
                _t.css({
                    'position': 'absolute'
                    ,'width':'100%'
                    ,'height':_t.siblings('.wp-post-image').eq(0).height()
                    ,'top':'0'
                    ,'left':'0'
                })
            }
        })
    }

})

        </script><?php
    }
}





        if(isset($this->mainoptions['replace_powerpress_plugin']) && $this->mainoptions['replace_powerpress_plugin']=='on') {


            ?>
            <style>
                .powerpress_player {
                    display: none;
                }
            </style><?php


            global $post;

            global $powerpress_feed;
            //            print_rr($powerpress_feed);

            // PowerPress settings:
            $GeneralSettings = get_option('powerpress_general');
            //                print_rr($GeneralSettings);


            $feed_slug = 'podcast';


            if (function_exists('powerpress_get_enclosure_data') && $post && $post->post_type=='post') {


                $EpisodeData = powerpress_get_enclosure_data($post->ID, $feed_slug);

                //            print_rr($EpisodeData);


                if ($EpisodeData && isset($EpisodeData['url'])) {


                    //            echo 'whaaa';
                    $this->sliders__player_index++;

                    //                $fout = '';


                    $src = get_post_meta($post->ID, 'dzsap_woo_product_track', true);


                    $this->front_scripts();

                    $margs = $this->powerpress_generate_margs();


                    //        $enc_margs = simple_encrypt(json_encode($margs),'1111222233334444');
                    //        $enc_margs = gzcompress(json_encode($embed_margs),9);
                    $enc_margs = json_encode($margs);
                    $enc_margs = base64_encode(json_encode($margs));
                    //        $enc_margs = base64_decode(base64_encode(json_encode($embed_margs)));

                    //        $embed_code = '<iframe src=\'' . $this->base_url . 'bridge.php?type=player&margs='.urlencode($enc_margs).'\' style="overflow:hidden; transition: height 0.3s ease-out;" width="100%" height="152" scrolling="no" frameborder="0"></iframe>';


                    $embed_url = site_url() . '?action=embed_zoomsounds&type=player&margs=' . urlencode($enc_margs);
                    $embed_code = '<iframe src=\'' . $embed_url . '\' style="overflow:hidden; transition: height 0.3s ease-out;" width="100%" height="152" scrolling="no" frameborder="0"></iframe>';


                    ?>
                    <meta name="twitter:card" content="player">
                    <meta name="twitter:site" content="@youtube">
                    <meta name="twitter:url" content="<?php echo get_permalink($post->ID); ?>">
                    <meta name="twitter:title" content="<?php echo get_permalink($post->post_title); ?>">
                    <meta name="twitter:description" content="<?php echo get_permalink($post->post_content); ?>">
                    <meta name="twitter:image" content="">
                    <meta name="twitter:app:name:iphone" content="<?php echo get_permalink($post->ID); ?>">
                    <meta name="twitter:app:name:googleplay" content="<?php echo get_permalink($post->post_title); ?>">
                    <meta name="twitter:player" content="<?php echo $embed_url; ?>">
                    <meta name="twitter:player:width" content="1280">
                    <meta name="twitter:player:height" content="300"><?php


                }


            }

        }






        if(isset($_GET['action'])){
            if($_GET['action']=='embed_zoomsounds'){


                echo '<div class="zoomsounds-embed-con">';

                $args = array();
                if(isset($_GET['type']) && $_GET['type']=='gallery'){

                    $args = array(
                        'id' => $_GET['id'],
                        'embedded' => 'on',
                    );


                    if(isset($_GET['db'])){
                        $args['db'] = $_GET['db'];
                    };
                    echo $this->show_shortcode($args);

                }
                if(isset($_GET['type']) && $_GET['type']=='playlist'){

                    $args = array(
                        'ids' => $_GET['ids'],
                        'embedded' => 'on',
                    );


                    if(isset($_GET['db'])){
                        $args['db'] = $_GET['db'];
                    };
                    echo $this->shortcode_playlist($args);

                }




                if(isset($_GET['type']) && $_GET['type']=='player'){


//    echo $_GET['margs'];
                    $args = array();
                    try{
//        echo '.'.stripslashes($_GET['margs']).'.';
                        $args = @unserialize((stripslashes($_GET['margs'])));
                    }catch(Exception $e){

//        $args = array();
                    }




//    print_r($args);

                    if(is_array($args)){

                    }else{
                        $args = array();



//        echo 'try json decode -> ';
//        echo stripslashes(stripslashes($_GET['margs']));
//        echo ' <- ';
//
//        echo '
//        try json decode -> ';
//        echo (stripslashes($_GET['margs']));
//        echo ' <- ';


                        $args = json_decode((stripslashes(base64_decode($_GET['margs']))),true);

//        print_rr($args);

                        if(is_object($args) || is_array($args)){

                        }else{
                            $args = array();



                        }

                    }
//    print_r($args);
                    $args['embedded']='on';
                    $args['extra_classes']=' test';
                    $args['called_from']='embed';


                    echo $this->shortcode_player($args);

                }
                echo '</div>';
            }
        }
    }

    function my_formatter($content) {
        $new_content = '';
        $pattern_full = '{(\[raw\].*?\[/raw\])}is';
        $pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
        $pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

        foreach ($pieces as $piece) {
            if (preg_match($pattern_contents, $piece, $matches)) {
                $new_content .= $matches[1];
            } else {
                $new_content .= wptexturize(wpautop($piece));
            }
        }
        return $new_content;
    }


    //include the tinymce javascript plugin
    function tinymce_external_plugins($plugin_array) {
        $plugin_array['ve_zoomsounds_player'] = $this->base_url.'/tinymce/visualeditor/editor_plugin.js';
        $plugin_array['noneditable'] = $this->base_url.'/tinymce/noneditable/plugin.min.js';
        return $plugin_array;
    }

    //include the css file to style the graphic that replaces the shortcode
    function myformatTinyMCE($options){

        $ext = 'iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src|id|class|title|style],video[source],source[*]';

//    if ( isset( $options['extended_valid_elements'] ) )
//        $options['extended_valid_elements'] .= ',' . $ext;
//    else
//        $options['extended_valid_elements'] = $ext;
//
//
//        $options['media_strict'] = 'false';
//        $options['noneditable_leave_contenteditable'] = 'true';
//


//        $options['content_css'] .= ",".$this->base_url.'/tinymce/visualeditor/editor-style.css';


        if($this->mainoptions['replace_playlist_shortcode'] == 'on'){


            $options['content_css'] .= ",".$this->base_url.'audioplayer/audioplayer.css';
        }
        if($this->mainoptions['replace_audio_shortcode'] && $this->mainoptions['replace_audio_shortcode']!=='off'){


            $options['content_css'] .= ",".$this->base_url.'audioplayer/audioplayer.css';
        }

//    print_r($options);
        return $options;
    }

    public function generate_item_structure($pargs = null) {
        $margs = array(
            'generator_type' => 'normal',
            'type' => '',
            'source' => '',
            'sourceogg' => '',
            'waveformbg' => '',
            'waveformprog' => '',
            'thumb' => '',
            'linktomediafile' => '',
            'playfrom' => '',
            'bgimage' => '',
            'extra_html' => '',
            'extra_html_left' => '',
            'extra_html_in_controls_left' => '',
            'extra_html_in_controls_right' => '',
            'menu_artistname' => '',
            'menu_songname' => '',
            'menu_extrahtml' => '',
        );

        if (is_array($pargs) == false) {
            $pargs = array();
        }

        $margs = array_merge($margs, $pargs);


        $lab = 'type';
        $val = $margs[$lab];




        $uploadbtnstring = '<button class="button-secondary action upload_file ">Upload</button>';



        if ($this->mainoptions['usewordpressuploader'] != 'on') {
            $uploadbtnstring = '<div class="dzs-upload">
<form name="upload" action="#" method="POST" enctype="multipart/form-data">
    	<input type="button" value="Upload" class="btn_upl"/>
        <input type="file" name="file_field" class="file_field"/>
        <input type="submit" class="btn_submit"/>
</form>
</div>
<div class="feedback"></div>';
        }



        $aux = '';
        if ($margs['generator_type'] != 'onlyitems') {
            $aux = '<div class="item-con">
            <div class="item-delete">x</div>
            <div class="item-duplicate"></div>
        <div class="item-preview" style="">
        </div>
        <div class="item-settings-con">';
        }

        $aux.='<div class="setting type_all">
            <h4 class="non-underline"><span class="underline">' . __('Type', 'dzsap') . '*</span>&nbsp;&nbsp;&nbsp;<span class="sidenote">select one from below</span></h4>

            <div class="main-feed-chooser select-hidden-metastyle select-hidden-foritemtype">
' . DZSHelpers::generate_select('0-0-' . $lab, array('options' => array('mediafile', 'soundcloud', 'shoutcast', 'youtube', 'audio', 'inline'), 'seekval' => $val, 'class' => 'textinput item-type', 'extraattr' => ' data-label="' . $lab . '"')) . '
                <div class="option-con clearfix">

                    <div class="an-option">
                    <div class="an-title">
                    ' . __('Media File', 'dzsap') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Link to a media file from your WordPress Media Library.', 'dzsap') . '
                    </div>
                    </div>

                    <div class="an-option">
                    <div class="an-title">
                    ' . __('SoundCloud Sound', 'dzsap') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Stream SoundCloud sounds. Input the full link to the sound in the Source field. '
                . 'You will have to input your SoundCloud API Key into ZoomSounds > Settings.', 'dzsap') . ' <a href="' . $this->base_url . 'readme/index.html#handbrake" target="_blank" class="">Documentation here</a>.
                    </div>
                    </div>

                    <div class="an-option">
                    <div class="an-title">
                    ' . __('ShoutCast Radio', 'dzsap') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Insert a shoutcast radio address. It will have to stream in mpeg format. Input the address, example:  ', 'dzsap') . ' - http://vimeo.com/<strong>55698309</strong>
                    </div>
                    </div>

                    <div class="an-option">
                    <div class="an-title">
                    ' . __('YouTube', 'dzsap') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Input the YouTube video id. Warning - will not work on iOS.', 'dzsap') . '
                    </div>
                    </div>
                    
                    
                    
                    <div class="an-option">
                    <div class="an-title">
                    
                    ' . __('Self-Hosted Audio', 'dzsap') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Only mp3 is mandatory. Browsers that cannot decode mp3 will use the included Flash Player backup '
                . '. If you want full html5 player, you must set a ogg sound too.', 'dzsap') . '
                    </div>
                    </div>
                    
                    

                    <div class="an-option">
                    <div class="an-title">
                    ' . __('Inline Content', 'dzsap') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Insert in the <strong>Source</strong> field custom content ( ie. embed from a custom site ).', 'dzsap') . '
                    </div>
                    </div>
                </div>
            </div>
        </div>';




        $lab = 'source';
        $val = $margs[$lab];


        $aux.='<div class="setting type_all type_mediafile_hide">
            <div class="setting-label">' . __('Source', 'dzsap') . '*
                <div class="info-con">
                <div class="info-icon"></div>
                <div class="sidenote">' . __('Below you will enter your audio file address. If it is a video from YouTube or Vimeo you just need to enter
                the id of the video in the . The ID is the bolded part http://www.youtube.com/watch?v=<strong>j_w4Bi0sq_w</strong>.
                If it is a local video you just need to write its location there or upload it through the Upload button ( .mp3 format ).', 'dzsap') . '
                    </div>
                </div>
            </div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput main-source type_all upload-type-audio', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . $uploadbtnstring . '
        </div>';



        $lab = 'soundcloud_track_id';
        $val = '';

        if(isset($margs[$lab])){
            $val = $margs[$lab];
        }


        $aux.='<div class="setting type_soundcloud">
            <div class="setting-label">' . __('Track ID', 'dzsap') . '
            </div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput ', 'seekval' => $val, 'extraattr' => '')).'
                <div class="sidenote">' . __('Only for Private Soundcloud files. Guide on how to get the track_id - ', 'dzsap') .'<a href="http://digitalzoomstudio.net/docs/wpzoomsounds/#faq_secret_token">'.__("here").'</a>' . '
        </div>
        </div>';



        $lab = 'soundcloud_secret_token';
        $val = '';

        if(isset($margs[$lab])){
            $val = $margs[$lab];
        }


        $aux.='<div class="setting type_soundcloud">
            <div class="setting-label">' . __('Secret Token', 'dzsap') . '
            </div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput ', 'seekval' => $val, 'extraattr' => '')).'
                <div class="sidenote">' . __('Only for Private Soundcloud files. Guide on how to get the track_id - ', 'dzsap') .'<a href="http://digitalzoomstudio.net/docs/wpzoomsounds/#faq_secret_token">'.__("here").'</a>' . '
                    </div>
        </div>';


        $lab = 'sourceogg';
        $val = $margs[$lab];

        $aux.='<div class="setting type_all type_mediafile_hide">
            <div class="setting-label">HTML5 OGG ' . __('Format', 'dzsap') . '</div>
            <div class="sidenote">' . __('Optional ogg / ogv file', 'dzsap') . ' / ' . __('Only for the Video or Audio type', 'dzsap') . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . $uploadbtnstring . '
        </div>';



        if($this->mainoptions['skinwave_wave_mode']!='canvas') {
            $lab = 'waveformbg';
            $val = $margs[$lab];

            $aux .= '<div class="setting type_all type_mediafile_hide">
            <div class="setting-label">' . __('WaveForm Background Image', 'dzsap') . '</div>
            <div class="sidenote">' . __('Optional waveform image / ', 'dzsap') . ' / ' . __('Only for skin-wave', 'dzsap') . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . $uploadbtnstring . ' <span class="aux-wave-generator"><button class="btn-autogenerate-waveform-bg button-secondary">' . __("Auto Generate") . '</button></span>
        </div>';


            //simple with upload and wave generator
            $lab = 'waveformprog';
            $val = $margs[$lab];

            $aux .= '<div class="setting type_all type_mediafile_hide">
            <div class="setting-label">' . __('WaveForm Progress Image', 'dzsap') . '</div>
            <div class="sidenote">' . __('Optional waveform image / ', 'dzsap') . ' / ' . __('Only for skin-wave', 'dzsap') . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . $uploadbtnstring . ' <span class="aux-wave-generator"><button class="btn-autogenerate-waveform-prog button-secondary">Auto Generate</button></span>
        </div>';
        }



        $lab = 'linktomediafile';
        $val = $margs[$lab];

        $aux.='<div class="setting type_all">
            <div class="setting-label">' . __('Link To Media File', 'dzsap') . '</div>
            <div class="sidenote">' . __('you can link to a media file in order to have comment / rates - just input the id of the media here or ', 'dzsap') . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput type_all upload-type-audio upload-prop-id main-media-file', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . $this->misc_generate_upload_btn(array('label' => 'Link')) . '
</div>';


        //textarea special thumb
        $lab = 'thumb';
        $val = $margs[$lab];


        $aux.='
        <div class="setting type_all ">
            <div class="setting-label">' . __('Thumbnail', 'dzsap') . '</div>
            <div class="sidenote">' . __('a thumbnail ', 'dzsap') . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput main-thumb type_all upload-type-image', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . $uploadbtnstring . '
</div>';





        //simple with upload and wave generator
        $lab = 'playfrom';
        $val = $margs[$lab];

        $aux.='<div class="setting type_all">
            <div class="setting-label">' . __('Play From', 'dzsap') . '</div>
            <div class="sidenote">' . __('choose a number of seconds from which the track to play from ( for example if set "70" then the track will start to play from 1 minute and 10 seconds ) or input "last" for the track to play at the last position where it was.', 'dzsap') . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . '
        </div>';



        //simple with upload and wave generator
        $lab = 'bgimage';
        $val = $margs[$lab];

        $aux.='<div class="setting type_all">
            <div class="setting-label">' . __('Background Image', 'dzsap') . '</div>
            <div class="sidenote">' . __('optional - choose a background image to appear ( needs a wrapper / read docs )', 'dzsap') . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"'))  . $this->misc_generate_upload_btn(array('label' => __('Upload', 'dzsap'))) .'
        </div>';


        $lab = 'play_in_footer_player';
        $val = '';

        $aux.='<div class="setting type_all">
            <div class="setting-label">' . __('Play in footer player', 'dzsap') . '</div>
            <div class="sidenote">' . __('optional - play this track in the footer player ( footer player must be setuped on the page ) ', 'dzsap') . '</div>
' . DZSHelpers::generate_select('0-0-' . $lab, array('class' => 'textinput  styleme', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"', 'options' => array('off','on') )) .'
        </div>';


        $lab = 'enable_download_button';
        $val = '';

        $aux.='<div class="setting type_all">
            <div class="setting-label">' . __('Enable Download Button', 'dzsap') . '</div>
            <div class="sidenote">' . __('optional - Enable Download Button for this track', 'dzsap') . '</div>
' . DZSHelpers::generate_select('0-0-' . $lab, array('class' => 'textinput  styleme', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"', 'options' => array('off','on') )) .'
        </div>';


        $lab = 'download_custom_link';
        $val = '';

        if(isset($margs[$lab])){

            $val = $margs[$lab];
        }

        $aux.='<div class="setting type_all">
            <div class="setting-label">' . __('Custom Link Download', 'dzsap') . '</div>
            <div class="sidenote">' . __('a custom link for the download button - clicknig it will go to this link if set, if it is not set then it will just download the track', 'dzsap') . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . '
        </div>';


        $lab = 'songname';
        $val = '';

        if(isset($margs[$lab])){

            $val = $margs[$lab];
        }

        $aux.='<div class="setting type_all">
            <div class="setting-label">' . __('Song name', 'dzsap') . '</div>
            <div class="sidenote">' . wp_kses(sprintf(__('leave blank and zoomsounds will try to auto generate song name from mp3 id3 or from attached file meta. Or you can input %s to force no song name in the player', 'dzsap'), '<strong>none</strong>'), $this->allowed_tags) . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . '
        </div>';




        $aux.='<br>';
        $aux.='<div class="dzstoggle toggle1" rel="">
        <div class="toggle-title" style="">' . __('Extra HTML Options', 'dzsap') . '</div>
        <div class="toggle-content" style="z-index:5;">';

        $aux.='<img src="https://lh3.googleusercontent.com/JY9Q72y_Wkx4Au0Ijxjf2GCZUblfYbpyjooMaSt90XG9zOjd7vlddxLJTTX7C2UEV5TqBKBsSaFw3Pr8Psafl8XvzWMOzFaxJfndci9idgqFHSnEw9rd5K92tQyAiVqxPO30qznMwqIjIHQTm2hijSLM2S9OqVinEP_TGoKhtmgrCro7NmsNn0-T4N_Mmn3htOFy4o4mMZciif-zVcQ6T0HTB4n2xzI49Sn_s08ekF8DFwcE58n8Dp5LGfQpUeI8nfK8LSv4mKC1TKiewKkOm-YwGy3bhC8BFRsUXBDHd-YtX0y7HV7SfIg9hvA4QRJHBUQPod5YrDIODH7YLQi7HVIceBwyaYPvTAZEZh5oifrCCj61sSZztfjra-WbcxoRoUVrZSssvxLR1lJgH8WpnxdV-1qmDAr-0p7LKhdJM2_4P79SIOIKuYOWaDyx7GQ8CAjco--fhiwbYCxqgCXyGtRjpGYJV6IEKh7UhwEsNnkUAxWB-YoQrtFgoB3Rw4uFRdQCs--YHTeydLCEaAEL5CNwd6j0hh1UDunj1Xj7bmc=w736-h291-no"/>';

        //textarea simple
        $lab = 'extra_html';
        $val = $margs[$lab];


        $aux.='
       <div class="setting type_all">
                <div class="setting-label">' . __('Extra HTML', 'dzsap') . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
                <div class="sidenote">' . __('(1) extra html you may want underneath item', 'dzsap') . '</div>
</div>';




        $lab = 'extra_html_left';
        $val = $margs[$lab];


        $aux.='
       <div class="setting type_all">
                <div class="setting-label">' . __('Extra HTML to the Left', 'dzsap') . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
                <div class="sidenote">' . __('(2) extra html placed in the left of Like button', 'dzsap') . '</div>
</div>';




        $lab = 'extra_html_in_controls_left';
        $val = $margs[$lab];


        $aux.='
       <div class="setting type_all">
                <div class="setting-label">' . __('Extra HTML in Left Controls', 'dzsap') . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
                <div class="sidenote">' . __('(3) extra html placed in the player&quot;s ', 'dzsap') . '</div>
</div>';


        $lab = 'extra_html_in_controls_right';
        $val = $margs[$lab];


        $aux.='
       <div class="setting type_all">
                <div class="setting-label">' . __('Extra HTML in Right Controls', 'dzsap') . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
                <div class="sidenote">' . __('(3) extra html placed in the player&quot;s ', 'dzsap') . '</div>
</div>';


        $aux.='</div>
        </div>';



        $aux.='<div class="dzstoggle toggle1" rel="">
        <div class="toggle-title" style="">' . __('Menu Options', 'dzsap') . '</div>
        <div class="toggle-content">';


        //textarea simple
        $lab = 'menu_artistname';
        $val = $margs[$lab];


        $aux.='
       <div class="setting type_all">
                <div class="setting-label">' . __('Artist Name', 'dzsap') . '</div>
                <div class="sidenote">' . __('an artist name if you include this item in a playlist', 'dzsap') . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
</div>';


        //textarea simple
        $lab = 'menu_songname';
        $val = $margs[$lab];


        $aux.='
       <div class="setting type_all">
                <div class="setting-label">' . __('Song Name', 'dzsap') . '</div>
                <div class="sidenote">' . __('a song name', 'dzsap') . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
</div>';
        //textarea simple
        $lab = 'menu_extrahtml';
        $val = $margs[$lab];


        $aux.='
       <div class="setting type_all">
                <div class="setting-label">' . __('Extra HTML', 'dzsap') . '</div>
                <div class="sidenote">' . __('extra html you may want in the menu item', 'dzsap') . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
</div>';







        $aux.='</div>
        </div>';




        if ($margs['generator_type'] != 'onlyitems') {
            $aux.='</div><!--end item-settings-con-->
</div>';
        }





        return $aux;
    }

    function handle_admin_footer() {


        if(isset($_GET['taxonomy']) && $_GET['taxonomy']=='dzsap_sliders'){


            echo '<script>';
            echo 'jQuery(document).ready(function($){';

            echo '$("#toplevel_page_dzsap_menu, #toplevel_page_dzsap_menu > a").addClass("wp-has-current-submenu");';
            echo '$("#toplevel_page_dzsap_menu .wp-first-item").addClass("current");';
            echo '$("#menu-posts-dzsap_items, #menu-posts-dzsap_items>a").removeClass("wp-has-current-submenu wp-menu-open");';
            echo '});';
            echo '</script>';

        }
    }

	function mysql_get_track_activity($track_id, $pargs = array()){



		// -- get last ON for interval training

		$margs = array(
			'get_last'=>'off',
			'call_from'=>'default',
			'interval'=>'24',
			'type'=>'view',
			'table'=>'detect',
			'day_start'=>'3',
			'day_end'=>'2',
			'get_count'=>'off',
		);

		if($pargs){
			$margs = array_merge($margs, $pargs);
		}


		global $wpdb;
		$table_name = $wpdb->prefix.'dzsap_activity';


		$format_track_id = 'id_video';



		$margs['table']=$table_name;

		$query = "SELECT ";


		if($margs['get_count']=='on'){

			$query.='COUNT(*)';
		}else{

			$query.='*';
		}

		$query.=" FROM `".$margs['table']."` WHERE `".$format_track_id."` = '".$track_id;


		if(strpos($margs['type'], '%')!==false){

			$query.="' AND type LIKE '".$margs['type']."'";
		}else{

			$query.="' AND type='".$margs['type']."'";
		}


		if($margs['get_last']=='on'){
			$query.=' AND date > DATE_SUB(NOW(), INTERVAL '.$margs['interval'].' HOUR)';
		}

		if($margs['get_last']=='day'){
			$query.=' AND date BETWEEN DATE_SUB(NOW(), INTERVAL '.$margs['day_start'].' DAY)
    AND DATE_SUB(NOW(), INTERVAL  '.$margs['day_end'].' DAY)';

//            echo ' query - '.$query;
		}

		// -- interval start / end


//        echo 'query - '.$query."\n"."\n";


		if(isset($margs['id_user'])){
			$query.=' AND id_user=\''.$margs['id_user'].'\'';
		}






		$results = $GLOBALS['wpdb']->get_results( $query, OBJECT );



		$finalval = 0;
		if(is_array($results) && count($results)>0){


			if($margs['get_count']=='on'){


				if(isset($results[0])){
					$results[0] = (array)$results[0];

//				    print_rr($results);
					return $results[0]['COUNT(*)'];

				}
			}else{

				if($margs['call_from']=='debug'){

					error_log(print_rr($results,true));
				}
				foreach($results as $lab => $aux2){
					$results[$lab] = (array)$results[$lab];

					$finalval+=$results[$lab]['val'];
				}
			}


		}


		return $finalval;


	}


	function wp_dashboard_setup() {

		wp_add_dashboard_widget('dzsap_dashboard_analytics', // Widget slug.
			'ZoomSounds Analytics', // Title.
			'dzsap_analytics_dashboard_content'

		);
	}
//    function wp_dashboard_setup() {
//
//        wp_add_dashboard_widget(
//            'dzsap_analytics_dashboard_content', // Widget slug.
//            'ZoomSounds Comments Statistic', // Title.
//            array($this, 'dashboard_comments_display') // Display function.
//        );
//    }

    public static function sort_commnr($a, $b) {
        $key = 'commnr';
        return $b[$key] - $a[$key];
    }

    function dashboard_comments_display() {

//	echo "Hello World, I'm a great Dashboard Widget";

        $type = 'attachement';
        $args = array(
            'post_type' => 'attachment',
            'numberposts' => null,
            'posts_per_page' => '-1',
            'post_mime_type' => 'audio',
            'post_status' => null
        );
        $attachments = get_posts($args);

        $arr_attcomms = array();
        foreach ($attachments as $att) {
            $comments_count = wp_count_comments($att->ID);
            $aux = array('id' => $att->ID, 'commnr' => ($comments_count->approved));
            array_push($arr_attcomms, $aux);
        }
        //print_r($arr_attcomms);



        usort($arr_attcomms, array('DZSAudioPlayer', 'sort_commnr'));

//        print_r($arr_attcomms);


        echo '<div id="chart_div"></div>';
        //print_r($arr_attcomms);



        echo '<script type="text/javascript">
      google.load("visualization", "1.0", {"packages":["corechart"]});


      google.setOnLoadCallback(drawChart);


      function drawChart() {
      
        var data = new google.visualization.DataTable();
        data.addColumn("string", "Topping");
        data.addColumn("number", "Slices");
        data.addRows([';
        $i = 0;
        foreach ($arr_attcomms as $att) {
            echo '';
//            ['Mushrooms', 3],
            $auxpo = get_post($att['id']);
//            print_r($aux);

            if ($i > 0) {
                echo ',';
            }
            echo '["' . $auxpo->post_title . '", ' . $att['commnr'] . ']';
            $i++;
            //echo 'Track <strong>'.$att['id'].'</strong>, '.$auxpo->post_title.' - '.$att['commnr'].' comments<br/>';
        };

        echo ']);


var options = {"title":"' . __('Number of Comments', 'dzsap') . '",
               "width":"100%",
               "height":300};

var chart = new google.visualization.PieChart(document.getElementById("chart_div"));
chart.draw(data, options);
}
</script>';
    }

    function handle_wp_head() {
        echo '<script>';
        echo 'window.ajaxurl="' . admin_url('admin-ajax.php') . '";';
        echo 'window.dzsap_curr_user="' . get_current_user_id() . '";';
        echo 'window.dzsap_settings= { dzsap_site_url: "' . site_url() . '/",wpurl: "' . site_url() . '/",version: "' . DZSAP_VERSION . '",ajax_url: "' . admin_url('admin-ajax.php') . '", debug_mode:"'.$this->mainoptions['debug_mode'].'" ';


//        echo ' $this->mainoptions - '; print_rr($this->mainoptions);
        $lab = 'dzsaap_default_portal_upload_type';
        if($this->mainoptions[$lab] && $this->mainoptions[$lab]!='audio'){

            echo ','.$lab.':"'.$this->mainoptions[$lab].'"';
        }

	echo '}; ';


        if($this->mainoptions['keyboard_show_tooltips']!='off'||
           $this->mainoptions['keyboard_play_trigger_step_back']!='off'||
           $this->mainoptions['keyboard_step_back_amount']!='5'||
           $this->mainoptions['keyboard_step_back']!='37'||
           $this->mainoptions['keyboard_step_forward']!='39'||
           $this->mainoptions['keyboard_sync_players_goto_prev']!=''||
           $this->mainoptions['keyboard_sync_players_goto_next']!=''||
           $this->mainoptions['keyboard_pause_play']!='32'
        ){

            echo 'window.dzsap_keyboard_controls = {
\'play_trigger_step_back\':\''.$this->mainoptions['keyboard_play_trigger_step_back'].'\'
,\'step_back_amount\':\''.$this->mainoptions['keyboard_step_back_amount'].'\'
,\'step_back\':\''.$this->mainoptions['keyboard_step_back'].'\'
,\'step_forward\':\''.$this->mainoptions['keyboard_step_forward'].'\'
,\'sync_players_goto_prev\':\''.$this->mainoptions['keyboard_sync_players_goto_prev'].'\'
,\'sync_players_goto_next\':\''.$this->mainoptions['keyboard_sync_players_goto_next'].'\'
,\'pause_play\':\''.$this->mainoptions['keyboard_pause_play'].'\'
,\'show_tooltips\':\''.$this->mainoptions['keyboard_show_tooltips'].'\'
}';
        }

        echo '</script>';

	    echo '<style class="dzsap-extrastyling">.feed-dzsap{ display:none; }';
        if ($this->mainoptions['extra_css']) {
            echo $this->mainoptions['extra_css'];
        }
	    echo '</style>';


//        echo 'is_tax - '.;


		    if ( is_tax($this->taxname_sliders) || ( $this->mainoptions['single_index_seo_disable']=='on' && is_singular( 'dzsap_items' ) ) ) {
			    echo '<meta name="robots" content="noindex, follow">';
		    }

//        print_rr($post);
        if($this->mainoptions['replace_powerpress_plugin']=='on'){

            global $post;

            if($post){
                if($post->ID!='4812' && $post->ID!='23950'){
//                if($post->ID!='23950'){
                    $this->mainoptions['replace_powerpress_plugin']='off';
//                    echo "CEVA";
                }
            }

        }



        if(isset($_GET['action'])) {
            if ($_GET['action'] == 'embed_zoomsounds') {

                // -- embedded css
                ?>
                <style>
                    html, body {
                        background-color: transparent;
                    }
                    body > * {
                        display: none !important;
                    }
                    body > .dzsap-main-con {
                        display: block !important;
                    }

                    body .zoomsounds-embed-con {
                        display: block !important;
                        position: fixed;
                        top:0;
                        left:0;
                        width: 100%;
                    }
                </style><?php

            }
        }


                if (isset($_GET['dzsap_generate_pcm']) && $_GET['dzsap_generate_pcm']) {




            $id = $this->clean($_GET['dzsap_generate_pcm']);

	                $lab = 'dzsap_pcm_data_'.($id);


//        update_option("dzsap_ceva", "ceva");
//        update_option($lab, "ceva");

	                update_option($lab, '');

	                $source = $this->get_track_source($id);


	                if($source){

		                $lab = 'dzsap_pcm_data_'.($this->clean($source));
		                update_option($lab, '');
                    }
//	                echo 'source - '.$source;


            ?>
            <style>
                html{
                    margin-top:0!important;
                }
                body > *{
                    opacity: 0;
                    display: none;
                }
                body > #ap_regenerate{
                    opacity: 1;
                    display: block;
                }
            </style>
            <script>
                jQuery(document).ready(function($){
                    var aux = '';

                    $('body').addClass('dzsap-ready');

                    $('body').prepend('<div id="ap_regenerate" data-type="audio" class="audioplayer-tobe skin-wave " data-source="<?php echo $_GET['dzsap_source']; ?>" data-playerid="<?php echo $_GET['dzsap_generate_pcm']; ?>" data-playfrom="0"> </div>');

                    // -- waveform regeneration

                    setTimeout(function(){
                        dzsap_init(".audioplayer-tobe", {
                            autoplay: "off"
                            ,skinwave_mode: 'normal'
                            ,settings_php_handler: window.ajaxurl // -- the path of the publisher.php file, this is used to handle comments, likes etc.
                            ,skinwave_wave_mode: 'canvas' // --- "normal" or "canvas"
                            ,skinwave_wave_mode_canvas_waves_number: '3' // --- the number of waves in the canvas
                            ,skinwave_wave_mode_canvas_waves_padding: '1' // --- padding between waves
                            ,skinwave_wave_mode_canvas_reflection_size: '0.25' // --- the reflection size
                            ,pcm_data_try_to_generate: 'on' // --- try to find out the pcm data and sent it via ajax ( maybe send it via php_handler
                            ,skinwave_comments_enable: 'off' // -- enable the comments, publisher.php must be in the same folder as this html, also if you want the comments to automatically be taken from the database remember to set skinwave_comments_retrievefromajax to ON
                            ,failsafe_repair_media_element: 500 // == light or full
                            ,settings_extrahtml_in_float_right: '<div class="orange-button dzstooltip-con" style="top:10px;"><span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black align-right" style="width: auto; white-space: nowrap;">Add to Cart</span><i class="fa fa-shopping-cart"></i></div><div class="orange-button dzstooltip-con" style="top:10px;"><span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black align-right" style="width: auto; white-space: nowrap;">Download</span><i class="fa fa-download"></i></div>'
                        })
                    })

//        $('body').children().css('display','none');
                });
                //    jQuery('body').children().css('display','none');
                console.info('regenerate waves');
            </script>


            <?php
            wp_enqueue_script('dzsap', $this->base_url . "audioplayer/audioplayer.js");
            wp_enqueue_style('dzsap', $this->base_url . 'audioplayer/audioplayer.css');
        }
    }

    function ajax_get_thumb_from_meta() {

        //print_r($_POST);


//        echo 'hmm';

        $pid = $_POST['postdata'];



//        print_r($file);

//        print_r($metadata);


        if(get_post_meta($pid, '_dzsap-thumb',true)){

            echo get_post_meta($pid, '_dzsap-thumb',true);
        }else{





            $upload_dir = wp_upload_dir();
            $upload_dir_url = $upload_dir['url'].'/';
            $upload_dir_path = $upload_dir['path'].'/';


//            print_r($upload_dir);





            $file = get_attached_file($pid);
            $metadata = wp_read_audio_metadata( $file );
//            echo 'image data - ';
            if(isset($metadata['image']) && $metadata['image']['data']){
//                echo base64_encode($metadata['image']['data']);


                file_put_contents($upload_dir_path.'audio_image_'.$pid.'.jpg', $metadata['image']['data']);


                echo $upload_dir_url.'audio_image_'.$pid.'.jpg';

            }
        }



//        $meta = wp_get_attachment_metadata($_POST['postdata']);

//        print_r($meta);


        die();
    }

    function ajax_front_submitcomment() {

        //print_r($_POST);

        $time = current_time('mysql');

        $playerid = $_POST['playerid'];
        $playerid = str_replace('ap', '', $playerid);

        $email = '';
        $comm_author = $_POST['skinwave_comments_account'];


        $user_id = get_current_user_id();
        $user_data = get_userdata($user_id);

//        print_r($user_data);

        if(isset($user_data->data)){

            if(isset($user_data->data->ID)){
                $email = $user_data->data->user_email;
                $comm_author = $user_data->data->user_login;
            }
        }


        $data = array(
            'comment_post_ID' => $playerid,
            'comment_author' => $comm_author,
            'comment_author_email' => $email,
            'comment_author_url' => $_POST['comm_position'],
            'comment_content' => $_POST['postdata'],
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => 1,
            'comment_author_IP' => '127.0.0.1',
            'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
            'comment_date' => $time,
            'comment_approved' => 1,
        );

        wp_insert_comment($data);


        setcookie("commentsubmitted-" . $playerid, '1', time() + 36000, COOKIEPATH);

        print_r($data);

        echo 'success';
        die();
    }
    function ajax_delete_pcm() {

        //print_r($_POST);


        $playerid = $_POST['playerid'];



        $lab = 'dzsap_pcm_data_'.($this->clean($_POST['playerid']));


        delete_option($lab);

	    if(isset($_POST['track_src'])){

		    $lab = 'dzsap_pcm_data_'.($this->clean($_POST['track_src']));


		    delete_option($lab);
	    }
        echo 'success - '.$lab;
        die();
    }
    function ajax_send_queue_from_sliders_admin() {

//        print_r($_POST);

        $response = array(
                'report'=>'success',
                'items'=>array(),
        );

        $queue_calls = json_decode(stripslashes($_POST['postdata']), true);

//        error_log('$queue_calls - '.print_r($queue_calls,true));

        foreach ($queue_calls as $qc){

            if($qc['type']=='set_meta_order'){
                foreach($qc['items'] as $it){

                    update_post_meta($it['id'], 'dzsap_meta_order_'.$qc['term_id'],$it['order']);
                }
            }
            if($qc['type']=='set_meta'){

                if($qc['lab']=='the_post_title'){



                    $aferent_lab = $qc['lab'];

                    if($qc['lab']){
                        $aferent_lab = 'post_title';
                    }

	                $my_post = array(
		                'ID'           => $qc['item_id'],
		                $aferent_lab   => $qc['val'],

	                );

// Update the post into the database
	                wp_update_post( $my_post );
                }else{

	                update_post_meta($qc['item_id'], $qc['lab'], $qc['val']);
                }

            }
            if($qc['type']=='delete_item'){


                $post_id = $qc['id'];


	            $term_list = wp_get_post_terms($post_id, $this->taxname_sliders, array("fields" => "all"));


	            $response['report_type']='delete_item';
	            $response['report_message']=esc_html__("Item deleted",'dzsap');



	            if(is_array($term_list) && count($term_list)==1){

	                wp_delete_post($post_id);
                }else{
		            wp_remove_object_terms( $post_id, $qc['term_slug'], $this->taxname_sliders );
                }

            }
            if($qc['type']=='create_item'){

//                print_r($qc);



                $taxonomy = $this->taxname_sliders;



	            $current_user = wp_get_current_user();
	            $new_post_author_id = $current_user->ID;


                $args = array(
                    'post_title' => __("Insert Name",'dzsap'),
                    'post_content' => 'content here',
                    'post_status' => 'publish',
                    'post_author' => $new_post_author_id,
                    'post_type' => 'dzsap_items',
                );
	            //  default_zoomsounds_item_settings


	            if(isset($qc['term_slug']) && $qc['term_slug']){

	                $title = substr($qc['term_slug'],0,4);


		            if(isset($qc['dzsap_meta_order_'.$qc['term_id']])){
			            $title.=$qc['dzsap_meta_order_'.$qc['term_id']];
		            }

		            $args['post_title']=$title;

	            }


	            if($this->mainoptions['try_to_get_id3_thumb_in_frontend']=='on'){

	                $title = '';
		            $args['post_title']=$title;
                }


	            // -- search for default




                error_log('post - '.print_r($_POST,true));
                error_log('$qc - '.print_r($qc,true));





	            $the_slug = 'default_zoomsounds_item_settings';
	            $the_slug_term = '';

                if(isset($qc['term_slug']) && $qc['term_slug']){
	                $the_slug_term .= $the_slug.'_'.$qc['term_slug'];
                }


	            $args4 = array(
		            'name'        => $the_slug,
		            'post_type'   => 'dzsap_items',
		            'post_status' => 'any',
		            'numberposts' => 1
	            );
	            $my_posts = get_posts($args4);
	            if( $my_posts ){
		            $args = array_merge($args,$this->sanitize_to_gallery_item( $my_posts[0] ));

		            error_log("FOUND DEFAULT, new args - ".print_r($args,true));
	            }


	            error_log('$the_slug_term - '.$the_slug_term);
	            if($the_slug_term){

		            $args4 = array(
			            'name'        => $the_slug_term,
			            'post_type'   => 'dzsap_items',
			            'post_status' => 'any',
			            'numberposts' => 1
		            );
		            $my_posts = get_posts($args4);
		            if( $my_posts ){
			            $args = array_merge($args,$this->sanitize_to_gallery_item( $my_posts[0] ));

			            error_log("FOUND DEFAULT .. for term, new args - ".print_r($args,true));
		            }

	            }

	            // -- end search for default




	            if(isset($qc['post_title']) && $qc['post_title']){
		            $args['post_title']= $qc['post_title'];

	            }



//                $new_created_item = wp_insert_post($args);





	            error_log("prepare args - ".print_r($args,true));

	            $args['call_from']='send queue from sliders admin';
	            $new_created_item = $this->import_demo_insert_post_complete($args);

                if(isset($qc['term_slug']) && $qc['term_slug']){
	                wp_set_post_terms( $new_created_item, dzs_sanitize_for_post_terms($qc['term_slug']), $taxonomy );

                }








                foreach ($qc as $lab=>$val){
                    if(strpos($lab,'dzsap_meta')===0){
                        update_post_meta($new_created_item,$lab,$val);
                    }
                }

//        wp_set_post_terms($new_created_item,$arr_cats[0],$taxonomy);

                array_push($response['items'],array(
                    'type'=>'create_item',
                    'str'=>$this->sliders_admin_generate_item(get_post($new_created_item)),
                ));
            }




	        if($qc['type']=='duplicate_item'){

//                print_r($qc);





		        $reference_po_id = ($qc['id']);

		        $sample_post_2_id = $this->duplicate_post($reference_po_id);


//        wp_set_post_terms($sample_post_2_id,$arr_cats[0],$taxonomy);

		        array_push($response['items'],array(
			        'type'=>'create_item',
			        'original_request'=>'duplicate_item',
			        'original_post_id'=>$reference_po_id,
			        'str'=>$this->sliders_admin_generate_item(get_post($sample_post_2_id)),
		        ));
	        }
        }

        echo json_encode($response);
        die();
    }

    function duplicate_post($reference_po_id, $pargs=array()){


        $margs = array(
          'new_term_slug'=>'',
          'call_from'=>'default',
          'new_tax'=>'dzsap_sliders',
        );

        $margs = array_merge($margs,$pargs);

	    $reference_po = get_post($reference_po_id);




	    $current_user = wp_get_current_user();
	    $new_post_author_id = $current_user->ID;

	    $args = array(
		    'post_title' => $reference_po->post_title,
		    'post_content' => $reference_po->post_content,
		    'post_status' => 'publish',
		    'post_author' => $new_post_author_id,
		    'post_type' => $reference_po->post_type,
	    );


	    $sample_post_2_id = wp_insert_post($args);




	    /*
		 * get all current post terms ad set them to the new post draft
		 */
	    $taxonomies = get_object_taxonomies($reference_po->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
	    foreach ($taxonomies as $taxonomy) {
		    if($margs['new_term_slug']){
		        if($taxonomy=='dzsap_sliders'){
		            continue;
                }
		    }
		    $post_terms = wp_get_object_terms($reference_po_id, $taxonomy, array('fields' => 'slugs'));
		    wp_set_object_terms($sample_post_2_id, $post_terms, $taxonomy, false);
	    }


	    // -- for duplicate term
	    if($margs['new_term_slug']){

		    wp_set_object_terms($sample_post_2_id, $margs['new_term_slug'], $margs['new_tax'], false);
        }else{

        }




	    /*
		 * duplicate all post meta just in two SQL queries
		 */
	    global $wpdb;
	    $sql_query_sel = array();
	    $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$reference_po_id");
	    if (count($post_meta_infos)!=0) {
		    $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
		    foreach ($post_meta_infos as $meta_info) {
			    $meta_key = $meta_info->meta_key;
			    if( $meta_key == '_wp_old_slug' ) continue;
			    $meta_value = addslashes($meta_info->meta_value);
			    $sql_query_sel[]= "SELECT $sample_post_2_id, '$meta_key', '$meta_value'";
		    }
		    $sql_query.= implode(" UNION ALL ", $sql_query_sel);
		    $wpdb->query($sql_query);
	    }

	    return $sample_post_2_id;
    }


    function sliders_admin_generate_item_meta_cat($cat, $po, $pargs=array()){




	    $margs = array(

		    'for_shortcode_generator'=>false,
		    'for_item_meta'=>false,
	    );

	    $margs = array_merge($margs, $pargs);

        $fout = '';
	    // -- we need real location, not insert-id
	    $struct_uploader = '<div class="dzs-wordpress-uploader ">
    <a href="#" class="button-secondary">' . __('Upload', 'dzsvp') . '</a>
</div>';


	    // -- generate item category ( for sliders admin )
	    foreach ($this->options_item_meta as $lab => $oim){



	        $oim = array_merge(array(
	                'category'=>'',
	                'no_preview'=>'',
	                'it_is_for'=>'item_meta',
            ), $oim);


	        // -- some sanitizing
	        if($oim['type']=='image'){
	            $oim['type']='attach';
            }



	        if(isset($oim['options'])){
	            if(isset($oim['choices'])==false){
		            $oim['choices'] = $oim['options'];
                }
            }

	        if($oim['category']==$cat){

            }else{
	            if($cat=='main'){
	                if($oim['category']==''){


                    }else{
	                    continue;
                    }
                }else{
		            continue;
	            }
            }

            if($oim['it_is_for']=='shortcode_generator'){




//	            print_rr($margs);
	            if($margs['for_shortcode_generator']==false){
	                continue;
                }
            }

            if($oim['it_is_for']=='for_item_meta_only'){




//	            print_rr($margs);
	            if($margs['for_item_meta']==false){
	                continue;
                }
            }


		    if($oim['type']=='dzs_row'){
			    $fout.='<section class="dzs-row">';
			    continue;

		    }
		    if($oim['type']=='dzs_col_md_6'){
			    $fout.='<section class="dzs-col-md-6">';
			    continue;

		    }
		    if($oim['type']=='dzs_col_md_12'){
			    $fout.='<section class="dzs-col-md-6">';
			    continue;

		    }

		    if($oim['type']=='dzs_row_end'){
			    $fout.='</section><!--dzs row end-->';
			    continue;

		    }
		    if($oim['type']=='dzs_col_md_6_end'){
			    $fout.='</section><!--dzs dzs_col_md_6_end end-->';
			    continue;

		    }
		    if($oim['type']=='dzs_col_md_12_end'){
			    $fout.='</section><!--dzs dzs_col_md_12_end end-->';
			    continue;

		    }
		    if($oim['type']=='custom_html'){
			    $fout.=$oim['custom_html'];
			    continue;

		    }


		    $fout.='
                    <div class="setting ';
		    $option_name = $oim['name'];

		    if($oim['type']=='attach'){
			    $fout.=' setting-upload';
		    }

		    $fout.='" ';

		    if(isset($oim['dependency']) && $oim['dependency']){
		        $fout.=' data-dependency=\''.json_encode($oim['dependency']).'\'';
            }



		    $fout.='>';



//		    print_rr($margs);
//		    error_log('add attachment id');
		    if((strpos($option_name,'item_source') || $option_name=='source') ){

//		        error_log('add attachment id');

			    $lab_aux = 'dzsap_meta_source_attachment_id';
			    $val_aux = '';
			    if($po){

				    $val_aux = get_post_meta($po->ID, $lab_aux, true);
                }


                $class='setting-field shortcode-field';

			    if($margs['for_shortcode_generator']){
			        $class.=' insert-id';
                }

			    $fout.= DZSHelpers::generate_input_text($lab_aux, array(
				    'class' => $class,
				    'seekval' => $val_aux,
				    'input_type' => 'hidden',
			    ));
		    }

		    $fout.='<h5 class="setting-label">'.$oim['title'].'</h5>';


		    if($oim['type']=='attach'){


                if($oim['no_preview']!='on') {
                    $fout .= '<span class="uploader-preview"></span>';
                }
		    }


		    if($margs['for_shortcode_generator']){
		        $option_name = str_replace('dzsap_meta_item_','',$option_name);
		        $option_name = str_replace('dzsap_meta_','',$option_name);

		        if($option_name=='the_post_title'){
		            $option_name='songname';
                }
            }

            $extraattr_input = '';

		    if(isset($oim['extraattr_input']) && $oim['extraattr_input']){
		        $extraattr_input.= $oim['extraattr_input'];
            }

		    $val = '';

		    if($po && is_int($po->ID)){

			    $val = get_post_meta($po->ID, $option_name, true);
		    }

		    if($po && $option_name=='the_post_title'){
			    $val = $po->post_title;
		    }

		    $class = 'setting-field medium';

		    if($oim['type']=='attach'){
			    $class.=' uploader-target';
		    }

		    if($margs['for_shortcode_generator']){
		        $class.=' shortcode-field';
            }

		    if($oim['type']=='attach') {


			    if(isset($oim['upload_type']) && $oim['upload_type']){
				    $class.=' upload-type-'.$oim['upload_type'];
			    }
			    $class='setting-field shortcode-field';

			    if($option_name=='source' && $margs['for_shortcode_generator']){
				    $class.=' insert-id';
			    }

			    $fout.= DZSHelpers::generate_input_text($option_name, array(
				    'class' => $class,
				    'seekval' => $val,
				    'extraattr' => $extraattr_input,
			    ));
		    }
		    if($oim['type']=='text') {
			    $fout.= DZSHelpers::generate_input_text($option_name, array(
				    'class' => $class,
				    'seekval' => $val,
				    'extraattr' => $extraattr_input,
			    ));
		    }
		    if($oim['type']=='textarea') {
			    $fout.= DZSHelpers::generate_input_textarea($option_name, array(
				    'class' => $class,
				    'seekval' => $val,
				    'extraattr' => $extraattr_input,
			    ));
		    }
		    if($oim['type']=='select') {


		        $class='';

		        if(isset($oim['class'])){
		            $class.=$oim['class'];
                }
			    $class .= ' dzs-style-me skin-beige setting-field';

			    if(isset($oim['select_type']) && $oim['select_type']){
				    $class.=' '.$oim['select_type'];
			    }
			    if($margs['for_shortcode_generator']){
				    $class.=' shortcode-field';
			    }

			    $fout.= DZSHelpers::generate_select($option_name, array(
				    'class' => $class,
				    'seekval' => $val,
				    'options' => $oim['choices'],
				    'extraattr' => $extraattr_input,
			    ));

			    if(isset($oim['select_type']) && $oim['select_type']=='opener-listbuttons'){

				    $fout.= '<ul class="dzs-style-me-feeder">';

				    foreach ($oim['choices_html'] as $oim_html){

					    $fout.= '<li>';
					    $fout.= $oim_html;
					    $fout.= '</li>';
				    }

				    $fout.= '</ul>';
			    }


		    }

		    if($oim['type']=='attach') {




			    $fout.= '<div class="dzs-wordpress-uploader here-uploader ">
<a href="#" class="button-secondary';


			    if(isset($oim['upload_btn_extra_classes']) && $oim['upload_btn_extra_classes']){
				    $fout.= ' '.$oim['upload_btn_extra_classes'];
			    }


			    $fout.= '">' . __('Upload', 'dzsvp') . '</a>
</div>';

//			    $fout.= $struct_uploader;
		    }

		    if(isset($oim['sidenote']) && $oim['sidenote']){
			    $fout.= '<div class="sidenote">'.$oim['sidenote'].'</div>';
		    }



                if(isset($oim['sidenote-2']) && $oim['sidenote-2']){


                    $sidenote_2_class = '';

                    if(isset($oim['sidenote-2-class'])){
                        $sidenote_2_class = $oim['sidenote-2-class'];
                    }
                    $fout.='<div class="sidenote-2 '. $sidenote_2_class .'">'. $oim['sidenote-2'].'</div>';
                }


		    $fout.='
                    </div>';



	    }


        return $fout;
    }

    function sliders_admin_generate_item($po){


        $fout = '';
        $thumb = '';
        $thumb_from_meta = '';
	    // -- we need real location, not insert-id
	    $struct_uploader = '<div class="dzs-wordpress-uploader ">
    <a href="#" class="button-secondary">' . __('Upload', 'dzsvp') . '</a>
</div>';


	    $po_id = '';
        if($po && is_int($po->ID)){

            $thumb = $this->get_post_thumb_src($po->ID);

	        $po_id = $po->ID;
//            echo ' thumb - ';
//            print_r($thumb);


            $thumb_from_meta = get_post_meta($po->ID, 'dzsap_meta_item_thumb',true);
        }

        if($thumb_from_meta){

            $thumb = $thumb_from_meta;
        }

        $thumb_url = '';
        if($thumb){
            $thumb_url = $this->sanitize_id_to_src($thumb);

//                    echo ' thumb - '.$this->sanitize_id_to_src($thumb);
        }



        if($po_id){

	        $fout.= '<div class="slider-item dzstooltip-con for-click';

	        if($po && $po->ID=='placeholder'){
		        $fout.= ' slider-item--placeholder';
	        }

	        $fout.= '" data-id="'.$po->ID.'">';



//            $fout.='<div class="auxdev-dzs-meta-order" style="display:none; " >'.get_post_meta($po->ID,'dzsap_meta_order_54',true).'</div>';


	        $fout.= '<div class="divimage" style="background-image:url('.$thumb_url.');"></div>';
	        $fout.= '<div class="slider-item--title" >'.$po->post_title.'</div>';

	        $fout.='
        <div class="delete-btn item-control-btn"><i class="fa fa-times-circle-o"></i></div>
        <div class="clone-item-btn item-control-btn"><i class="fa fa-clone"></i></div>
        <div class="dzstooltip dzstooltip-legacy skin-black transition-fade arrow-top align-center">
            <div class="dzstooltip--selector-top"></div>

            <div class="dzstooltip--content">';





	        $fout.='<div class="dzs-tabs dzs-tabs-meta-item  skin-default " data-options=\'{ "design_tabsposition" : "top"
,"design_transition": "fade"
,"design_tabswidth": "default"
,"toggle_breakpoint" : "200"
,"settings_appendWholeContent": "true"
,"toggle_type": "accordion"
}
\' style=\'padding: 0;\'>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu ">'.esc_html__("General",'dzsap').'
    </div>
    <div class="tab-content tab-content-item-meta-cat-main">

'.$this->sliders_admin_generate_item_meta_cat('main', $po).'
    </div>
    </div>
    ';


	        foreach ($this->item_meta_categories_lng as $lab=>$val){


		        ob_start();
		        ?>

                <div class="dzs-tab-tobe">
                <div class="tab-menu ">
			        <?php
			        echo ($val);
			        ?>
                </div>
                <div class="tab-content tab-content-cat-<?php echo $lab; ?>">



			        <?php
			        echo $this->sliders_admin_generate_item_meta_cat($lab, $po);
			        ?>


                </div>
                </div><?php

		        $fout.=ob_get_clean();



	        }

	        $fout.='</div>';// -- end tabs




	        $fout.='
                    </div>';
	        $fout.='
                    </div>';
	        $fout.='
                    </div>';
        }

        return $fout;
    }

    function ajax_submit_download() {

        $aux_likes = 0;
        $playerid = '';

        if (isset($_POST['playerid'])) {
            $playerid = $_POST['playerid'];
            $playerid = str_replace('ap', '', $playerid);
        }

        if (is_numeric($playerid) && get_post_meta($playerid, '_dzsap_downloads', true) != '') {
            $aux_likes = intval(get_post_meta($playerid, '_dzsap_downloads', true));
        }

        if (isset($_COOKIE['downloadsubmitted-' . $playerid])) {

        } else {

        }

        $aux_likes = $aux_likes + 1;



        $this->insert_activity(array(
            'id_video'=>$playerid,
            'type'=>'download',
        ));


        if (is_numeric($playerid)){

            update_post_meta($playerid, '_dzsap_downloads', $aux_likes);
        }



        setcookie("downloadsubmitted-" . $playerid, '1', time() + (intval($this->mainoptions['play_remember_time']) * 60), COOKIEPATH);

        echo 'success';
        die();
    }

    function ajax_submit_views() {

        $aux_likes = 0;
        $playerid = '';

        if (isset($_POST['playerid'])) {
            $playerid = $_POST['playerid'];
            $playerid = str_replace('ap', '', $playerid);
        }

        if (get_post_meta($playerid, '_dzsap_views', true) != '') {
            $aux_likes = intval(get_post_meta($playerid, '_dzsap_views', true));
        }




        $this->analytics_submit_into_table(array(
                'type'=>'view',
        ));

	    echo 'success';

        if (isset($_COOKIE['viewsubmitted-' . $playerid])) {

        } else {
            $aux_likes = $aux_likes + 1;



            $this->insert_activity(array(
                'id_video'=>$playerid,
                'type'=>'view',
            ));

        }





        update_post_meta($playerid, '_dzsap_views', $aux_likes);

        setcookie("viewsubmitted-" . $playerid, '1', time() + (intval($this->mainoptions['play_remember_time']) * 60), COOKIEPATH);

        die();
    }

    function ajax_submit_rate() {

        //print_r($_COOKIE);


        $rate_index = 0;
        $rate_nr = 0;
        $playerid = '';

        if (isset($_POST['playerid'])) {
            $playerid = $_POST['playerid'];
            $playerid = str_replace('ap', '', $playerid);
        }

        if (get_post_meta($playerid, '_dzsap_rate_nr', true) != '') {
            $rate_nr = intval(get_post_meta($playerid, '_dzsap_rate_nr', true));
        }
        if (get_post_meta($playerid, '_dzsap_rate_index', true) != '') {
            $rate_index = intval(get_post_meta($playerid, '_dzsap_rate_index', true));
        }



        if (!isset($_COOKIE['dzsap_ratesubmitted-' . $playerid])) {
            $rate_nr++;
        }

        if ($rate_nr <= 0) {
            $rate_nr = 1;
        }



        $rate_index = ($rate_index * ($rate_nr - 1) + intval($_POST['postdata'])) / ($rate_nr);


        setcookie("dzsap_ratesubmitted-" . $playerid, $_POST['postdata'], time() + 36000, COOKIEPATH);



        update_post_meta($playerid, '_dzsap_rate_index', $rate_index);
        update_post_meta($playerid, '_dzsap_rate_nr', $rate_nr);

        echo json_encode(array(
                'index'=>$rate_index,
                'number'=>$rate_nr,
        ));
        die();
    }
    function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
    function ajax_submit_pcm() {

        //print_r($_COOKIE);

        $lab = '';

        if($_POST['playerid']){

	        $lab = 'dzsap_pcm_data_'.($this->clean($_POST['playerid']));
        }


//        update_option("dzsap_ceva", "ceva");
//        update_option($lab, "ceva");



        if( (isset($_POST['call_from']) && $_POST['call_from']='manual_wave_overwrite') || strpos($_POST['postdata'],',')!==false){

	        $_POST['postdata'] = stripslashes($_POST['postdata']);
	        update_option($lab, $_POST['postdata']);


	        if(isset($_POST['source'])){

		        $lab = 'dzsap_pcm_data_'.($this->clean($_POST['source']));
		        $_POST['postdata'] = stripslashes($_POST['postdata']);
		        update_option($lab, $_POST['postdata']);
	        }
//        echo $lab. ' ';


	        echo 'success';
        }

        die();
    }

    function ajax_submit_sample_tracks() {

        //print_r($_COOKIE);

        $this->sample_data = array(
            'media'=>array(),
        );






        $args = array(
            'post_title' => 'Track 1 from stephaniequinn.com',
            'post_content' => 'stephaniequinn.com',
            'post_status' => 'inherit',
            'post_author' => 1,
            'post_type' => 'attachment',
            'post_mime_type' => 'audio/mpeg',
            'post_name' => 'steph1',
            'guid' => 'http://www.stephaniequinn.com/Music/Commercial%20DEMO%20-%2011.mp3',
        );

        $sample_post_2_id = wp_insert_post($args);
//        wp_set_post_terms($sample_post_2_id,$arr_cats[0],$taxonomy);
        update_post_meta($sample_post_2_id,'_waveformbg','https://lh3.googleusercontent.com/OCkCqtmYpqevOPlhNY4R8oy37CmypYXtsM6CdwstJp-2X8y4O_MdmnOOyTZ2dODVq7sfxLqoRG2H-fGJ8GAwYDp7jtiyyesUiMjIZA4czV7dDqnaw0qhpkRBpfSmqW_uOkQtGvhJUn9nYAK2MQwQ_PtCfl4uHgb1cae5n7qNC8DjRgVorBBr_gZVLg0IZFXbLW0UTp-8KsqrZSyGHAgxbh7Q40-CKFvBKxZ7KblCTfwsEun4LElkYFe5ZPZOsn1EBrxsbXrSyAZVmm0VX7UXRnEQR-5YTIzZ6ttugwYonTFNwmiGxOCsg5RyYpwTNWMLE1v2fBUsBgSStiLrnwQqrK4VAfV-irLXdfXsy6ZG174u0uPdjGJq3qw3PcJUHatmxZDC5PbSrxTHR-K6OqTOV7bM641t40ZVNZfZmjOTzzL-eDWkKCUu5q5VBm254sJ4FK63bP5QbxOQem6nPadxEayRSKfyF4z4HUnoqsR1giPk8eWI63LcgGOZeSWGVw0T27N_Ugwz37Twr5Ilyk7q66elCiyOxK7IUuiur6-QYi0=w1170-h140-no');
        update_post_meta($sample_post_2_id,'_waveformprog','https://lh3.googleusercontent.com/3ZCeepH9HAhs1ojwrMVKRW4poGaqPSbeczAAs8XjBl8E4zh0vSzXY4ou7KtRXUoMDff70qz8vEa5YLwq_4kp4ufRHcTK8_7lbs5Ux4jTETAkhluI75nUweiBYztNkwtxRggzTLnu2kdyVn3lubZGDbe4-pxyvBtz2tWauKs9fw7wiMCcrkFz5BFi_X1q7ViGA205qTfuTLjltWzom09Xm8vgt5EsTHyInFoMAeSobImMrG5j67VTgrX_9vYDNu3RE_TbISRY9c7wdEXOplQZXJDHH3c86rdVaoclhGAbli3mHJ92iZmGrZM1JH0glyj-ymSSq8RU1Tw2Slb1QFYEwzJpr_wOR9BqqccLAf-yLawNG5TqTQLhrYekNfPaWEtUrcYvHMDeg2R_x7zZg0Q_FI4qvUjBrTu8ClZIf_fml4mer7KEl3uhNEDNr7pe9suucRGO_f_whT8bqjFsRCvh9obFhvj0Suvc-SNFTeLavV6EwIqFVYdHCwyedHxdmOGTsruvXw3CRqon0UFb2jqR2GO6ZUSQ9k9emXdGCZAVzqY=w1170-h140-no');
        update_post_meta($sample_post_2_id,'_dzsap-thumb','https://lh3.googleusercontent.com/dF5JBlMfXMsYxXl3pvzmAtkWOhC-aP1rPOpoDHlOSXU1s0tG9XcgfXonQ6Z27jqId77KI3yv9nkbDWVKD3DsHTjoeHfw2PgpH9aoiykmbPXmQ64OKEVn1uJ5gGeiKD1zyPRlHd-yg7wy59wLoUxYpbbJpdf4uiB8Bf7NNo_1VXpyaMGjHRI7BMl5jFyXkJA7H2J5xT3kemlEo7HMUAg7vRDhCBLdvGoyNzZCuzFJ8meA3TLxi8SoQdCn371iv7joSWSfdQH6MCbE9VmCvLnYJIpkPs1PEtYOlbPUnb2UdFEA6kNiJmWnNqjOYxdb2v9mfsggNv8rk5IEazadXCwBqhREiCYvFd0fB3zsx9-zUHASEjWCF-LNFAYHvv8N4ZM7wzeWbRSsSKbxqk2ma7aym_QVc5GqDMQkp1LlEQxMI2zCIACiukehV6DvVOvw5Z1JLLPKL6Gq4kN8oNuS8glcgHzhwIlPBXy1wQ3hz_PU_H2Iu_wZt0eag77YArwha1Av5sINngPyJHu0UI2OrqgQd-7HqiGGuzWUkumAR8UAYQ=s80-no');





        array_push($this->sample_data['media'], $sample_post_2_id);
        $this->sample_data['first_sg_id'] = $sample_post_2_id;






        $args = array(
            'post_title' => 'Track 2 from stephaniequinn.com',
            'post_content' => 'stephaniequinn.com',
            'post_status' => 'inherit',
            'post_author' => 1,
            'post_type' => 'attachment',
            'post_mime_type' => 'audio/mpeg',
            'post_name' => 'steph1',
            'guid' => 'http://www.stephaniequinn.com/Music/Commercial%20DEMO%20-%2001.mp3',
        );

        $sample_post_2_id = wp_insert_post($args);
//        wp_set_post_terms($sample_post_2_id,$arr_cats[0],$taxonomy);
        update_post_meta($sample_post_2_id,'_waveformbg','https://lh3.googleusercontent.com/s_WsedJQkZIRGfooorFv1oZRApVy4FIpYvjP76Kpbo-5leiu1avPr65ElLuMb0bzRQuLeuk8OQnU4pywclzzjIDlZbQaWnCjnOIaQzkk37zyPKSJb-nnY2aov-SavJgFmAN2P6CeBdHI74tJaAOYycRxP7KrCdMdx0vwAixVcYkeJ7zR7Iad5ifaJ-jlBh_7mf97Xro6aVawW9BdxCs006vxrIY0l4QuNvOmBJ3jFcv38qkEeemaMDKxeaYYVPCzr5_ZnfumgK6WFvIrAEjiexlcFK2m5sFXz1c1b0IWyYYAITtYcasVqgAGuCsWTM9ujqR_T0dzWeg_uWOpZNJp2Y04LIsxmqMyCo6bL9mkWly0wLGkwVSpZFSZUKGJ5Vmti94Z6NXeVC4wpb-GOaYk5U3CDbxFDTBqXA3Gi5RT7mocTG3N4ZOR2gaIb530e0to6K2rMUixSqSvfOvfqV-vfsU4AZGs_NGF5-z5bFHioCTSXtmcNfl1CQn7HZnUqbdjE90R-vvvcI0SlYp6x9VCOhWof958SJzAGQSXmubbA-Q=w1170-h140-no');
        update_post_meta($sample_post_2_id,'_waveformprog','https://lh3.googleusercontent.com/Xl5bEyPhd4Rin99rRZg8vwj7XRuee4ED9d_FGas4ayh8G_VlZFtRUlfPYozrHduEKdhiW2AgEELjpbCubLhZbUZaFUaBNgwVbkVYtlDBvs1EI78hnDsgUozzltwIAypfe6OlgZn7nyUiYtDTG4iMBgBLLFX1CeN9LDmmB3EQO4d820eyIn0xz9ba9UEERq9ILzC2QkkWeCZQXS5zElaTXOLAVlZh2qgRbNkFNMjiQfCXuLbPizNKagbixAMXqiqOD-Z_vS7JklaeW2LuYHyrtp5MVW92NgHERk_P01N04CS2-dxc0ufYpo-vAenz6s2EVxHi292aRvC95alzGIT0_B30p5Cs_9yw_06fsypf3XTPd6ZqVgW2pdGxYOMk8Kwg_2IMEjULUkf9WSoVBarxAetG0hsfIVT9KVwsZBuER9dcXmLZpndLyH6wHejzIXb6FueuTZdWpw5_opTqqxQpLEM27V9J1hLJFyCcAcysVEVZkB-m5viDePPL1WqwFebBoOETjc4OIhh8Zs-dVeZNQSMI8nzH2d9kP3w6ocm-8HQ=w1170-h140-no');
        update_post_meta($sample_post_2_id,'_dzsap-thumb','https://lh3.googleusercontent.com/dF5JBlMfXMsYxXl3pvzmAtkWOhC-aP1rPOpoDHlOSXU1s0tG9XcgfXonQ6Z27jqId77KI3yv9nkbDWVKD3DsHTjoeHfw2PgpH9aoiykmbPXmQ64OKEVn1uJ5gGeiKD1zyPRlHd-yg7wy59wLoUxYpbbJpdf4uiB8Bf7NNo_1VXpyaMGjHRI7BMl5jFyXkJA7H2J5xT3kemlEo7HMUAg7vRDhCBLdvGoyNzZCuzFJ8meA3TLxi8SoQdCn371iv7joSWSfdQH6MCbE9VmCvLnYJIpkPs1PEtYOlbPUnb2UdFEA6kNiJmWnNqjOYxdb2v9mfsggNv8rk5IEazadXCwBqhREiCYvFd0fB3zsx9-zUHASEjWCF-LNFAYHvv8N4ZM7wzeWbRSsSKbxqk2ma7aym_QVc5GqDMQkp1LlEQxMI2zCIACiukehV6DvVOvw5Z1JLLPKL6Gq4kN8oNuS8glcgHzhwIlPBXy1wQ3hz_PU_H2Iu_wZt0eag77YArwha1Av5sINngPyJHu0UI2OrqgQd-7HqiGGuzWUkumAR8UAYQ=s80-no');


        array_push($this->sample_data['media'], $sample_post_2_id);



        $time = current_time('mysql');

        $playerid = $sample_post_2_id;
//        $playerid = str_replace('ap', '', $playerid);


        $data = array(
            'comment_post_ID' => $playerid,
            'comment_author' => 'admin',
            'comment_author_email' => 'admin@admin.com',
            'comment_author_url' => 'http://',
            'comment_content' => '<span class="dzstooltip-con" style="left:37.66387884267631%"><span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black talign-start style-rounded color-dark-light" style="width: 250px;"><span class="dzstooltip--inner"><span class="the-comment-author">@admin</span> says:<br>test</span><span class="the-avatar" style="background-image: url(http://1.gravatar.com/avatar/12d1738b0f28c211e5fd5ae066e631a1?s=20&#038;d=mm&#038;r=g)"></span></span></span>',
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => 1,
            'comment_author_IP' => '127.0.0.1',
            'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
            'comment_date' => $time,
            'comment_approved' => 1,
        );

        wp_insert_comment($data);


        $data = array(
            'comment_post_ID' => $playerid,
            'comment_author' => 'admin',
            'comment_author_email' => 'admin@admin.com',
            'comment_author_url' => 'http://',
            'comment_content' => '<span class="dzstooltip-con" style="left:37.66387884267631%"><span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black talign-start style-rounded color-dark-light" style="width: 250px;"><span class="dzstooltip--inner"><span class="the-comment-author">@admin</span> says:<br>test</span><span class="the-avatar" style="background-image: url(http://1.gravatar.com/avatar/12d1738b0f28c211e5fd5ae066e631a1?s=20&#038;d=mm&#038;r=g)"></span></span></span>',
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => 1,
            'comment_author_IP' => '127.0.0.1',
            'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
            'comment_date' => $time,
            'comment_approved' => 1,
        );

        wp_insert_comment($data);


        update_option($this->dbname_sample_data, $this->sample_data);




        echo 'success';

        die();
    }

    function ajax_remove_sample_tracks() {

        //print_r($_COOKIE);



//        print_r($this->sample_data);




        foreach ($this->sample_data['media'] as $pid) {
            wp_delete_post($pid);
        };

        $this->sample_data = false;
        update_option($this->dbname_sample_data, $this->sample_data);




        echo 'success';

        die();
    }

    function ajax_submit_like() {

        //print_r($_COOKIE);


        $aux_likes = 0;
        $playerid = '';

        if (isset($_POST['playerid'])) {
            $playerid = $_POST['playerid'];
            $playerid = str_replace('ap', '', $playerid);
        }



	    if (get_post_meta($playerid, '_dzsap_likes', true) != '') {
		    $aux_likes = intval(get_post_meta($_POST['playerid'], '_dzsap_likes', true));
	    }



	    $aux_likes = $aux_likes + 1;

	    update_post_meta($playerid, '_dzsap_likes', $aux_likes);

//	    $this->analytics_submit_into_table(array(
//		    'type'=>'like',
//	    ));
//	    echo 'success';


        setcookie("dzsap_likesubmitted-" . $playerid, '1', time() + 36000, COOKIEPATH);

	    $this->insert_activity(array(
		    'id_video'=>$playerid,
		    'type'=>'like',
	    ));

	    echo json_encode(array(
		    'status'=>'success',
		    'nr_likes'=>$aux_likes,
	    ));
        die();
    }

    function ajax_retract_like() {

        //print_r($_COOKIE);


        $aux_likes = 1;
        $playerid = '';

        if (isset($_POST['playerid'])) {
            $playerid = $_POST['playerid'];
            $playerid = str_replace('ap', '', $playerid);
        }


        if (get_post_meta($playerid, '_dzsap_likes', true) != '') {
            $aux_likes = intval(get_post_meta($_POST['playerid'], '_dzsap_likes', true));
        }

        $aux_likes = $aux_likes - 1;

        update_post_meta($playerid, '_dzsap_likes', $aux_likes);

        setcookie("dzsap_likesubmitted-" . $playerid, '', time() - 36000, COOKIEPATH);


	    $user_id = 0;
	    $current_user = wp_get_current_user();

	    if ($current_user) {
		    if ($current_user->ID) {
			    $user_id = $current_user->ID;
		    }
	    }


	    $this->delete_activity(array(
		    'id_video'=>$playerid,
		    'id_user'=>$user_id,
		    'type'=>'like',
	    ));

	    echo json_encode(array(
		    'status'=>'success',
		    'nr_likes'=>$aux_likes,
	    ));
        die();
    }







    function sanitize_for_javascript_double_quote_value($arg){

	    $arg = str_replace('"','',$arg);

	    if($arg=='/'){
	        $arg = '';
        }

	    return $arg;

    }
    function sanitize_to_hex_color_without_hash($arg){


	    $arg = str_replace('#','',$arg);

	    return $arg;
    }

    function handle_admin_head(){
        // on every admin page <head>
        //echo 'ceva23';
        ///siteurl : "'.site_url().'",
        $aux = admin_url( 'admin.php?page='.$this->adminpagename);

        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_configs) {
            $aux = admin_url( 'admin.php?page='.$this->adminpagename_configs);
        }

        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_about) {

            wp_enqueue_style('dzsvg', $this->base_url . 'libs/videogallery/vplayer.css');
            wp_enqueue_script('dzsvg', $this->base_url . "libs/videogallery/vplayer.js");
        }

        $params = array('currslider' => '_currslider_');
        $newurl = add_query_arg($params, $aux);

        $params = array('deleteslider' => '_currslider_');
        $delurl = add_query_arg($params, $aux);

        $theurl_forwaveforms = $this->base_url;
        $thepath_forwaveforms = $this->base_url;

        if (isset($this->mainoptions['use_external_uploaddir']) && $this->mainoptions['use_external_uploaddir'] == 'on') {
//            $theurl_forwaveforms = site_url('wp-content') . '/upload/';

            $upload_dir = wp_upload_dir();
            $theurl_forwaveforms = $upload_dir['url'].'/';

            $aux = $upload_dir['path'].'/';
            $thepath_forwaveforms = str_replace('\\', '/', $aux);
        }

        echo '<script>';


//        echo 'window.dzsap_settings= { dzsap_site_url: "' . site_url() . '/",wpurl: "' . site_url() . '/",version: "' . DZSAP_VERSION . '",ajax_url: "' . admin_url('admin-ajax.php') . '", debug_mode:"'.$this->mainoptions['debug_mode'].'" }; ';

        echo '
        
            window.ultibox_options_init = {
                \'settings_deeplinking\' : \'off\'
                ,\'extra_classes\' : \'close-btn-inset\'
            };
            
        window.init_zoombox_settings = { settings_disableSocial : "on" ,settings_deeplinking : "off" }; var dzsap_settings = { thepath: "' . $this->base_url . '",the_url: "' . $this->base_url . '",theurl_forwaveforms: "' . $theurl_forwaveforms . '",siteurl: "' . site_url() . '",site_url: "' . site_url() . '"
            ,thepath_forwaveforms: "' . $thepath_forwaveforms . '"
            , is_safebinding: "' . $this->mainoptions['is_safebinding'] . '", admin_close_otheritems:"' . $this->mainoptions['admin_close_otheritems'] . '",settings_wavestyle:"' . $this->mainoptions['settings_wavestyle'] . '"
,url_vpconfig:"' . admin_url( 'admin.php?page='.$this->adminpagename_configs.'&currslider={{currslider}}').'"
,shortcode_generator_url: "'.admin_url('admin.php?page='.$this->page_mainoptions_link).'&dzsap_shortcode_builder=on"
,shortcode_generator_player_url: "'.admin_url('admin.php?page='.$this->page_mainoptions_link).'&dzsap_shortcode_player_builder=on"
,translate_add_gallery : "'.__('Add Playlist').'"
,translate_add_player : "'.__('Add Player').'"
,soundcloud_apikey : "' . $this->mainoptions['soundcloud_api_key'] . '"
';

        //echo 'hmm';
        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename && (isset($this->mainitems[$this->currSlider])==false || $this->mainitems[$this->currSlider] == '')) {
            echo ', addslider:"on"';
        }
        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_configs && (isset($this->mainitems_configs[$this->currSlider])==false || $this->mainitems_configs[$this->currSlider] == '')) {
            echo ', addslider:"on"';
        }
        echo ',urldelslider:"' . $delurl . '", urlcurrslider:"' . $newurl . '", currSlider:"' . $this->currSlider . '", currdb:"' . $this->currDb . '", color_waveformbg:"' . $this->sanitize_to_hex_color_without_hash($this->mainoptions['color_waveformbg']) . '", color_waveformprog:"' . $this->sanitize_to_hex_color_without_hash($this->mainoptions['color_waveformprog']) . '", waveformgenerator_multiplier:"' . $this->mainoptions['waveformgenerator_multiplier'] . '"};';
        echo '  </script>
';



//        error_log('$this->mainoptions[\'enable_auto_backup\'] - '.$this->mainoptions['enable_auto_backup']);
        if ($this->mainoptions['enable_auto_backup'] == 'on') {
//            $this->do_backup();
            $last_backup = get_option('dzsap_last_backup');


//            error_log('$last_backup - '.$last_backup) ;
            if ($last_backup) {

                $timestamp = time();
                if (abs($timestamp - $last_backup) > (3600 * 24 * 1)) {

                    $this->do_backup();
                }

            } else {
                $this->do_backup();
            }
        }
//	    $this->do_backup();
        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename){
        }
        if (isset($_GET['taxonomy']) && $_GET['taxonomy'] == $this->taxname_sliders){

            ?><style>body.taxonomy-dzsap_sliders .wrap,.dzsap-sliders-con{ opacity:0; transition: opacity 0.3s ease-out; }
                body.taxonomy-dzsap_sliders.sliders-loaded .wrap, body.taxonomy-dzsap_sliders.sliders-loaded .dzsap-sliders-con{
                    opacity:1;
                }
</style>
<?php
        }
    }



    function ajax_import_item_lib() {


        $cont = '';

        $this->db_read_mainitems();

        if($_POST['demo']=='sample_vimeo_channel33'){
            //            $cont = json_encode(array(
            //                'response_type'=>'success',
            //                'items'=>array(
            //
            //                    array(
            //                        'type'=>'slider_import',
            //                        'src'=>'a:74:{s:8:"feedfrom";s:13:"vmuserchannel";s:2:"id";s:20:"sample_vimeo_channel";s:6:"height";s:3:"300";s:11:"displaymode";s:6:"normal";s:12:"skin_html5vg";s:28:"skin-boxy skin-boxy--rounded";s:8:"vpconfig";s:17:"skinauroradefault";s:8:"nav_type";s:6:"thumbs";s:25:"nav_type_outer_max_height";s:0:"";s:12:"menuposition";s:6:"bottom";s:8:"autoplay";s:2:"on";s:12:"autoplaynext";s:2:"on";s:13:"cueFirstVideo";s:2:"on";s:9:"randomize";s:3:"off";s:5:"order";s:3:"ASC";s:10:"transition";s:4:"fade";s:27:"enableunderneathdescription";s:3:"off";s:19:"enable_search_field";s:3:"off";s:21:"search_field_location";s:7:"outside";s:23:"settings_enable_linking";s:3:"off";s:11:"autoplay_ad";s:2:"on";s:30:"set_responsive_ratio_to_detect";s:2:"on";s:11:"sharebutton";s:3:"off";s:12:"facebooklink";s:0:"";s:11:"twitterlink";s:0:"";s:14:"googlepluslink";s:0:"";s:16:"social_extracode";s:0:"";s:11:"embedbutton";s:3:"off";s:4:"logo";s:0:"";s:8:"logoLink";s:0:"";s:14:"html5designmiw";s:3:"120";s:14:"html5designmih";s:3:"120";s:14:"html5designmis";s:2:"15";s:16:"thumb_extraclass";s:0:"";s:24:"disable_menu_description";s:3:"off";s:26:"design_navigationuseeasing";s:2:"on";s:23:"menu_description_format";s:0:"";s:9:"max_width";s:0:"";s:10:"coverImage";s:0:"";s:9:"nav_space";s:2:"30";s:13:"disable_title";s:3:"off";s:19:"disable_video_title";s:3:"off";s:10:"laptopskin";s:3:"off";s:15:"html5transition";s:7:"slideup";s:3:"rtl";s:3:"off";s:13:"extra_classes";s:0:"";s:7:"bgcolor";s:11:"transparent";s:6:"shadow";s:3:"off";s:5:"width";s:4:"100%";s:16:"forcevideoheight";s:0:"";s:16:"mode_wall_layout";s:4:"none";s:11:"maxlen_desc";s:3:"250";s:15:"readmore_markup";s:65:"<p><a class=ignore-zoombox href={{postlink}}>read more »</a></p>";s:9:"striptags";s:2:"on";s:26:"try_to_close_unclosed_tags";s:2:"on";s:22:"desc_aside_maxlen_desc";s:3:"250";s:20:"desc_aside_striptags";s:2:"on";s:37:"desc_aside_try_to_close_unclosed_tags";s:2:"on";s:17:"rtmp_streamserver";s:0:"";s:16:"enable_secondcon";s:3:"off";s:15:"enable_outernav";s:3:"off";s:28:"enable_outernav_video_author";s:3:"off";s:9:"playorder";s:3:"ASC";s:7:"init_on";s:4:"init";s:19:"ids_point_to_source";s:3:"off";s:39:"autoplay_on_mobile_too_with_video_muted";s:3:"off";s:16:"youtubefeed_user";s:0:"";s:17:"ytplaylist_source";s:0:"";s:17:"ytkeywords_source";s:0:"";s:21:"youtubefeed_maxvideos";s:2:"50";s:14:"vimeofeed_user";s:9:"fancyshot";s:17:"vimeofeed_channel";s:0:"";s:17:"vimeofeed_vmalbum";s:0:"";s:15:"vimeo_maxvideos";s:2:"25";s:10:"vimeo_sort";s:7:"default";}}',
            //                    )
            //                ),
            //                'settings'=>array(
            //                    'final_shortcode'=>'[dzs_videogallery id="'.$lab.'" db="main"]'
            //                ),
            //            ));
        }else{

            $url = 'https://zoomthe.me/updater_dzsap/getdemo.php?demo='.$_POST['demo'].'&purchase_code='.$this->mainoptions['dzsap_purchase_code'].'&site_url='.urlencode(site_url());
            $cont = file_get_contents($url);
        }




        //        echo $url;




        $resp = json_decode($cont,true);


        if($resp['response_type']=='success'){

            //            print_r($resp);
            foreach ($resp['items'] as $lab=>$it){
                //                print_r($it);


//                print_r($it);

                if($it['type']=='slider_import'){

                    $sw_import = true;
                    $slider = unserialize($it['src']);


                    //                    print_r($slider);
                    foreach ($this->mainitems as $mainitem){
                        //                        print_r($mainitem);

                        if($slider['settings']['id']===$mainitem['settings']['id']){

                            //                            echo '$slider[\'settings\'][\'id\'] - '.$slider['settings']['id'].' - $mainitem[\'settings\'][\'id\'] - '.$mainitem['settings']['id'];
                            $sw_import=false;
                        }
                    }

                    //                    print_r($slider);
                    //                    echo '$sw_import - '.$sw_import;


                    if($sw_import){






                        array_push($this->mainitems, $slider);



                        update_option($this->dbname_mainitems, $this->mainitems);
                    }
                }



	            if($it['type']=='set_curr_page_footer_player'){

//                    error_log("SET FOOTER PLAYER - ".print_rr($_POST, array('echo'=>false)));

                    if(isset($_POST['post_id']) && $_POST['post_id']){
                        $id = $_POST['post_id'];

                        update_post_meta($id,'dzsap_footer_enable','on');
                        update_post_meta($id,'dzsap_footer_feed_type','parent');
                        update_post_meta($id,'dzsap_footer_vpconfig',$it['src']);
//	                    error_log("DID IT");
                    }
	            }
	            if($it['type']=='apconfig_import'){

		            $sw_import = true;
		            $slider = unserialize($it['src']);


		            //                    print_r($slider);
		            error_log('$slider[\'settings\'][\'id\'] - '.print_r($slider['settings']['id'],true));
		            error_log('mainitems_configs - '.print_r($this->mainitems_configs,true));



		            foreach ($this->mainitems_configs as $mainitem){
			            //                        print_r($mainitem);

			            if($slider['settings']['id']===$mainitem['settings']['id']){

				            //                            echo '$slider[\'settings\'][\'id\'] - '.$slider['settings']['id'].' - $mainitem[\'settings\'][\'id\'] - '.$mainitem['settings']['id'];
				            $sw_import=false;
			            }
		            }

		            //                    print_r($slider);
		            //                    echo '$sw_import - '.$sw_import;


		            if($sw_import){






			            array_push($this->mainitems_configs, $slider);



			            error_log('mainitems_configs - '.print_r($this->mainitems_configs,true));
			            update_option($this->dbname_mainitems_configs, $this->mainitems_configs);
		            }
	            }



                if($it['type']=='dzsap_category'){


                    $args = $it;


                    $args['taxonomy']='dzsap_category';
                    $this->import_demo_create_term_if_it_does_not_exist($args);


                }
                if($it['type']=='product_cat'){


                    $args = $it;


                    $args['taxonomy']='product_cat';
                    $this->import_demo_create_term_if_it_does_not_exist($args);


                }
                if($it['type']=='dzsap_items'){


                    $args = $it;




                    $taxonomy = 'dzsap_category';

                    if(isset($args['post_type']) && $args['post_type']=='product'){


                        $taxonomy = 'product_cat';
                    }
                    if($args['term_slug']){



                        $term = get_term_by('slug', $args['term_slug'], $taxonomy);


                        if ($term) {



                            $args['term']=$term;


                        }


                        $args['taxonomy']=$taxonomy;

                    }











	                $args['call_from']='import item lib';

                    $this->import_demo_insert_post_complete($args);


                }
            }
        }


        echo json_encode($resp);
        die();
    }

	function import_slider($file_cont){


		$tax = $this->taxname_sliders;
		try{

			$file_cont = str_replace('\\\\"','\\"',$file_cont);
			$arr = @json_decode($file_cont,true);

			error_log( 'content json - '. print_rr($arr,true));

			if($arr && is_array($arr)){

				$type = 'json';
			}else{
				try{

					$arr = unserialize($file_cont);


					error_log( 'content serial - '. print_rr($arr,true). ' - '.print_rr($file_cont,true));
					$type = 'serial';
				}catch(Exception $e){

					error_log( 'failed parsing'. print_rr($file_cont,true));
				}
			}

			if(is_array($arr)){
				if($type=='json'){







					$reference_term_name = $arr['original_term_name'];
					$reference_term_slug = $arr['original_term_slug'];

//			    print_rr($reference_term_name);
//			    print_rr($reference_term_slug);
//			    print_rr($query);
					$original_name = $reference_term_name;
					$original_slug = $reference_term_slug;



					$new_term_slug = $reference_term_slug;
					$new_term_name = $reference_term_name;



					$ind = 1;
					$breaker = 100;


					$tax = $this->taxname_sliders;
					$term = term_exists($new_term_name, $tax);
					if ($term !== 0 && $term !== null) {


						$new_term_name=$original_name.'-'.$ind;
						$new_term_slug=$original_slug.'-'.$ind;
						$ind++;


						 // -- we will try to find
						while(1){

							$term = term_exists($new_term_name, $tax);
							if ($term !== 0 && $term !== null) {

								$new_term_name=$original_name.'-'.$ind;
								$new_term_slug=$original_slug.'-'.$ind;
								$ind++;
							}else{

								error_log("SEEMS THAT TERM DOES NOT EXIST ".$new_term_name.' '.$new_term_slug);
								break;
							}

							$breaker--;

							if($breaker<0){
								break;
							}
						}

					}else{



					}



					$new_term = wp_insert_term(
						$new_term_name, // the term
						$tax, // the taxonomy
						array(

							'slug' => $new_term_slug,
						)
					);


					$new_term_id = '';
					if(is_array($new_term)){

						$new_term_id = $new_term['term_id'];
					}else{
						error_log(' .. ERROR the name is '.$new_term_name);
						error_log(' .. $tax is '.$tax);
						error_log(print_r($new_term,true));
					}



					$term_meta = array_merge(array(), $arr['term_meta']);

					unset($term_meta['items']);

					update_option("taxonomy_$new_term_id", $term_meta);


					foreach ($arr['items'] as $po){

					     // -i item in

						$args = array_merge(array(), $po);

						// -- we will prefer slug

                        error_log('new slug - '.$new_term_slug);
						$args['term']=$new_term_slug;
						$args['taxonomy']=$tax;
						$args['call_from']='import_slider items json';

						$this->import_demo_insert_post_complete($args);



					}

//			$new_term = get_term_by('slug',$new_term_slug,$tax);

//            error_log(print_rr($new_term,array('echo'=>false)));














				}



				// -- legacy
				if($type=='serial'){


					$new_term_id = '';
					$new_term = null;
					$original_slug = '';
					$new_term_slug = '';


					foreach ($arr as $lab=>$val){


						if($lab==='settings'){




							// -- settings


							$reference_term_name = $val['id'];
							$reference_term_slug = $val['id'];

//			    print_rr($reference_term_name);
//			    print_rr($reference_term_slug);
//			    print_rr($query);
							$original_name = $reference_term_name;
							$original_slug = $reference_term_slug;



							$new_term_slug = $reference_term_slug;
							$new_term_name = $reference_term_name;



							$ind = 1;
							$breaker = 100;


							$term = term_exists($new_term_slug, $tax);
							if ($term !== 0 && $term !== null) {




								while(1){

									$term = term_exists($new_term_slug, $tax);
									if ($term !== 0 && $term !== null) {

										$ind++;
										$new_term_slug=$original_slug.'-'.$ind;
									}else{
										break;
									}

									$breaker--;

									if($breaker<0){
										break;
									}
								}

								$ind++;
								$new_term_name=$original_name.'-'.$ind;
								$new_term_slug=$original_slug.'-'.$ind;
							}else{

							}



							$new_term = wp_insert_term(
								$new_term_name, // the term
								$tax, // the taxonomy
								array(

									'slug' => $new_term_slug,
								)
							);


							if(is_array($new_term)){

								$new_term_id = $new_term['term_id'];
							}else{
								error_log(' .. the name is '.$new_term_name);
								error_log(print_r($new_term,true));
							}


							$term_meta = array_merge(array(), $val);

							unset($term_meta['items']);

							update_option("taxonomy_$new_term_id", $term_meta);
						}else{
						    // -- item in serial

							$args = array_merge(array(), $val);

							$args['term']=$new_term;
							$args['taxonomy']=$tax;
							$args['post_name'] =$original_slug.'-'.$lab;
							$args['post_title'] =$original_slug.'-'.$lab;

							if(isset($args['menu_artistname'])){
								$args['post_title'] = $args['menu_artistname'];
							}
							if(isset($args['menu_songname'])){
								$args['post_content'] = $args['menu_songname'];
							}

							// -- import slider meta values
							foreach ($this->options_item_meta as $oim){
								$long_name = $oim['name'];

								$short_name = str_replace('dzsap_meta_','',$oim['name']);



								if(isset($args[$short_name])){

									$args[$long_name] = $args[$short_name];
								}
							}


							$args['call_from']='import_slider items serial';

							$this->import_demo_insert_post_complete($args);

						}



					}
				}
			}
		}catch(Exception $err){
			print_rr($err);
		}

	}


	function import_demo_create_term_if_it_does_not_exist($pargs = array()) {


        $margs = array(

            'term_name' => '',
            'slug' => '',
            'taxonomy' => '',
            'description' => '',
            'parent' => '',
        );

        $margs = array_merge($margs, $pargs);

        $term = get_term_by('slug', $margs['slug'], $margs['taxonomy']);


        if ($term) {

        } else {


            $args = array(
                'description' => $margs['description'],
                'slug' => $margs['slug'],


            );

            if ($margs['parent']) {
                $args['parent'] = $margs['parent'];
            }

            $term = wp_insert_term($margs['term_name'], $margs['taxonomy'], $args);

        }
        return $term;

    }



    function import_demo_create_attachment($img_url, $port_id, $img_path){






        $attachment = array(
            'guid'           => $img_url,
            'post_mime_type' => 'image/jpeg',
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $img_url ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // Insert the attachment.
        $attach_id = wp_insert_attachment( $attachment, $img_url, $port_id );


        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        // Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata( $attach_id, $img_path );
        //        die();
        wp_update_attachment_metadata( $attach_id, $attach_data );

        return $attach_id;
    }


    function import_demo_create_portfolio_item($pargs = array()) {






        $margs = array(

            'post_title'=>'',
            'post_content'=>'',
            'post_status'=>'',
            'post_type'=>'dzsvcs_port_items',
        );

        $margs = array_merge($margs, $pargs);



        $args = array(
            'post_type' => $margs['post_type'],
            'post_title' => $margs['post_title'],
            'post_content' => $margs['post_content'],
            'post_status'=>$margs['post_status'],



            /*other default parameters you want to set*/
        );



        $post_id = wp_insert_post($args);

        return $post_id;


    }

    function import_demo_insert_post_complete($pargs = array()) {






        $margs = array(

            'post_title'=>'',
            'call_from'=>'default',

            'post_content'=>'',
            'post_type'=>'dzsap_items',
            'post_status'=>'publish',
            'post_name'=>'',
            'img_url'=>'',
            'img_path'=>'',
            'term'=>'',
            'taxonomy'=>'',
            'attach_id'=>'',
            'dzsvp_thumb'=>'',
            'dzsvp_item_type'=>'detect',
            'dzsvp_featured_media'=>'',


        );

        $margs = array_merge($margs, $pargs);



        if($margs['post_name']){







	        $ind = 1;
	        $breaker = 100;



	        $the_slug = $margs['post_name'];
	        $original_slug = $margs['post_name'];
	        $args = array(
		        'name'        => $the_slug,
		        'post_type'   => $margs['post_type'],
		        'post_status' => 'publish',
		        'numberposts' => 1
	        );
	        $my_posts = get_posts($args);
	        if ($my_posts) {




		        while(1){

			        $the_slug = $margs['post_name'];
			        $original_slug = $margs['post_name'];
			        $args = array(
				        'name'        => $the_slug,
				        'post_type'   => $margs['post_type'],
				        'post_status' => 'publish',
				        'numberposts' => 1
			        );
			        $my_posts = get_posts($args);
			        if ($my_posts) {

				        $ind++;
				        $margs['post_name']=$original_slug.'-'.$ind;
			        }else{
				        break;
			        }

			        $breaker--;

			        if($breaker<0){
				        break;
			        }
		        }

		        $ind++;

		        $margs['post_name']=$original_slug.'-'.$ind;
	        }else{

	        }









        }

        $args = array(
            'post_type' => $margs['post_type'],
            'post_title' => $margs['post_title'],

            'post_content' => $margs['post_content'],
            'post_status'=>$margs['post_status'],



            /*other default parameters you want to set*/
        );


        if($margs['post_name']){
            //            $args['name']=$margs['post_name'];
            $args['post_name']=$margs['post_name'];
        }


        if($margs['term']){

            $term = $margs['term'];
        }
        $taxonomy = $margs['taxonomy'];

        if($margs['img_url']){

            $img_url = $margs['img_url'];
        }
        $img_path = $margs['img_path'];



        //        print_rr($margs);



	    error_log(' item import - '.print_rr($margs,true).print_rr($args,true));
        $port_id = $this->import_demo_create_portfolio_item($args);

        if($margs['term']) {
            $term = $margs['term'];


            if(is_object($margs['term']) && isset($margs['term']->term_id)){
                $term = $margs['term']->term_id;
            }else{

	            if(is_array($margs['term']) && isset($margs['term']['term_id'])){
		            $term = $margs['term']['term_id'];
	            }
            }
            wp_set_post_terms($port_id, $term, $taxonomy);
        }


        foreach ($margs as $lab => $val){
            if(strpos($lab,'dzsap_')===0){

	            update_post_meta($port_id,$lab,$val);
            }
        }







        //        update_post_meta($port_id,'q_meta_post_media',$img_url);







        if($margs['attach_id']){

            set_post_thumbnail( $port_id, $margs['attach_id'] );
        }else{

            if($margs['img_url']) {
                $attach_id = $this->import_demo_create_attachment($img_url, $port_id, $img_path);
                set_post_thumbnail($port_id, $attach_id);

                $this->import_demo_last_attach_id = $attach_id;
            }

        }





        return $port_id;



    }

    function do_backup() {

        $timestamp = time();

//        echo 'time - '.$timestamp;

        $data = get_option($this->dbname_mainitems);

        if (is_array($data)) {
            $data = serialize($data);
        }

//        echo ' data - '.$data;
//        file_put_contents('backups/backup_'.$timestamp,$data);
        $upload_dir = wp_upload_dir();
//        file_put_contents($this->base_path . 'backups/backup_' . $timestamp . '.txt', $data);


        error_log('do_backup()'.' '.time());

        if(file_exists($upload_dir['basedir'] . '/dzsap_backups')){

//            echo 'dada';
        }else{

//            echo 'nunu';
            mkdir($upload_dir['basedir'] . '/dzsap_backups', 0755);
        }

        file_put_contents($upload_dir['basedir'] . '/dzsap_backups/backup_' . $timestamp . '.txt', $data);


//        $theurl_forwaveforms = $upload_dir['url'].'/';

//        echo $upload_dir['basedir'] . '/dzsap_backups/backup_' . $timestamp . '.txt';

//        print_r($upload_dir);

        update_option('dzsap_last_backup', $timestamp);


	    if($this->mainoptions['playlists_mode']=='normal') {




		    $terms = get_terms( $this->taxname_sliders, array(
			    'hide_empty' => false,
		    ) );

		    foreach($terms as $term){

		        $data = $this->playlist_export($term->term_id);

			    if ( is_array( $data ) ) {
				    $data = json_encode( $data );
			    }
//                file_put_contents($this->base_path . 'backups/backup_' . $adb . '_' . $timestamp . '.txt', $data);
			    file_put_contents( $upload_dir['basedir'] . '/dzsap_backups/backup_' . $term->slug . '_' . $timestamp . '.txt', $data );
            }
	    }else{

		    if ( is_array( $this->dbs ) ) {
			    foreach ( $this->dbs as $adb ) {
				    $data = get_option( $this->dbname_mainitems . '-' . $adb );

				    if ( is_array( $data ) ) {
					    $data = serialize( $data );
				    }
//                file_put_contents($this->base_path . 'backups/backup_' . $adb . '_' . $timestamp . '.txt', $data);
				    file_put_contents( $upload_dir['basedir'] . '/dzsap_backups/backup_' . $adb . '_' . $timestamp . '.txt', $data );


			    }
		    }
        }

	    $logged_backups = array();
        try{

	        $logged_backups = json_decode(get_option('dzsap_backuplog'),true);
        }catch(Exception $err){

        }
	    if(is_array($logged_backups)==false){
		    $logged_backups = array();
	    }


        array_push($logged_backups, time());
        if(count($logged_backups)>5){
            array_shift($logged_backups);
        }


        update_option('dzsap_backuplog',json_encode($logged_backups));
    }

    function playlist_export($term_id, $pargs=array()) {



        $margs = array(
                'download_export'=>false
        );

        $margs = array_merge($margs,$pargs);

	    $term_meta = get_option( "taxonomy_$term_id" );

//		print_rr($term_meta);

	    $tax = $this->taxname_sliders;

	    $reference_term = get_term_by( 'id', $term_id, $tax );

//	        print_rr($reference_term);


	    $reference_term_name = $reference_term->name;
	    $reference_term_slug = $reference_term->slug;
	    $selected_term_id    = $reference_term->term_id;


	    if ( $selected_term_id ) {

		    $args = array(
			    'post_type'   => 'dzsap_items',
			    'numberposts' => - 1,
			    'posts_per_page' => - 1,
			    //                'meta_key' => 'dzsap_meta_order_'.$selected_term,

			    'orderby'    => 'meta_value_num',
			    'order'      => 'ASC',
			    'meta_query' => array(
				    'relation' => 'OR',
				    array(
					    'key'     => 'dzsap_meta_order_' . $selected_term_id,
					    //                        'value' => '',
					    'compare' => 'EXISTS',
				    ),
				    array(
					    'key'     => 'dzsap_meta_order_' . $selected_term_id,
					    //                        'value' => '',
					    'compare' => 'NOT EXISTS'
				    )
			    ),
			    'tax_query'  => array(
				    array(
					    'taxonomy' => $tax,
					    'field'    => 'id',
					    'terms'    => $selected_term_id // Where term_id of Term 1 is "1".
				    )
			    ),
		    );

		    $my_query = new WP_Query( $args );

//            print_r($my_query);


//            print_r($my_query->posts);


		    $arr_export = array(
			    'original_term_id'   => $selected_term_id,
			    'original_term_slug' => $reference_term_slug,
			    'original_term_name' => $reference_term_name,
			    'original_site_url'  => site_url(),
			    'export_type'        => 'meta_term',
			    'term_meta'          => $term_meta,
			    'items'              => array(),
		    );

		    foreach ( $my_query->posts as $po ) {

//                print_r($po);


			    $po_sanitized = $this->sanitize_to_gallery_item( $po );


			    array_push( $arr_export['items'], $po_sanitized );

//                print_rr($po);
//                print_rr($po_sanitized);
//			        print_rr($po);
		    }


		    if($margs['download_export']){

			    header( 'Content-Type: text/plain' );
			    header( 'Content-Disposition: attachment; filename="' . "dzsap_export_" . $reference_term_slug . ".txt" . '"' );
            }

		    return $arr_export;
	    }else{
	        return array();
        }
    }

    function get_wishlist(){


	    $arr_wishlist = array();

	    if(get_user_meta(get_current_user_id(),'dzsap_wishlist',true) && get_user_meta(get_current_user_id(),'dzsap_wishlist',true)!='null'){
		    try{

			    $arr_wishlist = json_decode(get_user_meta(get_current_user_id(),'dzsap_wishlist',true),true);
		    }catch(Exception $e){

		    }
	    }

	    return $arr_wishlist;
    }

    function shortcode_player_button($atts, $content = null) {

	    // -- [player_button]

        //[dzsap_woo_grid --]
        //print_r($current_user->data);
        //echo 'ceva'.isset($current_user->data->user_nicename);


//        error_log('ratatata');
        $fout = '';


        $margs = array(
            'link'=>'',
            'style'=>'',
            'label'=>'',
            'icon'=>'',
            'color'=>'',
            'target'=>'',
            'background_color'=>'',
            'extra_classes'=>'',
            'extraattr'=>'',
            'post_id'=>'',
        );

        if ($atts) {

            $margs = array_merge($margs, $atts);
        }


//        echo 'shortcode_player_button margs - '; print_rr($margs);

        $tag = 'div';
	    if($margs['link']) {
	        $tag = 'a';
	    }


	    $fout .= '<'.$tag;


        if($margs['link']){

	        $fout.=' href="'.$margs['link'].'"';
        }


	    if($margs['target']){

		    $fout.=' target="'.$margs['target'].'"';
	    }else{

		    $fout.=' target="'.''.'"';
        }

        $fout.=' class="'.$margs['style'].'';

        if($margs['style']=='player-but') {
            $fout .= ' dzstooltip-con';
        }

        if($content){
            $margs['label']= $content;
        }

        $fout.=' '.$margs['extra_classes'];

        $fout.='"';
        $fout.=' style="';

        if($margs['color']){
            $fout.='color: '. $margs['color'].';';
        }

        $fout.='"';


//        print_rr($margs);

        if($margs['post_id']){

	        $fout.=' data-post_id="'.$margs['post_id'].'"';


	        if(strpos($margs['extra_classes'],'dzsap-wishlist-but')!==false){


	            $arr_wishlist= $this->get_wishlist();


//		        print_rr($arr_wishlist);
		        if(in_array($margs['post_id'],$arr_wishlist)){


		            $margs['icon'] = str_replace('fa-star-o','fa-star',$margs['icon']);
                }

            }


	        $margs['extraattr'] = str_replace('{{posturl}}',get_permalink($margs['post_id']),$margs['extraattr']);
        }

        $fout.=' '.$margs['extraattr'];

        $fout.='>';

        if($margs['style']=='player-but'){
            $fout.='<span class="the-icon-bg"></span>';
        }
        if($margs['style']=='btn-zoomsounds'){
            $fout.='<span class="the-bg" style="';

            if($margs['background_color']){
                $fout.='background-color: '. $margs['background_color'].';';
            }

            $fout.='"></span>';
        }

        if($margs['style']=='player-but'){
            $fout.='<span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black align-right talign-end style-rounded color-dark-light" style="width: auto; white-space: nowrap;"><span class="dzstooltip--inner" style="margin-right: -10px;">'.$margs['label'].'</span></span>';
        }
        if($margs['style']=='player-but'){
            $fout.='<i class="svg-icon fa '. $margs['icon'].'"></i>';
        }
        if($margs['style']=='btn-zoomsounds'){
            $fout.='<span class="the-icon"><i class="fa '. $margs['icon'].'"></i></span>';
//            $fout.='<i class="svg-icon fa '. $margs['icon'].'"></i>';
        }


        if($margs['style']=='btn-zoomsounds'){
            $fout.='<span class="the-label ">'.$margs['label'].'</span>';
        }


        $fout.='</'.$tag.'>';

        //<a href="#" class=" btn-zoomsounds  " style="color: #FFF;"><span class="the-bg" style="background-color: #00aced;"></span> <span class="the-label ">Twitter</span></a>





        return $fout;

    }


	function sanitize_term_slug_to_id($arg, $taxonomy_name = 'dzsvideo_category'){


		if(is_numeric($arg)){

		}else{

			$term = get_term_by('slug', $arg, $taxonomy_name);

			if($term){
				$arg = $term->term_id;
			}
//                    echo 'new term_id - '; print_r($term_id);
		}


		return $arg;
	}

    function shortcode_woo_grid($atts, $content = null) {
        //[dzsap_woo_grid --]
        global $current_user;

        //print_r($current_user->data);
        //echo 'ceva'.isset($current_user->data->user_nicename);
        $this->sliders__player_index++;

        $fout = '';





        $this->front_scripts();
        wp_enqueue_style('dzs.zoomsounds-grid', $this->base_url . 'audioplayer/audioportal-grid.css');

        $margs = array(
            'style' => 'under',
            'vpconfig' => '',
            'settings_wpqargs' => '',
            'faketarget' => '',
            'type' => 'product',
            'cats' => '',
            'count' => '10', // -- posts per page
            'ids' => '',
            'layout' => '4-cols',
        );

        if($atts){

            $margs = array_merge($margs, $atts);
        }




        $args_wpqargs = array();
        $margs['settings_wpqargs'] = html_entity_decode($margs['settings_wpqargs']);
        parse_str($margs['settings_wpqargs'],$args_wpqargs);


        $wpqargs = array(
            'post_type' => $margs['type'],
            'posts_per_page' => '-1',
        );

        if($margs['count']){
            $wpqargs['posts_per_page']=$margs['count'];
        }

        if (!isset($args_wpqargs) || $args_wpqargs == false || is_array($args_wpqargs) == false) {
            $args_wpqargs = array();
        }

        $taxonomy = 'product_cat';

        if($margs['type']=='attachment'){
            $wpqargs['post_mime_type']='audio/mpeg';
//            $wpqargs['post_mime_type'] = 'image';

            $wpqargs['post_parent']=null;
            $wpqargs['post_status']='inherit';
        }



        if($margs['cats']){


            $thecustomcats = array();
            $thecustomcats = explode(',',$margs['cats']);
            $thecustomcats = array_values($thecustomcats);

            foreach ($thecustomcats as $lab=>$val){

                $thecustomcats[$lab] = $this->sanitize_term_slug_to_id($val,$taxonomy);
            }

            if ($wpqargs['post_type'] == 'product') {


                $wpqargs['tax_query'] = array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'id',
                        'terms' => $thecustomcats,
                    )
                );
            }
            if ($wpqargs['post_type'] == 'attachment') {
            }




        }


        if(isset($_GET['query_song_tag']) && $_GET['query_song_tag']){


	        $taxonomy = 'dzsap_tags';

            $tax_query = array(

	            'taxonomy' => $taxonomy,
	            'field' => 'slug',
	            'terms' => $_GET['query_song_tag'],
            );
            if(isset($wpqargs['tax_query']) && count($wpqargs['tax_query'])){

                array_push($wpqargs['tax_query'], $tax_query);
            }else{
	            $wpqargs['tax_query'] = array(
		            $tax_query
                );
            }
        }

//        print_rr($thecustomcats);

        if($margs['ids']){

            $aux_arr = explode(',',$margs['ids']);

            $wpqargs['post__in'] = $aux_arr;



        }

        $str_layout = '';

        $str_layout.='dzs-layout--'.$margs['layout'];

        $wpqargs=array_merge($wpqargs,$args_wpqargs);


        $query = new WP_Query($wpqargs);

        $its = $query->posts;

//        print_rr($query);;

        if($margs['style']=='noir' || $margs['style']=='style1' || $margs['style']=='style2'){

            $fout.='<div class="dzsap-grid '.$str_layout.' style-'.$margs['style'].'">';
        }else{
            $fout.='<div class="dzsap-woo-grid style-'.$margs['style'].'">';

        }


        if($margs['style']=='style4'){
            $fout.='<ul class="style-nova">';
        }
        if($margs['style']=='style3'){
            $fout.='<div class="dzsap-header-tr">
                            <div class="column-for-player">'.$this->mainoptions['i18n_play'].'</div>
                            <div class="column-for-title">'.$this->mainoptions['i18n_title'].'</div>
                            <div class="column-for-buy">'.$this->mainoptions['i18n_buy'].'</div>
                        </div>';

//            print_r($its);
        }

        foreach($its as $it){


            $src = get_post_meta($it->ID,'dzsap_woo_product_track',true);

            if($margs['type']=='product'){
                if($src==''){
                    $aux = get_post_meta($it->ID,'_downloadable_files',true);
                    if($aux && is_array($aux)){

                        $aux = array_values($aux);


                        if(isset($aux[0]) && isset($aux[0]['file']) && strpos($aux[0]['file'], '.mp3')!==false){

                            $src = $aux[0]['file'];
                        }
                    }

//                    echo '$aux - ';print_r($aux);
                }
            }

            $type = 'audio';

//            print_r($margs);
            if($margs['type']=='dzsap_items'){
                $src = get_post_meta($it->ID,'dzsap_meta_item_source',true);
                $type = get_post_meta($it->ID,'dzsap_meta_type',true);
            }

            if($margs['type']=='attachment'){
                $src = $it->guid;

//                print_r($margs);
            }

            $buy_link =site_url().'/cart/?add-to-cart='.$it->ID;


//            print_r($it);
//            echo 'hmm';
//            echo dzs_curr_url();
            $buy_link = DZSHelpers::remove_query_arg(dzs_curr_url(), '0');
            $buy_link = DZSHelpers::remove_query_arg(dzs_curr_url(), 'dzswtl_action');
            $buy_link = add_query_arg(array(
                'add-to-cart'=>$it->ID

            ),$buy_link);

            if(strpos($buy_link,'?')===false){

	            $buy_link = str_replace('&add-to-cart','?add-to-cart',$buy_link);
            }

            if(get_post_meta($it->ID,'dzsap_woo_custom_link',true)){

                $buy_link = get_post_meta($it->ID,'dzsap_woo_custom_link',true);
            }



            if($src){

            }else{
                continue;
            }

            if($margs['style']=='noir' || $margs['style']=='style1' || $margs['style']=='style2'){
                $fout.='<div class="dzs-layout-item "';
                $fout.='>';
                $fout.='<div class="grid-object ';


                $fout.='"';
                $fout.='>';
            }else{

                if($margs['style']!='style4') {
                    $fout .= '<div class="grid-object ';

                    if ($src) {
                        $fout .= ' zoomsounds-woo-grid-item';
                    }
                    $fout .= '">';
                }
            }



            $cue = 'on';
            $thumb_url = '';
            $title = '';

            $shortdesc = '';
            $longdesc = '';







            $price = get_post_meta($it->ID, 'dzsap_meta_item_price',true);

            if($margs['type']=='product'){
                if(get_post_meta($it->ID, '_regular_price',true)){
                    $price = '';
                    if(function_exists('get_woocommerce_currency_symbol')){
                        $price.=get_woocommerce_currency_symbol();
                    }
                    $price .= get_post_meta($it->ID, '_regular_price',true);
                }
            }





            if($margs['faketarget']){

//                    $type='fake';

            }

            $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( $it->ID ), 'large' );
            if(is_array($thumb_url) && isset($thumb_url[0])){
                $thumb_url = $thumb_url[0];
            }
            if($margs['type']=='attachment' && get_post_meta($it->ID,'_dzsap-thumb',true)){
                $thumb_url=get_post_meta($it->ID,'_dzsap-thumb',true);
            }


            $html_meta_artist = '';

            $title = $it->post_title;
            $shortdesc = get_post_meta($it->ID,'dzsap_woo_subtitle',true);
            $longdesc = $it->post_excerpt;

            $user_info = get_userdata($it->post_author);
//            print_r($it);
//            print_r($user_info);
            $author_name =  $user_info->data->display_name;

            if($title){
                $html_meta_artist = '<div class="meta-artist"><span class="the-artist">'.$author_name.'</span><span class="the-name">'.$title.'</span></div>';
            }




            $str_pcm = '';

            if ($this->mainoptions['skinwave_wave_mode'] == 'canvas') {
//                print_r($che);



                $args = array(
                    'source'=>$src,
                    'linktomediafile'=>$it->ID,
                    'playerid'=>$it->ID,
                );

                $str_pcm.=$this->generate_pcm($args);
            } else {

            }


            $wavebg = get_post_meta($it->ID,'dzsap_woo_product_track_waveformbg',true);
            $waveprog = get_post_meta($it->ID,'dzsap_woo_product_track_waveformprog',true);

            if($margs['style']=='under'){


//                print_r($it);



                if($margs['faketarget']){

//                    $type='fake';

                }

                $args = array(

                    'source' => $src,
                    'cue' => $cue,
                    'config' => $margs['vpconfig'],
                    'autoplay' => 'off',
                    'show_tags' => 'on',
                    'type' => $type,
                    'faketarget' => $margs['faketarget'],
                    'sample_time_start' => get_post_meta($it->ID,'dzsap_woo_sample_time_start',true),
                    'sample_time_end' => get_post_meta($it->ID,'dzsap_woo_sample_time_end',true),
                    'sample_time_total' => get_post_meta($it->ID,'dzsap_woo_sample_time_total',true),
                    'playerid' => $it->ID,
                    'thumb' => $this->get_thumbnail($it->ID),
                    'thumbnail' => $this->get_thumbnail($it->ID),
                    'called_from' => 'woogrid under',
                );


//                print_r($args);

                $taxonomy = 'dzsap_tags';


	            $term_list = wp_get_post_terms($it->ID, $taxonomy, array("fields" => "all"));


//	            echo '$it->ID - '.$it->ID;print_rr($term_list);





	            $fout.=$this->shortcode_player($args);

            }




            if($margs['style']=='style4'){


//                print_r($it);


                $fout.='<li>

                            <div class="li-thumb" style="background-image: url('.$thumb_url.')">
                                ';







                $args = array(

                    'source' => $src,
                    'cue' => $cue,
                    'extra_classes_player' => 'center-it',
                    'config' => array(

                        'skin_ap' => 'skin-customcontrols'
                    ),
                    'inner_html' => ' <div class="custom-play-btn playbtn-darkround" data-border-radius="5px" data-size="30px"></div>
        <div class="custom-pause-btn pausebtn-darkround" data-border-radius="5px" data-size="30px"></div>

        <div class="meta-artist-con">

            <span class="the-artist">'.$title.'</span>
            <span class="the-name">'.$shortdesc.'</span>
        </div>',
                    'autoplay' => 'off',
                    'type' => $type,
                    'faketarget' => $margs['faketarget'],
                    'sample_time_start' => get_post_meta($it->ID,'dzsap_woo_sample_time_start',true),
                    'sample_time_end' => get_post_meta($it->ID,'dzsap_woo_sample_time_end',true),
                    'sample_time_total' => get_post_meta($it->ID,'dzsap_woo_sample_time_total',true),
                    'playerid' => $it->ID,
                    'called_from' => 'woogrid style4',
                );


//                print_r($args);

                $fout.=$this->shortcode_player($args);

                $fout.='
                            </div>

                            <div class="li-meta"><a class="ajax-link track-title" href="index.php?page=track&track_id=2">'.$title.'</a><div class=" track-by">by '.$author_name.'</div><div class="the-price">'.__("Free").'</div></div>

                        </li>';




            }



            if($margs['style']=='noir'){


//                print_r($it);

//                echo 'test'.$wavebg;


//                print_r($it);



                if($margs['faketarget']){

//                    $type='fake';

                }

                $args = array(

                    'source' => $src,
                    'cue' => $cue,
                    'config' => $margs['vpconfig'],
                    'autoplay' => 'off',
                    'type' => $type,
                    'faketarget' => $margs['faketarget'],
                    'sample_time_start' => get_post_meta($it->ID,'dzsap_woo_sample_time_start',true),
                    'sample_time_end' => get_post_meta($it->ID,'dzsap_woo_sample_time_end',true),
                    'sample_time_total' => get_post_meta($it->ID,'dzsap_woo_sample_time_total',true),
                    'playerid' => $it->ID,
                    'called_from' => 'woogrid noir',
                );


//                print_r($args);

                $fout.=$this->shortcode_player($args);

                $fout.='

                        <h4 class="the-title">'.$title.'</h4>
                        <div class="the-price">'.$price.'</div>

                        <a  href="'.$buy_link.'" class="dzs-button-dzsap padding-small"><span class="the-bg"></span><span class="the-text">'.$this->mainoptions['i18n_buy'].'</span></a>';
            }




            if($margs['style']=='style1'){


//                print_r($it);

//                echo 'test'.$wavebg;


                if($margs['type']=='attachment'){
                    $shortdesc = $it->post_content;
                }

//                print_r($thumb_url);

                $buystring = '<a href="'.$buy_link.'" class="button-buy" style="font-size: 16px;">'.$this->mainoptions['i18n_buy'].'</a>&nbsp;';


                $waveformbg_str = '';
                $waveformprog_str = '';

                if($margs['type']=='attachment'){
                    $buystring = '';

//                    echo 'ceva'.get_post_meta($it->ID,'_waveformbg',true);

                    if(get_post_meta($it->ID,'_waveformbg',true)){
                        $wavebg.=get_post_meta($it->ID,'_waveformbg',true);
                    }

                    if(get_post_meta($it->ID,'_waveformprog',true)){
                        $waveprog=get_post_meta($it->ID,'_waveformprog',true);
                    }

                }

                if($thumb_url){

                    $fout.='<img src="'.$thumb_url.'" class="fullwidth"/>';
                }


                // daon pizda masi
                // test test test test test2134541234567890 123456678o90

//                print_r($margs);


//                echo 'ceva'.$waveformbg_str;

                $fout.='<div class="label-artist"><a href="'.get_permalink($it->ID).'">'.$title.'</a></div>
<div class="label-song">'.$shortdesc.'</div>
<div class="dzsap-grid-meta-buy" style="margin-top: 15px;">
'.$buystring;
                if($src) {
                    $fout .= '
<span href="#" class="button-buy audioplayer-song-changer from-style-1" style="font-size: 16px; background-color: #a861c6" data-fakeplayer="' . $margs['faketarget'] . '"  style="" data-thumb="' . $thumb_url . '"  data-bgimage="img/bg.jpg"';


                    $fout.=$str_pcm;


                    $fout .= ' data-type="' . $type . '" data-playerid="' . $it->ID . '" data-source="' . $src . '" >' . $this->mainoptions['i18n_play'] . '
' . $html_meta_artist . '
</span>';

                }
                $fout.='
</div>';
//$longdesc


            }


            if($margs['style']=='style2'){

//                echo 'ceva';

//                print_r($it);


                if($margs['faketarget']){

//                    $type='fake';

                }

                $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( $it->ID ), 'large' );
                if(is_array($thumb_url) && isset($thumb_url[0])){
                    $thumb_url = $thumb_url[0];
                }
                if($margs['type']=='attachment'){
                    $thumb_url=get_post_meta($it->ID,'_dzsap-thumb',true);
                }



                $title = $it->post_title;
                $shortdesc = get_post_meta($it->ID,'dzsap_woo_subtitle',true);
                $longdesc = $it->post_excerpt;


                if($margs['type']=='attachment'){
                    $shortdesc = $it->post_content;
                }

//                print_r($thumb_url);



                $buystring = '<a href="'.$buy_link.'" class="button-buy" style="font-size: 16px;">'.$this->mainoptions['i18n_buy'].'</a>&nbsp;';

                if($margs['type']=='attachment'){
                    $buystring = '';
                }


                $fout.='<div class="dzsap-grid-style2-item">';

                if(isset($margs['style2_hover']) && $margs['style2_hover']=='play'){

                    $fout.= '<div class="divimage" style="width: 100%; padding-top: 100%; background-image:url('.$thumb_url.');"></div>';
                }else{

                    if($thumb_url){

                        $fout.='<img src="'.$thumb_url.'" class="fullwidth"/>';
                    }

                }
                $fout.='<div class="centered-content-con"><div class="centered-content">';


                if(isset($margs['style2_hover']) && $margs['style2_hover']=='play'){


                    if($src){



                        $args = array(

                            'source' => $src,
                            'cue' => $cue,
                            'extra_classes_player' => 'center-it',
                            'config' => array(

                                'skin_ap' => 'skin-customcontrols'
                            ),
                            'inner_html' => ' <div class="custom-play-btn playbtn-darkround" data-border-radius="5px" data-size="30px"></div>
        <div class="custom-pause-btn pausebtn-darkround" data-border-radius="5px" data-size="30px"></div>

        <div class="meta-artist-con">

            <span class="the-artist">'.$title.'</span>
            <span class="the-name">'.$shortdesc.'</span>
        </div>',
                            'autoplay' => 'off',
                            'type' => $type,
                            'faketarget' => $margs['faketarget'],
                            'sample_time_start' => get_post_meta($it->ID,'dzsap_woo_sample_time_start',true),
                            'sample_time_end' => get_post_meta($it->ID,'dzsap_woo_sample_time_end',true),
                            'sample_time_total' => get_post_meta($it->ID,'dzsap_woo_sample_time_total',true),
                            'playerid' => $it->ID,
                            'called_from' => 'woogrid style2 grid play',
                        );
                        $fout.=$this->shortcode_player($args);



                    }

                }else{
                    $fout.='<div class="label-artist">'.$title.'</div>
<div class="label-song">'.$shortdesc.'</div>
<div class="dzsap-grid-meta-buy" style="margin-top: 15px;">
'.$buystring;


                    if($src){

                        $fout.='
<span href="#" class="button-buy audioplayer-song-changer" style="font-size: 16px; background-color: #a861c6" data-fakeplayer="'.$margs['faketarget'].'"  style="" data-thumb="'.$thumb_url.'"  data-bgimage="img/bg.jpg" data-scrubbg="'.$wavebg.'" data-scrubprog="'.$waveprog.'"  data-playerid="' . $it->ID . '" data-type="'.$type.'" data-source="'.$src.'" >'.$this->mainoptions['i18n_play'].'
'.$longdesc.'
</span>';
                    }
                    $fout.='</div>';
                }


                $fout.='
</div>
</div>';



                $fout.='
</div>';


                if(isset($margs['style2_hover']) && $margs['style2_hover']=='play') {
                    $fout .= '<h3>';
                    $fout .= $title;
                    $fout .= '</h3>';
                }


            }


            if($margs['style']=='style3'){

//                echo 'ceva';

//                print_r($it);


                if($margs['faketarget']){

//                    $type='fake';

                }

                $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( $it->ID ), 'large' );
                if(is_array($thumb_url) && isset($thumb_url[0])){
                    $thumb_url = $thumb_url[0];
                }


                if($margs['type']=='attachment'){
                    $thumb_url=get_post_meta($it->ID,'_dzsap-thumb',true);
                }



                $title = $it->post_title;
                $shortdesc = get_post_meta($it->ID,'dzsap_woo_subtitle',true);
                $longdesc = $it->post_excerpt;



                if(get_permalink($it->ID)){

                    $title = '<a href="'.get_permalink($it->ID).'">'.$title.'</a>';
                }



                if($margs['type']=='attachment'){
                    $shortdesc = $it->post_content;
                }

//                print_r($thumb_url);

//                echo '$buy_link - '.$buy_link;

                $buystring = '<a href="'.$buy_link.'" class="button-buy grid-buy-btn" style="font-size: 16px;">'.$this->mainoptions['i18n_buy'].'</a>';

                if($margs['type']=='attachment'){
                    $buystring = '';
                }

//                print_r($margs);
//                print_r($it);
                $args = array(

                    'source' => $src,
                    'cue' => $cue,
                    'height' => '',
                    'extra_classes_player' => 'position-relative',
                    'config' => array(

                            'skin_ap' => 'skin-customcontrols'
                    ),
                    'autoplay' => 'off',
                    'type' => $type,
                    'faketarget' => $margs['faketarget'],
                    'inner_html' => ' <div class="custom-play-btn position-relative playbtn-darkround" data-border-radius="5px" data-size="30px"></div>
        <div class="custom-pause-btn position-relative pausebtn-darkround" data-border-radius="5px" data-size="30px"></div>

        <div class="meta-artist-con">

            <span class="the-artist">'.$title.'</span>
            <span class="the-name">'.$shortdesc.'</span>
        </div>',
                    'sample_time_start' => get_post_meta($it->ID,'dzsap_woo_sample_time_start',true),
                    'sample_time_end' => get_post_meta($it->ID,'dzsap_woo_sample_time_end',true),
                    'sample_time_total' => get_post_meta($it->ID,'dzsap_woo_sample_time_total',true),
                    'playerid' => $it->ID,
                    'called_from' => ' woo_grid',
                );


//                print_r($args);



                /*
                 *
                 * <div  data-type="audio" class="audioplayer-tobe skin-customcontrols auto-init position-relative "   data-fakeplayer="'.$margs['faketarget'].'"  data-source="'.$src.'"  data-scrubbg="'.$wavebg.'" data-scrubprog="'.$waveprog.'" data-playfrom="last" data-type="'.$type.'" ';


                $fout.=$str_pcm;

                if($thumb_url){

                    $fout.=' data-thumb="'.$thumb_url.'"';
                }

                $fout.='>



    </div>
                 */



                $fout.='<div class="dzsap-product-tr">
<div class="column-for-player">
';
                $fout.=$this->shortcode_player($args);

                $fout.='

</div>';

                $fout.='
<div class="column-for-title">';



                /*
                $fout.=$title;

                if($shortdesc){
                    $fout.='- '.$shortdesc;
                }
                */



                $fout.='';


//                $author_user = get_user_by('id',$it->post_author);

//                print_r($author_user);

                $fout.=$title;

                $fout.=' - '.$author_name;


                $fout.='</div>';


//                print_r($it);




$fout.='<div class="column-for-buy">'.$buystring.'</div>
</div>';



            }


            if($margs['style']=='noir' || $margs['style']=='style1' || $margs['style']=='style2') {
                $fout .= '</div>';
                $fout .= '</div>';
            }else{

                if($margs['style']!='style4') {
                    $fout .= '</div>';
                }
            }
        }


        if($margs['style']=='style4'){
            $fout.='</ul>';
        }
        $fout.='</div>';

//        print_r($its); print_r($margs); echo 'alceva'.$fout;

        return $fout;
    }


    function shortcode_audio($atts, $content = null) {
        global $current_user;

        //print_r($current_user->data);
        //echo 'ceva'.isset($current_user->data->user_nicename);
        //[zoomsounds_player source="pathto.mp3"]
        $this->sliders__player_index++;

        $fout = '';





        $this->front_scripts();

        $margs = array(
            'mp3' => '',
            'config' => 'default',
        );

        $margs = array_merge($margs, $atts);

//        print_r($margs);
        $margs['source'] = $margs['mp3'];
        $margs['config'] = $this->mainoptions['replace_audio_shortcode'];
        $margs['called_from'] = 'audio_shortcode';


	    $audio_attachments = get_posts( array(
		    'post_type' => 'attachment',
		    'post_mime_type' => 'audio'
	    ) );

//	    print_rr($audio_attachments);

	    $pid = 0;
	    foreach ($audio_attachments as $lab => $val){


	        if($val->guid==$margs['source']){
		        $pid = $val->ID;
	            break;
            }
        }

        if($pid){
//	        $po = get_post($pid);

//	        print_rr($po);

            $margs['source']=$pid;
        }


	    if($this->mainoptions['replace_audio_shortcode_extra_args']){
	        try{

	            $arr = json_decode($this->mainoptions['replace_audio_shortcode_extra_args'],true);

//	            echo 'arr - '; print_rr($arr);
	            $margs = array_merge($margs,$arr);
            }catch(Exception $e){


            }
	    }

        if($this->mainoptions['replace_audio_shortcode_play_in_footer']=='on'){
            $margs['play_target']='footer';
        }

        $playerid = '';

        $fout.=$this->shortcode_player($margs, $content);

//        print_r($its); print_r($margs); echo 'alceva'.$fout;

        return $fout;
    }


    function get_track_source($sourceid, &$playerid, &$margs){

//        echo 'ceva = alceva';
        if((intval($sourceid))){
            $player_post_id = intval($sourceid);
            $player_post = get_post(intval($sourceid));


//                echo ' is_int(intval($margs[\'source\']))  - '.is_int(intval($sourceid));
//                echo ' (intval($margs[\'source\']))  - '.(intval($sourceid));

//                print_rr($player_post);


//            print_r($player_post);

            if($player_post && $player_post->post_type=='attachment'){
                $media = wp_get_attachment_url($player_post_id);

//                echo 'media - '.$media;
                $sourceid = $media;
                if($playerid){

                }else{
                    $playerid = $player_post_id;
                    $margs['playerid'] = $player_post_id;
                }

//                    print_r($media);
            }
            if($player_post && $player_post->post_type=='product'){




                $sourceid = get_post_meta($player_post->ID,'dzsap_woo_product_track',true);


                if($sourceid==''){
                    $aux = get_post_meta($player_post->ID,'_downloadable_files',true);
                    if($aux && is_array($aux)){

                        $aux = array_values($aux);


                        if(isset($aux[0]) && isset($aux[0]['file']) && strpos($aux[0]['file'], '.mp3')!==false){

                            $sourceid = $aux[0]['file'];
                        }
                    }

//                    echo '$aux - ';print_r($aux);
                }

                if($playerid){

                }else{
                    $playerid = $player_post_id;
                    $margs['playerid'] = $player_post_id;
                }

//                    print_r($media);
            }
            if($player_post && $player_post->post_type=='dzsap_items'){




                $sourceid = get_post_meta($player_post->ID,'dzsap_meta_item_source',true);





//                    print_r($media);
            }


            if($sourceid==''){
                if(function_exists('get_field')){
                    $arr = get_field('long_preview',$player_post_id);


                    if($arr){

                        $media = wp_get_attachment_url($arr);

//                echo 'media - '.$media;
                        $sourceid = $media;
                    }

                    if($sourceid==''){
                        if(function_exists('get_field')){
                            $arr = get_field('short_preview',$player_post_id);


                            if($arr){

                                $media = wp_get_attachment_url($arr);

//                echo 'media - '.$media;
                                $sourceid = $media;
                            }
                        }
                    }
                }
            }
        }else{


//                echo "WHAA";
            if($sourceid=='{{postid}}'){

                global $post;


                if($post){
                    $player_post = $post;
                }




                $sourceid = get_post_meta($player_post->ID,'dzsap_woo_product_track',true);


                if($sourceid==''){
                    $aux = get_post_meta($player_post->ID,'_downloadable_files',true);
                    if($aux && is_array($aux)){

                        $aux = array_values($aux);


                        if(isset($aux[0]) && isset($aux[0]['file']) && strpos($aux[0]['file'], '.mp3')!==false){

                            $sourceid = $aux[0]['file'];
                        }
                    }

//                    echo '$aux - ';print_r($aux);
                }



                if($margs['playerid']==''){
                    $margs['playerid'] = $player_post->ID;
                }



            }




//                echo 'whaaaa';
        }

        return $sourceid;
    }


    function get_zoomsounds_player_config_settings($config_name){



        $vpsettingsdefault = array(
            'id' => 'default',
            'skin_ap' => 'skin-wave',
            'settings_backup_type' => 'full',
            'skinwave_dynamicwaves' => 'off',
            'skinwave_enablespectrum' => 'off',
            'skinwave_enablereflect' => 'on',
            'skinwave_comments_enable' => 'off',
            'disable_volume' => 'default',
            'playfrom' => 'default',
            'enable_embed_button' => 'off',
            'loop' => 'off',
            'soundcloud_track_id' => '',
            'soundcloud_secret_token' => '',
        );

        $vpsettings = array();

        $vpconfig_k = -1;

        $vpsettings = array();
        $vpconfig_id = $config_name;


        if(is_array($config_name)){


            $vpsettings['settings'] = $config_name;


        }else{

            for ($i = 0; $i < count($this->mainitems_configs); $i++) {
                if ((isset($vpconfig_id)) && ($vpconfig_id == $this->mainitems_configs[$i]['settings']['id'])) {
                    $vpconfig_k = $i;
                }
            }



            if ($vpconfig_k > -1) {
                $vpsettings = $this->mainitems_configs[$vpconfig_k];
            } else {
                $vpsettings['settings'] = $vpsettingsdefault;
            }

            if (is_array($vpsettings) == false || is_array($vpsettings['settings']) == false) {
                $vpsettings = array('settings' => $vpsettingsdefault);
            }
        }

        return $vpsettings;
    }

    function sanitize_from_shortcode_attr($arg){

	    $arg = str_replace('{{lsqb}}','[',$arg);
	    $arg = str_replace('{{rsqb}}',']',$arg);

	    return $arg;
    }

    function show_curr_plays($pargs = array(), $content = '') {

	    $fout = '';


	    $str_views =$this->mainoptions['str_views'];


	    global $post;



	    if(isset($pargs['id'])){
	        $post = get_post($pargs['id']);
        }


	    if($post){

		    $aux = get_post_meta($post->ID, '_dzsap_views', true);
		    if ($aux == '') {
			    $aux = 0;
		    }
		    $fout = str_replace('{{get_plays}}', $aux, $str_views);

//	    $fout.=' ';

	    }
	    return $fout;
    }
    function shortcode_player($pargs = array(), $content = '') {
        //[zoomsounds_player source="pathto.mp3" artistname="" songname=""]
        global $current_user,$post;

        //print_r($current_user->data);
        //echo 'ceva'.isset($current_user->data->user_nicename);
        $this->sliders__player_index++;

        $fout = '';


        $player_idx = $this->sliders__player_index;



        $this->front_scripts();

        $margs = array(
            'width' => '100%',
            'config' => 'default',
            'height' => '300',
            'source' => '',
            'sourceogg' => '',
            'coverimage' => '',
            'waveformbg' => '',
            'waveformprog' => '',
            'cue' => 'auto',
            'loop' => 'off',
            'autoplay' => 'off',
            'show_tags' => 'off',
            'type' => 'audio',
            'player' => '',
            'itunes_link' => '',
            'playerid' => '', // -- if player id okay
            'thumb' => '',
            'thumb_for_parent' => '',
            'mp4' => '',
            'openinzoombox' => 'off',
            'enable_likes' => 'off',
            'enable_downloads_counter' => 'off',
            'enable_views' => 'off',
            'enable_rates' => 'off',
            'playfrom' => 'off',
            'artistname' => 'default',
            'songname' => 'default',
            'single' => 'on',
            'embedded' => 'off',
            'divinsteadofscript' => 'off',
            'init_player' => 'on',
            'faketarget' => '',
            'sample_time_start' => '',
            'sample_time_end' => '',
            'sample_time_total' => '',
            'feed_type' => '',
            'extra_init_settings' => '',
            'player_index' => $player_idx,
            'inner_html' => '',
            'extra_classes' => '',
            'extra_html' => '',
            'js_settings_extrahtml_in_float_right' => '',
            'play_target' => 'default',
            'dzsap_meta_source_attachment_id' => '',
            'outer_comments_field' => '',
            'extra_classes_player' => '',
            'called_from' => 'player',
        );





        $default_margs = array_merge(array(), $margs);

        $margs = array_merge($margs, $pargs);
        $original_player_margs = array_merge($margs, array());

        $original_source = $margs['source'];



        $embed_margs = array();

        foreach ($margs as $lab=>$arg){

            if(isset($margs[$lab])){

                if(isset($default_margs[$lab])==false || $margs[$lab]!==$default_margs[$lab]){
                    $embed_margs[$lab] = $margs[$lab];
                }
            }
        }

//        print_r($embed_margs);





//	    echo ' margs init shortcode_player() - '; print_rr($margs);

        if($margs['feed_type']=='s3'){

            // -- amazon s3
            // todo: maybe move to parse_items

            $path = dirname(__FILE__).'/class_parts/aws/aws-autoloader.php';

//            echo 'file_exists($path) - '.file_exists($path);

            if(file_exists($path)){

//                echo 'ceva';

	            require_once($path);


	            $s3 = null;


	            try{

		            $s3 = new Aws\S3\S3Client(array(

			            'credentials'	=> array(
				            'key'		=> $this->mainoptions['aws_key'],
				            'secret'	=> $this->mainoptions['aws_key_secret']
			            ),
			            'version' => 'latest',
			            'region'  => $this->mainoptions['aws_region']
		            ));
                }catch(Exception $e){


	                echo 'cannot load aws - '; print_rr($e);

		            $credentials = new Credentials($this->mainoptions['aws_key'], $this->mainoptions['aws_key_secret']);

		            $s3_client = new S3Client(array(
			            'version'     => 'latest',
			            'region'      => $this->mainoptions['aws_region'],
			            'credentials' => $credentials
		            ));
                }


                if($s3){


	                $cmd = $s3->getCommand('GetObject', array(
		                'Bucket'						=> $this->mainoptions['aws_bucket'],
		                'Key'    						=> $original_source,
		                'ResponseContentDisposition'	=> 'filename='.str_replace(array('%21', '%2A', '%27', '%28', '%29', '%20'), array('!', '*', '\'', '(', ')', ' '), rawurlencode('ceva'.'.'.pathinfo('ceva', PATHINFO_EXTENSION)))
	                ));

//	            echo 'cmd - '; print_rr($cmd);

	                $req = $s3->createPresignedRequest($cmd, '1 day');
	                $url = (string)$req->getUri();




	                $margs['source'] = $url;
                }


            }else{
                echo 'install amazon s3';
            }


        }


        $playerid = '';


        $player_post = null;
        $player_post_id = 0;


//        echo 'ceva';
//        print_rr($margs);


        if($margs['play_target']=='footer'){
            if(isset($margs['faketarget']) && $margs['faketarget']){

            }else{
                $margs['faketarget'] = '.dzsap_footer';
            }
        }




	    $po = null;


	    if(is_int(intval($margs['source']))){
		    $po = get_post($margs['source']);

		    if($po){
			    if($po->post_type==$this->dbname_mainitems){


				    $margs['post_content'] = $po->post_content;

			    }
		    }


//		    echo "HIER";
	    }


//	    echo 'whaa'; print_rr($margs);
	    if($margs['source']){
//            echo $margs['source'];
//            echo is_int(intval($margs['source']));

            if($this->get_track_source($margs['source'],$playerid, $margs)!=$margs['source']){

//                echo '$margs[\'source\'] - ';print_rr($margs['source']); echo '('.is_numeric($margs['source']).')';
	            if(is_numeric($margs['source'])){

		            if(isset($margs['playerid']) == false || $margs['playerid']==''){

			            $margs['playerid']= $margs['source'];
		            }
	            }
	            $margs['source'] = $this->get_track_source($margs['source'],$playerid, $margs);
            }


        }







//        echo ' margs hier - '; print_rr($margs);


//        print_rr($margs);
        $i = 0;

        // --  here we will detect video player configs and call parse_items To Be Continued...
        // --  audio player configuration setup
        $vpsettingsdefault = array(
            'id' => 'default',
            'skin_ap' => 'skin-wave',
            'settings_backup_type' => 'full',
            'skinwave_dynamicwaves' => 'off',
            'skinwave_enablespectrum' => 'off',
            'skinwave_enablereflect' => 'on',
            'skinwave_comments_enable' => 'off',
            'disable_volume' => 'default',
            'playfrom' => 'default',
            'enable_embed_button' => 'off',
            'loop' => 'off',
            'soundcloud_track_id' => '',
            'soundcloud_secret_token' => '',
            'cue_method' => 'on',
        );

        $vpsettings = array();

        $vpconfig_k = -1;
        $vpconfig_id = $margs['config'];


        if(is_array($margs['config'])){


            $vpsettings['settings'] = $margs['config'];


        }else{

            for ($i = 0; $i < count($this->mainitems_configs); $i++) {
                if ((isset($vpconfig_id)) && ($vpconfig_id == $this->mainitems_configs[$i]['settings']['id'])) {
                    $vpconfig_k = $i;
                }
            }



            if ($vpconfig_k > -1) {
                $vpsettings = $this->mainitems_configs[$vpconfig_k];
            } else {
                $vpsettings['settings'] = $vpsettingsdefault;
            }

            if (is_array($vpsettings) == false || is_array($vpsettings['settings']) == false) {
                $vpsettings = array('settings' => $vpsettingsdefault);
            }
        }

        if($margs['config']=='temp123'){
            $vpsettings = get_option('dzsap_temp_vpconfig');
        }

        //print_r($vpsettings);

	    if(isset($margs['playerid']) && $margs['playerid']){

	    }else{



		    if(is_numeric($margs['source'])){
			    $margs['playerid'] = $margs['source'];
		    }else{

//	            $fout.=' data-player-id="'.dzs_clean_string($che['source']).'"';
			    $margs['playerid'] = $this->encode_to_number($margs['source']);
		    }



		    if($margs['dzsap_meta_source_attachment_id'] && is_numeric($margs['dzsap_meta_source_attachment_id'])){

//			    echo 'whaaa';
			    $margs['playerid'] = $margs['dzsap_meta_source_attachment_id'];
		    }

	    }



//	    echo ' margs hier 2 - '; print_rr($margs);
//	    echo ' margs - '; print_rr($margs);



        if($vpsettings['settings']['skin_ap']=='skin-wave'){
            if($margs['waveformbg']==''){
                $margs['waveformbg']=$this->base_url.'waves/scrubbg_default.png';
            }
            if($margs['waveformprog']==''){
                $margs['waveformprog']=$this->base_url.'waves/scrubprog_default.png';
            }
//            print_r($margs);
        }


        if(is_array($margs['config'])){
            $vpsettings['settings'] = array_merge($vpsettingsdefault, $margs['config']);
        }


        $its = array(0 => $margs, 'settings' => array());

        if(isset($vpsettings['settings']) == false || is_array($vpsettings['settings'])==false){

            $vpsettings['settings'] = array_merge($vpsettingsdefault, array());
        }

        $its['settings'] = array_merge($its['settings'], $vpsettings['settings']);




        if($margs['enable_views']=='on'){
            $its['settings']['enable_views'] = 'on';
        }


        $margs = array_merge($margs, $vpsettings['settings']);


        // -- lets overwrite some settings that we forced from shortcode args



        if(isset($pargs['enable_embed_button']) && $pargs['enable_embed_button']){

            $margs['enable_embed_button'] = $pargs['enable_embed_button'];
        }


//	    echo ' margs hier 2 - '; print_rr($margs);






	    if(isset($margs['js_settings_extrahtml_in_float_right_from_config']) && $margs['js_settings_extrahtml_in_float_right_from_config']){


//            print_rr($its[0]);



		    $margs['js_settings_extrahtml_in_float_right_from_config'] = $this->sanitize_to_extra_html($margs['js_settings_extrahtml_in_float_right_from_config'],$its[0]);


		    $margs['js_settings_extrahtml_in_float_right_from_config'] = str_replace(array("\r", "\r\n", "\n"), '', $margs['js_settings_extrahtml_in_float_right_from_config']);


		    $margs['js_settings_extrahtml_in_float_right'].=do_shortcode($margs['js_settings_extrahtml_in_float_right_from_config']);
	    }


	    if(isset($margs['js_settings_extrahtml_in_bottom_controls_from_config']) && $margs['js_settings_extrahtml_in_bottom_controls_from_config']){
//            $margs['extra_html'].=do_shortcode($margs['js_settings_extrahtml_in_bottom_controls_from_config']);
		    $margs['js_settings_extrahtml_in_bottom_controls_from_config'] = str_replace(array("\r", "\r\n", "\n"), '', $margs['js_settings_extrahtml_in_bottom_controls_from_config']);
		    $margs['extra_html_in_player'].=do_shortcode($margs['js_settings_extrahtml_in_bottom_controls_from_config']);
//            $margs['extra_html_left'].=do_shortcode($margs['js_settings_extrahtml_in_bottom_controls_from_config']);
	    }

        // -- right controls
//        if(isset($margs['js_settings_extrahtml_in_float_right_from_config']) && $margs['js_settings_extrahtml_in_float_right_from_config']){
//
//
//
//
//            $margs['js_settings_extrahtml_in_float_right_from_config'] = str_replace(array("\r", "\r\n", "\n"), '', $margs['js_settings_extrahtml_in_float_right_from_config']);
//
//
//            $margs['js_settings_extrahtml_in_float_right'].=do_shortcode($margs['js_settings_extrahtml_in_float_right_from_config']);
//        }






//        print_rr($vpsettings['settings']);
//        print_rr($margs);
//        print_rr($its);







        if(isset($margs['settings_extrahtml_after_playpause'])){

        }else{
            $margs['settings_extrahtml_after_playpause'] = '';
        }
        if(isset($its[0]['settings_extrahtml_after_playpause'])){

        }else{
            $its[0]['settings_extrahtml_after_playpause'] = '';
        }


        if(isset($margs['settings_extrahtml_after_playpause_from_config']) && $margs['settings_extrahtml_after_playpause_from_config']){
            $margs['settings_extrahtml_after_playpause_from_config'] = str_replace(array("\r", "\r\n", "\n"), '', $margs['settings_extrahtml_after_playpause_from_config']);


            $margs['settings_extrahtml_after_playpause']=do_shortcode($margs['settings_extrahtml_after_playpause_from_config']);
            $its[0]['settings_extrahtml_after_playpause']=do_shortcode($margs['settings_extrahtml_after_playpause_from_config']);
        }





        if(isset($margs['settings_extrahtml_after_con_controls'])){

        }else{
            $margs['settings_extrahtml_after_con_controls'] = '';
        }
        if(isset($its[0]['settings_extrahtml_after_con_controls'])){

        }else{
            $its[0]['settings_extrahtml_after_con_controls'] = '';
        }


        if(isset($margs['settings_extrahtml_after_con_controls_from_config']) && $margs['settings_extrahtml_after_con_controls_from_config']){
            $margs['settings_extrahtml_after_con_controls_from_config'] = str_replace(array("\r", "\r\n", "\n"), '', $margs['settings_extrahtml_after_con_controls_from_config']);

            $margs['settings_extrahtml_after_con_controls'].=do_shortcode($margs['settings_extrahtml_after_con_controls_from_config']);
            $its[0]['settings_extrahtml_after_con_controls'].=do_shortcode($margs['settings_extrahtml_after_con_controls_from_config']);

        }

//        print_rr($margs);




        if(isset($margs['cat_feed_data'])){

            include_once "class_parts/powerpress_cat_feed_data.php";
        }


        if($post && isset($post->ID)){

	        $margs['js_settings_extrahtml_in_float_right'] = str_replace('{{meta1val}}',
		        get_post_meta($post->ID, 'dzsap_meta_extra_meta_label_1',true),
		        $margs['js_settings_extrahtml_in_float_right']
	        );
	        $margs['js_settings_extrahtml_in_float_right'] = str_replace('{{meta2val}}',get_post_meta($post->ID, 'dzsap_meta_extra_meta_label_2',true),$margs['js_settings_extrahtml_in_float_right']);
	        $margs['js_settings_extrahtml_in_float_right'] = str_replace('{{meta3val}}',get_post_meta($post->ID, 'dzsap_meta_extra_meta_label_3',true),$margs['js_settings_extrahtml_in_float_right']);
        }





//        print_r($margs); print_r($its); print_r($vpsettings);

//        print_r($margs);


        $has_extra_html = false;

        if (isset($margs) && ($margs['enable_views'] == 'on' || $margs['enable_downloads_counter'] == 'on' || $margs['enable_likes'] == 'on' || $margs['enable_rates'] == 'on' || (isset($margs['extra_html']) && $margs['extra_html'] ) )) {
            $has_extra_html = true;
        }




	    $margs['extra_html'] = $this->sanitize_from_shortcode_attr($margs['extra_html']);

//        echo 'margs - '; print_rr($margs);


        // -- END sanitize $margs





//	    echo ' margs init shortcode_player() - '; print_rr($margs);


//        $enc_margs = simple_encrypt(json_encode($margs),'1111222233334444');
//        $enc_margs = gzcompress(json_encode($embed_margs),9);
//        $enc_margs = json_encode($embed_margs);


        if(isset($embed_margs['cat_feed_data'])){
            unset($embed_margs['cat_feed_data']);
        }

//        print_rr($embed_margs);
        $enc_margs = base64_encode(json_encode($embed_margs));
//        $enc_margs = base64_decode(base64_encode(json_encode($embed_margs)));

//        $embed_code = '<iframe src=\'' . $this->base_url . 'bridge.php?type=player&margs='.urlencode($enc_margs).'\' style="overflow:hidden; transition: height 0.3s ease-out;" width="100%" height="152" scrolling="no" frameborder="0"></iframe>';
        $embed_code = '<iframe src=\'' . site_url() . '?action=embed_zoomsounds&type=player&margs='.urlencode($enc_margs).'\' style="overflow:hidden; transition: height 0.3s ease-out;" width="100%" height="152" scrolling="no" frameborder="0"></iframe>';
        $embed_code = str_replace('"',"'", $embed_code);
        $embed_code = htmlentities($embed_code, ENT_QUOTES);

        $margs['has_extra_html']=$has_extra_html;
        $margs['embed_code']=$embed_code;
//        echo ' has extra html - '.$has_extra_html;


        if ($margs['openinzoombox'] != 'on') {

//            if(isset($margs['called_from'])==false || $margs['called_from']==''){
//            }
//            $args = array('called_from'=> 'player');
//            $args = array_merge($margs, $args);
//            $fout.='make playir ->';

            if($margs['itunes_link']){

                if(isset($its[0]['extra_html'])==false){
                    $its[0]['extra_html'] = '';
                }

                $its[0]['extra_html'].=' <a href="'. $margs['itunes_link'].'" target="_blank" class=" btn-zoomsounds btn-itunes "><span class="the-icon"><i class="fa fa-apple"></i></span><span class="the-label ">iTunes</span></a>';
            }

//            print_r($margs);
            $margs['the_content']=$content;

//            print_rr($margs);

//            if($margs['songname']=='default'){
//                $margs['songname']='';
//            }
//            if($margs['artistname']=='default'){
//                $margs['artistname']='';
//            }
            if($margs['songname'] && $margs['songname']!='default'){

                if(isset($its[0]['menu_songname'])==false || !($its[0]['menu_songname'] && $its[0]['menu_songname']!='default')){

                    $its[0]['menu_songname'] = $margs['songname'];
                }
            }
            if($margs['artistname'] && $margs['artistname']!='default'){

                if(isset($its[0]['menu_artistname'])==false || !($its[0]['menu_artistname'] && $its[0]['menu_artistname']!='default')){

                    $its[0]['menu_artistname'] = $margs['artistname'];
                }
            }


            if(isset($margs['product_id']) && $margs['product_id']){

	            $pid = $margs['product_id'];



//	            echo 'all meta - '.print_rr(get_post_meta($pid),true);
	            if(get_post_meta($pid,'dzsap_meta_replace_artistname',true)){

		            $its[0]['artistname'] = get_post_meta($pid,'dzsap_meta_replace_artistname',true);
	            }
            }


//            echo 'margs 5-  ';print_rr($margs);



//            $its[0] = $this->sanitize_to_gallery_item($its[0]);


//            print_rr($its);

//            echo 'start parse_items from shortcode_player*()';
//            print_rr($margs);
            $fout .= $this->parse_items($its, $margs);
        }





//        print_rr($margs);

	    $player_id = $margs['playerid'];

//	    print_rr($margs);


	    // -- normal mode
        if($margs['init_player']=='on'){


            wp_enqueue_style( 'dzsap', $this->base_url . 'audioplayer/audioplayer.css');
            wp_enqueue_script( 'dzsap', $this->base_url . 'audioplayer/audioplayer.js', array('jquery'));

            if ($margs['openinzoombox'] != 'on') {
                if($margs['divinsteadofscript']!='on'){
                    $fout.='<script>';
                }else{
                    $fout.='<div class="toexecute">';
                }




//                print_r($its);

//                echo 'what what in the butt: '.$playerid;

                $str_id = '';
                if($margs['playerid']){

                    $str_id.='.ap'.$margs['playerid'];
                }


                $loop = 'off';
                if(isset($vpsettings['settings']['loop']) && $vpsettings['settings']['loop']=='on') {
                    $loop = $vpsettings['settings']['loop'];
                }


//                print_r($margs);
                if(isset($margs['loop']) && $margs['loop']=='on'){
                    $loop = 'on';
                }

                $preload_method = 'metadata';
                $design_animateplaypause = 'off';

                if(isset($vpsettings['settings']['preload_method'])){
                    $preload_method = $vpsettings['settings']['preload_method'];
                }
                if(isset($vpsettings['settings']['design_animateplaypause'])){
                    $design_animateplaypause = $vpsettings['settings']['design_animateplaypause'];
                }

                $fout.=' jQuery(document).ready(function ($){';



                if($this->mainoptions['js_init_timeout']){
                    $fout.=' setTimeout(function(){';
                }


                if($margs['cue']=='auto'){
                    if(isset($its['settings']['cue_method']) && $its['settings']['cue_method']){
                        $margs['cue']=$its['settings']['cue_method'];
                    }else{
                        $margs['cue']='on';
                    }
                }


                $fout.='var settings_ap'.$this->clean($player_id).' = {  design_skin: "' . $vpsettings['settings']['skin_ap'] . '"  ,autoplay: "' . $margs['autoplay'] . '"';


                if(isset($vpsettings['settings']['disable_volume'])){

	                $fout.=',disable_volume:"' . $vpsettings['settings']['disable_volume'] . '"';
                }

                if($this->mainoptions['analytics_enable']=='on'){

	                $fout .= ',action_video_contor_60secs : window.dzsap_wp_send_contor_60_secs';
                }

                $fout.='  ,loop:"' . $loop . '"  ,cue: "' . $margs['cue'] . '"  ,embedded: "' . $margs['embedded'] . '"  ,preload_method:"' . $preload_method . '" ,design_animateplaypause:"' . $design_animateplaypause . '" ,skinwave_dynamicwaves:"' . $vpsettings['settings']['skinwave_dynamicwaves'] . '"  ,skinwave_enableSpectrum:"' . $vpsettings['settings']['skinwave_enablespectrum'] . '"  ,skinwave_enableReflect:"' . $vpsettings['settings']['skinwave_enablereflect'] . '"';



                if(isset($vpsettings['settings']['settings_backup_type'])){
                    $fout.=',settings_backup_type:"' . $vpsettings['settings']['settings_backup_type'] . '"';
                }
                if(isset($vpsettings['settings']['playfrom'])){
                    $fout.=',playfrom:"' . $vpsettings['settings']['playfrom'] . '"';
                }
                if(isset($vpsettings['settings']['default_volume'])){
                    $fout.=',default_volume:"' . $vpsettings['settings']['default_volume'] . '"';
                }
                if(isset($vpsettings['settings']['disable_scrubbar'])){
                    $fout.=',disable_scrub:"' . $vpsettings['settings']['disable_scrubbar'] . '"';
                }


	            if(get_post_meta($player_id,'dzsap_total_time',true)){


//                    echo 'get_post_meta($player_id,\'dzsap_total_time\',true) - '.get_post_meta($player_id,'dzsap_total_time',true);
	            }else{


//                    print_rr($margs);

                    if($margs['type']!='fake' && $this->mainoptions['try_to_cache_total_time']=='on'){

	                    $fout.=',"action_received_time_total":' . 'window.dzsap_send_total_time' . '';
                    }

                }


                if(isset($margs['outer_comments_field']) && $margs['outer_comments_field']=='on'){



                    $fout.=',skinwave_comments_mode_outer_selector: ".zoomsounds-comment-wrapper"';
                }
                if($this->mainoptions['mobile_disable_footer_player']=='on'){
                    if(isset($margs['called_from']) && $margs['called_from']=='footer_player'){

                        $fout.=',mobile_delete: "on"';

                    }else{
                        $fout.=',mobile_disable_fakeplayer: "on"';
                    }
                }


                $fout.=',soundcloud_apikey:"' . $this->mainoptions['soundcloud_api_key'] . '"  ,skinwave_comments_enable:"' . $vpsettings['settings']['skinwave_comments_enable'] . '"';

                $fout.=',settings_php_handler:window.ajaxurl';
                if ($vpsettings['settings']['skinwave_comments_enable'] == 'on') {
                    if (isset($current_user->data->user_nicename)) {
                        $fout.=',skinwave_comments_account:"' . $current_user->data->user_nicename . '"';
                        $fout.=',skinwave_comments_avatar:"' . $this->get_avatar_url(get_avatar($current_user->data->ID, 20)) . '"';
                    }
                }




                if (isset($its['settings']['skinwave_mode']) && $its['settings']['skinwave_mode']) {
                    $fout.=',skinwave_mode:"' . $its['settings']['skinwave_mode'] . '"';
                }


                $fout.=',skinwave_wave_mode:"' . $this->mainoptions['skinwave_wave_mode'] . '"';

//                $this->mainoptions['color_waveformbg'] = str_replace('#','',$this->mainoptions['color_waveformbg']);




                $color_waveformbg =$this->mainoptions['color_waveformbg'];
                $color_waveformprog =$this->mainoptions['color_waveformprog'];
                $skinwave_wave_mode_canvas_waves_number = $this->mainoptions['skinwave_wave_mode_canvas_waves_number'];
                $skinwave_wave_mode_canvas_waves_padding = $this->mainoptions['skinwave_wave_mode_canvas_waves_padding'];
                $skinwave_wave_mode_canvas_reflection_size = $this->mainoptions['skinwave_wave_mode_canvas_reflection_size'];

//                print_rr($vpsettings);


                $lab = 'color_waveformbg';
                if(isset($vpsettings['settings']) && isset($vpsettings['settings'][$lab]) && $vpsettings['settings'][$lab]){
                    $color_waveformbg = $vpsettings['settings'][$lab];
                }
                $lab = 'color_waveformprog';
                if(isset($vpsettings['settings']) && isset($vpsettings['settings'][$lab]) && $vpsettings['settings'][$lab]){
	                $color_waveformprog = $vpsettings['settings'][$lab];
                }
                $lab = 'skinwave_wave_mode_canvas_waves_number';
                if(isset($vpsettings['settings']) && isset($vpsettings['settings'][$lab]) && $vpsettings['settings'][$lab]){
	                $skinwave_wave_mode_canvas_waves_number = $vpsettings['settings'][$lab];
                }

//                echo 'vpsettings - '; print_rr($vpsettings);

                $lab = 'skinwave_wave_mode_canvas_waves_padding';
                if(isset($vpsettings['settings']) && isset($vpsettings['settings'][$lab]) && $vpsettings['settings'][$lab]){
	                $skinwave_wave_mode_canvas_waves_padding = $vpsettings['settings'][$lab];
                }
                $lab = 'skinwave_wave_mode_canvas_reflection_size';
                if(isset($vpsettings['settings']) && isset($vpsettings['settings'][$lab]) && $vpsettings['settings'][$lab]){
	                $skinwave_wave_mode_canvas_reflection_size = $vpsettings['settings'][$lab];
                }

//                echo '$color_waveformbg - '.$color_waveformbg;

                if($this->mainoptions['skinwave_wave_mode']=='canvas'){
                    $fout.=',pcm_data_try_to_generate: "'.$this->mainoptions['pcm_data_try_to_generate'].'"';
                    $fout.=',"pcm_notice": "'.$this->mainoptions['pcm_notice'].'"';
                    $fout.=',"notice_no_media": "'.$this->mainoptions['notice_no_media'].'"';
                    $fout.=',design_color_bg: "'.$this->sanitize_to_hex_color_without_hash($color_waveformbg).'"';
                    $fout.=',design_color_highlight: "'.$this->sanitize_to_hex_color_without_hash($color_waveformprog).'"';
                    $fout.=',skinwave_wave_mode_canvas_waves_number: "'.$this->sanitize_for_javascript_double_quote_value($skinwave_wave_mode_canvas_waves_number).'"';

                    $fout.=',skinwave_wave_mode_canvas_waves_padding: "'.$this->sanitize_for_javascript_double_quote_value($skinwave_wave_mode_canvas_waves_padding).'"';

                    $fout.=',skinwave_wave_mode_canvas_reflection_size: "'.$this->sanitize_for_javascript_double_quote_value($skinwave_wave_mode_canvas_reflection_size).'"';
                }


                if (isset($its['settings']['skinwave_wave_mode_canvas_mode']) && $its['settings']['skinwave_wave_mode_canvas_mode'] ) {
                    $fout.=',skinwave_wave_mode_canvas_mode:"' . $its['settings']['skinwave_wave_mode_canvas_mode'] . '"';
                }


                if (isset($its['settings']['scrubbar_tweak_overflow_hidden']) && $its['settings']['scrubbar_tweak_overflow_hidden'] == 'on') {
                    $fout.=',scrubbar_tweak_overflow_hidden:"' . $its['settings']['scrubbar_tweak_overflow_hidden'] . '"';
                }


                if(isset($this->mainoptions['skinwave_wave_mode_canvas_normalize']) && $this->mainoptions['skinwave_wave_mode_canvas_normalize']=='off'){
                    $fout.=',skinwave_wave_mode_canvas_normalize:"' . $this->mainoptions['skinwave_wave_mode_canvas_normalize'] . '"';
                }
                if (isset($its['settings']['preview_on_hover']) && $its['settings']['preview_on_hover'] ) {
                    $fout.=',preview_on_hover:"' . $its['settings']['preview_on_hover'] . '"';
                }
                if (isset($its['settings']['player_navigation']) && $its['settings']['player_navigation']  && $its['settings']['player_navigation']!='default' ) {
                    $fout.=',player_navigation:"' . $its['settings']['player_navigation'] . '"';
                }


                $lab = 'footer_btn_playlist';
                if ($margs['called_from']=='footer_player' && isset($its['settings'][$lab]) && $its['settings'][$lab]  && $its['settings'][$lab]=='on' ) {
                    $fout.=','.$lab.':"' . $its['settings'][$lab] . '"';
                    $fout.=',construct_player_list_for_sync:"on"';
                }



                $fout.=',skinwave_comments_playerid:"' . $margs['playerid'] . '"';


                if($margs['js_settings_extrahtml_in_float_right']){

                    // -- here we set it
                    $fout.=',settings_extrahtml_in_float_right: \''.$margs['js_settings_extrahtml_in_float_right'].'\'';


                    if(strpos($margs['js_settings_extrahtml_in_float_right'], 'dzsap-multisharer-but')!==false){

                        $this->sw_enable_multisharer = true;

                    }

                }




                if(isset($vpsettings['settings']['restyle_player_over_400']) && $vpsettings['settings']['restyle_player_over_400']){
                    $fout.=',restyle_player_over_400: "'.$vpsettings['settings']['restyle_player_over_400'].'"';
                }
                if(isset($vpsettings['settings']['restyle_player_under_400']) && $vpsettings['settings']['restyle_player_under_400']){
                    $fout.=',restyle_player_under_400: "'.$vpsettings['settings']['restyle_player_under_400'].'"';
                }




                if($margs['embedded']!='on'){

//                    echo ' ceva  - '. (isset($vpsettings['settings']['enable_embed_button']) && ( $vpsettings['settings']['enable_embed_button']=='on' || $vpsettings['settings']['enable_embed_button']=='in_player_controls'  || $vpsettings['settings']['enable_embed_button']=='in_extra_html' || $vpsettings['settings']['enable_embed_button']=='in_lightbox' ));

	                if(isset($vpsettings['settings']['enable_embed_button']) && ( $vpsettings['settings']['enable_embed_button']=='on' || $vpsettings['settings']['enable_embed_button']=='in_player_controls'  || $vpsettings['settings']['enable_embed_button']=='in_extra_html' || $vpsettings['settings']['enable_embed_button']=='in_lightbox' ) ){
		                $str_db = '';
//                echo 'ceva22'.$str;


		                //<span class=\"dzstooltip transition-slidein arrow-bottom align-left skin-black \" style=\"width: 350px; \"><span style=\"max-height: 150px; overflow:hidden; display: block; white-space: normal; font-weight: normal\">{{embed_code}}</span> <span class=\"copy-embed-code-btn\"><i class=\"fa fa-clipboard\"></i> '.__('Copy Embed').'</span> </span>

		                $fout.=',embed_code:"'.$embed_code.'"
';

		                if($has_extra_html){

		                }else{

			                if( $vpsettings['settings']['enable_embed_button']=='on' || $vpsettings['settings']['enable_embed_button']=='in_player_controls' ){

				                $fout.=',enable_embed_button:"'.'on'.'"';
			                }

		                }
	                }
                }




//                print_r($this->mainoptions);

                if($this->mainoptions['failsafe_repair_media_element']=='on'){
                    $fout.=',failsafe_repair_media_element:1000';
                }

                if($this->mainoptions['construct_player_list_for_sync']=='on'){
                    $fout.=',construct_player_list_for_sync:"'.$this->mainoptions['construct_player_list_for_sync'].'"';
                }

                $str_post_id = '';

                if($post){
                    $str_post_id = '_'.$post->ID;
                }



                $fout.=',php_retriever:"' . $this->base_url . 'soundcloudretriever.php" ';

                $fout.=$margs['extra_init_settings'];

$fout.='}; ';


                $fout.='try{ dzsap_init(".ap_idx'.$str_post_id.'_'.$player_idx.'",settings_ap'.$this->clean($player_id).'); }catch(err){ console.warn("cannot init player", err); }';



                if($this->mainoptions['js_init_timeout']){
                    $fout.='}, '.$this->mainoptions['js_init_timeout'].');';
                }

$fout.=' }); ';


                //console.info("inited", $(".ap_idx'.$str_post_id.'_'.$player_idx.'"));

                if($margs['divinsteadofscript']!='on'){
                    $fout.='</script>';
                }else{
                    $fout.='</div>';
                }
            } else {
                // ------ zoombox open

                wp_enqueue_style('ultibox', $this->base_url . 'libs/ultibox/ultibox.css');
                wp_enqueue_script('ultibox', $this->base_url . 'libs/ultibox/ultibox.js');

                $fout.='<a href="' . $margs['source'] . '" data-sourceogg="' . $margs['sourceogg'] . '" data-waveformbg="' . $margs['waveformbg'] . '" data-waveformprog="' . $margs['waveformprog'] . '" data-type="' . $margs['type'] . '" data-coverimage="' . $margs['coverimage'] . '" class="zoombox effect-justopacity">' . $content . '</a>';



                if($margs['divinsteadofscript']!='on'){
                    $fout.='<script>';
                }else{
                    $fout.='<div class="toexecute">';
                }
                $fout.='(function(){
var auxap = jQuery(".audioplayer-tobe").last();
jQuery(document).ready(function ($){
var settings_ap'.$this->clean($player_id).' = {
    design_skin: "' . $vpsettings['settings']['skin_ap'] . '"
    ,skinwave_dynamicwaves:"' . $vpsettings['settings']['skinwave_dynamicwaves'] . '"
    ,disable_volume:"' . $vpsettings['settings']['disable_volume'] . '"
    ,disable_volume:"' . $vpsettings['settings']['loop'] . '"
    ,skinwave_enableSpectrum:"' . $vpsettings['settings']['skinwave_enablespectrum'] . '"
    ,skinwave_enableReflect:"' . $vpsettings['settings']['skinwave_enablereflect'] . '"
    ,skinwave_comments_enable:"' . $vpsettings['settings']['skinwave_comments_enable'] . '"';

                $fout.=',settings_php_handler:window.ajaxurl';
                if (isset($vpsettings['settings']['settings_backup_type']) && $vpsettings['settings']['settings_backup_type']) {
                    $fout.=',settings_backup_type:"' . $vpsettings['settings']['settings_backup_type'] . '"';
                }
                if ($vpsettings['settings']['skinwave_comments_enable'] == 'on') {
                    if (isset($current_user->data->user_nicename)) {
                        $fout.=',skinwave_comments_account:"' . $current_user->data->user_nicename . '"';
                        $fout.=',skinwave_comments_avatar:"' . $this->get_avatar_url(get_avatar($current_user->data->ID, 20)) . '"';
                        $fout.=',skinwave_comments_playerid:"' . $margs['playerid'] . '"';
                    }
                }


                if(isset($vpsettings['settings']['disable_scrubbar'])){
                    $fout.=',disable_scrub:"' . $vpsettings['settings']['disable_scrubbar'] . '"';
                }

                $fout.='
};
$(".zoombox").zoomBox({audioplayer_settings: settings_ap'.$this->clean($player_id).'});
});
})();';

                if($margs['divinsteadofscript']!='on'){
                    $fout.='</script>';
                }else{
                    $fout.='</div>';
                }


            }
        }


	    if ($this->mainoptions['analytics_enable'] == 'on') {

		    if(current_user_can('manage_options')){

//		        print_rr($margs);


		        if($margs['called_from']!='footer_player'){

			        $fout .= '<div class="extra-btns-con">';
			        $fout .= '<span class="btn-zoomsounds stats-btn" data-playerid="'.$margs['playerid'].'"><span class="the-icon"><i class="fa fa-tachometer" aria-hidden="true"></i></span><span class="btn-label">'.esc_html__('Stats','dzsap').'</span></span>';
			        $fout .= '</div>';
                }



			    wp_enqueue_script('audioplayer-showcase',$this->base_url.'libs/audioplayer_showcase/audioplayer_showcase.js');
			    wp_enqueue_style('audioplayer-showcase',$this->base_url.'libs/audioplayer_showcase/audioplayer_showcase.css');
			    wp_enqueue_style('fontawesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');


//			    wp_enqueue_style('dzsvg_showcase', $this->thepath . 'front-dzsvp.css');
//			    wp_enqueue_script('dzsvg_showcase', $this->thepath . 'front-dzsvp.js');
		    }




	    }



//        print_r($its); print_r($margs); echo 'alceva'.$fout;

        wp_enqueue_script('dzsap', $this->base_url . "audioplayer/audioplayer.js");
        wp_enqueue_style('dzsap', $this->base_url . 'audioplayer/audioplayer.css');
        return $fout;
    }

    function get_avatar_url($arg) {
        preg_match("/src='(.*?)'/i", $arg, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return '';
    }

    function log_event($arg) {
        $fil = dirname(__FILE__) . "/log.txt";
        $fh = @fopen($fil, 'a');
        @fwrite($fh, ($arg . "\n"));
        @fclose($fh);
    }


    function get_post_meta_all($argid){
        $arr = get_post_meta($argid);

        print_rr($arr);

        return $arr;
    }
	function encode_to_number($string) {
		return substr(sprintf("%u", crc32($string)),0,8);
		$ans = array();
		$string = str_split($string);
		#go through every character, changing it to its ASCII value
		for ($i = 0; $i < count($string); $i++) {

			#ord turns a character into its ASCII values
			$ascii = (string) ord($string[$i]);

			#make sure it's 3 characters long
			if (strlen($ascii) < 3)
				$ascii = '0'.$ascii;
			$ans[] = $ascii;
		}

		#turn it into a string
		return implode('', $ans);
	}

	function shortcode_wishlist($pargs = array()) {



		$margs = array(

		);




		$arr_wishlist= $this->get_wishlist();


//		print_rr($arr_wishlist);



		 $fout = '';
		 $fout .= '<div class="dzsap-wishlist">';


		 if(get_current_user_id()){

			 foreach ($arr_wishlist as $pl){


				 $fout.=$this->shortcode_player(array(
					 'source'=>$pl,
					 'called_from'=>'shortcode_wishlist',
					 'config'=>'wishlist-player',
				 ));
			 }
         }else{
		     $fout.='<div class="dzsap-warning warning">'.esc_html__("You need to be logged in to have a wishlist.").'</div>';
         }

		$fout .= '</div>';

		 return $fout;
	}
	function shortcode_showcase($pargs = array()) {


	    $fout = '';


	    $margs = array(
		    'feed_from' => 'audio_items',
		    'mode' => 'scrollmenu',
		    'desc_count' => 'default',
		    'desc_readmore_markup' => 'default',
		    'max_videos' => '',
		    'cat' => '',
		    'paged' => '',


		    'count' => '5',
		    'ids' => '',
		    'style' => 'playlist',
		    'order' => 'DESC',
		    'orderby' => 'date',
	    );



	    if (!is_array($pargs)) {
		    $pargs = array();
	    }
	    $margs = array_merge($margs, $pargs);

	    //[zoomsounds_showcase feed_from="audio_items" ids="1,2,3"]
        include_once("class_parts/front_shortcode_showcase.php");

	    return $fout;

    }

    function get_thumbnail($id, $pargs = array()){

	    $margs = array(
		    'size'=>'thumbnail'
	    );
	    if (!is_array($pargs)) {
		    $pargs = array();
	    }
	    $margs = array_merge($margs, $pargs);

        $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'thumbnail');
        //                echo 'ceva'; print_r($imgsrc);


        //            print_r($author_data);


        $thumb = '';

        if ($imgsrc) {

            if (is_array($imgsrc)) {
                $thumb = $imgsrc[0];
            } else {
                $thumb = $imgsrc;
            }

        } else {
            if (get_post_meta($id, 'dzsvp_thumb', true)) {
                $thumb = get_post_meta($id, 'dzsvp_thumb', true);
            }else{
	            if (get_post_meta($id, 'dzsap_meta_item_thumb', true)) {
		            $thumb = get_post_meta($id, 'dzsap_meta_item_thumb', true);
	            }
            }
        }

        return $thumb;
    }

    function get_views_for_track($argid){
        return get_post_meta($argid,'_dzsap_views',true);
    }
    function get_likes_for_track($argid){

//        echo 'argid - '.$argid.' get_post_meta($argid,\'_dzsap_likes\',true) -  '.get_post_meta($argid,'_dzsap_likes',true).'<br><br>';

        if(get_post_meta($argid,'_dzsap_likes',true)){

	        return get_post_meta($argid,'_dzsap_likes',true);
        }else{
            return 0;
        }
    }
    function transform_to_array_for_parse($argits, $pargs = array()) {

        global $post;
        $margs = array(
            'type' => 'video_items',
            'mode' => 'posts',
        );

        if (!is_array($pargs)) {
            $pargs = array();
        }
        $margs = array_merge($margs, $pargs);


        $its = array();


//        print_r($argits);

        foreach ($argits as $it) {


//            print_r($it);


            $aux25 = array();

            $aux25['extra_classes'] = '';


            if ($margs['feed_from'] == 'audio_items') {
                $it_id = $it->ID;
                $aux25['id'] = $it->ID;
                $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($it_id), "full");
//                echo 'ceva'; print_r($imgsrc);


//            print_r($author_data);


                if ($imgsrc) {

                    if (is_array($imgsrc)) {
                        $aux25['thumbnail'] = $imgsrc[0];
                    } else {
                        $aux25['thumbnail'] = $imgsrc;
                    }

                } else {
                    if (get_post_meta($it_id, 'dzsvp_thumb', true)) {
                        $aux25['thumbnail'] = get_post_meta($it_id, 'dzsvp_thumb', true);
                    }
                }


                $aux25['type'] = get_post_meta($it_id, 'dzsvp_item_type', true);
                $aux25['date'] = $it->post_date;


//                print_r($margs);

                if(isset($margs['orderby'])){

                    if($margs['orderby']=='views'){

                        $aux25['views'] = $this->get_views_for_track($it_id);
                    }
                    if($margs['orderby']=='likes'){

                        $aux25['likes'] = $this->get_likes_for_track($it_id);

                        if($this->get_likes_for_track($it_id)){
//                            echo 'found likes - '; print_rr($aux25);
                        }
                    }
                }


//                $aux = get_post_meta($it_id, 'dzsap_woo_product_track', true);


                $args = array();
                $aux = $this->get_track_source($it_id, $it_id, $args);





                $aux25['source'] = $aux;

//                echo 'aux - '.$aux;



	            $thumb = $this->get_post_thumb_src($it_id);

//            echo ' thumb - ';
//            print_r($thumb);


	            $thumb_from_meta = get_post_meta($it_id, 'dzsap_meta_item_thumb',true);

	            if($thumb_from_meta){

		            $thumb = $thumb_from_meta;
	            }

	            if($thumb){
//                $its[$lab]->thumbnail = $thumb;
		            $aux25['thumbnail'] = $thumb;
	            }





	            $aux25['title'] = $it->post_title;
                $aux25['id'] = $it_id;


                $aux25['permalink'] = get_permalink($it_id);
                $aux25['permalink_to_post'] = get_permalink($it_id);

//                if ($margs['linking_type'] == 'zoombox') {
//                    $aux25['permalink'] = $aux25['source'];
//                }




//                print_r($margs);


//                print_r($it);


                $maxlen = $margs['desc_count'];

//            print_r($margs);

                if ($maxlen == 'default') {

                    if ($margs['mode'] == 'scrollmenu') {
                        $maxlen = 50;
                    }
                }
                if ($maxlen == 'default') {
                    $maxlen = 100;
                }


                if ($margs['desc_readmore_markup'] == 'default') {
                    if ($margs['mode'] == 'scrollmenu') {
                        $margs['desc_readmore_markup'] = ' <span style="opacity:0.75;">[...]</span>';
                    }
                }
                if ($margs['desc_readmore_markup'] == 'default') {
                    $margs['desc_readmore_markup'] = '';
                }








//                $aux25['description'] = $this->sanitize_description($it->post_content, array('desc_count' => intval($maxlen), 'striptags' => 'on', 'try_to_close_unclosed_tags' => 'on', 'desc_readmore_markup' => $margs['desc_readmore_markup'],));


                if ($post && $post->ID === $it_id) {
                    $aux25['extra_classes'] .= ' active';
                }





	            $user_info = get_userdata($post->post_author);

	            if($user_info->first_name){

		            $aux25['artistname'] = $user_info->last_name .  " " . $user_info->first_name;
	            }else{

		            $aux25['artistname'] = $user_info->user_login;
	            }


	            if(get_post_meta($it_id,'dzsap_meta_replace_artistname',true)){

		            $aux25['artistname'] = get_post_meta($it_id,'dzsap_meta_replace_artistname',true);
	            }

	            if(get_post_meta($it_id,'dzsap_meta_replace_menu_artistname',true)){

		            $aux25['menu_artistname'] = get_post_meta($it_id,'dzsap_meta_replace_menu_artistname',true);
	            }

	            if(get_post_meta($it_id,'dzsap_meta_replace_menu_songname',true)){

		            $aux25['menu_songname'] = get_post_meta($it_id,'dzsap_meta_replace_menu_songname',true);
	            }



	            $aux25['sourceogg'] = '';


	            $aux25['songname'] = $post->post_title;

//                echo 'aux25';
//                print_rr($aux25);
                array_push($its, $aux25);
            }


        }


        return $its;

    }



    function shortcode_playlist($atts){

        //[playlist ids="2,3,4"]

        global $current_user;
        $fout = '';
        $iout = ''; //items parse

        $margs = array(
            'ids' => '1'
        , 'embedded_in_zoombox' => 'off'
        , 'embedded' => 'off'
        , 'db' => 'main'
        );

        if ($atts == '') {
            $atts = array();
        }

        $margs = array_merge($margs, $atts);


//        print_rr($margs);

        $po_array = explode(",", $margs['ids']);

        $fout.='[zoomsounds id="playlist_gallery" embedded="'.$margs['embedded'].'" for_embed_ids="'.$margs['ids'].'"]';






        //===setting up the db
        $currDb = '';
        if (isset($margs['db']) && $margs['db'] != '') {
            $this->currDb = $margs['db'];
            $currDb = $this->currDb;
        }
        $this->dbs = get_option($this->dbname_dbs);

        //echo 'ceva'; print_r($this->dbs);
        if ($currDb != 'main' && $currDb != '') {
            $this->dbname_mainitems.='-' . $currDb;
            $this->mainitems = get_option($this->dbname_mainitems);
        }
        //===setting up the db END






        $this->front_scripts();



        $this->sliders_index++;


        $i = 0;
        $k = 0;
        $id = 'playlist_gallery';
        if (isset($margs['id'])) {
            $id = $margs['id'];
        }

        //echo 'ceva' . $id;
        for ($i = 0; $i < count($this->mainitems); $i++) {
            if ((isset($id)) && ($id == $this->mainitems[$i]['settings']['id']))
                $k = $i;
        }


        for ($i = 0; $i < count($this->mainitems); $i++) {
            if ((isset($id)) && ($id == $this->mainitems[$i]['settings']['id']))
                $k = $i;
        }

        $its = $this->mainitems[$k];


//        print_r($its);

        $enable_likes = 'off';

        $enable_views = 'off';
        $enable_downloads_counter = 'off';

        if($its){
            if($its['settings']['enable_views']){
                $enable_views = $its['settings']['enable_views'];
            }
            if($its['settings']['enable_likes']){
                $enable_likes = $its['settings']['enable_likes'];
            }
            if($its['settings']['enable_downloads_counter']){
                $enable_downloads_counter = $its['settings']['enable_downloads_counter'];
            }
        }



        foreach($po_array as $po_id){


            if(is_numeric($po_id)){

                $po = get_post($po_id);

//            print_r($po);


                $waveformbg=$this->base_url.'waves/scrubbg_default.png';
                $waveformprog=$this->base_url.'waves/scrubprog_default.png';

                if(get_post_meta($po_id,'_waveformbg',true)){
                    $waveformbg = get_post_meta($po_id,'_waveformbg',true);
                }
                if(get_post_meta($po_id,'_waveformprog',true)){
                    $waveformprog = get_post_meta($po_id,'_waveformprog',true);
                }

//            echo 'ceva2'.(get_post_meta($po_id,'_waveformprog',true));

//            print_r(wp_get_attachment_metadata($po_id));


                $title = $po->post_title;
                $title = str_replace(array('"', '[',']'),'&quot;',$title);
                $desc = $po->post_content;
                $desc = str_replace(array('"', '[',']'),'&quot;',$desc);
                $fout.='[zoomsounds_player source="'.$po->guid.'" config="playlist_player" playerid="'.$po_id.'" thumb="" autoplay="on" cue="on" enable_likes="'.$enable_likes.'" enable_views="'.$enable_views.'"  enable_downloads_counter="'.$enable_downloads_counter.'" songname="'.$title.'" artistname="'.$desc.'" init_player="off"]';
            }else{

                $fout.='[zoomsounds_player source="'.$po_id.'" config="playlist_player" playerid="'.$po_id.'" thumb="" autoplay="off" cue="on" enable_likes="'.$enable_likes.'" enable_views="'.$enable_views.'"  enable_downloads_counter="'.$enable_downloads_counter.'"  init_player="off"]';
            }

        }
        $fout.='[/zoomsounds]';



//        echo 'fout - '.$fout;
        $fout=do_shortcode($fout);

//        print_r($margs);

        return $fout;
    }



    function sanitize_to_gallery_item($che){

        $po_id = $che->ID;



        $che = (array) $che;


	    $user_info = get_userdata($che['post_author']);

	    if(isset($user_info) && $user_info && isset($user_info->first_name) && $user_info->first_name){

		    $che['artistname'] = $user_info->last_name .  " " . $user_info->first_name;
        }else{

		    if(isset($user_info->user_login)) {
			    $che['artistname'] = $user_info->user_login;
		    }
        }


        if(get_post_meta($po_id,'dzsap_meta_replace_artistname',true)){

	        $che['artistname'] = get_post_meta($po_id,'dzsap_meta_replace_artistname',true);
        }

        if(get_post_meta($po_id,'dzsap_meta_replace_menu_artistname',true)){

	        $che['menu_artistname'] = get_post_meta($po_id,'dzsap_meta_replace_menu_artistname',true);
        }

        if(get_post_meta($po_id,'dzsap_meta_replace_menu_songname',true)){

	        $che['menu_songname'] = get_post_meta($po_id,'dzsap_meta_replace_menu_songname',true);
        }



        $che['sourceogg'] = '';
        $che['source'] = get_post_meta($po_id,'dzsap_meta_item_source',true);

        $che['songname'] = $che['post_title'];
        $che['playfrom'] = '0';
        $che['thumb'] = get_post_meta($po_id,'dzsap_meta_item_thumb',true);
        $che['type'] = get_post_meta($po_id,'dzsap_meta_type',true);
        $che['playerid'] = $po_id;




//        echo 'get_post_meta - '; print_rr(get_post_meta($po_id));


        // -- sanitize to gallery item
        foreach ($this->options_item_meta as $oim){



	        if(isset($oim['name'])){
            if($oim['name']==='post_content'){
                continue;
            }


		        $long_name = $oim['name'];
	            $short_name = str_replace('dzsap_meta_','',$oim['name']);



		        $che[$oim['name']]=get_post_meta($po_id,$oim['name'],true);
		        $che[$short_name] = get_post_meta($po_id,$long_name,true);
            }else{
	            continue;
            }
        }



        $lab = 'dzsap_meta_source_attachment_id';
        if(get_post_meta($po_id,$lab,true)){
            $che[$lab]=get_post_meta($po_id,$lab,true);
        }


        return $che;
    }


    function show_shortcode($atts=array(), $content=null) {

        //[zoomsounds id="thheid"]

        global  $current_user;
        $fout = '';
        $iout = ''; //items parse

        $margs = array(
            'id' => 'default'
        , 'db' => ''
        , 'category' => ''
        , 'extra_classes' => ''
        , 'fullscreen' => 'off'
        , 'settings_separation_mode' => 'normal'  // === normal ( no pagination ) or pages or scroll or button
        , 'settings_separation_pages_number' => '5'//=== the number of items per 'page'
        , 'settings_separation_paged' => '0'//=== the page number
        , 'return_onlyitems' => 'off' // ==return only the items ( used by pagination )
        , 'playerid' => ''
        , 'embedded' => 'off'
        , 'divinsteadofscript' => 'off'
        , 'width' => '-1'
        , 'height' => '-1'
        , 'embedded_in_zoombox' => 'off'
        , 'for_embed_ids' => ''
        , 'single' => 'off'
        , 'play_target' => 'default'
        );

        if ($atts == '') {
            $atts = array();
        }

        $margs = array_merge($margs, $atts);




        //===setting up the db
        $currDb = '';
        if (isset($margs['db']) && $margs['db'] != '') {
            $this->currDb = $margs['db'];
            $currDb = $this->currDb;
        }
        $this->dbs = get_option($this->dbname_dbs);



        $this->db_read_mainitems();








        //echo 'ceva'; print_r($this->dbs);
	    if($this->mainoptions['playlists_mode']=='normal'){

	    }else{
		    if ($currDb != 'main' && $currDb != '') {
			    $this->dbname_mainitems.='-' . $currDb;
			    $this->mainitems = get_option($this->dbname_mainitems);
		    }
	    }

        //===setting up the db END




        if ($this->mainitems == '') {
            return;
        }

        $this->front_scripts();



        $this->sliders_index++;


        $i = 0;
        $k = 0;
        $id = 'default';

        $its = array(
                'settings'=>array(),
        );
	    $selected_term_id='';



	    if($this->mainoptions['playlists_mode']=='normal') {
		    $tax = $this->taxname_sliders;


		    $reference_term = get_term_by('slug',$margs['id'],$tax);
		    $reference_term_name = $reference_term->name;
		    $reference_term_slug = $reference_term->slug;

		    $selected_term_id = $reference_term->term_id;

		    $term_meta = get_option( "taxonomy_$selected_term_id" );






//	        print_rr($reference_term);




	        // -- main order
	        if($selected_term_id) {

		        $args = array(
			        'post_type'   => 'dzsap_items',
			        'numberposts' => - 1,
			        'posts_per_page' => - 1,
			        //                'meta_key' => 'dzsap_meta_order_'.$selected_term,

			        'orderby'    => 'meta_value_num',
			        'order'      => 'ASC',

			        'tax_query'  => array(
				        array(
					        'taxonomy' => $tax,
					        'field'    => 'id',
					        'terms'    => $selected_term_id // Where term_id of Term 1 is "1".
				        )
			        ),
		        );


//		        print_rr($term_meta);




                if(isset($term_meta['orderby'])){
                    if($term_meta['orderby']=='rand'){
                        $args['orderby']=$term_meta['orderby'];
                    }
                    if($term_meta['orderby']=='custom'){
                        $args['meta_query']= array(
	                        'relation' => 'OR',
	                        array(
		                        'key'     => 'dzsap_meta_order_' . $selected_term_id,
		                        //                        'value' => '',
		                        'compare' => 'EXISTS',
	                        ),
	                        array(
		                        'key'     => 'dzsap_meta_order_' . $selected_term_id,
		                        //                        'value' => '',
		                        'compare' => 'NOT EXISTS'
	                        )
                        );
                    }
                    if($term_meta['orderby']=='ratings_score'){
                        $args['orderby']='meta_value_num';

                        $key = '_dzsap_rate_index';
	                    $args['meta_query']= array(
		                    'relation' => 'OR',
		                    array(
			                    'key'     => $key,
			                    'compare' => 'EXISTS',
		                    ),
		                    array(
			                    'key'     => $key,
			                    'compare' => 'NOT EXISTS'
		                    )
	                    );
                        $args['meta_type']='NUMERIC';
                        $args['order']='DESC';

                    }
                    if($term_meta['orderby']=='ratings_number'){
                        $args['orderby']='meta_value_num';

	                    $key = '_dzsap_rate_nr';
	                    $args['meta_query']= array(
		                    'relation' => 'OR',
		                    array(
			                    'key'     => $key,
			                    'compare' => 'EXISTS',
		                    ),
		                    array(
			                    'key'     => $key,
			                    'compare' => 'NOT EXISTS'
		                    )
	                    );
                        $args['meta_type']='NUMERIC';
                        $args['order']='DESC';

//                        echo 'cev';

//                        print_rr($args);
                    }
                }
		        $my_query = new WP_Query( $args );

//            print_r($my_query);


//            print_r($my_query->posts);

		        foreach ( $my_query->posts as $po ) {

//                print_r($po);





                    $por = $this->sanitize_to_gallery_item($po);

                    array_push($its, $por);

//			        print_rr($po);
		        }
	        }


        }else{

	        if (isset($margs['id'])) {
		        $id = $margs['id'];
	        }

	        //echo 'ceva' . $id;
	        for ($i = 0; $i < count($this->mainitems); $i++) {

	            if(isset($this->mainitems[$i]) && isset($this->mainitems[$i]['settings'])){

		            if ((isset($id)) && ($id == $this->mainitems[$i]['settings']['id'])) {
			            $k = $i;
		            }
                }
	        }
	        $its = $this->mainitems[$k];
        }





	    $its_settings_default = array(
		    'galleryskin'=>'skin-wave',
		    'vpconfig'=>'default',
		    'bgcolor'=>'transparent',
		    'width'=>'',
		    'height'=>'',
		    'autoplay'=>'',
		    'autoplaynext'=>'on',
		    'autoplay_next'=>'',
		    'menuposition'=>'bottom',
	    );
	    if($this->mainoptions['playlists_mode']=='normal'){

		    $its_settings_default['id']=$selected_term_id;

	    }

	    $its['settings'] = array_merge($its_settings_default, $its['settings']);




	    if($this->mainoptions['playlists_mode']=='normal'){






//		    print_rr($term_meta);



            if(is_array($term_meta)){

	            foreach ($term_meta as $lab => $val){
		            if($lab=='autoplay_next'){

			            $lab = 'autoplaynext';
		            }
		            $its['settings'][$lab]=$val;

	            }
            }




	    }



        //print_r($this->mainitems);
        // -- audio player configuration setup
        $vpsettingsdefault = array(
            'id' => 'default',
            'skin_ap' => 'skin-wave',
            'settings_backup_type' => 'full',
            'skinwave_dynamicwaves' => 'off',
            'skinwave_enablespectrum' => 'off',
            'skinwave_enablereflect' => 'on',
            'skinwave_comments_enable' => 'off',
            'skinwave_mode' => 'normal',
            'playfrom' => 'default',
            'enable_embed_button' => 'off',
            'loop' => 'off',
            'preload_method' => 'metadata',
            'cue_method' => 'on',
        );

        $vpsettings = array();


        $i = 0;
        $vpconfig_k = -1;
        $vpconfig_id = $its['settings']['vpconfig'];
        for ($i = 0; $i < count($this->mainitems_configs); $i++) {
            if ((isset($vpconfig_id)) && ($vpconfig_id == $this->mainitems_configs[$i]['settings']['id'])) {
                $vpconfig_k = $i;
            }
        }

        if ($vpconfig_k > -1) {
            $vpsettings = $this->mainitems_configs[$vpconfig_k];
        } else {
            $vpsettings['settings'] = $vpsettingsdefault;
        }

        //print_r($this->mainitems_configs); echo $its['settings']['vpconfig'];


        if (!isset($vpsettings['settings']) || $vpsettings['settings'] == '') {
            $vpsettings['settings'] = array();
        }


        //print_r($vpsettings);

        $vpsettings['settings'] = array_merge($vpsettingsdefault, $vpsettings['settings']);

        unset($vpsettings['settings']['id']);
        //print_r($vpsettings);

        $its['settings'] = array_merge($its['settings'], $vpsettings['settings']);


        //this works only for the zoomsounds_player shortcode ==== not anymore hahaha
//        $its['settings']['skinwave_comments_enable'] = 'off';
        //print_r($its);
        // ===== some sanitizing
        $tw = $its['settings']['width'];
        $th = $its['settings']['height'];


        if($margs['width']!='-1'){
            $tw = $margs['width'];
        }
        if($margs['height']!='-1'){
            $th = $margs['height'];
        }






        $str_tw = '';
        $str_th = '';

        if ($tw != '') {
            $str_tw.='width: ';
            if (strpos($tw, "%") === false && strpos($tw, "auto") === false) {
                $str_tw .= $tw . 'px';
            }
            $str_tw.=';';
        }

        if ($th != '') {
            $str_th.='height: ';
            if (strpos($th, "%") === false && $th != 'auto' && $th != '') {
                $str_th .= $th . 'px';
            }
            $str_th.=';';
        }


        $galleryskin = 'skin-wave';

        if(isset($its['settings']['galleryskin'])){
            $galleryskin=$its['settings']['galleryskin'];
        }


//        print_rr($its);


        if(isset($its['settings']['colorhighlight']) && $its['settings']['colorhighlight']){

            $fout.='<style class="audiogallery-style">';
            if($its['settings']['skin_ap']=='skin-wave'){


                $fout.='.audiogallery#ag'.$this->sliders_index.' .audioplayer .player-but .the-icon-bg, .audiogallery#ag'.$this->sliders_index.' .audioplayer .playbtn .the-icon-bg , .audiogallery#ag'.$this->sliders_index.' .audioplayer .pausebtn .the-icon-bg, .audiogallery#ag'.$this->sliders_index.' .audioplayer.skin-wave .ap-controls .scrubbar .scrubBox-hover { background-color: #'.$its['settings']['colorhighlight'].';   border-color: #'.$its['settings']['colorhighlight'].';   }  
                .audiogallery#ag'.$this->sliders_index.' .audioplayer .meta-artist .the-artist { color: #'.$its['settings']['colorhighlight'].';}  
                
                ';
            }


            if($its['settings']['skin_ap']=='skin-pro'){

                $selector = '.audiogallery#ag'.$this->sliders_index.' .audioplayer.skin-pro';
                $fout.=$selector.' .ap-controls .scrubbar .scrub-prog{  background-color: #'.$its['settings']['colorhighlight'].';  }';
            }

            $fout.='</style>';

        }



        if(isset($its['settings']['enable_bg_wrapper']) && $its['settings']['enable_bg_wrapper']=='on'){

            $fout.='<div class="ap-wrapper">
<div class="the-bg"></div>';
        }
        $fout.='<div id="ag' . $this->sliders_index . '" class="audiogallery '.$galleryskin.' id_' . $its['settings']['id'] . ' ';


        if($margs['extra_classes']){
            $fout.=' '.$margs['extra_classes'];
        }




        $fout.='" style="background-color:' . $its['settings']['bgcolor'] . ';' . $str_tw . '' . $str_th . '">';



        //$fout.=$this->parse_items($its, $margs);

//        print_r($its); print_r($margs);
        if($content){


//            echo 'do_shortcode(content); '; $content. ' '.do_shortcode($content);

            $iout.=do_shortcode($content);
        }else{

            $args = array(
                    'called_from' => 'gallery',
                    'gallery_skin' => $galleryskin,
            );
            $args = array_merge($vpsettings['settings'], $args);
            $args = array_merge($args, $margs);

//            print_rr($its);



            $iout.=$this->parse_items($its, $args);
        }

        $fout.='<div class="items">';
        $fout.=$iout;


        $fout.='</div>';
        $fout.='</div>'; // -- end .audiogallery



	    if(isset($its['settings']['enable_bg_wrapper']) && $its['settings']['enable_bg_wrapper']=='on'){
		    $fout.='</div>';
	    }

        if($margs['divinsteadofscript']!='on'){
            $fout.='<script>';
        }else{
            $fout.='<div class="toexecute">';
        }



        $fout.='jQuery(document).ready(function ($) {
        var settings_ap = ';


        $fout.='{
"design_skin": "' . $its['settings']['skin_ap'] . '"
,"skinwave_dynamicwaves":"' . $its['settings']['skinwave_dynamicwaves'] . '"
,"skinwave_enableSpectrum":"' . $its['settings']['skinwave_enablespectrum'] . '"
,"settings_backup_type":"' . $its['settings']['settings_backup_type'] . '"
,"skinwave_enableReflect":"' . $its['settings']['skinwave_enablereflect'] . '"
,"skinwave_comments_enable":"' . $its['settings']['skinwave_comments_enable'] . '"
,"soundcloud_apikey":"' . $this->mainoptions['soundcloud_api_key'] . '"
,"php_retriever":"' . $this->base_url . 'soundcloudretriever.php"
            ';


        if(isset($its['settings']['playfrom'])){
            $fout.=',"playfrom":"' . $its['settings']['playfrom'] . '"';
        }
        if(isset($vpsettings['settings']['default_volume'])){
            $fout.=',"default_volume":"' . $vpsettings['settings']['default_volume'] . '"';
        }
        if(isset($this->mainoptions['skinwave_wave_mode_canvas_normalize']) && $this->mainoptions['skinwave_wave_mode_canvas_normalize']=='off'){
            $fout.=',"skinwave_wave_mode_canvas_normalize":"' . $this->mainoptions['skinwave_wave_mode_canvas_normalize'] . '"';
        }
        if(isset($its['settings']['disable_volume'])){
            $fout.=',"disable_volume":"' . $its['settings']['disable_volume'] . '"';
        }
        if(isset($its['settings']['loop'])){
            $fout.=',"loop":"' . $its['settings']['loop'] . '"';
        }





	    if(isset($its['settings']['enable_embed_button']) && ( $its['settings']['enable_embed_button']!='off')){

		    $str_db = '';
		    if($this->currDb!=''){
			    $str_db='&db=' . $this->currDb . '';
		    }
		    if($margs['id']=='playlist_gallery'){
//                $str = '<iframe src="' . $this->base_url . 'bridge.php?type=playlist&ids=' . $margs['for_embed_ids'] . ''.$str_db.'" width="100%" height="'.$its['settings']['height'].'" style="overflow:hidden; transition: height 0.5s ease-out;" scrolling="no" frameborder="0"></iframe>';
			    $str = '<iframe src="' . site_url() . '?action=zoomsounds-embedtype=playlist&ids=' . $margs['for_embed_ids'] . ''.$str_db.'" width="100%" height="'.$its['settings']['height'].'" style="overflow:hidden; transition: height 0.5s ease-out;" scrolling="no" frameborder="0"></iframe>';
		    }else{
			    $str = '<iframe src="' . site_url() . '?action=zoomsounds-embed&type=gallery&id=' . $its['settings']['id'] . ''.$str_db.'" width="100%" height="'.$its['settings']['height'].'" style="overflow:hidden; transition: height 0.5s ease-out;" scrolling="no" frameborder="0"></iframe>';
		    }


		    $str = str_replace('"',"'", $str);
		    $fout.=',"embed_code":"'.htmlentities($str, ENT_QUOTES).'"';
        }

        if(isset($its['settings']['enable_embed_button']) && ( $its['settings']['enable_embed_button']=='on' || $vpsettings['settings']['enable_embed_button']=='in_player_controls' ) ){
            $fout.=',"enable_embed_button":"'.'on'.'"';
        }

        $fout.=',"settings_php_handler":window.ajaxurl';
        if ($its['settings']['skinwave_comments_enable'] == 'on') {
            if (isset($current_user->data->user_nicename)) {
                $fout.=',"skinwave_comments_account":"' . $current_user->data->user_nicename . '"';
                $fout.=',"skinwave_comments_avatar":"' . $this->get_avatar_url(get_avatar($current_user->data->ID, 20)) . '"';
                $fout.=',"skinwave_comments_playerid":"' . $margs['playerid'] . '"';
            }
        }
        if (isset($its['settings']['skinwave_mode']) && $its['settings']['skinwave_mode'] == 'small') {
            $fout.=',"skinwave_mode":"' . $its['settings']['skinwave_mode'] . '"';
        }
        $fout.=',"skinwave_wave_mode":"' . $this->mainoptions['skinwave_wave_mode'] . '"';

        $this->mainoptions['color_waveformbg'] = str_replace('#','',$this->mainoptions['color_waveformbg']);
        if($this->mainoptions['skinwave_wave_mode']=='canvas'){
            $fout.=',"pcm_data_try_to_generate": "'.$this->mainoptions['pcm_data_try_to_generate'].'"';
            $fout.=',"pcm_notice": "'.$this->mainoptions['pcm_notice'].'"';
	        $fout.=',"notice_no_media": "'.$this->mainoptions['notice_no_media'].'"';
            $fout.=',"design_color_bg": "'.$this->sanitize_to_hex_color_without_hash($this->mainoptions['color_waveformbg']).'"';
            $fout.=',"design_color_highlight": "'.$this->sanitize_to_hex_color_without_hash($this->mainoptions['color_waveformprog']).'"';
            $fout.=',"skinwave_wave_mode_canvas_waves_number": "'.$this->sanitize_for_javascript_double_quote_value($this->mainoptions['skinwave_wave_mode_canvas_waves_number']).'"';
            $fout.=',"skinwave_wave_mode_canvas_waves_padding": "'.$this->sanitize_for_javascript_double_quote_value($this->mainoptions['skinwave_wave_mode_canvas_waves_padding']).'"';
            $fout.=',"skinwave_wave_mode_canvas_reflection_size": "'.$this->sanitize_for_javascript_double_quote_value($this->mainoptions['skinwave_wave_mode_canvas_reflection_size']).'"';



            if (isset($its['settings']['skinwave_wave_mode_canvas_mode']) && $its['settings']['skinwave_wave_mode_canvas_mode'] ) {
                $fout.=',"skinwave_wave_mode_canvas_mode":"' . $its['settings']['skinwave_wave_mode_canvas_mode'] . '"';
            }


        }




        $preload_method = 'metadata';
        $design_animateplaypause = 'off';

        if(isset($vpsettings['settings']['preload_method'])){
            $preload_method = $vpsettings['settings']['preload_method'];
        }


        if(isset($vpsettings['settings']['restyle_player_over_400']) && $vpsettings['settings']['restyle_player_over_400']){
            $fout.=',"restyle_player_over_400": "'.$vpsettings['settings']['restyle_player_over_400'].'"';
        }
        if(isset($vpsettings['settings']['restyle_player_under_400']) && $vpsettings['settings']['restyle_player_under_400']){
            $fout.=',"restyle_player_under_400": "'.$vpsettings['settings']['restyle_player_under_400'].'"';
        }

        $fout.=',"preload_method":"' . $vpsettings['settings']['preload_method'] . '"';

//                print_r($this->mainoptions);

        if($this->mainoptions['failsafe_repair_media_element']=='on'){
            $fout.=',"failsafe_repair_media_element":1000';
        }

        if ($this->mainoptions['settings_trigger_resize'] == 'on') {
            $fout.=',"settings_trigger_resize":"1000"';
        };

        if ($this->mainoptions['wavesurfer_pcm_length'] != '200') {
            $fout.=',"wavesurfer_pcm_length":"'.$this->mainoptions['wavesurfer_pcm_length'].'"';
        };




//        print_rr($its);
        $fout.='};';
        // -- end settings ap


        $fout.=' dzsag_init("#ag' . $this->sliders_index . '",';



        // -- start settings
        $fout.='{
            "transition":"fade"
            ,"autoplay" : "' . $its['settings']['autoplay'] . '"
            ,"embedded" : "' . $margs['embedded'] . '"
            ,"autoplayNext" : "' . $its['settings']['autoplaynext'] . '"
            ,"design_menu_position" :"' . $its['settings']['menuposition'] . '"
            ';


        $fout.=',"settings_ap": settings_ap';
//        print_rr($its);

        if (isset($its['settings']['disable_player_navigation'])) {
$fout.=',"disable_player_navigation":"' . $its['settings']['disable_player_navigation'] . '"';
        }
        if (isset($this->mainoptions['loop_playlist']) && $this->mainoptions['loop_playlist']) {
$fout.=',"loop_playlist":"' . $this->mainoptions['loop_playlist'] . '"';
        }
        if (isset($its['settings']['player_navigation'])) {
$fout.=',"player_navigation":"' . $its['settings']['player_navigation'] . '"';
        }
        if (isset($its['settings']['cuefirstmedia'])) {
            $fout.=',"cueFirstMedia":"' . $its['settings']['cuefirstmedia'] . '"';
        }
        if (isset($its['settings']['mode'])) {
            $fout.=',"settings_mode":"' . $its['settings']['mode'] . '"';
        }
        if (isset($its['settings']['settings_mode_showall_show_number'])) {
            $fout.=',"settings_mode_showall_show_number":"' . $its['settings']['settings_mode_showall_show_number'] . '"';
        }



        if(isset($_GET['fromsharer']) && $_GET['fromsharer']=='on'){
            if(isset($_GET['audiogallery_startitem_ag1']) && $_GET['audiogallery_startitem_ag1']!==''){


                $its['settings']['design_menu_state'] = 'closed';
            }
        }

        if (isset($its['settings']['design_menu_state'])) {
            $fout.=',"design_menu_state":"' . $its['settings']['design_menu_state'] . '"';
        }
        if (isset($its['settings']['design_menu_height']) && $its['settings']['design_menu_height']!='') {
            $fout.=',"design_menu_height":"' . $its['settings']['design_menu_height'] . '"';
        }


        if (isset($its['settings']['design_menu_show_player_state_button'])) {
            $fout.=',"design_menu_show_player_state_button":"' . $its['settings']['design_menu_show_player_state_button'] . '"';
        }

        if (isset($its['settings']['settings_enable_linking'])) {
            $fout.=',"settings_enable_linking":"' . $its['settings']['settings_enable_linking'] . '"';
        }
        if (isset($its['settings']['enable_linking'])) {
            $fout.=',"settings_enable_linking":"' . $its['settings']['enable_linking'] . '"';
        }

        if($this->mainoptions['force_autoplay_when_coming_from_share_link']=='on'){
            $fout.=',"force_autoplay_when_coming_from_share_link": "on"';
        }


        $fout.='}';

        // -- end settings



        $fout.=');';

        $fout.='});';

        if($margs['divinsteadofscript']!='on'){
            $fout.='</script>';
        }else{
            $fout.='</div>';
        }


//end document ready an script

        $url = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';

        if($this->mainoptions['fontawesome_load_local']=='on'){
            $url = $this->base_url.'libs/fontawesome/font-awesome.min.css';
        }


        wp_enqueue_style('fontawesome',$url);

        if ($margs['return_onlyitems'] != 'on') {
            return $fout;
        } else {
            return $iout;
        }




        //echo $k;
    }


    function show_shortcode_lightbox($atts,$content = null) {

        $fout = '';
        //$this->sliders_index++;

        $this->front_scripts();

        wp_enqueue_style('ultibox',$this->base_url.'libs/ultibox/ultibox.css');
        wp_enqueue_script('ultibox',$this->base_url.'libs/ultibox/ultibox.js');

        $args = array(
            'id' => 'default'
        ,'db' => ''
        ,'category' => ''
        ,'width' => ''
        ,'height' => ''
        ,'gallerywidth' => '800'
        ,'galleryheight' => '370'
        );
        $args = array_merge($args,$atts);
        $fout.='<div class="ultibox"';

        if ($args['width'] != '') {
            $fout.=' data-width="'.$args['width'].'"';
        }
        if ($args['height'] != '') {
            $fout.=' data-height="'.$args['height'].'"';
        }
        if ($args['gallerywidth'] != '') {
            $fout.=' data-bigwidth="'.$args['gallerywidth'].'"';
        }
        if ($args['galleryheight'] != '') {
            $fout.=' data-bigheight="'.$args['galleryheight'].'"';
        }

        $fout.='data-src="'.$this->base_url.'retriever.php?id='.$args['id'].'" data-type="ajax">'.$content.'</div>';
        $fout.='<script>
jQuery(document).ready(function($){
$(".zoombox").zoomBox();
});
</script>';

        return $fout;
    }


    function get_soundcloud_track_source($che){
        $fout = '';

        $sw_was_cached = false;


        $cacher = get_option('dzsap_cache_soundcloudtracks');

        if(is_array($cacher)==false){
            $cacher = array();
        }



        if(isset($cacher[$che['soundcloud_track_id']])){
            $fout = $cacher[$che['soundcloud_track_id']]['source'];
            $sw_was_cached=true;
        }

//        print_r($cacher); echo ' is cached - '.$sw_was_cached.'||';


        if($sw_was_cached==false){

            $aux = DZSHelpers::get_contents('https://api.soundcloud.com/tracks/'.$che['soundcloud_track_id'].'.json?secret_token='.$che['soundcloud_secret_token'].'&client_id='.$this->mainoptions['soundcloud_api_key']);


            $auxa = json_decode($aux);



            $fout = $auxa->stream_url.'&client_id='.$this->mainoptions['soundcloud_api_key'];


            $cacher[$che['soundcloud_track_id']] = array(
                'source'=>$fout
            );


            if($fout){

                update_option('dzsap_cache_soundcloudtracks', $cacher);
            }


        }

        return $fout;
    }

	function is_bot() {

//        return true;
		return (
        (isset($_SERVER['HTTP_USER_AGENT'])
			&& preg_match('/bot|crawl|slurp|spider|metrix|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
        )
		);
	}

	function sanitize_from_meta_textarea($arg){


	    $arg = stripslashes($arg);
	    $arg = str_replace('{{quots}}','\'',$arg);

	    return $arg;

    }


    function get_songname_from_attachment($che){


	    $songname = '';
	    $attachment_id = '';
//			            print_rr($che);

	    if(isset($che['dzsap_meta_source_attachment_id']) && $che['dzsap_meta_source_attachment_id']){
//			                print_rr($che);
		    $attachment_id = $che['dzsap_meta_source_attachment_id'];

	    }else{
		    if($che && isset($che['ID']) && $che['ID']){
			    $attachment_id = get_post_meta($che['ID'],'dzsap_meta_source_attachment_id',true);
		    }
	    }

	    if($attachment_id){


		    $att = get_post($attachment_id);

//			                echo 'att - <hr>';print_rr($att);

		    if($att->post_title){
			    $songname=$att->post_title;
		    }
	    }



	    return $songname;


    }

    function get_download_link($che, $playerid){


	    $download_link = '';


	    if( (isset($che['download_custom_link']) && $che['download_custom_link'] && $che['download_custom_link']!='off') ){
		    $download_link = $che['download_custom_link'];
	    }else{
		    if($playerid){

			    $download_link = site_url().'?action=dzsap_download&id='.$playerid;
		    }else{


			    if($this->mainoptions['download_link_links_directly_to_file']=='on'){

				    $download_link = $che['source'];
			    }else{

				    $download_link = site_url().'?action=dzsap_download&link='.urlencode($che['source']);
			    }
		    }
	    }

	    return $download_link;
    }
    function sanitize_to_css_perc($arg){


	    $fout = $arg;

	    if(strpos($arg, '%')===false){
		    $fout.='%';
	    }



	    $fout = str_replace('http://','',$fout);
	    $fout = str_replace('https://','',$fout);

	    return $fout;

    }
    function parse_items($its, $pargs = array()) {
        // -- returns only the html5 gallery items

        global $post;

        $fout = '';
        $start_nr = 0; // === the i start nr
        $end_nr = count($its); // === the i start nr
        $nr_per_page = 5;
        $nr_items = count($its);

        $margs = array(
            'menu_facebook_share'=>'auto',
            'menu_like_button'=>'auto',
            'gallery_skin'=>'skin-wave',
            'called_from'=>'skin-wave',
            'skinwave_mode'=>'normal',
            'single'=>'off',
            'wrapper_image' => '',
            'extra_html_in_player' => '',
            'extra_classes' => '',
            'wrapper_image_type' => '', // zoomsounds-wrapper-bg-bellow or zoomsounds-wrapper-bg-center
        );

        $margs = array_merge($margs, $pargs);

//        echo 'parse_items margs -'; print_rr($margs);
//        echo 'parse_items $its -'; print_rr($its);


//        echo 'start its - '; print_rr($its);

        // -- sanitizing
        if($margs['wrapper_image']==''){
            if(isset($margs['cover']) && $margs['cover']){
	            $margs['wrapper_image'] = $margs['cover'];
            }
        }

//	    echo 'margs init - '; print_rr($margs);
        if (isset($its['settings'])) {
            $nr_items--;
            $end_nr--;

            if(isset($its['settings']['enable_views'])==false){
                $its['settings']['enable_views'] = 'off';
            }
            if(isset($its['settings']['enable_likes'])==false){
                $its['settings']['enable_likes'] = 'off';
            }
            if(isset($its['settings']['enable_rates'])==false){
                $its['settings']['enable_rates'] = 'off';
            }
            if(isset($its['settings']['enable_downloads_counter'])==false){
                $its['settings']['enable_downloads_counter'] = 'off';
            }


            if(isset($margs['enable_views']) && $margs['enable_views']==='on'){
                $its['settings']['enable_views'] = 'on';
            }
            if(isset($margs['enable_downloads_counter']) && $margs['enable_downloads_counter']==='on'){
                $its['settings']['enable_downloads_counter'] = 'on';
            }

            if(isset($margs['enable_likes']) && $margs['enable_likes']==='on'){
                $its['settings']['enable_likes'] = 'on';
            }
            if(isset($margs['enable_rates']) && $margs['enable_rates']==='on'){
                $its['settings']['enable_rates'] = 'on';
            }
            if($margs['single']=='on' && isset($its['settings']['id']) && $its['settings']['id']){
                $its['settings']['vpconfig'] = $its['settings']['id'];
            }






            if(isset($its['settings']['enable_alternate_layout']) && $its['settings']['enable_alternate_layout']==='on'){
                $margs['enable_alternate_layout'] = 'on';
                $margs['skinwave_mode']='alternate';
            }
        }


//        echo 'parsed string: ';
//            echo 'its - '; print_rr($its);
//            print_rr($margs);



//        echo '$start_nr - '.$start_nr;
//        echo '$end_nr - '.$end_nr;



        for ($i = $start_nr; $i < $end_nr; $i++) {


            $i_fout = '';
            $che = array(
                'menu_artistname' => 'default',
                'menu_songname' => 'default',
                'menu_extrahtml' => '',
                'extra_html' => '',
                'called_from' => '',
                'songname' => '',
                'artistname' => '',
                'show_tags' => 'off',
                'playerid' => '', // -- playerid for database
            );


            if (is_array($its[$i]) == false) {
                $its[$i] = array();
            }

            $che = array_merge($che, $its[$i]);


//            echo 'che init - ';
//            print_rr($che);

            // -- let us assign default
	        if($che['songname']){

	        }else{
		        $che['songname'] = 'default';
	        }


	        if($che['artistname']){

	        }else{
		        $che['artistname'] = 'default';
	        }

	        if($che['songname']=='default'){
		        if($che['menu_songname'] && $che['menu_songname']!='default' && $che['menu_songname']!='none'){

			        $che['songname'] = $che['menu_songname'];
		        }
	        }

	        if($che['artistname']=='default'){
		        if($che['menu_artistname'] && $che['menu_artistname']!='default' && $che['menu_artistname']!='none'){

			        $che['artistname'] = $che['menu_artistname'];
		        }
	        }









	        $che['extra_html'] = str_replace('{{lsqb}}','[',$che['extra_html']);
	        $che['extra_html'] = str_replace('{{rsqb}}',']',$che['extra_html']);
            $meta = array();

	        $playerid = '';

//            echo 'che[source] - > '.$che['source'].' ... ';

//            echo '$che - '; print_rr($che);


            if($che['source'] && is_numeric($che['source'])){
                $player_post_id = intval($che['source']);
                $player_post = get_post(intval($che['source']));


//                echo 'che[source] - > '.$che['source'].' ... ';
//                print_r($player_post);

                if($player_post && $player_post->post_type=='attachment'){
                    $media = wp_get_attachment_url($player_post_id);

                    $che['source'] = $media;
                    if($playerid){

                    }else{
                        $playerid = $player_post_id;
                        $che['playerid'] = $player_post_id;
                    }

//                    print_r($media);
                }

//                echo '(isset($che[\'ID\']) && $che[\'playerid\']==false && is_numeric($che[\'ID\'])) - '.(isset($che['ID']) && $che['playerid']==false && is_numeric($che['ID']));

            }

            if(isset($che['ID']) && $che['playerid']==false && is_numeric($che['ID'])){
                $che['playerid']= $che['ID'];
            }




//            print_rr($che);


//            echo '$its - '; print_rr($its);
//            echo '$che - '; print_rr($che);


	        if(isset($its['settings']['js_settings_extrahtml_in_float_right_from_config']) && $its['settings']['js_settings_extrahtml_in_float_right_from_config']){


//                echo 'js_settings_extrahtml_in_float_right_from_config -> '.$its['settings']['js_settings_extrahtml_in_float_right_from_config'];

		        if(isset($che['extra_html_in_controls_right']) && $che['extra_html_in_controls_right']){




//		            echo 'whyyyy ? ';

		        }else{

		            // -- we set extra html in controls right for che but what does that help us with ?
			        $its['settings']['js_settings_extrahtml_in_float_right_from_config']=str_replace('{{singlequot}}', '\'',$its['settings']['js_settings_extrahtml_in_float_right_from_config']);
		            $che['extra_html_in_controls_right']=stripslashes($its['settings']['js_settings_extrahtml_in_float_right_from_config']);
		        }
	        }

//	        echo '$its - '; print_rr($its);


	        if(isset($its['settings']['js_settings_extrahtml_in_bottom_controls_from_config']) && $its['settings']['js_settings_extrahtml_in_bottom_controls_from_config']){


		        if(isset($che['extra_html_in_bottom_controls']) && $che['extra_html_in_bottom_controls']){

		        }else{

		            $che['extra_html_in_bottom_controls']=$this->sanitize_from_meta_textarea($its['settings']['js_settings_extrahtml_in_bottom_controls_from_config']);
		        }
	        }



            if (isset($che['playerid']) && $che['playerid'] != '') {
                $playerid = $che['playerid'];
            }


            if ($playerid == '' && isset($che['linktomediafile']) && $che['linktomediafile'] != '') {
                $playerid = $che['linktomediafile'];
            }



            $po = null;
//	        print_rr($this->mainoptions);
//	        echo 'che -6'; print_rr($che);


            if ($playerid) {
                $po = get_post($playerid);
//                print_r($po);


                $meta = wp_get_attachment_metadata($playerid);





//                echo 'meta ( '.$playerid.' ) - '; print_rr($meta);
//                echo 'post ( '.$playerid.' ) - '; print_rr($po);




	            // -- found player ID end




//	            print_rr($che);

	            if($che['show_tags']=='on'){


		            $taxonomy = 'dzsap_tags';


//		            echo '$playerid - '.$playerid;
		            $term_list = wp_get_post_terms($playerid, $taxonomy, array("fields" => "all"));


		            if(is_array($term_list) && count($term_list)>0){


		                // -- todo: outside player, do we need it inside ?
			            $i_fout.='<div class="tag-list">';

			            $cach_tag = $term_list[0];
			            $i_fout.='<a class="dzsap-tag" href="';


			            $i_fout.=add_query_arg(array(
				            'query_song_tag'=>$cach_tag->slug
			            ),dzs_curr_url());

			            $i_fout.='">';
			            $i_fout.=$cach_tag->name;
			            $i_fout.='</a>';
			            if(count($term_list)>1){



				            $i_fout.='<span class="dzstooltip-con" style=""><span class="dzstooltip arrow-from-end transition-slidein arrow-top align-right skin-black" style="width: auto;white-space:nowrap;">';

				            foreach ($term_list as $lab=>$term){


					            if($lab){


						            $cach_tag = $term;
						            $i_fout.='<a class="dzsap-tag" href="';


						            $i_fout.=add_query_arg(array(
							            'query_song_tag'=>$cach_tag->slug
						            ),dzs_curr_url());

						            $i_fout.='">';
						            $i_fout.=$cach_tag->name;

						            $i_fout.='</a>';

					            }

				            }
				            $i_fout.='</span>';

				            $i_fout.='<span class="the-label">...</span>';
			            }

			            $i_fout.='</div>';

		            }

	            }






                if($this->mainoptions['try_to_hide_url']=='on'){

                }


//                print_rr($che);






                // -- we need to get source from library on mediafile
	            if ($che['type']=='mediafile'){
		            $che['source'] = '';
                }


                // -- from mediafile
                if(@wp_get_attachment_url($playerid)){
                    if ($che['source'] == ''){

                        $che['source'] = @wp_get_attachment_url($playerid);
                    }
                }
//                print_rr($che);

                if ($che['source'] == '' && $po) {
                    $che['source'] = $po->guid;
//                    print_r($che);
                }



                if ((!isset($che['artistname_from_meta']) || $che['artistname_from_meta'] == '')) {
//                    print_r($meta);
//                    print_r($meta['artist']);


                    if(isset($meta['artist'])){

                        $che['artistname_from_meta']=$meta['artist'];
                    }
                };



                if ((!isset($che['songname_from_meta']) || $che['songname_from_meta'] == '')) {
//                    print_r($meta);
//                    print_r($meta['artist']);


                    if(isset($meta['title'])){

                        $che['songname_from_meta']=$meta['title'];
                    }
                };
                if ((!isset($che['publisher']) || $che['publisher'] == '')) {
//                    print_r($meta);
//                    print_r($meta['artist']);


                    if(isset($meta['publisher'])){

                        $che['publisher']=$meta['publisher'];
                    }
                };



                // -- @deprecated
                if ((!isset($che['waveformbg']) || $che['waveformbg'] == '') && $po && get_post_meta($po->ID, '_waveformbg', true) != '') {
                    $che['waveformbg'] = get_post_meta($po->ID, '_waveformbg', true);
                };


                if ((!isset($che['waveformprog']) || $che['waveformprog'] == '') && $po && get_post_meta($po->ID, '_waveformprog', true) != '') {
                    $che['waveformprog'] = get_post_meta($po->ID, '_waveformprog', true);
                };
	            // -- @deprecated waveform jpeg END


                if ( (isset($che['thumb'])==false || $che['thumb'] == '') && isset($po)) {


//                    $che['thumb'] = get_post_meta($po->ID, '_dzsap-thumb', true);

                    if(get_post_meta($po->ID, '_dzsap-thumb',true)){

                        $che['thumb'] =  get_post_meta($po->ID, '_dzsap-thumb',true);
                    }else{

                    }
                };


                if ($che['sourceogg'] == '' && isset($po) &&  get_post_meta($po->ID, '_dzsap_sourceogg', true) != '') {
                    $che['sourceogg'] = get_post_meta($po->ID, '_dzsap_sourceogg', true);
                };
            }




	        if( $this->mainoptions['try_to_hide_url']=='on' &&  ( (isset($che['linktomediafile']) && $che['linktomediafile']) || is_int($playerid) || (isset($che['product_id']) && $che['product_id']) ) ){

		        $nonce = rand(0,10000);


		        $id_for_nonce = '';


		        if(is_int($playerid)){
			        $id_for_nonce = $playerid;
		        }else{

			        if((isset($che['product_id']) && $che['product_id'])){
				        $id_for_nonce = $che['product_id'];
			        }

		        }


//                    print_rr($_SERVER);

		        $lab = 'dzsap_nonce_for_'.$id_for_nonce.'_ip_'.$_SERVER['REMOTE_ADDR'];

		        $lab = $this->clean($lab);


//                    $_SESSION[$lab] = $nonce;



		        $nonce = '{{generatenonce}}';


//                    print_r($_SESSION);

		        $src = site_url().'/index.php?dzsap_action=generatenonce&id='.$id_for_nonce.'&'.$lab.'='.$nonce;

		        $che['source'] = $src;
	        }




//	        echo '$che1 - '; print_rr($che);

//	        $this->get_post_meta_all($playerid);

            if(isset($che['artistname_from_meta'])){
                if($che['artistname_from_meta'] && $che['artistname_from_meta']!='default') {
                    if ($che['artistname'] == '' || $che['artistname'] == 'default') {
                        $che['artistname'] = $che['artistname_from_meta'];
                    }
                }
            }

//            echo 'che her - '; print_rr($che);


//            echo 'post meta all'; print_rr(get_post_meta($playerid));

            if($che['songname']=='default'){

                if(get_post_meta($playerid,'songname',true)){
	                $che['songname'] = get_post_meta($playerid,'songname',true);
                }else{

//                    echo 'whaaa';
	                if(get_post_meta($playerid,'dzsap_meta_replace_songname',true)){
		                $che['songname'] = get_post_meta($playerid,'dzsap_meta_replace_songname',true);
	                }else{

		                if($po){


			                if($po->post_title){
				                $che['songname']=$po->post_title;
			                }

		                }
		                else{

			                if(isset($che['linktomediafile']) && $che['linktomediafile']){
				                $po_att = get_post($che['linktomediafile']);

				                if($po_att->post_title){
					                $che['songname']=$po_att->post_title;
				                }
			                }
		                }
                    }
                }


            }
            if($che['artistname']=='default'){


	            if(get_post_meta($playerid,'artistname',true)){
		            $che['artistname'] = get_post_meta($playerid,'artistname',true);
	            }else{
		            if(get_post_meta($playerid,'dzsap_meta_replace_artistname',true)){
			            $che['artistname'] = get_post_meta($playerid,'dzsap_meta_replace_artistname',true);
		            }else{

		            if(isset($che['linktomediafile']) && $che['linktomediafile']){
			            $po_att = get_post($che['linktomediafile']);

//                    print_rr($po_att);
			            $user_info = get_userdata($po_att->post_author);
			            if($user_info->user_login){
				            $che['artistname']=$user_info->user_login;
			            }
		            }
		            }
                }


            }

            if(isset($che['songname_from_meta'])){
                if($che['songname_from_meta'] && $che['songname_from_meta']!='default'){

                    if($che['songname']==='' || $che['songname']=='default'){

                        $che['songname'] = $che['songname_from_meta'];
                    }
                }
            }

	        if($che['menu_songname']==='' || $che['menu_songname']=='default'){
                $che['menu_songname'] = $che['songname'];
	        }
	        if($che['menu_artistname']==='' || $che['menu_artistname']=='default'){
                $che['menu_artistname'] = $che['artistname'];
	        }



            if($che['songname']=='none' || $che['songname'] == 'default'){
	            $che['songname'] = '';
            }
            if($che['artistname']=='none' || $che['artistname'] == 'default'){
	            $che['artistname'] = '';
            }
            if($che['menu_songname']=='none' || $che['menu_songname'] == 'default'){
	            $che['menu_songname'] = '';
            }
            if($che['menu_artistname']=='none' || $che['menu_artistname'] == 'default'){
	            $che['menu_artistname'] = '';
            }


//	        echo 'che after songname and artist name replace - '; print_rr($che);

            $type = 'audio';

            if (isset($che['type']) && $che['type'] != '') {
                $type = $che['type'];
            }

            if ($type == 'inline') {
                $i_fout.=$che['source'];
                continue;
            }


            if ($che['source'] == '' || $che['source'] == ' ') {
                continue;
            }
//            print_r($che); echo $playerid;



            if(isset($_GET['fromsharer']) && $_GET['fromsharer']=='on'){
                if(isset($_GET['audiogallery_startitem_ag1']) && $_GET['audiogallery_startitem_ag1']){


                    if($i==$_GET['audiogallery_startitem_ag1']){
//            print_rr($che);

                        $this->og_data = array(
                            'title'=>$che['menu_songname'],
                            'image'=>$che['thumb'],
                            'description'=>__("by").' '.$che['menu_artistname'],
                        );
                    }
                }
            }

            if(strpos($che['source'], 'soundcloud.com')!==false){
                if(isset($che['soundcloud_track_id']) && isset($che['soundcloud_secret_token']) && $che['soundcloud_track_id'] && $che['soundcloud_secret_token']){


//                print_r($auxa);

                    $che['source']=$this->get_soundcloud_track_source($che);
//                    $che['type']='audio';

                    if($type=='soundcloud'){
                        $type = 'audio';
                    }
                }
            }



            $the_player_id='';

            if($playerid){

                $the_player_id = 'ap' . $playerid . '';
            }



//            print_rr($margs);
//            print_rr($che);
	        if(isset($margs['player_id']) && $margs['player_id']){

	        }else{

		        if(isset($margs['playerid']) && $margs['playerid']){
			        $margs['player_id'] = $margs['playerid'];
		        }
            }

            if(isset($margs['player_id']) && $margs['player_id']){
//                print_r($margs);
                $the_player_id = $margs['player_id'];
            }else{

	            if(isset($margs['player_id']) && $margs['player_id']){
//                print_r($margs);
		            $the_player_id = $margs['player_id'];
	            }
            }






//            echo '$its - '.print_rr($its,true);
//            echo '$che - '.print_rr($che,true);
//            echo 'hmm - '; print_rr($margs);  print_rr($its);

//            echo ' chec before extra_html do_shortcode '; print_rr($che);

	        $che['extra_html'] = do_shortcode($che['extra_html']);
            if(isset($margs['extra_html_in_player']) && ( $margs['extra_html_in_player'])){

                $che['extra_html'].=$margs['extra_html_in_player'];
            }



//            print_rr($che);

	        if((isset($che['extrahtml_in_bottom_controls_from_player']) && $che['extrahtml_in_bottom_controls_from_player'] )) {

		        $che['extra_html'] .= wp_kses(do_shortcode( $this->sanitize_from_shortcode_attr($che['extrahtml_in_bottom_controls_from_player'] )),$this->allowed_tags);
	        }else{
		        if ( ( isset( $che['extra_html_in_bottom_controls'] ) && $che['extra_html_in_bottom_controls'] ) ) {

			        $che['extra_html'] .= do_shortcode( $che['extra_html_in_bottom_controls'] );

		        }
            }

//            print_rr($che);



	        if(isset($margs['called_from']) && ( $margs['called_from']=='player' || $margs['called_from']=='footer_player' ) && isset($margs['colorhighlight']) && $margs['colorhighlight']){
                $i_fout.='<style class="player-custom-style">';
                if($margs['skin_ap']=='skin-wave'){

//                    print_r($this);

                    if($the_player_id){

	                    $selector = '.audioplayer.skin-wave#'.$the_player_id;
                    }else{


                    }






//                    print_rr($margs);


	                if(isset($margs['playerid']) && $margs['playerid']){

	                }else{



		                if(is_numeric($margs['source'])){
			                $margs['playerid'] = $margs['source'];
		                }else{

//	            $fout.=' data-player-id="'.dzs_clean_string($che['source']).'"';
			                $margs['playerid'] = $this->encode_to_number($margs['source']);
		                }

	                }

	                $selector = 'body .audioplayer.skin-wave.playerid-'.$margs['playerid'].':not(.a)';
//	                $selector = '.audioplayer.skin-wave';

                    if(isset($its['settings']['button_aspect'])){



                        if($its['settings']['button_aspect']=='default'){

                            $i_fout.=$selector.' .ap-controls .con-playpause .playbtn , '.$selector.' .ap-controls .con-playpause .pausebtn { background-color: #'.$margs['colorhighlight'].';} ';
                        }



                        if($its['settings']['button_aspect']=='button-aspect-noir button-aspect-noir--filled'){
                            $i_fout.=' '.$selector.' .player-but .the-icon-bg, '.$selector.' .playbtn .the-icon-bg , '.$selector.' .pausebtn .the-icon-bg,  '.$selector.' .ap-controls .scrubbar .scrubBox-hover , '.$selector.' .volume_active { background-color: #'.$margs['colorhighlight'].'; border-color: #'.$margs['colorhighlight'].';}';
                        }
                    }

                }

//                print_rr($margs);
                if($margs['skin_ap']=='skin-pro'){

                    $selector = '.audioplayer.skin-pro';

                    if(isset($its['settings']['vpconfig'])){
                        $selector.='.apconfig-'.$its['settings']['vpconfig'];
                    }

                    $i_fout.=$selector.' .ap-controls .scrubbar .scrub-prog{  background-color: #'.$margs['colorhighlight'].';  }';
                }




                $i_fout.=' </style>';
            }






	        $thumb_link_attr = '';
	        $fakeplayer_attr = '';
	        $thumb_for_parent_attr = '';

	        $pcm = '';
	        if($this->mainoptions['skinwave_wave_mode']=='canvas') {
		        $pcm = $this->generate_pcm( $che );
	        }



            $i_fout.='<div class="audioplayer-tobe';


            $str_post_id = '';

            if($post){
                $str_post_id = '_'.$post->ID;
            }





            $i_fout.=' playerid-'.$margs['playerid'];


            if(isset( $its[$i]['player_index'] ) && $its[$i]['player_index']){
                $i_fout.=' ap_idx'.$str_post_id.'_'.$its[$i]['player_index'];
            }

            if(isset($margs['single']) && $margs['single']=='on'){
                $i_fout.=' is-single-player';
            }

//            print_r($che);
//            print_r($its);

//            print_r($its['settings']);
//            print_r($margs);

            if($its && $its['settings'] && isset($its['settings']['vpconfig']) && $its['settings']['vpconfig']){
                $aux = str_replace(' ','-',$its['settings']['vpconfig']);
                $i_fout.=' apconfig-'.$aux;


//                print_r($margs);
//                print_r($its);



                if(isset($margs['skin_ap']) && $margs['skin_ap']){


                    if($margs['called_from']=='gallery'){

                        $i_fout.=' '.$margs['skin_ap'];
                    }


                }

//                print_r($its['settings']);

                if(isset($its['settings']['button_aspect']) && $its['settings']['button_aspect']!='default'){
                    $i_fout.=' '.$its['settings']['button_aspect'];

                    if(isset($its['settings']['colorhighlight']) &&$its['settings']['colorhighlight'] ){
                        // TODO: maybe force aspect noir filled ? if aspect noir is set


                    }
                }
            }


            if(isset($che['wrapper_image_type']) && $che['wrapper_image_type']){

                $i_fout.=' '.$che['wrapper_image_type'];
            }
            if(isset($margs['extra_classes_player'])){
                $i_fout.=' '.$margs['extra_classes_player'];
            }

            if($margs['called_from']=='footer_player'||$margs['called_from']=='player'||$margs['called_from']=='gallery'){

//                print_r($its);
//                print_r($margs);
                $i_fout.=' '.$margs['skin_ap'];
            }



            if(isset($margs['enable_alternate_layout']) && $margs['skinwave_mode']=='normal' && $margs['enable_alternate_layout']=='on'){
                $i_fout.=' alternate-layout';
            }

            if(isset($its['settings']['extra_classes_player'])){
                $i_fout.=' '.$its['settings']['extra_classes_player'];
            }
            if(isset($its['settings']['skinwave_mode'])){

                if($margs['skinwave_mode']=='alternate' ){
                    $i_fout.=' alternate-layout';
                }
                if($margs['skinwave_mode']=='nocontrols' ){
                    $i_fout.=' skin-wave-mode-nocontrols';
                }
            }

            $i_fout.=' '.$the_player_id;

//            print_rr($che);

//            print_rr($its);

            if(isset($its['settings']) && isset($its['settings']['disable_volume']) && $its['settings']['disable_volume']=='on'){
                $i_fout.=' disable-volume';
            }

            if(isset($che['extra_classes']) && $che['extra_classes']){
                $i_fout .=' '.$che['extra_classes'];
            }
            if(isset($che['embedded']) && $che['embedded']=='on'){
                $i_fout .=' '.' is-in-embed-player';
            }



            $i_fout.='" ';

            // -- end class


	        $the_player_id = str_replace('ap','',$the_player_id);
//	        echo 'get_post_meta($the_player_id ( '.$the_player_id.' ),\'dzsap_total_time\',true) from parse_items - '.get_post_meta($the_player_id,'dzsap_total_time',true).' -> '.intval(get_post_meta($the_player_id,'dzsap_total_time',true));

//            print_rr($margs);




	        $post_type = '';

	        if($che['playerid']){

		        $po = get_post($che['playerid']);

		        if($po){
			        if($po->post_type){
				        $post_type = $po->post_type;
			        }
		        }

		        if($post_type){

			        $i_fout.=' data-posttype="'.$post_type.'"';

			        $che['post_type']=$post_type;
		        }
            }


            // -- try to set from cache total time
	        if(isset($margs['source']) && $margs['source']!='fake' && get_post_meta($the_player_id,'dzsap_total_time',true)){

//	            echo 'whaaa ';

	            $i_fout.=' data-sample_time_total="'.intval(get_post_meta($the_player_id,'dzsap_total_time',true)).'"';
	        }


            if($this->check_if_user_played_track($playerid)===true){
                $i_fout.=' data-viewsubmitted="on"';
            }

//            echo '$the_player_id - '.$the_player_id;

            if ($the_player_id != '') {
                $the_player_id_sanitized_to_number = str_replace('ap','',$the_player_id);



                if($margs['called_from']=='footer_player'){

	                $i_fout.= ' id="dzsap_footer"';
                }else{
                    $i_fout.= ' id="ap'.$the_player_id_sanitized_to_number.'"';
                }

                $i_fout.=' data-playerid="'.$the_player_id_sanitized_to_number.'"';
            };




//	        echo '$this->mainoptions[\'try_to_get_id3_thumb_in_frontend\'] - '.$this->mainoptions['try_to_get_id3_thumb_in_frontend'];




	        if(isset($che['dzsap_meta_source_attachment_id']) && $che['dzsap_meta_source_attachment_id']){

	        }else{
	            // -- try to get dzsap_meta_source_attachment_id if it's a dzsap_item
	            if($che['playerid']){


	                if(get_post_meta($che['playerid'],'dzsap_meta_source_attachment_id',true)){
		                $che['dzsap_meta_source_attachment_id'] = get_post_meta($che['playerid'],'dzsap_meta_source_attachment_id',true);
                    }


                }
            }





	        if( $this->mainoptions['try_to_get_id3_thumb_in_frontend']=='on'){


		        if(isset($che['dzsap_meta_source_attachment_id']) && $che['dzsap_meta_source_attachment_id']){

			        if( ! (isset( $che['thumb']) && $che['thumb'])){

				        // -- get base64 data in frontend


				        //                print_rr($che);
				        //                echo '$attachment_id - '; print_rr($attachment_id);


				        $file = get_attached_file($che['dzsap_meta_source_attachment_id']);

				        require_once( ABSPATH . 'wp-admin/includes/media.php' );
				        $metadata = wp_read_audio_metadata($file);

				        //	                echo 'metadata -> '; print_rr($metadata);

				        if($metadata && isset($metadata['image']) && isset($metadata['image']['data'])){


					        //	                    echo 'lala';
					        $che['thumb']='data:image/jpeg;base64,'.base64_encode($metadata['image']['data']);
				        }

			        }


                    //print_rr($che);


			        if( ! (isset( $che['artistname']) && $che['artistname'])){


				        $file = get_attached_file($che['dzsap_meta_source_attachment_id']);

				        require_once( ABSPATH . 'wp-admin/includes/media.php' );
				        $metadata = wp_read_audio_metadata($file);

//				        print_rr($metadata);

			        }

		        }
	        }





	        if($che['thumb'] && $che['thumb']!='default'){

		        $che['thumb'] = $this->sanitize_id_to_src($che['thumb']);
            }else{
	            if(isset($che['post_type']) && $che['post_type']){
		            $che['thumb'] = $this->get_thumbnail($che['playerid']);
                }
            }




            if (isset($che['thumb']) && $che['thumb']=='none') {
                $che['thumb']='';
            }
            if (isset($che['thumb']) && $che['thumb']) {
                $i_fout.=' data-thumb="' . $che['thumb'] . '"';
            };
            if (isset($che['thumb_for_parent']) && $che['thumb_for_parent']) {

	            $thumb_for_parent_attr.=' data-thumb_for_parent="' . $che['thumb_for_parent'] . '"';
            };
	        $i_fout.= $thumb_for_parent_attr;

            if (isset($che['thumb_link']) && $che['thumb_link']) {
	            $thumb_link_attr.=' data-thumb_link="' . $che['thumb_link'] . '"';
            };

            $i_fout .=$thumb_link_attr;
            if( isset($che['wrapper_image']) && $che['wrapper_image']){
                $i_fout.=' data-wrapper-image="'.$this->sanitize_id_to_src($che['wrapper_image']).'" ';
            }

            if (isset($che['publisher']) && $che['publisher']) {
                $i_fout.=' data-publisher="' . $che['publisher'] . '"';
            };




            if(isset($che['sample_time_start']) && $che['sample_time_start']){
                if($this->mainoptions['sample_time_pseudo']=='pseudo'){

                    $i_fout.=' data-pseudo-sample_time_start="'.$che['sample_time_start'].'"';
                }else{

                    $i_fout.=' data-sample_time_start="'.$che['sample_time_start'].'"';
                }
            }

            if(isset($che['sample_time_end']) && $che['sample_time_end']){
                if($this->mainoptions['sample_time_pseudo']=='pseudo'){

                    $i_fout.=' data-pseudo-sample_time_end="'.$che['sample_time_end'].'"';
                }else{

                    $i_fout.=' data-sample_time_end="'.$che['sample_time_end'].'"';
                }
            }

            if(isset($che['sample_time_total']) && $che['sample_time_total']){
                $i_fout.=' data-sample_time_total="'.$che['sample_time_total'].'"';
            }


            if($margs['called_from']=='gallery'){

//                print_r($che);
            }

	        if (isset($che['play_in_footer_player']) && $che['play_in_footer_player'] =='on') {

		        $fakeplayer_attr = ' data-fakeplayer=".dzsap_footer"';
	        };





            if($this->mainoptions['skinwave_wave_mode']=='canvas'){
//                print_r($che);


                $i_fout.=$pcm;
            }else{
                if (isset($che['waveformbg']) && $che['waveformbg'] != '') {
                    $i_fout.=' data-scrubbg="' . $che['waveformbg'] . '"';
                };
                if (isset($che['waveformprog']) && $che['waveformprog'] != '') {
                    $i_fout.=' data-scrubprog="' . $che['waveformprog'] . '"';
                };
            }

            if ($type != '') {

                if($type=='detect'){
                    if($che['source']){

                        if($che['source']!=sanitize_youtube_url_to_id($che['source'])){
                            $type='youtube';
	                        $che['source']=sanitize_youtube_url_to_id($che['source']);
                        }
                    }
                }
                $i_fout.=' data-type="' . $type . '"';
            };


            if(($this->mainoptions['developer_check_for_bots_and_dont_reveal_source']=='on' && $this->is_bot()==false) || $this->mainoptions['developer_check_for_bots_and_dont_reveal_source']!='on'){

	            if (isset($che['source']) && $che['source'] != '') {
		            $i_fout.=' data-source="' . $che['source'] . '"';
	            };
	            if (isset($che['sourceogg']) && $che['sourceogg'] != '') {
		            $i_fout.=' data-sourceogg="' . $che['sourceogg'] . '"';
	            };
            }

            if (isset($che['bgimage']) && $che['bgimage'] != '') {
                $i_fout.=' data-bgimage="' . $che['bgimage'] . '"';
                $i_fout.=' data-wrapper-image="' . $che['bgimage'] . '"';
            };



//            print_r($che);
            if ($che['playfrom']) {
                $i_fout.=' data-playfrom="' . $che['playfrom'] . '"';
            };

//                    print_r($margs);;
            if(isset($margs['single']) && $margs['single']=='on'){
                if(isset($margs['width']) && isset($margs['height'])){

                    // ===== some sanitizing
                    $tw = $margs['width'];
                    $th = $margs['height'];
                    $str_tw = '';
                    $str_th = '';




                    if($tw!=''){
                        if (strpos($tw, "%") === false && $tw!='auto') {
                            $str_tw = ' width: '.$tw.'px;';
                        }else{
                            $str_tw = ' width: '.$tw.';';
                        }
                    }


                    if($th!=''){
                        if (strpos($th, "%") === false && $th!='auto') {
                            $str_th = ' height: '.$th.'px;';
                        }else{
                            $str_th = ' height: '.$th.';';
                        }
                    }

//                    print_r($margs); echo $str_tw; echo $str_th;


                    $i_fout.=' style="'.$str_tw.'"';

                }
            }
            if(isset($margs['faketarget']) && $margs['faketarget']){
                $fakeplayer_attr=' data-fakeplayer="'.$margs['faketarget'].'"';
            }

            $i_fout.=$fakeplayer_attr;

            $i_fout.='>';
            //print_r($che);
            $che['menu_artistname'] = stripslashes($che['menu_artistname']);
            $che['menu_songname'] = stripslashes($che['menu_songname']);


            if($che['menu_artistname']=='default'){


                if($che['artistname']){
                    $che['menu_artistname'] = $che['artistname'];
                }else{
                    if($playerid){



                        $che['menu_artistname'] = $po->post_title;
                    }
                }


            }
            if($che['menu_songname']=='default'){

                if($che['songname']){
                    $che['menu_songname'] = $che['songname'];
                }else{
                    if($playerid){


                        if($po->post_content){
                            $che['menu_songname'] = $po->post_content;
                        }


                        if($po->post_excerpt){
                            $che['menu_songname'] = $po->post_excerpt;
                        }

                        if($po->post_type=='attachment'){
                            $po_metadata = wp_get_attachment_metadata($playerid);

                            //                        print_r($po_metadata);
                        }

                    }

                }
            }
            if($che['menu_artistname']=='default'){



                $che['menu_artistname'] = '';
            }
            if($che['menu_songname']=='default'){
                $che['menu_songname'] = '';
            }


//            print_rr($che);

            if(isset($che['replace_songname']) && $che['replace_songname']){

	            $che['songname'] = $che['replace_songname'];
            }



	        if($che['songname']=='default' || $che['songname']=='{{id3}}'){
		        // -- lets see id3 tag

//		        echo 'ABSPATH - '.ABSPATH.'|||';
//		        echo 'get_home_url - '.get_home_url();

		        $home_url = get_home_url();


//		        echo 'che before id 3 analyze'; print_rr($che);

		        if(strpos($che['source'], $home_url)!==false){
			        $mp3path = str_replace($home_url, ABSPATH, $che['source']);

//			        echo '$mp3path - '.$mp3path;
//			        echo 'function_exists(\'id3_get_tag\') - '.function_exists('id3_get_tag');

			        if(function_exists('id3_get_tag')){

				        $tag = id3_get_tag( $mp3path );

//				        echo ' tags -'; print_rr($tag);
                    }else{






			            if($this->get_songname_from_attachment($che)){
			                $che['songname']=$this->get_songname_from_attachment($che);
                        }





//
//				        print_rr($metadata);
                    }
		        }else{


		            // -- outer domain source;
			        if($this->get_songname_from_attachment($che)){
				        $che['songname']=$this->get_songname_from_attachment($che);
			        }

		        }
	        }

//	        echo ' che after id3 analyze'; print_rr($che);

	        if($che['songname']=='default'){
		        $che['songname'] = '';
	        }



	        if($che['artistname']=='none'){
		        $che['artistname'] = '';
	        }


	        if($che['songname']=='none'){
		        $che['songname'] = '';
	        }






            if($che['artistname']=='default'){
                $che['artistname'] = '';
            }

//            print_rr($che);
            if($che['songname']=='default' || $che['songname']=='{{id3}}'){
                $che['songname'] = '';
            }


//            print_r($che);


//            print_rr($che);

            if(isset($che['player_id']) && $che['player_id']=='dzsap_footer'){
                $che['menu_artistname'] = ' ';
                $che['menu_songname'] = ' ';
            }

            $meta_artist_html = '';


            $has_artist_name = false;
//            print_rr($che);
            if (( isset($che['artistname']) && $che['artistname'] )  || ( isset($che['songname']) && $che['songname'] ) || $margs['called_from']=='footer_player' ) {
	            $meta_artist_html.='<div class="meta-artist track-meta-for-dzsap">';
	            $meta_artist_html.='<span class="the-artist first-line">';




	            if($che['artistname']){
		            $has_artist_name = true;


		            $meta_artist_html.='<span class="first-line-label">' . $che['artistname'] . '</span>';
                }


                if(isset($margs['settings_extrahtml_after_artist'])){
	                $meta_artist_html.=wp_kses(do_shortcode($margs['settings_extrahtml_after_artist']), $this->allowed_tags);
                }

//                print_rr($margs);

	            $meta_artist_html.='</span>';
                if ($che['songname'] != '' || $che['called_from']=='footer_player' ) {

                    if($has_artist_name){

	                    $meta_artist_html.='&nbsp;';
                    }

	                $meta_artist_html.='<span class="the-name the-songname second-line">' . $che['songname'] . '</span>';
                }

	            $meta_artist_html.='</div>';
            }


//            echo 'che 5 '; print_rr($che);
            if($che['artistname']){

                $i_fout.='<div class="feed-dzsap feed-artist-name">'.$che['artistname'].'</div>';
            }
            if($che['songname']){

                $i_fout.='<div class="feed-dzsap feed-song-name">'.$che['songname'].'</div>';
            }

            $i_fout .= $meta_artist_html;

//            print_rr($che);

            if(isset($che['wrapper_image_type']) && $che['wrapper_image_type']){



                if($che['wrapper_image_type']=='zoomsounds-wrapper-bg-bellow'){

                    $this->sw_enable_multisharer = true;
                    $i_fout.='<div href="#" class=" dzsap-wrapper-but dzsap-multisharer-but "><span class="the-icon">{{svg_share_icon}}</span> </div>';

                    $i_fout.='<div href="#" class=" dzsap-wrapper-but btn-like "><span class="the-icon">{{heart_svg}}</span> </div>';
                }

            }




            // -- menu
            if ($che['menu_artistname'] != '' || $che['menu_songname'] != '' || (isset( $che['thumb']) && $che['thumb'] != '')) {
                $i_fout.='<div class="menu-description">';
                if (isset($che['thumb']) && $che['thumb'] ) {
                    $i_fout.='<div class="menu-item-thumb-con"><div class="menu-item-thumb" style="background-image: url(' . $che['thumb'] . ')"></div></div>';
                }


//                print_r($margs);

                if($margs['gallery_skin']=='skin-aura'){
                    $i_fout.='<div class="menu-artist-info">';
                }


                $i_fout.='<span class="the-artist">' . $che['menu_artistname'] . '</span>';
                $i_fout.='<span class="the-name">' . $che['menu_songname'] . '</span>';


                if($margs['gallery_skin']=='skin-aura'){
                    $i_fout.='</div>';
                }

                if (isset($_COOKIE['dzsap_ratesubmitted-' . $playerid])) {
                    $che['menu_extrahtml'] = str_replace('download-after-rate', 'download-after-rate active', $che['menu_extrahtml']);
                } else {
                    if (isset($_COOKIE['commentsubmitted-' . $playerid])) {
                        $che['menu_extrahtml'] = str_replace('download-after-rate', 'download-after-rate active', $che['menu_extrahtml']);
                    };
                }


//                print_r($margs);
                if($margs['gallery_skin']=='skin-aura'){
                    $i_fout.='<div class="menu-item-views"><svg class="svg-icon" version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="11.161px" height="12.817px" viewBox="0 0 11.161 12.817" enable-background="new 0 0 11.161 12.817" xml:space="preserve"> <g> <g> <g> <path fill="#D2D6DB" d="M8.233,4.589c1.401,0.871,2.662,1.77,2.801,1.998c0.139,0.228-1.456,1.371-2.896,2.177l-4.408,2.465 c-1.44,0.805-2.835,1.474-3.101,1.484c-0.266,0.012-0.483-1.938-0.483-3.588V3.666c0-1.65,0.095-3.19,0.212-3.422 c0.116-0.232,1.875,0.613,3.276,1.484L8.233,4.589z"/> </g> </g> </g> </svg> <span class="the-count">'.get_post_meta($playerid, '_dzsap_views', true).'</span></div>';



                    if($margs['menu_facebook_share']=='auto' || $margs['menu_facebook_share']=='on' || $margs['menu_like_button']=='auto' || $margs['menu_like_button']=='on'){

                        $i_fout.='<div class="float-right">';
                        if($margs['menu_facebook_share']=='auto' || $margs['menu_facebook_share']=='on'){

                            $i_fout.='<a class="btn-zoomsounds-menu menu-facebook-share"  onclick=\'window.dzs_open_social_link("http://www.facebook.com/sharer.php?u={{shareurl}}",this); return false;\'><i class="fa fa-share" aria-hidden="true"></i></a>';
                        }
                        if($margs['menu_like_button']=='auto' || $margs['menu_like_button']=='on'){

                            $i_fout.='<a class="btn-zoomsounds-menu menu-btn-like "><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>';

                        }

                        $i_fout.='</div>';
                    }
                }


                $i_fout.=stripslashes($che['menu_extrahtml']);
                $i_fout.='</div>';
            }

//            print_r($its);
            if (isset($its['settings']['skinwave_comments_enable']) && $its['settings']['skinwave_comments_enable'] == 'on') {

                if ($playerid != '') {

                    $i_fout.='<div class="the-comments">';
                    $comms = get_comments(array('post_id' => $playerid));
//                    echo 'cevacomm'; print_r($comms);
                    foreach ($comms as $comm) {


                        $i_fout.='<span class="dzstooltip-con" style="left:'.$this->sanitize_to_css_perc($comm->comment_author_url).'"><span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black" style="width: 250px;"><span class="the-comment-author">@'.$comm->comment_author.'</span> says:<br>'.$comm->comment_content.'</span><div class="the-avatar" style="background-image: url(https://secure.gravatar.com/avatar/'.md5($comm->comment_author_email).'?s=20)"></div></span>';




                    }
                    $i_fout.='</div>';


                    wp_enqueue_style('dzs.tooltip', $this->base_url . 'dzstooltip/dzstooltip.css');
                }
            }

            if (isset($its['settings']) && $its['settings']['skin_ap'] && ( $its['settings']['skin_ap']=='skin-customcontrols' || $its['settings']['skin_ap']=='skin-customhtml')  ){


                if($margs['the_content']){

                    $i_fout.=do_shortcode($margs['the_content']);



                }else{


//                    $vpset = $this->get_zoomsounds_player_config_settings($this->mainoptions['enable_global_footer_player']);
                    if(isset($margs['settings_extrahtml_in_player']) && $margs['settings_extrahtml_in_player']){

                        $i_fout.=$this->sanitize_from_meta_textarea($margs['settings_extrahtml_in_player']);




                    }else{
                        $i_fout.=' <div class="custom-play-btn"><div class=" play-button-con"  style="color: #cb1919; width: 25px; height: 25px;"><i class="fa fa-play" style="font-size: 12px;"></i></div></div>  <div class="custom-pause-btn"><div class=" play-button-con "  style="color: #cb1919; width: 25px; height: 25px;"><i class="fa fa-pause" style="font-size: 12px;"></i></div></div>';
                    }
                }
//                print_r($margs);
            }
            // --- extra html meta
//            print_r($its);
//            print_r($margs);


            if($this->debug){
                print_rr($che);
            }



            if($playerid){

            }else{

	            $playerid = $this->encode_to_number($che['source']);
            }

            $che_post = null;
	        if($playerid){
		        $che_post = get_post($playerid);
	        }



//            print_rr($che);
            if (isset($its['settings']) && ($its['settings']['enable_views'] == 'on' || $its['settings']['enable_downloads_counter'] == 'on' || $its['settings']['enable_likes'] == 'on' || $its['settings']['enable_rates'] == 'on' || (isset($che['extra_html']) && $che['extra_html'] )

                || (isset($its['settings']['menu_right_enable_info_btn']) && $its['settings']['menu_right_enable_info_btn']=='on')   )
                || (isset($its['settings']['menu_right_enable_multishare']) && $its['settings']['menu_right_enable_multishare']=='on')    || (isset($che['enable_download_button']) && $che['enable_download_button']=='on' )
                || (isset($che['extra_html_in_controls_right']) && $che['extra_html_in_controls_right'] )
                || (isset($che['extra_html_in_bottom_controls']) && $che['extra_html_in_bottom_controls'] )
                || (isset($che['extrahtml_in_float_right_from_player']) && $che['extrahtml_in_float_right_from_player'] )
                || (isset($che['extra_html_left']) && $che['extra_html_left'] )
            ) {
                $aux_extra_html = '';



                if(isset($che['extra_html_left'])){

	                $che['extra_html_left'] = wp_kses($che['extra_html_left'],$this->allowed_tags);
                }
//	            print_rr($che);

                if((isset($che['extra_html_left']) && $che['extra_html_left'] )){
                    $aux_extra_html.='<div class="extra-html--left">'.$che['extra_html_left'].'</div>';
                    $aux_extra_html.='<-- END .extra-html--left -->';
                }








	            $aux_extra_html_left = '';
                if ($its['settings']['enable_likes'] == 'on') {
                    $aux_extra_html_left.=$this->mainoptions['str_likes_part1'];

                    if (isset($_COOKIE["dzsap_likesubmitted-" . $playerid])) {
                        $aux_extra_html_left = str_replace('<span class="btn-zoomsounds btn-like">', '<span class="btn-zoomsounds btn-like active">', $aux_extra_html_left);
                    }
                }


//                print_r($che);





                if( (isset($che['enable_download_button']) && $che['enable_download_button']=='on' ) ){


                    $download_link = '';



                    $download_link = $this->get_download_link($che,$playerid);

                    $download_str='<a target="_blank" href="'.$download_link.'" class="btn-zoomsounds btn-zoomsounds-download"';


                    if($this->mainoptions['register_to_download_opens_in_new_link']=='on'){
                        $download_str.=' target="_blank"';
                    }

                    $download_str.='><span class="the-icon"><i class="fa fa-get-pocket"></i></span><span class="the-label">'.$this->mainoptions['i18n_free_download'].'</span></a>';





                    $allow_download = true;





                    if($this->mainoptions['allow_download_only_for_registered_users']=='on'){

                       // dzstooltip-con" style="top:10px;"><span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black align-right" style="width: auto; white-space: nowrap;">Add to Cart</span>
                        global $current_user;

//                        print_rr($current_user);


                        if($current_user->ID){

                            if($this->mainoptions['allow_download_only_for_registered_users_capability'] && $this->mainoptions['allow_download_only_for_registered_users_capability']!='read'){
                                if(current_user_can($this->mainoptions['allow_download_only_for_registered_users_capability'])==false){


	                                $allow_download = false;

                                }
                            }

                        }else {

                            $allow_download = false;
                        }


                    }

                    if($allow_download==false){

                        
                        // -- downlaod button


                        $download_str = '<span href="' . $download_link . '" class="btn-zoomsounds btn-zoomsounds-download  dzstooltip-con "><span class="tooltip-indicator"><span class="the-icon"><i class="fa fa-get-pocket"></i></span><span class="the-label" style="opacity:0.5">' . $this->mainoptions['i18n_free_download'] . '</span></span> <span class="dzstooltip arrow-from-start transition-slidein arrow-bottom talign-start style-rounded color-dark-light align-right" style="width: auto; white-space: nowrap;"><span class="dzstooltip--inner">' . $this->mainoptions['i18n_register_to_download'] . '</span></span> </span>';
                    }


                    // data-playerid="'.$playerid.'"
                    $aux_extra_html_left.=$download_str;

                }

                // -- end download button



//                print_rr($che);


//                echo 'margs - '; print_rr($margs);
                if(isset($margs['single']) && $margs['single']=='on'){
                    if(isset($margs['enable_embed_button']) && ( $margs['enable_embed_button']=='on' || $margs['enable_embed_button']=='in_extra_html'  )  ){

                        if(isset($margs['embed_code']) && $margs['embed_code'] && $margs['embedded']!='on'){

                            $aux_extra_html_left.='<span class=" btn-zoomsounds dzstooltip-con btn-embed">  ';


	                        $aux_extra_html_left.='<span class="tooltip-indicator"><span class="the-icon"><i class="fa fa-share"></i></span><span class="the-label ">'.__('Embed').'</span></span>';



                            $aux_extra_html_left.='<span class="dzstooltip transition-slidein arrow-bottom talign-start style-rounded color-dark-light " style="width: 350px; "><span class="dzstooltip--inner"><span style="max-height: 150px; overflow:hidden; display: block; white-space: normal; font-weight: normal">{{embed_code}}</span> <span class="copy-embed-code-btn"><i class="fa fa-clipboard"></i> '.esc_html__('Copy Embed','dzsap').'</span> </span></span> ';


                            $aux_extra_html_left.='</span>';
                        }
                    }
                }


                if($aux_extra_html_left){

	                $aux_extra_html.='<div class="extra-html--left ">';
	                $aux_extra_html.=$aux_extra_html_left;
	                $aux_extra_html.='</div><!-- end .extra-html--left-->';
                }





                if ($its['settings']['enable_rates'] == 'on') {
//                    $aux_extra_html.='<div class="star-rating-con"><div class="star-rating-bg"></div><div class="star-rating-set-clip" style="width: ';

                    $aux = get_post_meta($playerid, '_dzsap_rate_index', true);

                    // -- 1 to 5
                    if ($aux == '') {
                        $aux = 0;
                    } else {
                        $aux = floatval ( $aux) / 5;
                    }
                    if($aux>5){
                        $aux = 5;
                    }

	                $perc = floatval( ( $aux ) * 100 );


	                $aux_extra_html.= '<div class="star-rating-con" data-initial-rating-index="'.$aux.'">';

//						echo 'whyy?';

                    $arte_stars = '<span class="rating-bg"><span class="rating-inner">{{starssvg}}</span></span>
                                    <span class="rating-prog" style="width: '.$perc.'%;"><span class="rating-inner">{{starssvg}}</span></span>';


                    $arte_stars = str_replace('{{starssvg}}',$this->svg_star.$this->svg_star.$this->svg_star.$this->svg_star.$this->svg_star, $arte_stars);

	                $aux_extra_html.=$arte_stars;
	                $aux_extra_html.='</div>';


                }



//                print_r($its);

//                error_log('$playerid - '.$playerid);
                if ($its['settings']['enable_views'] == 'on') {
                    $aux_extra_html.=$this->mainoptions['str_views'];
                    $aux = get_post_meta($playerid, '_dzsap_views', true);
                    if ($aux == '') {
                        $aux = 0;
                    }
                    $aux_extra_html = str_replace('{{get_plays}}', $aux, $aux_extra_html);
                }
                if ($its['settings']['enable_downloads_counter'] == 'on') {
                    $aux_extra_html.=$this->mainoptions['str_downloads_counter'];
                    $aux = get_post_meta($playerid, '_dzsap_downloads', true);
                    if ($aux == '') {
                        $aux = 0;
                    }
                    $aux_extra_html = str_replace('{{get_downloads}}', $aux, $aux_extra_html);
                }
                if ($its['settings']['enable_likes'] == 'on') {
                    $aux_extra_html.=$this->mainoptions['str_likes_part2'];
                    $aux = get_post_meta($playerid, '_dzsap_likes', true);

//                    echo '$playerid - '.$playerid;
                    if ($aux == '' || $aux == '-1') {
                        $aux = 0;
                    }
                    $aux_extra_html = str_replace('{{get_likes}}', $aux, $aux_extra_html);
                }




                if ($its['settings']['enable_rates'] == 'on') {
                    $aux_extra_html.=$this->mainoptions['str_rates'];
                    $aux = get_post_meta($playerid, '_dzsap_rate_nr', true);
                    if ($aux == '') {
                        $aux = 0;
                    }
                    $aux_extra_html = str_replace('{{get_rates}}', $aux, $aux_extra_html);

                    if (isset($_COOKIE['dzsap_ratesubmitted-' . $playerid])) {
                        $aux_extra_html.='{{ratesubmitted=' . $_COOKIE['dzsap_ratesubmitted-' . $playerid] . '}}';
                    };
                }


                if((isset($che['extra_html']) && $che['extra_html'] )){
                    $aux_extra_html.=''.$che['extra_html'];
                }



                if((isset($che['extra_html_in_controls_left']) && $che['extra_html_in_controls_left'] )){
                    $i_fout.='<div class="extra-html-in-controls-left" style="opacity:0;">'.$che['extra_html_in_controls_left'].'</div>';
                }




//                echo '$start_nr - '.$start_nr;
//                echo '$end_nr - '.$end_nr;
//                echo '$i - '.$i;
//                print_r($its);
//                print_rr($che);




                $str_info_btn = '';
                $str_multishare_btn = '';
                $str_extra_html_in_right_controls = '';


	            if((isset($its['settings']['menu_right_enable_info_btn']) && $its['settings']['menu_right_enable_info_btn']=='on') && isset($che) && isset($che['post_content']) && $che['post_content']){




//	                print_rr($che);

                    // -- infobtn

		            $str_info_btn .= do_shortcode('[player_button style="player-but"  icon="fa-info" link=""]'.wpautop(do_shortcode($che['post_content'])).'[/player_button]');





	            }

	            if(isset($its['settings']['menu_right_enable_multishare']) && $its['settings']['menu_right_enable_multishare']=='on'){
		            $this->sw_enable_multisharer = true;
	                $str_multishare_btn.=' <div class="player-but sharer-dzsap-but dzsap-multisharer-but"><div class="the-icon-bg"></div>{{svg_share_icon}}</div>';
                }


//                echo 'we arrived hier'; print_rr($che);
	            if((isset($che['extra_html_in_controls_right']) && $che['extra_html_in_controls_right'] )){





		            $download_link = '';


//		            echo 'we hot here';

		            $download_link = $this->get_download_link($che,$che['playerid']);


//	                print_rr($che);

//                    echo '$che[\'extra_html_in_controls_right\'] - '.$che['extra_html_in_controls_right'];
		            $che['extra_html_in_controls_right'] = str_replace('{{addtocart}}',add_query_arg(array(
			            'add-to-cart'=>$che['playerid']
                    ),dzs_curr_url()),$che['extra_html_in_controls_right']);
		            $che['extra_html_in_controls_right'] = str_replace('{{downloadlink}}',$download_link,$che['extra_html_in_controls_right']);
		            $che['extra_html_in_controls_right'] = str_replace('{{replacewithpostid}}',$che['playerid'],$che['extra_html_in_controls_right']);




		            if(get_permalink($che['playerid'])){


		                if(isset($margs['extra_html_in_controls_right'])){

			                $margs['extra_html_in_controls_right'] = str_replace('{{posturl}}',get_permalink($che['playerid']),$margs['extra_html_in_controls_right']);
                        }
                    }



//		            print_rr($che);

//                    print_rr($che);




		            $str_extra_html_in_right_controls.=''.(do_shortcode($che['extra_html_in_controls_right'])).'';

//		            print_rr($che_post);

		            if($che_post){

			            $str_extra_html_in_right_controls=''.$this->sanitize_to_extra_html($str_extra_html_in_right_controls, $che).'';
                    }



//		            echo '$che[\'extra_html_in_controls_right\'] 2 -> '.$che['extra_html_in_controls_right'];
//		            echo '$str_extra_html_in_right_controls  -2> '.$str_extra_html_in_right_controls;


	            }

                if(isset($che['extrahtml_in_float_right_from_player']) && $che['extrahtml_in_float_right_from_player']){

	                $str_extra_html_in_right_controls .=''.do_shortcode($this->sanitize_from_shortcode_attr($che['extrahtml_in_float_right_from_player'])).'';
                }

//                echo '$str_extra_html_in_right_controls - '.$str_extra_html_in_right_controls.'|';

	            if(strpos($str_extra_html_in_right_controls, 'dzsap-multisharer-but')!==false){

		            $this->sw_enable_multisharer = true;

	            }



//	            echo 'its- ';print_rr($its);
//	            echo 'margs- ';print_rr($margs);
//	            echo '$str_extra_html_in_right_controls -3> ';print_rr($str_extra_html_in_right_controls);
                if( isset($its['settings']) && ( $str_info_btn || $str_multishare_btn  || $str_extra_html_in_right_controls ) )  {


	                 // -- extra-html in right controls set in parse_items
                    $i_fout.='<div class="extra-html-in-controls-right" style="opacity:0;">';




                    $i_fout.=$str_info_btn;
                    $i_fout.=$str_multishare_btn;
                    $i_fout.=$str_extra_html_in_right_controls;






                    $i_fout.='</div>';
                }


//                echo 'hmmdada';
                if(strpos($aux_extra_html,'<i class="fa')!==false){


	                $url = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';

	                if($this->mainoptions['fontawesome_load_local']=='on'){
		                $url = $this->base_url.'libs/fontawesome/font-awesome.min.css';
	                }


	                wp_enqueue_style('fontawesome',$url);

                }
                
//                print_rr($che);

//	            echo '$aux_extra_html - '; print_rr($aux_extra_html);




                $i_fout.='<div class="extra-html" data-playerid="'.$che['playerid'].'" style="opacity:0;">' . ($aux_extra_html) . '</div>';
            }


            if($margs['called_from']=='single_product_summary'){

                if(isset($margs['product_id'])){

                if($this->mainoptions['wc_product_play_in_footer']=='on'){


//                    print_rr($margs);

                    $vpset = $this->get_zoomsounds_player_config_settings($this->mainoptions['enable_global_footer_player']);

//                    print_rr($vpset);








//                    print_rr($margs);



                    $price = '';

                        if(get_post_meta($margs['product_id'], '_regular_price',true)){
                            if(function_exists('get_woocommerce_currency_symbol')){
                                $price.=get_woocommerce_currency_symbol();
                            }
                            if(get_post_meta($margs['product_id'], '_sale_price',true)){

                                $price .= get_post_meta($margs['product_id'], '_sale_price',true);
                            }else{

                                $price .= get_post_meta($margs['product_id'], '_regular_price',true);
                            }
                        }





                    if(strpos($vpset['settings']['extra_classes_player'],'skinvariation-wave-righter')!==false){

                        $i_fout.='<div class="feed-dzsap-for-extra-html-right"><form method="post" style="margin: 0!important; "><button class="zoomsounds-add-tocart-btn" name="add-to-cart" value="'.$margs['product_id'].'"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;&nbsp;<span class="the-price">'.$price.'</span></button></form></div>';
                    }




                }
                }

            }


            if (isset($che['inner_html']) && $che['inner_html']) {
                $i_fout.=$che['inner_html'];
            }


            if(isset($che['settings_extrahtml_after_playpause']) && $che['settings_extrahtml_after_playpause']){
                $i_fout.='<div class="feed-dzsap feed-dzsap-after-playpause">';
                $i_fout.='<span class="con-after-playpause">';
                $i_fout.=$che['settings_extrahtml_after_playpause'];
                $i_fout.='</span>';
                $i_fout.='</div>';
            }


            if(isset($che['settings_extrahtml_after_con_controls']) && $che['settings_extrahtml_after_con_controls']){
                $i_fout.='<div class="feed-dzsap feed-dzsap-after-con-controls">';
                $i_fout.='<span class="con-after-con-controls">';
                $i_fout.=$che['settings_extrahtml_after_con_controls'];
                $i_fout.='</span>';
                $i_fout.='</div>';
            }



            $i_fout.='</div>';

            if (isset($che['apply_script'])) {

            }


	        if (isset($its['settings']) && $its['settings']['skin_ap'] && (  $its['settings']['skin_ap']=='skin-customhtml')  ){


//                print_rr($margs);
                $i_fout = $this->sanitize_from_meta_textarea($margs['settings_extrahtml_in_player']);

                $i_fout = str_replace('{{artist_complete_html}}',$meta_artist_html, $i_fout);


		        $lab = 'source';

		        if(isset($che[$lab])){
			        $i_fout = str_replace('{{'.$lab.'}}',$che[$lab], $i_fout);
		        }else{

			        $i_fout = str_replace('{{'.$lab.'}}','', $i_fout);
		        }
		        $lab = 'type';

		        if(isset($che[$lab])){
			        $i_fout = str_replace('{{'.$lab.'}}',$che[$lab], $i_fout);
		        }else{

			        $i_fout = str_replace('{{'.$lab.'}}','', $i_fout);
		        }

                $lab = 'thumb';

                if(isset($che[$lab])){
	                $i_fout = str_replace('{{'.$lab.'}}',$che[$lab], $i_fout);
                }else{

	                $i_fout = str_replace('{{'.$lab.'}}','', $i_fout);
                }
                $lab = 'pcm';

		        $i_fout = str_replace('{{'.$lab.'}}',$pcm, $i_fout);

		        $lab='fakeplayer_attr';
		        $i_fout = str_replace('{{'.$lab.'}}',$fakeplayer_attr, $i_fout);

		        $lab='thumb_for_parent_attr';
		        $i_fout = str_replace('{{'.$lab.'}}',$thumb_for_parent_attr, $i_fout);

		        $lab='thumb_link';
		        $i_fout = str_replace('{{'.$lab.'}}',$thumb_link_attr, $i_fout);

	        }

	        $fout.=$i_fout;
        }



        return $fout;
    }

	function check_if_user_played_track($track_id){

		global $current_user;

//        echo 'current_user - ';print_r($current_user);

		if($current_user && isset($current_user->data) && $current_user->data && isset($current_user->data->ID) && $current_user->data->ID){
			//--- if user logged in

//            echo 'dadada';
			return $this->mysql_check_if_user_did_activity($current_user->data->ID, $track_id,'view');
		}else{
			if (isset($_COOKIE['viewsubmitted-' . $track_id])) {
				return true;
			}
			return false;
		}
	}


	function check_if_user_liked_track($track_id,$id_user=0){

		global $current_user;

//        echo 'current_user - ';print_r($current_user);



		if($id_user==0 && $current_user && isset($current_user->data) && $current_user->data && isset($current_user->data->ID) && $current_user->data->ID){
			$id_user = $current_user->data->ID;
		}

		if($id_user){
			//--- if user logged in

//            echo 'dadada';

			// todo: maybe not permanent

//            if (isset($_COOKIE['dzsap_likesubmitted-' . $track_id])) {
//                return true;
//            }

//            echo ' $id_user-'.$id_user.'| - ( $track_id - '.$track_id.' ) -- $this->mysql_check_if_user_did_activity($id_user, $track_id,\'like\') -> '.($this->mysql_check_if_user_did_activity($id_user, $track_id,'like'));

			return $this->mysql_check_if_user_did_activity($id_user, $track_id,'like');
		}else{

//            echo 'check_if_user_liked_track - $_COOKIE '; print_rr($_COOKIE);
			if (isset($_COOKIE['likesubmitted-' . $track_id])) {
				return true;
			}
			if (isset($_COOKIE['dzsap_likesubmitted-' . $track_id])) {
				return true;
			}
			return false;
		}
	}

	function mysql_check_if_user_did_activity($id_user, $track_id, $type='view'){


//	    print_rr($this->mainoptions);
		if($this->mainoptions['wpdb_enable']=='on') {
			global $wpdb;


			$currip = $this->misc_get_ip();
			$date = date('Y-m-d H:i:s');
			$table_name = $wpdb->prefix . 'dzsap_activity';

			$user_id = 0;

//        echo '$id_user - '.$id_user.' track_id - '.$track_id;
//                    error_log('adding '.$table_name);

//            echo 'get_option(\'dzsap_table_activity_created\') - '.get_option('dzsap_table_activity_created');
			if (get_option('dzsap_table_activity_created')) {

				$table_name = $wpdb->prefix . 'dzsap_activity';
				$query = "SELECT * FROM $table_name WHERE `id_user` = '$id_user' AND `id_video`='$track_id' AND `type`='$type'";


//        echo $query;
				$mylink = $wpdb->get_row($query);

//                echo 'mylink -> '; print_r($mylink);

				if ($mylink && isset($mylink->id)) {
					return true;
				}
			}


			return false;
		}




	}





    function get_post_thumb_src($it_id){
        $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($it_id), "full");

        return $imgsrc[0];
    }

    function object_to_array($data){
        if (is_array($data) || is_object($data)){
            $result = array();
            foreach ($data as $key => $value)
            {
                $result[$key] = object_to_array($value);
            }
            return $result;
        }
        return $data;
    }


    function sanitize_id_to_src($arg){

//        echo ' arg - '.$arg;
        if(is_numeric($arg)){

            $imgsrc = wp_get_attachment_image_src($arg, 'full');
//            print_r($imgsrc);
//            echo ' $imgsrc - '.$imgsrc;
            return $imgsrc[0];
        }else{
            return $arg;
        }


    }


	function sanitize_to_extra_html($extra_html, $po=null){



		$playerid = 0;





		if($po){
			if(isset($po->ID)){

				$playerid = $po->ID;
			}
			if(isset($po['playerid'])){
				$playerid = $po['playerid'];
			}
		}


//		echo 'playerid - '.$playerid;

//        echo '$extra_html - '.$extra_html; echo ' ||| ';

		$extra_html=str_replace('{{theid}}',$playerid,$extra_html);


//		echo '$extra_html after - '.$extra_html; echo ' ||| *** ||| ';


		$icon_wishlist = 'fa-heart-o';
		if(strpos($extra_html,'{{audio_love_toggler_icon}}')!==false){

			//	        $arr_wishlist= $this->get_wishlist();



			global $dzsap;

			//            print_rr($dzsap);

//		    echo 'playerid - '.$playerid;
//            echo '$dzsap->check_if_user_liked_track($playerid - '.$playerid.') - '.$dzsap->check_if_user_liked_track($playerid);
			if($dzsap && $dzsap->check_if_user_liked_track($playerid)){
				$icon_wishlist = str_replace('fa-heart-o','fa-heart',$icon_wishlist);
			}


			//	        echo '$arr_wishlist - '; print_rr($arr_wishlist);
			//	        if(in_array($po->ID,$arr_wishlist)){
			//	        }
			$extra_html=str_replace('{{audio_love_toggler_icon}}',$icon_wishlist,$extra_html);
		}



		$extra_html=str_replace('{{hearts_svg}}','<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.0" width="15" height="15"  viewBox="0 0 645 700" id="svg2"> <defs id="defs4" /> <g id="layer1"> <path d="M 297.29747,550.86823 C 283.52243,535.43191 249.1268,505.33855 220.86277,483.99412 C 137.11867,420.75228 125.72108,411.5999 91.719238,380.29088 C 29.03471,322.57071 2.413622,264.58086 2.5048478,185.95124 C 2.5493594,147.56739 5.1656152,132.77929 15.914734,110.15398 C 34.151433,71.768267 61.014996,43.244667 95.360052,25.799457 C 119.68545,13.443675 131.6827,7.9542046 172.30448,7.7296236 C 214.79777,7.4947896 223.74311,12.449347 248.73919,26.181459 C 279.1637,42.895777 310.47909,78.617167 316.95242,103.99205 L 320.95052,119.66445 L 330.81015,98.079942 C 386.52632,-23.892986 564.40851,-22.06811 626.31244,101.11153 C 645.95011,140.18758 648.10608,223.6247 630.69256,270.6244 C 607.97729,331.93377 565.31255,378.67493 466.68622,450.30098 C 402.0054,497.27462 328.80148,568.34684 323.70555,578.32901 C 317.79007,589.91654 323.42339,580.14491 297.29747,550.86823 z" id="path2417" style="" /> <g transform="translate(129.28571,-64.285714)" id="g2221" /> </g> </svg>',$extra_html);
		$extra_html=str_replace('{{site_url}}',site_url(),$extra_html);

		$permalink = '';
		if(isset($po) && $po && isset($po->ID) && $po->ID){
			$permalink = get_permalink($po->ID);
		}
		$extra_html=str_replace('{{itempermalink}}',$permalink,$extra_html);

		return $extra_html;
	}


	function sanitize_to_array_for_parse($its, $margs){
//        print_r($its);
//        print_r($margs);

        foreach($its as $lab => $it){
//            $its[$lab] = $this->object_to_array($it);
            $its[$lab] = (array) $it;


            $thumb = $this->get_post_thumb_src($it->ID);

//            echo ' thumb - ';
//            print_r($thumb);


            $thumb_from_meta = get_post_meta($it->ID, 'dzsrst_meta_item_thumb',true);

            if($thumb_from_meta){

                $thumb = $thumb_from_meta;
            }

            if($thumb){
//                $its[$lab]->thumbnail = $thumb;
                $its[$lab]['thumbnail'] = $thumb;
            }

//            print_r($margs);


            $its[$lab]['title_permalink'] = get_permalink($it->ID);

            $its[$lab]['price'] = get_post_meta($it->ID, 'dzsrst_meta_item_price',true);

            if($margs['post_type']=='product'){
                if(get_post_meta($it->ID, '_regular_price',true)){
                    $its[$lab]['price'] = '';
                    if(function_exists('get_woocommerce_currency_symbol')){
                        $its[$lab]['price'].=get_woocommerce_currency_symbol();
                    }
                    $its[$lab]['price'] .= get_post_meta($it->ID, '_regular_price',true);
                }
            }

//            $its[$lab]['ingredients'] = get_post_meta($it->ID, 'dzsrst_meta_item_ingredients',true);
            $its[$lab]['bigimage'] = $this->sanitize_id_to_src(get_post_meta($it->ID, 'dzsrst_meta_item_bigimage',true));









        }

        return $its;
    }











    function cs_home_before(){
        //    echo 'hmmdada';
        // -- enqueue in cusotmizer

        wp_enqueue_script( 'dzsap-admin-for-cornerstone', $this->base_url . 'assets/admin/admin-for-cornerstone.js', array('jquery'));
        wp_enqueue_script( 'dzsap-admin-global', $this->base_url . 'admin/admin_global.js', array('jquery'));
        wp_enqueue_style( 'dzsap-admin-global', $this->base_url . 'admin/admin_global.css');

    }

    function cs_register_elements() {

//        echo 'ceva';

//        error_log('register_elements');
        cornerstone_register_element( 'CS_DZSAP', 'dzsap', $this->base_path . 'cs/dzsap' );
        cornerstone_register_element( 'CS_DZSAP_PLAYLIST', 'dzsap_playlist', $this->base_path . 'cs/dzsap_playlist' );
//        cornerstone_register_element( 'CS_DZSAP_PLAYLIST', 'dzsap_playlist', $this->base_path . 'includes/dzsap_playlist' );

    }

    function cs_enqueue() {
        wp_enqueue_style( 'dzsap', $this->base_url . 'audioplayer/audioplayer.css');
        wp_enqueue_script( 'dzsap', $this->base_url . 'audioplayer/audioplayer.js', array('jquery'));


        //    wp_enqueue_style( 'dzs.scroller', $this->base_url . 'assets/dzsscroller/scroller.css');
        //    wp_enqueue_script( 'dzs.scroller', $this->base_url . 'assets/dzsscroller/scroller.js');
    }

    function cs_icon_map( $icon_map ) {
        $icon_map['dzsap'] = $this->base_url . '/assets/svg/icons.svg';
        return $icon_map;
    }






    function delete_activity($pargs = array()){
	    if($this->mainoptions['wpdb_enable']=='on'){
		    global $wpdb;


		    $margs = array(
			    'type' => 'download',
			    'id_user' => '',
			    'id_video' => '',
		    );

		    if ($pargs == '' || is_array($pargs) == false) {
			    $pargs = array();
		    }

		    $margs = array_merge($margs, $pargs);

		    $currip = $this->misc_get_ip();
		    $date = date('Y-m-d H:i:s');




		    if(get_option('dzsap_table_activity_created')) {
			    $table_name = $wpdb->prefix . 'dzsap_activity';

			    $user_id = 0;
			    $current_user = wp_get_current_user();

			    if ($current_user) {
				    if ($current_user->ID) {
					    $user_id = $current_user->ID;
				    }
			    }

//                    error_log('adding '.$table_name);

			    $args = array(
				    'ip' => $currip,
				    'type' => $margs['type'],
				    'id_user' => $user_id,
				    'id_video' => $margs['id_video'],
				    'date' => $date,
			    );


$sql = 				    "
                DELETE FROM $table_name
		 WHERE type = '".$margs['type']."'
		";

if($user_id){
    $sql.= "AND id_user='".$user_id."'";
}
if($margs['id_video']){
    $sql.= "AND id_video='".$margs['id_video']."'";
}

			    $wpdb->prepare(
$sql,array()
			    );



			    error_log('adding '.$table_name. ' deleting args - '.print_r($args,true));

		    }else{

			    $this->create_activity_table();
		    }
	    }

    }
    function insert_activity($pargs = array()){


//	    error_log('$this->mainoptions[\'wpdb_enable\'] - '.($this->mainoptions['wpdb_enable']));
        if($this->mainoptions['wpdb_enable']=='on'){
            global $wpdb;


            $margs = array(
                'type' => 'download',
                'id_user' => '',
                'id_video' => '',
            );

            if ($pargs == '' || is_array($pargs) == false) {
                $pargs = array();
            }

            $margs = array_merge($margs, $pargs);

            $currip = $this->misc_get_ip();
            $date = date('Y-m-d H:i:s');




            if(get_option('dzsap_table_activity_created')) {
                $table_name = $wpdb->prefix . 'dzsap_activity';

                $user_id = 0;
                $current_user = wp_get_current_user();

                if ($current_user) {
                    if ($current_user->ID) {
                        $user_id = $current_user->ID;
                    }
                }

//                    error_log('adding '.$table_name);

	            $args = array(
		            'ip' => $currip,
		            'type' => $margs['type'],
		            'id_user' => $user_id,
		            'id_video' => $margs['id_video'],
		            'date' => $date,
	            );


                if($margs['type']=='like' || $margs['type']=='download'){
                    $args['val']=1;
                }

	            error_log('adding '.$table_name. ' insert args - '.print_r($args,true));

	            $wpdb->insert($table_name, $args);
            }else{

	            $this->create_activity_table();
            }
        }

    }




    function ajax_delete_notice() {





        //        print_r($_POST);

        update_option($_POST['postdata'],'seen');
        die();
    }


    function ajax_deactivate_license() {

        $this->mainoptions['dzsap_purchase_code'] = '';
        $this->mainoptions['dzsap_purchase_code_binded'] = 'off';
        update_option($this->dbname_options, $this->mainoptions);

        die();
    }
    function ajax_activate_license() {





        $this->mainoptions['dzsap_purchase_code'] = $_POST['postdata'];
        $this->mainoptions['dzsap_purchase_code_binded'] = 'on';
        update_option($this->dbname_options, $this->mainoptions);

        die();

    }
    function handle_widgets_init(){




//	    error_log("HMM");
	    include_once "widget.php";
	    $dzsap_widget = new DZSAP_Tags_Widget();

	    $dzsap_widget::register_this_widget();

	    add_action('widgets_init', array($dzsap_widget, 'register_this_widget'));
    }
    function handle_admin_init(){

//        echo 'ceva';
	    if ($this->mainoptions['analytics_enable'] == 'on') {

		    wp_enqueue_script('google.charts', 'https://www.gstatic.com/charts/loader.js');

		    if ($this->mainoptions['analytics_enable_location'] == 'on') {

			    wp_enqueue_script('google.maps', 'https://www.google.com/jsapi');
		    }
	    }

        add_settings_section('dzsap-permalink', __('Audio Items Permalink Base', 'dzsap'), array($this, 'permalink_settings'), 'permalink');


	    if ( $this->mainoptions['analytics_table_created'] == 'off' ) {

		    $this->analytics_table_create();
	    }

    }

	function analytics_table_create(){

		global $wpdb;
//            $table_name = $wpdb->prefix . 'dzsvg_views';
//            if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
//                //table not in database. Create new table
//                $charset_collate = $wpdb->get_charset_collate();
//
//                $sql = "CREATE TABLE $table_name (
//          id mediumint(9) NOT NULL AUTO_INCREMENT,
//          type varchar(100) NOT NULL,
//          id_user int(10) NOT NULL,
//          ip varchar(255) NOT NULL,
//          id_video int(10) NOT NULL,
//          date datetime NOT NULL,
//          UNIQUE KEY id (id)
//     ) $charset_collate;";
//                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//                dbDelta($sql);
//            } else {
//            }
		$table_name = $wpdb->prefix . 'dzsap_activity';
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			//table not in database. Create new table
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          type varchar(100) NOT NULL,
          country varchar(100) NULL,
          id_user int(10) NOT NULL,
          val int(255) NOT NULL,
          ip varchar(255) NOT NULL,
          id_video int(10) NOT NULL,
          date datetime NOT NULL,
          UNIQUE KEY id (id)
     ) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );




			$this->mainoptions['analytics_table_created'] = 'on';;
			update_option($this->dbname_options, $this->mainoptions);

		} else {
		}

	}


    function permalink_settings() {

        echo wpautop(__('These settings control the permalinks used for products. These settings only apply when <strong>not using "default" permalinks above</strong>.', 'dzsap'));

        $permalinks = get_option('dzsap_permalinks');
        $dzsap_permalink = $permalinks['item_base'];
        //echo 'ceva';

        $item_base = _x('audio', 'default-slug', 'dzsap');

        $structures = array(0 => '', 1 => '/' . trailingslashit($item_base));
        ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th><label><input name="dzsap_permalink" type="radio" value="<?php echo $structures[0]; ?>"
                                  class="dzsaptog" <?php checked($structures[0], $dzsap_permalink); ?> /> <?php _e('Default'); ?>
                    </label></th>
                <td><code><?php echo home_url(); ?>/?audio=sample-item</code></td>
            </tr>
            <tr>
                <th><label><input name="dzsap_permalink" type="radio" value="<?php echo $structures[1]; ?>"
                                  class="dzsaptog" <?php checked($structures[1], $dzsap_permalink); ?> /> <?php _e('Product', 'dzsap'); ?>
                    </label></th>
                <td><code><?php echo home_url(); ?>/<?php echo $item_base; ?>/sample-item/</code></td>
            </tr>
            <tr>
                <th><label><input name="dzsap_permalink" id="dzsap_custom_selection" type="radio" value="custom"
                                  class="tog" <?php checked(in_array($dzsap_permalink, $structures), false); ?> />
                        <?php _e('Custom Base', 'dzsap'); ?></label></th>
                <td>
                    <input name="dzsap_permalink_structure" id="dzsap_permalink_structure" type="text"
                           value="<?php echo esc_attr($dzsap_permalink); ?>" class="regular-text code"> <span
                        class="description"><?php _e('Enter a custom base to use. A base <strong>must</strong> be set or WordPress will use default instead.', 'dzsap'); ?></span>
                </td>
            </tr>
            </tbody>
        </table>
        <script type="text/javascript">
            jQuery(function () {
                jQuery('input.dzsaptog').change(function () {
                    jQuery('#dzsap_permalink_structure').val(jQuery(this).val());
                });

                jQuery('#dzsap_permalink_structure').focus(function () {
                    jQuery('#dzsap_custom_selection').click();
                });
            });
        </script>
        <?php
    }





    function register_links() {

        global $dzsap;


        register_taxonomy('dzsap_category', 'dzsap_items', array('label' => __('Audio Categories', 'dzsap'), 'query_var' => true, 'show_ui' => true, 'hierarchical' => true, 'rewrite' => array('slug' => $dzsap->mainoptions['dzsap_categories_rewrite']),));


        register_taxonomy('dzsap_tags', 'dzsap_items', array('label' => __('Song tags', 'dzsap'), 'query_var' => true, 'show_ui' => true, 'hierarchical' => false, 'rewrite' => array('slug' => $dzsap->mainoptions['dzsap_tags_rewrite']),));


	    $labels = array(
		    'name'              => esc_html__( 'Audio galleries', 'dzsap' ),
		    'singular_name'     => esc_html__( 'Audio gallery', 'dzsap' ),
		    'search_items'      => esc_html__( 'Search galleries', 'dzsap' ),
		    'all_items'         => esc_html__( 'All galleries', 'dzsap' ),
		    'parent_item'       => esc_html__( 'Parent gallery', 'dzsap' ),
		    'parent_item_colon' => esc_html__( 'Parent gallery', 'dzsap' ),
		    'edit_item'         => esc_html__( 'Edit gallery', 'dzsap' ),
		    'update_item'       => esc_html__( 'Update gallery', 'dzsap' ),
		    'add_new_item'      => esc_html__( 'Add playlist', 'dzsap' ),
		    'new_item_name'     => esc_html__( 'New gallery name', 'dzsap' ),
		    'menu_name'         => esc_html__( 'Galleries', 'dzsap' ),

            
	    );



	    $cap_manage_terms = $this->taxname_sliders.'_manage_categories';

	    if(current_user_can('manage_options')){
		    $cap_manage_terms = 'manage_options';
        }

        register_taxonomy($this->taxname_sliders, 'dzsap_items', array(

                'label' => esc_html__('Audio Playlists', 'dzsap'),
                'labels' => $labels,
                'query_var' => true,
                'show_ui' => true,
                'hierarchical' => false,
                 'rewrite' => array('slug' => $this->mainoptions['dzsap_sliders_rewrite']),
                 'show_in_menu'=>false,
                'capabilities'=>array(
	                'manage_terms' => $cap_manage_terms,
'edit_terms' => $cap_manage_terms,
'delete_terms' => $cap_manage_terms,
'assign_terms' => $cap_manage_terms,
                ),
            ));


//        add_action( 'dzsap_sliders_add_tag_form_fields', 'add_feature_group_field', 10, 2 );
        add_action( 'category_edit_form_fields', array($this,'term_meta_fields'), 10, 10 );


//        add_action( 'dzsap_sliders_add_form_fields', 'add_feature_group_field', 10, 2 );
//        add_action( 'dzsap_sliders_edit_form_fields', 'add_feature_group_field', 10, 10
        add_action( 'edited_category', array($this,'save_taxonomy_custom_meta'), 10, 2 );

//        add_action( 'created_dzsap_sliders', 'save_feature_meta', 10, 2 );
//        add_action( 'edited_dzsap_sliders', 'save_feature_meta', 10, 2 );


        $labels = array('name' => 'Audio Items', 'singular_name' => 'Audio Item',);

        $permalinks = get_option('dzsap_permalinks');
        //print_r($permalinks);

        $item_slug_permalink = empty($permalinks['item_base']) ? _x('audio', 'slug', 'dzsap') : $permalinks['item_base'];


        $args = array('labels' => $labels, 'public' => true, 'has_archive' => true, 'hierarchical' => false, 'supports' => array('title', 'editor', 'author', 'thumbnail', 'post-thumbnail', 'comments', 'excerpt', 'custom-fields'), 'rewrite' => array('slug' => $item_slug_permalink), 'yarpp_support' => true, 'capabilities' => array(),//'taxonomies' => array('categoryportfolio'),
        );


        register_post_type('dzsap_items', $args);
    }


    function check_posts_init(){





	    if (isset($_GET['action']) && $_GET['action'] == 'ajax_dzsap_submit_contor_60_secs') {

		    $date = date('Y-m-d');

//                $date = date("Y-m-d", time() - 60 * 60 * 24);


		    $country = '0';
		    $id = $_POST['video_analytics_id'];

		    if ($this->mainoptions['analytics_enable_location'] == 'on') {

//                    print_r($_SERVER);

			    if ($_SERVER['REMOTE_ADDR']) {

//                        $aux = wp_file


				    $request = wp_remote_get('https://ipinfo.io/' . $_SERVER['REMOTE_ADDR'] . '/json');
				    $response = wp_remote_retrieve_body($request);
				    $aux_arr = json_decode($response);
//                        print_r($aux_arr);

				    if ($aux_arr) {
					    $country = $aux_arr->country;
				    }
			    }
		    }


		    $userid = '';
		    $userid = get_current_user_id();
		    if ($this->mainoptions['analytics_enable_user_track'] == 'on') {

			    if ( $_POST['curr_user'] ) {
				    $userid = $_POST['curr_user'];
			    }
		    }



		    $playerid = $id;

		    global $wpdb;
		    $table_name = $wpdb->prefix.'dzsap_activity';


		    $results = $GLOBALS['wpdb']->get_results( 'SELECT * FROM '.$table_name.' WHERE id_user = \''.$userid.'\' AND date=\''.$date.'\'  AND type=\''.'timewatched'.'\' AND id_video=\''.$playerid.'\'', OBJECT );


//			    print_rr($results);

		    if(is_array($results) && count($results)>0){


			    $val = intval($results[0]->val);
//				    echo '$val  - '.$val;
			    $newval = $val+60;

			    $wpdb->update(
				    $table_name,
				    array(
					    'val' => $val+60,
				    ),
				    array( 'ID' => $results[0]->id ),
				    array(
					    '%s',	// value1
				    ),
				    array( '%d' )
			    );

//				    echo '$newval  - '.$newval;

		    }else{
			    $currip = $this->misc_get_ip();


			    $wpdb->insert(
				    $table_name,
				    array(
					    'ip' => $currip,
					    'type' => 'timewatched',
					    'id_user' => $userid,
					    'id_video' => $playerid,
					    'date' => $date,
					    'val' => 60,
					    'country' => $country,
				    )
			    );
		    }






		    // -- global table

		    $query = 'SELECT * FROM '.$table_name.' WHERE id_user = \'0\' AND date=\''.$date.'\'  AND type=\''.'timewatched'.'\' AND id_video=\''.(0).'\'';
		    if($this->mainoptions['analytics_enable_location']=='on' && $country){
			    $query.=' AND country=\''.$country.'\'';
		    }
		    $results = $GLOBALS['wpdb']->get_results($query , OBJECT );


		    if(is_array($results) && count($results)>0){


			    $val = intval($results[0]->val);
			    $newval = $val+60;

			    $wpdb->update(
				    $table_name,
				    array(
					    'val' => $val+60,
				    ),
				    array( 'ID' => $results[0]->id ),
				    array(
					    '%s',	// value1
				    ),
				    array( '%d' )
			    );


		    }else{

			    $wpdb->insert(
				    $table_name,
				    array(
					    'ip' => 0,
					    'type' => 'timewatched',
					    'id_user' => 0,
					    'id_video' => 0,
					    'date' => $date,
					    'country' => $country,
					    'val' => 60,
				    )
			    );
		    }



		    die();


	    }

    }



    function term_meta_fields($term){
        // this will add the custom meta field to the add new term page

        $t_id = $term->term_id;

        // retrieve the existing value(s) for this meta field. This returns an array
        $term_meta = get_option("taxonomy_$t_id");

        $tem = array(
                'name'=>'feed_xml',
                'no_preview'=>'default',
                'title'=>__('XML Feed'),
        );

        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label
                        for="term_meta[<?php echo $tem['name']; ?>]"><?php echo $tem['title']; ?></label></th>
            <td class="<?php
            if(isset($tem['type']) && $tem['type']=='media-upload'){
                echo 'setting-upload';
            }
            ?>">





                <?php



                if(isset($tem['type']) && $tem['type']=='media-upload'){
                    if($tem['no_preview']!='on'){
                        echo '<span class="uploader-preview"></span>';
                    }

                }
                ?>



                <?php
                $lab = 'term_meta['.$tem['name'].']';

                $val = '';

                if(isset($term_meta[$tem['name']])){

                    $val = esc_attr($term_meta[$tem['name']]) ? esc_attr($term_meta[$tem['name']]) : '';
                    $val = stripslashes($val);
                }

                $class = 'setting-field medium';


                if(isset($tem['type']) && $tem['type']=='media-upload') {
                    $class.=' uploader-target';
                }

//                echo DZSHelpers::generate_input_text($lab, array(
//                    'class'=>$class,
//                    'seekval'=>$val,
//                    'id'=>$lab,
//                ));

                echo DZSHelpers::generate_input_textarea($lab, array(
                    'class'=>$class,
                    'seekval'=>$val,
                    'extraattr'=>' style="width: 100%; " rows="5"',
                    'id'=>$lab,
                ));


                ?>
                <?php

                ?>
                <p class="description"><?php _e('Enter a value for this field', 'pippin'); ?></p>
            </td>
        </tr>
        <?php
    }




    function save_taxonomy_custom_meta( $term_id ) {
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id" );
            $cat_keys = array_keys( $_POST['term_meta'] );
            foreach ( $cat_keys as $key ) {
                if ( isset ( $_POST['term_meta'][$key] ) ) {
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }
            // Save the option array.
            update_option( "taxonomy_$t_id", $term_meta );
        }
    }

    function handle_admin_menu() {

        if ($this->pluginmode == 'theme') {
            $dzsap_page = add_theme_page(__('DZS ZoomSounds', 'dzsap'), __('DZS ZoomSounds', 'dzsap'), $this->admin_capability, $this->adminpagename, array($this, 'admin_page'));
        } else {
            //$dzsap_page = add_options_page(__('DZS ZoomSounds', 'dzsap'), __('DZS ZoomSounds', 'dzsap'), $this->admin_capability, $this->adminpagename, array($this, 'admin_page'));




            $capability = 'dzsap_manage_options';

            if(current_user_can('manage_options')){
	            $capability = 'manage_options';
            }




            $dzsap_page = add_menu_page(__('ZoomSounds', 'dzsap'), __('ZoomSounds', 'dzsap'), $capability, $this->adminpagename, array($this, 'admin_page'), 'div');


	        $capability = 'dzsap_manage_vpconfigs';

	        if(current_user_can('manage_options')){
		        $capability = 'manage_options';
	        }


	        $dzsap_subpage = add_submenu_page($this->adminpagename, 'ZoomSounds '.__('Player Configs', 'dzsap'), __('Player Configs', 'dzsap'), $capability, $this->adminpagename_configs, array($this, 'admin_page_vpc'));




	        $capability = 'dzsap_manage_options';

	        if(current_user_can('manage_options')){
		        $capability = 'manage_options';
	        }
            $dzsap_subpage = add_submenu_page($this->adminpagename, __('ZoomSounds Settings', 'dzsap'), __('Settings', 'dzsap'), $capability, $this->page_mainoptions_link, array($this, 'admin_page_mainoptions'));



	        $capability = 'manage_options';


            $dzsap_subpage = add_submenu_page($this->adminpagename, __('Autoupdater', 'dzsap'), __('Autoupdater', 'dzsap'), $this->admin_capability, $this->adminpagename_autoupdater, array($this, 'admin_page_autoupdater'));


            $capability='delete_posts';
	        if(current_user_can('manage_options')){
		        $capability = 'manage_options';
	        }
            $dzsap_subpage = add_submenu_page($this->adminpagename, __('About ZoomSounds', 'dzsap'), __('About', 'dzsap'), $capability, $this->adminpagename_about, array($this, 'admin_page_about'));
        }



        //echo $dzsap_page;
    }

    function admin_page_about() {

        include_once('class_parts/admin-page-about.php');


        wp_enqueue_style('dzstabsandaccordions', $this->thepath . 'libs/dzstabsandaccordions/dzstabsandaccordions.css');
        wp_enqueue_script('dzstabsandaccordions', $this->thepath . "libs/dzstabsandaccordions/dzstabsandaccordions.js");
	    $url = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';

	    if($this->mainoptions['fontawesome_load_local']=='on'){
		    $url = $this->base_url.'libs/fontawesome/font-awesome.min.css';
	    }


	    wp_enqueue_style('fontawesome',$url);

    }


    function admin_page_autoupdater() {

        ?>
        <div class="wrap">


            <?php

            $auxarray = array();


            if (isset($_GET['dzsap_purchase_remove_binded']) && $_GET['dzsap_purchase_remove_binded'] == 'on') {

                $this->mainoptions['dzsap_purchase_code_binded'] = 'off';

                update_option($this->dbname_options, $this->mainoptions);

            }

            if (isset($_POST['action']) && $_POST['action'] === 'dzsap_update_request') {


                if (isset($_POST['dzsap_purchase_code'])) {
                    $auxarray = array('dzsap_purchase_code' => $_POST['dzsap_purchase_code']);
                    $auxarray = array_merge($this->mainoptions, $auxarray);

                    $this->mainoptions = $auxarray;


                    update_option($this->dbname_options, $auxarray);
                }


            }

            $extra_class = '';
            $extra_attr = '';
            $form_method = "POST";
            $form_action = "";
            $disable_button = '';

            $lab = 'dzsap_purchase_code';

            if ($this->mainoptions['dzsap_purchase_code_binded'] == 'on') {
                $extra_attr = ' disabled';
                $disable_button = ' <input type="hidden" name="purchase_code" value="' . $this->mainoptions[$lab] . '"/><input type="hidden" name="site_url" value="' . site_url() . '"/><input type="hidden" name="redirect_url" value="' . esc_url(add_query_arg('dzsap_purchase_remove_binded', 'on', dzs_curr_url())) . '"/><button class="button-secondary" name="action" value="dzsap_purchase_code_disable">' . __("Disable Key") . '</button>';
                $form_action = ' action="https://zoomthe.me/updater_dzsap/servezip.php"';
            }


            echo '<form' . $form_action . ' class="mainsettings" method="' . $form_method . '">';

            echo '
                <div class="setting">
                    <div class="label">' . __('Purchase Code', 'dzsap') . '</div>
                    ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab], 'class' => $extra_class, 'extra_attr' => $extra_attr)) . $disable_button . '
                    <div class="sidenote">' . __('You can <a href="https://lh5.googleusercontent.com/-o4WL83UU4RY/Unpayq3yUvI/AAAAAAAAJ_w/HJmso_FFLNQ/w786-h1179-no/puchase.jpg" target="“_blank”">find it here</a> ', 'dzsap') . '</div>
                </div>';


            if ($this->mainoptions['dzsap_purchase_code_binded'] == 'on') {
                echo '</form><form class="mainsettings" method="post">';
            }

            echo '<p><button class="button-primary" name="action" value="dzsap_update_request">' . __("Update") . '</button></p>';



            ?>
            </form>
        </div>
        <?php





        if (isset($_POST['action']) && $_POST['action'] === 'dzsap_update_request') {


            //            echo 'ceva';


            //            die();


            $aux = 'https://zoomthe.me/updater_dzsap/servezip.php?purchase_code=' . $this->mainoptions['dzsap_purchase_code'] . '&site_url=' . site_url();
            $res = DZSHelpers::get_contents($aux);

            //            echo 'hmm'; echo strpos($res,'<div class="error">'); echo 'dada'; echo $res;
            if ($res === false) {
                echo 'server offline';
            } else {
                if (strpos($res, '<div class="error">') === 0) {
                    echo $res;


                    if (strpos($res, '<div class="error">error: in progress') === 0) {

                        $this->mainoptions['dzsap_purchase_code_binded'] = 'on';
                        update_option($this->dbname_options, $this->mainoptions);
                    }
                } else {

                    file_put_contents(dirname(__FILE__) . '/update.zip', $res);
                    if (class_exists('ZipArchive')) {
                        $zip = new ZipArchive;
                        $res = $zip->open(dirname(__FILE__) . '/update.zip');
                        //test
                        if ($res === TRUE) {
                            //                echo 'ok';
                            $zip->extractTo(dirname(__FILE__));
                            $zip->close();


                            $this->mainoptions['dzsap_purchase_code_binded'] = 'on';
                            update_option($this->dbname_options, $this->mainoptions);


                        } else {
                            echo 'failed, code:' . $res;
                        }
                        echo __('Update done.');
                    } else {

                        echo __('ZipArchive class not found.');
                    }

                }
            }
        }

    }
    function enqueue_fontawesome() {

	    $url = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';

	    if($this->mainoptions['fontawesome_load_local']=='on'){
		    $url = $this->base_url.'libs/fontawesome/font-awesome.min.css';
	    }


	    wp_enqueue_style('fontawesome',$url);
    }
    function admin_scripts() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('tiny_mce');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('dzsap_admin', $this->base_url . "admin/admin.js");
        wp_enqueue_style('dzsap_admin', $this->base_url . 'admin/admin.css');
        wp_enqueue_script('dzs.farbtastic', $this->base_url . "libs/farbtastic/farbtastic.js");
        wp_enqueue_style('dzs.farbtastic', $this->base_url . 'libs/farbtastic/farbtastic.css');

        wp_enqueue_style('dzs.scroller', $this->base_url . 'dzsscroller/scroller.css');
        wp_enqueue_script('dzs.scroller', $this->base_url . 'dzsscroller/scroller.js');
        wp_enqueue_style('dzs.dzstoggle', $this->base_url . 'dzstoggle/dzstoggle.css');
        wp_enqueue_script('dzs.dzstoggle', $this->base_url . 'dzstoggle/dzstoggle.js');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');


        if(isset($_GET['from']) && $_GET['from']=='shortcodegenerator'){

            wp_enqueue_style('dzs.remove_wp_bar', $this->base_url . 'tinymce/remove_wp_bar.css');

        }
    }

    function front_scripts() {
        wp_enqueue_script('dzsap', $this->base_url . "audioplayer/audioplayer.js");
        wp_enqueue_style('dzsap', $this->base_url . 'audioplayer/audioplayer.css');
//        wp_enqueue_style('dzs.tooltip', $this->base_url . 'dzstooltip/dzstooltip.css');


    }

    function add_simple_field($pname, $otherargs = array()) {
        global $data;
        $fout = '';
        $val = '';

        $args = array(
            'val' => ''
        );
        $args = array_merge($args, $otherargs);

        $val = $args['val'];

        //====check if the data from database txt corresponds
        if (isset($data[$pname])) {
            $val = $data[$pname];
        }
        $fout.='<div class="setting"><input type="text" class="textinput short" name="' . $pname . '" value="' . $val . '"></div>';
        echo $fout;
    }

    function add_cb_field($pname) {
        global $data;
        $fout = '';
        $val = '';
        if (isset($data[$pname]))
            $val = $data[$pname];
        $checked = '';
        if ($val == 'on')
            $checked = ' checked';

        $fout.='<div class="setting"><input type="checkbox" class="textinput" name="' . $pname . '" value="on" ' . $checked . '/> on</div>';
        echo $fout;
    }

    function add_cp_field($pname, $otherargs = array()) {
        global $data;
        $fout = '';
        $val = '';


        $args = array(
            'val' => ''
        );

        $args = array_merge($args, $otherargs);



        //print_r($args);
        $val = $args['val'];

        //====check if the data from database txt corresponds
        if (isset($data[$pname])) {
            $val = $data[$pname];
        }

        $fout.='
<div class="setting"><input type="text" class="textinput short with-colorpicker" name="' . $pname . '" value="' . $val . '">
<div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
</div>';
        echo $fout;
    }

    function misc_input_text($argname, $pargs = array()) {
        $fout = '';

        $margs = array('type' => 'text', 'class' => '', 'seekval' => '', 'extra_attr' => '',);


        $margs = array_merge($margs, $pargs);

        $type = 'text';
        if (isset($margs['type'])) {
            $type = $margs['type'];
        }
        $fout .= '<input type="' . $type . '"';
        if (isset($margs['class'])) {
            $fout .= ' class="' . $margs['class'] . '"';
        }
        $fout .= ' name="' . $argname . '"';
        if (isset($margs['seekval'])) {
            $fout .= ' value="' . $margs['seekval'] . '"';
        }

        $fout .= $margs['extra_attr'];

        $fout .= '/>';
        return $fout;
    }

    function misc_input_textarea($argname, $otherargs = array()) {
        $fout = '';
        $fout.='<textarea';
        $fout.=' name="' . $argname . '"';

        $margs = array(
            'class' => '',
            'val' => '', // === default value
            'seekval' => '', // ===the value to be seeked
            'type' => '',
        );
        $margs = array_merge($margs, $otherargs);



        if ($margs['class'] != '') {
            $fout.=' class="' . $margs['class'] . '"';
        }
        $fout.='>';
        if (isset($margs['seekval']) && $margs['seekval'] != '') {
            $fout.='' . $margs['seekval'] . '';
        } else {
            $fout.='' . $margs['val'] . '';
        }
        $fout.='</textarea>';

        return $fout;
    }

    function misc_generate_upload_btn($pargs = array()) {

        $margs = array(
            'label' => 'Upload'
        );

        if ($pargs == '' || is_array($pargs) == false) {
            $pargs = array();
        }

        $margs = array_merge($margs, $pargs);

        $uploadbtnstring = '<button class="button-secondary action upload_file ">' . $margs['label'] . '</button>';



        if ($this->mainoptions['usewordpressuploader'] != 'on') {
            $uploadbtnstring = '<div class="dzs-upload">
<form name="upload" action="#" method="POST" enctype="multipart/form-data">
    	<input type="button" value="' . $margs['label'] . '" class="btn_upl"/>
        <input type="file" name="file_field" class="file_field"/>
        <input type="submit" class="btn_submit"/>
</form>
</div>
<div class="feedback"></div>';
        }

        return $uploadbtnstring;
    }

    function misc_input_checkbox($argname, $argopts) {
        $fout = '';
        $auxtype = 'checkbox';

        if (isset($argopts['type'])) {
            if ($argopts['type'] == 'radio') {
                $auxtype = 'radio';
            }
        }
        $fout.='<input type="' . $auxtype . '"';
        $fout.=' name="' . $argname . '"';
        if (isset($argopts['class'])) {
            $fout.=' class="' . $argopts['class'] . '"';
        }
        $theval = 'on';
        if (isset($argopts['val'])) {
            $fout.=' value="' . $argopts['val'] . '"';
            $theval = $argopts['val'];
        } else {
            $fout.=' value="on"';
        }
        //print_r($this->mainoptions); print_r($argopts['seekval']);
        if (isset($argopts['seekval'])) {
            $auxsw = false;
            if (is_array($argopts['seekval'])) {
                //echo 'ceva'; print_r($argopts['seekval']);
                foreach ($argopts['seekval'] as $opt) {
                    //echo 'ceva'; echo $opt; echo
                    if ($opt == $argopts['val']) {
                        $auxsw = true;
                    }
                }
            } else {
                //echo $argopts['seekval']; echo $theval;
                if ($argopts['seekval'] == $theval) {
                    //echo $argval;
                    $auxsw = true;
                }
            }
            if ($auxsw == true) {
                $fout.=' checked="checked"';
            }
        }
        $fout.='/>';
        return $fout;
    }

    function admin_page_mainoptions() {
        //print_r($this->mainoptions);
        if (isset($_POST['dzsap_delete_plugin_data']) && $_POST['dzsap_delete_plugin_data'] == 'on') {





            // -- delete plugin data

            if($this->dbs && is_array($this->dbs) && count($this->dbs)){

                foreach ($this->dbs as $db){

                    $aux = $this->dbname_mainitems;
                    $aux.='-' . $db;

                    delete_option($this->$aux);
                }
            }

            delete_option($this->dbname_dbs);

            delete_option($this->dbname_mainitems);
            delete_option($this->dbname_mainitems_configs);
            delete_option($this->dbname_options);
        }





	    if(isset($_GET['dzsap_shortcode_builder']) && $_GET['dzsap_shortcode_builder']=='on'){
		    dzsap_shortcode_builder();
	    }elseif (isset($_GET['dzsap_shortcode_player_builder']) && $_GET['dzsap_shortcode_player_builder']=='on'){


		    dzsap_shortcode_player_builder();
	    }elseif (isset($_GET['dzsap_preview_player']) && $_GET['dzsap_preview_player']=='on'){

		    include_once "class_parts/admin-preview-player.php";


		    dzsap_preview_player();
	    }else{

		    include_once "class_parts/admin-page-mainoptions.php";
	    }
            //print_r($this->mainoptions);
            ?>


            <div class="clear"></div><br/>
            <?php
        }


    function admin_page() {
        ?>
        <div class="wrap">
            <div class="import-export-db-con">
                <div class="the-toggle"></div>
                <div class="the-content-mask" style="">

                    <div class="the-content">
                        <h2><?php echo __("Whole Database"); ?></h2>
                        <form enctype="multipart/form-data" action="" method="POST">

                            <div class="">
                                <h3><?php echo __("Import Whole Database"); ?></h3>
                                <input name="dzsap_importdbupload" type="file" size="10"/><br />
                            </div>
                            <div class="">
                                <input class="button-secondary" type="submit" name="dzsap_importdb" value="Import" />
                            </div>
                            <div class="clear"></div>
                        </form>


                        <div class="">
                            <h3><?php echo __("Export Whole Database"); ?></h3>
                        </div>
                        <div class="">
                            <form action="" method="POST"><input class="button-secondary" type="submit" name="dzsap_exportdb" value="Export"/></form>
                        </div>
                        <br>
                        <br>
                        <h1><?php echo __("OR"); ?></h1>
                        <br>
                        <br>


                        <h2><?php echo __("Single Slider"); ?></h2>



                        <form enctype="multipart/form-data" action="" method="POST">
                            <div class="">
                                <h3><?php echo __("Import a Single Slider");?></h3>
                                <input name="importsliderupload" type="file" size="10"/><br />
                            </div>
                            <div class="">
                                <input class="button-secondary" type="submit" name="dzsap_importslider" value="Import" />
                            </div>
                            <div class="clear"></div>
                        </form>

                    </div>
                </div>
            </div>
            <h2>DZS <?php _e('ZoomSounds Admin', 'dzsap'); ?>&nbsp; <span style="font-size:13px; font-weight: 100;">version <?php echo DZSAP_VERSION; ?></span> <img alt="" style="visibility: visible;" id="main-ajax-loading" src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/></h2>
            <noscript><?php _e('You need javascript for this.', 'dzsap'); ?></noscript>
            <div class="top-buttons">
                <a href="<?php echo $this->base_url; ?>readme/index.html" class="button-secondary action"><?php _e('Documentation', 'dzsap'); ?></a>
                <div class="super-select db-select dzsap"><button class="button-secondary btn-show-dbs">Current Database - <span class="strong currdb"><?php
                            if ($this->currDb == '') {
                                echo 'main';
                            } else {
                                echo $this->currDb;
                            }
                            ?></span></button>
                    <select class="main-select hidden"><?php
                        //print_r($this->dbs);

                        if (is_array($this->dbs)) {
                            foreach ($this->dbs as $adb) {
                                $params = array('dbname' => $adb);
                                $newurl = add_query_arg($params, dzs_curr_url());
                                echo '<option' . ' data-newurl="' . $newurl . '"' . '>' . $adb . '</option>';
                            }
                        } else {
                            $params = array('dbname' => 'main');
                            $newurl = add_query_arg($params, dzs_curr_url());
                            echo '<option' . ' data-newurl="' . $newurl . '"' . ' selected="selected"' . '>' . $adb . '</option>';
                        }
                        ?></select><div class="hidden replaceurlhelper"><?php
                        $params = array('dbname' => 'replaceurlhere');
                        $newurl = add_query_arg($params, dzs_curr_url());
                        echo $newurl;
                        ?></div>
                </div>
            </div>
            <table cellspacing="0" class="wp-list-table widefat dzs_admin_table main_sliders">
                <thead>
                <tr>
                    <th style="" class="manage-column column-name" id="name" scope="col"><?php _e('ID', 'dzsap'); ?></th>
                    <th class="column-edit">Edit</th>
                    <th class="column-edit">Embed</th>
                    <th class="column-edit">Export</th>
                    <th class="column-edit">Duplicate</th>
                    <?php
                    if ($this->mainoptions['is_safebinding'] != 'on') {
                        ?>
                        <?php
                    }
                    ?>
                    <th class="column-edit">Delete</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <?php
            $url_add = '';
            $url_add = '';
            $items = $this->mainitems;
            //echo count($items);

            $aux = remove_query_arg('deleteslider', admin_url('admin.php?page='.$this->adminpagename.'&adder=adder'));

            $nextslidernr = count($items);
            if ($nextslidernr < 1) {
                //$nextslidernr = 1;
            }
            $params = array('currslider' => $nextslidernr);
            $url_add = add_query_arg($params, $aux);
            ?>
            <a class="button-secondary add-slider" href="<?php echo $url_add; ?>"><?php _e('Add Playlist', 'dzsap'); ?></a>
            <form class="master-settings">
            </form>
            <div class="saveconfirmer"><?php _e('Loading...', 'dzsap'); ?></div>
            <a href="#" class="button-primary master-save"></a> <img alt="" style="position:fixed; bottom:18px; right:125px; visibility: hidden;" id="save-ajax-loading" src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>

            <a href="#" class="button-primary master-save"><?php _e('Save All Galleries', 'dzsap'); ?></a>
            <a href="#" class="button-primary slider-save"><?php _e('Save Gallery', 'dzsap'); ?></a>
        </div>
        <script>
            <?php
            //$jsnewline = '\\' + "\n";
            $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->sliderstructure);
            $aux = str_replace(array("'"), '&quot;', $aux);
            echo "var sliderstructure = '" . $aux . "';
    ";
            $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->itemstructure);
            $aux = str_replace(array("'"), '&quot;', $aux);
            echo "var itemstructure = '" . $aux . "';
    ";
            $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->videoplayerconfig);
            $aux = str_replace(array("'"), '&quot;', $aux);
            echo "var videoplayerconfig = '" . $aux . "';
    ";
            ?>
            jQuery(document).ready(function($) {
                sliders_ready($);
                if (jQuery.fn.multiUploader) {
                    jQuery('.dzs-multi-upload').multiUploader();
                }
                <?php
                $items = $this->mainitems;
                for ($i = 0; $i < count($items); $i++) {
                    //print_r($items[$i]);
                    $aux = '';
                    if (isset($items[$i]) && isset($items[$i]['settings']) && isset($items[$i]['settings']['id'])) {
                        //echo $items[$i]['settings']['id'];

	                    $items[$i]['settings']['id'] = str_replace('"','',$items[$i]['settings']['id']);
                        $aux = '{ name: "' . $items[$i]['settings']['id'] . '"}';
                    }

                    echo "sliders_addslider(" . $aux . ");";
                }
                if (count($items) > 0)
                    echo 'sliders_showslider(0);';
                for ($i = 0; $i < count($items); $i++) {
                    //echo $i . $this->currSlider . 'cevava';
                    if (($this->mainoptions['is_safebinding'] != 'on' || $i == $this->currSlider) && is_array($items[$i])) {

                        //==== jsi is the javascript I, if safebinding is on then the jsi is always 0 ( only one gallery )
                        $jsi = $i;
                        if ($this->mainoptions['is_safebinding'] == 'on') {
                            $jsi = 0;
                        }

                        for ($j = 0; $j < count($items[$i]) - 1; $j++) {
                            echo "sliders_additem(" . $jsi . ");";
                        }

                        foreach ($items[$i] as $label => $value) {
                            if ($label === 'settings') {
                                if (is_array($items[$i][$label])) {
                                    foreach ($items[$i][$label] as $sublabel => $subvalue) {
                                        $subvalue = (string) $subvalue;
                                        $subvalue = stripslashes($subvalue);
                                        $subvalue = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $subvalue);
                                        $subvalue = str_replace(array("'"), '"', $subvalue);
                                        $subvalue = str_replace(array("</script>"), '{{scriptend}}', $subvalue);
                                        echo 'sliders_change(' . $jsi . ', "settings", "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
                                    }
                                }
                            } else {

                                if (is_array($items[$i][$label])) {
                                    foreach ($items[$i][$label] as $sublabel => $subvalue) {
                                        $subvalue = (string) $subvalue;
                                        $subvalue = stripslashes($subvalue);
                                        $subvalue = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $subvalue);
                                        $subvalue = str_replace(array("'"), '"', $subvalue);
                                        $subvalue = str_replace(array("</script>"), '{{scriptend}}', $subvalue);
                                        if ($label == '') {
                                            $label = '0';
                                        }
                                        echo 'sliders_change(' . $jsi . ', ' . $label . ', "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
                                    }
                                }
                            }
                        }
                        if ($this->mainoptions['is_safebinding'] == 'on') {
                            break;
                        }
                    }
                }
                ?>
                jQuery('#main-ajax-loading').css('visibility', 'hidden');
                if (dzsap_settings.is_safebinding == "on") {
                    jQuery('.master-save').remove();
                    if (dzsap_settings.addslider == "on") {
                        sliders_addslider();
                        window.currSlider_nr = -1
                        sliders_showslider(0);
                    }
                    jQuery('.slider-in-table').each(function() {
//                        jQuery(this).children('.button_view').eq(3).remove();
                    });
                }
                check_global_items();
                sliders_allready();
            });
        </script>
        <?php
    }

    function admin_page_vpc() {
        ?>
        <div class="wrap">
            <div class="import-export-db-con">
                <div class="the-toggle"></div>
                <div class="the-content-mask" style="">

                    <div class="the-content">



                        <form enctype="multipart/form-data" action="" method="POST">
                            <div class="one_half">
                                <h3>Import Slider</h3>
                                <input name="importsliderupload" type="file" size="10"/><br />
                            </div>
                            <div class="one_half last alignright">
                                <input class="button-secondary" type="submit" name="dzsap_import_config" value="Import" />
                            </div>
                            <div class="clear"></div>
                        </form>


                        <div class="clear"></div>

                    </div>
                </div>
            </div>
            <h2>DZS <?php _e('ZoomSounds Admin', 'dzsap'); ?> <img alt="" style="visibility: visible;" id="main-ajax-loading" src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/></h2>
            <noscript><?php _e('You need javascript for this.', 'dzsap'); ?></noscript>
            <div class="top-buttons">
                <a href="<?php echo $this->base_url; ?>readme/index.html" class="button-secondary action"><?php _e('Documentation', 'dzsap'); ?></a>

            </div>
            <table cellspacing="0" class="wp-list-table widefat dzs_admin_table main_sliders">
                <thead>
                <tr>
                    <th style="" class="manage-column column-name" id="name" scope="col"><?php _e('ID', 'dzsap'); ?></th>
                    <th class="column-edit">Edit</th>
                    <th class="column-edit">Embed</th>
                    <th class="column-edit"><?php echo esc_html__("Export", 'dzsap'); ?></th>
                    <th class="column-edit"><?php echo esc_html__("Duplicate", 'dzsap'); ?></th>

                    <th class="column-edit">Delete</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <?php
            $url_add = '';
            $url_add = '';
            $items = $this->mainitems_configs;
            //echo count($items);
            //print_r($items);

            $aux = remove_query_arg('deleteslider', dzs_curr_url());
            $aux = admin_url('admin.php?page='.$this->adminpagename_configs.'&adder=adder');
            $params = array('currslider' => count($items));
            $url_add = add_query_arg($params, $aux);

            $id = ($items[$this->currSlider]['settings']['id']);
            ?>
            <a class="button-secondary add-slider" href="<?php echo $url_add; ?>"><?php _e('Add Configuration', 'dzsap'); ?></a>
            <form class="master-settings only-settings-con mode_vpconfigs">
            </form>
            <div class="saveconfirmer"><?php _e('Loading...', 'dzsap'); ?></div>
            <a href="#" class="button-primary master-save-vpc"></a> <img alt="" style="position:fixed; bottom:18px; right:125px; visibility: hidden;" id="save-ajax-loading" src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>

            <a href="#" class="button-primary master-save-vpc"><?php _e('Save All Configs', 'dzsap'); ?></a>
            <a href="#" class="button-primary slider-save-vpc"><?php _e('Save Config', 'dzsap'); ?></a>

            <div class="preview-player-iframe-con">

                <iframe class="preview-player-iframe" width="100%" height="300" src="<?php echo admin_url('admin.php?page=dzsap-mo&dzsap_preview_player=on&config='.urlencode($id).''); ?>"></iframe>
                <div class="button-secondary btn-refresh-preview"><i class="fa fa-refresh"></i> <?php echo esc_html__("Refresh preview", 'dzsap'); ?></div>
            </div>
        </div>
        <script>
            <?php
            //$jsnewline = '\\' + "\n";

            $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->sliderstructure);
            $aux = str_replace(array("'"), '&quot;', $aux);
            echo "var sliderstructure = '" . $aux . "';
    ";

            $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->videoplayerconfig);
            $aux = str_replace(array("'"), '&quot;', $aux);
            echo "var videoplayerconfig = '" . $aux . "';
    ";
            ?>
            jQuery(document).ready(function($) {
                sliders_ready($);
                if ($.fn.multiUploader) {
                    $('.dzs-multi-upload').multiUploader();
                }
                <?php
                $items = $this->mainitems_configs;
                for ($i = 0; $i < count($items); $i++) {
                    //print_r($items[$i]);

                    $aux = '';
                    if (isset($items[$i]) && isset($items[$i]['settings']) && isset($items[$i]['settings']['id'])) {
                        //echo $items[$i]['settings']['id'];
	                    if($items[$i]['settings']['id']=='temp123'){
		                    continue;
	                    }
	                    $items[$i]['settings']['id'] = str_replace('"','',$items[$i]['settings']['id']);
                        $aux = '{ name: "' . $items[$i]['settings']['id'] . '"}';
                    }
                    echo "sliders_addslider(" . $aux . ");";
                }
                if (count($items) > 0)
                    echo 'sliders_showslider(0);';
                for ($i = 0; $i < count($items); $i++) {
                    //echo $i . $this->currSlider . 'cevava';
                    if (($this->mainoptions['is_safebinding'] != 'on' || $i == $this->currSlider) && is_array($items[$i])) {

                        //==== jsi is the javascript I, if safebinding is on then the jsi is always 0 ( only one gallery )
                        $jsi = $i;
                        if ($this->mainoptions['is_safebinding'] == 'on') {
                            $jsi = 0;
                        }

                        for ($j = 0; $j < count($items[$i]) - 1; $j++) {
                            echo "sliders_additem(" . $jsi . ");";
                        }

                        foreach ($items[$i] as $label => $value) {
                            if ($label === 'settings') {
                                if (is_array($items[$i][$label])) {
                                    foreach ($items[$i][$label] as $sublabel => $subvalue) {
                                        $subvalue = (string) $subvalue;
                                        $subvalue = stripslashes($subvalue);
                                        $subvalue = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $subvalue);
                                        $subvalue = str_replace(array("'"), '"', $subvalue);
                                        $subvalue = str_replace(array("</script>"), '{{scriptend}}', $subvalue);
                                        echo 'sliders_change(' . $jsi . ', "settings", "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
                                    }
                                }
                            } else {

                                if (is_array($items[$i][$label])) {
                                    foreach ($items[$i][$label] as $sublabel => $subvalue) {
                                        $subvalue = (string) $subvalue;
                                        $subvalue = stripslashes($subvalue);
                                        $subvalue = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $subvalue);
                                        $subvalue = str_replace(array("'"), '"', $subvalue);
                                        $subvalue = str_replace(array("</script>"), '{{scriptend}}', $subvalue);
                                        if ($label == '') {
                                            $label = '0';
                                        }
                                        echo 'sliders_change(' . $jsi . ', ' . $label . ', "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
                                    }
                                }
                            }
                        }
                        if ($this->mainoptions['is_safebinding'] == 'on') {
                            break;
                        }
                    }
                }
                ?>
                jQuery('#main-ajax-loading').css('visibility', 'hidden');
                if (dzsap_settings.is_safebinding == "on") {
                    jQuery('.master-save-vpc').remove();
                    if (dzsap_settings.addslider == "on") {
                        //console.log(dzsap_settings.addslider)
                        sliders_addslider();
                        window.currSlider_nr = -1
                        sliders_showslider(0);
                    }
                    jQuery('.slider-in-table').each(function() {

                    });
                }
                check_global_items();
                sliders_allready();
            });
        </script>
        <?php
    }

    function post_options() {
        //// POST OPTIONS ///

        if (isset($_POST['dzsap_exportdb'])) {


            //===setting up the db
            $currDb = '';
            if (isset($_POST['currdb']) && $_POST['currdb'] != '') {
                $this->currDb = $_POST['currdb'];
                $currDb = $this->currDb;
            }

            //echo 'ceva'; print_r($this->dbs);
            if ($currDb != 'main' && $currDb != '') {
                $this->dbname_mainitems.='-' . $currDb;
                $this->mainitems = get_option($this->dbname_mainitems);
            }
            //===setting up the db END

            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . "dzsap_backup.txt" . '"');
            echo serialize($this->mainitems);
            die();
        }

        if (isset($_POST['dzsap_exportslider'])) {


            //===setting up the db
            $currDb = '';
            if (isset($_POST['currdb']) && $_POST['currdb'] != '') {
                $this->currDb = $_POST['currdb'];
                $currDb = $this->currDb;
            }


	        $this->db_read_mainitems();

            //echo 'ceva'; print_r($this->dbs);
            if ($currDb != 'main' && $currDb != '') {
                $this->dbname_mainitems.='-' . $currDb;
                $this->mainitems = get_option($this->dbname_mainitems);
            }
            //===setting up the db END
            //print_r($currDb);

            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . "dzsap-slider-" . $_POST['slidername'] . ".txt" . '"');
            //print_r($_POST);

            error_log("EXPORTING SLIDER ( currdb - ".$currDb." )". print_rr($this->mainitems, array('echo'=>false)));
            echo serialize($this->mainitems[$_POST['slidernr']]);
            die();
        }

        if (isset($_POST['dzsap_exportslider_config'])) {


            //===setting up the db
            $currDb = '';


            error_log('hmm');

	        $this->db_read_mainitems();

            //echo 'ceva'; print_r($this->dbs);

            //===setting up the db END
            //print_r($currDb);

            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . "dzsap-slider-" . $_POST['slidername'] . ".txt" . '"');
            //print_r($_POST);

            error_log("EXPORTING SLIDER CONFIG ( currdb - ".$currDb." )". print_rr($this->mainitems_configs, array('echo'=>false)));
            echo serialize($this->mainitems_configs[$_POST['slidernr']]);
            die();
        }


        if (isset($_POST['dzsap_importdb'])) {
            //print_r( $_FILES);
            $file_data = file_get_contents($_FILES['dzsap_importdbupload']['tmp_name']);
            $aux  = unserialize($file_data);

            if(is_array($aux)){

                $this->mainitems = array_merge($this->mainitems, $aux);
                update_option($this->dbname_mainitems, $this->mainitems);
            }
        }

        if (isset($_POST['dzsap_importslider'])) {
            //print_r( $_FILES);
            $file_data = file_get_contents($_FILES['importsliderupload']['tmp_name']);
            $auxslider = unserialize($file_data);
            //replace_in_matrix('http://localhost/wpmu/eos/wp-content/themes/eos/', THEME_URL, $this->mainitems);
            //replace_in_matrix('http://eos.digitalzoomstudio.net/wp-content/themes/eos/', THEME_URL, $this->mainitems);
            //echo 'ceva';
            //print_r($auxslider);
            $this->mainitems = get_option($this->dbname_mainitems);
            //print_r($this->mainitems);
            $this->mainitems[] = $auxslider;

            update_option($this->dbname_mainitems, $this->mainitems);
        }

        if (isset($_POST['dzsap_import_config'])) {
            //print_r( $_FILES);
            $file_data = file_get_contents($_FILES['importsliderupload']['tmp_name']);
            $auxslider = unserialize($file_data);
            //replace_in_matrix('http://localhost/wpmu/eos/wp-content/themes/eos/', THEME_URL, $this->mainitems);
            //replace_in_matrix('http://eos.digitalzoomstudio.net/wp-content/themes/eos/', THEME_URL, $this->mainitems);
            //echo 'ceva';
            //print_r($auxslider);
            $this->mainitems_configs = get_option($this->dbname_mainitems_configs);
            //print_r($this->mainitems);
            $this->mainitems_configs[] = $auxslider;

            update_option($this->dbname_mainitems_configs, $this->mainitems_configs);
        }

        if (isset($_POST['dzsap_saveoptions'])) {
            $this->mainoptions['usewordpressuploader'] = $_POST['usewordpressuploader'];
            $this->mainoptions['embed_prettyphoto'] = $_POST['embed_prettyphoto'];
            $this->mainoptions['use_external_uploaddir'] = $_POST['use_external_uploaddir'];
            $this->mainoptions['disable_prettyphoto'] = $_POST['disable_prettyphoto'];


            update_option($this->dbname_options, $this->mainoptions);
        }
    }



    function misc_get_ip() {

        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }

        $ip = filter_var($ip, FILTER_VALIDATE_IP);
        $ip = ($ip === false) ? '0.0.0.0' : $ip;


        return $ip;
    }



    function create_user($user_name,$user_email){
	    $user_id = 0;
	    $user_id = username_exists( $user_name );
	    if ( !$user_id and email_exists($user_email) == false ) {
		    $random_password = 'test';
		    $user_id = wp_create_user( $user_name, $random_password, $user_email );
		    update_option('dzsapp_portal_user',$user_id);
	    } else {
		    $random_password = __('User already exists.  Password inherited.');
	    }

	    return $user_id;
    }
    function post_save_mo() {
        $auxarray = array();
        //parsing post data
        parse_str($_POST['postdata'], $auxarray);
//        print_r($auxarray);

        $auxarray_before = array(
            'use_external_uploaddir' => 'off'
        );


        $auxarray = array_merge($auxarray_before, $auxarray);

        if (isset($auxarray['dzsaap_enable_unregistered_submit']) && $auxarray['dzsaap_enable_unregistered_submit'] == 'on') {


            $user_name='portal_user';
	        $user_email='portal_user@gmail.com';
	        $user_id = $this->create_user($user_name,$user_email);

	        error_log('dzsapp_portal_user - '.$user_id);


        }
        if ($auxarray['use_external_uploaddir'] == 'on') {

            $path_uploaddir = dirname(dirname(dirname(__FILE__))) . '/upload';
            if (is_dir($path_uploaddir) === false) {
                mkdir($path_uploaddir, 0755);
            }
            $path_uploaddir_waves = dirname(dirname(dirname(__FILE__))) . '/upload/waves';
            if (is_dir($path_uploaddir_waves) === false) {
                mkdir($path_uploaddir_waves, 0755);
            }
        }


	    if (isset($auxarray['track_downloads']) && $auxarray['track_downloads'] == 'on' || isset($auxarray['analytics_enable']) && $auxarray['analytics_enable'] == 'on') {
//            echo 'hmmdadadadada';


		    $this->create_activity_table();

		    $auxarray['wpdb_enable']='on';

	    }

	    if(isset($auxarray['analytics_enable']) && $auxarray['analytics_enable'] == 'off'){

		    $auxarray['wpdb_enable']='off';
        }

//        error_log('auxarray - '. print_rr($auxarray,true));

        update_option($this->dbname_options, $auxarray);
        die();
    }

	function create_activity_table(){

		global $wpdb;




		$auxarray['wpdb_enable'] = 'on';

		$table_name = $wpdb->prefix . 'dzsap_activity';
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			//table not in database. Create new table
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          type varchar(100) NOT NULL,
          id_user int(10) NOT NULL,
          ip varchar(255) NOT NULL,
          id_video varchar(255) NOT NULL,
          date datetime NOT NULL,
          UNIQUE KEY id (id)
     ) $charset_collate;";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

		} else {
		}

		update_option('dzsap_table_activity_created','on');
	}
    function post_save() {
        //---this is the main save function which saves item
        $auxarray = array();
        $mainarray = array();

        //print_r($this->mainitems);
        //parsing post data
        parse_str($_POST['postdata'], $auxarray);


        if (isset($_POST['currdb'])) {
            $this->currDb = $_POST['currdb'];
        }
        //echo 'ceva'; print_r($this->dbs);
        if ($this->currDb != 'main' && $this->currDb != '') {
            $this->dbname_mainitems.='-' . $this->currDb;
        }
        //echo $this->dbname_mainitems;
        if (isset($_POST['sliderid'])) {
            //print_r($auxarray);
            $mainarray = get_option($this->dbname_mainitems);
            foreach ($auxarray as $label => $value) {
                $aux = explode('-', $label);
                $tempmainarray[$aux[1]][$aux[2]] = $auxarray[$label];
            }
            $mainarray[$_POST['sliderid']] = $tempmainarray;
        } else {
            foreach ($auxarray as $label => $value) {
                //echo $auxarray[$label];
                $aux = explode('-', $label);
                $mainarray[$aux[0]][$aux[1]][$aux[2]] = $auxarray[$label];
            }
        }
        echo $this->dbname_mainitems;
//        print_r($_POST);
//        print_r($this->currDb);
        echo isset($_POST['currdb']);
//        print_r($mainarray);
        update_option($this->dbname_mainitems, $mainarray);
        echo 'success';
        die();
    }

    function post_save_configs() {
        //---this is the main save function which saves item
        $auxarray = array();
        $mainarray = array();

        //print_r($this->mainitems);
        //parsing post data
        parse_str($_POST['postdata'], $auxarray);

//        echo 'auxarray - '; print_rr($auxarray);


        if (isset($_POST['currdb'])) {
            $this->currDb = $_POST['currdb'];
        }
        //echo 'ceva'; print_r($this->dbs);
        if ($this->currDb != 'main' && $this->currDb != '') {
            $this->dbname_mainitems_configs.='-' . $this->currDb;
        }
        //echo $this->dbname_mainitems;
        if (isset($_POST['sliderid'])) {
            //print_r($auxarray);
            $mainarray = get_option($this->dbname_mainitems_configs);
            foreach ($auxarray as $label => $value) {
                $aux = explode('-', $label);
                $tempmainarray[$aux[1]][$aux[2]] = $auxarray[$label];
            }
            $mainarray[$_POST['sliderid']] = $tempmainarray;
        } else {


	        if (isset($_POST['slider_name'])) {


	            if($_POST['slider_name']=='temp123'){

                }
	            $auxarray['0-settings-id']=$_POST['slider_name'];



		        $vpconfig_k = count($this->mainitems_configs);
		        $vpconfig_id = $_POST['slider_name'];
		        for ($i = 0; $i < count($this->mainitems_configs); $i++) {
			        if ((isset($vpconfig_id)) && ($vpconfig_id == $this->mainitems_configs[$i]['settings']['id'])) {
				        $vpconfig_k = $i;
			        }
		        }


		        $mainarray = get_option($this->dbname_mainitems_configs);
		        foreach ($auxarray as $label => $value) {
			        $aux = explode('-', $label);
			        $tempmainarray[$aux[1]][$aux[2]] = $auxarray[$label];
		        }


		        if($_POST['slider_name']=='temp123'){

//		            echo 'tempmainarray - '; print_rr($tempmainarray);
		            update_option('dzsap_temp_vpconfig',$tempmainarray);
		        }else{

			        $mainarray[$vpconfig_k] = $tempmainarray;
                }


//		        print_rr($mainarray);

	        }else{

		        foreach ($auxarray as $label => $value) {
			        //echo $auxarray[$label];
			        $aux = explode('-', $label);
			        $mainarray[$aux[0]][$aux[1]][$aux[2]] = $auxarray[$label];
		        }
            }

        }
        //echo $this->dbname_mainitems; print_r($_POST); print_r($this->currDb); echo isset($_POST['currdb']);
        update_option($this->dbname_mainitems_configs, $mainarray);
        echo 'success';
        die();
    }

    function filter_attachment_fields_to_edit($form_fields, $post) {


        $vpconfigsstr = '';
        $the_id = $post->ID;
        $post_type = get_post_mime_type($the_id);
        //print_r($this->mainitems_configs);
        ////print_r($post);


        if (strpos($post_type, "audio") === false) {
            return $form_fields;
        }

        foreach ($this->mainitems_configs as $vpconfig) {
            //print_r($vpconfig);
            $vpconfigsstr .='<option value="' . $vpconfig['settings']['id'] . '">' . $vpconfig['settings']['id'] . '</option>';
        }



        $html_sel = '<select class="styleme" id="attachments-' . $post->ID . '-dzsap-config" name="attachments[' . $post->ID . '][dzsap-config]"><option value="default">Default Settings</option>';
        $html_sel.=$vpconfigsstr;
        $html_sel .='</select>';
        //$html_sel.='<div>'.$post_type.'</div>';

        $form_fields['dzsap-config'] = array(
            'label' => 'ZoomSounds Player Config',
            'input' => 'html',
            'html' => $html_sel,
            'helps' => 'choose a configuration for the player / edit in ZoomSounds > Player Configs',
        );



        if($this->mainoptions['skinwave_wave_mode']!='canvas') {

            $lab = 'waveformbg';
//        print_r($post);

            $loc = $post->guid;

            if (wp_get_attachment_url($post->id)) {
                $loc = wp_get_attachment_url($post->id);
            }

//        echo 'url -> '.$loc;

            $html_input = '<div class="aux-file-location" style="display:none;">' . $loc . '</div><input id="attachments-' . $post->ID . '-' . $lab . '" class="textinput upload-prev main-thumb" name="attachments[' . $post->ID . '][' . $lab . ']"';
            if (get_post_meta($the_id, '_' . $lab, true) != '') {
                $html_input .= ' value="' . get_post_meta($the_id, '_' . $lab, true) . '"';
            }
            $html_input .= '/><span class="aux-wave-generator"><button class="btn-autogenerate-waveform-bg button-secondary">Auto Generate</button></span> &nbsp;<button class="btn-generate-default-waveform-bg button-secondary">Default Waveform</button>';

            $form_fields[$lab] = array(
                'label' => 'Waveform Background',
                'input' => 'html',
                'html' => $html_input,
                'helps' => '* only for skin-wave / the path to the waveform bg file / auto generate the wave form by cliking the auto generate button and then the orange button that appears ( wait for loading ) <br> <em>note: only recommded for regular songs ( under 5-6 minutes ) - anything else then that is very cpu extensive / better to use a fake waveform ( the default waveform button ) ',
            );


            $lab = 'waveformprog';
            $html_input = '<div class="aux-file-location" style="display:none;">' . $loc . '</div><input id="attachments-' . $post->ID . '-' . $lab . '" class="textinput upload-prev main-thumb" name="attachments[' . $post->ID . '][' . $lab . ']"';
            if (get_post_meta($the_id, '_' . $lab, true) != '') {
                $html_input .= ' value="' . get_post_meta($the_id, '_' . $lab, true) . '"';
            }
            $html_input .= '/><span class="aux-wave-generator"><button class="btn-autogenerate-waveform-prog button-secondary">Auto Generate</button></span> &nbsp;<button class="btn-generate-default-waveform-prog button-secondary">Default Waveform</button>';

            $form_fields[$lab] = array(
                'label' => 'Waveform Progress',
                'input' => 'html',
                'html' => $html_input,
                'helps' => '* only for skin-wave / the path to the waveform progress file / auto generate the wave form by cliking the auto generate button and then the orange button that appears',
            );

        }








        $lab = 'dzsap-thumb';
        $html_input = '<input id="attachments-' . $post->ID . '-' . $lab . '" class="upload-target-prev" name="attachments[' . $post->ID . '][' . $lab . ']"';
        if (get_post_meta($the_id, '_' . $lab, true) != '') {
            $html_input.=' value="' . get_post_meta($the_id, '_' . $lab, true) . '"';
        }
        $html_input.='/><a href="#" class="upload-for-target button-secondary">' . __('Upload', 'dzsap') . '</a>';

        $form_fields[$lab] = array(
            'label' => __('Thumbnail','dzsap'),
            'input' => 'html',
            'html' => $html_input,
            'helps' => __('choose a thumbnail / optional','dzsap'),
        );


        $lab = 'dzsap_sourceogg';
        $html_input = '<input id="attachments-' . $post->ID . '-' . $lab . '" class="upload-target-prev upload-type-audio" name="attachments[' . $post->ID . '][' . $lab . ']"';
        if (get_post_meta($the_id, '_' . $lab, true) != '') {
            $html_input.=' value="' . get_post_meta($the_id, '_' . $lab, true) . '"';
        }
        $html_input.='/><button class="upload-for-target button-secondary">' . __('Upload', 'dzsap') . '</button>';

        $form_fields[$lab] = array(
            'label' => __('OGG Source'),
            'input' => 'html',
            'html' => $html_input,
            'helps' => 'optional - if you do not set this, the full flash player backup will kick in.',
        );




        return $form_fields;
    }

    function filter_attachment_fields_to_save($post, $attachment) {
        //print_r($post);
        $pid = $post['ID'];
        $lab = 'waveformbg';
        //print_r($attachment);
        if (isset($attachment[$lab])) {
            update_post_meta($pid, '_' . $lab, $attachment[$lab]);
        }
        $lab = 'waveformprog';
        if (isset($attachment[$lab])) {
            update_post_meta($pid, '_' . $lab, $attachment[$lab]);
        }
        $lab = 'dzsap-thumb';
        if (isset($attachment[$lab])) {
            update_post_meta($pid, '_' . $lab, $attachment[$lab]);
        }
        $lab = 'dzsap_sourceogg';
        if (isset($attachment[$lab])) {
            update_post_meta($pid, '_' . $lab, $attachment[$lab]);
        }
        return $post;
    }

}



$dzsap_got_category_feed = false;




if(function_exists('dzsap_sort_by_likes')==false) {
	function dzsap_sort_by_likes($a, $b)
	{
	    if(isset($a['likes']) && is_numeric($a['likes']) && isset($b['likes']) && is_numeric($b['likes'])){

		    return $b['likes'] - $a['likes'];
        }
	}
}