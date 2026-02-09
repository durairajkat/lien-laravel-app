$(document).ready(function () {


	// $('.nextt').click(function (){
	//   var type = $(this).attr('data-id');

	//   $("[data-tab-id="+type+"]").trigger('click');

	// });


	let project_id = $('#project_id').val();
	let active_panel = 'summary';
	let search_timer = null;
	let contacts_first = true;
	let json_url = '/member/project/json';
	let loader = $('#ajax-loader');

	// show ajax loader in respective panel
	let showLoader = (panel_override) => {

		let which_panel = active_panel;

		if (panel_override) which_panel = panel_override;

		//if($('.ajax-loader-hidden').length > 0) return;

		let panel = $('.tab-content#' + which_panel);
		let wrappers = panel.find('.item-wrapper');

		if ($(wrappers).find('.ajax-loader-hidden').length > 0) return;

		wrappers.each((key, item) => {

			let wrapper = $(item);

			wrapper.addClass('ajax-loader-hidden').hide();
			wrapper.after(loader.clone().addClass('ajax-loader'));
		});

	};

	// show ajax loader in respective panel
	let showHeaderLoader = () => {

		let which_panel = 'header-panel';

		let panel = $('#' + which_panel);
		let wrappers = panel.find('.item-wrapper');

		if ($(wrappers).find('.ajax-loader-hidden').length > 0) return;

		wrappers.each((key, item) => {

			let wrapper = $(item);

			wrapper.addClass('ajax-loader-hidden').hide();
			wrapper.after(loader.clone().addClass('ajax-loader'));
		});

	};

	// hide loader and show content
	let hideLoader = () => {

		$('.ajax-loader-hidden').show().removeClass('ajax-loader-hidden');
		$('.ajax-loader').remove();

	};

	// refresh active tab
	let refreshTab = (tabId) => {
		$('.myfromdiv .mycustomfrom').html('');

		if (!tabId) tabId = active_panel;

		if (tabId == 'summary') loadSummary();
		if (tabId == 'contract') loadContract();
		if (tabId == 'contacts') loadContacts();
		if (tabId == 'dates') loadDates();
		if (tabId == 'deadlines') loadDates();
		if (tabId == 'tasks') loadTasks();
	};

	// job contacts search
	let searchContacts = () => {

		if (contacts_first) return _searchContacts();

		clearTimeout(search_timer);

		search_timer = setTimeout(() => {
			_searchContacts();
		}, 500);

	};

	let _searchContacts = () => {

		contacts_first = false;

		let query = $('#contacts-query').val();

		if (query.trim().length == 0) {

			// lets clear any old search results
			$('.contact-search-result').remove();
			$('#contact-add-button').hide();

			return;
		}


		if ($('.ajax-loader-hidden').length > 0) return;

		let panel = $('.tab-content#' + active_panel);
		let item = panel.find('#contact-search-result-wrapper');
		let wrapper = $(item);

		wrapper.addClass('ajax-loader-hidden').hide();
		wrapper.after(loader.clone().addClass('ajax-loader'));

		$.getJSON(json_url, {
			panel: active_panel,
			project_id: project_id,
			query: query
		}, (data) => {

			let result = data.result;
			if (!result) {
				alert("An error occurred while contacting the service");
				return;
			}

			// lets clear any old search results
			$('.contact-search-result').remove();
			$('#contact-add-button').hide();

			let contacts = data.contacts;

			for (let i in contacts) {

				let contact = contacts[i];
				let result = $('#contact-search-template').clone(false, true).attr('data-result-id', contact.id).attr('id', '').appendTo('#contact-search-results').addClass('contact-search-result');

				result.find('.result-name').text(contact.name);
				result.find('.result-role').text(contact.role);
				result.find('.result-company').text(contact.company);
				result.find('.result-assign').on('click', (e) => {

					// prevent # bounce
					e.preventDefault();

					$.getJSON(json_url, {
						panel: active_panel,
						project_id: project_id,
						assign: contact.id
					}, (data) => {

						let result = data.result;
						if (!result) {
							alert("An error occurred while contacting the service");
							return;
						}

						refreshTab();
					});

				});
			}

			if (contacts.length == 0) {
				//$('#contact-add-button').show();
			}

			hideLoader();
		});
	};

	let loadContacts = () => {
		$('#modal-add-contact').show();

		contacts_first = false;

		// clear search results
		//$('.contact-search-result').remove();

		// refresh search results
		_searchContacts();

		let query = $('#contacts-query').val();

		showLoader();

		$.getJSON(json_url, {
			panel: active_panel,
			project_id: project_id
		}, (data) => {

			let result = data.result;
			if (!result) {
				alert("An error occurred while contacting the service");
				return;
			}

			hideLoader();
			$('#modal-add-contact').show();


			// lets clear any old search results
			$('.contact-result').remove();

			let contacts = data.contacts;

			for (let i in contacts) {

				let contact = contacts[i];
				let result = $('#contact-template')
					.clone(false, true)
					.attr('data-result-id', contact.id)
					.attr('id', '')
					.appendTo('#contact-results')
					.addClass('contact-result');

				result.find('.result-name').text(contact.name);
				result.find('.result-role').text(contact.role);
				result.find('.result-company').text(contact.company);
				result.find('.result-addr').text(contact.address);
				result.find('.result-phone').text(contact.phone);

				result.find('.result-view').attr('data-item-id', contact.id);
				result.find('.result-edit').attr('data-item-id', contact.id);

				result.find('.result-remove').on('click', (e) => {

					// prevent # bounce
					e.preventDefault();

					$.getJSON(json_url, {
						panel: active_panel,
						project_id: project_id,
						unassign: contact.id
					}, (data) => {

						let result = data.result;
						if (!result) {
							alert("An error occurred while contacting the service");
							return;
						}

						refreshTab();
					});

				});

			}
		});
	};

	// TODO: make a universal function to load dates, tasks, contacts now that they follow the same format

	let loadDates = () => {
		$('#modal-add-contact').hide();
		$('[data-modal-id="modal-edit-job-dates"]').click();
		showLoader();

		$.getJSON(json_url, {
			panel: active_panel,
			project_id: project_id
		}, (data) => {

			let result = data.result;
			if (!result) {
				alert("An error occurred while contacting the service");
				return;
			}

			hideLoader();
			$('[data-modal-id="modal-edit-job-dates"]').click();


			// lets clear any old search results
			$('.date-result').remove();
			$('.deadline-result').remove();

			let dates = data.dates;
			let deadlines = data.deadlines;

			for (let i in dates) {

				let date = dates[i];
				let date_name = date.name;
				let date_value = "";

				if (typeof date.date !== 'undefined') {
					date_value = date.date[0].value;
				}

				let result = $('#date-template').clone(false, true).attr('data-result-id', date.id).attr('id', '').appendTo('#date-results').addClass('date-result');

				result.find('.result-name').text(date_name);
				result.find('.result-value').text(date_value);
			}

			for (let i in deadlines) {

				let deadline = deadlines[i];
				let result = $('#deadline-template').clone(false, true).attr('data-result-id', deadline.id).attr('id', '').appendTo('#deadline-results').addClass('deadline-result');

				result.find('.result-name').text(deadline.name);
				result.find('.result-desc').text(deadline.desc);
				result.find('.result-date').text(deadline.date);
				result.find('.result-days').text(deadline.days);

			}
		});
	};

	let loadContract = () => {
		$('#modal-add-contact').hide();

		showLoader();

		$.getJSON(json_url, {
			panel: active_panel,
			project_id: project_id
		}, (data) => {

			let result = data.result;
			if (!result) {
				alert("An error occurred while contacting the service");
				return;
			}

			loadContractOverview(() => {
				hideLoader();
			});

			// lets clear any old search results
			$('.contract-result').remove();

			let contracts = data.contracts;

			for (let i in contracts) {

				let contract = contracts[i];
				let contract_name = contract.name;
				let contract_value = contract.value;


				let result = $('#contract-template').clone(false, true).attr('data-result-id', contract.id).attr('id', '').appendTo('#contract-results').addClass('contract-result');

				result.find('.result-name').text(contract_name);
				result.find('.result-value').text(contract_value);
			}
		});
	};

	let loadContractOverview = (callback) => {

		showLoader('contract-overview');

		$.getJSON(json_url, {
			panel: 'contract-overview',
			project_id: project_id
		}, (data) => {

			let result = data.result;
			if (!result) {
				alert("An error occurred while contacting the service");
				return;
			}

			// lets clear any old search results
			$('.active-tab .contract-overview-result').remove();

			let contracts = data.contracts;

			for (let i in contracts) {

				let contract = contracts[i];
				let contract_name = contract.name;
				let contract_value = contract.value;


				let result = $('.contract-overview-template').first().clone(false, true).attr('data-result-id', contract.id).removeClass('contract-overview-template').appendTo('.active-tab .contract-overview-results').addClass('contract-overview-result');

				result.find('.result-name').text(contract_name);
				result.find('.result-value').text(contract_value);
			}

			if (callback) callback();
		});
	};

	let loadHeader = () => {

		showHeaderLoader();

		$('.send-claim').hide();

		$.getJSON(json_url, {
			panel: 'header',
			project_id: project_id
		}, (data) => {

			let result = data.result;
			if (!result) {
				alert("An error occurred while contacting the service");
				return;
			}

			let details = data.header;
			let container = $('#job-header-results');

			container.find('.result-company').html(details.company_name);
			container.find('.result-name').html(details.name);
			container.find('.result-address').html(details.address);
			container.find('.result-phone').html(details.phone);
			$('#job-header-title').text(details.project_title);

			if (data.info_sheet_active == true) {
				$('.send-claim').show();
			}

		});
	};

	let loadSummary = () => {
		$('#modal-add-contact').hide();

		showLoader();

		$.getJSON(json_url, {
			panel: active_panel,
			project_id: project_id
		}, (data) => {

			let result = data.result;
			if (!result) {
				alert("An error occurred while contacting the service");
				return;
			}

			loadContractOverview(() => {
				hideLoader();
			});

			// check if theres a customer at all
			let mapped = data.customer_mapped;

			if (!mapped) {
				$('#assign-customer').show();
				$('#edit-customer').hide();
				$('#customer-results').hide();
				$('#deadline-resultss').show();
			} else {
				$('#assign-customer').hide();
				$('#edit-customer').show();
				$('#customer-results').show();
				$('#deadline-resultss').show();
			}

			// lets clear any old search results
			$('.customer-result').remove();

			let customers = data.customer;
			let deadlines = data.deadlines;
			console.log(deadlines);
			let resultss = $('#deadline-templates');
			let html = '';
			for (let i in deadlines) {

				let deadline = deadlines[i];

				//.clone(false, true).attr('data-result-id', deadline.id).attr('id', '').appendTo('#deadline-resultss').addClass('deadline-result');
				html += '<p class="result-name"><span class="bold">' + deadline.name + '</span></p><p class="result-desc">' + deadline.desc + '</p><p><span class="bold">Complete by: <span class="result-date not-bold">' + deadline.date + '</span></span></p><p><span class="bold">Days left: <span class="result-days not-bold">' + deadline.days + '</span></span></p>';
				/* result.find('.result-name').text(deadline.name);
				result.find('.result-desc').text(deadline.desc);
				result.find('.result-date').text(deadline.date);
				result.find('.result-days').text(deadline.days); */
			}
			resultss.append(html);
			for (let i in customers) {

				let info = customers[i];
				let info_name = info.name;
				let info_value = info.value;


				let result = $('#customer-template').clone(false, true).attr('data-result-id', info.id).attr('id', '').appendTo('#customer-results').addClass('customer-result');

				result.find('.result-name').html(info_name);
				result.find('.result-value').html(info_value);
			}

			let details = data.details;
			let container = $('#job-details-results');

			container.find('.result-name').html(details.name);
			container.find('.result-address').html(details.address);
			container.find('.result-type').html(details.type);
			container.find('.result-role').html(details.role);

			container.find('h4').show();

			let lien = data.lien;
			container = $('#lien-results');

			container.html(lien);
		});
	};

	let loadTasks = () => {
		$('#modal-add-contact').hide();
		showLoader();

		$.getJSON(json_url, {
			panel: active_panel,
			project_id: project_id
		}, (data) => {

			let result = data.result;
			if (!result) {
				alert("An error occurred while contacting the service");
				return;
			}

			hideLoader();

			// lets clear any old search results
			$('.task-result').remove();

			let tasks = data.tasks;

			for (let i in tasks) {

				let task = tasks[i];
				let result = $('#task-template')
					.clone(false, true)
					.attr('data-result-id', task.id)
					.attr('id', '')
					.appendTo('#task-results')
					.addClass('task-result');

				result.find('.result-name').text(task.name);
				result.find('.result-date').text(task.due_date);
				result.find('.result-completed').text(task.completed);
				result.find('.result-comments').text(task.comment);
				result.find('.button-edit').attr('data-edit-id', task.id);
				result.find('.result-delete').on('click', (e) => {

					// prevent # bounce
					e.preventDefault();

					$.getJSON(json_url, {
						panel: active_panel,
						project_id: project_id,
						delete_id: task.id
					}, (data) => {

						let result = data.result;
						if (!result) {
							alert("An error occurred while contacting the service");
							return;
						}

						refreshTab();
					});

				});
			}
		});
	};

	// todo: replace all the getJSON requests with this
	let _loadPanel = (callback, panel_override) => {

		let panel = active_panel;

		if (panel_override) panel = panel_override;

		$.getJSON(json_url, {
			panel: panel,
			project_id: project_id,
			query: query
		}, (data) => {

			let result = data.result;
			if (!result) {
				alert("An error occurred while contacting the service");
				return;
			}

			callback(data);

		});


	};

	$('#contacts').on('keyup', '#contacts-query', (e) => {
		searchContacts();
	});

	$('#contacts').on('click', '#search-contacts', (e) => {
		searchContacts();
	});


	let changeTab = (e, id) => {
		// set our hashtag so when we refresh we can come right back
		window.location.hash = "#" + id;

		// store the active tab for other funcs
		active_panel = id;
		setTimeout(function () {
			$('.active-tab button[data-modal-id]').trigger('click');
		}, 1000);



		// todo:
		// spruce up this spaghetti
		document.getElementsByClassName('active')[0].classList.remove('active');
		e.currentTarget.classList.add('active');
		$("#progressbar li[data-tab-id='" + id + "']").prevAll().addClass('active-tab-header active');
		$("#progressbar li[data-tab-id='" + id + "']").nextAll().removeClass('active-tab-header active');
		e.currentTarget.classList.add('active-tab-header');
		document.getElementsByClassName('active-tab')[0].classList.remove('active-tab');
		document.getElementById(id).classList.add('active-tab');
		//document.getElementById('modal').classList.remove('hide-modal')
	};

	// sets modal errors
	let setErrors = (title, arrErrors) => {
		$('.modal-alert-messages').vineErrorMsg(title, arrErrors);
	};

	// changing tabs on the page
	$('.tablink').on('click', (e) => {

		// grab target tab ID
		let tabId = $(e.target).attr('data-tab-id');

		// initiate tab change
		changeTab(e, tabId);


		// todo:
		// eliminate hard loads/refreshes
		// summary and contract are the last pages to do this with

		refreshTab(tabId);
	});

	// send claim modal
	$('.send-claim').on('click', (e) => {

		// prevent bouncing the browser to the top of the page
		e.preventDefault();

		let button = $(e.target);
		button.attr('disabled', 'true');

		let project_id = $(button).attr('data-project-id');
		let modal_id = 'send-claim';
		let json_url = $('#claim-dialog > form').attr('action');

		$.getJSON(json_url, {
			project_id: project_id
		}, (data) => {

			let result = data.result;
			if (!result) {
				alert("An error occurred while contacting the service");
				return;
			}

			// set modal title
			let modal_title = data.title;
			let claim_status = data.info_sheet_active;

			// remove any old modal with the same name so that we fully regenerate everything
			$('#' + modal_id).unbind().remove();

			// clone a new dialog from the template
			$('#claim-dialog').clone(false, false).attr('id', modal_id).appendTo('#custom-modals');

			// populate dialog with information we got from the server
			let modal = $('#' + modal_id);



			// Setup custom dialog
			$('#' + modal_id).vineDialog({
				hasTitle: true,
				canClose: true,
				autoOpen: false,
				width: '800px',
				height: 'auto',
				title: modal_title,
				buttons: {
					'Cancel': {
						text: 'Cancel',
						cssClass: 'vine-modal',
						type: 'button',
						onClick: function () {
							// this will call the onClose() function
							$(this).vineDialog('close');
						}
					},
					'Submit': {
						text: 'Send Claim',
						cssClass: 'vine-modal fixate',
						type: 'button',
						onClick: function (e) {
							// we are using a separate handler below for this
						}
					}
				},
				onOpen: function () {
					$('.modal-alert-messages').empty();
					//$('.modal-alert-messages').vineSuccessMsg('Example onOpen() event.', 5000);

					if (!claim_status) {
						$('button.vine-modal.fixate').attr('disabled', 'true');
						$('.attach-document').hide();
					}
				},
				onClose: function () {
					// $('.modal-alert-messages').vineErrorMsg('Example onClose() event.', {
					//     'first_name' : 'Field specific error.',
					//     'last_name'  : 'Field specific error.'
					// }, 5000);

					button.removeAttr('disabled');
				},
				onRefresh: function () {

				}
			});

			$('#' + modal_id).on('click', '#upload_document', (e) => {

				$('#uploadFile_hidden').click();

				return false;

			});

			// bind the submit button this way because otherwise there's a duplicate submit bug
			$('#' + modal_id).on('click', 'button.vine-modal.fixate', (e) => {

				let values = modal.find('form').serializeArray();

				let btn = $(e.target);
				btn.attr('disabled', 'true');

				$.getJSON(json_url, {
					modal: modal_id,
					project_id: project_id,
					save: values
				}, (data) => {

					if (data.errors) {
						setErrors('Please correct these issues', data.errors);
					} else {
						$('#' + modal_id).vineDialog('close');

						alert(data.message);
					}
				}).error((jqXHR, exception) => {

					if (jqXHR.status === 0) {
						setErrors('Couldn\'t connect to server');
					} else if (jqXHR.status == 404) {
						setErrors('The requested page couldn\'t be found');
					} else if (jqXHR.status == 500) {
						setErrors('The requested page returned an Interal Server Error');
					} else if (exception === 'parsererror') {
						setErrors('Received an invalid JSON response');
					} else if (exception === 'timeout') {
						setErrors('The request timed out');
					} else if (exception === 'abort') {
						setErrors('The request was aborted');
					} else {
						setErrors('Uncaught Error: ' + jqXHR.responseText);
					}

				}).always(() => {
					refreshTab();
					button.removeAttr('disabled');
					btn.removeAttr('disabled');
				});
			});

			// display the modal
			$('#' + modal_id).vineDialog('open');
			$('.date').datepicker();
		});

	});


	// LienController@saveJobInfoFile
	$('body').on('change', '#uploadFile_hidden', (e) => {
		let form = new FormData();
		let filename = $("#uploadFile_hidden")[0].files[0].name;

		form.append("lien", $("#uploadFile_hidden")[0].files[0]);
		form.append("_filename", filename);
		form.append("_token", $('#uploadFile_hidden').attr('data-token'));

		$.ajax({
			type: "POST",
			url: $('#claim-dialog > form').attr('action') + "?upload=true&project_id=" + project_id,
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			beforeSend: function () {
				HoldOn.open();
			},
			complete: function () {
				HoldOn.close();
			},
			success: function (data) {

				if (data.status == true) {
					$('.uploadedFiles').append(
						'                                                    <div class="col-md-12">\n' +
						'                                                        <div class="fileRow">\n' +
						'                                                            <div class="col-xs-8">\n' +
						'                                                                <div class="fileName">\n' +
						'                                                                    <a href="/upload/' + data.name + '" target="_blank">' + data.name + '\n' +
						'                                                                    </a>\n' +
						'                                                                </div>\n' +
						'                                                            </div>\n' +
						'                                                        </div>\n' +
						'                                                    </div>\n' +
						'                                                    <input type="hidden" name="newfiles[]" value="' + data.name + '">'
					);
				} else {
					$('#error').text(data.message);
					$('.error-field').show();
				}

			}
		});
	});

	// open dialog when any edit button is clicked
	$('body').on('click', '[data-modal-id]', function (e) {

		// prevent bouncing the browser to the top of the page
		e.preventDefault();

		let button = $(this);
		button.attr('disabled', 'true');

		let modal_id = $(this).attr('data-modal-id');
		let json_url = $('#custom-dialog > form').attr('action');
		let item_id = $(this).attr('data-edit-id');
		let modal = {};
		let modal_buttons = {};
		let col2_default_active = true;

		if (typeof item_id == 'undefined' || item_id.length == 0) {
			item_id = $(this).attr('data-item-id');
		}

		let showCol2_Default = () => {
			$('.modal-alert-messages').empty();

			let col = modal.find('.custom-modal-right');

			col.find('.content').show();
			col.find('.custom-modal-col2').hide();

			// show cancel button
			modal.find('.actions button.button[data-auto-call="button-2"]').show();

			// hide create button
			modal.find('.actions button.button[data-auto-call="button-1"]').hide();

			// change save label
			modal.find('.actions button.button[data-auto-call="button-0"]').text('Save Contact');

			col2_default_active = true;
		};

		let showCol2_Alternate = () => {
			$('.modal-alert-messages').empty();

			let col = modal.find('.custom-modal-right');

			col.find('.content').hide();
			col.find('.custom-modal-col2').show();

			// hide cancel button
			modal.find('.actions button.button[data-auto-call="button-2"]').hide();

			// show create button
			modal.find('.actions button.button[data-auto-call="button-1"]').show();

			// change save label
			modal.find('.actions button.button[data-auto-call="button-0"]').text('Save Contacts');

			col2_default_active = false;
		};

		// called before the modal opens, and they may make changes to the modal settings itself
		let handleCol1DataPre = (data) => {
			modal_buttons = {
				'Save Contacts': {
					text: 'Save Contacts',
					cssClass: 'vine-modal fixate',
					type: 'button',
					onClick: function (e) {
						// we are using a separate handler below for this
					}
				},
				'Create Contact': {
					text: 'Create Contact',
					cssClass: 'vine-modal',
					type: 'button',
					onClick: function () {
						showCol2_Default();
					}
				},
				'Cancel': {
					text: 'Cancel',
					cssClass: 'vine-modal',
					type: 'button',
					onClick: function () {
						showCol2_Alternate();
					}
				}
			};
		};

		let handleCol2DataPre = (data) => {};

		// called after the modal opens, and everything in it is initialized
		let handleCol1Data = (data) => {

			// get template for selectbox
			let field_template = modal.find('[data-field-template="selectbox"]').first();

			for (let i in data) {

				let contact = data[i];
				let field_name = 'col1_select_' + i;

				// create our new field
				let new_field = field_template.clone(false, false).attr('id', field_name).attr('data-id', contact.id).removeClass('field-template').addClass('col1_select');

				// change the name label
				new_field.find('span').first().text(contact.name);

				// change the company label
				new_field.find('span').last().text(contact.company);

				// set role
				new_field.attr('data-role', contact.role);

				// add it to our dialog
				new_field.appendTo(modal.find('.custom-modal-col1'));

				initSelectbox(new_field);
			}
		};

		let handleCol2Data = (data) => {
			let modal = $('#' + modal_id);
			// get template for selectbox
			let field_template = modal.find('[data-field-template="xselectbox"]').first();

			for (let i in data) {

				let contact = data[i];
				let field_name = 'col2_select_' + i;

				// create our new field
				let new_field = field_template.clone(false, false).attr('id', field_name).attr('data-id', contact.id).removeClass('field-template').addClass('col2_select');

				// change the name label
				new_field.find('span').first().text(contact.name);

				// change the company label
				new_field.find('span').last().text(contact.company);

				// add it to our dialog
				new_field.appendTo(modal.find('.custom-modal-col2'));

				initSelectbox(new_field);

			}

			// show the alternate content panel
			showCol2_Alternate();

			// show all items
			handleFilter();
		};

		let handleFilter = (value) => {

			if (!value) value = 'all';

			let items = [];

			if (value == "all") {
				items = $('.col1_select');
			} else {
				items = $('.col1_select[data-role="' + value + '"]');

				let hide_items = $('.col1_select:not([data-role="' + value + '"])');
				hide_items.hide();
			}

			items.show();

		};

		let handleMultiColData = (data) => {

			let roles = data.roles;
			let field_name = 'col1_filter';
			let modal = $('#' + modal_id);
			// create our new field
			let field_template = modal.find('[data-field-template="dropdown"]').first();
			let new_field = field_template.clone(false, false).attr('id', field_name).removeClass('field-template');

			new_field.find('label').hide();

			let addOption = (role, val) => {
				let new_option = $('<option></option>').attr('value', role).text(role);

				if (val) {
					new_option.attr('value', val);
				}

				new_field.find('select').append(new_option);
			};

			addOption("-- All Roles --", "all");

			for (let i in roles) {

				let role = roles[i];
				addOption(role);
			}

			let filterDropdown = () => {
				let op = new_field.find('select').find('option:selected');
				let text = op.text();
				let value = op.attr('value');

				// set label
				new_field.find('.vine-text').text(text);

				handleFilter(value);
			};

			if (new_field.find('.vine-text').text() == "") {
				filterDropdown();
			}

			new_field.find('select').on('change', function () {
				filterDropdown();
			});

			// add it to our dialog
			new_field.appendTo(modal.find('.actions'));

			// assign our listener
			modal.data('getValues', () => {
				return getValues();
			});
		};

		// called to activate listeners on selectbox check's
		let initSelectbox = (field) => {

			let check = field.find('.selectbox-check').first();
			field.attr('data-selected', 'off');

			field.click((e) => {

				if (field.attr('data-selected') == 'off' || !field.attr('data-selected')) {
					field.attr('data-selected', 'on');
					check.show();
				} else {
					field.attr('data-selected', 'off');
					check.hide();
				}

			});
		};

		let getValues = () => {
			// if we are on the create contact pane just ignore the selection
			if (col2_default_active) return {};

			let items = {
				assign: {},
				unassign: {}
			};
			let modal = $('#' + modal_id);
			let assign = modal.find('div.col1_select[data-selected="on"]');
			$.each(assign, (i, contact) => {

				contact = $(contact);
				let contact_id = contact.attr('data-id');

				items.assign[contact_id] = true;
			});

			let unassign = modal.find('div.col2_select[data-selected="on"]');
			$.each(unassign, (i, contact) => {

				contact = $(contact);
				let contact_id = contact.attr('data-id');

				items.unassign[contact_id] = true;
			});

			return items;
		};

		// grab the details of the item we are editing
		$.getJSON(json_url, {
			modal: modal_id,
			project_id: project_id,
			item_id: item_id
		}, (data) => {

			let result = data.result;
			if (!result) {
				alert("An error occurred while contacting the service");
				return;
			}

			// set modal title
			let modal_title = data.title;
			let modal_size = data.size ? data.size : 640;
			modal_buttons = {
				'Cancel': {
					text: 'Cancel',
					cssClass: 'vine-modal',
					type: 'button',
					onClick: function () {
						// this will call the onClose() function
						$(this).vineDialog('close');
					}
				},
				'Submit': {
					text: 'Submit',
					cssClass: 'vine-modal fixate',
					type: 'button',
					onClick: function (e) {
						// we are using a separate handler below for this
					}
				}
			};

			// remove any old modal with the same name so that we fully regenerate everything
			$('#' + modal_id).unbind().remove();

			// clone a new dialog from the template
			$('#custom-dialog').clone(false, false).attr('id', modal_id).appendTo('#custom-modals');

			// populate dialog with information we got from the server
			modal = $('#' + modal_id);

			// set our item id if applicable
			if (typeof item_id !== 'undefined') modal.find('#item_id').val(item_id);

			let refresh_all = {};

			// process fields
			for (let i in data.fields) {

				let field_name = i;
				let field_data = data.fields[i];

				let addField = (field_name, field_data, linked_to) => {

					let field_title = field_data[0];
					let field_value = field_data[1];
					let field_type = 'text';
					let field_options = [];
					let option_multi = false;
					let linked_field = false;
					let handleChange = () => {};

					if (typeof field_data[2] !== "undefined") field_type = field_data[2];
					if (typeof field_data[3] !== "undefined") field_options = field_data[3];

					if (field_type.length == 0) {
						field_type = 'text';
					}

					let field_template = modal.find('[data-field-template="' + field_type + '"]').first();

					// create our new field
					let new_field = field_template.clone(false, false).attr('id', field_name).removeClass('field-template');

					// assign it a name, label, and content
					new_field.find('label').text(field_title);

					// if its a dropdown
					if (field_type == 'dropdown') {

						new_field.find('select').attr('name', field_name);

						for (let j in field_options) {

							let option_id = j;
							let option_text = field_options[j];

							if (typeof option_text == "object") {
								option_multi = option_text;
								option_text = option_multi.name;
								linked_field = [field_name, option_id];

								handleChange = (e) => {

									let current_val = new_field.find('select').val();
									let sub_fields = modal.find('[data-linked-field="' + linked_field[0] + '"]');

									for (let k = 0; k < sub_fields.length; k++) {

										let sub_field = $(sub_fields[k]);
										let sub_linked_val = $(sub_field).attr('data-linked-val');


										if (sub_linked_val == current_val) {
											sub_field.show();
										} else {
											sub_field.hide();
										}
									}


									sub_fields = modal.find('[data-linked-field]');
									for (let k = 0; k < sub_fields.length; k++) {

										let sub_field = $(sub_fields[k]);
										let sub_linked_field = $(sub_field).attr('data-linked-field');
										let sub_linked_val = $(sub_field).attr('data-linked-val');
										let sub_parent_field = $(modal).find('[name="' + sub_linked_field + '"]');

										if (!sub_parent_field) {
											sub_field.hide();
										} else {
											//sub_field.show();
										}
									}
								};

								new_field.on('change', function (e) {
									handleChange(e);
								});
							}

							let option = $('<option></option>').val(option_id).text(option_text).appendTo(new_field.find('select'));

							if (option_id == field_value) {
								option.attr('selected', 'selected');
								new_field.find('.vine-text').text(option_text);
							}
						}

						let setDropdownText = () => {
							let text = new_field.find('select').find('option:selected').text();
							new_field.find('.vine-text').text(text);
						};

						if (new_field.find('.vine-text').text() == "") {
							setDropdownText();
						}

						new_field.find('select').on('change', function () {
							setDropdownText();
						});

					} else if (field_type == 'radio') {

						for (let j in field_options) {

							let option_id = j;
							let option_text = field_options[j];

							let radio_field = new_field.find('#radio-template').clone(false, false).attr('id', '');

							radio_field
								.find('input')
								.attr('name', field_name)
								.val(option_id);

							radio_field
								.find('span')
								.text(option_text);

							if (option_id == field_value) radio_field.find('input').attr('checked', 'checked');

							radio_field.appendTo(new_field);
						}

					} else {
						new_field.find('input,textarea,radio,select,span').attr('name', field_name).val(field_value).text(field_value);
					}

					// add it to our dialog
					new_field.appendTo(modal.find('.content'));

					if (linked_to) {
						new_field.attr('data-linked-field', linked_to[0]);
						new_field.attr('data-linked-val', linked_to[1]);
						//new_field.hide();
					}

					// process recursive fields if any
					if (option_multi) {

						let processMulti = (data, linked_to) => {
							for (let j in data.values) {
								field_name = j;
								field_data = data.values[j];

								addField(field_name, field_data, linked_to);
							}
						};

						processMulti(option_multi, linked_field);
					}

					// refresh fields
					handleChange();
				};

				addField(field_name, field_data);
			}

			// process other cols if any
			if (typeof data.col_1 !== 'undefined') {
				handleCol1DataPre(data.col_1);
			}

			if (typeof data.col_2 !== 'undefined') {
				handleCol2DataPre(data.col_2);
			}

			// Setup custom dialog
			$('#' + modal_id).vineDialog({
				hasTitle: true,
				canClose: true,
				autoOpen: false,
				width: modal_size,
				height: 'auto',
				title: modal_title,
				buttons: modal_buttons,
				onOpen: function () {
					$('.modal-alert-messages').empty();

					// process other cols if any
					if (typeof data.col_1 !== 'undefined' || typeof data.col_2 !== 'undefined') {
						handleMultiColData(data);
					}

					if (typeof data.col_1 !== 'undefined') {
						handleCol1Data(data.col_1);
					}

					if (typeof data.col_2 !== 'undefined') {
						handleCol2Data(data.col_2);
					}

					//$('.modal-alert-messages').vineSuccessMsg('Example onOpen() event.', 5000);
				},
				onClose: function () {
					// $('.modal-alert-messages').vineErrorMsg('Example onClose() event.', {
					//     'first_name' : 'Field specific error.',
					//     'last_name'  : 'Field specific error.'
					// }, 5000);

					button.removeAttr('disabled');
				},
				onRefresh: function () {

				}
			});

			// bind the submit button this way because otherwise there's a duplicate submit bug
			$('#' + modal_id).on('click', 'button.vine-modal.fixate', (e) => {

				let values = modal.find('form').serializeArray();
				let fields = {
					modal: modal_id,
					project_id: project_id,
					save: values
				};

				let btn = $(e.target);
				btn.attr('disabled', 'true');

				let cust = modal.data('getValues');
				if (typeof cust == 'function') {
					let val = cust();

					fields.custom_value = val;
				}

				$.getJSON(json_url, fields, (data) => {

					if (data.errors) {
						setErrors('Please correct these issues', data.errors);
					} else {
						$('#' + modal_id).vineDialog('close');
					}
				}).error((jqXHR, exception) => {

					if (jqXHR.status === 0) {
						setErrors('Couldn\'t connect to server');
					} else if (jqXHR.status == 404) {
						setErrors('The requested page couldn\'t be found');
					} else if (jqXHR.status == 500) {
						setErrors('The requested page returned an Interal Server Error');
					} else if (exception === 'parsererror') {
						setErrors('Received an invalid JSON response');
					} else if (exception === 'timeout') {
						setErrors('The request timed out');
					} else if (exception === 'abort') {
						setErrors('The request was aborted');
					} else {
						setErrors('Uncaught Error: ' + jqXHR.responseText);
					}

				}).always(() => {
					refreshTab();

					if (active_panel == "summary") {
						loadHeader();
					}

					button.removeAttr('disabled');
					btn.removeAttr('disabled');
				});
			});

			// display the modal
			$('#' + modal_id).vineDialog('open');
			$('#' + modal_id).hide();
			let sus = window.location.hash;
			if (sus == "#contacts") {
				refreshTab();
				$('#modal-add-contact').show();
			} else {
				$('#modal-add-contact').hide();
				refreshTab();

			}
			//$('#modal-add-contact').show();
			$('.date').datepicker();
			$('#vine-overlay').remove();
			setTimeout(function () {

				$('.active-tab').append('<div class="row row-first myfromdiv"><div class="col col-12"><div class="item"><h2 class="header header-item">' + modal_title + '</h2><div class="item-wrapper mycustomfrom"></div></div></div></div>');
				$('#' + modal_id).clone().appendTo('.mycustomfrom');
				$('.mycustomfrom #' + modal_id).removeAttr('style');
				$('.mycustomfrom .close').remove();
				$('.mycustomfrom .title').remove();
				$('.mycustomfrom #' + modal_id).show();
				// $("html, body").animate({ scrollTop: $(document).height() }, 1000);
				$('.date').datepicker();
				let field_name = 'col1_filter';
				let modal = $('#' + modal_id);
				// create our new field
				let field_template = modal.find('[data-field-template="dropdown"]').first();
				let new_field = field_template.clone(false, false).attr('id', field_name).removeClass('field-template');
				let filterDropdown = () => {
					let op = new_field.find('select').find('option:selected');
					let text = op.text();
					let value = op.attr('value');

					// set label
					new_field.find('.vine-text').text(text);

					handleFilter(value);
				};
				let handleFilter = (value) => {

					if (!value) value = 'all';

					let items = [];

					if (value == "all") {
						items = $('.col1_select');
					} else {
						items = $('.col1_select[data-role="' + value + '"]');

						let hide_items = $('.col1_select:not([data-role="' + value + '"])');
						hide_items.hide();
					}

					items.show();

				};

				if (new_field.find('.vine-text').text() == "") {
					filterDropdown();
				}

				new_field.find('select').on('change', function () {

					filterDropdown();
				});

				$(".col1_select").each(function () {
					let check = $(this).find('.selectbox-check').first();
					$(this).attr('data-selected', 'off');

					$(this).click((e) => {

						if ($(this).attr('data-selected') == 'off' || !$(this).attr('data-selected')) {
							$(this).attr('data-selected', 'on');
							check.show();
						} else {
							$(this).attr('data-selected', 'off');
							check.hide();
						}

					});
				});

				if (modal_id == 'modal-edit-job-dates' || modal_id == 'modal-edit-job-contract' || modal_id == 'modal-add-job-task' || modal_id == 'modal-edit-job-details') {

					$('#' + modal_id).on('click', 'button.vine-modal.fixate', (e) => {
						let modal = $('#' + modal_id);
						let values = modal.find('form').serializeArray();
						let btn = $(e.target);
						btn.attr('disabled', 'true');

						$.getJSON(json_url, {
							modal: modal_id,
							project_id: project_id,
							save: values
						}, (data) => {

							if (data.errors) {
								setErrors('Please correct these issues', data.errors);
							} else {
								$('#' + modal_id).vineDialog('close');
								$('.myfromdiv').remove();
								// alert(data.message);
							}
						}).error((jqXHR, exception) => {

							if (jqXHR.status === 0) {
								setErrors('Couldn\'t connect to server');
							} else if (jqXHR.status == 404) {
								setErrors('The requested page couldn\'t be found');
							} else if (jqXHR.status == 500) {
								setErrors('The requested page returned an Interal Server Error');
							} else if (exception === 'parsererror') {
								setErrors('Received an invalid JSON response');
							} else if (exception === 'timeout') {
								setErrors('The request timed out');
							} else if (exception === 'abort') {
								setErrors('The request was aborted');
							} else {
								setErrors('Uncaught Error: ' + jqXHR.responseText);
							}

						}).always(() => {
							refreshTab();
							button.removeAttr('disabled');
							btn.removeAttr('disabled');
						});
					});
				}
				if (modal_id == 'modal-add-contact') {
					$('#' + modal_id).show();

					$('#' + modal_id).on('click', 'button.vine-modal.fixate', (e) => {
						let modal = $('#' + modal_id);
						let values = modal.find('form').serializeArray();
						let fields = {
							modal: modal_id,
							project_id: project_id,
							save: values
						};
						$('#' + modal_id).show();

						//$('.date').datepicker();
						//$('#vine-overlay').remove();
						let btn = $(e.target);
						btn.attr('disabled', 'false');

						let cust = modal.data('getValues');
						if (typeof cust == 'function') {
							let val = cust();

							fields.custom_value = val;
						}

						$.getJSON(json_url, fields, (data) => {

							if (data.errors) {
								setErrors('Please correct these issues', data.errors);
							} else {
								$('#' + modal_id).vineDialog('close');
								$('.myfromdiv').remove();
							}
						}).error((jqXHR, exception) => {

							if (jqXHR.status === 0) {
								setErrors('Couldn\'t connect to server');
							} else if (jqXHR.status == 404) {
								setErrors('The requested page couldn\'t be found');
							} else if (jqXHR.status == 500) {
								setErrors('The requested page returned an Interal Server Error');
							} else if (exception === 'parsererror') {
								setErrors('Received an invalid JSON response');
							} else if (exception === 'timeout') {
								setErrors('The request timed out');
							} else if (exception === 'abort') {
								setErrors('The request was aborted');
							} else {
								setErrors('Uncaught Error: ' + jqXHR.responseText);
							}

						}).always(() => {
							refreshTab();

							if (active_panel == "summary") {
								loadHeader();
							}

							button.removeAttr('disabled');
							btn.removeAttr('disabled');
						});
					});
				}
			}, 1000);


		});
	});

	// go to contract page when contract overview is clicked
	$('body').on('click', '.contract-overview', (e) => {
		$('[data-tab-id="contract"]').click();
	});
	$(window).load(function () {
		$('[data-modal-id="modal-edit-job-dates"]').click();
		//$('#modal-edit-job-dates').modal('show');
	});

	// set cursor to hand when mouseover the contract overview
	$('.contract-overview').css('cursor', 'pointer');

	// process hashtag if any
	let hashtag = window.location.hash;

	if (hashtag.length > 1) {
		hashtag = hashtag.substr(1);

		// click the tab if it exists
		$('[data-tab-id="' + hashtag + '"]').click();
	} else {
		refreshTab();
	}

	loadHeader();
});
