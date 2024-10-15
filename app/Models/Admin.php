<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
   protected $table = 'admins';
   
   public function transactions()
    {
        return $this->hasMany(TransactionHistory::class, 'user_id'); // Assuming 'user_id' is the foreign key
    }
}
