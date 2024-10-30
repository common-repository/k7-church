<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/pages
 */

defined('ABSPATH') || exit;


final class Church_Init
{


    /**
     * Loop through the classes, initialize them,
     * and call the register() method if it exists
     * @return
     */
    public static function ch_registerServices()
    {

        foreach (self::ch_getServices() as $class) {
            $service = self::ch_instantiate($class);
            if (method_exists($service, 'ch_register')) {
                $service->ch_register();
            }
        }
    }

    /**
     * Store all the classes inside an array
     * @return array Full list of classes
     */
    public static function ch_getServices()
    {
        return [
            Church_Dashboard::class,
            Church_Enqueue::class,
            Church_SettingsLinks::class,
            Church_CustomPostTypeController::class,
            Church_CustomTaxonomyController::class,
            Church_WidgetController::class,
            Church_GalleryController::class,
            Church_TestimonialController::class,
            Church_TemplateController::class,
            Church_AuthController::class,
            Church_MembershipController::class,
            Church_ChatController::class,
            Church_LocationController::class,
            Church_SermonController::class,
            Church_RegisterController::class,
            Church_LocationWidget::class,
            Church_NotificationController::class,
            Church_EventController::class,
            Church_ParticipantController::class,
        ];
    }

    /**
     * Initialize the class
     * @param  class $class class from the services array
     * @return class instance  new instance of the class
     */
    private static function ch_instantiate($class)
    {
        $service = new $class();

        return $service;
    }
}
