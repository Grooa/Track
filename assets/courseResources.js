function CourseResources() {

    var reqUrl = 'pa=Track.listCourses';

    function buildReqUrl(trackId) {
        return ip.baseUrl + '?' + reqUrl + '&trackId=' + trackId;
    }

    function generateSelectList(courses) {
        if (!(courses instanceof Array)) {
            return "";
        }

        return courses.map(function (c) {
            return '<option value="' + c[0] + '">' + c[1] + '</option>';
        }).join(' ');
    }

    function getHelpError(sibling) {
        var siblings = sibling.parentNode.childNodes;

        for (var i = 0; i < siblings.length; i++) {
            if (siblings[i].className === 'help-error') {
                return siblings[i];
            }
        }

        return null;
    }

    function fetchCourses(id) {
        return new Promise(function (rsv, rr) {
            if (!id) {
                return rsv(null);
            }

            var url = buildReqUrl(id);

            $.ajax({
                dataType: 'json',
                url: url
            })
                .done(function (data) {
                    if (!data) {
                        return rr(new Error('No Courses Available for this track'));
                    }
                    return rsv(data);
                })
                .fail(function (err) {
                    return rr(err);
                });
        });
    }

    function courseUpdateHandler(fetcher, course, helpError) {
        fetcher
            .then(function (data) {
                course.value = null;
                course.innerHTML = generateSelectList(data);
            })
            .catch(function (err) {
                helpError.innerHTML = err.message;
                helpError.style.display = 'block'
            });
    }

    /**
     * Handles
     * */
    function trackUpdateHandler(track, course) {
        if (!track || !course) {
            return;
        }
        var helpError = getHelpError(course);
        helpError.innerHTML = '';
        helpError.style.display = 'none';

        courseUpdateHandler(fetchCourses(track.value), course, helpError);

        track.addEventListener('change', function (evt) {
            var value = evt.target.value || null;
            courseUpdateHandler(fetchCourses(value), course, helpError);
        });
    }

    return {
        trackUpdateHandler: trackUpdateHandler
    };
}

$(document).ready(function () {
    var resources = new CourseResources();

    $('.ipsGrid').on('createModalOpen.ipGrid', function (evt) {
        var track = document.getElementsByName('trackId');
        track = !!track ? track[0] : null;

        var course = document.getElementsByName('courseId');
        course = !!course ? course[0] : null;

        resources.trackUpdateHandler(track, course);
    }).on('updateModalOpen.ipGrid', function (evt) {
        var track = document.getElementsByName('trackId');
        track = !!track ? track[0] : null;

        var course = document.getElementsByName('courseId');
        course = !!course ? course[0] : null;

        console.log(course);

        resources.trackUpdateHandler(track, course);
    });

});