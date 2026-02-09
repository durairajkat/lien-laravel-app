function validateEmail(mail) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
        return true;
    }
    $('#error-message').html("<p class='input-error'>You have entered an invalid email address!</p>");
    return false;
}

function validateNumber(number, type) {
    if (/^-?\d*\.?\d*$/.test(number)) {
        return true;
    }
    $('#error-message').html("<p class='input-error'>You have entered an invalid " + type + " !</p>");
    return false;
}


$(document).ready(function () {
    let mobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (mobile.Android() || mobile.BlackBerry() || mobile.iOS() || mobile.Opera() || mobile.Windows());
        }
    };
    $('#activate-step-5').on('click',function () {
        if(mobile.any() || !mobile.any()){
            window.location.href = jobInfo;
        }
        else{
            window.location.href = nextUrl;
        }
    });

    $(".header").click(function () {

        $header = $(this);
        //getting the next element
        $content = $header.next();
        //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
        $content.slideToggle(500, function () {
            //execute this after slideToggle is done
            //change text of header based on visibility of content div
            $header.text(function () {
                //change text based on condition
                return $content.is(":visible") ? "Collapse" : "Expand";
            });
        });

    });

    //expand/collapse all
    $('.expandall-icon').click(function () {
        $(this).addClass('expand-inactive');
        $('.expandall-icon2').addClass('expand-inactive');
        $('.collapseall-icon').removeClass('collapse-inactive');
        $('.collapseall-icon2').removeClass('collapse-inactive');
        $('.collapse').addClass('in');
        $('.collapse').css('height','auto');
    });
    $('.collapseall-icon').click(function () {
        $(this).addClass('collapse-inactive');
        $('.collapseall-icon2').addClass('collapse-inactive');
        $('.expandall-icon').removeClass('expand-inactive');
        $('.expandall-icon2').removeClass('expand-inactive');
        $('.collapse').removeClass('in');
        $('.collapse').css('height','0px');
    }).addClass('collapse-inactive');
    $('.expandall-icon2').click(function () {
        $(this).addClass('expand-inactive');
        $('.expandall-icon').addClass('expand-inactive');
        $('.collapseall-icon2').removeClass('collapse-inactive');
        $('.collapseall-icon').removeClass('collapse-inactive');
        $('.collapse').addClass('in');
        $('.collapse').css('height','auto');
    });
    $('.collapseall-icon2').click(function () {
        $(this).addClass('collapse-inactive');
        $('.collapseall-icon').addClass('collapse-inactive');
        $('.expandall-icon2').removeClass('expand-inactive');
        $('.expandall-icon').removeClass('expand-inactive');
        $('.collapse').removeClass('in');
        $('.collapse').css('height','0px');
    }).addClass('collapse-inactive');

    $('.show_more').click(function () {
        var id = $(this).data('id');
        $(this).addClass('show-inactive');
        $('#customerLess'+id).removeClass('show-inactive');
        $('.customer'+id).show();
    });

    $('.show_less').click(function () {
        var id = $(this).data('id');
        $(this).addClass('show-inactive');
        $('#customerMore'+id).removeClass('show-inactive');
        $('.customer'+id).hide();
    }).addClass('show-inactive');


    /** On click Add more lien providers button*/
    $('.add_button').click(function () {
        if($('.contact_row').length < 10) {
            var clonedEl = $('.contact_row:first').clone();
            clonedEl.appendTo($('.contact_table_body'));
            clonedEl.find("input").not('.customer_id').val('');
            clonedEl.find("input.customer_id").val('0');
            clonedEl.find("select").val('CEO');
            clonedEl.find('.add_button').addClass('remove_button').attr('title','Remove field').removeClass('add_button');
            clonedEl.find('img').attr('src', url+'/images/remove-icon.png');
            renumber_blocks();
        }
    });

    /** On click delete lien providers button*/
    $(document).delegate('.remove_button','click',function (event) {
        if($('.contact_row').length > 1) {
            $('.contact_row:last').remove();
            renumber_blocks();
        }
    });

    /**Arrange the index for lien providers.*/
    function renumber_blocks() {
        $(".contact_row").each(function(index) {
            var prefix = "contacts[" + index + "]";
            $(this).find("input").each(function() {
                this.name = this.name.replace(/contacts\[\d+\]/, prefix);
            });

            $(this).find("select").each(function() {
                this.name = this.name.replace(/contacts\[\d+\]/, prefix);
            });
        });
    }


    /**Modal trigger function*/
    $('.editContact').on('click', function () {
        $('#TypeForm')[0].reset();
        $('#company').removeAttr('data-company_name');
        $('.contact_row').not(':first').remove();
        var contact = $(this).data('contact');
        $('#dataType').val(contact);
        var contactType = $(this).data('contacttype');
        if (contactType == 'Owner' || contactType == 'General Contractor') {
            $('#contactType').val(contactType);
        }
        if (contact == 'customer') {
            $('.contractType').hide();
            $('#contactType').attr('required',false);
            $('.autocomplete').attr('data-type', 'customer');
        } else {
            $('.contactType').show();
            $('#contactType').attr('required',true);
            $('.autocomplete').attr('data-type', 'industry');
        }
        var contact_id = $(this).data('customer_id');
        var company_id = $(this).data('company_id');
        var map_id = $(this).data('map_id')
        var type = 'add';
        console.log(map_id);
        $.ajax({
            url: getContactData,
            dataType: "json",
            type: 'post',
            data: {
                contact_id: contact_id,
                company_id: company_id,
                map_id: map_id,
                _token: token
            },
            success: function (data) {
                if (data.success == true) {
                    type = 'edit';
                    $('#company').val(company_id).trigger('chosen:updated');
                  //  $('#company_id').val(data.company.id);
                    $('#website').val(data.company.website);
                    $('#addressType').val(data.maping !== null ? data.maping.address : (data.company.address !== null ? data.company.address : ''));
                    $('#cityType').val(data.maping !== null ? data.maping.city : (data.company.city !== null ? data.company.city : ''));
                    $('#stateModal').val(data.maping !== null ? data.maping.state_id : (data.company.state_id !== null ? data.company.state_id : ''));
                    $('#zipType').val(data.maping !== null ? data.maping.zip : (data.company.zip !== null ? data.company.zip : ''));
                    $('#phone').val(data.maping !== null ? data.maping.phone: (data.company.phone !== null ? data.company.phone : ''));
                    $('#fax').val(data.maping !== null ? data.maping.fax : (data.company.fax !== null ? data.company.fax : ''));
                    var html = '';
                    if (data.customers.length > 0) {
                        $.each(data.customers, function (index, value) {
                            if (index == 0) {
                                if (value.title == 'Other') {
                                    $('.contact_row:first').find('.title').hide();
                                    $('.contact_row:first').find('.title_other').show();
                                    /*$('#title').hide();
                                    $('#title_other').show();*/
                                }
                                $('.contact_row:first').find('.title').val(value.title);
                                $('.contact_row:first').find('.title_other').val(value.title_other);
                                $('.contact_row:first').find('.first_name').val(value.first_name);
                                $('.contact_row:first').find('.last_name').val(value.last_name);
                                $('.contact_row:first').find('.email').val(value.email);
                                $('.contact_row:first').find('.phone').val(value.phone);
                                $('.contact_row:first').find('.cell').val(value.cell);
                                $('.contact_row:first').find('.customer_id').val(value.id);
                                /*$('#customer_id').val(value.id);
                                $('#title_other').val(value.title_other);
                                $('#firstName').val(value.first_name);
                                $('#lastName').val(value.last_name);
                                $('#email').val(value.email);
                                $('#directPhone').val(value.phone);
                                $('#cellPhone').val(value.cell);*/
                            } else {
                                var title_other = value.title_other != null ? value.title_other : '';
                                var firstName = value.first_name != null ? value.first_name : '';
                                var lastName = value.last_name != null ? value.last_name : '';
                                var email = value.email != null ? value.email : '';
                                var direct_phone = value.phone != null ? value.phone : '';
                                var cell = value.cell != null ? value.cell : '';
                                if (value.title != '') {
                                    if (value.title != 'Other') {
                                        html += '<tr class="contact_row"> <td> <select class="form-control error title" name="contacts['+index+'][title]">' +
                                                ' <option value="CEO" ' + (value.title === "CEO" ? "selected" : "") + '>CEO</option> ' +
                                                '<option value="CFO" ' + (value.title === "CFO" ? "selected" : "") + '>CFO</option> ' +
                                                '<option value="Credit" ' + (value.title === "Credit" ? "selected" : "") + '>Credit</option>' +
                                                ' <option value="PM" ' + (value.title === "PM" ? "selected" : "") + '>PM</option> ' +
                                                '<option value="Corporation Counsel" ' + (value.title === "Corporation Counsel" ? "selected" : "") + '>Corporation Counsel</option> ' +
                                                '<option value="A/R Manager" ' + (value.title === "A/R Manager" ? "selected" : "") + '>A/R Manager</option> ' +
                                                '<option value="Other">Other</option> </select>' +
                                                '<div class="title_other" style="display: none;"> ' +
                                                '<input type="text" name="contacts['+index+'][titleOther]"class="form-control error" value="' + title_other + '">' +
                                                '<a href="#" class="titleOtherBtn">Change</a> </div> </td>';


                                        /*html += '<tr class="contact_row"><td> <select class="form-control error title" name="contacts[]">' +
                                            ' <option value="CEO" ' + (value.title === "CEO" ? "selected" : "") + '>CEO</option> ' +
                                            '<option value="CFO" ' + (value.title === "CFO" ? "selected" : "") + '>CFO</option> ' +
                                            '<option value="Credit" ' + (value.title === "Credit" ? "selected" : "") + '>Credit</option>' +
                                            ' <option value="PM" ' + (value.title === "PM" ? "selected" : "") + '>PM</option> ' +
                                            '<option value="Corporation Counsel" ' + (value.title === "Corporation Counsel" ? "selected" : "") + '>Corporation Counsel</option> ' +
                                            '<option value="A/R Manager" ' + (value.title === "A/R Manager" ? "selected" : "") + '>A/R Manager</option> ' +
                                            '<option value="Other">Other</option> </select>' +
                                            '<div class="title_other" style="display: none;"> ' +
                                            '<input type="text" name="titleOther[]" class="form-control error" value="' + title_other + '">' +
                                            ' <a href="#" class="titleOtherBtn">Change</a> </div> </td>';*/
                                    } else {
                                        html += '<tr class="contact_row"> <td> <select class="form-control error title" name="contacts['+index+'][title]">' +
                                                ' <option value="CEO" >CEO</option> ' +
                                                '<option value="CFO" >CFO</option> ' +
                                                '<option value="Credit" >Credit</option>' +
                                                ' <option value="PM" >PM</option> ' +
                                                '<option value="Corporation Counsel" >Corporation Counsel</option> ' +
                                                '<option value="A/R Manager" >A/R Manager</option> ' +
                                                '<option value="Other">Other</option> </select>' +
                                                '<div class="title_other" style="display: none;"> ' +
                                                '<input type="text" name="contacts['+index+'][titleOther]"class="form-control error" value="' + title_other + '">' +
                                                '<a href="#" class="titleOtherBtn">Change</a> </div> </td>';


                                        /*html += '<tr class="external"><td> <select class="form-control error title1" name="title[]"  style="display: none;">' +
                                            ' <option value="CEO">CEO</option> ' +
                                            '<option value="CFO" >CFO</option> ' +
                                            '<option value="Credit" >Credit</option> <option value="PM">PM</option> ' +
                                            '<option value="Corporation Counsel" >Corporation Counsel</option> ' +
                                            '<option value="A/R Manager">A/R Manager</option> ' +
                                            '<option value="Other">Other</option> </select>' +
                                            '<div class="title_other"> ' +
                                            '<input type="text" name="titleOther[]" class="form-control error" value="' + title_other + '">' +
                                            ' <a href="#" class="titleOtherBtn">Change</a> </div> </td>';*/
                                    }
                                } else {
                                    html += '<tr class="contact_row"> <td> <select class="form-control error title" name="contacts['+index+'][title]">' +
                                        ' <option value="CEO" >CEO</option> ' +
                                        '<option value="CFO" >CFO</option> ' +
                                        '<option value="Credit" >Credit</option>' +
                                        ' <option value="PM" >PM</option> ' +
                                        '<option value="Corporation Counsel" >Corporation Counsel</option> ' +
                                        '<option value="A/R Manager" >A/R Manager</option> ' +
                                        '<option value="Other">Other</option> </select>' +
                                        '<div class="title_other" style="display: none;"> ' +
                                        '<input type="text" name="contacts['+index+'][titleOther]"class="form-control error" value="' + value.title_other + '">' +
                                        '<a href="#" class="titleOtherBtn">Change</a> </div> </td>';

                                   /* html += '<tr class="external"><td> <select class="form-control error title1" name="title[]" >' +
                                        ' <option value="CEO">CEO</option> ' +
                                        '<option value="CFO">CFO</option> ' +
                                        '<option value="Credit">Credit</option> <option value="PM">PM</option> ' +
                                        '<option value="Corporation Counsel">Corporation Counsel</option> ' +
                                        '<option value="A/R Manager">A/R Manager</option> ' +
                                        '<option value="Other">Other</option> </select>' +
                                        '<div class="title_other" style="display: none;"> ' +
                                        '<input type="text" name="titleOther[]" class="form-control error" value="' + value.title_other + '">' +
                                        ' <a href="#" class="titleOtherBtn">Change</a> </div> </td>';*/
                                }

                                html += '<td><input class="form-control error contacts first_name autocomplete_contact_first_name" type="text" name="contacts['+index+'][firstName]" autocomplete="off" value="' + firstName + '" data-field="first_name" placeholder="First Name" required/>' +
                                        '<input type="hidden" data-field="customer_id" class="customer_id" name="contacts['+index+'][customerId]" value="' + value.id + '" /> </td>'+
                                        '<td><input class="form-control error contacts last_name" type="text" value="' + lastName + '" name="contacts['+index+'][lastName]" data-field="last_name" placeholder="Last Name"/></td>'+
                                        '<td><input class="form-control error contacts email" type="email" name="contacts['+index+'][email]" data-field="email"  value="' + email + '" placeholder="Email" required/></td>'+
                                        '<td><input class="form-control error contacts phone" type="number" name="contacts['+index+'][directPhone]" data-field="phone" value="' + direct_phone + '"  placeholder="Direct Phone"/></td>'+
                                        '<td><input class="form-control error cell" type="number" name="contacts['+index+'][cellPhone]" value="' + cell + '"  data-field="cell" placeholder="Cell Phone"/></td>'+
                                        '<td><a href="javascript:void(0);" class="add_button" title="Remove field">'+
                                        '<img src="' + baseUrl + '/images/remove-icon.png" height="30px" width="30px"/></a></td></tr>';

                              /*  html += '<td><input class="form-control error autocomplete_contact_first_name" autocomplete="off" type="text" name="firstName[]" value="' + firstName + '" placeholder="First Name"/></td>' +
                                    '<td><input class="form-control error" type="text" name="lastName[]" value="' + lastName + '" placeholder="Last Name"/>' +
                                    '<input class="form-control error" type="hidden" name="customer_id[]" value="' + value.id + '" placeholder="Last Name"/></td>' +
                                    '<td><input class="form-control error" type="email" name="email[]" value="' + email + '" placeholder="Email"/></td>' +
                                    '<td><input class="form-control error" type="text" name="directPhone[]" value="' + direct_phone + '" placeholder="Direct Phone"/></td>' +
                                    '<td><input class="form-control error" type="text" name="cellPhone[]" value="' + cell + '" placeholder="Cell Phone"/></td>' +
                                    '<td><a href="javascript:void(0);" class="remove_button1" title="Remove field">' +
                                    '<img src="' + baseUrl + '/images/remove-icon.png" height="30px" width="30px"/></a></td></tr>';*/
                            }
                        });
                    }
                    $('.contact_row:last').after(html);
                    $('#contactType').removeAttr('disabled');
                    $('#contactType').val(data.customers[0].contact_type);
                } else {
                    $('#customer_id').val(0);
                }
            }, error: function(data, errorThrown) {
                $('.external').remove();
                $('#customer_id').val(0);
            }
        });
        $('#addCustomerModel').modal('show');
    });

    $('#TypeForm').on('submit', function (e) {
        e.preventDefault();
        var flag = true;
        var type = $('#dataType').val();//customer or industry
        //Company
        var company = $('#company').val();
        var company_name = $('#company').data('company_name');
        if (company == '') {
            $('#company').addClass('input-error');
            flag = false;
        }
        //Contact Type for industry
        var contactType = $('#contactType').val();
        if (type == 'industry') {
            if (contactType == '') {
                $('#contactType').addClass('input-error');
                flag = false;
            }
        }
        var website = $('#website').val();
        var address = $('#addressType').val();
        var city = $('#cityType').val();
        //State
        var state = $('#stateModal').val();
        if (state == '') {
            $('#stateModal').addClass('input-error');
            flag = false;
        }
        //Zip
        var zip = $('#zipType').val();
        if (zip != '' && !validateNumber(zip, 'zip')) {
            $('#zipType').addClass('input-error');
            flag = false;
        }
        var phone = $('#phone').val();
        if (phone != '' && !validateNumber(phone, 'phone')) {
            $('#phone').addClass('input-error');
            flag = false;
        }
        var fax = $('#fax').val();
        if (fax != '' && !validateNumber(fax, 'fax')) {
            $('#fax').addClass('input-error');
            flag = false;
        }
        var company_id = $('#company_id').val();

        var contacts = mapData();

        //Customer Information
        /*var firstName = $("input[name='firstName[]']")
            .map(function () {
                return $(this).val();
            }).get();

        var lastName = $("input[name='lastName[]']")
            .map(function () {
                return $(this).val();
            }).get();

        var email = $("input[name='email[]']")
            .map(function () {
                return $(this).val();
            }).get();

        var directPhone = $("input[name='directPhone[]']")
            .map(function () {
                return $(this).val();
            }).get();

        var cellPhone = $("input[name='cellPhone[]']")
            .map(function () {
                return $(this).val();
            }).get();

        var title = $("select[name='title[]']")
            .map(function () {
                return $(this).val();
            }).get();

        var titleOther = $("input[name='titleOther[]']")
            .map(function () {
                return $(this).val();
            }).get();

        var customer_id = $("input[name='customer_id[]']")
            .map(function () {
                return $(this).val();
            }).get();*/

        if (flag) {
            $.ajax({
                type: "POST",
                url: newContacts,
                data: {
                    contact: type,
                    contactType: contactType,
                    company: company,
                    company_name: company_name,
                    website: website,
                    address: address,
                    city: city,
                    state: state,
                    zip: zip,
                    phone: phone,
                    fax: fax,
                 /*   company_id: company_id,
                    title: title,
                    titleOther: titleOther,
                    firstName: firstName,
                    customer_id: customer_id,
                    lastName: lastName,
                    email: email,
                    directPhone: directPhone,
                    cell: cellPhone,*/
                    contacts: contacts,
                    user_id: user_id,
                    _token: token,
                    project_id: project_id,
                },
                success: function (data) {
                    if (data.success) {
                        $('#error-message').html('<p class="alert alert-success">' + data.message + '</p>');
                        $('#TypeForm')[0].reset();
                        $("input").not('.customer_id').val('');
                        $("input.customer_id").val('0');
                        $("select").val('CEO');
                        setTimeout(function () {
                            window.location.reload(true);
                        }, 2000);
                    } else {
                        $('#error-message').html('<p class="input-error">' + data.message + '</p>');
                    }
                }, error: function(data, errorThrown) {
                    $('#error-message').html('<p class="input-error">' + data.message + '</p>');
                }
            });
        }
    });
    $('.error').on('focus', function () {
        $(this).removeClass('input-error');
        $('#error-message').html('');
    });

    function mapData() {
        var contacts = [];
        var max = $('.contact_row').length;
        for(var i = 0; i < max; i++) {
            contacts.push({
                'title': $('select[name="contacts['+i+'][title]"').val() !== undefined ? $('select[name="contacts['+i+'][title]"').val() : null,
                'title_other': $('input[name="contacts['+i+'][titleOther]"').val() !== undefined ? $('input[name="contacts['+i+'][titleOther]"').val() : null,
                'first_name': $('input[name="contacts['+i+'][firstName]"').val() !== undefined ? $('input[name="contacts['+i+'][firstName]"').val() :null,
                'last_name': $('input[name="contacts['+i+'][lastName]"').val() !== undefined ? $('input[name="contacts['+i+'][lastName]"').val() : null,
                'email': $('input[name="contacts['+i+'][email]"').val() !== undefined ? $('input[name="contacts['+i+'][email]"').val() : null,
                'customer_id': $('input[name="contacts['+i+'][customerId]"').val() !== undefined ? $('input[name="contacts['+i+'][customerId]"').val() : null,
                'phone': $('input[name="contacts['+i+'][directPhone]"').val() !== undefined ? $('input[name="contacts['+i+'][directPhone]"').val() : null,
                'cell': $('input[name="contacts['+i+'][cellPhone]"').val() !== undefined ? $('input[name="contacts['+i+'][cellPhone]"').val() : null,
            });
        }
        return contacts;
    }

    /*var maxField1 = 10; //Input fields increment limitation
    var addButton1 = $('.add_button'); //Add button selector
    var wrapper1 = $('.field_wrapper'); //Input field wrapper
    var fieldHTML1 = '<tr class="external"><td> <select class="form-control error title1" name="title[]">' +
        ' <option value="CEO">CEO</option> <option value="CFO">CFO</option> ' +
        '<option value="Credit">Credit</option> <option value="PM">PM</option> ' +
        '<option value="Corporation Counsel">Corporation Counsel</option> ' +
        '<option value="A/R Manager">A/R Manager</option> ' +
        '<option value="Other">Other</option> </select>' +
        '<div class="title_other" style="display: none;"> ' +
        '<input type="text" name="titleOther[]" class="form-control error">' +
        ' <a href="#" class="titleOtherBtn">Change</a> </div> </td>' +
        '<td><input class="form-control error autocomplete_contact_first_name" autocomplete="off" type="text" name="firstName[]" placeholder="First Name"/></td>' +
        '<td><input class="form-control error" type="text" name="lastName[]" placeholder="Last Name"/>' +
        '<input class="form-control error" type="hidden" name="customer_id[]" value="0" placeholder="Last Name"/></td>' +
        '<td><input class="form-control error" type="email" name="email[]" placeholder="Email"/></td>' +
        '<td><input class="form-control error" type="text" name="directPhone[]" placeholder="Direct Phone"/></td>' +
        '<td><input class="form-control error" type="text" name="cellPhone[]" placeholder="Cell Phone"/></td>' +
        '<td><a href="javascript:void(0);" class="remove_button1" title="Remove field">' +
        '<img src="' + baseUrl + '/images/remove-icon.png" height="30px" width="30px"/></a></td></tr>'; //New input field html
    var x1 = 1; //Initial field counter is 1
    $(addButton1).click(function () { //Once add button is clicked
        if (x1 < maxField1) { //Check maximum number of input fields
            x1++; //Increment field counter
            $('.field_wrapper tr:last').after(fieldHTML1); // Add field html
        }
    });
    $(wrapper1).on('click', '.remove_button1', function (e) { //Once remove button is clicked
        e.preventDefault();
        $(this).parents('tr').remove(); //Remove field html
        x1--; //Decrement field counter
    });*/


});
$(document).delegate('.title', 'change', function () {
    var item = $(this).val();
    if (item == 'Other') {
        $(this).hide();
        $(this).next('.title_other').show();
    }
});
$(document).delegate('.titleOtherBtn', 'click', function () {
    $(this).parent('div').hide();
    $(this).parent('div').parent('td').children('.title').val('CEO');
    $(this).parent('div').parent('td').children('.title').show();
});
/*$('.autocomplete').autocomplete({
    source: function (request, response) {
        var type = $('.autocomplete').data('type');
        var key = $('.autocomplete').val();
        $.ajax({
            url: autoComplete,
            dataType: "json",
            data: {
                type: type,
                key: key,
                _token: token
            },
            success: function (data) {
                var array = $.map(data.data, function (item) {
                    return {
                        label: item.company,
                        value: item.company,
                        id: item.id,
                        data: item.data
                    }
                });
                response(array)
            }
        });
    },
    minLength: 1,
    max: 10,
    select: function (event, ui) {
        $('#company').val(ui.item.data.company);
        $('#company_id').val(ui.item.data.id);
        $('#website').val(ui.item.data.website);
        $('#addressType').val(ui.item.data.address);
        $('#cityType').val(ui.item.data.city);
        $('#stateModal').val(ui.item.data.state_id);
        $('#zipType').val(ui.item.data.zip);
        $('#phone').val(ui.item.data.phone);
        $('#fax').val(ui.item.data.fax);
    }
});*/

