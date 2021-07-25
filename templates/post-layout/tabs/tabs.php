<ul class="jankx-tabs post-layout-tabs">
    <?php foreach ($tabs as $tab) : ?>
        <?php if ($tab->isValid()) : ?>
        <li class="the-tab<?php echo ($first_tab->type_name == $tab->type_name && $first_tab->object_id == $tab->object_id) ? ' active' : '' ?> "
            data-type="<?php echo $tab->type; ?>"
            data-type-name="<?php echo $tab->type_name; ?>"
            data-object-id="<?php echo $tab->object_id; ?>"
        >
            <?php if ($tab->url) : ?>
                <a href="<?php echo $tab->url; ?>" title="<?php echo $tab->title; ?>"><?php echo $tab->title; ?></a>
            <?php else : ?>
                <?php echo $tab->title; ?>
            <?php endif; ?>
        </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

<?php echo $tab_content; ?>
