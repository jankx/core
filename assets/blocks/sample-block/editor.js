/**
 * Sample Block Editor Script
 *
 * Handles the editor interface for the Sample Block in Gutenberg.
 */

( function( blocks, element, blockEditor ) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType( 'jankx/sample-block', {
        edit: function( props ) {
            var blockProps = useBlockProps();
            var title = props.attributes.title;
            var content = props.attributes.content;

            return el(
                'div',
                blockProps,
                el(
                    'div',
                    { className: 'wp-block-jankx-sample-block-editor' },
                    el(
                        'input',
                        {
                            type: 'text',
                            value: title,
                            placeholder: 'Enter title...',
                            onChange: function( event ) {
                                props.setAttributes( { title: event.target.value } );
                            }
                        }
                    ),
                    el(
                        'textarea',
                        {
                            value: content,
                            placeholder: 'Enter content...',
                            onChange: function( event ) {
                                props.setAttributes( { content: event.target.value } );
                            }
                        }
                    )
                )
            );
        },
        save: function() {
            // Dynamic block, no save function needed as rendering is done server-side
            return null;
        }
    } );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );