function eternalShowMenuPopup(objMenu, event, popupId)
{
    var $ = jQuery;
    if (typeof eternalCustommenuTimerHide[popupId] != 'undefined') 
        clearTimeout(eternalCustommenuTimerHide[popupId]);
    
    var $popup = $('#' + popupId); 
    if (!$popup.length) 
        return;
    
    if (!!eternalActiveMenu)
        eternalHideMenuPopup(objMenu, event, eternalActiveMenu.popupId, eternalActiveMenu.menuId);
    
    $objMenu = $(objMenu);
    eternalActiveMenu = {menuId: objMenu.id, popupId: popupId};
    if (!$objMenu.hasClass('active')) {
        $objMenu.addClass('active');
        var pos = eternalPopupPos(objMenu);
        var con_width = $objMenu.closest('.container').width();
        $popup.css({
            'top': pos.top + 'px',
            'left': pos.left + 'px',
            'width': $objMenu.closest('.container').width() + 'px'
        });
        eternalSetPopupZIndex($popup);
        
        // --- Static Block width ---
        var $block2 = $popup.find('div.block2');
        if ($block2.length) {
            block2_id = $block2.attr('id');
            var wStart = block2_id.indexOf('_w');
            if (wStart > -1) {
                var w = block2_id.substr(wStart+2);
            } else {
                var w = 0;
                $popup.find('div.block1 div.column').each(function() {
                    w += $(this).width();
                });
            }
            if (w) $block2.css('width', w + 'px');
        }
        // --- change href ---
        var $eternalMenuAnchor = $objMenu.find('a');
        eternalChangeTopMenuHref($eternalMenuAnchor);
        $popup.stop(true, true).fadeIn();
    }
}

function eternalHideMenuPopup(element, event, popupId, menuId)
{
    var $ = jQuery;
    if (typeof eternalCustommenuTimerShow[popupId] != 'undefined') 
        clearTimeout(eternalCustommenuTimerShow[popupId]);
    
    var $element = $(element); 
    var $objMenu = $('#' + menuId);
    var $popup = $('#' + popupId); 
    if (!$popup.length) 
        return;
        
    var eternalCurrentMouseTarget = getCurrentMouseTarget(event);
    if (!!eternalCurrentMouseTarget) {
        if (!eternalIsChildOf($element.get(0), eternalCurrentMouseTarget) && $element.get(0) != eternalCurrentMouseTarget) {
            if (!eternalIsChildOf($popup.get(0), eternalCurrentMouseTarget) && $popup.get(0) != eternalCurrentMouseTarget) {
                if ($objMenu.hasClass('active')) {
                    $objMenu.removeClass('active');
                    // --- change href ---
                    var $eternalMenuAnchor = $objMenu.find('a');
                    eternalChangeTopMenuHref($eternalMenuAnchor);
                    $popup.stop(true, true).fadeOut();                
                }
            }
        }
    }
}

function eternalPopupOver(element, event, popupId, menuId)
{
    if (typeof eternalCustommenuTimerHide[popupId] != 'undefined') clearTimeout(eternalCustommenuTimerHide[popupId]);
}

function eternalPopupPos(objMenu, w)
{
    var $ = jQuery;
    $objMenu = $(objMenu);
    var pos = $objMenu.offset();
    var $wraper = $('#custommenu');
    var $container = $('.header-container .container');
    var posWraper = $wraper.offset();
    var posContainer = $container.offset();
    var xTop = pos.top - posWraper.top;
    if (CUSTOMMENU_POPUP_TOP_OFFSET) {
        xTop += CUSTOMMENU_POPUP_TOP_OFFSET;
    } else {
        xTop += $objMenu.height();
    }
    var res = {'top': xTop};
    xLeft = posContainer.left - posWraper.left + 15;
    res.left = xLeft;
    return res;
}

function eternalChangeTopMenuHref($eternalMenuAnchor)
{
    if ($eternalMenuAnchor.attr('rel') == '#')
        return;
    var eternalValue = $eternalMenuAnchor.attr('href');
    $eternalMenuAnchor.attr('href', $eternalMenuAnchor.attr('rel'));
    $eternalMenuAnchor.attr('rel', eternalValue);
}

function eternalIsChildOf(parent, child)
{
    if (child != null) {
        while (child.parentNode) {
            if ((child = child.parentNode) == parent) {
                return true;
            }
        }
    }
    return false;
}

function eternalSetPopupZIndex($popup)
{
    var $ = jQuery;
    $('.eternal-custom-menu-popup').each(function(){
       $(this).css('z-index', '98');
    });
    $popup.css('z-index', '99');
}

