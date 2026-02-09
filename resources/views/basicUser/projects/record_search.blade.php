@extends('basicUser.projects.search')
@section('body')
    @php
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $mobile =
        preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) ||
        preg_match(
            '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent, 0, 4),
        );
    @endphp

    <style>
        #overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            cursor: pointer;
            display: block;
        }

        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            margin-left: auto;
            margin-right: auto;
            -webkit-animation: spin 2s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
            margin-top: 20%;
        }

        .address_container {
            padding: 7%;
        }

        .job-info-bgcolor {
            background-color: #fff;
            box-shadow: 5px 9px 16px rgba(0, 0, 0, .16);
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        h4,
        h3 {
            color: #1084ff;
            font-weight: 600;
        }

        .api-data {
            color: #323435 !important;
        }

        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
            /* add padding to account for vertical scrollbar */
            padding-right: 20px;
        }

    </style>
    @if (isset($_GET['view']) && $_GET['view'] === 'detailed')
        <span id="stepNumDetailed" data-step="4"></span>
    @else
    @endif
    @if (isset($_GET['edit']))
        <span id="editFlag"></span>
        <form id="editJobDesc" action="#" method="post"
            class="form-horizontal project-form project_details create-project-form form-no-padding create-project-form--edit">
        @else
            <form id="editJobDesc" action="#" method="post"
                class="form-horizontal project-form project_details create-project-form">
    @endif

    <div class="row">
        <div class="container-holder col-lg-6" style="display: block;">
            <div class="create-project-form-bgcolor">
                <div id="overlay">
                    <div class="loader"></div>
                </div>
                <div class="create-project-form-header">
                    <h2>Enter Job Description</h2>
                    @if (isset($_GET['edit']) and $mobile)
                        <span class="mobile-nav--menu" onclick="openNav()" data-target="detailed"><i
                                class="fa fa-ellipsis-v" aria-hidden="true"></i></span>
                    @endif
                </div>
                @if (isset($_GET['edit']))
                    <div class="form-padding-wrapper match-width">
                @endif
                {{ csrf_field() }}
                <div class="row">
                    <div class="address_container col-md-12 col-sm-12">
                        <div style="display:none" class="border-table">
                            <label for="job_name">Job Name</label>
                            <input type="text" name="job_name" value="" class="form-control input-field">
                        </div>
                        <div class="border-table">
                            <label for="job_address">Job Address</label>
                            <input type="text" id="job_address" name="job_address" value=""
                                class="search-construction form-control input-field">
                        </div>
                        <div class="border-table">
                            <label for="job_city">Job City</label>
                            <input type="text" id="job_city" name="job_city" value=""
                                class="search-construction form-control input-field">
                        </div>
                        <div class="border-table">
                            <label for="job_state">Job State</label>
                            <select name="job_state" id="job_state" class="form-control input-field" disabled>
                                <option value="">Select a State</option>
                                @foreach ($states as $state)
                                    <option>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="border-table">
                            <label for="job_zip">Job Zip</label>
                            <input type="text" id="job_zip" name="job_zip" value=""
                                class="search-construction form-control input-field">
                        </div>

                        <div class="border-table">
                            <label for="county">County</label>
                            <input type="text" id="job_county" name="county" value=""
                                class="search-construction form-control input-field">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="job-info-container create-project-form-bgcolor col-lg-6" style="width: 50%; display:none1; height:auto; padding: 7% 5%;"> --}}
        <div class="job-info-container job-info-bgcolor col-lg-6">
            <h3 class="api-address"> Job Address Here </h3> <br>
            <hr style="border: 1px solid #1084ff;"> <br>
            <div class="border-table">
                <h4 for="job_date">
                    Date of Job Listed on Permit
                </h4>
                <label class="api-data api-date">
                    Date of Job Here
                </label>
            </div>
            <br>

            <div class="border-table">
                <h4 for="job_description">Description of job listed on Permit</h4>
                <label class="api-data api-description"> Description of job Here.</label>
            </div>
            <br>

            <div class="border-table">
                <h4 for="job_value">Value of job listed on Permit</h4>
                <label class="api-data api-value"> Value of job Here.</label>
            </div>
            <br>

            <div class="border-table">
                <h4 for="job_industry_contacts">Number of Industry Contacts</h4>
                <label class="api-data api_industry_contacts"> Contacts here.</label>
            </div>
            <div class="border-table">
                <h4 for="job_person_contacts">Number of Personal Contacts</h4>
                <label class="api-data api_person_contacts"> Contacts here.</label>
            </div>
            <br>

            <a href="javascript:void(0)" class="import-job project-create-continue">
                Import Job
            </a>
        </div>
    </div>
    </div>

    </form>

    </div>
    </div>
    </div>
