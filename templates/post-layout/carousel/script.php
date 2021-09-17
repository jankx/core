<script>
    var <?php echo $var; ?>__configs = <?php echo json_encode($config); ?>;
    var <?php echo $var; ?> = new Splide('#<?php echo $id; ?>', <?php echo $var; ?>__configs).mount();
</script>
