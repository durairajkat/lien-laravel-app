$(document).on('ready', function(){
    $(document).on('click', '#launchJobInfo', function(){
        let user = $('#launchJobInfo').attr('data-id')
        let url = $('#launchJobInfo').attr('data-url')
        let token = $('#jobInfoHide').val()
        let jobInfo = $('#jobInfoURL').val();
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: token
            },
            success: function (data) {
                let newJobInfo = jobInfo + '/member/project/job-info-sheet/' + data['project_id']
                window.location = newJobInfo
            }
        });
    })
})
