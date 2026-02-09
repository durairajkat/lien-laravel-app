var comboBoxVariable;
var companies = [];

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
    if(type == "phone"){
        if (/^\(?(\d{3})\)?[-\. ]?(\d{3})[-\. ]?(\d{4})$/.test(number)) {
            return true;
        }

    }
    $('#error-message').html("<p class='input-error'>You have entered an invalid " + type + " !</p>");
    return false;
}

$('#contactType').chosen({
    width: "100%",
    no_results_text: "Oops, nothing found!"
});

$(document).ready(function () {
    /** On click Add more lien providers button*/
    $('.add_button').click(function () {
        if($('.contact_row').length < 10) {
            var clonedEl = $('.contact_row:first').clone();
            clonedEl.appendTo($('.contact_table_body'));
            clonedEl.find("input").not('.customer_id').val('');
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

    /**When adding a contact in project details page.*/
    $('.addNew').on('click', function () {
        console.log('@@@@@@@@@@@@@@@@@@@@@@@@@ 1');
        $('#company_new').hide();
        $('#TypeForm')[0].reset();
        $('#contactType').val("").trigger('chosen:updated');
        $('#company').removeAttr('data-company_name');
        $('.contact_row').not(':first').remove();
        $('#company').val("").trigger('chosen:updated');
        $('.customer_id').val(0);
        var type = $(this).data('type');
        $('.modal_title').html(type);
        console.log('add new button clicked, type = ', type);


        // 06-sep
        if (type == 'Customer') {
            resetModalCompanyList(type)

            $('.contractType').hide();
            $('#contactType').attr('required',false);
            $('.CustomerShow').show();
            $('.formSubmit').attr('data-type', 'customer');
            $('#dataType').val('customer');
            getCompanies('customer');

        } else if ( type == 'Industry') {
            resetModalCompanyList(type)

            $('.contractType').show();
            $('#contactType').attr('required',true);
            $('.CustomerShow').hide();
            $('.formSubmit').attr('data-type', 'industry');
            $('#dataType').val('industry');
            getCompanies('industry');
        } else {
            // Company
            $('.contractType').hide();
            $('#contactType').attr('required',false);
            $('.CustomerShow').hide();
            $('.formSubmit').attr('data-type', 'customer');
            $('#dataType').val('customer');
            getCompanies('company');
        }
        $('#addCustomerModel').modal('show');
    });

    $('#addCustomerModel').on('hidden.bs.modal', function () {
        console.log("##################### ");
        $('#company_new').attr('style', '');
    })

    /**When adding a contact in project details page.*/
    $(document).on('click','.editContact' ,function () {

        $('#company_new').attr('style', '');
        $('#company_new').hide();
        $('#TypeForm')[0].reset();
        $('#contactType').val("").trigger('chosen:updated');
        $('#company').removeAttr('data-company_name');
        $('.contact_row').not(':first').remove();
        $('#company').val("").trigger('chosen:updated');
        $('.customer_id').val(0);
        var type = $(this).data('type');
        var id = $(this).data('id');
        $('.modal_title').html(type);

        // 06-sep
        if (type == 'Customer') {
            $('.contractType').hide();
            $('#contactType').attr('required',false);
            $('.CustomerShow').show();
            $('.formSubmit').attr('data-type', 'customer');
            $('#dataType').val('customer');
            getCompanies('customer' , id);

        } else if ( type == 'Industry') {
            $('.contractType').show();
            $('#contactType').attr('required',true);
            $('.CustomerShow').hide();
            $('.formSubmit').attr('data-type', 'industry');
            $('#dataType').val('industry');
            getCompanies('industry', id);
        } else {
            // Company
            $('.contractType').hide();
            $('#contactType').attr('required',false);
            $('.CustomerShow').hide();
            $('.formSubmit').attr('data-type', 'customer');
            $('#dataType').val('customer');
            getCompanies('company', id);
        }
        $('#addCustomerModel').modal('show');
    });


    /**When deleting a contact in project contact page.*/
    $(document).on('click','.deleteContact' ,function () {
        var id = $(this).data('id');
        swal({
            type: 'warning',
            title: 'Warning',
            text: 'Are you sure you want to delete this contact !!!',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
        }).then(function () {
            $.ajax({
                type: "POST",
                url: deleteContacts,
                data: {
                    id: id,
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

    function cancelDropDown(ev) {
        ev.preventDefault();
    }

    // $("#company").addEventListener("mousedown", cancelDropDown, false);
    $("#companies").autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: fetchCompanies,
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 3,
        select: function( event, ui ) {
            log( ui.item ?
                "Selected: " + ui.item.label :
                "Nothing selected, input was " + this.value);
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        });


        function getCompanies(type, contact = null) {
            $.ajax({
                url: fetchCompanies,
                dataType: "json",
                data: {
                    type: type,
                    _token: token
                },
                success: function (response) {
                    if(response.success && response.data.length > 0) {
                        $('#company_new').val('').trigger('chosen:updated');
                        reset();
                        companies = response.data;
                        var html = '<option value=""></option>';
                        $.each(response.data, function (index, company) {
                            html += '<option value='+company.id+'>'+company.company+'</option>';
                        });

                        $('#company_new').chosen('destroy').html(html).val('').change(
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
                    } else {
                        reset();
                    }

                    if(contact){
                        $.ajax({
                            url: getContactDetailsURL,
                            dataType: "json",
                            data: {
                                id: contact,
                                type: type,
                                _token: token
                            },
                            success: function (data) {
                                if(type == 'industry'){
                                    $('#contactType').val(data.data.contact_type);
                                    $("#contactType").trigger('chosen:updated');
                                    $("#contactType").trigger('change');
                                    setTimeout(function(){
                                        $("#company").val(data.company.company_id);
                                        $("#company_new").val(data.company.company_id);

                                        $.each(companies, function (index, company) {
                                            if(company.id == data.company.company_id){
                                                $('.ui-autocomplete-input').focus().val(company.company);
                                                $("#new_company_name").text(company.company);
                                            }
                                        });

                                        $("#company").trigger('chosen:updated');
                                        $("#company").trigger('change');
                                    },1500);
                                }
                                $("#company").val(data.company.company_id);
                                $("#company").trigger('chosen:updated');
                                $("#company").trigger('change');
                                $("#company_new").val(data.company.company_id);

                                $.each(companies, function (index, company) {
                                    if(company.id == data.company.company_id){
                                        $('.ui-autocomplete-input').focus().val(company.company);
                                        $("#new_company_name").text(company.company);
                                    }
                                });

                                var response = {};
                                response.data = data.company;
                                setData(response);
                                $('#title').val(data.data.title);
                                $('#first_name').val(data.data.first_name);
                                $('#lastName').val(data.data.last_name);
                                $('#email').val(data.data.email);
                                $('#directPhone').val(data.data.phone);
                                $('#cellPhone').val(data.data.cell);
                                $('#customerId').val(data.data.id);
                            }
                        });
                    }
                }
            });
        }


        $('#TypeForm').on('submit', function (e) {
            e.preventDefault();
            var customer_id = $('#customerId').val();
            var flag = true;
            var type = $('#dataType').val();//customer or industry
            //Company
            var company = $('#company_new').val();
            var company_name = $("#new_company_name").text(); //$('#company_new').data('company_name');

            company_name = company_name.trim();

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
                        user_id: user_id,
                        contacts: contacts,
                        _token: token,
                    },
                    success: function (data) {
                        if (data.success) {
                            if (data.data != '') {
                                if(customer_id > 0){
                                    location.reload();
                                }
                                if (type == 'industry') {
                                    var htmlData = '';
                                    var updatedhtmlData = [];
                                    $.each(data.data['customers'], function( index, value ) {
                                        htmlData += '<option value="' + value.map_id + '" selected>' + ((value.first_name !== null) ? value.first_name : '' ) + ' '+  ((value.last_name !== null) ? value.last_name : '' ) +' : '+value.contact_type+' ( '+ value.company  /*data.data['company']*/ + ' )</option>';
                                    });
                                    var html = '';
                                    $.each(data.data['new_customers'], function( index, value ) {
                                        updatedhtmlData.push(value.map_id);

                                        var type = "Industry";
                                        var row = "<tr>";
                                        var column = "<td>";

                                        column += value.first_name + ' ' +  value.last_name;
                                        column += "</td>";

                                        column += "<td>";
                                        column += value.contact_type;
                                        column += "</td>";

                                        column += "<td>";
                                        column += value.company;
                                        column += "</td>";

                                        column += "<td>";
                                        column += value.phone;
                                        column += "</td>";

                                        column += "<td>";
                                        column += value.email;
                                        column += "</td>";

                                        column += "<td>";
                                        column += "<i data-id='" + value.id + "' data-type='" + type +"' title='Edit' class='fa fa-edit editContact'></i>";
                                        column += "<i style='margin-left:10px;' data-id='" + value.id + "' data-type='" + type +"' title='Delete' class='fa fa-trash deleteContact'></i>";
                                        column += "</td>";

                                        row += column;
                                        row += "</tr>";

                                        html += row;
                                    });
                                    var values = $(".industry-contract").chosen().val();
                                    var updatedValues = $.merge( $.merge( [], (updatedhtmlData !== null) ? updatedhtmlData : []), (values !== null) ? values : [] );
                                    $('.industry-contract').chosen('destroy').html(htmlData).val(updatedValues).chosen();
                                    submitChanges();
                                    $('#industry_contact_table_body').append(html);

                                    $(".address_holder").show();
                                } else {
                                    var htmlData = '<option value ="" '+(data.data['selected_customer'] == "" ? "selected" : '')+'>Select a customer contact</option>';
                                    $.each(data.data['customers'], function( index, value ) {
                                        htmlData += '<option value="' + value.map_id + '" '+(data.data['selected_customer'] != "" && value.map_id == data.data['selected_customer'].map_id ? "selected" : '')+'>' + ((value.first_name !== null) ? value.first_name : '' ) + ' ' + ((value.last_name !== null) ? value.last_name : '' ) + ' ( '+value.company /*data.data['company']*/ + ' )</option>';
                                    });
                                    //$('.customer-contract').append('<option value="' + data.data['customers'][0].map_id + '" selected>' + data.data['customers'][0].first_name+' '+data.data['customers'][0].last_name+' : '+data.data['contact']+' ( '+data.data['company'] + ' )</option>');
                                    $('.customer-contract').html(htmlData);

                                    $('#customer-contract').val(data.data.selected_customer.map_id);
                                    $('#customer-contract').trigger("change");
                                    location.reload();

                                    $(".section-industry-contact_holder").show();
                                }
                                $('.formSubmit').removeData('type');
                                // window.location.reload();
                                $('#addCustomerModel').modal('hide');
                            } else {
                                $('#error-message').html('<p class="input-error">Can not able to auto-populate.Please Select contact</p>');
                            }
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

    $( "#customer-contract" ).change(function() {
        var contacts = [];
        var contact_id = $('#customer-contract').find(":selected").val();
        $.each(customerContacts, function(index, value ){
            if(value.id == contact_id){
                contacts.push(customerContacts[index]);
            }
        });
        populateContactTable('#customer_contact_table_body' , contacts)
    });

    $( "#industry-contract" ).change(function() {
        console.log('industry-contract trigger');
        var contacts = [];
        $('#industry-contract').find(":selected").each(function() {
            console.log('industry-contract trigger selected');
            contact_id = $(this).val();
            console.log('industry-contract trigger selected contact_id: '+contact_id);
            $.each(industryContacts, function(index, value ){
                console.log('industry-contract trigger selected contact_id: '+contact_id+' - valueId: '+value.id);
                if(value.id == contact_id){
                    contacts.push(industryContacts[index]);
                }
            });
        });
        console.log('industry-contract trigger contacts: ', contacts);
        populateContactTable('#industry_contact_table_body' , contacts)
    });

    function populateContactTable(table, contacts ) {
        submitChanges();
        var html = "";
        var type = "Customer"
        if(table == "#industry_contact_table_body"){
            type = "Industry";
        }
        $(table).empty();
        $.each(contacts, function(index, value){
            var row = "<tr>";
            var column = "<td>";

            column += value.contacts.first_name + ' ' +  value.contacts.last_name;
            column += "</td>";

            if(table == '#industry_contact_table_body'){
                column += "<td>";
                column += value.contacts.contact_type;
                column += "</td>";
            }

            column += "<td>";
            column += value.company.company;
            column += "</td>";

            column += "<td>";
            column += value.contacts.phone;
            column += "</td>";

            column += "<td>";
            column += value.contacts.email;
            column += "</td>";

            column += "<td>";
            column += "<i data-id='" + value.contacts.id + "' data-type='" + type +"' title='Edit' class='fa fa-edit editContact'></i>";
            column += "<i style='margin-left:10px;' data-id='" + value.contacts.id + "' data-type='" + type +"' title='Delete' class='fa fa-trash deleteContact'></i>";
            column += "</td>";

            row += column;
            row += "</tr>";

            html += row;
        });
        $(table).html(html);
    }

    function submitChanges() {
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

    $('#contactType').change(function () {
        var role = $(this).val()
        console.log('role type changed', role)
        resetModalCompanyList("Industry", role)


        // $.ajax({
        //     url: autoCompleteCompanyOnRoleChange,
        //     dataType: "json",
        //     data: {
        //         key: $(this).val(),
        //         _token: token
        //     },
        //     success: function (response) {
        //         console.log('response', response)
        //         if(response.success && response.data.length > 0) {
        //             reset();
        //             var company_id = null;
        //             var html = '<option value=""></option>';
        //             $.each(response.data, function (index, company) {
        //                 html += '<option value='+company.id+' >'+company.company+'</option>';
        //                 company_id = company.id;
        //
        //             });
        //
        //             if(company_id) {
        //                 $.ajax({
        //                     url: autoCompleteCompany,
        //                     dataType: "json",
        //                     data: {
        //                         key: company_id,
        //                         _token: token
        //                     },
        //                     success: function (response) {
        //                         if(response.success && response.data !==  null) {
        //                             setData(response);
        //                         } else {
        //                             reset();
        //                         }
        //                     }
        //                 });
        //             }
        //
        //
        //             $('#company').chosen('destroy').html(html).val('').chosen({
        //                 width: "100%",
        //                 no_results_text: "Oops, nothing found! <a class='add_company_from_search'>Click here to add company</a>"
        //             }).change(
        //                 function () {
        //                     if($(this).val() != "new_data") {
        //                         $.ajax({
        //                             url: autoCompleteCompany,
        //                             dataType: "json",
        //                             data: {
        //                                 key: $(this).val(),
        //                                 _token: token
        //                             },
        //                             success: function (response) {
        //                                 if(response.success && response.data !==  null) {
        //                                     setData(response);
        //                                 } else {
        //                                     reset();
        //                                 }
        //                             }
        //                         });
        //
        //                     } else {
        //                         reset();
        //                     }
        //                 }
        //             );
        //         } else {
        //             reset();
        //         }
        //     }
        // });
    });
    function resetModalCompanyList(type, role='')
    {
        console.log('type = ', type)

        $("#company_new option[value!='new_data']").remove();
        if(type=='Customer'){

            var items = $('#customer-contract option').clone();
            items.each(function(){
                $(this).text($(this).attr('company'));
                $('#company_new').append($(this));
            })
        }else if(type=='Industry'){

            var items = $('#industry-contract option').clone();
            items.each(function(){
                $(this).text($(this).attr('company'));
                if(role=='')
                    $('#company_new').append($(this));
                else if(role == $(this).attr('contact_type'))
                    $('#company_new').append($(this));
            })
        }


    }
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
        $('#website').val("");
        $('#addressType').val("");
        $('#cityType').val("");
        $('#stateModal').val("");
        $('#zipType').val("");
        $('#phone').val("");
        $('#fax').val("");
    }

    function setData(response) {
        console.log('response.data.website',response.data.website);
        $('#website').val(response.data.website);
        $('#addressType').val(response.data.address);
        $('#cityType').val(response.data.city);
        $('#stateModal').val(response.data.state_id);
        $('#zipType').val(response.data.zip);
        $('#phone').val(response.data.phone);
        $('#fax').val(response.data.fax);
    }

    $(document).delegate('.add_company_from_search','click',function () {
        var company = $("#new_company_name").text();
        // var company = chosen.data('chosen').get_search_text();
        $("#company_new option[value='new_data']").remove();
        $('#company_new').append("<option value='new_data'>"+company+"</option>");
        $("#add_new_company_button").hide();
        // $("#new_company_name").text("");
        $('.ui-autocomplete-input').focus().val(company);
        $('#company_new').val("new_data");
        reset();
    });

    function addNewCompany () {
        var company = $("#new_company_name").text();
        // var company = chosen.data('chosen').get_search_text();
        $("#company_new option[value='new_data']").remove();
        $('#company_new').append("<option value='new_data'>"+company+"</option>");
        $("#add_new_company_button").hide();
        // $("#new_company_name").text("");
        // $('.ui-autocomplete-input').focus().val(company);
        $('#company_new').val("new_data");

        console.log($("#new_company_name").text());
        console.log($('.ui-autocomplete-input').val());
        reset();
    }

    $(document).delegate('.autocomplete_contact_first_name', 'focus', function(e) {
        $(this).autocomplete({
            source: function (request, response) {
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
            }
        });
    });

    $(document).delegate('autocomplete_contact_first_name', 'blur', function(e) {
        var target = $(this);
        if (target.hasClass('ui-autocomplete-input')) {
            target.autocomplete('destroy');
        }
    });

    $(function() {
        $.widget( "custom.combobox", {
            _create: function() {
                this.wrapper = $( "<span>" )
                .addClass( "custom-combobox" )
                .insertAfter( this.element );

                this.element.hide();
                this._createAutocomplete();
                this._createShowAllButton();
            },

            _createAutocomplete: function() {
                var selected = this.element.children( ":selected" ),
                value = selected.val() ? selected.text() : "";

                this.input = $( "<input class='col-sm-12'>" )
                .appendTo( this.wrapper )
                .val( value )
                .attr( "title", "" )
                .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
                .autocomplete({
                    delay: 0,
                    minLength: 0,
                    source: $.proxy( this, "_source" )
                })
                .tooltip({
                    classes: {
                        "ui-tooltip": "ui-state-highlight"
                    }
                });

                this._on( this.input, {

                    autocompleteselect: function( event, ui ) {
                        ui.item.option.selected = true;
                        $.ajax({
                            url: autoCompleteCompany,
                            dataType: "json",
                            data: {
                                key: ui.item.option.value,
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

                        this._trigger( "select", event, {
                            item: ui.item.option
                        });
                    },
                    autocompletechange: "_removeIfInvalid"
                });
            },

            _createShowAllButton: function() {
                var input = this.input,
                wasOpen = false;

                $( "<a>" )
                .attr( "tabIndex", -1 )
                .attr( "title", "Show All Items" )
                .tooltip()
                .appendTo( this.wrapper )
                .button({
                    icons: {
                        primary: "ui-icon-triangle-1-s"
                    },
                    text: false
                })
                .removeClass( "ui-corner-all" )
                .addClass( "custom-combobox-toggle ui-corner-right" )
                .on( "mousedown", function() {
                    wasOpen = input.autocomplete( "widget" ).is( ":visible" );
                })
                .on( "click", function() {
                    input.trigger( "focus" );

                    // Close if already visible
                    if ( wasOpen ) {
                        return;
                    }
                    // Pass empty string as value to search for, displaying all results
                    input.autocomplete( "search", "" );
                });
            },

            _source: function( request, response ) {
                var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                // var testing = true;
                comboBoxVariable = this;
                var typedText = request.term;
                var options = this.element.children( "option" ).map(function() {
                    var text = $( this ).text();
                    if ( this.value && ( !request.term || matcher.test(text) ) ){
                        $("#add_new_company_button").hide();
                        // $("#new_company_name").text("");
                        return {
                            label: text,
                            value: text,
                            option: this
                        };
                    }
                })
                if (options.length === 0) {
                    $("#comp_new").text(" " + typedText);
                    // $("#add_new_company_button").show();
                    $("#new_company_name").text(typedText);
                    addNewCompany();
                }
                response( options );

            },
            _removeIfInvalid: function( event, ui ) {
                // return;
                var companyFound =false;
                $.each(companies, function (index, company) {
                    if($("#company_new").val() == company.id){
                        companyFound = true;
                    }
                });
                // Selected an item, nothing to do
                if ( ui.item || companyFound) {
                    return;
                }
                // Search for a match (case-insensitive)
                var value = this.input.val(),
                valueLowerCase = value.toLowerCase(),
                valid = false;
                this.element.children( "option" ).each(function() {
                    if ( $( this ).text().toLowerCase() === valueLowerCase ) {
                        this.selected = valid = true;
                        return false;
                    }
                });

                // Found a match, nothing to do
                if ( valid ) {
                    return;
                }
                // $("#add_new_company_button").hide();
                // Remove invalid value
                this.input
                .val( "" )
                .attr( "title", value + " didn't match any item" )
                .tooltip( "open" );
                this.element.val( "" );
                this._delay(function() {
                    this.input.tooltip( "close" ).attr( "title", "" );
                }, 2500 );
                this.input.autocomplete( "instance" ).term = "";
            },

            _destroy: function() {
                this.wrapper.remove();
                this.element.show();
            }
        });

        $( "#company_new" ).combobox();
        $( "#toggle" ).on( "click", function() {
            $( "#company_new" ).toggle();
        });
    });

    function setAutocomplete(autocompleteId,valuetoset) {
        $(autocompleteId).val(valuetoset);
        $(autocompleteId).autocomplete("search", valuetoset);
        var list = $(autocompleteId).autocomplete("widget");
        $(list[0].children[0]).click();
    }
