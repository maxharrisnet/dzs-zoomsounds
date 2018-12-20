<?php

require_once('get_wp.php');
//print_r($dzsap);
?>
<!doctype html>
<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $dzsap->thepath; ?>audioplayer/audioplayer.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $dzsap->thepath; ?>dzstooltip/dzstooltip.css"/>
</head>
<body>
<?php
$args = array();
if(isset($_GET['type']) && $_GET['type']=='gallery'){
    
    $args = array(
        'id' => $_GET['id'],
        'embedded' => 'on',
    );


            if(isset($_GET['db'])){
                $args['db'] = $_GET['db'];
            };
    echo $dzsap->show_shortcode($args);

}
if(isset($_GET['type']) && $_GET['type']=='playlist'){

    $args = array(
        'ids' => $_GET['ids'],
        'embedded' => 'on',
    );


            if(isset($_GET['db'])){
                $args['db'] = $_GET['db'];
            };
    echo $dzsap->shortcode_playlist($args);

}




if(isset($_GET['type']) && $_GET['type']=='player'){
    
    
//    echo $_GET['margs'];
    $args = array();
    try{
//        echo '.'.stripslashes($_GET['margs']).'.';
    $args = @unserialize((stripslashes($_GET['margs'])));
    }catch(Exception $e){

//        $args = array();
    }




//    print_r($args);

    if(is_array($args)){

    }else{
        $args = array();



//        echo 'try json decode -> ';
//        echo stripslashes(stripslashes($_GET['margs']));
//        echo ' <- ';
//
//        echo '
//        try json decode -> ';
//        echo (stripslashes($_GET['margs']));
//        echo ' <- ';


        $args = json_decode((stripslashes(base64_decode($_GET['margs']))),true);

//        print_r($args);

        if(is_object($args) || is_array($args)){

        }else{
            $args = array();



        }

    }
//    print_r($args);
    $args['embedded']='on';


    echo $dzsap->shortcode_player($args);

}


?>
<script type="text/javascript" src="<?php echo $dzsap->thepath; ?>audioplayer/audioplayer.js"></script>
</body>
</html>