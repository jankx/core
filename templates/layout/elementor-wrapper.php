<style>
    .jankx-base .jankx-container {
        max-width: <?php printf('%s%s', $desktop['width'], $desktop['unit']); ?>;
    }
    @media(max-width: <?php echo $breakpoints['lg'] ?>px) {
        .jankx-base .jankx-container {
            max-width: <?php printf('%s%s', $tablet['width'], $tablet['unit']); ?>;
        }
    }
    @media(max-width: <?php echo $breakpoints['md'] ?>px) {
        .jankx-base .jankx-container {
            max-width: <?php printf('%s%s', $mobile['width'], $mobile['unit']); ?>;
        }
    }
</style>
