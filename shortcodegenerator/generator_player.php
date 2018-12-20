<?php
// some total cache vars that needs to be like this

function dzsap_shortcode_player_builder(){
	global $dzsap;


	$sample_data_installed = false;
	if($dzsap->sample_data && is_array($dzsap->sample_data)){
		$sample_data_installed = true;
	}


	$ids = '';




	if($dzsap->sample_data && isset($dzsap->sample_data['media'])){

		for($i=0;$i<count($dzsap->sample_data['media']);$i++){

			if($i>0){
				$ids.=',';
			}


			$ids.=$dzsap->sample_data['media'][$i];
		}

	}

	?>

    <style>.setting #wp-content-editor-tools{ padding-top: 0; } body .sidenote{ color: #777777; }</style>
    <script>
		<?php
		if(isset($_GET['sel'])){
			$aux = str_replace(array("\r","\r\n","\n"),'',$_GET['sel']);
			$aux = str_replace("'",'\\\'',$aux);
			echo 'window.dzsap_startinit = \''.stripslashes($aux).'\';';



		}

		if(isset($dzsap->sample_data['media']) && isset($dzsap->sample_data['media'][0]) ){

		?>
        window.sg1_shortcode = '[zoomsounds_player source="http://www.stephaniequinn.com/Music/Commercial%20DEMO%20-%2011.mp3" config="skinwavewithcomments" playerid="<?php echo $dzsap->sample_data['media'][0]; ?>" waveformbg="https://lh3.googleusercontent.com/OCkCqtmYpqevOPlhNY4R8oy37CmypYXtsM6CdwstJp-2X8y4O_MdmnOOyTZ2dODVq7sfxLqoRG2H-fGJ8GAwYDp7jtiyyesUiMjIZA4czV7dDqnaw0qhpkRBpfSmqW_uOkQtGvhJUn9nYAK2MQwQ_PtCfl4uHgb1cae5n7qNC8DjRgVorBBr_gZVLg0IZFXbLW0UTp-8KsqrZSyGHAgxbh7Q40-CKFvBKxZ7KblCTfwsEun4LElkYFe5ZPZOsn1EBrxsbXrSyAZVmm0VX7UXRnEQR-5YTIzZ6ttugwYonTFNwmiGxOCsg5RyYpwTNWMLE1v2fBUsBgSStiLrnwQqrK4VAfV-irLXdfXsy6ZG174u0uPdjGJq3qw3PcJUHatmxZDC5PbSrxTHR-K6OqTOV7bM641t40ZVNZfZmjOTzzL-eDWkKCUu5q5VBm254sJ4FK63bP5QbxOQem6nPadxEayRSKfyF4z4HUnoqsR1giPk8eWI63LcgGOZeSWGVw0T27N_Ugwz37Twr5Ilyk7q66elCiyOxK7IUuiur6-QYi0=w1170-h140-no" waveformprog="https://lh3.googleusercontent.com/3ZCeepH9HAhs1ojwrMVKRW4poGaqPSbeczAAs8XjBl8E4zh0vSzXY4ou7KtRXUoMDff70qz8vEa5YLwq_4kp4ufRHcTK8_7lbs5Ux4jTETAkhluI75nUweiBYztNkwtxRggzTLnu2kdyVn3lubZGDbe4-pxyvBtz2tWauKs9fw7wiMCcrkFz5BFi_X1q7ViGA205qTfuTLjltWzom09Xm8vgt5EsTHyInFoMAeSobImMrG5j67VTgrX_9vYDNu3RE_TbISRY9c7wdEXOplQZXJDHH3c86rdVaoclhGAbli3mHJ92iZmGrZM1JH0glyj-ymSSq8RU1Tw2Slb1QFYEwzJpr_wOR9BqqccLAf-yLawNG5TqTQLhrYekNfPaWEtUrcYvHMDeg2R_x7zZg0Q_FI4qvUjBrTu8ClZIf_fml4mer7KEl3uhNEDNr7pe9suucRGO_f_whT8bqjFsRCvh9obFhvj0Suvc-SNFTeLavV6EwIqFVYdHCwyedHxdmOGTsruvXw3CRqon0UFb2jqR2GO6ZUSQ9k9emXdGCZAVzqY=w1170-h140-no" thumb="" autoplay="on" cue="on" enable_likes="off" enable_views="off" songname="Track 1 from stephaniequinn.com" artistname="Steph"]';
        window.sg3_shortcode = '[zoomsounds_player source="http://www.stephaniequinn.com/Music/Commercial%20DEMO%20-%2011.mp3" config="example-skin-aria" playerid="<?php echo $dzsap->sample_data['media'][0]; ?>" waveformbg="https://lh3.googleusercontent.com/OCkCqtmYpqevOPlhNY4R8oy37CmypYXtsM6CdwstJp-2X8y4O_MdmnOOyTZ2dODVq7sfxLqoRG2H-fGJ8GAwYDp7jtiyyesUiMjIZA4czV7dDqnaw0qhpkRBpfSmqW_uOkQtGvhJUn9nYAK2MQwQ_PtCfl4uHgb1cae5n7qNC8DjRgVorBBr_gZVLg0IZFXbLW0UTp-8KsqrZSyGHAgxbh7Q40-CKFvBKxZ7KblCTfwsEun4LElkYFe5ZPZOsn1EBrxsbXrSyAZVmm0VX7UXRnEQR-5YTIzZ6ttugwYonTFNwmiGxOCsg5RyYpwTNWMLE1v2fBUsBgSStiLrnwQqrK4VAfV-irLXdfXsy6ZG174u0uPdjGJq3qw3PcJUHatmxZDC5PbSrxTHR-K6OqTOV7bM641t40ZVNZfZmjOTzzL-eDWkKCUu5q5VBm254sJ4FK63bP5QbxOQem6nPadxEayRSKfyF4z4HUnoqsR1giPk8eWI63LcgGOZeSWGVw0T27N_Ugwz37Twr5Ilyk7q66elCiyOxK7IUuiur6-QYi0=w1170-h140-no" waveformprog="https://lh3.googleusercontent.com/3ZCeepH9HAhs1ojwrMVKRW4poGaqPSbeczAAs8XjBl8E4zh0vSzXY4ou7KtRXUoMDff70qz8vEa5YLwq_4kp4ufRHcTK8_7lbs5Ux4jTETAkhluI75nUweiBYztNkwtxRggzTLnu2kdyVn3lubZGDbe4-pxyvBtz2tWauKs9fw7wiMCcrkFz5BFi_X1q7ViGA205qTfuTLjltWzom09Xm8vgt5EsTHyInFoMAeSobImMrG5j67VTgrX_9vYDNu3RE_TbISRY9c7wdEXOplQZXJDHH3c86rdVaoclhGAbli3mHJ92iZmGrZM1JH0glyj-ymSSq8RU1Tw2Slb1QFYEwzJpr_wOR9BqqccLAf-yLawNG5TqTQLhrYekNfPaWEtUrcYvHMDeg2R_x7zZg0Q_FI4qvUjBrTu8ClZIf_fml4mer7KEl3uhNEDNr7pe9suucRGO_f_whT8bqjFsRCvh9obFhvj0Suvc-SNFTeLavV6EwIqFVYdHCwyedHxdmOGTsruvXw3CRqon0UFb2jqR2GO6ZUSQ9k9emXdGCZAVzqY=w1170-h140-no" thumb="" autoplay="on" cue="on" enable_likes="off" enable_views="off" songname="Track 1 from stephaniequinn.com" artistname="Steph"]';
        window.sg2_shortcode = '[dzsap_woo_grid type="attachment" style="style1" faketarget=".dzsap_footer" ids="<?php echo $ids; ?>" ]';<?php

		}
		?>
    </script>
    <style>
        #dzsap-shortcode-tabs .tab-menu-con.is-always-active .tab-menu{
            padding-left: 15px;

        }
        #dzsap-shortcode-tabs .tab-menu-con.is-always-active .tab-menu:before{
            display: none;;
        }
    </style>
    <div class="wrap wrap-for-generator-player <?php
	if($sample_data_installed){
		echo 'sample-data-installed';
	}
	?>">
        <h3>ZoomSounds <?php echo __(" Shortcode Generator"); ?></h3>






		<?php






		$options_array = array();
		$ilab = 0;
		foreach($dzsap->options_array_player as $lab => $opt){

//        print_r($opt);

			if(
				$lab=='source'
				||$lab=='type'
				||$lab=='config'
				||$lab=='thumb'
				||$lab=='cover'
				||$lab=='autoplay'
				||$lab=='loop'
				||$lab=='extra_classes'
				||$lab=='extra_classes_player'
				||$lab=='songname'
				||$lab=='artistname'
				||$lab=='open_in_ultibox'
				||$lab=='enable_likes'
				||$lab=='enable_views'
				||$lab=='enable_download_button'
				||$lab=='playerid'
				||$lab=='itunes_link'
				||$lab=='wrapper_image'
				||$lab=='play_target'
				||$lab=='download_custom_link'
				||$lab=='download_link_label'
			){
				continue;
			}


			$options_array[$ilab] = array(
				'type'=>$opt['type'],
				'param_name'=>$lab,
				'heading' => $opt['title'],
				//            'context' => $opt['context'],
			);


//        echo 'lab - '.$lab; print_rr($opt);
			if(isset($opt['type'])){
				$options_array[$ilab]['type'] = $opt['type'];
				if($opt['type']=='select'){
					$options_array[$ilab]['type'] = 'dropdown';
				}
				if($opt['type']=='text'){
					$options_array[$ilab]['type'] = 'textfield';
				}
				if($opt['type']=='image'){
					$opt['type'] = 'upload';
					$opt['library_type'] = 'image';
				}
				if($opt['type']=='image'){
				}
				if($opt['type']=='upload'){
					$options_array[$ilab]['type'] = 'dzs_add_media_att';
				}
			}
			if(isset($opt['sidenote'])){
				$options_array[$ilab]['description'] = $opt['sidenote'];
			}
			if(isset($opt['default'])){
				$options_array[$ilab]['std'] = $opt['default'];
				$options_array[$ilab]['default'] = $opt['default'];
			}
			if(isset($opt['options'])){
				$options_array[$ilab]['value'] = $opt['options'];
			}

			if(isset($opt['library_type'])){
				$options_array[$ilab]['library_type'] = $opt['library_type'];
			}

			if(isset($opt['class'])){
				$options_array[$ilab]['class'] = $opt['class'];
			}




			?>
        <div class="setting" <?php

		if(isset($opt['dependency']) && $opt['dependency']){
			echo 'data-dependency=\''.json_encode($opt['dependency']).'\'';
		}


		?> data-label="<?php echo $lab; ?>">
            <h4 class="setting-label"><?php echo $opt['title']; ?></h4>
			<?php

			$option_name = $lab;
//    error_log('$option_name - '.$option_name);

			?>
        <div class="input-con type-<?php echo $opt['type']; ?>">
			<?php
			if($opt['type'] == 'text'){
				echo DZSHelpers::generate_input_text($lab, array(
					'class'=>'shortcode-field  dzs-dependency-field',
				));
			}
			if($opt['type'] == 'textarea_html'){
				$content = '';
				$editor_id = $lab;

				wp_editor( $content, $editor_id );
			}
			if($opt['type'] == 'upload'){

				$upload_class = 'shortcode-field upload-target-prev upload-type-'.$opt['library_type'].' ';

				if(isset($opt['prefer_id']) && $opt['prefer_id']=='on'){
					$upload_class.=' upload-get-id';
				}

				$upload_class.=' dzs-dependency-field';
				echo DZSHelpers::generate_input_text($lab, array(
					'class'=>$upload_class,
				));

				echo '<a href="#" class="button-secondary upload-for-target">'.__("Upload").'</a>';
			}
			if($opt['type'] == 'select'){
				echo DZSHelpers::generate_select($lab, array(
					'class'=>'shortcode-field dzs-style-me skin-beige dzs-dependency-field',
					'options'=>$opt['options'],
				));

			}
			?>
            </div><?php
			if(isset($opt['sidenote'])){

				?>
                <div class="sidenote"><?php echo $opt['sidenote']; ?></div>
				<?php
			}
			if(isset($opt['sidenote-2']) && $opt['sidenote-2']){


				$sidenote_2_class = '';

				if(isset($opt['sidenote-2-class'])){
					$sidenote_2_class = $opt['sidenote-2-class'];
				}
				?>            <div class="sidenote-2 <?php echo $sidenote_2_class ?>"><?php echo $opt['sidenote-2']; ?></div>
				<?php
			}
			?>


            </div><?php

			$ilab++;
		}





		$args = array(
			'for_shortcode_generator'=>true,
		);
		$fout = '';
		$fout.='<div class="dzs-tabs dzs-tabs-meta-item auto-init skin-default " data-options=\'{ "design_tabsposition" : "top"
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

'.$dzsap->sliders_admin_generate_item_meta_cat('main', null,$args).'
    </div>
    </div>
    ';


		foreach ($dzsap->item_meta_categories_lng as $lab=>$val){


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
				echo $dzsap->sliders_admin_generate_item_meta_cat($lab, null,$args);
				?>


            </div>
            </div><?php

			$fout.=ob_get_clean();



		}

		$fout.='</div>';// -- end tabs

		echo $fout;

		echo '<br>';
		echo '<button class="button-primary submit-shortcode">'.__("Submit Shortcode").'</button>';


		?><div class="shortcode-output"></div>

    </div><?php

}