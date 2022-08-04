<?php

namespace TNM\USSD\Test\Requests;

abstract class XMLRequest
{
    abstract function getTemplate(): string;

    abstract function getPayload(): array;

    public function make(): string
    {
        $content = $this->getTemplate();
        foreach ($this->getPayload() as $placeholder => $value)
            $content = str_replace(sprintf('{{%s}}', $placeholder), $value, $content);

        return $content;
    }
}
