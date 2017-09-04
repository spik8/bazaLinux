<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
class RaportsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getRaport()
    {
        return view('raports.raport');
    }

    public function getRaportUser()
    {
        return view('raports.raportuser');
    }

    public function getRaportPlus()
    {
        return view('raports.raportplus');
    }

    public function getRaportUserPlus()
    {
        return view('raports.raportuserplus');
    }

    public function setRaportuserPlus(Request $request)
    {
        $datajeden = $request->input('datejeden');
        $dataod = $request->input('dateod');
        $datado = $request->input('datedo');
        $tablica = array();
        if($datajeden != '')
        {
            array_push($tablica,$datajeden);
            $this->setSingleRaportuserPlus($tablica,1);
        }else if($datajeden =='' && ($dataod !='' && $datado !=''))
        {
            array_push($tablica,$dataod);
            array_push($tablica,$datado);
            $this->setSingleRaportuserPlus($tablica,2);
        }else
        {
            return view('raports.raportuserplus');
        }
        return view('raports.raportuserplusPOST')
            ->with('dataraportu',$tablica)
            ->with('dane',session()->get('resandship'))
            ->with('oddzialy', session()->get('departamentres'))
            ->with('zapytanie',session()->get('departamentship'))
            ->with('employeeres',session()->get('employeeres'))
            ->with('cityres',session()->get('cityres'))
            ->with('cityship',session()->get('cityship'))
            ->with('employeeship',session()->get('employeeship'));

    }


    public function setRaportUser(Request $request)
    {
        $datajeden = $request->input('datejeden');
        $dataod = $request->input('dateod');
        $datado = $request->input('datedo');
        $tablica = array();
        if($datajeden != '')
        {
            array_push($tablica,$datajeden);
            $this->setSingleRaportUser($tablica,1);
        }else if($datajeden =='' && ($dataod !='' && $datado !=''))
        {
            if($dataod > $datado)
            {
                return view('raports.raportuser');
            }
            array_push($tablica,$dataod);
            array_push($tablica,$datado);
            $this->setSingleRaportUser($tablica,2);
        }else
        {
            return view('raports.raportuser');
        }
        return view('raports.raportuserPOST')
            ->with('dataraportu',$tablica)
            ->with('dane',session()->get('resandship'))
            ->with('oddzialy', session()->get('departamentres'))
            ->with('zapytanie',session()->get('departamentship'))
            ->with('employeeres',session()->get('employeeres'))
            ->with('employeeship',session()->get('employeeship'));

    }
    public function setRaport(Request $request)
    {
        $datajeden = $request->input('datejeden');
        $dataod = $request->input('dateod');
        $datado = $request->input('datedo');
        $tablica = array();
        if($datajeden != '')
        {
            array_push($tablica,$datajeden);
            $this->setSingleRaport($tablica,1);
        }else if($datajeden =='' && ($dataod !='' && $datado !=''))
        {
            if($dataod > $datado)
            {
                return view('raports.raport');
            }
            array_push($tablica,$dataod);
            array_push($tablica,$datado);
            $this->setSingleRaport($tablica,2);
        }else
        {
            return view('raports.raport');
        }
        return view('raports.raportPOST')
            ->with('dataraportu',$tablica)
            ->with('dane',session()->get('resandship'))
            ->with('oddzialy', session()->get('departamentres'))
            ->with('zapytanie',session()->get('departamentship'))
            ->with('employeeres',session()->get('employeeres'))
            ->with('employeeship',session()->get('employeeship'));

    }


    public function setRaportPlus(Request $request)
    {
        $datajeden = $request->input('datejeden');
        $dataod = $request->input('dateod');
        $datado = $request->input('datedo');
        $tablica = array();
        if($datajeden != '')
        {
            array_push($tablica,$datajeden);
            $this->setSingleRaportPlus($tablica,1);
        }else if($datajeden =='' && ($dataod !='' && $datado !=''))
        {
            array_push($tablica,$dataod);
            array_push($tablica,$datado);
            $this->setSingleRaportPlus($tablica,2);
        }else
        {
            return view('raports.raportplus');
        }
        return view('raports.raportplusPOST')
            ->with('dataraportu',$tablica)
            ->with('dane',session()->get('resandship'))
            ->with('oddzialy', session()->get('departamentres'))
            ->with('zapytanie',session()->get('departamentship'))
            ->with('employeeres',session()->get('employeeres'))
            ->with('cityres',session()->get('cityres'))
            ->with('cityship',session()->get('cityship'))
            ->with('employeeship',session()->get('employeeship'));

    }




