<?php

$arr_off_on = array(
	array(
		'label'=>__("Off"),
		'value'=>'off',
	),
	array(
		'label'=>__("On"),
		'value'=>'on',
	),
);

$arr_ap_configs = array(
	array(
		'label'=>__("Default"),
		'value'=>'default',
	),
);

if(isset($this->mainitems_configs)){

	foreach ($this->mainitems_configs as $mc){


		$aux = array(
			'label'=>$mc['settings']['id'],
			'value'=>$mc['settings']['id'],
		);

		array_push($arr_ap_configs, $aux);
	}
}


$this->options_item_meta = array(





	array(
		'name' => 'artistname',
		'type' => 'text',
		'title' => esc_html__("Artist Title",'dzsap'). ' '.esc_html__("( line 1 )",'dzsap'),
		'sidenote' => __("title to appear on the left top"),

		'context' => 'content',
		'default' => 'default',
		'category'=>'',
		//        'it_is_for' => 'shortcode_generator',
	),


	array(
		'name'=>'the_post_title',
		'type'=>'text',
		'title'=>__("Song Title"),
		'only_for'=>array('sliders_admin'),
		'sidenote'=>__("the title of the song"),
	),



	array(
		'name'=>'dzsap_meta_replace_songname',
		'type'=>'text',
		'category'=>'extra_html',
		'title'=>__("Song name"),
		'sidenote'=>esc_html__("replace song name or input ",'dzsap')."<strong>none</strong> ".esc_html__("for nothing in the field, or input a ",'dzsap').'<strong>{{id3}}</strong> '.esc_html__("for trying to get id3 tags",'dzsap'),
	),


	array(
		'name'=>'dzsap_meta_type',
		'type'=>'select',
		'select_type'=>'opener-listbuttons',
		'title'=>__("Type"),
		'sidenote'=>__("select the type of media"),
		'choices'=>array(
			array(
				'label'=>'',
				'value'=>'detect',
			),
			array(
				'label'=>__("Self Hosted"),
				'value'=>'audio',
			),
			array(
				'label'=>__("soundcloud"),
				'value'=>'soundcloud',
			),
			array(
				'label'=>__("shoutcast"),
				'value'=>'shoutcast',
			),
		),
		'choices_html'=>array(
			'<span class="option-con"><img src="'.$this->base_url.'admin//img/type_audio.png"/><span class="option-label">'.__("Auto detect").'</span></span>',
			'<span class="option-con"><img src="'.$this->base_url.'admin//img/type_audio.png"/><span class="option-label">'.__("Self Hosted").'</span></span>',
			'<span class="option-con"><img src="'.$this->base_url.'admin//img/type_soundcloud.png"/><span class="option-label">'.__("SoundCloud").'</span></span>',
			'<span class="option-con"><img src="'.$this->base_url.'admin//img/type_radio.png"/><span class="option-label">'.__("Radio Station").'</span></span>',
		),


	),

	array(
		'name'=>'dzsap_meta_item_source',
		'type'=>'attach',
		'no_preview'=>'on',
		'title'=>__("Source"),
		'upload_btn_extra_classes'=>'main-source-upload',
		'sidenote'=>__("link to mp3 or soundcloud link"),
	),

	array(
		'name'=>'dzsap_meta_item_thumb',
		'type'=>'attach',
		'upload_type'=>'image',
		'title'=>__("Thumbnail"),
		'sidenote'=>__("This will replace the default wordpress thumbnail"),
		'sidenote-2' => sprintf(__("input %snone%s to force no thumbnail",'dzsap'),'<strong>','</strong>'),
	),



	array(
		'name'=>'dzsap_meta_replace_artistname',
		'type'=>'text',
		'it_is_for'=>'for_item_meta_only',
		'title'=>__("Artist name"),
		'sidenote'=>esc_html__("Leave nothing in the field for default artist name ( your author name ); input ",'dzsap')."<strong>none</strong> ".esc_html__("for nothing in the field, or input a custom name",'dzsap'),
	),

	array(
		'name'=>'post_content',
		'type'=>'textarea',
		'upload_type'=>'image',
		'title'=>__("Description"),
		'sidenote'=>sprintf(__("this description will appear if the %sInfo button%s is enabled in the Player Configuration",'dzsap'),'<strong>','</strong>'),
	),




	array(
		'type'=>'dzs_row',
		'category'=>'misc',
		'it_is_for' => 'shortcode_generator',
	),


	array(
		'type'=>'dzs_col_md_6',
		'category'=>'misc',
		'it_is_for' => 'shortcode_generator',
	),

	array(
		'name'=>'dzsap_meta_playfrom',
		'type'=>'text',
		'category'=>'misc',
		'title'=>__("Play from"),
		'sidenote'=>__("choose a number of seconds from which the track to play from ( for example if set \"70\" then the track will start to play from 1 minute and 10 seconds ) or input \"last\" for the track to play at the last position where it was.",'dzsap'),
	),


	array(
		'name'=>'dzsap_meta_play_in_footer_player',
		'type'=>'select',
		'category'=>'misc',
		'title'=>__("Play in footer player",'dzsap'),
		'sidenote'=>__("optional - play this track in the footer player ( footer player must be setuped on the page )",'dzsap'),
		'choices'=>array(
			array(
				'label'=>__("Inline"),
				'value'=>'off',
			),
			array(
				'label'=>__("In footer"),
				'value'=>'on',
			),
		),
	),


	array(
		'type'=>'dzs_col_md_6_end',
		'category'=>'misc',
		'it_is_for' => 'shortcode_generator',
	),


	array(
		'type'=>'dzs_col_md_6',
		'category'=>'misc',
		'it_is_for' => 'shortcode_generator',
	),
	array(
		'name'=>'dzsap_meta_enable_download_button',
		'type'=>'select',
		'class'=>' dzs-dependency-field',
		'category'=>'misc',
		'title'=>__("Enable Download Button",'dzsap'),
		'sidenote'=>__("optional - Enable Download Button for this track",'dzsap'),
		'choices'=>array(
			array(
				'label'=>__("Disable"),
				'value'=>'off',
			),
			array(
				'label'=>__("Enable"),
				'value'=>'on',
			),
		),
	),
	array(
		'name'=>'dzsap_meta_download_custom_link_enable',
		'type'=>'select',
		'category'=>'misc',
		'class'=>'dzs-dependency-field',
		'title'=>__("Custom Link Download",'dzsap'),
		'sidenote'=>__("a custom link for the download button - clicknig it will go to this link if set, if it is not set then it will just download the track",'dzsap'),
		'choices'=>array(
			array(
				'label'=>__("Disable"),
				'value'=>'off',
			),
			array(
				'label'=>__("Enable"),
				'value'=>'on',
			),
		),
	),
	array(
		'name'=>'dzsap_meta_download_custom_link',
		'type'=>'text',
		'category'=>'misc',
		'title'=>__("Custom Link Download",'dzsap'),
		'sidenote'=>__("a custom link for the download button - clicknig it will go to this link if set, if it is not set then it will just download the track",'dzsap'),

		'dependency' => array(

			array(
				'element'=>'dzsap_meta_download_custom_link_enable',
				'value'=>array('on'),
			),
		)
		,
	),

	array(
		'type'=>'dzs_col_md_6_end',
		'category'=>'misc',
		'it_is_for' => 'shortcode_generator',
	),

	array(
		'type'=>'dzs_row_end',
		'category'=>'misc',
		'it_is_for' => 'shortcode_generator',
	),




	array(
		'name'=>'dzsap_meta_item_extra_classes_player',
		'type'=>'text',
		'category'=>'extra_html',
		'title'=>__("Extra Classes"),
		'sidenote'=>__("extra html classes applied to the player"),
	),



	array(
		'name'=>'dzsap_meta_replace_menu_songname',
		'type'=>'text',
		'category'=>'extra_html',
		'title'=>__("Menu song name"),
		'sidenote'=>esc_html__("Leave nothing in the field for default artist name ( your author name ); input ",'dzsap')."<strong>none</strong> ".esc_html__("for nothing in the field, or input a custom name",'dzsap'),
	),

	array(
		'name'=>'dzsap_meta_replace_menu_artistname',
		'type'=>'text',
		'category'=>'extra_html',
		'title'=>__("Menu artist name"),
		'sidenote'=>esc_html__("Leave nothing in the field for default artist name ( your author name ); input ",'dzsap')."<strong>none</strong> ".esc_html__("for nothing in the field, or input a custom name",'dzsap'),
	),



	array(
		'name'=>'dzsap_meta_replace_menu_artistname',
		'type'=>'custom_html',
		'category'=>'extra_html',
		'only_for'=>array('sliders_admin'),
		'title'=>__("Menu artist name"),
		'custom_html'=>'<h3>'.esc_html__("add custom buttons to player",'dzsap').'</h3><div class="player-mockup"><img class="the-img fullwidth" src="'.$this->base_url.'assets/svg/skin_wave.svg"/><div class="bottom-right-buttons-area"></div><div class="bottom-buttons-area"></div></div>',
	),


	//  extrahtml_in_float_right_from_config








	array(
		'type'=>'dzs_row',
		'category'=>'extra_html',
		'it_is_for' => 'shortcode_generator',
	),


	array(
		'type'=>'dzs_col_md_6',
		'category'=>'extra_html',
		'it_is_for' => 'shortcode_generator',
	),



	array(
		'name'=>'extrahtml_in_bottom_controls_from_player',
		'type'=>'textarea',
		'category'=>'extra_html',
		'extraattr' => ' style="width: 100%; "',
		'title'=>__("Extra HTML in Bottom Controls"),
		'sidenote'=>esc_html__("extra html on the in the bottom controls, leave nothing here and the content will come from the player configuration, if you have set anything here",'dzsap').'<br>'
		            .'<strong>'.esc_html__("demo content: ",'dzsap').'</strong>'.'[player_button style="btn-zoomsounds" background_color="#86ccb6" color="#ffffff" label="Twitter" icon="fa-twitter" link="#"]'
		,
	),

	array(
		'type'=>'dzs_col_md_6_end',
		'category'=>'extra_html',
		'it_is_for' => 'shortcode_generator',
	),

	// -----

	array(
		'type'=>'dzs_col_md_6',
		'category'=>'extra_html',
		'it_is_for' => 'shortcode_generator',
	),

	array(
		'name'=>'extrahtml_in_float_right_from_player',
		'type'=>'textarea',
		'category'=>'extra_html',
		'title'=>__("Extra HTML in Right Controls"),
		'sidenote'=>esc_html__("extra html on the in the bottom controls, leave nothing here and the content will come from the player configuration, if you have set anything here",'dzsap').'<br>'
		            .'<strong>'.esc_html__("demo content: ",'dzsap').'</strong>'.'[player_button style="player-but" label="Twitter Profile" icon="fa-twitter" link="#"] '
	),






	array(
		'type'=>'dzs_col_md_6_end',
		'category'=>'extra_html',
		'it_is_for' => 'shortcode_generator',
	),


	array(
		'type'=>'dzs_row_end',
		'category'=>'extra_html',
		'it_is_for' => 'shortcode_generator',
	),


	// -----








	array(
		'name'=>'dzsap_meta_config',
		'type'=>'select',
		'category'=>'',
		'title'=>esc_html__("Audio Player Configuration",'dzsap'),
		'sidenote'=>sprintf(__("the audio player configuration , can be edited in %s > Player Configurations"),'ZoomSounds'),
		'choices'=>$arr_ap_configs,
		'default' => 'default',
		'it_is_for' => 'shortcode_generator',
	),


	array(
		'name'=>'cover',
		'type' => 'image',
		'title' => __("Cover"),
		'sidenote' => __("cover image to show before video play"),

		'context' => 'content',
		'default' => '',
		'category'=>'',
		'it_is_for' => 'shortcode_generator',
	),




	array(
		'name'=>'autoplay',
		'type' => 'select',
		'title' => __("Autoplay"),
		'sidenote' => __("autoplay the videos"),

		'context' => 'content',
		'options' => $arr_off_on,
		'default' => 'off',
		'category'=>'',
		'it_is_for' => 'shortcode_generator',
	),
	array(
		'name'=>'loop',
		'type' => 'select',
		'title' => __("Loop"),
		'sidenote' => __("loop the video on end"),

		'context' => 'content',
		'options' => $arr_off_on,
		'default' => 'off',
		'category'=>'',
		'it_is_for' => 'shortcode_generator',
	),



	array(
		'name' => 'open_in_ultibox',
		'type' => 'select',
		'class' => 'dzs-dependency-field',
		'title' => __("Open in Ultibox?"),
		'sidenote' => __("open the current player in a lightbox"),

		'context' => 'content',
		'options' => $arr_off_on,
		'default' => 'off',
		'category'=>'',
		'it_is_for' => 'shortcode_generator',
	),





	array(
		'name' => 'enable_likes',
		'type' => 'select',
		'title' => __("Enable Likes ? "),
		'sidenote' => __("enable like count and button"),
		'sidenote-2' => __("you need to have a id to link the player to in the database for the views, likes, etc to be recorded"),
		'sidenote-2-class' => 'notice-for-playerid warning',

		'context' => 'content',
		'options' => $arr_off_on,
		'default' => 'off',
		'category'=>'',
		'it_is_for' => 'shortcode_generator',
	),


	array(
		'name' => 'enable_views',
		'type' => 'select',
		'title' => __("Enable Play Count ? "),
		'sidenote' => __("enable play count "),
		'sidenote-2' => __("you need to have a id to link the player to in the database for the views, likes, etc to be recorded"),
		'sidenote-2-class' => 'notice-for-playerid warning',

		'context' => 'content',
		'options' => $arr_off_on,
		'default' => 'off',
		'category'=>'',
		'it_is_for' => 'shortcode_generator',
	),


	array(
		'name' => 'itunes_link',
		'type' => 'text',
		'title' => esc_html__("iTunes Link",'dzsap'),
		'sidenote' => esc_html__("input an optional link to the itunes track page",'dzsap'),

		'context' => 'content',
		'default' => '',
		'category'=>'',
		'it_is_for' => 'shortcode_generator',
	),



	array(
		'name' => 'playerid',
		'type' => 'text',
		'title' => __("Link to ID"),
		'sidenote' => __("you need to link to a player id"),
		'class' => 'dzs-dependency-field',

		'context' => 'content',
		'default' => '',
		'category'=>'',
		'it_is_for' => 'shortcode_generator',
	),






	array(
		'name' => 'wrapper_image',
		'type' => 'attach',
		'library_type' => 'image',
		'upload_type' => 'upload',
		'class' => '',
		'title' => __("Wrapper Image"),
		'sidenote' => __("The source, input a mp4 or a youtube link or a youtube id or a vimeo link or a vimeo id"),

		'context' => 'content',
		'category'=>'misc',
		'default' => '',
		'prefer_id' => 'on',
	),



	array(
		'name' => 'wrapper_image_type',
		'type' => 'select',
		'title' => __("Wrapper Image Type"),

		'context' => 'content',
		'category'=>'misc',
		'options' => array(
			array(
				'label'=>__("Wide Image Wrapper"),
				'value'=>'zoomsounds-wrapper-bg-center',
			),
			array(
				'label'=>__("Rectangle Image Wrapper"),
				'value'=>'zoomsounds-wrapper-bg-bellow',
			),
			array(
				'label'=>__("No wrapper"),
				'value'=>'zoomsounds-no-wrapper',
			),
		),
		'default' => 'off',
		'dependency' => array(

			array(
				'element'=>'wrapper_image',
				'value'=>array('anything_but_blank'),
			),
		)
		,
	),

);