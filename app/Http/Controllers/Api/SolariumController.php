<?php

namespace App\Http\Controllers\Api;

use Solarium\Client as SolrClient;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher;


class SolariumController extends Controller
{
    protected $client;

    protected $options = [];

    public function __construct()
    {
        $this->options = [
            'endpoint' => [
                'localhost' => [
                    'host' => env('SOLR_HOST', 'localhost'),
                    'port' => env('SOLR_PORT', '8983'),
                    'path' => env('SOLR_PATH', ''),
                    'core' => env('SOLR_CORE', 'collection1'),
                    // 'debug' => env('SOLR_DEBUG', true)
                ]
            ]
        ];
        $adapter = new Curl();
        $eventDispatcher = new EventDispatcher();
		$this->client = new SolrClient($adapter, $eventDispatcher, $this->options);
    }

    public function ping()
    {
        // create a ping query
        $ping = $this->client->createPing();
        // execute the ping query
        try {
            $this->client->ping($ping);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'code' => 500]);
        }
    }

    public function search()
    {
        try {
            $query = $this->client->createSelect();
            $query->addFilterQuery(array('key'=>'provence', 'query'=>'provence:Groningen', 'tag'=>'include'));
            $query->addFilterQuery(array('key'=>'degree', 'query'=>'degree:MBO', 'tag'=>'exclude'));
            $facets = $query->getFacetSet();
            $facets->createFacetField(array('field'=>'degree', 'exclude'=>'exclude'));
            $resultset = $this->client->select($query);
            echo 'NumFound: ' . $resultset->getNumFound();
            foreach ($resultset as $document) {
                echo '<hr/><table>';
                foreach ($document as $field => $value) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                    echo '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
                }
                echo '</table>';
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }
}