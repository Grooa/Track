<?php if (!empty($tracks)): ?>

    <ul class="tiled shadowed">
        <?php foreach ($tracks as $track): ?>
            <li>
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

                <a class="button colored" href="<?= ipConfig()->baseUrl() . 'online-courses/' . $track['trackId'] ?>">
                    Checkout module
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>