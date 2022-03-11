<?php

namespace App\Form;

use App\Domain\Auth\Entity\User;
use App\Domain\Booking\Entity\Room;
use App\Domain\Booking\BookingFormDTO;
use App\Domain\Booking\Entity\Establishment;
use Symfony\Component\Form\AbstractType;
use App\Domain\Booking\Entity\Reservation;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BookingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date de la réservation',
                'data' => new DateTime()
            ])
            ->add('timeStart', TimeType::class, [
                'hours' => $options['openedHours'], 
                'minutes' => range(0, 59, 5), 
                'label' => 'Heure de début'
            ])
            ->add('timeEnd', TimeType::class, [
                'hours' => $options['openedHours'],
                'minutes' => range(0, 59, 5),
                'label' => 'Heure de fin'
            ])
            ->add('room', EntityType::class, [
                'label' => 'Salle',
                'required' => true,
                'class' => Room::class,
                'query_builder' => function(EntityRepository $er) use($options)
                {
                    // Make sure we only display bookable rooms, in the correct establishment.
                    return $er->createQueryBuilder('r')
                        ->where('r.isBookable = TRUE')
                        ->andWhere('r.establishment = :est')
                        ->setParameter('est', $options['establishment'])
                        ->orderBy('r.name', 'ASC')
                    ;
                }
            ])
            ->add('participants', EntityType::class, [
                'label' => 'Participants (optionnel)',
                'required' => false,
                'class' => User::class,
                'multiple' => true,
                'query_builder' => function(EntityRepository $er) use($options)
                {
                    // Make sure we do not display user not in the current estblishment.
                    // Also make sure we do not add the current User to participant list. It would not make any sense.
                    return $er->createQueryBuilder('u')
                        ->where('u.establishment = :establishment')
                        ->setParameter('establishment', $options['establishment'])
                        ->andWhere('u.id != :currentUser')
                        ->setParameter('currentUser', $options['currentUser'])
                        ->orderBy('u.name', 'ASC')
                    ;
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Réserver'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookingFormDTO::class,
            'openedHours' => null,
            'establishment' => null,
            'currentUser' => null
        ]);

        $resolver->setAllowedTypes('openedHours', 'array');
        $resolver->setAllowedTypes('establishment', Establishment::class);
        $resolver->setAllowedTypes('currentUser', User::class);
    }
}
