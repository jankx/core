<?php 
if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}
 ?>
<form <?php echo $form_attributes; ?>>
    <input <?php echo $input_attributes; ?>>
    <button type="submit"><?php echo $submit_text; ?></button>
</form>