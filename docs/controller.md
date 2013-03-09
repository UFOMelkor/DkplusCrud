# Contents

- [Controller](#controller)
    - [Actions](#actions)
        - [DefaultAction](#defaultaction)
        - [SingleEntityAction](#singleentityaction)
        - [UpdateFormAction](#updateformaction)
    - [Features](#features)
        - [AjaxFormSupport](#ajaxformsupport)
        - [AjaxLayoutDisabling](#ajaxlayoutdisabling)
        - [Assigning](#assigning)
        - [CreationFormProvider](#creationformprovider)
        - [Deletion](#deletion)
        - [EntitiesProvider](#entitiesprovider)
        - [EntityProvider](#entityprovider)
        - [FormHandling](#formhandling)
        - [IdentifierProvider](#identifierprovider)
        - [MultipleInputFilter](#multipleinputfilter)
        - [NotFoundReplacing](#notfoundreplacing)
        - [PaginationProvider](#paginationprovider)
        - [Rendering](#rendering)
        - [SingleInputFilter](#singleinputfilter)
        - [UpdateFormProvider](#updateformprovider)

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
would be nearly the same as

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

Every action must implement [`DkplusCrud\Controller\Action\ActionInterface`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Action/ActionInterface.php). It triggers at least three events, a `pre`-, `main`- and a `post`-event using [`DkplusCrud\Controller\Event`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Event.php). For more informations about the event please have a look at the source code.
Actually there are 3 different actions:

### DefaultAction

[`DkplusCrud\Controller\Action\DefaultAction`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Action/DefaultAction.php)

Can be used in most cases. It does not more than triggering the three events und returning the event result.

### SingleEntityAction

[`DkplusCrud\Controller\Action\SingleEntityAction`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Action/SingleEntityAction.php)

Should be used in most cases working on a single entity like showing or deleting. If there is no entity available after the `pre`-event a `notFound`-event will be triggered instead of `main`- and `post`-event.

### UpdateFormAction

[`DkplusCrud\Controller\Action\UpdateFormAction`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Action/UpdateFormAction.php)

Should be used for updating an entity. Like the `SingleEntityAction` it triggers a `notFound`-event if no form is available after the `pre`-event.

## Features

### AjaxFormSupport

[`DkplusCrud\Controller\Feature\AjaxFormSupport`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/AjaxFormSupport.php)

**Event:** `post`

### AjaxLayoutDisabling

[`DkplusCrud\Controller\Feature\AjaxLayoutDisabling`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/AjaxLayoutDisabling.php)

**Event:** `post`

### Assigning

[`DkplusCrud\Controller\Feature\Assigning`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/Assigning.php)
**Event:** configurable (default: `post`)


### CreationFormProvider

[`DkplusCrud\Controller\Feature\CreationFormProvider`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/CreationFormProvider.php)

**Event:** `pre`

### Deletion

[`DkplusCrud\Controller\Feature\Deletion`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/Deletion.php)

**Event:** `main`

### EntitiesProvider

[`DkplusCrud\Controller\Feature\EntitiesProvider`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/EntitiesProvider.php)

**Event:** `pre`

### EntityProvider

[`DkplusCrud\Controller\Feature\EntityProvider`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/EntityProvider.php)

**Event:** `pre`

### FormHandling

[`DkplusCrud\Controller\Feature\FormHandling`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/FormHandling.php)

**Event:** `main`

### IdentifierProvider

[`DkplusCrud\Controller\Feature\IdentifierProvider`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/IdentifierProvider.php)

**Event:** `pre`

### MultipleInputFilter

[`DkplusCrud\Controller\Feature\MultipleInputFilter`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/MultipleInputFilter.php)

**Event:** `post`

### NotFoundReplacing

[`DkplusCrud\Controller\Feature\NotFoundReplacing`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/NotFoundReplacing.php)

**Event:** `notFound`

### PaginationProvider

[`DkplusCrud\Controller\Feature\PaginationProvider`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/PaginationProvider.php)

**Event:** `pre`

### Rendering

[`DkplusCrud\Controller\Feature\Rendering`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/Rendering.php)

**Event:** configurable (default: `main`)

### SingleInputFilter

[`DkplusCrud\Controller\Feature\SingleInputFilter`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/SingleInputFilter.php)

**Event:** `post`

### UpdateFormProvider

[`DkplusCrud\Controller\Feature\UpdateFormProvider`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/UpdateFormProvider.php)

**Event:** `pre`