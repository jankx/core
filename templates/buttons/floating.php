<?php 
if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}
 ?>
<div class="floating-buttons <?= $this->e($style); ?>-style">
    <div class="buttons-wrap">
    <?php foreach ($buttons as $button) {
        $data = $button;
        if (!isset($button['effect'])) {
            $data['effect'] = $effect;
        }
        if (!isset($button['target'])) {
            $data['target'] = $target;
        }
        jankx_template('buttons/button', $data);
    }
    ?>
    </div>
</div>
