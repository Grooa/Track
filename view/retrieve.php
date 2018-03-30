<article>
    <?= ipSlot('xBreadcrumb', [
        ['uri' => $track['courseRootUri'], 'label' => $track['grooaCourse']['name']],
        ['uri' => "online-courses/" . $track['trackId'], 'label' => $track['title'], 'active' => true]
    ]) ?>

    <?php
    $isBusinessUser = ipUser()->isLoggedIn() ?
        \Plugin\GrooaUser\Model\GrooaUser::isBusinessUser(ipUser()->userId()) : false;
    ?>

    <h1><?= $track['title'] ?></h1>

    <section class="metadata course-actions">
        <?php if (!$hasPurchased): ?>
            <strong class="price"><?= !empty($track['price']) ? $track['price'] : 0.0 ?> €</strong>
        <?php endif; ?>

        <?php if (!ipUser()->isLoggedIn()): ?>
            <? // Notify user to login, before he purchases?>
            <a href="<?= ipConfig()->baseUrl() ?>login"
               class="button login"
               data-track-content
               data-content-name="Master Class purchase login">
                Login to access</a>
        <?php endif; ?>

        <?php if (ipUser()->isLoggedIn() && !$hasPurchased): ?>
            <a
                class="button btn-business"
                href="<?= ipConfig()->baseUrl() ?>online-courses/contact/?course=<?= $track['trackId'] ?>"
                data-track-content
                data-content-name="Master Class sales contact">
                Contact us to acquire module</a>
        <?php endif; ?>
    </section>

    <div class="video-metadata columns">
        <section class="course-information no-fill">
            <h2 class="clean">Videos</h2>

            <?php if (count($track['courses']) > 0): ?>
                <?= ipSlot('Track_listCourses', ['track' => $track, 'hasPurchased' => $hasPurchased]) ?>
            <?php else: ?>
                <div class="data">
                    <p class="centered">This module hasn't any videos available</p>
                </div>
            <?php endif; ?>
        </section>

        <section class="course-information">
            <h2 class="clean">About this module</h2>

            <div class="data"><?= $track['longDescription'] ?></div>
        </section>
    </div>
</article>
