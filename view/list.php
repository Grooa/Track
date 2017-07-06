<?php ?>
<h1>Online Courses</h1>
<small>
    Accompanying our hands on and live courses, Grooa now offers online courses.
    These are courses you can purchase and watch at your computer or mobile devices,
    at your own convenience and time.
</small>

<section>
    <ul class="tiled">
        <?php foreach ($tracks as $track): ?>
            <li>
                <div class="thumbnail">
                    <img src="<?= ipFileUrl('file/repository/' . $track['thumbnail']) ?>" alt="">
                </div>
                <h3><?= $track['title'] ?></h3>
                <strong class="price-tiled">Price: € <?=!empty($track['price']) ? $track['price'] : 0 ?></strong>
                <div class="description"><?= $track['shortDescription'] ?></div>
                <a class="button colored" href="/ImpressPages/tracks/<?= $track['trackId'] ?>">Checkout course</a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

