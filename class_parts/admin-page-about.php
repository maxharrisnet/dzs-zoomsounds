<?php

//print_r($this->mainoptions);


wp_enqueue_style('dzsulb', $this->base_url . 'libs/ultibox/ultibox.css');
wp_enqueue_script('dzsulb', $this->base_url . 'libs/ultibox/ultibox.js');


$role = get_role( 'administrator' );


if($role){


	$cap = 'dzsap_make_shortcode';

	$role->add_cap( $cap );


	$cap = 'dzsap_manage_options';

	$role->add_cap( $cap );



	$role->add_cap($this->taxname_sliders.'_manage_categories');
	$role->add_cap('dzsap_manage_vpconfigs');

}
?>
    <style>
        .about-text{
            font-size: 16px;
            color: #444444;
            margin-top: 15px;
        }
        .white-bg{
            background-color: #ffffff;
            padding: 15px;
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.15);

        }
        .white-bg .vplayer{
            transform: scale(1);
            transform-origin: center center;
            box-shadow: 0 0 5px 0 rgba(0,0,0,0);
            transition-property: opacity, visibility, top, height, transform,box-shadow;
        }
        .white-bg .vplayer:hover{
            transform: scale(1.3);
            z-index: 9999999999;
            box-shadow: 0 0 5px 0 rgba(0,0,0,0.5);

        }
        .white-bg h4{
            margin-top:0;

        }
        .ultibox-gallery-arrow{
            display: none;
        }
    </style>
    <script>
        jQuery(document).ready(function($){
            $(document).on('mouseover','.vplayer', function(){
                if($(this).get(0) && $(this).get(0).api_playMovie){

                    $(this).get(0).api_playMovie();
                }
            })
            $(document).on('click','.tab-menu', function(){


                dzsvp_init('.vplayer-tobe.tobe-inited', {init_each: true});
                dzsap_init('.videogallery.auto-init', {init_each: true});
            })
            $(document).on('click','.btn-disable-activation', function(){



                var _t = $(this);



                console.info("DISABLE ACTIVATION");
                open_ultibox(null, {

                    type: 'inlinecontent'
                    ,source: '#loading-activation'

                });








                $.get( "https://zoomthe.me/updater_dzsap/check_activation.php?purchase_code="+$('*[name=dzsap_purchase_code]').val()+'&site_url='+encodeURIComponent(dzsap_settings.site_url)+'&action=dzsap_purchase_code_disable', function( data ) {


//                console.warn('data->',data);



                    $('.dzs-center-flex').eq(0).html(data);

                    if(data.indexOf('success')>-1){
//                    console.warn('hmm');




                        setTimeout(function(){

                            var data2 = {
                                action: 'dzsap_deactivate'
                                ,postdata: $('*[name=dzsap_purchase_code]').eq(0).val()
                            };

                            $.post(ajaxurl, data2, function(response) {
                                //console.log(response);
                                if(window.console !=undefined ){
                                    //console.log(response);
                                }

                                setTimeout(function(){

                                    location.reload();
                                },1000)

//                            window.location.href = window.location.href;


                            });
                        },10)

                    }






                });


                return false;
            })


            $(document).on('submit','.activate-form',function(){
                var _t = $(this);



                open_ultibox(null, {

                    type: 'inlinecontent'
                    ,source: '#loading-activation'

                });



//            console.info("WHAT IS WPURL",dzsap_settings);
//            console.info("WHAT IS WPURL",window.dzsap_settings.wpurl);




                $.get( "https://zoomthe.me/updater_dzsap/check_activation.php?purchase_code="+$('*[name=dzsap_purchase_code]').val()+'&site_url='+encodeURIComponent(dzsap_settings.site_url), function( data ) {


//                console.warn('data->',data);



                    $('.dzs-center-flex').html(data);

                    if(data.indexOf('success')>-1){
//                    console.warn('hmm');

                        setTimeout(function(){

                            var data2 = {
                                action: 'dzsap_activate'
                                ,postdata: $('*[name=dzsap_purchase_code]').eq(0).val()
                            };

                            $.post(ajaxurl, data2, function(response) {
                                //console.log(response);
                                if(window.console !=undefined ){
                                    //console.log(response);
                                }

                                location.reload();
//                            window.location.href = window.location.href;


                            });
                        },10)

                    }






                });









                return false;
            })

            setTimeout(function(){
                jQuery.get( "https://zoomthe.me/cronjobs/cache/dzsap_get_version.static.html", function( data ) {

//            console.info(data);
                    var newvrs = Number(data);

                    $('.latest-version').animate({
                        'opacity':'0'
                    },300);

                    setTimeout(function(){

                        $('.latest-version').html(newvrs);
                        $('.latest-version').animate({
                            'opacity':'1'
                        },300);
                    },300);


                });




            }, 1000);
            setTimeout(function(){


                $.get( "https://zoomthe.me/updater_dzsap/getdemo.php?demo=1", function( data ) {


                    console.info("DATA -4 ",data);


                })
            }, 2000);
        })
    </script>

    <style>.dzs-tabs.transition-fade{
            overflow: visible;
        }</style>




    <div id="loading-activation" class="feed-ultibox show-in-ultibox">

        <div class="dzs-center-flex">

            <i class="fa-spin fa fa-circle-o-notch" style="font-size: 30px;"></i>
        </div>

    </div>


    <div class="wrap wrap-dzsap-about" style="max-width: 1200px;">
        <h1><?php echo __("Welcome to"); echo ' ZoomSounds '; echo DZSAP_VERSION; ?></h1>




        <div class="about-text"><?php echo __("
            Congratulations! You are about to use the most powerful audio player."); ?>	</div>
        <p class="useful-links">
            <a href="<?php echo admin_url('admin.php?page=dzsap_menu'); ?>" target=""
               class="button-primary action"><?php _e('Gallery Admin', 'dzsap'); ?></a>
            <a href="<?php echo $this->thepath; ?>readme/index.html"
               class="button-secondary action"><?php _e('Documentation', 'dzsap'); ?></a>


        </p>


        <div class="dzs-tabs auto-init dzs-tabs-dzsvp-page skin-box" data-options="{ 'design_tabsposition' : 'top'
,design_transition: 'fade'
,design_tabswidth: 'default'
,toggle_breakpoint : '400'
,settings_appendWholeContent : true
,toggle_type: 'accordion'
}">




            <div class="dzs-tab-tobe">
                <div class="tab-menu"><i class="fa fa-video-camera"></i><?php echo __('Intro', 'dzsvp'); ?></div>
                <div class="tab-content">

                    <div class="dzs-row">
                        <div class="dzs-col-md-6">

                            <img class="fullwidth" src="https://s3.envato.com/files/198770381/preview-wordpress-audio-player.jpg" style="border: 2px solid #aaa;"/>



                        </div>
                        <div class="dzs-col-md-6">

                            <p>
								<?php
								echo sprintf(__("Want a nifty, cutting-edge, retina-ready, responsive html5 audio player for your site ? ZoomSounds is the perfect candidate. With nine skins to fit every brand, multiple layout for the wave skin, only one format required to function, ZoomSounds is the perfect choice for an audio player."));
								?>
                            </p>
                            <p>
								<?php
								echo sprintf(__("Supports self hosted mp3 / m4a / wav, youtube and mp3 shoutcast radio stations. You can also embed SoundCloud songs just by inputing their link in the shortcode generator – that easy. You just need a soundcloud account and api key ( you can request that on their site ) ." ));
								?>
                            </p>
                        </div>
                    </div>
                </div>

            </div>


            <div class="dzs-tab-tobe">
                <div class="tab-menu"><i class="fa fa-video-camera"></i><?php echo __('Create Galleries', 'dzsvp'); ?></div>
                <div class="tab-content">

                    <div class="dzs-row">
                        <div class="dzs-col-md-6">


                            <div  class="vplayer-tobe  tobe-inited skin_noskin" data-videoTitle="How to setup gallery quick demo" data-type="youtube" data-src="https://www.youtube.com/watch?v=DF8xQKMs_qY" data-loop="on" data-responsive_ratio="0.562" data-options='{
            autoplay: "off"
            ,autoplay_on_mobile_too_with_video_muted: "on"
            ,settings_suggestedQuality: "hd1080"
}'></div>


                        </div>
                        <div class="dzs-col-md-6">

                            <p>
								<?php
								echo sprintf(__("This is how easy it is to create new galleries. Just add your items, choose your settings, and embed the gallery into any page. "));
								?>
                            </p>
                            <p>
								<?php
								echo sprintf(__("Multiple options like menu position, display mode, video description style, video items sorting can be chosen for each gallery. And options like looping, autoplay, cover images can be chosen for each individual item" ));
								?>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="dzs-tab-tobe">
                <div class="tab-menu"><i class="fa fa-flask"></i><?php echo __('Audio Showcase', 'dzsvp'); ?></div>
                <div class="tab-content">

                    <div class="dzs-row">
                        <div class="dzs-col-md-6">

                            <div  class="vplayer-tobe tobe-inited skin_noskin" data-videoTitle="How to setup video items" data-type="youtube" data-src="https://www.youtube.com/watch?v=Er2WgT99xDM" data-loop="on" data-responsive_ratio="detect" data-options='{
            autoplay: "off"
            ,autoplay_on_mobile_too_with_video_muted: "on"
}'></div>
                        </div>
                        <div class="dzs-col-md-6">

                            <p>
								<?php
								echo sprintf(__("For creating video items with their own page and comments, you can use the custom video items page. These items have multiple layouts and can be added to any page via the awesome Video Showcase shortcode generator"));
								?></p>
                            <p>
								<?php
								echo sprintf(__("You can even allow your visitors to upload videos from youtube or self hosted if the %sVideo Portal%s addon is installed"),'<strong>','</strong>');
								?></p>

                        </div>
                    </div>
                </div>

            </div>

            <div class="dzs-tab-tobe">
                <div class="tab-menu"><i class="fa fa-cog"></i><?php echo __('Shortcode Generator', 'dzsvp'); ?></div>
                <div class="tab-content">

                    <div class="dzs-row">
                        <div class="dzs-col-md-6">

                            <img src="https://i.imgur.com/6FgtoMo.png" style="box-shadow: 0 0 3px 0 rgba(0,0,0,0.3);" class="fullwidth"/>
                        </div>
                        <div class="dzs-col-md-6">

                            <p>

								<?php
								echo sprintf(__("ZoomSounds is very easy to use - it is based on shortcodes but you do not need to remember any shortcodes - the included shortcode generator makes life easy by getting a visual interface for selecting / customizing settings of the gallery."));
								?>
                            </p>

                        </div>
                    </div>
                </div>

            </div>

        </div>


        <br>
        <br>
        <div class="dzs-row">

			<?php
			if(current_user_can('manage_options')){
				?>
                <div class="dzs-col-md-4">

                    <div class="white-bg">

                        <h4><?php echo __("Activate"); ?> ZoomSounds</h4>

						<?php

						$auxarray = array();


						if (isset($_GET['dzsap_purchase_remove_binded']) && $_GET['dzsap_purchase_remove_binded'] == 'on') {

							$this->mainoptions['dzsap_purchase_code_binded'] = 'off';

							update_option($this->dboptionsname, $this->mainoptions);

						}

						if (isset($_POST['action'])){


							if($_POST['action'] === 'dzsap_update_request' || $_POST['action'] === 'dzsap_register_request') {

								if (isset($_POST['dzsap_purchase_code'])) {
									$auxarray = array('dzsap_purchase_code' => $_POST['dzsap_purchase_code']);
									$auxarray = array_merge($this->mainoptions, $auxarray);

									$this->mainoptions = $auxarray;


									update_option($this->dboptionsname, $auxarray);
								}
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
							$disable_button = ' <input type="hidden" name="purchase_code" value="' . $this->mainoptions[$lab] . '"/><input type="hidden" name="site_url" value="' . site_url() . '"/><input type="hidden" name="redirect_url" value="' . esc_url(add_query_arg('dzsap_purchase_remove_binded', 'on', dzs_curr_url())) . '"/><button class="button-secondary btn-disable-activation" name="action" value="dzsap_purchase_code_disable">' . __("Disable Key") . '</button>';
							$form_action = ' action="https://zoomthe.me/updater_dzsap/servezip.php"';
						}


						?>
                        <form  action="https://zoomthe.me/updater_dzsap/check_activation.php" class="mainsettings activate-form" method="POST">
							<?php
							?><div class="sidenote"><?php echo __("Unlock "); echo " ZoomSounds"; echo __(" for premium benefits like one click sample galleries install and autoupdate.")  ?></div><?php
							echo '
            
                <div class="setting">
                    <div class="label">' . __("Purchase Code", 'dzsap') . '</div>
                    ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab], 'class' => $extra_class, 'extra_attr' => $extra_attr)) . $disable_button . '
                    <div class="sidenote">' . sprintf(__("You can %sfind it here%s ", 'dzsap'),'<a href="https://lh5.googleusercontent.com/-o4WL83UU4RY/Unpayq3yUvI/AAAAAAAAJ_w/HJmso_FFLNQ/w786-h1179-no/puchase.jpg" target="“_blank”">','</a>') . '</div>
                </div>';



							echo '<p><button class="button-primary" name="action" value="dzsap_register_request">' . __("Activate",'dzsap') . '</button></p>';


							if ($this->mainoptions['dzsap_purchase_code_binded'] == 'on') {
								echo '';
							}
							?></form>
                        <br>

						<?php

						/*
						 *
						 * <?php echo $form_action ?>
						 */

						?>
                        <form  class="mainsettings update-form" method="post"><?php

							?>
                            <strong><?php echo __("Current Version"); ?></strong>
                            <p><span class="version-number"
                                     style="font-size:13px; font-weight: 100;"><span
                                            class="now-version"><?php echo DZSAP_VERSION; ?></span></span></p>
                            <strong><?php echo __("Latest Version"); ?></strong>
                            <p><span class="version-number"
                                     style="font-size:13px; font-weight: 100; min-height: 17px;"><span
                                            class="latest-version" style=" min-height: 21px; display: inline-block"> <i class="fa-spin fa fa-circle-o-notch"></i> </span></span></p>

							<?php

							$str_disabled = ' disabled';

							if ($this->mainoptions['dzsap_purchase_code_binded'] == 'on') {
								$str_disabled = '';
							}


							echo '<p><button class="button-primary" name="action" value="dzsap_update_request" '.$str_disabled.'>' . __("Update",'dzsap') . '</button></p>';



							?>
                        </form><?php


						if (isset($_POST['action']) && $_POST['action'] === 'dzsap_update_request') {


//            echo 'ceva';


//            die();


							$aux = 'https://zoomthe.me/updater_dzsap/servezip.php?purchase_code=' . $this->mainoptions['dzsap_purchase_code'] . '&site_url=' . site_url().'&do_not_also_activate=on';
							$res = DZSHelpers::get_contents($aux);

//            echo 'hmm'; echo strpos($res,'<div class="error">'); echo 'dada'; echo $res;
							if ($res === false) {
								echo 'server offline';
							} else {
								if (strpos($res, '<div class="error">') === 0) {
									echo $res;


									if (strpos($res, '<div class="error">error: in progress') === 0) {

										$this->mainoptions['dzsap_purchase_code_binded'] = 'on';
										update_option($this->dboptionsname, $this->mainoptions);
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
											update_option($this->dboptionsname, $this->mainoptions);


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



						?>

                    </div>
                </div>
				<?php
			}
			?>

            <div class="dzs-col-md-4">

                <div class="white-bg">

                    <h4><?php echo __("One click sample data"); ?></h4>

                    <img src="http://i.imgur.com/g3TzzAX.png" class="fullwidth"/>

                    <p>
						<?php
						echo sprintf(__("Want to import some sample content from the video gallery demo ? Shortcode generator comes to your help with sample data. The sample data tab allows for quick one click import of some demos."));
						?>
                    </p>

                </div>
            </div>
            <div class="dzs-col-md-4 system-check">



                <div class="white-bg">

                    <h4><?php echo __("System Check"); ?></h4>

                    <div class="setting">

                        <h4 class="setting-label">GetText <?php echo __("Support"); ?></h4>


						<?php
						if (function_exists("gettext")) {
							echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> '.''.__("supported").'</div>';
						} else {

							echo '<div class="setting-text-notok">'.''.__("not supported").'</div>';
						}
						?>
                    </div>


                    <div class="setting">

                        <h4 class="setting-label">ZipArchive <?php echo __("Support"); ?></h4>


						<?php
						if (class_exists("ZipArchive")) {
							echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> '.''.__("supported").'</div>';
						} else {

							echo '<div class="setting-text-notok">'.''.__("not supported").'</div>';
						}
						?>
                    </div>
                    <div class="setting">

                        <h4 class="setting-label">Curl <?php echo __("Support"); ?></h4>


						<?php
						if (function_exists('curl_version')) {
							echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> '.''.__("supported").'</div>';
						} else {

							echo '<div class="setting-text-notok">'.''.__("not supported").'</div>';
						}
						?>

                    </div>
                    <div class="setting">

                        <h4 class="setting-label">allow_url_fopen <?php echo __("Support"); ?></h4>


						<?php
						if (ini_get('allow_url_fopen')) {
							echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> '.''.__("supported").'</div>';
						} else {

							echo '<div class="setting-text-notok">'.''.__("not supported").'</div>';
						}
						?>


                    </div>



                    <div class="setting">

                        <h4 class="setting-label">ZoomIt <?php echo __("Server Communication"); ?></h4>

                        <i class="fa-spin fa fa-circle-o-notch replace-with-server-communication-feedback"></i>


                    </div>



                    <div class="setting">

                        <h4 class="setting-label"><?php echo __("PHP Version"); ?></h4>

                        <div class="setting-text-ok">
							<?php
							echo phpversion();
							?>
                        </div>


                    </div>

                </div>
            </div>


        </div>

        <br>
        <a href="<?php echo admin_url('admin.php?page=dzsap_menu&donotshowaboutagain=on'); ?>" target=""
           class="button-primary action"><?php _e('Got it! Lets go.', 'dzsap'); ?></a>
    </div>
<?php
