
window.waves_fieldtaget = null;
window.waves_filename = null;

window.inter_dzs_check_dependency_settings = 0;

window.dzs_check_dependency_settings = function(pargs){

    // -- this checks for all dependencies .. lets make a timer


    if(window.inter_dzs_check_dependency_settings){
        clearTimeout(window.inter_dzs_check_dependency_settings);

    }

    window.inter_dzs_check_dependency_settings = setTimeout(function(){
        dzs_check_dependency_settings_real(pargs);
    },100);



}


window.dzs_check_dependency_settings_real = function(pargs){
    var margs = {
        target_attribute: 'name'
    }

    var $ = jQuery;
    $('*[data-dependency]').each(function(){
        var _t = $(this);


        // console.info(_t);
        var dep_arr = {};


        var aux_depedency = _t.attr('data-dependency');


        // return false;

        // console.warn('aux_depedency - ',aux_depedency);
        if(aux_depedency.indexOf('"')==0){
            aux_depedency = aux_depedency.substr(1, aux_depedency.length);
            aux_depedency = aux_depedency.substr(0, aux_depedency.length-1);
        }

        // console.warn('aux_depedency - ',aux_depedency);
        aux_depedency = aux_depedency.replace(/{quotquot}/g, '"');

        // console.warn('aux_depedency - ',aux_depedency);
        try{
            dep_arr = JSON.parse(aux_depedency);

            //console.warn(_t, dep_arr);

            if(dep_arr[0]){


                //console.info(dep_arr[0]);

                var _c = null;



                var target_attribute = margs.target_attribute;

                var target_con = $(document);


                if(_t.hasClass('check-label')){
                    target_attribute = 'data-label';
                }
                if(_t.hasClass('check-parent1')){
                    target_con = _t.parent();
                }
                if(_t.hasClass('check-parent2')){
                    target_con = _t.parent().parent();
                }
                if(_t.hasClass('check-parent3')){
                    target_con = _t.parent().parent().parent();
                }






                // console.warn('target_con - ',target_con);
                // console.warn('target_attribute - ',target_attribute);


                if(dep_arr[0].lab){
                    _c = target_con.find('*['+target_attribute+'="'+dep_arr[0].lab+'"]:not(.fake-input)').eq(0);
                }
                if(dep_arr[0].label){
                    _c = target_con.find('*['+target_attribute+'="'+dep_arr[0].label+'"]:not(.fake-input)').eq(0);
                }
                if(dep_arr[0].element){


                    // -- if it's player generator there is no dzsap_meta_
                    if($('body').hasClass('zoomsounds_page_dzsap-mo')){
                        dep_arr[0].element = String(dep_arr[0].element).replace('dzsap_meta_','');
                    }

                    _c = target_con.find('*['+target_attribute+'="'+dep_arr[0].element+'"]:not(.fake-input)').eq(0);
                }




                if(dep_arr[0].element && dep_arr[0].element=='dzsap_meta_download_custom_link_enable') {
                    // console.info('_c - ',_c);
                }


                var cval = _c.val();


                if(_c.attr('type')=='checkbox'){
                    if(_c.prop('checked')){

                    }else{
                        cval = '';
                    }
                }

                var sw_show = false;

                if(dep_arr[0].val) {
                    for (var i3 in dep_arr[0].val) {

                        //console.info(_c, cval, dep_arr[0].val[i3]);
                        if (cval == dep_arr[0].val[i3]) {
                            sw_show = true;
                            break;

                        }
                    }
                }

                if(dep_arr.relation){



                    // console.error(dep_arr.relation);

                    for(var i in dep_arr){
                        if(i=='relation'){
                            continue;
                        }


                        if(dep_arr[i].value){
                            if(dep_arr.relation=='AND'){
                                sw_show=false;
                            }



                            if(dep_arr[0].element){
                                _c = target_con.find('*['+target_attribute+'="'+dep_arr[i].element+'"]:not(.fake-input)').eq(0);
                            }


                            for(var i3 in dep_arr[i].value) {


                                // console.info('_c.val() -  ',_c.val(), dep_arr[i].value[i3]);
                                if (_c.val() == dep_arr[i].value[i3]) {


                                    if(_c.attr('type')=='checkbox'){
                                        if(_c.val() == dep_arr[i].value[i3] && _c.prop('checked')){

                                            sw_show = true;
                                        }
                                    }else{

                                        sw_show = true;
                                    }

                                    break;

                                }


                                if(dep_arr[i].value[i3]=='anything_but_blank' && cval){

                                    sw_show=true;
                                    break;
                                }
                            }

                            // console.info('sw_show - ',sw_show);
                        }

                    }

                }else{

                    if(dep_arr[0].value){

                        for(var i3 in dep_arr[0].value) {
                            if (_c.val() == dep_arr[0].value[i3]) {


                                if(_c.attr('type')=='checkbox'){
                                    if(_c.val() == dep_arr[0].value[i3] && _c.prop('checked')){

                                        sw_show = true;
                                    }
                                }else{

                                    sw_show = true;
                                }

                                break;

                            }


                            if(dep_arr[0].value[i3]=='anything_but_blank' && cval){

                                sw_show=true;
                                break;
                            }
                        }
                    }
                }


                if(sw_show){
                    _t.show();
                }else{
                    _t.hide();
                }


            }


        }catch(err){
            console.info('cannot parse depedency json', "'",aux_depedency, "'", err, _t);
        }
    })
}

