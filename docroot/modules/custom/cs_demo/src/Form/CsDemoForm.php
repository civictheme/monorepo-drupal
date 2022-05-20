<?php

namespace Drupal\cs_demo\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\cs_demo\CsDemoRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CsDemoForm.
 *
 * Admin form to provision demo items.
 *
 * @package Drupal\cs_demo\Form
 */
class CsDemoForm extends FormBase implements ContainerInjectionInterface {

  /**
   * The demo repository instance.
   *
   * @var \Drupal\cs_demo\CsDemoRepository
   */
  protected $demoRepository;

  /**
   * CsDemoForm constructor.
   */
  public function __construct(CsDemoRepository $demoRepository) {
    $this->demoRepository = $demoRepository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      CsDemoRepository::getInstance()
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cs_demo_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $info = $this->demoRepository->getInfo();

    foreach ($info as $item) {
      $options[$item['entity_type'] . '__' . $item['bundle']] = [
        $item['entity_type'],
        $item['bundle'],
        $item['#weight'],
        count($this->demoRepository->getEntities($item['entity_type'], $item['bundle'])),
      ];
    }

    $header = [
      $this->t('Entity'),
      $this->t('Bundle'),
      $this->t('Weight'),
      $this->t('Created count'),
    ];
    $form['table'] = [
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $options,
    ];

    $form['generate'] = [
      '#type' => 'submit',
      '#name' => 'generate',
      '#value' => $this->t('Generate'),
    ];

    $form['delete'] = [
      '#type' => 'submit',
      '#name' => 'delete',
      '#value' => $this->t('Delete'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $results = array_filter($form_state->getValue('table'));

    $info = $this->demoRepository->getInfo();
    // Sort results by weight set in info.
    $results = array_intersect_key(array_merge($info, $results), $results);

    $info = [];
    foreach ($results as $result) {
      [$entity_type, $bundle] = explode('__', $result);
      $item_info = $this->demoRepository->findInfo($entity_type, $bundle);
      if ($item_info) {
        $info[] = $item_info;
      }
    }

    $triggering_element = $form_state->getTriggeringElement();
    $button_name = $triggering_element['#name'];
    if ($button_name === 'generate') {
      $this->demoRepository->createBatch($info);
    }
    elseif ($button_name === 'delete') {
      $this->demoRepository->removeBatch($info);
    }
  }

}
