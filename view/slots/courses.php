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
                   href="<?= ipConfig()->baseUrl() . 'online-courses/' . $track['trackId'] ?>/course/<?= $course['courseId'] ?>">
                    Watch course
                </a>
            <?php endif; ?>

        </li>
    <?php endforeach; ?>
</ul>