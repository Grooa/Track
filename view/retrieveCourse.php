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
    <video
            id="courseVid"
            class="object"
            width="500"
            height="200"
            data-track-content
            data-content-name="Track Video"
            data-content-piece="<?=$track['title'] . '-' . $course['title']?>"
            poster="<?= !empty($course['largeThumbnail']) ? ipFileUrl('file/repository/' . $course['largeThumbnail']) : ''?>"
            controls
            controlsList="nodownload">

        <source src="<?= $course['video'] ?>" type="video/mp4">
        Your device do not support video playback
    </video>
</section>

<section class="object" style="margin: 2em auto">
    <h2>Resources</h2>
    <p id="loader" class="centered">Loading ...</p>
    <ul
            id="courseResources"
            class="course-resources dashed"
            data-track-content
            data-content-name="Track Resources"></ul>
</section>

<script type="text/javascript">
    var trackId = '<?=$track['trackId']?>';
    var courseId = '<?=$course['courseId']?>';
</script>
