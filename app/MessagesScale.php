<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessagesScale extends Model
{
    protected $table = 'messages_scale';

    protected $fillable = [
    	'code', 'name', 'recommendation', 'scale_evaluations_id', 'messages_expressions_id'
    ];

    public function messageExpression()
    {
    	return $this->hasMany(MessagesExpressions::class, 'messages_expressions_id');
    }
}
