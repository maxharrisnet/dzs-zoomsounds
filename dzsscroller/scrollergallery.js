(function(e){e.fn.scrollergallery=function(c){c=e.extend({scrollerSettings:{},galleryType:"scroller",settings_fullwidthHack:"off",layout:"masonry",innerWidth:"0",design_bgpadding:"100",design_bgrotation:"10",design_parallaxeffect:"off",design_itemwidth:"",design_itemheight:"",settings_lightboxlibrary:"zoombox"},c);c.design_bgpadding=parseInt(c.design_bgpadding,10);c.design_bgrotation=parseInt(c.design_bgrotation,10);!1==isNaN(parseInt(c.design_itemheight,10))&&(c.design_itemheight=parseInt(c.design_itemheight,
    10));!1==isNaN(parseInt(c.design_itemwidth,10))&&(c.design_itemheight=parseInt(c.design_itemheight,10));this.each(function(){function s(a){E++;E>=B&&F()}function F(){!0!=G&&(G=!0,"masonry"==c.layout&&h.masonry({columnWidth:1}),"collage"==c.layout&&h.collagePlus({targetHeight:100}),p=h.width(),d.bind("mousemove",X),"scroller"==c.type&&(h=d.find(".inner").eq(0),d.scroller(c.scrollerSettings),q=d.find(".scroller").eq(0),q.addClass("con-items"),H()),"arrows"==c.type&&Y(),"zoombox"==c.settings_lightboxlibrary&&
    e(".zoombox").zoomBox(),"prettyPhoto"==c.settings_lightboxlibrary&&jQuery.fn.prettyPhoto&&d.find("a[rel^='prettyPhoto']").prettyPhoto())}function X(a){I=a.pageX-d.offset().left;J=a.pageY-d.offset().top;"on"==c.design_parallaxeffect&&(t=u=0,v&&(u=I/w-0.5,t=J/x-0.5),clearTimeout(K),K=setTimeout(Z,10))}function Z(){d.find(".the-bg").eq(0).css({transform:"translate3d("+(6*u-3)+"%,"+(6*t-3)+"%,0)"});q.css({transform:"translate3d("+(12*u-6)+"px,"+(12*t-6)+"px,0)"})}function Y(){d.addClass("type-arrows");
    h=d.find(".inner").eq(0);h.wrap('<div class="con-items clip-inners"><div class="inners"></div></div>');f=d.find(".inners").eq(0);q=d.find(".con-items").eq(0);var a=[],b=0;h.children().each(function(){var b=jQuery(this);0==parseInt(b.css("top"),10)&&a.push(parseInt(b.css("left"),10))});for(k=a.length-1;0<k;k--)b=-a[k],l.push(b);l.push(0);var L=a.length-1;for(k=1;k<a.length;k++)b=p-a[L],l.push(b),L--;n=parseInt(l.length/2,10);f.append(h.clone());f.append(h.clone());f.children().eq(0).find("div.sgitem[data-type=imageandprettyphoto]").each(function(){var a=
        jQuery(this);void 0!=a.attr("data-prettyPhotoGallery")&&""!=a.attr("data-prettyPhotoGallery")&&a.children("a").attr("rel",a.children("a").attr("rel")+"1")});f.children().eq(1).find("div.sgitem[data-type=imageandprettyphoto]").each(function(){var a=jQuery(this);void 0!=a.attr("data-prettyPhotoGallery")&&""!=a.attr("data-prettyPhotoGallery")&&a.children("a").attr("rel",a.children("a").attr("rel")+"2")});f.children().eq(2).find("div.sgitem[data-type=imageandprettyphoto]").each(function(){var a=jQuery(this);
        void 0!=a.attr("data-prettyPhotoGallery")&&""!=a.attr("data-prettyPhotoGallery")&&a.children("a").attr("rel",a.children("a").attr("rel")+"3")});"masonry"==c.layout&&f.children().addClass("masonry");"collage"==c.layout&&f.children().addClass("layout-collage");f.children().eq(0).css("left",-p);f.children().eq(1).css("left",0);f.children().eq(2).css("left",p);d.append('<div class="arrowleft-con"><div class="arrowleft"></div></div>');d.append('<div class="arrowright-con"><div class="arrowright"></div></div>');
    m=y;f.css("left",m);"on"==c.settings_fullwidthHack&&(M=d.offset().left,N=jQuery(window).width());d.find(".arrowright-con").bind("click",$);d.find(".arrowleft-con").bind("click",aa);H()}function aa(){var a=n;a++;a>=l.length?O():C(a)}function $(){var a=n;a--;0>a?O():C(a)}function O(){!0!=z&&(0==n&&(m+=p,f.css("left",m)),n>=l.length-1&&(m-=p,f.css("left",m)),C(parseInt(l.length/2,10)))}function P(){"on"==c.design_parallaxeffect&&"arrows"==c.type&&(q.css({left:c.design_bgpadding,width:w-2*c.design_bgpadding,
    height:x-2*c.design_bgpadding,overflow:"hidden"}),v&&(f.css({left:0}),y=0))}function C(a){!0!=z&&(z=!0,n=a,f.animate({left:l[a]+y},{queue:!1,complete:ba}),m=l[a]+y,n=a)}function ba(){z=!1}function ca(a){var b=e(this),c=b.parent().children().index(b);if(!0!=r[c]){var d=a.pageX-b.offset().left;a=a.pageY-b.offset().top;b=Q(d,a,b.width(),b.height());"left"==b&&e(this).find(".desc").css(D);"right"==b&&e(this).find(".desc").css(R);"top"==b&&e(this).find(".desc").css(S);"bottom"==b&&e(this).find(".desc").css(T);
    e(this).find(".desc").eq(0).stop().animate({left:0,top:0},{duration:300,queue:!0,complete:da,easing:U});r[c]=!0}}function ea(a){var b=e(this),c=b.parent().children().index(b),d=a.pageX-b.offset().left;a=a.pageY-b.offset().top;b=Q(d,a,b.width(),b.height());d=D;"left"==b&&(d=D);"right"==b&&(d=R);"top"==b&&(d=S);"bottom"==b&&(d=T);e(this).find(".desc").eq(0).animate(d,{duration:300,queue:!0,complete:fa,easing:U});r[c]=!0}function da(){var a=e(this).parent().parent().children().index(e(this).parent());
    r[a]=!1}function fa(){var a=e(this),a=a.parent().parent().children().index(a.parent());r[a]=!1}function A(a,b,c,d){a-=c;b-=d;return a*a+b*b}function Q(a,b,c,d){var e=A(a,b,c/2,0),g=A(a,b,c/2,d),f=A(a,b,0,d/2);a=A(a,b,c,d/2);switch(Math.min(e,g,f,a)){case f:return"left";case a:return"right";case e:return"top";case g:return"bottom"}}function H(){d.parent().hasClass("scroller-gallery-con")&&(V=d.parent(),V.children(".preloader").fadeOut("slow"));w=d.width();x=d.height();0==d.css("opacity")&&d.animate({opacity:1},
    600);v&&q.css({top:c.design_bgpadding});1==d.find(".real-inner").length&&(h=d.find(".real-inner"));for(k=0;k<B;k++)r[k]=!1;d.find(".inner").children().each(function(){var a="page",b=e(this);b.hasClass("a-image")&&(a="image");b.hasClass("a-video")&&(a="video");b.find(".desc").children("div").append('<div class="icon '+a+'"></div>');a="";"imageandlink"==b.attr("data-type")&&(a+='<a href="'+b.attr("data-link")+'">',a+="</a>");"imageandlightbox"==b.attr("data-type")&&("prettyPhoto"==c.settings_lightboxlibrary&&
    (a='<a href="'+b.attr("data-link")+'" rel="prettyPhoto',void 0!=b.attr("data-lightboxgallery")&&""!=b.attr("data-lightboxgallery")&&(a+="["+b.attr("data-lightboxgallery")+']"')),"zoombox"==c.settings_lightboxlibrary&&(a='<a href="'+b.attr("data-link")+'" class="zoombox"',void 0!=b.attr("data-lightboxgallery")&&""!=b.attr("data-lightboxgallery")&&(a+=' data-biggallery="'+b.attr("data-lightboxgallery")+'" data-biggallerythumbnail="'+b.attr("data-src")+'"')),a+="></a>");b.find(".fake-link").wrap(a)});
    d.find(".inner").children().bind("mouseenter",ca);d.find(".inner").children().bind("mouseleave",ea);e(window).bind("resize",W);W();P()}function W(){w=d.width();x=d.height();"on"==c.settings_fullwidthHack&&(aux=0,aux=M+(jQuery(window).width()-N)/2,0>aux&&(aux=0),d.css("left",-aux));P()}c.innerWidth=parseInt(c.innerWidth,10);var w=0,x=0,p=0,M=0,N=0,d=jQuery(this),h,f,V,l=[],m=0,n=0,q,z=!1,r=[],G=!1,E=0,B,y=50,U="linear",k=0,v=!1,D={left:"-100%",top:0},R={left:"100%",top:0},S={left:"0",top:"-100%"},
    T={left:"0",top:"100%"},I=0,J=0,u,t,K=0;(function(){h=d.find(".inner").eq(0);0!=c.innerWidth&&h.css("width",c.innerWidth);B=h.children().length;0<d.children(".clip-bg").length&&(v=!0);h.children().each(function(){var a=jQuery(this);if(a.hasClass("sgitem-tobe")){a.removeClass("sgitem-tobe");var b="",d="",e="",f=!1;isNaN(parseInt(c.design_itemwidth,10))||(d=" width: "+c.design_itemwidth+"px;");isNaN(parseInt(c.design_itemheight,10))||(e=" height: "+c.design_itemheight+"px;");var g="data-itemwidth";
    a.attr(g)&&""!=a.attr(g)&&(d=-1<a.attr(g).indexOf("%")?" width: "+a.attr(g)+";":" width: "+a.attr(g)+"px;");g="data-itemheight";a.attr(g)&&""!=a.attr(g)&&(e=-1<a.attr(g).indexOf("%")?" height: "+a.attr(g)+";":" height: "+a.attr(g)+"px;");""!=d&&(f=!0);g='<img src="'+a.attr("data-src")+'" style="'+d+e+'"/>';f&&(g='<div class="imgtobg" style="'+d+e+" background-image:url("+a.attr("data-src")+')"></div>');"image"==a.attr("data-type")&&(b=g,a.prepend(b));"imageandlink"==a.attr("data-type")&&(b='<a href="'+
        a.attr("data-link")+'">',b=b+g+"</a>",a.prepend(b));"imageandlightbox"==a.attr("data-type")&&(0==a.find(".desc").length&&("prettyPhoto"==c.settings_lightboxlibrary&&(b='<a href="'+a.attr("data-link")+'" rel="prettyPhoto',void 0!=a.attr("data-lightboxgallery")&&""!=a.attr("data-lightboxgallery")&&(b+="["+a.attr("data-lightboxgallery")+']"')),"zoombox"==c.settings_lightboxlibrary&&(b='<a href="'+a.attr("data-link")+'" class="zoombox"',void 0!=a.attr("data-lightboxgallery")&&""!=a.attr("data-lightboxgallery")&&
        (b+=' data-biggallery="'+a.attr("data-lightboxgallery")+'"')),b+=">"),b+=g,0==a.find(".desc").length&&(b+="</a>"),a.prepend(b))}a.addClass("sgitem");a="IMG"==a.get(0).nodeName?a.get(0):a.find("img").eq(0).get(0);void 0==a?s():!0==a.complete&&0!=a.naturalWidth?s():jQuery(a).bind("load",s)});setTimeout(F,5E3)})();return this})};window.dzssg_init=function(c,s){e(c).scrollergallery(s)}})(jQuery);