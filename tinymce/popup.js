var coll_buffer=0;
var func_output='';
var fout = '';
jQuery(document).ready(function($){

    setTimeout(reskin_select, 10);

    $(document).on('click', '.insert-sample-tracks,.insert-sample-library,.remove-sample-tracks, button.sg-1, button.sg-2, button.sg-3,.lib-item,#insert_tests', handle_mouse);
    $('#insert_single_player').bind('click', click_insert_single_player);

    console.log($('#insert_tests'));


    function get_query_arg(purl, key){
        //console.info(purl);

        console.info("THIS", purl, key);
        if (purl.indexOf(key + '=') > -1) {
            //faconsole.log('testtt');
            var regexS = "[?&]" + key + "(.+?)(?=&|$)";
            var regex = new RegExp(regexS);
            var regtest = regex.exec(purl);


            //console.info(regex, regtest);
            if (regtest != null) {
                //var splitterS = regtest;


                if (regtest[1]) {
                    var aux = regtest[1].replace(/=/g, '');
                    return aux;
                } else {
                    return '';
                }


            }
            //$('.zoombox').eq
        }
    }



    function handle_mouse(e){
        var _t = $(this);

        if(e.type=='click'){







            if(_t.attr('id')=='insert_tests'){
                console.info("CEVA");
                console.info("LOW");
                prepare_fout();
                tinymce_add_content(fout);
                return false;
            }
            if(_t.hasClass('insert-sample-library')){




                window.open_ultibox(null,{


                    type:'inlinecontent'
                    ,source: '#import-sample-lib'
                    // ,inline_content_move: 'on'
                    ,scaling:'fill' // -- this is the under description
                    ,suggested_width:'95vw' // -- this is the under description
                    ,suggested_height:'95vh' // -- this is the under description
                    ,item: null // -- we can pass the items from here too

                });


            }
            if(_t.hasClass('lib-item')){


                var post_id = 1;

                console.warn('post_id ' ,get_query_arg(top.location.href, 'post'));
                if(get_query_arg(top.location.href, 'post')){
                    post_id = get_query_arg(top.location.href, 'post');
                }

                var data = {
                    action: 'dzsap_import_item_lib'
                    ,demo: _t.attr('data-demo')
                    ,post_id: post_id
                };

                _t.addClass('loading');

                jQuery.ajax({
                    type: "POST",
                    // url: 'https://zoomthe.me/updater_dzsap/getdemo.php',
                    url: ajaxurl,
                    data: data,
                    success: function(response) {
                        if(typeof window.console != "undefined" ){ console.log('Ajax - submit view - ' + response); }

                        setTimeout(function(){
                            "use strict";

                            _t.removeClass('loading');
                        },100);
                        //console.info(response);



                        // show_notice(response);
                        if(response.indexOf('"response_type":"error"')>-1){

                            show_notice(response);
                        }else{
                            var resp = JSON.parse(response);



                            console.info('response from wordpress import_lib - ');
                            console.warn(resp);



                            tinymce_add_content(resp.settings.final_shortcode);

                            close_ultibox();

                            setTimeout(function(){
                                "use strict";
                                top.close_ultibox();
                            },500);

                            if(resp.items){


                                for(var lab in resp.items){

                                    var c = resp.items[lab];

                                    console.warn('c -');
                                    console.warn(c);



                                    if(c.type=='set_curr_page_footer_player'){


                                        if(parent.set_curr_page_footer_player){

                                            parent.set_curr_page_footer_player(c);
                                        }

                                    }
                                }
                            }



                        }




                    },
                    error:function(arg){
                        if(typeof window.console != "undefined" ){ console.warn('Got this from the server: ' + arg); };
                    }
                });


            }













            if(_t.hasClass('insert-sample-tracks')){
                console.log('ceva', _t);




                var data = {
                    action: 'ajax_dzsap_insert_sample_tracks'
                };


                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: data,
                    success: function(response) {
                        console.log(response);
                        window.location.reload();

                    },
                    error:function(arg){
                        if(typeof window.console != "undefined" ){ console.log('Got this from the server: ' + arg, arg); };
                    }
                });

                return false;
            }
            if(_t.hasClass('remove-sample-tracks')){
                console.log('ceva', _t);




                var data = {
                    action: 'ajax_dzsap_remove_sample_tracks'
                };


                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: data,
                    success: function(response) {
                        console.log(response);
                        window.location.reload();

                    },
                    error:function(arg){
                        if(typeof window.console != "undefined" ){ console.log('Got this from the server: ' + arg, arg); };
                    }
                });

                return false;
            }


            if(_t.hasClass('sg-1')){
                //console.log('ceva2', _t);


                fout=window.sg1_shortcode;

                tinymce_add_content(fout);

            }
            if(_t.hasClass('sg-3')){
                //console.log('ceva2', _t);


                fout=window.sg3_shortcode;

                tinymce_add_content(fout);

            }


            if(_t.hasClass('sg-2')){
                //console.log('ceva2', _t);


                fout=window.sg2_shortcode;

                if(parent.dzsap_prepare_footer_player){
                    parent.dzsap_prepare_footer_player();
                }

                tinymce_add_content(fout);

            }
        }
    }

});

