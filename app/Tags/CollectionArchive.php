<?php

namespace App\Tags;

use Statamic\Tags\Collection\Entries;
use Statamic\Tags\Concerns\OutputsItems;
use Statamic\Tags\Tags;

class CollectionArchive extends Tags
{
    use OutputsItems;

    /**
     * Generates the archive for the given collection using a wildcard tag.
     *
     * @param  string  $tag The collection name.
     * @return array The generated archive array.
     */
    public function wildcard($tag)
    {
        // Set the 'from' parameter to the given tag/collection name.
        $this->params['from'] = $tag;

        // Retrieve the entries using the built-in Statamic Entries helper.
        // This will allow our custom tag to behave just like the built-in
        // Statamic collection tag, and allows us to also support all of
        // the same parameters that the collection tag supports.
        $posts = $this->output((new Entries($this->params))->get());

        // We will assume that we are always using pagination.
        if (! $posts['no_results']) {
            // Get the array alias for the results or use the default 'results'.
            $arrayAlias = $this->params->get('as', 'results');
            $queryResults = $posts[$arrayAlias];

            // Group the entries by year and month.
            $queryResults = collect($queryResults)->groupBy(function ($item, $key) {
                return $item->date()->format('Y');
            })->map(function ($item, $key) use ($arrayAlias) {
                return [
                    'year' => $key,
                    $arrayAlias => collect($item)->groupBy(function ($item, $key) {
                        return $item->date()->format('F');
                    })->map(function ($item, $key) use ($arrayAlias) {
                        return [
                            'month' => $key,
                            $arrayAlias => $item,
                        ];
                    })->values()->toArray(),
                ];
            });

            // Update the original posts array with the grouped results.
            $posts[$arrayAlias] = $queryResults->values()->toArray();
        }

        // Return the generated archive array.
        return $posts;
    }
}
