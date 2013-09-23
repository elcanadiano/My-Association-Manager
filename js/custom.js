var is_selected = false;

$(function(){
    $('#mobile-dropdown').click(function(){

        // If it is selected, remove the highlight and collapse the dropdown menu.
        if(is_selected) {
            $('#mobile-dropdown').removeClass('selected');

            $('#main-nav').slideUp(200, function(){
                $('#main-nav').removeAttr('style');
            });

            is_selected = false;
        } else {
            $('#mobile-dropdown').addClass('selected');

            $('#main-nav').slideDown(200);

            is_selected = true;
        }
    });
});