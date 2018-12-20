<?php



$this->videoplayerconfig = '<div class="slider-con" style="display:none;">
        <div class="settings-con">';





$this->videoplayerconfig .= '
        <div class="dzs-tabs auto-init-from-nice">

                <div class="dzs-tab-tobe">
                    <div class="tab-menu with-tooltip">
                        <i class="fa fa-tachometer"></i>'.__("General").'
                    </div>
                    <div class="tab-content">
                        <br>




        
        <div class="setting type_all">
            <div class="setting-label">' . __('Config ID', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting main-id" name="0-settings-id" value="default"/>
            <div class="sidenote">' . __('Choose an unique id.', 'dzsap') . '</div>
        </div>
        
        
        
        
        
        <div class="setting styleme setting-skin_ap">
        
            <div class="setting-label">' . __('Audio Player Skin', 'dzsap') . '</div>
            <select class="dzs-style-me textinput opener-listbuttons mainsetting skin-gamma" name="0-settings-skin_ap">
                <option value="skin-wave"></option>
                <option value="skin-silver"></option>
                <option value="skin-aria"></option>
                <option value="skin-default"></option>
                <option value="skin-pro"></option>
                <option>skin-minimal</option>
                <option>skin-minion</option>
                <option>skin-justthumbandbutton</option>
                <option>skin-steel</option>
                <option>skin-customcontrols</option>
                <option>skin-customhtml</option>
            </select>
            <ul class="dzs-style-me-feeder">

                <div class="bigoption">
                    <span class="option-con"><img src="'.$this->base_url.'assets/svg/skin_wave.svg"><span class="option-label">Wave skin full</span></span>
                </div>
                
                <div class="bigoption">
                    <span class="option-con"><img src="'.$this->base_url.'assets/svg/skin_silver.svg"><span class="option-label">Silver skin</span></span>
                </div>
                <div class="bigoption">
                    <span class="option-con"><img src="'.$this->base_url.'assets/svg/skin_aria.svg"><span class="option-label">Aria skin</span></span>
                </div>
                <div class="bigoption">
                    <span class="option-con"><img src="'.$this->base_url.'assets/svg/skin_default.svg"><span class="option-label">Thumb skin</span></span>
                </div>
                <div class="bigoption">
                    <span class="option-con"><img src="'.$this->base_url.'assets/svg/skin_pro.svg"><span class="option-label">Pro skin</span></span>
                </div>
                <div class="bigoption">
                    <span class="option-con"><img src="'.$this->base_url.'assets/svg/skin_minimal.svg"><span class="option-label">'.esc_html__("Minimal",'dzsap').' '.esc_html__("skin",'dzsap').'</span></span>
                </div>
                <div class="bigoption">
                    <span class="option-con"><img src="'.$this->base_url.'assets/svg/skin_minion.svg"><span class="option-label">'.esc_html__("Minion",'dzsap').' '.esc_html__("skin",'dzsap').'</span></span>
                </div>
                <div class="bigoption">
                    <span class="option-con"><img src="'.$this->base_url.'assets/svg/skin_justthumbandbutton.svg"><span class="option-label">'.esc_html__("Thumb and button",'dzsap').' '.esc_html__("skin",'dzsap').'</span></span>
                </div>
                <div class="bigoption">
                    <span class="option-con"><img src="'.$this->base_url.'assets/svg/skin_steel.svg"><span class="option-label">'.esc_html__("Steel",'dzsap').' '.esc_html__("skin",'dzsap').'</span></span>
                </div>
                <div class="bigoption">
                    <span class="option-con"><img src="'.$this->base_url.'assets/svg/skin_customcontrols.svg"><span class="option-label">'.esc_html__("Custom controls",'dzsap').' '.esc_html__("skin",'dzsap').'</span></span>
                </div>
                <div class="bigoption">
                    <span class="option-con"><img src="'.$this->base_url.'assets/svg/skin_customhtml.svg"><span class="option-label">'.esc_html__("Custom html",'dzsap').' '.esc_html__("skin",'dzsap').'</span></span>
                </div>
            </ul>


        </div>
        
        
        
        
        
';





$dependency = array(

	array(
		'lab'=>'skin_ap',
		'val'=>array('skin-customcontrols','skin-customhtml'),
	),
	//                    'relation'=>'AND',
);


