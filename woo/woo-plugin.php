<?php

add_action( 'woocommerce_init', 'dzsap_woo_woocommerce_init' );


function dzsap_woo_woocommerce_init(){
//    echo 'ceva';



	add_action( 'woocommerce_product_write_panel_tabs', 'dzsap_woo_product_write_panel_tab' );
//    add_action( 'woocommerce_product_write_panels', 'dzsap_woo_product_write_panel' );
	add_action( 'woocommerce_product_data_panels', 'dzsap_woo_product_write_panel' );
	add_action( 'woocommerce_process_product_meta',  'dzsap_woo_product_save_data', 10, 2 );

	// frontend stuff
	add_filter( 'woocommerce_product_tabs','dzsap_woo_add_custom_product_tabs');

	add_filter( 'woocommerce_custom_product_tabs_lite_content', 'dzsap_woo_do_shortcode' );

}










$dzsap_woo_tab_data = false;


function dzsap_woo_add_custom_product_tabs( $tabs ) {
	global $product,$dzsap_woo_tab_data;

	return $tabs;
}


function dzsap_woo_custom_product_tabs_panel_content( $key, $tab ) {

	// allow shortcodes to function
	$content = apply_filters( 'the_content', $tab['content'] );
	$content = str_replace( ']]>', ']]&gt;', $content );

	echo apply_filters( 'woocommerce_custom_product_tabs_lite_heading', '<h2>' . $tab['title'] . '</h2>', $tab );
	echo apply_filters( 'woocommerce_custom_product_tabs_lite_content', $content, $tab );
}


function dzsap_woo_product_write_panel_tab() {
	echo "<li class=\"product_tabs_lite_tab\"><a href=\"#woocommerce_dzsap_tab\"><span>" . __( 'ZoomSounds') . "</span></a></li>";
}


/**
 * Adds the panel to the Product Data postbox in the product interface
 */
