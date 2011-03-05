function FabsPlayer(id, swfPath, playlist){
    var $ = jQuery;
    $(document).ready(function(){
     
        var playItem = 0;
        var map = {};
        var linkMap = {};
        var autoload = false;
        var lastPos = null;
        var isPlaying = false;
        var volume = $.cookie('jp-volume');
        if( typeof volume != 'string'){
            volume = 80;
        }
        else{
            volume = Math.min( 100, Math.max( 0, parseInt(volume) ) );
        }
        
        // initialize the playlist
        $.each(playlist, function(index, song){
            song.thumb = song.thumb.replace(/(width|height)=".*?"/gi, '');
            map[song.mp3] = index;
        });
        
        // check to see if we have stored the last song listened to..
        if( map[$.cookie('lastSong')] || map[$.cookie('lastSong')] === 0 ){
            playItem = map[$.cookie('lastSong')];
            autoload = $.cookie('jp-autoload') === 'true' || $.cookie('jp-autoload') === true;
            lastPos = $.cookie('lastPosition');
        }
        
        // initialize any links on the page to use this playlist..
        $('a').each( function(i,el){
            // console.log(el.href, map[el.href]);
            if( map[el.href] || map[el.href] === 0 ){
                
                // register this bad boy in our link map
                if( !linkMap[el.href] ) linkMap[el.href] = [];
                linkMap[el.href].push(el);
                $(el).addClass('jp-mp3-link nice-button');
                $('<b class="jp-mp3-button" />').appendTo($(el));
                
                $(el).click( function(event){
                    event.preventDefault();
                    // check if its playing, if so, pause
                    if( playItem == map[el.href] ){
                        if( isPlaying ){
                            $(id).jPlayer('pause');
                        }
                        else{
                            $(id).jPlayer('play');
                        }
                    }
                    else{
                        playListChange( map[el.href] );
                    }
                });
            }
        });
     
        // Local copy of jQuery selectors, for performance.
        var jpPlayTime = $("#jplayer_play_time");
        var jpTotalTime = $("#jplayer_total_time");
        var jpContainer = $('.jp-playlist-player');
        var playlistToggle = $('#jplayer_playlist_toggle');
        
        playlistToggle.click( function(event){
            event.preventDefault();
            var animate = jpContainer.hasClass('jp-playlist-hidden');
            jpContainer.toggleClass('jp-playlist-hidden');
            if( animate ){
                $('#jplayer_playlist').height(0);
                $('#jplayer_playlist').stop();
                $('#jplayer_playlist').animate({
                    height: 84
                });
            }
            else{
                $('#jplayer_playlist').stop();
                $('#jplayer_playlist').animate({
                    height: 0
                });
            }
        });
     
        $(id).jPlayer({
            volume: volume,
            swfPath: swfPath,
            ready: function() {
                displayPlayList();
                playListInit(autoload); // Parameter is a boolean for autoplay.
            }
        })
        .jPlayer("onProgressChange", function(loadPercent, playedPercentRelative, playedPercentAbsolute, playedTime, totalTime) {
            jpPlayTime.text($.jPlayer.convertTime(playedTime));
            jpTotalTime.text($.jPlayer.convertTime(totalTime));
            $.cookie('lastPosition', playedTime, {path:'/'});
        })
        .jPlayer("onSoundComplete", function() {
            playListNext();
        });
        
        $(id).bind('jPlayer.setButtons', function(e, _isPlaying){
            isPlaying = _isPlaying?true:false;
            $.cookie('jp-autoload', isPlaying, {path:'/'});
            updateLinks();
        });
        $(id).bind('jPlayer.volume', function(e, v){
            $.cookie('jp-volume', v, {path:'/'});
        });
     
        $("#jplayer_previous").click( function() {
            playListPrev();
            $(this).blur();
            return false;
        });
     
        $("#jplayer_next").click( function() {
            playListNext();
            $(this).blur();
            return false;
        });
     
        function displayPlayList() {
            $("#jplayer_playlist ul").empty();
            for (i=0; i < playlist.length; i++) {
                var listItem = (i == playlist.length-1) ? "<li class='jplayer_playlist_item_last'>" : "<li>";
                listItem += "<a href='#' id='jplayer_playlist_item_"+i+"' tabindex='1'>"+ playlist[i].title +"</a></li>";
                $("#jplayer_playlist ul").append(listItem);
                $("#jplayer_playlist_item_"+i).data( "index", i ).click( function() {
                    var index = $(this).data("index");
                    if (playItem != index) {
                        playListChange( index );
                    } else {
                        $("#jquery_jplayer").jPlayer("play");
                    }
                    $(this).blur();
                    return false;
                });
            }
        }
     
        function playListInit(autoplay) {
            if(autoplay) {
                playListChange( playItem, lastPos );
            } else {
                playListConfig( playItem );
            }
        }
     
        function playListConfig( index ) {
            $("#jplayer_playlist_item_"+playItem).removeClass("jplayer_playlist_current").parent().removeClass("jplayer_playlist_current");
            $("#jplayer_playlist_item_"+index).addClass("jplayer_playlist_current").parent().addClass("jplayer_playlist_current");
            updateLinks(true);
            playItem = index;
            
            // update the thumbnail...
            $('#jp_album_art').html('<a href="'+playlist[playItem].link+'">'+playlist[playItem].thumb+'</a>');
            $('#jp_song_title').html(playlist[playItem].title);
            $('#jp_album_title').html('<a href="'+playlist[playItem].link+'">'+playlist[playItem].parent_title+'</a>');
            $("#jquery_jplayer").jPlayer("setFile", playlist[playItem].mp3);
            
            // remember the last song
            $.cookie('lastSong', playlist[playItem].mp3, {path:'/'});
            updateLinks();
        }
     
        function playListChange( index, lastPos ) {
            playListConfig( index );
            if( lastPos ){
                $("#jquery_jplayer").jPlayer("playHeadTime", lastPos);
            }
            else{
                $("#jquery_jplayer").jPlayer("play");
            }
            updateLinks();
        }
     
        function playListNext() {
            var index = (playItem+1 < playlist.length) ? playItem+1 : 0;
            playListChange( index );
        }
     
        function playListPrev() {
            var index = (playItem-1 >= 0) ? playItem-1 : playlist.length-1;
            playListChange( index );
        }
        
        function updateLinks(remove){
            // get the links
            var links = linkMap[playlist[playItem].mp3];
            if( !links ){
                return;
            }
            $.each(links, function(index, link){
                if( remove || !isPlaying ){
                    $(link).removeClass('jp-mp3-link-playing');
                }
                else{
                    $(link).addClass('jp-mp3-link-playing');
                }
            });
            
        }
    });
}