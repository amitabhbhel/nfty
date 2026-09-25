<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Upstox;

class UpstoxController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct($api = new Upstox())
	{
        $this->api = new Upstox();
	}


    //
    public function ins(){
        return json_decode($this->api->getInstrument());
    }



    public function optionChain(){
        return $this->analyseAndSaveChainData($this->api->getOptionChian());
        return $this->api->getOptionChian();
    }

    private function analyseAndSaveChainData($chain){
        $columns_to_analyse = [
            'ce_oi', 'pe_oi',
            'ce_oi_change', 'pe_oi_change',
            'ce_volume', 'pe_volume',            
        ];

        [$status, $chain] = $this->analyseChainData(
            $chain,
            $columns_to_analyse,
        );
        $status =  $this->analyseSupAndRes($status);
        return [$status, $chain];
    }


    private function analyseChainData($chain, $columns){
        $status = [];
        $strikes = [];
        $chain = collect($chain);

        if($chain->count() < 2) return "No chain data available.";

        $status['spot'] = $chain[0]['underlying_spot_price'];
        // difference of strike price
        $base = $chain->get(intdiv($chain->count(),2) +1)['strike_price'] - $chain->get(intdiv($chain->count(),2))['strike_price'];
        # get the imaginary line for ITM & OTM
        $status['line'] = $base*round($status['spot']/$base); //$base * intdiv($chain->get(0)['UnderlyingValue'], $base);

        foreach ($columns as $x) {
            // Find two heigest value of x
            $temp = $chain->sortByDesc($x)->values();
            $hl = $temp->take(2)->values();
            $status[$x.'_hi'] = (bool) $hl[0][$x] ? $hl[0][$x] : -1;
            $status[$x.'_lo'] = (bool) $hl[1][$x] ? $hl[1][$x] : $hl[0][$x];

            // calculate percentage of two heighest values
            $per = (($hl[1][$x]>0) && ($hl[0][$x]>0)) ? round(100 * $hl[1][$x] / $hl[0][$x], 2) : 100;
            $status[$x] = (($per<100) && ($per>=75)) ? ($hl[1]['strike_price'] > $hl[0]['strike_price'] ? "WTT" : "WTB") : "STR";
            $status[$x.'_fr'] = $hl[0]['strike_price'];
            $status[$x.'_to'] = (($per<100) && ($per>=75)) ? $hl[1]['strike_price'] : $hl[0]['strike_price'];
            $status[$x.'_pc'] = (($per<100) && ($per>=75)) ? $per : 100;

            // filtering heighest and lowest strikes for saving and display
            $filtered = $temp->filter(function ($value, $key) use($x, $hl){
                return ($value[$x] > 0) && ($value[$x] > ($hl[0][$x]/2));
            });
            $strikes[] = $filtered->max('strike_price') + $base;
            $strikes[] = $filtered->min('strike_price') - $base;
        }

        return [$status, $chain];
    }


    /**
     * analyze support and resistance status
     */
    private function analyseSupAndRes($status){
        foreach (['CE', 'PE'] as $v){
            $status[($v=='CE' ? 'res' : 'sup').'_fr'] = ($v=='CE')
                ? min($status['ce_oi_fr'], $status['ce_volume_fr'])
                : max($status['pe_oi_fr'], $status['pe_volume_fr']);

            $status[($v=='CE' ? 'res' : 'sup').'_to'] = ($v=='CE')
                ? min($status['ce_oi_to'], $status['ce_volume_to'])
                : max($status['pe_oi_to'], $status['pe_volume_to']);

            $status[($v=='CE' ? 'res' : 'sup')] = ($status[($v=='CE' ? 'res' : 'sup').'_fr'] == $status[($v=='CE' ? 'res' : 'sup').'_to'])
                ? 'STR'
                : (
                    ($status[($v=='CE' ? 'res' : 'sup').'_fr'] < $status[($v=='CE' ? 'res' : 'sup').'_to']) ? 
                    'WTT' : 'WTB'
                );
        }
        return $status;
    }




    // /* 
    // * Analyize Option Chain data
    // */
    // function analyizeChain($chain, $ason, $underlyingPrice){
    //     // convert to a collection and retrun if number of rows are very less
    //     $coll = collect($chain);
    //     if($coll->count() < 2) return;

    //     $coll = $this->timeValue($coll);
    //     $coll = $this->maxPain($coll);
        
    //     // difference of strike price
    //     $base = $coll->get(intdiv($coll->count(),2) +1)['CE_StrikePrice'] - $coll->get(intdiv($coll->count(),2))['CE_StrikePrice'];
    //     # get the imaginary line for ITM & OTM
    //     $line = $base*round($coll->get(0)['UnderlyingValue']/$base); //$base * intdiv($coll->get(0)['UnderlyingValue'], $base);

    //     $status = [];
    //     $strikes = [];
    //     foreach (['CE_OpenInterest', 'CE_Volume', 'PE_OpenInterest', 'PE_Volume', 'CE_ChangeInOI', 'PE_ChangeInOI', 'CE_TV', 'PE_TV'] as $x) {
    //         $temp = $coll->sortByDesc($x)->values();
    //         // $temp = $coll->where('CE_StrikePrice', substr($x, 0, 2)=='CE' ? '>=' : '<=', substr($x, 0, 2)=='CE' ? $line : ($line+$base))->sortByDesc($x)->values();
    //         $hl = $temp->take(2)->values();

    //         $status[$x.'_H1'] = (bool) $hl[0][$x] ? $hl[0][$x] : -1;
    //         $status[$x.'_H2'] = (bool) $hl[1][$x] ? $hl[1][$x] : $hl[0][$x];

    //         $per = (($hl[1][$x]>0) && ($hl[0][$x]>0)) ? round(100 * $hl[1][$x] / $hl[0][$x], 2) : 100;
    //         $status[$x] = (($per<100) && ($per>=75)) ? ($hl[1]['CE_StrikePrice'] > $hl[0]['CE_StrikePrice'] ? "WTT" : "WTB") : "STR";
    //         $status[$x.'_From'] = $hl[0]['CE_StrikePrice'];
    //         $status[$x.'_To'] = (($per<100) && ($per>=75)) ? $hl[1]['CE_StrikePrice'] : $hl[0]['CE_StrikePrice'];
    //         $status[$x.'_Per'] = (($per<100) && ($per>=75)) ? $per : 100;

    //         // finding height and lowest strik prices to display
    //         $filtered = $temp->filter(function ($value, $key) use($x, $hl){
    //             // return ($value[$x] > 0) && (((int)$value[$x]) > intdiv($hl[0][$x], 2));
    //             return ($value[$x] > 0) && ($value[$x] > ($hl[0][$x]/2));
    //         });
    //         $strikes[] = $filtered->max('CE_StrikePrice') + $base;
    //         $strikes[] = $filtered->min('CE_StrikePrice') - $base;
    //     }

    //     // analyze support and resistance status
    //     foreach (['CE', 'PE'] as $v){
    //         $status[($v=='CE' ? 'RES' : 'SUP').'_From'] = ($v=='CE') ? min($status['CE_OpenInterest_From'], $status['CE_Volume_From']) : max($status['PE_OpenInterest_From'], $status['PE_Volume_From']);
    //         $status[($v=='CE' ? 'RES' : 'SUP').'_To'] = ($v=='CE') ? min($status['CE_OpenInterest_To'], $status['CE_Volume_To']) : max($status['PE_OpenInterest_To'], $status['PE_Volume_To']);
    //         $status[($v=='CE' ? 'RES' : 'SUP')] =($status[($v=='CE' ? 'RES' : 'SUP').'_From'] == $status[($v=='CE' ? 'RES' : 'SUP').'_To']) ? 'STR' : (($status[($v=='CE' ? 'RES' : 'SUP').'_From'] < $status[($v=='CE' ? 'RES' : 'SUP').'_To']) ? 'WTT' : 'WTB');
    //     }
        
    //     $status['Line'] = $line;
    //     $status['UnderlyingValue'] = $coll->get(0)['UnderlyingValue'];
    //     $status['AsOn'] = $ason; //date("Y-m-d H:i:s", intval(preg_replace('/\D/', '', $ason))/1000);

    //     $coll = $coll->whereBetween('CE_StrikePrice', [min($strikes), max($strikes)])->values();

    //     return ['base'=> $base, 'line'=> $line, 'status'=> $status, 'coll'=> $coll];
    // }    


    // /**
    //  * Calulate Maxpain for option chain
    //  */
    // public function maxPain($oc){
    //     $noc = $oc->map(function($item, $key) use($oc){
    //         $loss = 0;
    //         foreach ($oc as $x){
    //             $loss += (
    //                 ($item['CE_StrikePrice'] > $x['CE_StrikePrice']) ? 
    //                 $x['CE_OpenInterest']*($item['CE_StrikePrice'] - $x['CE_StrikePrice']) : 
    //                 $x['PE_OpenInterest']*($x['CE_StrikePrice'] - $item['CE_StrikePrice'])
    //             );
    //         }
    //         $item['MaxPain'] = $loss; 
    //         return $item;
    //     });

    //     return $noc;
    // }

    // /**
    //  * Calculate diversions for option cahin data
    //  */
    // public function calculateDiversion($coll, $base, $status){        
    //     $ncoll = $coll->map(function($item, $key) use($coll, $base, $status){
    //         // datetime of chain data to store in db
    //         $item['AsOn'] = $status['AsOn'];

    //         // difference with next strike price 
    //         $nkey = ($key+1)<$coll->count() ? $key+1 : $key;
    //         $item['CE_R2'] = $item['CE_LTP']-$coll[$nkey]['CE_LTP'];
    //         $item['PE_R2'] = $item['PE_LTP']-$coll[$nkey]['PE_LTP'];

    //         // difference with privious strike price
    //         $pkey = ($key-1)<0 ? $key : $key-1;
    //         $item['CE_R1'] = $item['CE_LTP']-$coll[$pkey]['CE_LTP'];
    //         $item['PE_R1'] = $item['PE_LTP']-$coll[$pkey]['PE_LTP'];

    //         // calculated divesion prices
    //         $item['CE_R'] = [];
    //         $item['PE_R'] = [];
    //         foreach (['CE_R1', 'CE_R2', 'PE_R1', 'PE_R2'] as $x) {
    //             $item[$x] = abs(round($item[$x],2));
    //             $p = $item['CE_StrikePrice'] + $item[$x];
    //             $n = $item['CE_StrikePrice'] - $item[$x];
    //             // dont include the price if it cross next or previous strike price
    //             if ($p < $item['CE_StrikePrice']+$base) $item['CE_R'][] = $p;
    //             if ($n > $item['CE_StrikePrice']-$base) $item['PE_R'][] = $n;                
    //         }

    //         sort($item['CE_R']);
    //         sort($item['PE_R']);
    //         $item['CE_R'] = implode(" ", array_unique($item['CE_R']));
    //         $item['PE_R'] = implode(" ", array_unique($item['PE_R']));

    //         return $item;
    //     });

    //     return $ncoll;
    // }


    // {
    //     "expiry": "2026-09-29T00:00:00+00:00",
    //     "strike_price": 15000,
    //     "underlying_key": "NSE_INDEX|Nifty 50",
    //     "underlying_spot_price": 23873.45,
    //     "ce_instrument_key": "NSE_FO|65881",
    //     "ce_ltp": 0,
    //     "ce_volume": 0,
    //     "ce_oi": 0,
    //     "ce_close_price": 10499.3,
    //     "ce_bid_price": 0,
    //     "ce_bid_qty": 0,
    //     "ce_ask_price": 9426.35,
    //     "ce_ask_qty": 1690,
    //     "ce_prev_oi": 0,
    //     "ce_vega": 0,
    //     "ce_theta": 0,
    //     "ce_gamma": 0,
    //     "ce_delta": 1,
    //     "ce_iv": 0,
    //     "ce_pop": 99,
    //     "pe_instrument_key": "NSE_FO|65882",
    //     "pe_ltp": 1.3,
    //     "pe_volume": 8125,
    //     "pe_oi": 83590,
    //     "pe_close_price": 1.25,
    //     "pe_bid_price": 1.2,
    //     "pe_bid_qty": 520,
    //     "pe_ask_price": 1.45,
    //     "pe_ask_qty": 1755,
    //     "pe_prev_oi": 83460,
    //     "pe_vega": 0.2501,
    //     "pe_theta": -0.2855,
    //     "pe_gamma": 0,
    //     "pe_delta": -0.0012,
    //     "pe_iv": 59.33,
    //     "pe_pop": 0.2
    // },    

}
