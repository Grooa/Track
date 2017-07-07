<nav class="breadcrumbs">
    <a href="<?=ipConfig()->baseUrl()?>tracks">Tracks</a>
    <a href="<?=ipConfig()->baseUrl()?>tracks/<?=$track['trackId']?>" class="currentPage"><?=$track['title']?></a>
</nav>

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

<?php if (ipUser()->isLoggedIn() && !$hasPurchased): ?>
    <?php // No reason to load paypal before user has logged in ?>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>

    <script type="text/javascript">
        var CREATE_PAYMENT_URL = '<?=ipConfig()->baseUrl()?>paypal/create-payment?track=<?=$track['trackId']?>';
        var EXECUTE_PAYMENT_URL = '<?=ipConfig()->baseUrl()?>paypal/execute-payment?track=<?=$track['trackId']?>';

        paypal.Button.render({

            env: 'sandbox', // Or 'production'

            commit: true, // Show a 'Pay Now' button

            style: {
                size: 'responsive',
                color: 'blue',
                shape: 'pill',
                label: 'checkout'
            },

            payment: function () {
                return paypal.request.post(CREATE_PAYMENT_URL).then(function (data) {
                    return data.paymentID; // Returned from local REST-API
                });
            },

            onAuthorize: function (data) {
                return paypal.request.post(EXECUTE_PAYMENT_URL, {
                    paymentID: data.paymentID,
                    payerID: data.payerID
                }).then(function () {
                    location.reload();

                    // The payment is complete!
                    // You can now show a confirmation message to the customer
                });
            },

            onCancel: function (data, actions) {
                console.log('cancelled');
                console.log(data);
                console.log(actions);
            }

        }, '#paypal-button');
    </script>

<?php endif; ?>