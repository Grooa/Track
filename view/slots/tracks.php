<?php if (empty($error)): ?>

    <ul class="course-list">
        <?php $i = 1;
        foreach ($tracks as $track): ?>
            <li class="course-module clickable">
                <a href="<?= ipConfig()->baseUrl() . 'online-courses/' . $track['trackId'] ?>">
                    <div class="course-module-metadata">
                        <? // @todo:ffl - type could be other values, s.t. introduction, segment, webinar?>
                        <strong class="type">Module <?= $i ?></strong>
                        <h3 class="title"><?= $track['title'] ?></h3>
                    </div>

                    <div class="description"><?= !empty($track['shortDescription']) ? $track['shortDescription'] : '' ?></div>

                    <?php if (!empty($showCreatedOn) && $showCreatedOn == true): ?>
                        <em>Added
                            <time><?= $track['createdOn'] ?></time>
                        </em>
                    <?php endif; ?>

                    <button>Checkout</button>
                </a>
            </li>
            <?php $i += 1; endforeach; ?>
    </ul>

<?php else: ?>
    <p class="centered"><?= $error ?></p>
<?php endif; ?>
