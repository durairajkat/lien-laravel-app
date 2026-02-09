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


$('#paginate').change(function () {
    var location = window.location.href;
    if($(this).val() != '') {
        location = appendToQueryString('paginate', $(this).val());
        window.location.href = location;
    } else {
        location = removeURLParameter(location,'paginate');
        window.location.href = location;
    }
});
function removeURLParameter(url, parameter) {
    //prefer to use l.search if you have a location/link object
    var urlparts= url.split('?');
    if (urlparts.length>=2) {

        var prefix= encodeURIComponent(parameter)+'=';
        var pars= urlparts[1].split(/[&;]/g);

        //reverse iteration as may be destructive
        for (var i= pars.length; i-- > 0;) {
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                pars.splice(i, 1);
            }
        }

        url= urlparts[0]+'?'+pars.join('&');
        return url;
    } else {
        return url;
    }
}
appendToQueryString = function (param, val) {
    var queryString = window.location.search.replace("?", "");
    var parameterListRaw = queryString == "" ? [] : queryString.split("&");
    var parameterList = {};
    for (var i = 0; i < parameterListRaw.length; i++) {
        var parameter = parameterListRaw[i].split("=");
        if(parameter[0] != 'page'){
            parameterList[parameter[0]] = parameter[1];
        }
    }
    parameterList[param] = val;
    var newQueryString = "?";
    for (var item in parameterList) {
        if (parameterList.hasOwnProperty(item)) {
            newQueryString += item + "=" + parameterList[item] + "&";
        }
    }
    newQueryString = newQueryString.replace(/&$/, "");
    return location.origin + location.pathname + newQueryString;
}
function replaceUrlParam(url, paramName, paramValue)
{
    if (paramValue == null) {
        paramValue = '';
    }
    var pattern = new RegExp('\\b('+paramName+'=).*?(&|$)');
    if (url.search(pattern)>=0) {
        return url.replace(pattern,'$1' + paramValue + '$2');
    }
    url = url.replace(/\?$/,'');
    return url + (url.indexOf('?')>0 ? '&' : '?') + paramName + '=' + paramValue;
}
$('.fa-sort').on('click', function () {
    var col = $(this).data('col');
    var type = $(this).data('type');
    var location = window.location.href;
    var location = replaceUrlParam(location, 'sortBy', type);
    var location = replaceUrlParam(location, 'sortWith', col);
    window.location.href = location;
});

