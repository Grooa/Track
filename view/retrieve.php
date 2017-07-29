<?= ipSlot('xBreadcrumb', [
    ['uri' => "tracks", 'label' => 'Tracks'],
    ['uri' => "tracks/" . $track['trackId'], 'label' => $track['title'], 'active' => true]
]) ?>

    <h1><?= $track['title'] ?></h1>
    <div class="introduction"><?= $track['longDescription'] ?></div>

    <section class="metadata">
        <?php if (!$hasPurchased): ?>
            <em class="price">Price: <strong><?= !empty($track['price']) ? $track['price'] : 0.0 ?> €</strong></em>
        <?php else: ?>
            <div class="continue">Purchased:
                <date><?= $purchasedOn ?></date>
            </div>
        <?php endif; ?>

        <?php if (!ipUser()->isLoggedIn()): ?>
            <a href="<?= ipConfig()->baseUrl() ?>login" class="button login">Login to purchase</a>
        <?php endif; ?>

        <?php if (ipUser()->isLoggedIn() && !$hasPurchased): ?>
            <div id="paypal-button" class="paypal-autorendered"></div>
        <?php endif; ?>
    </section>

    <section>
        <h2>Videos</h2>
        <small class="description">Bellow are the included videos for this track</small>

        <?= ipSlot('Track_listCourses', ['track' => $track, 'hasPurchased' => $hasPurchased]) ?>
    </section>

<?php if (ipUser()->isLoggedIn() && !$hasPurchased): ?>
    <?= ipSlot('paypalCheckout', ['trackId' => $track['trackId']]) ?>
<?php endif; ?>