<section class="card track">
    <div class="metadata">
        <div class="thumbnail <?= !empty($hasPurchased) && $hasPurchased == false ? 'blurred' : '' ?>">
            <img src="<?= ipFileUrl('file/repository/' . $track['thumbnail']) ?>" alt="<?=$track['title']?>">
        </div>

        <?php if (empty($hasPurchased) || $hasPurchased == false): ?>
            <strong class="price-tiled">€ <?=!empty($track['price']) ? $track['price'] : 0 ?></strong>
        <?php endif; ?>
    </div>

    <h3><?= $track['title'] ?></h3>
    <div class="description"><?= !empty($track['shortDescription']) ? $track['shortDescription'] : '' ?></div>

    <?php if (!empty($showCreatedOn) && $showCreatedOn == true): ?>
        <em>Added <time><?=$track['createdOn']?></time></em>
    <?php endif; ?>

    <a class="button colored" href="<?= ipConfig()->baseUrl() . 'online-courses/' . $track['trackId'] ?>">
        Checkout course
    </a>
</section>