$(document).ready(function () {
    $('.industry').hide();
    $('.addIndustryButton').hide();
    $('.industryContacts').on('click', function () {
        $('.industry').show();
        $('.customer').hide();
        $('.addCustomerButton').hide();
        $('.addIndustryButton').show();

    });
    $('.customerContacts').on('click', function () {
        $('.industry').hide();
        $('.customer').show();
        $('.addCustomerButton').show();
        $('.addIndustryButton').hide();

    });
    $('.contactsList > li').on('click', function () {
        $('.contactsList > li').removeClass("select");
        $(this).addClass('select');

    });



    /** On click Add more lien providers button*/
    $('.add_button').click(function () {
        if($('.contact_row').length < 10) {
            var clonedEl = $('.contact_row:first').clone();
            clonedEl.appendTo($('.contact_table_body'));
            clonedEl.find("input").not('.customer_id').val('');
            clonedEl.find("input.customer_id").val('0');
            clonedEl.find("select").val('CEO');
            clonedEl.find('.add_button').addClass('remove_button').attr('title','Remove field').removeClass('add_button');
            clonedEl.find('img').attr('src', baseUrl+'/images/remove-icon.png');
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

    $('.addCustomerButton').on('click', function () {
        $('#TypeForm')[0].reset();
        $('#company').removeAttr('data-company_name');
        var type = $(this).data('type');
        $('.contact_row').not(':first').remove();
        $('#company').val("").trigger('chosen:updated');
        $('.customer_id').val(0);
        $('.modal_title').html(type);

        $('.contractType').hide();
        $('#contactType').attr('required',false);
        var contact = $(this).data('contact');
        $('#dataType').val(contact);

       /* $('.external').remove();
        $('#contactId').val(0);*/
        if (type == 'Edit') {
          /*  $('.formSubmit').attr('data-type', 'add');
            $('.title').html('Add');*/
            /*$('.contractType').hide();
            $('#contactType').attr('required',false);
            $('.CustomerShow').show();
            $('.formSubmit').attr('data-type', 'customer');
            $('#dataType').val('customer');*/
        /*} else {*/
            $('#company').val($(this).data('id')).trigger('chosen:updated');
            $('#website').val($(this).data('website'));
            $('#addressType').val($(this).data('address'));
            $('#cityType').val($(this).data('city'));
            $('#stateModal').val($(this).data('state'));
            $('#zipType').val($(this).data('zip'));
            $('#phone').val($(this).data('phone'));
            $('#fax').val($(this).data('fax'));
            var contactInformation = $(this).data('contactinformation');
            var html = '';
            if (contactInformation.length > 0) {
                $.each(contactInformation, function (index, value) {
                    if (index == 0) {
                        if (value.title == 'Other') {
                           /* $('#title2').hide();
                            $('#title_other').show();*/
                            $('.contact_row:first').find('.title').hide();
                            $('.contact_row:first').find('.title_other').show();
                        }
                      /*  $('#title2').val(value.title);
                        $('#title_other').val(value.title_other);
                        $('#firstName').val(value.first_name);
                        $('#lastName').val(value.last_name);
                        $('#email').val(value.email);
                        $('#directPhone').val(value.phone);
                        $('#cellPhone').val(value.cell);
                        $('#contactId').val(value.id);*/
                        $('.contact_row:first').find('.title').val(value.title);
                        $('.contact_row:first').find('.title_other').val(value.title_other);
                        $('.contact_row:first').find('.first_name').val(value.first_name);
                        $('.contact_row:first').find('.last_name').val(value.last_name);
                        $('.contact_row:first').find('.email').val(value.email);
                        $('.contact_row:first').find('.phone').val(value.phone);
                        $('.contact_row:first').find('.cell').val(value.cell);
                        $('.contact_row:first').find('.customer_id').val(value.id);
                    } else {
                        var title_other = value.title_other != null ? value.title_other : '';
                        var firstName = value.first_name != null ? value.first_name : '';
                        var lastName = value.last_name != null ? value.last_name : '';
                        var email = value.email != null ? value.email : '';
                        var direct_phone = value.phone != null ? value.phone : '';
                        var cell = value.cell != null ? value.cell : '';
                        var id = value.id != null ? value.id : '';

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



                                /* html += '<tr class="external"><td> <select class="form-control error title1" name="title[]">' +
                                     ' <option value="CEO" ' + (value.title === "CEO" ? "selected" : "") + '>CEO</option> ' +
                                     '<option value="CFO" ' + (value.title === "CFO" ? "selected" : "") + '>CFO</option> ' +
                                     '<option value="Credit" ' + (value.title === "Credit" ? "selected" : "") + '>Credit</option> ' +
                                     '<option value="PM" '+ (value.title === "PM" ? "selected" : "") + '>PM</option> ' +
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
                              /*  html += '<tr class="external"><td> <select class="form-control error title1" name="title[]"  style="display: none;">' +
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

                            /*html += '<tr class="external"><td> <select class="form-control error title1" name="title[]" >' +
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

                        /*html += '<td><input class="form-control error autocomplete_contact_first_name" type="text" name="firstName[]" value="' + firstName + '" placeholder="First Name"/></td>' +
                            '<td><input class="form-control error" type="text" name="lastName[]" value="' + lastName + '" placeholder="Last Name"/></td>' +
                            '<td><input class="form-control error" type="email" name="email[]" value="' + email + '" placeholder="Email"/></td>' +
                            '<td><input class="form-control error" type="text" name="directPhone[]" value="' + direct_phone + '" placeholder="Direct Phone"/></td>' +
                            '<td><input class="form-control error" type="text" name="cellPhone[]" value="' + cell + '" placeholder="Cell Phone"/></td>' +
                            '<td><a href="javascript:void(0);" class="remove_button1" title="Remove field">' +
                            '<img src= '+baseUrl+'"/images/remove-icon.png" height="30px" width="30px"/></a><input class="form-control error" type="hidden" name="contactId[]" value="' + id + '" /></td></tr>';*/
                    }
                });
            }
            $('.contact_row:last').after(html);
            $('#contactType').removeAttr('disabled');
          //  $('#contactType').val(data.customers[0].contact_type);

           /* $('.field_wrapper4 tr:last').after(html);
            $('#id').val($(this).data('id'));
            $('.formSubmit').attr('data-type', 'edit');
            $('.title').html('Edit');*/
        }
        $('#addCustomerModel').modal('show');
    });

    /*$('.addIndustryButton').on('click', function () {
        $('#IndustryEditForm')[0].reset();
        var type = $(this).data('type');
        if (type == 'add') {
            $('.formSubmit').attr('data-type', 'add');
            $('.title').html('Add');
        } else {
            $('#contactType1').val($(this).data('contact_type'));
            $('#company1').val($(this).data('company'));
            $('#firstName1').val($(this).data('first_name'));
            $('#lastName1').val($(this).data('last_name'));
            $('#address1').val($(this).data('address'));
            $('#city1').val($(this).data('city'));
            $('#state1').val($(this).data('state'));
            $('#zip1').val($(this).data('zip'));
            //$('#phone1').val($(this).data('phone'));
            var phone = $(this).data('phone');
            var html = '';
            $.each(phone, function (index, value) {
                if (index == 0) {
                    $('#phone1').val(value)
                } else {
                    html += '<div style="padding-top:50px;">' +
                        '<div class="col-md-4">&nbsp;</div>' +
                        '<div class="col-md-6"><input type="text" name="phone1[]" value="' + value + '" class="form-control error phone" placeholder="Enter Phone Number"/></div>' +
                        '<div class="col-md-2"><a href="javascript:void(0);" class="remove_button1" title="Remove field">' +
                        '<img src="'+baseUrl+'/images/remove-icon.png" height="30px" width="30px"/></a></div></div>';
                }
            });
            $('.field_wrapper3').html(html);
            $('#fax1').val($(this).data('fax'));
            $('#email1').val($(this).data('email'));
            $('#id1').val($(this).data('id'));
            $('.formSubmitIndustry').attr('data-type', 'edit');
            $('.title').html('Edit');
        }
        $('#addIndustryModel').modal('show');
    });*/

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

       /* var company = $('#company').val();
        var flag = true;
        var id = $('#id').val();
        if (company == '') {
            $('#company').addClass('input-error');
            flag = false;
        }
        var website = $('#website').val();
        var address = $('#address').val();
        var city = $('#city').val();
        var state = $('#state').val();
        if (state == '') {
            $('#state').addClass('input-error');
            flag = false;
        }
        var zip = $('#zip').val();
        if (zip != '' && !validateNumber(zip, 'zip')) {
            $('#zip').addClass('input-error');
            flag = false;
        }
        var phone = $("#phone").val();
        var fax = $('#fax').val();
        if (fax != '' && !validateNumber(fax, 'fax')) {
            $('#fax').addClass('input-error');
            flag = false;
        }*/
       /* var firstName = $("input[name='firstName[]']")
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

        var contactId = $("input[name='contactId[]']")
            .map(function () {
                return $(this).val();
            }).get();*/

        //var type = $(this).data('type');
        if (flag) {
            $.ajax({
                type: "POST",
              //  url: "{{ route('create.contact') }}",
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
                    contacts: contacts,
                    /* company_id: company_id,
                     title: title,
                     titleOther: titleOther,
                     firstName: firstName,
                     customer_id: customer_id,
                     lastName: lastName,
                     email: email,
                     directPhone: directPhone,
                     cell: cellPhone,*/
                    user_id: user_id,
                    _token: token
                   /* id: id,
                    type: type,
                    contactId: contactId,
                    contact: '0',
                    company: company,
                    website: website,
                    address: address,
                    city: city,
                    state: state,
                    zip: zip,
                    phone: phone,
                    fax: fax,
                    title: title,
                    titleOther: titleOther,
                    firstName: firstName,
                    lastName: lastName,
                    email: email,
                    directPhone: directPhone,
                    cell: cellPhone,
                    //user_id: '{{ Auth::user()->id }}',
                    user_id: user_id,
                    //_token: "{{ csrf_token() }}"
                    _token: token*/
                },
                success: function (data) {
                    if (data.success) {
                        if(!data.empty) {
                            $('#error-message').html('<p class="alert alert-success">' + data.message + '</p>');
                        }
                        $('#TypeForm')[0].reset();
                        $("input").not('.customer_id').val('');
                        $("input.customer_id").val('0');
                        $("select").val('CEO');
                        setTimeout(function () {
                            window.location.reload(true);
                        }, 2000);
                    } else {
                        $('#error-message').html('<p class="alert alert-danger">' + data.message + '</p>');
                    }
                }, error: function(data, errorThrown) {
                    $('#error-message').html('<p class="input-error">' + data.message + '</p>');
                }
            });
        }
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

    function getIds() {
        var ids = [];
        var maxLength = $('.contact_row').length;
        for(var i = 0; i < maxLength; i++) {
            ids.push($('input[name="contacts['+i+'][customerId]"').val());
        }
        return ids;
    }
   /* $('.formSubmitIndustry').on('click', function () {
        var company = $('#company1').val();
        var flag = true;
        var id = $('#id1').val();
        if (company == '') {
            $('#company1').addClass('input-error');
            flag = false;
        }
        var firstName = $('#firstName1').val();

        var contactType = $('#contactType1').val();
        if (contactType == '') {
            $('#contactType').addClass('input-error');
            flag = false;
        }
        var lastName = $('#lastName1').val();
        var address = $('#address1').val();
        if (address == '') {
            $('#address1').addClass('input-error');
            flag = false;
        }
        var city = $('#city1').val();
        if (city == '') {
            $('#city1').addClass('input-error');
            flag = false;
        }
        var state = $('#state1').val();
        if (state == '') {
            $('#state1').addClass('input-error');
            flag = false;
        }
        var zip = $('#zip1').val();
        if (zip == '') {
            $('#zip1').addClass('input-error');
            flag = false;
        }
        if (!validateNumber(zip, 'zip')) {
            $('#zip1').addClass('input-error');
            flag = false;
        }
        var phone = $("input[name='phone1[]']")
            .map(function () {
                return $(this).val();
            }).get();
        $.each(phone, function (index, value) {
            if (value != '' && !validateNumber(value, 'phone')) {
                $("input[name='phone[]']").addClass('input-error');
                flag = false;
            }
        });
        var fax = $('#fax1').val();
        if (fax == '') {
            $('#fax1').addClass('input-error');
            flag = false;
        }
        if (!validateNumber(fax, 'fax')) {
            $('#fax1').addClass('input-error');
            flag = false;
        }
        var email = $('#email1').val();
        if (email == '') {
            $('#email1').addClass('input-error');
            flag = false;
        }
        if (!validateEmail(email)) {
            $('#email1').addClass('input-error');
            flag = false;
        }
        var type = $(this).data('type');

        if (flag) {
            $.ajax({
                type: "POST",
                url: "{{ route('create.contact') }}",
                data: {
                    id: id,
                    type: type,
                    contact: '1',
                    contactType: contactType,
                    company: company,
                    firstName: firstName,
                    lastName: lastName,
                    address: address,
                    city: city,
                    zip: zip,
                    phone: phone,
                    fax: fax,
                    email: email,
                    state: state,
                    user_id: '{{ Auth::user()->id }}',
                    _token: "{{ csrf_token() }}"
                },
                success: function (data) {
                    if (data.status) {
                        $('#success-message').html('<p class="alert alert-success">' + data.message + '</p>');
                        $('#IndustryEditForm')[0].reset();
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    } else {
                        $('#success-message').html('<p class="input-error">' + data.message + '</p>');
                    }
                }
            });
        }
    });*/
    $('.delete').on('click', function () {
        var id = $(this).data('id');
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function () {
            $.ajax({
                type: "POST",
               /* url: "{{ route('customer.delete.contact') }}",*/
                url: deleteContacts,
                data: {
                    id: id,
                   /* _token: "{{ csrf_token() }}"*/
                    _token: token
                },
                success: function (data) {
                    if (data.status) {
                        swal({
                            position: 'center',
                            type: 'success',
                            title: 'Contact deleted successfully',
                        }).then(function () {
                            window.location.reload(true);
                        });
                    } else {
                        swal(
                            'Error',
                            data.message,
                            'error'
                        )
                    }
                }
            });
        });
    });
    $('.error').on('focus', function () {
        $(this).removeClass('input-error');
        $('#error-message').html('');
    });



   /* var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<tr class="external"><td> <select class="form-control error title2" name="title[]">' +
        ' <option value="CEO">CEO</option> <option value="CFO">CFO</option> ' +
        '<option value="Credit">Credit</option> <option value="PM">PM</option> ' +
        '<option value="Corporation Counsel">Corporation Counsel</option> ' +
        '<option value="A/R Manager">A/R Manager</option> ' +
        '<option value="Other">Other</option> </select>' +
        '<div class="title_other" style="display: none;"> ' +
        '<input type="text" name="titleOther[]" class="form-control error">' +
        ' <a href="#" class="titleOtherBtn">Change</a> </div> </td>' +
        '<td><input class="form-control error autocomplete_contact_first_name" type="text" name="firstName[]" placeholder="First Name"/></td>' +
        '<td><input class="form-control error" type="text" name="lastName[]" placeholder="Last Name"/></td>' +
        '<td><input class="form-control error" type="email" name="email[]" placeholder="Email"/></td>' +
        '<td><input class="form-control error" type="text" name="directPhone[]" placeholder="Direct Phone"/></td>' +
        '<td><input class="form-control error" type="text" name="cellPhone[]" placeholder="Cell Phone"/></td>' +
        '<td><a href="javascript:void(0);" class="remove_button" title="Remove field">' +
        '<img src="'+baseUrl+'/images/remove-icon.png" height="30px" width="30px"/></a><input class="form-control error" type="hidden" name="contactId[]" value="0" /></td></tr>'; //New input field html
    var x = 1; //Initial field counter is 1
    $(addButton).click(function () { //Once add button is clicked
        if (x < maxField) { //Check maximum number of input fields
            x++; //Increment field counter
            //$(wrapper).append(fieldHTML); // Add field htm
            $('.field_wrapper4 tr:last').after(fieldHTML);
        }
    });
    $(wrapper).on('click', '.remove_button', function (e) { //Once remove button is clicked
        e.preventDefault();
        $(this).parents('tr').remove(); //Remove field html
        x--; //Decrement field counter
    });


    var maxField1 = 10; //Input fields increment limitation
    var addButton1 = $('.add_button1'); //Add button selector
    var wrapper1 = $('.field_wrapper2'); //Input field wrapper
    var fieldHTML1 = '<div style="padding-top:50px;">' +
        '<div class="col-md-4">&nbsp;</div>' +
        '<div class="col-md-6"><input type="text" name="phone1[]" class="form-control error phone1" placeholder="Enter Phone Number"/></div>' +
        '<div class="col-md-2"><a href="javascript:void(0);" class="remove_button1" title="Remove field">' +
        '<img src="'+baseUrl+'/images/remove-icon.png" height="30px" width="30px"/></a></div></div>'; //New input field html
    var x1 = 1; //Initial field counter is 1
    $(addButton1).click(function () { //Once add button is clicked
        if (x1 < maxField1) { //Check maximum number of input fields
            x1++; //Increment field counter
            $(wrapper1).append(fieldHTML1); // Add field html
        }
    });
    $(wrapper1).on('click', '.remove_button1', function (e) { //Once remove button is clicked
        e.preventDefault();
        $(this).parent().parent('div').remove(); //Remove field html
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


/*
$('.autocomplete').autocomplete({
    source: function (request, response) {
        var key = request.term;
        $.ajax({
            //url: "{{ route('autocomplete.contact.company') }}",
            url: autoComplete,
            dataType: "json",
            data: {
                key: key,
                //_token: '{{ csrf_token() }}'
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
        // $('.external').remove();
        // $('#contactType1').removeAttr('disabled');
        // $('.formSubmitIndustry').attr('data-type', 'edit');
        // $('#contactType1').val(ui.item.data.contact_type);
        // $('#id1').val(ui.item.id);
        $('#website').val(ui.item.data.website);
        $('#address').val(ui.item.data.address);
        $('#city').val(ui.item.data.city);
        $('#state').val(ui.item.data.state_id);
        $('#zip').val(ui.item.data.zip);
        $('#phone').val(ui.item.data.phone);
        $('#fax').val(ui.item.data.fax);
        $('.title').val('CEO');
        $('#firstName').val('');
        $('#lastName').val('');
        $('#email').val('');
        $('#directPhone').val('');
        $('#cellPhone').val('');
    }
});
*/


var chosen = $('.autocomplete').chosen({
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
                //url: "{{ route('autocomplete.contact.firstname') }}",
                url: autoCompleteContact,
                dataType: "json",
                data: {
                    // id: id,
                    //   key: key,
                    //_token: '{{ csrf_token() }}'
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
            $(this).closest("tr").find("input[name='directPhone[]']").val(ui.item.data.direct_phone);
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
                    ids: ids,
                    key: key,
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