var chosen = $('#company').chosen({
                    width: "100%",
                    no_results_text: "Oops, nothing found! <a class='add_company_from_search'>Click here to add company</a>"
                }).change(
                    function () {
                        if($(this).val() != "new_data") {
                            $.ajax({
                                url: autoCompleteCompany,
                                dataType: "json",
                                data: {
                                    key: $(this).val(),
                                    _token: token
                                },
                                success: function (response) {
                                    if(response.success && response.data !==  null) {
                                        setData(response);
                                    } else {
                                        reset();
                                    }
                                }
                            });

                        } else {
                            reset();
                        }
                    }
                );

function reset() {
    // $('#company').val("");
    // $('#company_id').val("0");
    $('#website').val("");
    $('#addressType').val("");
    $('#cityType').val("");
    $('#stateModal').val("");
    $('#zipType').val("");
    $('#phone').val("");
    $('#fax').val("");

    /* $('#website').attr('readonly',false);
     $('#addressType').attr('readonly',false);
     $('#cityType').attr('readonly',false);
     $('#stateModal').attr('readonly',false);
     $('#zipType').attr('readonly',false);
     $('#phone').attr('readonly',false);
     $('#fax').attr('readonly',false);*/

}

function setData(response) {
    //   $('#company').val(response.data.company);
    //   $('#company_id').val(response.data.id);
    $('#website').val(response.data.website);
    $('#addressType').val(response.data.address);
    $('#cityType').val(response.data.city);
    $('#stateModal').val(response.data.state_id);
    $('#zipType').val(response.data.zip);
    $('#phone').val(response.data.phone);
    $('#fax').val(response.data.fax);

    /* $('#website').attr('readonly',true);
     $('#addressType').attr('readonly',true);
     $('#cityType').attr('readonly',true);
     $('#stateModal').attr('readonly',true);
     $('#zipType').attr('readonly',true);
     $('#phone').attr('readonly',true);
     $('#fax').attr('readonly',true);*/
}

