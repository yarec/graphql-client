
### Usage

    $endpoint = 'http://api.example.com/graphql';
    $client = new Client($endpoint);

    // for one
    $opts = [
        'args' => [
            'id'=> 1234
        ],
        'resp' => [
            'id',
            'name',
            'email'
        ]
    ];

    $data = $client->query('user', $opts);


    //for list
    $opts = [
        'params' => [
            'per_page' => 3,
            'page' => 2,
        ],
        'args' => ['sex' => 'male'],
        'resp' => [
            'data' => [
                'id',
                'name',
                'email'
            ]
        ]
    ];

    $data = $this->client->query('users', $opts);

### DEV

composer config repositories.gql-client path $PATH/yarec/graphql-client

composer require yarec/graphql-client -vvv
