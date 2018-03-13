<?php if (ipUser()->isLoggedIn()): ?>
    <?= ipSlot(
        'Track_userTracks',
        [
            'userId' => ipUser()->userId(),
            'grooaCourse' => 'the-mindful-leadership-gym'
        ]) ?>
<?php else: ?>
    <?= ipAdminId() ? '[Track_MyMindfulLeadershipGymModules: User not logged in]' : '' ?>
<?php endif; ?>
