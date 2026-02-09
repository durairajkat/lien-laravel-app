$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});


$(document).ready(function () {

    var queryStringExists = checkQuery('project_id');
    if(!queryStringExists) {
        //resetValues();
    }

    function resetValues() {
        $('#p_name').val('');
        $('#state').val('');
        $('#project_type').val('');
        $('#role').val('');
        $('#customer').val('');
        $('#question').html('');
        $('#customer-contract').val('');
        $('#industry-contract').val('').trigger('chosen:updated');
        $('#project_address').val('');
        $('#project_city').val('');
        $('#project_county').val('');
        $('#project_zip').val('');
        $('#company_work').val('');
    }

    <!-- Industry contacts Chosen fields -->
    $(".industry-contract").chosen({
        no_results_text: "Oops, nothing found!",
        width: "100%"
    });

    <!-- Save Data once they click on Express button without saving anything -->
    $('.clickExpress').on('click', function (event) {
        event.stopPropagation();
        event.preventDefault();
        var data = $(".project_details").serialize();
        $.ajax({
            type: "POST",
            url: saveSession,
            data: data,
            success: function (data) {
                if (data.status) {
                    window.location.href = replaceUrlParam('view', 'express', window.location.href);
                    return true;
                }
            }
        });
    });
    $('.clickDetailed').on('click', function (event) {
        event.stopPropagation();
        event.preventDefault();
        var data = $(".project_details").serialize();
        $.ajax({
            type: "POST",
            url: saveSession,
            data: data,
            success: function (data) {
                if (data.status) {
                    window.location.href = replaceUrlParam('view', 'detailed', window.location.href);
                    return true;
                }
            }
        });
    });
    $('.customer-contract').on('focus', function () {
        /*$('html, body').animate({
            scrollTop: $(".section-industry-contact_holder").offset().top
        }, 2000);*/
        var customer_contract = $('.customer-contract').val();
        if (customer_contract != '') {
            $(".section-industry-contact_holder").show();
            // $('html, body').animate({
            //     scrollTop: $(".section-industry-contact_holder").offset().top
            // }, 2000);
        } else {
            $(".section-industry-contact_holder").show();
            $(".address_holder").hide();
            $(".city_holder").hide();
            $(".county_holder").hide();
            $(".companyWork_holder").hide();
        }
    });
    $('#industry_contract_chosen').focusin(function () {
        /*$('html, body').animate({
            scrollTop: $(".address_holder").offset().top
        }, 2000);*/
        var industryContact = $('.industry-contract').val();
        if (industryContact != '') {
            $(".address_holder").show();
        } else {
            $(".address_holder").show();
            $(".city_holder").hide();
            $(".county_holder").hide();
            $(".companyWork_holder").hide();
        }
    });
    $('.addressText').on('focus', function () {
        /*$('html, body').animate({
            scrollTop: $(".city_holder").offset().top
        }, 2000);*/
        var address = $('.addressText').val();
        if (address != '') {
            $(".city_holder").show();
        } else {
            $(".city_holder").show();
            $(".county_holder").hide();
            $(".companyWork_holder").hide();
        }

        var target = $(".city_holder");
        $('html, body').animate({'scrollTop': target.offset().top}, 2400, 'swing');
    });
    $('.cityText').on('focus', function () {
        /*$('html, body').animate({
            scrollTop: $(".county_holder").offset().top
        }, 2000);*/
        var city = $('.cityText').val();
        if (city != '') {
            $(".county_holder").show();
        } else {
            $(".county_holder").show();
            $(".companyWork_holder").hide();
        }
        var target = $(".county_holder");
        $('html, body').animate({'scrollTop': target.offset().top}, 2400, 'swing');
    });
    $('.countyText').on('focus', function () {
        /*$('html, body').animate({
            scrollTop: $(".zip_holder").offset().top
        }, 2000);*/
        var county = $('.countyText').val();
        if (county != '') {
            $(".zip_holder").show();
        } else {
            $(".zip_holder").show();
            $(".companyWork_holder").hide();
        }
        var target = $(".zip_holder");
        $('html, body').animate({'scrollTop': target.offset().top}, 2400, 'swing');
    });

    $('.zipText').on('focus', function () {
        /*$('html, body').animate({
            scrollTop: $(".companyWork_holder").offset().top
        }, 2000);*/
        var zip = $('.zipText').val();
        if (zip != '') {
            $(".companyWork_holder").show();
        } else {
            $(".companyWork_holder").show();
        }
        var target = $(".companyWork_holder");
        $('html, body').animate({'scrollTop': target.offset().top}, 2400, 'swing');
    });
    $('#customer').on('change', function () {
        /*$('html, body').animate({
            scrollTop: $(".section-customer-contact_holder").offset().top
        }, 2000);*/
        var customer = $('#customer').val();
        if (customer != '') {
            $(".section-customer-contact_holder").show();
        } else {
            $(".section-customer-contact_holder").hide();
            $(".section-industry-contact_holder").hide();
            $(".address_holder").hide();
            $(".city_holder").hide();
            $(".county_holder").hide();
            $(".companyWork_holder").hide();
        }
    });
    // if(location.search.indexOf('view=')>=0){
    //     var c = getParameterByName("view",window.location.href);
    //     if(c == 'detailed'){
    //         $('#section-customer-contact').show();
    //     } else {
    //         $('#section-customer-contact').hide();
    //     }
    // }
    $('.nav-tabs a').on('click', function () {
        $('.tab-view').attr('data-type', $(this).data('type'));
    });
//            $('.todo').on('click', function(){
//                alert('fgdf');
//              //  $('.todo').siblings().removeClass('header-progress-item done');
//                $('.todo').addClass('header-progress-item done');
//            });
    $('.todo').addClass('header-progress-item done');
    $('#demo').on('shown.bs.collapse', function () {
        $('#nameButton').html('Express');
    });

    $('#demo').on('hidden.bs.collapse', function () {
        $('#nameButton').html('Detailed');
    });


    $('.error').on('focus', function () {
        $(this).removeClass('input-error');
        $('#error-message').html('');
    });
    $('#activate-step-2, #activate-step-2-out').on('click', function (e) {
        // will go to job description
        var buttonName = $(this);
        var parent_fieldset = $(this).parent().parent().parent().parent().parent().parent().parent('form');
        var next_step = true;

        parent_fieldset.find('.input-field').each(function () {
            if ($(this).val() == "") {
                $(this).addClass('input-error');
                next_step = false;
            } else {
                $(this).removeClass('input-error');
            }
        });
        if (next_step) {
            let savePref = 'saveSettings'
            if($('#preferences').is(':checked')){
                savePref = savePref
            }
            else{
                savePref = null
            }
            var data = $(".project_details").serialize();// serializes the form's elements.
            data += '&preferences='+ savePref
            var tabView = $('.tab-view').data('type');
            // console.log(data)
            $.ajax({
                type: "POST",
                url: projectSubmit,
                data: data,
                success: function (data) {
                if (data.status) {
                    //var url = '';
                   /* if (view != '') {
                        url = lienUrl+'?project_id=' + data.project_id + '&view='+view;
                    } else if (view != '' && view != 'detailed') {
                        url = lienUrl+'?project_id=' + data.project_id + '&view='+view;
                    } else {
                        url = contractUrl+'?project_id=' + data.project_id + '&view='+view;
                    }*/

                    if (view != '' && view == 'detailed') {
                        url = contractUrl+'?project_id=' + data.project_id + '&view='+view;
                        //url = lienUrl+'?project_id=' + data.project_id + '&view='+view;
                    } else if (view === '' && edit === false || view != 'detailed' && edit ===false) {
                        //url = url + '/member/create/project/remedydates/' + data.project_id;
                        //url = url + '/member/project/dashboard?project_id=' + data.project_id + '&edit=true';
                        // added on aug-13-2019
                        // url = url + '/member/create/project?project_id=' + data.project_id + '&edit=true';
                        // added on aug-19-2019
                        url = url + '/member/create/project/remedydates/' + data.project_id + '?edit=true';

                    } else if(edit === true) {
                        // 16 aug
                        url = url + '/member/create/edit/jobdescription/' + data.project_id + '?edit=true';
                        // url = url + '/member/create/project/remedydates/' + data.project_id + '?edit=true';
                    }
                    else {
                        url = lienUrl+'?project_id=' + data.project_id + '&view='+view;
                        //url = contractUrl+'?project_id=' + data.project_id + '&view='+view;
                    }

                    // now we want to load next page - 16 aug
                    //if($('#editFlag').length > 0){
                    //    window.location.reload()
                    //} else {
                        window.location.href = url;
                    //}

                } else {
                    swal(
                        'Error',
                        data.message,
                        'error'
                    );
                }

            }
        });
        }
    });

    $('#activate-step-2b, #activate-step-2b-out').on('click', function (e) {
        // will go to deadlines
        var buttonName = $(this);
        var parent_fieldset = $(this).parent().parent().parent().parent().parent().parent().parent('form');
        var next_step = true;

        parent_fieldset.find('.input-field').each(function () {
            if ($(this).val() == "") {
                $(this).addClass('input-error');
                next_step = false;
            } else {
                $(this).removeClass('input-error');
            }
        });
        if (next_step) {
            let savePref = 'saveSettings'
            if($('#preferences').is(':checked')){
                savePref = savePref
            }
            else{
                savePref = null
            }
            var data = $(".project_details").serialize();// serializes the form's elements.
            data += '&preferences='+ savePref
            var tabView = $('.tab-view').data('type');
            // console.log(data)
            $.ajax({
                type: "POST",
                url: projectSubmit,
                data: data,
                success: function (data) {
                    if (data.status) {
                        //var url = '';
                        /* if (view != '') {
                             url = lienUrl+'?project_id=' + data.project_id + '&view='+view;
                         } else if (view != '' && view != 'detailed') {
                             url = lienUrl+'?project_id=' + data.project_id + '&view='+view;
                         } else {
                             url = contractUrl+'?project_id=' + data.project_id + '&view='+view;
                         }*/

                        if (view != '' && view == 'detailed') {
                            url = contractUrl+'?project_id=' + data.project_id + '&view='+view;
                            //url = lienUrl+'?project_id=' + data.project_id + '&view='+view;
                        } else if (view === '' && edit === false || view != 'detailed' && edit ===false) {
                            //url = url + '/member/create/project/remedydates/' + data.project_id;
                            //url = url + '/member/project/dashboard?project_id=' + data.project_id + '&edit=true';
                            // added on aug-13-2019
                            url = url + '/member/create/project?project_id=' + data.project_id + '&edit=true';

                        } else if(edit === true) {
                            // 16 aug
                            // url = url + '/member/create/edit/jobdescription/' + data.project_id + '?edit=true';
                            // url = url + '/member/create/project/remedydates/' + data.project_id + '?edit=true';
                            url = url + '/member/create/project/deadlines/' + data.project_id + '?edit=true';
                        }
                        else {
                            url = lienUrl+'?project_id=' + data.project_id + '&view='+view;
                            //url = contractUrl+'?project_id=' + data.project_id + '&view='+view;
                        }

                        // now we want to load next page - 16 aug
                        //if($('#editFlag').length > 0){
                        //    window.location.reload()
                        //} else {
                        window.location.href = url;
                        //}

                    } else {
                        swal(
                            'Error',
                            data.message,
                            'error'
                        );
                    }

                }
            });
        }
    });
    
    $('#activate-step-8, #activate-step-8-out').on('click', function (e) {
        // will go to job summary
        var buttonName = $(this);
        var parent_fieldset = $(this).parent().parent().parent().parent().parent().parent().parent('form');
        var next_step = true;

        parent_fieldset.find('.input-field').each(function () {
            if ($(this).val() == "") {
                $(this).addClass('input-error');
                next_step = false;
            } else {
                $(this).removeClass('input-error');
            }
        });
        if (next_step) {
            let savePref = 'saveSettings'
            if($('#preferences').is(':checked')){
                savePref = savePref
            }
            else{
                savePref = null
            }
            var data = $(".project_details").serialize();// serializes the form's elements.
            data += '&preferences='+ savePref
            var tabView = $('.tab-view').data('type');
            // console.log(data)
            $.ajax({
                type: "POST",
                url: projectSubmit,
                data: data,
                success: function (data) {
                if (data.status) {
                    //var url = '';
                   /* if (view != '') {
                        url = lienUrl+'?project_id=' + data.project_id + '&view='+view;
                    } else if (view != '' && view != 'detailed') {
                        url = lienUrl+'?project_id=' + data.project_id + '&view='+view;
                    } else {
                        url = contractUrl+'?project_id=' + data.project_id + '&view='+view;
                    }*/

                    if (view != '' && view == 'detailed') {
                        url = contractUrl+'?project_id=' + data.project_id + '&view='+view;
                        //url = lienUrl+'?project_id=' + data.project_id + '&view='+view;
                    } else if (view === '' && edit === false || view != 'detailed' && edit ===false) {
                        //url = url + '/member/create/project/remedydates/' + data.project_id;
                        //url = url + '/member/project/dashboard?project_id=' + data.project_id + '&edit=true';
                        // added on aug-13-2019
                        // url = url + '/member/create/project?project_id=' + data.project_id + '&edit=true';
                        // added on aug-19-2019
                        url = url + '/member/project/summary/view/' + data.project_id + '?edit=true';

                    } else if(edit === true) {
                        // 16 aug
                        url = url + '/member/create/edit/jobdescription/' + data.project_id + '?edit=true';
                        // url = url + '/member/create/project/remedydates/' + data.project_id + '?edit=true';
                    }
                    else {
                        url = lienUrl+'?project_id=' + data.project_id + '&view='+view;
                        //url = contractUrl+'?project_id=' + data.project_id + '&view='+view;
                    }

                    // now we want to load next page - 16 aug
                    //if($('#editFlag').length > 0){
                    //    window.location.reload()
                    //} else {
                        window.location.href = url;
                    //}

                } else {
                    swal(
                        'Error',
                        data.message,
                        'error'
                    );
                }

            }
        });
        }
    });

    $('#save_quit').on('click', function (e) {
        var buttonName = $(this);
        var parent_fieldset = $(this).parent().parent().parent().parent().parent().parent().parent('form');
        var next_step = true;

        parent_fieldset.find('.input-field').each(function () {
            if ($(this).val() == "") {
                $(this).addClass('input-error');
                next_step = false;
            } else {
                $(this).removeClass('input-error');
            }
        });

        if (next_step) {
            var data = $(".project_details").serialize();// serializes the form's elements.
            $.ajax({
                type: "POST",
                url: projectSubmit,
                data: data,
                success: function (data) {
                if (data.status) {
                    var url = memberProject;
                    window.location.href = url;
                } else {
                    swal(
                        'Error',
                        data.message,
                        'error'
                    );
                }

            }
        });
        }
    });
    $('.checkTier').on('change', function () {

        $('#question').html('');
        var role = $('#role').val();
        if (role == '') {
            return false;
        }
        var state = $('#state').val();
        if (state == '') {
            return false;
        }
        var project_type = $('#project_type').val();
        if (project_type == '') {
            return false;
        }
        $.ajax({
            type: "POST",
            url: checkRole,
            data: {
                role: role,
                state: state,
                project_type: project_type,
                _token: token
            },
            success: function (data) {
                if (data.status) {
                    var html = '<option value="">Select a Select your customer</option>';
                    $.each(data.data, function (index, value) {
                        html += '<option value="' + value.customer.id + '">' + value.customer.name + '</option>';
                    });
                    $('#customer').empty().append(html);
                } else {
                    var html = '<option value="">Select a Select your customer</option>';
                    $('#customer').empty().append(html);
                    swal(
                        'ALERT',
                        data.message,
                        'error'
                    )
                }
            }
        });
    });
    // $('.checkTier').on('change', function () {
    //     console.log('hello');
    //     $('#question').html('');
    //     var role = $('#role').val();
    //     if (role == '') {
    //         return false;
    //     }
    //     var state = $('#state').val();
    //     if (state == '') {
    //         return false;
    //     }
    //     var project_type = $('#project_type').val();
    //     if (project_type == '') {
    //         return false;
    //     }
    //     $.ajax({
    //         type: "POST",
    //         url: "{{ route('project.role.check') }}",
    //         data: {
    //             role: role,
    //             state: state,
    //             project_type: project_type,
    //             _token: "{{ csrf_token() }}"
    //         },
    //         success: function (data) {
    //             if (data.status) {
    //                 var html = '<option value="">Select a Select your customer</option>';
    //                 $.each(data.data, function (index, value) {
    //                     html += '<option value="' + value.id + '">' + value.customer.name + '</option>';
    //                 });
    //                 $('#customer').empty().append(html);
    //             } else {
    //                 var html = '<option value="">Select a Select your customer</option>';
    //                 $('#customer').empty().append(html);
    //                 swal(
    //                     'Error',
    //                     data.message,
    //                     'error'
    //                 )
    //             }
    //         }
    //     });
    // });


    $('#customer').on('change', function () {
        $('#question').html('');
        var role = $('#role').val();
        if (role == '') {
            return false;
        }
        var customer = $('#customer').val();
        if (customer == '') {
            return false;
        }
        var state = $('#state').val();
        if (state == '') {
            return false;
        }
        var project_type = $('#project_type').val();
        if (project_type == '') {
            return false;
        }

        $.ajax({
            type: "POST",
            url: checkQuestion,
            data: {
                role: role,
                customer: customer,
                state: state,
                project_type: project_type,
                _token: token
            },
            success: function (data) {
                if (data.status) {
                    var html = '';
                    $.each(data.data, function (indexMain, question) {
                        html += '<div class="row">' +
                            '<label class="push-30">' + question.question + '</label>' +
                            '<div class="col-md-12">';
                        $.each(question.answer, function (index, value) {
                            if (index == 0) {
                                html += '<label class="radio-label"><input type="radio" class="error" name="answer[' + question.id + ']" value="' + value + '" checked>' + value + '</label>';
                            } else {
                                html += '<label class="radio-label"><input type="radio" class="error" name="answer[' + question.id + ']" value="' + value + '">' + value + '</label>';
                            }
                        });
                        html += '</div></div>'
                    });
                    $('#question').html(html);
                    $('.question').show();
                } else {
                    $('#question').html('');
                    $('.question').hide();
                }
            }
        });
    });
            if (condition == '1') {
                $('#question').html('');
                var role = $('#role').val();
                if (role == '') {
                    return false;
                }
                var customer = $('#customer').val();
                if (customer == '') {
                    return false;
                }
                var state = $('#state').val();
                if (state == '') {
                    return false;
                }
                var project_type = $('#project_type').val();
                if (project_type == '') {
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: checkQuestion,
                    data: {
                        role: role,
                        customer: customer,
                        state: state,
                        project_type: project_type,
                        _token: token
                    },
                    success: function (data) {
                        if (data.status) {
                            var html = '';
                            $.each(data.data, function (indexMain, question) {
                                html += '<div class="row">' +
                                    '<label class="control-label col-xs-6">' + question.question + '</label>' +
                                    '<div class="col-xs-6">';
                                $.each(question.answer, function (index, value) {
                                    if (indexMain == 0) {
                                        if (project_id > 0) {
                                            if (value === answer1) {
                                                html += '<label class="radio-inline"><input type="radio" class="error" name="answer[' + question.id + ']" value="' + value + '" checked>' + value + '</label>';
                                            } else {
                                                html += '<label class="radio-inline"><input type="radio" class="error" name="answer[' + question.id + ']" value="' + value + '">' + value + '</label>';
                                            }
                                        } else {
                                            if (index === 0) {
                                                html += '<label class="radio-inline"><input type="radio" class="error" name="answer[' + question.id + ']" value="' + value + '" checked>' + value + '</label>';
                                            } else {
                                                html += '<label class="radio-inline"><input type="radio" class="error" name="answer[' + question.id + ']" value="' + value + '">' + value + '</label>';
                                            }
                                        }
                                    } else {
                                        if (project_id > 0) {
                                            if (value === answer2) {
                                                html += '<label class="radio-inline"><input type="radio" class="error" name="answer[' + question.id + ']" value="' + value + '" checked>' + value + '</label>';
                                            } else {
                                                html += '<label class="radio-inline"><input type="radio" class="error" name="answer[' + question.id + ']" value="' + value + '">' + value + '</label>';
                                            }
                                        } else {
                                            if (index === 0) {
                                                html += '<label class="radio-inline"><input type="radio" class="error" name="answer[' + question.id + ']" value="' + value + '" checked>' + value + '</label>';
                                            } else {
                                                html += '<label class="radio-inline"><input type="radio" class="error" name="answer[' + question.id + ']" value="' + value + '">' + value + '</label>';
                                            }
                                        }
                                    }
                                });
                                html += '</div></div>'
                            });
                            $('#question').html(html);
                            $('.question').show();
                        } else {
                            $('#question').html('');
                            $('.question').hide();
                        }
                    }
                });

            }




    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    $("#state").on('change', function () {
        var currentstate = $(this).val();
        var pname = $("#p_name").val();

        if (currentstate == '') {
            $(".project_type_holder").hide();
            $(".role_holder").hide();
            $(".customer_holder").hide();
            $("#p_name").val('');
        } else {
            $(".project_type_holder").show();
            $('html, body').animate({
                scrollTop: $("#project_type").offset().top
            }, 2000);
        }
    });

    $("#project_type").on('change', function () {
        var currentprojecttype = $(this).val();
        var pname = $("#p_name").val();
        if (currentprojecttype == '') {
            // console.log('here');
            $(".role_holder").hide();
            $(".customer_holder").hide();
            $("#p_name").val('');
        } else {
            $(".role_holder").show();
            $('html, body').animate({
                scrollTop: $("#role").offset().top
            }, 2000);
            //if (pname == '') {
            //    console.log('there1');
                checkProjectName(currentprojecttype);
            //}

        }
    });

    $("#role").on('change', function () {
        var currentrole = $(this).val();

        if (currentrole == '') {
            $(".customer_holder").hide();
        } else {
            $(".customer_holder").show();
            $('html, body').animate({
                scrollTop: $("#customer").offset().top
            }, 2000);
        }
    });


    // skip buttons
    // 13-aug-2019

    $('.skip-job-dates').on('click', function (event) {

        //event.stopPropagation();
        event.preventDefault();
        // console.log('1');
        // 16 aug
        //window.location.href = '/member/create/project/remedydates/' + project_id + '?edit=true';
        window.location.href = '/member/create/edit/jobdescription/' + project_id + '?edit=true';

    });






    function checkProjectName(currentprojecttype) {

        if (currentprojecttype != '') {
            var statedata = $("#state :selected").text();
            var ptdata = $("#project_type :selected").text();
            var projectname = statedata + "-" + ptdata + "-Initial Deadlines";

            $.ajax({
                type: "POST",
                url: checkName,
                data: {
                    projectname: projectname,
                    _token: token
                },
                success: function (data) {
                    if (ptdata != "Select a project type" && $('#p_name').val().length == 0) {

                        $("#p_name").val(data);
                    }
                }
            });

        } else {
            $("#p_name").val('');
        }
    }
    if (project_id > 0) {
        $(".project_type_holder").show();
        $(".role_holder").show();
        $(".customer_holder").show();
        $(".section-customer-contact_holder").show();
        $(".section-industry-contact_holder").show();
        $(".address_holder").show();
        $(".city_holder").show();
        $(".county_holder").show();
        $(".companyWork_holder").show();
        $(".zip_holder").show();
    }

    function checkQuery(sParam) {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++) {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam) {
                return true;
            }
        }
    }
});