$this->videoplayerconfig.='<div class="setting styleme" data-dependency=\''.json_encode($dependency).'\'>
            <div class="setting-label">' . __("Extra HTML in Player", 'dzsap') . '</div>
            <textarea rows="5" type="text" style="width: 100%;" class="textinput mainsetting" name="0-settings-settings_extrahtml_in_player" ></textarea>
            <div class="sidenote">' . __('enable a embed button for visitors to be able the embed the player on their sites.', 'dzsap') . '</div>
        </div>';

$this->videoplayerconfig.='<div class="setting styleme" >
            <div class="setting-label">' . __('Enable Embed Button', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_embed_button">
                <option value="off">'.__("Disable").'</option>
                <option value="in_player_controls">'.__("In player controls").'</option>
                <option value="in_extra_html">'.__("Below player").'</option>
                <option value="in_lightbox">'.__("In multisharer").'</option>
            </select>
            <div class="sidenote">' . __('enable a embed button for visitors to be able the embed the player on their sites. ( for multisharer, enable multisharer button below ) ', 'dzsap') . '</div>
        </div>
        
        
        
                    
        <div class="setting styleme">
            <div class="setting-label">' . __('Hover to Play', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-preview_on_hover">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('zoomsounds offers the possibility to play tracks on hover', 'dzsap') . '</div>
        </div>
        
        
        
        
        
        
        <div class="setting styleme">
            <div class="setting-label">' . __('Loop', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-loop">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('Loop the track on song end', 'dzsap') . '</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Preload Method', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-preload_method">
                <option>metadata</option>
                <option>auto</option>
                <option>none</option>
            </select>
            <div class="sidenote">' . __('none - preload no info / metadata - preload only metadata ( total time and thumbnail ) / auto - preload the whole track ', 'dzsap') . '</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Cue Media', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-cue_method">
                <option>on</option>
                <option>off</option>
            </select>
            <div class="sidenote">' . __('settings this to OFF will not load the media at all, not even the metadata', 'dzsap') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Play From', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-playfrom" value="off"/>
            <div class="sidenote">' . __('This is a default setting, it can be changed individually per item ( it will be overwritten if set ) . - choose a number of seconds from which the track to play from ( for example if set "70" then the track will start to play from 1 minute and 10 seconds ) or input "last" for the track to play at the last position where it was.', 'dzsap') . '</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Default Volume', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-default_volume" value="default"/>
            <div class="sidenote">' . __('number / set the default volume 0-1 or "last" for the last known volume', 'dzsap') . '</div>
        </div>
        
        
        
        
        
        ';





$lab = 'menu_right_enable_info_btn';
$this->videoplayerconfig.='
        <div class="setting styleme">
            <div class="setting-label">' . __('Enable Info Button in Player', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-'.$lab.'">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('deprecated - use universal share instead ', 'dzsap') . '</div>
        </div>';



$lab = 'menu_right_enable_multishare';
$this->videoplayerconfig.='
        <div class="setting styleme">
            <div class="setting-label">' . __('Enable Universal Share in Player', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-'.$lab.'">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('enable a button that brings up a lightbox with all share options', 'dzsap') . '</div>
        </div>';

$lab = 'player_navigation';
$this->videoplayerconfig.='
        <div class="setting styleme">
            <div class="setting-label">' . __('Player Navigation', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-'.$lab.'">
                <option value="default">'.__("Detect").'</option>
                <option value="off">'.__("Disable").'</option>
                <option value="on">'.__("Force On").'</option>
            </select>
            <div class="sidenote">' . __('show or not the left and right arrows alongside the play button - leave default for the player to auto detect if it needs them', 'dzsap') . '</div>
        </div>';

$lab = 'footer_btn_playlist';
$this->videoplayerconfig.='
        <div class="setting styleme">
            <div class="setting-label">' . __('Sticky player playlist button', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-'.$lab.'">
                <option value="off">'.__("Disable").'</option>
                <option value="on">'.__("Enable").'</option>
            </select>
            <div class="sidenote">' . __('show a playlist button in the footer player', 'dzsap') . '</div>
        </div>';


$this->videoplayerconfig.='



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
                        <i class="fa fa-paint-brush"></i>'.__("Styling").'
                    </div>
                    <div class="tab-content">
                    
                    
                    
        <div class="setting styleme">
            <div class="setting-label">' . __('Animate Play Pause', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-design_animateplaypause">
                <option>default</option>
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('fade animation on play / pause', 'dzsap') . '</div>
        </div>
        
        
        
        
        
        ';






