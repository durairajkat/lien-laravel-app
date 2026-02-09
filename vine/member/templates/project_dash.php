    <link rel="stylesheet" href="/vine/member/css/main.css?r=<?php echo time(); ?>"> <!-- Prevent caching during development -->
    <!-- Font Awesome -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/admin_assets/bower_components/font-awesome/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">

    <!--    <img src="nav.png" class="nav-img"/>-->

    <form id="uploadForm" enctype="multipart/form-data" method="post">
        <input type="file" id="uploadFile_hidden" data-token="<?= csrf_token() ?>" style="display: none">
    </form>

    <div class="container container-wide vine-container">
        <div class="row row-first">
            <div class="col col-1 col--empty">
            </div>
            <div class="col col-10 project-box">
                <div class="project-header">
                    <h2 class="header" id="job-header-title">Project Dashboard</h2>
                    <div class="container vine-container">
                        <div class="row row-first" id="header-panel">
                            <div class="col col-7">
                                <!--                            <h2>--><?php $user->details ? $user->details->getCompany->company : '' ?>
                                <!--</h2>-->
                                <!--                            <h3>John Smith</h3>-->
                                <!--                            <h4>--><?php $user->details ? $user->details->address : '' ?>
                                <!--</h4>-->
                                <!--                            <h4>--><?php $user->details ? $user->details->city : '' ?>
                                <!--,-->
                                <!--                                --><?php
                                                                        //                                foreach($states as $state) {
                                                                        //                                    if ($state->id === $user->details->state_id):
                                                                        //                                        echo $user->details && $user->details->state_id == $state->id ? $state->name : '';
                                                                        //                                    endif;
                                                                        //                                }
                                                                        //
                                                                        ?>
                                <!--                            </h4>-->
                                <!--                            <h4>--><?php $user->details ? $user->details->phone : '' ?>
                                <!--</h4>-->




                                <div class="item-wrapper" id="job-header-results">
                                    <h2 class="result-company"></h2>
                                    <h3 class="result-name"></h3>
                                    <h4 class="result-address"></h4>
                                    <h4 class="result-citystate"></h4>
                                    <h4 class="result-phone"></h4>
                                </div>
                            </div>
                            <div class="col col-4">
                                <h2><?php
                                    foreach ($types as $type) {
                                        if (
                                            Session::has('projectType') && Session::get('projectType') == $type->id || ((isset($project) && $project != '' && $project->project_type_id == $type->id))
                                        ) {
                                            echo $type->project_type;
                                        }
                                    }
                                    ?></h2>
                                <h3>
                                    <?php
                                    $city = ($project ? $project->city : '');

                                    foreach ($states as $state) {
                                        if ($state->id === $project->state_id) {
                                            $st = $user->details && $project->state_id == $state->id ? $state->name : '';

                                            if (!empty($city) && !empty($st)) {
                                                echo "$city, $st";
                                            } else {

                                                echo $city;
                                                echo $st;
                                            }
                                        }
                                    }
                                    ?>
                                </h3>
                                <button class="button send-claim" data-project-id="<?= $project->id ?>">Send Claim</button>
                            </div>
                        </div>






                        <div class="col col-12 col-last">
                            @include('basicUser.partials.multi-step-form')
                        </div>
                    </div>

                </div>





                <fieldset>
                    <div class="tab-content active-tab" id="dates">
                        <div class="row row-first">
                            <div class="btn-skip">
                                <button type="button" onclick="progress('dates','contract')" class="next action-button button button-small skip">Skip</button>
                                <button type="button" onclick="progress('dates','contract')" class="next action-button button button-small">Continue</button>
                            </div>
                            <div class="col col-12">
                                <div class="item">
                                    <h2 class="header header-item">Job Dates</h2>

                                    <div class="item-wrapper">
                                        <table id="date-results">
                                            <tr id="date-template">
                                                <td class="result-name"></td>
                                                <td class="result-value"></td>
                                            </tr>
                                        </table>
                                        <button class="button button-small" data-modal-id="modal-edit-job-dates">Edit Job Dates</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--div class="row">
                            <div class="col col-12">
                                <div class="item">
                                    <h2 class="header header-item">Job Deadlines</h2>
                                    <div class="item-wrapper">
                                        <table id="deadline-results">
                                            <tr id="deadline-template">
                                                <td class="result-name"></td>
                                                <td class="result-desc"></td>
                                                <td class="bold">Complete by: <span class="result-date not-bold"></span></td>
                                                <td class="bold">Days left: <span class="result-days not-bold"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div-->
                    <!--  <button type="button" class="action-button previous_button">Back</button> -->


                    <!-- <button class="button back" data-modal-id="modal-back2">Back</button>
                    <button class="button next">Continue</button>
                    </div><!-- End Dates Tab-->
                </fieldset>

                <fieldset>
                    <div class="tab-content" id="contract">
                        <div class="row row-first">

                            <div class="btn-skip">
                                <button type="button" onclick="progress('contract','contacts')" class="next action-button button button-small skip">Skip</button>
                                <button type="button" onclick="progress('contract','contacts')" class="next action-button button button-small">Continue</button>
                            </div>

                            <div class="col col-8">
                                <div class="item">
                                    <h2 class="header header-item">Contract Details</h2>
                                    <div class="item-wrapper">
                                        <table id="contract-results">
                                            <tr id="contract-template">
                                                <td class="result-name"></td>
                                                <td class="result-value"></td>
                                            </tr>
                                        </table>
                                        <button class="button button-small" data-modal-id="modal-edit-job-contract">Edit Contract</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="item">
                                    <h2 class="header header-item">Contract Overview</h2>
                                    <div class="item-wrapper">
                                        <table class="contract-overview-results">
                                            <tr class="contract-overview-template">
                                                <td class="result-name"></td>
                                                <td class="result-value"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <button class="button back" data-modal-id="modal-back3">Back</button> -->
                        <!--button type="button" class="next action-button button button-small">Continue</button-->
                        <div class="btn-skip">
                            <button type="button" onclick="progress('contract','contacts')" class="next action-button button button-small skip">Skip</button>
                            <button type="button" onclick="progress('contract','contacts')" class="next action-button button button-small">Continue</button>
                        </div>
                        <!-- <button class="button next">Continue</button> -->
                    </div><!-- End Contract Tab-->
                </fieldset>

                <fieldset>
                    <div class="tab-content" id="contacts">
                        <div class="row row-first">
                            <div class="col col-12">
                                <div class="item">

                                    <div id="contact-search-result-wrapper">
                                        <table id="contact-search-results">
                                            <tr id="contact-search-template">
                                                <td class="result-name"></td>
                                                <td class="result-role"></td>
                                                <td class="result-company"></td>
                                                <td class="result-actions">
                                                    <a href="#" class="result-assign">Assign to project</a>
                                                </td>
                                            </tr>
                                        </table>
                                        <div id="contact-add-button">
                                            <button class="button button-small" data-modal-id="modal-add-contact">Add Contact</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="contact-results">
                            <div class="col col-4" id="contact-template">
                                <div class="item">
                                    <h2 class="header header-item result-name"></h2>
                                    <div class="item-wrapper">
                                        <table>
                                            <tr>
                                                <td>Role:</td>
                                                <td class="result-role"></td>
                                            </tr>
                                            <tr>
                                                <td>Company:</td>
                                                <td class="result-company"></td>
                                            </tr>
                                            <tr>
                                                <td>Address:</td>
                                                <td class="result-addr"></td>
                                            </tr>
                                            <tr>
                                                <td>Phone Number:</td>
                                                <td class="result-phone"></td>
                                            </tr>
                                        </table>
                                        <a href="#" class="contact-link result-edit button-edit" data-modal-id="modal-edit-contact">Edit</a>
                                        <a href="#" class="contact-link result-view button-view" data-modal-id="modal-view-contact">View</a>
                                        <a href="#" class="contact-link result-remove">Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-skip">
                            <button type="button" onclick="progress('contacts','tasks')" class="next action-button button button-small skip">Skip</button>
                            <button type="button" onclick="progress('contacts','tasks')" class="next action-button button button-small">Continue</button>
                        </div>
                    </div><!-- End Contacts Tab -->

                </fieldset>


                <fieldset>
                    <div class="tab-content" id="tasks">
                        <div class="row row-first">
                            <div class="btn-skip">
                                <button type="button" onclick="progress('tasks','documents')" class="next action-button button button-small skip">Skip</button>
                                <button type="button" onclick="progress('tasks','documents')" class="next action-button button button-small">Continue</button>
                            </div>
                            <div class="col col-12">

                                <div class="item">
                                    <h2 class="header header-item">Job Tasks</h2>
                                    <div class="item-wrapper">
                                        <table class="no-bold-rows" id="task-results">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Due Date</th>
                                                    <th>Date Completed</th>
                                                    <th>Comments</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tr id="task-template">
                                                <td class="result-name"></td>
                                                <td class="result-date"></td>
                                                <td class="result-completed"></td>
                                                <td class="result-comments"></td>
                                                <td>
                                                    <a href="#" class="button-edit" data-modal-id="modal-edit-job-task">Edit</a>
                                                    <a href="#" class="result-delete">Delete</a>
                                                </td>
                                            </tr>
                                        </table>

                                        <button class="button button-small button-edit" data-modal-id="modal-add-job-task">Add Task</button>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--button type="button" class="next action-button button button-small">Continue</button-->
                        <div class="btn-skip">
                            <button type="button" onclick="progress('tasks','documents')" class="next action-button button button-small skip">Skip</button>
                            <button type="button" onclick="progress('tasks','documents')" class="next action-button button button-small">Continue</button>
                        </div>
                    </div><!-- End Tasks Tab-->
                    </form>

                </fieldset>
                <fieldset>

                    <div class="tab-content" id="documents">
                        <div class="row row-first">
                            <div class="btn-skip">
                                <button type="button" onclick="progress('documents','deadlines')" class="next action-button button button-small skip">Skip</button>
                                <button type="button" onclick="progress('documents','deadlines')" class="next action-button button button-small">Continue</button>
                            </div>
                            <div class="col col-12">
                                <div class="item">
                                    <h2 class="header header-item">Project Documents</h2>
                                    <div id="myitem">
                                        <table class="no-bold-rows">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>File Name</th>
                                                    <th>Date</th>
                                                    <!-- <th>Due Date</th>
                                                <th>Date Completed</th>
                                                <th>Comments</th> -->
                                                    <th>Notes</th>
                                                    <th>User Action</th>
                                                </tr>
                                            </thead>

                                            <?php
                                            $s = 0;
                                            foreach ($documents as $doc) { ?>
                                                <tr>
                                                    <td><?php echo $doc->title; ?></td>
                                                    <td><a target="_blank" href="<?php echo 'https://www.nlb711.slysandbox.com/storage/tmp/uploads/' . $doc->filename . ''; ?>"><?php echo $doc->filename; ?></a>

                                                    </td>
                                                    <td><?php echo $doc->date; ?></td>
                                                    <td><?php echo $doc->notes; ?></td>
                                                    <td><a href="<?php echo '/member/project/delete/' . $doc->id . '/' . $doc->project_id . ''; ?>">DELETE</a></td>

                                                </tr> <?php
                                                        $s++;
                                                    } ?>

                                        </table>
                                        <br><br>

                                    </div>
                                </div>
                                <div class="item">

                                    <div id="myitem">
                                        <form action="https://www.nlb711.slysandbox.com/member/project/save" id="dragandrop" method="POST" enctype="multipart/form-data">
                                            <?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

                                            ?>
                                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                            <input type="hidden" name="project_id" value="<?php echo $_GET['project_id']; ?>">
                                            <input type="hidden" name="return_url" value="<?php echo $actual_link . ''; ?>">
                                            <div class="form-group">
                                                <label for="name">File Name</label>
                                                <input type="text" name="name" id="name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="note">Note</label>
                                                <textarea rows="4" cols="50" name="note" class="form-control"></textarea>
                                            </div>
                                            <div class="form-control" id="dropbox">
                                                <label for="document">Documents</label>
                                                <div class="needsclick dropzone" id="document-dropzone">
                                                </div>
                                            </div>
                                            <div class="clearfix"><br></div>
                                            <div class="form-group">
                                                <input class="action-button button button-small" type="submit" value="Save">
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <!--button type="button" class="next action-button button button-small">Continue</button-->
                            <div class="btn-skip">
                                <button type="button" onclick="progress('documents','deadlines')" class="next action-button button button-small skip">Skip</button>
                                <button type="button" onclick="progress('documents','deadlines')" class="next action-button button button-small">Continue</button>
                            </div>
                        </div>
                    </div>

                    <!-- End Tasks Tab-->
                </fieldset>
                <fieldset>
                    <div class="tab-content" id="deadlines">
                        <div class="row">
                            <div class="btn-skip">
                                <button type="button" onclick="progress('deadline','summary')" class="next action-button button button-small skip">Skip</button>
                                <button type="button" onclick="progress('deadline','summary')" class="next action-button button button-small">Continue</button>
                            </div>
                            <div class="col col-12">
                                <div class="item">
                                    <h2 class="header header-item">Job Deadlines</h2>
                                    <div class="item-wrapper">
                                        <table id="deadline-results">
                                            <tr id="deadline-template">
                                                <td class="result-name"></td>
                                                <td class="result-desc"></td>
                                                <td class="bold">Complete by: <span class="result-date not-bold"></span>
                                                    <p class="deadline_message"><b>WARNING: These are estimated job deadlines**</b></p>
                                                </td>
                                                <td class="bold">Days left: <span class="result-days not-bold"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <p class="deadline_message"><b>WARNING: These are estimated job deadlines**</b></p> -->
                        <div class="btn-skip">
                            <button type="button" onclick="progress('deadline','summary')" class="next action-button button button-small skip">Skip</button>
                            <button type="button" onclick="progress('deadline','summary')" class="next action-button button button-small">Continue</button>
                        </div>


                        <!--  <button type="button" class="action-button previous_button">Back</button> -->

                    </div>

                    <!-- End Tasks Tab-->
                </fieldset>

                <fieldset>
                    <div class="tab-content " id="summary">
                        <div class="row row-first">
                            <div class="btn-skip-1">

                                <button type="button" class="next action-button button button-small view-sheet">View Sheet</button>
                                <button type="button" class="next action-button button button-small finish">Finish</button>

                            </div>
                            <div class="col col-8">
                                <div class="item">
                                    <h2 class="header header-item">Job Details</h2>
                                    <div class="item-wrapper" id="job-details-results">
                                        <h2 class="result-name"></h2>
                                        <h3 class="result-address"></h3>
                                        <!--                                        <h4 class="result-type"></h4>-->
                                        <!--                                        <h4>Your Role: <strong class="result-role"></strong></h4>-->
                                        <button class="button button-small button-edit" data-modal-id="modal-edit-job-details">Edit Job Details</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col col-4 col-last">
                                <div class="item contract-overview">
                                    <h2 class="header header-item">Contract Overview</h2>
                                    <div class="item-wrapper">
                                        <table class="contract-overview-results">
                                            <tr class="contract-overview-template">
                                                <td class="result-name"></td>
                                                <td class="result-value"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-8">
                                <div class="item">
                                    <h2 class="header header-item">Customer Information</h2>
                                    <div class="item-wrapper">
                                        <table id="customer-results">
                                            <tr id="customer-template">
                                                <td class="result-name"></td>
                                                <td class="result-value"></td>
                                            </tr>
                                        </table>



                                    </div>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="item">
                                    <h2 class="header header-item">Lien Overview</h2>
                                    <div class="item-wrapper" id="lien-results"></div>
                                </div>
                                <div class="item">
                                    <h2 class="header header-item">Deadlines</h2>
                                    <div class="item-wrapper" id="deadline-data">

                                        <div id="deadline-resultss">
                                            <div id="deadline-templates">

                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <div class="btn-skip-1">


                                <button type="button" class="next action-button button button-small view-sheet">View Sheet</button>
                                <button type="button" class="next action-button button button-small finish">Finish</button>


                            </div>
                        </div>
                        <!-- <button type="button" class="action-button previous_button">Back</button> -->
                        <!-- <button type="button" class="next action-button">Continue</button>   -->
                        <!-- <button class="button back" data-modal-id="modal-back">Back</button> -->

                        <!-- <button class="button next">Continue</button> -->
                    </div><!-- End Summary Tab-->

                </fieldset>
                <?php //require_once "resources/views/project/dragandrop.blade.php";
                ?>





            </div>
        </div>
    </div>
    <div class="col col-1 col--empty col--no-pad">
    </div>

    <!--/div>
    </div-->
    <!--<div class="toolbar">
        <ul>
            <li>History</li>
            <li>Documents</li>
        </ul>
    </div>-->

    <div style="display: none;">
        <div id="ajax-loader">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
    </div>

    <div id="custom-modals" style="display: none">
        <div id="custom-dialog" data-auto-open="true" data-title="">
            <form method="post" action="/member/project/json">
                <input type="hidden" id="project_id" name="project_id" value="<?= $project->id ?>" />
                <input type="hidden" id="item_id" name="item_id" value="" />
                <div class="custom-modal-left custom-modal-col1">

                    <div class="field-template" data-field-template="selectbox">
                        <i class="fa fa-check-circle selectbox-check"></i>

                        <span>John smith - General Contractor</span>
                        <span>JS Builders LLC</span>
                    </div>

                </div>
                <div class="custom-modal-right ">

                    <div class="modal-alert-messages"></div>

                    <div class="alert failure" style="display:none;"></div>

                    <div class="custom-modal-col2">
                        <h3>Selected Project Contacts:</h3>

                        <div class="field-template" data-field-template="xselectbox">
                            <i class="fa fa-backspace selectbox-check"></i>

                            <span>John smith - General Contractor</span>
                            <span>JS Builders LLC</span>
                        </div>
                    </div>

                    <div class="content" style="display:block!important;">
                        <div data-field-template="text" class="row field-template field-half">
                            <label>Field Label</label>
                            <input type="text" />
                        </div>

                        <div data-field-template="financial" class="row field-template field-half">
                            <label>Field Label</label>
                            <input type="text" />
                        </div>

                        <div data-field-template="longtext" class="row field-template">
                            <label>Field Label</label>
                            <input type="text" />
                        </div>

                        <div data-field-template="textarea" class="row field-template">
                            <label>Field Label</label>
                            <textarea></textarea>
                        </div>

                        <div data-field-template="radio" class="row field-template">
                            <label>Field Label</label>
                            <div id="radio-template">
                                <input type="radio" />
                                <span class="radio-label"></span>
                            </div>
                        </div>

                        <div data-field-template="dropdown" class="row field-template">
                            <label>Field Label</label>
                            <div class="vine-select" style="margin: 0px 0px 1px;">
                                <select data-vineselect="true" style="margin: 0 !important;"></select>
                                <span class="vine-text"></span>
                                <span class="vine-arrow"></span>
                            </div>
                        </div>

                        <div data-field-template="datepicker" class="row field-template">
                            <label>Field Label</label>
                            <input type="text" class="form-control date multiple" />
                        </div>

                        <div data-field-template="label" class="row field-template field-half">
                            <label>Field Label</label>
                            <span></span>
                        </div>

                        <div data-field-template="sep" class="row field-template">
                            <hr />
                        </div>
                    </div>

                </div>
            </form>
        </div>
        <div id="claim-dialog" data-auto-open="true" data-title="">
            <form method="post" id='uploadForm' action="/member/project/send-claim" enctype="multipart/form-data">
                <input type="hidden" id="project_id" name="project_id" value="<?= $project->id ?>" />
                <div class="modal-alert-messages"></div>

                <div class="alert failure" style="display: none;"></div>

                <div class="content">

                    <div class="table-responsive">
                        <div class="black-box-main">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>Agree Terms and Conditions </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 footerTxt">
                                    Please remember to fax to NLB all documentation related to this
                                    project. This includes contracts, invoices, notices, purchase orders,
                                    etc. <strong>FAX: 847-432-8950</strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 footerTxt">
                                    <p><strong>Liability Limitations:</strong> National Lien and Bond Claim Systems, a division of
                                        Network*50, Inc (NLB) does not guarantee or in any way represent or warrant the
                                        information transmitted or received by customer or third parties. Customer acknowledges
                                        and agrees that the service provided by NLB consists solely of providing access to a filing
                                        network which may in appropriate cases involve attorneys. NLB is not in any way
                                        responsible or liable for errors, omissions, inadequacy or interruptions. In the event any
                                        errors is attributable to NLB or to the equipment, customer should be entitled only to a
                                        refund of the cost for preparation of any notices. The refund shall be exclusively in lieu of
                                        any other damages or remedies against NLB.</p>
                                </div>
                            </div>

                            <div class="col-md-12 footerTxt attach-document" style="display: ">
                                <div class="col-md-4">
                                    <label>Attach a Document</label>
                                </div>
                                <div class="col-md-8">
                                    <button id="upload_document" class="button vine-modal" style="margin: 0">Upload File</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 footerTxt">

                                    <div class="row">
                                        <?
                                        if (isset($jobInfoSheet) && count($jobInfoSheet->jobFiles) > 0) {

                                            foreach ($jobInfoSheet->jobFiles as $file) :
                                        ?>

                                        <?

                                            endforeach;
                                        }
                                        ?>
                                    </div>

                                    <div class="uploadedFiles"></div>

                                </div>
                            </div>
                            <div class="row footerTxt">
                                <div style="text-align:center; "><img src="<?= url('/') ?>/images/nlb_black.png" alt="NLB" align="middle"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 footerTxt">
                                    <p>&nbsp;</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 field">
                                    <label><input type="checkbox" name="Agree" id="agree">
                                        &nbsp;By submitting this
                                        form, you agree to the Liability Limitation terms stated herein.</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 footerTxt">
                                    <p>&nbsp;</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <div class="col-md-5">
                                        <strong>Customer Signature:</strong>
                                    </div>
                                    <div class="col-md-7 field">
                                        <input name="Signature" type="text" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="col-md-5">
                                        <strong>Date:</strong>
                                    </div>
                                    <div class="col-md-7 field">
                                        <input name="SignatureDate" type="text" class="date" value="" style="width:100px" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>



    <style type="text/css">
        #deadline-data {
            height: 250px;
            overflow: hidden;
            overflow-y: scroll;
        }

        .tab-content .item-wrapper {
            display: block !important;
        }

        #myitem {
            padding: 15px;
        }

        #dropbox {
            display: contents;
        }

        .multi_step_form #msform fieldset {
            border: 0;
            position: relative;
            width: 100%;
            left: 0;
            right: 0;
        }

        .multi_step_form #msform #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            width: 95%;
        }

        .multi_step_form #msform #progressbar li {
            list-style-type: none;
            color: #99a2a8;
            font-size: 9px;
            width: calc(86%/7);
            float: left;
            position: relative;
            font: 500 13px/1 'Roboto', sans-serif;
        }

        .multi_step_form #msform #progressbar li:before {
            content: "";
            font: normal normal normal 5px/30px Ionicons;
            width: 30px;
            height: 30px;
            line-height: 30px;
            display: block;
            background: #eaf0f4;
            border-radius: 50%;
            margin: 9px auto 10px auto;
            z-index: 3;
            position: relative;
        }

        .multi_step_form #msform #progressbar li:after {
            content: '';
            width: 100%;
            height: 10px;
            background: #eaf0f4;
            position: absolute;
            left: -48%;
            top: 21px;
            z-index: 1;
        }

        .multi_step_form #msform #progressbar li:last-child:after {
            width: 150%;
        }

        .multi_step_form #msform #progressbar li.active-tab-header {
            color: #23527c;
        }

        .multi_step_form #msform #progressbar li.active-tab-header:before,
        .multi_step_form #msform #progressbar li.active-tab-header:after {
            background: #23527c;
            color: white;
        }

        .skip {
            background: #d3d3d3;
            color: #000;
            margin-right: 10px;
        }

        .btn-skip {
            text-align: right;
        }

        button#dropdownMenu2 {
            border: none;
        }

        .btn-skip-1 {
            text-align: left;
            display: flex;
        }

        .mycustomfrom .content {
            width: auto;
            padding: 20px;

        }

        .mycustomfrom #modal-add-contact {
            width: auto;
        }
    </style>
    <script data-cfasync="false" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <Script type="text/javascript">
        function progress(current_fs, next_fs) {

            $("#progressbar li[data-tab-id='" + next_fs + "']").prevAll().addClass('active-tab-header active');
            $("#progressbar li[data-tab-id='" + next_fs + "']").trigger('click');
            $("#" + current_fs).removeClass("active-tab");
            $("#" + next_fs).addClass("active-tab");

        }


        $(".previous_button").click(function() {

            current_fs = $(this).parent();
            next_fs = $(this).parent().parent().prev().find('.tab-content');
            header_fs = $(this).parent().parent().prev();
            //de-activate current step on progressbar
            $("#progressbar li").eq($("fieldset").index(header_fs)).removeClass("active");

            //show the previous fieldset
            //previous_fs.show();
            current_fs.addClass("active-tab");
            next_fs.removeClass("active-tab");
            //hide the current fieldset with style
        });

        $(".view-sheet").click(function() {
            location.href = '/member/project/job-info-sheet/' + <?php echo $_GET['project_id']; ?>
        });

        $(".finish").click(function() {

            location.href = '/';

        });
    </Script>