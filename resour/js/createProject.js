/*
createProject
DCobb
Using this to control some functionality on project creation.
~ Changes Progress Bar
~ Updates Job Description during File A Claim process
 */

$(document).ready(function(){
    if($('.progress').length > 0){
    let step = $('#stepNum').attr('data-step')
    let progressBar = $('.progress-bar')
    let progressParent = $('.progress')
    let stepDetailed = $('#stepNumDetailed').attr('data-step')
    let claimLink = $('#claimOrProject');
    if('#stepNum' || '#stepNumDetailed'){
        if(step != 1 || stepDetailed != 1){
            claimLink.hide();
        }
    }
        if($('#stepNum')){
            switch(step)
            {
                case '1':
                    progressParent.html('<div class="progress-bar progress-bar--step1" role="progressbar" aria-valuenow="33%" aria-valuemin="0" aria-valuemax="100">Step 1</div>')
                    break;
                case '2':
                    progressParent.html('<div class="progress-bar progress-bar--step2" role="progressbar" aria-valuenow="66%" aria-valuemin="0" aria-valuemax="100">Step 2</div>')
                    break;
                case '3':
                    progressParent.html('<div class="progress-bar progress-bar--step3" role="progressbar" aria-valuenow="100%" aria-valuemin="0" aria-valuemax="100">Step 3</div>')
                    break;

            }
        }
        if($('#stepNumDetailed')){
            switch(stepDetailed)
            {
                case '1':
                    progressParent.html('<div class="progress-bar progress-bar--step1--detailed" role="progressbar" aria-valuenow="16.6666666667" aria-valuemin="0" aria-valuemax="100">Step 1</div>')
                    break;
                case '2':
                    progressParent.html('<div class="progress-bar progress-bar--step2--detailed" role="progressbar" aria-valuenow="33.3333333333" aria-valuemin="0" aria-valuemax="100">Step 2</div>')
                    break;
                case '3':
                    progressParent.html('<div class="progress-bar progress-bar--step3--detailed" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">Step 3</div>')
                    break;
                case '4':
                    progressParent.html('<div class="progress-bar progress-bar--step4--detailed" role="progressbar" aria-valuenow="66.6666666667" aria-valuemin="0" aria-valuemax="100">Step 4</div>')
                    break;
                case '5':
                    progressParent.html('<div class="progress-bar progress-bar--step5--detailed" role="progressbar" aria-valuenow="83.3333333333" aria-valuemin="0" aria-valuemax="100">Step 5</div>')
                    break;
                case '6':
                    progressParent.html('<div class="progress-bar progress-bar--step6--detailed" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Step 6</div>')
                    break;

            }
        }

    }

    $(document).on('click', '#addContactsStep', function(){
        // go to job dates
        let data = $('#editJobDesc').serialize();
        $.ajax({
            type:'POST',
            url:saveJobDescription,
            data:data,
            success: function(data){
                //if($('#editFlag').length > 0){
                //    window.location.reload()
                //} else {
                    // 16 aug
                    window.location.href = contractURL;
                //}
            }
        })
    })
    $(document).on('click', '#addContactsStep-out', function(){
        // go to job dates
        let data = $('#editJobDesc').serialize();
        $.ajax({
            type:'POST',
            url:saveJobDescription,
            data:data,
            success: function(data){
                //if($('#editFlag').length > 0){
                //    window.location.reload()
                //} else {
                // 16 aug
                window.location.href = contractURL;
                //}
            }
        })
    })


})