function tinymce_add_content(arg){
    console.log('tinymce_add_content', arg);




    if(top==window){

        console.info('jQuery(\'.shortcode-output\') - ',jQuery('.shortcode-output'), arg);
        jQuery('.shortcode-output').text(arg);
    }else{


        if(top.dzsap_widget_shortcode){
            top.dzsap_widget_shortcode.val(arg);

            top.dzsap_widget_shortcode = null;

            console.info(top.close_zoombox2);
            if(top.close_zoombox2){
                top.close_zoombox2();
            }
        }else{

            console.info(top.dzsap_receiver);

            if(typeof(top.dzsap_receiver)=='function'){
                top.dzsap_receiver(arg);
            }

        }

    }
}

function click_insert_tests(){
}

function prepare_fout(){
    console.log('prepare_fout()');
    fout='';
    fout+='[zoomsounds';
    var _c,
        _c2
    ;
    /*
    _c = $('input[name=settings_width]');
    if(_c.val()!=''){
        fout+=' width=' + _c.val() + '';
    }
    _c = $('input[name=settings_height]');
    if(_c.val()!=''){
        fout+=' height=' + _c.val() + '';
    }
    */
    _c = jQuery('select[name=dzsap_selectid]');
    if(_c.val()!=''){
        fout+=' id="' + _c.val() + '"';
    }
    _c = jQuery('*[name=width]');
    if(_c.val()!=''){
        fout+=' width="' + _c.val() + '"';
    }
    _c = jQuery('*[name=height]');
    if(_c.val()!=''){
        fout+=' height="' + _c.val() + '"';
    }

    /*
    if($('select[name=dzsap_settings_separation_mode]').val!='normal'){
        _c = $('select[name=dzsap_settings_separation_mode]');
        if(_c.val()!=''){
            fout+=' settings_separation_mode="' + _c.val() + '"';
        }
        _c = $('input[name=dzsap_settings_separation_pages_number]');
        if(_c.val()!=''){
            fout+=' settings_separation_pages_number="' + _c.val() + '"';
        }
    }
    */

    fout+=']';
}





function reskin_select(){
    for(i=0;i<jQuery('select').length;i++){
        var _cache = jQuery('select').eq(i);
        //console.log(_cache.parent().attr('class'));

        if(_cache.hasClass('styleme')==false || _cache.parent().hasClass('select_wrapper') || _cache.parent().hasClass('select-wrapper')){
            continue;
        }
        var sel = (_cache.find(':selected'));
        _cache.wrap('<div class="select-wrapper"></div>')
        _cache.parent().prepend('<span>' + sel.text() + '</span>')
    }
    jQuery('.select-wrapper select').unbind();
    jQuery('.select-wrapper select').live('change',change_select);
}

function change_select(){
    var selval = (jQuery(this).find(':selected').text());
    jQuery(this).parent().children('span').text(selval);
}
function prepare_fout_single(){
    fout='';

//    [zoomsounds_player source="http://localhost/wordpress/wp-content/uploads/2013/11/song.mp3" config="skinwavewithcomments" playerid="4306" waveformbg="http://localhost/wordpress/wp-content/plugins/dzs-zoomsounds/waves/scrubbg_songmp3.png" waveformprog="http://localhost/wordpress/wp-content/plugins/dzs-zoomsounds/waves/scrubprog_songmp3.png" thumb="http://localhost/wordpress/wp-content/uploads/2013/03/1185428_13454282.jpeg" autoplay="on" cue="on" enable_likes="off" enable_views="off" playfrom="10"]

    fout+='[zoomsounds_player';


    var _targetSettinger = jQuery('.con-only-single').eq(0);
    jQuery('.item-con').each(function(){
        var _t = jQuery(this);
        if(_t.hasClass('active')){
            _targetSettinger = _t;
        }
    })



//    console.info(_targetSettinger);

    var lab = '';
    var _c;

    lab = 'source';
    _c = _targetSettinger.find('*[data-label="'+lab+'"]');
    fout+=' source="'+_c.val()+'"';

    lab = 'vpconfig';
    _c = jQuery('.con-only-single').eq(0).find('*[data-label="'+lab+'"]');
    fout+=' config="'+_c.val()+'"';

    lab = 'linktomediafile';
    _c = _targetSettinger.find('*[data-label="'+lab+'"]');
    fout+=' playerid="'+_c.val()+'"';

    lab = 'waveformbg';
    _c = _targetSettinger.find('*[data-label="'+lab+'"]');
    fout+=' waveformbg="'+_c.val()+'"';

    lab = 'waveformprog';
    _c = _targetSettinger.find('*[data-label="'+lab+'"]');
    fout+=' waveformprog="'+_c.val()+'"';

    lab = 'thumb';
    _c = _targetSettinger.find('*[data-label="'+lab+'"]');
    fout+=' thumb="'+_c.val()+'"';

    fout+=' autoplay="on" cue="on" enable_likes="off" enable_views="off" enable_rates="off"';



    lab = 'playfrom';
    _c = _targetSettinger.find('*[data-label="'+lab+'"]');
    fout+=' playfrom="'+_c.val()+'"';

//        console.info(_c);





    fout+=']';
}
function click_insert_single_player(){

    prepare_fout_single();
    tinymce_add_content(fout);
    return false;
}