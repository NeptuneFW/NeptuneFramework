<?php
namespace Libs\Router;

class HttpStatus
{
    protected $code,
              $message;
    protected static $http_messages = [
      100 => 'Continue',
      101 => 'Switching Protocols',
      200 => 'OK',
      201 => 'Created',
      202 => 'Accepted',
      203 => 'Non-Authoritative Information',
      204 => 'No Content',
      205 => 'Reset Content',
      206 => 'Partial Content',
      300 => 'Multiple Choices',
      301 => 'Moved Permanently',
      302 => 'Found',
      303 => 'See Other',
      304 => 'Not Modified',
      305 => 'Use Proxy',
      306 => '(Unused)',
      307 => 'Temporary Redirect',
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      406 => 'Not Acceptable',
      407 => 'Proxy Authentication Required',
      408 => 'Request Timeout',
      409 => 'Conflict',
      410 => 'Gone',
      411 => 'Length Required',
      412 => 'Precondition Failed',
      413 => 'Request Entity Too Large',
      414 => 'Request-URI Too Long',
      415 => 'Unsupported Media Type',
      416 => 'Requested Range Not Satisfiable',
      417 => 'Expectation Failed',
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      502 => 'Bad Gateway',
      503 => 'Service Unavailable',
      504 => 'Gateway Timeout',
      505 => 'HTTP Version Not Supported',
    ];

    public function __construct($code, $message = null)
    {
      $this->setCode($code);
      if (null === $message)
      {
        $message = static::getMessageFromCode($code);
      }
      $this->message = $message;
    }
    public function getCode()
    {
      return $this->code;
    }
    public function getMessage()
    {
      return $this->message;
    }
    public function setCode($code)
    {
      $this->code = (int) $code;
      return $this;
    }
    public function setMessage($message)
    {
      $this->message = (string) $message;
      return $this;
    }
    public function getFormattedString()
    {
      $string = (string) $this->code;
      if (null !== $this->message)
      {
        $string = $string . ' ' . $this->message;
      }
      return $string;
    }
    public function __toString()
    {
      return $this->getFormattedString();
    }
    public static function getMessageFromCode($int)
    {
      if (isset(static::$http_messages[ $int ]))
      {
        return static::$http_messages[ $int ];
      }
      else
      {
        return null;
      }
    }
}