window.dzs_handle_submit_dependency_field = function(e) {
    var _t = jQuery(this);

    if (e.type == 'change') {
        //console.info(_t);
        if (_t.hasClass('dzs-dependency-field')) {
            // console.info("ceva");
            dzs_check_dependency_settings();
        }
    }
}

window.api_wavesentfromflash = function(arg){
    console.info(window.waves_fieldtaget, arg);
    if(window.waves_fieldtaget){
        window.waves_filename = window.waves_filename.replace('{{dirname}}', dzsap_settings.theurl_forwaveforms);
        window.waves_filename = window.waves_filename.replace('{{uploaddirname}}', dzsap_settings.theurl_forwaveforms);
        window.waves_filename = window.waves_filename.replace(/%20/g, '');
        window.waves_filename = window.waves_filename.replace(/%C3%A9/g, 'é');
        window.waves_filename = window.waves_filename.replace('%2520', '');
        window.waves_fieldtaget.val(window.waves_filename);
        window.waves_fieldtaget.trigger('change');
        if(window.waves_fieldtaget.next().hasClass('aux-wave-generator')){

            window.waves_fieldtaget.next().find('button').show();
            window.waves_fieldtaget.next().find('object').remove();
        }else{


            if(window.waves_fieldtaget.next().find('.aux-wave-generator').length>0){

                window.waves_fieldtaget.next().find('.aux-wave-generator').find('button').show();
                window.waves_fieldtaget.next().find('.aux-wave-generator').find('object').remove();
            }else{

                window.waves_fieldtaget.next().next().find('button').show();
                window.waves_fieldtaget.next().next().find('object').remove();
            }
        }



    }
    //if(window.console) { console.info(window.waves_fieldtaget,arg); };
}

