<?php

namespace AbdelrhmanSaeed\Dto;


class Rules
{
    private array $errors = [];
    private mixed $value = null;

    public function __construct(
        private DTO $DTO, private string $key, private array $data)
    {
        $this->value = $this->fetchFromRequest($this->key);
    }

    public function getValue(): mixed {
        return $this->value;
    }

    private function fetchFromRequest(string $name): mixed
    {
        $dataPointer    = $this->data;
        $dimensions     = explode('.', $name);

        foreach($dimensions as $dimension) {
            $dataPointer = $dataPointer[$dimension];
        }

        return $dataPointer;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function numeric(): self
    {
        if (! is_numeric($this->value)) {
            $this->DTO->addError($this->key, 'not integer');
        }

        return $this;
    }

    public function gt(int $num): self
    {
        if ($this->value >! $num) {
            $this->DTO->addError($this->key, "not greater than $num");
        }

        return $this;
    }

    public function lt(int $num): self
    {
        if ($this->value <! $num) {
            $this->DTO->addError($this->key, "not less than $num");
        }

        return $this;
    }

    public function between(int $num1, int $num2): self
    {
        if ($this->value < $num1 || $this->value > $num2) {
            $this->DTO->addError($this->key, "not between $num1 and $num2");
        }

        return $this;
    }

    public function string(): self
    {
        if (! is_string($this->value)) {
            $this->DTO->addError($this->key, 'not string');
        }

        return $this;
    }


    public function float(): self
    {
        if (! is_float($this->value)) {
            $this->DTO->addError($this->key, 'not float');
        }

        return $this;
    }


    public function bool(): self
    {
        if (! is_bool($this->value)) {
            $this->DTO->addError($this->key, 'not boolean');
        }

        return $this;
    }


    public function array(): self
    {
        if (! is_array($this->value)) {
            $this->DTO->addError($this->key, 'not array');
        }

        return $this;
    }

    public function email(): self
    {
        if (! filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            $this->DTO->addError($this->key, 'invalid email');
        }

        return $this;
    }

    public function json(): self
    {
        if (is_string($this->value) && ! json_validate($this->value)) {
            $this->DTO->addError($this->key, 'not json');
        }

        return $this;
    }

    public function null(): self
    {
        if (! is_null($this->value)) {
            $this->DTO->addError($this->key, 'not null');
        }

        return $this;
    }

    public function empty(): self
    {
        if (! empty($this->value)) {
            $this->DTO->addError($this->key, 'not empty');
        }

        return $this;
    }

    public function filled(): self
    {
        if (empty($this->value)) {
            $this->DTO->addError($this->key, 'not filled');
        }

        return $this;
    }

    public function callback(callable $callable): self
    {
        if (! $callable()) {
            $this->DTO->addError($this->key, 'callback failed');
        }

        return $this;
    }

    // password

    public function confirmed(): self
    {
        if (! $this->value === $this->fetchFromRequest("{$this->key}_confirmation")) {
            $this->DTO->addError($this->key, "copies don't match");
        }

        return $this;
    }

    // date and time
    
    public function date(?string $format = null): self
    {
        $dateTime = \DateTime::createFromFormat($format ??= 'Y-m-d', $this->value);
        if ($dateTime && $dateTime->format($format) === $this->value) {
            return $this;
        }

        $this->DTO->addError($this->key, "doesn't match the date form of '$format'");
        return $this;
    }

    public function dateTime(?string $format = null): self
    {
        return $this->date('Y-m-d H:i:s');
    }

    public function time(?string $format = null): self
    {
        return $this->date('H:i');
    }

    public function after(string $name): self
    {
        if (new \DateTime($this->value) <= new \DateTime($this->fetchFromRequest($name))) {
            $this->DTO->addError($this->key, "{$this->key} is not after {$name}");
        }
        
        return $this;
    }

    public function before(string $name): self
    {
        if (new \DateTime($this->value) >= new \DateTime($this->fetchFromRequest($name))) {
            $this->DTO->addError($this->key, "{$this->key} is not before {$name}");
        }
        
        return $this;
    }

    public function sameTime(string $name): self
    {
        if (new \DateTime($this->value) != new \DateTime($this->fetchFromRequest($name))) {
            $this->DTO->addError($this->key, "{$this->key} is not the same time as {$name}");
        }
        
        return $this;
    }
}