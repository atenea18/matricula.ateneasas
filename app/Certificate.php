<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
    	'institution_id', 'header', 'rector_firm', 'rector_number_document', 'rector_place_expedition', 'secretary_firm', 'secretary_number_document', 'secretary_place_expedition', 'place_expedition_document'
    ];
}
