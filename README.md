# Set up
Pull this repo and run `docker compose up -d`

# Run queuer
To schedule collection of the data - after all docker services are spinning run:

`docker compose run queuer php ./bin/collector queue DaysCount currency [base_currency]`
base currency can be empty if base currency is ruble.

***Example***:`docker compose run queuer php ./bin/collector queue 180 usd eur`

# Collecting process.
There is separate service to consume from the queue called 'collector'. You can scale by spinning more instances:

`docker compose up -d --scale collector=5`

To see the process execute within the rabbit container:

`docker compose exec rabbit rabbitmqctl list_queues`

Also you can access see database data like this:

`docker compose exec db mysql -uroot -prootroot -e 'select rate,rateDayChange,currency,baseCurrency,date from app.rate_stat order by date;'`

# Additional info
For caching redis service is utilized. It caches retrieved cbr data by key:value.