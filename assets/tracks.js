function Course() {

    function getTrackId() {
        var baseUrl = window.location.href.replace(ip.baseUrl, "");
        var id = baseUrl.replace('tracks/', '');

        if (id.length < 1 || isNaN(id)) {
            return null;
        }

        return parseInt(id);
    }

    function loadResources(selector) {
        var list = $(selector);

        var trackId = getTrackId();

        $.ajax({
            dataType: 'json',
            url: ip.baseUrl + 'tracks',

            statusCode: {
                403: function () {
                    console.error('Looks like you don\'t have access to this course!');
                },
                404: function () {
                    console.error('The course you are looking for doesn\'t exist!');
                }
            }
        })
            .done(function (data) {
                console.log('success');
                console.log(data);
            })
            .fail(function (err) {
                console.error('error');
                console.error(err);
            });
    }

    return {
        loadResource: loadResources
    };
}


$(document).ready(function() {
    var course = new Course();
    //course.loadResource('#courseResources');
});

