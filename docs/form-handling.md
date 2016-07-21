# FormHandling

FormHandling is not just about providing forms for creating and updating entities,
it's also about putting the data, that are provided from the form, back into the entity.

Even if DkplusCrud can handle forms, you must provide the form and a hydrator by yourself.

There are 2 possible solutions provided by DkplusCrud.

## BindFormHandler

The first solutions is the ‘zf2-way’ of form handling. The form will do the most staff
and you need to inject the hydrator into the form.

## FactoryFormHandler

If the constructor of your entity needs some parameters, you cannot use the `BindFormHandler`.
Instead you can use the `FactoryFormHandler`. He obtains a form and an object implementing 
[`HydrationFactoryInterface`](https://github.com/UFOMelkor/DkplusBase/blob/master/src/DkplusBase/Stdlib/Hydrator/HydrationFactoryInterface.php).
