function slideChange(args) { 
    
}
function slideComplete(args) {
    if (!args.slideChanged) return false;
    effects(args);                                     
}

function sliderLoaded(args) {
    effects(args);
    slideChange(args);
}
function effects(args){
    var sub_container = $(args.sliderObject).find('.sub-container'),
        thisContainer = $(args.currentSlideObject).find('.sub-container'),
        anim, animMain, animSub, animTitle, animContent;
        sub_container.find('.slider-desc, .blank-dark, .slider-desc-title, .slider-desc-content').attr('style', '');
    if (thisContainer.length > 0) {
        if (thisContainer.attr('class').indexOf('fromside') > 0) {
             anim = {
                'margin-left': '-390px',
                opacity: 1
            };
            animMain = {
                'margin-right': '-370px',
                opacity: 1
            };
            animTitle = {
                'margin-left': 0,
                opacity: 1
            };
            animContent = {
                'margin-left': 0,
                opacity: 1
            };
            thisContainer.find('.slider-desc').delay(100).animate(anim, 1000, 'easeOutBounce');
            thisContainer.find('.blank-dark').delay(1100).animate(animMain, 1000, 'easeOutBounce');
            thisContainer.find('.slider-desc-title').delay(2000).animate(animTitle, 1000, 'easeOutCirc');
            thisContainer.find('.slider-desc-content').delay(3000).animate(animContent, 1000, 'easeOutCirc');
            
        } else if (thisContainer.attr('class').indexOf('fromtop') > 0){
            anim = {
                'margin-left': '-390px',
                opacity: 1
            };
            animMain = {
                'margin-top': 0
            };
            animSub = {
                opacity: 1
            };
            animTitle = {
                opacity: 1
            };
            animContent = {
                opacity: 1
            };
            thisContainer.find('.slider-desc').delay(100).animate(anim, 1000, 'easeOutExpo');
            thisContainer.find('.slider-desc').delay(200).animate(animMain, 1200, 'easeOutExpo');
            thisContainer.find('.blank-dark').delay(1500).animate(animSub, 800, 'easeOutSine');
            thisContainer.find('.slider-desc-title').delay(2000).animate(animTitle, 1000, 'easeOutCirc');
            thisContainer.find('.slider-desc-content').delay(3000).animate(animContent, 1000, 'easeOutCirc');
        } else {
            anim = {
                opacity: 1
            };
            animMain = {
                opacity: 1
            };
            animTitle = {
                'margin-left': 0,
                opacity: 1
            };
            animContent = {
                opacity: 1
            };
            thisContainer.find('.slider-desc').delay(100).animate(anim, 1000, 'easeOutQuad');
            thisContainer.find('.blank-dark').delay(1100).animate(animMain, 1000, 'easeOutCubic');
            thisContainer.find('.slider-desc-title').delay(2000).animate(animTitle, 1000, 'easeOutCirc');
            thisContainer.find('.slider-desc-content').delay(3000).animate(animContent, 1000, 'easeOutCirc');
        }
    }
    
}
