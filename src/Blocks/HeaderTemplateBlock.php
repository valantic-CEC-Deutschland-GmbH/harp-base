<?php

declare(strict_types = 1);

namespace App\Blocks;

use Htmxfony\Template\TemplateBlock;

class HeaderTemplateBlock extends TemplateBlock
{
    public function __construct(string $template, string $name)
    {


        parent::__construct($template, $name, $data);
    }
}
