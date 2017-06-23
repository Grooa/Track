
<section>
    <h1><?=$course['title']?></h1>
    <div class="introduction"><?=$course['long_description']?></div>
</section>

<section>
    <video width="500" height="200" controls>
        <source src="<?=ipFileUrl('file/repository/' . $course['video'])?>" type="video/mp4">
        Your device do not support video playback
    </video>
</section>

<section>
    <h2>Resources</h2>

</section>