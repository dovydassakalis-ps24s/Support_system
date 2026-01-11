<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bilietas;
use Carbon\Carbon;
use Illuminate\Support\Facades\PDF;
use Illuminate\Support\Facades\Mail;


class TicketController extends Controller
{
    // Rodo bilieto kūrimo formą
    public function kurti()
    {
        return view('bilietas.kurti');
    }

    // Saugo naują bilietą į db
    public function store(Request $request)
    {
        // Validacija
        $request->validate([
            'pavadinimas' => 'required|string|max:255',
            'prioritetas' => 'required|string',
            'kategorija' => 'required|string',
            'aprasymas' => 'required|string|max:500',
        ]);

        // Generuojam unikalų bilieto ID paskutiniais 5 timestamp'o skaitmenim
        do {
            $id = substr(time(), -5);
        } while (Bilietas::where('bilieto_id', $id)->exists());

        // Sukuriam bilietą
        Bilietas::create([
            'bilieto_id' => $id,
            'user_id' => Auth::id(),
            'pavadinimas' => $request->pavadinimas,
            'prioritetas' => $request->prioritetas,
            'kategorija' => $request->kategorija,
            'aprasymas' => $request->aprasymas,
            'statusas' => 'Laukiama',
            'uzregistruota' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Bilietas sėkmingai sukurtas.');
    }

    // Rodo aktyvius bilietus - filtaruoja pagal vartotoją jei reikia
    public function aktyvus(Request $request)
    {
        $rodytiTikMano = $request->query('mano') === '1';

        $query = Bilietas::query();

        // Rodo tik mano bilietus jei pasirinkta
        if ($rodytiTikMano) {
            $query->where('user_id', Auth::id());
        }
        // grupuoja pagal statusa
        $bilietai = $query->get()->groupBy('statusas');

        return view('aktyvus', compact('bilietai', 'rodytiTikMano'));
    }

    // Keičia bilieto statusą ir siunčia el. laišką jei reik
    public function keistiStatusa(Request $request)
    {
        // Validacija
        $request->validate([
            'id' => 'required|integer',
            'statusas' => 'required|string'
        ]);
  
        $bilietas = Bilietas::findOrFail($request->id);

        $senas_statusas = $bilietas->statusas;
        $naujas_statusas = $request->statusas;

        $bilietas->statusas = $naujas_statusas;

        // Jei statusas pakeičiamas į Laukiama, išvalom uždarymo datą ir komentarą
        if ($naujas_statusas === 'Vykdoma') {
            $bilietas->uzdaryta = null;
            $bilietas->komentaras = "";
        }

        $bilietas->save();

        // Siunčiam laišką tik jei statusas keičiasi iš Laukiama → Vykdoma
        if ($senas_statusas === 'Laukiama' && $naujas_statusas === 'Vykdoma') {
            $vartotojas = $bilietas->user;

            if ($vartotojas) {
                Mail::send('el_laiskai.statusas_i_vykdoma',
                    ['bilietas' => $bilietas],
                    function ($zinute) use ($vartotojas, $bilietas) {
                        $zinute->to($vartotojas->email)
                            ->subject("Bilieto #{$bilietas->bilieto_id} statusas pakeistas");
                    }
                );
            } else {
                \Log::warning("Bilietas #{$bilietas->id} neturi priskirto vartotojo — laiškas nesiunčiamas.");
            }
        }

        return response()->json(['success' => true]);
    }

    // Rodo bilieto redagavimo formą
    public function redaguoti($id)
    {
        $bilietas = Bilietas::findOrFail($id);

        // Leidžiama redaguoti tik savo bilietus, kurių statusas "Laukiama"
        if ($bilietas->user_id !== Auth::id() || $bilietas->statusas !== 'Laukiama') {
            abort(403);
        }

        return view('redaguoti', compact('bilietas'));
    }

    // bilieto atnaujinimas
    public function atnaujinti(Request $request, $id)
    {
        $bilietas = Bilietas::findOrFail($id);

        // Leidžiama atnaujinti tik savo bilietus, kurių statusas "Laukiama"
        if ($bilietas->user_id !== Auth::id() || $bilietas->statusas !== 'Laukiama') {
            abort(403);
        }
        // Validacija
        $request->validate([
            'pavadinimas' => 'required|string|max:255',
            'prioritetas' => 'required|string',
            'kategorija' => 'required|string',
            'aprasymas' => 'required|string|max:500',
        ]);
        // Atnaujinam bilietą
        $bilietas->update([
            'pavadinimas' => $request->pavadinimas,
            'prioritetas' => $request->prioritetas,
            'kategorija' => $request->kategorija,
            'aprasymas' => $request->aprasymas,
        ]);

        return redirect()->route('aktyvus')->with('success', 'Bilietas atnaujintas.');
    }

    // Bilieto ištrynimas
    public function istrinti($id)
    {
        $bilietas = Bilietas::findOrFail($id);
        $user = Auth::user();

        // Admin gali trinti viską
        if ($user->name === 'admin') {
            $bilietas->delete();
            return response()->json(['success' => true]);
        }

        // Paprastas vartotojas — tik savo ir tik Laukiama
        if ($bilietas->user_id !== $user->id || $bilietas->statusas !== 'Laukiama') {
            abort(403);
        }

        $bilietas->delete();
        return response()->json(['success' => true]);
    }

    // Prideda komentarą ir uždaro bilietą
    public function pridetiKomentara(Request $request, $id)
    {
        // Validacija
        $request->validate([
            'komentaras' => 'required|string|max:2000'
        ]);

        $bilietas = Bilietas::findOrFail($id);
        $senas_statusas = $bilietas->statusas;

        // komentaras ir statuso keitimas į įvykdyta
        $bilietas->komentaras = $request->komentaras;
        $bilietas->statusas = "Įvykdyta";
        $bilietas->uzdaryta = now();
        $bilietas->save();

        // Siunčiam laišką tik jei statusas buvo Vykdoma
        if ($senas_statusas === 'Vykdoma') {
            $vartotojas = $bilietas->user;

            if ($vartotojas) {
                Mail::send('el_laiskai.statusas_i_ivykdyta',
                    ['bilietas' => $bilietas],
                    function ($zinute) use ($vartotojas, $bilietas) {
                        $zinute->to($vartotojas->email)
                            ->subject("Bilietas #{$bilietas->bilieto_id} uždarytas");
                    }
                );
            } else {
                \Log::warning("Bilietas #{$bilietas->id} neturi priskirto vartotojo — laiškas nesiunčiamas.");
            }
        }

        return response()->json([
            'success' => true,
            'uzregistruota' => $bilietas->uzregistruota,
            'uzdaryta' => $bilietas->uzdaryta,
            'komentaras' => $bilietas->komentaras ?? '',
        ]);
    }

    // Generuoja PDF ataskaitą su aktyviais bilietais
    public function aktyviuAtaskaita()
    {
        $bilietai = Bilietas::whereIn('statusas', ['Laukiama', 'Vykdoma'])->get();
        $data = Carbon::now()->format('Y-m-d');

        $pdf = \PDF::loadView('pdf.active', compact('bilietai'));

        return $pdf->download("aktyvus_bilietai_{$data}.pdf");
    }

    // Siunčia PDF ataskaitą el. paštu
    public function siustiAtaskaita(Request $request)
    {
        // Validacija
        $request->validate([
            'email' => 'required|email'
        ]);

        $bilietai = Bilietas::whereIn('statusas', ['Laukiama', 'Vykdoma'])->get();
        $data = Carbon::now()->format('Y-m-d');
        $pdf = \PDF::loadView('pdf.active', compact('bilietai'))->output();

    // Siunčia PDF el. paštu
    Mail::send([], [], function ($message) use ($pdf, $request, $data) {
        $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->to($request->email)
            ->subject("Aktyvių bilietų ataskaita – $data")
            ->attachData($pdf, "aktyvus_bilietai_{$data}.pdf");
    });

        return response()->json(['success' => true]);
    }
}