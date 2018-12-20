<?php

//print_r($this);


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

$arr_on_off = array(
	array(
		'label'=>__("On"),
		'value'=>'on',
	),
	array(
		'label'=>__("Off"),
		'value'=>'off',
	),
);
$arr_default_detect = array(
	array(
		'label'=>__("Default"),
		'value'=>'default',
	),
	array(
		'label'=>__("Detect"),
		'value'=>'detect',
	),
);

$types = array(
	array(
		'label'=>__("Auto Detect"),
		'value'=>'detect',
	),
	array(
		'label'=>__("Audio"),
		'value'=>'audio',
	),
	array(
		'value'=>'soundcloud',
		'label'=>("Soundcloud"),
	),
	array(
		'value'=>'shoutcast',
		'label'=>__("Radio Station"),
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


$arr_wrapper_type = array(
	array(
		'label'=>__("Wide Image Wrapper"),
		'value'=>'zoomsounds-wrapper-bg-center',
	),
	array(
		'label'=>__("Rectangle Image Wrapper"),
		'value'=>'zoomsounds-wrapper-bg-bellow',
	),
);
$dependency_wrapper_type = array(

	array(
		'element'=>'wrapper_image',
		'value'=>array('anything_but_blank'),
	),
)
;



$dependency_content = array(

	array(
		'element'=>'open_in_ultibox',
		'value'=>array('on'),
	),
)
;
$dependency_download = array(

	array(
		'element'=>'enable_download_button',
		'value'=>array('on'),
	),
)
;

$this->options_array_player = array(



	'source' => array(
		'type' => 'upload',
		'library_type' => 'audio',
		'upload_type' => 'upload',
		'class' => '',
		'title' => __("Source"),
		'sidenote' => __("The source, input a mp3 or a youtube link"),

		'context' => 'content',
		'default' => '',
		'prefer_id' => 'on',
	),




	'type' => array(
		'type' => 'select',
		'title' => __("Type"),
		'sidenote' => sprintf(__("leave the type to default for the player to decide wheter it is a souncloud link of a self hosted mp3")),


		'context' => 'content',
		'options' => $types,
		'default' => 'normal',
	),
	'config' => array(
		'type' => 'select',
		'title' => __("Audio Player Configuration"),
		'holder' => 'div',
		'sidenote' => sprintf(__("the audio player configuration , can be edited in %s > Player Configurations"),'ZoomSounds'),

		'context' => 'content',
		'options' => $arr_ap_configs,
		'default' => 'default',
	),
	'thumb' => array(
		'type' => 'image',
		'title' => __("Thumbnail"),
		'sidenote' => __("a thumbnail for the song"),
		'sidenote-2' => sprintf(__("input %snone%s to force no thumbnail",'dzsap'),'<strong>','</strong>'),

		'context' => 'content',
		'default' => '',
	),
	'cover' => array(
		'type' => 'image',
		'title' => __("Cover"),
		'sidenote' => __("cover image to show before video play"),

		'context' => 'content',
		'default' => '',
	),
	'autoplay' => array(
		'type' => 'select',
		'title' => __("Autoplay"),
		'sidenote' => __("autoplay the videos"),

		'context' => 'content',
		'options' => $arr_off_on,
		'default' => 'off',
	),
	'loop' => array(
		'type' => 'select',
		'title' => __("Loop"),
		'sidenote' => __("loop the video on end"),

		'context' => 'content',
		'options' => $arr_off_on,
		'default' => 'off',
	),
	'extra_classes_player' => array(
		'type' => 'text',
		'title' => __("Extra Classes to the Player"),
		'sidenote' => __("enter a extra css class for the player for example, entering \"with-bottom-shadow\" will create a shadow underneath the player"),

		'context' => 'content',
		'default' => '',
	),
	'artistname' => array(
		'type' => 'text',
		'title' => esc_html__("Artist Title",'dzsap'). ' '.sprintf(esc_html__("( line %s )",'dzsap'),'1'),
		'sidenote' => __("title to appear on the left top"),

		'context' => 'content',
		'default' => 'default',
	),
	'songname' => array(
		'type' => 'text',
		'title' => __("Song Title"),
		'sidenote' => __("title to appear on the left top"),

		'context' => 'content',
		'default' => 'default',
	),
	'open_in_ultibox' => array(
		'type' => 'select',
		'title' => __("Open in Ultibox?"),
		'sidenote' => __("open the current player in a lightbox"),

		'context' => 'content',
		'options' => $arr_off_on,
		'default' => 'off',
	),
	'content' => array(
		'type' => 'textarea_html',
		'title' => __("Content"),
		'sidenote' => __("description to appear if the info button is enabled in video player configurations"),

		'context' => 'content',
		'default' => '',
		'dependency' => $dependency_content,
	),
	'playerid' => array(
		'type' => 'text',
		'title' => __("Link to ID"),
		'sidenote' => __("you need to link to a player id"),

		'context' => 'content',
		'default' => '',
	),





	'enable_likes' => array(
		'type' => 'select',
		'title' => __("Enable Likes ? "),
		'sidenote' => __("enable like count and button"),
		'sidenote-2' => __("you need to have a id to link the player to in the database for the views, likes, etc to be recorded"),
		'sidenote-2-class' => 'notice-for-playerid warning',

		'context' => 'content',
		'options' => $arr_off_on,
		'default' => 'off',
	),


	'enable_views' => array(
		'type' => 'select',
		'title' => __("Enable Play Count ? "),
		'sidenote' => __("enable play count "),
		'sidenote-2' => __("you need to have a id to link the player to in the database for the views, likes, etc to be recorded"),
		'sidenote-2-class' => 'notice-for-playerid warning',

		'context' => 'content',
		'options' => $arr_off_on,
		'default' => 'off',
	),



	// -- download
	'enable_download_button' => array(
		'type' => 'select',
		'title' => __("Enable Download Button ? "),
		'sidenote' => __("enable a download button for this item"),
		'sidenote-2' => __("you need to have a id to link the player to in the database for the views, likes, etc to be recorded"),
		'sidenote-2-class' => 'notice-for-playerid warning',

		'class' => ' dzs-dependency-field',
		'context' => 'content',
		'options' => $arr_off_on,
		'default' => 'off',
	),
	'download_custom_link' => array(
		'type' => 'text',
		'title' => __("Download Link"),
		'sidenote' => __("if no link is set then the button will just download the track"),

		'context' => 'content',
		'default' => '',
		'dependency' => $dependency_download,
	),
	'download_link_label' => array(
		'type' => 'text',
		'title' => __("Link Label"),
		'sidenote' => __("If link button is enabled in the player configurations, then you can set a link here"),

		'context' => 'content',
		'default' => '',
		'dependency' => $dependency_download,
	),
	// -- download END



	'itunes_link' => array(
		'type' => 'text',
		'title' => __("iTunes Link"),
		'sidenote' => __("input an optional link to the itunes track page"),

		'context' => 'content',
		'default' => '',
	),





	'wrapper_image' => array(
		'type' => 'upload',
		'library_type' => 'image',
		'upload_type' => 'upload',
		'class' => '',
		'title' => __("Wrapper Image"),
		'sidenote' => __("The source, input a mp4 or a youtube link or a youtube id or a vimeo link or a vimeo id"),

		'context' => 'content',
		'default' => '',
		'prefer_id' => 'off',
	),



	'wrapper_image_type' => array(
		'type' => 'select',
		'title' => __("Wrapper Image Type"),

		'context' => 'content',
		'options' => $arr_wrapper_type,
		'default' => 'off',
		'dependency' => $dependency_wrapper_type,
	),

	'play_target' => array(
		'type' => 'select',
		'title' => __("Play Externally ?"),

		'context' => 'content',
		'options' => array(
			array(
				'label'=>__("No"),
				'value'=>'default',
			),
			array(
				'label'=>__("Play in Footer"),
				'value'=>'footer',
			),
		),
		'default' => 'default',
	),




	'extra_classes' => array(
		'type' => 'text',
		'title' => __("Extra Classes"),
		'sidenote' => __("some extra classes"),

		'context' => 'content',
		'default' => '',
	),






);