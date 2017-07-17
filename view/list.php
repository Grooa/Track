<?= ipSlot('xBreadcrumb', [['uri' => "tracks", 'label' => 'Tracks', 'active' => true]]) ?>

<h1>Online Courses</h1>
<small class="description">
    Accompanying our <em>hands on</em> and <em>live courses</em>, Grooa now offers <em>online courses</em>.
    These are courses you can purchase and watch on your computer or mobile devices,
    at your own convenience and time.
</small>

<!--TODO:ffl - Convert to Slot -->
<section>
    <ul class="tiled shadowed">
        <?php foreach ($tracks as $track): ?>
            <li>
                <div class="thumbnail">
                    <img src="<?= ipFileUrl('file/repository/' . $track['thumbnail']) ?>" alt="">
                </div>
                <h3><?= $track['title'] ?></h3>
                <strong class="price-tiled">Price: € <?=!empty($track['price']) ? $track['price'] : 0 ?></strong>
                <div class="description"><?= $track['shortDescription'] ?></div>
                <a class="button colored" href="<?= ipConfig()->baseUrl() . 'tracks/' . $track['trackId'] ?>">Checkout course</a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