jQuery(document).ready(function($){
    //return;
    // Create the media frame.
    $(document).delegate('.btn-autogenerate-waveform-bg', 'click', click_btn_autogenerate_waveform_bg);
    $(document).delegate('.btn-autogenerate-waveform-prog', 'click', click_btn_autogenerate_waveform_prog);
    $(document).delegate('.btn-generate-default-waveform-bg', 'click', click_btn_generate_default_waveform_bg);
    $(document).delegate('.btn-generate-default-waveform-prog', 'click', click_btn_generate_default_waveform_prog);
    $(document).delegate('.upload-for-target', 'click', click_btn_upload_for_target);
    $(document).delegate('select.vpconfig-select', 'change', change_vpconfig);


    $(document).on('click', '.regenerate-waveform, .btn-delete-waveform-data', handle_mouse);
    $(document).on('click', '.btn-refresh-preview', handle_mouse);




    var _feedbacker = $('.feedbacker');
    var _wrap = $('.wrap').eq(0);







    $(document).off('click.dzswup','.dzs-wordpress-uploader');
    $(document).on('click.dzswup','.dzs-wordpress-uploader', function(e){
        var _t = $(this);
        var _targetInput = _t.prev();

        var searched_type = '';

        if(_targetInput.hasClass('upload-type-audio')){
            searched_type = 'audio';
        }
        if(_targetInput.hasClass('upload-type-video')){
            searched_type = 'video';
        }
        if(_targetInput.hasClass('upload-type-image')){
            searched_type = 'image';
        }


        frame = wp.media.frames.dzsp_addimage = wp.media({
            title: "Insert Media",
            library: {
                type: searched_type
            },

            // Customize the submit button.
            button: {
                // Set the text of the button.
                text: "Insert Media",
                close: true
            }
        });

        // When an image is selected, run a callback.
        frame.on( 'select', function() {
            // Grab the selected attachment.
            var attachment = frame.state().get('selection').first();

            //console.log(attachment.attributes.url);
            var arg = attachment.attributes.url;

            // console.info(attachment);
            if(_t.hasClass('insert-id')){
                arg = attachment.attributes.id;
            }

            _targetInput.val(arg);
            _targetInput.trigger('change');
            // _targetInput.trigger('keyup');

            console.info('attachment - ',attachment);
            console.info('_targetInput - ',_targetInput);


            var _con = null;

            if(_targetInput.parent().parent().hasClass('tab-content')){
                _con = _targetInput.parent().parent();


            }



            if(_targetInput.attr('name')=='dzsap_meta_item_source'){


                console.info('narayana');
                if(_con){


                    console.info('attachment.attributes - ',attachment.attributes);
                    console.info('attachment.attributes.artist - ',attachment.attributes.artist);
                    console.info('attachment.attributes.artist - ',attachment.attributes['artist']);


                    setTimeout(function(arg){

                        // -- for  some reason there is a delay...
                        console.info('attachment.attributes - ',arg.attributes);
                        console.info('attachment.attributes.artist - ',arg.attributes.artist);
                        console.info('attachment.attributes.artist - ',arg.attributes['artist']);



                        if(arg.attributes.title){

                            var lab = 'the_post_title';
                            if(_con.find('*[name="'+lab+'"]').eq(0).val() == ''){
                                _con.find('*[name="'+lab+'"]').eq(0).val(arg.attributes.title);
                            }
                            setTimeout(function(arg2){
                                arg2.trigger('change');
                            },500,_con.find('*[name="'+lab+'"]').eq(0))
                        }
                        if(arg.attributes.artist){

                            var lab = 'artistname';

                            // console.info('_con.find(\'*[name="\'+lab+\'"]\') - ',_con.find('*[name="'+lab+'"]'));
                            if(_con.find('*[name="'+lab+'"]').eq(0).val() == ''){
                                _con.find('*[name="'+lab+'"]').eq(0).val(arg.attributes.artist);
                            }

                            setTimeout(function(arg2){
                                arg2.trigger('change');
                            },500,_con.find('*[name="'+lab+'"]').eq(0))
                        }
                    },500,attachment);

                }
            }

            if(_targetInput.attr('name').indexOf('item_source')>-1 || _targetInput.attr('name')=='source'){

                // console.info('_targetInput.parent().find(\'.dzsap_meta_source_attachment_id\') - ',_targetInput.parent().find('*[name="dzsap_meta_source_attachment_id"]'));
                _targetInput.parent().find('*[name="dzsap_meta_source_attachment_id"]').eq(0).val(attachment.attributes.id)
            }
            // console.info('_targetInput - ',_targetInput);
//            frame.close();
        });

        // Finally, open the modal.
        frame.open();

        e.stopPropagation();
        e.preventDefault();
        return false;
    });






    $(document).off('click','.dzs-btn-add-media-att');
    $(document).on('click','.dzs-btn-add-media-att',  function(){
        var _t = $(this);

        var args = {
            title: 'Add Item',
            button: {
                text: 'Select'
            },
            multiple: false
        };

        if(_t.attr('data-library_type')){
            args.library = {
                'type':_t.attr('data-library_type')
            }
        }

        console.info(_t);

        var item_gallery_frame = wp.media.frames.downloadable_file = wp.media(args);

        item_gallery_frame.on( 'select', function() {

            var selection = item_gallery_frame.state().get('selection');
            selection = selection.toJSON();

            var ik=0;
            for(ik=0;ik<selection.length;ik++){

                var _c = selection[ik];
                //console.info(_c);
                if(_c.id==undefined){
                    continue;
                }

                if(_t.hasClass('button-setting-input-url')){

                    _t.parent().parent().find('input').eq(0).val(_c.url);
                }else{

                    _t.parent().parent().find('input').eq(0).val(_c.id);
                }


                _t.parent().parent().find('input').eq(0).trigger('change');

            }
        });



        // Finally, open the modal.
        item_gallery_frame.open();

        return false;
    });


    $('.uploader-target').off('change');
    $('.uploader-target').on('change',function(){

        var _t = $(this);
        var val = _t.val();
        var _previewer = null;

        if(_t.prev().hasClass('uploader-preview')){
            _previewer = _t.prev();
        }

        if(_previewer){



            // console.info(val);

            if(isNaN(Number(val))==false){

                var data = {
                    action: 'dzs_get_attachment_src'
                    ,id: val
                };


                jQuery.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    data: data,
                    success: function(response) {

                        console.warn(response, (response && (response.indexOf('.jpg')>-1 || response.indexOf('.jpeg')>-1)  ) );

                        if(response && (response.indexOf('.jpg')>-1 || response.indexOf('.jpeg')>-1  )) {

                            _previewer.css('background-image', 'url('+response+')')
                            _previewer.html(' ');
                            _previewer.removeClass('empty');
                        }else{

                            _previewer.html('');
                            _previewer.addClass('empty');
                        }
                    },
                    error:function(arg){
                        if(typeof window.console != "undefined" ){ console.warn('Got this from the server: ' + arg); };
                    }
                });
            }else{

                _previewer.css('background-image', 'url('+val+')')
                _previewer.html(' ');
                _previewer.removeClass('empty');


            }

            if(val==''){

                _previewer.html('');
                _previewer.addClass('empty');
            }



        }
    });


    setTimeout(function(){
        $('.uploader-target').trigger('change');
    },500);




    setTimeout(reskin_select, 10);
    setTimeout(function(){

        $('select.vpconfig-select').trigger('change');
    },1000);



    $(document).off('change','.dzs-dependency-field',  dzs_handle_submit_dependency_field);
    $(document).on('change','.dzs-dependency-field',  dzs_handle_submit_dependency_field);

    setTimeout(function(){

        // console.info($('.dzs-dependency-field'));
        $('.dzs-dependency-field').trigger('change');


        // console.info('hmm',$('.edit_form_line input[name=source], .wrap input[name=source]'));
    },800);







    $(document).on('change.dzsap_get_thumb', '*[name="dzsap_meta_source_attachment_id"]', function(){



        var _t = $(this);

        console.info('_t -5 ',_t);


        var _con = null;

        if(_t.parent().parent().parent().parent().parent().hasClass('dzstooltip--content')){
            _con =_t.parent().parent().parent().parent().parent();
        }

        console.info('_con - 5',_con);


        if(_con){
            var _c = _con.find('*[name="dzsap_meta_item_thumb"]');
            if(_c){

                console.info('_c - 5',_c, _c.val());
                if(_c.val()==''){

                    var data = {
                        action: 'dzsap_get_thumb_from_meta'
                        ,postdata: _t.val()
                    };


                    var _mainThumb = _c;


                    jQuery.ajax({
                        type: "POST",
                        url: window.ajaxurl,
                        data: data,
                        success: function(response) {

                            console.groupCollapsed('imagedata');
                            console.log('Got this from the server: ' + response);
                            console.groupEnd();

                            if(response.indexOf('image data - ')==0){
                                // console.info('yes',_mainThumb);

                                response = response.replace('image data - ', '');

                                // console.info(_t.parent().parent().parent().find('.item-preview'))

                                if(response){


                                    if(_mainThumb.val()=='' && _mainThumb.val()!='none') {
                                        _mainThumb.val('data:image/jpeg;base64,' + response);
                                        _mainThumb.trigger('change');
                                    }
                                }
                                // _t.parent().parent().parent().find('.item-preview').css('background-image', "url(data:image/jpeg;base64,"+response+')');
                            }else{

                                // _t.parent().parent().parent().find('.item-preview').css('background-image', "url("+response+')');
                                if(_mainThumb.val()=='' && _mainThumb.val()!='none') {
                                    _mainThumb.val(response);
                                    _mainThumb.trigger('change');
                                }
                            }

                        },
                        error:function(arg){
                            if(typeof window.console != "undefined" ){ console.warn('Got this from the server: ' + arg); };
                        }
                    });
                }
            }
        }

    });
    $(document).on('change.dzsap_global', '.edit_form_line input[name=source], .wrap input[name=source],input[name=playerid]', function(){
        var _t = $(this);


        var sw_show_notice = true;
        console.info('check if can be linked :D ',isNaN(Number(_t.val())), (Number(_t.val())));

        if(isNaN(Number(_t.val())) && $('input[name=playerid]').eq(0).val()==''){

        }else{


            sw_show_notice= false;
            // -- why hide player id
            // $('div[data-label="playerid"],*[data-vc-shortcode-param-name="playerid"]').hide();
        }

        var _c = $('*[name="dzsap_meta_source_attachment_id"]').eq(0);
        if(isNaN(Number(_c.val())) && _c.val()==''){

        }else{


            sw_show_notice= false;
            // -- why hide player id
            // $('div[data-label="playerid"],*[data-vc-shortcode-param-name="playerid"]').hide();
        }


        _c.trigger('change');


        if(sw_show_notice){

            $('div[data-label="playerid"],*[data-vc-shortcode-param-name="playerid"]').show();
            $('.notice-for-playerid').show();
        }else{

            $('.notice-for-playerid').hide();
        }



    })

    $('input[name=source]').trigger('change');
    setTimeout(function(){

        $('input[name=source]').trigger('change');
    },1000);




    $(".with_colorpicker").each(function(){
        var _t = $(this);
        if(_t.hasClass("treated")){
            return;
        }
        if($.fn.farbtastic){
            //console.log(_t);
            _t.next().find(".picker").farbtastic(_t);

        }else{ if(window.console){ console.info("declare farbtastic..."); } };
        _t.addClass("treated");

        _t.bind("change", function(){
            //console.log(_t);
            jQuery("#customstyle_body").html("body{ background-color:" + $("input[name=color_bg]").val() + "} .dzsportfolio, .dzsportfolio a{ color:" + $("input[name=color_main]").val() + "} .dzsportfolio .portitem:hover .the-title, .dzsportfolio .selector-con .categories .a-category.active { color:" + $("input[name=color_high]").val() + " }");
        });
        _t.trigger("change");
        _t.bind("click", function(){
            if(_t.next().hasClass("picker-con")){
                _t.next().find(".the-icon").eq(0).trigger("click");
            }
        })
    });



    function handle_mouse(e){

        var _t  = ($(this));

        if(e.type=='click'){


            if(_t.hasClass('btn-refresh-preview')){


                // jQuery('#save-ajax-loading').css('visibility', 'visible');
                var mainarray = currSlider.serializeAnything();

                //console.log(currSlider, currSlider.serializeAnything(), currSlider_nr);

                var auxslidernr = currSlider_nr;

                if(dzsap_settings.is_safebinding=='on'){
                    auxslidernr = dzsap_settings.currSlider;
                }

                var data = {
                    action: 'dzsap_save_configs'
                    ,postdata: mainarray
                    ,slider_name : 'temp123'
                    , currdb: dzsap_settings.currdb
                };
                jQuery.post(ajaxurl, data, function(response) {
                    if(window.console != undefined){
                        console.log('Got this from the server: ' + response);
                    }

                    $('.preview-player-iframe').attr('src','http://devsite/wpfactory/dzsap/wp-admin/admin.php?page=dzsap-mo&dzsap_preview_player=on&config=temp123');
                });
                return false;
            }
            if(_t.hasClass('btn-delete-waveform-data')){

                var r = confirm('are you sure you want to delete waveforms?');

                if(r){

                }else{
                    return false;
                }

            }


            if(_t.hasClass('regenerate-waveform')){

                _t.attr('data-player-source', $('#dzsap_woo_product_track').val()); // -- tbc



                var data = {
                    action: 'dzsap_delete_pcm'
                    ,playerid: _t.attr('data-playerid')
                    ,track_src: $('*[name="dzsap_woo_product_track"]').eq(0).val()
                };



                // console.error("TRY TO GET PCM");



                $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    data: data,
                    success: function (response) {
                        //if(typeof window.console != "undefined" ){ console.log('Ajax - get - comments - ' + response); }

                        console.groupCollapsed("receivedResponse");
                        console.info(response);
                        console.groupEnd();


                        _t.after('<iframe src="'+dzsap_settings.siteurl+'/?dzsap_generate_pcm='+_t.attr('data-playerid')+'&dzsap_source='+encodeURIComponent(_t.attr('data-player-source'))+'" width="100%" height="180"></iframe>')


                        setTimeout(function(){
                            _t.next('iframe').remove();
                        },10000);


                    },
                    error: function (arg) {
                        if (typeof window.console != "undefined") {
                            console.log('Got this from the server: ' + arg, arg);
                        }
                        ;
                    }
                })





                return false;
            }

        }
    }


    function change_vpconfig(){
        var _t = $(this);

        var _con = null;


        if(_t.parent().hasClass('vpconfig-wrapper')) {

            _con = _t.parent();
        }
        if(_t.parent().parent().hasClass('vpconfig-wrapper')) {

            _con = _t.parent().parent();
        }


        //console.info(_t,_con);


        if(_con){

            var selopt = _t.children(':selected');

            //console.info(selopt);

            // if(selopt.attr('data-sliderlink')){
            //     var aux='<a target="_blank" class="zoombox" data-type="iframe" href="'+dzsap_settings.url_vpconfig+'">Edit Configuration</a>';
            //
            //     //console.info(aux);
            //     aux = aux.replace(/{{currslider}}/g,selopt.attr('data-sliderlink'));
            //     //console.info(aux);
            //     //console.info(aux, _con.find('.edit-link-con').eq(0));
            //     _con.find('.edit-link-con').eq(0).html(aux);
            // }else{
            //
            //     _con.find('.edit-link-con').eq(0).html('');
            // }
        }

    }

    function click_btn_autogenerate_waveform_bg(e){
        var _t = $(this);
        var _themedia = '';
        var _con = null;


        // console.info($('.edd_repeatable_upload_field_container'));
        if($('.edd_repeatable_upload_field_container').length>0){

            var val = $('.edd_repeatable_upload_field_container input').eq(0).val();



            console.info($('*[name*="preview_files[0]"]').eq(0).length);
            if($('*[name*="preview_files[0]"]').eq(0).length && String($('*[name*="preview_files[0]"]').eq(0).val()) &&  (String($('*[name*="preview_files[0]"]').eq(0).val()).indexOf('mp3')> String($('*[name*="preview_files[0]"]').eq(0).val()).length-5 || String($('*[name*="preview_files[0]"]').eq(0).val()).indexOf('soundcloud.com')>-1 )  ) {

                val = $('*[name*="preview_files[0]"]').eq(0).val();
            }

            if(_t.parent().prev().prev().hasClass('aux-file-location')){
                _t.parent().prev().prev().html(val);
            }else{

                _t.parent().prev().before('<div class="aux-file-location">'+val+'</div>');
            }

            if(val.indexOf('soundcloud.com/')>-1){
                _t.parent().parent().parent().addClass('item-settings-con type_soundcloud')
            }
        }


        var initial_source = '';
        var is_souncloud = false;

        if(_t.parent().prev().prev().hasClass('aux-file-location')){
            _themedia = _t.parent().prev().prev().html();
        }else{
            if(_t.parent().parent().parent().find('.main-source').length>0){
                _themedia = _t.parent().parent().parent().find('.main-source').eq(0).val();
            }else{
                //console.log(_t.parent().parent().parent().parent().parent());
                if(_t.parent().parent().parent().parent().parent().hasClass('wc-metaboxes-wrapper')){
                    _con = _t.parent().parent().parent().parent().parent();
                    _themedia = _con.find('input[name="dzsap_woo_product_track"]').eq(0).val();

                    if(_con.parent().hasClass('product_data')){
                        if(_con.parent().find('input[name="_wc_file_urls[]"]').length>0){

                            if(_con.parent().find('input[name="_wc_file_urls[]"]').eq(0).val() && String(_con.parent().find('input[name="_wc_file_urls[]"]').eq(0).val()).indexOf('.mp3')>String(_con.parent().find('input[name="_wc_file_urls[]"]').eq(0).val()).length-5){

                                _themedia=_con.parent().find('input[name="_wc_file_urls[]"]').eq(0).val();
                            }
                        }
                    }
                    //console.info(_themedia);

                }
            }
        }

        initial_source = _themedia;

        // console.info(initial_source);

        if(_t.parent().parent().parent().hasClass('item-settings-con')){
            var _con = _t.parent().parent().parent();

            console.info(_con);

            if(_con.hasClass('type_soundcloud') && dzsap_settings.soundcloud_apikey){

                if(_con.attr('data-sc_source')){



                    _themedia = encodeURIComponent(dzsap_settings.thepath+'soundcloudretriever.php?scurl=' + _con.attr('data-sc_source'));
                    _con.attr('data-sc_source', '');
                    is_souncloud = true;

                }else{
                    var encoded_themedia = encodeURIComponent(_themedia);
                    var aux = 'http://api.' + 'soundcloud.com' + '/resolve?url='+_themedia+'&format=json&consumer_key=' + dzsap_settings.soundcloud_apikey;
                    // console.info(aux,_themedia);

                    if( (o.design_skin=='skin-wave' && !cthis.attr('data-scrubbg')) || is_ie8()){
                        o.skinwave_enableReflect='off';
                    }

                    aux = encodeURIComponent(aux);
                    $.getJSON((dzsap_settings.thepath+'soundcloudretriever.php?scurl='+aux), function(data) {

                        console.info(data.stream_url+'?consumer_key='+ dzsap_settings.soundcloud_apikey+'&origin=localhost');

                        _con.attr('data-sc_source',data.stream_url+'?consumer_key='+ dzsap_settings.soundcloud_apikey+'&origin=localhost');


                        _t.trigger('click');
                    });

                    return false;
                }



            }
        }



        if(typeof dzsap_settings!='undefined'){

            //console.info(_themedia);

            var s_filename_arr = _themedia.split('/');

            //console.info(s_filename_arr);
            var s_filename = s_filename_arr[s_filename_arr.length-1];

            s_filename = encodeURIComponent(s_filename);
            s_filename = s_filename.replace('.', '');


            if(is_souncloud){
                var auxa = initial_source.split('/');

                // console.info(auxa);
                s_filename = auxa[auxa.length-1];
            }

            window.waves_filename = '{{dirname}}waves/scrubbg_'+s_filename+'.png';

            if(dzsap_settings.theurl_forwaveforms != dzsap_settings.thepath){
                window.waves_filename = '{{uploaddirname}}scrubbg_'+s_filename+'.png';
            }


            ///console.info(s_filename);



            var str_sample_time_start = '';
            var str_sample_time_end = '';
            var str_sample_time_total = '';


            if(_con){
                if(_con.find('.sample-time-start-feeder').length>0){
                    if(Number(_con.find('.sample-time-start-feeder').eq(0).val())>0){
                        str_sample_time_start='&sample_time_start='+Number(_con.find('.sample-time-start-feeder').eq(0).val());
                    }
                }
                if(_con.find('.sample-time-end-feeder').length>0){
                    if(Number(_con.find('.sample-time-end-feeder').eq(0).val())>0){
                        str_sample_time_end='&sample_time_end='+Number(_con.find('.sample-time-end-feeder').eq(0).val());
                    }
                }
                if(_con.find('.sample-time-total-feeder').length>0){
                    if(Number(_con.find('.sample-time-total-feeder').eq(0).val())>0){
                        str_sample_time_total='&sample_time_total='+Number(_con.find('.sample-time-total-feeder').eq(0).val());
                    }
                }
            }

            var aux23 = window.waves_filename;

            if(aux23.indexOf('{{uploaddirname}}')>-1){

                aux23 = dzsap_settings.thepath_forwaveforms+'scrubbg_'+s_filename+'.png';
            }



            //console.log(_themedia);

            var aux='<object type="application/x-shockwave-flash" data="'+dzsap_settings.thepath+'wavegenerator.swf" width="230" height="30" id="flashcontent" style="visibility: visible;"><param name="movie" value="'+dzsap_settings.thepath+'wavegenerator.swf"><param name="menu" value="false"><param name="allowScriptAccess" value="always"><param name="scale" value="noscale"><param name="allowFullScreen" value="true"><param name="wmode" value="opaque"><param name="flashvars" value="settings_multiplier='+dzsap_settings.waveformgenerator_multiplier+'&media='+_themedia+'&savetophp_loc='+dzsap_settings.thepath+'savepng.php&savetophp_pngloc='+aux23+'&savetophp_pngprogloc=waves/scrubprog.png&color_wavesbg='+dzsap_settings.color_waveformbg+'&color_wavesprog='+dzsap_settings.color_waveformprog+'&settings_wavestyle='+dzsap_settings.settings_wavestyle+'&settings_onlyautowavebg=on&settings_enablejscallback=on'+str_sample_time_start+str_sample_time_end+str_sample_time_total+'"></object>';


            _t.parent().append(aux);
            if(_t.parent().prev().hasClass('upload-prev')){
                window.waves_fieldtaget = _t.parent().prev();
            }else{
                if(_t.parent().prev().prev().prev().hasClass('upload-prev')){

                    window.waves_fieldtaget = _t.parent().prev().prev().prev();
                }else{

                    window.waves_fieldtaget = _t.parent().prev().prev();
                }
            }

            //console.info(_t.parent().parent());
            if(_t.parent().parent().prev().hasClass('upload-target-prev')){

                window.waves_fieldtaget = _t.parent().parent().prev();

            }

            console.warn(window.waves_fieldtaget)


            _t.hide();
        }


        return false;
    }
    function click_btn_autogenerate_waveform_prog(e){
        var _t = $(this);
        var _themedia = '';
        var _con = null;


        var initial_source = '';
        var is_souncloud = false;



        if($('.edd_repeatable_upload_field_container').length>0){

            var val = $('.edd_repeatable_upload_field_container input').eq(0).val();



            if($('*[name*="preview_files[0]"]').eq(0).length && String($('*[name*="preview_files[0]"]').eq(0).val()) && (String($('*[name*="preview_files[0]"]').eq(0).val()).indexOf('mp3')> String($('*[name*="preview_files[0]"]').eq(0).val()).length-5 || String($('*[name*="preview_files[0]"]').eq(0).val()).indexOf('soundcloud.com')>-1 )  ) {

                val = $('*[name*="preview_files[0]"]').eq(0).val();
            }

            if(_t.parent().prev().prev().hasClass('aux-file-location')){
                _t.parent().prev().prev().html(val);
            }else{

                _t.parent().prev().before('<div class="aux-file-location">'+val+'</div>');
            }

            if(val.indexOf('soundcloud.com/')>-1){
                _t.parent().parent().parent().addClass('item-settings-con type_soundcloud')
            }
        }


        if(_t.parent().prev().prev().hasClass('aux-file-location')){
            _themedia = _t.parent().prev().prev().html();
        }else{
            if(_t.parent().parent().parent().find('.main-source').length>0){
                _themedia = _t.parent().parent().parent().find('.main-source').eq(0).val();
            }else{
                //console.log(_t.parent().parent().parent().parent().parent());
                if(_t.parent().parent().parent().parent().parent().hasClass('wc-metaboxes-wrapper')){
                    _con = _t.parent().parent().parent().parent().parent();
                    _themedia = _t.parent().parent().parent().parent().parent().find('input[name="dzsap_woo_product_track"]').eq(0).val();
                    //console.info(_themedia);

                }
            }
        }





        initial_source = _themedia;

        // console.info(initial_source);

        if(_t.parent().parent().parent().hasClass('item-settings-con')){
            var _con = _t.parent().parent().parent();

            console.info(_con);

            if(_con.hasClass('type_soundcloud') && dzsap_settings.soundcloud_apikey){

                if(_con.attr('data-sc_source')){



                    _themedia = encodeURIComponent(dzsap_settings.thepath+'soundcloudretriever.php?scurl=' + _con.attr('data-sc_source'));
                    _con.attr('data-sc_source', '');
                    is_souncloud = true;

                }else{
                    var encoded_themedia = encodeURIComponent(_themedia);
                    var aux = 'http://api.' + 'soundcloud.com' + '/resolve?url='+_themedia+'&format=json&consumer_key=' + dzsap_settings.soundcloud_apikey;
                    // console.info(aux,_themedia);

                    if( (o.design_skin=='skin-wave' && !cthis.attr('data-scrubbg')) || is_ie8()){
                        o.skinwave_enableReflect='off';
                    }

                    aux = encodeURIComponent(aux);
                    $.getJSON((dzsap_settings.thepath+'soundcloudretriever.php?scurl='+aux), function(data) {

                        console.info(data.stream_url+'?consumer_key='+ dzsap_settings.soundcloud_apikey+'&origin=localhost');

                        _con.attr('data-sc_source',data.stream_url+'?consumer_key='+ dzsap_settings.soundcloud_apikey+'&origin=localhost');


                        _t.trigger('click');
                    });

                    return false;
                }



            }
        }




        if(typeof dzsap_settings!='undefined'){

            //console.info(_themedia);

            var s_filename_arr = _themedia.split('/');

            //console.info(s_filename_arr);
            var s_filename = s_filename_arr[s_filename_arr.length-1];

            s_filename = encodeURIComponent(s_filename);
            s_filename = s_filename.replace('.', '');


            if(is_souncloud){
                var auxa = initial_source.split('/');

                // console.info(auxa);
                s_filename = auxa[auxa.length-1];
            }

            window.waves_filename = '{{dirname}}waves/scrubprog_'+s_filename+'.png';

            if(dzsap_settings.theurl_forwaveforms != dzsap_settings.thepath){
                window.waves_filename = '{{uploaddirname}}scrubprog_'+s_filename+'.png';
            }
            ///console.info(s_filename);



            var str_sample_time_start = '';
            var str_sample_time_end = '';
            var str_sample_time_total = '';


            if(_con){
                if(_con.find('.sample-time-start-feeder').length>0){
                    if(Number(_con.find('.sample-time-start-feeder').eq(0).val())>0){
                        str_sample_time_start='&sample_time_start='+Number(_con.find('.sample-time-start-feeder').eq(0).val());
                    }
                }
                if(_con.find('.sample-time-end-feeder').length>0){
                    if(Number(_con.find('.sample-time-end-feeder').eq(0).val())>0){
                        str_sample_time_end='&sample_time_end='+Number(_con.find('.sample-time-end-feeder').eq(0).val());
                    }
                }
                if(_con.find('.sample-time-total-feeder').length>0){
                    if(Number(_con.find('.sample-time-total-feeder').eq(0).val())>0){
                        str_sample_time_total='&sample_time_total='+Number(_con.find('.sample-time-total-feeder').eq(0).val());
                    }
                }
            }

            var aux23 = window.waves_filename;

            if(aux23.indexOf('{{uploaddirname}}')>-1){

                aux23 = dzsap_settings.thepath_forwaveforms+'scrubprog_'+s_filename+'.png';
            }


            var aux='<object type="application/x-shockwave-flash" data="'+dzsap_settings.thepath+'wavegenerator.swf" width="230" height="30" id="flashcontent" style="visibility: visible;"><param name="movie" value="'+dzsap_settings.thepath+'wavegenerator.swf"><param name="menu" value="false"><param name="allowScriptAccess" value="always"><param name="scale" value="noscale"><param name="allowFullScreen" value="true"><param name="wmode" value="opaque"><param name="flashvars" value="settings_multiplier='+dzsap_settings.waveformgenerator_multiplier+'&media='+_themedia+'&savetophp_loc='+dzsap_settings.thepath+'savepng.php&savetophp_pngloc='+window.waves_filename+'&savetophp_pngprogloc='+aux23+'&color_wavesbg='+dzsap_settings.color_waveformbg+'&color_wavesprog='+dzsap_settings.color_waveformprog+'&settings_wavestyle='+dzsap_settings.settings_wavestyle+'&settings_onlyautowaveprog=on&settings_enablejscallback=on'+str_sample_time_start+str_sample_time_end+str_sample_time_total+'"></object>';


            _t.parent().append(aux);
            if(_t.parent().prev().hasClass('upload-prev')){
                window.waves_fieldtaget = _t.parent().prev();
            }else{

                if(_t.parent().prev().prev().prev().hasClass('upload-prev')){

                    window.waves_fieldtaget = _t.parent().prev().prev().prev();
                }else{

                    window.waves_fieldtaget = _t.parent().prev().prev();
                }
            }

            //console.info(_t.parent().parent());
            if(_t.parent().parent().prev().hasClass('upload-target-prev')){

                window.waves_fieldtaget = _t.parent().parent().prev();

            }


            _t.hide();
        }


        return false;
    }

    function click_btn_generate_default_waveform_bg(e){
        var _t = $(this);
        var _themedia = dzsap_settings.thepath + 'waves/scrubbg_default.png';

        _t.parent().find('.textinput').eq(0).val(_themedia);


        return false;
    }
    function click_btn_generate_default_waveform_prog(e){
        var _t = $(this);
        var _themedia = dzsap_settings.thepath + 'waves/scrubprog_default.png';

        _t.parent().find('.textinput').eq(0).val(_themedia);


        return false;
    }
    function click_btn_upload_for_target(e){
        var _t = $(this);
        var _targetInput = _t.prev();


        if(_t.parent().hasClass('upload-for-target-con')){
            _targetInput = _t.parent().find('input').eq(0);
        }else{

            if(_t.parent().parent().parent().hasClass('upload-for-target-con')){

                _targetInput = _t.parent().parent().parent().find('input').eq(0);
            }
        }

        var searched_type = '';

        if(_targetInput.hasClass('upload-type-audio')){
            searched_type = 'audio';
        }
        if(_targetInput.hasClass('upload-type-image')){
            searched_type = 'image';
        };

        var frame = wp.media.frames.dzsap_thumb = wp.media({
            // Set the title of the modal.
            title: "Insert Preview Image",

            // Tell the modal to show only images.
            library: {
                type: searched_type
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

            //console.log(attachment.attributes, $('*[name*="video-player-config"]'));
            var arg = attachment.attributes.url;


            console.warn(attachment);
            if(_targetInput.hasClass('upload-get-id')){

                arg= attachment.attributes.id;

            }
            if(_targetInput.hasClass('upload-target-prev')){
                _targetInput.val(arg);
                _targetInput.trigger('change');
            }





            frame.close();
        });

        // Finally, open the modal.
        frame.open();



        return false;
    }




    if(_wrap.hasClass('wrap-for-generator-player')){
    }
});



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
    jQuery(document).undelegate(".select-wrapper select", "change");
    jQuery(document).delegate(".select-wrapper select", "change",  change_select);


    function change_select(){
        var selval = (jQuery(this).find(':selected').text());
        jQuery(this).parent().children('span').text(selval);
    }

}





function get_query_arg(purl, key){
    //console.info(purl);

    // console.info("THIS", purl, key);
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



function add_query_arg(purl, key,value){
    key = encodeURIComponent(key); value = encodeURIComponent(value);

    var s = purl;
    var pair = key+"="+value;

    var r = new RegExp("(&|\\?)"+key+"=[^\&]*");

    s = s.replace(r,"$1"+pair);
    //console.log(s, pair);
    if(s.indexOf(key + '=')>-1){


    }else{
        if(s.indexOf('?')>-1){
            s+='&'+pair;
        }else{
            s+='?'+pair;
        }
    }
    //if(!RegExp.$1) {s += (s.length>0 ? '&' : '?') + kvp;};

    return s;
}

