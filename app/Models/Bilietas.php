<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Bilietas extends Model
{
    //db lentele - bilietai
    protected $table = 'bilietai';
    //pildomi laukai
    protected $fillable = [
        'bilieto_id',
        'user_id',
        'pavadinimas',
        'prioritetas',
        'kategorija',
        'aprasymas',
        'uzregistruota',
        'uzdaryta',
    ];

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

}
