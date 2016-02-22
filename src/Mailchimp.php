<?php

  namespace Mailchimp;

  use BadMethodCallException;
  use Exception;
  use InvalidArgumentException;
  use Psr\Http\Message\ResponseInterface;
  use GuzzleHttp\Client;
  use GuzzleHttp\Exception\ClientException;
  use GuzzleHttp\Exception\RequestException;
  use Illuminate\Support\Collection;


  class Mailchimp
  {
    /**
     *  Endpoint for Mailchimp API v3 calls
     *  @var string
     */
    private $endpoint = 'https://us1.api.mailchimp.com/3.0/';

    /**
      *  Get the API Key
      *  @var string
      */
    private $api_key;

    /**
      *  Get the Client
      *  @var client
      */
    private $client;

    /**
      *  Options for the request
      *  @var array
      */
    public $options = [];

    /**
      *  Constructor
      *  @param string api_key
      */
    public function __construct($api_key = '')
    {
      $this->api_key = $api_key;
      $this->client = new Client();

      $this->getEndpoint();

      $this->options['headers'] = [
        'Authorization' => 'apikey ' . $this->api_key,
      ];
    }

    /**
      *  Get the Endpoint
      *  @var string
      */
    public function getEndpoint()
    {
      return $this->endpoint;
    }

    /**
      *  Set the API Key
      *  @var string $api_key
      */
    public function setApiKey($api_key)
    {
      $this->api_key = $api_key;
    }

    /**
      *  Get the options
      *  @param string $method
      *  @param array $args
      *  @return array
      */
    public function getOptions($method, $args)
    {
      if(count($args) == 0){
        return $this->options;
      }

      if($method == 'get'){
        $this->options['query'] = $args;
      }else{
        $this->options['json'] = $args;
      }

      return $this->options;
    }


    public function makeRequest($resource, $args, $method)
    {
      try{
        $options = $this->getOptions($method, $args);
        $response = $this->client->{$method}($this->endpoint . $resource, $options);

        $collection = new Collection(
          json_decode($response->getBody());
        )

        return $collection;
      } catch(ClientException $e){
          throw new Exception($e->getResponse()->getBody());
      } catch(RequestException $e){
          $response = $e->getResponse();

          if($response instanceof ResponseInterface){
            throw new Exception($e->getResponse()->getBody());
          }

          throw new Exception($e->getMessage());
      }
    }





  }

 ?>
