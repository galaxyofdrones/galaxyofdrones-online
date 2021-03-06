<?php

namespace App\Models\Concerns;

trait HasShield
{
    /**
     * Has shield?
     *
     * @return bool
     */
    public function hasShield()
    {
        return $this->shield && $this->shield->remaining;
    }
}
