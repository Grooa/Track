<?php if (!empty($tracks)): ?>

    <ul class="tiled shadowed">
        <?php foreach ($tracks as $track): ?>
            <li>
                <div class="thumbnail">
                    <img src="<?= ipFileUrl('file/repository/' . $track['thumbnail']) ?>" alt="">
                </div>
                <h3><?= $track['title'] ?></h3>

                <?php if (empty($hasPurchased) || $hasPurchased == false): ?>
                    <strong class="price-tiled">Price: € <?= !empty($track['price']) ? $track['price'] : 0.0 ?></strong>
                <?php endif; ?>

                <div class="description"><?= !empty($track['shortDescription']) ? $track['shortDescription'] : '' ?></div>

                <?php if (!empty($showCreatedOn) && $showCreatedOn == true): ?>
                    <em>Added <time><?=$track['createdOn']?></time></em>
                <?php endif; ?>

                <a class="button colored" href="<?= ipConfig()->baseUrl() . 'tracks/' . $track['trackId'] ?>">
                    Checkout course
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>