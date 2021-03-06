# Zinio Challenge by Cesc

See below for the docker approach.

### Requisites

You will need PHP installed in your machine as well as composer:

```curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer ```

### Installation

You will need to get the vendor for this. Execute:
`composer install`

### Execution

Just execute on this same directory
`php solve.php`
As per requested on the instructions

### Tests

Execute this (using the same exec from the vendor, you will have it installed after executing `composer install`)

`vendor/phpunit/phpunit/phpunit `

## Docker approach

You can also execute the script with Docker.

### Requisites
You will need to have docker installed.

### Installation
You will need to create an image using the present Dockerfile. For that execute:
`docker build . -t cesc-challenge`
(cesc-challenge is the name)

### Execution
Run the image you just build:
`docker run -ti cesc-challenge`

You will have the output on the console.

### Tests
If you want to change the input (the file cities.txt) you will need to build the image again
since the way I put in this README to build doesn't use any volumes.

There is no way to execute tests on this docker. This image is just a machine with PHP installed
and set to execute the script.

## Comments

### About the algorithm

I am going to be very frank, I actually did a very extensive search on the internet for this version
of the traveling salesman problem, but I couldn't find a good approach for the case in hand, where 
the graph is a complete graph. All of what I found was based on non-complete graphs, and the solutions
were extremely overcomplicated, not really suitable for the case in hand.

I also didn't want to overdo it in a challenge, so I took a simple solution which is to find
the closest city of the ones that the salesman didn't visit yet. This approach is polynomial in
complexity, and while I (again being very frank) forgot the maths to prove that indeed it does
-or not, probably- find the best solution, I couldn't find any counterexample (with small graphs
of few nodes-cities) that this approach won't solve it by returning the best path. 
(I'm quite sure though there are cases that it will not return the best path). Anyway, it will
do a good job and fast.

(In what I've been very blunt copying was the algorithm that calculates the distance between
two coordinates)

### About Symfony
The email (but not the instructions) suggested to use Symfony, so I did. While it was relief using
Symfony's dependency injection container, I think that for a simple script, where an input is a text
file in the same machine, and the output is the standard output, the use of Symfony is a little bit
overkill.

Anyway I used the Symfony command feature, and the solve.php what actually does is execute a command.
It should be the same as executing `bin/console cesc:challenge cities.txt`

### More about infra
Since it is a simple script, having a repository to save in memory would be a little bit overkill.
But having a repo implementing this interface:

```interface CityRepositoryInterface
{
    public function save(City $city):void;

    public function remove(City $city): void;

    public function getClosestCity(City $city): City;

}
```
might make the service even more readable. And if we were to save in DB for real, we could use in
built function of the DB for the coordinates te get the closes city, avoiding us to code both
the `City:getDistanceFromCity()` and `GetShortestPathService:getClosestCityIndexInArray()`. Actually
the code for the service would be prettier if we were to use this repo and testing of it easier.

Actually I am going to do it: checkout branch `feature/with-repo` to see the code.

### About Docker
The email also suggested the use of docker. For a simple script, there's no need to overcomplicate
Docker with adding a server/php-fpm service, it just needs to execute the script.

