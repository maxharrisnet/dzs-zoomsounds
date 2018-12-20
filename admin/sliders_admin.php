<?php






// -- in action init

//add_action( 'dzsap_sliders_add_form_fields', 'dzsap_sliders_admin_add_feature_group_field', 10, 2 );
add_action( 'dzsap_sliders_edit_form_fields', 'dzsap_sliders_admin_add_feature_group_field', 10, 10 );

add_filter('dzsap_sliders_row_actions', 'dzsap_sliders_admin_duplicate_post_link', 10, 2);
add_action('admin_action_dzsap_duplicate_slider_term', 'dzsap_action_dzsap_duplicate_slider_term', 10, 2);





add_action('admin_init', 'dzsap_sliders_admin_init',1000);



function dzsap_sliders_admin_init(){

	global $dzsap;
	$tax = 'dzsap_sliders';
	if (  ( isset($_REQUEST['action']) && 'dzsap_duplicate_slider_term' == $_REQUEST['action'] ) ) {

		if(! ( isset( $_GET['term_id']) || isset( $_POST['term_id']) )){
			wp_die("no term_id set");
		}






		/*
		 * get the original post id
		 */
		$term_id = (isset($_GET['term_id']) ? absint( $_GET['term_id'] ) : absint( $_POST['term_id'] ) );

		$term_meta = get_option("taxonomy_$term_id");

		/*
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;



		 * Nonce verification
		 */
		if ( isset($_GET['duplicate-nonce-for-term-id-'.$term_id]) &&  wp_verify_nonce( $_GET['duplicate-nonce-for-term-id-'.$term_id],'duplicate-nonce-for-term-id-'.$term_id ) ){





			$args = array(
				'post_type' => 'dzsap_items',
				'tax_query' => array(
					array(
						'taxonomy' => 'dzsap_sliders',
						'field' => 'id',
						'terms' => $term_id
					)
				),
			);
			$query = new WP_Query( $args );



			$reference_term = get_term($term_id,$tax);


			$reference_term_name = $reference_term->name;
			$reference_term_slug = $reference_term->slug;

//			    print_rr($reference_term_name);
//			    print_rr($reference_term_slug);
//			    print_rr($query);


			$new_term_name = $reference_term_name.' '.esc_html("Copy",'dzsap');
			$new_term_slug = $reference_term_slug.'-copy';
			$original_slug_name = $reference_term_slug.'-copy';


			$ind = 1;
			$breaker = 100;
			while(1){

				$term = term_exists($new_term_slug, $tax);
				if ($term !== 0 && $term !== null) {

					$ind++;
					$new_term_slug=$original_slug_name.'-'.$ind;
				}else{
					break;
				}

				$breaker--;

				if($breaker<0){
					break;
				}
			}


			$new_term = wp_insert_term(
				$new_term_name, // the term
				$tax, // the taxonomy
				array(

					'slug' => $new_term_slug,
				)
			);



			foreach ($query->posts as $po){


				$dzsap->duplicate_post($po->ID, array(
					'new_term_slug'=>$new_term_slug,
					'call_from'=>'default',
					'new_tax'=>$tax,
				));



			}

//			$new_term = get_term_by('slug',$new_term_slug,$tax);

//            error_log(print_rr($new_term,array('echo'=>false)));
			$new_term_id = $new_term['term_id'];


			update_option("taxonomy_$new_term_id", $term_meta);
			wp_redirect( admin_url( 'term.php?taxonomy='.$tax.'&tag_ID='.$new_term_id.'&post_type=dzsap_items' ) );

			exit;





//		exit;
		} else {
			$aux = ('invalid nonce for term_id' . $term_id . 'duplicate-nonce-for-term-id-'.$term_id);

			$aux.=print_rr($_SESSION);

			$aux.=' searched nonce - '.$_GET['duplicate-nonce-for-term-id-'.$term_id];
			$aux.=' searched nonce verify - '.wp_verify_nonce( $_GET['duplicate-nonce-for-term-id-'.$term_id],'duplicate-nonce-for-term-id-'.$term_id );


			wp_die($aux);
		}
	}


	// -- export
	if (  ( isset($_REQUEST['action']) && 'dzsap_export_slider_term' == $_REQUEST['action'] ) ) {


		/*
		 * get the original post id
		 */
		$term_id = (isset($_GET['term_id']) ? absint( $_GET['term_id'] ) : absint( $_POST['term_id'] ) );





		$arr_export = $dzsap->playlist_export($term_id, array(
			'download_export'=>true
		));
		echo json_encode($arr_export);
		die();




		exit;

	}







	// -- import

	if(isset($_POST['action']) && $_POST['action']=='dzsap_import_slider'){

		if(isset($_FILES['dzsap_import_slider_file'])){

			$file_arr = $_FILES['dzsap_import_slider_file'];

			$file_cont = file_get_contents($file_arr['tmp_name'],true);

//			print_rr($file_cont);



			$type = 'none';


			try{

				$arr = json_decode($file_cont,true);

				error_log( 'content - '. print_rr($arr,true));

				if($arr && is_array($arr)){

					$type = 'json';
				}else{
					$arr = unserialize($file_cont);


					error_log( 'content - '. print_rr($arr,true));
					$type = 'serial';
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


						$term = term_exists($new_term_name, $tax);
						if ($term !== 0 && $term !== null) {


							$new_term_name=$original_name.'-'.$ind;
							$new_term_slug=$original_slug.'-'.$ind;
							$ind++;


							while(1){

								$term = term_exists($new_term_name, $tax);
								if ($term !== 0 && $term !== null) {

									$new_term_name=$original_name.'-'.$ind;
									$new_term_slug=$original_slug.'-'.$ind;
									$ind++;
								}else{
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
							error_log(' .. the name is '.$new_term_name);
							error_log(print_r($new_term,true));
						}



						$term_meta = array_merge(array(), $arr['term_meta']);

						unset($term_meta['items']);

						update_option("taxonomy_$new_term_id", $term_meta);


						foreach ($arr['items'] as $po){

							$args = array_merge(array(), $po);

							$args['term']=$new_term_slug;
							$args['taxonomy']=$tax;

							$args['call_from']='sliders_admin import slider_file';
							$dzsap->import_demo_insert_post_complete($args);



						}

//			$new_term = get_term_by('slug',$new_term_slug,$tax);

//            error_log(print_rr($new_term,array('echo'=>false)));














					}



					// -- legacy
					if($type=='serial'){


						$new_term_id = '';
						$new_term = null;
						$original_slug = '';


						foreach ($arr as $lab=>$val){


							if($lab==='settings'){





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


								// -- admin init
								foreach ($dzsap->options_item_meta as $oim){
									$long_name = $oim['name'];

									$short_name = str_replace('dzsap_meta_','',$oim['name']);


									$args[$long_name] = $args[$short_name];
								}



								$dzsap->import_demo_insert_post_complete($args);

							}



						}
					}
				}
			}catch(Exception $err){
				print_rr($err);
			}
		}
	}
}





function dzsap_action_dzsap_duplicate_slider_term(  ) {




}
function dzsap_sliders_admin_duplicate_post_link( $actions, $term ) {

//    error_log(print_rr($term,array('echo'=>false)));
	if (current_user_can('edit_posts')) {



		// Create an nonce, and add it as a query var in a link to perform an action.
		$nonce = wp_create_nonce( 'duplicate-nonce-for-term-id-'.$term->term_id );

		$actions['duplicate'] = '<a href="' . admin_url('edit-tags.php?taxonomy=dzsap_sliders&post_type=dzsap_items&action=dzsap_duplicate_slider_term&term_id=' . $term->term_id) . '&duplicate-nonce-for-term-id-' . ($term->term_id) . '='.$nonce.'" title="Duplicate this item" rel="permalink">'.esc_html("Duplicate",'dzsap').'</a>';
	}


	$actions['export'] = '<a href="' . admin_url('edit-tags.php?taxonomy=dzsap_sliders&post_type=dzsap_items&action=dzsap_export_slider_term&term_id=' . $term->term_id) . '" title="Duplicate this item" rel="permalink">'.esc_html("Export",'dzsap').'</a>';





	return $actions;
}


function dzsap_sliders_admin(){

	if(isset($_GET['taxonomy']) && $_GET['taxonomy']=='dzsap_sliders' ){


		//&& isset($_GET['tag_ID'])
		global $dzsap;














		$tax = 'dzsap_sliders';














//        echo 'here <strong>sliders_admin.php</strong> ';

		wp_enqueue_script('sliders_admin',$dzsap->base_url.'admin/sliders_admin.js');
		wp_enqueue_script('dzstaa',$dzsap->base_url.'libs/dzstabsandaccordions/dzstabsandaccordions.js');
		wp_enqueue_style('dzstaa',$dzsap->base_url.'libs/dzstabsandaccordions/dzstabsandaccordions.css');
		wp_enqueue_script('dzs.farbtastic', $dzsap->base_url . "libs/farbtastic/farbtastic.js");
		wp_enqueue_style('dzs.farbtastic', $dzsap->base_url . 'libs/farbtastic/farbtastic.css');


		$terms = get_terms( $tax, array(
			'hide_empty' => false,
		) );

//        print_r($terms);




		$i23=0;

//	    $dzsap->db_read_mainitems();

//        print_r($dzsap);

//	    print_rr($dzsap->options_slider);



		$selected_term = null;
		$curr_term = null;
		$selected_term_id = '';
		$selected_term_name = '';
		$selected_term_slug = '';
		if(isset($_GET['tag_ID'])){

			$curr_term = get_term($_GET['tag_ID'], $tax);


			if(isset($curr_term)){

				$selected_term_id = $curr_term->term_id;
				$selected_term_name = $curr_term->name;
				$selected_term_slug = $curr_term->slug;
			}




			if(isset($_GET['tag_ID'])){
				$selected_term = $_GET['tag_ID'];

//		        $term = get_term($_GET['tag_ID'], $tax);
//		        $selected_term_name = $term->name;

//            print_r($term);
			}
		}




//        echo $selected_term;


		?>



    <div class="dzsap-sliders-con" data-term_id="<?php echo $selected_term_id; ?>" data-term-slug="<?php echo $selected_term_slug; ?>">

        <h3 class="slider-label" style="font-weight: normal">
            <span><?php echo __("Editing "); ?></span><span style="font-weight: bold;"><?php echo $selected_term_name; ?></span> <span class="slider-status empty ">
                <div class="slider-status--inner loading"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i> <span class="text-label"><?php echo __("Saving"); ?></span></div>
            </span>
        </h3>




        <div class="dzsap-slider-items">

		<?php

		if($selected_term){

			$args = array(
				'post_type'     => 'dzsap_items',
				'numberposts' => -1,
				'posts_per_page' => '-1',
				//                'meta_key' => 'dzsap_meta_order_'.$selected_term,

				'orderby' => 'meta_value_num',
				'order' => 'ASC',
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key'=>'dzsap_meta_order_'.$selected_term,
						//                        'value' => '',
						'compare' => 'EXISTS',
					),
					array(
						'key'=>'dzsap_meta_order_'.$selected_term,
						//                        'value' => '',
						'compare' => 'NOT EXISTS'
					)
				),
				'tax_query' => array(
					array(
						'taxonomy' => $tax,
						'field' => 'id',
						'terms' => $selected_term // Where term_id of Term 1 is "1".
					)
				),
			);

			$my_query = new WP_Query( $args );

//            print_r($my_query);


//            print_r($my_query->posts);

			foreach ($my_query->posts as $po){

//                print_r($po);
				echo $dzsap->sliders_admin_generate_item($po);


			}
			$object = (object) [
				'ID' => 'placeholder',
				'post_title' => 'placeholder',
			];
//           echo  $dzsap->sliders_admin_generate_item($object)
			?>

            </div>

            <div class="add-btn">
                <i class="fa fa-plus-circle add-btn--icon"></i>
                <div class="add-btn-new button-secondary"><?php echo __("Create New Item"); ?></div>
                <div class="add-btn-existing add-btn-existing-media upload-type-audio button-secondary"><?php echo __("Add From Library"); ?></div>
            </div>

            <br>
            <br>




            <div id="tabs-box" class="dzs-tabs  skin-qcre " data-options='{ "design_tabsposition" : "top"
,"design_transition": "fade"
,"design_tabswidth": "default"
,"toggle_breakpoint" : "400"
,"settings_appendWholeContent": "true"
,"toggle_type": "accordion"
}
'>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu ">
						<?php
						echo esc_html__("Main Settings",'dzsap');
						?>
                    </div>
                    <div class="tab-content tab-content-cat-main">


                    </div>
                </div>



				<?php
				foreach ($dzsap->options_slider_categories_lng as $lab=>$val){



					?>

                    <div class="dzs-tab-tobe">
                    <div class="tab-menu ">
						<?php
						echo ($val);
						?>
                    </div>
                    <div class="tab-content tab-content-cat-<?php echo $lab; ?>">


                        <table class="form-table custom-form-table sa-category-<?php echo $lab; ?>">
                            <tbody>
							<?php
							dzsap_sliders_admin_parse_options($curr_term,$lab);
							?>
                            </tbody>

                        </table>

                    </div>
                    </div><?php

				}
				?>




            </div>





            <div class="dzsap-sliders">
                <table class="wp-list-table widefat fixed striped tags">
                    <thead>
                    <tr>




                        <th scope="col" id="name" class="manage-column column-name column-primary sortable desc"><a href="http://localhost/wordpress/wp-admin/edit-tags.php?taxonomy=dzsap_sliders&amp;post_type=dzsap_items&amp;orderby=name&amp;order=asc"><span>Name</span><span class="sorting-indicator"></span></a></th>




                        <th scope="col" id="slug" class="manage-column column-slug sortable desc"><a href="http://localhost/wordpress/wp-admin/edit-tags.php?taxonomy=dzsap_sliders&amp;post_type=dzsap_items&amp;orderby=slug&amp;order=asc"><span><?php echo __("Edit"); ?></span><span class="sorting-indicator"></span></a></th>

                        <th scope="col" id="posts" class="manage-column column-posts num sortable desc"><a href="http://localhost/wordpress/wp-admin/edit-tags.php?taxonomy=dzsap_sliders&amp;post_type=dzsap_items&amp;orderby=count&amp;order=asc"><span>Count</span><span class="sorting-indicator"></span></a></th>	</tr>
                    </thead>

                    <tbody id="the-list" data-wp-lists="list:tag">


					<?php


					foreach ($terms as $tm){

						?>


                        <tr id="tag-<?php echo $tm->term_id; ?>">

                            <td class="name column-name has-row-actions column-primary" data-colname="Name"><strong>
                                    <a class="row-title" href="<?php echo site_url(); ?>/wp-admin/term.php?taxonomy=dzsap_sliders&amp;tag_ID=<?php echo $tm->term_id; ?>&amp;post_type=dzsap_items&amp;wp_http_referer=%2Fwordpress%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Ddzsap_sliders%26post_type%3Ddzsap_items" aria-label="“<?php echo $tm->name; ?>” (Edit)"><?php echo $tm->name; ?></a></strong>
                                <br>
                                <div class="hidden" id="inline_<?php echo $tm->term_id; ?>">

                                    <div class="name"><?php echo $tm->name; ?></div><div class="slug"><?php echo $tm->slug; ?></div><div class="parent">0</div></div><div class="row-actions">

                                    <span class="edit"><a href="<?php echo site_url(); ?>/wp-admin/term.php?taxonomy=dzsap_sliders&amp;tag_ID=<?php echo $tm->term_id; ?>&amp;post_type=dzsap_items&amp;wp_http_referer=%2Fwordpress%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Ddzsap_sliders%26post_type%3Ddzsap_items" aria-label="Edit “Test 1”">Edit</a> | </span>

                                    <span class="delete"><a href="edit-tags.php?action=delete&amp;taxonomy=dzsap_sliders&amp;tag_ID=<?php echo $tm->term_id; ?>&amp;_wpnonce=<?php echo wp_create_nonce('delete-tag_' . $tm->term_id); ?>" class="delete-tag aria-button-if-js" aria-label="Delete “<?php echo $tm->name; ?>”" role="button">Delete</a> | </span><span class="view"><a href="<?php echo site_url(); ?>/audio-sliders/test-1/" aria-label="View “Test 1” archive">View</a></span></div><button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td>

                            <td class="description column-description" data-colname="Description">Edit</td>

                            <td class="slug column-slug" data-colname="Slug"><?php echo $tm->count; ?></td>
                        </tr>
						<?php
					}
					?>



                    </tbody>



                </table>

            </div>




            </div>




			<?php
		}else{
			echo '</div></div>';
			?>


            <form class="import-slider-form" style="display: none;" enctype="multipart/form-data" action="" method="POST">
                <h3>Import Slider</h3>
                <p><input name="dzsap_import_slider_file" type="file" size="10"/></p>
                <button class="button-secondary" type="submit" name="action" value="dzsap_import_slider"  ><?php echo esc_html__("Import"); ?></button>
                <div class="clear"></div>
				<?php





				?>
            </form>
			<?php
		}
		?>
        <div class="feedbacker"><?php echo esc_html__("Loading..."); ?></div><?php
	}
}







