<?php

use Alfred\Workflows\Workflow;

use Algolia\AlgoliaSearch\SearchClient;
use Algolia\AlgoliaSearch\Support\UserAgent;

require __DIR__ . '/vendor/autoload.php';

$query = $argv[1];
$version = getenv('version') ?: '5.2';
$workflow = new Workflow;

// previous versions are using different app ID
if ($version === '5.0' || $version === '5.1') {
    $algolia = SearchClient::create('BH4D9OD16A', '5990ad008512000bba2cf951ccf0332f');
} else {
    $algolia = SearchClient::create('AK7KMZKZHQ', '3151f502c7b9e9dafd5e6372b691a24e');
}

UserAgent::addCustomUserAgent('Alfred Workflow', '0.3.0');

$index = $algolia->initIndex('bootstrap');
$search = $index->search($query, ['facetFilters' => [
    sprintf('version:%s', $version),
]]);

$results = $search['hits'];

if (empty($results)) {
    $google = sprintf('https://www.google.com/search?q=%s', rawurlencode("bootstrap {$query}"));

    $workflow->result()
        ->title('No match found. Search Google...')
        ->icon('google.png')
        ->subtitle(sprintf('No match found. Search Google for: "%s"', $query))
        ->arg($google)
        ->quicklookurl($google)
        ->valid(true);

    $workflow->result()
        ->title('No match found. Open docs...')
        ->icon('icon.png')
        ->subtitle('No match found. Open getbootstrap.com/docs...')
        ->arg('https://getbootstrap.com/docs/')
        ->quicklookurl('https://getbootstrap.com/docs/')
        ->valid(true);

    echo $workflow->output();
    exit;
}

$urls = [];

foreach ($results as $hit) {
    $url = $hit['url'];

    if (in_array($url, $urls, true)) {
        continue;
    }

    $urls[] = $url;

    $hasText = isset($hit['_highlightResult']['content']['value']);

    $title = $hit['hierarchy']['lvl0'];
    $subtitle = subtitle($hit);

    if ($subtitle) {
        $title = "{$title} » {$subtitle}";
    }

    $title = strip_tags(html_entity_decode($title, ENT_QUOTES, 'UTF-8'));

    $text = strip_tags(html_entity_decode($subtitle, ENT_QUOTES, 'UTF-8'));
    $text = preg_replace('/\s+/', ' ', $text);

    $workflow->result()
        ->uid($hit['objectID'])
        ->title($title)
        ->autocomplete($title)
        ->subtitle($text)
        ->arg($url)
        ->quicklookurl($url)
        ->valid(true);
}

echo $workflow->output();

function subtitle($hit)
{
    if (isset($hit['hierarchy']['lvl3'])) {
        return $hit['hierarchy']['lvl3'];
    }

    if (isset($hit['hierarchy']['lvl2'])) {
        return $hit['hierarchy']['lvl2'];
    }

    if (isset($hit['hierarchy']['lvl1'])) {
        return $hit['hierarchy']['lvl1'];
    }

    return null;
}
