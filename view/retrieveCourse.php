<?= ipSlot('xBreadcrumb', [
    [
        'uri' => "online-courses",
        'label' => 'Master Class'
    ],
    [
        'uri' => "online-courses/" . $track['trackId'],
        'label' => $track['title']
    ],
    [
        'uri' => "online-courses/" . $track['trackId'] . '/v/' . $course['courseId'],
        'label' => $course['title'],
        'active' => true
    ]
]) ?>

<section>
	<h1><?=$track['title']?></h1>
    <h2>Video - <?= $course['title'] ?></h2>
    <?= ipRenderWidget('Text', ['text' => '<div class="introduction">' . $course['longDescription'] . '</div>']) ?>
</section>

<section>
    <?//= ipSlot('VideoJs_player', [
//        'sources' => [
//            'video/mp4' => $course['video']
//        ]
//    ]) ?>
    <video id="courseVid" class="object" width="500" height="200"
		poster="<?= !empty($course['largeThumbnail']) ? ipFileUrl('file/repository/' . $course['largeThumbnail']) : ''?>"	
		controls controlsList="nodownload">
        <source src="<?= $course['video'] ?>" type="video/mp4">
        Your device do not support video playback
    </video>
</section>

<section class="object" style="margin: 2em auto">
    <h2>Resources</h2>
    <p id="loader" class="centered">Loading ...</p>
    <ul id="courseResources" class="course-resources dashed"></ul>
</section>

<script type="text/javascript">
    var trackId = '<?=$track['trackId']?>';
    var courseId = '<?=$course['courseId']?>';

    var courseVid = document.getElementById('courseVid');

    // Disable right click (simple solution)
    courseVid.oncontextmenu = function(evt) {
        evt.preventDefault();
        return false;
    };

    document.addEventListener("DOMContentLoaded", function() {
        var courseResources = $('#courseResources');

        if (courseResources) {
            $.ajax({
                type: "GET",
                url: ip.baseUrl + 'online-courses/' + trackId + '/v/' + courseId + '/resources',
                dataType: 'json',
                success: function(data) {
                    if (!data || data.length < 1) {
                        $('#loader').html('This module has no resources');
                        return;
                    }

                    $('#loader').remove();

                    data.forEach(function(d) {
                        courseResources
                            .append(
                                '<li><a href="' + (d.url || '#') +'" target="_blank" title="' + d.filename + '">' + d.label + '</a></li>'
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

</script>
