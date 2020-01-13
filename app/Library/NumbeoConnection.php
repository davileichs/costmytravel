<?php

namespace App\Library;

use Symfony\Component\DomCrawler\Crawler;
use App\Helpers\HandlingHelper as Handling;


/**
 * Numbeo crawloer and extract data
 *
 * @author Davi Leichsenring
 */
class NumbeoConnection {


   /**
    * @var string Numbero site
    */
    private $baseUrl = 'https://www.numbeo.com/cost-of-living/in/';

    /**
     * @var string result crawler
     */
    private $result;

    /**
     * @var string price
     */
    private $priceMeal;

    /**
     * @var string price
     */
    private $priceTicket;



    /**
     * Start Crawler and save data
     *
     */
    public function __construct(string $search) {

        $url = $this->baseUrl . $search  . '?displayCurrency=EUR';

        $crawler = new Crawler( file_get_contents( $url ) );

        $nodeValues = $crawler->filterXPath( '//table[@class="data_wide_table"]/*')->each( function ( Crawler $node, $i ) {
            return $node->text();
        });

        $this->result = $nodeValues;

    }



    /**
     * Get Data relative from Meals and Transport
     *
     * @return array prices
     */
    public function getData() {

        $priceMeal = 0;
        $priceTicket = 0;

        foreach($this->result as $line) {

            $this->getRegText($line);

        }

        return ['priceMeal' => $this->priceMeal, 'priceTicket' => $this->priceTicket];

    }


    /**
     * Save prices from String
     *
     * @return double price
     */
    private function getRegText($text) {

        $price = 0;

        $regMeal = '/Meal, Inexpensive Restaurant/';
        $regTicket = '/One-way Ticket/';

        preg_match($regMeal, $text, $matchesMeal);
        preg_match($regTicket, $text, $matchesTicket);

        if ($matchesMeal) {
            $this->priceMeal = $this->getPrice($text);
        }

        if ($matchesTicket) {
            $this->priceTicket = $this->getPrice($text);
        }


    }



    /**
     * Get price from string
     *
     * @return array object
     */
    private function getPrice($text) {

        $reg = '/(?<price>(\d+\.\d+))/';

        preg_match($reg, $text, $matches);

        if ($matches['price']) {
          return $matches['price'];
        }

        return 0;

    }


}
