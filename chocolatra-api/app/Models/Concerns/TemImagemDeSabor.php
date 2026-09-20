<?php

// app/Models/Concerns/TemImagemDeSabor.php

namespace App\Models\Concerns;

trait TemImagemDeSabor
{
    public function getImgUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return asset('images/sabores/' . $this->image);
    }
}