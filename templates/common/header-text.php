<?php
// This is hack for renderer don't send view all URL to template
if (!isset($url)) {
    $url = array();
}

$u = array_get($url, 'url', false);
$classes = array('widget-text-header');
if ($u) {
    $classes[] = 'has-link';
}
$attributes = array(
    'class' => $classes
);
?>
<h3 <?php echo jankx_generate_html_attributes($attributes); ?>>
    <?php if ($u) : ?>
        <a href="<?php echo $url; ?>"><?php echo $text; ?></a>
    <?php else : ?>
        <?php echo $text; ?>
    <?php endif; ?>
</h3>
