
// Activate Next Step
function replaceUrlParam(paramName, paramValue, url) {
    if (paramValue == null)
        paramValue = '';
    var pattern = new RegExp('\\b(' + paramName + '=).*?(&|$)')
    if (url.search(pattern) >= 0) {
        return url.replace(pattern, '$1' + paramValue + '$2');
    }
    url = url.replace(/\?$/, '');
    return url + (url.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue
}
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
    
$(document).ready(function() {
    if(location.search.indexOf('view=')>=0){
        var c = getParameterByName("view",window.location.href);
        if(c == 'detailed'){
            $('#detailedLi').addClass('active');
            $('#expressLi').removeClass('active');
        } else {
            $('#expressLi').addClass('active');
            $('#detailedLi').removeClass('active');
        }
        $('.header-progress-item').find('a').each(function () {
            var url = replaceUrlParam('view', c, $(this).attr('href'));
            $(this).attr('href', url);
        });
    }


    $('.project-form select,input[type="text"],input[type="number"]').on('focus', function () {
        $(this).removeClass('input-error');
    });
//    $('.clickExpress').on('click', function () {
//        window.location.href = replaceUrlParam('view','express',window.location.href);
//    });
//    $('.clickDetailed').on('click', function () {
//        window.location.href = replaceUrlParam('view','detailed',window.location.href);
//    });

    // Explicitly save/update a url parameter using HTML5's replaceState().
});



