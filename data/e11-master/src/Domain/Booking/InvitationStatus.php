<?php

namespace App\Domain\Booking;

abstract class InvitationStatus{

    public const ACCEPTED = 'accepted';

    public const REJECTED = 'rejected';

    public const PENDING = 'pending';

}