<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use Doctrine\Persistence\ObjectManager;

class SubscriptionFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $this->create(Subscription::class, function (Subscription $subscription) use ($manager) {
            $subscription->setName('Free')
                ->setPrice(0)
                ->setDescription(
                    '<ul class="fa-ul"><li><span class="fa-li"><i class="fas fa-check"></i></span>Возможность создать более 1 статьи</li><li><span class="fa-li"><i class="fas fa-check"></i></span>Базовые возможности генератора</li><li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Продвинутые возможности генератора</li><li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Свои модули</li></ul>'
                );
        });

        $this->create(Subscription::class, function (Subscription $subscription) use ($manager) {
            $subscription->setName('Plus')
                ->setPrice(9)
                ->setDescription(
                    '<ul class="fa-ul"><li><span class="fa-li"><i class="fas fa-check"></i></span>Возможность создать более 1 статьи</li><li><span class="fa-li"><i class="fas fa-check"></i></span>Базовые возможности генератора</li><li><span class="fa-li"><i class="fas fa-check"></i></span>Продвинутые возможности генератора</li><li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Свои модули</li></ul>'
                );
        });

        $this->create(Subscription::class, function (Subscription $subscription) use ($manager) {
            $subscription->setName('Pro')
                ->setPrice(49)
                ->setDescription(
                    '<ul class="fa-ul"><li><span class="fa-li"><i class="fas fa-check"></i></span><strong>Безлимитная генерация статей для вашего аккаунта</strong></li><li><span class="fa-li"><i class="fas fa-check"></i></span>Базовые возможности генератора</li><li><span class="fa-li"><i class="fas fa-check"></i></span>Продвинутые возможности генератора</li><li><span class="fa-li"><i class="fas fa-check"></i></span>Свои модули</li></ul>'
                );
        });

        $manager->flush();
    }
}
