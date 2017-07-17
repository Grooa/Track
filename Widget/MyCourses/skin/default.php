<?= ipUser()->isLoggedIn() ?
    ipSlot('Track_userTracks', ['userId' => ipUser()->userId() ]) :
    '[Track_MyCourses: User not logged in]'
?>