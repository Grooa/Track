<?= ipSlot('xBreadcrumb', [
    ['uri' => "online-courses", 'label' => 'Online Courses'],
    ['uri' => "online-courses/" . $track['trackId'], 'label' => $track['title'], 'active' => true]
]) ?>

<?php
$isBusinessUser = ipUser()->isLoggedIn() ?
    \Plugin\GrooaUser\Model\GrooaUser::isBusinessUser(ipUser()->userId()) : false;
?>

    <h1><?= $track['title'] ?></h1>
    <div class="introduction"><?= $track['longDescription'] ?></div>

    <section class="metadata">
        <?php if (!$hasPurchased): ?>
            <em class="price">Price: <strong><?= !empty($track['price']) ? $track['price'] : 0.0 ?> €</strong></em>
        <?php else: ?>
            <div class="continue">Course Purchased</div>
        <?php endif; ?>

        <?php if (!ipUser()->isLoggedIn()): ?>
            <?// Notify user to login, before he purchases?>
            <a href="<?= ipConfig()->baseUrl() ?>login" class="button login">Login to purchase</a>
        <?php endif; ?>

        <?php if (ipUser()->isLoggedIn() && !$hasPurchased): ?>
            <?// Ensure only a personal account can purchase through PayPal ?>
            <?php if (!$isBusinessUser): ?>
                <div id="paypal-button" class="paypal-autorendered"></div>
            <?php else: ?>
                <a class="button btn-business"
                   href="<?=ipConfig()->baseUrl()?>online-courses/contact/?course=<?=$track['trackId']?>">Contact sales to purchase</a>
            <?php endif; ?>
        <?php endif; ?>
    </section>

    <section>
        <h2>Videos</h2>
        <small class="description">Bellow are the included videos for this track</small>

        <?= ipSlot('Track_listCourses', ['track' => $track, 'hasPurchased' => $hasPurchased]) ?>
    </section>

<?php if (ipUser()->isLoggedIn() && !$hasPurchased && !$isBusinessUser): ?>
    <?// Only load PayPal when necessary ?>
    <?= ipSlot('paypalCheckout', ['trackId' => $track['trackId']]) ?>
<?php endif; ?>