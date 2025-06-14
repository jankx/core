<?php 
if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}
 ?>
<ul class="jankx-tabs post-layout-tabs">
    <?php foreach ($tabs as $tab) : ?>
        <?php if ($tab->isValid()) : ?>
        <li class="the-tab has-event<?php echo ($first_tab->type_name == $tab->type_name && $first_tab->object_id == $tab->object_id) ? ' active' : '' ?> "
            data-type="<?php echo $tab->type; ?>"
            data-type-name="<?php echo $tab->type_name; ?>"
            data-object-id="<?php echo $tab->object_id; ?>"
        >
            <a href="<?php echo $tab->url ? $tab->url : '#'; ?>" title="<?php echo $tab->title; ?>"><?php echo $tab->title; ?></a>
        </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

<?php echo $tab_content; ?>
