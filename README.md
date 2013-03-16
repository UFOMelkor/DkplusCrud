# DkplusCrud [![Build Status](https://secure.travis-ci.org/UFOMelkor/DkplusCrud.png?branch=master)](http://travis-ci.org/UFOMelkor/DkplusCrud)

## Introduction

DkplusCrud provides full crud functionality for zf2-modules. It can be used e.g.
for rendering single view scripts, creating entities by using a form, updating
them by using the same form, displaying and deleting.

Supports
- [controllers](https://github.com/UFOMelkor/DkplusCrud/tree/master/docs/controller.md) using different features
- a [service](https://github.com/UFOMelkor/DkplusCrud/tree/master/docs/service.md) that connects mappers and form handling.
- [mappers](https://github.com/UFOMelkor/DkplusCrud/tree/master/docs/mapper.md) supporting [DoctrineORM](http://doctrine-project.org/) (Zend\Db will be added in futere)
- two different [form handlings](https://github.com/UFOMelkor/DkplusCrud/tree/master/docs/form-handling.md) for supporting entities with and without constructor parameters.

The only things you need to provide are view scripts, your model (e.g. in form of entities) and the form-objects.

## Installation

Installation of DkplusCrud uses composer. For composer documentation, please refer to [getcomposer.org](http://getcomposer.org/).