//RAPORT DLA POJEDYNCZEJ OSOBY
    function setSingleRaportUser($date,$typ)
    {
///////////////////////////////////////BADANIA Wysylka/////////////////////////////////////////////
        $user = Auth::user();
        $id = $user->id;
        if($typ == 1) {
            $resandship = DB::table('log_download')
                ->selectRaw('baza,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->where(function ($q) {
                    $q->where('baza', 'Badania')
                        ->orWhere('baza', 'Wysylka');
                })
                ->where('id_user', '=', $id)
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('baza')
                ->get();
        }else {
            $resandship = DB::table('log_download')
                ->selectRaw('baza,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->where(function ($q) {
                    $q->where('baza', 'Badania')
                        ->orWhere('baza', 'Wysylka');
                })
                ->where('id_user', '=', $id)
                ->whereBetween('date', [$date[0].'%',$date[1].' 23:59:59'])
                ->groupBy('baza')
                ->get();
        }
        $resandship = json_decode(json_encode((array) $resandship), true);
        $resandship = $this->setArray($resandship);

///////////////////////////////////////BADANIA Oddziały/////////////////////////////////////////////
        if($typ == 1) {
            $departamentres = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('id_user', '=', $id)
                ->where('baza', 'like', 'Badania')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }else
        {
            $departamentres = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('id_user', '=', $id)
                ->where('baza', 'like', 'Badania')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }
        $departamentres = json_decode(json_encode((array) $departamentres), true);
        $departamentres = $this->setArray($departamentres);

///////////////////////////////////////WYSYLKA Oddziały/////////////////////////////////////////////
        if($typ == 1) {
            $departamentship = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('id_user', '=', $id)
                ->where('baza', 'like', 'Wysylka')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }else
        {
            $departamentship = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('id_user', '=', $id)
                ->where('baza', 'like', 'Wysylka')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }
        $departamentship = json_decode(json_encode((array) $departamentship), true);
        $departamentship = $this->setArray($departamentship);

///////////////////////////////////////Badania Pracownicy/////////////////////////////////////////////
        if($typ == 1) {
            $employeeres = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('id_user', '=', $id)
                ->where('baza', 'like', 'Badania')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->get();
        }
        else
        {
            $employeeres = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Badania')
                ->where('id_user', '=', $id)
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->get();
        }
        $employeeres = json_decode(json_encode((array) $employeeres), true);
        $employeeres = $this->setArray($employeeres);

///////////////////////////////////////Wysylka Pracownicy/////////////////////////////////////////////
        if($typ == 1) {
            $employeeship = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('id_user', '=', $id)
                ->where('baza', 'like', 'Wysylka')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->get();
        }else
        {
            $employeeship = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('id_user', '=', $id)
                ->where('baza', 'like', 'Wysylka')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->get();
        }
        $employeeship = json_decode(json_encode((array) $employeeship), true);
        $employeeship = $this->setArray($employeeship);


        session()->put('resandship',$resandship);
        session()->put('departamentres',$departamentres);
        session()->put('departamentship',$departamentship);
        session()->put('employeeres',$employeeres);
        session()->put('employeeship',$employeeship);

    }















    function setSingleRaport($date,$typ)
    {
///////////////////////////////////////BADANIA Wysylka/////////////////////////////////////////////
        if($typ == 1) {
            $resandship = DB::table('log_download')
                ->selectRaw('baza,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->where(function ($q) {
                    $q->where('baza', 'Badania')
                        ->orWhere('baza', 'Wysylka');
                })
                ->where('id_user', '>', 100)
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('baza')
                ->get();
        }else {
                    $resandship = DB::table('log_download')
                    ->selectRaw('baza,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                    ->where(function ($q) {
                        $q->where('baza', 'Badania')
                            ->orWhere('baza', 'Wysylka');
                    })
                    ->where('id_user', '>', 100)
                    ->whereBetween('date', [$date[0].'%',$date[1].' 23:59:59'])
                    ->groupBy('baza')
                    ->get();
        }
            $resandship = json_decode(json_encode((array) $resandship), true);
            $resandship = $this->setArray($resandship);

