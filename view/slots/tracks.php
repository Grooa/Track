<?php if (empty($error)): ?>

    <ul class="tiled">
        <?php foreach ($tracks as $track): ?>
            <li class="course-module">
                <div class="metadata">
                    <div class="thumbnail <?= !empty($hasPurchased) && $hasPurchased == false ? 'blurred' : '' ?>">
                        <img src="<?= ipFileUrl('file/repository/' . $track['thumbnail']) ?>" alt="<?=$track['title']?>">
                    </div>

                </div>

                <h3><?= $track['title'] ?></h3>
                <div class="description"><?= !empty($track['shortDescription']) ? $track['shortDescription'] : '' ?></div>

                <?php if (!empty($showCreatedOn) && $showCreatedOn == true): ?>
                    <em>Added <time><?=$track['createdOn']?></time></em>
                <?php endif; ?>

                <a class="button colored see-course" href="<?= ipConfig()->baseUrl() . 'online-courses/' . $track['trackId'] ?>">
                    Checkout module
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

<?php else: ?>
    <p class="centered"><?=$error?></p>
<?php endif; ?>
