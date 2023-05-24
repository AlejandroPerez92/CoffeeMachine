# Coffee Machine
The Coffee Machine is an awesome console application that takes a few input parameters
(drink type, amount of money, number of sugars, extra hot check)
and is capable of ordering a drink while displaying a cool message for the desired drink.
It also includes a sales context to keep track of the total sales for each product.

## What's the goal
This is a simple repo for improve, test and showcase my abilities.

The project simulates a IT company with two isolated teams (Orders and Sales)

Periodically I will iterate the project for implement new knowledge and technologies

## Current status
This application is implemented using the Symfony CLI framework and Tactician as the bus implementation.
It was designed following the principles of Domain-Driven Design (DDD) and tested using PHPUnit.

The project is structured into two bounded contexts:
1. Order: This bounded context contains all the logic for the order process, from ordering a drink to pay it.
2. Sales: This bounded context is responsible for tracking the total income per product. It consists of two modules:
   1. Orders: To ensure the independence of the two teams, Sales has this module to maintain a projection of the Orders and dispatch custom events.
   2. ProductSales: By listening to the custom events dispatched by our team in their Order module, it increments the total product income.

## RoadBook
While I don't have a specific timeline, I do have a list of tasks to complete:

1. ~~Refactor the Sales bounded context to promote the Order module as a new module.~~
2. Refactor domain events to use object attributes as payloads 
(since the listeners are in the infrastructure layer, it doesn't matter if they use objects from other bounded contexts and modules).
3. Refactor all classes to use PHP 8.1 constructors.
4. Implement real persistence using MySQL, Redis, and Mongo to provide examples in all three technologies.
5. Refactor unit tests to use mocks instead of in-memory repositories.
6. Install Symfony Messenger and migrate the buses to Symfony Messenger.
7. Utilize an AMQBroker to dispatch domain events and make the consumers asynchronous.
8. Install Symfony API.
9. Install Behat.
10. Create endpoints to interact with the services.

## How it works
### Order your dirk
Command
```
app:order-drink 

```

Arguments

|#|Name|Type|Required|Description|Values|Default|
|---|---|---|---|---|---|---|
|1|drinkType|string|true|Type of drink|tea, coffee, chocolate|
|2|money|float|true|Amount of money given by the user in unit of currency||
|3|sugars|int|false|Number of sugars|0, 1, 2|0|

Options

|Name|Type|Required|Description|Values|Default|
|---|---|---|---|---|---|
|extraHot (--extra-hot, -e)| |false|Flag indicating if the user wants extra hot drink|true, false|false|

List prices

|Drink|Price|
|---|---|
|Tea|0.4|
|Coffee|0.5|
|Chocolate|0.6|

### See the sales resume
```
app:sales
```
|Drink|Money|
|---|---|
|Tea|15|
|Coffee|25.75|
|Chocolate|36|

## Project set up

Install and run the application.
```
docker/composer install
docker/up
```

Examples of the use of the application.
```
docker/console app:order-drink tea 0.5 1 -e
docker/console app:order-drink coffee 0.5
docker/console app:order-drink chocolate 1 --extra-hot
```

Run tests
```
docker/test
```