function getCurrentMouseTarget(xEvent)
{
    var eternalCurrentMouseTarget = null;
    if (xEvent.toElement) {
        eternalCurrentMouseTarget = xEvent.toElement;
    } else if (xEvent.relatedTarget) {
        eternalCurrentMouseTarget = xEvent.relatedTarget;
    }
    return eternalCurrentMouseTarget;
}

function getCurrentMouseTargetMobile(xEvent)
{
    if (!xEvent) var xEvent = window.event;
    var eternalCurrentMouseTarget = null;
    if (xEvent.target) eternalCurrentMouseTarget = xEvent.target;
        else if (xEvent.srcElement) eternalCurrentMouseTarget = xEvent.srcElement;
    if (eternalCurrentMouseTarget.nodeType == 3) // defeat Safari bug
        eternalCurrentMouseTarget = eternalCurrentMouseTarget.parentNode;
    return eternalCurrentMouseTarget;
}

/* Mobile */
function eternalMenuButtonToggle()
{
    var $ = jQuery;
    if ($('.header-right').hasClass('menu-active')){
        $('.header-right').removeClass('menu-active');
    }else{
        $('.header-right').addClass('menu-active');
    }    
//    $('#menu-content').slideToggle(400);
    $('#menu-content').slideToggle(100);
}

function eternalGetMobileSubMenuLevel(id)
{
    var $ = jQuery;
    var rel = $('#' + id).attr('rel');
    if (!rel) return 0;
    return parseInt(rel.replace('level', ''));
}

function eternalSubMenuToggle(obj, activeMenuId, activeSubMenuId)
{
    var $ = jQuery;
    var $obj = $(obj);
    var currLevel = eternalGetMobileSubMenuLevel(activeSubMenuId);
    // --- hide submenus ---
    $('.eternal-custom-menu-submenu').each(function() {
        item_id = $(this).attr('id');
        if (item_id == activeSubMenuId) 
            return;
        var xLevel = eternalGetMobileSubMenuLevel(item_id);
        if (xLevel >= currLevel) {
            $('#' + item_id).stop(true, true).slideUp(100);
        }
    });
    // --- reset button state ---
    setTimeout(function() {
        $('#custommenu-mobile').find('span.button').each(function() {
            var subMenuId = $(this).attr('rel');
            if (!subMenuId) subMenuId = 'submenu-mobile-custom';
            if ($('#' + subMenuId).css('display') == 'none') {
                $(this).removeClass('open');
            }
        }); 
    }, 700)
    // ---
    $activeSubMenu = $('#' + activeSubMenuId);
    if ($activeSubMenu.css('display') == 'none') {
        $activeSubMenu.stop(true, true).slideDown(100);		
        $obj.addClass('open');
    } else {
        $activeSubMenu.stop(true, true).slideUp(100);
        $obj.removeClass('open');
    }
}

function eternalResetMobileMenuState()
{
    var $ = jQuery;
    $('#menu-content').hide();
    $('.eternal-custom-menu-submenu').each(function() {
        $(this).hide();
    });
    $('#custommenu-mobile').find('span.button').each(function() {
        $(this).removeClass('open');
    });
}

function eternalCustomMenuMobileToggle()
{
    var $ = jQuery;
    var w = window,
        d = document,
        e = d.documentElement,
        g = d.getElementsByTagName('body')[0],
        x = g.clientWidth || w.innerWidth || e.clientWidth ,
        y = w.innerHeight|| e.clientHeight || g.clientHeight;  
   
    if (x <= 991 || eternalIsMobile.any()) {
        $('#custommenu').hide();
        $('#custommenu-mobile').show();        
        if (ZONDA_HEADER_TYPE == 'type2') {
            $('.header-menu-right .header-right').css('float', 'none');
            $('.header-menu-right .header-right .nav-container').css('float', 'left');
        }
    } else {
        $('#custommenu-mobile').hide();
        eternalResetMobileMenuState();
        $('#custommenu').show();
        $('.header-right').removeClass('menu-active');
        if (ZONDA_HEADER_TYPE == 'type2') {
            $('.header-menu-right .header-right').css('float', '');
            $('.header-menu-right .header-right .nav-container').css('float', '');
        }
    }    
    if (eternalIsMobile.any()) {
        $('.header-container').addClass('mobile-header');
    }
}

Event.observe(window, 'resize', function() {
    eternalCustomMenuMobileToggle();
});

document.observe("dom:loaded", function() {
    //run navigation with delays
    mainNav("nav-links", {"show_delay":"0","hide_delay":"0"});
});
