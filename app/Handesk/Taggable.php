<?php

namespace App\Handesk;

trait Taggable
{
    public function tagsString($glue = ',')
    {
        return implode($glue, $this->tags->pluck('name')->toArray());
    }

    public function attachTags($tagNames)
    {
        $this->tags()->attach($this->findTagsIds($tagNames)->toArray());

        return $this;
    }

    public function detachTag($tagName)
    {
        $this->tags()->detach(Tag::whereName(strtolower($tagName))->get());
    }

    private function findTagsIds($tagNames)
    {
        return collect(is_array($tagNames) ? $tagNames : explode(',', $tagNames))->map(function ($tagName) {
            return Tag::firstOrCreate(['name' => strtolower($tagName)]);
        })->unique('id')->pluck('id');
    }
}