///////////////////////////////////////BADANIA Oddziały/////////////////////////////////////////////
        if($typ == 1) {
            $departamentres = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('baza', 'like', 'Badania')
                ->where('departments_t.id', '!=', '1')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }else
        {
            $departamentres = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('baza', 'like', 'Badania')
                ->where('departments_t.id', '!=', '1')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }
        $departamentres = json_decode(json_encode((array) $departamentres), true);
        $departamentres = $this->setArray($departamentres);

///////////////////////////////////////WYSYLKA Oddziały/////////////////////////////////////////////
        if($typ == 1) {
            $departamentship = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('baza', 'like', 'Wysylka')
                ->where('date', 'like', $date[0] . '%')
                ->where('departments_t.id', '!=', '1')
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }else
        {
            $departamentship = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('baza', 'like', 'Wysylka')
                ->where('departments_t.id', '!=', '1')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }
        $departamentship = json_decode(json_encode((array) $departamentship), true);
        $departamentship = $this->setArray($departamentship);

///////////////////////////////////////Badania Pracownicy/////////////////////////////////////////////
        if($typ == 1) {
            $employeeres = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Badania')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->get();
        }
        else
        {
            $employeeres = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Badania')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->get();
        }
        $employeeres = json_decode(json_encode((array) $employeeres), true);
        $employeeres = $this->setArray($employeeres);

///////////////////////////////////////Wysylka Pracownicy/////////////////////////////////////////////
        if($typ == 1) {
            $employeeship = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Wysylka')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->get();
        }else
        {
            $employeeship = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Wysylka')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->get();
        }
        $employeeship = json_decode(json_encode((array) $employeeship), true);
        $employeeship = $this->setArray($employeeship);


        session()->put('resandship',$resandship);
        session()->put('departamentres',$departamentres);
        session()->put('departamentship',$departamentship);
        session()->put('employeeres',$employeeres);
        session()->put('employeeship',$employeeship);
    }



    function setSingleRaportPlus($date,$typ)
    {
///////////////////////////////////////BADANIA Wysylka/////////////////////////////////////////////
        if($typ == 1) {
            $resandship = DB::table('log_download')
                ->selectRaw('baza,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->where(function ($q) {
                    $q->where('baza', 'Badania')
                        ->orWhere('baza', 'Wysylka');
                })
                ->where('id_user', '>', 100)
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('baza')
                ->get();
        }else {
            $resandship = DB::table('log_download')
                ->selectRaw('baza,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->where(function ($q) {
                    $q->where('baza', 'Badania')
                        ->orWhere('baza', 'Wysylka');
                })
                ->where('id_user', '>', 100)
                ->whereBetween('date', [$date[0].'%',$date[1].' 23:59:59'])
                ->groupBy('baza')
                ->get();
        }
        $resandship = json_decode(json_encode((array) $resandship), true);
        $resandship = $this->setArray($resandship);

///////////////////////////////////////BADANIA Oddziały/////////////////////////////////////////////
        if($typ == 1) {
            $departamentres = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('baza', 'like', 'Badania')
                ->where('departments_t.id', '!=', '1')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }else
        {
            $departamentres = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('baza', 'like', 'Badania')
                ->where('departments_t.id', '!=', '1')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }
        $departamentres = json_decode(json_encode((array) $departamentres), true);
        $departamentres = $this->setArray($departamentres);

///////////////////////////////////////WYSYLKA Oddziały/////////////////////////////////////////////
        if($typ == 1) {
            $departamentship = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('baza', 'like', 'Wysylka')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }else
        {
            $departamentship = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('baza', 'like', 'Wysylka')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }
        $departamentship = json_decode(json_encode((array) $departamentship), true);
        $departamentship = $this->setArray($departamentship);

///////////////////////////////////////Badania Pracownicy/////////////////////////////////////////////
        if($typ == 1) {
            $employeeres = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last,users_t.id, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Badania')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->groupBy('users_t.id')
                ->get();
        }
        else
        {
            $employeeres = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last,users_t.id, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Badania')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->groupBy('users_t.id')
                ->get();
        }
        $employeeres = json_decode(json_encode((array) $employeeres), true);
        $employeeres = $this->setArray($employeeres);

