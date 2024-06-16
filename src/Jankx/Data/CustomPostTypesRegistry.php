<?php

namespace Jankx\Data;

use Jankx\Configs\PostTypeConfigurations;
use Jankx\GlobalConfigs;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CustomPostTypesRegistry
{
    public function registerPostTypes()
    {
        $encoders    = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer  = new Serializer($normalizers, $encoders);

        $postTypeConfigs = GlobalConfigs::get('post_types', []);
        if (count($postTypeConfigs) > 0) {
            foreach ($postTypeConfigs as $configs) {
                /**
                 * @var \Jankx\Configs\PostTypeConfigurations
                 */
                $postTypeConfig = $serializer->denormalize($configs, PostTypeConfigurations::class, 'json');
                register_post_type(
                    $postTypeConfig->getType(),
                    apply_filters(
                        'jankx/post_types/' . $postTypeConfig->getType() . '/args',
                        $postTypeConfig->getOptions()
                    )
                );
            }
        }
    }
}
