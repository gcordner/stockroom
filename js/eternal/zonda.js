var eternalIsMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (eternalIsMobile.Android() || eternalIsMobile.BlackBerry() || eternalIsMobile.iOS() || eternalIsMobile.Opera() || eternalIsMobile.Windows());
    }
};

// Prototype trigger event
Element.prototype.triggerEvent = function(eventName)
{
    if (document.createEvent)
    {
        var evt = document.createEvent('HTMLEvents');
        evt.initEvent(eventName, true, true);

        return this.dispatchEvent(evt);
    }

    if (this.fireEvent)
        return this.fireEvent('on' + eventName);
}

var global_select = '';

function eternalEvent(id, event) {
    global_select = id;
    $(id).triggerEvent(event);
    global_select = '';
}


function zonda_refresh_elements() {
    // Customize Selectbox
    jQuery('.form-list select').each(function() {
        var id = jQuery(this).attr('id');
        if (jQuery(this).css('display') == 'none') {
            jQuery(this).selectbox('detach');
            jQuery(this).hide();
        } else {
            jQuery(this).selectbox('attach');
        }
    });
    jQuery('.option select').each(function() {
        var id = jQuery(this).attr('id');
        if (jQuery(this).css('display') == 'none') {
            jQuery(this).selectbox('detach');
            jQuery(this).hide();
        } else {
            jQuery(this).selectbox('attach');
        }
    });
}


//header toplinks align when window resizes
function top_resize(){
    var $ = jQuery;
    var $mobilelinks = $('.mobile-toplinks .links');    
    if ($mobilelinks.length){
        var li_sum = 0;
        $('.mobile-toplinks .links li').each(function(){            
                li_sum += $(this).width();              
        });        
        if ( li_sum > $('.mobile-toplinks .links').width() - 10){
            $('.mobile-toplinks .links .login-container').css('display', 'none');
            $('.mobile-toplinks .login-container-big').css('display', 'block');
        }else{
            $('.mobile-toplinks .links .login-container').css('display', 'inline-block');
            $('.mobile-toplinks .login-container-big').css('display', 'none');
        }
    } 
}

