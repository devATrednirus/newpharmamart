<?php

namespace App\Models;

use App\Models\Scopes\LocalizedScope;
use App\Models\Scopes\StrictActiveScope;
use App\Observer\PaymentObserver;
use Larapen\Admin\app\Models\Crud;
use Jenssegers\Date\Date;

class Enquiry extends BaseModel
{
	use Crud;
    //
}
