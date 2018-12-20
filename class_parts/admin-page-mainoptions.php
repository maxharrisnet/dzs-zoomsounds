<?php

//print_r($this->db_mainoptions);


//        print_r($this->db_mainoptions);
?>

    <div class="wrap">
        <h2><?php echo __('ZoomSounds Main Settings', 'dzsap'); ?></h2>
        <br/>

        <a class="zoombox button-secondary" href="<?php echo $this->thepath; ?>readme/index.html"
           data-bigwidth="1100" data-scaling="fill"
           data-bigheight="700"><?php echo __("Documentation"); ?></a>

        <a href="<?php echo admin_url('admin.php?page='.$this->page_mainoptions_link.'&dzsap_shortcode_builder=on'); ?>" target="_blank"
           class="button-secondary action"><?php _e('Gallery Generator', 'dzsap'); ?></a>

        <a href="<?php echo admin_url('admin.php?page='.$this->page_mainoptions_link.'&dzsap_shortcode_player_builder=on'); ?>" target="_blank"
           class="button-secondary action"><?php _e('Player Generator', 'dzsap'); ?></a>


		<?php
		do_action('dzsap_mainoptions_before_tabs');
		?>

        <form class="mainsettings">





            <div class="dzs-tabs auto-init" data-options="{ 'design_tabsposition' : 'top'
