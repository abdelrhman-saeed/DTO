<?php

namespace AbdelrhmanSaeed\DTO;


abstract class DTO
{
    protected array $errors = [];
    protected array $requiredData = [];

    public function __construct(protected array $data)
    {
        $this->rules();
    }

    protected function input(string $name): Rules
    {
        return new Rules($this, $this->requiredData[] = $name, $this->data);
    }

    public function addError(string $k, string $message): self
    {

        $dataPointer = &$this->data;
        $kDimensions = explode('.', $k);

        for($i = 0; $i < count($kDimensions) -1; $i++) {
            $dataPointer = &$dataPointer[$kDimensions[$i]];
        }

        unset($dataPointer[end($kDimensions)]);

        $this->errors[$k][] = $message;

        return $this;
    }


    public function getValidated(): array
    {

        $validated = [];

        foreach ($this->requiredData as $singleRequirement)
        {
            $currentPath = [];
            $currentDataPointer = &$this->data;

            foreach (explode('.', $singleRequirement) as $requirementDimension)
            {
                if (!isset($currentDataPointer[$requirementDimension])) {
                    break 2;
                }

                $currentPath[] = $requirementDimension;
                $currentDataPointer = &$currentDataPointer[$requirementDimension];
            }

            if (count($currentPath) == 1) {
                $validated[$currentPath[0]] = $currentDataPointer;
                continue;
            }

            $refValidated = &$validated[$currentPath[0]];

            for($i = 1; $i < count($currentPath) -1; $i++) {
                $refValidated[$currentPath[$i]] = null;
                $refValidated = &$refValidated;
            }

            $refValidated[$currentPath[$i]] = $currentDataPointer;

        }

        return $validated;
    }

    public function getErros(): array {
        return $this->errors;
    }

    abstract protected function rules(): void;
}