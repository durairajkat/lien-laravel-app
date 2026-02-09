/*
Job_Info_Dates
DCobb
Processes created and updated Remedy Dates on the Job Info Sheet
Watch for changes in the date fields on the Job Information sheet
Check if the date is recurring and if it is add another input field to parent div
Process the date change as a new date or an edited date
 */

$(document).ready(function () {
    // valueState checks to see if the input already has a value
    let valueState = false;

    $(document).on('click', '.multiple', function(e){
        if ($(this).val().length === 0){
            valueState = false
        }
        else{
            valueState = true
        }
    })
    // Check to see if the input field with class multiple has changed
    $(document).on('change', '.multiple', function(){
        const el = $(this)
        let dateExists = el.attr('data-existing')
        let recurring = el.attr('data-recurring')
        if (recurring === '1') {
            /*
            If this is a recurring date field find the parent div and append
            another input field
             */
            let parent = el.closest('.dateContainer')
            let name = el.attr('name')
            let inputField = "<input type=\"text\" class=\"form-control date multiple\" name=\""+name+"\"value=\"\"data-provide=\"datepicker\"  data-recurring=\""+recurring+"\" data-dateId = \"1\" data-existing=\"false\">"
            if (!valueState) {
                el.closest('.dateContainer').append(inputField)
            }


        }
        // if the date already exists we want to update it
        if (el.attr('data-existing') === "true") {
            let dateId = el.attr('data-dateId')
            // Send an update for the specific date ID
            let project = project_id
            let data = el.serialize()
            data = data + '&date_id=' + dateId + '&_token='+token+'&project_id='+project+'&tab_view=detailed'
            $.ajax({
                type: "POST",
                url: updateDate,
                data: data,
                success: function (data) {
                }
            });

        }
        // if the date doesn't exist we need to create it
        else {
            let dateId = el.attr('data-dateId')
            let project = project_id
            let data = el.serialize()
            data = data + '&_token='+token+'&project_id='+project+'&tab_view=detailed'
            $.ajax({
                type: "POST",
                url: submitDate,
                data: data,
                success: function (response) {
                    let res = response;
                    el.attr('data-dateId', res.id)
                    el.attr('data-existing', true)
                    el.attr('value', res.date)
                }
            });
        }

    })
})
