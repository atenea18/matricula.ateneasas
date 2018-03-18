<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotesParameters extends Model
{
    public function evaluationParameters()
    {
        return $this->belongsTo(EvaluationParameter::class);
    }
}
