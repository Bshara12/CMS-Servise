<?php

namespace App\Domains\CMS\Read\Repositories\Field;

use App\Models\DataType;
use App\Models\DataTypeField;

class FieldRepositoryRead
{
  public function list(DataType $dataType)
  {
    return DataTypeField::where('data_type_id', $dataType->id)->get();
  }

  public function indexTrashed(DataType $dataType)
  {
    return DataTypeField::onlyTrashed()->where('data_type_id', $dataType->id)->get();
  }
}