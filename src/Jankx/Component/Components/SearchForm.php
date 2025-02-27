<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\Component;

class SearchForm extends Component
{
    const COMPONENT_NAME = 'search_form';

    public function getName()
    {
        return static::COMPONENT_NAME;
    }

    public function parseProps($props)
    {
        $this->props = wp_parse_args($props, array(
            'id' => null,
            'action' => '',
            'method' => 'GET',
            'live_search' => false,
            'live_search_url' => '',
            'placeholder' => '',
            'submit_text' => __('Submit', 'jankx'),
            'input_name' => 's',
        ));
    }

    public function render()
    {
        $formAttributes = array(
            'method' => strtoupper($this->props['method']),
            'action' => $this->props['action'],
            'class' => 'jankx-search-form',
        );
        $inputAttributes = array(
            'type' => 'text',
            'placeholder' => $this->props['placeholder'],
            'name' => $this->props['input_name'],
            'value' => get_search_query(),
        );

        if ($this->props['id']) {
            $formAttributes['id'] = "jankx-form-{$this->props['id']}";
        }

        return $this->renderViaEngine(
            'search_form',
            array(
                'form_attributes' => jankx_generate_html_attributes($formAttributes),
                'input_attributes'  => jankx_generate_html_attributes($inputAttributes),
                'submit_text' => $this->props['submit_text'],
            ),
            'seach_form',
            false // Do not echo the template
        );
    }
}