$lab = 'enable_footer_close_button';
$this->videoplayerconfig.='
        <div class="setting styleme">
            <div class="setting-label">' . esc_html__('Enable Footer Close Button', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-'.$lab.'">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('only for footer players', 'dzsap') . '</div>
        </div>';

$lab = 'disable_scrubbar';
$this->videoplayerconfig.='
        <div class="setting styleme">
            <div class="setting-label">' . __('Disable Scrubbar', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-'.$lab.'">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('disable the scrubbar / wave', 'dzsap') . '</div>
        </div>';


$this->videoplayerconfig.='
        <div class="setting styleme">
            <div class="setting-label">' . __('Disable Volume', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-disable_volume">
                <option value="default">'.__("Detect").'</option>
                <option value="off">'.__("Disable").'</option>
                <option value="on">'.__("Force On").'</option>
            </select>
            <div class="sidenote">' . __('disable the volume bar if set to "on". set to skin default when "default" is set.', 'dzsap') . '</div>
        </div>';




// 111111

$this->videoplayerconfig.='<div class="setting type_all"><div class="label">'.__("Highlight Color").'</div><input type="text" name="0-settings-colorhighlight" class="textinput mainsetting colorpicker-nohash with_colorpicker" value=""/><div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div><div class="sidenote">' . __('Only for <strong>skin-wave</strong>', 'dzsap') . '</div></div>';



//ob_start();



/*
 * <div class="sidenote">'.sprintf(__("you can input a gradient by inputing %s with your colors",'dzsap'),'<strong>000000,ffffff</strong>').'</div>
 */

                        $val = '';

                        $lab = '0-settings-'.'color_waveformbg';

$this->videoplayerconfig.= ' <div class="setting type_all">
                    <div class="label">' . __('Waveform BG Color', 'dzsap') . '</div>
                    <input type="text" name="'.$lab.'" class="textinput mainsetting colorpicker-nohash with_colorpicker" value=""/><div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
                </div>';


                        $lab = '0-settings-'.'color_waveformprog';

$this->videoplayerconfig.= ' <div class="setting type_all">
                    <div class="label">' . __('Waveform Progress Color', 'dzsap') . '</div>
                    <input type="text" name="'.$lab.'" class="textinput mainsetting colorpicker-nohash with_colorpicker" value=""/><div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
                </div>';

//                        $val = '';
//
//$lab = '0-settings-'.'color_waveformprog';
//
//$this->videoplayerconfig.= '<div class="setting">
//                    <h4 class="label">' . __('Waveform Progress Color', 'dzsap') . '</h4>
//                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $val, 'type' => 'colorpicker', 'class' => 'colorpicker-nohash')) . '
//                </div>';

//$this->videoplayerconfig.=ob_get_clean();




$this->videoplayerconfig.='
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    
                    
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
                        <i class="fa fa-bar-chart"></i>Skin-Wave
                    </div>
                    <div class="tab-content">
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    <div class="setting styleme">
            <div class="setting-label">' . __('Dynamic Waves', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-skinwave_dynamicwaves">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('*only on skin-wave - dynamic waves that act on volume change', 'dzsap') . '</div>
        </div>';




ob_start();

