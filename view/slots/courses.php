<?php if ($hasPurchased && !empty($track['courses']) && count($track['courses']) < 2): ?>
    <a
            class="button lonely"
            data-track-content
            data-content-name="Course Link"
            data-content-piece="<?= $track['title'] . '-' . $track['courses'][0]['title'] ?>"
            href="<?= ipConfig()->baseUrl() ?>online-courses/<?= $track['trackId'] ?>/v/<?= $track['courses'][0]['courseId'] ?>">
        Watch Video
    </a>

<?php else: ?>
    <ul class="course-list">
        <?php $i = 1;
        foreach ($track['courses'] as $course): ?>
            <li id="video-<?=$i?>" class="course-module course-video clickable <?= !$hasPurchased ? 'inactive' : '' ?>">
                <a href="<?= $hasPurchased ? ipConfig()->baseUrl() . 'online-courses/' . $track['trackId'] . '/v/' . $course['courseId'] . '/' : "#video-$i"?>">
                <div class="course-module-metadata">
                    <strong class="type">Video <?= $i ?></strong>
                    <h3 class="title"><?= $course['title'] ?></h3>
                </div>

                <div class="description"><?= !empty($course['shortDescription']) ? $course['shortDescription'] : '' ?></div>

                <button><?= $hasPurchased ? 'Watch video' : "You don't have access" ?></button>

                </a>
            </li>
            <?php $i += 1; endforeach; ?>
    </ul>
<?php endif; ?>