@extends('basicUser.layout.main')

@section('title','New Claim')

@section('content')


<section class="bodypart">
    <div class="container">
      <form method="post" id="claim_data_sheet" action="{{ route('submit.new.claim') }}" enctype="multipart/form-data" >
 {{ csrf_field() }}
  <input type="hidden" name="step" value="2">
  <input type="hidden" name="id" value="{{$id or ''}}">
        <div class="row">
            <div class="col-md-10 col-sm-12 col-md-offset-1">
                <div class="center-part">
                <h1><a href="#"><span>i</span>Company™ Claim Data</a></h1>
                </div><br>
                <div class="project-name"><input type="text" name ="project_name" value="{{$project_name or ''}}"></div>
                <div class="time-nav">
    <div class="header-progress-container header-progress-new">
        <ol class="header-progress-list">

            <li class="header-progress-item done first"> <a href="{{route('admin.new.claim_step1')}}">Project Details</a></li>
            <li class="header-progress-item done second"> <a href="{{route('admin.new.claim_step2')}}">&nbsp;</a></li>
            <li class="header-progress-item todo third"> <a href="{{route('admin.new.claim_step3')}}">&nbsp;</a></li>
            <li class="header-progress-item todo forth "> <a href="{{route('admin.new.claim_step4')}}">&nbsp;</a></li>
            <li class="header-progress-item todo done"> Submit</li>

        </ol>
    </div>
    </div>
    <br>
    <div class="tab-content-body">


{{-- <div class="step-two" >
        <div class="row">
            <div class="col-md-8 col-sm-8">
                <div class="border-table shadow left-box">
                    <h2>Liability Limitation</h2>
                    <p>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                    </p>
                    <div class="checkbox-area">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">By checking this box I agree to these terms</label>
                    </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="border-table shadow right-box">
                    <div class="center-part">
                        <h2>PROJECT STATE</h2>
                        <a href="#">CHOOSE ONE</a>
                    </div>
                </div>
                <div class="tab-right center-part">
                    
                            <a href="#"><img src="{{ env('ASSET_URL') }}/images/next-step.jpg" alt="img"></a>
                       
                </div>
            </div>
        </div>
    </div> --}}    
    
