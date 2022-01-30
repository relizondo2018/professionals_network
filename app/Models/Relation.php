<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    use HasFactory;
	

	public function relatedUser() {
		return $this->belongsTo('App\Models\User', 'related_id', 'id');
	}
}
