<?php





$its = array();
if($margs['feed_from']){

	if($margs['ids']){

	}

	$wpqargs = array();
	$wpqargs['posts_per_page']= '-1';
	$wpqargs['post_type']= 'any';
	$wpqargs['orderby']= $margs['orderby'];
	$wpqargs['order']= $margs['order'];

	if($margs['count']){

		$wpqargs['posts_per_page']=$margs['count'];
	}
	if($margs['orderby']=='likes'){
		$wpqargs['posts_per_page']= '-1';

    }
	if($margs['feed_from']=='audio_items'){
		$wpqargs['post_type']='dzsap_items';
	}




	if($margs['ids']){
		$wpqargs['post__in']= explode(',',$margs['ids']);
	}

//            print_rr($margs['ids']);

	$query = new WP_Query($wpqargs);


//            print_rr($query);
//            print_rr($query->posts);








	$its = $this->transform_to_array_for_parse($query->posts, $margs);



	if($margs['orderby']=='likes') {

		usort($its, "dzsap_sort_by_likes");
//            print_r($auxa);
//		$its = array_reverse($its);
//            print_r($auxa);

		if($margs['count']){

			$its = array_slice($its, 0, intval($margs['count']));   // returns "a", "b", and "c"
		}
	}



//            echo 'its - ';print_rr($its);



	if($margs['style']=='playlist'){

		$args = array(
			'ids' => '1'
			, 'embedded_in_zoombox' => 'off'
			, 'embedded' => 'off'
			, 'db' => 'main'
		);

//            if ($pargs == '') {
//                $atts = array();
//            }

//            $args = array_merge($args, $atts);

//            $po_array = explode(",", $args['ids']);

		$fout.='[zoomsounds id="playlist_gallery" embedded="'.$args['embedded'].'" extra_classes="from-wc-album" for_embed_ids="'.$margs['ids'].'"]';







		$this->front_scripts();



		$this->sliders_index++;


		$i = 0;
		$k = 0;
		$id = 'playlist_gallery';
		if (isset($margs['id'])) {
			$id = $margs['id'];
		}

		//echo 'ceva' . $id;

		// TODO: legacy, but new ?
		for ($i = 0; $i < count($this->mainitems); $i++) {
			if ((isset($id)) && ($id == $this->mainitems[$i]['settings']['id']))
				$k = $i;
		}


//        print_r($its);

		$enable_likes = 'off';

		$enable_views = 'off';
		$enable_downloads_counter = 'off';



		$its = array_reverse($its);

		foreach($its as $it){

//                $po = get_post($po_id);

//            print_r($po);

//                print_rr($it);



//            echo 'ceva2'.(get_post_meta($po_id,'_waveformprog',true));

//            print_r(wp_get_attachment_metadata($po_id));


			$po = get_post($it['id']);


			$title = $po->post_title;
			$desc = ' ';
			$title = ' ';
			$desc = $po->post_title;
//                $title = str_replace(array('"', '[',']'),'&quot;',$title);
//                $desc = $po->post_content;
//                $desc = str_replace(array('"', '[',']'),'&quot;',$desc);



			$src = $it['source'];





			if($this->mainoptions['try_to_hide_url']=='on'){



//                    print_r($_SESSION);

				$nonce = '{{generatenonce}}';


				$nonce = rand(0,10000);

				$id = $it['id'];








				$lab = 'dzsap_nonce_for_'.$id.'_ip_'.$_SERVER['REMOTE_ADDR'];

				$lab = $this->clean($lab);
				$_SESSION[$lab] = $nonce;

				$src = site_url().'/index.php?dzsap_action=get_track_source&id='.$id.'&'.$lab.'='.$nonce;
			}





			$sample_time_start=get_post_meta($it['id'],'dzsap_woo_sample_time_start',true);
			$sample_time_end=get_post_meta($it['id'],'dzsap_woo_sample_time_end',true);
			$sample_time_total=get_post_meta($it['id'],'dzsap_woo_sample_time_total',true);





			$fout.='[zoomsounds_player source="'.$src.'" config="playlist_player" playerid="'.$it['id'].'"  thumb="" autoplay="on" cue="auto" enable_likes="'.$enable_likes.'" enable_views="'.$enable_views.'"  enable_downloads_counter="'.$enable_downloads_counter.'" songname="'.$title.'" artistname="'.$desc.'" init_player="off" called_from="just_for_vc_grouped"';


			if($sample_time_start){

				$fout.=' sample_time_start="'.$sample_time_start.'"';
			}
			if($sample_time_end){
				$fout.=' sample_time_end="'.$sample_time_end.'"';
			}
			if($sample_time_total){
				$fout.=' sample_time_total="'.$sample_time_total.'"';
			}

			$fout.=']';
		}
		$fout.='[/zoomsounds]';


//            echo 'shortcode - '.$fout;
//            echo 'do_shortcode - '.do_shortcode($fout);

		$fout=do_shortcode($fout);

	}





































	//- list





	$i_number = 0;
	if($margs['style']==='widget_player'){
		$fout.='<div class="list-tracks-con">';

//            print_r($margs);


//        print_rr($its);
		foreach($its as $track){

//                print_r($track);


			$link = get_permalink($track['id']);




			$fout.='<a class="list-track ajax-link" href="'.$link.'">';

			$fout.='<div class="track-thumb"';

			$l = '';

			if(isset($track['thumbnail'])){

				$l = $this->sanitize_id_to_src($track['thumbnail']);
			}



			$src_thumb = $l;


			$fout.=' style="background-image: url('.$src_thumb.')"';

			$fout.='>';
			$fout.='</div>';


			$fout.='<div class="track-meta">';
			$fout.='<span class="track-title"';
			$fout.='>';

			$fout.='<span href="">'.$track['title'].'</span>';

			$fout.='</span>';



			$fout.='<div class="track-author"';
			$fout.='>';

			$fout.='<span >'.$track['artistname'].'</span>';

			$fout.='</div>';

			$fout.='<div class="track-number"';
			$fout.='><span class="the-number">';

			$track_number = ($i_number+1);
			if($margs['paged']){
				$track_number+=intval($margs['paged']) * $margs['limit_posts'];
			}

			$fout.=$track_number;

			$fout.='</span></div>';




			$fout.='</div>';


//			print_rr($margs);
			if($margs['style_widget_player_show_likes']==='on'){
//				print_rr($track);

				$fout.='<div class="likes-show">';

				$fout.='<span class="the-count">';
				$fout.=$track['likes'];
				$fout.='</span>';

				$fout.='<i class="fa fa-thumbs-up"></i>';


				$fout.='</div>';
			}


			$fout.='</a>';


			$i_number++;

		}
		$fout.='</div>';


		wp_enqueue_script('audioplayer-showcase',$this->base_url.'libs/audioplayer_showcase/audioplayer_showcase.js');
		wp_enqueue_style('audioplayer-showcase',$this->base_url.'libs/audioplayer_showcase/audioplayer_showcase.css');
		wp_enqueue_style('fontawesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');


	}




}