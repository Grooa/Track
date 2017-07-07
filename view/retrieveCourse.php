<nav class="breadcrumbs">
    <a href="<?=ipConfig()->baseUrl()?>tracks">Tracks</a>
    <a href="<?=ipConfig()->baseUrl()?>tracks/<?=$track['trackId']?>">
        <?=$track['title']?>
    </a>
    <a href="<?=ipConfig()->baseUrl()?>tracks/<?=$track['trackId']?>/course/<?=$course['courseId']?>"
       class="currentPage">
        <?=$course['title']?>
    </a>
</nav>

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

</section>