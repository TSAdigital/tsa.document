<?php
namespace app\rbac;

use yii\rbac\Rule;

/**
 * Проверяем authorID на соответствие с пользователем, переданным через параметры
 */
class ResolutionRule extends Rule
{
    public $name = 'isResolution';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if(!empty($params['document_resolution']->resolution) and is_array($params['document_resolution']->resolution)){
           return $params['document_resolution'] && (in_array($user, $params['document_resolution']->resolution) or $params['document_resolution']->author == $user);
        }
        return true;
    }
}
