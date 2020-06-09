<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

class e2Controller extends Controller
{
    private $matches = array();
    private $attachments = array();

    private $baseUrl = 'https://api.sports-cube.com';



    /**
    * View index page
    *
    *
    */
    public function index()
    {
        $date = Carbon::now('Europe/Vienna')->startOfDay();

        //add one hour since Carbon can't handle well timezone different from GMT0, just workaround for this project
        $datafrom = $date->addDays(1)->addHour()->toIso8601ZuluString();
        $datato = $date->copy()->addDays(2)->endOfDay()->addHour()->toIso8601ZuluString();

        $link = '/v3/de_DE/15/matches?attach=matches.competition&matchdate_from='.$datafrom.'&matchdate_to='.$datato.'&states=PRE';

        $this->getAllData($link);

        $this->orderImportantMatches();

        $matchesDates = $this->separateDateMatches();

        return view('e2')->with(compact('matchesDates'));
    }



    /**
    * Get data from API
    *
    * @param String $link
    *
    * @return json
    */
    private function getData(String $link)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->baseUrl . $link);

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody());
        }

        return $data;
    }


    /**
    * Compile all matches in one array
    *
    * @param Object $data
    *
    */
    private function compileMatches($data)
    {
        foreach ($data->data as $key => $value) {
            array_push($this->matches, $this->getMatchValues($data, $value));
        }
    }


    /**
    * Organize Match Values
    *
    * @param Object $data
    * @param String $value
    *
    * @return array
    */
    private function getMatchValues($data, $value)
    {
        $attachments = $data->attachments->$value;

        $competition = $attachments->competition;

        $type = $this->getData($data->attachments->$competition->country);

        $response = [
          'importance'  => $data->attachments->$competition->globalImportance,
          'date_pos'    => Carbon::create($attachments->matchdate)->format('Ymd'),
          'date'        => Carbon::create($attachments->matchdate)->format('d.m.Y'),
          'hour'        => Carbon::create($attachments->matchdate)->format('H:s'),
          'teamhome'    => $data->attachments->{$attachments->teams->home}->fullname,
          'teamaway'    => $data->attachments->{$attachments->teams->away}->fullname,
          'type'        => $type->data->name,
          'competition' => $data->attachments->$competition->name,
        ];


        return $response;
    }



    /**
    * Recursive get all data from API
    *
    * @param String $link
    *
    */
    private function getAllData($link)
    {
        $data = $this->getData($link);

        $this->compileMatches($data);


        if (!empty($data->pagination->next)) {
            $this->getAllData($data->pagination->next);
        }
    }


    /**
    * Separate Matches by date and sort
    *
    *
    * @return array
    */
    private function separateDateMatches()
    {
        $matches = $this->matches;

        foreach ($matches as $match) {
            $date = $match['date_pos'];

            if (!isset($list[$date])) {
                $list[$date] = new \stdClass();
            }

            $list[$date]->date = $match['date'];
            $list[$date]->data[] = $match;
        }

        foreach ($list as $k=>$value) {
            $list[$k]->data = array_slice($list[$k]->data, 0, 15);
        }

        sort($list);

        return $list;
    }


    /**
    * Sort array by importance and playoff time
    *
    *
    */
    private function orderImportantMatches()
    {
        usort($this->matches, function ($a, $b) {
            return $b['importance'] <=> $a['importance'] ?: $b['hour'] <=> $a['hour'];
        });
    }
}
