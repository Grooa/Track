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

    <section class="video-titles">
        <h1><?= $track['title'] ?></h1>
    </section>
</div>

<div class="video-metadata columns">
    <section class="course-information most-important">
        <h2>Description</h2>
        <div class="data">
            <?//todo:ffl - Change to longDescription on video when we will focus on shorter modules ?>
            <?= !empty($track['longDescription']) ? $track['longDescription'] : ''?>
        </div>
    </section>

    <section class="course-information">
        <h2 class="colored">Resources</h2>
        <div class="data">
            <p id="loader" class="centered">Loading ...</p>
            <ul
                    id="courseResources"
                    class="course-resources dashed"
                    data-track-content
                    data-content-name="Track Resources"></ul>
        </div>
    </section>
</div>

<script type="text/javascript">
    var trackId = '<?=$track['trackId']?>';
    var courseId = '<?=$course['courseId']?>';
</script>
