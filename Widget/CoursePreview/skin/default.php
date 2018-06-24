<section class="gc-course-preview">
    <?php if (empty($course)): ?>
        <p>[No course is selected. Please select one]</p>
    <?php else: ?>
        <a href="<?= $course['url'] ?>" title="Go to <?= $course['name'] ?>">
            <div class="gc-course-preview-thumbnail-cover">
                <img src="<?= $course['cover'] ?>" alt="<?= $course['name'] ?>">
            </div>

            <div>
                <h3 class="gc-course-preview-name"><?= $course['name'] ?></h3>
                <p class="gc-course-preview-description"><?= $course['description'] ?></p>
            </div>
        </a>
    <?php endif; ?>
</section>
