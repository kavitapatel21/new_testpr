<?php
/**
 * Widgets class.
 *
 * @category   Class
 * @package    TestimonialsCarouselElementor
 * @subpackage WordPress
 * @author
 * @copyright
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link
 * @since      7.1.0
 * php version 7.4.1
 */

namespace TestimonialsCarouselElementor;

// Security Note: Blocks direct access to the plugin PHP files.
use Elementor\Plugin;

defined('ABSPATH') || die();

/**
 * Class Plugin
 *
 * Main Plugin class
 *
 * @since 7.1.0
 */
class Widgets
{

  /**
   * Instance
   *
   * @since 7.1.0
   * @access private
   * @static
   *
   * @var Plugin The single instance of the class.
   */
  private static $instance = null;

  /**
   * Instance
   *
   * Ensures only one instance of the class is loaded or can be loaded.
   *
   * @return Plugin An instance of the class.
   * @since 7.1.0
   * @access public
   *
   */
  public static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * Include Widgets files
   *
   * Load widgets files
   *
   * @since 7.1.0
   * @access private
   */
  private function include_widgets_files()
  {
    require_once 'widgets/class-testimonialscarousel.php';
    require_once 'widgets/class-testimonialscarousel-logo.php';
    require_once 'widgets/class-testimonialscarousel-centered.php';
    require_once 'widgets/class-testimonialscarousel-bottom.php';
    require_once 'widgets/class-testimonialscarousel-coverflow.php';
    require_once 'widgets/class-testimonialscarousel-gallery-coverflow.php';
    require_once 'widgets/class-testimonialscarousel-employees.php';
  }

  /**
   * Register Widgets
   *
   * Register new Elementor widgets.
   *
   * @since 7.1.0
   * @access public
   */
  public function register_widgets()
  {
    // It's now safe to include Widgets files.
    $this->include_widgets_files();

    // Register the plugin widget classes.
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\TestimonialsCarousel());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\TestimonialsCarousel_Logo());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\TestimonialsCarousel_Centered());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\TestimonialsCarousel_Bottom());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\TestimonialsCarousel_Coverflow());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\TestimonialsCarousel_Gallery_Coverflow());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\TestimonialsCarousel_Employees());
  }


  /**
   *  Plugin class constructor
   *
   * Register plugin action hooks and filters
   *
   * @since 7.1.0
   * @access public
   */
  public function __construct()
  {
    // Register the widgets.
    add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'));
  }
}

// Instantiate the Widgets class.
Widgets::instance();
