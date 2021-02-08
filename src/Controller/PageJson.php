<?php

namespace Drupal\site_api_key\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @file
 * Contains \Drupal\site_api_key\Controller\PageJson.
 */


/**
 * Provides route responses for pagejson routing.
 */
class PageJson extends ControllerBase {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a store LanguageManagerInterface.
   *
   * @param Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The Config Factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * Render location node content.
   */
  public function content(NodeInterface $node, Request $request) {
    $config = $this->config('system.site');
    $configkey = $config->get('siteapikey');
    $path = $request->getpathInfo();
    $arg = explode('/', $path);
    $siteapikey = $arg[2];
    if ($node->getType() == "page" && $configkey == $siteapikey) {
      $json_array['data']['nid'] = $node->id();
      $json_array['data']['title'] = $node->get('title')->value;
      return new JsonResponse($json_array);
    }
    else {
      $response = new RedirectResponse('/system/403');
      $response->send();
      exit;
    }
  }

}
