(function() {
	$(document).ready(function(){
		// Store variables
        var accordion_head = $('.accordion > li > a'),
            accordion_body = $('.accordion li > .sub-menu');
        // Open the first tab on load
        accordion_head.next().show();
        // Click function
        accordion_head.on('click', function(event) {
            // Disable header links
            event.preventDefault();
            // Show and hide the tabs on click

            if ($(this).attr('class') != 'active'){
                $(this).next().show();
                $(this).next().stop(true,true).slideToggle(200);
                //accordion_head.removeClass('active');
                $(this).addClass('active');
            }else{
                $(this).next().slideUp(200);
                $(this).next().stop(true,true).slideToggle(200);
                $(this).removeClass('active');
            }
        });

        $('#check_all_chk').click(function(event) {
            var $check_single_chks = $(this).parent('th').parent('tr').parent('thead').next('tbody').find('.check_single_chk');
            
            if($(this).prop('checked')){
                $check_single_chks.prop('checked', true);
            }
            else{
                $check_single_chks.prop('checked', false);
            }
        });

	});
	
}).call(this);

function open_loading_window(){
    $("#loading_window").modal("show");
}
function close_loading_window(){
    $("#loading_window").removeAttr('title').modal("hide");
}