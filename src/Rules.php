<?php

namespace AbdelrhmanSaeed\Dto;


class Rules
{

    private array $errors = [];

    public function __construct(private DTO $DTO, private string $key, private mixed $value) {}

    public function getValue(): mixed {
        return $this->value;
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
}