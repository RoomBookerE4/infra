<?php

namespace App\Domain\Booking\Voter;

use App\Domain\Auth\Entity\User;
use App\Domain\Booking\BookingService;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class BookingVoter extends Voter
{

    public function __construct(
        private BookingService $bookingService,
        private Security $security
    )
    {
        
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['CAN_EDIT', 'CAN_CANCEL', 'CAN_VIEW'])
            && $subject instanceof \App\Domain\Booking\Entity\Booking;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        /** @var \App\Domain\Booking\Entity\Booking $booking */
        $booking = $subject; // We just want to use a more meaningful variable.

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Basically, an ADMIN or a TEACHER can do whatever they want in our use case.
        if($this->security->isGranted('ROLE_MANAGEMENT'))
        {
            // Just give them access.
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'CAN_EDIT':
                return $this->bookingService->isOrganizer($booking, $user);
                break;
            case 'CAN_CANCEL':
                return $this->bookingService->isOrganizer($booking, $user);
                break;
            case 'CAN_VIEW':
                // Check wether the current user is a participant in the current meeting.
                return $this->bookingService->filterParticipantWithUser($booking, $user) != null;
                break;
        }

        return false;
    }
}
