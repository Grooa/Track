<?= ipSlot('xBreadcrumb', [
    [
        'uri' => "online-courses",
        'label' => 'Master Class'
    ],
    [
        'uri' => "online-courses/" . $track['trackId'],
        'label' => $track['title'],
        'active' => true
    ]
//    [
//        'uri' => "online-courses/" . $track['trackId'] . '/v/' . $course['courseId'],
//        'label' => !empty($course['title']) ? $course['title'] : 'Video',
//        'active' => true
//    ]
]) ?>

<section>
    <?= ipRenderWidget('Text', ['text' => '<div class="introduction">' .
        (!empty($course['longDescription']) ? $course['longDescription'] : $track['longDescription'])
        . '</div>'
    ]) ?>
</section>

<div class="video-player-frame">
    <video
            id="courseVid"
            class="object course-video"
            width="500"
            height="200"
            data-track-content
            data-content-name="Track Video"
            data-content-piece="<?= $track['title'] . '-' . $course['title'] ?>"
            poster="<?= ipFileUrl('file/repository/' . (!empty($course['largeThumbnail']) ? $course['largeThumbnail'] : $track['largeThumbnail'])) ?>"
            controls
            controlsList="nodownload">

        <source src="<?= $course['video'] ?>" type="video/mp4">
        Your device do not support video playback
    </video>

    <section class="video-metadata">
        <h1><?= $track['title'] ?></h1>
    </section>
</div>

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
