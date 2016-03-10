<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:22
 *
 * @var WizardEvent $event
 */


$this->renderPartial($checkoutModel->scenario, compact('checkoutModel', 'profileModels', 'userProfiles', 'saved', 'trip', 'points', 'selPoints', 'places', 'prices', 'back')); ?>