//sticky header
function header_fixed() {
    var $ = jQuery; 
    var w = window,
        d = document,
        e = d.documentElement,
        g = d.getElementsByTagName('body')[0],
        x = g.clientWidth || w.innerWidth || e.clientWidth ,
        y = w.innerHeight|| e.clientHeight || g.clientHeight;                   
    if (eternalIsMobile.any() || x <= 991) {
        $(".header-container").removeClass("fixed");
        $('.header-container .header-menu-back').css('height', 'auto');
        return;
    }
    var padding_height = $('.header-container .header-menu-back').height() - $('.header-container').height();
    window_y = $(window).scrollTop();            
    if (window_y > $('.header-container').height()) {
        if (!($(".header-container").hasClass("fixed"))){            
            $(".header-container").addClass("fixed");
            if ($('.nicescroll-rails.nicescroll-rail').length){
                nice.resize();
            }
            if ($('body').hasClass('header-abs')) {
                $('.header-abs .header-container').css('position','relative');
                $('body').css('margin-top', padding_height);
                $('#loading-mask,#after-loading-success-message').css('margin-top',-1 * padding_height);
            } else {
                if ($('.header-container').attr('position') == 'relative') {
                    $('body').css('padding-top', $('.header-container.fiexed').height());
                }
            }
            if (ZONDA_HEADER_TYPE == 'type2') {
                $('.header-container.fixed .header-menu-back').height(70);
                $('.header-container.fixed .header-menu-back').css('top', '-70px');    
            } else {
                $('.header-container.fixed .header-menu-back').height('auto');
                $('.header-container.fixed .header-menu-back').css('top', '-200px');
            }
            
			$('.header-container.fixed .header-menu-back').stop().animate({'top': 0},
            function(){
                $('.header-container.fixed .header-menu-back').css('overflow','visible');
            });
        }
    } else {
        if (($(".header-container").hasClass("fixed"))){
            $(".header-container").removeClass("fixed");
            $('.header-container .header-menu-back').css('height', 'auto');  
            if ($('.nicescroll-rails.nicescroll-rail').length){
                nice.resize();
            }
            if ($('body').hasClass('header-abs')) {
                $('.header-abs .header-container').css('position','absolute');
                $('body').css('margin-top', 0);
                $('#loading-mask,#after-loading-success-message').css('margin-top',0);
            } else {
                if ($('.header-container').attr('position') == 'relative') {
                    $('body').css('padding-top', '0');
                }
            }
        }
    }
}
//product grid resize            
function products_grid_resize(){
    var $ = jQuery;
    var winWidth = $(window).outerWidth();
    if (ZONDA_RESPONSIVE) {
        if (winWidth >= 991) {
                $('.2columns .products-grid .item').removeClass('item-first');
                $('.2columns .products-grid .col3-1').addClass('item-first');
        } else {
                $('.2columns .products-grid .item').removeClass('item-first');
                $('.2columns .products-grid .col2-1').addClass('item-first');
        }
    }
    if (ZONDA_RESPONSIVE) {
        if (winWidth >= 991) {
                $('.1column .category-products .products-grid .item').removeClass('col4');
                $('.1column .category-products .products-grid .item').addClass('col3');
                $('.1column .category-products .products-grid .item').removeClass('item-first');
                $('.1column .category-products .products-grid .col4-1').addClass('item-first');
        } else {
                $('.1column .category-products .products-grid .item').removeClass('item-first');
                $('.1column .category-products .products-grid .col3-1').addClass('item-first');
        }
    }
    if (ZONDA_RESPONSIVE) {
        if (winWidth >= 991) {
                $('.1column.cms-index-index .products-grid .item').removeClass('col4');
                $('.1column.cms-index-index .products-grid .item').addClass('col3');
                $('.1column.cms-index-index .products-grid .item').removeClass('item-first');
                $('.1column.cms-index-index .products-grid .col4-1').addClass('item-first');
        } else {
                $('.1column.cms-index-index .products-grid .item').removeClass('item-first');
                $('.1column.cms-index-index .products-grid .col3-1').addClass('item-first');
        }
    }
    
}
function side_tool_bar() {
    var $ = jQuery;
    if ($('.block-layered-nav').length){
        var block = $('.block-layered-nav .filter-list');
        $('.filter-item', block).last().removeClass('akordeon-border-bottom');
    }
}

function footer_logo_setting() {
	var $ = jQuery;
	if (ZONDA_BRANDS_CUSTOM)
	{
		if ($('.footer-banner').length)
		{
			$('.footer-top .footer-column-1 h3.title').addClass('custom-title');
		}
	}
}
function product_img_view() {
     jQuery('li a.view-product').fancybox({
            'width'                : 700,
            'height'            : 'auto',
            helpers: {
                title: {
                    type: 'inside'
                }
            }    
        }); 
}
function more_info_view() {
      if(eternalIsMobile.any()) {
          jQuery('.more-info').css('display','none');
      }
}
jQuery(document).ready(function($){
    top_resize();
    side_tool_bar();
	footer_logo_setting();
    products_grid_resize();
    more_info_view();
    //product image fancybox
    product_img_view();
    if ( ZONDA_HEADER_FIXED ) 
        header_fixed();    
    var top_timer;
    $(window).resize(function(){
        clearTimeout(top_timer);
        top_timer = setTimeout(function(){
            top_resize();
            header_fixed();
            products_grid_resize();            
        }, 100);        
    });
    $(window).scroll(function(){
        if ( ZONDA_HEADER_FIXED ) 
            header_fixed();
    });
    // select box change event
    $('.form-list select,.option select').change(function(e) {
        id = $(this).attr('id');
        if (id && id != global_select)
            eternalEvent($(this).attr('id'), 'change');
        zonda_refresh_elements();
    });
});