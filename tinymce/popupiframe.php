<?php
// some total cache vars that needs to be like this

function dzsap_shortcode_builder()
{
	global $dzsap;


	$sample_data_installed = false;
	if($dzsap->sample_data && is_array($dzsap->sample_data)){
		$sample_data_installed = true;
	}


	$ids = '';



	for($i=0;$i<count($dzsap->sample_data['media']);$i++){

		if($i>0){
			$ids.=',';
		}


		$ids.=$dzsap->sample_data['media'][$i];
	}


	?>

    <style>
        .setting #wp-content-editor-tools{ padding-top: 0; }
        body .sidenote{ color: #777777; }
    </style>
    <script>
		<?php
		if(isset($_GET['sel'])){
			$aux = str_replace(array("\r","\r\n","\n"),'',$_GET['sel']);
			$aux = str_replace("'",'"',$aux);
			echo 'window.dzsprx_sel = \''.stripslashes($aux).'\'';



		}
		?>
        window.sg1_shortcode = '[zoomsounds_player source="http://www.stephaniequinn.com/Music/Commercial%20DEMO%20-%2011.mp3" config="skinwavewithcomments" playerid="<?php echo $dzsap->sample_data['media'][0]; ?>" waveformbg="https://lh3.googleusercontent.com/OCkCqtmYpqevOPlhNY4R8oy37CmypYXtsM6CdwstJp-2X8y4O_MdmnOOyTZ2dODVq7sfxLqoRG2H-fGJ8GAwYDp7jtiyyesUiMjIZA4czV7dDqnaw0qhpkRBpfSmqW_uOkQtGvhJUn9nYAK2MQwQ_PtCfl4uHgb1cae5n7qNC8DjRgVorBBr_gZVLg0IZFXbLW0UTp-8KsqrZSyGHAgxbh7Q40-CKFvBKxZ7KblCTfwsEun4LElkYFe5ZPZOsn1EBrxsbXrSyAZVmm0VX7UXRnEQR-5YTIzZ6ttugwYonTFNwmiGxOCsg5RyYpwTNWMLE1v2fBUsBgSStiLrnwQqrK4VAfV-irLXdfXsy6ZG174u0uPdjGJq3qw3PcJUHatmxZDC5PbSrxTHR-K6OqTOV7bM641t40ZVNZfZmjOTzzL-eDWkKCUu5q5VBm254sJ4FK63bP5QbxOQem6nPadxEayRSKfyF4z4HUnoqsR1giPk8eWI63LcgGOZeSWGVw0T27N_Ugwz37Twr5Ilyk7q66elCiyOxK7IUuiur6-QYi0=w1170-h140-no" waveformprog="https://lh3.googleusercontent.com/3ZCeepH9HAhs1ojwrMVKRW4poGaqPSbeczAAs8XjBl8E4zh0vSzXY4ou7KtRXUoMDff70qz8vEa5YLwq_4kp4ufRHcTK8_7lbs5Ux4jTETAkhluI75nUweiBYztNkwtxRggzTLnu2kdyVn3lubZGDbe4-pxyvBtz2tWauKs9fw7wiMCcrkFz5BFi_X1q7ViGA205qTfuTLjltWzom09Xm8vgt5EsTHyInFoMAeSobImMrG5j67VTgrX_9vYDNu3RE_TbISRY9c7wdEXOplQZXJDHH3c86rdVaoclhGAbli3mHJ92iZmGrZM1JH0glyj-ymSSq8RU1Tw2Slb1QFYEwzJpr_wOR9BqqccLAf-yLawNG5TqTQLhrYekNfPaWEtUrcYvHMDeg2R_x7zZg0Q_FI4qvUjBrTu8ClZIf_fml4mer7KEl3uhNEDNr7pe9suucRGO_f_whT8bqjFsRCvh9obFhvj0Suvc-SNFTeLavV6EwIqFVYdHCwyedHxdmOGTsruvXw3CRqon0UFb2jqR2GO6ZUSQ9k9emXdGCZAVzqY=w1170-h140-no" thumb="" autoplay="on" cue="on" enable_likes="off" enable_views="off" songname="Track 1 from stephaniequinn.com" artistname="Steph"]';
        window.sg3_shortcode = '[zoomsounds_player source="http://www.stephaniequinn.com/Music/Commercial%20DEMO%20-%2011.mp3" config="example-skin-aria" playerid="<?php echo $dzsap->sample_data['media'][0]; ?>" waveformbg="https://lh3.googleusercontent.com/OCkCqtmYpqevOPlhNY4R8oy37CmypYXtsM6CdwstJp-2X8y4O_MdmnOOyTZ2dODVq7sfxLqoRG2H-fGJ8GAwYDp7jtiyyesUiMjIZA4czV7dDqnaw0qhpkRBpfSmqW_uOkQtGvhJUn9nYAK2MQwQ_PtCfl4uHgb1cae5n7qNC8DjRgVorBBr_gZVLg0IZFXbLW0UTp-8KsqrZSyGHAgxbh7Q40-CKFvBKxZ7KblCTfwsEun4LElkYFe5ZPZOsn1EBrxsbXrSyAZVmm0VX7UXRnEQR-5YTIzZ6ttugwYonTFNwmiGxOCsg5RyYpwTNWMLE1v2fBUsBgSStiLrnwQqrK4VAfV-irLXdfXsy6ZG174u0uPdjGJq3qw3PcJUHatmxZDC5PbSrxTHR-K6OqTOV7bM641t40ZVNZfZmjOTzzL-eDWkKCUu5q5VBm254sJ4FK63bP5QbxOQem6nPadxEayRSKfyF4z4HUnoqsR1giPk8eWI63LcgGOZeSWGVw0T27N_Ugwz37Twr5Ilyk7q66elCiyOxK7IUuiur6-QYi0=w1170-h140-no" waveformprog="https://lh3.googleusercontent.com/3ZCeepH9HAhs1ojwrMVKRW4poGaqPSbeczAAs8XjBl8E4zh0vSzXY4ou7KtRXUoMDff70qz8vEa5YLwq_4kp4ufRHcTK8_7lbs5Ux4jTETAkhluI75nUweiBYztNkwtxRggzTLnu2kdyVn3lubZGDbe4-pxyvBtz2tWauKs9fw7wiMCcrkFz5BFi_X1q7ViGA205qTfuTLjltWzom09Xm8vgt5EsTHyInFoMAeSobImMrG5j67VTgrX_9vYDNu3RE_TbISRY9c7wdEXOplQZXJDHH3c86rdVaoclhGAbli3mHJ92iZmGrZM1JH0glyj-ymSSq8RU1Tw2Slb1QFYEwzJpr_wOR9BqqccLAf-yLawNG5TqTQLhrYekNfPaWEtUrcYvHMDeg2R_x7zZg0Q_FI4qvUjBrTu8ClZIf_fml4mer7KEl3uhNEDNr7pe9suucRGO_f_whT8bqjFsRCvh9obFhvj0Suvc-SNFTeLavV6EwIqFVYdHCwyedHxdmOGTsruvXw3CRqon0UFb2jqR2GO6ZUSQ9k9emXdGCZAVzqY=w1170-h140-no" thumb="" autoplay="on" cue="on" enable_likes="off" enable_views="off" songname="Track 1 from stephaniequinn.com" artistname="Steph"]';
        window.sg2_shortcode = '[dzsap_woo_grid type="attachment" style="style1" faketarget=".dzsap_footer" ids="<?php echo $ids; ?>" ]';
    </script>
    <style>
        #dzsap-shortcode-tabs .tab-menu-con.is-always-active .tab-menu{
            padding-left: 15px;

        }
        #dzsap-shortcode-tabs .tab-menu-con.is-always-active .tab-menu:before{
            display: none;;
        }
    </style>
    <div class="wrap <?php
	if($sample_data_installed){
		echo 'sample-data-installed';
	}
	?>">
        <h3>ZoomSounds <?php echo __(" Shortcode Generator"); ?></h3>


        <div id="dzsap-shortcode-tabs" class="dzs-tabs auto-init skin-box" data-options="{ 'design_tabsposition' : 'top'
                ,design_transition: 'fade'
                ,design_tabswidth: 'fullwidth'
                ,toggle_breakpoint : '4000'
                 ,toggle_type: 'toggle'
                 ,settings_appendWholeContent: true
                 }">

            <div class="dzs-tab-tobe">
                <div class="tab-menu with-tooltip">
                    Generate Sample Shortcode
                </div>
                <div class="tab-content">
                    <p>You can generate an example from the preview</p>
					<?php
					if($sample_data_installed===false){
						echo '<p><button id="" class="button-secondary insert-sample-tracks">Insert Sample Data</button></p>';
					}else{

						echo '<p><button id="" class="button-secondary remove-sample-tracks">Remove Sample Data</button></p>';
					}
					?>


                    <div class="dzspb_lay_row shortcode-generator-cols">

                        <div class="dzspb_layb_one_third">
							<?php
							echo '<img class="fullwidth" src="'.$dzsap->thepath.'tinymce/img/sg1.png"/>';
							?>
                            <h3><?php echo __('Player with Wave and Comments'); ?></h3>
                            <p><button class="button-primary sg-1"<?php
								if($sample_data_installed===false){
									echo 'disabled';
								}
								?>><?php echo __('Insert Shortcode'); ?></button></p>
                            <p class="sidenote sidenote-for-sample-data-not-installed"><?php echo __('Install sample data first, to generate this example'); ?></p>
                        </div>
                        <div class="dzspb_layb_one_third">
							<?php
							echo '<img  class="fullwidth" src="'.$dzsap->thepath.'tinymce/img/sg2.png"/>';
							?>

                            <h3><?php echo __('Bottom Player with Grid Display'); ?></h3>
                            <p><button class="button-primary sg-2"<?php
								if($sample_data_installed===false){
									echo 'disabled';
								}
								?>><?php echo __('Insert Shortcode'); ?></button></p>
                            <p class="sidenote sidenote-for-sample-data-not-installed"><?php echo __('Install sample data first, to generate this example'); ?></p>
                        </div>
                        <div class="dzspb_layb_one_third">
							<?php
							echo '<img class="fullwidth" src="'.$dzsap->thepath.'tinymce/img/sg3.png"/>';
							?>

                            <h3><?php echo __('Audio Player with Custom Skin'); ?></h3>
                            <p><button class="button-primary sg-3"<?php
								if($sample_data_installed===false){
									echo 'disabled';
								}
								?>><?php echo __('Insert Shortcode'); ?></button></p>
                            <p class="sidenote sidenote-for-sample-data-not-installed"><?php echo __('Install sample data first, to generate this example'); ?></p>
                        </div>
                    </div>
                </div>
            </div>



            <div class="dzs-tab-tobe active is-always-active">
                <div class="tab-menu with-tooltip">
                    Custom Shortcode
                </div>
                <div class="tab-content">
                    <div class="sc-menu">
                        <div class="setting type_any">
                            <h4><?php echo esc_html__("Select a Gallery to Insert",'dzsap'); ?></h4>
                            <select class="styleme" name="dzsap_selectid">
								<?php

								$dzsap->db_read_mainitems();


								if($dzsap->mainoptions['playlists_mode']=='normal'){

									foreach ($dzsap->mainitems as $mainitem) {
										echo '<option value="'.$mainitem['value'].'">'.$mainitem['label'].'</option>';
									}
								}else{

									foreach ($dzsap->mainitems as $mainitem) {
										echo '<option>' . ($mainitem['settings']['id']) . '</option>';
									}
								}
								?>
                            </select>

                            <h4><?php echo esc_html__("Force Width",'dzsap'); ?></h4>
                            <input class="textinput" name="width"/>


                            <h4><?php echo esc_html__("Force Height",'dzsap'); ?></h4>
                            <input class="textinput" name="height"/>
                        </div>
                        <!--
                        <div class="setting type_any">
                            <h3>Select a Pagination Method</h3>
                            <select class="styleme" name="ddzsap_settings_separation_mode">
                                <option>normal</option>
                                <option>pages</option>
                                <option>scroll</option>
                                <option>button</option>
                            </select>
                            <div class="sidenote">Useful if you have many videos and you want to separate them somehow.</div>
                        </div>
                        <div class="setting type_any">
                            <h3>Select Number of Items per Page</h3>
                            <input name="ddzsap_settings_separation_pages_number" value="5"/>
                            <div class="sidenote">Useful if you have many videos and you want to separate them somehow.</div>
                        </div>
                        -->
                        <div class="clear"></div>
                        <br/>
                        <br/>
                        <button id="insert_tests" class="button-primary">Insert Gallery</button>
                    </div>
                </div>
            </div>

        </div>



        <div class="shortcode-output"></div>

        <div class="bottom-right-buttons">

            <button id="" class="button-secondary insert-sample-library"><?php echo __("One Click Install Example"); ?></button>
            <span style="font-size: 11px; opacity: 0.5;"><?php echo __("OR", 'dzsvg'); ?></span>
            <button id="insert_tests" class="button-primary insert-tests"><?php echo __("Insert Gallery"); ?></button>
        </div>




        <div id="import-sample-lib" class="show-in-ultibox">
			<?php

			echo '<h3>'.__("Import Demo",'dzsap').'</h3>';


			$args = array(
				'featured_image' => $dzsap->base_url.'img/sample_gallery_1.jpg',
				'title' => 'Sample Gallery',
				'demo-slug' => 'sample-gallery-1',
			)    ;

			dzsap_generate_example_lib_item($args);


			$args = array(
				'featured_image' => $dzsap->base_url.'img/sample_grid_style_1.jpg',
				'title' => 'Grid Style 1',
				'demo-slug' => 'sample_grid_style_1',
			)    ;

			dzsap_generate_example_lib_item($args);


			$args = array(
				'featured_image' => $dzsap->base_url.'img/sample_soundcloud_gallery_just_thumbs.jpg',
				'title' => 'Soundcloud Thumbnail Grid',
				'demo-slug' => 'sample_soundcloud_gallery_just_thumbs',
			)    ;



			dzsap_generate_example_lib_item($args);


			$args = array(
				'featured_image' => $dzsap->base_url.'img/sample_player_with_buttons.jpg',
				'title' => 'Player with Buttons',
				'demo-slug' => 'sample_player_with_buttons',
			)    ;



			dzsap_generate_example_lib_item($args);


			$args = array(
				'featured_image' => $dzsap->base_url.'assets/sampledata_img/single_player_wrapper.jpg',
				'title' => 'Single player with rectangle',
				'demo-slug' => 'single_player_wrapper',
			);



			dzsap_generate_example_lib_item($args);





			$args = array(
				'featured_image' => $dzsap->base_url.'assets/sampledata_img/single_wave_and_single_jtap.jpg',
				'title' => 'Two players',
				'demo-slug' => 'single_wave_and_single_jtap',
			);



			dzsap_generate_example_lib_item($args);



			$lab = 'small_play_and_pause';


			$args = array(
				'featured_image' => $dzsap->base_url.'assets/sampledata_img/'.$lab.'.jpg',
				'title' => 'Small play and pause controls',
				'demo-slug' => $lab,
			);



			dzsap_generate_example_lib_item($args);



			$lab = 'consecutive_player';


			$args = array(
				'featured_image' => $dzsap->base_url.'assets/sampledata_img/'.$lab.'.jpg',
				'title' => 'Consecutive player',
				'demo-slug' => $lab,
			);



			dzsap_generate_example_lib_item($args);








			?>
        </div>




    </div><?php
}