$(document).delegate('.add_company_from_search','click',function () {
    var company = chosen.data('chosen').get_search_text();
    $(".autocomplete option[value='new_data']").remove();
    $('.autocomplete').append("<option value='new_data'>"+company+"</option>");
    $('.autocomplete').val('new_data'); // if you want it to be automatically selected
    $('.autocomplete').trigger("chosen:updated");
    $('#company').attr('data-company_name',company);
    reset();
});

/*

$(document).delegate('.autocomplete_contact_first_name', 'focus', function(e) {
    $(this).autocomplete({
        source: function (request, response) {
            //var id = $('#id1').val();
            //var key = $(this).val();
            $.ajax({
                url: autoCompleteContact,
                dataType: "json",
                data: {
                    // id: id,
                    //   key: key,
                    _token: token
                },
                success: function (data) {
                    var array = $.map(data.data, function (item) {
                        return {
                            label: item.first_name,
                            value: item.first_name,
                            id: item.id,
                            data: item
                        }
                    });
                    response(array)
                }
            });
        },
        minLength: 1,
        max: 10,
        select: function (event, ui) {
            if (ui.item.data.title != '') {
                if (ui.item.data.title != 'Other') {
                    $(this).closest("tr").find(".title1").val(ui.item.data.title);
                } else {
                    $(this).closest("tr").find("input[name='titleOther[]']").val(ui.item.data.title_other);
                    $(this).closest("tr").find(".title_other").show();
                    $(this).closest("tr").find(".title1").hide();
                }
            } else {
                $(this).closest("tr").find(".title1").val('CEO');
            }
            $(this).closest("tr").find("input[name='lastName[]']").val(ui.item.data.last_name);
            $(this).closest("tr").find("input[name='customer_id[]']").val(ui.item.data.id);
            $(this).closest("tr").find("input[name='email[]']").val(ui.item.data.email);
            $(this).closest("tr").find("input[name='directPhone[]']").val(ui.item.data.phone);
            $(this).closest("tr").find("input[name='cellPhone[]']").val(ui.item.data.cell);
        }
    });
});
$(document).delegate('autocomplete_contact_first_name', 'blur', function(e) {
    var target = $(this);
    if (target.hasClass('ui-autocomplete-input')) {
        target.autocomplete('destroy');
    }
});*/



