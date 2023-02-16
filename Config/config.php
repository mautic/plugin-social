<?php

return [
    'name'        => 'Social Media',
    'description' => 'Enables integrations with Mautic supported social media services.',
    'version'     => '1.0',
    'author'      => 'Mautic',

    'routes' => [
        'main' => [
            'mautic_social_index' => [
                'path'       => '/monitoring/{page}',
                'controller' => 'MauticPlugin\MauticSocialBundle\Controller\MonitoringController::indexAction',
            ],
            'mautic_social_action' => [
                'path'       => '/monitoring/{objectAction}/{objectId}',
                'controller' => 'MauticPlugin\MauticSocialBundle\Controller\MonitoringController::executeAction',
            ],
            'mautic_social_contacts' => [
                'path'       => '/monitoring/view/{objectId}/contacts/{page}',
                'controller' => 'MauticPlugin\MauticSocialBundle\Controller\MonitoringController::contactsAction',
            ],
            'mautic_tweet_index' => [
                'path'       => '/tweets/{page}',
                'controller' => 'MauticPlugin\MauticSocialBundle\Controller\TweetController::indexAction',
            ],
            'mautic_tweet_action' => [
                'path'       => '/tweets/{objectAction}/{objectId}',
                'controller' => 'MauticPlugin\MauticSocialBundle\Controller\TweetController::executeAction',
            ],
        ],
        'api' => [
            'mautic_api_tweetsstandard' => [
                'standard_entity' => true,
                'name'            => 'tweets',
                'path'            => '/tweets',
                'controller'      => 'MauticPlugin\MauticSocialBundle\Controller\Api\TweetApiController',
            ],
        ],
        'public' => [
            'mautic_social_js_generate' => [
                'path'       => '/social/generate/{formName}.js',
                'controller' => 'MauticPlugin\MauticSocialBundle\Controller\JsController::generateAction',
            ],
        ],
    ],

    'services' => [
        'models' => [
            'mautic.social.model.monitoring' => [
                'class' => 'MauticPlugin\MauticSocialBundle\Model\MonitoringModel',
            ],
            'mautic.social.model.postcount' => [
                'class' => 'MauticPlugin\MauticSocialBundle\Model\PostCountModel',
            ],
            'mautic.social.model.tweet' => [
                'class' => 'MauticPlugin\MauticSocialBundle\Model\TweetModel',
            ],
        ],
        'others' => [
            'mautic.social.helper.campaign' => [
                'class'     => 'MauticPlugin\MauticSocialBundle\Helper\CampaignEventHelper',
                'arguments' => [
                    'mautic.helper.integration',
                    'mautic.page.model.trackable',
                    'mautic.page.helper.token',
                    'mautic.asset.helper.token',
                    'mautic.social.model.tweet',
                ],
            ],
            'mautic.social.helper.twitter_command' => [
                'class'     => \MauticPlugin\MauticSocialBundle\Helper\TwitterCommandHelper::class,
                'arguments' => [
                    'mautic.lead.model.lead',
                    'mautic.lead.model.field',
                    'mautic.social.model.monitoring',
                    'mautic.social.model.postcount',
                    'translator',
                    'doctrine.orm.entity_manager',
                    'mautic.helper.core_parameters',
                ],
            ],
        ],
        'integrations' => [
            'mautic.integration.facebook' => [
                'class'     => \MauticPlugin\MauticSocialBundle\Integration\FacebookIntegration::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'session',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                    'mautic.helper.integration',
                ],
            ],
            'mautic.integration.foursquare' => [
                'class'     => \MauticPlugin\MauticSocialBundle\Integration\FoursquareIntegration::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'session',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                    'mautic.helper.integration',
                ],
            ],
            'mautic.integration.instagram' => [
                'class'     => \MauticPlugin\MauticSocialBundle\Integration\InstagramIntegration::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'session',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                    'mautic.helper.integration',
                ],
            ],
            'mautic.integration.linkedin' => [
                'class'     => \MauticPlugin\MauticSocialBundle\Integration\LinkedInIntegration::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'session',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                    'mautic.helper.integration',
                ],
            ],
            'mautic.integration.twitter' => [
                'class'     => \MauticPlugin\MauticSocialBundle\Integration\TwitterIntegration::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'session',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                    'mautic.helper.integration',
                ],
            ],
        ],
    ],
    'menu' => [
        'main' => [
            'mautic.social.monitoring' => [
                'route'    => 'mautic_social_index',
                'parent'   => 'mautic.core.channels',
                'access'   => 'mauticSocial:monitoring:view',
                'priority' => 0,
            ],
            'mautic.social.tweets' => [
                'route'    => 'mautic_tweet_index',
                'access'   => ['mauticSocial:tweets:viewown', 'mauticSocial:tweets:viewother'],
                'parent'   => 'mautic.core.channels',
                'priority' => 80,
                'checks'   => [
                    'integration' => [
                        'Twitter' => [
                            'enabled' => true,
                        ],
                    ],
                ],
            ],
        ],
    ],

    'categories' => [
        'plugin:mauticSocial' => 'mautic.social.monitoring',
    ],

    'twitter' => [
        'tweet_request_count' => 100,
    ],

    'parameters' => [
        'twitter_handle_field' => 'twitter',
    ],
];