$dzsap_example_lib_index = 0;
function dzsap_generate_example_lib_item($pargs){
	global $dzsap_example_lib_index,$dzsap;

	$margs = array(
		'featured_image' => '',
		'title' => '',
		'demo-slug' => '',
	);


	$margs = array_merge($margs, $pargs);

	if($dzsap_example_lib_index%3==0){
		echo '<div class="dzs-row">';

	}


	?><div class="dzs-col-md-4">
    <div class="lib-item <?php


	if($dzsap->mainoptions['dzsap_purchase_code_binded']=='on'){

	}else{

		echo ' dzstooltip-con';

		echo ' disabled';
	}


	?>" data-demo="<?php echo $margs['demo-slug']; ?>"><?php


		if($dzsap->mainoptions['dzsap_purchase_code_binded']=='on'){

		}else{

			?><div class=" dzstooltip skin-black arrow-bottom align-left">
			<?php echo __("You need to activate zoomsounds with purchase code before importing demos");
			?>
            </div>
			<?php
		}


		?>
        <i class="fa  fa-lock lock-icon"></i>
        <div class="loading-overlay">
            <i class="fa fa-spin fa-circle-o-notch loading-icon"></i>
        </div>
        <div class="divimage" style="background-image:url(<?php echo $margs['featured_image']; ?>); "></div>
        <h5><?php echo $margs['title'];; ?></h5>

    </div>

    </div><?php





	if($dzsap_example_lib_index%3==2){

		echo '</div>';
	}


	$dzsap_example_lib_index++;




}