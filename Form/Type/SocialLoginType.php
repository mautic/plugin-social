<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticSocialBundle\Form\Type;

use Mautic\PluginBundle\Helper\IntegrationHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


/**
 * Class FacebookLoginType
 *
 * @package Mautic\FormBundle\Form\Type
 */
class SocialLoginType extends AbstractType
{
    /**
     * @var IntegrationHelper
     */
    private $helper;

    /**
     * SocialLoginType constructor.
     *
     * @param IntegrationHelper $helper
     */
    public function __construct(IntegrationHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $integrations       = '';
        $integrationObjects = $this->helper->getIntegrationObjects(null, 'login_button');

        foreach ($integrationObjects as $integrationObject) {
            if ($integrationObject->getIntegrationSettings()->isPublished()) {
                /** @var \Mautic\AssetBundle\Model\AssetModel $model */
                $model = $this->factory->getModel('form');
                $integrations .= $integrationObject->getName().",";
                $integration = [
                    'integration' => $integrationObject->getName(),
                ];

                $builder->add(
                    'authUrl_'.$integrationObject->getName(),
                    'hidden',
                    [
                        'data' => $model->buildUrl('mautic_integration_auth_user', $integration, true, []),
                    ]
                );

            }
        }

        $builder->add(
            'integrations',
            'hidden',
            [
                'data' => $integrations,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "sociallogin";
    }
}