# Discord Clone

## Table of Contents

- [Introduction](#introduction)
- [Stack](#stack)
- [Installation](#installation)
- [Tests](#tests)
- [Documentation](#documentation)
- [Contributing and Examples](#contributing-and-examples)
- [First time in the project](#first-time-in-the-project)
- [About implementation](#about-implementation)

## Introduction

This is a simple discord clone with real time chat, authentication and guilds (servers) with channels system.
The objetive of this project is show a websocket example with Laravel Reverb.
Also, demonstrate examples of how use Laravel Sanctum and application of the SOLID principles.

## Stack

- [Laravel 11]()
- [Laravel Reverb](https://reverb.laravel.com/)
- [Vue](https://vuejs.org/)
- [PostgreSQL](https://www.postgresql.org/)
- [Docker](https://www.docker.com/)
- [Pest](https://pestphp.com/)

## Installation

To execute this project, you need to have installed Docker in your machine. After that, follow the steps:

1. Clone the repository

```
git clone https://github.com/otaaaviio/disc-laravel.git
```

2. Enter on project folder

```
cd disc-laravel
git checkout ota/development
```

3. In src folder, execute the bin for setup:

```bash
./bin/setup.sh
```

ps: This step should take a while, because the docker will download the images and install the dependencies.

4. Access in your browser the address:

```
http://0.0.0.0:80
```

5. In .env, you need change this variables for production:

```
REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
```

For the next times, you can use this for stop or start the containers:

```bash
./bin/start.sh
```

```bash
./bin/stop.sh
```

## Tests

This project contains units and integration tests. Always this branch is pushed, will execute tests in GitHub Actions. Also, to you execute tests, use the command:

```bash
./vendor/bin/sail test
```

ps: You need run the containers before execute the tests.

## First time in the project

When you set up the project with setup.sh, will run seeder with 2 users: <br>
Adm user:

```
email: adm@admin.com
password: password
```

Normal user:

```
email: usr@user.com
password: password
```

With these credentials, you can access the application in different browsers and experience the real-time chat
functionality.

## Documentation

I use the [scramble](https://scramble.dedoc.co/) for the api documentation. You can see accessing the address:

```
http://0.0.0.0:80/docs/api
```

In this documentation, you can see the endpoints, request and response examples.

## About implementation

### Services: <br>

I use service abstraction, to separate the business logic from the controller and have a better maintenance. <br>

### Repositories: <br>

About repositories, I don't like use repository pattern in simple laravel projects,
because in majority cases, the eloquent is enough, and we don't change the framework project. <br>
In larger projects, where there might be a need to change the framework, the repository pattern is a good choice.

### Websockets: <br>

I use Laravel Reverb to implement the websockets. It's not hard to implement, but debug this should be a little hard if
you don't have familiarity with websockets. <br>
Even though it's a relatively new technology, it's a good choice to learn and apply in your personal or professional
projects.

### Jobs: <br>

I use jobs to send the mail to new users, with welcome message. And use a different connection to send the mail. <br>

### Tests: <br>

I use Pest for tests. It's a simple and clean test framework. I like it because it's easy to read and write tests. <br>

### Docker: <br>

I use the Laravel Sail with some modifications for this project, like change the images and add vite container. <br>

### FrontEnd: <br>

I use Vue for frontend, just for my familiarity with this framework.
And I focus in the backend, so the frontend is simple and functional.

## Contributing and Examples

You can fork this project and make a pull request with your changes. I will be happy to review and accept your
contribution.
If you follow the current project pattern, everything will work fine. :D
<br>
A example to you follow, with this you can maintain the project clean and organized, with easy maintenance:

Resource to lead with responses to client:

```php
class ModelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
        // here you can edit what you want to return
        ];
    }
}
```

Request to validate the request data:

```php
class ModelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // some rules you want validated
        ];
    }
}
```

Exceptions to lead with errors in a clean way and eay to understand:

```php
class ModelException extends Exception
{
    public function __construct($message = '', $code = StatusCode::HTTP_INTERNAL_SERVER_ERROR, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function someError(): self
    {
        return new self(
            'Some error message here',
            StatusCode::HTTP_ERROR
        );
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
```

Controller to receive request and return answer to client:

```php
public function store(ModelRequest $request)
{
    // this will clean fields that are not in the rules
    $data = $this->validated();

    //here you process the request, within show the business logic 
    $modelResource = $this->service->create($data);

    // here you can style the response and return 
    return response()->json([
    'message' => 'Some message here',
    'model' => $modelResource
    ], StatusCode::HTTP_CREATED);
}
```

Service to lead with business logic and return the resource to controller:

```php
public function create(array $data)
{
    // here you process the data and return the resource and treat errors with custom exceptions
    
    if($error) {
        // this is useful to maintain the handling of errors in the same place
        throw CustomException::someError();
    }
    
    return ModelResource::make($model);
}
```

For Websockets, you can add new events to project, like:

```php
class SomeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Model $model
    ) {
        //
    }

    // channel where the event will be broadcast
    public function broadcastOn(): Channel
    {
        return new Channel('channel.'.$this->model->id);
    }

    // data to send in event
    public function broadcastWith(): array
    {
        return [
            'model' => $this->model
        ];
    }

    // person event name to access in client side
    public function broadcastAs(): string
    {
        return 'some-event';
    }
}
```
With websockets you can add real-time features to your project, like chat, notifications and others.
For more infos, you can see the [Laravel Documentation](https://laravel.com/docs)

## Contact

If you have any questions, you can contact me by email or linkedin:

```
oglamberty@inf.ufsm.br

https://www.linkedin.com/in/otaaaviio/
```
