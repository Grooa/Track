<?= ipSlot('xBreadcrumb', [
    [
        'uri' => "tracks",
        'label' => 'Tracks'
    ],
    [
        'uri' => "tracks/" . $track['trackId'],
        'label' => $track['title']
    ],
    [
        'uri' => "tracks/" . $track['trackId'] . '/course/' . $course['courseId'],
        'label' => $course['title'],
        'active' => true
    ]
]) ?>

<section>
    <h1><?=$course['title']?></h1>
    <div class="introduction"><?=$course['longDescription']?></div>
</section>

<section>
    <video width="500" height="200" controls>
        <source src="<?=$course['video']?>" type="video/mp4">
        Your device do not support video playback
    </video>
</section>

<section>
    <h2>Resources</h2>
    <ul id="courseResources" class="course-resources"></ul>
</section>