<?php
require_once('get_wp.php');
$embedded_in_zoombox = 'off';

if(isset($_GET['embedded_in_zoombox'])){
    $embedded_in_zoombox = $_GET['embedded_in_zoombox'];
}
echo do_shortcode('[zoomsounds id="'.$_GET['id'].'" embedded_in_zoombox="'.$embedded_in_zoombox.'"]');