function dzsap_sliders_admin_add_feature_group_field($term) {

//    echo 'cevadada';





	global $dzsap;





//    error_log('ceva3');






	$arr_off_on  =array(
		array(
			'label'=>esc_html__("Off",'dzsap'),
			'value'=>'off',
		),
		array(
			'label'=>esc_html__("On",'dzsap'),
			'value'=>'on',
		),
	);





	$dzsap->options_slider = array(



		array(
			'name'=>'galleryskin',
			'type'=>'select',
			'category'=>'main',
			'select_type'=>'opener-listbuttons',
			'title'=>esc_html__('Gallery Skin','dzsap'),
			'extra_classes'=>'opener-listbuttons-flex-full',
			'sidenote'=>__("select the type of media"),
			'choices'=>array(
				array(
					'label'=>esc_html__("Wave",'dzsap'),
					'value'=>'skin-wave',
				),
				array(
					'label'=>esc_html__("Default",'dzsap'),
					'value'=>'skin-default',
				),
				array(
					'label'=>esc_html__("Aura",'dzsap'),
					'value'=>'skin-aura',
				),
			),
			'choices_html'=>array(
				'<span class="option-con"><img src="'.$dzsap->base_url.'img/galleryskin-wave.jpg"/><span class="option-label">'.esc_html__("Wave",'dzsap').'</span></span>',
				'<span class="option-con"><img src="'.$dzsap->base_url.'img/galleryskin-default.jpg"/><span class="option-label">'.esc_html__("Default",'dzsap').'</span></span>',
				'<span class="option-con"><img src="'.$dzsap->base_url.'img/galleryskin-aura.jpg"/><span class="option-label">'.esc_html__("Aura",'dzsap').'</span></span>',
			),


		),



		array(
			'name'=>'vpconfig',
			'title'=>esc_html__('Player Configuration','dzsap'),
			'description'=>esc_html__('choose the gallery skin','dzsap'),
			'type'=>'select',
			'category'=>'main',
			'options'=>array(
			),
		),
		array(
			'name'=>'mode',
			'title'=>esc_html__('Mode','dzsap'),
			'description'=>esc_html__('choose the gallery mode','dzsap'),
			'type'=>'select',
			'category'=>'main',
			'options'=>array(
				array(
					'label'=>esc_html__("Default",'dzsap'),
					'value'=>'mode-normal',
				),
				array(
					'label'=>esc_html__("Show all",'dzsap'),
					'value'=>'mode-showall',
				),
			),
		),
		array(
			'name'=>'settings_mode_showall_show_number',
			'title'=>esc_html__('Mode Showall Number','dzsap'),
			'description'=>esc_html__('display the number','dzsap'),
			'type'=>'select',
			'category'=>'main',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsap'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsap'),
					'value'=>'on',
				),
			),
			'dependency'=>array(
				array(
					'element'=>'term_meta[mode]',
					'value'=>array('mode-showall'),
				),

			),
		),
		array(
			'name'=>'enable_linking',
			'title'=>esc_html__('Linking','dzsap'),
			'description'=>esc_html__('choose the gallery skin','dzsap'),
			'type'=>'select',
			'category'=>'main',
			'options'=>$arr_off_on,
		),
		array(
			'name'=>'orderby',
			'title'=>esc_html__('Order by','dzsap'),
			'description'=>esc_html__('choose an order','dzsap'),
			'type'=>'select',
			'category'=>'main',
			'options'=>array(
				array(
					'label'=>esc_html__("Custom",'dzsap'),
					'value'=>'custom',
				),
				array(
					'label'=>esc_html__("Random",'dzsap'),
					'value'=>'rand',
				),
				array(
					'label'=>esc_html__("Ratings score",'dzsap'),
					'value'=>'ratings_score',
				),
				array(
					'label'=>esc_html__("Ratings number",'dzsap'),
					'value'=>'ratings_number',
				),
			),

		),

		array(
			'name'=>'bgcolor',
			'title'=>esc_html__('Background Color','dzsap'),
			'category'=>'appearence',
			'description'=>esc_html__('for tag color ','dzsap'),
			'type'=>'color',
		),
		array(
			'name'=>'disable_player_navigation',
			'title'=>esc_html__('Disable Player Navigation','dzsap'),
			'category'=>'appearence',
			'description'=>esc_html__('Disable arrows for gallery navigation on the player','dzsap'),
			'type'=>'select',
			'options'=>$arr_off_on,
		),
		array(
			'name'=>'enable_bg_wrapper',
			'title'=>esc_html__('Enable background wrapper','dzsap'),
			'category'=>'appearence',
			'description'=>wp_kses(sprintf(__('Enable a background wrapper for all the gallery, as seen %shere%s','dzsap'),'<a href="https://previews.envatousercontent.com/files/242206229/index-gallery.html" target="_blank">','</a>'),$dzsap->allowed_tags),
			'type'=>'select',
			'options'=>$arr_off_on,
		),
		array(
			'name'=>'menuposition',
			'title'=>esc_html__('Menu Position','dzsap'),
			'description'=>esc_html__('Menu Position if the mode allows it','dzsap'),

			'type'=>'select',
			'category'=>'menu',
			'options'=>array(
				array(
					'label'=>esc_html__("Bottom",'dzsap'),
					'value'=>'bottom',
				),
				array(
					'label'=>esc_html__("Top",'dzsap'),
					'value'=>'top',
				),
				array(
					'label'=>esc_html__("Hide",'dzsap'),
					'value'=>'none',
				),
			),
		),
		array(
			'name'=>'design_menu_state',
			'title'=>esc_html__('Menu State','dzsap'),
			'description'=>esc_html__('If you set this to closed, you should enable the <strong>Menu State Button</strong> below. ','dzsap'),

			'type'=>'select',
			'category'=>'menu',
			'options'=>array(
				array(
					'label'=>esc_html__("Open",'dzsap'),
					'value'=>'open',
				),
				array(
					'label'=>esc_html__("Closed",'dzsap'),
					'value'=>'closed',
				),

			),
		),
		array(
			'name'=>'design_menu_show_player_state_button',
			'title'=>esc_html__('Menu State Button','dzsap'),
			'description'=>esc_html__('If you set this to closed, you should enable the <strong>Menu State Button</strong> below. ','dzsap'),

			'type'=>'select',
			'category'=>'menu',
			'options'=>$arr_off_on,
		),
		array(
			'name'=>'menu_facebook_share',
			'title'=>esc_html__('Facebook Share','dzsap'),
			'description'=>esc_html__('enable a facebook share button in the menu ','dzsap'),

			'type'=>'select',
			'category'=>'menu',
			'options'=>array(
				array(
					'label'=>esc_html__("Auto",'dzsap'),
					'value'=>'auto',
				),
				array(
					'label'=>esc_html__("Disable",'dzsap'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsap'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'menu_like_button',
			'title'=>esc_html__('Like button','dzsap'),
			'description'=>esc_html__('enable a like button in the menu ','dzsap'),

			'type'=>'select',
			'category'=>'menu',
			'options'=>array(
				array(
					'label'=>esc_html__("Auto",'dzsap'),
					'value'=>'auto',
				),
				array(
					'label'=>esc_html__("Disable",'dzsap'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsap'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'design_menu_height',
			'title'=>esc_html__('Menu Maximum Height','dzsap'),
			'description'=>sprintf(esc_html__('input a height in pixels / or input %s to show all menu items','dzsap'),'<strong>auto</strong>'),


			'type'=>'text',
			'category'=>'menu',

		),
		array(
			'name'=>'cuefirstmedia',
			'title'=>esc_html__('Cue First media','dzsap'),


			'type'=>'select',
			'category'=>'autoplay',
			'options'=>array(
				array(
					'label'=>esc_html__("Enable",'dzsap'),
					'value'=>'on',
				),
				array(
					'label'=>esc_html__("Disable",'dzsap'),
					'value'=>'off',
				),
			),
		),
		array(
			'name'=>'autoplay',
			'title'=>esc_html__('Autoplay','dzsap'),


			'type'=>'select',
			'category'=>'autoplay',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsap'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsap'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'autoplay_next',
			'title'=>esc_html__('Autoplay next','dzsap'),


			'type'=>'select',
			'category'=>'autoplay',
			'options'=>array(
				array(
					'label'=>esc_html__("Enable",'dzsap'),
					'value'=>'on',
				),
				array(
					'label'=>esc_html__("Disable",'dzsap'),
					'value'=>'off',
				),
			),
		),
		array(
			'name'=>'enable_views',
			'title'=>esc_html__('Enable play count','dzsap'),


			'type'=>'select',
			'category'=>'counters',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsap'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsap'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'enable_downloads_counter',
			'title'=>esc_html__('Enable downloads counter','dzsap'),


			'type'=>'select',
			'category'=>'counters',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsap'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsap'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'enable_likes',
			'title'=>esc_html__('Enable like count','dzsap'),


			'type'=>'select',
			'category'=>'counters',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsap'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsap'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'enable_rates',
			'title'=>esc_html__('Enable rating','dzsap'),


			'type'=>'select',
			'category'=>'counters',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsap'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsap'),
					'value'=>'on',
				),
			),
		),
	);



	$dzsap->options_slider_categories_lng = array(
		'appearence'=>esc_html__("Appearance",'dzsap'),
		'menu'=>esc_html__("Menu",'dzsap'),
		'autoplay'=>esc_html__("Play Options",'dzsap'),
		'counters'=>esc_html__("Counters",'dzsap'),
	);



//	'misc'=>esc_html__("Miscellaneous",'dzsap'),
























	$i23 = 0;
	foreach ($dzsap->mainitems_configs as $vpconfig) {
		//print_r($vpconfig);


		$aux = array(
			'label'=>$vpconfig['settings']['id'],
			'value'=>$vpconfig['settings']['id'],
		);

//            print_rr($aux);


		foreach($dzsap->options_slider as $lab => $so){

			if($so['name']=='vpconfig'){

//	                print_rr($aux);


				array_push($dzsap->options_slider[$lab]['options'],$aux);

				break;
			}
		}


		$i23++;
	}



	dzsap_sliders_admin_parse_options($term,'main');


}

function dzsap_sliders_admin_parse_options($term,$cat='main'){

	global $dzsap;
	$indtem = 0;



	$t_id = $term->term_id;

	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option("taxonomy_$t_id");

//	echo '$term_meta - '; print_rr($term_meta);


	// -- we need real location, not insert-id

	$struct_uploader = '<div class="dzs-wordpress-uploader ">
<a href="#" class="button-secondary">' . __('Upload', 'dzsvp') . '</a>
</div>';

	foreach ($dzsap->options_slider as $tem) {


//	    echo '$indtem - '.$indtem;
//	    echo '$indtem%2 - '.($indtem%2);


		if($cat=='main'){

			if(isset($tem['category'])==false || (isset($tem['category']) && $tem['category']=='main') ){

			}else{
				continue;
			}
		}else{

			if((isset($tem['category']) && $tem['category']==$cat) ){

			}else{
				continue;
			}
		}
		if($indtem%2===0){
//			echo '<tr class="clear"></tr>';

//	        echo 'yes';
		}

		if(isset($tem['choices'])){
			$tem['options']=$tem['choices'];
		}

		if(isset($tem['sidenote'])){
			$tem['description']=$tem['sidenote'];
		}
		?>
        <tr class="form-field" <?php


		if(isset($tem['dependency'])){

			echo ' data-dependency=\''.json_encode($tem['dependency']).'\'';
		}

		?>>
            <th scope="row" valign="top"><label
                        for="term_meta[<?php echo $tem['name']; ?>]"><?php echo $tem['title']; ?></label></th>
            <td class="<?php
			if($tem['type']=='media-upload'){
				echo 'setting-upload';
			}
			?>">





				<?php
				// -- main options

				if($tem['type']=='media-upload' || $tem['type']=='color'){
					echo '<div class="uploader-three-floats">';
				}

				if($tem['type']=='media-upload'){
					echo '<span class="uploader-preview"></span>';
				}
				?>



				<?php
				$lab = 'term_meta['.$tem['name'].']';

				$val = '';

				if(isset($term_meta[$tem['name']])){

					$val = esc_attr($term_meta[$tem['name']]) ? esc_attr($term_meta[$tem['name']]) : '';
				}

				$class = 'setting-field medium';


				if($tem['type']=='media-upload') {
					$class.=' uploader-target';
				}

				if($tem['type']=='color') {
					$class .= ' wp-color-picker-init';
				}
				if($tem['type']=='media-upload' || $tem['type']=='text' || $tem['type']=='input' || $tem['type']=='color') {



					if($tem['type']=='color'){
						$class.=' with_colorpicker';
					}

					echo DZSHelpers::generate_input_text($lab, array(
						'class' => $class,
						'seekval' => $val,
						'id' => $lab,
					));

				}


				if($tem['type']=='select') {

//				    print_rr($tem);


					$class.=' dzs-style-me skin-beige';

					if(isset($tem['select_type'])){
						$class.=' '.$tem['select_type'];
					}
					if(isset($tem['extra_classes'])) {
						$class .= ' ' . $tem['extra_classes'];
					}
					$class.=' dzs-dependency-field';
					echo DZSHelpers::generate_select($lab, array(
						'class' => $class,
						'options' => $tem['options'],
						'seekval' => $val,
						'id' => $lab,
					));



					if(isset($tem['select_type']) && $tem['select_type']=='opener-listbuttons'){

						echo  '<ul class="dzs-style-me-feeder">';

						foreach ($tem['choices_html'] as $oim_html){

							echo '<li>';
							echo $oim_html;
							echo '</li>';
						}

						echo '</ul>';


					}
				}

				if($tem['type']=='color') {
//                DZSHelpers::generate_input_text($lab, array('val' => '', 'class' => 'wp-color-picker-init ', 'seekval' => $val));


					echo '<div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>';
				}


				// -- media upload
				if($tem['type']=='media-upload') {

					echo '<div class="dzs-wordpress-uploader here-uploader ">
<a href="#" class="button-secondary';


					if(isset($tem['upload_btn_extra_classes']) && $tem['upload_btn_extra_classes']){
						echo ' '.$tem['upload_btn_extra_classes'];
					}


					echo '">' . __('Upload', 'dzsvp') . '</a>
</div>';
//					echo $struct_uploader;
				}
				?>
				<?php


				if($tem['type']=='media-upload' || $tem['type']=='color'){
					echo '</div><!-- end uploader three floats -->';
				}

				$description = '';
				if(isset($tem['description'])){
					$description = $tem['description'];
				}

				if($description){
					?>
                    <p class="description"><?php echo $description; ?></p>
					<?php


				}
				?>
            </td>
        </tr>
		<?php

		$indtem++;
	}

}