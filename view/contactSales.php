<?= ipSlot('xBreadcrumb', [
    ['uri' => "online-courses", 'label' => 'Online Courses'],
    ['uri' => "online-courses/" . $track['trackId'], 'label' => $track['title']],
    ['uri' => 'online-courses/contact', 'label' => 'Contact Sales', 'active' => true]
]) ?>

<?php echo ipRenderWidget('Heading', array('title' => __('Contact Sales', 'Track', false))); ?>

<?php echo ipBlock('introduction')->render() ?>

<div class="ipWidget">
    <?= $form->render() ?>
</div>
