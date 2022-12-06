<?php
namespace app\commands;

use app\rbac\AuthorRule;
use app\rbac\ResolutionRule;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {

        $auth = Yii::$app->authManager;

        $createDocument = $auth->createPermission('createDocument');
        $createDocument->description = 'Создавать документ';
        $auth->add($createDocument);

        $viewDocument = $auth->createPermission('viewDocument');
        $viewDocument->description = 'Просматривать документ';
        $auth->add($viewDocument);

        $updateDocument = $auth->createPermission('updateDocument');
        $updateDocument->description = 'Редактировать документ';
        $auth->add($updateDocument);

        $user = $auth->createRole('user');
        $user->description = 'Пользователь';
        $auth->add($user);

        $author = $auth->createRole('author');
        $author->description = 'Автор';
        $auth->add($author);
        $auth->addChild($author, $user);
        $auth->addChild($author, $createDocument);

        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';
        $auth->add($admin);
        $auth->addChild($admin, $viewDocument);
        $auth->addChild($admin, $updateDocument);
        $auth->addChild($admin, $author);

        $auth->assign($admin, 1);

        $rule = new AuthorRule;
        $auth->add($rule);

        $updateOwnDocument = $auth->createPermission('updateOwnDocument');
        $updateOwnDocument->description = 'Редактировать свой документ';
        $updateOwnDocument->ruleName = $rule->name;
        $auth->add($updateOwnDocument);
        $auth->addChild($updateOwnDocument, $updateDocument);
        $auth->addChild($author, $updateOwnDocument);

        $rule = new ResolutionRule();
        $auth->add($rule);

        $viewOwnDocument = $auth->createPermission('viewOwnDocument');
        $viewOwnDocument->description = 'Просматривать документ';
        $viewOwnDocument->ruleName = $rule->name;
        $auth->add($viewOwnDocument);
        $auth->addChild($viewOwnDocument, $viewDocument);
        $auth->addChild($user, $viewOwnDocument);
    }
}
