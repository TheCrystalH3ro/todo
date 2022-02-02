<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model {
    use HasFactory;

    public function members() {
        return $this->belongsToMany(User::class, 'task_user');
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'task_category');
    }

    public function removeRelation($relation) {

        foreach($relation as $item) {

            $item->delete();

        }

        $this->save();

    }

}
