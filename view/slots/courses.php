

<?php if ($hasPurchased && !empty($track['courses']) && count($track['courses']) < 2): ?>
    <a
            class="button lonely"
            data-track-content
            data-content-name="Course Link"
            data-content-piece="<?=$track['title'] . '-' . $track['courses'][0]['title']?>"
            href="<?=ipConfig()->baseUrl()?>online-courses/<?=$track['trackId']?>/v/<?=$track['courses'][0]['courseId']?>">
        Watch Video
    </a>

<?php else: ?>
    <ul class="tiled shadowed clickable">
        <?php foreach ($track['courses'] as $course): ?>
            <li>
                <div class="thumbnail <?= !$hasPurchased ? 'blurred' : '' ?>">
                    <img src="<?= ipFileUrl('file/repository/' . $track['thumbnail']) ?>" alt="">
                    <svg class="play" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                        <path d="M20 33l12-9-12-9v18zm4-29C12.95 4 4 12.95 4 24s8.95 20 20 20 20-8.95 20-20S35.05 4 24 4zm0 36c-8.82 0-16-7.18-16-16S15.18 8 24 8s16 7.18 16 16-7.18 16-16 16z"/>
                    </svg>
                </div>
                <h3><?= $course['title'] ?></h3>
                <?php if (!empty($course['shortDescription'])): ?>
                    <div class="description"><?= $course['shortDescription'] ?></div>
                <?php endif; ?>

                <?php if ($hasPurchased): ?>
                    <a class="button colored"
                       data-track-content
                       data-content-name="Course List"
                       data-content-piece="<?=$track['title'] . '-' . $course['title']?>"
                       href="<?= ipConfig()->baseUrl() . 'online-courses/' . $track['trackId'] ?>/v/<?= $course['courseId'] ?>">
                        Watch video
                    </a>
                <?php endif; ?>

            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>