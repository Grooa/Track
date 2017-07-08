<nav class="breadcrumbs">
    <a href="<?=ipConfig()->baseUrl()?>tracks">Tracks</a>
    <a href="<?=ipConfig()->baseUrl()?>tracks/<?=$track['trackId']?>" class="currentPage"><?=$track['title']?></a>
</nav>

<?=ipSlot('socialshare')?>

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
</section>

<?php if (ipUser()->isLoggedIn() && !$hasPurchased): ?>
    <div id="paypal-button" class="paypal"></div>
<?php endif; ?>

<section>
    <h2>Courses</h2>
    <small class="description">Bellow is the included courses for each track</small>

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
</section>

<?=!empty($payPalCheckout) ? $payPalCheckout : ''?>