,design_transition: 'fade'
,design_tabswidth: 'default'
,toggle_breakpoint : '400'
,toggle_type: 'accordion'
,toggle_type: 'accordion'
,settings_enable_linking : 'on'
,settings_appendWholeContent: true
,refresh_tab_height: '1000'
}">

                <div class="dzs-tab-tobe">
                    <div class="tab-menu with-tooltip">
                        <i class="fa fa-tachometer"></i> <?php echo __("Settings"); ?>
                    </div>
                    <div class="tab-content">
                        <br>



                        <!-- general settings tab content -->


                        <div class="setting">
                            <h4 class="label"><?php echo __('Playlists Mode','dzsap'); ?></h4>
							<?php



							$lab = 'playlists_mode';



							echo DZSHelpers::generate_select($lab,array('class' => 'vpconfig-select styleme','options' => array(
								array(
									'label'=>esc_html__("Normal",'dzsap'),
									'value'=>'normal',
								),
								array(
									'label'=>esc_html__("Legacy",'dzsap'),
									'value'=>'legacy',
								),
							),'seekval' => $this->mainoptions[$lab])); ?>

                            <div class="edit-link-con" style="margin-top: 10px;"></div>

                            <div class="sidenote"><?php echo __("this will output the footer player on the whole site."); ?></div>
                        </div>





                        <div class="setting">
                            <h4 class="label"><?php echo __('Enable Autobackup', 'dzsap'); ?></h4>
							<?php
							$lab = 'enable_auto_backup';
							echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo sprintf(__('enable auto backup % backups will be in %s folder', 'dzsap'), '/','wp-content/dzsap_backups'); ?></div>
                        </div>

                        <div class="setting">
                            <h4 class="label"><?php echo __('Activate Comments Widget', 'dzsap'); ?></h4>
							<?php
							$lab = 'activate_comments_widget';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('comments widget in the wordpress dashboard', 'dzsap'); ?></div>
                        </div>







                        <div class="setting">

							<?php
							$lab = 'download_link_links_directly_to_file';



							echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));
							?>




                            <h4 class="label"><?php echo __('Download Link links directly to file', 'dzsap'); ?></h4>
							<?php
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div class="sidenote"><?php echo __('activate debug mode ( advanced mode )', 'dzsap'); ?></div>

                        </div>



                        <div class="setting">

							<?php
							$lab = 'force_autoplay_when_coming_from_share_link';
							?>

                            <h4 class="label"><?php echo __('Force Autoplay When Coming From Shared Link', 'dzsap'); ?></h4>
							<?php
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div class="sidenote"><?php echo __('when your users click on the shared link, this will force autoplay for them', 'dzsap'); ?></div>

                        </div>





                        <div class="setting">
                            <h4 class="label"><?php echo __('Use WordPress Database to Store Track Data', 'dzsap'); ?></h4>
							<?php $lab = 'wpdb_enable';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('Use WordPress Database to Store Track Data', 'dzsap'); ?></div>
                        </div>



                        <div class="setting">
                            <h4 class="label"><?php echo __('Track Downloads', 'dzsap'); ?></h4>
							<?php $lab = 'track_downloads';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('create table for tracking views / downloads / etc.', 'dzsap'); ?></div>
                        </div>


						<?php

						$lab = 'mobile_disable_footer_player';
						echo DZSHelpers::generate_input_text($lab,array('id' => $lab,'input_type' => 'hidden','class' => 'mainsetting', 'val' => 'off'))
						?>
                        <div class="setting">
                            <h4 class="label"><?php echo __('Disable Footer Player in Mobile', 'dzsap'); ?></h4>
							<?php $lab = 'mobile_disable_footer_player';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('disable the footer player on mobile', 'dzsap'); ?></div>
                        </div>


                        <div class="setting">
                            <h4 class="label"><?php echo __('Always Embed Scripts?', 'dzsap'); ?></h4>
							<?php
							$lab = 'always_embed';
							echo '<div class="dzscheckbox skin-nova">
 '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).' <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('by default scripts and styles from this gallery are included only when needed for optimizations reasons, but you can choose to always use them ( useful for when you are using a ajax theme that does not reload the whole page on url change )', 'dzsap'); ?></div>
                        </div>


                        <div class="setting">
                            <h4 class="label"><?php echo __('Disable audio item indexing', 'dzsap'); ?></h4>
							<?php
							$lab = 'single_index_seo_disable';
							echo '<div class="dzscheckbox skin-nova">
 '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).' <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('disable google indexing on audio item page', 'dzsap'); ?></div>
                        </div>


                        <div class="setting">
                            <h4 class="label"><?php echo __('Loop playlist ?', 'dzsap'); ?></h4>
							<?php
							$lab = 'loop_playlist';
							echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));
							echo '<div class="dzscheckbox skin-nova">
 '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).' <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('loop the playlist after reaching end', 'dzsap'); ?></div>
                        </div>


                        <div class="setting">
                            <h4 class="label"><?php echo __('Try to get id3 thumbnail in frontend', 'dzsap'); ?></h4>
							<?php
							$lab = 'try_to_get_id3_thumb_in_frontend';
							echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));
							echo '<div class="dzscheckbox skin-nova">
 '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).' <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('thumbnail for tracks that have id3 will autogenerate in the backend. if you want to generate in the fronend too check this', 'dzsap'); ?></div>
                        </div>






                        <div class="setting">
                            <h4 class="label"><?php echo __('Enable Global Footer Player','dzsap'); ?></h4>
							<?php



							$lab = 'enable_global_footer_player';

							$vpconfigs_arr = array(
								array('lab'=>__("Off"), 'val'=>'off')
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

							echo DZSHelpers::generate_select($lab,array('class' => 'vpconfig-select styleme','options' => $vpconfigs_arr,'seekval' => $this->mainoptions[$lab])); ?>

                            <div class="edit-link-con" style="margin-top: 10px;"></div>

                            <div class="sidenote"><?php echo __("this will output the footer player on the whole site."); ?></div>
                        </div>


                        <div class="setting">
                            <h4 class="label"><?php echo __('Waveform Mode','dzsap'); ?></h4>
							<?php



							$lab = 'skinwave_wave_mode';

							$opts = array(
								array(
									'lab'=>__("Image"),
									'val'=>'image'
								),
								array(
									'lab'=>__("Canvas"),
									'val'=>'canvas'
								),
								array(
									'lab'=>__("Line"),
									'val'=>'line'
								),
							);


							echo DZSHelpers::generate_select($lab,array('class' => ' dzs-dependency-field  styleme','options' => $opts,'seekval' => $this->mainoptions[$lab])); ?>


                            <div class="sidenote"><?php echo __("this is the wave style "). '<br>'; printf("<strong> %s </strong> - %s <br>", __("Image"), __("is just a image png that must be generated from the backend")  );  echo sprintf("<strong> %s </strong> - %s <br>", __("Canvas"), __("is a new and more immersive mode to show the waves. you can control color more easily, reflection size and wave bars number"));  ?></div>
                        </div>


						<?php

						$dependency = array(

							array(
								'element'=>'skinwave_wave_mode',
								'value'=>array('canvas'),
							),
						)
						;
						?>
                        <div class="setting" data-dependency='<?php echo json_encode($dependency); ?>'>
                            <h4 class="label"><?php echo __('Reflection Size','dzsap'); ?></h4>
							<?php



							$lab = 'skinwave_wave_mode_canvas_reflection_size';

							$opts = array(
								array(
									'lab'=>__("None"),
									'val'=>'0'
								),
								array(
									'lab'=>__("Normal"),
									'val'=>'0.25'
								),
								array(
									'lab'=>__("Big"),
									'val'=>'0.5'
								),
							);


							echo DZSHelpers::generate_select($lab,array('class' => ' styleme','options' => $opts,'seekval' => $this->mainoptions[$lab])); ?>


                            <div class="sidenote"><?php echo __("the waveform bars size / the number of bars on screen"). '';  ?></div>
                        </div>


						<?php

						$dependency = array(

							array(
								'element'=>'skinwave_wave_mode',
								'value'=>array('canvas'),
							),
						)
						;
						?>
                        <div class="setting" data-dependency='<?php echo json_encode($dependency); ?>'>
                            <h4 class="label"><?php echo __('Waves Number','dzsap'); ?></h4>
							<?php




							$lab = 'skinwave_wave_mode_canvas_waves_number';

							echo DZSHelpers::generate_input_text($lab,array(
								'class' => 'disabled ',
								'type' => 'slider',
								'slider_min'=>'0',
								'slider_max'=>'300',
								'seekval' => $this->mainoptions[$lab]
							)); ?>


                            <div class="sidenote"><?php echo sprintf(__("%s - %s pixel %s
                            %s - %s pixels %s
                            %s - %s pixels %s
                            %s - means that there will be the number of waves that you set - for example 100 means 100 waves
                            ")
										,'<strong>1</strong>'
										,'1'
										,'<br>'
										,'<strong>2</strong>'
										,'2'
										,'<br>'
										,'<strong>3</strong>'
										,'3'
										,'<br>'
										,'<strong>any number over 3 - </strong>'

							                                 ). '';  ?></div>
                        </div>
                        <style>.disabled{
                                pointer-events:auto;
                            }</style>
                        <div class="setting" data-dependency='<?php echo json_encode($dependency); ?>'>
                            <h4 class="label"><?php echo __('Waves Bar Spacing','dzsap'); ?></h4>
							<?php




							$lab = 'skinwave_wave_mode_canvas_waves_padding';

							echo DZSHelpers::generate_input_text($lab,array(
								'class' => 'disabled ',
								'type' => 'slider',
								'slider_min'=>'0',
								'slider_max'=>'4',
								'seekval' => $this->mainoptions[$lab]
							)); ?>


                            <div class="sidenote"><?php echo __("spacing betweekn bars"). '';  ?></div>
                        </div>







						<?php

						$dependency = array(

							array(
								'element'=>'skinwave_wave_mode',
								'value'=>array('canvas'),
							),
						)
						;
						?>
                        <div class="setting" data-dependency='<?php echo json_encode($dependency); ?>'>
                            <h4 class="label"><?php echo __('Normalize ','dzsap'); ?></h4>
							<?php



							$lab = 'skinwave_wave_mode_canvas_normalize';

							$opts = array(
								array(
									'lab'=>__("Normalize Waves"),
									'val'=>'on'
								),
								array(
									'lab'=>__("Do not normalize"),
									'val'=>'off'
								),

							);


							echo DZSHelpers::generate_select($lab,array('class' => ' styleme','options' => $opts,'seekval' => $this->mainoptions[$lab])); ?>


                            <div class="sidenote"><?php echo __("normalize the waves to look like they have continuity , or disable normalizing to make the waveforms follow the real sound"). '';  ?></div>
                        </div>






                        <div class="setting" >
                            <h4 class="label"><?php echo __('Allow Download Only for Registered Users ','dzsap'); ?></h4>
							<?php



							$lab = 'allow_download_only_for_registered_users';

							$opts = array(
								array(
									'lab'=>__("Off"),
									'val'=>'off'
								),
								array(
									'lab'=>__("On"),
									'val'=>'on'
								),

							);


							echo DZSHelpers::generate_select($lab,array(
								'class' => ' styleme dzs-dependency-field',
								'options' => $opts,
								'seekval' => $this->mainoptions[$lab]));
							?>


                            <div class="sidenote"><?php echo __("allow the download tab only for registered users"). '';  ?></div>
                        </div>









						<?php

						$dependency = array(

							array(
								'element'=>'allow_download_only_for_registered_users',
								'value'=>array('on'),
							),
						)
						;
						?>
                        <div class="setting" data-dependency='<?php echo json_encode($dependency); ?>'>
                            <h4 class="label"><?php echo __('Allow Download Only for Registered Users ','dzsap'); ?></h4>
							<?php



							$lab = 'allow_download_only_for_registered_users_capability';

							$opts = array(
								array(
									'lab'=>__("Subscriber"),
									'val'=>'read'
								),
								array(
									'lab'=>__("Contributor"),
									'val'=>'edit_posts'
								),
								array(
									'lab'=>__("Author"),
									'val'=>'edit_published_posts'
								),

							);


							echo DZSHelpers::generate_select($lab,array(
								'class' => ' styleme ',
								'options' => $opts,
								'seekval' => $this->mainoptions[$lab]));

							?>


                            <div class="sidenote"><?php echo __("select a class to restrict downloads too"). '';  ?></div>
                        </div>







                        <div class="setting">
                            <h4 class="label"><?php echo __('SoundCloud API Key', 'dzsap'); ?></h4>
							<?php
							$val = '';
							if ($this->mainoptions['soundcloud_api_key']) {
								$val = $this->mainoptions['soundcloud_api_key'];
							}
							echo DZSHelpers::generate_input_text('soundcloud_api_key', array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
							?>
                            <div
                                    class="sidenote"><?php echo __('You can get one by going to <a href="http://soundcloud.com/you/apps/new">here</a> and registering a new app. The api key wil lbe the client ID you get at the end.', 'dzsap'); ?></div>
                        </div>

                        <div class="setting">
                            <h4 class="label"><?php echo __('Play Remember Time', 'dzsap'); ?></h4>
							<?php
							$lab = 'play_remember_time';
							$val = '';
							if ($this->mainoptions[$lab]) {
								$val = $this->mainoptions[$lab];
							}
							echo DZSHelpers::generate_input_text($lab, array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
							?>
                            <div
                                    class="sidenote"><?php echo __('plays are regitered by ip - you can specify a time ( in minutes ) at which plays are remembers. after this time - a new play can be registered for the same ip', 'dzsap'); ?></div>
                        </div>
                        <div class="setting">
                            <h4 class="label"><?php echo __('Like Markup Part 1', 'dzsap'); ?></h4>
							<?php
							$val = '';
							$lab = 'str_likes_part1';
							if ($this->mainoptions[$lab]) {
								$val = stripslashes($this->mainoptions[$lab]);
							}
							echo $this->misc_input_textarea($lab, array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
							?>
                            <div class="sidenote"><?php echo __('You can translate here.', 'dzsap'); ?></div>
                        </div>
                        <div class="setting">
                            <h4 class="label"><?php echo __('Plays Markup', 'dzsap'); ?></h4>
							<?php
							$val = '';
							$lab = 'str_views';
							if ($this->mainoptions[$lab]) {
								$val = stripslashes($this->mainoptions[$lab]);
							}
							echo $this->misc_input_textarea($lab, array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
							?>
                            <div class="sidenote"><?php echo __('You can translate here.', 'dzsap'); ?></div>
                        </div>
                        <div class="setting">
                            <h4 class="label"><?php echo __('Like Markup Part 2', 'dzsap'); ?></h4>
							<?php
							$val = '';
							$lab = 'str_likes_part2';
							if ($this->mainoptions[$lab]) {
								$val = stripslashes($this->mainoptions[$lab]);
							}
							echo $this->misc_input_textarea($lab, array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
							?>
                            <div class="sidenote"><?php echo __('You can translate here.', 'dzsap'); ?></div>
                        </div>
                        <div class="setting">
                            <h4 class="label"><?php echo __('Rates Markup', 'dzsap'); ?></h4>
							<?php
							$val = '';
							$lab = 'str_rates';
							if ($this->mainoptions[$lab]) {
								$val = stripslashes($this->mainoptions[$lab]);
							}
							echo $this->misc_input_textarea($lab, array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
							?>
                            <div class="sidenote"><?php echo __('You can translate here.', 'dzsap'); ?></div>
                        </div>






                        <!-- end general settings -->


                    </div>
                </div>

                <div class="dzs-tab-tobe tab-disabled">
                    <div class="tab-menu ">
                        &nbsp;&nbsp;
                    </div>
                    <div class="tab-content">

                    </div>
                </div>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu with-tooltip">
                        <i class="fa fa-shopping-cart"></i> <?php echo __("Single audio page") ?>
                    </div>
                    <div class="tab-content">
                        <br>

                        <div class="setting">
                            <h4 class="label"><?php echo __('Single Product ZoomSounds Preview - Optional shortcode','dzsap'); ?></h4>
							<?php



							$lab = 'dzsapp_player_shortcode';



							echo DZSHelpers::generate_input_textarea($lab,array('class' => ' ','seekval' => $this->mainoptions[$lab])); ?>



                            <div class="sidenote"><?php echo esc_html__("you can input here shortcode to replace the main player in woocommerce product ie -",'dzsap'); ?><br><pre style="white-space: pre-line;">[zoomsounds_player type="detect" dzsap_meta_source_attachment_id="{{postid}}" source="{{source}}" thumb="{{thumb}}" config="skinwavewithcomments" autoplay="off" loop="off" open_in_ultibox="off" enable_likes="off" enable_views="on" play_in_footer_player="on" enable_download_button="off" download_custom_link_enable="off"]</pre></div>
                        </div>

                    </div>
                </div>

                <div class="dzs-tab-tobe tab-disabled">
                    <div class="tab-menu ">
                        &nbsp;&nbsp;
                    </div>
                    <div class="tab-content">

                    </div>
                </div>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu with-tooltip">
                        <i class="fa fa-shopping-cart"></i> <?php echo __("WooCommerce Loops") ?>
                    </div>
                    <div class="tab-content">
                        <br>






                        <div class="setting">
                            <h4 class="label"><?php echo __('Single Product ZoomSounds Preview','dzsap'); ?></h4>
							<?php



							$lab = 'wc_single_product_player';



							echo DZSHelpers::generate_select($lab,array('class' => 'vpconfig-select styleme','options' => $vpconfigs_arr,'seekval' => $this->mainoptions[$lab])); ?>

                            <div class="edit-link-con" style="margin-top: 10px;"></div>

                            <div class="sidenote"><?php echo __("this will output a preview player in the woocommerce product page if a track is set in the zoomsounds settings of the product."); ?></div>
                        </div>

                        <div class="setting">
                            <h4 class="label"><?php echo __('Single Product ZoomSounds Preview - Optional shortcode','dzsap'); ?></h4>
							<?php



							$lab = 'wc_single_product_player_shortcode';



							echo DZSHelpers::generate_input_textarea($lab,array('class' => ' ','seekval' => $this->mainoptions[$lab])); ?>



                            <div class="sidenote"><?php echo esc_html__("you can input here shortcode to replace the main player in woocommerce product ie -",'dzsap'); ?><br><pre style="white-space: pre-line;">[zoomsounds_player type="detect" dzsap_meta_source_attachment_id="{{postid}}" source="{{source}}" thumb="{{thumb}}" config="skinwavewithcomments" autoplay="off" loop="off" open_in_ultibox="off" enable_likes="off" enable_views="on" play_in_footer_player="on" enable_download_button="off" download_custom_link_enable="off"]</pre></div>
                        </div>





                        <div class="setting">
                            <h4 class="label"><?php echo __('Loop Product ZoomSounds Preview','dzsap'); ?></h4>
							<?php



							$lab = 'wc_loop_product_player';



							echo DZSHelpers::generate_select($lab,array('class' => ' styleme','options' => $vpconfigs_arr,'seekval' => $this->mainoptions[$lab])); ?>

                            <div class="edit-link-con" style="margin-top: 10px;"></div>

                            <div class="sidenote"><?php echo __("this will output a preview player in the woocommerce shop page if a track is set in the zoomsounds settings of the product."); ?></div>
                        </div>





                        <div class="setting">
                            <h4 class="label"><?php echo __('Play in Sticky Player ? ','dzsap'); ?></h4>
							<?php



							$lab = 'wc_product_play_in_footer';



							echo DZSHelpers::generate_select($lab,array('class' => ' styleme','options' => array(
								array(
									'label'=>__("Off"),
									'value'=>'off',
								),
								array(
									'label'=>__("On"),
									'value'=>'on',
								),
							),'seekval' => $this->mainoptions[$lab])); ?>

                            <div class="edit-link-con" style="margin-top: 10px;"></div>

                            <div class="sidenote"><?php echo __("this will output a preview player in the woocommerce shop page if a track is set in the zoomsounds settings of the product."); ?></div>
                        </div>



                        <div class="setting">
                            <h4 class="label"><?php echo __('Try to hide real url','dzsap'); ?> (beta)</h4>
							<?php



							$lab = 'try_to_hide_url';



							echo DZSHelpers::generate_select($lab,array('class' => 'styleme','options' => array(
								array(
									'label'=>__("Off"),
									'value'=>'off',
								),
								array(
									'label'=>__("On"),
									'value'=>'on',
								),
							),'seekval' => $this->mainoptions[$lab])); ?>



                            <div class="sidenote"><?php echo __("( beta ) try to hide real url and deny access for direct download - will cause problems in seeking the mp3 progress"); ?></div>
                        </div>


                        <div class="setting">
                            <h4 class="label"><?php echo __('Samples Times Reflect','dzsap'); ?></h4>
							<?php



							$lab = 'sample_time_pseudo';



							echo DZSHelpers::generate_select($lab,array('class' => 'styleme','options' => array(
								array(
									'label'=>__("Part of Real Track"),
									'value'=>'',
								),
								array(
									'label'=>__("Part of Preview Track"),
									'value'=>'pseudo',
								),
							),'seekval' => $this->mainoptions[$lab])); ?>



                            <div class="sidenote"><?php echo __("this controls wheter the sample time start / end reflect the part of the real track or the preview track"); ?></div>
                        </div>





                        <div class="setting">
                            <h4 class="label"><?php echo __('Product Player Position','dzsap'); ?></h4>
							<?php



							$lab = 'wc_single_player_position';



							echo DZSHelpers::generate_select($lab,array('class' => 'vpconfig-select styleme','options' => array(
								array(
									'label'=>__("Top of product"),
									'value'=>'top',
								),
								array(
									'label'=>__("Overlay product image"),
									'value'=>'overlay',
								),
								array(
									'label'=>__("Bellow product"),
									'value'=>'bellow',
								),
							),'seekval' => $this->mainoptions[$lab])); ?>

                            <div class="edit-link-con" style="margin-top: 10px;"></div>

                            <div class="sidenote"><?php echo __("this will output a preview player in the woocommerce single product page if a track is set in the zoomsounds settings of the product."); ?></div>
                        </div>





                        <div class="setting">
                            <h4 class="label"><?php echo __('Product Loop Position','dzsap'); ?></h4>
							<?php



							$lab = 'wc_loop_player_position';



							echo DZSHelpers::generate_select($lab,array('class' => 'vpconfig-select styleme','options' => array(
								array(
									'label'=>__("Top of product"),
									'value'=>'top',
								),
								array(
									'label'=>__("Overlay product image"),
									'value'=>'overlay',
								),
								array(
									'label'=>__("Below product"),
									'value'=>'bellow',
								),
							),'seekval' => $this->mainoptions[$lab])); ?>

                            <div class="edit-link-con" style="margin-top: 10px;"></div>

                            <div class="sidenote"><?php echo __("this will output a preview player in the woocommerce shop page if a track is set in the zoomsounds settings of the product."); ?></div>
                        </div>


                    </div>

                </div>



                <div class="dzs-tab-tobe tab-disabled">
                    <div class="tab-menu ">
                        &nbsp;&nbsp;
                    </div>
                    <div class="tab-content">

                    </div>
                </div>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu with-tooltip">
                        <i class="fa fa-flag"></i> <?php echo __("Translate") ?>
                    </div>
                    <div class="tab-content">
                        <br>



                        <div class="sidenote"><?php echo __("Note that integral translation of the plugin can be done by installing the WPML plugin. Or by using PO Edit and modifying the core wordpress language. We provide next only a few strings to be translated, for convenience:"); ?></div>

						<?php
						$lab = 'i18n_buy';

						echo '
                <div class="setting">
                    <h4 class="label">' . __('Translate "Buy"', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array( 'seekval' => $this->mainoptions[$lab])) . '
                </h4>';

						$lab = 'i18n_title';

						echo '
                <div class="setting">
                    <h4 class="label">' . __('Translate "Title"', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array( 'seekval' => $this->mainoptions[$lab])) . '
                </h4>';




						$lab = 'i18n_play';

						echo '
                <div class="setting">
                    <h4 class="label">' . __('Translate "Play"', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array( 'seekval' => $this->mainoptions[$lab])) . '
                </h4>';
						$lab = 'i18n_free_download';

						echo '
                <div class="setting">
                    <h4 class="label">' . __('Translate "Free Download"', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array( 'seekval' => $this->mainoptions[$lab])) . '
                </h4>';



						$lab = 'i18n_register_to_download';

						echo '
                <div class="setting">
                    <h4 class="label">' . __('Translate "Register to download"', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array( 'seekval' => $this->mainoptions[$lab])) . '
                </h4>';


						?>




                        <div class="setting">
                            <h4 class="label"><?php echo __('Register to download - opens in new window','dzsap'); ?></h4>
							<?php



							$lab = 'register_to_download_opens_in_new_link';





							echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));


							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>






                        </div>

                    </div>
                </div>



                <div class="dzs-tab-tobe tab-disabled">
                    <div class="tab-menu ">
                        &nbsp;&nbsp;
                    </div>
                    <div class="tab-content">

                    </div>
                </div>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu with-tooltip">
                        <i class="fa fa-keyboard-o"></i> <?php echo __("Keyboard") ?>
                    </div>
                    <div class="tab-content">
                        <br>



                        <div class="sidenote"><?php echo wp_kses(sprintf(__("keyboard controls setup: %s %s escape key - %s space key - %s 
                        left key - %s
                        right key - %s
                        up key - %s
                        down key - %s
                        you can input something like this %s also
                        ",'dzsap'),'<br>','<br>','<strong>27</strong><br>'
								,'<strong>32</strong><br>'
								,'<strong>37</strong><br>'
								,'<strong>39</strong><br>'
								,'<strong>38</strong><br>'
								,'<strong>40</strong><br>'
								,'<strong>ctrl+39</strong>'
							), $this->allowed_tags); ?></div>

						<?php

						$lab = 'keyboard_pause_play';

						echo '
                <div class="setting">
                    <h4 class="label">' . __('Play / pause code', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array( 'seekval' => $this->mainoptions[$lab])) . '
                </h4>';




						$lab = 'keyboard_step_forward';

						echo '
                <div class="setting">
                    <h4 class="label">' . __('Step forward key code', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array( 'seekval' => $this->mainoptions[$lab])) . '
                </h4>';
						$lab = 'keyboard_step_back';

						echo '
                <div class="setting">
                    <h4 class="label">' . __('Step back key code', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array( 'seekval' => $this->mainoptions[$lab])) . '
                </h4>';



						$lab = 'keyboard_step_back_amount';

						echo '
                <div class="setting">
                    <h4 class="label">' . __('Back amount in seconds', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array( 'seekval' => $this->mainoptions[$lab])) . '
                </h4>';





						$lab = 'keyboard_sync_players_goto_prev';

						echo '
                <div class="setting">
                    <h4 class="label">' . __('Previous track', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array( 'seekval' => $this->mainoptions[$lab])) . '
                    <div class="sidenote">'.esc_html__('either enable Play Single Players One After Another on the Page in Developer Settings, or enable the sticky player playlist in Player configurations - for this to work', 'dzsap').'</div>
                </h4>';
						$lab = 'keyboard_sync_players_goto_next';

						echo '
                <div class="setting">
                    <h4 class="label">' . __('Next track', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array( 'seekval' => $this->mainoptions[$lab])) . '
                </h4>';



						?>




                        <div class="setting">
                            <h4 class="label"><?php echo esc_html__('Show tooltips','dzsap'); ?></h4>
							<?php

							$lab = 'keyboard_show_tooltips';


							echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));


							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>

                        </div>




                        <div class="setting">
                            <h4 class="label"><?php echo esc_html__('Play triggers step back','dzsap'); ?></h4>
							<?php

							$lab = 'keyboard_play_trigger_step_back';


							echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));


							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>

                        </div>

                    </div>
                </div>



















                <div class="dzs-tab-tobe tab-disabled">
                    <div class="tab-menu ">
                        &nbsp;&nbsp;
                    </div>
                    <div class="tab-content">

                    </div>
                </div>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu with-tooltip">
                        <i class="fa fa-paint-brush"></i> <?php echo __("Appearance") ?>
                    </div>
                    <div class="tab-content">
                        <br>


						<?php
						$val = '444444';

						if ($this->mainoptions['color_waveformbg']) {
							$val = $this->mainoptions['color_waveformbg'];
						}
						echo '
                <h3>'.__("Wave Form Options").'</h3>
                <div class="setting">
                    <h4 class="label">' . __('Waveform BG Color', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text('color_waveformbg', array('val' => 'ffffff', 'seekval' => $val, 'type' => 'colorpicker', 'class' => 'colorpicker-nohash')) . '
                    <div class="sidenote">'.sprintf(__("you can input a gradient by inputing %s with your colors",'dzsap'),'<strong>000000,ffffff</strong>').'</div>
                </h4>';

						$val = 'ef6b13';

						if ($this->mainoptions['color_waveformprog']) {
							$val = $this->mainoptions['color_waveformprog'];
						}

						echo '<div class="setting">
                    <h4 class="label">' . __('Waveform Progress Color', 'dzsap') . '</div>
                    ' . DZSHelpers::generate_input_text('color_waveformprog', array('seekval' => $val, 'type' => 'colorpicker', 'class' => 'colorpicker-nohash')) . '
                </h4>';
						?>




						<?php

						$dependency = array(

							array(
								'element'=>'skinwave_wave_mode',
								'value'=>array('image'),
							),
						)
						;
						?>
                        <div class="setting" data-dependency='<?php echo json_encode($dependency); ?>'>
                            <h4 class="label"><?php echo __('Multiplier', 'dzsap'); ?></h4>
							<?php
							$val = 'ffffff';
							$lab = 'waveformgenerator_multiplier';
							if ($this->mainoptions[$lab]) {
								$val = $this->mainoptions[$lab];
							}
							echo DZSHelpers::generate_input_text($lab, array('val' => '1', 'seekval' => $val, 'type' => '', 'class' => ''));
							?>
                            <div
                                    class="sidenote"><?php echo __('If your waveformes come out a little flat and need some amplifying, you can increase this value .', 'dzsap'); ?></div>
                        </div>


                        <div class="setting"  data-dependency='<?php echo json_encode($dependency); ?>'>
                            <h4 class="label"><?php echo __('Waveform Style', 'dzsap'); ?></h4>
							<?php echo DZSHelpers::generate_select('settings_wavestyle', array('options' => array('reflect', 'normal'), 'seekval' => $this->mainoptions['settings_wavestyle'])); ?>

                        </div>





                        <div class="setting">
                            <h4 class="label"><?php echo __('Extra CSS', 'dzsap'); ?></h4>
							<?php
							echo DZSHelpers::generate_input_textarea('extra_css', array(
								'val' => '',
								'extraattr' => ' rows="5" style="width: 100%;"',
								'seekval' => $this->mainoptions['extra_css'],
							));
							?>

                        </div>


                    </div>
                </div>

                <div class="dzs-tab-tobe tab-disabled">
                    <div class="tab-menu ">
                        &nbsp;&nbsp;
                    </div>
                    <div class="tab-content">

                    </div>
                </div>




                <div class="dzs-tab-tobe">
                    <div class="tab-menu with-tooltip">
                        <i class="fa fa-bar-chart"></i> <?php echo __("Analytics") ?>
                    </div>
                    <div class="tab-content">
                        <br>


                        <div class="dzs-container">
                            <div class="full">
                                <div class="setting">

									<?php
									$lab = 'analytics_enable';
									echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
									?>
                                    <h4 class="setting-label"><?php echo __('Enable Analytics', 'dzsap'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
										<?php
										echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div
                                            class="sidenote"><?php echo __('activate analytics for the galleries', 'dzsap'); ?></div>
                                </div>


                                <div class="setting">

									<?php
									$lab = 'analytics_enable_location';
									echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
									?>
                                    <h4 class="setting-label"><?php echo __('Track Users Country?', 'dzsap'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
										<?php
										echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div
                                            class="sidenote"><?php echo __('use geolocation to track users country', 'dzsap'); ?></div>
                                </div>

                                <div class="setting">

									<?php
									$lab = 'analytics_enable_user_track';
									echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
									?>
                                    <h4 class="setting-label"><?php echo __('Track Statistic by User?', 'dzsap'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
										<?php
										echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div
                                            class="sidenote"><?php echo __('track views and minutes watched of each user', 'dzsap'); ?></div>
                                </div>


                            </div>




                        </div>


                    </div>
                </div>





                <!-- system check -->
                <div class="dzs-tab-tobe tab-disabled">
                    <div class="tab-menu ">
                        &nbsp;&nbsp;
                    </div>
                    <div class="tab-content">

                    </div>
                </div>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu with-tooltip">
                        <i class="fa fa-bookmark"></i> <?php echo __("Meta Options"); ?>
                    </div>

                    <div class="tab-content">
                        <br>



                        <div class="setting">
                            <h4 class="setting-label"><?php echo __('Enable Meta Options for ... ', 'dzspb'); ?></h4>
							<?php
							$lab = 'dzsap_meta_post_types';
							//        print_r($this->mainoptions[$lab]);
							//        print_r(get_option('active_plugins'));



							$args = array(
								'public' => true,
								'_builtin' => false
							);

							$output = 'names'; // names or objects, note names is the default
							$operator = 'and'; // 'and' or 'or'

							$post_types = get_post_types($args, $output, $operator);


							//                            print_rr($this->mainoptions[$lab]);


							echo DZSHelpers::generate_input_checkbox($lab . '[]', array('class' => 'styleme', 'def_value' => '', 'seekval' => $this->mainoptions[$lab], 'val' => 'post'));
							echo __(' post', 'dzspb');
							echo '<br/>';
							echo DZSHelpers::generate_input_checkbox($lab . '[]', array('class' => 'styleme', 'def_value' => '', 'seekval' => $this->mainoptions[$lab], 'val' => 'page'));
							echo __(' page', 'dzspb');
							echo '<br/>';
							foreach ($post_types as $lab=>$post_type) {

								$val = '';

								if(isset($this->mainoptions[$lab])){
									$val = $this->mainoptions[$lab];
								}
								echo DZSHelpers::generate_input_checkbox($lab . '[]', array('class' => 'styleme', 'def_value' => '', 'seekval' => $val, 'val' => $post_type));
								echo __(' ' . $post_type, 'dzspb');
								echo '<br/>';
							}
							?>
                            <div class="clear"></div>
                            <div class='sidenote'><?php echo sprintf(__('allows for %s meta options for these post types', 'dzsap'),'ZoomSounds'); ?></div>
                            <div class="clear"></div>
                        </div>




						<?php

						$nr = 1;
						$lab = 'extra_meta_label_'.$nr;

						$val = '';

						if(isset($this->mainoptions[$lab])){
							$val = $this->mainoptions[$lab];
						}


						//htmlentities
						?>
                        <div class="setting">


                            <h4 class="setting-label"><?php echo sprintf(__('Optional Meta Box %s Label', 'dzsap'),'<strong>'.$nr.'</strong>'); ?></h4>
							<?php

							echo DZSHelpers::generate_input_text($lab,array('class' => ' ','seekval' => $val));

							?>

                            <div class="sidenote"><?php echo __("place a optional meta box label - that can be replaced with in the zoomsounds extra html"); ?></div>
                        </div>




						<?php

						$nr = 2;
						$lab = 'extra_meta_label_'.$nr;

						$val = '';

						if(isset($this->mainoptions[$lab])){
							$val = $this->mainoptions[$lab];
						}


						//htmlentities
						?>
                        <div class="setting">


                            <h4 class="setting-label"><?php echo sprintf(__('Optional Meta Box %s Label', 'dzsap'),'<strong>'.$nr.'</strong>'); ?></h4>
							<?php

							echo DZSHelpers::generate_input_text($lab,array('class' => ' ','seekval' => $val));

							?>

                            <div class="sidenote"><?php echo __("place a optional meta box label - that can be replaced with in the zoomsounds extra html"); ?></div>
                        </div>




						<?php

						$nr = 3;
						$lab = 'extra_meta_label_'.$nr;

						$val = '';

						if(isset($this->mainoptions[$lab])){
							$val = $this->mainoptions[$lab];
						}


						//htmlentities
						?>
                        <div class="setting">


                            <h4 class="setting-label"><?php echo sprintf(__('Optional Meta Box %s Label', 'dzsap'),'<strong>'.$nr.'</strong>'); ?></h4>
							<?php

							echo DZSHelpers::generate_input_text($lab,array('class' => ' ','seekval' => $val));

							?>

                            <div class="sidenote"><?php echo __("place a optional meta box label - that can be replaced with in the zoomsounds extra html"); ?></div>
                        </div>

                    </div>
                </div>



                <!-- system check -->
                <div class="dzs-tab-tobe tab-disabled">
                    <div class="tab-menu ">
                        &nbsp;&nbsp;
                    </div>
                    <div class="tab-content">

                    </div>
                </div>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu with-tooltip">
                        <i class="fa fa-gear"></i> <?php echo __("System Check"); ?>
                    </div>
                    <div class="tab-content">
                        <br>



                        <div class="setting">

                            <h4 class="setting-label">GetText <?php echo __("Support"); ?></h4>


							<?php
							if (function_exists("gettext")) {
								echo '<div class="setting-text-ok"><i class="fa fa-check"></i> '.''.__("supported").'</div>';
							} else {

								echo '<div class="setting-text-notok">'.''.__("not supported").'</div>';
							}
							?>

                            <div class="sidenote"><?php echo __('translation support'); ?></div>
                        </div>


                        <div class="setting">

                            <h4 class="setting-label">ZipArchive <?php echo __("Support"); ?></h4>


							<?php
							if (class_exists("ZipArchive")) {
								echo '<div class="setting-text-ok"><i class="fa fa-check"></i> '.''.__("supported").'</div>';
							} else {

								echo '<div class="setting-text-notok">'.''.__("not supported").'</div>';
							}
							?>

                            <div class="sidenote"><?php echo __('zip making for album download support'); ?></div>
                        </div>
                        <div class="setting">

                            <h4 class="setting-label">Curl <?php echo __("Support"); ?></h4>


							<?php
							if (function_exists('curl_version')) {
								echo '<div class="setting-text-ok"><i class="fa fa-check"></i> '.''.__("supported").'</div>';
							} else {

								echo '<div class="setting-text-notok">'.''.__("not supported").'</div>';
							}
							?>

                            <div class="sidenote"><?php echo __('for making youtube / vimeo api calls'); ?></div>
                        </div>
                        <div class="setting">

                            <h4 class="setting-label">allow_url_fopen <?php echo __("Support"); ?></h4>


							<?php
							if (ini_get('allow_url_fopen')) {
								echo '<div class="setting-text-ok"><i class="fa fa-check"></i> '.''.__("supported").'</div>';
							} else {

								echo '<div class="setting-text-notok">'.''.__("not supported").'</div>';
							}
							?>

                            <div class="sidenote"><?php echo __('for making youtube / vimeo api calls'); ?></div>
                        </div>



                        <div class="setting">

                            <h4 class="setting-label"><?php echo __("PHP Version"); ?></h4>

                            <div class="setting-text-ok">
								<?php
								echo phpversion();
								?>
                            </div>

                            <div class="sidenote"><?php echo __('the install php version, 5.4 or greater required for facebook api'); ?></div>
                        </div>



                        <div class="setting">

                            <h4 class="setting-label"><?php echo __("Server IP"); ?></h4>

                            <div class="setting-text-ok">
								<?php
								//                                print_r($_SERVER);
								print_r($_SERVER['SERVER_ADDR']);
								?>
                            </div>

                            <div class="sidenote"><?php echo __('server ip address'); ?></div>
                        </div>




                        <div class="setting">

                            <h4 class="setting-label"><?php echo __("Permissions check"); ?></h4>


							<?php
							$role = get_role('administrator');
							$role->add_cap($this->taxname_sliders.'_manage_categories');
							$role->add_cap('dzsap_manage_vpconfigs');
							?>

							<?php
							$cap = $this->taxname_sliders.'_manage_categories';
							?>
                            <div class="permission-check-div">
                                <strong class="permission"><?php
									echo $cap;
									?>
                                </strong> -
                                <span class="label">

                            <?php
                            if (current_user_can($cap)) {
	                            echo '<span class="setting-text-ok"><i class="fa fa-check"></i> '.''.esc_html__("allowed",'dzsap').'</span>';
                            } else {

	                            echo '<span class="setting-text-notok"><i class="fa fa-times"></i> '.''.esc_html__("not allowed",'dzsap').'</span>';
                            }
                            ?>
                                </span>
                            </div>

							<?php
							$cap = 'dzsap_manage_options';
							?>
                            <div class="permission-check-div">
                                <strong class="permission"><?php
									echo $cap;
									?>
                                </strong> -
                                <span class="label">

                            <?php
                            if (current_user_can($cap)) {
	                            echo '<span class="setting-text-ok"><i class="fa fa-check"></i> '.''.esc_html__("allowed",'dzsap').'</span>';
                            } else {

	                            echo '<span class="setting-text-notok"><i class="fa fa-times"></i> '.''.esc_html__("not allowed",'dzsap').'</span>';
                            }
                            ?>
                                </span>
                            </div>

							<?php
							$cap = 'dzsap_make_shortcode';
							?>
                            <div class="permission-check-div">
                                <strong class="permission"><?php
									echo $cap;
									?>
                                </strong> -
                                <span class="label">

                            <?php
                            if (current_user_can($cap)) {
	                            echo '<span class="setting-text-ok"><i class="fa fa-check"></i> '.''.esc_html__("allowed",'dzsap').'</span>';
                            } else {

	                            echo '<span class="setting-text-notok"><i class="fa fa-times"></i> '.''.esc_html__("not allowed",'dzsap').'</span>';
                            }
                            ?>
                                </span>
                            </div>

							<?php
							$cap = 'dzsap_manage_vpconfigs';
							?>
                            <div class="permission-check-div">
                                <strong class="permission"><?php
									echo $cap;
									?>
                                </strong> -
                                <span class="label">

                            <?php
                            if (current_user_can($cap)) {
	                            echo '<span class="setting-text-ok"><i class="fa fa-check"></i> '.''.esc_html__("allowed",'dzsap').'</span>';
                            } else {

	                            echo '<span class="setting-text-notok"><i class="fa fa-times"></i> '.''.esc_html__("not allowed",'dzsap').'</span>';
                            }
                            ?>
                                </span>
                            </div>



                        </div>



                        <div class="setting">

                            <h4 class="setting-label"><?php echo __("Analytics table status"); ?></h4>
							<?php
							global $wpdb;

							$table_name = $wpdb->prefix . 'dzsap_activity';

							$var = $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" );

							//                        print_rr($var);
							if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {

								echo '<div class="setting-text-notok error">'.''.__("table not installed").'</div>';
							} else {
								echo '<div class="setting-text-ok"><i class="fa fa-check"></i> '.''.__("table ok").'</div>';




								echo '<p class=""><a class="button-secondary repair-table" href="'.admin_url('admin.php?page=dzsap-mo&tab=17&analytics_table_repair=on').'">'.__("repair table").'</a></p>';



								echo '<p class=""><a class="button-secondary" href="'.admin_url('admin.php?page=dzsap-mo&tab=17&show_analytics_table_last_10_rows=on').'">'.__("check last 10 rows").'</a></p>';



								if(isset($_GET['show_analytics_table_last_10_rows']) && $_GET['show_analytics_table_last_10_rows']=='on'){

									$query = 'SELECT * FROM '.$table_name.' ORDER BY id DESC LIMIT 10';
									$results = $GLOBALS['wpdb']->get_results($query , OBJECT );

									print_rr($results);
								}
								if(isset($_GET['analytics_table_repair']) && $_GET['analytics_table_repair']=='on'){



									$query = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS
           WHERE TABLE_SCHEMA=\''.DB_NAME.'\' AND TABLE_NAME=\''.$table_name.'\' AND column_name=\'country\'';


									$val = $wpdb->query($query);


//echo $query; print_r($val);

									$sw = false;
									if($val !== FALSE){
										//DO SOMETHING! IT EXISTS!

										if($val->num_rows>0){


										}else{

											$query = 'ALTER TABLE `'.$table_name.'` ADD `country` mediumtext NULL ;';


											$val = $wpdb->query($query);


											$sw = true;


										}

									}

									$query = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS
           WHERE TABLE_SCHEMA=\''.DB_NAME.'\' AND TABLE_NAME=\''.$table_name.'\' AND column_name=\'val\'';


									$val = $wpdb->query($query);


//echo $query; print_r($val);

									if($val !== FALSE){
										//DO SOMETHING! IT EXISTS!

										if($val->num_rows>0){


										}else{

											$query = 'ALTER TABLE `'.$table_name.'` ADD `val` int(255) NULL ;';


											$val = $wpdb->query($query);


											$sw = true;


										}

									}

									if($sw){

										echo 'table repaired!';
									}else{

										echo 'table was already okay';

										//
									}




								}

							}
							?>

							<?php
							if (ini_get('allow_url_fopen')) {
							} else {

							}
							?>

                            <div class="sidenote"><?php echo __('check if the analytics table exists'); ?></div>
                        </div>






                        <div class="setting">

                            <h4 class="setting-label"><?php echo __("Backup log2"); ?></h4>

                            <pre><?php
								$logged_backups = array();
								try{

									$logged_backups = json_decode(get_option('dzsap_backuplog'),true);
								}catch(Exception $err){

								}

								if(is_array($logged_backups)==false){
									$logged_backups = array();
								}

								//	                            echo '$logged_backups - '.print_rr($logged_backups,true);
								foreach ($logged_backups as $lb){
									echo date("F j, Y, g:i a",$lb).'<br>';
								}
								?></pre>
                        </div>


                        <div class="setting">

                            <h3 class="setting-label"><?php echo esc_html__("AWS Support",'dzsap'); ?></h3>

							<?php



							if(isset($_GET['install_aws']) && $_GET['install_aws']=='on'){



								$aux = 'https://s3.eu-west-3.amazonaws.com/zoomitflash-test-bucket/aws.zip';
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

										file_put_contents(dirname(__FILE__) . '/aws.zip', $res);
										if (class_exists('ZipArchive')) {
											$zip = new ZipArchive;
											$res = $zip->open(dirname(__FILE__) . '/aws.zip');
											//test
											if ($res === TRUE) {
												//                echo 'ok';
												$zip->extractTo(dirname(__FILE__));
												$zip->close();





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

							if(file_exists(dirname(__FILE__).'/aws/aws-autoloader.php')){
								?>



                                <div class="setting">
                                <h5 class="label"><?php echo __('Enable AWS Support', 'dzsap'); ?></h5>
								<?php
								$lab = 'aws_enable_support';
								echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));
								echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
								?>
                                <div
                                        class="sidenote"><?php echo sprintf(__('enable aws support', 'dzsap'), '/','wp-content/dzsap_backups'); ?></div>
                                </div><?php


								$lab = 'aws_key';
								?>
                                <div class="setting">


                                    <h5 class="label">Amazon S3 <?php echo esc_html__('Key', 'dzsap'); ?></h5>
									<?php

									echo DZSHelpers::generate_input_text($lab,array('class' => ' ','seekval' => $this->mainoptions[$lab]));

									?>
                                    <div class="sidenote"><?php echo wp_kses(sprintf(__("tutorial %shere%s",'dzsap'),

											'<a target="_blank" href="https://zoomthe.me/knowledge-base/zoomsounds-audio-player/article/how-to-enable-amazon-s3-support-for-reading-files-from-bucket/">',
											'</a>'
										),$dzsap->allowed_tags); ?></div>


                                </div>
								<?php


								$lab = 'aws_key_secret';
								?>
                                <div class="setting">


                                    <h5 class="label">Amazon S3 <?php echo esc_html__('Secret', 'dzsap'); ?></h5>
									<?php

									echo DZSHelpers::generate_input_text($lab,array('class' => ' ','seekval' => $this->mainoptions[$lab]));

									?>


                                </div>
								<?php


								$lab = 'aws_region';
								?>
                                <div class="setting">


                                    <h5 class="label">Amazon S3 <?php echo esc_html__('Region code', 'dzsap'); ?></h5>
									<?php

									echo DZSHelpers::generate_input_text($lab,array('class' => ' ','seekval' => $this->mainoptions[$lab]));

									?>
                                    <div class="sidenote"><?php echo wp_kses(sprintf(__("region code ( ie. %s ) - full list %shere%s",'dzsap'),
											'<strong>eu-west</strong>',
											'<a target="_blank" href="https://docs.aws.amazon.com/general/latest/gr/rande.html">',
											'</a>'
										),$dzsap->allowed_tags); ?></div>


                                </div>
								<?php


								$lab = 'aws_bucket';
								?>
                                <div class="setting">


                                    <h5 class="label">Amazon S3 <?php echo esc_html__('Bucket', 'dzsap'); ?></h5>
									<?php

									echo DZSHelpers::generate_input_text($lab,array('class' => ' ','seekval' => $this->mainoptions[$lab]));

									?>


                                </div>
								<?php

							}else{

								echo '<p class=""><a class="button-secondary repair-table" href="'.admin_url('admin.php?page=dzsap-mo&tab=17&install_aws=on').'">'.esc_html__("install aws",'dzsap').'</a></p>';
							}



							?>
                        </div>








                    </div>
                </div>
                <!-- system check END -->











                <div class="dzs-tab-tobe tab-disabled">
                    <div class="tab-menu ">
                        &nbsp;&nbsp;
                    </div>
                    <div class="tab-content">

                    </div>
                </div>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu with-tooltip">
                        <i class="fa fa-gears"></i> <?php echo __("Developer") ?>
                    </div>
                    <div class="tab-content">
                        <br>


                        <!-- developer tab content -->


                        <div class="setting">
                            <h4 class="label"><?php echo __('do not use wordpres uploader', 'dzsap'); ?></h4>
							<?php
							$lab = 'usewordpressuploader';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                        </div>

                        <div class="setting">
                            <h4
                                    class="label"><?php echo __('Use External wp-content Upload Directory ?', 'dzsap'); ?></h4>

							<?php
							$lab = 'use_external_uploaddir';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).' <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('use an outside directory for uploading files', 'dzsap'); ?></div>
                        </div>





						<?php

						$lab = 'failsafe_repair_media_element';
						echo DZSHelpers::generate_input_text($lab,array('id' => $lab,'input_type' => 'hidden','class' => 'mainsetting', 'val' => 'off'))
						?>
                        <div class="setting">
							<?php
							?>
                            <h4 class="label"><?php echo __('Repair Media Element', 'dzsap'); ?></h4>
							<?php
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('If the audio element used by zoomsounds is somehow replaced ( maybe conflicting with media element ) - you can use this to repair function ', 'dzsap'); ?></div>
                        </div>





                        <div class="setting">
                            <h4
                                    class="label"><?php echo __('Disable Preview Shortcodes in TinyMce Editor', 'dszap'); ?></h4>

							<?php
							$lab = 'tinymce_disable_preview_shortcodes';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('add a box with the shortcode in the tinymce Visual Editor', 'dszap'); ?></div>
                        </div>


						<?php
						$lab = 'developer_check_for_bots_and_dont_reveal_source';
						?>
                        <div class="setting">
							<?php
							echo DZSHelpers::generate_input_text($lab,array('id' => $lab,'input_type' => 'hidden','class' => 'mainsetting', 'val' => 'off'))
							?>
                            <h4 class="label"><?php echo __('Disable bot source', 'dzsap'); ?></h4>
							<?php
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('check for bot scrubing the site - if it is detected, do not show him the source field', 'dzsap'); ?></div>
                        </div>


						<?php
						$lab = 'try_to_cache_total_time';
						?>
                        <div class="setting">
							<?php
							echo DZSHelpers::generate_input_text($lab,array('id' => $lab,'input_type' => 'hidden','class' => 'mainsetting', 'val' => 'off'))
							?>
                            <h4 class="label"><?php echo __('Try to cache total time', 'dzsap'); ?></h4>
							<?php
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('try to cache the total time, so that the meta data does not need to be loaded in order to show track time', 'dzsap'); ?></div>
                        </div>


						<?php
						$lab = 'notice_no_media';
						?>
                        <div class="setting">
							<?php
							echo DZSHelpers::generate_input_text($lab,array('id' => $lab,'input_type' => 'hidden','class' => 'mainsetting', 'val' => 'off'))
							?>
                            <h4 class="label"><?php echo __('Notice - no media', 'dzsap'); ?></h4>
							<?php
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('display when the audio cannot be loaded', 'dzsap'); ?></div>
                        </div>
						<?php
						$lab = 'pcm_notice';
						?>
                        <div class="setting">
							<?php
							echo DZSHelpers::generate_input_text($lab,array('id' => $lab,'input_type' => 'hidden','class' => 'mainsetting', 'val' => 'off'))
							?>
                            <h4 class="label"><?php echo __('Wave Generating Notice', 'dzsap'); ?></h4>
							<?php
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('display the wave generating notice - or else the notice will not show but the wave forms will still generate', 'dzsap'); ?></div>
                        </div>


						<?php
						$lab = 'pcm_data_try_to_generate';
						?>
                        <div class="setting">
							<?php
							echo DZSHelpers::generate_input_text($lab,array('id' => $lab,'input_type' => 'hidden','class' => 'mainsetting', 'val' => 'off'))
							?>
                            <h4 class="label"><?php echo __('Wave Generation', 'dzsap'); ?></h4>
							<?php
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('generate wave form or display placeholders ( off ) ', 'dzsap'); ?></div>
                        </div>


						<?php
						$lab = 'construct_player_list_for_sync';
						?>
                        <div class="setting">
                            <h4 class="label"><?php echo __("Play Single Players One After Another on the Page");  ?></h4>
							<?php echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>

                            <p class="sidenote"><?php echo __('Automatically identify all the single players in the page', 'dzsap'); ?></p>
                        </div>







                        <div class="setting">
                            <h4 class="label"><?php echo __('Safe Binding?', 'dzsap'); ?></h4>

							<?php
							$lab = 'is_safebinding';
							echo '<div class="dzscheckbox skin-nova">
'.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
 <label for="'.$lab.'"></label>
</div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('the galleries admin can use a complex ajax backend to ensure fast editing, but this can cause limitation issues on php servers. Turn this to on if you want a faster editing experience ( and if you have less then 20 videos accross galleries ) ', 'dzsap'); ?></div>
                        </div>
                        <div class="setting">
                            <h4 class="label"><?php echo __('Do Not Use Caching', 'dzsap'); ?></h4>
							<?php
							$lab = 'use_api_caching';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'off','seekval' => $this->mainoptions[$lab])).'
    <label for="'.$lab.'"></label>
</div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('use caching for vimeo / youtube api ( recommended - on )', 'dzsap'); ?></div>
                        </div>


                        <div class="setting">
                            <h4 class="label"><?php echo __('Force File Get Contents', 'dzsap'); ?></h4>
							<?php
							$lab = 'force_file_get_contents';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('sometimes curl will not work for retrieving youtube user name / playlist - try enabling this option if so...', 'dzsap'); ?></div>
                        </div>
                        <div class="setting">
                            <h4 class="label"><?php echo __('Force Refresh Size Every 1000ms', 'dzsap'); ?></h4>
							<?php
							$lab = 'settings_trigger_resize';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('sometimes sizes need to be recalculated ( for example if you use the gallery in tabs )', 'dzsap'); ?></div>
                        </div>






                        <div class="setting">
                            <h4 class="label"><?php echo __('Replace Playlist Shortcode', 'dzsap'); ?></h4>
							<?php $lab = 'replace_playlist_shortcode';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('replace the default wordpress audio playlist with a zoomsounds playlist ', 'dzsap'); ?></div>
                        </div>



                        <div class="setting">
                            <h4 class="label"><?php echo __('Enable Powerpress Support', 'dzsap'); ?></h4>
							<?php $lab = 'replace_powerpress_plugin';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('replace the current powerpress player with zoomsounds ', 'dzsap'); ?></div>
                        </div>



                        <div class="setting">
                            <h4 class="label"><?php echo 'Powerpress - '; echo __(' try to read category data ', 'dzsap'); echo 'xml';?></h4>
							<?php $lab = 'powerpress_read_category_xml';
							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>
                            <div
                                    class="sidenote"><?php echo __('replace the current powerpress player with zoomsounds ', 'dzsap'); ?></div>
                        </div>


                        <div class="setting">
                            <h4 class="label"><?php echo __('Replace default wordpress audio shortcode','dzsap'); ?></h4>
							<?php



							$lab = 'replace_audio_shortcode';

							$vpconfigs_arr = array(
								array('lab'=>__("Off"), 'val'=>'off')
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

							echo DZSHelpers::generate_select($lab,array('class' => 'vpconfig-select styleme','options' => $vpconfigs_arr,'seekval' => $this->mainoptions[$lab])); ?>

                            <div class="edit-link-con" style="margin-top: 10px;"></div>

                            <div class="sidenote"><?php echo __("select a audio player configuration with which to replace the default wordpress player"); ?></div>
                        </div>


						<?php

						$lab = 'replace_audio_shortcode_extra_args';


						//htmlentities
						?>
                        <div class="setting">


                            <h4 class="label"><?php echo __('Extra arguments for default audio shortcode', 'dzsap'); ?></h4>
							<?php echo $this->misc_input_textarea($lab, array('val' => '', 'seekval' => stripslashes($this->mainoptions[$lab]))); ?>

                            <div class="sidenote"><?php echo esc_html__("in json format",'dzsap'); ?></div>
                        </div>



                        <div class="setting">
                            <h4 class="label"><?php echo __('Play default shortcode in footer player','dzsap'); ?></h4>
							<?php



							$lab = 'replace_audio_shortcode_play_in_footer';





							echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));


							echo '<div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => $this->mainoptions[$lab])).'
                                        <label for="'.$lab.'"></label>
                                    </div>';
							?>





                            <div class="sidenote"><?php echo __("only if a player configuration is selected for the default player, then this will play in the footer player"); ?></div>
                        </div>








                        <div class="setting">
                            <h4 class="label"><?php echo __('Load local fontawesome','dzsap'); ?></h4>
							<?php



							$lab = 'fontawesome_load_local';

							$vpconfigs_arr = array(
								array(
									'lab'=>__("Off"),
									'val'=>'off'
								),
								array(
									'lab'=>__("On"),
									'val'=>'on'
								),
							);


							echo DZSHelpers::generate_select($lab,array('class' => 'vpconfig-select styleme','options' => $vpconfigs_arr,'seekval' => $this->mainoptions[$lab])); ?>



                            <div class="sidenote"><?php echo __("select a audio player configuration with which to replace the default wordpress player"); ?></div>
                        </div>




						<?php
						$lab = 'js_init_timeout';
						?>
                        <div class="setting">


                            <h4 class="label"><?php echo __('Javascript Init Timeout', 'dzsap'); ?></h4>
							<?php

							echo DZSHelpers::generate_input_text($lab,array('class' => ' ','seekval' => $this->mainoptions[$lab]));

							?>

                            <div class="sidenote"><?php echo __("place a timeout for initializing the player ( in ms ) "); ?></div>
                        </div>




						<?php

						$lab = 'wavesurfer_pcm_length';


						//htmlentities
						?>
                        <div class="setting">


                            <h4 class="label"><?php echo __('Precision', 'dzsap'); ?></h4>
							<?php

							echo DZSHelpers::generate_input_text($lab,array('class' => ' ','seekval' => $this->mainoptions[$lab]));

							?>

                            <div class="sidenote"><?php echo esc_html__("higher is more precise, but occupies more storage space",'dzsap'); ?></div>
                        </div>




						<?php

						$lab = 'extra_js';


						//htmlentities
						?>
                        <div class="setting">


                            <h4 class="label"><?php echo __('Extra Javascript', 'dzsap'); ?></h4>
							<?php echo $this->misc_input_textarea($lab, array('val' => '', 'seekval' => stripslashes($this->mainoptions[$lab]))); ?>

                            <div class="sidenote"><?php echo __("extra javascript on page load"); ?></div>
                        </div>






                    </div>
                </div>


				<?php
				do_action('dzsap_mainoptions_after_last_tab');
				?>

            </div>







            <br/>
            <br/>
            <br/>
            <a href='#'
               class="button-primary save-btn dzsap-save-main-options save-mainoptions"><?php echo __('Save Options', 'dzsap'); ?></a>
        </form>
        <br/><br/>



        <div class="dzstoggle toggle1<?php

		$lab = 'track_id';
		if(isset($_GET[$lab])){
			echo ' active';
		}

		?>" rel="">
            <div class="toggle-title" style=""><?php echo  __('Analyze track data', 'dzsap'); ?></div>
            <div class="toggle-content" style="<?php

			$lab = 'track_id';
			if(isset($_GET[$lab])){
				echo 'height: auto;';
			}

			?>">




                <div class="sidenote"><?php echo __("Analyze wave data or generate wave data for a single track."); ?></div>

                <form action="admin.php?page=dzsap-mo" method="get">
                    <div class="setting">

                        <h4 class="setting-label"><?php echo __("Track"); ?> <?php echo __("Id"); ?></h4>

						<?php


						$lab = 'page';
						echo DZSHelpers::generate_input_text($lab, array(
							'seekval' => 'dzsap-mo',
							'input_type' => 'hidden',
						));

						$lab = 'track_id';
						$val = '';

						if(isset($_GET[$lab])){
							$val = $_GET[$lab];
						}
						echo DZSHelpers::generate_input_text($lab, array( 'seekval' => $val));
						?>
                        <div class="sidenote"><?php echo __("get track by id or source"); ?></div>
                    </div>
                    <div class="setting">

                        <h4 class="setting-label"><?php echo __("Get pcm from url"); ?></h4>

						<?php


						$lab = 'track_source';
						$val = '';

						if(isset($_GET[$lab])){
							$val = $_GET[$lab];
						}
						echo DZSHelpers::generate_input_text($lab, array( 'seekval' => $val));
						?>
                        <div class="sidenote"><?php echo __("( donor mp3 ) ( optional ) get pcm data from another mp3 url"); ?></div>
                    </div>
                    <button class="button-secondary" name="dzsap_action" value="generate_wave"><?php echo __("Get Track Data"); ?></button>

                </form>

				<?php



				if(isset($_GET[$lab])){
				?>

                <div class="setting">

                    <h4 class="setting-label"><?php echo __("Flash"); ?> <?php echo __("Tool"); ?></h4>


					<?php

					/*
			echo '<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" WIDTH="320" HEIGHT="240" id="Yourfilename" ALIGN="">
			<PARAM NAME=movie VALUE="'.$this->base_url.'wavegenerator.swf">
			<PARAM NAME=quality VALUE=high>
			<PARAM NAME=bgcolor VALUE=#333399>
			<PARAM NAME="media" VALUE="http://localhost:8888/testimages/adg3.mp3">
			<EMBED src="'.$this->base_url.'wavegenerator.swf" quality=high bgcolor=#333399 WIDTH="320" HEIGHT="240" NAME="Yourfilename" ALIGN="" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED></OBJECT> ';
					*/

					global $dzsap;




					$lab = 'track_id';
					$val = '';

					if(isset($_GET[$lab])){
						$val = $_GET[$lab];
					}


					$id = $val ;
					$id = $dzsap->clean($id);
					$media_po = get_post($id);

					$flash_src = '';

					$src = wp_get_attachment_url($id);


					if(isset($_GET['track_source'])){
						$flash_src = $_GET['track_source'];
					}else{
						$flash_src = $src;
					}

					$aux= '
    <EMBED src="'.$this->base_url.'wavegenerator.swf" quality=high bgcolor=#ddd WIDTH="100%" HEIGHT="580" NAME="Yourfilename" ALIGN="" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer" FlashVars="media='.urlencode($flash_src);

					//$aux.='&wave_generation=wavearr';

					$aux.='"></EMBED>
';

					echo $aux;

					?>
                </div>

                <script>

                    window.api_wave_data = function(arg){
                        console.info('wave_data - ',arg);
                    }
                </script>

                <div class="sidenote"><?php echo __("copy the text from above in the box below to overwrite pcm data"); ?> -  <?php echo sprintf(__("in order to save the pcm data fro mthe flash tool, click the text above and press %s ( %s ) "),"ctrl + a", __("Select All")); ?></div>

                <div class="setting">

                    <h4 class="setting-label"><?php echo __("PCM"); ?> <?php echo __("Data"); ?></h4>
					<?php

					$lab = 'dzsap_pcm_data';



					//                echo print_rrr($media_po);

					$val = get_option($lab.'_'.$id);
					echo DZSHelpers::generate_input_textarea($lab, array(
						'seekval' => $val,
						'extraattr' => ' data-id="'.$id.'" style="width: 100%;" rows="5" ',
					));
					?>

                    <button name="dzsap_save_pcm" value="on"
                            class="button-secondary"><?php echo __('Save PCM Data From Textarea', 'dzsap'); ?></button>
                </div>
            </div><!-- end toggle content -->
			<?php
			// -- end analyzing
			}

			?>


        </div>
        <!-- end analyze track data -->




        <div class="dzstoggle toggle1">
            <div class="toggle-title" style=""><?php echo  __('Delete Plugin Data', 'dzsap'); ?></div>
            <div class="toggle-content" style="">
                <br>
                <form class="mainsettings" method="POST">
                    <button name="dzsap_delete_plugin_data" value="on"
                            class="button-secondary"><?php echo __('Delete plugin data', 'dzsap'); ?></button>
                </form>
                <br>
                <form class="mainsettings" method="POST">
					<?php
					$nonce = wp_create_nonce( 'dzsap_delete_waveforms_nonce' );
					?>
                    <input  type="hidden" name="action" value="dzsap_delete_waveforms"/>
                    <input  type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
                    <button  class="button-secondary btn-delete-waveform-data"><?php echo __('Delete waveform data', 'dzsap'); ?></button>
                </form>
                <br>
                <form class="mainsettings" method="POST">
					<?php
					$nonce = wp_create_nonce( 'dzsap_delete_times_nonce' );
					?>
                    <input  type="hidden" name="action" value="dzsap_delete_times"/>
                    <input  type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
                    <button  class="button-secondary btn-delete-waveform-data"><?php echo __('Delete total times', 'dzsap'); ?></button>
                </form>
                <br>

            </div>

        </div>




        <div class="saveconfirmer" style=""><img alt="" style="" id="save-ajax-loading2"
                                                 src="<?php echo site_url(); ?>/wp-admin/images/wpspin_light.gif"/>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                sliders_ready($);
//                        $('input:checkbox').checkbox();
            })
        </script>
    </div>
    <div class="clear"></div><br/>
<?php