$(document).delegate('.autocomplete_contact_first_name', 'focus', function(e) {
    $(this).autocomplete({
        source: function (request, response) {
            //var id = $('#id1').val();
            var key = request.term;
            var ids = [];
            var maxLength = $('.contact_row').length;
            for(var i = 0; i < maxLength; i++) {
                ids.push($('input[name="contacts['+i+'][customerId]"').val());
            }
            $.ajax({
                url: autoCompleteContact,
                dataType: "json",
                data: {
                    // id: id,
                    key: key,
                    ids: ids,
                    type: $('#dataType').val(),
                    company_id: $('#company').val(),
                    contact_type: $('#contactType').val(),
                    _token: token
                },
                success: function (data) {
                    var array = $.map(data.data, function (item) {
                        return {
                            label: item.first_name,
                            value: item.first_name,
                            id: item.id,
                            data: item
                        }
                    });
                    response(array)
                }
            });
        },
        minLength: 1,
        max: 10,
        select: function (event, ui) {
            if (ui.item.data.title != '') {
                if (ui.item.data.title != 'Other') {
                    $(this).closest("tr").find(".title").val(ui.item.data.title);
                } else {
                    $(this).closest("tr").find(".title_other").val(ui.item.data.title_other);
                    $(this).closest("tr").find(".title_other").show();
                    $(this).closest("tr").find(".title").hide();
                }
            } else {
                $(this).closest("tr").find(".title").val('CEO');
            }
            //$(this).closest("tr").find(".first_name").val(ui.item.data.last_name);
            $(this).closest("tr").find(".last_name").val(ui.item.data.last_name);
            $(this).closest("tr").find(".email").val(ui.item.data.email);
            $(this).closest("tr").find(".phone").val(ui.item.data.phone);
            $(this).closest("tr").find(".cell").val(ui.item.data.cell);
            $(this).closest("tr").find(".customer_id").val(ui.item.data.id);
            /*$(this).closest("tr").find("input[name='contacts[][lastName]']").val(ui.item.data.last_name);
             $(this).closest("tr").find("input[name='customer_id[]']").val(ui.item.data.id);
             $(this).closest("tr").find("input[name='contacts[][email]']").val(ui.item.data.email);
             $(this).closest("tr").find("input[name='contacts[][directPhone]']").val(ui.item.data.phone);
             $(this).closest("tr").find("input[name='contacts[][cellPhone]']").val(ui.item.data.cell);*/
        }
    });
});

$(document).delegate('autocomplete_contact_first_name', 'blur', function(e) {
    var target = $(this);
    if (target.hasClass('ui-autocomplete-input')) {
        target.autocomplete('destroy');
    }
});
