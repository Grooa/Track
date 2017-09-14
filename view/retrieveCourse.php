<?= ipSlot('xBreadcrumb', [
    [
        'uri' => "online-courses",
        'label' => 'Tracks'
    ],
    [
        'uri' => "online-courses/" . $track['trackId'],
        'label' => $track['title']
    ],
    [
        'uri' => "online-courses/" . $track['trackId'] . '/course/' . $course['courseId'],
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

<section>
    <h2>Resources</h2>
    <ul id="courseResources" class="course-resources"></ul>
</section>

<script type="text/javascript">
    var courseVid = document.getElementById('courseVid');

    // Disable right click (simple solution)
    courseVid.oncontextmenu = function(evt) {
        evt.preventDefault();
        return false;
    }
</script>
