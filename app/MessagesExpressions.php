<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessagesExpressions extends Model
{
    protected $table = 'messages_expressions';

    protected $fillable = [
    	'name', 'reinforcement', 'recommendation', 'institution_id'
    ];

    public function performances()
    {
    	return $this->hasMany(Performances::class, 'messages_expressions_id');
    }
}
