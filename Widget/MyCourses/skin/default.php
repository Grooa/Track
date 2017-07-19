<?php if (ipUser()->isLoggedIn()): ?>
    <h2>Your Courses</h2>
    <p>Courses you have recently purchased</p>
    <?= ipSlot('Track_userTracks', ['userId' => ipUser()->userId()])?>
<?php else: ?>
    <?= ipAdminId() ? '[Track_MyCourses: User not logged in]' : '' ?>
<?php endif; ?>
