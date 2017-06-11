@extends('layouts.profile')
@section('title', 'View Recruiter Profile')

@section('content')
    <h1><?php echo $profile[0]->first_name.' '.$profile[0]->last_name ?></h1>
    <div>
        <div>Email</div>
    </div>
    <div>
        <div><?php echo $profile[0]->email ?></div>
    </div>

    <div>
        <div>Phone</div>
    </div>
    <div>
        <div><?php echo $profile[0]->phone ?></div>
    </div>

    <div>
        <div>Company</div>
    </div>
    <div>
        <div><?php echo $profile[0]->company ?></div>
    </div>

    <div>
        <div>Street Address</div>
    </div>
    <div>
        <div><?php echo $profile[0]->street_address1 ?></div>
    </div>

    <div>
        <div>Street Address 2</div>
    </div>
    <div>
        <div><?php echo $profile[0]->street_address2 ?></div>
    </div>

    <div>
        <div>City</div>
    </div>
    <div>
        <div><?php echo $profile[0]->city ?></div>
    </div>

    <div>
        <div>State</div>
    </div>
    <div>
        <div><?php echo $profile[0]->state ?></div>
    </div>

    <div>
        <div>Zip Code</div>
    </div>
    <div>
        <div><?php echo $profile[0]->zip ?></div>
    </div>	
@endsection
