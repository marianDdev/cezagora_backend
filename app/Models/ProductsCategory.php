<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsCategory extends Model
{
    use HasFactory;

    const CATEGORIES = [
        'skin_cream',
        'skin_emulsion',
        'skin_lotion',
        'skin_gel',
        'skin_oil',
        'face_mask',
        'tinted_base',
        'powder',
        'after-bath',
        'soap',
        'perfume',
        'toilet_water',
        'eau_de_cologne',
        'bath_salt',
        'bath_foam',
        'bath_oil',
        'bath_gel',
        'shower_foam',
        'shower_oil',
        'shower_gel',
        'depilatory',
        'deodorant',
        'antiperspirant',
        'hair_colorant',
        'hair_lotion',
        'hair_powder',
        'shampoo',
        'hair-conditioner',
        'shaving_cream',
        'shaving_foam',
        'shaving_lotion',
        'make-up',
        'make-up_removal',
        'for_lips',
        'for_teeth',
        'for_nails',
        'for_intimate_hygiene',
        'sunbathing',
        'tanning',
        'skin_whitening',
        'anti_wrinkle',
    ];
}
