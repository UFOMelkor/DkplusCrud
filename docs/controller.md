# Controller

Working with the [`DkplusCrud\Controller\Controller`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Controller.php) is not about writing actions, it's about adding actions and features.

## Actions

Instead of writing actions you can simple add them to the controller:

```php
use DkplusCrud\Controller\Controller;
use DkplusCrud\Controller\Action;

$controller = new Controller();
$controller->addAction('create', new Action\SingleEntityAction());
$controller->addAction('update', new Action\UpdateFormAction());
```
would be simple the same as

```php
class MyController
{
    public function createAction()
    {
    }

    public function updateAction()
    {
    }
}
```

Every action must implement [`DkplusCrud\Controller\Action\ActionInterface`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Action/ActionInterface.php). Actually there are 3 different actions:
- [`DefaultAction`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Action/DefaultAction.php) which can be used in most cases.
- [`SingleEntityAction`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Action/SingleEntityAction.php) that should be used in most cases working on a single entity like showing or deleting.
- [`UpdateFormAction`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Action/UpdateFormAction.php) that should be used for updating an entity.