<div class="step-three">
   <div class="row">
      <div class="col-md-8 col-sm-8">
         <div class="row">
            <div class="col-md-6 col-sm-6">
               <div class="border-table shadow box-height left-box center-part">
                  <h2>DESCRIPTION OF YOUR<br> PRODUCT/SERVICE PROVIDED:</h2>
                  <div class="row">
                     <div class="col-md-6 col-xs-12 spec-col">
                        <div class="radio-style">
                           <ul>
                              <li>
                                 <input type="radio" id="f-option" name="status" value="Materials">
                                 <label for="f-option">Materials Only</label>
                                 <div class="check"></div>
                              </li>
                              <li>
                                 <input type="radio" id="s-option" name="status" value="Labor">
                                 <label for="s-option">Labor Only</label>
                                 <div class="check">
                                    <div class="inside"></div>
                                 </div>
                              </li>
                              <li>
                                 <input type="radio" id="t-option" name="status" value="Materials & Labor">
                                 <label for="t-option">Materials & Labor</label>
                                 <div class="check">
                                    <div class="inside"></div>
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                     <div class="col-md-6 col-xs-12 border-left">
                        <p>
                           Is your product custom manufactured 
                           for the projects?
                        </p>
                        <div class="radio-style radio-style-spc">
                           <ul>
                              <li>
                                 <input type="radio" id="f-option1" name="custom" value="Yes">
                                 <label for="f-option1">Yes</label>
                                 <div class="check"></div>
                              </li>
                              <li>
                                 <input type="radio" id="s-option1" name="custom" value="No">
                                 <label for="s-option1">No</label>
                                 <div class="check">
                                    <div class="inside"></div>
                                 </div>
                              </li>
                              <li>
                                 <div class="upload-btn-wrapper contract">
                                    <button class="btn">If Yes Contract<br> Dates</button>
                                    <input type="file" name="myfile_date" id="date_file" onchange="javascript:dateFile()"/>
                                     <div id="fileList4"></div>
                                 </div>
                                
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-6 col-sm-6">
               <div class="border-table shadow box-height left-box center-part">
                  <h2>PAYMENT</h2>
                  <div class="row">
                     <div class="col-md-6 col-xs-12 spec-col">
                        <p>Have you issued a preliminary notice?</p>
                        <div class="radio-style radio-style-spc">
                           <ul>
                              <li>
                                 <input type="radio" id="f-option2" name="preliminary" value="Yes">
                                 <label for="f-option2">Yes</label>
                                 <div class="check"></div>
                              </li>
                              <li>
                                 <input type="radio" id="s-option2" name="preliminary" value="No">
                                 <label for="s-option2">No</label>
                                 <div class="check">
                                    <div class="inside"></div>
                                 </div>
                              </li>
                              <li>
                                 <div class="upload-btn-wrapper preliminary">
                                    <button class="btn">If Yes,<br> Upload Copy</button>
                                    <input type="file" name="myfile_preliminary" id="file" onchange="javascript:prilimitary()" />

                                 </div>
                                 <div id="fileList"></div>
                              </li>
                           </ul>
                        </div>
                     </div>
                     <div class="col-md-6 col-xs-12 spec-col border-left">
                        <p>Have you issued a lien waiver?</p>
                        <div class="radio-style radio-style-spc">
                           <ul>
                              <li>
                                 <input type="radio" id="f-option3" name="lien">
                                 <label for="f-option3">Yes</label>
                                 <div class="check"></div>
                              </li>
                              <li>
                                 <input type="radio" id="s-option3" name="lien">
                                 <label for="s-option3">No</label>
                                 <div class="check">
                                    <div class="inside"></div>
                                 </div>
                              </li>
                              <li>
                                 <div class="upload-btn-wrapper lien">
                                    <button class="btn">If Yes,<br> Upload Copy</button>
                                    <input type="file" name="myfile_lien" id="file3" onchange="javascript:lienNotice()"/>
                                 </div>
                                 
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-8">
               <div class="row bottom-upload">
                  <div class="col-sm-3">
                     <div class="bottom-upload">
                        <div class="upload-btn-wrapper">
                           <button class="btn">Upload</button>
                           <input name="myfile" type="file" id="file2" onchange="javascript:product()">
                           
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-9">
                     <div class="file-msg">
                        <p>Product Literature</p>
                        <p>Shop Drawing</p>
                       <!--  <p>Notice of Completion</p>
                        <p>Notice of Acceptance</p> -->
                     </div>
                     <div id="fileList2"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-4 col-sm-4">
         <div class="tab-right">
            <ul>
               <li><a href="{{ route('admin.new.claim_step3') }}">Skip</a></li>
               <li>
                  <!-- <a href="javascript:void(0)" id="activate-step-3">
                  <img src="{{ env('ASSET_URL') }}/images/next-step.jpg" alt="img">
                  </a> -->

                   <button type="submit" class="next-btn">
                  <!-- <img src="{{ env('ASSET_URL') }}/images/next-step.jpg" alt="img"> -->
                  </button>
               </li>
            </ul>
         {{--   <div class="sq">
               <p class="align-right">
                  <a href="#" id="save_quit">
                  Save &amp; Quit
                  </a>
               </p>
            </div> --}}
         </div>
      </div>
   </div>
</div>


 








    </div>




            </div>
        </div>    
    </div>    
