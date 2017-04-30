<?php
class Validator
{
    protected $db;
    protected $errorHandler;
    protected $items;
    protected $rules = ['required', 'minlength', 'maxlength', 'email', 'match','telephone','regex'];
    public $messages = [
        'required' => ':field alanı boş bırakılamaz.',
        'minlength' => ':field alanı en az :satisifer karakter olabilir.',
        'maxlength' => ':field alanı en fazla :satisifer karakter olabilir.',
        'email' => ':field geçersiz bir email adresidir.',
        'match' => ':field alanı :satisifer alanı ile eşleşmiyor.',
        'telephone' => ':field alanı geçersiz bir telefon numarasıdır.',
        'regex' => ':field alanı tanımlı pattern ile eşleşmiyor.'
    ];
    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler  = $errorHandler;
    }
    public function set($items, $rules)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $this->items = $items;
            foreach ($items as $item => $value)
            {
                if (in_array($item, array_keys($rules)))
                {
                    $this->validate([
                        'field' => $item,
                        'value' => $value,
                        'rules' => $rules[$item]
                    ]);
                }
            }
        }
        else if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            die(ErrorHandler::show('GET olarak gelen işlemler kabul edilemiyor. Lütfen bir POST isteği atın.'));
        }
        else
        {
            die(ErrorHandler::show('Bu işlem sadece POST işlemi kabul edebilir.'));
        }
    }
    protected function validate($item)
    {
        $field = $item['field'];
        foreach ($item['rules'] as $rule => $satisifer)
        {
            if (in_array($rule, $this->rules))
            {
                if (!call_user_func_array([$this, $rule], [$field, $item['value'], $satisifer]))
                {
                    $this->errorHandler->addError(str_replace([':field', ':satisifer'], [$field, $satisifer], $this->messages[$rule]), $field);
                }
            }
        }
    }
    public function fails()
    {
        return $this->errorHandler->hasErrors();
    }
    public function errors()
    {
        return $this->errorHandler;
    }
    protected function required($field, $value, $satisifer)
    {
        return !empty(htmlspecialchars(trim($value)));
    }
    protected function minlength($field, $value, $satisifer)
    {
        return mb_strlen($value) >= $satisifer;
    }
    protected function maxlength($field, $value, $satisifer)
    {
        return mb_strlen($value) <= $satisifer;
    }
    protected function email($field, $value, $satisifer)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    protected function match($field, $value, $satisifer)
    {
        return $value === $this->items[$satisifer];
    }
    protected function telephone($field, $value, $satisifer)
    {
        $value      = str_replace(' ', '', $value);
        $tel      = ltrim($value, '0');
        if (!is_int($tel) && (strlen($tel) < 10) || (strlen($tel) > 10))
        {
        }
        else
        {
            return $tel;
        }
    }
    protected function regex($field,$value,$satisifer)
    {
        return preg_match($satisifer,$value) ;
    }
}