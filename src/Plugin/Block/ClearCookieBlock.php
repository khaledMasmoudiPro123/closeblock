<?php

namespace Drupal\closeblock\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'ClearCookie' Block.
 *
 * @Block(
 *   id = "closeblock_clear_cookie_block",
 *   admin_label = @Translation("Clear Cookie"),
 *   category = @Translation("Close block"),
 * )
 */
class ClearCookieBlock extends BlockBase {

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The current user account.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Class constructor.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param object $formBuilder
   *   The form builder.
   * @param object $currentUser
   *   The current user account.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $formBuilder, $currentUser) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $formBuilder;
    $this->currentUser = $currentUser;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = $this->formBuilder->getForm('Drupal\closeblock\Form\CloseBlockClearCookieForm');

    $build['closeblock_block'] = [
      '#theme' => 'custom_block_closeblock',
      '#content'  => $form,
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, $this->currentUser->hasPermission('close block'));
  }

}