</section>



   <!--  <section class="bodypart">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-sm-12 col-md-offset-1">
                    <div class="row main-page">
                        <form method="post" id="claim_data_sheet" action="{{ route('submit.new.claim') }}">
                            <div class="col-md-12 col-sm-12">
                            <div class="first-step border-table">
                                <div class="center-part">
                                    <h1><a href="#">new claim data sheet</a></h1>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="border-table">
                                            <div class="form-group">
                                                <label>Your Company Name:</label>
                                                <input type="text" name="company_name" class="form-control"
                                                       placeholder="Your company name">
                                            </div>
                                            <div class="form-group">
                                                <label>Contact Name:</label>
                                                <input type="text" name="contact_name" class="form-control"
                                                       placeholder="Your name">
                                            </div>
                                            <div class="form-group">
                                                <label>Address:</label>
                                                <input type="text" name="address" class="form-control"
                                                       placeholder="Your address">
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="row">
                                                            <div class="col-md-3 col-sm-3"><label>City</label></div>
                                                            <div class="col-md-9 col-sm-9">
                                                                <input type="text" name="city" class="form-control"
                                                                       placeholder="City">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="row">
                                                            <div class="col-md-3 col-sm-3"><label>State </label>
                                                            </div>
                                                            <div class="col-md-9 col-sm-9">
                                                                <input type="text" name="state" class="form-control"
                                                                       placeholder="State">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="row">
                                                            <div class="col-md-3 col-sm-3"><label>Zip </label></div>
                                                            <div class="col-md-9 col-sm-9">
                                                                <input type="text" name="zip" class="form-control"
                                                                       placeholder="Zip">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="row">
                                                            <div class="col-md-3 col-sm-3"><label>Phone </label>
                                                            </div>
                                                            <div class="col-md-9 col-sm-9">
                                                                <input type="text" name="phone" class="form-control"
                                                                       placeholder="Phone">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="row">
                                                            <div class="col-md-3 col-sm-3"><label>FAX </label></div>
                                                            <div class="col-md-9 col-sm-9">
                                                                <input type="text" name="fax" class="form-control"
                                                                       placeholder="FAX">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="row">
                                                            <div class="col-md-3 col-sm-3"><label>Email </label>
                                                            </div>
                                                            <div class="col-md-9 col-sm-9">
                                                                <input type="text" name="email" class="form-control"
                                                                       placeholder="Email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-table">
                                    <div class="form-group list-style">
                                        <label>CHECK TYPE OF CLAIM:</label>
                                        <ul>
                                            <li><input name="claim_type" value="preliminary_notice" type="radio">
                                                Preliminary Notice
                                            </li>
                                            <li><input name="claim_type" value="lien_claim" type="radio"> Lien Claim
                                            </li>
                                            <li><input name="claim_type" value="bond_claim" type="radio"> Bond Claim
                                            </li>
                                            <li><input name="claim_type" value="collection" type="radio"> Collection
                                            </li>
                                            <li><input name="claim_type" value="precipitation" type="radio">
                                                Precipitation
                                            </li>
                                            <li><input name="claim_type" value="litigation" type="radio"> Litigation
                                            </li>
                                            <li><input name="claim_type" value="bankruptcy" type="radio"> Bankruptcy
                                            </li>
                                            <li><input name="claim_type" value="other" type="radio"> Other <input
                                                        name="other_claim" id="other_claim" type="text"
                                                        style="display: none;"></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="border-table">
                                    <label>YOUR CONTRACT INFORMATION:</label>
                                    <div class="form-group">
                                        <label>Contract Date</label>
                                        <input name="contract_date" type="text" class="form-control1 date">
                                    </div>
                                    <hr>
                                    <div class="align-center">
                                        <div class="form-group">
                                            <label>Base contract amount</label>
                                            $<input name="base_amount" type="text" id="base_amount" value="0"
                                                    class="form-control1">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <label>Do you have a</label>
                                        <table class="table no-margin no-border">
                                            <tr>
                                                <td>
                                                    <input name="contract_type" type="radio"> written (Fax copies to
                                                    NLB) or
                                                </td>
                                                <td>
                                                    + Value of extras or changes
                                                </td>
                                                <td>
                                                    $ <input name="extra_amount" type="text" id="extra_amount"
                                                             value="0" class="form-control1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;">
                                                    <input name="contract_type" type="radio"> verbal contract?
                                                    (Fax copies of P.O.'s, contracts, invoices, delivery tickets,
                                                    change orders, and billing statements,etc. to NLB)
                                                </td>
                                                <td colspan="2">
                                                    <table class="table no-border no-margin">
                                                        <tr>
                                                            <td>= Revised Contract subtotal</td>
                                                            <td>$ <input name="contact_total" type="text"
                                                                         id="contact_total" class="form-control1">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>- Less Amount Paid to date & credits</td>
                                                            <td>$ <input name="payment" type="text" id="payment"
                                                                         value="0" class="form-control1"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>= Claim Amount Total</td>
                                                            <td>$ <input name="claim_amount" type="text"
                                                                         id="claim_amount" class="form-control1">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    Does all extra work relate to original contract?
                                                    <div class="form-group list-style">
                                                        <ul class="inline-block">
                                                            <li><input name="extra_work" type="radio"> Yes</li>
                                                            <li><input name="extra_work" type="radio"> No</li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="border-table">
                                    <label>CIRCLE PROJECT STATE:</label>
                                    <select name="project_state" class="form-control1">
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="text-align:center">
                                    <a href="#" id="first_step_button">
                                        <img src="{{ env('ASSET_URL') }}/images/next-step.jpg" alt="img">
                                    </a>
                                </div>
                                <!--
                                   <div class="border-table">
                                       <label>DESCRIPTION OF YOUR PRODUCT/SERVICES PROVIDED</label> ( fax to NLB your product literature)
                                   <div class="form-group list-style">
                                   <ul>
                                       <li>Did you provide?</li>
                                       <li><input type="radio"> Materials only</li>
                                       <li><input type="radio"> Labor only</li>
                                       <li><input type="radio">  Materials & Labor
                                   Is your product custom manufactured for the project? </li>
                                       <li><input type="radio"> Yes, first date of fabrication (includes shop drawings) <input type="text" class="form-control1"></li>
                                       <li><input type="radio"> No</li>
                                   </ul>

                                   </div>
                                   </div>
                                   -->


@endsection

