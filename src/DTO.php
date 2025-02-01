<?php

namespace AbdelrhmanSaeed\DTO;


abstract class DTO
{
    protected array $errors = [];

    public function __construct(protected array $validated)
    {
        $this->rules();
    }

    protected function input(string $name): Rules
    {
        $nested = explode('.', $name);
        $value  = $this->validated[$nested[0]];

        for($i = 1; $i < count($nested); $i++) {
            $value = $value[$nested[$i]];
        }

        return new Rules($this,  end($nested), $value);
    }

    public function addError(string $k, string $message): self
    {
        if (isset($this->validated[$k])) {
            unset($this->validated[$k]);
        }

        $this->errors[$k][] = $message;
        return $this;
    }

    public function addValidated(string $key, $value): self
    {
        $this->validated[$key] = $value;
        return $this;
    }

    public function getValidated(): array {
        return $this->validated;
    }

    public function getErros(): array {
        return $this->errors;
    }

    abstract protected function rules(): void;
}