function dzsap_woo_product_write_panel() {
	global $post, $dzsap;
	// the product


	echo '<div id="woocommerce_dzsap_tab" class="panel wc-metaboxes-wrapper woocommerce_options_panel">';


	$lab ='dzsap_woo_product_track';
	echo '<div class="upload-for-target-con">';
	woocommerce_wp_text_input( array( 'id' => $lab, 'class'=>'upload-target-prev', 'label' => __( 'Track' ), 'description' => ( '<button class="button-secondary action upload-for-target ">Upload</button>'), 'value' => get_post_meta($post->ID,$lab,true) ) );


	echo '</div>';



	if($dzsap->mainoptions['skinwave_wave_mode']!='canvas') {


		$lab = 'dzsap_woo_product_track_waveformbg';
		echo '<div class="upload-for-target-con">';
		woocommerce_wp_text_input(array('id' => $lab, 'class' => 'upload-target-prev', 'label' => __('Track Waveform BG'), 'description' => ('<span class="aux-wave-generator"><button class="btn-autogenerate-waveform-bg button-secondary">Auto Generate</button></span> &nbsp;<button class="btn-generate-default-waveform-bg button-secondary">Default Waveform</button>'), 'value' => get_post_meta($post->ID, $lab, true)));
		echo '</div>';


		$lab = 'dzsap_woo_product_track_waveformprog';
		echo '<div class="upload-for-target-con">';
		woocommerce_wp_text_input(array('id' => $lab, 'class' => 'upload-target-prev', 'label' => __('Track Waveform Progress '), 'description' => ('<span class="aux-wave-generator"><button class="btn-autogenerate-waveform-prog button-secondary">Auto Generate</button></span> &nbsp;<button class="btn-generate-default-waveform-prog button-secondary">Default Waveform</button>'), 'value' => get_post_meta($post->ID, $lab, true)));
		echo '</div>';
	}else{


		?>

        <p class="form-field dzsap_woo_product_track_field "><label for="dzsap_woo_product_track">Waveform</label>
            <span class="regenerate-waveform-con">
            <button class="button-secondary regenerate-waveform regenerate-waveform--from-woo " data-playerid="<?php echo $_GET['post']; ?>"><?php echo __("Regenerate Waveform"); ?></button>
        </span> </p>




		<?php

	}


	$lab ='dzsap_woo_sample_time_start';

	$val = 0;

	if(get_post_meta($post->ID,$lab,true)){
		$val = get_post_meta($post->ID,$lab,true);
	}

	echo '<div class="woocommerce_dzsap_tab-setting">';
	woocommerce_wp_text_input( array( 'id' => $lab, 'class'=>'sample-time-start-feeder', 'label' => __( 'Sample Time Start' ), 'description' => __('If this is a sample ( you are not showing the full track, you can input a start time here ), leave 0 if not'), 'value' => $val ) );


	echo '</div>';



	$lab ='dzsap_woo_sample_time_end';

	$val = 0;

	if(get_post_meta($post->ID,$lab,true)){
		$val = get_post_meta($post->ID,$lab,true);
	}


	echo '<div class="woocommerce_dzsap_tab-setting">';
	woocommerce_wp_text_input( array( 'id' => $lab, 'class'=>'sample-time-end-feeder', 'label' => __( 'Sample Time End' ), 'description' => __('If this is a sample ( you are not showing the full track, you can input a end time here ), leave 0 if not'), 'value' => $val ) );


	echo '</div>';



	$lab ='dzsap_woo_sample_time_total';

	$val = 0;

	if(get_post_meta($post->ID,$lab,true)){
		$val = get_post_meta($post->ID,$lab,true);
	}


	echo '<div class="woocommerce_dzsap_tab-setting">';
	woocommerce_wp_text_input( array( 'id' => $lab, 'class'=>'sample-time-total-feeder', 'label' => __( 'Sample Time Total' ), 'description' => __('The total track duration  ( in seconds ) '), 'value' => $val ) );


	echo '</div>';




	$lab ='dzsap_woo_subtitle';
	$val = '';

	if(get_post_meta($post->ID,$lab,true)){
		$val = get_post_meta($post->ID,$lab,true);
	}

	echo '<div class="woocommerce_dzsap_tab-setting">';
	woocommerce_wp_text_input( array( 'id' => $lab, 'class'=>'', 'label' => __( 'Subtitle' ), 'description' => __('The subtitle for some grid styles'), 'value' => $val ) );


	echo '</div>';




	$lab ='dzsap_woo_custom_link';
	$val = '';

	if(get_post_meta($post->ID,$lab,true)){
		$val = get_post_meta($post->ID,$lab,true);
	}

	echo '<div class="woocommerce_dzsap_tab-setting">';
	woocommerce_wp_text_input( array( 'id' => $lab, 'class'=>'', 'label' => __( 'Custom Link on Buy' ), 'description' => __('Custom link on Buy button click'), 'value' => $val ) );


	echo '</div>';




	$lab ='dzsap_meta_replace_artistname';
	$val = '';

	if(get_post_meta($post->ID,$lab,true)){
		$val = get_post_meta($post->ID,$lab,true);
	}




	echo '<div class="woocommerce_dzsap_tab-setting">';
	woocommerce_wp_text_input( array( 'id' => $lab, 'class'=>'', 'label' => __( 'Artist name' ), 'description' =>
		wp_kses(sprintf(
				__('default will be the author name input %s for no author name','dzsap')
				,'<strong>none</strong>'
			)
			,$dzsap->allowed_tags),
	                                  'value' => $val  ));


	echo '</div>';



//        dzsap_woo_woocommerce_wp_textarea_input( array( 'id' => '_wc_custom_product_tabs_lite_tab_content', 'label' => __( 'Content' ), 'placeholder' => __( 'HTML and text to display.' ), 'value' => $tab['content'], 'style' => 'width:70%;height:21.5em;' ) );
	echo '</div>';

}


function dzsap_woo_product_save_data( $post_id, $post ) {

	/* Check autosave */
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
//    if (isset($_REQUEST['dzs_nonce'])) {
//        $nonce = $_REQUEST['dzs_nonce'];
//        if (!wp_verify_nonce($nonce,'dzs_nonce'))
//            wp_die('Security check');
//    }
	if (is_array($_POST)) {
		$auxa = $_POST;
//        print_r($auxa);
		foreach ($auxa as $label => $value) {

			//print_r($label); print_r($value);
			if (strpos($label,'dzsap_woo_') !== false) {
				dzs_savemeta($post_id,$label,$value);
			}
		}
	}

}


function dzsap_woo_woocommerce_wp_textarea_input( $field ) {
	global $thepostid, $post;

	if ( ! $thepostid ) $thepostid = $post->ID;
	if ( ! isset( $field['placeholder'] ) ) $field['placeholder'] = '';
	if ( ! isset( $field['class'] ) ) $field['class'] = 'short';
	if ( ! isset( $field['value'] ) ) $field['value'] = get_post_meta( $thepostid, $field['id'], true );

	echo '<p class="form-field ' . $field['id'] . '_field"><label style="display:block;" for="' . $field['id'] . '">' . $field['label'] . '</label><textarea class="' . $field['class'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" placeholder="' . $field['placeholder'] . '" rows="2" cols="20"' . (isset( $field['style'] ) ? ' style="' . $field['style'] . '"' : '') . '>' . esc_textarea( $field['value'] ) . '</textarea> ';

	if ( isset( $field['description'] ) && $field['description'] ) {
		echo '<span class="description">' . $field['description'] . '</span>';
	}

	echo '</p>';
}

