<?php

namespace Drupal\distribution_message\EventSubscriber;

use Drupal\distribution\Entity\Commission;
use Drupal\distribution\Event\CommissionEvent;
use Drupal\message\Entity\Message;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DistributionCommission.
 */
class DistributionCommission implements EventSubscriberInterface
{


    /**
     * Constructs a new DistributionCommission object.
     */
    public function __construct()
    {

    }

    /**
     * {@inheritdoc}
     */
    static function getSubscribedEvents()
    {
        $events['distribution.commission.promotion'] = ['distribution_commission_promotion'];
        $events['distribution.commission.chain'] = ['distribution_commission_chain'];
        $events['distribution.commission.leader'] = ['distribution_commission_leader'];

        return $events;
    }

    /**
     * This method is called whenever the distribution.commission.promotion event is
     * dispatched.
     *
     * @param CommissionEvent $event
     */
    public function distribution_commission_promotion(CommissionEvent $event)
    {
        $this->createMessage($event->getCommission());
    }

    /**
     * This method is called whenever the distribution.commission.chain event is
     * dispatched.
     *
     * @param CommissionEvent $event
     */
    public function distribution_commission_chain(CommissionEvent $event)
    {
        $this->createMessage($event->getCommission());
    }

    /**
     * This method is called whenever the distribution.commission.leader event is
     * dispatched.
     *
     * @param CommissionEvent $event
     */
    public function distribution_commission_leader(CommissionEvent $event)
    {
        $this->createMessage($event->getCommission());
    }

    private function createMessage(Commission $commission)
    {
        $message = Message::create(['template' => 'distribution_commission']);

        $message->setArguments([
            '@amount' => $commission->getAmount()->getNumber()
        ]);

        $message->setOwnerId($commission->getDistributor()->getOwnerId());
        $message->save();
    }
}
