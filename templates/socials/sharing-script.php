<script>
    var share_content = document.getElementById('jankx-sharing-content');
    if (share_content) {
        var jankx_socials_sharing = new Drop({
            target: document.querySelector('.jankx-socials-sharing .jankx-sharing-button'),
            content: share_content.innerHTML,
            classes: 'drop-theme-arrows',
            constrainToWindow: true,
            position: 'bottom center',
            openOn: 'click'
        })
    }
</script>