@section('script')
    <script type="text/javascript">
  
        $(document).ready(function () {

           
           $('.contract').hide();
            $('.preliminary').hide();
            $('.lien').hide();
           //    $('.third-step').hide();
      
            var base_amount = 0;
            var extra_amount = 0;
            var payment = 0;
            var total = 0;
            var claim_total = 0;
            totalAmount();

            $('#base_amount,#extra_amount,#payment').on('change', function () {
                totalAmount();
            });

            function totalAmount() {
                base_amount = $('#base_amount').val();
                extra_amount = $('#extra_amount').val();
                payment = $('#payment').val();
                total = parseFloat(base_amount) + parseFloat(extra_amount);
                claim_total = parseFloat(total) - parseFloat(payment);
                $('#contact_total').val(parseFloat(total));
                $('#claim_amount').val(parseFloat(claim_total));
            }

            $('#f-option1').on('click', function () {
                $('.contract').show();
                
            });
            $('#s-option1').on('click', function () {
                $('.contract').hide();
                
            });
            $('#f-option2').on('click', function () {
                $('.preliminary').show();
                
            });
            $('#s-option2').on('click', function () {
                $('.preliminary').hide();
                
            });
            $('#f-option3').on('click', function () {
                $('.lien').show();
                
            });
            $('#s-option3').on('click', function () {
                $('.lien').hide();
                
            });
            $('#first_back_button').on('click', function () {
                $('.first-step').show();
                $('.second-step').hide();
                $('.third-step').hide();
            });
            $('#second_step_button').on('click', function () {
                $('.first-step').hide();
                $('.second-step').hide();
                $('.third-step').show();
            });
            $('#second_back_button').on('click', function () {
                $('.first-step').hide();
                $('.second-step').show();
                $('.third-step').hide();
            });
            $('input[name="claim_type"]').on('click', function () {
                if ($(this).val() == 'other') {
                    $('#other_claim').show();
                }
                else {
                    $('#other_claim').hide();
                }
            });
            $('input[name="whole_completed"]').on('click', function () {
                if ($(this).val() == 'yes') {
                    $('#whole_completed_date').show();
                }
                else {
                    $('#whole_completed_date').hide();
                }
            });
            $('input[name="payment_bound"]').on('click', function () {
                if ($(this).val() == 'yes') {
                    $('#payment_bound_div').show();
                }
                else {
                    $('#payment_bound_div').hide();
                }
            });
            $('input[name="project_status"]').on('click', function () {
                if ($(this).val() == 'other') {
                    $('#project_status_other').show();
                }
                else {
                    $('#project_status_other').hide();
                }
            });
            $('input[name="other"]').on('click', function () {
                if ($('input[name="other"]').prop('checked')) {
                    $('#check_list_other').show();
                }
                else {
                    $('#check_list_other').hide();
                }
            });
            $('input[name="web_search"]').on('click', function () {
                if ($(this).val() == 'other') {
                    $('#web_search_other').show();
                }
                else {
                    $('#web_search_other').hide();
                }
            });
           // window.setInterval(saveForm, 10000);
        });
        function saveForm() {
            var formData = $('#claim_data_sheet').serialize();
            $.ajax({
                type: "POST",
                url: "{{ route('save.new.claim') }}",
                data: formData,
                success: function(data) {
                    $('#claim_id').val(data.data);
                }
            });
        }

    </script>
    <script type="text/javascript">
        $("#file-upload").change(function(){
  $("#file-name").text(this.files[0].name);
});
    </script>

<script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  $( function() {
    $( "#datepicker1" ).datepicker();
  } );
  dateFile = function() {
  var input = document.getElementById('date_file');
  var output = document.getElementById('fileList4');
  //alert('dfssadf');

  //output.innerHTML = '<ul>';
  for (var i = 0; i < input.files.length; ++i) {
    alert(input.files.item(i).name+' file attached') ;
  }

}
prilimitary = function() {
  var input = document.getElementById('file');
  var output = document.getElementById('fileList');

  // output.innerHTML = '<ul>';
  // for (var i = 0; i < input.files.length; ++i) {
  //   output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
  // }
  // output.innerHTML += '</ul>';
  //alert(input.files.item(i).name+' file is attched')
  for (var i = 0; i < input.files.length; ++i) {
    alert(input.files.item(i).name+' file attached') ;
  }
}
lienNotice = function() {
  var input = document.getElementById('file3');
  var output = document.getElementById('fileList3');

  for (var i = 0; i < input.files.length; ++i) {
    alert(input.files.item(i).name+' file attached') ;
  }
}
product = function() {
  var input = document.getElementById('file2');
  var output = document.getElementById('fileList2');

  //output.innerHTML = '<ul>';
  for (var i = 0; i < input.files.length; ++i) {
    output.innerHTML += input.files.item(i).name+ ' file attached';
  }
  //output.innerHTML += '</ul>';
}

</script>
@endsection