<?php

namespace App\Http\Controllers;
use App\Record;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Log;

class PackageController extends Controller
{
    protected $delimiter  = ';';

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getPackage()
    {
        $user = Auth::user();
        $id = $user['id'];
        $paczka =
            Log::join('woj', 'log_download.idwoj', 'woj.idwoj')
                ->where('id_user', '=', $id)
                ->orderBy('date','desc')
                ->limit(20)
                ->get();
        return view('package.history')->with('paczka',$paczka);
    }

    function setArray($tab)
    {
        $tablica = array();
        $tablica2 = array();
        foreach ($tab as $item)
        {
            array_push($tablica,$item);
        }

        foreach ($tablica as $item)
        {
            foreach ($item as $value)
            {
                array_push($tablica2,$value);
            }
        }
        return $tablica2;
    }
    public function historyCSVDownload()
    {
        $naglowek = session()->get('naglowek');
        $napis = session()->get('napis');


        session()->forget('naglowek');
        session()->forget('napis');
        //Zwrocenie Pliku do pobrania
        return Excel::create($napis, function ($excel) use ($naglowek) {
            $excel->sheet('sheet1', function ($sheet) use ($naglowek) {
                $sheet->fromArray($naglowek, null, 'A1', false, false);
            });
        })->export('csv');

    }
    public function HistoryCSV(Request $request)
    {
        $id = $request['id'];
        $miasto = $request['miasto'];
        $baza = $request['baza'];
        $baza8 = $request['bis'];
        $zg = $request['zg'];
        $ev = $request['event'];
        $reszta = $request['reszta'];

        $system = 1;
        if($baza == "Wysylka") {
            $dane =
                Record::select('imie', 'nazwisko', 'nrdomu', 'ulica', 'nrdomu', 'nrmieszkania', 'miasto', 'idkod', 'telefon','data_wysylka as data')
                    ->where('wysylka', '=', $id)
                    ->get();
            $system = 1;
        }else
        {
            $dane =
                Record::select('imie', 'nazwisko', 'nrdomu', 'ulica', 'nrdomu', 'nrmieszkania', 'miasto', 'idkod', 'telefon','data as data')
                    ->where('badania', '=', $id)
                    ->get();
            $system = 0;
        }
        print_r($dane);
        $dane = json_decode(json_encode((array) $dane), true);
        $dane = self::setArray($dane);


        $data = $dane[0]['data'];
        $napis = $miasto.'_8-'.$baza8.'_zg-'.$zg.'_ev-'.$ev.'_r-'.$reszta;
        $napis = $napis.'_'.$data;

        // Tablica Naglówka
        $naglowek = array();
        //Na podstawie wybranego systemu strzoenie odpowiedniego nagłówka
        if($system == 0)
            $naglowek[] = array('Imie','Nazwisko','Ulica','Nr. Domu','Nr. Mieszkania','Miasto','Kod','Telefon');
        else
            $naglowek[] = array('Telefon','Imie','Nazwisko','Ulica','Nr. Domu','Kod Pocztowy','Miasto');
        //Wpisanie danych do pliku
        foreach ($dane as $item)
        {
            if($system == 0)
                $naglowek[] = array($item['imie'],$item['nazwisko'],$item['ulica'],$item['nrdomu'],$item['nrmieszkania'],$item['miasto'],$item['idkod'],$item['telefon']);
            else
                $naglowek[] = array($item['telefon'],$item['imie'],$item['nazwisko'],$item['ulica'],$item['nrdomu'],$item['idkod'],$item['miasto']);
        }

        session()->put('naglowek',$naglowek);
        session()->put('napis',$napis);



    }


}