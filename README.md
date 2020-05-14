# CryptoApi


<h3>Prerequisites</h3>


1. https://www.coinapi.io/pricing?apikey API key, request are made to sandbox api in the APP, so no need to worry about free 100 requests

2. Docker

3. Postman (optional)


<h3>Launching the app</h3>

1. Clone the repository `git clone https://github.com/zilius/cybernews.git`

2. CD into dir

3. By default app uses `8091` on host machine for http connections and `3310` for db connections. You can change these variables in `Makefile`

4. enter `Make init` command to build the project it will request for root password, to change project directory owner to non-root user `sudo chown -R $(USER):$(USER) $(WORK_DIR)` - this is the command that requests it (root password will be requested after composer install all dependencies)

5. `http://localhost:8091/` in your borwser to make sure API is running `API is working` - message should be displayed.

6. Keep the window where you run the app, open new terminal window and cd into dir and type `make ssh`

7. Generate app key with `php artisan key:generate`

8. Install migrations and run them with `php artisan migrate:install php artisan migrate`

9. Seed the db with `php artisan db:seed`

10. To make sure everything is up to date run `composer dump-autoload -o` and `php artisan config:cache`

<h3>Connecting to DB</h3>

<b>Host</b>: 127.0.0.1

<b>User</b>: app

<b>Password</b>: app

<b>Database</b>.: app


<h3> Using the app </h3>

Database is prefilled with 10 users to access the api you will need to take api_token from one of the users. If you are to lazy to connect to db you can use `debug` api token since this value is hardcoded.

<h4>Authenticating</h4>

There's a test GET route to make sure you are authenticated `api/test`

Add `Authorization` header to request with value of `Bearer {token}` below is a curl example:

```
curl -X GET \
  http://localhost:8091/api/test \
  -H 'authorization: Bearer debug' \
  -H 'cache-control: no-cache' \
  -H 'postman-token: dcaa5bd4-28e1-0288-2965-82757eb22766'
```

Calling this url `You are authenticated!` message should be displayed, otherwise exception that login route is not defined will be thrown.

<h4>Crud commands</h4>

POST `/asset/post`

Creates new asset for the user

example request: 

```
curl -X POST \
  http://localhost:8091/api/asset/post \
  -H 'authorization: Bearer debug' \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -H 'postman-token: 40ee9dee-8a7f-f209-f9f2-bd0cbc0f68d3' \
  -d '{
	"label":"USB",
	"currency_code":"XRP",
	"value":"14.564"
}'
```

on success json of newly createad asset is preserved


```
{
  "user_id":11,
  "label":"USB",
  "currency_code":"XRP",
  "value":"14.564",
  "updated_at":"2020-05-08T12:23:29.000000Z",
  "created_at":"2020-05-08T12:23:29.000000Z",
  "id":1
}
```

possible error messages:

```
Asset with this label already exist, please use PUT if you want to deposit currency,
Value must be positive,
Please select label,
XRP ETH BTC are supported

```



PUT `/asset/put`

Updates assets currency amount user can post negative and positive amounts

example request:

```
curl -X PUT \
  http://localhost:8091/api/asset/put \
  -H 'authorization: Bearer debug' \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -H 'postman-token: 613b6212-7293-c76c-a70f-b37d056bd0e5' \
  -d '{
	"label":"USB",
	"currency_code":"XRP",
	"value":"1"
}'
```
on success returs new amount

```
17.564
```

possible error messages:

```
Asset does not exist, please add it,
You dont have enough currency,
Value is not set
```


GET `/asset/get`

Gets specific or all assets of users

example request for all user assets

```
curl -X GET \
  http://localhost:8091/api/asset/get \
  -H 'authorization: Bearer debug' \
  -H 'cache-control: no-cache' \
  -H 'postman-token: e0fff265-dd45-be5d-902f-b571ba7e6d5e'
```

example response:

```
{
    "USB": {
        "XRP": "1.5640"
    },
    "SSS": {
        "ETH": "55.0000",
        "BTC": "55.0000"
    },
    "FRIEND": {
        "BTC": "55.0000"
    },
    "PINIGINE": {
        "XRP": "55.0000"
    }
}
```

example request for specific asset 

```
curl -X GET \
  'http://localhost:8091/api/asset/get?label=PINIGINE' \
  -H 'authorization: Bearer debug' \
  -H 'cache-control: no-cache' \
  -H 'postman-token: 3f005429-7b8e-78be-1a64-c77d7fe48576'
```

example response

```
{
    "PINIGINE": {
        "XRP": "55.0000"
    }
}
```


DELETE `/asset/delete`

Deletes whole assets with all currencies

example request 

```
curl -X DELETE \
  http://localhost:8091/api/asset/delete \
  -H 'authorization: Bearer debug' \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -H 'postman-token: 8c1a707d-c96d-a0b7-592e-6276cd9804c5' \
  -d '{
	"label":"PINIGINE"
}'
```

returns number of affected rows


<h3>Getting total assets value in USD</h3>

GET `/api/totalBalance` - returns user assets values in USD

example request:

```
curl -X GET \
  http://localhost:8091/api/totalBalance \
  -H 'authorization: Bearer debug' \
  -H 'cache-control: no-cache' \
  -H 'postman-token: ec101770-76ca-3f69-ae8d-415d4f7ac666'
```

example response

```
{
    "USB": {
        "XRP": 0.338997
    },
    "SSS": {
        "ETH": 11602.824307396932,
        "BTC": 544841.2606344089
    },
    "FRIEND": {
        "BTC": 544841.2606344089
    },
    "TOTALS": {
        "XRP": 0.338997,
        "ETH": 11602.824307396932,
        "BTC": 1089682.5212688178
    }
}
```




