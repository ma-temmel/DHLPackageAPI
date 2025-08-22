<?php

namespace Temmel\DHLPackageAPI\Contracts;

interface Jsonable
{
    public function toJson(int $options = 0): string;
}
