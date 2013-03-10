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

Working with the [`DkplusCrud\Controller\Controller`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Controller.php)
is not about writing actions, it's about adding actions and features.

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

Every action must implement [`DkplusCrud\Controller\Action\ActionInterface`]
(https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Action/ActionInterface.php).
It triggers at least three events, a `pre`-, `main`- and a `post`-event using
[`DkplusCrud\Controller\Event`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Event.php).
For more informations about the event please have a look at the source code.
Actually there are 3 different actions:

### DefaultAction

[`DkplusCrud\Controller\Action\DefaultAction`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Action/DefaultAction.php)  
Can be used in most cases. It does not more than triggering the three events and
returning the event result. On each event you can hook some features in so they will be executed.

### SingleEntityAction

[`DkplusCrud\Controller\Action\SingleEntityAction`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Action/SingleEntityAction.php)  
Should be used in most cases working on a single entity like showing or deleting.
If there is no entity available after the `pre`-event a `notFound`-event will be triggered
instead of the `main`- and `post`-event.

### UpdateFormAction

[`DkplusCrud\Controller\Action\UpdateFormAction`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Action/UpdateFormAction.php)  
Should be used for updating an entity. Like the `SingleEntityAction` it triggers
a `notFound`-event if no form is available after the `pre`-event.

## Features

### AjaxFormSupport

[`DkplusCrud\Controller\Feature\AjaxFormSupport`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/AjaxFormSupport.php)  
**Event:** `post`  
Assigns the messages from the form to the view model. For this the form must be validated,
but there will be no handling of a valid form (for this look at the [FormHandling](#formhandling)-Feature).
If there is no instance of `Zend\View\Model\JsonModel` available the view model will be overriden.  
The feature can handle post and query data by determining the request-method.
As the name suggests it will only does its work if an ajax request has been detected.

### AjaxLayoutDisabling

[`DkplusCrud\Controller\Feature\AjaxLayoutDisabling`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/AjaxLayoutDisabling.php)  
**Event:** `post`  
Disables the layout if an ajax request has been detected.

### Assigning

[`DkplusCrud\Controller\Feature\Assigning`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/Assigning.php)  
**Event:** configurable (default: `post`)  
Assigns a variable to the view model. By default the variable will be get from the event,
so you can assign a paginator or anything else stored in the event object.
If you want to assign a variable directly you have to call `useEvent(false)`.

### CreationFormProvider

[`DkplusCrud\Controller\Feature\CreationFormProvider`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/CreationFormProvider.php)  
**Event:** `pre`  
Gets the creation form from the service and puts it into the event for further use.

### Deletion

[`DkplusCrud\Controller\Feature\Deletion`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/Deletion.php)  
**Event:** `main`  
Deletes an entity and then redirects and adds a flash message. Needs an entity.

### EntitiesProvider

[`DkplusCrud\Controller\Feature\EntitiesProvider`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/EntitiesProvider.php)  
**Event:** `pre`  
Gets entities as array from the service and puts them into the event for further use.

### EntityProvider
[`DkplusCrud\Controller\Feature\EntityProvider`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/EntityProvider.php)  
**Event:** `pre`  
Gets a single entity from the service and puts it into the event for further use. Needs an identifier.

### FormHandling

[`DkplusCrud\Controller\Feature\FormHandling`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/FormHandling.php)  
**Event:** `main`  
This feature does several things. First it uses postRedirectGet for getting the form data.
Then it puts the form as `form`-variable into the view model and if there are form data
available from postRedirectGet they will be applied to the form. Last if the form is valid it
saves the form data using the service, redirects and adds a flash message.  
By default this feature will not handle ajax requests. So if you want to do this,
you must explicit enable it by calling `handleAjaxRequest`.

### IdentifierProvider

[`DkplusCrud\Controller\Feature\IdentifierProvider`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/IdentifierProvider.php)  
**Event:**: `pre`  
Gets an identifier from the route match and puts him into the event.

### MultipleInputFilter

[`DkplusCrud\Controller\Feature\MultipleInputFilter`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/MultipleInputFilter.php)  
**Event:** `post`  
Modifies a `EntitiesProvider` or a `PaginationProvider` by modifying the query using route, post or query parameters.

### NotFoundReplacing

[`DkplusCrud\Controller\Feature\NotFoundReplacing`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/NotFoundReplacing.php)  
**Event:** `notFound`  
Can be used if an entity has not been found. It sets a 404 response code and returns the content of another action,
so you could show a list of related entities or something else.

### PaginationProvider

[`DkplusCrud\Controller\Feature\PaginationProvider`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/PaginationProvider.php)  
**Event:** `pre`  
Gets entities as paginator from the service and puts them into the event for further use.

### Rendering

[`DkplusCrud\Controller\Feature\Rendering`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/Rendering.php)  
**Event:** configurable (default: `main`)  
Renders the given view script. Can be used only once per Action.

### SingleInputFilter

[`DkplusCrud\Controller\Feature\SingleInputFilter`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/SingleInputFilter.php)  
**Event:** `post`  
Like [MultipleInputFilter](#multipleinputfilter) but using a exactly one parameter for all filter columns.

### UpdateFormProvider

[`DkplusCrud\Controller\Feature\UpdateFormProvider`](https://github.com/UFOMelkor/DkplusCrud/blob/master/src/DkplusCrud/Controller/Feature/UpdateFormProvider.php)  
**Event:** `pre`  
Gets a form from the service and puts it into the event for further use. Needs an identifer.