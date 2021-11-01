
## Vending Machine API

<p>
<a href="https://github.com/hmones/vending-machine/actions"><img src="https://github.com/hmones/vending-machine/actions/workflows/build.yml/badge.svg" alt="Build Status"></a>
<a href="https://github.styleci.io/repos/422692373"><img src="https://github.styleci.io/repos/422692373/shield" alt="Style CI"></a>
</p>

The code is a sample API for a vending machine, allowing users with a “seller” role to add, update or remove products, while users with a “buyer” role can deposit coins into the machine and make purchases. Your vending machine should only accept 5, 10, 20, 50 and 100 cent coins

## Installation

For trying out the API, make sure you have docker and docker-compose installed on your system, then go to the root directory and run the following commands in order:

- Initialize the containers `./vendor/sail up -d`
- To run migrations and load database with seeds `./vendor/sail artisan migrate:fresh`

## Testing
For testing simply run ```composer test```
