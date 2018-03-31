<?= ipSlot('xBreadcrumb', [
    ['uri' => $track['courseRootUri'], 'label' => $track['grooaCourse']['name']],
    ['uri' => "online-courses/" . $track['trackId'], 'label' => $track['title'], 'active' => true]
]) ?>

<div id="viewModule"></div>
