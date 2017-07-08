<ul class="tiled shadowed">
    <?php foreach ($track['courses'] as $course): ?>
        <li>
            <div class="thumbnail <?= !$hasPurchased ? 'blurred' : '' ?>">
                <img src="<?= ipFileUrl('file/repository/' . $track['thumbnail']) ?>" alt="">
            </div>
            <h3><?= $course['title'] ?></h3>
            <?php if (!empty($course['shortDescription'])): ?>
                <div><?= $course['shortDescription'] ?></div>
            <?php endif; ?>

            <?php if ($hasPurchased): ?>
                <a class="button colored"
                   href="/ImpressPages/tracks/<?= $track['trackId'] ?>/course/<?= $course['courseId'] ?>">
                    Watch course
                </a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>