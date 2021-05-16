<h1 class="page-header">
<?php
if (is_a($queried_object, WP_Term::class)) {
    echo $queried_object->name;
}
?>
</h1>