///////////////////////////////////////Wysylka Pracownicy/////////////////////////////////////////////
        if($typ == 1) {
            $employeeship = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last,users_t.id, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Wysylka')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->groupBy('users_t.id')
                ->get();
        }else
        {
            $employeeship = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last,users_t.id, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Wysylka')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->groupBy('users_t.id')
                ->get();
        }
        $employeeship = json_decode(json_encode((array) $employeeship), true);
        $employeeship = $this->setArray($employeeship);

        if($typ == 1) {
            $cityres = DB::table('log_download')
                ->selectRaw('woj.woj,miasto,id_user,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('woj', 'log_download.idwoj', 'woj.idwoj')
                ->where('baza', 'Badania')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('miasto', 'id_user', 'woj.woj')
                ->get();

        }else {
            $cityres = DB::table('log_download')
                ->selectRaw('woj.woj,miasto,id_user,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('woj', 'log_download.idwoj', 'woj.idwoj')
                ->where('baza', 'Badania')
                -> whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('miasto', 'id_user', 'woj.woj')
                ->get();
        }
        $cityres = json_decode(json_encode((array)$cityres), true);
        $cityres = $this->setArray($cityres);

        if($typ == 1) {
            $cityship = DB::table('log_download')
                ->selectRaw('woj.woj,miasto,id_user,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('woj', 'log_download.idwoj', 'woj.idwoj')
                ->where('baza', 'Wysylka')
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('miasto', 'id_user', 'woj.woj')
                ->get();

        }else {
            $cityship = DB::table('log_download')
                ->selectRaw('woj.woj,miasto,id_user,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('woj', 'log_download.idwoj', 'woj.idwoj')
                ->where('baza', 'Wysylka')
                -> whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('miasto', 'id_user', 'woj.woj')
                ->get();
        }
        $cityship = json_decode(json_encode((array)$cityship), true);
        $cityship = $this->setArray($cityship);

        session()->put('resandship', $resandship);
        session()->put('departamentres', $departamentres);
        session()->put('departamentship', $departamentship);
        session()->put('employeeres', $employeeres);
        session()->put('employeeship', $employeeship);
        session()->put('cityres', $cityres);
        session()->put('cityship', $cityship);

    }


    function setSingleRaportuserPlus($date,$typ)
    {
        $user = Auth::user();
        $id = $user->id;
///////////////////////////////////////BADANIA Wysylka/////////////////////////////////////////////
        if($typ == 1) {
            $resandship = DB::table('log_download')
                ->selectRaw('baza,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->where(function ($q) {
                    $q->where('baza', 'Badania')
                        ->orWhere('baza', 'Wysylka');
                })
                ->where('id_user', '=', $id)
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('baza')
                ->get();
        }else {
            $resandship = DB::table('log_download')
                ->selectRaw('baza,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->where(function ($q) {
                    $q->where('baza', 'Badania')
                        ->orWhere('baza', 'Wysylka');
                })
                ->where('id_user', '=', $id)
                ->whereBetween('date', [$date[0].'%',$date[1].' 23:59:59'])
                ->groupBy('baza')
                ->get();
        }
        $resandship = json_decode(json_encode((array) $resandship), true);
        $resandship = $this->setArray($resandship);

///////////////////////////////////////BADANIA Oddziały/////////////////////////////////////////////
        if($typ == 1) {
            $departamentres = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('baza', 'like', 'Badania')
                ->where('id_user', '=', $id)
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }else
        {
            $departamentres = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('baza', 'like', 'Badania')
                ->where('id_user', '=', $id)
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }
        $departamentres = json_decode(json_encode((array) $departamentres), true);
        $departamentres = $this->setArray($departamentres);

///////////////////////////////////////WYSYLKA Oddziały/////////////////////////////////////////////
        if($typ == 1) {
            $departamentship = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('baza', 'like', 'Wysylka')
                ->where('id_user', '=', $id)
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }else
        {
            $departamentship = DB::table('log_download')
                ->selectRaw('departments_t.name,departments_t.id,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('departments_t', 'users_t.dep_id', 'departments_t.id')
                ->where('baza', 'like', 'Wysylka')
                ->where('id_user', '=', $id)
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('departments_t.name')
                ->groupBy('departments_t.id')
                ->get();
        }
        $departamentship = json_decode(json_encode((array) $departamentship), true);
        $departamentship = $this->setArray($departamentship);

///////////////////////////////////////Badania Pracownicy/////////////////////////////////////////////
        if($typ == 1) {
            $employeeres = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last,users_t.id, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Badania')
                ->where('id_user', '=', $id)
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->groupBy('users_t.id')
                ->get();
        }
        else
        {
            $employeeres = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last,users_t.id, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Badania')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->where('id_user', '=', $id)
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->groupBy('users_t.id')
                ->get();
        }
        $employeeres = json_decode(json_encode((array) $employeeres), true);
        $employeeres = $this->setArray($employeeres);

///////////////////////////////////////Wysylka Pracownicy/////////////////////////////////////////////
        if($typ == 1) {
            $employeeship = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last,users_t.id, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Wysylka')
                ->where('date', 'like', $date[0] . '%')
                ->where('id_user', '=', $id)
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->groupBy('users_t.id')
                ->get();
        }else
        {
            $employeeship = DB::table('log_download')
                ->selectRaw('users_t.name,users_t.last,users_t.id, users_t.dep_id, sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('users_t', 'id_user', 'users_t.id')
                ->where('baza', 'like', 'Wysylka')
                ->whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->where('id_user', '=', $id)
                ->groupBy('users_t.name')
                ->groupBy('users_t.last')
                ->groupBy('users_t.dep_id')
                ->groupBy('users_t.id')
                ->get();
        }
        $employeeship = json_decode(json_encode((array) $employeeship), true);
        $employeeship = $this->setArray($employeeship);

        if($typ == 1) {
            $cityres = DB::table('log_download')
                ->selectRaw('woj.woj,miasto,id_user,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('woj', 'log_download.idwoj', 'woj.idwoj')
                ->where('baza', 'Badania')
                ->where('id_user', '=', $id)
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('miasto', 'id_user', 'woj.woj')
                ->get();

        }else {
            $cityres = DB::table('log_download')
                ->selectRaw('woj.woj,miasto,id_user,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('woj', 'log_download.idwoj', 'woj.idwoj')
                ->where('baza', 'Badania')
                ->where('id_user', '=', $id)
                -> whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('miasto', 'id_user', 'woj.woj')
                ->get();
        }
        $cityres = json_decode(json_encode((array)$cityres), true);
        $cityres = $this->setArray($cityres);

        if($typ == 1) {
            $cityship = DB::table('log_download')
                ->selectRaw('woj.woj,miasto,id_user,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('woj', 'log_download.idwoj', 'woj.idwoj')
                ->where('baza', 'Wysylka')
                ->where('id_user', '=', $id)
                ->where('date', 'like', $date[0] . '%')
                ->groupBy('miasto', 'id_user', 'woj.woj')
                ->get();

        }else {
            $cityship = DB::table('log_download')
                ->selectRaw('woj.woj,miasto,id_user,sum(baza8) as bisnode,sum(bazazg) as zgody,sum(bazareszta) as reszta,sum(bazaevent) as event,sum(baza8)+sum(bazazg)+sum(bazareszta)+sum(bazaevent) as suma')
                ->join('woj', 'log_download.idwoj', 'woj.idwoj')
                ->where('baza', 'Wysylka')
                ->where('id_user', '=', $id)
                -> whereBetween('date', [$date[0] . '%', $date[1] . ' 23:59:59'])
                ->groupBy('miasto', 'id_user', 'woj.woj')
                ->get();
        }
        $cityship = json_decode(json_encode((array)$cityship), true);
        $cityship = $this->setArray($cityship);

        session()->put('resandship', $resandship);
        session()->put('departamentres', $departamentres);
        session()->put('departamentship', $departamentship);
        session()->put('employeeres', $employeeres);
        session()->put('employeeship', $employeeship);
        session()->put('cityres', $cityres);
        session()->put('cityship', $cityship);

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



    public function setdata()
    {
        return view('raports.raport');
    }

}
