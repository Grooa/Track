<?php
/**
 * Layout is changed by creating a page with route-path `tracks`,
 * in the admin menu
 */
?>
<div class="ipWidget">
    <h2>Hello Tracks</h2>

    <ul class="preview">
        <?php foreach($tracks as $track): ?>
            <li>
                <div class="thumbnail">
                    <img src="<?=ipFileUrl('file/repository/' . $track['thumbnail'])?>" alt="">
                </div>
                <h3><?=$track['title']?></h3>
                <p><?=$track['short_description']?></p>
                <a href="tracks/<?=$track['track_id']?>">Read more</a>
            </li>
        <?php endforeach; ?>
    </ul>

</div>
