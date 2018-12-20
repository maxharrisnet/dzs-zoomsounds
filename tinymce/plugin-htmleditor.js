//console.log('ceva');

window.htmleditor_sel = '';
window.mceeditor_sel = '';
jQuery(document).ready(function($){
    if(typeof(dzsap_settings)=='undefined'){
        if(window.console){ console.log('dzsap_settings not defined'); };
        return;
    }

    /*
    $('#wp-content-media-buttons').append('<a class="shortcode_opener" id="dzsap_shortcode" style="cursor:pointer; display: inline-block; vertical-align: middle;width:auto; height:28px; margin-right: 5px; background-color: #ffffff; color: #726b6b; padding-right: 10px; border: 1px solid rgba(0,0,0,0.3); border-radius:3px; line-height: 1; font-size:13px; padding-left:0;"><i class="" style="  background-size:cover; background-repeat: no-repeat; background-position: center center; background-image: url('+dzsap_settings.the_url+'tinymce/img/shortcodes-small-retina.png); width:28px; height: 28px; display:inline-block;  vertical-align: middle; margin-right: 5px; " ></i> <span style="display: inline-block; vertical-align: middle; font-size: 11px; font-weight: bold;">'+dzsap_settings.translate_add_gallery+'</span></a>');

    $('#wp-content-media-buttons').append('<a class="shortcode_opener_player" id="dzsap_shortcode_player" style="cursor:pointer; display: inline-block; vertical-align: middle;width:auto; height:28px; margin-right: 5px; background-color: #ffffff; color: #726b6b; padding-right: 10px; border: 1px solid rgba(0,0,0,0.3); border-radius:3px; line-height: 1; font-size:13px; padding-left:0;"><i class="" style="  background-size:cover; background-repeat: no-repeat; background-position: center center; background-image: url('+dzsap_settings.the_url+'tinymce/img/shortcodes-small-retina.png); width:28px; height: 28px; display:inline-block;  vertical-align: middle; margin-right: 5px; " ></i> <span style="display: inline-block; vertical-align: middle; font-size: 11px; font-weight: bold;">'+dzsap_settings.translate_add_player+'</span></a>');



    $('#wp-content-media-buttons').append('<a title="add a ZoomSounds player for local audio library" class="shortcode_opener" id="dzsap_shortcode_addvideoplayerfromlibrary" style="cursor:pointer; display: inline-block; vertical-align: middle; background-size:cover; background-repeat: no-repeat; background-position: center center; width:25px; height:25px; background-image: url('+dzsap_settings.thepath+'tinymce/img/shortcodes-small-addvideoplayerfromlibrary-retina.png);"></a>');
    //$('#dzsap_shortcode').bind('click');


    */

    $('#dzsap_shortcode').bind('click', function(){

        var parsel = '';
        if(window.tinyMCE == undefined || window.tinyMCE.activeEditor==null){
            var textarea = document.getElementById("content");
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            var sel = textarea.value.substring(start, end);

            //console.log(sel);

            //textarea.value = 'ceva';
            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.htmleditor_sel = sel;
            }else{
                window.htmleditor_sel = '';
            }
        }else{
            //console.log(window.tinyMCE.activeEditor);
            var ed = window.tinyMCE.activeEditor;
            var sel=ed.selection.getContent();

            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.mceeditor_sel = sel;
            }else{
                window.mceeditor_sel = '';
            }
            //console.log(aux);
        }


        $.fn.zoomBox.open(dzsap_settings.shortcode_generator_url + '', 'iframe', {width: 700, height: 500});


        return false;
    });


    $('#dzsap_shortcode_player').bind('click', function(){
            //console.log('click');


        var parsel = '';
        if(window.tinyMCE && window.tinyMCE.activeEditor==null){
            var textarea = document.getElementById("content");
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            var sel = textarea.value.substring(start, end);

            //console.log(sel);

            //textarea.value = 'ceva';
            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.htmleditor_sel = sel;
            }else{
                window.htmleditor_sel = '';
            }
        }else{
            //console.log(window.tinyMCE.activeEditor);
            var ed = window.tinyMCE.activeEditor;
            var sel=ed.selection.getContent();

            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.mceeditor_sel = sel;
            }else{
                window.mceeditor_sel = '';
            }
            //console.log(aux);
        }



        $.fn.zoomBox.open(dzsap_settings.shortcode_generator_player_url + '', 'iframe', {width: 700, height: 500});

    });
    $('#dzsap_shortcode_addvideoplayerfromlibrary').bind('click', function(){
            //console.log('click');

            var frame = wp.media.frames.dzsap_addplayer = wp.media({
                // Set the title of the modal.
                title: "Insert Audio Player",

                // Tell the modal to show only images.
                library: {
                    type: 'audio'
                },

                // Customize the submit button.
                button: {
                    // Set the text of the button.
                    text: "Insert Media",
                    // Tell the button not to close the modal, since we're
                    // going to refresh the page when the image is selected.
                    close: false
                }
            });

            // When an image is selected, run a callback.
            frame.on( 'select', function() {
                // Grab the selected attachment.
                var attachment = frame.state().get('selection').first();

                //console.info(attachment);
                //console.log(attachment.attributes, $('*[name*="video-player-config"]'));
                var arg = '[zoomsounds_player source="'+attachment.attributes.url+'" config="'+$('*[name*="dzsap-config"]').val()+'"';

                if(attachment.attributes.id){
                    arg+=' playerid="'+attachment.attributes.id+'"';
                }

                if($('*[name*="waveformbg"]').length>0){
                    arg+=' waveformbg="'+$('*[name*="waveformbg"]').eq(0).val()+'"';
                }
                if($('*[name*="waveformprog"]').length>0){
                    arg+=' waveformprog="'+$('*[name*="waveformprog"]').eq(0).val()+'"';
                }
                if($('*[name*="dzsap-thumb"]').length>0){
                    arg+=' thumb="'+$('*[name*="dzsap-thumb"]').eq(0).val()+'"';
                }

                arg+=' autoplay="off" cue="on" enable_likes="off" enable_views="off"'

                if($('label[data-setting="title"] input').length>0){
                    arg+=' songname="'+$('label[data-setting="title"] input').eq(0).val()+'"';

                }
                if($('label[data-setting="caption"] textarea').length>0){
                    arg+=' artistname="'+$('label[data-setting="caption"] textarea').eq(0).val()+'"';

                }

                arg+=']';



                if(typeof(top.dzsap_receiver)=='function'){
                    top.dzsap_receiver(arg);
                }
                frame.close();
            });

            // Finally, open the modal.
            frame.open();
    });
})