@endsection
@section('script')
    <script
        src="https://maps.google.com/maps/api/js?key=AIzaSyDseZQvDvs27d4W4k_aSXGGVbylo1oF3A4&libraries=places&callback=initAutocomplete"
        type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $("#lat_area").addClass("d-none");
            $("#long_area").addClass("d-none");

            // $(".create-project-form-bgcolor").css({"width":"45%","color":"white"});
            // $(".job-info-container").css({"width":"45%","color":"white", "background":"#rgb(243 243 238)" ,"display":"block"});
        });
    </script>
    {{-- <script>
    google.maps.event.addDomListener(window, 'load', initialize);
    function initialize() {
    var options = {
    componentRestrictions: {country: "US"}
};
var input = document.getElementById('job_address');
var autocomplete = new google.maps.places.Autocomplete(input, options);
autocomplete.addListener('place_changed', function() {
var place = autocomplete.getPlace();

let addressComponents = place.address_components;
let state = "";
let city = "";
let postalCode = "";
let county = "";
addressComponents.forEach((component, i) => {
let typesArray = addressComponents[i].types;
if(Array.isArray(typesArray)){
typesArray.forEach((type, j) => {
if (typesArray[j] == "postal_code") {
postalCode = addressComponents[i].long_name
}
if (typesArray[j] == "locality") {
city = addressComponents[i].long_name
}
if (typesArray[j] == "administrative_area_level_1") {
state = addressComponents[i].short_name
}
if (typesArray[j] == "administrative_area_level_2") {
county = addressComponents[i].long_name
}
});
}
});
// $('#job_address').val(source.siteaddresspartial);
// console.log(source.area.area_name);
$('#job_city').val(city);
$('#job_zip').val(postalCode);
$('#job_county').val(county);
$("#job_state option").filter(function() {
return this.text == state;
}).attr('selected', true);
});
}
</script> --}}
    <script>
        $(document).ready(function() {
            $('#overlay').hide();
            $('html, body').animate({
                scrollTop: $('#progressbar').offset().top
            }, 'slow');

            $(document).on('click', '#save_quit', function(e) {
                e.preventDefault()
                window.location = "{{ route('member.dashboard') }}"
            })

            $(document).on('click', '.mobile-nav-tab', function() {
                let tab = $(this).attr('data-tab')
                $('.mobile-nav--menu').attr('data-target', tab)
            })

            $(document).on('click', '.sidenav', function() {
                $(".sidenav").css('width', '0px');
            })

            // skip buttons
            // 13-aug-2019
            $('.skip-contract-details').on('click', function(event) {
                //event.stopPropagation();
                event.preventDefault();
                swal({
                    title: 'Are you sure you want to skip this?',
                    // text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    // buttonsStyling: false
                }).then(function() {
                    window.location.href = '/member/project/contract/view?project_id=' +
                        project_id + '&edit=true';
                })
            });

        })

        function openNav(e) {
            let menu = $('.mobile-nav--menu').attr('data-target')
            if (menu == 'express') {
                $('#mobileNav').css('width', '100%');
            } else {
                $('#mobileNavDetailed').css('width', '100%')
            }
        }

        function closeNav() {
            $(".sidenav").css('width', '0px');
        }
    </script>

    <script>
        let token = '{{ csrf_token() }}'
        let baseUrl = "{{ env('ASSET_URL') }}"

        var searchProjectConstructionMonitor = "{{ route('member.search.construction.monitor') }}";
        var createProjectConstructionMonitor = "{{ route('member.create.construction.monitor') }}";

        var availableTags = [];
        var projects = [];
        var selected_project;

        $(".search-construction").autocomplete({
            minLength: 0,
            select: function(event, ui) {
                $(this).autocomplete("close");
                $(this).focus();
                selected_project = projects[ui.item.idx];
                // $(".create-project-form-bgcolor").css({"width":"50%","color":"white"});
                // $(".job-info-container").css({"width":"50%","color":"white", "background":"#rgb(243 243 238)" ,"display":"block"});
                // $(".container-holder").css({"display":"flex"});

                selected_project = projects[ui.item.idx];
                if (selected_project != undefined) {
                    setTimeout(() => {
                        populateData(selected_project, 'select');
                    }, 500);
                }
            },
            focus: function(event, ui) {
                $(this).focus();
                // $(".create-project-form-bgcolor").css({"width":"50%","color":"white"});
                $(".ui-menu").css({
                    "width": "30%"
                });
                // $(".job-info-container").css({"width":"50%","color":"white", "background":"#rgb(243 243 238)" ,"display":"block"});
                // $(".container-holder").css({"display":"flex"});

                selected_project = projects[ui.item.idx];
                if (selected_project != undefined) {
                    populateData(selected_project, 'focus')
                }

            }
            /* other options */
        }).on("focus", function() {
            $(this).autocomplete("search", "");
        });

        function populateData(selected_project, action) {
            var source = selected_project._source;
            var d = new Date(source.permitdate);

            var day = d.getDate();
            var month = d.getMonth() + 1;

            if (month < 10) month = '0' + month;
            if (day < 10) day = '0' + day;

            var companyLinks = source.companylinks;
            var companyLinksCount = 0;
            companyLinks.forEach((item, i) => {
                if (item.contact != null) {
                    companyLinksCount++;
                }
            });

            var personContactCount = source.personlinks.length;
            $('.api-date').text(day + '/' + month + '/' + d.getFullYear());
            $('.api-address').text(source.siteaddress);
            $('.api-description').text(source.description);
            $('.api-value').text(source.valuation.toLocaleString());
            $('.api_industry_contacts').text(companyLinksCount);
            $('.api_person_contacts').text(personContactCount);
            if (action == 'select') {
                $('#job_address').val(source.siteaddresspartial);
                $('#job_city').val(source.city);
                $('#job_zip').val(source.zip);
                $('#job_county').val(source.county.county_name);
                console.log(source.area.area_name);
                $("#job_state option").filter(function() {
                    return this.text == source.area.area_name;
                }).attr('selected', true);
            }
        }

        $(document).on('click', '.import-job', function(e) {
            var source = selected_project._source;
            $.ajax({
                type: "POST",
                url: createProjectConstructionMonitor,
                data: {
                    data: selected_project,
                    project_id: project_id,
                    // status: projectData,
                    _token: token
                },
                success: function(data) {
                    if (data.status) {
                        window.location
                    .reload(); //= "/member/create/project?project_id=" + data.data +"&edit=true";
                    }
                }
            });
        });
        // $(document).on('focus', '#job_zip', function(e){
        //     console.log(">...>")
        //     $("#job_address").val("ABC");
        // });

        var timestamp = Date.now();
        $(document).on('keyup', '.search-construction', function(e) {
            e.preventDefault();
            var inp = String.fromCharCode(e.keyCode);
            var char = false;
            if (/[a-zA-Z0-9-_ ]/.test(inp)) {
                char = true;
            }

            var url = "https://api.constructionmonitor.com/v2/permits/?term=";
            var term = $(this).val();
            $('#project_search_button').prop('disabled', true);

            if (term == '') {
                $('#overlay').hide();
            }

            if ((term.length >= 4 && char) || availableTags.length > 0) {

                if (availableTags.length == 0) {
                    $('#overlay').show();
                }
                var component = $(this);
                $('#overlay').hide();

                getData(term, component)
            }
        })


        function getData(term, component) {
            $.ajax({
                url: searchProjectConstructionMonitor,
                method: "GET",
                mode: "cors",
                data: {
                    search_term: term,
                    _token: token
                },
                success: function(data) {
                    // $(".create-project-form-bgcolor").css({"width":"50%","color":"white"});
                    // $(".ui-menu").css({"width":"50%"});
                    // $(".job-info-container").css({"width":"50%","color":"white", "background":"#rgb(243 243 238)" ,"display":"block"});
                    // $(".container-holder").css({"display":"flex"});
                    $('#overlay').hide();
                    $('#project_search_button').prop('disabled', false);
                    var projectsResponse = data.data;
                    availableTags = [];

                    $.each(projectsResponse, function(key, value) {
                        availableTags.push({
                            label: value._source.siteaddresspartial,
                            idx: key
                        });
                    });

                    if (availableTags.length > 0) {
                        projects = data.data;
                        $('.search-construction').autocomplete({
                            source: availableTags
                        });
                    }
                    if (component != "") {
                        component.focus();
                    }
                    $('#overlay').hide();

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#overlay').hide();
                    $('#project_search_button').prop('disabled', false);
                }
            });
        }
    </script>
    <script src="{{ env('ASSET_URL') }}/js/modals/contacts/job_info.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/job_info_dates.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/createProject.js"></script>
@endsection
