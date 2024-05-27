<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    private Tag $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function getTags(){
        return $this->tag->get();
    }
}
