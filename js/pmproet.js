jQuery(document).ready(function($) {
    
	/* Variables */
	var template, disabled, $subject, $editor, $testemail;
	$subject = $("#email_template_subject").closest("tr");
	$editor = $("#wp-email_template_body-wrap");
	$testemail = $("#test_email_address").closest("tr");
	
    $(".hide-while-loading").hide();
    $(".controls").hide();
    $(".striped tr:even").css('background-color','#efefef');

    /* PMPro Email Template Switcher */
    $("#pmpro_email_template_switcher").change(function() {
        
        $(".status_message").hide();
        template = $(this).val();
        
        //get template data
        if (template)
            getTemplate(template);
        else {
            $(".hide-while-loading").hide();
            $(".controls").hide();
        }
    });

    $("#submit_template_data").click(function() {
        saveTemplate()
    });

    $("#reset_template_data").click(function() {
        resetTemplate();
    });

    $("#email_template_disable").click(function(e) {
        disableTemplate();
    });

    $("#send_test_email").click(function(e) {       
		saveTemplate().done(setTimeout(function(){sendTestEmail();}, '1000'));
    });

    /* Functions */
    function getTemplate(template) {        
				
		//hide stuff and show ajax spinner
        $(".hide-while-loading").hide();
        $("#pmproet-spinner").show();

        //get template data
        $data = {
            template: template,
            action: 'pmproet_get_template_data',
            security: $('input[name=security]').val()
        };

        $.post(ajaxurl, $data, function(response) {
            var template_data = JSON.parse(response);

            //show/hide stuff
			$("#pmproet-spinner").hide();
            $(".controls").show();
            $(".hide-while-loading").show();
            $(".status").hide();

            //change disable text
            if (template == 'header' || template === 'footer') {

                $subject.hide();
				$testemail.hide();
				
                if(template == 'header')
                    $("#disable_label").text("Disable email header for all PMPro emails?");
                else
                    $("#disable_label").text("Disable email footer for all PMPro emails?");

                //hide description
                $("#disable_description").hide();
            }
            else {
                $testemail.show();
				$("#disable_label").text("Disable this email?");
                $("#disable_description").show().text("PMPro emails with this template will not be sent.");
            }

            // populate subject and body
			$('#email_template_subject').val(template_data['subject']);
			$('#email_template_body').val(template_data['body']);

            // disable form
            disabled = template_data['disabled'];
            toggleFormDisabled(disabled);
        });
    }

    function saveTemplate() {

//        $(".controls").hide();
        $("#submit_template_data").attr("disabled", true);
        $(".status").hide();
        console.log(template);

        $data = {
            template: template,
            subject: $("#email_template_subject").val(),
            body: $("#email_template_body").val(),
            action: 'pmproet_save_template_data',
            security: $('input[name=security]').val()
        };
        $.post(ajaxurl, $data, function(response) {
            if(response != 0) {
                $("#message").addClass('updated');
            }
            else {
                $("#message").addClass("error");
            }
            $("#submit_template_data").attr("disabled", false);
            $(".status_message").html(response);
            $(".status").show();
            $(".status_message").show();
        });

		return $.Deferred().resolve();
    }

    function resetTemplate() {

        var r = confirm('Are you sure? Your current template settings will be deleted permanently.');

        if(!r) return false;

        $data = {
            template: template,
            action: 'pmproet_reset_template_data',
            security: $('input[name=security]').val()
        };
        $.post(ajaxurl, $data, function(response) {
            var template_data = $.parseJSON(response);
            $('#email_template_subject').val(template_data['subject']);
            $('#email_template_body').val(template_data['body']);
        });

        return true;
    }

    function disableTemplate() {

        //update wp_options
        data = {
            template: template,
            action: 'pmproet_disable_template',
            disabled: $("#email_template_disable").is(":checked"),
            security: $('input[name=security]').val()
        };

        $.post(ajaxurl, data, function(response) {

            response = JSON.parse(response);

            //failure
            if(response['result'] == false) {
                $("#message").addClass("error");
                $(".status_message").show().text("There was an error updating your template settings.");
            }
            else {
                if(response['status'] == 'true') {
                    $("#message").addClass("updated");
                    $(".status_message").show().text("Template Disabled");
                }
                else {
                    $("#message").addClass("updated");
                    $(".status_message").show().text("Template Enabled");
                }
            }

            $(".hide-while-loading").show();

            disabled = response['status'];

            toggleFormDisabled(disabled);
        });
    }

    function sendTestEmail() {

        //hide stuff and show ajax spinner
        $(".hide-while-loading").hide();
        $("#pmproet-spinner").show();

        data = {
            template: template,
            email: $("#test_email_address").val(),			
            action: 'pmproet_send_test',
            security: $('input[name=security]').val()
        };

        $.post(ajaxurl, data, function(success) {
            //show/hide stuff
            $("#pmproet-spinner").hide();
            $(".controls").show();
            $(".hide-while-loading").show();

            if(success) {
                $("#message").addClass("updated").removeClass("error");
                $(".status_message").show().text("Test e-mail sent successfully.");
            }
            else {
                $("#message").addClass("error").removeClass("updated");
                $(".status_message").show().text("Test e-mail failed.");
            }

        })
    }

    function toggleFormDisabled(disabled) {

        if(disabled == 'true') {
            $("#email_template_disable").attr('checked', true);
            $("#email_template_body").attr('readonly', 'readonly').attr('disabled', 'disabled');
            $("#email_template_subject").attr('readonly', 'readonly').attr('disabled', 'disabled');
            $(".controls").hide();
        }
        else {
            $("#email_template_disable").attr('checked', false);
            $("#email_template_body").removeAttr('readonly','readonly').removeAttr('disabled', 'disabled');
            $("#email_template_subject").removeAttr('readonly','readonly').removeAttr('disabled', 'disabled');
            $(".controls").show();
        }

    }

});