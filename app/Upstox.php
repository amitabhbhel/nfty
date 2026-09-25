<?php

namespace App;

use GuzzleHttp\Client;
use Upstox\Client\Configuration;
use Upstox\Client\Api\InstrumentsApi;
use Upstox\Client\Api\OptionsApi;
use Upstox\Client\Api\ExpiredInstrumentApi;
use Upstox\Client\ObjectSerializer;

class Upstox
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        // Configure OAuth2 access token
        $this->config = Configuration::getDefaultConfiguration()
            ->setAccessToken(env('ANALYTIC_TOKEN'));
    }


    /**
     * Fetch instrument
     */
    public function getInstrument(){
        $apiInstance = new InstrumentsApi(
            new Client(),
            $this->config
        );

        // Define search keyword (e.g., 'RELIANCE')
        $searchKeyword = 'NIFTY';

        try {
            // Perform search
            //$query, $exchanges = null, $segments = null, $instrument_types = null, $expiry = null, $atm_offset = null, $page_number = '1', $records = '10'
            $result = $apiInstance->searchInstrument($searchKeyword);
            return $result;
        } catch (Exception $e) {
            echo 'Exception: ', $e->getMessage(), PHP_EOL;
        }
    }



    /**
     * Fetch option chian
     */
    function getOptionChian(){
        $apiInstance = new OptionsApi(
            new Client(),
            $this->config
        );

        $params = [
            'instrument_key' => "NSE_INDEX|Nifty 50",
            'expiry_date' => 'current_month'
        ];
        
        try {
            // Perform search
            $result = $apiInstance->getPutCallOptionChain("NSE_INDEX|Nifty 50", 'current_month');
            // Convert the SDK Model object directly into a clean PHP Array
            $result = ObjectSerializer::sanitizeForSerialization($result);
            return $this->processChainData($result->data);

        } catch (Exception $e) {
            echo 'Exception: ', $e->getMessage(), PHP_EOL;
        }
    }


    /**
     * convert option multistage chain data into single level array
     */
    private function processChainData($array){
        $chain = [];
        foreach ($array as $k => $v) {
            $chain[$k] = $this->normalizeArrayWithKeys($v);
            
            // add oi_change in chain data
            $chain[$k]['ce_oi_change'] = $chain[$k]['ce_oi'] - $chain[$k]['ce_prev_oi'];
            $chain[$k]['pe_oi_change'] = $chain[$k]['pe_oi'] - $chain[$k]['pe_prev_oi'];

            // add time value in chain date
            [$chain[$k]['ce_tv'], $chain[$k]['pe_tv']] = $this->timeValue($chain[$k]);

            // remove unwanted keys from chain data
            // $chain[$k] = $this->removeUnwantedKeys($chain[$k]);
        }
        return $chain;        
    }
    // normalize multilvevl array in single level array    
    private function normalizeArrayWithKeys($array, $prefix = '') {
        $row = [];    
        foreach($array as $key => $value) {
            $newKey = $prefix ? $prefix : (($key == "call_options") ? 'ce_' : ($key == "put_options" ? "pe_" : ""));
            
            if (is_object($value) || is_array($value)) {
                $row = array_merge($row, $this->normalizeArrayWithKeys($value, $newKey ));
            } else {
                $row[$newKey.$key] = $value;
            }  
        }
        return $row;
    }
    // calculate time value
    private function timeValue($item){
        $ce_iv = max(($item['underlying_spot_price'] - $item['strike_price']), 0);
        $pe_iv = max(($item['strike_price'] - $item['underlying_spot_price']), 0);

        $item['ce_tv'] = round($item['ce_ltp'] > 0 ? $item['ce_ltp'] - $ce_iv : 0, 2);
        $item['pe_tv'] = round($item['pe_ltp'] > 0 ? $item['pe_ltp'] - $pe_iv : 0, 2);

        return [$item['ce_tv'], $item['pe_tv']];
    }
    // remove unwanted keys
    private function removeUnwantedKeys($item){
        $keys = ['underlying_key', 'underlying_spot_price'];
        foreach ($keys as $key) {
            unset($item[$key]);
        }
        return $item;
    }


    /**
     * Fetch expiries of partucular instrument
     */
    function getExpiries(){
        $apiInstance = new ExpiredInstrumentApi(
            new Client(),
            $this->config
        );
       
        try {
            // Perform search
            //$query, $exchanges = null, $segments = null, $instrument_types = null, $expiry = null, $atm_offset = null, $page_number = '1', $records = '10'
            $result = $apiInstance->getExpiries("NSE_INDEX|Nifty 50",);
            return $result;
        } catch (Exception $e) {
            echo 'Exception: ', $e->getMessage(), PHP_EOL;
        }
    }
}