window.htmleditor_sel = 'notset';
window.mceeditor_sel = 'notset';
window.dzsrst_widget_shortcode = null;

jQuery(document).ready(function($){
    if(typeof(dzsap_settings)=='undefined'){
        if(window.console){ console.log('dzsrst_settings not defined'); };
        return;
    }







    // console.info("$('#wp-content-media-buttons' - ",$('#wp-content-media-buttons'));

    $('#wp-content-media-buttons').append('<button type="button" id="dzsap-shortcode-generator" class="dzs-shortcode-button button " data-editor="content"><span class="the-icon"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="1px" height="1px" viewBox="-0.5 0.5 1 1" enable-background="new -0.5 0.5 1 1" xml:space="preserve"> <g> <rect x="-0.484" y="0.589" width="0.678" height="0.027"/> <path d="M0.194,0.828c-0.058,0-0.111,0.017-0.158,0.046h-0.523V0.9h0.488c-0.061,0.054-0.1,0.131-0.1,0.219 c0,0.015,0.002,0.026,0.002,0.04h-0.388v0.025h0.394c0.031,0.132,0.146,0.227,0.285,0.227c0.161,0,0.292-0.131,0.292-0.292 C0.487,0.959,0.355,0.828,0.194,0.828z M0.194,1.386c-0.123,0-0.228-0.086-0.258-0.201c-0.002-0.009-0.003-0.016-0.005-0.025 c-0.001-0.014-0.002-0.025-0.002-0.04c0-0.09,0.046-0.171,0.115-0.219c0.016-0.01,0.032-0.02,0.049-0.026 c0.031-0.013,0.066-0.02,0.101-0.02c0.147,0,0.265,0.12,0.265,0.266C0.459,1.267,0.341,1.386,0.194,1.386z"/> <path d="M0.304,1.109L0.151,1.016c-0.004-0.002-0.009-0.002-0.013,0C0.135,1.018,0.132,1.023,0.132,1.028v0.133v0.025v0.029 c0,0.005,0.003,0.01,0.007,0.013c0.002,0,0.003,0,0.006,0s0.005,0,0.007,0l0.153-0.097C0.308,1.129,0.31,1.126,0.31,1.119 C0.31,1.114,0.308,1.111,0.304,1.109z M0.192,1.169L0.165,1.185L0.157,1.191V1.185V1.159V1.05l0.114,0.071L0.192,1.169z"/> </g> </svg> </span> <span class="the-label"> '+window.dzsap_settings.translate_add_gallery+'</span></button>');



    $('#wp-content-media-buttons').append('<button type="button" id="dzsap-shortcode-generator-player" class="dzs-shortcode-button button " data-editor="content"><span class="the-icon"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="100px" viewBox="-50 -49 100 100" enable-background="new -50 -49 100 100" xml:space="preserve"> <g> <path d="M0.919-46.588c-9.584,0-18.294,2.895-26.031,7.576l-8.591,7.014l-5.725,7.043l8.51-9.707 C-40.988-25.8-47.44-13.066-47.44,1.448c0,2.417,0.324,4.188,0.324,6.606l-0.286-5.194l1.333,9.228l0,0 c5.081,21.92,24.098,37.558,46.988,37.558c26.601,0,48.207-21.759,48.207-48.197C49.286-24.992,27.521-46.588,0.919-46.588z M0.919,45.458c-20.311,0-37.634-14.19-42.554-33.046c-0.324-1.617-0.485-2.74-0.809-4.197c-0.162-2.255-0.324-4.187-0.324-6.605 c0-14.989,7.585-28.21,18.949-36.271c2.656-1.609,5.32-3.226,8.052-4.188c5.159-2.265,10.963-3.226,16.685-3.226 c24.267,0,43.771,19.664,43.771,43.846C44.69,25.947,25.187,45.458,0.919,45.458z"/> <path d="M19.137-0.168L-6.171-15.637c-0.647-0.323-1.447-0.323-2.095,0c-0.562,0.315-1.047,1.286-1.047,1.933v22.08v4.036v4.835 c0,0.962,0.485,1.607,1.208,2.256c0.324,0,0.486,0,0.971,0c0.478,0,0.799,0,1.123,0L19.298,3.38 c0.484-0.323,0.808-0.809,0.808-1.932C20.105,0.64,19.782,0.153,19.137-0.168z M0.603,9.672l-4.433,2.74l-1.294,0.962v-0.962V8.055 v-18.056L13.661,1.771L0.603,9.672z"/> </g> </svg></span> <span class="the-label"> '+dzsap_settings.translate_add_player+'</span></button>');




    $('#dzsap-shortcode-generator').bind('click', function(){
        //tb_show('ZSVG Shortcodes', dzsrst_settings.thepath + 'tinymce/popupiframe.php?width=630&height=800');


        var parsel = '';
        if(jQuery('#wp-content-wrap').hasClass('tmce-active') && window.tinyMCE ){

            //console.log(window.tinyMCE.activeEditor);
            var ed = window.tinyMCE.activeEditor;
            var sel=ed.selection.getContent();

            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.mceeditor_sel = sel;
            }else{
                window.mceeditor_sel = '';
            }
            //console.log(aux);


            window.htmleditor_sel = 'notset';


        }else{




            var textarea = document.getElementById("content");
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            var sel = textarea.value.substring(start, end);

            //console.log(sel);

            //textarea.value = 'ceva';
            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.htmleditor_sel = sel;
            }else{
                window.htmleditor_sel = '';
            }

            window.mceeditor_sel = 'notset';
        }

        window.open_ultibox(null,{

            type: 'iframe'
            ,source: dzsap_settings.shortcode_generator_url + parsel
            ,scaling: 'fill' // -- this is the under description
            ,suggested_width: 800 // -- this is the under description
            ,suggested_height: 600 // -- this is the under description
            ,item: null // -- we can pass the items from here too

        })



        return false;
    })

    $('#dzsap-shortcode-generator-player').bind('click', function(){
        //tb_show('ZSVG Shortcodes', dzsrst_settings.thepath + 'tinymce/popupiframe.php?width=630&height=800');


        var parsel = '';
        if(jQuery('#wp-content-wrap').hasClass('tmce-active') && window.tinyMCE ){

            //console.log(window.tinyMCE.activeEditor);
            var ed = window.tinyMCE.activeEditor;
            var sel=ed.selection.getContent();

            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.mceeditor_sel = sel;
            }else{
                window.mceeditor_sel = '';
            }
            //console.log(aux);


            window.htmleditor_sel = 'notset';


        }else{




            var textarea = document.getElementById("content");
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            var sel = textarea.value.substring(start, end);

            //console.log(sel);

            //textarea.value = 'ceva';
            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.htmleditor_sel = sel;
            }else{
                window.htmleditor_sel = '';
            }

            window.mceeditor_sel = 'notset';
        }

        window.open_ultibox(null,{

            type: 'iframe'
            ,source: dzsap_settings.shortcode_generator_player_url + parsel
            ,scaling: 'fill' // -- this is the under description
            ,suggested_width: 800 // -- this is the under description
            ,suggested_height: 600 // -- this is the under description
            ,item: null // -- we can pass the items from here too

        })

        return false;
    })




})