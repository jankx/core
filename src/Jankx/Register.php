<?php
namespace Jankx;

use Jankx;

class Register
{
    private static function registerSidebar($id, $args)
    {
        $args = wp_parse_args(
            $args,
            apply_filters(
                'jankx_sidebar_args',
                array(
                    'before_widget' => '<div id="%1%s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h4 class="wtitle widget-title"><span>',
                    'after_title'   => '</span></h4>',
                )
            )
        );
        $args['id'] = $id;
        register_sidebar($args);
    }

    public static function menus()
    {
        $supportMenus = apply_filters('jankx_support_menus', array(
            'primary' => __('Primary Menu', 'jankx'),
        ));
        foreach ($supportMenus as $location => $description) {
            register_nav_menu($location, $description);
        }
    }

    public static function sidebars()
    {
        $maxSidebars = apply_filters('jankx_support_max_sidebars', 2);

        if ($maxSidebars > 0) {
            self::registerSidebar(
                'primary',
                apply_filters(
                    'jankx_sidebar_primary_args',
                    array(
                        'name' => __('Primary Sidebar', 'jankx')
                    )
                )
            );
        }
        if ($maxSidebars > 1) {
            self::registerSidebar(
                'alternative',
                apply_filters(
                    'jankx_sidebar_alt_args',
                    array(
                        'name' => __('Second Sidebar', 'jankx')
                    )
                )
            );
        }

        $additionSidebars = apply_filters('jankx_addition_sidebars', array());
        if (empty($additionSidebars)) {
            self::registerFooterWidgets($sidebarArgs);
            return;
        }
        foreach ($additionSidebars as $additionSidebar => $sidebarArgs) {
            self::registerSidebar(
                $additionSidebar,
                apply_filters(
                    "jankx_sidebar_{$additionSidebar}_args",
                    $sidebarArgs
                )
            );
        }
        self::registerFooterWidgets($sidebarArgs);
    }

    public static function getFooterWigetColumns()
    {
        return apply_filters('jankx_footer_widget_columns', 4);
    }
    public static function getFooterWidgetPrefix()
    {
        return apply_filters('jankx_footer_widget_prefix', 'footer-');
    }

    public static function registerFooterWidgets($sidebarArgs = array())
    {
        $numOfFooterWidgets = self::getFooterWigetColumns();
        $footerWidgetPrefix = self::getFooterWidgetPrefix();
        for ($i = 1; $i<= $numOfFooterWidgets; $i++) {
            $sidebarArgs['name'] = sprintf(__('Footer %d', 'jankx'), $i);

            self::registerSidebar(
                sprintf('%s-%s', $footerWidgetPrefix, $i),
                apply_filters("jankx_sidebar_{$footerWidgetPrefix}_{$i}_args", $sidebarArgs)
            );
        }
    }
}
