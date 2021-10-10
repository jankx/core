<?php
namespace Jankx;

use Jankx\Option\Interfaces\Adapter;
use Jankx\Option\Framework;

class Option
{
    public static function get($optionName, $defaultValue = null)
    {
        $pre = apply_filters("jankx_get_option_{$optionName}", null, $defaultValue);
        if (!is_null($pre)) {
            return $pre;
        }

        $framework = Framework::getActiveFramework();
        if (is_null($framework)) {
            return $defaultValue;
        }

        if (!is_a($framework, Adapter::class)) {
            throw new \Exception(sprintf(
                'The option framework must be an instance of %s',
                Adapter::class
            ));
        }

        return $framework->getOption(
            $optionName,
            $defaultValue
        );
    }
}
