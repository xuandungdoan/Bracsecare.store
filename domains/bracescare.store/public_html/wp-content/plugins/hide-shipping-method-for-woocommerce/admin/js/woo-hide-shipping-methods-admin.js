(function ($) {
    'use strict';
    jQuery(".multiselect2").select2();
    function allowSpeicalCharacter(str) {
        return str.replace('&#8211;', '–').replace("&gt;", ">").replace("&lt;", "<").replace("&#197;", "Å");
    }
    $(".shipping_method_list").select2({
        placeholder: "Select shipping method"
    });
    $("#sm_select_day_of_week").select2({
        placeholder: "Select days of the week"
    });
    function productFilter() {
        jQuery('.product_fees_conditions_values_product').each(function () {
            $('.product_fees_conditions_values_product').select2({
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            value: params.term,
                            action: 'whsma_product_fees_conditions_values_product_ajax'
                        };
                    },
                    processResults: function (data) {
                        var options = [];
                        if (data) {
                            $.each(data, function (index, text) {
                                options.push({id: text[0], text: allowSpeicalCharacter(text[1])});
                            });

                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        });
    }

    function varproductFilter() {
        $('.product_fees_conditions_values_var_product').each(function () {
            $('.product_fees_conditions_values_var_product').select2({
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            value: params.term,
                            action: 'whsma_product_fees_conditions_varible_values_product_ajax__premium_only'
                        };
                    },
                    processResults: function (data) {
                        var options = [];
                        if (data) {
                            $.each(data, function (index, text) {
                                options.push({id: text[0], text: allowSpeicalCharacter(text[1])});
                            });

                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        });
    }

    
/* Premium Code Stripped by Freemius */

    function setAllAttributes(element, attributes) {
        Object.keys(attributes).forEach(function (key) {
            element.setAttribute(key, attributes[key]);
            // use val
        });
        return element;
    }

    function numberValidateForAdvanceRules() {
        $('.number-field').keypress(function (e) {
            var regex = new RegExp("^[0-9-%.]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        $('.qty-class').keypress(function (e) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        $('.weight-class, .price-class').keypress(function (e) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
    }

    $(window).load(function () {
        $(".multiselect2").select2();

        $(".shipping_method_list").select2({
            placeholder: "Select shipping method"
        });
        $("#sm_select_day_of_week").select2({
            placeholder: "Select days of the week"
        });
        // $("#sm_time_from").select2({
        //     placeholder: "Select start time"
        // });
        // $("#sm_time_to").select2({
        //     placeholder: "Select end time"
        // });

        $('.hide_shipping').hide();

        
/* Premium Code Stripped by Freemius */



        var ele = $('#total_row').val();
        var count;
        if (ele > 2) {
            count = ele;
        } else {
            count = 2;
        }
        $('body').on('click', '#fee-add-field', function () {
            var fee_add_field = $('#tbl-shipping-method tbody').get(0);

            var tr = document.createElement("tr");
            tr = setAllAttributes(tr, {"id": "row_" + count});
            fee_add_field.appendChild(tr);

            // generate td of condition
            var td = document.createElement("td");
            td = setAllAttributes(td, {});
            tr.appendChild(td);
            var conditions = document.createElement("select");
            conditions = setAllAttributes(conditions, {
                "rel-id": count,
                "id": "product_fees_conditions_condition_" + count,
                "name": "fees[product_fees_conditions_condition][]",
                "class": "product_fees_conditions_condition"
            });
            conditions = insertOptions(conditions, get_all_condition());
            td.appendChild(conditions);
            // td ends

            // generate td for equal or no equal to
            td = document.createElement("td");
            td = setAllAttributes(td, {});
            tr.appendChild(td);
            var conditions_is = document.createElement("select");
            conditions_is = setAllAttributes(conditions_is, {
                "name": "fees[product_fees_conditions_is][]",
                "class": "product_fees_conditions_is product_fees_conditions_is_" + count
            });
            conditions_is = insertOptions(conditions_is, condition_types());
            td.appendChild(conditions_is);
            // td ends

            // td for condition values
            td = document.createElement("td");
            td = setAllAttributes(td, {"id": "column_" + count});
            tr.appendChild(td);
            condition_values(jQuery('#product_fees_conditions_condition_' + count));

            var condition_key = document.createElement("input");
            condition_key = setAllAttributes(condition_key, {
                "type": "hidden",
                "name": "condition_key[value_" + count + "][]",
                "value": "",
            });
            td.appendChild(condition_key);
            var conditions_values_index = jQuery(".product_fees_conditions_values_" + count).get(0);
            jQuery(".product_fees_conditions_values_" + count).trigger("chosen:updated");
            jQuery(".multiselect2").select2();
            // td ends

            // td for delete button
            td = document.createElement("td");
            tr.appendChild(td);
            var delete_button = document.createElement("a");
            delete_button = setAllAttributes(delete_button, {
                "id": "fee-delete-field",
                "rel-id": count,
                "title": coditional_vars.delete,
                "class": "delete-row",
                "href": "javascript:;"
            });
            var deleteicon = document.createElement('i');
            deleteicon = setAllAttributes(deleteicon, {
                "class": "fa fa-trash"
            });
            delete_button.appendChild(deleteicon);
            td.appendChild(delete_button);
            // td ends

            count++;
        });

        $('body').on('change', '.product_fees_conditions_condition', function () {
            condition_values(this);
        });

        /* description toggle */
        $('span.woo_hide_shipping_methods_tab_description').click(function (event) {
            event.preventDefault();
            $(this).next('p.description').toggle();
        });

        
/* Premium Code Stripped by Freemius */


        //remove tr on delete icon click
        $('body').on('click', '.delete-row', function () {
            $(this).parent().parent().remove();
        });

        function insertOptions(parentElement, options) {
            for (var i = 0; i < options.length; i++) {
                if (options[i].type == 'optgroup') {
                    var optgroup = document.createElement("optgroup");
                    optgroup = setAllAttributes(optgroup, options[i].attributes);
                    for (var j = 0; j < options[i].options.length; j++) {
                        var option = document.createElement("option");
                        option = setAllAttributes(option, options[i].options[j].attributes);
                        option.textContent = options[i].options[j].name;
                        optgroup.appendChild(option);
                    }
                    parentElement.appendChild(optgroup);
                } else {
                    var option = document.createElement("option");
                    option = setAllAttributes(option, options[i].attributes);
                    option.textContent = allowSpeicalCharacter(options[i].name);
                    parentElement.appendChild(option);
                }

            }
            return parentElement;

        }

        function get_all_condition() {
            return [
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.location_specific},
                    "options": [
                        {"name": coditional_vars.country, "attributes": {"value": "country"}},
                        
/* Premium Code Stripped by Freemius */

                    ]
                },
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.product_specific},
                    "options": [
                        {"name": coditional_vars.cart_contains_product, "attributes": {"value": "product"}},
                        
/* Premium Code Stripped by Freemius */

                        {"name": coditional_vars.cart_contains_category_product, "attributes": {"value": "category"}},
                        
/* Premium Code Stripped by Freemius */

                    ]
                },
                
/* Premium Code Stripped by Freemius */

                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.user_specific},
                    "options": [
                        {"name": coditional_vars.user, "attributes": {"value": "user"}},
                        
/* Premium Code Stripped by Freemius */

                    ]
                },
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.cart_specific},
                    "options": [
                        {"name": coditional_vars.cart_subtotal_before_discount, "attributes": {"value": "cart_total"}},
                        
/* Premium Code Stripped by Freemius */

                        {"name": coditional_vars.quantity, "attributes": {"value": "quantity"}},
                        
/* Premium Code Stripped by Freemius */

                    ]
                },
            ];
        }

        function condition_values(element) {
            var condition = $(element).val();
            var count = $(element).attr('rel-id');
            var column = jQuery('#column_' + count).get(0);
            jQuery(column).empty();
            var loader = document.createElement('img');
            loader = setAllAttributes(loader, {'src': coditional_vars.plugin_url + 'images/ajax-loader.gif'});
            column.appendChild(loader);

            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'whsma_product_fees_conditions_values_ajax',
                    'condition': condition,
                    'count': count
                },
                contentType: "application/json",
                success: function (response) {
                    var condition_values;
                    jQuery('.product_fees_conditions_is_' + count).empty();
                    var column = jQuery('#column_' + count).get(0);
                    var condition_is = jQuery('.product_fees_conditions_is_' + count).get(0);
                    if (condition == 'cart_total'
                        || condition == 'quantity'
                        
/* Premium Code Stripped by Freemius */

                    ) {
                        condition_is = insertOptions(condition_is, condition_types(true));
                    } else {
                        condition_is = insertOptions(condition_is, condition_types(false));
                    }
                    jQuery('.product_fees_conditions_is_' + count).trigger("change");
                    jQuery(column).empty();

                    var placeholder_msg = "";
                    var condition_values_id = '';
                    var extra_class = '';
                    if (condition == 'product') {
                        condition_values_id = 'product-filter-' + count;
                        placeholder_msg = coditional_vars.validation_length1;
                        extra_class = 'product_fees_conditions_values_product';
                    } 
/* Premium Code Stripped by Freemius */
 else {
                        placeholder_msg = coditional_vars.select_some_options;
                    }

                    if (isJson(response)) {
                        condition_values = document.createElement("select");
                        condition_values = setAllAttributes(condition_values, {
                            "name": "fees[product_fees_conditions_values][value_" + count + "][]",
                            "class": "whsm_select product_fees_conditions_values product_fees_conditions_values_" + count + " multiselect2 " + extra_class,
                            "multiple": "multiple",
                            "id": condition_values_id,
                            "data-placeholder": placeholder_msg
                        });
                        column.appendChild(condition_values);
                        var data = JSON.parse(response);
                        condition_values = insertOptions(condition_values, data);
                    } else {
                        condition_values = document.createElement(jQuery.trim(response));
                        condition_values = setAllAttributes(condition_values, {
                            "name": "fees[product_fees_conditions_values][value_" + count + "]",
                            "class": "product_fees_conditions_values",
                            "type": "text",

                        });
                        column.appendChild(condition_values);
                    }
                    column = $('#column_' + count).get(0);
                    var input_node = document.createElement('input');
                    input_node = setAllAttributes(input_node, {
                        'type': 'hidden',
                        'name': 'condition_key[value_' + count + '][]',
                        'value': ''
                    });
                    column.appendChild(input_node);

                    jQuery(".multiselect2").select2();
                    productFilter();

                    
/* Premium Code Stripped by Freemius */

                    numberValidateForAdvanceRules();
                }
            });
        }

        function condition_types(text = false) {
            if (text == true) {
                return [
                    {"name": coditional_vars.equal_to, "attributes": {"value": "is_equal_to"}},
                    {"name": coditional_vars.less_or_equal_to, "attributes": {"value": "less_equal_to"}},
                    {"name": coditional_vars.less_than, "attributes": {"value": "less_then"}},
                    {"name": coditional_vars.greater_or_equal_to, "attributes": {"value": "greater_equal_to"}},
                    {"name": coditional_vars.greater_than, "attributes": {"value": "greater_then"}},
                    {"name": coditional_vars.not_equal_to, "attributes": {"value": "not_in"}},
                ];
            } else {
                return [
                    {"name": coditional_vars.equal_to, "attributes": {"value": "is_equal_to"}},
                    {"name": coditional_vars.not_equal_to, "attributes": {"value": "not_in"}},
                ];

            }

        }

        productFilter();

        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (err) {
                return false;
            }
            return true;
        }
    });
    jQuery(window).on('load', function () {
        jQuery(".multiselect2").select2();

        jQuery(".shipping_method_list").select2({
            placeholder: "Select shipping method"
        });
        jQuery("#sm_select_day_of_week").select2({
            placeholder: "Select days of the week"
        });
        // jQuery("#sm_time_from").select2({
        //     placeholder: "Select start time"
        // });
        // jQuery("#sm_time_to").select2({
        //     placeholder: "Select end time"
        // });

        function allowSpeicalCharacter(str) {
            return str.replace('&#8211;', '–').replace("&gt;", ">").replace("&lt;", "<").replace("&#197;", "Å");
        }

        jQuery('.product_fees_conditions_values_product').each(function () {
            $('.product_fees_conditions_values_product').select2({
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            value: params.term,
                            action: 'whsma_product_fees_conditions_values_product_ajax'
                        };
                    },
                    processResults: function (data) {
                        var options = [];
                        if (data) {
                            $.each(data, function (index, text) {
                                options.push({id: text[0], text: allowSpeicalCharacter(text[1])});
                            });

                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        });


        $('.product_fees_conditions_values_var_product').each(function () {
            $('.product_fees_conditions_values_var_product').select2({
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            value: params.term,
                            action: 'whsma_product_fees_conditions_varible_values_product_ajax__premium_only'
                        };
                    },
                    processResults: function (data) {
                        var options = [];
                        if (data) {
                            $.each(data, function (index, text) {
                                options.push({id: text[0], text: allowSpeicalCharacter(text[1])});
                            });

                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        });

        
/* Premium Code Stripped by Freemius */

        function setAllAttributes(element, attributes) {
            Object.keys(attributes).forEach(function (key) {
                element.setAttribute(key, attributes[key]);
                // use val
            });
            return element;
        }

        function numberValidateForAdvanceRules() {
            $('.number-field').keypress(function (e) {
                var regex = new RegExp("^[0-9-%.]+$");
                var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
                if (regex.test(str)) {
                    return true;
                }
                e.preventDefault();
                return false;
            });
            $('.qty-class').keypress(function (e) {
                var regex = new RegExp("^[0-9]+$");
                var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
                if (regex.test(str)) {
                    return true;
                }
                e.preventDefault();
                return false;
            });
            $('.weight-class, .price-class').keypress(function (e) {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
                if (regex.test(str)) {
                    return true;
                }
                e.preventDefault();
                return false;
            });
        }
    });
})(jQuery);


/* Premium Code Stripped by Freemius */
