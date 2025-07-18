Jankx Assets is used to manage CSS, JS, libraries with beautiful syntax.
=


# WordPress syntax

```
// JS
wp_register_script('jankx-global-filter', $this->asset_url('js/global-filter.js'), array('choices', 'jankx-post-layout'), static::VERSION, true);
wp_localize_script('jankx-global-filter', 'jkx_global_filter', array(
  'ajax_url' => admin_url('admin-ajax.php'),
  'action' => 'request-post',
));
wp_enqueue_script('jankx-global-filter');


// CSS
wp_register_style('jankx-global-filter', $this->asset_url('css/global-filter.css'), array('choices'), static::VERSION);
wp_enqueue_style('jankx-global-filter');
```

# Jankx assets syntax

```
js(
    'jankx-global-filter',
    $this->asset_url('js/global-filter.js'),
    array('choices', 'jankx-post-layout'),
    static::VERSION,
    true
  )
  ->localize('jkx_global_filter', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'action' => 'request-post',
  ))
  ->enqueue();

css(
  'jankx-global-filter',
  $this->asset_url('css/global-filter.css'),
  array('choices'),
  static::VERSION
)->enqueue();
```
