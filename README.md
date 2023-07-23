
# About SyncWorks API
First of all thank you for giving me this opportunity, means a lot to me.

This application has been developed in a WSL environment.

## Prerequisites

Make sure you have PHP, Composer, and Docker installed on your computer. Sail uses Docker to set up the development environment.

Ensure you have Git installed for version control (optional but recommended).

## Configuration
This application comes with Laravel Sail inside. So after successfully clone the app

### Install all dependencies


```bash
composer install
```

### Initialize Sail

Initialize the Laravel Sail environment by running the following command in the root of your Laravel project:

```bash
php artisan sail:install

```


### Start Docker Containers:

Sail uses Docker to run the development environment. To start the Docker containers, run the following command:


```bash
./vendor/bin/sail up

```

### Migrate and seed:
The database will come with basic data

```bash
./vendor/bin/sail artisan migrate:fresh --seed

```

### Access the Application:

Once the containers are up and running, you can access your Laravel application at http://localhost.

For testing in post man use the the default user
```
test-company
secret

```


## Testing

Running All Tests:

To run all your PHPUnit tests, simply execute the following command:


```bash
./vendor/bin/sail test

```

Or with code coverage

```bash
./vendor/bin/sail test --coverage

```


# Endpoints

GET: /api/

Get a list of events.

Response:
```json
{
    "data": [
        {
            "id": 1,
            "event_title": "Provident eligendi sequi suscipit aut aut quos corrupti.",
            "event_start_date": "1974-02-27 16:19:40",
            "event_end_date": "1974-02-27 17:44:17",
            "organization_id": 1
        },
        {
            "id": 3,
            "event_title": "Sit est quis tempore voluptas rerum id sint non.",
            "event_start_date": "1989-08-25 08:48:40",
            "event_end_date": "1989-08-25 09:00:08",
            "organization_id": 1
        },
        ...
    ]
}

```

GET /api/{id}

Get a single event by ID.

Parameters:

    id (integer) - ID of the event.

Response:

```json

{
    "data": {
        "id": 90,
        "event_title": "Qui aliquid culpa et quo est repellendus.",
        "event_start_date": "2004-10-03 01:57:56",
        "event_end_date": "2004-10-03 03:42:25",
        "organization_id": 1
    }
}
```

POST /api/events

Create a new event.

Request:

```json
{
    "event_title" : "Stand up commedy show",
    "event_start_date": "2023-06-06 08:30:30",
    "event_end_date": "2023-06-06 09:30:30"
}
```

Response:
```json
{
    "id": 201,
    "event_title": "Stand up commedy show",
    "event_start_date": "2023-06-06 08:30:30",
    "event_end_date": "2023-06-06 09:30:30",
    "organization_id": 1
}
```


PUT /api/{id}

Update an existing event.

Parameters:

    id (integer) - ID of the event to update.

Request:

```json

{
    "event_title" : "Stand up commedy show in beira",
    "event_start_date": "2023-06-06 08:30:30",
    "event_end_date": "2023-06-06 09:30:30"
}
```

Response:

```json
{
    "id": 201,
    "event_title": "Stand up commedy show in beira",
    "event_start_date": "2023-06-06 08:30:30",
    "event_end_date": "2023-06-06 09:30:30",
    "organization_id": 1
}
```
ELETE /api/{id}

Delete a event.

Parameters:

    id (integer) - ID of the event to delete.

Response:

```json

```

# Recommendation

If any issue arises, please do not hesitate to contact me for clarifications

