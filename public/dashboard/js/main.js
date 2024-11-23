$.noConflict();

jQuery(document).ready(function($) {

	$('#username').on('keyup',function(){
                var username = $('#username').val();
                if(username.length > 2) {
                    $('#username_availability_result').html('Loading..').css({'color':'#009933'});
                    var post_string = 'username='+username;
                    $.ajax({
                        type : 'POST',
                        data : post_string,
                        url  : 'check_user.php',
                        success: function(responseText){
                        if(responseText == 0){
                            $('#username_availability_result').html('The Username is Available').css({'color':'#009933'});
    						$("#register").show();
                        }else if(responseText > 0){
                            $('#username_availability_result').html('This Username has been chosen').css({'color':'Red'});
    						$("#register").hide();
                        }else{
                            alert('Problem with mysql query');
                        }
                    }
                });
            }else{
                $('#username_availability_result').html('');
            }
        });
	$('#ref_username').on('keyup',function(){
                var username = $('#ref_username').val();
                if(username.length > 2) {
                    $('#ref_availability_result').html('Loading..').css({'color':'#009933'});
                    var post_string = 'username='+username;
                    $.ajax({
                        type : 'POST',
                        data : post_string,
                        url  : 'check_user.php',
                        success: function(responseText){
                        if(responseText == 0){
                            $('#ref_availability_result').html('The Ref Does not exist').css({'color':'red'});
    						$("#register").hide();
                        }else if(responseText > 0){
                            $('#ref_availability_result').html('Valid Ref').css({'color':'#009933'});
    						$("#register").show();
                        }else{
                            alert('Problem with mysql query');
                        }
                    }
                });
            }else{
                $('#ref_availability_result').html('');
            }
        });
	$('#rwallet').on('keyup',function()
	{
		var receiver = document.getElementById('rwallet').value;
		$('#receiver_availability_result').html('Loading..').css({'color':'#009933'});
		if(receiver !== null)
		{
			var post_string ='receiver='+receiver;
			 $.ajax({
                        type : 'POST',
                        data : post_string,
                        url  : 'validate_receiver.php',
                        success: function(responseText){
                        if(responseText.length < 2){
                            $('#receiver_availability_result').html("Username does not exist.").css({'color':'Red'});
    						$("#transact_transfer").hide();
                        }else if(responseText.length > 0){
                            $('#receiver_availability_result').html(responseText).css({'color':'#009933'});
    						$("#transact_transfer").show();
                        }else{
                            alert('Problem with query');
                        }
                    }
                });
		}else
		{
			$('#receiver_availability_result').html('Pls enter receivers username!').css({'color':'Red'});
		}
	});	
		
	"use strict";

	[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {
		new SelectFx(el);
	} );

	jQuery('.selectpicker').selectpicker;
	

	$('#menuToggle').on('click', function(event) {
		$('body').toggleClass('open');
	});

	$('.search-trigger').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		$('.search-trigger').parent('.header-left').addClass('open');
	});

	$('.search-close').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		$('.search-trigger').parent('.header-left').removeClass('open');
	});

	// $('.user-area> a').on('click', function(event) {
	// 	event.preventDefault();
	// 	event.stopPropagation();
	// 	$('.user-menu').parent().removeClass('open');
	// 	$('.user-menu').parent().toggleClass('open');
	// });

});