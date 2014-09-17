jQuery(document).ready(function($){
    $('.show-case-plus').hover(function(e){
        //hover plus button
        $btw = 10;
        $product_id = $(this).attr('data-productid');
        $product_position = $(this).attr('data-productpos');
        $prod_element = $('#' + $product_id);
        $offset = $(this).position();
        switch($product_position) {
            case 'left-top':
                $offset_x = $offset.left + $(this).width() + $btw;
                $offset_y = $offset.top;
                break;
            case 'left-bottom':
                $offset_x = $offset.left + $(this).width() + $btw;
                $offset_y = $offset.top + $(this).height() - $prod_element.height();
                break;
            case 'right-top':
                $offset_x = $offset.left - $prod_element.width() - $btw;
                $offset_y = $offset.top;
                break;
            case 'right-bottom':
                $offset_x = $offset.left - $prod_element.width() - $btw;
                $offset_y = $offset.top + $(this).height() - $prod_element.height();
                break;
            default:
                $offset_x = $offset.left + $(this).width() + $btw;
                $offset_y = $offset.top;
        }
        $prod_element.css('top',$offset_y);
        $prod_element.css('left',$offset_x);
        $prod_element.show();
    },function(e){
        //out plus button
        $('#' + $product_id).hide();
    });
});