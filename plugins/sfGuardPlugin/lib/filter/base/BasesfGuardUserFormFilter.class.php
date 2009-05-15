<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * sfGuardUser filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasesfGuardUserFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'username'                      => new sfWidgetFormFilterInput(),
      'algorithm'                     => new sfWidgetFormFilterInput(),
      'salt'                          => new sfWidgetFormFilterInput(),
      'password'                      => new sfWidgetFormFilterInput(),
      'created_at'                    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'last_login'                    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'is_active'                     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_super_admin'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'sf_guard_user_permission_list' => new sfWidgetFormPropelChoice(array('model' => 'sfGuardPermission', 'add_empty' => true)),
      'sf_guard_user_group_list'      => new sfWidgetFormPropelChoice(array('model' => 'sfGuardGroup', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'username'                      => new sfValidatorPass(array('required' => false)),
      'algorithm'                     => new sfValidatorPass(array('required' => false)),
      'salt'                          => new sfValidatorPass(array('required' => false)),
      'password'                      => new sfValidatorPass(array('required' => false)),
      'created_at'                    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'last_login'                    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'is_active'                     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_super_admin'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'sf_guard_user_permission_list' => new sfValidatorPropelChoice(array('model' => 'sfGuardPermission', 'required' => false)),
      'sf_guard_user_group_list'      => new sfValidatorPropelChoice(array('model' => 'sfGuardGroup', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addsfGuardUserPermissionListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(sfGuardUserPermissionPeer::USER_ID, sfGuardUserPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(sfGuardUserPermissionPeer::PERMISSION_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(sfGuardUserPermissionPeer::PERMISSION_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function addsfGuardUserGroupListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(sfGuardUserGroupPeer::USER_ID, sfGuardUserPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(sfGuardUserGroupPeer::GROUP_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(sfGuardUserGroupPeer::GROUP_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'sfGuardUser';
  }

  public function getFields()
  {
    return array(
      'id'                            => 'Number',
      'username'                      => 'Text',
      'algorithm'                     => 'Text',
      'salt'                          => 'Text',
      'password'                      => 'Text',
      'created_at'                    => 'Date',
      'last_login'                    => 'Date',
      'is_active'                     => 'Boolean',
      'is_super_admin'                => 'Boolean',
      'sf_guard_user_permission_list' => 'ManyKey',
      'sf_guard_user_group_list'      => 'ManyKey',
    );
  }
}
