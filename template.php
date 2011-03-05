<?php

$_fabs_player_instance=0;
function fabs_player_instance($options=array())
{
    global $_fabs_player_instance, $wpdb;
    $i = ++$_fabs_player_instance;
    
    $swfPath = plugins_url('/jplayer/jQuery.jPlayer.1.2.0/', __FILE__ );
    $mp3s = fabs_player_get_mp3s($options);
    
    if( $i == 1 ){
        ?>
        <script type="text/javascript" src="<?php echo plugins_url('/js/jquery.cookie.js', __FILE__); ?>"></script>
        <script type="text/javascript" src="<?php echo plugins_url('/jplayer/jQuery.jPlayer.1.2.0/jquery.jplayer.min.js', __FILE__); ?>"></script>
        <script type="text/javascript" src="<?php echo plugins_url('/js/jplayer.playlist.js', __FILE__); ?>"></script>
        <script type="text/javascript">
        new FabsPlayer("#jquery_jplayer", "<?php echo $swfPath ?>", <?php echo json_encode($mp3s); ?>);
        </script>
        <?php
    }
    ?>
    <div id="jquery_jplayer"></div>
    <div class="jp-playlist-player jp-playlist-hidden">
        <div class="jp-interface">
            <div id="jp_album_art_container">
                
                <div id="jp_album_art"></div>
                <div id="jp_album_art_bottom">
                    <div id="jplayer_volume_bar" class="jp-volume-bar">
                        <div id="jplayer_volume_bar_value" class="jp-volume-bar-value"></div>
                        <a href="#" id="jplayer_volume_min"></a>
                        <a href="#" id="jplayer_volume_max"></a>
                    </div>
                    <div class="jp-time-info">
                        <span id="jplayer_play_time" class="jp-play-time"></span> /
                        <span id="jplayer_total_time" class="jp-total-time"></span>
                    </div>
                </div>
            </div>
            <div class="jp-player-container">
                <div id="jp_song_info">
                    <span id="jp_song_title">&nbsp;</span>
                    <span id="jp_album_title">&nbsp;</span>
                </div>
                <ul class="jp-controls">
                    <li><a href="#" id="jplayer_play" class="jp-play" tabindex="1">play</a></li>
                    <li><a href="#" id="jplayer_pause" class="jp-pause" tabindex="1">pause</a></li>
                    <li><a href="#" id="jplayer_stop" class="jp-stop" tabindex="1">stop</a></li>
                    <li><a href="#" id="jplayer_previous" class="jp-previous" tabindex="1">previous</a></li>
                    <li><a href="#" id="jplayer_next" class="jp-next" tabindex="1">next</a></li>
                    <li><a href="#" id="jplayer_playlist_toggle" class="jp-playlist-toggle" tabindex="1">open/close playlist</a></li>
                </ul>
                <div class="progress-container">
                    <div class="jp-progress">
                        <div id="jplayer_load_bar" class="jp-load-bar">
                            <div id="jplayer_play_bar" class="jp-play-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="jplayer_playlist" class="jp-playlist">
            <ul>
              <!-- The function displayPlayList() uses this unordered list -->
                <li></li>
            </ul>
        </div>
    </div>
    <?php
}