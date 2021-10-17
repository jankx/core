<ul class="jankx-tabs post-layout-tabs">
    <?php foreach($tabs as $tab): ?>
        <li class="the-tab<?php echo $tab->isActive() ? ' active' : ''; ?>">
            <a
                href="<?php echo esc_attr($tab->url); ?>"
                title="<?php echo $this->e($tab->title) ?>"
                <?php echo jankx_generate_html_attributes($tab->attributes); ?>
            >
                <?php echo $this->e($tab->title); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
