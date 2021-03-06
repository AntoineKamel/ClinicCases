$(document).ready(function(){

		$('div.wrapper').addClass('ui-corner-all');

		//Submit the form
		$('#sbmt').click(function(event){
			event.preventDefault();

			var formVals = $(this).closest('form');
            var errString = validNewAccount(formVals);
            if (errString.length)
            {
                notify(errString,true);
                formVals.find('.ui-state-error').click(function() {
                    $(this).removeClass('ui-state-error');
                });

                return false;
            }
            else
            {
				formValsArray = formVals.serializeArray();
                $.post('../lib/php/users/new_account_process.php', formValsArray, function(data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true)
                    {
                        notify(serverResponse.message,true);
                        Recaptcha.reload();
                    }
                    else
                    {
                        notify(serverResponse.message,false);
                        formVals.remove();
                        $('div.new_account_right').html(serverResponse.html);
                        $('div.new_account_left p').remove();

                    }
                });
			}
		});


	});