?><div class="setting">
    <h4 class="label"><?php echo __('Waveform Mode','dzsap'); ?></h4>
	<?php



	$lab = '0-settings-'.'skinwave_wave_mode';

	$opts = array(
		array(
			'lab'=>__("Default"),
			'val'=>''
		),
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


	echo DZSHelpers::generate_select($lab,array('class' => ' dzs-dependency-field  styleme','options' => $opts,'seekval' => 'canvas')); ?>


    <div class="sidenote"><?php echo __("this is the wave style "). '<br>'; printf("<strong> %s </strong> - %s <br>", __("Image"), __("is just a image png that must be generated from the backend")  );  echo sprintf("<strong> %s </strong> - %s <br>", __("Canvas"), __("is a new and more immersive mode to show the waves. you can control color more easily, reflection size and wave bars number"));  ?></div>
    </div>


<?php


?>
    <div class="setting" >
        <h4 class="label"><?php echo __('Reflection Size','dzsap'); ?></h4>
		<?php



		$lab = '0-settings-'.'skinwave_wave_mode_canvas_reflection_size';

		$opts = array(
			array(
				'lab'=>__("Default"),
				'val'=>''
			),
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


		echo DZSHelpers::generate_select($lab,array('class' => ' styleme','options' => $opts,'seekval' => '0.25')); ?>


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
    <div class="setting" >
        <h4 class="label"><?php echo __('Waves Number','dzsap'); ?></h4>
		<?php




		$lab = '0-settings-'.'skinwave_wave_mode_canvas_waves_number';

		echo DZSHelpers::generate_input_text($lab,array(
			'class' => 'disabled ',
			'type' => 'text',
			
			'seekval' => ''
		)); ?>


        <div class="sidenote"><?php echo sprintf(__("%s - %s default global option %s
                            %s - %s pixel %s
                            %s - %s pixels %s
                            %s - %s pixels %s
                            %s - means that there will be the number of waves that you set - for example 100 means 100 waves
                            ")
					,'<strong></strong>'
					,'leave nothing and option will come from global settings'
					,'<br>'
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
    <div class="setting" >
        <h4 class="label"><?php echo __('Waves Bar Spacing','dzsap'); ?></h4>
		<?php




		$lab = '0-settings-'.'skinwave_wave_mode_canvas_waves_padding';

		echo DZSHelpers::generate_input_text($lab,array(
			'class' => 'disabled ',
			'type' => 'text',

			'seekval' => ''
		)); ?>


        <div class="sidenote"><?php echo __("spacing between bars - leave nothing here and this will come from global"). '';  ?></div>
    </div>







<?php

;
?>
    <div class="setting" >
    <h4 class="label"><?php echo __('Normalize ','dzsap'); ?></h4>
	<?php



	$lab = '0-settings-'.'skinwave_wave_mode_canvas_normalize';

	$opts = array(
		array(
			'lab'=>__("Default"),
			'val'=>''
		),
		array(
			'lab'=>__("Normalize Waves"),
			'val'=>'on'
		),
		array(
			'lab'=>__("Do not normalize"),
			'val'=>'off'
		),

	);


	echo DZSHelpers::generate_select($lab,array('class' => ' styleme','options' => $opts,'seekval' => 'on')); ?>


    <div class="sidenote"><?php echo __("normalize the waves to look like they have continuity , or disable normalizing to make the waveforms follow the real sound"). '';  ?></div>
    </div><?php
$this->videoplayerconfig.=ob_get_clean();



$this->videoplayerconfig.='<div class="setting styleme">
            <div class="setting-label">' . __('Enable Spectrum', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-skinwave_enablespectrum">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('*only on skin-wave - enable a realtime spectrum analyzer instead of the static generated waveform / the file must be on the same server for security issues', 'dzsap') . '</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Enable Reflect', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-skinwave_enablereflect">
                <option>on</option>
                <option>off</option>
            </select>
            <div class="sidenote">' . __('*only on skin-wave - enable a small reflection of the waves / spectrum', 'dzsap') . '</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Enable Commenting', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-skinwave_comments_enable">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('*only on skin-wave - enable time-based commenting', 'dzsap') . '</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Skin layout', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-skinwave_mode">
                <option value="normal">' . __('Normal', 'dzsap') . '</option>
                <option value="small">' . __('Slick', 'dzsap') . '</option>
                <option value="alternate">' . __('Alternate', 'dzsap') . '</option>
                <option value="nocontrols">' . __('Just Wave', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . __('choose the normal or slick theming', 'dzsap') . '</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Wave Mode', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-skinwave_wave_mode_canvas_mode">
                <option value="normal">' . __('Bar', 'dzsap') . '</option>
                <option value="reflecto">' . __('Wave', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . __('choose a bar type format or a wave for the waveform style', 'dzsap') . '</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Button Style', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-button_aspect">
                <option value="default">' . __('Default', 'dzsap') . '</option>
                <option value="button-aspect-noir">' . __('Aspect Noir', 'dzsap') . '</option>
                <option value="button-aspect-noir button-aspect-noir--filled">' . __('Aspect Noir Filled', 'dzsap') . '</option>
                <option value="button-aspect-noir button-aspect-noir--stroked">' . __('Aspect Noir Stroked', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . __('Button Style', 'dzsap') . '</div>
            <p><img src="http://i.imgur.com/aVIk654.png"/> <img src="http://i.imgur.com/oVUgjff.png"/> </p>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Tweak the Bar Aligment ', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-scrubbar_tweak_overflow_hidden">
                <option value="off">' . __('Off', 'dzsap') . '</option>
                <option value="on">' . __('On', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . __('set this to <strong>on</strong> to get better animation on changing songs ( recommended only if you are changing songs with a footer player ) ', 'dzsap') . '</div>
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
                        <i class="fa fa-puzzle-piece"></i>'.__("Misc").'
                    </div>
                    <div class="tab-content">
                    
                    
                    
        
                    
                    
                    
                    
        <div class="setting type_all">
            <div class="setting-label">' . __('Extra Classes for Player', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-extra_classes_player" />
            <div class="sidenote">' . __('extra classes ', 'dzsap') . '</div>
        </div>
                    
                    
                    
                    
                    
        <div class="setting type_all">
            <div class="setting-label">' . __('Classes for window width under 400 px ', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-restyle_player_under_400" />
            <div class="sidenote">' . __('developers only - ', 'dzsap') . '* - ' . __('apply some special classes for when the viewport is under 400px (mobiles view)', 'dzsap') . '</div>
        </div>
                    
                    
                    
                    
        <div class="setting type_all">
            <div class="setting-label">' . __('Classes for window width over 400 px ', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-restyle_player_over_400" />
            <div class="sidenote">' . __('developers only - ', 'dzsap') . '* - ' . __('apply some special classes for when the viewport is over 400px (desktop / tablet view)', 'dzsap') . ' / ' . __('remember that in order for the mobile setting to work, this must have input also', 'dzsap') . '</div>
        </div>
                    
                    
                    
                    
        <div class="setting type_all">
            <div class="setting-label">' . __('Extra HTML After Artist', 'dzsap') . '</div>
            <textarea rows="5" type="text" style="width: 100%;" class="textinput mainsetting" name="0-settings-settings_extrahtml_after_artist" ></textarea>
            <div class="sidenote">' . __('extra html on the rift of the artist field ( first line ) ', 'dzsap') . '</div>
        </div>
                    
                    
                    
                    
                    
        <div class="setting type_all">
            <div class="setting-label">' . __('Extra HTML in Right Controls', 'dzsap') . '</div>
            <textarea rows="5" type="text" style="width: 100%;" class="textinput mainsetting" name="0-settings-js_settings_extrahtml_in_float_right_from_config" ></textarea>
            <div class="sidenote">' . __('extra html on the in the right controls ', 'dzsap') . '</div>
        </div>
                    
                    
                    
                    
                    
        <div class="setting type_all">
            <div class="setting-label">' . __('Extra HTML in Bottom Controls', 'dzsap') . '</div>
            <textarea rows="5" type="text" style="width: 100%;" class="textinput mainsetting" name="0-settings-js_settings_extrahtml_in_bottom_controls_from_config" ></textarea>
            <div class="sidenote">' . __('extra html on the in the Bottom controls ', 'dzsap') . '</div>
        </div>
                    
                    
                    
                    
                    
        <div class="setting type_all">
            <div class="setting-label">' . __('Extra HTML in After Play Button', 'dzsap') . '</div>
            <textarea rows="5" type="text" style="width: 100%;" class="textinput mainsetting" name="0-settings-settings_extrahtml_after_playpause_from_config" ></textarea>
            <div class="sidenote">' . __('Extra HTML in After Play Button', 'dzsap') . '</div>
        </div>
                    
                    
        <div class="setting type_all">
            <div class="setting-label">' . __('Extra HTML after Controls', 'dzsap') . '</div>
            <textarea rows="5" type="text" style="width: 100%;" class="textinput mainsetting" name="0-settings-settings_extrahtml_after_con_controls_from_config" ></textarea>
            <div class="sidenote">' . __('Extra HTML after Controls', 'dzsap') . '</div>
        </div>
                    
                    
                    </div>
                    
                    </div>
                    
                    
                    
                    
                    </div><!-- end .tabs-->
        
        
        ';

/*
*/

$this->videoplayerconfig.='

        
        ';



$val = 'ea8c52';

//        <h3>Wave Form Options</h3>
//</div>
//
//        <div class="setting">
//<div class="label">' . __('Highlight Color', 'dzsap') . '</div>





$this->videoplayerconfig.='


        </div><!--end settings con-->
        <div class="clearboth"></div>
        </div>';


