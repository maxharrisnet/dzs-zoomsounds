<?php 
    $struct_tag = '<div class="admin-item-playlist">
                <div class="tag-title">Playlist <span class="delete-tag">delete this playlist</span></div>
                <div class="setting">
                <span class="label">Playlist ID</span><input type="text" value="" class="textinput" name="playlistid" placeholder="enter the playlist id here"/>
                </div>
            </div>';
    
    
        $aux = str_replace(array("\r", "\r\n", "\n"), '', $struct_tag);
        
        $initer = '';
        if(isset($_GET['initer'])){
            $initer = $_GET['initer'];
        }
        
?>
<!doctype html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="popup.css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="popup.js"></script>
    <script>
        <?php echo "var struct_tag = '" . $aux . "';"; ?>
        <?php echo "var initer = '" . $initer . "';"; ?>
    </script>
    </head>
    <body>
        <div class="add-tag">add a playlist</div>
        <div class="con-tags">
        </div>
        <hr>
        <div class="btn-submit">submit</div>
        <div class="sidenote">The YouTube API limit for videos retrieved is 50. To overcome this, you can add your 
            playlist multiple times. So if your playlist contains 100 videos, you would add the same playlist twice here.</div>
    </body>
</html>