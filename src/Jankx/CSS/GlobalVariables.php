<?php
namespace Jankx\CSS;

class GlobalVariables
{
    protected static $variables;

    protected static function getDefaultVariables()
    {
        return array(
            'body-font' => 'Montserrat, san-serif',
        );
    }

    public static function init()
    {
        if (is_null(static::$variables)) {
            static::$variables = apply_filters(
                'jankx/css/variables',
                static::getDefaultVariables()
            );
        }
        add_action('wp_head', array(__CLASS__, 'defineGlobalVariables'), 5);
    }

    protected static function writeValue($value, $variableName = null)
    {
        return $value;
    }

    public static function defineGlobalVariables()
    {
        $globalVariables = static::$variables;
        ?>
        <!-- Jankx Global CSS Variables -->
        <style>
            :root {
                <?php
                foreach ($globalVariables as $globalVariable => $value) {
                    printf("--%s: %s;\n", $globalVariable, static::writeValue($value, $globalVariable));
                }
                ?>
            }
        </style>
        <!-- / Global CSS Variables. -->
        <?php
    }
}
