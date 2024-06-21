jQuery(document).ready(function($) {

    /**
     * Build email suggestions process
     * 
     * @since 3.5.0
     * @version 3.5.2
     * @param {string} field | Field to add actions
     * @package MeuMouse.com
     */
    function email_suggestions(field) {
        var email_providers = fcw_emails_suggestions_params.get_providers || [];

        $(field).each( function() {
            var input_field = $(this);
            var field_id = input_field.attr('id');

            // Check and create the suggestion container if it doesn't exist
            if ($('#flexify_checkout_email_suggest_' + field_id).length < 1) {
                input_field.after('<div id="flexify_checkout_email_suggest_' + field_id + '"></div>');
            }

            var suggestion_container = $('#flexify_checkout_email_suggest_' + field_id);
            var auto_list = $("<ul>").addClass("auto-list").appendTo(suggestion_container);

            /**
             * Display suggestions list
             * 
             * @since 3.5.0
             * @param {string} list | Email start part filled
             */
            function show_suggestions(list) {
                auto_list.empty();

                list.forEach( function(provider) {
                    var suggestion = $("<li>").text(input_field.val().split('@')[0] + "@" + provider);

                    suggestion.on("click", function() {
                        input_field.val($(this).text());
                        auto_list.removeClass('show');
                        $("button[type='submit']").prop("disabled", false);
                        input_field.change();
                    });

                    auto_list.append(suggestion);
                });

                auto_list.addClass('show');
            }

            input_field.on("keyup", function(e) {
                var value = input_field.val();

                if (value.includes("@")) {
                    var parts = value.split("@");

                    if (parts.length > 1) {
                        var domainPart = parts[1];
                        var suggestions = email_providers.filter( function(provider) {
                            return provider.startsWith(domainPart);
                        });

                        if (suggestions.length > 0) {
                            show_suggestions(suggestions);
                        } else {
                            auto_list.removeClass('show');
                        }
                    }
                } else {
                    auto_list.removeClass('show');
                }
            });

            input_field.on("blur", function() {
                setTimeout( function() {
                    auto_list.removeClass('show');
                }, 200);
            });
        });
    }

    // Apply email_suggestions function to all email fields in WooCommerce checkout
    var email_fields = $("p.form-row[data-type='email'] input");

    email_suggestions(email_fields);
});