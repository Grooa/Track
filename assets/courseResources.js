
document.addEventListener("DOMContentLoaded", function() {
    disableVideoDownload(document.getElementById('courseVid'));

    var courseResources = $('#courseResources');
    var loader = $('#loader');

    if (courseResources) {
        $.ajax({
            type: "GET",
            url: ip.baseUrl + 'online-courses/' + trackId + '/v/' + courseId + '/resources',
            dataType: 'json',
            success: function(data) {
                if (!data || data.length < 1) {
                    loader.html('This module has no resources');
                    return;
                }

                loader.remove();

                data.forEach(function(d) {
                    courseResources
                        .append(
                            '<li><a href="' + (d.url || '#') +'" target="_blank" data-content-piece="' + d.label + '" title="' + d.filename + '">' + d.label + '</a></li>'
                        );
                });
            },
            error: function (err) {
                console.error('Could not load resources for the video');
                console.error(err);
            }
        });
    }
});

/**
 * Remove right click and eventual other items
 *
 * */
function disableVideoDownload (video) {
    // Disable right click (simple solution)
    video.oncontextmenu = function(evt) {
        evt.preventDefault();
        return false;
    };
}