<?php if (ipUser()->isLoggedIn()): ?>
    <?= ipSlot(
        'Track_userTracks',
        [
            'userId' => ipUser()->userId(),
            'grooaCourse' => 'the-clear-master-class'
        ]) ?>
<?php else: ?>
    <?= ipAdminId() ? '[Track_MyCourses: User not logged in]' : '' ?>
<?php endif; ?>
