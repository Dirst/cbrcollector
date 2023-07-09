# Set up
Pull this repo and run `docker compose up -d`

# Run queuer
To schedule collection of the data - after all docker services are spinning run:

`docker compose run queuer php ./bin/collector queue DaysCount currency [base_currency]`
base currency can be empty (ruble by default).

***Example***:`docker compose run queuer php ./bin/collector queue 180 usd eur`

# Collecting process.
There is separate service to consume from the queue. You can scale it by spinning more instances:

`docker compose up -d --scale collector=5`

Execute for rabbit container in order to see the queue items number:

`docker compose exec rabbit rabbitmqctl list_queues`

Also you can get collected data like this:

`docker compose exec db mysql -uroot -prootroot -e 'select rate,rateDayChange,currency,baseCurrency,date from app.rate_stat order by date;'`

# Additional info
Redis service is utilized for caching. It caches retrieved cbr data by key:value.

In order to save time on assessment:
- Process of validating and storing collection of currencies is simplified.
- No phpunit tests added.