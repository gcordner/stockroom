	/*
	*
	*
	*/
jQuery.noConflict();
jQuery(function($) {
	var myhref,qsbtt;

	// base function
	
	//get IE version
	function ieVersion(){
		var rv = -1; // Return value assumes failure.
		if (navigator.appName == 'Microsoft Internet Explorer'){
			var ua = navigator.userAgent;
			var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null)
				rv = parseFloat( RegExp.$1 );
		}
		return rv;
	}

	//read href attr in a tag
	function readHref(){
		var mypath = arguments[0];
		var patt = /\/[^\/]{0,}$/ig;
		if(mypath[mypath.length-1]=="/"){
			mypath = mypath.substring(0,mypath.length-1);
			return (mypath.match(patt)+"/");
		}
		return mypath.match(patt);
	}


	//string trim
	function strTrim(){
		return arguments[0].replace(/^\s+|\s+$/g,"");
	}

	function _qsJnit(){
        $('li a.quick-view').fancybox({
			'titleShow'            : false,
			'width'                : 700,
			'height'            : 'auto',
			'autoSize'            : false,
			'autoScale'            : false,
			'transitionIn'        : 'none',
			'transitionOut'        : 'none',
			'autoDimensions'    : false,
			'scrolling'         : 'no',
			'padding'             :0,
			'margin'            :0,
			'type'                : 'ajax',                    
			helpers : {
				title : null 
			}
		});
	}
	_qsJnit({
		itemClass : '.products-grid li.item', //selector for each items in catalog product list,use to insert quickview image
		aClass : 'a.quick-view', //selector for each a tag in product items,give us href for one product
		imgClass: '.product-image' //class for quickview href product-collateral
	});
});


