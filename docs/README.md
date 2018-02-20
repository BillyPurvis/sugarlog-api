# SugarLog Backend Guide

## The Project

SugarLog aims to improve how patients log, manage and track their diabetes records, without patient paywalls. SugarLog allows users to create logs wherever they are, analyse their history with graphs, setting up schedules to remind them when to take results or medication. SugarLog also allows users to export their data to their medical team with ease, avoiding scraps of paper, awkward device exports, and forgetting to bring results; it's all done for them! 

In the future, we aim to connect patients and medical professionals closer, making it even easier!
 
 
## Table Of Contents
    
- [Authentication](AUTHENTICATION.md)

## Approach

SugarLog follows Domain Driven Design (DDD) approach. Each part of the application is split into layers and sub layers. We're using a slightly tweaked version of [Fabian Keller's](https://www.fabian-keller.de/blog/domain-driven-design-with-symfony-a-folder-structure) approach; we don't use CQRS to avoid complexity and verbosity. We also utilize CommandBus to aid in abstraction of logic and ability to keep bounded contexts, and change third party services with ease. Rather than binding a service to a specific domain. 

Each component is can be defined as a Domain of the application. Such as a User domain, Sales Domain, Order Domain and so forth. Each domain is separated from another; a [bounded context](http://www.informit.com/articles/article.aspx?p=2738465&seqNum=3). For example, in an order, a Customer context doesn't care about the delivery address. It only cares about what is being ordered for what customers. Although, a customer in the delivery context would need to know the customer's name, address, and delivery method. Finally, a billing customer context doesn't care about the delivery method, delivery address or the color of the customer's underwear. It only cares about what is relevant to solve its problem. 


## Layered Architecture

[Stolen from Fabian Keller's article](https://www.fabian-keller.de/blog/domain-driven-design-with-symfony-a-folder-structure)

The code is organized by functional components, which have a logically coherent and tight coupling of the contained models. A component (e.g., a User component) is then organized into five different layers:

* Domain layer: contains the actual business logic and domain models.
* Infrastructure layer: binds the business logic implementation to infrastructure and frameworks, such as Symfony or Doctrine.
* Presentation layer: is responsible for presenting a user interface, allowing users to make use of the business logic.
* Api layer: provides an API interface to interact with the domain services.
* Tests layer: Contains all tests relevant to the component.


## When to use a Console Command

I tend to write a console (cli) command when something may need to be done with a cron job, or without a UI and isolated. For example, creating a user could be a console command, same for deleting and such. 

More uses: 

* Manual task automation
* Data caculations (via cron)
* Admin tools (such as users)

## When to use command bus

When something could be used in another area, it's probably a good idea to wrap it in a command bus. Which is called based on an Event Emitted. A good use case is if we want to calculate some generated data, but we don't want that blocking our registration controller. We can pass it off to a command, we can call multiple commands from an Event Handler. 

[Good Read](https://en.wikipedia.org/wiki/Command_pattern)


## Contributing

We follow the GitFlow method of Git. Where master is not the parent branch of all branches. Develop is used as our parent branch, and master is only for releases. 

To create a new feature: 

* Pull from develop
* Branch pre-fixed with feature/
* Try to be descriptive of what your branch adds, or does. IE: feature/creates-slack-notification-service

Hopefully hot patches won't be needed, but pulling from master or develop (or the release branch) and prefixed with hotfix/ will be fine. 

### Pull Requests

All changes require a PR to develop (not master). 

If I don't get to it, contact me somehow. 


## Conclusion

We're still finding new ways to do things, such as event sourcing may be used eventually for billing related purposes. If there's any issues, just make a new issue on Github, or PM me. 
